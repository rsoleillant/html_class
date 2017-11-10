<?php

/* * *****************************************************************************
  Create Date  : 29/11/2012
  ----------------------------------------------------------------------
  Class name  : asis_projet_perso_liaison_asis_projer_perso_asis_projet_loader
  Version     : 1.0
  Author      : Romain ROBERT
  Description : Permet l'affichage d'un bloc à placé dans un contenu

 * ****************************************************************************** */

class ecran_block {

//**** Attributs ****************************************************************
    protected $stri_title;
    protected $obj_table_ico;
    protected $obj_table_data;
    protected $bool_visible;

    /*     * *****************************************************************************
     * Constructeur du block présent dans un objet de type CONTENU
     * 
     * Parametres : $stri_title : Titre
     * $table_ico : pour les icônes
     * String : message d'aide qui safficher si pas de table data
     * Table : Pour les données dans l'écran détails       
     * Retour :                          
     * ***************************************************************************** */

    function __construct($stri_title, $table_ico, $table_data,$bool_visible = true) {
        $this->stri_title = $stri_title;
        $this->obj_table_ico = $table_ico;
        $this->obj_table_data = $table_data;
        $this->bool_visible=$bool_visible;
    }

//*** setter ******************************************************************
    public function setTitle($mixed_value) {
        $this->stri_title = $mixed_value;
    }

    public function setTableIco($mixed_value) {
        $this->obj_table_ico = $mixed_value;
    }

    public function setTableData($mixed_value) {
        $this->obj_table_data = $mixed_value;
    }
     public function setVisible($mixed_value) {
        $this->bool_visible = $mixed_value;
    }

//*** getter ******************************************************************
    public function getTitle() {
        return $this->stri_title;
    }

    public function getTableIco() {
        return $this->obj_table_ico;
    }

    public function getTableData() {
        return $this->obj_table_data;
    }
    public function getVisible() {
        return $this->bool_visible;
    }

//*** Méthodes d'affichage ****************************************************

    /*     * *****************************************************************************
     * Pour construire l'interface html du bandeau Détails-Récap de gauche
     * 
     * Parametres : aucun 
     * Retour : table : le code html                         
     * ***************************************************************************** */
    public function htmlValue() {
        //Obj titre du block
        $obj_font_detail = new font($this->stri_title, true);
        $obj_font_detail->setStyle('font-size:17px');


        //Entete du block
        $obj_table_detail_int = new table();
        $obj_table_detail_int->setWidth("100%");
        $obj_tr = $obj_table_detail_int->addTr();
        $obj_td = $obj_tr->addTd($obj_font_detail);
        $obj_td->setNowrap(true);
        $obj_td = $obj_tr->addTd($this->obj_table_ico);
        $obj_td->setAlign("right");

        //Création table détails   
        $obj_table_detail = new table();
        $obj_table_detail->setStyle("height: 100%;margin-bottom :13px;margin-top :5px;");
        $obj_table_detail->setWidth("100%");
        $obj_table_detail->setId("id_block_" . str_replace(" ","_",$this->stri_title));
        $obj_table_detail->setClass("details contenu");
        $obj_tr = $obj_table_detail->addTr();
        $obj_td = $obj_tr->addTd($obj_table_detail_int);
        $obj_td->setStyle("padding-left:0.5em;");
        $obj_td->setClass("titre2 entete");
        $obj_tr = $obj_table_detail->addTr();
        $obj_td = $obj_tr->addTd($this->obj_table_data);
            $obj_td->setStyle("padding: 7px;");

        
        //Si bool défini a false
        if (!$this->bool_visible)
        {
            $obj_table_detail->setStyle("display:none;");
        }
        
        
        return $obj_table_detail->htmlValue();
    }

}

?>
