<?php

/* * *****************************************************************************
  Create Date : 25/11/2009
  ----------------------------------------------------------------------
  Class name : AjaxGSA
  Version : 1.0
  Author : R�my Soleillant
  Description : Un GSA bas� sur l'objet AjaxMultiselect
 * ****************************************************************************** */

class ajaxGSA {

    //**** attribute *************************************************************
    public $obj_ajax_multiselect;     //l'objet ajax_multiselect utilis� pour cr�er le GSA
    public $stri_groupe_sql;          //le sql utilis� pour le groupe
    public $stri_site_sql;            //le sql utilis� pour le site
    public $stri_application;         //le sql utilis� pour l'application
    public $bool_societe_non_valide;  //pour savoir si on doit afficher les soci�t�s valident ou non
    public $arra_select_name = array();

    //**** constructor ***********************************************************

    /*     * ***********************************************************
     *
     * parametres : bool : pour lancer la construction automatique des selects
     *              bool : pour afficher les soci�t� � l'�tat clos   
     * retour : objet de la classe rules_applicator   
     *                        
     * ************************************************************ */

    function __construct($bool_auto_build = true, $bool_societe_non_valide = true) {
        //gestion ou non de l'affichage des soci�t�s non valide
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

        if (isset($_GET['newlang'])) {//r�initialisation sur changement de langue
            ajaxMultiselect::purgeTemp($ModName . "_AjaxGSA");
        }

        if (isset($_POST['groupe'])) {//si transmission ext�rieur des donn�es
            $obj_multiselect->selectOptionForSelect("groupe", $_POST['groupe']);
        }

        if (isset($_POST['site'])) {//si transmission ext�rieur des donn�es
            $obj_multiselect->selectOptionForSelect("site", $_POST['site']);
        }

        if (isset($_POST['application'])) {//si transmission ext�rieur des donn�es
            $obj_multiselect->selectOptionForSelect("application", $_POST['application']);
        }

        $stri_res = $obj_multiselect->htmlValue();

        //v�rification si l'objet s�rialis� doit �tre mis � jour
        $stri_sql = "SELECT Count(*) from (" . $this->stri_groupe_sql . ")";
        $obj_query = new querry_select($stri_sql);
        $arra_res = $obj_query->execute();
        $int_nb_option_in_database = $arra_res[0][0];

        $obj_first_select = $obj_multiselect->getSelect("groupe");
        $int_nb_option = $obj_first_select->getNumberOption() - 1; //le nombre d'option dans la liste - 1 repr�sente le nombre de soci�t�s.

        $bool_obsolete = ($int_nb_option_in_database != $int_nb_option); //r�initialisation si le nombre de soci�t� dans la bdd n'est pas le m�me que le nombre d'options
        if ($bool_obsolete) {//si le multiselect doit �tre reconstruit
            ajaxMultiselect::purgeTemp($ModName . "_AjaxGSA"); //suppession du fichier temporaire
            return $this->htmlValue(); //on relance la construction du multiselect
        }
        return $stri_res;
    }

    public function htmlTr() {
        global $ModName;
        $obj_multiselect = $this->obj_ajax_multiselect;

        if (isset($_GET['newlang'])) {//r�initialisation sur changement de langue
            ajaxMultiselect::purgeTemp($ModName . "_AjaxGSA");
        }

        if (isset($_POST['groupe'])) {//si transmission ext�rieur des donn�es
            $obj_multiselect->selectOptionForSelect("groupe", $_POST['groupe']);
        }

        if (isset($_POST['site'])) {//si transmission ext�rieur des donn�es
            $obj_multiselect->selectOptionForSelect("site", $_POST['site']);
        }

        if (isset($_POST['application'])) {//si transmission ext�rieur des donn�es
            $obj_multiselect->selectOptionForSelect("application", $_POST['application']);
        }

        $array = $obj_multiselect->htmlValueTr();

        //v�rification si l'objet s�rialis� doit �tre mis � jour
        $stri_sql = "SELECT Count(*) from (" . $this->stri_groupe_sql . ")";
        $obj_query = new querry_select($stri_sql);
        $arra_res = $obj_query->execute();
        $int_nb_option_in_database = $arra_res[0][0];

        $obj_first_select = $obj_multiselect->getSelect("groupe");
        $int_nb_option = $obj_first_select->getNumberOption() - 1; //le nombre d'option dans la liste - 1 repr�sente le nombre de soci�t�s.

        $bool_obsolete = ($int_nb_option_in_database != $int_nb_option); //r�initialisation si le nombre de soci�t� dans la bdd n'est pas le m�me que le nombre d'options
        if ($bool_obsolete) {//si le multiselect doit �tre reconstruit
            ajaxMultiselect::purgeTemp($ModName . "_AjaxGSA"); //suppession du fichier temporaire
            return $this->htmlValue(); //on relance la construction du multiselect
        }
        return $array;
    }

}

?>
