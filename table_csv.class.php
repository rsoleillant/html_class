<?php
/*******************************************************************************
Create Date : 08/12/2010
 ----------------------------------------------------------------------
 Class name :  table_csv
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de convertir un tableau html en fichier csv
********************************************************************************/
include_once("includes/classes/php_writeexcel/php_writeexcel.pkg.php");

class table_csv
{
  //**** attribute *************************************************************
 protected $obj_table;//La table à convertir
 protected $stri_nom_fichier;//Le nom complet du fichier

  //**** constructor ***********************************************************
   
   /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe rules_applicator   
   *                        
   **************************************************************/         
  function __construct(table $obj_table,$stri_nom_fichier="") 
  {
    $this->obj_table=$obj_table;
    $this->stri_nom_fichier=($stri_nom_fichier!="")?$stri_nom_fichier:$_SERVER['DOCUMENT_ROOT']."/temp/".pnusergetvar("uid")."/table.csv";
  }  
 
  //**** setter ****************************************************************
  public function setWorkbook($value){$this->obj_workbook=$value;}
  public function setTable($value){$this->obj_table=$value;}
  public function setNomFichier($value){$this->stri_nom_fichier=$value;}
  public function setNomFeuille($value){$this->stri_nom_feuille=$value;}

    
  //**** getter ****************************************************************
  public function getWorkbook(){return $this->obj_workbook;}
  public function getTable(){return $this->obj_table;}
  public function getNomFichier(){return $this->stri_nom_fichier;}
  public function getNomFeuille(){return $this->stri_nom_feuille;}

 
  
  //**** public method *********************************************************  
  
 /*************************************************************
 * Permet de supprimer le html d'un text
 * parametres : string : le texte à nettoyer
 * retour : string : le texte sans balise html
 *                        
 **************************************************************/ 
  public function protegeTexte($stri_text)
  {   
   //gestion des saut de lignes
   $arra_replace=array("\r","\n",";");
   $arra_replacement=array("","","");
   
   $stri_text_ok=str_replace($arra_replace,$arra_replacement,$stri_text);
  
   //suppression des tags html 
   $stri_sans_html=strip_tags($stri_text_ok);
  
   //protection contre les formules
   $stri_sans_html=($stri_sans_html{0}=="=")?" ".$stri_sans_html:$stri_sans_html;//si le texte commence par =, on ajoute un espace pour ne pas considéré le texte comme une formule 
   
   return $stri_sans_html;   
  }
  
  
  /*************************************************************
 * Permet de créer le fichier csv contenant la table html
 * parametres : aucun
 * retour : string : le chemin du fichier créé
 *                        
 **************************************************************/ 
  public function converti()
  {
    $obj_table=$this->obj_table;
    $arra_tr=$obj_table->getTr();
        
    $stri_res="";
    foreach($arra_tr as $obj_tr)
    {
     $arra_td=$obj_tr->getTd();
     $arra_texte=array();//tableau temporaire pour stocker les textes d'une ligne
     foreach($arra_td as $obj_td)
     {
      $arra_texte[]=$this->protegeTexte($obj_td->getValue());     
     }
     
      $stri_res.=implode(";", $arra_texte)."\n";
    }
     
   
    //on créer le fichier
    $obj_file_writer=new file_reader_writer($this->stri_nom_fichier);
    $obj_file_writer->openFile("w");
    $obj_file_writer->write($stri_res);
    $obj_file_writer->closeFile();
    
    return $this->stri_nom_fichier;
  }
  

}




?>
