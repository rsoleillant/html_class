<?php
/*******************************************************************************
Create Date : 30/03/2010
 ----------------------------------------------------------------------
 Class name : fieldset
 Version : 1.0
 Author : ESCOT A
 Description : élément html <fieldset>
********************************************************************************/

class fieldset {
   
   /*attribute***********************************************/
   protected $stri_value;
   protected $stri_legend;
   protected $id_fieldset;
   protected $stri_style;   
   public $arra_sauv=array();
   
  
  //**** constructor ***********************************************************
   function __construct($stri_legend,$stri_value="") {
       $this->stri_value=$stri_value;
       $this->stri_legend=$stri_legend;       
   }
  
  
  //**** setter **************************************************************** 

  public function setLegend($value)
  {
    $this->stri_legend=$value;
  }
  
  public function setValue($value)
  {
    $this->stri_value=$value;
  }
  
  public function setId($value)
  {
    $this->id_fieldset=$value;
  }
  
  public function setStyle($value)
  {
    $this->stri_style=$value;
  }
  
  //**** getter ****************************************************************
  
  public function getLegend()
  {return $this->stri_legend;}
   
  public function getValue()
  {return $this->stri_value;}
  
    
  
  //**** other method **********************************************************
  public function htmlValue()
  {
    $stri_res="<fieldset ";
    $stri_res.=($this->id_fieldset!="")? " id=\"".$this->id_fieldset."\" " : "";
    $stri_res.=($this->stri_style!="")? " style=\"".$this->stri_style."\" " : "";
    $stri_res.=">";
    $stri_res.="<legend>".$this->stri_legend."</legend>";
    $stri_res.= $this->stri_value;
    $stri_res.="</fieldset>";
    return $stri_res;
  }

   
  
}

?>
