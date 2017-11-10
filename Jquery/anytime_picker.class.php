<?php
/*******************************************************************************
Create Date : 27/01/2012
 ----------------------------------------------------------------------
 Class name : anytime_picker
 Version : 1.0
 Author : Rémy Soleillant
 Description : élément jquery permettant la saisie d'une date et de l'heure

********************************************************************************/
class anytime_picker extends text {
   
 //**** attribute *************************************************************
 protected static $int_nb_instance; //Le nombre d'instance de la classe
 protected $stri_format;  //Le format de la date
 //**** constructor ***********************************************************
  function __construct($stri_name, $stri_value="") 
  { 
    self::$int_nb_instance++;
    parent::__construct($stri_name, $stri_value);
    //$this->stri_id=self::$int_nb_instance."_".$stri_name;
    $this->stri_format="%d/%m/%Y %T"; 
         
    //inclusion du fichier de langue
    include_once("includes/classes/anytime_picker/pnlang/".pnUserGetLang()."/user.php"); 
  }
 
  //**** setter ****************************************************************
  public function setFormat($value)
  {
  /*
   Formats possibles :
    %a	Abbreviated weekday name (Sun...Sat)
    %B	Abbreviation for Before Common Era (if year<1)*
    %b	Abbreviated month name (Jan...Dec)
    %C	Abbreviation for Common Era (if year>=1)*
    %c	Month, numeric (1..12)
    %D	Day of the month with English suffix (1st, 2nd, ...)
    %d	Day of the month, numeric (00...31)
    %E	Era abbreviation*
    %e	Day of the month, numeric (0...31)
    %H	Hour (00...23)
    %h	Hour (01...12)
    %I	Hour (01...12)
    %i	Minutes, numeric (00...59)
    %k	Hour (0...23)
    %l	Hour (1...12)
    %M	Month name (January...December)
    %m	Month, numeric (01...12)
    %p	AM or PM
    %r	Time, 12-hour (hh:mm:ss followed by AM or PM)
    %S	Seconds (00...59)
    %s	Seconds (00...59)
    %T	Time, 24-hour (hh:mm:ss)
    %W	Weekday name (Sunday...Saturday)
    %w	Day of the week (0=Sunday...6=Saturday)
    %Y	Year, numeric, four digits (possibly signed)
    %y	Year, numeric, two digits (possibly signed)
    %Z	Year, numeric, four digits (no sign)*
    %z	Year, numeric, variable length (no sign)*
    %#	Signed UTC offset in minutes*
    %+	Signed UTC offset in %h%i format*
    %-	Signed UTC offset in %l%i format*
    %:	Signed UTC offset in %h:%i format*
    %;	Signed UTC offset in %l:%i format*
    %@	UTC offset time zone label*
    %%	A literal % character
    
    voir http://www.ama3.com/anytime/
   */
  
    $this->stri_format=$value;
  
  }


  //**** getter ****************************************************************
  public function getFormat(){return $this->stri_format;}

  //**** public method ********************************************************* 
  //fonction jquery permettant d'afficher le slider
  public function jqueryValue(){
            
      $stri_jquery = 	'
      <link rel="stylesheet" type="text/css" href="includes/classes/anytime_picker/anytime.css" />
      <script src="includes/classes/anytime_picker/anytime.js"></script>
      <script>
                          
                    
                          
                           function initAnyTime(field)
                           {      
                              if($(field).hasClass("initialized"))//si le champ est déjà initialisé
                             {return;}  
                             //génération d un identifiant temporaire
                            var temp_id =new Date().getTime();
                            var old_id=field.id;  //sauvegarde de l id actuel
                            field.id=temp_id;
                            $(field).addClass("initialized");//pour ne pas initialiser plusieurs fois
                              
                              $(field).AnyTime_picker(
                              {  
                                format: "'.$this->stri_format.'",
                                labelTitle:"'._LABEL_TITLE.'",
                                labelDayOfMonth:"'._LABEL_DAY_OF_MONTH.'",
                                labelHour:"'._LABEL_HOUR.'",
                                labelMinute:"'._LABEL_MINUTE.'",
                                labelMonth:"'._LABEL_MONTH.'",
                                labelSecond:"'._LABEL_SECOND.'",
                                labelYear:"'._LABEL_YEAR.'",
                                dayAbbreviations:new Array("'._ABREV_DIM.'","'._ABREV_LUN.'","'._ABREV_MAR.'",
                                                           "'._ABREV_MER.'","'._ABREV_JEU.'","'._ABREV_VEN.'","'._ABREV_SAM.'")              
                                
                              } );
                              
                              field.id=old_id; //restauratio de l id d origine  
                           }
                      		              
                        </script>';
	   
                         
                    
	   return $stri_jquery;
  }
  
   
  public function htmlValue()
  {
     $this->setOnmouseOver("initAnyTime(this);");//l'initialisation se fait sur onmouseover, permet de supporter le clonage
     //$this->setOnmouseOver("initAnyTime2(this);");//l'initialisation se fait sur onmouseover, permet de supporter le clonage
      
     $stri_res=parent::htmlValue();//construction du htmlValue de la classe text
     
     return $stri_res.$this->jqueryValue();
  }
  
  //**** method for serialization **********************************************
 
}

?>
