<?php
/*******************************************************************************
Create Date : 05/06/2006
 -------------------------------------------------------------------------------
 Class name : multi_select
 Version : 1.2
 Author : Rémy Soleillant
 Description : gère plusieurs objet select liée entre eux
********************************************************************************/
  
include_once("table.class.php");
class multi_select {
   
   /*attribute***********************************************/
   protected $arra_object=array();
   protected $stri_page_url;
   protected $stri_form_url;
   protected $stri_form_name;
   protected $stri_select_style="width:100%";
   protected $int_select_width=146;
   public $arra_sauv=array();
  
   
   /* constructor***************************************************************/
   function __construct($form_name,$form_url,$stri_page_url) {
       $this->stri_form_name=$form_name;
       $this->stri_form_url=$form_url;
       $this->stri_page_url=$stri_page_url;
   }
   

   
   /*setter*********************************************************************/
  public function setSelectStyle($value)
  {$this->stri_select_style=$value;}
  
  public function setFormName($name)
  {$this->stri_form_name=$name;}
  
  public function setFormUrl($url)
  {$this->stri_form_url=$url;}
  
  public function setPageUrl($url)
  {$this->stri_page_url=$url;}
  
  public function setDisabled($bool)
  {
    for($i=0;$i<count($this->arra_object);$i++)
   {$this->arra_object[$i]['select']->setDisabled($bool);}
  }
    function setInt_select_width($int_select_width) {
      $this->int_select_width = $int_select_width;
  }
  /*getter**********************************************************************/
  public function getFormName()
  {return $this->stri_form_name;}
  
  public function getFormUrl()
  {return $this->stri_form_url;}
  
  public function getPageUrl()
  {return $this->stri_page_url;}
  
  public function getObject()
  {return $this->arra_object;}
  
  public function getIemeSelect($int)
  {
  return $this->arra_object[$int]['select'];}
  
  public function getIemeQuerry($int)
  {return $this->arra_object[$int]['querry'];}
  
  public function getSelectStyle()
  {return $this->stri_select_style;}
    /* method for serialization **************************************************/
  public function __sleep() {
     $this->arra_sauv['form_url']  = $this->stri_form_url;
     $this->arra_sauv['form_name']  = $this->stri_form_name;
     $this->arra_sauv['page_url']  = $this->stri_page_url;
     $this->arra_sauv['select_style']  = $this->stri_select_style;
     $nbr=count($this->arra_object);
     for($i=0;$i<$nbr;$i++)
     {
     $arra_temp[$i]['font']=serialize($this->arra_object[$i]['font']);
     $arra_temp[$i]['select']=serialize($this->arra_object[$i]['select']);
     $arra_temp[$i]['querry']=serialize($this->arra_object[$i]['querry']);
       
     }
    
     $this->arra_sauv['arra_object']=$arra_temp;
     return array('arra_sauv');
   }
  public function __wakeup() 
    {
     $this->stri_form_url= $this->arra_sauv['form_url'];
     $this->stri_form_name= $this->arra_sauv['form_name'];
     $this->stri_page_url= $this->arra_sauv['page_url'];
     $this->stri_select_style= $this->arra_sauv['select_style'];   
     $arra_temp=$this->arra_sauv['arra_object'];
     $nbr_object=count($arra_temp);
     for($i=0;$i<$nbr_object;$i++)
     {
     $this->arra_object[$i]['font']= unserialize($arra_temp[$i]['font']);
     $this->arra_object[$i]['select']= unserialize($arra_temp[$i]['select']);
     $this->arra_object[$i]['querry']= unserialize($arra_temp[$i]['querry']);
     }
     $this->arra_sauv = array();
     
    }
 
  
  /*other method****************************************************************/
  public function addObject($obj_select,$obj_querry,$obj_font)
  {
    $int_i=count($this->arra_object);
    $this->arra_object[$int_i]['select']=$obj_select;
    $this->arra_object[$int_i]['querry']=$obj_querry;
    $this->arra_object[$int_i]['font']=$obj_font;    
  }
  
 
  private function putOptionInIemeSelect($int)
  {
   $querry=$this->arra_object[$int]['querry'];
  for($i=0;$i<$int;$i++)
   {
    $select_name=$this->arra_object[$i]['select']->getName();
    $querry->setIemeData($i,$_POST[$select_name]);
   }
   $querry->generateSql();
   //querry must have only one field in 'select field from...'
   $select=$this->arra_object[$int]['select'];
   $select->makeQuerryToSelect($querry,$querry->getNumFieldToUseValue(),$querry->getNumFieldToShow()); 
   $nbr_result=$querry->getNumberResult();
  
   if($nbr_result==1)
   {
    $unique_result=$querry->getUniqueResult();
   
    $select->selectOption($unique_result);
    
    $bool_not_last_select=($i!=count($this->arra_object)-1);
    $bool_select_void_option=($_POST["select_clicked"]==$int)&&($_POST[$next_name]=="");
    
    //if($i!=count($this->arra_object)-1)
    if(($bool_not_last_select)&&(!$bool_select_void_option))
    {
     $next_name=$this->arra_object[$i]['select']->getName(); 
     $_POST[$next_name]=$unique_result;
    }
    
    
   }
   
  }
  
  private function initialiseSelect()
  {
  //initialisation du premier select 
       $select_init=$this->arra_object[0]['select'];
       $querry_init=$this->arra_object[0]['querry'];
       $select_init->makeQuerryToSelect($querry_init,0,0);
       $select_init->selectOption($_POST[$select_init->getName()]);
       $stri_change=$select_init->getOnchange();
     $stri_change.="document.".$this->stri_form_name.".action='".$this->stri_page_url."'";
       $stri_change=$stri_change."; document.".$this->stri_form_name.".select_clicked.value=0";
       $stri_change=$stri_change."; document.".$this->stri_form_name.".submit();";

       
       //$stri_change="if(this.value!=''){".$stri_change."}";                                             //jb
      
      
      // $stri_change.="; test_for_submit(this)";
      $select_init->setOnchange($stri_change);   
   if(!empty($_POST))
   {    
    //initialisation des autres select   
       for($i=1;$i<count($this->arra_object);$i++)
       {       
        $this->putOptionInIemeSelect($i);
        $select=$this->arra_object[$i]['select'];
        
        $stri_select_name=$select->getName();
        $stri_change=$select->getOnchange();
        $stri_change_url="document.".$this->stri_form_name.".action='".$this->stri_page_url."';";
        $stri_change_click="document.".$this->stri_form_name.".select_clicked.value=$i;";
        $stri_change_submit="document.".$this->stri_form_name.".submit();";     
        $stri_change=$stri_change_click."if(this.value!=''){".$stri_change_url.$stri_change_submit."}";
        //$stri_change=$stri_change."; document.".$this->stri_form_name.".submit()";
        //$stri_change="if(this.value!=''){".$stri_change."}";
        $select->setOnchange($stri_change); 
        $select->selectOption($_POST[$stri_select_name]);
       }
        $stri_change="document.".$this->stri_form_name.".action='".$this->stri_form_url."'";
        $stri_change="; document.".$this->stri_form_name.".select_clicked.value=$i";
        $select->setOnchange($stri_change);  
    }
  }
  
  public function htmlValue()
  {
  //on initialise tout les champs 
   $this->initialiseSelect();
   $obj_hidden=new hidden("select_clicked",0);
   $html_table=new table();
   $tr1=new tr();
   if($this->arra_object[0]['select']->getStyle()=="")//si le select n'a pas de style, on lui met celui du multiselect
   {$this->arra_object[0]['select']->setStyle($this->stri_select_style); }
   
   
   $tr1->addTd($this->arra_object[0]['font']->htmlValue());
   $temp_td=$tr1->addTd($this->arra_object[0]['select']->htmlValue().$obj_hidden->htmlValue());
   $temp_td->setWidth($this->int_select_width);
   $int_nb_select=count($this->arra_object);
   for($i=1;$i<$int_nb_select;$i++)
   {
     $obj_select=$this->arra_object[$i]['select'];
      
     if($obj_select->getStyle()=="")//si le select n'a pas de style, on lui met celui du multiselect
     {$obj_select->setStyle($this->stri_select_style);
     
     } 
     $tr1->addTd($this->arra_object[$i]['font']->htmlValue());
     $temp_td=$tr1->addTd($obj_select->htmlValue());
     $temp_td->setWidth($this->int_select_width);
   }
   $html_table->insertTr($tr1);
   $html_table->setBorder(0);
   return $html_table->htmlValue();
  }
  
 
  
}

?>
