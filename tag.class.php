<?php
/*******************************************************************************
Create Date : 01/10/2008
 ----------------------------------------------------------------------
 Class name : tag
 Version : 1.0
 Author : R�my Soleillant
 Description : tag html g�n�rique
********************************************************************************/
class tag {
   
   //**** attribute ************************************************************
   
   protected $arra_attributes=array(); //tableau contenant les attributs et leur valeur
   protected $stri_value;          //valeur contenu entre le tag ouvrant et le tag fermant
   public $arra_sauv=array();     //tableau pour la s�rialisation
  
  //**** constructor ***********************************************************
  function __construct($tag_name="",$stri_value="") 
  {
    if($tag_name!="")
     {$this->addAttribute($tag_name,"");}
   
    if($stri_value)
    {$this->setValue($stri_value);}
  }
 
  //**** setter ****************************************************************
  public function setAttributes($value){$this->arra_attributes=$value;}  
  public function setValue($value){$this->stri_value=$value;}  
 
  //**** getter ****************************************************************
  public function getAttributes(){return $this->arra_attributes;}
 
  
  //**** public method *********************************************************
  public function addAttribute($stri_name,$stri_value="")
  {
   $this->arra_attributes[$stri_name]=$stri_value;
  }
  
  public function htmlValue()
  {
    $stri_res="<";
    foreach($this->arra_attributes as $attribute=>$value)
    {
     $stri_value=($value!="")?'="'.$value.'" ':' ';
     $stri_res.=$attribute.$stri_value;
    }
    $stri_res.=">";
    
    //s'il y a une valeur, le tag est fermant
   if($this->stri_value!="")
   {
    //r�cup�ration du nom du tag : le premier attribut
    $arra_key=array_keys($this->arra_attributes);
    $stri_tag_name=$arra_key[0];
    $stri_res.=$this->stri_value."</".$stri_tag_name.">";
   }
    
   return $stri_res;
  }
  
  //**** method for serialization **********************************************
  public function __sleep() 
  {
    //s�rialisation de la classe a
    $this->arra_sauv['arra_attributes']= $this->arra_attributes;
    return array('arra_sauv');
  }
  
  public function __wakeup() 
  {
    //d�s�rialisation de la classe a
    $this->arra_attributes= $this->arra_sauv['arra_attributes'];   
    $this->arra_sauv = array();
  } 
}

?>
