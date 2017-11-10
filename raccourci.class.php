<?php
/**********************************************************************************/
/*  Titre : raccourci.class.php
/*  Auteur : Romain BEAULIEU
/*  Cr�ation : 27/08/2010
/*  Description : Permet de cr�er et d'afficher des raccourcis sur l'appli
/*  H�ritage : aucun
/**********************************************************************************/


class raccourci
{
  /************************ Attributs *****************************************/
  protected $arra_raccourci = Array(); //tableau contenant les param�tres des raccourcis
  static private $int_nb_raccourci;
  
  /************************ Constructeur **************************************/
  public function __construct()
  {
    global $pnconfig;
                                                                                  
    if($pnconfig['dbuname']!="savoye")//savoye ne d�sire pas voir les racourcis
    {
     $this->addRaccourci("http://jama.a-sis.eu/login.req","./images/icone_jama.png","Jama","Jama");
     $this->addRaccourci("http://confluence.a-sis.eu/dashboard.action","./images/icone_jira.png","Confluence","Confluence");
     $this->addRaccourci("http://jira.a-sis.eu/secure/Dashboard.jspa","./images/icone_confluence.png","Jira","Jira");
     $this->addRaccourci("http://isis/","./images/icone_isis.png","Isis","Isis");
    }
  }
  
  
  public function htmlValue()
  /*****************************************************************************
   *  Param�tres :  
   *  Retour :      
   *  Description : Affiche le code html de la classe
  *****************************************************************************/           
  {
    $themeName = $GLOBALS['themeName'];

    $obj_table = new table();
      $obj_table->setClass('html_class raccourci');
    $obj_table->setBorder(0);
    
    $obj_tr = $obj_table->addTr();
      
    foreach($this->arra_raccourci as $arra_one_res)
    {
      //param�trage de l'icone
      $obj_img = new img($arra_one_res["path_img"]);
      $obj_img->setHeight("40");
      $obj_img->setAlt($arra_one_res["alt"]);
      $obj_img->setClass('drop-shadow');

      //pam�trage du lien html
      $obj_a = new a($arra_one_res["url"],$obj_img->htmlValue(),true);
      $obj_a->setTitle($arra_one_res["title"]);
      $obj_a->setTarget("_blank");
      
      $obj_td = $obj_tr->addTd($obj_a->htmlValue());
    }
      
    return $obj_table->htmlValue();
  }
  
  
  public function addRaccourci($stri_url,$stri_path_img,$stri_title,$stri_alt)
  /*****************************************************************************
   *  Param�tres :  $stri_url : le lien du raccourci
   *                $stri_path_img : le chemin de l'image � utiliser
   *                $stri_title : l'infobulle � afficher sur le raccourci      
   *  Retour :      
   *  Description : Ajoute un raccourci
  *****************************************************************************/           
  {
    self::$int_nb_raccourci++;

    $this->arra_raccourci[self::$int_nb_raccourci]["url"] = $stri_url;
    $this->arra_raccourci[self::$int_nb_raccourci]["path_img"] = $stri_path_img;
    $this->arra_raccourci[self::$int_nb_raccourci]["title"] = $stri_title;
    $this->arra_raccourci[self::$int_nb_raccourci]["alt"] = $stri_alt;
  }
  
}//fin de la classe

?>
