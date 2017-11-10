<?php
/*******************************************************************************
Create Date : 02/01/2013
 ----------------------------------------------------------------------
 Class name : date-range
 Version : 1.0
 Author : Christophe CAYUELA
 Description : élément jquery "datepicker" permettant de faire l'interface 
 d'une date à une autre.
********************************************************************************/
class date_range{
   
 //**** attribute *************************************************************
  protected $stri_name; //Nom qui servira aux deux datepickers 
  protected $stri_label1; //Label du premier datepicker
  protected $stri_label2; //Label du deuxième datepicker
  protected $int_display; //Entier pour l'affichage ligne ou colonne
  protected $stri_lang;   //Langue des datepickers
  
  protected $stri_date_from; //Value de date from au format '2009-11-01'
  protected $stri_date_to;   //Value de date to au format '2009-11-01'
  
 /**** constructor ***********************************************************
  * Attributs :
  *  Name (qui servira d'id aussi) des deux champs input
  * Display : 
  *  rien  => affichage colonne
  *  1 => affichage ligne  
  *  ****************************************************************/  
  function __construct($name,$label1,$label2,$affichage="",$lang="eng") 
  { 
    $this->stri_name=$name;
    
    $this->stri_label1=$label1;
    $this->stri_label2=$label2;
    
    $this->int_display=$affichage;
    
    $this->stri_lang=$lang;
    
    //Inclure la langue de l'utilisateur
    $link=dirname(__FILE__)."/date_range_lang/jquery.ui.datepicker-".$this->stri_lang.".js";        
    if (is_file($link))
    { 
      $link="includes/classes/html_class/Jquery/date_range_lang/jquery.ui.datepicker-".$this->stri_lang.".js"; 
      echo'<script type="text/javascript" src="'.$link.'"></script>';
    }    
      
  }
 
  //**** setter ****************************************************************
   public function setDateFrom($mixed_value){$this->stri_date_from=$mixed_value;}
   public function setDateTo($mixed_value){$this->stri_date_to=$mixed_value;}
  //**** getter ****************************************************************
   public function getDateFrom(){ return $this->stri_date_from;}
   public function getDateTo(){return $this->stri_date_to;}
  
  //**** public method ********************************************************* 
  //fonction jquery permettant d'afficher le slider
  public function jqueryValue(){

    $stri_jquery= 	'<script language="javascript">
                          $(function() {';
      
    //Default Language ENG
    $stri_jquery.='$.datepicker.setDefaults($.datepicker.regional["'.$this->stri_lang.'"]);';
    
    $stri_jquery.='    
        $( "#'.$this->stri_name.'_from" ).datepicker({
            showWeek: true, //Montrer numéro semaine
            firstDay: 1,
            changeYear: true,
            onClose: function( selectedDate ) {
                $( "#'.$this->stri_name.'_to" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#'.$this->stri_name.'_to" ).datepicker({
            showWeek: true,
            firstDay: 1,
            changeYear: true,
            onClose: function( selectedDate ) {
                $( "#'.$this->stri_name.'_from" ).datepicker( "option", "maxDate", selectedDate );
            }
        
        });
        var DateFrom = "'.$this->stri_date_from.'";
        var parsedDateFrom = $.datepicker.parseDate("yy-mm-dd", DateFrom);
        
        var DateTo = "'.$this->stri_date_to.'";
        var parsedDateTo = $.datepicker.parseDate("yy-mm-dd", DateTo);
        
        $( "#'.$this->stri_name.'_from" ).datepicker("setDate",parsedDateFrom);
        $( "#'.$this->stri_name.'_to" ).datepicker("setDate",parsedDateTo);
    });
		                        
                        </script>';
	   
                        
	   return $stri_jquery;
  }
  
   
  public function htmlValue()
  {
     //Objet pour le table
     $input1= new text($this->stri_name.'_from',"");
     $input1->setId($this->stri_name.'_from');
     $input1->setStyle("width:80px;");
     
     $input2= new text($this->stri_name.'_to',"");
     $input2->setId($this->stri_name.'_to');
     $input2->setStyle("width:80px;");
     
     $obj_main_table= new table();
     
     //Affichage ligne sinon affichage colonne
     if ($this->int_display==1)
     {
        $obj_tr=$obj_main_table->addTr();
        $obj_td=$obj_tr->addTd($this->stri_label1);
        $obj_td=$obj_tr->addTd($input1);
        $obj_td=$obj_tr->addTd($this->stri_label2);
        $obj_td=$obj_tr->addTd($input2);
     }
     else
     {
       $obj_tr=$obj_main_table->addTr();
        $obj_td=$obj_tr->addTd($this->stri_label1);
        $obj_td->setAlign("center");
        $obj_td->setWidth("50px");
        $obj_td=$obj_tr->addTd($input1);
        $obj_tr=$obj_main_table->addTr();
        $obj_td=$obj_tr->addTd($this->stri_label2);
        $obj_td->setAlign("center");
        $obj_td=$obj_tr->addTd($input2);
     }
     $obj_main_table->setBorder("0");
     
     $stri_res=$obj_main_table->htmlValue();
     return $stri_res.$this->jqueryValue();
  }
  
  //**** method for serialization **********************************************
 
}
      
?>
