<?php

/*******************************************************************************
Create Date : 01/01/2009
 ----------------------------------------------------------------------
 Class name : debug_scanner
 Version : 1.0
 Author : Rémy Soleillant
 Description : 
********************************************************************************/

class debug_scanner extends serialisable
{
 //**** attribute ***********************************************************
  protected $arra_reference=array();//Tableau contenant les temps d'accès de référence aux différents fichiers
  protected $arra_fichier=array();//Tableaux des différents fichiers scannés avec leur date de dernier accès
  protected $arra_black_list=array();//Tableaux contenant la liste noire des répertoire à ne pas analyser
 //**** constructor ******************************************************** 

  /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe  debug_scanner  
   *                        
   **************************************************************/         
  function __construct() 
  {
   
  }   
 
 //**** setter *************************************************************
  public function setReference($value){$this->arra_reference=$value;}
  public function setFichier($value){$this->arra_fichier=$value;}
  public function setBlackList($value){$this->arra_black_list=$value;}

 //**** getter *************************************************************
  public function getReference(){return $this->arra_reference;}
  public function getFichier(){return $this->arra_fichier;}
  public function getBlackList(){return $this->arra_black_list;}

 //**** other method *******************************************************
  /*************************************************************
  Permet de scanner un dossier et de regarder les temps d'accès des différentes fichiers
 
 Paramètres : string : le chemin du répertoire à scanner
 Retour : array : tableau des sous répertoire rencontrés
  
  **************************************************************/     
  public function scannerDossier($stri_path)
  {
    $MyDirectory = opendir($stri_path) or die('Erreur dossier '.$stri_path.' non trouvé');
    $arra_sous_repertoire=array();
  	while($Entry = @readdir($MyDirectory)) 
    {
     $bool_ignore_parent=(($Entry == '.') || ($Entry == '..'));
     $bool_presence_liste=isset($this->arra_black_list[$stri_path.'/'.$Entry]);
     $bool_php=(substr($Entry, -4)==".php");
     $bool_directory=is_dir($stri_path.'/'.$Entry);
    // echo "extension ".substr($Entry, -4);
     $bool_ignore=$bool_ignore_parent||$bool_presence_liste;
     if(!$bool_ignore)
     {
       $arra_stat=stat($stri_path.'/'.$Entry);
       if($bool_directory)
       {$this->scannerDossier($stri_path.'/'.$Entry);}
       else
       {
        if($bool_php)
        {$this->arra_fichier[$stri_path.'/'.$Entry]= $arra_stat['atime'];}
       }
     }
     
   /* 
  	 if($Entry != '.' && $Entry != '..')
     {
  	  if(!isset($this->arra_black_list[$stri_path.'/'.$Entry]))//si le répertoire n'est pas dans la liste noire
  	  {
    	  $arra_stat=stat($stri_path.'/'.$Entry);
    		if(is_dir($stri_path.'/'.$Entry)) 
        {            
         //$arra_sous_repertoire[$stri_path.'/'.$Entry]= $arra_stat['atime'];          
    		 $this->scannerDossier($stri_path.'/'.$Entry);               
    		}
    		else 
        {           
          $this->arra_fichier[$stri_path.'/'.$Entry]= $arra_stat['atime'];
        }
  	   }
     }*/
    }
  }
 

 /*************************************************************
  Permet de lancer le scan complète à partir d'un point de départ
 
  Paramètres : string : le dossier à scanner
  Retour : array : tableau des fichiers qui ont été scannés avec leur date de dernier accès
  
  **************************************************************/     
  public function lancerScan($stri_path)
  {
    $obj_ds=self::loadFromTemp("debug_scanner");
    if(is_object($obj_ds))
    {
     $this->arra_reference=$obj_ds->getReference();
     //$this->arra_black_list=$obj_ds->getBlackList();
    }
    
    $this->scannerDossier($stri_path);
    
    $arra_access=array_diff ($this->arra_fichier,$this->arra_reference);//tous les fichiers qui ont été accédés
    asort($arra_access);
  
   $this->arra_reference=$this->arra_fichier;
   
   $this->saveInTemp("debug_scanner");
   return $arra_access;
  }
 
 /*
 public function __sleep()
 {
  $this->arra_fichier=array();
  parent::__sleep();
 }*/
  public function construireBandeExecution()
  {
   global $arra_bande_date,$arra_bande_script;
   
   $obj_table_be=new table();
    $obj_tr_total=$obj_table_be->addTr();
   $int_nb_date=count($arra_bande_date);
   $arra_pile_script=array();
    for($i=1;$i<$int_nb_date;$i++)
    {
      if($arra_bande_script[$i-1]!=$arra_pile_script[count($arra_pile_script)-1])//si le script courant est différent de la fin de la pile
     {$arra_pile_script[]=$arra_bande_script[$i-1];//on empile
     }
     else
     {
      $depile=array_pop($arra_pile_script);//on dépile le dernier élément
     }
     
     $stri_script=$arra_pile_script[count($arra_pile_script)-1];//le script est le dernier élément de la pile
     
     $float_time=$arra_bande_date[$i]-$arra_bande_date[$i-1];
     $arra_temps[]=$float_time;
     //echo "temps $i script ".$arra_script[$i-1].":".$float_time."<br />";
     
     $obj_tr=$obj_table_be->addTr();
      $obj_tr->addTd(dirname($stri_script));
      $obj_tr->addTd(basename($stri_script));
      $obj_tr->addTd($float_time);
      
     $obj_tr->setHeight($float_time*200);
    }
    
    $int_total=array_sum($arra_temps);
    
    $obj_td=$obj_tr_total->addTd("Total : ".$int_total);
      $obj_td->setColspan(3);
      $obj_td->setAlign("right");
    
    return $obj_table_be->htmlValue();
  }
  
  
  /*************************************************************
  Permet de voir la trace de debug d'où je viens
 
  Paramètres : aucun
  Retour : l'endroit d'où je viens
  
  **************************************************************/     
  public static function jeViensDou()
  {
    $arra_trace=debug_backtrace();
    array_shift($arra_trace);//on enlève le premier élément qui correspond à la méthode actuelle
    array_shift($arra_trace);//on enlève le deuxième élément qui correspond à la méthode appellante d'où est lancé le debug
    
    $arra_element= array_shift($arra_trace);//on est sur l'élément qui nous intéresse
   //on ne garde que les infos principales
    $arra_res['file']=$arra_element['file'];
    $arra_res['line']=$arra_element['line'];
    $arra_res['function']=$arra_element['function'];
    $arra_res['class']=$arra_element['class'];
    
    return $arra_res;
  }
}
?>
