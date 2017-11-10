<?php

/*******************************************************************************
Create Date : 11/09/2009
 ----------------------------------------------------------------------
 Class name : serialisable
 Version : 1.1
 Author : R�my Soleillant
 Description : Permet de rendre un objet s�rialisable avec compression des donn�es
               La sauvegarde est bas�e sur l'enregistrement dans un fichier sur disque
********************************************************************************/

class serialisable 
{
 //**** attribute ***********************************************************
  private static $stri_save_path="temp";//Le chemin o� sauvegarder les fichiers temporaire
  private static $int_recursif=0;//Permet de savoir si on est dans un appel r�cursif de m�thode ou si on est dans le premier appel
  protected static $bool_compressed=true;//si on doit compresser les donn�es
  protected static $int_compression_level=9;//le niveau de compression 0 null 9 maximal
  public $arra_sauv;//Le tableau contenant les donn�es s�rialis�es
  
 //**** constructor ******************************************************** 
 
 //**** setter *************************************************************
  public static function setSavePath($value){self::$stri_save_path=$value;}
  public static function setCompressed($value){self::$bool_compressed=$value;}
  public static function setCompressionLevel($value){self::$int_compression_level=$value;}
 //**** getter *************************************************************
  public static function getSavePath(){return self::$stri_save_path;}
  public static function getCompressed(){return self::$bool_compressed;}
  public static function getCompressionLevel(){return self::$int_compression_level;}
 //**** other method *******************************************************
 
 /*************************************************************
  Permet de construire le nom du fichier temporaire.
  Propose un nom par d�faut si aucun param�tre n'est pass�
 
   Param�tres : string : l'identifiant du fichier
   Retour     : string : le nom du fichier 
   
  **************************************************************/ 
 public static function constructFileName($stri_extra_id)
 {
  global $ModName;
  $stri_complete_id=($stri_extra_id!="")?$stri_extra_id:$ModName;
  $stri_complete_id=($stri_complete_id=="")?"PHP_object":$stri_complete_id;
  
  return  $stri_complete_id.".slz";
 }
 
 /*************************************************************
  Permet de construire le chemin dans lequel sont stock�s les fichiers temporaires
 
   Param�tres : aucun
   Retour     : string : le chemin des fichiers temporaires
   
  **************************************************************/ 
 public static function constructUserTempPath()
 {
  $stri_user_id=pnusergetvar("uid"); //bas� sur l'identifiant de l'user mais d�pendant du cms
  //$stri_user_id=$_SERVER["REMOTE_ADDR"];//compl�tement ind�pendant du cms
  $stri_user_temp_path=$_SERVER['DOCUMENT_ROOT'].serialisable::$stri_save_path."/".$stri_user_id;
  @mkdir($stri_user_temp_path,0777,true);
  
  return $stri_user_temp_path;
 }
 
 /*************************************************************
  Permet de sauvegarder un objet s�rialis�
 
   Param�tres : string : l'identifiant de l'objet
   Retour     : bool  : true  => l'objet � bien �t� sauvegard�
                        false => �chec de la sauvegarde
   
  **************************************************************/ 
 public function saveInTemp($stri_extra_id="")
 {

  //serialisable::$bool_compressed=true;//activation de la compression des donn�es
  $stri_user_temp_path=serialisable::constructUserTempPath();
  $stri_slz=serialize($this);//s�rialisation de l'objet  
  $stri_file_name=serialisable::constructFileName($stri_extra_id);
  $obj_writer=new file_reader_writer($stri_user_temp_path."/".$stri_file_name);
  $obj_writer->openFile("w");
  $bool_write=$obj_writer->write($stri_slz);
  $obj_writer->closeFile();
    
  return $bool_write;
 }
 
 /*************************************************************
  Permet de charger un objet s�rialis�
 
   Param�tres : string : l'identifiant de l'objet
   Retour     : obj : l'objet qui � �t� s�rialis�
   
  **************************************************************/    
 public static function loadFromTemp($stri_extra_id="")
 {
   //serialisable::$bool_compressed=true;//activation de la compression des donn�es
   $stri_file_name=serialisable::constructFileName($stri_extra_id);
   $stri_user_temp_path=serialisable::constructUserTempPath();  
   $ob_reader=new file_reader_writer($stri_user_temp_path."/".$stri_file_name);
   $ob_reader->openFile("r");
   $stri_slz=$ob_reader->read();
   $obj=unserialize($stri_slz);
   $ob_reader->closeFile();
   return $obj;
 }
 
  /*************************************************************
   Permet de copier un fichier dans le temp
   Cette m�thode est notement utilis� dans le cas de clonage
   d'objet
 
   Param�tres : string : l'identifiant de l'objet � copier
                string : le nouvel identifiant de la copie
   Retour     : bool : true  : copie r�ussie
                       false : �chec de la copie
   
  **************************************************************/    
 public static function copyFromTemp($stri_extra_id,$stri_new_id)
 {
   
   
   $stri_file_name=serialisable::constructFileName($stri_extra_id);
   $stri_file_copy_name=serialisable::constructFileName($stri_new_id);
   $stri_user_temp_path=serialisable::constructUserTempPath();  
   
   $bool_res=false;
   
   if(!is_file($stri_user_temp_path."/".$stri_file_copy_name))//pour ne copier qu'une seule fois
   {$bool_res=copy($stri_user_temp_path."/".$stri_file_name,$stri_user_temp_path."/".$stri_file_copy_name);}   
   
   return $bool_res;
 }
 
  /*************************************************************
  Permet de supprimer le fichier temporaire
 
   Param�tres : string : l'identifiant de l'objet
   Retour     : bool : true  => fichier supprim�
                       false => la suppression n'a pas pu �tre faite
   
  **************************************************************/    
 public static function purgeTemp($stri_extra_id="")
 {
   //serialisable::$bool_compressed=true;//activation de la compression des donn�es
   $stri_file_name=serialisable::constructFileName($stri_extra_id);
   $stri_user_temp_path=serialisable::constructUserTempPath();
   $stri_file=$stri_user_temp_path."/".$stri_file_name;

   if((is_file($stri_file))&&(strpos($stri_file,"/temp/" )!==false))//on ne supprime que si le fichier existe et s'il se trouve dans un r�pertoire temp
   {return unlink($stri_file);}
   
   return false;
 }
 
  /*************************************************************
  Permet de supprimer tous les fichiers temporaire
 
   Param�tres : aucun
   Retour     : bool : true  => fichier supprim�
                       false => la suppression n'a pas pu �tre faite
   
  **************************************************************/    
 public static function purgeAllTempFile()
 {
  // serialisable::$bool_compressed=true;//activation de la compression des donn�es
   $stri_file_name=serialisable::constructFileName($stri_extra_id);
   $stri_user_temp_path=serialisable::constructUserTempPath();
   
    $obj_dir_reader=new file_reader_writer($stri_user_temp_path);
    $arra_file=$obj_dir_reader->readDirectory();
      
     foreach($arra_file as $stri_file)
     {
      unlink($stri_file);
     }
  
   
   return false;
 }
 
  /*************************************************************
   Permet de faire la s�rialisation en profondeur d'un tableau
   Cette m�thode est r�cursive
   Param�tres : array : le tableau � s�rialiser
   Retour     : string : le tableau s�rialis�
   
  **************************************************************/     
  public function serializeArray($arra_to_slz)
  {
  
     $arra_temp=array();
     foreach($arra_to_slz as $key=>$mixed_value)
     {
      if(is_array($mixed_value))//cas de tableau $arra_to_slz multidimentionnel
      {
        
        $arra_temp[$key]=$this->serializeArray($mixed_value);
      }
      else
      {
      
       $stri_slz=serialize($mixed_value);
       $arra_temp[$key]=$stri_slz;//cas d'une donn�e de type simple ou objet
        
      // $arra_temp[$key]=serialize($mixed_value);//cas d'une donn�e de type simple ou objet
     
        
        if(is_object($mixed_value))
        {
          $mixed_value->arra_sauv=array(); //lib�ration de m�moire          
        }
      }
     }
 
    return serialize($arra_temp);//a ce stade, le tableau que l'on serialize est un tableau � une seule dimension ne contenant que du texte
  }
 
  /*************************************************************
   Permet de faire la d�s�rialisation en profondeur d'un tableau
   Cette m�thode est r�cursive
   Param�tres : array : le tableau � s�rialiser
   Retour     : array : le tableau d�s�rialis�
   
  **************************************************************/     
  public function unserializeArray($arra_to_slz)
  {
     $arra_temp=array();
     foreach($arra_to_slz as $key=>$mixed_value)//pour chaque �l�ment du tableau partiellement d�s�rialis�
     {
      $mixed_unslz=unserialize($mixed_value);//d�s�rialisation de l'�l�ment
      if(is_array($mixed_unslz))//si l'�l�ment est un tableau, on est dans le cas d'un tableau $arra_to_slz multidimentionnel
      {$arra_temp[$key]=$this->unserializeArray($mixed_unslz);}
      else
      {$arra_temp[$key]=$mixed_unslz;}//tous les autres cas, type simple ou objet
     }
     
     return $arra_temp;
  }
  
 
  /*************************************************************
  M�thode appell� automatiquement lors de la s�rialisation
 
   Param�tres : aucun
   Retour     : array : le tableau des attributs s�rialis�s
   
  **************************************************************/     
  public static $int_iteration=0;  
  public function __sleep()
  {   
  //self::$int_iteration++;
  //echo "d�but ".memory_get_usage()." iteration ".serialisable::$int_iteration." classe : ".get_class($this)."<br />";                           
    $arra_attribute=get_object_vars($this);//r�cup�ration de tous les attributs
    $arra_sauvegarde=array();
    
    serialisable::$int_recursif++;//un pas en avant dans la r�cursivit�
    foreach($arra_attribute as $stri_name=>$mixed_attribute)
    { 
      
      if($stri_name=="arra_sauv"){continue;}//on ne traite pas le tableau contenant les donn�es s�rialis�es
       
      if(is_array($mixed_attribute))//cas d'un attribut tableau
      {
        //if($stri_name=="arra_sauv"){echo "skip<br />";continue;}//on ne traite pas le tableau contenant les donn�es s�rialis�es
     
        $arra_sauvegarde[$stri_name]=$this->serializeArray($mixed_attribute);
      }
      else//tous les autres cas
      {
       //echo "sauvegarde de $stri_name<br />";
         
        if($mixed_attribute!="")  //sauvegarde si non vide
        {
         $arra_sauvegarde[$stri_name]=serialize($mixed_attribute);
        }                                                         
         //$arra_sauvegarde[$stri_name]=serialize($mixed_attribute);

        if(is_object($mixed_attribute))
        {
          $mixed_attribute->arra_sauv=array();//lib�ration de m�moire
        }
        
      }
     
      //echo "arra_sauvegarde ".count($arra_sauvegarde)." it�ration ".self::$int_iteration."<br />";
    }
    serialisable::$int_recursif--;//un pas en arri�re dans la r�cursivit�
    //$stri_sauv=implode("@|@", $arra_sauvegarde);//transformation du tableau de sauvegarde en chaine
    $stri_sauv=serialize($arra_sauvegarde);
    $stri_compressed=$stri_sauv;
   
    
    $this->arra_sauv["compressed"]=$stri_compressed;//par d�faut, on met la chaine compress� dans le tableau de retour
    if(serialisable::$int_recursif==0)//si on est pas dans un appel, r�cursif, on prot�ge la chaine s�rialis� pour qu'elle puisse passer en session
    {
       
      if(serialisable::$bool_compressed)
      {      
        $stri_compressed=gzcompress($stri_sauv,self::$int_compression_level);     
      }//compression du tableau
    
      $arra_original=array("'",'"');
      $arra_replace=array("�sc@�","�dc@@�");
      $stri_compressed_corrected=str_replace($arra_original,$arra_replace,$stri_compressed);
      $this->arra_sauv["compressed"]=$stri_compressed_corrected;//�crasement de la valeur par d�faut
                
     
    } 
                      
     //echo "fin ".memory_get_usage()." iteration ".serialisable::$int_iteration."<br />";  
    // self::$int_iteration--;          
    return array('arra_sauv');    
  }
 

 
 /*************************************************************
  M�thode appell�e automatiquement � la d�s�rialisation
 
   Param�tres : aucun
   Retour :   aucun
  
  **************************************************************/     
  public function __wakeup()
  { 
     self::$int_iteration++;
    // echo "d�but ".memory_get_usage()." iteration ".serialisable::$int_iteration."<br />";  
    
    $stri_uncompressed=$this->arra_sauv["compressed"];
    if((serialisable::$int_recursif==0))//si on est pas dans un appel, r�cursif, on prot�ge la chaine s�rialis� pour qu'elle puisse passer en session
    {  
      $arra_original=array("'",'"');
      $arra_replace=array("�sc@�","�dc@@�");
   
      $stri_compressed_corrected=str_replace($arra_replace,$arra_original,$this->arra_sauv["compressed"]);
      $stri_uncompressed=$stri_compressed_corrected;
      
     if(serialisable::$bool_compressed)
     {$stri_uncompressed= gzuncompress($stri_compressed_corrected);}
     unset($stri_compressed_corrected);
  
    }
    
    unset($this->arra_sauv["compressed"]); //unset pour �conomiser la m�moire et permetre la d�s�rialisation d'objet plus gros

    $arra_attribute =unserialize($stri_uncompressed);

    unset($stri_uncompressed); 
   
   
    serialisable::$int_recursif++;//un pas en avant dans la r�cursivit�
    foreach($arra_attribute as $stri_name=>$str_slz_attribute)
    { 
      $mixed_attribute=unserialize($str_slz_attribute);
      if(is_array($mixed_attribute))//cas d'un attribut tableau
      {
       if($stri_name=="arra_sauv"){continue;}//on ne traite pas le tableau contenant les donn�es s�rialis�es
       $this->$stri_name=$this->unserializeArray($mixed_attribute);
      }
      else //pour tous les autres cas
      {$this->$stri_name=$mixed_attribute;}
    }  
                
   
     unset($arra_attribute); 
  
   
    serialisable::$int_recursif--;//un pas en arri�re dans la r�cursivit�
    $this->arra_sauv=array();
      //echo "fin ".memory_get_usage()." iteration ".serialisable::$int_iteration."<br />";  
  }
 
  /*************************************************************
  M�thode appell�e automatiquement � l'appel sur eval suite �
  var_export
 
   Param�tres : aucun
   Retour :   aucun
  
  **************************************************************/     
    public static function __set_state($an_array)
    {
        $obj = new self();
        //$obj->var1 = $an_array['var1'];
        //$obj->var2 = $an_array['var2'];
        return $obj;
    }
}
?>
