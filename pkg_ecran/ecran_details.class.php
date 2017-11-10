<?php

/* * *****************************************************************************
  Create Date  : 29/11/2012
  ----------------------------------------------------------------------
  Class name  : ecran_deatils
  Version     : 1.0
  Author      : CAYUELA Christophe & ROBERT Romain
  Description : Permet l'affichage du bloc d�tails

 * ****************************************************************************** */

class ecran_details {

//**** Attributs ****************************************************************
    protected $obj_font_title;
    protected $obj_table_ico;
    protected $obj_table_data;
    protected $obj_aide;
    protected $bool_title;
    protected $bool_table_ico;
    protected $bool_scroll;
    protected $title_nowrap = false;

//**** Methodes *****************************************************************  
//*** constructor *************************************************************

    /*     * *****************************************************************************
     * Constructeur du loader d�finissant le sql de base
     * 
     * Parametres : String : title
     * Table : pour les ic�nes
     * String : message d'aide qui safficher si pas de table data
     * Table : Pour les donn�es dans l'�cran d�tails       
     * Retour :                          
     * ***************************************************************************** */
    function __construct($stri_title, $table_ico = "", $stri_aide="", $table_data = "") {
        $this->obj_font_title = $stri_title;
        $this->obj_table_ico = $table_ico;
        $this->obj_table_data = $table_data;
        $this->obj_message_aide = $stri_aide;

        $this->bool_title = true;
        $this->bool_table_ico = true;
        $this->bool_scroll = false;
    }

//*** setter ******************************************************************
    public function setTitle($mixed_value) {
        $this->obj_font_title = $mixed_value;
    }

     public function setBoolScroll($mixed_value) {
        $this->bool_scroll = $mixed_value;
    }

    
    public function setTableIco($mixed_value) {
        $this->obj_table_ico = $mixed_value;
    }

    public function setTableData($mixed_value) {
        $this->obj_table_data = $mixed_value;
    }

    public function setMessageAide($mixed_value) {
        $this->obj_message_aide = $mixed_value;
    }

//*** getter ******************************************************************
    public function getTitle() {
        return $this->obj_font_title;
    }
    
    public function getBoolScroll() {
        return $this->bool_scroll;
    }

    public function getTableIco() {
        return $this->obj_table_ico;
    }

    public function getTableData() {
        return $this->obj_table_data;
    }

    public function getMessageAide() {
        return $this->obj_message_aide;
    }

//*********Enable/Disable ************************************
    public function setEnableTitle($mixed_value) {
        $this->bool_title = $mixed_value;
    }

    public function setEnableTableLogo($mixed_value) {
        $this->bool_table_ico = $mixed_value;
    }

//**********Nowrap******************************************************  
    public function setTitleNowrap($mixed_value) {
        $this->title_nowrap = $mixed_value;
    }

//*** M�thodes d'affichage ****************************************************

    /*     * *****************************************************************************
     * Pour construire l'interface html de recherche
     * 
     * Parametres : aucun 
     * Retour : strin : le code html                         
     * ***************************************************************************** */
    public function htmlValue() {
        
        
        if ($this->bool_scroll)
        {
                    //  INTERNE  //
                
             //Permet de faire flotter la div d�tails lors d'un scroll sur l'�cran
            echo "
            <script>
            $(document).ready(function () {  
            

                
                function getEndMod()
                {

                    //R�cupere et retourne le top du flag en_mod d�fini dans 
                    var end_mod =  $('#flag__end_mod').offset().top;
                    return end_mod;
                }
                
                function getEndDetails()
                {
                    var bottom = $('#details').offset().top + parseFloat($('#details').height());
                    return bottom;
                }
    

              
    
                var top = $('#details').offset().top - parseFloat($('#details').css('marginTop').replace(/auto/, 0));
                var left = $('#details').position().left;

                $(window).scroll(function (event) {
                
                      
                
                
                      // what the y position of the scroll is
                      var y = $(this).scrollTop();

                      var x = $(this).scrollLeft();

                      var end_mod = getEndMod();
                      var end_details = getEndDetails();
                      
                      

                    // whether that's below the form
                     if (y >= top && y < end_mod) 
                     {
                       // if so, ad the fixed class
                       $('#details').addClass('fixed');
                       
                     } 
                     else 
                        {
                          // otherwise remove it
                          $('#details').removeClass('fixed');
                        }
                      
                    //Si div details franchit la fin du module () div en fin d'index.php
                    if (end_details >= end_mod )
                    {
                        //$('#details').hide();
                    }
                    else
                      {
                           $('#details').show();
                      }
                      

                    //Lors d'un scroll horizontal
                    if (x > 0 )
                      {
                           //$('#details').removeClass('fixed');
                      }
                      $('#details').css('left', left - $(window).scrollLeft() );

                        

                });
            });
            </script>
            <style>
                #details.fixed 
                {
                    position: fixed;
                    top: 0;
                    /*width :15.15%;*/
                    /*width :17%;*/
                    /*width :300px;*/
                    
                    
                    width :inherit;
                    min-width :inherit;

                }
            </style>";
                
                 
            /*echo '  
            <script>
                $(function()
                {
                    $("#details").headerScrollable(
                        {
                            "background-image": "url(images/MaJ_graphique/circuit_imprimee.png)",
                            "background-repeat": "repeat",
                            "position" : "fixed",
                            "top" : "0px"
                        }
                    );
                });
            </script> ';
             * 
             */
            
        }
            
        
       
        
        //Objet titre
        $obj_font_detail = new font($this->obj_font_title, true);
        $obj_font_detail->setStyle('font-size:17px');

        $obj_table_detail_int = new table();
        $obj_table_detail_int->setWidth('100%');
        $obj_table_detail_int->setId("id_title_recap");
        $obj_tr = $obj_table_detail_int->addTr();
        if ($this->bool_title) {
            $obj_td = $obj_tr->addTd($obj_font_detail);
        } else {
            $obj_td = $obj_tr->addTd("");
        }

        $obj_td->setWidth("100%");

        if ($this->title_nowrap)
            $obj_td->setNowrap(true);

        if ($this->bool_table_ico) {
            $obj_td = $obj_tr->addTd($this->obj_table_ico);
        } else {
            $obj_td = $obj_tr->addTd("");
        }

        $obj_td->setAlign("right");
        $obj_td->setNowrap(true);
        
        if ($this->obj_table_data == "") {
            
            $obj_font_messdet = new font($this->obj_message_aide, false, true, false);
            $obj_font_messdet->setSize(2);
            $stri_mess = $obj_font_messdet->htmlValue();
            
            $stri_mess = nl2br($stri_mess); //Messaage avec retour a la ligne
            $obj_div_det = new div('', $stri_mess );
                $obj_div_det->setStyle("min-width:210px;overflow:none;min-height:430px;padding-top: 10px;text-indent:20px;");
                
        } else {
            $obj_div_det = new div('', $this->obj_table_data);
                $obj_div_det->setStyle("min-width:210px;overflow:none;min-height:430px;padding-top: 10px;");
        }

        
        $obj_div_det->setId("id_div_recap");
        $obj_div_det->setClass("id_div_recap");
        
        
        //Cr�ation table d�tails   
        $obj_table_detail = new table();
        $obj_tr = $obj_table_detail->addTr();
        $obj_td = $obj_tr->addTd($obj_table_detail_int);
        $obj_td->setStyle("padding-left:0.5em;");
        $obj_td->setClass("titre2 entete");
        $obj_tr = $obj_table_detail->addTr();
        $obj_td = $obj_tr->addTd($obj_div_det->htmlValue());
        $obj_table_detail->setStyle("background-image: url(images/MaJ_graphique/circuit_imprimee.png); background-repeat: repeat;margin-top: 5px;");
        $obj_table_detail->setWidth("100%");
        $obj_table_detail->setBorder("0");
        $obj_table_detail->setId("details");
        $obj_table_detail->setClass("details contenu");

        return $obj_table_detail->htmlValue();
    }

}

?>
