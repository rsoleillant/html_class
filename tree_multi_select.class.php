<?php
/*******************************************************************************
Create Date : 05/06/2006
 ----------------------------------------------------------------------
 Class name : tree_multi_select
 Version : 1.0
 Author : Rémy Soleillant
 Description : gère plusieurs objet select liée entre eux en s'appuyant sur un objet tree
********************************************************************************/
  
include_once("table.class.php");
class tree_multi_select { 
   
   /*attribute***********************************************/
   protected $obj_tree;
   protected $arra_select;
   protected $stri_page_url;
   protected $stri_form_url;
   protected $stri_form_name;
   protected $stri_select_style="width:146px";
   public $arra_sauv=array();
  
   
   /* constructor***************************************************************/
   function __construct($form_name,$form_url,$stri_page_url,$obj_tree) {
       $this->stri_form_name=$form_name;
       $this->stri_form_url=$form_url;
       $this->stri_page_url=$stri_page_url;
       $this->obj_tree=$obj_tree;
       $this->createSelect();
      
       
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
    for($i=0;$i<count($this->arra_select);$i++)
   {$this->arra_select[$i]->setDisabled($bool);}
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
  {return $this->arra_select[$int];}
  
  public function getSelectStyle()
  {return $this->stri_select_style;}
    /* method for serialization **************************************************/
  public function __sleep() {
     $this->arra_sauv['page_url']= $this->stri_page_url;
     $this->arra_sauv['form_url']= $this->stri_form_url;
     $this->arra_sauv['form_name']= $this->stri_form_name;
     $this->arra_sauv['select_style']= $this->stri_select_style;
     $this->arra_sauv['tree']= serialize($this->obj_tree);
     $nbr=count($this->arra_select);
     for($i=0;$i<$nbr;$i++)
     {
     $arra_temp[$i]=serialize($this->arra_select[$i]);        
     }
     $this->arra_sauv['arra_select']=$arra_temp;
     return array('arra_sauv');
   }
  public function __wakeup() 
    {
     $this->stri_page_url= $this->arra_sauv['page_url'];
     $this->stri_form_url= $this->arra_sauv['form_url'];
     $this->stri_form_name= $this->arra_sauv['form_name'];
     $this->stri_select_style= $this->arra_sauv['select_style'];
     $this->obj_tree= unserialize($this->arra_sauv['tree']);
     $arra_temp=$this->arra_sauv['arra_select'];
     $nbr_object=count($arra_temp);
     for($i=0;$i<$nbr_object;$i++)
     {
     $this->arra_select[$i]= unserialize($arra_temp[$i]);
     }
     $this->arra_sauv = array();
     
    }
 
  
  /*other method****************************************************************/ 
  //permet de créer les objets select
  private function createSelect()
  {
    $int_nbr_list=$this->obj_tree->getMaxHerarchyLevel();
    $index_id=$this->stri_form_name."_id_selected";
    if(!empty($_POST[$index_id]))
    {$id_selected=$_POST[$index_id];}
    
    
    $arra_res=$this->obj_tree->getAllInfoById($id_selected);
    $hierarchy_level=$arra_res['hierarchy_level'];
    $id_current_parent=$arra_res['id_parent'];
    $id_current=$id_selected;
    
    //creation de toute les listes déroulante parent de la liste choisie 
    for($i=$hierarchy_level;$i>0;$i--)
    { 
      $arra_element=$this->obj_tree->findAllSoon($id_current_parent);
      $select_name=$this->stri_form_name."_level_".$i;
      $obj_select=new select($select_name);
      $obj_select->addOption("","__");
      //l'url de la page est changé automatiquement pour retomber sur la page actuelle
      $stri_onchange="document.".$this->stri_form_name.".action='".$this->stri_page_url."'; ";
      $stri_onchange.="document.".$this->stri_form_name.".$index_id.value=document."
                               .$this->stri_form_name.".".$select_name.".value;";
      $stri_onchange.="document.".$this->stri_form_name.".submit();";
    
      //on met les options dans l'objet select
      foreach($arra_element as $soon)
      {$obj_select->addOption($soon['my_id'],$soon['label']);}
      $obj_select->setStyle($this->stri_select_style);
      $obj_select->setOnchange($stri_onchange);
      $obj_select->selectOption($id_current);
      $id_current=$id_current_parent;
      $id_current_parent=$this->obj_tree->getParentBySoonId($id_current_parent);
      $this->arra_select[$i]=$obj_select;
      
    }
    //creation de la liste déroulante juste après celle selectionnée
    $current_id=$arra_res['my_id'];
    $select_name=$this->stri_form_name."_level_".($hierarchy_level+1);
    $obj_next_select=new select($select_name);
    $obj_next_select->addOption("","__");
    $stri_onchange="document.".$this->stri_form_name.".action='".$this->stri_page_url."'; ";
    $stri_onchange.="document.".$this->stri_form_name.".$index_id.value=document."
                               .$this->stri_form_name.".".$select_name.".value;";
    $stri_onchange.="document.".$this->stri_form_name.".submit();";
    $obj_next_select->setOnchange($stri_onchange);
    $obj_next_select->setStyle($this->stri_select_style);
    $arra_element=$this->obj_tree->findAllSoon($arra_res['my_id']);
    foreach($arra_element as $element)
    {$obj_next_select->addOption($element['my_id'],$element['label']);}
    //initialisation des autres listes déroulante dans le cas ou la liste actuellement traité n'as qu'une seule option
   if($obj_next_select->getNumberOption()==2)      
      {
        $_POST[$index_id]=$obj_next_select->getIemeOption(1)->getValue();
        $my_id=$obj_next_select->getIemeOption(1)->getValue();
        $int_option_added=2;
        $obj_next_select->selectOption($my_id);
        while(($int_option_added==2)&&($hierarchy_level<$int_nbr_list-1))
        { 
          $int_option_added=$this->initialiseNextSelect($my_id); 
          $hierarchy_level++;
          $arra_soon=$this->obj_tree->findAllSoon($my_id);
          $my_id=$arra_soon[0]['my_id'];
        }
      }
    $this->arra_select[$arra_res['hierarchy_level']+1]=$obj_next_select;
    //creation des toutes les autres listes déroulante qui ne peuvent pas être initialisées
    for($i=$hierarchy_level+2;$i<=$int_nbr_list;$i++)
    {
      $select_name=$this->stri_form_name."_level_".$i;
      $obj_last_select=new select($select_name);
      $obj_last_select->addOption("","__");
      $obj_last_select->setStyle($this->stri_select_style);
      $this->arra_select[$i]=$obj_last_select;
    }   
   
    
    //$num_end_select=count($this->arra_select);
    $num_end_select=$this->obj_tree->getMaxHerarchyLevel();
   // echo "le select qui bouge pas est $num_end2<br>";
    $this->arra_select[$num_end_select]->setOnchange("");
  } 
  
  //permet d'initialisé la liste déroulante suivante en fonction d'un élément de la liste précédente
  //cet élément est l'identifiant d'un noeud de l'arbre
  private function initialiseNextSelect($id)
  {
   $arra_res=$this->obj_tree->getAllInfoById($id);
   $my_hierarchy_level=$arra_res['hierarchy_level'];
   $arra_soon=$this->obj_tree->findAllSoon($id);
   $select_name=$this->stri_form_name."_level_".($arra_res['hierarchy_level']+1);
   $obj_select=new select($select_name);
   $obj_select->addOption("","__");
   $index_id=$this->stri_form_name."_id_selected";
   foreach($arra_soon as $soon)
   { 
     $soon_id=$soon['my_id'];
     $obj_select->addOption($soon['my_id'],$soon['label']);}
     $stri_onchange="document.".$this->stri_form_name.".action='".$this->stri_page_url."'; ";
     $stri_onchange.="document.".$this->stri_form_name.".$index_id.value=document."
                                 .$this->stri_form_name.".".$select_name.".value;";
     $stri_onchange.="document.".$this->stri_form_name.".submit();";
     $obj_select->setOnchange($stri_onchange);
     $obj_select->setStyle($this->stri_select_style);  
     $nbr_option=$obj_select->getNumberOption();
     if($nbr_option==2)
     {$obj_select->selectOption($soon_id);}
     $this->arra_select[$my_hierarchy_level+1]=$obj_select;
     
     
     return $nbr_option;
  
  }
  
  //affiche le multi select
  public function htmlValue($arra_label="",$obj_table="")
  {
   if(!empty($obj_table))
   {return $obj_table->htmlValue();}
   $index_id=$this->stri_form_name."_id_selected";
   $obj_hidden=new hidden($index_id,"init");
   
   $obj_table=new table();
   $obj_tr1=new tr();
   $i=0;
   $nbr_list=count($this->arra_select);
   for($i=1;$i<=$nbr_list;$i++)
   {
    $select=$this->arra_select[$i];
    $font=new font($arra_label[$i-1]);
    $obj_tr1->addTd($font->htmlValue());   
    $obj_tr1->addTd($select->htmlValue());
   }
  
   $obj_table->insertTr($obj_tr1);
   $obj_table->setBorder(0);
   return $obj_table->htmlValue().$obj_hidden->htmlValue();
  }
  public function selectId($id)
  {
    $nbr_before=count($this->arra_select);
    $index_id=$this->stri_form_name."_id_selected";
    if(!empty($_POST[$index_id]))
    {return "";}
    $_POST[$index_id]=$id;
    $this->createSelect();
    $nbr=count($this->arra_select);
    if($nbr_before!=$nbr)
    {unset($this->arra_select[$nbr]);}

  }
}

?>
