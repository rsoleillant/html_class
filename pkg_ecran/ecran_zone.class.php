<?php

/* * *****************************************************************************
  Create Date  : 29/11/2012
  ----------------------------------------------------------------------
  Class name  : asis_projet_perso_liaison_asis_projer_perso_asis_projet_loader
  Version     : 1.0
  Author      : Romain ROBERT
  Description : Permet l'affichage d'une zone clickable à placé dans un block

 * ****************************************************************************** */

class ecran_zone {

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
    function __construct($stri_title, $obj_contenu,$stri_name_group_zone="") {
        $this->stri_title = $stri_title;
        $this->obj_contenu = $obj_contenu;
        $this->stri_name_group_zone = $stri_name_group_zone;
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
    
     public function setNameGroupZone($mixed_value) {
        $this->stri_name_group_zone = $mixed_value;
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
     * Pour construire le code javascript qui permet d'afficher 
     * ou de masquer le groupe de zones auquel un button reduire/agrandire est rattaché
     * 
     * Parametres : aucun 
     * Retour : javascript value
     * ********************************************************************** */
    public function javascriptValue()
    {
        
        //Détermine si le membre privée à été intialisé
        $stri_name_group_zone = ($this->stri_name_group_zone != '')?$this->stri_name_group_zone : 'null';
        
        
        $this->obj_javascripter->addFunctionOnce("
         $(function()
        {
        
            //  *** Vérfication ques zones à l'état reduit  *** ///
            $('.not_selection.".$stri_name_group_zone."').click(function()
            {
            var arra_bool = Array();
                $('.not_selection.".$stri_name_group_zone."').each(function()
                {
                    if ($(this).parent().parent().attr('style').indexOf('display: none') === -1)
                    {
                        if ($(this).find('span').attr('class').indexOf('ui-icon-triangle-1-n') === -1)
                        {
                            arra_bool.push(true);    //Réduit
                        }
                        else
                        {
                            arra_bool.push(false);   //Agrandit
                        }
                    }
                });
               


                //Parcours de la collection et vérification si réduit, Sinon return
                var int_nb_false=null;
                var int_nb_true=null;
                for (i=0; i<arra_bool.length ; i++)
                {
                    if (arra_bool[i] == false)
                    {
                        int_nb_false=int_nb_false+1;
                    }
                    else
                    {
                        int_nb_true=int_nb_true+1;
                    }
                }
                
                if (arra_bool.length == int_nb_false && arra_bool[0]==false)
                {
                    //Convertit le boutton agrandir / réduire 
                    $('#".$stri_name_group_zone."').removeClass('reductAll expandAll');
                    $('#".$stri_name_group_zone."').addClass('reductAll');
                    $('#".$stri_name_group_zone."').removeAttr('onclick');
                    $('#".$stri_name_group_zone."').attr('onClick','hideZone_".$stri_name_group_zone."()');
                }
                
                if (arra_bool.length == int_nb_true && arra_bool[0]==true)
                {
                    //Convertit le boutton agrandir / réduire 
                    $('#".$stri_name_group_zone."').removeClass('reductAll expandAll');
                    $('#".$stri_name_group_zone."').addClass('expandAll');
                    $('#".$stri_name_group_zone."').removeAttr('onclick');
                    $('#".$stri_name_group_zone."').attr('onClick','showZone_".$stri_name_group_zone."()');
                }
                
                
                    
                
            
            });

   

    });    
        ");
        
        
        
        $this->obj_javascripter->addFunction("
        
        //Masque ou affiche le groupe de zones ayant le meme nom que le boutton reduire/agrandir    (attr on click)
        function hideZone_".$this->stri_name_group_zone."()
            {
                //$('.reductible.".$this->stri_name_group_zone."').hide();
                $('.reductible.".$this->stri_name_group_zone."').children().slideUp();
                $('.not_selection.".$this->stri_name_group_zone."').find('span').removeClass();
                $('.not_selection.".$this->stri_name_group_zone."').find('span').addClass('ui-icon ui-icon-triangle-1-s');
                $('#".$this->stri_name_group_zone."').toggleClass('expandAll reductAll');
                $('#".$this->stri_name_group_zone."').removeAttr('onclick');
                $('#".$this->stri_name_group_zone."').attr('onClick','showZone_".$this->stri_name_group_zone."()');
            }

        //Masque ou affiche le groupe de zones avec le bouton agrandir
        function showZone_".$this->stri_name_group_zone."()
            {
                //$('.reductible.".$this->stri_name_group_zone."').show();
                $('.reductible.".$this->stri_name_group_zone."').children().slideDown();
                $('.not_selection.".$this->stri_name_group_zone."').find('span').removeClass();
                $('.not_selection.".$this->stri_name_group_zone."').find('span').addClass('ui-icon ui-icon-triangle-1-n');
                $('#".$this->stri_name_group_zone."').toggleClass('expandAll reductAll');
                $('#".$this->stri_name_group_zone."').removeAttr('onclick');
                $('#".$this->stri_name_group_zone."').attr('onClick','hideZone_".$this->stri_name_group_zone."()');
            }
        ");
    }
    /*     * ******************************************************************
     * Pour construire l'interface html du bandeau Détails-Récap de gauche
     * 
     * Parametres : aucun 
     * Retour : Object table
     * ********************************************************************** */
    public function htmlValue($bool_reduct=false,$bool_sous_zone = false) {

        $obj_font_title = new font($this->stri_title, true, false, false);
        
        $stri_style= ($bool_sous_zone)?"font-weight :bold;;":"font-size:14px;";
        $obj_font_title->setStyle($stri_style);


        $obj_table_zone = new table();

        $obj_table_zone->setWidth('100%');
        $obj_table_zone->setClass("contenu");
        $obj_table_zone->setStyle('margin-top:10px;');
        
        //Dans le cas d'une zone not visible
        if ($this->bool_visible == false) {
            $obj_table_zone->setStyle('display: none; ');
        }
        $obj_tr = $obj_table_zone->addTr();
        
        $stri_class = ($bool_sous_zone)?"entete titre3":"titre3";
        
            $obj_tr->setClass($stri_class." not_selection ".$this->stri_name_group_zone); //ajoute le nom du groupe auquel la zone appartient
            $obj_tr->setOnclick("AfficheTr($(this));");
            $obj_tr->setstyle('cursor:pointer');
            
        //Dans le cas d'une zone not visible
        if ($bool_reduct==true)
        {
            $obj_td = $obj_tr->addTd($obj_font_title->htmlValue() . '<span style="float:right;"class="ui-icon ui-icon-triangle-1-s"> </span>');
        }
        else
        {
            $obj_td = $obj_tr->addTd($obj_font_title->htmlValue() . '<span style="float:right;"class="ui-icon ui-icon-triangle-1-n"> </span>');
        }
            
        $obj_tr = $obj_table_zone->addTr();
            $obj_tr->setClass("reductible ".$this->stri_name_group_zone);   //ajoute le nom du groupe auqel la zone appartient
                $obj_td = $obj_tr->addTd($this->obj_contenu);
                    $obj_td->setAlign("center");
            if ($bool_reduct==true)
            {
                $obj_td->setstyle('display :none;');
            }
            
        
        //construit et ajoute les function javascript    
        $this->javascriptValue();
            
            
        $stri_return=$this->obj_javascripter->javascriptValue().$obj_table_zone->htmlValue();
        
        //Retourne la javascript et la zone sous forme HTML
        return $stri_return;
    }

}

?>
