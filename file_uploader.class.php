<?php
/*******************************************************************************
Create Date : 22/05/2006
 ----------------------------------------------------------------------
 Class name : file
 Version : 1.1
 Author : Rémy Soleillant
 Description : élément html <input type='file'>
********************************************************************************/

class file_uploader 
{   
  //**** attribute ************************************************************
  protected $stri_file_local_name="";      // nom du input type=file utilisé  
  protected $stri_file_server_name="";     // nom du fichier sur le serveur  
  protected $stri_server_directory="";     // chemin ou mettre le fichier
  protected $stri_extension="";            // extensions autorisées séparées par des virgules
  protected $int_max_size;                 // taille maximale que doit faire le fichier
  protected $stri_error_message="";        // erreur générée lors de l'upload du fichier
  protected $arra_sauv;                    // tableau pour la sérialisation / désérialisation
  
  //**** constructor ***********************************************************
  function __construct($local_name,$server_name,$server_directory,$extension,$max_size=999999999) 
  {    
    //construit l'objet file_uploader
    //@param : $local_name => nom du input type=file utilisé
    //@param : $server_name => nom du fichier sur le serveur
    //@param : $server_directory => chemin ou mettre le fichier
    //@param : $extension => extensions autorisées séparées par des virgules ex: txt,doc,pdf
    //@param : $max_size => taille maximale que doit faire le fichier
    //@return : void
   
    //le nom du fichier sur le serveur ne doit pas comporter de caractère accentués
    //$stri_accent  ="ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ ";
    //$stri_noaccent="aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby_"; 
    //$server_name=strtr($server_name,$stri_accent,$stri_noaccent);
    
    //suppression des accent, remplacement des espace par underscore, suppression carractère non alpha numérique excepté underscore ""." et "@"
    //le @ peut être uilisé dans un nom de fichier en temps que séparateur de donnée (id@nom_fic ex :123654@mon_fic.txt)
    $search = array ('@[éèêëÊË]@i','@[àâäÂÄ]@i','@[îïÎÏ]@i','@[ûùüÛÜ]@i','@[ôöÔÖ]@i','@[ç]@i','@[ ]@i','@[^a-zA-Z0-9_.\@]@');
	  $replace = array ('e','a','i','u','o','c','_','');
	  $server_name=preg_replace($search, $replace, $server_name);

    
    $this->stri_file_local_name=$local_name;
    $this->stri_file_server_name=$server_name;
    $this->stri_server_directory=$server_directory;
    $this->stri_extension=$extension;
    $this->int_max_size=$max_size;
  }
  
  
  //**** setter ****************************************************************
  public function setFileLocalName($value){$this->stri_file_local_name=$value;}
  public function setFileServerName($value){$this->stri_file_server_name=$value;}
  public function setServerDirectory($value){$this->stri_server_directory=$value;}
  public function setExtension($value){$this->stri_extension=$value;}
  public function setMaxSize($int)
  { 
    if(is_numeric ($int))
      {$this->int_max_size=$int;}
    else
      {echo("<script>alert('int_max_size doit etre de type entier');</script>");}
  }
  
  
  //**** getter ****************************************************************
  public function getFileLocalName(){return $this->stri_file_local_name;}
  public function getFileServerName(){return $this->stri_file_server_name;}
  public function getServerDirectory(){return $this->stri_server_directory;}
  public function getExtension(){return $this->stri_extension;}
  public function getMaxSize(){return $this->int_max_size;}
  public function getErrorMessage(){return $this->stri_error_message;}
  
  
  //**** public method *********************************************************
  public function extensionVerification()
  {
    //verifie les extensions
    //@return : true => l'extension du fichier est autorisée 
    //          false => l'extension du fichier est interdite
    if($this->stri_extension==""){return true;}
    $stri_file_name=$_FILES[$this->stri_file_local_name]['name'];
    
    //recherche de l'extension du fichier
    $int_position_extension=strrpos($stri_file_name,".");
    $stri_extension=substr($stri_file_name,$int_position_extension+1);
      
    //création du tableau des extension autorisée
    $arra_extension=explode(",",strtolower($this->stri_extension)); 
   
    $bool_ok=in_array(strtolower($stri_extension),$arra_extension);
  
    return $bool_ok;
  }
  
  public function sizeVerification()
  {
    //verifie la taille du fichier
    //@return : true => la taille du fichier est correcte
    //          false => la taille du fichier est trop grande
    
    $bool_ok=true;
    //echo ">>taille du fichier : ".$_FILES[$this->stri_file_local_name]['size'];
    if($_FILES[$this->stri_file_local_name]['size']>$this->int_max_size)
    {
      $bool_ok=false;
    }
    return $bool_ok;
  }
  
  public function upload()
  {
    //uploade le fichier sur le serveur
    //@return : true => le fichier a bien été uploadé
    //          false => le fichier n'a pas pu être uploadé
    
    if( !is_uploaded_file($_FILES[$this->stri_file_local_name]['tmp_name']))
    {
      $this->stri_error_message=_ERROR_FILE_NOT_UPLOADED;
      echo "<font color='red'><b>Fichier local non trouvé<br></b></font>";
    //  return false;
    }
    else if(!$this->extensionVerification())
    {
      //echo "extension reelle ".$_FILES[$this->stri_file_local_name]['type']." extension voulue ".$this->stri_extension."<br>";
      $this->stri_error_message=_ERROR_EXTENSION;
      echo "<font color='red'><b>Extension du fichier incorrect<br></b></font>";
    }
    else if(!$this->sizeVerification())
    {
      $this->stri_error_message=_ERROR_FILE_SIZE;
      echo "<font color='red'><b>Le fichier dépasse la taille maximale autorisée. (".$this->getMaxSize()." octets)<br></b></font>";
    }
    else if(!move_uploaded_file($_FILES[$this->stri_file_local_name]['tmp_name'],
    $this->stri_server_directory.$this->stri_file_server_name))
    { 
	/*echo '<pre>'        ;
	var_dump($_FILES[$this->stri_file_local_name]['tmp_name'].'------------'. $this->stri_server_directory.$this->stri_file_server_name);
  var_dump($_POST);
  var_dump($_FILES);
	echo '</pre>';*/

      $this->stri_error_message=_ERROR_DIRECTORY;
      echo "<font color='red'><b>Upload du fichier impossible<br></b></font>";
    }   
    else if(!is_file($this->stri_server_directory.$this->stri_file_server_name))
    {
       $this->stri_error_message=_ERROR_FILE_UNAVAILABLE_ON_SERVER;
       echo "<font color='red'><b>Fichier non enregistré sur le serveur<br></b></font>";
    }
    
    
    //En cas de message d'erreur :
    if($this->stri_error_message != ""){
      //Ecriture des erreurs dans le fichier log : file_uploader.log
      $fh = fopen("includes/classes/html_class/file_uploader.log", 'a') or die("can't open file");
      $stri_error = "/*********************** Upload Error ***************************/ \n";
      $stri_error .= "date : ".date("d/m/Y_H:i:s")." user : ".pnUserGetVar('uid')." \n";
      $stri_error .= $this->stri_error_message."\n \n \n ";
      fwrite($fh, $stri_error);
      fclose($fh);
      
      return false;
    }else {
      return true;
    }
  }
  
  //**** method of serialization ***********************************************
  public function __sleep() 
  {
    //sérialisation de la classe file_upload
    $this->arra_sauv['file_local_name']= $this->stri_file_local_name;     
    $this->arra_sauv['file_server_name']= $this->stri_file_server_name;   
    $this->arra_sauv['server_directory']= $this->stri_server_directory; 
    $this->arra_sauv['extension']= $this->stri_extension;        
    $this->arra_sauv['max_size']= $this->int_max_size;             
    $this->arra_sauv['error_message']= $this->stri_error_message; 
    return array('arra_sauv');     
  }
  
  public function __wakeup() 
  {
    //désérialisation de la classe file_upload
    $this->stri_file_local_name= $this->arra_sauv['file_local_name'];     
    $this->stri_file_server_name= $this->arra_sauv['file_server_name'];   
    $this->stri_server_directory= $this->arra_sauv['server_directory']; 
    $this->stri_extension= $this->arra_sauv['extension'];        
    $this->int_max_size= $this->arra_sauv['max_size'];             
    $this->stri_error_message= $this->arra_sauv['error_message']; 
    $this->arra_sauv = array();       
  }
}
?>
