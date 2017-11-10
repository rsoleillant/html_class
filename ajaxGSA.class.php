<?php

/* * *****************************************************************************
  Create Date : 25/11/2009
  ----------------------------------------------------------------------
  Class name : AjaxGSA
  Version : 1.0
  Author : Rémy Soleillant
  Description : Un GSA basé sur l'objet AjaxMultiselect
 * ****************************************************************************** */

class ajaxGSA {

    //**** attribute *************************************************************
    public $obj_ajax_multiselect;     //l'objet ajax_multiselect utilisé pour créer le GSA
    public $stri_groupe_sql;          //le sql utilisé pour le groupe
    public $stri_site_sql;            //le sql utilisé pour le site
    public $stri_application;         //le sql utilisé pour l'application
    public $bool_societe_non_valide;  //pour savoir si on doit afficher les sociétés valident ou non
    public $arra_select_name = array();

    //**** constructor ***********************************************************

    /*     * ***********************************************************
     *
     * parametres : bool : pour lancer la construction automatique des selects
     *              bool : pour afficher les société à l'état clos   
     * retour : objet de la classe rules_applicator   
     *                        
     * ************************************************************ */

    function __construct($bool_auto_build = true, $bool_societe_non_valide = true) {
        //gestion ou non de l'affichage des sociétés non valide
        $stri_and_etat = "";
        $stri_where_etat = "";
        if (!$bool_societe_non_valide) {
            $stri_and_etat = "and etat=8100";
            $stri_where_etat = "where etat=8100";
        }
        $this->bool_societe_non_valide = $bool_societe_non_valide;

        $this->stri_groupe_sql = "select distinct groupe from societe order by groupe";
        $this->stri_site_sql = "select distinct site from societe where groupe='[groupe]' $stri_and_etat order by site";
        $this->stri_application = "select distinct application from societe where groupe='[groupe]' $stri_and_etat and site='[site]' order by site";

        if ($bool_auto_build) {
            $this->constructSelect();
        }
    }

    //**** setter ****************************************************************
    public function setGroupeSql($value) {
        $this->stri_groupe_sql = $value;
    }

    public function setSiteSql($value) {
        $this->stri_site_sql = $value;
    }

    public function setApplication($value) {
        $this->stri_application = $value;
    }

    public function setSocieteNonValide($value) {
        $this->bool_societe_non_valide = $value;
    }

    //**** getter ****************************************************************
    public function getAjaxMultiselect() {
        return $this->obj_ajax_multiselect;
    }

    public function getGroupeSql() {
        return $this->stri_groupe_sql;
    }

    public function getSiteSql() {
        return $this->stri_site_sql;
    }

    public function getApplication() {
        return $this->stri_application;
    }

    public function getSocieteNonValide() {
        return $this->bool_societe_non_valide;
    }

    //**** public method *********************************************************
    public function constructSelect() {
        global $ModName;
        $obj_multiselect = new ajaxMultiSelect($ModName . "_AjaxGSA");

        $obj_select = $obj_multiselect->addSelect("groupe", $this->stri_groupe_sql, _GROUPE, '', '', true);
        $obj_select->setStyle("width: 200px;");
        $this->arra_select_name[] = array(_GROUPE, $obj_select);
        $obj_select = $obj_multiselect->addSelect("site", $this->stri_site_sql, _SITE);
        $obj_select->setStyle("width: 200px;");
        $this->arra_select_name[] = array(_SITE, $obj_select);
        $obj_select = $obj_multiselect->addSelect("application", $this->stri_application, _APPLI);
        $obj_select->setStyle("width: 200px;");
        $this->arra_select_name[] = array(_APPLI, $obj_select);

        $this->obj_ajax_multiselect = $obj_multiselect;
    }

    public function htmlValue() {
        global $ModName;
        $obj_multiselect = $this->obj_ajax_multiselect;

        if (isset($_GET['newlang'])) {//réinitialisation sur changement de langue
            ajaxMultiselect::purgeTemp($ModName . "_AjaxGSA");
        }

        if (isset($_POST['groupe'])) {//si transmission extérieur des données
            $obj_multiselect->selectOptionForSelect("groupe", $_POST['groupe']);
        }

        if (isset($_POST['site'])) {//si transmission extérieur des données
            $obj_multiselect->selectOptionForSelect("site", $_POST['site']);
        }

        if (isset($_POST['application'])) {//si transmission extérieur des données
            $obj_multiselect->selectOptionForSelect("application", $_POST['application']);
        }

        $stri_res = $obj_multiselect->htmlValue();

        //vérification si l'objet sérialisé doit être mis à jour
        $stri_sql = "SELECT Count(*) from (" . $this->stri_groupe_sql . ")";
        $obj_query = new querry_select($stri_sql);
        $arra_res = $obj_query->execute();
        $int_nb_option_in_database = $arra_res[0][0];

        $obj_first_select = $obj_multiselect->getSelect("groupe");
        $int_nb_option = $obj_first_select->getNumberOption() - 1; //le nombre d'option dans la liste - 1 représente le nombre de sociétés.

        $bool_obsolete = ($int_nb_option_in_database != $int_nb_option); //réinitialisation si le nombre de société dans la bdd n'est pas le même que le nombre d'options
        if ($bool_obsolete) {//si le multiselect doit être reconstruit
            ajaxMultiselect::purgeTemp($ModName . "_AjaxGSA"); //suppession du fichier temporaire
            return $this->htmlValue(); //on relance la construction du multiselect
        }
        return $stri_res;
    }

    public function htmlTr() {
        global $ModName;
        $obj_multiselect = $this->obj_ajax_multiselect;

        if (isset($_GET['newlang'])) {//réinitialisation sur changement de langue
            ajaxMultiselect::purgeTemp($ModName . "_AjaxGSA");
        }

        if (isset($_POST['groupe'])) {//si transmission extérieur des données
            $obj_multiselect->selectOptionForSelect("groupe", $_POST['groupe']);
        }

        if (isset($_POST['site'])) {//si transmission extérieur des données
            $obj_multiselect->selectOptionForSelect("site", $_POST['site']);
        }

        if (isset($_POST['application'])) {//si transmission extérieur des données
            $obj_multiselect->selectOptionForSelect("application", $_POST['application']);
        }

        $array = $obj_multiselect->htmlValueTr();

        //vérification si l'objet sérialisé doit être mis à jour
        $stri_sql = "SELECT Count(*) from (" . $this->stri_groupe_sql . ")";
        $obj_query = new querry_select($stri_sql);
        $arra_res = $obj_query->execute();
        $int_nb_option_in_database = $arra_res[0][0];

        $obj_first_select = $obj_multiselect->getSelect("groupe");
        $int_nb_option = $obj_first_select->getNumberOption() - 1; //le nombre d'option dans la liste - 1 représente le nombre de sociétés.

        $bool_obsolete = ($int_nb_option_in_database != $int_nb_option); //réinitialisation si le nombre de société dans la bdd n'est pas le même que le nombre d'options
        if ($bool_obsolete) {//si le multiselect doit être reconstruit
            ajaxMultiselect::purgeTemp($ModName . "_AjaxGSA"); //suppession du fichier temporaire
            return $this->htmlValue(); //on relance la construction du multiselect
        }
        return $array;
    }

}

?>
