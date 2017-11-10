<?php
/*******************************************************************************
Create Date : 02/01/2012
 ----------------------------------------------------------------------
 Class name : date-range
 Version : 1.0
 Author : Romain ROBERT
 Description : élément jquery "datepicker" plage de date.
********************************************************************************/
class calendar_range_jquery{
   
 //**** attribute *************************************************************
   protected $stri_name; 
  protected $stri_label1;
  protected $stri_label2;
  protected $int_display;
  protected $stri_lang;
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
    
    
    $link=dirname(__FILE__)."/date_range_lang/jquery.ui.datepicker-".$this->stri_lang.".js";        
    if (is_file($link))
    { 
      $link="includes/classes/html_class/Jquery/date_range_lang/jquery.ui.datepicker-".$this->stri_lang.".js"; 
      echo'<script type="text/javascript" src="'.$link.'"></script>';
    }    
      
  }

  //**** public method ********************************************************* 
  public function jqueryValue(){

    $stri_jquery= 	'<script language="javascript">
                          $(function() {';
      
    //Default Language ENG
    if($this->stri_lang!="eng")
    {$stri_jquery.='$.datepicker.setDefaults($.datepicker.regional["'.$this->stri_lang.'"]);';}
    
    $stri_jquery.='    
        $( "#'.$this->stri_name.'_from" ).datepicker({
            showWeek: true, //Montrer numéro semaine
            firstDay: 1,
            changeYear: true,
            dateFormat: "yy/mm/dd",
            onClose: function( selectedDate ) {
                $( "#'.$this->stri_name.'_to" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#'.$this->stri_name.'_to" ).datepicker({
            showWeek: true,
            firstDay: 1,
            changeYear: true,
            
            dateFormat: "yy/mm/dd",
            onClose: function( selectedDate ) {
                $( "#'.$this->stri_name.'_from" ).datepicker( "option", "maxDate", selectedDate );
            }
        
        });
            
            
       });

                </script>';

	   return $stri_jquery;
  }
  
  //**** public method ********************************************************* 
  public function jqueryValueDate(){

      
      
      
    $stri_jquery= 	'<script language="javascript">
                          $(function() {';
      
    //Default Language ENG
    if($this->stri_lang!="eng")
    {$stri_jquery.='$.datepicker.setDefaults($.datepicker.regional["'.$this->stri_lang.'"]);';}
    
    $stri_jquery.='    
        $( "#'.$this->stri_name.'_from" ).datepicker({
            showWeek: true, //Montrer numéro semaine
            firstDay: 1,
            changeYear: true,
            dateFormat: "yy/mm/dd",
            onClose: function( selectedDate ) {
                $( "#'.$this->stri_name.'_to" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#'.$this->stri_name.'_to" ).datepicker({
            showWeek: true,
            firstDay: 1,
            changeYear: true,
            dateFormat: "yy/mm/dd",
            onClose: function( selectedDate ) {
                $( "#'.$this->stri_name.'_from" ).datepicker( "option", "maxDate", selectedDate );
            }
        
        });
        
        //Date actuelle et permiere date du mois
            var now = new Date();
            var last = new Date(now.getFullYear(), now.getMonth()-3, 1);


            $( "#'.$this->stri_name.'_from" ).datepicker("setDate",last);
            $( "#'.$this->stri_name.'_to" ).datepicker("setDate",now);
            
       });

    </script>';

	   return $stri_jquery;
  }
  
   
  public function htmlValue($value_input_from,$value_input_to,$bool_setDate=true)
  {
     //Objet pour le table
     $input1= new text($this->stri_name.'_from',$value_input_from);
     $input1->setId($this->stri_name.'_from');
     $input1->setStyle("width:125px;");

     $input2= new text($this->stri_name.'_to',$value_input_to);
     $input2->setId($this->stri_name.'_to');
     $input2->setStyle("width:125px;");
     
     $obj_main_table= new table();
     
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
     
     ($bool_setDate==true)?$stri_methode=$this->jqueryValueDate().$obj_main_table->htmlValue(): $stri_methode = $this->jqueryValue().$obj_main_table->htmlValue() ;
     
     return $stri_methode;
  }
  
  //**** method for serialization **********************************************
 
}
      
?>
