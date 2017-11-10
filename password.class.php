<?php
/*******************************************************************************
Create Date : 22/05/2006
 ----------------------------------------------------------------------
 Class name : password
 Version : 1.0
 Author : Rémy Soleillant
 Description : élément html <input type='password'>
********************************************************************************/
include_once('input.class.php');
class password extends input {
   
   /*attribute***********************************************/
   protected $bool_readonly=false;
   protected $int_maxlength="";
   protected $stri_onkeyup="";
   
   
   /* constructor***************************************************************/
   function __construct($name,$value) {
       $this->stri_value=$value;
       $this->stri_name=$name;
       $this->stri_type="password";
   }
  
  
   /*setter*********************************************************************/
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
  
  public function setMaxlength($int)
  {
    if(is_numeric ($int))
    {
      $this->int_maxlength=$int;
    }
    else
    {
      echo("<script>alert('int_maxlength doit etre de type entier');</script>");
    }
  }
  
  public function setOnKeyUp($value)
  {
    $this->stri_onkeyup=$value;
  }
  
  /*getter**********************************************************************/
  
  public function getReadonly()
  {return $this->bool_readonly;}
   
  public function getMaxlength()
  {return $this->int_maxlength;}
  
  public function getOnKeyUp()
  {return $this->stri_onkeyup;}  

  
  /*other method****************************************************************/
  public function htmlValue()
  {
  $stri_res=$this->super_htmlValue();
  $stri_res.=((string)$this->int_maxlength!="")?" maxlength=\"".$this->int_maxlength."\" ":"";
  $stri_res.=($this->bool_readonly)?"readonly":"";
  $stri_res.=($this->stri_onkeyup!="")? " onKeyUp=\"".$this->stri_onkeyup."\" " : "";
  $stri_res.=">"; 
  return $stri_res;
  }
  
  
  public function __sleep() 
  { 
    //sérialisation de la classe password 
    $this->arra_sauv['name']= $this->stri_name;
    $this->arra_sauv['type']= $this->stri_type;
    $this->arra_sauv['value']= $this->stri_value;
    $this->arra_sauv['disabled']= $this->bool_disabled;
    $this->arra_sauv['size']= $this->int_size;
    $this->arra_sauv['alt']= $this->stri_alt;
    $this->arra_sauv['onfocus']= $this->stri_onfocus;
    $this->arra_sauv['onblur']= $this->stri_onblur;
    $this->arra_sauv['onselect']= $this->stri_onselect;
    $this->arra_sauv['onchange']= $this->stri_onchange;
    $this->arra_sauv['onmouseover']= $this->stri_onmouseover;
    $this->arra_sauv['onmouseout']= $this->stri_onmouseout;
    $this->arra_sauv['onkeypress']= $this->stri_onkeypress;
    $this->arra_sauv['tabindex']= $this->int_tabindex;
    $this->arra_sauv['data_type']= $this->stri_data_type;
    $this->arra_sauv['title']= $this->stri_title;
    $this->arra_sauv['style']= $this->stri_style;
    $this->arra_sauv['id']= $this->stri_id;
    $this->arra_sauv['class']= $this->stri_class;
    $this->arra_sauv['can_be_empty']= $this->bool_can_be_empty;
    $this->arra_sauv['readonly']= $this->bool_readonly;
    $this->arra_sauv['maxlength']= $this->int_maxlength;
    $this->arra_sauv['onkeyup']= $this->stri_onkeyup;
    return array('arra_sauv');
  }

  public function __wakeup() 
  {
    //désérialisation de la classe password
    $this->stri_name= $this->arra_sauv['name'];
    $this->stri_type= $this->arra_sauv['type'];
    $this->stri_value= $this->arra_sauv['value'];
    $this->bool_disabled= $this->arra_sauv['disabled'];
    $this->int_size= $this->arra_sauv['size'];
    $this->stri_alt= $this->arra_sauv['alt'];
    $this->stri_onfocus= $this->arra_sauv['onfocus'];
    $this->stri_onblur= $this->arra_sauv['onblur'];
    $this->stri_onselect= $this->arra_sauv['onselect'];
    $this->stri_onchange= $this->arra_sauv['onchange'];
    $this->stri_onmouseover= $this->arra_sauv['onmouseover'];
    $this->stri_onmouseout= $this->arra_sauv['onmouseout'];
    $this->stri_onkeypress= $this->arra_sauv['onkeypress'];
    $this->int_tabindex= $this->arra_sauv['tabindex'];
    $this->stri_data_type= $this->arra_sauv['data_type'];
    $this->stri_title= $this->arra_sauv['title'];
    $this->stri_style= $this->arra_sauv['style'];
    $this->stri_id= $this->arra_sauv['id'];
    $this->stri_class= $this->arra_sauv['class'];
    $this->bool_can_be_empty= $this->arra_sauv['can_be_empty'];
    $this->bool_readonly= $this->arra_sauv['readonly'];
    $this->int_maxlength= $this->arra_sauv['maxlength'];
    $this->stri_onkeyup= $this->arra_sauv['onkeyup'];
    $this->arra_sauv = array(); 
  }  
}

?>
