<?php

/*******************************************************************************
Create Date : 25/05/2011
 ----------------------------------------------------------------------
 Class name : tableur_ligne
 Version : 1.0
 Author : Rémy Soleillant
 Description : Une ligne du tableur
********************************************************************************/

class tableur_ligne extends serialisable 
{
 //**** attribute ***********************************************************
  protected $int_num_ligne;//Le numéro de la ligne
  protected $arra_cellule;//Les cellules contenus dans la ligne
  protected $stri_color;  //La couleur de la ligne
  protected static $int_id;
 //**** constructor ******************************************************** 

  /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe  tableur_ligne  
   *                        
   **************************************************************/         
  function __construct() 
  {
    self::$int_id++;
    $this->int_num_ligne=self::$int_id;
    $this->stri_color="white";
  }   
 
 //**** setter *************************************************************
  public function setNumLigne($value){$this->int_num_ligne=$value;}
  public function setCellule($value){$this->arra_cellule=$value;}
  public function setColor($value){$this->stri_color=$value;}

 //**** getter *************************************************************
  public function getNumLigne(){return $this->int_num_ligne;}
  public function getCellule(){return $this->arra_cellule;}
  public function getColor(){return $this->stri_color;}

 //**** other method *******************************************************
  /*************************************************************
  Permet d'ajouter une cellule
 
 Paramètres : string : la valeur de la cellule
 Retour : obj cellule : la cellule nouvellement créé
     
  **************************************************************/     
  public function addCell($stri_value)
  {
    $obj_cellule=(is_object($stri_value))?$stri_value:new tableur_cellule($stri_value);
    
    $obj_cellule->setLigne($this);
    $this->arra_cellule[]=$obj_cellule;
    return  $obj_cellule;
  }
 

  
 //**** serialisation *******************************************************
/* public function __wakeup()
 {
   parent::__wakeup();
   
   foreach($this->arra_cellule as $obj_cellule)
   {
    $obj_cellule->setLigne($this);
   }
 
 }*/
  
  public function __sleep()
 {
  //sauvegarde des cellules
  $arra_cellule=$this->arra_cellule;
  
  //Pour éviter la sérialisation cyclique
  $this->arra_cellule=null;
 
  $mixed_res=parent::__sleep();
  
  //restauration de l'objet
  $this->arra_cellule=$arra_cellule;
  
  return $mixed_res;
 }
}
?>
