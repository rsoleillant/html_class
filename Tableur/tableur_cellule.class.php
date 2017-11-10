<?php

/*******************************************************************************
Create Date : 25/05/2011
 ----------------------------------------------------------------------
 Class name : tableur_cellule
 Version : 1.0
 Author : Rémy Soleillant
 Description : Cellule du tableur
********************************************************************************/

class tableur_cellule extends serialisable 
{
 //**** attribute ***********************************************************
  protected $mixed_valeur;//La valeur de la cellule 
  protected $obj_ligne;//La ligne contenant la cellule
  protected $obj_colonne;//La colonne contenant la cellule
  protected $stri_mode_edition;//Le mode d'édition de la cellule
  protected $obj_edition;   // L'objet html permettant d'éditer la cellule
  protected $obj_td; //L'objet td représentant la cellule
 
 //**** constructor ******************************************************** 

  /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe  tableur_cellule  
   *                        
   **************************************************************/         
  function __construct($mixed_valeur) 
  {
   $this->mixed_valeur=$mixed_valeur; 
   $this->stri_mode_edition="text_arrea";
   $this->obj_td=new td();
   $this->obj_edition=new text();//pour qu'il y ai toujours un objet d'édition
  }   
 
 //**** setter *************************************************************
  public function setValeur($value){$this->mixed_valeur=$value;}
  public function setLigne(tableur_ligne $value){$this->obj_ligne=$value;}
  public function setColonne(tableur_colonne $value){$this->obj_colonne=$value;}
  public function setModeEdition($value){$this->stri_mode_edition=$value;}
  public function setEdition($value){$this->obj_edition=$value;}

 //**** getter *************************************************************
  public function getValeur(){return $this->mixed_valeur;}
  public function getLigne(){return $this->obj_ligne;}
  public function getColonne(){return $this->obj_colonne;}
  public function getModeEdition(){return $this->stri_mode_edition;}
  public function getEdition(){return $this->obj_edition;}
  public function getTd(){return $this->obj_td;}

  
 /*************************************************************
  Permet d'obtenir le libellé de la cellule
  
 Paramètres : aucun
 Retour : string : le libellé
        
  **************************************************************/     
  public function getLibelle()
  {
     $arra_valeur=$this->obj_colonne->getValeurPossible(); 
     $stri_valeur=(count($arra_valeur)>0)?$arra_valeur[$this->mixed_valeur]:$this->mixed_valeur;//récupération de la valeur direct ou de son libellé 
  
     return $stri_valeur;
  }
 //**** other method *******************************************************
  /*************************************************************
  Permet d'obtenir les identifiant de la cellules construit à partir du numéro de ligne et 
 de colonne
 
 Paramètres : aucun
 Retour : string : 'num_ligne'_'num_colonne' l'identifiant de la cellule (ex :5_2) 
      
  **************************************************************/     
  public function getId()
  {
    return $this->obj_ligne->getNumLigne()."_".$this->obj_colonne->getNumColonne();
  }
 

 /*************************************************************
  Permet de construire l'objet html d'édition de la cellule
 
 Paramètres : aucun
 Retour : obj : l'objet html servant à l'édition (textarrea, calendrier, select ...)
          
  **************************************************************/     
  public function constructEdition()
  {
 
    switch($this->stri_mode_edition)
    {
     case "select" :
        $arra_valeur=$this->obj_colonne->getValeurPossible();
        $obj_edition=new select("reference");
          $obj_edition->makeArrayToSelect($arra_valeur,3);
          $obj_edition->selectOption($this->mixed_valeur);      
     break;
     case "calendar" :
        $obj_edition=new js_calendar("reference",$this->mixed_valeur);
       
        js_calendar::resetInstance();
     break;
     case "select_and_text":
        $arra_valeur=$this->obj_colonne->getValeurPossible();   
        $obj_edition=new select_and_text("reference",$this->mixed_valeur);
        $obj_edition->addOptionByArray($arra_valeur);
        $obj_edition->selectOption($this->mixed_valeur);    
     break;
     
     default:
     $obj_edition=new text_arrea("reference",$this->mixed_valeur);
      $obj_edition->setCols("80");
      $obj_edition->setRows("15"); 
      //$obj_edition->setStyle("width:200px;");
    }
    
    $this->obj_edition=$obj_edition;
    return $obj_edition;
  }
 
  /*************************************************************
  Permet de d'adapter l'interface d'édition en se basant sur
  le mode d'édition de la cellule de référence de la colone (va éviter de construire 
  plusieurs fois la même interface)
  Paramètres : aucun
  Retour : obj : l'objet html servant à l'édition (textarrea, calendrier, select ...)
          
  **************************************************************/     
  public function adaptEdition()
  {
   $obj_cell=$this->obj_colonne->getCelluleReference();
  
   $obj_edition_reference=$obj_cell->getEdition();
   
   
   $obj_edition=clone($obj_edition_reference);
   //$obj_edition->setName($this->getId());
   $obj_edition->setName($this->obj_colonne->getNom()."[]");
   $obj_edition->setValue($this->mixed_valeur);
   $obj_edition->setDisabled($this->obj_edition->getDisabled());//répercution de l'attribut disabled sur le véritable objet d'édition
   
   return $obj_edition;
  }

 /*************************************************************
  Permet de construire la représentation HTML de la cellule
 
 Paramètres : aucun
 Retour : string : le code HTML de la cellule
        
  **************************************************************/     
  public function constructTd()
  {
    $obj_edition=$this->adaptEdition();
    
    //construction de la div d'édition
    $obj_div_edition=new div();
      $obj_div_edition->setName("div_edition");
      $obj_div_edition->setContain($obj_edition->htmlValue());
      $obj_div_edition->setStyle("position:absolute;padding:5px;margin:0;background-color:blue;display:none;");
      $obj_div_edition->setOnclick("stopPropagation(event);");
      //$obj_div_edition->setOnmouseOut("switchCell(this.parentNode);");
    
    //construction de la div de visu 
     //$arra_valeur=$this->obj_colonne->getValeurPossible(); 
     //$stri_valeur=(count($arra_valeur)>0)?$arra_valeur[$this->mixed_valeur]:$this->mixed_valeur;//récupération de la valeur direct ou de son libellé 
    $stri_valeur=$this->getLibelle();
  
    $obj_div_visu=new div();
      $obj_div_visu->setName("div_visu");
      //$obj_div_visu->setContain("<pre style='padding:0;margin:0;'>".$stri_valeur."</pre>");
      $obj_div_visu->setContain($stri_valeur);
      //$obj_div_visu->setContain($this->getId());
      
      $obj_div_visu->setStyle("height:30px; overflow-y:hidden; overflow-x:hidden; white-space :pre-line;  ");
       
      //$obj_td=new td($obj_div_edition->htmlValue().$obj_div_visu->htmlValue());
      $obj_td=$this->obj_td;
      $obj_td->setValue($obj_div_edition->htmlValue().$obj_div_visu->htmlValue());
     
      $stri_color=$this->obj_ligne->getColor();
      $obj_td->setBgcolor($stri_color);
      
      if($this->obj_colonne->getEditable())//si la colonne est éditable 
      {  
          $stri_click=$obj_td->getOnclick();
          $obj_td->setOnclick("switchCell(this);".$stri_click);
      }
      
      if(!$this->obj_colonne->getVisible())
      {
         $obj_td->setStyle("display:none;");
      } 
    
      
    $this->obj_td=$obj_td;
    return $obj_td;
  }

  //**** clonage ************************************************************
  public function __clone()
  {  
     $this->obj_td=clone($this->obj_td);
  }
  
 //**** serialisation *******************************************************
 public function __sleep()
 {
  //sauvegarde de ligne et colonne
  $obj_ligne=$this->obj_ligne;
  $obj_colonne=$this->obj_colonne;
  
  //Pour éviter la sérialisation cyclique
  $this->obj_ligne=null;
  $this->obj_colonne=null;
 
  $mixed_res=parent::__sleep();
  
  //restauration de l'objet
  $this->obj_ligne=$obj_ligne;
  $this->obj_colonne=$obj_colonne;
  
  return $mixed_res;
 }
}
?>
