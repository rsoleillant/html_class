<?php
/*******************************************************************************
Create Date : 03/11/2011
 ----------------------------------------------------------------------
 Class name : module_step
 Version : 1.0
 Author : Lucie Prost
 Description : objet graphique
 Permet d'afficher un petit bandeau avec les différents étapes du module et leur état (rempli, en cours, non rempli)
 utilise la classe step.class.php pour ajouter des étapes au bandeau
 
********************************************************************************/
class module_step extends serialisable {
   
   //**** attribute ************************************************************
   protected $arra_step=null;       
   public $arra_sauv;

  
  //**** constructor ***********************************************************
  function __construct() 
  { 
  }
  
   
   
   //**** public method *********************************************************
  
  // Ajout d'une étape pour le module concerné, parametres de construction de l'étape : (utilisation de la classe step.class.php)
  // - onclick : stri, action a réaliser lors du click sur l'image de l'étape
  // - description : stri, description de l'étape
  // - img : stri, adresse de l'image à afficher pour représenter cette étape
  public function addStep($onclick, $description, $img, $background_color ="red")
  {
    $obj_step = new step($onclick, $description, $img, $background_color);

    $this->arra_step[] = $obj_step;
  } 
  
  public function htmlValue()
  {
     $table = new table();
     $table->setCellspacing(0);
     $table->setCellpadding(0);
       $tr = $table->addTr();
       
     foreach($this->arra_step as $step){        
          $img_td = new img("images/module/pellicule_gauche.gif");
           $img_td->setHeight("50px");
          $td = $tr->addTd($img_td->htmlValue());
           $td->setStyle("background-color:".$step->getBackground_color().";");
          
          $img_td = new img($step->getImg());
            $img_td->setWidth("21px");
            $img_td->setTitle($step->getDescription());
            $img_td->setClass("infobulle");
            $img_td->setOnclick($step->getOnclick());            
          $td = $tr->addTd($img_td->htmlValue());
           $td->setStyle("background-image:url(images/module/pellicule_milieu2.gif);background-color:".$step->getBackground_color()."; cursor:pointer;");
          
          $img_td = new img("images/module/pellicule_droite.gif");
           $img_td->setHeight("50px");
          $td = $tr->addTd($img_td->htmlValue());
           $td->setStyle("background-color:".$step->getBackground_color().";");          
     }
     
     return $table->htmlValue();
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
