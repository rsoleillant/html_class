<?php

/* * *****************************************************************************
  Create Date  : 12/12/2012
  ----------------------------------------------------------------------
  Class name  : ecran_bandeau
  Version     : 1.0
  Author      : CAYUELA Christophe & ROBERT Romain
  Description : Permet l'affichage du bloc bandeau au dessus du contenu

 * ****************************************************************************** */

class ecran_bandeau {

    //**** Attributs ****************************************************************
    protected $stri_name;   //String nom utilisateur
    protected $stri_date;  //String Date actuelle

    /*     * *****************************************************************************
     * Constructeur du loader définissant le sql de base
     * 
     * Parametres : String : title
     * Table : pour les icônes
     * String : message d'aide qui safficher si pas de table data
     * Table : Pour les données dans l'écran détails       
     * Retour :                          
     * ***************************************************************************** */

    function __construct() {
        $this->stri_name = _WELCOMETO . '&nbsp;' . pnUserGetVar('name') . '';
        //$this->stri_date = date("d M Y H:i");
        $this->stri_date = date("d/m/Y H:i");
    }

    //*** setter ******************************************************************
    public function setName($mixed_value) {
        $this->stri_name = $mixed_value;
    }

    public function setDate($mixed_value) {
        $this->stri_date = $mixed_value;
    }

    //*** getter ******************************************************************
    public function getName() {
        return $this->stri_name;
    }

    public function getDate() {
        return $this->stri_date;
    }

    //*** Méthodes d'affichage ****************************************************

    /*     * *****************************************************************************
     * Pour construire l'interface html de recherche
     * 
     * Parametres : aucun 
     * Retour : table : le code html                         
     * ***************************************************************************** */
    public function htmlValue() {
        $obj_table_bandeau = new table();
        $obj_table_bandeau->setWidth("100%");
        $obj_table_bandeau->setClass("titre2");
        $obj_tr = $obj_table_bandeau->addTr();
        $obj_td = $obj_tr->addTd($this->stri_name);
        $obj_td->setWidth('100%');
        $obj_td->setNoWrap(true);
        $obj_td->setAlign('left');
        $obj_td = $obj_tr->addTd($this->stri_date);
        $obj_td->setAlign('right');
        $obj_td->setStyle("padding-right:2px");
        $obj_td->setNoWrap(true);

        return $obj_table_bandeau->htmlValue();
    }

}

?>
