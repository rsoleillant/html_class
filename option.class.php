<?php
/*******************************************************************************
Create Date : 22/05/2006
 ----------------------------------------------------------------------
 Class name : option
 Version : 1.1
 Author : Rémy Soleillant
 Description : élément html <option> 
********************************************************************************/
class option  {
   
   /*attribute***********************************************/
   
   protected $stri_value="";
   protected $bool_disabled=false;  
   protected $bool_selected=false;
   protected $stri_label="";
   protected $stri_style="";
   protected $stri_class="";
   protected $stri_title="";
   protected $stri_onclick="";   //=> les actions js sur le clic de l'option
   protected $stri_onmouseover="";
   protected $stri_data_image;   //Avec plusgin, permet d'afficher une image dans le select 
       protected $arra_data;              //attribut data 
   public $arra_sauv=array();       //tableau pour la sérialisation

   
  
   
  
  /* constructor***************************************************************/
   function __construct($value,$label) {
       $this->stri_value=$value;
       $this->stri_label=htmlentities($label,ENT_COMPAT|ENT_HTML401, 'ISO-8859-1');
       //$this->stri_label=$label;
   }
  
   /*setter*********************************************************************/
   public function setOnclick($value){$this->stri_onclick=$value;}
   public function setOnmouseover($value){$this->stri_onmouseover=$value;}
  public function setSelected($bool)
  {
    if(is_bool($bool))
    {
      $this->bool_selected=$bool;
    }
    else
    {
      echo("<script>alert('bool_selected doit etre de type boolean');</script>");
    }
  }
  
  public function setLablel($value)
  {
    $this->stri_label=$value;
  }
  public function setClass($value)
  {
    $this->stri_class=$value;
  }
  
  public function setValue($value)
  {
    $this->stri_value=$value;
  }
  
 
  public function setDisabled($bool)
  {
    if(is_bool($bool))
    {
      $this->bool_disabled=$bool;
    }
    else
    {
      echo("<script>alert('bool_disabled doit etre de type boolean');</script>");
    }
  }
  
  public function setStyle($value)
  {
    $this->stri_style=$value;
  }  
  
  public function setTitle($value){$this->stri_title=$value;}
  public function setDataImage($value){$this->stri_data_image=$value;}

  
  
  /*getter**********************************************************************/
  public function getSelected()
  {return $this->bool_selected;}
  
  public function getStyle()
  {return $this->stri_style;}
  
  public function getValue()
  {return $this->stri_value;}
  
  public function getClass()
  {return $this->stri_class;}
  
  public function getDisabled()
  {return $this->bool_disabled;}
   
  public function getLabel()
  {return $this->stri_label;}
  
  public function getTitle(){return $this->stri_title;}
  public function getOnclick($value){return $this->stri_onclick;}
  public function getDataImage(){return $this->stri_data_image;}
   public function getOnmouseover($value){return $this->stri_onmouseover;}
   public function addData($stri_name,$value){$this->arra_data[$stri_name]=$value;}
  /* method for serialization **************************************************/
  public function __sleep() 
  {
    //sérialisation de la classe option
    $this->arra_sauv['stri_value']  = $this->stri_value;
    $this->arra_sauv['bool_disabled']  = $this->bool_disabled;
    $this->arra_sauv['bool_selected']  = $this->bool_selected;
    $this->arra_sauv['stri_label']  = $this->stri_label;
    $this->arra_sauv['stri_style']  = $this->stri_style;
    $this->arra_sauv['onclick']= $this->stri_onclick;  
    return array('arra_sauv');
  }
   
  public function __wakeup() 
  {
    //désérialisation de la classe option
    $this->stri_value  = $this->arra_sauv['stri_value'];
    $this->bool_selected  = $this->arra_sauv['bool_selected'];
    $this->bool_disabled  = $this->arra_sauv['bool_disabled'];
    $this->stri_label  = $this->arra_sauv['stri_label'];
    $this->stri_style  = $this->arra_sauv['stri_style'];
    $this->stri_onclick= $this->arra_sauv['onclick'];
    $this->arra_sauv = array(); 
  }
  
  /*other method****************************************************************/
  public function htmlValue()
  {
      $arra_data=array();
    foreach($this->arra_data as $stri_name=>$stri_value)
    {
      $arra_data[]='data-'.$stri_name.'="'.$stri_value.'"';
    }
    $stri_data=implode(' ', $arra_data) ;
    
  $stri_res="<option".
            " value='".$this->stri_value."' ";
          
  if($this->bool_disabled)
  {$stri_res=$stri_res."disabled ";}
  
  if($this->bool_selected)
  {$stri_res=$stri_res."selected ";}  
  
  if($this->stri_style!="")
  {$stri_res=$stri_res.'style= "'.$this->stri_style.'"';}
  
  if($this->stri_class!="")
  {$stri_res=$stri_res.'class= "'.$this->stri_class.'"';}
  
  if($this->stri_title!="")
  {$stri_res=$stri_res.'title= "'.$this->stri_title.'"';}
  
  if($this->stri_data_image!="")
  {$stri_res=$stri_res.'data-image= "'.$this->stri_data_image.'"';}
  
  $stri_res.=($this->stri_onclick!="")? " onclick=\"".$this->stri_onclick."\" " : "";
   $stri_res.=($this->stri_onmouseover!="")? " onmouseover=\"".$this->stri_onmouseover."\" " : ""; 
     $stri_res.=$stri_data;
  $stri_res=$stri_res." >".$this->stri_label." </option>";
  return $stri_res;
  }
  
  
}

?>
