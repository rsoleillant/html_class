<?php
/*******************************************************************************
Create Date : 12/01/2012
 ----------------------------------------------------------------------
 Class name : calendar_jquery
 Version : 1.0
 Author : Lucie Prost
 Description : élément jquery "calendar" permettant de créer un champ calendar
(champ texte avec affichage d'un calendrier lors d'un clic)
********************************************************************************/
class calendar_jquery extends text {
   
 //**** attribute *************************************************************
 protected static $int_nb_instance; //Le nombre d'instance de la classe
 protected $stri_format;            //Format de la date
 //**** constructor ***********************************************************
  function __construct($stri_name, $stri_value="") 
  { 
    self::$int_nb_instance++;
    parent::__construct($stri_name, $stri_value);
    $this->stri_id=self::$int_nb_instance."_".$stri_name;
    $this->stri_format='dd/mm/yy';
      
  }
 
  //**** setter ****************************************************************
   public function setFormat($value){$this->stri_format=$value;}


  //**** getter ****************************************************************
   public function getFormat(){return $this->stri_format;}

  //**** public method ********************************************************* 
  //fonction jquery permettant d'afficher le slider
  public function jqueryValue(){

      $stri_jquery = 	"<script>
                          function initCalendar(obj_input)
                          {                        
                           var obj_input=$(obj_input);
                           if(obj_input.data('initialized'))//si on a déjà fait l initialization
                           {return;}
                            
                            //- gestion d'attribution d'id
                            var stri_id= obj_input.attr('id');
                            if(stri_id=='')//si pas d'id existant
                            {   
                              var obj_date=new Date();
                              var stri_unique_id='id_'+obj_date.getTime().toString();
                              obj_input.attr('id',stri_unique_id);//attribution d'un id                                      obj_input.attr('id',stri_unique_id);//changement d'id pour corriger bug sur clonage                      
                            }                                           
                                
                            //- init du picker                                                   
                            obj_input.datepicker({dateFormat: '".$this->stri_format."'});
                            
                            //- marquage comme unitialisé
                            obj_input.data('initialized',true);
                            
                                   
                          }
		                        
                        </script>";
	                     
	   return $stri_jquery;
  }
  
   
  public function htmlValue()
  {
     
     $this->setOnmouseOver("initCalendar(this);");//l'initialisation se fait sur onmouseover, permet de supporter le clonage
     $stri_res=parent::htmlValue();//construction du htmlValue de la classe text
     
     return $stri_res.$this->jqueryValue();
  }
  
  //**** method for serialization **********************************************
 
}

?>
