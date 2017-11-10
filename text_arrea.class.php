<?php
/*******************************************************************************
Create Date : 22/05/2006
 ----------------------------------------------------------------------
 Class name : text_arrea
 Version : 1.0
 Author : Rémy Soleillant
 Description : élément html <textarea>
********************************************************************************/
class text_arrea {
    
   /*attribute***********************************************/
   protected $stri_name="";
   protected $int_rows=2;
   protected $stri_value="";
   protected $bool_disabled=false;
   protected $bool_readonly=false;
   protected $int_cols=20;
   protected $stri_onfocus="";
   protected $stri_onblur="";
   protected $stri_onselect="";
   protected $stri_onchange="";
   protected $stri_ondblclick="";
   protected $stri_onclick="";   
   protected $stri_onmouseover="";
   protected $stri_onmouseout="";
   protected $stri_onkeypress="";
   protected $stri_onkeydown="";
   protected $stri_onkeyup="";
   protected $int_tabindex="";
   protected $stri_data_type="string";
   protected $bool_can_be_empty=false;
   protected $stri_style;
   protected $stri_wrap;
   protected $stri_class="";
   protected $stri_id="";
   protected $stri_required="";
   protected $stri_placeholder="";
   
   protected $bool_auto_save; //pour activer la sauvegarde automatique en locale  
 
   public $arra_sauv=array();
  
  /* constructor***************************************************************/
   function __construct($name,$value) {
       $this->stri_name=$name;
       $this->stri_value=$value;
       
       $this->bool_auto_save=false;//par défaut, pas d'activation de sauvarde automatique
   }
   
   
  
   /*setter*********************************************************************/
 
  public function setName($stri_value)
  {
    $this->stri_name=$stri_value;
  }
  
  public function setRequired($stri_value)
  {
    $this->stri_required=$stri_value;
  }
  public function setPlaceholder($stri_value)
  {
    $this->stri_placeholder=$stri_value;
  }
  
  public function setReadonly($bool)
  {
    if(is_bool($bool))
    {
      $this->bool_readonly=$bool;
    }
    else
    {
      echo("<script>alert('bool_readonly doit etre de type boolean');</script>");
    }
  }
  
   public function setDataType($value)
  {
    $this->stri_data_type=$value;
  }
  
  public function setCanBeEmpty($bool)
  { if(is_bool($bool))
    {
      $this->bool_can_be_empty=$bool;
    }
    else
    {
      echo("<script>alert('bool_disabled doit etre de type boolean');</script>");
    }
  } 
  public function setValue($value)
  {
    $this->stri_value=$value;
  }
  
  
  public function setOnfocus($value)
  {
    $this->stri_onfocus=$value;
  }
  
  public function setClass($value)
  {$this->stri_class=$value;}
  
  public function setId($value)
  {$this->stri_id=$value;}
  
  public function setOndblclick($value)
  {$this->stri_ondblclick=$value;}
    
  
  public function setOnclick($value)
  {$this->stri_onclick=$value;}
  
  public function setOnblur($value)
  {
    $this->stri_onblur=$value;
  }
  
  public function setOnselect($value)
  {
    $this->stri_onselect=$value;
  }
  
  public function setOnchange($value)
  {
    $this->stri_onchange=$value;
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
  public function setRows($int)
  {
    if(is_numeric ($int))
    {
      $this->int_rows=$int;
    }
    else
    {
      echo("<script>alert('int_rows doit etre de type entier');</script>");
    }
  }
  
   public function setCols($int)
  {
    if(is_numeric ($int))
    {
      $this->int_cols=$int;
    }
    else
    {
      echo("<script>alert('int_cols doit etre de type entier');</script>");
    }
  }
  public function setTabIndex($num)
  {$this->stri_tabindex=$num;}
  
  public function setStyle($value)
  {
    $this->stri_style=$value;
  }
  
  public function setOnMouseOver($value)
  {
    $this->stri_onmouseover=$value;
  }
  
  public function setOnMouseOut($value)
  {
    $this->stri_onmouseout=$value;
  }
  
  public function setOnKeyPress($value)
  {
    $this->stri_onkeypress=$value;
  }
  
  public function setOnKeyDown($value)
  {
    $this->stri_onkeydown=$value;
  }
  
  public function setOnKeyUp($value)
  {
    $this->stri_onkeyup=$value;
  }
  
  public function setWrap($value)
  {
    $this->stri_wrap=$value;
  }
  public function setAutoSave($value){$this->bool_auto_save=$value;}

   /*getter**********************************************************************/
  public function getRows()
  {return $this->int_rows;}
  
  public function getRequired()
  {return $this->stri_required;}
  
  public function getPlaceholder()
  {return $this->stri_placeholder;}
  
  public function getDataType()
  {return $this->stri_data_type;}
  
  public function getCanBeEmpty()
  {return $this->bool_can_be_empty;}
  
  public function getClass()
  {return $this->stri_class;}
  
  public function getId()
  {return $this->stri_id;}
  
  public function getReadonly()
  {return $this->bool_readonly;}
  
  public function getName()
  {return $this->stri_name;}
   
  public function getValue()
  {return $this->stri_value;}
  
  public function getDisabled()
  {return $this->bool_disabled;}
   
  public function getCols() 
  {return $this->int_cols;}
  
   public function getOnfocus() 
  {return $this->stri_onfocus;}
  
   public function getOnblur() 
  {return $this->stri_onblur;}
  
   public function getOnselect() 
  {return $this->stri_onselect;}
  
  public function getOnchange() 
  {return $this->stri_onchange;}
  
  public function getOnclick() 
  {return $this->stri_onclick;}
  
  public function getStyle()
  {return $this->stri_style;}

  public function getOnmouseover()
  {return $this->stri_onmouseover;}
  
  public function getOnmouseout()
  {return $this->stri_onmouseout;}
  
  public function getOnKeyPress()
  {return $this->stri_onkeypress;}
  
  public function getOnKeyDown()
  {return $this->stri_onkeydown;}
  
  public function getOnKeyUp()
  {return $this->stri_onkeyup;}
  
  public function getWrap()
  {return $this->stri_wrap;}
  
  public function getAutoSave(){return $this->bool_auto_save;} 
   /* method for serialization **************************************************/
   public function __sleep() {
     /*$this->arra_sauv['stri_name']  = $this->stri_name;
     $this->arra_sauv['stri_value']  = $this->stri_value;*/
      $this->arra_sauv['name']= $this->stri_name;
      $this->arra_sauv['rows']= $this->int_rows;
      $this->arra_sauv['value']= $this->stri_value;
      $this->arra_sauv['disabled']= $this->bool_disabled;
      $this->arra_sauv['readonly']= $this->bool_readonly;
      $this->arra_sauv['cols']= $this->int_cols;
      $this->arra_sauv['onfocus']= $this->stri_onfocus;
      $this->arra_sauv['onblur']= $this->stri_onblur;
      $this->arra_sauv['onselect']= $this->stri_onselect;
      $this->arra_sauv['onchange']= $this->stri_onchange;
      $this->arra_sauv['ondblclick']= $this->stri_ondblclick;
      $this->arra_sauv['onclick']= $this->stri_onclick;
      $this->arra_sauv['tabindex']= $this->int_tabindex;

     return array('arra_sauv');
   }
   
  public function __wakeup() {
     /*$this->stri_name  = $this->arra_sauv['stri_name'];
     $this->stri_value= $this->arra_sauv['stri_value'];*/
      $this->stri_name= $this->arra_sauv['name'];
      $this->int_rows= $this->arra_sauv['rows'];
      $this->stri_value= $this->arra_sauv['value'];
      $this->bool_disabled= $this->arra_sauv['disabled'];
      $this->bool_readonly= $this->arra_sauv['readonly'];
      $this->int_cols= $this->arra_sauv['cols'];
      $this->stri_onfocus= $this->arra_sauv['onfocus'];
      $this->stri_onblur= $this->arra_sauv['onblur'];
      $this->stri_onselect= $this->arra_sauv['onselect'];
      $this->stri_onchange= $this->arra_sauv['onchange'];
      $this->stri_ondblclick= $this->arra_sauv['ondblclick'];
      $this->stri_onclick= $this->arra_sauv['onclick'];
      $this->int_tabindex= $this->arra_sauv['tabindex'];

     $this->arra_sauv = array();
      
   }
   
  /*other method****************************************************************/
  public function htmlValue()
  {
   //- gestion de sauvegarde automatique 
   if($this->bool_auto_save)
   {
     $this->stri_onmouseover.="text_arrea.initAutoSave($(this));";
   }
  
  
  $stri_res="<textarea "; 
  $stri_res.=($this->stri_name!="")?" name=\"".$this->stri_name."\" ":"";
  $stri_res.=((string)$this->int_cols!="")?"  cols=\"".$this->int_cols."\" " :"";
  $stri_res.=((string)$this->int_rows!="")?"  rows=\"".$this->int_rows."\" " :"";
  $stri_res.=($this->stri_onfocus!="")?" onfocus=\"".$this->stri_onfocus."\" ":"";
  $stri_res.=($this->stri_onblur!="")?" onblur=\"".$this->stri_onblur."\" ":"";
  $stri_res.=($this->stri_style!="")? " style=\"".$this->stri_style."\" " : "";
  $stri_res.=($this->stri_onselect!="")?" onselect=\"".$this->stri_onselect."\" ":"";
  $stri_res.=($this->stri_onchange!="")?" onchange=\"".$this->stri_onchange."\" ":"";
  $stri_res.=($this->stri_ondblclick!="")?" ondblclick=\"".$this->stri_ondblclick."\" ":"";
  $stri_res.=($this->stri_onclick!="")?" onclick=\"".$this->stri_onclick."\" ":"";    
  $stri_res.=($this->stri_onmouseover!="")? " onmouseover=\"".$this->stri_onmouseover."\" " : "";           
  $stri_res.=($this->stri_onmouseout!="")? " onmouseout=\"".$this->stri_onmouseout."\" " : "";
  $stri_res.=($this->stri_onkeypress!="")? " onKeyPress=\"".$this->stri_onkeypress."\" " : "";
  $stri_res.=($this->stri_onkeydown!="")? " onKeyDown=\"".$this->stri_onkeydown."\" " : "";
  $stri_res.=($this->stri_onkeyup!="")? " onKeyUp=\"".$this->stri_onkeyup."\" " : "";
  $stri_res.=($this->stri_wrap!="")? " wrap=\"".$this->stri_wrap."\" " : "";
  $stri_res.=($this->stri_class!="")? " class=\"".$this->stri_class."\" " : "";
  $stri_res.=($this->stri_id!="")? " id=\"".$this->stri_id."\" " : "";
  $stri_res.=($this->stri_required!="")? " required=\"".$this->stri_required."\" " : "";
  $stri_res.=($this->stri_placeholder!="")? " placeholder=\"".$this->stri_placeholder."\" " : "";
  $stri_res.=((string)$this->int_tabindex!="")?" tabindex=\"".$this->int_tabindex."\" ":"";
  
  
  if($this->bool_disabled)
  {$stri_res=$stri_res." disabled ";}
  if($this->bool_readonly)
  {$stri_res=$stri_res." readonly ";}
  $stri_res=$stri_res.">".$this->stri_value."</textarea>";
  return $stri_res;
  }
  
  
}

?>
