<?php
/*******************************************************************************
Create Date : 28/01/2011
 ----------------------------------------------------------------------
 Class name :  serialisable_array
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de stocker en tableau pour le rendre sérialisable
********************************************************************************/

class serialisable_array  extends serialisable
{
  //**** attribute *************************************************************
 protected $arra_array; //Le tableau à sérialiser
  //**** constructor ***********************************************************
   
   /*************************************************************
   *            
   * parametres : 
   * retour : objet de la classe rules_applicator   
   *                        
   **************************************************************/         
  function __construct($arra_array) 
  {
    $this->arra_array=$arra_array;
  }  
 
  //**** setter ****************************************************************
  public function setArray($value){$this->arra_array=$value;}

    
  //**** getter ****************************************************************
  public function getArray(){return $this->arra_array;}

 
  
  //**** public method *********************************************************
    /*************************************************************
  Méthode appellée automatiquement à la désérialisation
 
   Paramètres : aucun
   Retour :   aucun
  
  **************************************************************/     
  public function __wakeup()
  { 
  
  }
  
    /*************************************************************
  Méthode appellé automatiquement lors de la sérialisation
 
   Paramètres : aucun
   Retour     : array : le tableau des attributs sérialisés
   
  **************************************************************/     
  public function __sleep()
  {  
   return array('arra_array');
  }
 
}




?>
