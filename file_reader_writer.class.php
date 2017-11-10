<?php
/*******************************************************************************
Create Date : 18/12/2008
 ----------------------------------------------------------------------
 Class name : file_reader_writer
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de lire ou d'écrire dans un fichier
********************************************************************************/
include_once("file_reader_writer.class.php");
class file_reader_writer
{
  //**** attribute *************************************************************
  protected $stri_path;  //le chemin ou sauvegarder le fichier ex: modules/Hotline
  protected $stri_file_name; //le nom du fichier à utiliser    ex: log.txt 
  protected $obj_ressource="";  //la ressource utilisée pour accéder au fichier
  //**** constructor ***********************************************************
   
   /*************************************************************
   * Constructeur polymorphe
   * parametres : variant
   * retour : objet de la classe file_reader_writer   
   *                        
   **************************************************************/         
  function __construct() 
  {
    $arra_args=func_get_args();
    if(count($arra_args)==1)
    {
     if(is_dir($arra_args[0]))
     {$this->stri_path=$arra_args[0];}
     else     
     {
         $mix_param = (isset($arra_args[1])) ? $arra_args[1] : null;
         $this->construct2($arra_args[0],$mix_param);
         
     }  
    }
    else
    {
      $this->construct1($arra_args[0],$arra_args[1]);
    }
  }  
  
   /*************************************************************
   * Permet de construire l'objet à partir du path et du nom du fichier
   * parametres : stri_path : le chemin pour accèder au fichier
   *              stri_name : le nom du fichier     
   * retour : aucun
   *                        
   **************************************************************/  
  private function construct1($stri_path,$stri_name)
  {
   $this->stri_path=$stri_path;
   $this->stri_file_name=$stri_name;
  }
  
   /*************************************************************
   * Permet de construire l'objet à partir du nom complet du fichier
   * parametres : stri_file_name : le chemin complet pour accèder au fichier
   *              
   * retour : aucun
   *                        
   **************************************************************/  
  private function construct2($stri_file_name)
  {
    $stri_path=dirname($stri_file_name);
    $stri_name=basename($stri_file_name);
    
    $this->construct1($stri_path,$stri_name);
  }
  //**** setter ****************************************************************
  public function setPath($value){$this->stri_path=$value;}
  public function setFileName($value){$this->stri_file_name=$value;}
    
  //**** getter ****************************************************************
  public function getPath(){ return $this->stri_path;}
  public function getFileName(){return $this->stri_file_name;}
 
  
  //**** public method *********************************************************
  
   /*************************************************************
   *Permet d'ouvrir le fichier
   * parametres : $stri_mode : le mode d'ouverture du fichier. Ce paramètre
   *                           est le même que le paramètre "mode' de la fonction
   *                           php 'fopen'     
   *             
   * retour : bool : true => succès de l'ouverture
   *                 false=> échec    
   *                        
   **************************************************************/         
  public function openFile($stri_mode)
  {
   $stri_file=$this->stri_path."/".$this->stri_file_name;
   $res=fopen($stri_file,$stri_mode);
   
 
   
   if($res==false) 
   {return false;}
   $this->obj_ressource=$res;
   return true;
   
  }  
  
   /*************************************************************
   *Permet d'écrire dans le fichier
   * parametres : $stri_text : le texte à écrire dans le fichier
   * retour : bool : true => succès de l'écriture 
   *                 false=> échec    
   *                        
   **************************************************************/         
  public function write($stri_text)
  {
      if (is_resource($this->obj_ressource))
      {
        //$res=fwrite($this->obj_ressource,$stri_text);
        //- Romain le 04/08/2016 - Détection encodage UTF-8 pour conversion vers ISO-8859-1
        $res=fwrite($this->obj_ressource,  (mb_check_encoding($stri_text,'UTF-8')) ? utf8_decode($stri_text) : $stri_text );

        if($res===false)
        {return false;}
        
        return true;
      }
      
      return;
   
   
   
  }
  
   /*************************************************************
   *Permet de lire le contenu du fichier
   * parametres : aucun
   * retour : string : le contenu du fichier
   *          bool   :    false=> échec de lecture   
   *                        
   **************************************************************/         
  public function readV2()
  {
   $stri_file=$this->stri_path."/".$this->stri_file_name;
   $res=file_get_contents($stri_file);
   if($res===false)
   {return false;}
   
   return $res;
  }
  
   /*************************************************************
   *Permet de lire le contenu du fichier
   * parametres : aucun
   * retour : string : le contenu du fichier
   *          bool   :    false=> échec de lecture   
   *                        
   **************************************************************/         
  public function read()
  {
   $res=fread($this->obj_ressource,filesize ($this->stri_path."/".$this->stri_file_name));
   if($res===false)
   {return false;}
   
   return $res;
  }
  
  public function fgetss()
  {
    while (!feof($this->obj_ressource)) 
    {
      $ligne = fgets($this->obj_ressource, 1024);
      if (strpos($ligne,';\n?') !== false)
      {
        $ligne ='ca marche !!!!!!!!!!!'."\n";
      }
      echo $ligne."<br />";
    }
  }
  
   /*************************************************************
   *Permet de fermer le fichier
   * parametres : aucun
   * retour : bool : true => succès de la fermeture
   *                 false=> échec 
   *                        
   **************************************************************/         
  public function closeFile()
  {
        //Montée de version PHP 5.4 - Romain le 01/10/2015
        if (is_resource($this->obj_ressource))
        { return fclose($this->obj_ressource); }
    }
  
  
  /*************************************************************
   *Permet de lister les fichiers d'un répertoire
   * parametres : aucun
   * retour : array : liste des fichiers lu dans le répertoire
   *                        
   **************************************************************/         
  public function readDirectory($bool_directory=false,$bool_recursif=false)
  {
  
   $MyDirectory = opendir($this->stri_path);
   $arra_res=array();
	 while($Entry = @readdir($MyDirectory)) 
   {     
		if($bool_directory)//recherche de dossier
		{
        if((is_dir($this->stri_path.'/'.$Entry))&&($Entry{0}!="."))
    		{
          $arra_res[]=$this->stri_path.'/'.$Entry;
        }
    }
    else  //recherche de fichier
    {
      if(is_file($this->stri_path.'/'.$Entry))
  		{
        $arra_res[]=$this->stri_path.'/'.$Entry;
      }
    }
    
   
	 }     
	
  closedir($MyDirectory);
  
   return $arra_res;
  }
}




?>
