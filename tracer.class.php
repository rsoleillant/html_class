<?php
/*******************************************************************************
Create Date : 18/12/2008
 ----------------------------------------------------------------------
 Class name : tracer
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de remplir un fichier de log
********************************************************************************/
//classe mère
//include_once("file_reader_writer");


class tracer extends file_reader_writer  
{
  //**** attribute *************************************************************
 
  //**** constructor ***********************************************************
   
   /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe tracer   
   *                        
   **************************************************************/         
  function __construct($path="",$name="") 
  {
   
  
      
      
     $stri_file_name=($name=="")?date("dmy")."_".$_SESSION['PNSVuid'].".txt":$name;
  
   $stri_file_name=(is_file($path))?$path:$stri_file_name; 
   
   
   $stri_path=($path=="")?"modules/OutilsAdmin/trace":$path;
   //appel du constructeur de la classe mère
   //file_reader_writer::__construct($stri_path,$stri_file_name);
   parent::__construct($path);
 
  }  
 
  //**** desctructor ***********************************************************

  function __destruct()
  {
   $this->closeFile();
  }
  //**** setter ****************************************************************
   
  //**** getter ****************************************************************
 
  
  //**** public method *********************************************************
  public function trace($stri_text)
  {
   //ouverture du fichier
   if($this->obj_ressource=="")
   {$this->openFile("a+");}
   
 
   
   
   $stri_total="\n[".date("d/m/Y H:i:s")."]\n".$stri_text;
   return $this->write($stri_total);
  }
    
 

}




?>
