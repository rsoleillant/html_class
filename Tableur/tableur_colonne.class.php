<?php

/*******************************************************************************
Create Date : 25/05/2011
 ----------------------------------------------------------------------
 Class name : tableur_colonne
 Version : 1.0
 Author : Rémy Soleillant
 Description : Une colonne du tableur
********************************************************************************/

class tableur_colonne extends serialisable 
{
 //**** attribute ***********************************************************
  protected $arra_valeur_possible;//Tableau des différentes valeurs qui peuvent être prise dans la colonne
  protected $mixed_valeur_defaut;//Les valeurs par défaut d'une cellule de la colonne
  protected $int_num_colonne;//Le numéro de la colonne
  protected $arra_cellule;//Les cellules contenu dans la colonne
  protected $stri_nom;//Le nom de la colonne
  protected $obj_cellule_reference;//La cellule qui sert de modèle à toutes celles contenues dans la colonne
  protected $bool_editable; //Pour savoir si la colonne est éditable ou non
  protected $bool_visible;  //Pour savoir si la colonne est visible ou non
  protected static $int_id;



 
 //**** constructor ******************************************************** 

  /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe  tableur_colonne  
   *                        
   **************************************************************/         
  function __construct($stri_nom,$arra_valeur_possible=array(),$mixed_valeur_defaut="dd") 
  {
     self::$int_id++;
     $this->int_num_colonne=self::$int_id;
     $this->stri_nom=$stri_nom;
     $this->arra_valeur_possible=$arra_valeur_possible;
     $this->bool_visible=true;
     $this->mixed_valeur_defaut=$mixed_valeur_defaut;
     $this->bool_editable=true;//par défaut les cellules sont éditables
     $this->createReferenceCell();//création de la cellule de référence
  }   
 
 //**** setter *************************************************************
  public function setValeurPossible($value){$this->arra_valeur_possible=$value;}
  public function setValeurDefaut($value){$this->mixed_valeur_defaut=$value;}
  public function setCellule($value){$this->arra_cellule=$value;}
  public function setCelluleReference($value){$this->obj_cellule_reference=$value;}
  public function setNom($value){$this->stri_nom=$value;}
  
  //Permet de changer le mode d'édition de la colonne
  public function setModeEdition($value)
  {
    $this->createReferenceCell($value);//on recréer la cellule de référence
  }
  
  public function setEditable($value){$this->bool_editable=$value;}
  public function setVisible($value){$this->bool_visible=$value;}

 //**** getter *************************************************************
  public function getValeurPossible(){return $this->arra_valeur_possible;}
  public function getValeurDefaut(){return $this->mixed_valeur_defaut;}
  public function getNumColonne(){return $this->int_num_colonne;}
  public function getCellule(){return $this->arra_cellule;}
  public function getCelluleReference(){return $this->obj_cellule_reference;}
  public function getNom(){return $this->stri_nom;}
  public function getEditable(){return $this->bool_editable;}
  public function getIemeCellule($int_num_cell){return $this->arra_cellule[$int_num_cell];}
  public function getVisible(){return $this->bool_visible;}

 //**** other method *******************************************************
  /*************************************************************
  Permet d'ajouter une cellule
 
 Paramètres : obj tableur_cellule : la cellule à ajouter
 Retour : obj cellule : la cellule nouvellement créé
     
  **************************************************************/     
  public function addCell(tableur_cellule $obj_cellule)
  {
    $this->arra_cellule[]=$obj_cellule;
    $obj_cellule->setColonne($this);
    return  $obj_cellule;
  }
 
    /*************************************************************
  Permet de créer la cellule de référence
 
 Paramètres : aucun
 Retour : obj cellule : la cellule de référence
     
  **************************************************************/     
  public function createReferenceCell($stri_mode_edition="text_arrea")
  {
   $obj_cell=new tableur_cellule("");
   $obj_cell->setColonne($this);
   
   $obj_cell->setModeEdition($stri_mode_edition);
    
   if(count($this->arra_valeur_possible)>0)//si on a des valeurs possibles
   {
    //  $obj_cell->setModeEdition("select");//l'édition se fera avec une liste déroulante
      $obj_cell->setModeEdition($stri_mode_edition);//l'édition se fera avec une liste déroulante
   
   }
     
   
   $obj_cell->constructEdition();
   $this->obj_cellule_reference=$obj_cell;
  
 
  }
 
  //**** serialisation *******************************************************
 public function __wakeup()
 {
   parent::__wakeup();
  
   foreach($this->arra_cellule as $obj_cellule)
   {
   
    $obj_cellule->setColonne($this);
   }
 
 }
}
?>
