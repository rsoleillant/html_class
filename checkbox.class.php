<?php
/*******************************************************************************
Create Date : 22/05/2006
 ----------------------------------------------------------------------
 Class name : checkbox
 Version : 1.0
 Author : Rémy Soleillant
 Description : élément html <input type='checkbox'>
********************************************************************************/
include_once('input.class.php');

//la classe checkbox hérite de la classe input
class checkbox extends input
{   
  //**** attribute *************************************************************
  protected $bool_checked=false;    //=>permet de cocher -true- ou décocher -false- la case
  protected $stri_onclick="";       //=>définit les actions js sur le clic de la case
  protected $stri_name=""; 
  //**** constructor ***********************************************************
  function __construct($name,$value) 
  {
    //construit l'objet checkbox
    //@param : $name => le nom de l'objet checkbox
    //@param : $value => la valeur du checkbox qui est envoyé
    //@return : void 
    
    $this->stri_value=$value;
    $this->stri_name=$name;
    $this->stri_type="checkbox";
  }

  
  //**** setter ****************************************************************
  public function setChecked($bool)
  {
    //permet de cocher la case à cocher
    //@param : $bool => true : case cochée
    //                  false : case décochée
    //@return : void
    
    if(is_bool($bool))
      {$this->bool_checked=$bool;}
    else
    {echo("<script>alert('bool_checked doit etre de type boolean');</script>");}
  }  
  public function setOnclick($value){$this->stri_onclick=$value;}


  //**** getter ****************************************************************
  public function getChecked(){return $this->bool_checked;}
  public function getOnclick($value){return $this->stri_onclick;}
  public function getName(){return $this->stri_name;}
  
  //**** public method *********************************************************
  public function htmlValue()
  {
    //affiche la case à cocher
    //@return : [string] => le code HTML de la case à cocher
    $stri_res=$this->super_htmlValue();
    $stri_res.=($this->stri_onclick!="")? " onclick=\"".$this->stri_onclick."\" " : "";
    $stri_res.=($this->bool_checked)?" checked ":"";   
    $stri_res.=">"; 
    return $stri_res;
  }
  
  
  //**** method for serialization **********************************************
  public function __sleep() 
  {
    //sérialisation de la classe checkbox et de sa classe mère
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
    $this->arra_sauv['checked']= $this->bool_checked;
    $this->arra_sauv['onclick']= $this->stri_onclick;
    
    return array('arra_sauv');
  }

  public function __wakeup()
  {
    //désérialisation de la classe checkbox et de sa classe mère
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
    $this->bool_checked= $this->arra_sauv['checked'];
    $this->stri_onclick= $this->arra_sauv['onclick'];
    
    $this->arra_sauv = array();
  }  
  //Other method:
  //add by Y.M:
  public function trans_bool ($int)//permet de convertir une valeur numérique en booléen.
  {
    if ($int==0)
    {
    $this->bool_checked=FALSE;
    }
    else
    {
    $this->bool_checked=TRUE;
    }
  }
}

?>
