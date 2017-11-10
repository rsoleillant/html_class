<?php

/*******************************************************************************
Create Date : 23/04/2010
 ----------------------------------------------------------------------
 Class name : data_level_manager
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de changer le niveau de visibilité des données
********************************************************************************/

class data_level_manager 
{
 //**** attribute ***********************************************************
  protected static $arra_pnconfig;//Tableau de configuration du user et mdp bdd actuellement utilisé
  protected static $arra_previous_pnconfig;//tableau sur les mdp et user bdd précédemment utilisés
  protected $int_current_level;//Le niveau de visibilité courrant
  protected $int_previous_level;//Le niveau de visibilité précédant
  protected $stri_super_user_bdd;//L'utilisateur de bdd qui voit toutes les données
  private $arra_possesseur_config;//Les paramètres de configuration user et mdp en fonction des possesseurs

 
 //**** constructor ******************************************************** 

  /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe  data_level_manager  
   *                        
   **************************************************************/         
  function __construct() 
  {
   global $pnconfig; //on se base sur le paramètrage global de la bdd
   if(!isset(self::$arra_pnconfig))//si on est dans le cas de la première instanciation
   {
    self::$arra_pnconfig=$pnconfig;
    self::$arra_previous_pnconfig=$pnconfig;
   }
                // echo 'datalevelmanager';
   $this->stri_super_user_bdd=_SUPER_USER_BDD;//définition du super user à partir de la constante correspondante défini dans config/user_bd_choice.tintf.php
   
   //définition des users et mdp bdd
   $arra_possesseur_config[1]=array("dbuname"=>"asis","dbpass"=>"asis");
   $arra_possesseur_config[10]=array("dbuname"=>"savoye","dbpass"=>"savoye");
   $arra_possesseur_config[11]=array("dbuname"=>$this->stri_super_user_bdd,"dbpass"=>$this->stri_super_user_bdd);
   $arra_possesseur_config[20]=array("dbuname"=>"arch","dbpass"=>"arch"); //utilisateur pour l'archivage
   $this->arra_possesseur_config=$arra_possesseur_config;
   
   //correspondance entre user bdd et niveau
   $arra_user_level=array("asis"=>1,"savoye"=>10,$this->stri_super_user_bdd=>11,"arch"=>20);
   $this->int_current_level=$arra_user_level[$pnconfig['dbuname']];
   $this->int_previous_level=$this->int_current_level;
  }   
 
 
 //**** setter *************************************************************
  public function setCurrentLevel($value){$this->int_current_level=$value;}
  public function setPreviousLevel($value){$this->int_previous_level=$value;}

 //**** getter *************************************************************
  public function getCurrentLevel(){return $this->int_current_level;}
  public function getPreviousLevel(){return $this->int_previous_level;}

 //**** other method *******************************************************
  
 /*************************************************************
  Permet d'augmenter le niveau de visibilité des données
 
 Paramètres : aucun
 Retour : aucun
  **************************************************************/     
  public function increaseLevel()
  {
   $this->setLevel(11);
  }
 

 /*************************************************************
  Permet de diminuer le niveau de visibilité des données
 
  Paramètres : aucun
  Retour : aucun    
  **************************************************************/     
  public function decreaseLevel()
  {
   $this->setLevel($this->int_previous_level);//retour au niveau de visibilité précédant
  }
 

 /*************************************************************
  Permet de définir le niveau de visibilité des données en fonction de l'identifiant du possesseur
 
 Paramètres : int possesseur : 1 a-sis, 10 savoye, 11 commun
 Retour : aucun
   
  **************************************************************/     
  public function setLevel($int_possesseur)
  {
   if($this->int_current_level==$int_possesseur)//si on ne change pas de niveau de visibilité, rien à faire
   {return "";}
  
   if(!array_key_exists($int_possesseur,$this->arra_possesseur_config))//si on veux un niveau qui n'existe pas
   {
    trigger_error ("The level $int_possesseur of data visibilty does not exit", E_USER_ERROR  );
    return "";
   }
  
   //sauvegarde du niveau précédant
   self::$arra_previous_pnconfig=self::$arra_pnconfig;
   $this->int_previous_level=$this->int_current_level;
   $this->int_current_level=$int_possesseur; 
   
   //récupération du user et mdp
   $arra_config=$this->arra_possesseur_config[$int_possesseur];
   $stri_dbuname=$arra_config['dbuname'];
   $stri_dbpass=$arra_config['dbpass'];
   
   //actualisation des infos dans la classe
   self::$arra_pnconfig['dbuname']=$stri_dbuname;
   self::$arra_pnconfig['dbpass']=$stri_dbpass;
   
   //changement de connexion à la bdd
   global $pnconfig;
   $pnconfig['dbuname'] = $stri_dbuname;
   $pnconfig['dbpass']  =$stri_dbpass;
   
   //pnDBInit();//réinitialisation de la connexion
    
    global $dbconn;//on récupère l'objet de connexion global
    $dbh = $dbconn->Connect($pnconfig['dbhost'], $pnconfig['dbuname'], $pnconfig['dbpass'], $pnconfig['dbname']);//on se reconnect avec les nouveaux identifiant
    if (!$dbh) die("Error in connexion to database (file : ".__FILE__." line : ".__LINE__);
    
  }

}
?>
