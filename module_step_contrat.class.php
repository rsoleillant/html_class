<?php
/*******************************************************************************
Create Date : 26/01/2012
 ----------------------------------------------------------------------
 Class name : module_step
 Version : 1.0
 Author : Lucie Prost
 Description : objet graphique
Permet d'afficher le module step du module contrat
 
********************************************************************************/
class module_step_contrat extends module_step{
   
  //**** attribute ************************************************************
   protected $int_current_step=1; //numéro de l'étape courante (afin de définir les courleurs de fond : vert, orange, ou rouge)  
   // /!\ : la première étape correspond à l'étape courante 1, l'étape 2 à la l'étape courante 2, etc...     
   protected $obj_module_step = null;
   protected $arra_color;
   public $arra_sauv;

  
  //**** constructor ***********************************************************
  function __construct($int_current_step) 
  { 
    $this->int_current_step = $int_current_step;   
  }
 
  //****** Getters ***************************************************************
  public function getInt_current_step() { return $this->int_current_step; }
  public function getObj_module_step() { return $this->obj_module_step; }
  public function getArra_color() { return $this->arra_color; }
  
  //****** Setters ***************************************************************
  public function setInt_current_step($x) { $this->int_current_step = $x; }
  public function setObj_module_step($x) { $this->obj_module_step = $x; } 
  public function setArra_color($x) { $this->arra_color = $x; }
   
   //**** public method ********************************************************* 
  public function fillArraColor()
  {
     for($i=0;$i<5;$i++){
         if(($i+1) == $this->int_current_step){
          $this->arra_color[$i] = "orange";
         }
         elseif(($i+1) < $this->int_current_step){
          $this->arra_color[$i] = "green";
         }
         elseif(($i+1) > $this->int_current_step){
          $this->arra_color[$i] = "red";
         }
     }  
  }
  
  public function htmlValue()
  {
    $this->fillArraColor();
     $this->obj_module_step = new module_step();

    //$this->obj_module_step->addStep("document.getElementById('hid_onglet_3').value='1.1- Assoc. client';document.getElementById('form_onglet_3').submit();", _DEFINITION_CLIENT, "images/module/logo_film/client2.png", $this->arra_color[0]);
    $this->obj_module_step->addStep("document.getElementById('1.1- Assoc. client').click();", _DEFINITION_CLIENT, "images/module/logo_film/client2.png", $this->arra_color[0]);
  
   // $this->obj_module_step->addStep("document.getElementById('hid_onglet_3').value='1.2- Assoc. contrat';document.getElementById('form_onglet_3').submit();", _ASSOCIATION_CONTRAT, "images/module/logo_film/contrat.png", $this->arra_color[1]);
   // $this->obj_module_step->addStep("document.getElementById('hid_onglet_3').value='1.3- Select. opt';document.getElementById('form_onglet_3').submit();", _SELECTION_OPTIONS, "images/module/logo_film/option2.png", $this->arra_color[2]);
    $this->obj_module_step->addStep("document.getElementById('1.3- Select. opt').click();", _SELECTION_OPTIONS, "images/module/logo_film/option2.png", $this->arra_color[2]);
   
   //$this->obj_module_step->addStep("document.getElementById('hid_onglet_3').value='1.4- Tarification';document.getElementById('form_onglet_3').submit();", _TARIFICATION, "images/module/logo_film/tarif3.png", $this->arra_color[3]);
    //$this->obj_module_step->addStep("document.getElementById('hid_onglet_3').value='1.5- Recapitulatif';document.getElementById('form_onglet_3').submit();", _RECAPITULATIF, "images/module/logo_film/recap.png", $this->arra_color[4]);
    $this->obj_module_step->addStep("document.getElementById('1.5- Recapitulatif').click();", _RECAPITULATIF, "images/module/logo_film/recap.png", $this->arra_color[4]);
            
      
     return $this->obj_module_step->htmlValue();
  }
  
  //**** method for serialization **********************************************
  public function __sleep() 
  {
    //sérialisation de la classe 
    $this->arra_sauv['arra_step']= $this->arra_step;

    return array('arra_sauv');
  }
  
  public function __wakeup() 
  {
    //désérialisation de la classe 

    $this->arra_step= $this->arra_sauv['arra_step'];

    $this->arra_sauv = array();
  } 
}

?>
