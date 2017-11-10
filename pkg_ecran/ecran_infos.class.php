<?php

/* * *****************************************************************************
  Create Date  : 29/11/2012
  ----------------------------------------------------------------------
  Class name  : ecran_infos
  Version     : 1.0
  Author      : CAYUELA Christophe & ROBERT Romain
  Description : Permet l'affichage du bloc infos utiles

 * ****************************************************************************** */

class ecran_infos {

//**** Attributs ****************************************************************
    protected $stri_href;
    protected $stri_message;
    protected $stri_num_ecran;
    protected $bool_mess=true; //Message visible ou non
    protected $bool_num=true;   //Num page visible ou non
    protected $bool_image=true;  //Lien image visible ou non
    protected $bool_link=true;  //si lien ou non

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
    function __construct($stri_infos = "", $stri_num = "", $stri_href = "") {
        $this->stri_message = $stri_infos;
        $this->stri_num_ecran = $stri_num;
        $this->stri_href = $stri_href;
    }

//*** setter ******************************************************************
    public function setHref($mixed_value) {
        $this->stri_href = $mixed_value;
    }

    public function setMessage($mixed_value) {
        $this->stri_message = $mixed_value;
    }

    public function setNumEcran($mixed_value) {
        $this->stri_num_ecran = $mixed_value;
    }

//*** getter ******************************************************************
    public function getHref() {
        return $this->stri_href;
    }

    public function getMessage() {
        return $this->stri_message;
    }

    public function getNumEcran() {
        return $this->stri_num_ecran;
    }

//*********Enable/Disable ************************************
    public function setEnableMess($mixed_value) {
        $this->bool_mess = $mixed_value;
    }

    public function setEnableNum($mixed_value) {
        $this->bool_num = $mixed_value;
    }

    public function setEnableImage($mixed_value) {
        $this->bool_image = $mixed_value;
    }
    public function setEnableLink($mixed_value) {
        $this->bool_link = $mixed_value;
    }

//*** Méthodes d'affichage ****************************************************

    /*     * *****************************************************************************
     * Pour construire l'interface html de recherche
     * 
     * Parametres : aucun 
     * Retour : table : le code html                         
     * ***************************************************************************** */
    public function htmlValue() {
        //Image aide en ligne
        $obj_img_aide= new img("includes/classes/html_class/pkg_ecran/help/".pnUserGetLang()."/aide.gif");
        $obj_img_aide->setTitle(_FT_HELP);
        $obj_img_aide->setAlt(_FT_HELP);
        $obj_img_aide->setWidth("100%");
        $obj_img_aide->setWidth("245px");
        //$obj_td->setStyle("min-width: 220px;max-width: 220px;");
        //$obj_img_aide->setStyle("min-width: 220px;max-width: 220px;margin-top:6px;");;
        $obj_img_aide->setStyle("margin-top:6px;");
        $obj_a_aide = new a("", $obj_img_aide->htmlValue(), true);

        //Font numéro écran    
        $obj_font_num = new font($this->stri_num_ecran, true);
        $obj_font_num->setStyle('font-size:10px');
        $obj_font_num->setTitle(_NB_ECRAN);

        //Message du block    
        $obj_font_info = new font($this->stri_message, false, true, false);
        $obj_font_info->setSize(2);

        //Création table infos utiles    
        $obj_table_Info = new table();
        $obj_tr = $obj_table_Info->addTr();

        if ($this->bool_num) {
            $obj_td = $obj_tr->addTd($obj_font_num);
        } else {
            $obj_td = $obj_tr->addTd("");
        }

        $obj_td->setAlign('right');
        $obj_tr = $obj_table_Info->addTr();

        if ($this->bool_mess) {
            $obj_td = $obj_tr->addTd($obj_font_info);
            $obj_td->setStyle('text-indent:20px;');
        } else {
            $obj_td = $obj_tr->addTd("");
        }

        $obj_td->setColspan(2);
        $obj_table_Info->setStyle("height:100px;background-image:url(images/MaJ_graphique/help_book.png);background-repeat:no-repeat;");
        $obj_table_Info->setWidth("100%");
        $obj_table_Info->setBorder("0");
        $obj_table_Info->setId("table_infos_utile");
        $obj_table_Info->setClass("contenu table_infos_utile");


        /*if ($this->stri_href == "") {
            $obj_a_aide->setHref("_blank");
            $obj_a_aide->setTarget("_new");
        } else { */
            //Lien de la page d'aide
            $this->stri_href="modules.php?op=modload&amp;name=OnlineHelp&amp;file=index";
            $obj_a_aide->setHref($this->stri_href);
            $obj_a_aide->setTarget("_new");
        //}
        if($this->bool_link)
        {return $obj_a_aide->htmlValue() . $obj_table_Info->htmlValue();}
        else
        {return $obj_img_aide->htmlValue() . $obj_table_Info->htmlValue();}
    }

}

?>
