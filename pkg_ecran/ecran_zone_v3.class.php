<?php

/* * *****************************************************************************
  Create Date  : 29/11/2012
  ----------------------------------------------------------------------
  Class name  : asis_projet_perso_liaison_asis_projer_perso_asis_projet_loader
  Version     : 1.0
  Author      : Romain ROBERT
  Description : Permet l'affichage d'une zone clickable à placé dans un block

 * ****************************************************************************** */

class ecran_zone_v3 {

//**** Attributs ****************************************************************
    
    protected $stri_title;              //title
    protected $obj_contenu;             //Contenu
    protected $stri_name_group_zone;    //Nom du groupe (attr name du boutton reduire/agrandire)
    protected $bool_visible;            //Affiche la zone ou non
        
    protected $obj_javascripter;        //Javascripter 

//**** Methodes *****************************************************************  
//*** constructor *************************************************************

    /*     * *****************************************************************************
     * Constructeur du block présent dans un objet de type CONTENU
     * 
     * Parametres : $stri_title : Titre
     * $table_ico : pour les icônes
     * String : message d'aide qui safficher si pas de table data
     * Table : Pour les données dans l'écran détails       
     * Retour :                          
     * ***************************************************************************** */
    function __construct($stri_title, $obj_contenu) {
        $this->stri_title = $stri_title;
        $this->obj_contenu = $obj_contenu;
        $this->bool_visible = true;
        $this->obj_javascripter = new javascripter();
    }

//*** setter ******************************************************************
    public function setTitle($mixed_value) {
        $this->stri_title = $mixed_value;
    }

    public function setContenu($mixed_value) {
        $this->obj_contenu = $mixed_value;
    }
    

    public function setVisible($bool_value) {
        $this->bool_visible = $bool_value;
    }

//*** getter ******************************************************************
    public function getTitle() {
        return $this->stri_title;
    }

    public function getContenu() {
        return $this->obj_contenu;
    }
    
    public function getNameGroupZone() {
        return $this->obj_contenu;
    }

    public function getVisible() {
        return $this->bool_visible;
    }

//*** Méthodes d'affichage ****************************************************

    
    
    
    /*     * ******************************************************************
     * Pour construire l'interface html du bandeau Détails-Récap de gauche
     * 
     * Parametres : aucun 
     * Retour : Object table
     * ********************************************************************** */
    public function htmlValue($bool_reduct=false) {

        
        //Dans le cas d'une zone not visible
        if ($bool_reduct==true)
        {
            $stri_span = '<span style="float:right;"class="ui-icon ui-icon-triangle-1-s"> </span>';
            $stri_span = '<span style="float:right;"class="expandAll">
                </span>';
        }
        else
        {
            $stri_span ='<span style="float:right;"class="ui-icon ui-icon-triangle-1-n"> </span>';
            $stri_span ='<span style="float:right;"class="reductAll"> 
                </span>';
        }
        
         //Obj titre du block
        $obj_font_detail = new font($this->stri_title , true);
        $obj_font_detail->setStyle('font-size:17px;float: left');


        //Entete du block
        $obj_table_detail_int = new table();
        $obj_table_detail_int->setWidth("100%");
        $obj_tr = $obj_table_detail_int->addTr();
        $obj_td = $obj_tr->addTd($obj_font_detail->htmlValue());
        $obj_td->setNowrap(true);
        $obj_td = $obj_tr->addTd($stri_span);
        $obj_td->setAlign("right");
        $obj_td->setClass("infobulle");
        $obj_td->setTitle(__LIB_ECRAN_ZONE_AGRANDIR_REDUIRE);


        
        

        $obj_table_zone = new table();

        $obj_table_zone->setWidth('100%');
        $obj_table_zone->setClass("contenu");
        $obj_table_zone->setStyle('height: 100%;margin-bottom :13px;margin-top :5px;');
        
        //Dans le cas d'une zone not visible
        if ($this->bool_visible == false) {
            $obj_table_zone->setStyle('display: none; ');
        }
        $obj_tr = $obj_table_zone->addTr();
        
            $obj_tr->setClass(" not_selection ".$this->stri_name_group_zone); //ajoute le nom du groupe auquel la zone appartient
            //$obj_tr->setOnclick("AfficheTr($(this));");
            $obj_tr->setOnclick("AfficheTrV2($(this));");
            $obj_tr->setstyle('cursor:pointer');
            
        $obj_td = $obj_tr->addTd($obj_table_detail_int);
        $obj_td->setClass('entete titre2');
        $obj_td->setStyle('padding-left : 7px;');
            
        $obj_tr = $obj_table_zone->addTr();
            $obj_tr->setClass("reductible ".$this->stri_name_group_zone);   //ajoute le nom du groupe auqel la zone appartient
                $obj_td = $obj_tr->addTd($this->obj_contenu);
                    $obj_td->setAlign("center");
            if ($bool_reduct==true)
            {
                $obj_td->setstyle('display :none;');
            }
            
           $obj_img_etc = new img('images/demande_pdr/etc.png');
            $obj_img_etc->setClass('ecran_zone_v3__img_etc');
            $obj_img_etc->setWidth('24');
            $obj_img_etc->setTitle(__LIB_ECRAN_ZONE_AGRANDIR);
            ($bool_reduct) ? $obj_img_etc->setStyle('') : $obj_img_etc->setStyle('display: none;');
            
             $obj_tr = $obj_table_zone->addTr();
                $obj_tr->setHeight(7);
            $obj_tr = $obj_table_zone->addTr();
            $obj_tr->setOnclick("AfficheTrV2($(this).parents('table').first().find('tr').first());");
                $obj_td = $obj_tr->addTd($obj_img_etc);
                $obj_td->setClass("infobulle");
                $obj_td->setStyle("cursor: pointer");
                $obj_td->setAlign('center');
        
            
            
        $stri_return=$this->obj_javascripter->javascriptValue().$obj_table_zone->htmlValue();
        
        //Retourne la javascript et la zone sous forme HTML
        return $stri_return;
    }

}

?>
