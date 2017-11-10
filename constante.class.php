<?php
/*******************************************************************************
Create Date : 20/12/2011
 ----------------------------------------------------------------------
 Class name : constante
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de gérer les constantes en PHP
********************************************************************************/

class constante
{ 
   
   /*attribute***********************************************/
  
  
  //**** constructor ***********************************************************
   function __construct() {
      
       
   }
  
  
  //**** setter ****************************************************************
 
  //**** getter ****************************************************************
 
  //**** other method **********************************************************
  public static function constant($stri_value)
  {
   return (defined($stri_value))?constant($stri_value):$stri_value;
  }

  public static function constante($stri_value)
  {
   $obj_constant=new constante();
   return $obj_constant->constant($stri_value);
  }
  
}

?>
