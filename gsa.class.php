<?php

/* * *****************************************************************************
  Create Date : 02/07/08
  ----------------------------------------------------------------------
  Class name : GSA
  Version : 1.0
  Author : Rémi GAREL
  Description : créé les select GROUPE - SITE - APPLICATION avec ajax
  ------->ATTENTION DE NE PAS OUBLIER LES FICHIERS ajaxSite & ajaxAppli si on déplace celui-ci (cf modules/Contrats)
  -------> Et fonctionsJS_GSA (dossier parent)
 * ****************************************************************************** */

class GSA {

    // attribut--------------------------------------------------------
    protected $obj_formulaire;
    protected $obj_btn;
    protected $obj_select_g;
    protected $obj_select_s;
    protected $obj_select_a;
    protected $obj_div_s;
    protected $obj_div_a;
    protected $obj_js;

    // constructeur-----------------------------------------------------
    //Le mode sans arguments permet d'avoir le formulaire GROUPE - SITE - APPLICATION fonctionnel
    //Si l'argument mode est renseigner, l'objet met en place les éléments G - S - A mais le formulaire est à créer par le "demandeur" --> l'argument mode doit contenir l'URL de destination du formulaire !
    //L'affichage des éléments est désactivable grâce au 2eme argument, uniquement pour le mode où il n'y a pas de création de formulaire ! --> appelle : new GSA ("",false);  --> ce mode permet de gérer l'affichage des selects en utilisant les getters 
    function __construct($mode = "", $affichage = true, $path_js = "") {
        //permet de charger un fichier different
        if (is_null($path_js)) {
            $this->insererJS();
        } else {
            $this->loadJS($path_js);
        }
        $this->creerSelects();
        $this->creerDivs();
        if ($mode == "") {
            if ($affichage) {
                $this->afficher();
            }
        } else {
            $this->creerFormulaire($mode);
            $this->afficherFormulaire();
        }
    }

    //getter---------------------------------------------------------

    function getObjFormulaire() {
        return $this->obj_formulaire;
    }

    function getObjBtn() {
        return $this->obj_btn;
    }

    function getObjSelectG() {
        return $this->obj_select_g;
    }

    function getObjSelectS() {
        return $this->obj_select_s;
    }

    function getObjSelectA() {
        return $this->obj_select_a;
    }

    function getObjDivS() {
        return $this->obj_div_s;
    }

    function getObjDivA() {
        return $this->obj_div_a;
    }

    function getObjJS() {
        return $this->obj_js;
    }

    //setter-----------------------------------------------------------

    function setObjFormulaire($param) {
        $this->obj_formulaire = $param;
    }

    function setObjBtn($param) {
        $this->obj_btn = $param;
    }

    function setObjSelectG($param) {
        $this->obj_select_g = $param;
    }

    function setObjSelectS($param) {
        $this->obj_select_s = $param;
    }

    function setObjSelectA($param) {
        $this->obj_select_a = $param;
    }

    function setObjDivS($param) {
        $this->obj_div_s = $param;
    }

    function setObjDivA($param) {
        $this->obj_div_a = $param;
    }

    function setObjJS($param) {
        $this->obj_js = $param;
    }

    // fonction----------------------------------------------------------
    //Cette fonction insert le javascript permettant de faire fonctionner l'ajax
    private function insererJS() {
        $this->obj_js = new javascripter();
        $this->obj_js->addFile("includes/classes/html_class/fonctionJS_GSA.js");
        echo $this->obj_js->javascriptValue();
    }

    private function loadJS($path_js) {
        $this->obj_js = new javascripter();
        $this->obj_js->addFile($path_js);
        echo $this->obj_js->javascriptValue();
    }

    private function creerSelects() {
        //Select du groupe 
        $this->obj_select_g = new select();
        $this->obj_select_g->setStyle("width:300px");
        $this->obj_select_g->setId('groupe');
        $this->obj_select_g->setName('groupe');
        $this->obj_select_g->setClass('select_groupe');
        $this->obj_select_g->setOnChange('change($(this))');
        $this->obj_select_g->addOption(-1, "Aucun");
        $obj_req_g = new querry_select("SELECT DISTINCT groupe FROM societe  ORDER BY groupe");
        $this->obj_select_g->makeQuerryToSelect($obj_req_g, 0, 0);

        if (isset($_POST['groupe'])) {
            //Si le groupe est définit ou le remet en place
            $this->obj_select_g->selectOption($_POST['groupe']);
        }

        //Select du site 
        $this->obj_select_s = new select();
        $this->obj_select_s->setStyle("width:250px");
        $this->obj_select_s->setId('site');
        $this->obj_select_s->setName('site');
        $this->obj_select_s->setClass('select_site');
        $this->obj_select_s->setOnChange('changeSite($(this))');
        if (isset($_POST['groupe'])) {
            // Si le groupe est défini il y a forcément un site associé sélectionné qu'on remet également en place
            //$this->obj_select_s->addOption(" ",_MAKE_CHOICE);
            $obj_req_g = new querry_select("SELECT UNIQUE site FROM societe WHERE groupe='" . $_POST["groupe"] . "'  ORDER BY site");
            $this->obj_select_s->makeQuerryToSelect($obj_req_g, 0, 0);
            $this->obj_select_s->selectOption($_POST['site']);
            //$this->obj_select_s->selectOption(" ");
        } else {
            $this->obj_select_s->addOption(-1, "Choisir un groupe");
        }

        //Select de l'application
        $this->obj_select_a = new select();
        $this->obj_select_a->setStyle("width:200px");
        $this->obj_select_a->setId('application');
        $this->obj_select_a->setClass('select_application');
        $this->obj_select_a->setName('application');
        if (isset($_POST['groupe'])) {
            //De la meme manière que site on remet en place..
            $obj_req_g = new querry_select("SELECT UNIQUE application FROM societe WHERE site='" . $_POST["site"] . "' AND groupe ='" . $_POST["groupe"] . "' ORDER BY application");
            $this->obj_select_a->makeQuerryToSelect($obj_req_g, 0, 0);
            $this->obj_select_a->selectOption($_POST['application']);
        } else {
            $this->obj_select_a->addOption(-1, "Choisir un groupe");
        }
    }

    //On créé les divs qui permettrons à ajax de remplacer les select lors d'une sélection
    private function creerDivs() {

        //Div contenant le select du site
        $this->obj_div_s = new div("div_site", $this->obj_select_s->htmlValue());
        $this->obj_div_s->setClass("div_site");
        $this->obj_div_s->setStyle('display:inline');

        //Div contenant le select de l'application
        $this->obj_div_a = new div("appli", $this->obj_select_a->htmlValue());
        $this->obj_div_a->setClass("div_appli");
        $this->obj_div_a->setStyle('display:inline');
    }

    // la fonction qui permet l'affichage des selects 
    public function afficher() {

        echo _GROUPE;
        echo $this->obj_select_g->htmlValue();

        echo _SIT;

        echo $this->obj_div_s->htmlValue();

        echo _APPLI;
        echo $this->obj_div_a->htmlValue();
    }

    //Cette fonction créé le formulaire et son bouton de validation 
    private function creerFormulaire($mode) {

        $this->obj_formulaire = new form($mode, "POST");
        $this->obj_formulaire->setName('formulaireGSA');
        $this->obj_btn = new submit();
    }

    //Cette fonction affiche l'ensemble du formulaire utilisable directement ! 
    private function afficherFormulaire() {


        openTable();
        echo $this->obj_formulaire->getStartBalise();
        $this->afficher();
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $this->obj_btn->htmlValue();
        echo $this->obj_formulaire->getEndBalise();
        closeTable();
    }

    // Fonction permettant de remettre les valeurs à l'identique lorsqu'on a fait appelle au formulaire
    //@param : les 3 parametres sont, dans l'ordre, la clé  GROUPE - SITE - APPLICATION qu'on souhaite conserver

    public function insererValeurs($gpe, $site, $appli) {



        //Select du groupe ---> on est obligé de le créer à nouveau afin de la vider ! 
        $this->obj_select_g = new select();
        $this->obj_select_g->setStyle("width:350px");
        $this->obj_select_g->setId('groupe');
        $this->obj_select_g->setName('groupe');
        $this->obj_select_g->setClass('select_groupe');
        $this->obj_select_g->setOnChange('change($(this))');
        $this->obj_select_g->addOption(-1, "Aucun");

        //On remplie l'ascenseur groupe et on garde l'element selectionne
        $obj_req_g = new querry_select("SELECT UNIQUE groupe FROM SOCIETE ORDER BY groupe");
        $this->obj_select_g->makeQuerryToSelect($obj_req_g, 0, 0);
        $this->obj_select_g->selectOption($gpe);

        //On remplie l'ascenseur site et on garde l'element selectionne
        $obj_req_g = new querry_select("SELECT UNIQUE site FROM societe WHERE groupe='" . $gpe . "' ORDER BY site");
        $this->obj_select_s->makeQuerryToSelect($obj_req_g, 0, 0);
        $this->obj_select_s->selectOption($site);
        $this->obj_select_s->setClass('select_site');

        //On remplie l'ascenseur application et on garde l'element selectionne
        $obj_req_g = new querry_select("SELECT UNIQUE application FROM societe WHERE site='" . $site . "' AND groupe ='" . $gpe . "' ORDER BY application");
        $this->obj_select_a->makeQuerryToSelect($obj_req_g, 0, 0);
        $this->obj_select_a->selectOption($appli);
        $this->obj_select_a->setClass('select_application');
        
        $this->obj_div_s = new div("div_site", $this->obj_select_s->htmlValue());
        $this->obj_div_s->setClass("div_site");
        $this->obj_div_s->setStyle('display:inline');

        //Div contenant le select de l'application
        $this->obj_div_a = new div("appli", $this->obj_select_a->htmlValue());
        $this->obj_div_a->setClass("div_appli");
        $this->obj_div_a->setStyle('display:inline');
    }

}

?>
