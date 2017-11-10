<?php
/*******************************************************************************
Create Date : 28/01/2011
 ----------------------------------------------------------------------
 Class name :  serialisable_array
 Version : 1.0
 Author : R�my Soleillant
 Description : Permet de stocker en tableau pour le rendre s�rialisable
********************************************************************************/

class serialisable_array  extends serialisable
{
  //**** attribute *************************************************************
 protected $arra_array; //Le tableau � s�rialiser
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
  M�thode appell�e automatiquement � la d�s�rialisation
 
   Param�tres : aucun
   Retour :   aucun
  
  **************************************************************/     
  public function __wakeup()
  { 
  
  }
  
    /*************************************************************
  M�thode appell� automatiquement lors de la s�rialisation
 
   Param�tres : aucun
   Retour     : array : le tableau des attributs s�rialis�s
   
  **************************************************************/     
  public function __sleep()
  {  
   return array('arra_array');
  }
 
}




?>
