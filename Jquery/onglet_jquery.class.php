<?php
/*******************************************************************************
Create Date : 03/11/2011
 ----------------------------------------------------------------------
 Class name : onglet
 Version : 1.0
 Author : Lucie Prost
 Description : élément jquery "onglet" appartenant à une liste d'onglets (voir 'onglet_list.class.php')
 
 /!\ ne peut pas etre généré tout seul, création via la méthode "addOnglet"  de onglet_list.class.php  /!\ 
 
********************************************************************************/
class onglet_jquery extends serialisable {
   
   //**** attribute ************************************************************ 
   protected $stri_name="";       
   protected $stri_id="";
   protected $stri_class="";
   protected $stri_content="";
   protected $stri_list_name;
   public $arra_sauv;

  
  //**** constructor ***********************************************************
  function __construct($stri_name, $stri_id, $stri_content, $stri_list_name) 
  { 
    $this->stri_name=$stri_name;
    $this->stri_id=$stri_id;
    $this->stri_content=$stri_content;
    $this->stri_list_name = $stri_list_name;
  }
 
  //**** setter ****************************************************************
  public function setName($value){$this->stri_name=$value;}
  public function setId($value){$this->stri_id=$value;}
  public function setContent($value){$this->stri_content=$value;}
  public function setClass($value){$this->stri_class=$value;}
  public function setList_name($value){$this->stri_list_name=$value;}
  
  //**** getter ****************************************************************
   public function getName(){return $this->stri_name;} 
   public function getId(){return $this->stri_id;} 
   public function getContent(){return $this->stri_content;} 
   public function getList_name(){return $this->stri_list_name;}
  
  //**** public method *********************************************************  
  public function htmlValue()
  {
      $stri_res = "<a href='#".$this->stri_list_name."-".$this->stri_id."' class='".$this->stri_class."' style='width: 75%; text-align:center'>";
      $stri_res .= $this->stri_name;
      $stri_res .= "</a>";

      return $stri_res;
  }
  
  //**** method for serialization **********************************************
  public function __sleep() 
  {
    //sérialisation de la classe 
    $this->arra_sauv['name']= $this->stri_name;

    return array('arra_sauv');
  }
  
  public function __wakeup() 
  {
    //désérialisation de la classe 

    $this->stri_name= $this->arra_sauv['name'];

    $this->arra_sauv = array();
  } 
}

?>
