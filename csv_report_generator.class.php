<?php

/*******************************************************************************
Create Date : 04/03/2009
 ----------------------------------------------------------------------
 Class name : csv_report_generator
 Version : 1.0
 Author : gilles Rome
 Description : étend classe mère report_generator afin de générer un rapport au format CSV. 
********************************************************************************/


class csv_report_generator extends report_generator 
{
 //**** attribute *************************************************************
  protected $s_csv = ""; // string, contenue au format csv
  
  // variable de classe : paramètrage de la classe
  const S_SEPARATOR = ";"; // string, séparateur des éléments d'une ligne  
  const S_END_LINE = "\n"; // string, indicateur de fin de ligne
  const S_ESCAPE_CAR = " "; // string, caractère d'échapement
  
  /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe file_reader_writer   
   *                        
   **************************************************************/         
  public function __construct($sql,$title="",$sub_title="",$file_name="fichier")   
  {
    report_generator:: __construct($sql,$title,$sub_title,$file_name) ;
    
  }   

 //**** setter *************************************************************
 
 //**** getter *************************************************************
 
 //**** other method *******************************************************
 
  
   /*************************************************************
  Permet d'ajouter une ligne au rapport
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function addLine($s_line)
  {
    $this->s_csv.=str_replace( array(self::S_SEPARATOR, self::S_END_LINE), self::S_ESCAPE_CAR, $s_line).self::S_END_LINE;
  }

 

 /*************************************************************
  Permet d'ajouter une cellule au rapport.
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function addCell($s_contain)
  {
    $stri_res=str_replace( array(self::S_SEPARATOR, self::S_END_LINE), self::S_ESCAPE_CAR, $s_contain).self::S_SEPARATOR;
    $this->s_csv.=$stri_res;
  }

 

 /*************************************************************
  Permet de créer le fichier contenant le rapport.
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function makeFile()
  {
    $o_file_reader_writer = new file_reader_writer($this->stri_path,$this->stri_file_name);
    $o_file_reader_writer->openFile('w');
    $o_file_reader_writer->write($this->s_csv);
    $o_file_reader_writer->closeFile();
  }
  
  /*************************************************************
  Permet de créer une nouvelle ligne
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function newLine()
  {
    $this->s_csv.=self::S_END_LINE;
  }
  
  /*************************************************************
  Permet de prendre en compte les dimensions des colonnes.
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public  function sizeColumn()
  {
  // pas de taille de cellule en csv
  }
  
  /*************************************************************
  Permet d'appliquer un style de présentation dont le numéro est passé en paramètre
  
  parametres : 	$int_num_style : le numéro du style à appliquer		         
  retour :          
  **************************************************************/     
  public  function applyStyle($int_num_style)
  {
  // pas de style pour csv   
  }
}
?>
