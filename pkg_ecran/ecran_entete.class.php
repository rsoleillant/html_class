<?php

/* * *****************************************************************************
  Create Date  : 11/12/2012
  ----------------------------------------------------------------------
  Class name  :ecran_entete
  Version     : 1.0
  Author      : CAYUELA Christophe & ROBERT Romain
  Description : Permet l'affichage du bloc entete

 * ****************************************************************************** */

class ecran_entete {

//**** Attributs ****************************************************************
    protected $bool_title;   //Titre par défaut ou non
    protected $stri_titre;   //String titre
    protected $bool_logo_savoye;  //Booléen pour logo savoye
    protected $bool_logo_firm;  //Booléen pour logo entreprise
    protected $bool_lang;       //Affichage drapeau de langue pour page de login

//**** Methodes *****************************************************************  
//*** constructor *************************************************************

    /*     * *****************************************************************************
     * Constructeur du loader définissant le sql de base
     * 
     * Parametres : String : title
     * Table : pour les icônes
     * String : message d'aide qui safficher si pas de table data
     * Table : Pour les données dans l'écran détails       
     * Retour :                          
     * ***************************************************************************** */
    function __construct($stri_title = "") {
        $this->stri_title = $stri_title;
        $this->bool_title = true;
        $this->bool_logo_savoye = true;
        $this->bool_logo_firm = false;
        $this->bool_lang= false;
    }

//*** setter ******************************************************************
    public function setTitle($mixed_value) {
        $this->stri_title = $mixed_value;
    }

//*** getter ******************************************************************
    public function getTitle() {
        return $this->stri_title;
    }

//*********Enable/Disable ************************************
    public function setEnableTitle($mixed_value) {
        $this->bool_title = $mixed_value;
    }

    public function setEnableLogoSav($mixed_value) {
        $this->bool_logo_savoye = $mixed_value;
    }

    public function setEnableLogoFirm($mixed_value) {
        $this->bool_logo_firm = $mixed_value;
    }
    public function setEnableLang($mixed_value) {
        $this->bool_lang = $mixed_value;
    }

    public function getEnableTitle() {
        return $this->bool_title;
    }

    public function getEnableLogoSav() {
        return $this->bool_logo_savoye;
    }

    public function getEnableLogoFirm() {
        return $this->bool_logo_firm;
    }
    public function getEnableLang() {
        return $this->bool_lang;
    }

//*** Méthodes d'affichage ****************************************************
/******************************************************************************
     * Pour chercher le logo de la societe du contact
     * 
     * Parametres : aucun 
     * Retour : bool : true si trouvé, false sinon                        
     * ***************************************************************************** */
    public static function CreateLogo() {
        //Select pour obtenir le sexe du client (M ou F)
        $stri_sql = "SELECT raison_sociale,id_societe,logo FROM SOCIETE WHERE id_societe IN 
    (SELECT id_societe FROM contact_societe WHERE num_contact='".pnusergetvar('uid')."') 
                AND etat= 8100
                AND groupe='".$_SESSION['GROUPE_CLIENT']."' ";
        if($_SESSION['SITE_CLIENT']!="TOUS")
          {$stri_sql .=" AND site='".$_SESSION['SITE_CLIENT']."'";}        

        $obj_query_load = new querry_select($stri_sql);
        $arra_res_id = $obj_query_load->execute("assoc"); 
        
        $arra_res_id = $arra_res_id[0]['LOGO']; //Besoin que d'un id pour le logo
        
        if ($arra_res_id == NULL) {
            $stri_link1="modules/SEA/Partage_hotline/logo_entreprise/logo_absent_128x128.png"; //Extranet
            $stri_link2="modules/Hotline/Partage_hotline/logo_entreprise/logo_absent_128x128.png"; //Intranet
            
            $stri_link=(file_exists($stri_link1))?$stri_link1:$stri_link2;
            return $stri_link;
        } else {
            
            $url1="modules/SEA/Partage_hotline/logo_entreprise/".$arra_res_id; //Extranet
            $url2="modules/Hotline/Partage_hotline/logo_entreprise/".$arra_res_id; //Intranet
            
            $url=(file_exists($url1))?$url1:$url2;
            
            return $url;
        }
    }

    /*     * *****************************************************************************
     * Pour obtenir la raison sociale, pour affichage pour titre logo
     * 
     * Parametres : aucun 
     * Retour : string : la raison sociale avec espace à la place de '_'                       
     * ***************************************************************************** */

    public function getRaisonSociale() {
        $stri_sql = "SELECT DISTINCT raison_sociale FROM SOCIETE WHERE id_societe IN 
    (SELECT id_societe FROM contact_societe WHERE num_contact='".pnusergetvar('uid')."') 
    and etat= 8100";
        $obj_query_load = new querry_select($stri_sql);
        $arra_res_id = $obj_query_load->execute("assoc");
        $arra_res_id = $arra_res_id[0]['RAISON_SOCIALE']; //Besoin que d'un id pour le logo

        return str_replace('_', ' ', $arra_res_id);
    }
  /****************************************************************************
   *Pour affichage des drapeaux de langue sur la page login
   *
   *Paramètres : aucun
   *Retour : table de l'affichage
   ***************************************************************************/                        
   public function displayLang()
   {
      //Changement de langue sans changer de page actuelle
      $currentURL = $_SERVER['REQUEST_URI'];
      $pattern = '/\?newlang=.../';
      $currentURL = preg_replace($pattern, '', $currentURL);
      $pattern = '/\newlang=.../';
      $currentURL = pnVarPrepForDisplay(preg_replace($pattern, '', $currentURL));
      $append = "?";
      
      $lang = languagelist();
      $handle = opendir('language');
      while ($f = readdir($handle))
      {
         if (is_dir("language/$f") && (!empty($lang[$f])))
         {
             $langlist[$f] = $lang[$f];
             $sel_lang[$f] = '';
         }
      }
      
      asort($langlist);
      
      //Objet du table
      $obj_font_lang= new font(_YOUR_LANG,true);
      $obj_font_lang->setStyle("color:#FFFFFF;text-shadow:0px 0px 0px #FFF;");
      $obj_font_lang->setSize(1);
      
      //Table
      $obj_tabl_lang= new table();
      $obj_tabl_lang->setWidth("100%");
      
      $obj_tr=$obj_tabl_lang->addTr();
        $obj_td=$obj_tr->addTd($obj_font_lang);
        //$obj_td->setColspan(sizeof($langlist));
        $obj_td->setAlign("center");
      
      $obj_tr=$obj_tabl_lang->addTr();      
      foreach ($langlist as $k=>$v)
      {
        $imgsize = @getimagesize("images/flags/flag-$k.png");
        $content.= "<a href=\"$currentURL".$append."newlang=$k\"><img src=\"images/flags/flag-$k.png\" border=\"0\" alt=\"$lang[$k]\" hspace=\"3\" vspace=\"3\" $imgsize[3]></a>";
        
      }
      $obj_td=$obj_tr->addTd($content);
      $obj_td->setAlign("center");
      
      return $obj_tabl_lang->htmlValue();
   }
  /********************************************************************
   * Pour construire l'interface html de recherche
   * 
   * Parametres : aucun 
   * Retour : string : le code html                         
   * *************************************************************************/

    public function htmlValue() {
    
        //Tableau Entête
        //- objet de la table_entete 
        $arra_module = pnModGetInfo(pnModGetIDFromName($_GET['name']));
        //Si module visuclient affichage site ou groupe(si plusieurs site)
        if ($this->bool_title) {
            if ($arra_module['displayname'] == _DISPLAY_MOD_VISUCLIENT) {
                if ($_SESSION['SITE_CLIENT'] != "TOUS") {
                    $affichage = str_replace("_", " ", $_SESSION['SITE_CLIENT']);
                } else {
                    $affichage = _ALL_SITE . "" . str_replace("_", " ", $_SESSION['GROUPE_CLIENT']);
                } //Afichage sans underscore mais avec espace
                $obj_font_title0 = new font($arra_module['displayname'] . ' ' . $affichage, true);
                $obj_font_title0->setStyle('font-size:27px');
            } else {
                if ($arra_module['displayname']!=""){
                $obj_font_title0 = new font($arra_module['displayname'] . ' V ' . $arra_module['version'], true);
                $obj_font_title0->setStyle('font-size:27px');
                }
            }
        } else {
            $obj_font_title0 = new font($this->stri_title, true);
            $obj_font_title0->setStyle('font-size:27px');
        }

        //LOGO SAVOYELINE
        if ($_SERVER['SERVER_ADDR'] == '10.10.100.98') {
            $obj_img_Savoye = new img("images/MaJ_graphique/logo_a_sis_test.gif");
            $obj_img_Savoye->setStyle("cursor:pointer;padding:2px;");
            $obj_img_Savoye->setBorder("0");
            $obj_img_Savoye->setHeight("60px");
            $obj_img_Savoye->setWidth("150px");  
            $obj_img_Savoye->setTitle("Savoyeline");
            $obj_img_Savoye->setAlt("Savoyeline");
            $obj_a_Savoye = new a("http://" . $_SERVER['SERVER_NAME'] . "/", $obj_img_Savoye->htmlValue(), true);
            $obj_a_Savoye->setTarget("http://" . $_SERVER['SERVER_NAME'] . "/");
        } else {
            $obj_img_Savoye = new img("images/MaJ_graphique/logo_a_sis_gf.png");
            $obj_img_Savoye->setStyle("cursor:pointer;padding:2px;");
            $obj_img_Savoye->setBorder("0");
            $obj_img_Savoye->setHeight("60px");
            $obj_img_Savoye->setWidth("150px");
            $obj_img_Savoye->setTitle("Savoyeline");
            $obj_img_Savoye->setAlt("Savoyeline");
            $obj_a_Savoye = new a("/index.php", $obj_img_Savoye->htmlValue(), true);
        }

        //LOGO ENTREPRISE
        $obj_img_logo = new img($this->CreateLogo());
        //$obj_img_logo->setStyle("cursor:pointer;padding:2px;padding-right:10px;");
        $obj_img_logo->setStyle("padding:2px;padding-right:10px;");
        $obj_img_logo->setBorder("0");
        $obj_img_logo->setTitle($this->getRaisonSociale());
        $obj_img_logo->setAlt($this->getRaisonSociale());
        $obj_img_logo->setHeight("60px");
        $obj_img_logo->setWidth("165px");
        //$obj_a_logo = new a("http://" . $_SERVER['SERVER_NAME'] . "/", $obj_img_logo->htmlValue(), true);
        //$obj_a_logo->setTarget("http://" . $_SERVER['SERVER_NAME'] . "/");

        //Création table    
        $obj_table_entete = new table();
        $obj_tr = $obj_table_entete->addTr();
        
        //Bascule
        $obj_a_Savoye=($this->bool_logo_savoye)?$obj_a_Savoye:null;

        $obj_td = $obj_tr->addTd($obj_a_Savoye);
        $obj_td->setWidth("150px");
        $obj_td->setRowspan(2);
        $obj_td->setAlign('left');

        $obj_td = $obj_tr->addTd($obj_font_title0);
        $obj_td->setAlign('center');
        $obj_td->setStyle('height : 64px;');
            
        //Bascule
        $obj_img_logo=($this->bool_logo_firm)?$obj_img_logo:null;

        $obj_td = $obj_tr->addTd($obj_img_logo);
        $obj_td->setWidth("150px");
        $obj_td->setRowspan(2);
        $obj_td->setAlign('right');

        if($this->bool_lang)
        {
          $obj_td = $obj_tr->addTd($this->displayLang());
          $obj_td->setWidth("180px");
          $obj_td->setRowspan(2);
          $obj_td->setAlign('right');
        }
        else
        {$obj_td = $obj_tr->addTd("");}

            
        $obj_table_entete->setClass("titre0");
        $obj_table_entete->setBorder("0");
        $obj_table_entete->setWidth("100%");

        return $obj_table_entete->htmlValue();
    }

}

?>
