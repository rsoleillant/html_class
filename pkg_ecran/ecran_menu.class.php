<?php

/******************************************************************************
  Create Date  : 11/12/2012
  ----------------------------------------------------------------------
  Class name  : ecran_menu
  Version     : 1.0
  Author      : CAYUELA Christophe & ROBERT Romain
  Description : Permet l'affichage du bloc menu

 * ****************************************************************************** */

class ecran_menu {

//**** Attributs ****************************************************************
    protected $bool_site; //Select site visible ou non
    protected $bool_groupe; //Select groupe visible ou non
    protected $bool_open_incid; //Visibilité ou non du bouton ouvrir incident
    protected $obj_site;    //Select site
    protected $obj_groupe;  //Select groupe
    protected $obj_langue; //Select de langue
    protected $nb_groupe;

//**** Methodes *****************************************************************  
//*** constructor *************************************************************

/******************************************************************************
 * Constructeur du loader définissant le sql de base
 * 
 * Parametres : Aucun                      
 * ***************************************************************************** */
function __construct() {
    $this->bool_site = true;
    $this->bool_groupe = true;
    $this->bool_open_incid=true;
    $this->createSelectLangue();
    $this->createSelect();
}

//*** setter ******************************************************************
//*** getter ******************************************************************
    public function getNbGroupe() {
        return $this->nb_groupe;
    }

//*********Enable/Disable ************************************
    public function setEnableSite($mixed_value) {
        $this->bool_site = $mixed_value;
    }

    public function setEnableGroupe($mixed_value) {
        $this->bool_groupe = $mixed_value;
    }

//*****Object***********************************************
/**********************************************************************
 * Permet de créer les Select Site et Groupe
 * 
 * Paramètres : Aucun
 * Retour : Rien
 ************************************************************************/         
  public function createSelect() {
      //Traitement envoie formulaire Site
      if (isset($_POST['SITE_CLIENT'])) {
          $_SESSION['SITE_CLIENT'] = $_POST['SITE_CLIENT'];
      }
      if (isset($_POST['GROUPE_CLIENT'])) {
          $_SESSION['GROUPE_CLIENT'] = $_POST['GROUPE_CLIENT'];
      }
      //Nombre de site par l'utilisateur
      $stri_sql = "SELECT DISTINCT GROUPE FROM SOCIETE WHERE id_societe IN 
  (SELECT id_societe FROM contact_societe WHERE num_contact='" . pnusergetvar('uid') . "') and ETAT=8100 order by groupe asc";
      $obj_querry_select = new querry_select($stri_sql);
      $arra_res2 = $obj_querry_select->execute("assoc");

      //Select Groupe
      $this->obj_groupe = new select("GROUPE_CLIENT", "GROUPE_CLIENT");
      $this->obj_groupe->setStyle("text-align:left;");
      $this->obj_groupe->setId("groupe");
      $this->obj_groupe->setOnchange('envoyerFormulaire($(this));');
      $this->nb_groupe = sizeof($arra_res2);

      if ($this->nb_groupe == 1) {
          $this->obj_groupe->addOption($arra_res2[0]['GROUPE'], str_replace("_", " ", $arra_res2[0]['GROUPE'])."&nbsp;&nbsp;&nbsp;", "images/MaJ_graphique/blank.gif");
          $_SESSION['GROUPE_CLIENT'] = $arra_res2[0]['GROUPE'];
      } else {
          foreach ($arra_res2 as $key => $arra_value) {
              $this->obj_groupe->addOption($arra_value['GROUPE'], str_replace("_", " ", $arra_value['GROUPE'])."&nbsp;&nbsp;&nbsp;", "images/MaJ_graphique/blank.gif");
          }
      }


      //Make Selected the choice    
      $this->obj_groupe->selectOption($_SESSION['GROUPE_CLIENT']);

      if (!isset($_SESSION['GROUPE_CLIENT'])) {
          $_SESSION['GROUPE_CLIENT'] = $this->obj_groupe->getSelectedOptionValue();
      }

      //Nombre de site par l'utilisateur
      $stri_sql = "SELECT DISTINCT SITE FROM SOCIETE WHERE id_societe IN 
  (SELECT id_societe FROM contact_societe WHERE num_contact='" . pnusergetvar('uid') . "') and ETAT=8100 and upper(GROUPE)=upper('" . $_SESSION['GROUPE_CLIENT'] . "')
    ORDER BY SITE";
      $obj_querry_select = new querry_select($stri_sql);
      $arra_res = $obj_querry_select->execute("assoc");

      //Select Site
      //image blank.gif même hauter que les drapeaux pour être aligné avec eux et image vide
      $this->obj_site = new select("SITE_CLIENT", "SITE_CLIENT");
      $this->obj_site->setStyle("text-align:left;width:100%;");
      $this->obj_site->setId("site");
      $this->obj_site->setOnchange('envoyerFormulaire($(this));');
      if (sizeof($arra_res) == 1) {
          $this->obj_site->addOption($arra_res[0]['SITE'], str_replace("_", " ", $arra_res[0]['SITE'])."&nbsp;&nbsp;&nbsp;", "images/MaJ_graphique/blank.gif");
          $_SESSION['SITE_CLIENT'] = $arra_res[0]['SITE'];
      } else {
          $this->obj_site->addOption("TOUS", _TH_MAKE_CHOICE."&nbsp;&nbsp;&nbsp;", "images/MaJ_graphique/blank.gif");

          foreach ($arra_res as $key => $arra_value) {
              $this->obj_site->addOption($arra_value['SITE'], str_replace("_", " ", $arra_value['SITE'])."&nbsp;&nbsp;&nbsp;", "images/MaJ_graphique/blank.gif");
          }
      }

      //Make Selected the choice    
      $this->obj_site->selectOption($_SESSION['SITE_CLIENT']);

      if ($this->obj_site->getSelectedOptionValue() == "TOUS") {
          $_SESSION['SITE_CLIENT'] = "TOUS";
      }
  }

//*** Méthodes d'affichage ****************************************************

/* *****************************************************************************
 * Pour construire l'interface html de recherche
 * 
 * Parametres : aucun 
 * Retour : string : le code html                         
 * ***************************************************************************** */
public function htmlValue() {
    
    //Création menu selon utilisateur sur le modèle du menu bloc
    $ob_menu_arbre = $this->createMenuListExterne();

    //Menu2 ouvrir incidents
    $obj_a_incid = new a("modules.php?op=modload&amp;name=incidentclient&amp;file=index", __OUVRIR_INC);
    $obj_a_incid->setClass("dropdown2");

    $obj_table1 = new table();
    $obj_tr = $obj_table1->addTr();
    $obj_td = $obj_tr->addTd($this->obj_langue);
    if ($this->nb_groupe != 1 && $this->bool_groupe) {
        $obj_td = $obj_tr->addTd($this->obj_groupe);
    }
    if ($this->bool_site) {
        $obj_td = $obj_tr->addTd($this->obj_site);
    }
    $obj_tr->setStyle('height:20px;');
    $obj_table1->setBorder('0');

    //Création table menu   
    $obj_table_menu = new table();
    $obj_tr = $obj_table_menu->addTr();
    $obj_td = $obj_tr->addTd($ob_menu_arbre);
    $obj_td = $obj_tr->addTd($obj_table1->htmlValue());
    $obj_td->setValign('top');
    $obj_td->setAlign('right');
    
    if($this->bool_open_incid)
    {
      $obj_td = $obj_tr->addTd($obj_a_incid->htmlValue());
      $obj_td->setWidth("150px");
    }
    
    $obj_table_menu->setWidth("100%");
    $obj_table_menu->setBorder("0");

    return $obj_table_menu->htmlValue();
}
	/****************************************************************************
	* Pour construire le menu selon si on est en interne ou externe
	*
	* Parametres : aucun 
  * Retour : string : le code html avec la class dropdown  
	**************************************************************************/
	/*public function createMenuList(){
		if(_SUPER_USER_BDD=="m_test")
		{
			return $this->createMenuListInterne();
		}
		if(_SUPER_USER_BDD=="client" || _SUPER_USER_BDD=="call")
		{
			$this->bool_site = true;
			$this->bool_groupe = true;
			$this->bool_open_incid=true;
			return $this->createMenuListExterne();
		}
		return $this->createMenuListInterne();
	
	}*/
 /******************************************************************************
   * Pour construire l'interface menu de liste du Coté Intranet
   * 
   * Parametres : aucun 
   * Retour : string : le code html avec la class dropdown                         
   * ***************************************************************************** */

  public function createMenuListInterne() {
      $stri_sql = "SELECT * FROM mdp_blocks 
    WHERE pn_bid IN (SELECT pn_bid FROM mdp_userblocks WHERE pn_uid='" . pnusergetvar('uid') . "') 
    AND pn_bkey<>'thelang' 
    AND (pn_language='".pnUserGetLang()."' OR pn_language IS NULL) 
    AND pn_active='1' 
    AND pn_position='l'
    AND pn_title NOT LIKE 'CUST_%'
     ORDER BY pn_weight";

      $obj_query = new querry_select($stri_sql);
      $arra_res = $obj_query->execute("assoc");

      $stri_res = '<ul class="dropdown">';

      foreach ($arra_res as $key => $arra_value) {
          
		  $stri_res.='<li><a href="#" >' . constante::constant($arra_value['PN_TITLE']) . '</a><ul>';
          
          // Content
          if (!empty($arra_value['PN_CONTENT'])) {
              //Enlèvre tout ce qui ce trouve avant la première url
              $pos1 = stripos($arra_value['PN_CONTENT'], '"content"');
              $rest = substr($arra_value['PN_CONTENT'], $pos1 + 10);
              $pos2 = stripos($rest, '"');
              $rest = substr($rest, $pos2 + 1);

              //Traitement
              $contentlines = explode("LINESPLIT", $rest); //$arra_value['PN_CONTENT']);
              foreach ($contentlines as $contentline) {
                  list($url, $title, $comment) = explode('|', $contentline);
                  if (pnSecAuthAction(0, "Menublock::", "" . $arra_value['PN_TITLE'] . ":" . $title . ":", ACCESS_READ)) {
                      $url = trim($url);
                      
                      switch ($url[0]) { // Used to allow support for linking to modules with the use of bracket
                          case '[': // old style module link
                                  {
                                  $url = explode(':', substr($url, 1, - 1));
                                  $url = 'modules.php?op=modload&amp;name=' . $url[0] . '&amp;file=' . ((isset($url[1])) ? $url[1] : 'index');
                                  break;
                              }
                          case '{': // new module link 
                              {
                                  $url = explode(':', substr($url, 1, - 1));
                                  $url = 'index.php?module=' . $url[0] . '&amp;func=' . ((isset($url[1])) ? $url[1] : 'main');
                                  break;
                              }
                      }
                      if (!empty($title)) {
					  
                          //Gestion constante PHP
                          $title=constante::constant($title);
                          
                          if (!empty($url)) {
                              $stri_res.="<li><a href=\"$url\" >" . $title . "</a></li>";
                          } else {
                              $stri_res.="<li><a href=\"#\" >" . $title . "</a></li>";
                          }
                      }
                  }
              }
          }
          $stri_res.='</ul></li>';
      }

      //Image déconnexion
      /*$obj_img_deco = new img("images/MaJ_graphique/logout.png");
      $obj_img_deco->setWidth("16");
      $obj_img_deco->setHeight("16"); */

      $stri_res.='</ul>';
      return $stri_res;
  }
  /******************************************************************************
   * Pour construire l'interface menu de liste du côté Extranet
   * 
   * Parametres : aucun 
   * Retour : string : le code html avec la class dropdown                         
   * ***************************************************************************** */

  public function createMenuListExterne() {
      $stri_sql = "SELECT * FROM mdp_blocks 
  WHERE pn_bid IN (SELECT pn_bid FROM mdp_userblocks WHERE pn_uid='" . pnusergetvar('uid') . "') 
  AND pn_bkey<>'thelang' 
  /*AND pn_language='fra'*/
  AND pn_title LIKE 'CUST_%' 
  AND pn_active='1' 
  AND pn_position='l'
   ORDER BY pn_weight";

      $obj_query = new querry_select($stri_sql);
      $arra_res = $obj_query->execute("assoc");

      $stri_res = '<ul class="dropdown">';

      foreach ($arra_res as $key => $arra_value) {
          
          $stri_res.='<li><a href="#" >' . constante::constant($arra_value['PN_TITLE']) . '</a><ul>';

          // Content
          if (!empty($arra_value['PN_CONTENT'])) {
              //Enlèvre tout ce qui ce trouve avant la première url
              $pos1 = stripos($arra_value['PN_CONTENT'], '"content"');
              $rest = substr($arra_value['PN_CONTENT'], $pos1 + 10);
              $pos2 = stripos($rest, '"');
              $rest = substr($rest, $pos2 + 1);

              //Traitement
              $contentlines = explode("LINESPLIT", $rest); //$arra_value['PN_CONTENT']);
              foreach ($contentlines as $contentline) {
                  list($url, $title, $comment) = explode('|', $contentline);
                  if (pnSecAuthAction(0, "Menublock::", "" . $arra_value['PN_TITLE'] . ":" . $title . ":", ACCESS_READ)) {
                      $url = trim($url);
                      
                      switch ($url[0]) { // Used to allow support for linking to modules with the use of bracket
                          case '[': // old style module link
                                  {
                                  $url = explode(':', substr($url, 1, - 1));
                                  $url = 'modules.php?op=modload&amp;name=' . $url[0] . '&amp;file=' . ((isset($url[1])) ? $url[1] : 'index');
                                  break;
                              }
                          case '{': // new module link 
                              {
                                  $url = explode(':', substr($url, 1, - 1));
                                  $url = 'index.php?module=' . $url[0] . '&amp;func=' . ((isset($url[1])) ? $url[1] : 'main');
                                  break;
                              }
                      }
                      if (!empty($title)) {
                          //Gestion constante PHP
                          $title=constante::constant($title);
                                   
                          if (!empty($url)) {
                              $stri_res.="<li><a href=\"$url\" >" . $title. "</a></li>";
                          } else {
                              $stri_res.="<li><a href=\"#\" >" .$title. "</a></li>";
                          }
                      }
                  }
              }
          }
          if ($arra_value['PN_TITLE']=="CUST_CUSTOMER")
          {
            if ($this->LivretMachine()!=NULL)
            {$stri_res.="<li>".$this->LivretMachine()."</li>";}
          }
          $stri_res.='</ul></li>';
      }
      
      //Image déconnexion
      $obj_img_deco = new img("images/MaJ_graphique/logout.png");
      $obj_img_deco->setWidth("16");
      $obj_img_deco->setHeight("16");
      $obj_img_deco->setAlt(__LOG_OUT);
      //Menu déconnexion
      $obj_li_decon = new li("", "");
      $obj_li_a_decon = new a("user.php?module=NS-User&amp;op=logout",$obj_img_deco->htmlValue(),true);
      
      //Afichage comme il faut du td
      if (preg_match("/MSIE/", $_SERVER["HTTP_USER_AGENT"])) //Si IE
      {
        $obj_li_a_decon->setStyle("padding:2px 2px 0px;");
      }
      else //Sinon
      {
        $obj_li_a_decon->setStyle("padding:2px;");
      }
      $obj_li_a_decon->setTitle(__LOG_OUT);
      $obj_li_decon->addContain($obj_li_a_decon->htmlValue());

      $stri_res.=$obj_li_decon->htmlValue();

      $stri_res.='</ul>';
      return $stri_res;
  }
	/******************************************************************************************************
	* Permet de construire le livret machine
	*
	* Parametres : aucun 
    * Retour : string : le code html pour le lien du livret machine
	******************************************************************************************************/
    public function LivretMachine()
    {
      $stri_sql="SELECT DISTINCT num_groupe_site
              FROM lm_livret_machine
              WHERE num_groupe_site IN 
              (
                SELECT id_societe
                FROM contact_societe
                WHERE num_contact='".pnusergetvar("uid")."'
              )";
    $obj_query=new querry_select($stri_sql);
    $arra_res=$obj_query->execute();
    
    $_SESSION['livret_machine']=true;
    if(count($arra_res)==0)//si le livret machine n'est disponible
    {
      $_SESSION['livret_machine']=false;
    }
  
   if(!$_SESSION['livret_machine'])
   {return;}  //on n'affiche pas le block
  
  //affichage d'un lien sur livret machine
  $obj_a=new a("modules.php?op=modload&amp;name=Livret_machine&amp;file=index",_LIVRET_MACHINE);
  
    return $obj_a->htmlValue();
    }
/***************************************************************************
 * Permet de créer le select de langue
 * 
 * Paramètres : Aucun
 * Retour : Rien
 *****************************************************************************/
 public function createSelectLangue()
 {
    //Changement de langue sans changer de page actuelle
    $currentURL = $_SERVER['REQUEST_URI'];
    $pattern = '/\?newlang=.../';
    $currentURL = preg_replace($pattern, '', $currentURL);
    $pattern = '/\newlang=.../';
    $currentURL = pnVarPrepForDisplay(preg_replace($pattern, '', $currentURL));
    $append = "&amp;";
    
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
    //Select Langue avec drapeau utilisant plugin JQuery msDropdown
    $obj_langue = new select("langue", "langue");
    $obj_langue->setStyle("width:100px;text-align:left;");
    $obj_langue->setId("langue");
    $obj_langue->setOnchange("window.location.href=this.value");
    foreach ($langlist as $k=>$v)
    {
      $obj_langue->addOption("$currentURL" . $append . "newlang=$k", "&nbsp;&nbsp;".strtoupper($k), "images/flags/flag-$k.gif");
    }
    $option = $obj_langue->getOption();
    foreach ($option as $key => $arra_value) {
        if (strpos($arra_value->getValue(), pnUserGetLang()) !== false) {
            $arra_value->setSelected(true);
        }
    }
    $this->obj_langue=$obj_langue;
 }     
  

}

?>
