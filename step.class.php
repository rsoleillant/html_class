<?php
/*******************************************************************************
Create Date : 03/11/2011
 ----------------------------------------------------------------------
 Class name : step
 Version : 1.0
 Author : Lucie Prost
 Description : objet graphique
 Permet d'ajouter une étape à un objet module_step (addStep())
 Reflète graphiquement une étape d'un module

 /!\ Ne peut s'utiliser seule 
********************************************************************************/
class step extends serialisable {
   
   //**** attribute ************************************************************
   protected $stri_id = "";
   protected $stri_onclick = "";
   protected $stri_description = "";
   protected $stri_img = ""; 
   protected $stri_background_color = "";      
   public $arra_sauv;

  
  //**** constructor ***********************************************************
  function __construct($onclick, $description, $img, $background_color) 
  {
    $this->stri_onclick = $onclick;
    $this->stri_description = $description;
    $this->stri_img = $img; 
    $this->stri_background_color = $background_color;
  }
 
  
  //****getters*****************************************************************
  public function getId() { return $this->stri_id; } 
  public function getOnclick() { return $this->stri_onclick; } 
  public function getDescription() { return $this->stri_description; } 
  public function getImg() { return $this->stri_img; } 
  public function getBackground_color() { return $this->stri_background_color; }
  
  //****setters*****************************************************************
  public function setId($x) { $this->stri_id = $x; } 
  public function setOnclick($x) { $this->stri_onclick = $x; } 
  public function setDescription($x) { $this->stri_description = $x; } 
  public function setImg($x) { $this->stri_img = $x; } 
  public function setBackground_color($x) { $this->stri_background_color = $x; }
 
   
   //**** public method *********************************************************
  public function htmlValue()
  {     
     return null;
  }
  
  //**** method for serialization **********************************************
  public function __sleep() 
  {
    //sérialisation de la classe 
    $this->arra_sauv['id']= $this->stri_id;
    $this->arra_sauv['onclick']= $this->stri_onclick;
    $this->arra_sauv['description']= $this->stri_description;
    $this->arra_sauv['img']= $this->stri_img;

    return array('arra_sauv');
  }
  
  public function __wakeup() 
  {
    //désérialisation de la classe 

    $this->stri_id= $this->arra_sauv['id'];
    $this->stri_onclick= $this->arra_sauv['onclick'];
    $this->stri_description= $this->arra_sauv['description'];
    $this->stri_img= $this->arra_sauv['img'];

    $this->arra_sauv = array();
  } 
}

?>
