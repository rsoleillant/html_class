<?php

/* * *****************************************************************************
  Create Date  : 29/11/2012
  ----------------------------------------------------------------------
  Class name  : asis_projet_perso_liaison_asis_projer_perso_asis_projet_loader
  Version     : 1.0
  Author      : Romain ROBERT
  Description : Permet l'affichage d'un bloc à placé dans un contenu

 * ****************************************************************************** */

class ecran_zone_v2 {

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
        $obj_font_titre = new font($this->stri_title, true);
            $obj_font_titre->setStyle('font-size:13px');

            
        $obj_table_entete = new table();
        $obj_table_entete->setWidth('100%');
        $obj_table_entete->setClass('titre3-3 entete');
            $obj_tr = $obj_table_entete->addTr();
                $obj_td = $obj_tr->addTd($obj_font_titre);
                $obj_td = $obj_tr->addTd($this->obj_table_ico);
                    $obj_td->setAlign('right');
                    
        $obj_table_contenu = new table();
        $obj_table_contenu->setWidth('100%');
        $obj_table_contenu->setClass('contenu');
        $obj_table_contenu->setAlign('center');
        $obj_table_contenu->setStyle('margin-top: 10px;');
            $obj_tr = $obj_table_contenu->addTr();
                $obj_td = $obj_tr->addTd($obj_table_entete);
            $obj_tr = $obj_table_contenu->addTr();
                $obj_td = $obj_tr->addTd($this->obj_table_data);
                    $obj_td->setStyle("padding: 4px;");
                    $obj_td->setAlign("center");

        


        
        //Si bool défini a false
        if (!$this->bool_visible)
        {
            $obj_table_contenu->setStyle("display:none;");
        }
        
        
        return $obj_table_contenu->htmlValue();
    }

}

?>
