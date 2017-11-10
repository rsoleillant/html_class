<?php
/*******************************************************************************
Create Date : 12/01/2012
 ----------------------------------------------------------------------
 Class name : td_pattern
 Version : 1.0
 Author : R�my Soleillant
 Description : Le td pour la classe tr_pattern
********************************************************************************/
class td_pattern extends td {
   
   /*attribute***********************************************/
  protected $int_debut;     //L'indice de d�but � partir duquel appliquer le mod�le
  protected $int_fin;       //L'indice de fin jusqu'auquel appliquer le mod�le
  protected $stri_methode;  //La m�thode � utiliser pour v�rifier si le mod�le s'applique

   /* constructor***************************************************************/
     /*************************************************************
  Permet de construire un td de plusieurs fa�on
 
 Param�tres : mixed : string : la valeur � afficher dans le td
                      obj : un objet avec une m�thode htmlValue appell� sur htmlValue du td
                      array : un tableau d'objet htmlValue et/ou de string 
 Retour :aucun
  
  **************************************************************/ 
   function __construct($mixed_value) {
  
     parent::__construct($mixed_value);  
      
   }
  
  
   /*setter*********************************************************************/
  public function setDebut($value){$this->int_debut=$value;}
  public function setFin($value){$this->int_fin=$value;}
  public function setMethode($value){$this->stri_methode=$value;}

  
  /*getter**********************************************************************/
  public function getDebut(){return $this->int_debut;}
  public function getFin(){return $this->int_fin;}
  public function getMethode(){return $this->stri_methode;}

  
 
  
  /*other method****************************************************************/
  /*************************************************************
  Permet de construire le r�sum� du td qui s'affichera si on
  affiche le pattern.
  Le r�sum� montre sur quels td le mod�le va �tre appliqu� 
  ainsi que les diff�rents attributs qui seront appliqu�s
 
 Param�tres : aucun
 Retour : string : le r�sum�
  **************************************************************/
  public function constructResume()
  {
    $stri_plage=$this->int_debut;
    if($this->int_debut!==$this->int_fin)
    {
      $stri_plage="debut ".$this->int_debut."- fin ".$this->int_fin; 
    } 
    //$stri_plage=($this->int_debut==$this->int_fin)?$this->int_fin:(string)$this->int_debut."/".$this->int_fin;
    
    $stri_resume.="Td : ".$stri_plage." ".$this->stri_methode;
    $arra_attribut=$this->getModelAttribut();
    
    $stri_resume."Attribut :";
    foreach($arra_attribut as $stri_attribut)
    {
      $stri_resume.="<p>$stri_attribut : ".$this->$stri_attribut."</p>";
    }
    
   
    return $stri_resume;
  }
  
   /*************************************************************
  Pour obtenir la liste des attribut qui devront �tre appliqu�
  sur le td cible
 
 Param�tres : aucun
 Retour : array : la liste des attributs non vide
  **************************************************************/ 
  public function getModelAttribut()
  {
     $arra_attribut=get_object_vars($this);
   
   $arra_ignore=array("mixed_value","int_debut","int_fin","stri_resume","stri_methode");
   //recherche des attribut non vide
   foreach($arra_attribut as $stri_attribut=>$stri_value)
   {                       
    if(($stri_value!="")&&(!in_array($stri_attribut,$arra_ignore)))
    {
     $arra_non_vide[]=$stri_attribut;
    }
   }
   
   return $arra_non_vide;
  }
 
  /*************************************************************
  Surcharge de la m�thode htmlValue.
  Permet de mettre dans la valeur du td le r�sum� de ce qui 
  va �tre appliqu�
 
 Param�tres : aucun
 Retour : string : le code html
  **************************************************************/
  public function htmlValue()
  {
  
   $this->mixed_value.="<p>".$this->constructResume()."</p>";
    
   return parent::htmlValue();
  }
 
 /*************************************************************
  Permet d'appliquer le mod�le actuel sur un td
 
 Param�tres : obj td : le td sur lequel on veut appliquer le mod�le
 Retour : aucun
  **************************************************************/   
  public function applyPattern(td $obj_td)
 {
   //application des attributs sur le td
   $arra_attribut=$this->getModelAttribut();
   foreach($arra_attribut as $stri_attribut)
   {
    $stri_base=str_replace(array("int_","stri_"),"",$stri_attribut);
    $stri_base=str_replace(array("int_","stri_","arra_"),"",$stri_attribut);
    $stri_base=str_replace("_","",$stri_base);
    $stri_setter="set".$stri_base;
    
    $obj_td->$stri_setter($this->$stri_attribut);
   }
   
 }   
}
?>
