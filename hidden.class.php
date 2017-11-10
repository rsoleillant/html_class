<?php
/*******************************************************************************
Create Date : 22/05/2006
 ----------------------------------------------------------------------
 Class name : hidden
 Version : 1.0
 Author : Rémy Soleillant
 Description : élément html <input type='hidden'>
********************************************************************************/
include_once('input.class.php');

//la classe hidden hérite de la classe input
class hidden extends input 
{   
  //**** attribute *************************************************************


  //**** constructor ***********************************************************
  function __construct($name,$value) 
  {
    //construit l'objet hidden
    //@param : $name => le nom de l'objet hidden
    //         $value => la valeur du hidden
    //@return : void
    
    $this->stri_value=$value;
    $this->stri_name=$name;
    $this->stri_type="hidden";
  }  

  
  //**** setter ****************************************************************
  
  
  //**** getter ****************************************************************    
 
  //**** public method *********************************************************
  public function htmlValue()
  {
    //affichage de l'interface
    //@return : $stri_res => code HTML du hidden
    $stri_res=$this->super_htmlValue();
    $stri_res.=">"; 
    return $stri_res;
  }

  //**** method for *********************************************************
  public function __sleep() 
  {
    //sérialisation de la classe hidden
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
    return array('arra_sauv');
  }
   
  public function __wakeup() 
  {
    //désérialisation de la classe hidden 
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
    $this->arra_sauv = array(); 
  }
 
}

?>
