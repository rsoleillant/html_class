<?php

/*******************************************************************************
Create Date : 11/09/2009
 ----------------------------------------------------------------------
 Class name : serialisable
 Version : 2.0
 Author : R�my Soleillant
 Description : Permet de rendre un objet s�rialisable avec compression des donn�es
               La sauvegarde est bas�e sur un enregistrement en BDD
********************************************************************************/

class serialisable 
{
 //**** attribute ***********************************************************
  private static $stri_save_path="temp";//Le chemin o� sauvegarder les fichiers temporaire
  private static $int_recursif=0;//Permet de savoir si on est dans un appel r�cursif de m�thode ou si on est dans le premier appel
  protected static $bool_compressed=false;//si on doit compresser les donn�es
  public $arra_sauv;//Le tableau contenant les donn�es s�rialis�es
  
 //**** constructor ******************************************************** 
 
 //**** setter *************************************************************
 
 //**** getter *************************************************************
 
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
  serialisable::$bool_compressed=true;//activation de la compression des donn�es
  $stri_user_temp_path=serialisable::constructUserTempPath();
  $stri_slz=serialize($this);//s�rialisation de l'objet
  $stri_file_name=serialisable::constructFileName($stri_extra_id);
  
  
  //r�cup�ration des diff�rentes donn�es � ins�rer dans la base
  $stri_nom_fichier=$stri_user_temp_path."/".$stri_file_name;
  $int_num_user=pnUserGetVar("uid");
  
  //suppression de la base des �ventuels "fichiers"
  $stri_delete="delete from fichier_temp where num_user=$int_num_user and nom_fichier='$stri_nom_fichier'";
  $obj_query_delete=new querry_select($stri_delete);
  $obj_query_delete->execute();
  
  //d�coupe de l'objet s�rialis� en plusieurs morceaux
  $int_start=0;
  $int_length=3500;
  $stri_contenu=substr($stri_slz,$int_start,$int_length);
  $int_num_partie=1;
  while($stri_contenu!==false)
  {
   $query_insert=new querry_insert("fichier_temp");
    $query_insert->addField("num_user",$int_num_user);
    $query_insert->addField("nom_fichier",$stri_nom_fichier);
    $query_insert->addField("num_partie",$int_num_partie);
    $query_insert->addField("contenu",$stri_contenu);
    
   
    $bool_ok=$query_insert->execute();
    $int_num_partie++;
    $int_start+=$int_length;
    $stri_contenu=substr($stri_slz,$int_start,$int_length);
  }
  
    $obj_tracer=new tracer(dirname(__FILE__)."/debug.txt");
   $obj_tracer->trace(var_export("� la sauvegarde <br />".$stri_slz,true));
     
  /*   
  
  $obj_writer=new file_reader_writer($stri_user_temp_path."/".$stri_file_name);
  $obj_writer->openFile("w");
  $bool_write=$obj_writer->write($stri_slz);
  $obj_writer->closeFile();*/
  
  return $bool_ok;
 }
 
 /*************************************************************
  Permet de charger un objet s�rialis�
 
   Param�tres : string : l'identifiant de l'objet
   Retour     : obj : l'objet qui � �t� s�rialis�
   
  **************************************************************/    
 public static function loadFromTemp($stri_extra_id="")
 {
   serialisable::$bool_compressed=true;//activation de la compression des donn�es
   $stri_file_name=serialisable::constructFileName($stri_extra_id);
   $stri_user_temp_path=serialisable::constructUserTempPath();  
    
   //r�cup�ration des diff�rentes donn�es � ins�rer dans la base
   $stri_nom_fichier=$stri_user_temp_path."/".$stri_file_name;
   $int_num_user=pnUserGetVar("uid"); 
   
   $stri_sql="select contenu
              from fichier_temp
              where num_user=$int_num_user
                    and nom_fichier='$stri_nom_fichier'
              order by num_partie";
   $obj_query_select=new querry_select($stri_sql);
   $arra_res=$obj_query_select->execute();
   $stri_slz="";
   foreach($arra_res as $arra_one_res)
   {$stri_slz.=$arra_one_res[0];}
    
    
   
   
   /*$ob_reader=new file_reader_writer($stri_user_temp_path."/".$stri_file_name);
   $ob_reader->openFile("r");
   $stri_slz=$ob_reader->read();*/
   
   $obj=unserialize($stri_slz);
  
   //$obj_tracer->trace(var_export($obj,true));
   return $obj;
 }
 
  /*************************************************************
  Permet de supprimer le fichier temporaire
 
   Param�tres : string : l'identifiant de l'objet
   Retour     : bool : true  => fichier supprim�
                       false => la suppression n'a pas pu �tre faite
   
  **************************************************************/    
 public static function purgeTemp($stri_extra_id="")
 {
   serialisable::$bool_compressed=true;//activation de la compression des donn�es
   $stri_file_name=serialisable::constructFileName($stri_extra_id);
   $stri_user_temp_path=serialisable::constructUserTempPath();

   /*if((is_file($stri_file))&&(strpos($stri_file,"/temp/" )!==false))//on ne supprime que si le fichier existe et s'il se trouve dans un r�pertoire temp
   {*/
     //r�cup�ration des diff�rentes donn�es pour construire la clef de suppression
   $stri_nom_fichier=$stri_user_temp_path."/".$stri_file_name;
   $int_num_user=pnUserGetVar("uid"); 
   
   $stri_sql="select contenu
              from fichier_temp
              where num_user=$int_num_user
                    and nom_fichier='$stri_nom_fichier'
              order by num_partie";
   $obj_query_select=new querry_select($stri_sql);
   return true;
    //return unlink($stri_file);
   //}
   
   //return false;
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
  public function __sleep()
  {
    $arra_attribute=get_object_vars($this);//r�cup�ration de tous les attributs
    $arra_sauvegarde=array();
    
    serialisable::$int_recursif++;//un pas en avant dans la r�cursivit�
    foreach($arra_attribute as $stri_name=>$mixed_attribute)
    {
      if(is_array($mixed_attribute))//cas d'un attribut tableau
      {
        if($stri_name=="arra_sauv"){continue;}//on ne traite pas le tableau contenant les donn�es s�rialis�es
        $arra_sauvegarde[$stri_name]=$this->serializeArray($mixed_attribute);
      }
      else//tous les autres cas
      {
        $arra_sauvegarde[$stri_name]=serialize($mixed_attribute);
      }
    }
    serialisable::$int_recursif--;//un pas en arri�re dans la r�cursivit�
    //$stri_sauv=implode("@|@", $arra_sauvegarde);//transformation du tableau de sauvegarde en chaine
    $stri_sauv=serialize($arra_sauvegarde);
    $stri_compressed=$stri_sauv;
   
    
    $this->arra_sauv["compressed"]=$stri_compressed;//par d�faut, on met la chaine compress� dans le tableau de retour
    if(serialisable::$int_recursif==0)//si on est pas dans un appel, r�cursif, on prot�ge la chaine s�rialis� pour qu'elle puisse passer en session
    {
      if(serialisable::$bool_compressed)
      {$stri_compressed=gzcompress($stri_sauv);}//compression du tableau
    
      /*$arra_original=array("'",'"');
      $arra_replace=array("�@�","�@@�");
      $stri_compressed_corrected=str_replace($arra_original,$arra_replace,$stri_compressed);
      $this->arra_sauv["compressed"]=$stri_compressed_corrected;//�crasement de la valeur par d�faut*/
      $this->arra_sauv["compressed"]=$stri_compressed;
    } 
     
    return array('arra_sauv');    
  }
 

 
 /*************************************************************
  M�thode appell�e automatiquement � la d�s�rialisation
 
   Param�tres : aucun
   Retour :   aucun
  
  **************************************************************/     
  public function __wakeup()
  {
    $stri_uncompressed=$this->arra_sauv["compressed"];
    if((serialisable::$int_recursif==0))//si on est pas dans un appel, r�cursif, on prot�ge la chaine s�rialis� pour qu'elle puisse passer en session
    {  
      /*$arra_original=array("'",'"');
      $arra_replace=array("�@�","�@@�");
   
      $stri_compressed_corrected=str_replace($arra_replace,$arra_original,$this->arra_sauv["compressed"]);
      $stri_uncompressed=$stri_compressed_corrected;*/
      
      $stri_compressed_corrected=$this->arra_sauv["compressed"];
     if(serialisable::$bool_compressed)
     {$stri_uncompressed= gzuncompress($stri_compressed_corrected);}
    }
    
    
    //$arra_sauvegarde=explode("@|@", $stri_uncompressed);
    $arra_sauvegarde=unserialize($stri_uncompressed);
    $arra_attribute=$arra_sauvegarde;//r�cup�ration de tous les attributs
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
    serialisable::$int_recursif--;//un pas en arri�re dans la r�cursivit�
    $this->arra_sauv=array();
  }
 

}
?>
