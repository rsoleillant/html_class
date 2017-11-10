<?php

/* * *****************************************************************************
  Create Date  : 03/12/2012
  ----------------------------------------------------------------------
  Class name  : ecran_model
  Version     : 1.0
  Author      : CAYUELA Christophe & ROBERT Romain
  Description : Permet l'affichage de tout ce qui se trouve au dessu du header

 * ****************************************************************************** */

class ecran_model {

//**** Attributs ****************************************************************
    protected $obj_attr_menu;  // Obj Menu
    protected $obj_attr_head;  // Obj En-tete
    protected $obj_attr_infos; //Obj Infos-Utiles
    protected $obj_attr_details;  //Obj bandeau gauche navigateur
    protected $obj_attr_contenu;  // Obj Contenu

//**** Methodes *****************************************************************  
//*** constructor *************************************************************

    /*     * ****************************************************************************
     * Constructeur du loader définissant le sql de base
     * 
     * Parametres :
     * Obj ecran_infos : $obj_infos  -> block infos
     * Obj ecran_details : $obj_details -> block navigateur gauche
     * Obj ecran_header : $obj_header -> block en-tete
     * Obj ecran_contenu : $obj_contenu -> block contenu
     * Retour :  :                          
     * ***************************************************************************** */
    function __construct($obj_menu, ecran_entete $obj_head, ecran_infos $obj_infos, ecran_details $obj_details, $obj_contenu) {
        $this->obj_attr_menu = $obj_menu;
        $this->obj_attr_head = $obj_head;
        $this->obj_attr_infos = $obj_infos;
        $this->obj_attr_details = $obj_details;
        $this->obj_attr_contenu = $obj_contenu;
    }

//*** setter ******************************************************************
    public function setAttrMenu($mixed_value) {
        $this->obj_attr_menu = $mixed_value;
    }

    public function setAttrHead($mixed_value) {
        $this->obj_attr_head = $mixed_value;
    }

    public function setAttrInfos($mixed_value) {
        $this->obj_attr_infos = $mixed_value;
    }

    public function setAttrDetails($mixed_value) {
        $this->obj_attr_details = $mixed_value;
    }

    public function setAttrContenu($mixed_value) {
        $this->obj_attr_contenu = $mixed_value;
    }

//*** getter ******************************************************************
    public function getAttrMenu() {
        return $this->obj_attr_menu;
    }

    public function getAttrHead() {
        return $this->obj_attr_head;
    }

    public function getAttrInfos() {
        return $this->obj_attr_infos;
    }

    public function getAttrDetails() {
        return $this->obj_attr_details;
    }

    public function getAttrContenu() {
        return $this->obj_attr_contenu;
    }

//*** Méthodes d'affichage ****************************************************

    /*     * *****************************************************************************
     * Pour construire l'interface html de recherche
     * 
     * Parametres : aucun 
     * Retour : string : le code html                         
     * ***************************************************************************** */
    public function htmlValue($bool_navigateur) {

        //Tableau contenu              
        $obj_table_cont = new table();
        $obj_table_cont->setWidth("100%");

        $contenu = $this->obj_attr_contenu->htmlValue();
        $obj_tr = $obj_table_cont->addTr();
        $obj_td = $obj_tr->addTd($contenu);
        $obj_td->setWidth("100%");

        //Attribut écran
        if ($this->obj_attr_menu != "") {
            $menu = $this->obj_attr_menu->htmlValue();
        }
        $head = $this->obj_attr_head->htmlValue();
        $infos = $this->obj_attr_infos->htmlValue();
        $details = $this->obj_attr_details->htmlValue();

      
                    
        
        //Tableau principal model
        $obj_main_table = new table();
        $obj_main_table->setBorder("0");
        $obj_main_table->setWidth("100%");
        $obj_main_table->setStyle("height:100%;");
        $obj_tr = $obj_main_table->addTr();

        //Menu
        $obj_td = $obj_tr->addTd($menu);
        $obj_td->setColspan(2);
        $obj_tr = $obj_main_table->addTr();

        //Entete
        $obj_td = $obj_tr->addTd($head);
        $obj_td->setColspan(2);
        $obj_tr = $obj_main_table->addTr();


        if ($bool_navigateur) {
            
            $obj_td = $obj_tr->addTd(array($infos,$details));
            $obj_td->setId('ecran_infos');
            //$obj_td->setWidth("17%");
            
            $obj_td->setWidth("252px");
            $obj_td->setStyle("min-width: 252px;max-width: 252px;");
            
            
            //$obj_td->setStyle("min-width: 17%;max-width: 17%;");
            //$obj_td->setStyle("max-width: 17%;");
            $obj_td->setValign('top');
            $obj_td->setAlign('center');
        }

        $obj_td = $obj_tr->addTd($obj_table_cont);
        $obj_td->setWidth("100%");
        $obj_td->setStyle("height:100%;");
        $obj_td->setValign('top');
        
         //Flag pour scroll div details
        $stri_input_end_mod = new div("flag__end_mod", '');
              $stri_input_end_mod->setStyle('margin: 60%');
        
        $stri_return = $obj_main_table->htmlValue() . $stri_input_end_mod->htmlValue();

        return $stri_return;
    }

}

?>
