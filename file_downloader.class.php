<?php
/*******************************************************************************
Create Date : 06/04/2011
 ----------------------------------------------------------------------
 Class name : file_downloader
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de forcer le téléchargement d'un fichier. Attention, les entêtes
               http ne doivent pas avoir été envoyé pour que cela fonctionne. 
               La classe génère ses propres entêtes http
********************************************************************************/

class file_downloader extends serialisable  
{
  //**** attribute *************************************************************
 protected $stri_path_file;//le chemin complet où trouver le fichier
  //**** constructor ***********************************************************
   
   /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe rules_applicator   
   *                        
   **************************************************************/         
  function __construct($stri_path_file) 
  {
    $this->stri_path_file=$stri_path_file;
    $this->saveInTemp("file_downloader");
  }  
 
  //**** setter ****************************************************************
  public function setPathFile($value){$this->stri_path_file=$value;}

    
  //**** getter ****************************************************************
  public function getPathFile(){return $this->stri_path_file;}

 
  
  //**** public method *********************************************************
  
  /*************************************************************
   * Permet de lancer le téléchargement du fichier
   * parametres : aucun 
   * retour : aucun 
   *                        
   **************************************************************/  
  public function download()
  {
     //récupération du nom simple du fichier
     $stri_file=basename($this->stri_path_file);
    
    //on détermine le type de fichier
    switch(strrchr($stri_file, ".")) {
    
    case ".gz": $type = "application/x-gzip"; break;
    case ".tgz": $type = "application/x-gzip"; break;
    case ".zip": $type = "application/zip"; break;
    case ".pdf": $type = "application/pdf"; break;
    case ".png": $type = "image/png"; break;
    case ".gif": $type = "image/gif"; break;
    case ".jpg": $type = "image/jpeg"; break;
    case ".txt": $type = "text/plain"; break;
    case ".htm": $type = "text/html"; break;
    case ".html": $type = "text/html"; break;
    case ".docx": $type = "application/vnd.openxmlformats-officedocument.wordprocessingml.document"; break;
    default: $type = "application/octet-stream"; break;
    
     /* RS : 05/09/2011
     voir cette addresse pour ajout d'autres extensions microsoft : http://www.webdeveloper.com/forum/showthread.php?t=162526
     Pour que cela fonctionne, il faut que le fichier .htaccess soit configuré : home\www\N1\asislineProd\modules\Hotline\Partage_hotline\FICHIER_ATTACHE\.htaccess
     */
    }  
      
  
          //génération des header http
    ob_clean();
    header("Content-disposition: attachment; filename=$stri_file");
    header("Content-Type: application/force-download");
    header("Content-Transfer-Encoding: $type\n"); // Surtout ne pas enlever le \n
    header("Content-Length: ".filesize($this->stri_path_file));
    header("Pragma: no-cache");
    header('Expires: 0');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-cache, must-revalidate');
      
   
    if( strpos($_SERVER['HTTP_USER_AGENT'],"MSIE ")!==false) //Pour que ie retrouve le chemin du fichier
    {
     $stri_file_path=str_replace($_SERVER['DOCUMENT_ROOT'],"",$this->stri_path_file);
     header("location:".$stri_file_path);
    }
           
         
    //lecture du fichier
    readfile($this->stri_path_file);
    
    //suppresion du fichier
    //unlink($this->stri_path_file);
    
   
   
  }

  /*************************************************************
   * Permet de construire une iframe qui va lancer le téléchargement
   * automatiquement   
   * parametres : aucun 
   * retour : string : le code html 
   *                        
   **************************************************************/  
  public function htmlValue()
  {      
    $obj_iframe=new tag("iframe","download");
      $obj_iframe->addAttribute("src","modules.php?op=modload&name=Outils&file=file_downloader.tintf");
      $obj_iframe->addAttribute("width","0px");
      $obj_iframe->addAttribute("height","0px");
    return $obj_iframe->htmlValue();
  }
}




?>
