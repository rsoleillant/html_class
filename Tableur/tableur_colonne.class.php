<?php

/*******************************************************************************
Create Date : 25/05/2011
 ----------------------------------------------------------------------
 Class name : tableur_colonne
 Version : 1.0
 Author : R�my Soleillant
 Description : Une colonne du tableur
********************************************************************************/

class tableur_colonne extends serialisable 
{
 //**** attribute ***********************************************************
  protected $arra_valeur_possible;//Tableau des diff�rentes valeurs qui peuvent �tre prise dans la colonne
  protected $mixed_valeur_defaut;//Les valeurs par d�faut d'une cellule de la colonne
  protected $int_num_colonne;//Le num�ro de la colonne
  protected $arra_cellule;//Les cellules contenu dans la colonne
  protected $stri_nom;//Le nom de la colonne
  protected $obj_cellule_reference;//La cellule qui sert de mod�le � toutes celles contenues dans la colonne
  protected $bool_editable; //Pour savoir si la colonne est �ditable ou non
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
     $this->bool_editable=true;//par d�faut les cellules sont �ditables
     $this->createReferenceCell();//cr�ation de la cellule de r�f�rence
  }   
 
 //**** setter *************************************************************
  public function setValeurPossible($value){$this->arra_valeur_possible=$value;}
  public function setValeurDefaut($value){$this->mixed_valeur_defaut=$value;}
  public function setCellule($value){$this->arra_cellule=$value;}
  public function setCelluleReference($value){$this->obj_cellule_reference=$value;}
  public function setNom($value){$this->stri_nom=$value;}
  
  //Permet de changer le mode d'�dition de la colonne
  public function setModeEdition($value)
  {
    $this->createReferenceCell($value);//on recr�er la cellule de r�f�rence
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
 
 Param�tres : obj tableur_cellule : la cellule � ajouter
 Retour : obj cellule : la cellule nouvellement cr��
     
  **************************************************************/     
  public function addCell(tableur_cellule $obj_cellule)
  {
    $this->arra_cellule[]=$obj_cellule;
    $obj_cellule->setColonne($this);
    return  $obj_cellule;
  }
 
    /*************************************************************
  Permet de cr�er la cellule de r�f�rence
 
 Param�tres : aucun
 Retour : obj cellule : la cellule de r�f�rence
     
  **************************************************************/     
  public function createReferenceCell($stri_mode_edition="text_arrea")
  {
   $obj_cell=new tableur_cellule("");
   $obj_cell->setColonne($this);
   
   $obj_cell->setModeEdition($stri_mode_edition);
    
   if(count($this->arra_valeur_possible)>0)//si on a des valeurs possibles
   {
    //  $obj_cell->setModeEdition("select");//l'�dition se fera avec une liste d�roulante
      $obj_cell->setModeEdition($stri_mode_edition);//l'�dition se fera avec une liste d�roulante
   
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
