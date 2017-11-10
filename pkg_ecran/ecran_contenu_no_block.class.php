<?php

/* * *****************************************************************************
  Create Date  : 11/12/2012
  ----------------------------------------------------------------------
  Class name  : ecran_contenu
  Version     : 1.0
  Author      : Romain ROBERT
  Description : Permet l'affichage du bloc contenu

 * ****************************************************************************** */

class ecran_contenu_no_block {

//**** Attributs ****************************************************************
    protected $stri_titre_entete;
    protected $stri_src_img;
    protected $arra_ico;
    protected $obj_contenu;
    protected $obj_message;
    protected $obj_javascripter;   //Javascripter principal
    protected $bool_table_ico;  //Booléen pour table icone inséré
    protected $bool_exportPDF;  //Booléen pour affichage icone export PDF
    protected $bool_print;      //Booléen pour affichage icone print
    protected $bool_form; //Booléen sur toutes la page ou juste table
    protected $stri_action;
    protected $stri_name_form;
    protected $arra_object = null; //Array of object's form, use for javacriptVerification
    protected $stri_pdf_src="";    //Lien pour l'export PDF

//**** Methodes *****************************************************************  
//*** constructor *************************************************************

    /*     * *****************************************************************************
     * Constructeur du block contenu
     * 
     * Parametres : String : title
     * String : source img entete contenu
     * Table : pour les icônes d'action
     * String : message d'aide qui safficher si pas de table data
     * Table : Pour les données dans l'écran détails       
     * Retour                           
     * ***************************************************************************** */
    function __construct($stri_src_img, $stri_title, $arra_ico, $obj_contenu, $obj_message = "") {
        $this->stri_src_img = $stri_src_img;
        $this->stri_titre_entete = $stri_title;
        $this->arra_ico = $arra_ico;
        $this->obj_contenu = $obj_contenu;
        $this->obj_message = $obj_message;
        $this->obj_javascripter = new javascripter();

        $this->stri_action = "";
        $this->stri_name_form = "form_contenu";
        $this->arra_object = NULL;

        $this->bool_table_ico = true;
        $this->bool_exportPDF = false;
        $this->bool_print = false;
        $this->bool_form = false;
    }

//*** setter ******************************************************************
    public function setSrcImg($mixed_value) {
        $this->stri_src_img = $mixed_value;
    }

    public function setTitle($mixed_value) {
        $this->stri_titre_entete = $mixed_value;
    }

    public function setTableIco($mixed_value) {
        $this->arra_ico = $mixed_value;
    }

    public function setTableData($mixed_value) {
        $this->obj_contenu = $mixed_value;
    }

    public function setMessage($mixed_value) {
        $this->obj_message = $mixed_value;
    }

    public function setAction($mixed_value) {
        $this->stri_action = $mixed_value;
    }

    public function setNameForm($mixed_value) {
        $this->stri_name_form = $mixed_value;
    }

    public function setJavascripter($mixed_value) {
        $this->obj_javascripter = $mixed_value;
    }

    public function setArraObject($mixed_value) {
        $this->arra_object = $mixed_value;
    }
    
    public function setPdfSrc($mixed_value) {
        $this->stri_pdf_src = $mixed_value;
    }

//*** getter ******************************************************************
    public function getSrcImg() {
        return $this->stri_src_img;
    }

    public function getTitle() {
        return $this->stri_titre_entete;
    }

    public function getTableIco() {
        return $this->arra_ico;
    }

    public function getTableData() {
        return $this->obj_contenu;
    }

    public function getJavascripter() {
        return $this->obj_javascripter;
    }

    public function getMessage() {
        return $this->obj_message;
    }

    public function getAction() {
        return $this->stri_action;
    }

    public function getNameForm() {
        return $this->stri_name_form;
    }

    public function getArraObject() {
        return $this->arra_object;
    }
    
    public function getPdfSrc() {
        return $this->stri_pdf_src;
    }

    //******Enable**************************************************************
    public function setEnableTableIco($mixed_value) {
        $this->bool_table_ico = $mixed_value;
    }

    public function setEnableExportPDF($mixed_value) {
        $this->bool_exportPDF = $mixed_value;
    }

    public function setEnablePrint($mixed_value) {
        $this->bool_print = $mixed_value;
    }

    public function setEnableForm($mixed_value) {
        $this->bool_form = $mixed_value;
    }

    //*****Javascript function************************************************
    public function addJsFile($stri_src) {
        $this->obj_javascripter->addFile($stri_src);
    }
    
    //*****Javascript function************************************************
    public function addJsFunc($stri_func) {
        $this->obj_javascripter->addFunction($stri_func);
    }

//*** Méthodes d'affichage ****************************************************

    /*     * *****************************************************************************
     * Pour construire l'interface html de recherche
     * 
     * Parametres : aucun 
     * Retour : table : le code html                         
     * ***************************************************************************** */
    public function htmlValue() {
        //*****Gestion icone print et Export********
        if ($this->bool_print === true) {
            $obj_img_print = new img("images/MaJ_graphique/printer20x20.png");
            $obj_img_print->setStyle("cursor:pointer;");
            $obj_img_print->setBorder("0");
            $obj_img_print->setWidth("20px");
            $obj_img_print->setHeight("20px");
            $obj_img_print->setStyle("margin:1px 2px 1px 2px;");

            $currentURL = $_SERVER['REQUEST_URI'];
            //Impression display block =>impression liste plus joli
            if (isset($_GET['block'])) {
                $currentURL = str_replace("&block", "", "$currentURL");
            }
            $currentURL = str_replace("&","&amp;", "$currentURL");
            $obj_print = new a("" . $currentURL . "&amp;print", $obj_img_print->htmlValue(), true);
            $obj_print->setId("print");
            $obj_print->setTitle(_PRINT);
            $obj_print->setTarget("_blank");
        } else {
            $obj_print = "";
        }

        if ($this->bool_exportPDF === true) {
            $obj_img_pdf = new img("images/MaJ_graphique/PDF20x20.png");
            $obj_img_pdf->setStyle("cursor:pointer;");
            $obj_img_pdf->setBorder("0");
            $obj_img_pdf->setStyle("margin:1px 2px 1px 2px;");
            $obj_pdf = new a($this->stri_pdf_src, $obj_img_pdf->htmlValue(), true);
            $obj_pdf->setId("pdf");
            $obj_pdf->setTarget("_blank");
            $obj_pdf->setTitle("PDF");
        } else {
            $obj_pdf = "";
        }


        //Table Icone
        if ($this->bool_table_ico === true) {
            $obj_display = $this->arra_ico;
        } else {
            $obj_display = "";
        }

        //Objet display icone si tableau d'icône
        if (sizeof($obj_display) > 1) {
            $obj_display_ico = array($obj_pdf, "&nbsp;", $obj_print);
            foreach ($obj_display as $key => $display) { //Permet l'affichage de l'array icone
            //passé en paramètre
                $obj_display_ico = array_pad($obj_display_ico, sizeof($obj_display_ico) + 1, "&nbsp;");
                $obj_display_ico = array_pad($obj_display_ico, sizeof($obj_display_ico) + 1, $display);
            }
        } else {
            $obj_display_ico = array($obj_pdf, "&nbsp;", $obj_print, "&nbsp;", $obj_display);
        }

        //Titre de l'entete contenu
        $obj_font_titre = new font($this->stri_titre_entete, true);
        $obj_font_titre->setStyle('font-weight: bold;');
        $obj_font_titre->setSize(5);

        //Image de l'entete contenu
        $obj_img_entete = new img($this->stri_src_img);
        $obj_img_entete->setWidth("45px");
        $obj_bandeau = new ecran_bandeau();  //Instancie un bandeau composé de : "Bienvenue user     Date"  ;
        //Table d'entete contenu Avec : Image - Titre - Tableau ico actions
        $obj_table_entete = new table();
        $obj_table_entete->setWidth("100%");
        $obj_table_entete->setClass("titre4 entete");
        $obj_tr = $obj_table_entete->addTr();
        $obj_td = $obj_tr->addTd($obj_img_entete);
        $obj_td->setStyle('width:90px;');
        $obj_td = $obj_tr->addTd($obj_font_titre);
        $obj_td = $obj_tr->addTd($obj_display_ico); //array($obj_pdf,"&nbsp;",$obj_print,"&nbsp;",$obj_display2));
        $obj_td->setAlign("right");



        //Tableau retourner
        $obj_table_main = new table();
        $obj_table_main->setWidth("100%");
        $obj_tr = $obj_table_main->addTr();
        $obj_td = $obj_tr->addTd($obj_bandeau);
        $obj_tr = $obj_table_main->addTr();
        $obj_td = $obj_tr->addTd($obj_table_entete);
        $obj_tr = $obj_table_main->addTr();
        $obj_td = $obj_tr->addTd($this->obj_message);
        $obj_td->setAlign("center");
        $obj_td->setWidth("100%");

        $obj_tr = $obj_table_main->addTr();
        $obj_td = $obj_tr->addTd($this->obj_contenu);

        if ($this->bool_form === true) {
            $form = new form($this->stri_action, "post", $obj_table_main->htmlValue(), $this->stri_name_form);
            $form->setEnctype("multipart/form-data");

            //08/01/2013 : Gestion champ vide ou mal rempli des objets du formulaire 
            if (sizeof($this->arra_object) != 0) {
                foreach ($this->arra_object as $key => $arra_value) {
                    $form->addObject($arra_value);
                }
            }

            return $this->obj_javascripter->javascriptValue() . $form->htmlValue() . $form->javascriptVerification();
        } else {
            return $this->obj_javascripter->javascriptValue() . $obj_table_main->htmlValue();
        }
    }

}

?>
