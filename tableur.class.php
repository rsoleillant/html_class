<?php
/*******************************************************************************
Create Date : 26/10/2009
 ----------------------------------------------------------------------
 File name : tableur.class.php
 File type : autoload : chargement automatique
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de définir la fonction d'autoload du package tableur
********************************************************************************/

//ajout d'une fonction en tant que fonction d'autoload
spl_autoload_register("loadTableur");

//définition d'une fonction d'autoload
function loadTableur($stri_class)
{ 
  $stri_path =  dirname( __FILE__ );
  include_once("$stri_path/Tableur/$stri_class.class.php");
}



?>
