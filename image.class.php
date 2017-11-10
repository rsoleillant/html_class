<?php
/*******************************************************************************
Create Date : 05/05/2011
 ----------------------------------------------------------------------
 Class name : text
 Version : 1.0
 Author : Rémy Soleillant
 Description : élément html <input type='image'>
********************************************************************************/
include_once('input.class.php');
class image extends input {
   
   /*attribute***********************************************/
   protected $int_maxlength="";
   protected $stri_onkeyup="";
   protected $stri_onclick="";
   protected $stri_ondblclick="";
   protected $stri_src;
   
  
  //**** constructor ***********************************************************
   function __construct($name,$stri_src) {
       $this->stri_src=$stri_src;
       $this->stri_name=$name;
       $this->stri_type="image";
       
   }
  
  
  //**** setter ****************************************************************
  
  public function setOnKeyUp($value)
  {
    $this->stri_onkeyup=$value;
  }
  
  public function setOnclick($value)
  {
    $this->stri_onclick=$value;
  }
  
  public function setOndblclick($value)
  {
    $this->stri_ondblclick=$value;
  }
  
  public function setSrc($value){$this->stri_src=$value;}
 
  //**** getter ****************************************************************
  
  public function getReadonly()
  {return $this->bool_readonly;}
   
  public function getMaxlength()
  {return $this->int_maxlength;}
  
  public function getOnKeyUp()
  {return $this->stri_onkeyup;}  
  
   public function getOnclick() 
  {return $this->stri_onclick;}
  
  public function getOndblclick() 
  {return $this->stri_ondblclick;}
  
  public function getSrc(){return $this->stri_src;}
  //**** other method **********************************************************
  public function htmlValue()
  {
      
    //- Ajoute un ombre sur mouse hover
    $this->setClass($this->getClass().' drop-shadow');
      
  $stri_res=$this->super_htmlValue();
  //echo "<br />Maxl : ".$this->int_maxlength;
  $stri_res.=((string)$this->int_maxlength!="")?" maxlength=\"".$this->int_maxlength."\" ":"";
  $stri_res.=($this->stri_src!="")?"src=\"".$this->stri_src."\" ":"";
  $stri_res.=($this->stri_onkeyup!="")? " onKeyUp=\"".$this->stri_onkeyup."\" " : "";
  $stri_res.=($this->stri_onclick!="")?" onclick=\"".$this->stri_onclick."\" ":"";
  $stri_res.=($this->stri_ondblclick!="")?" ondblclick=\"".$this->stri_ondblclick."\" ":"";
  $stri_res.=">"; 
  return $stri_res;
  }
 
}

?>
