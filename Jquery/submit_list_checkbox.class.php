<?php
/*******************************************************************************
Create Date : 25/11/2011
 ----------------------------------------------------------------------
 Class name : submit_list_checkbox
 Version : 1.0
 Author : Lucie Prost
 Description : �l�ment jquery "submit list checkbox" 
(permet d'envoyer toutes les contenus de type "li" de checkbox � l'�tat "checked" vers une zone droppable) 
(fonctionne avec les option_jquery)
********************************************************************************/
class submit_list_checkbox extends serialisable {
   
   //**** attribute ************************************************************
   protected $stri_destination_zone="";     // nom de la zone de destination
   protected $bool_trash_option="";         // booleen, ajout d'une icone "poubelle" une fois l'�l�ment dropp�, lors du click sur cette poubelle => suppression de l�l�ment.
   protected $stri_submit_title="";         // titre qui apparait lors du survol du bouton submit
   protected $bool_icon_plus_minus="";      // utilisation d'icone +/- pour afficher du contenu d'options clon�es
   public $arra_sauv;

  
  //**** constructor ***********************************************************
  function __construct($stri_destination_zone, $bool_trash_option = true, $bool_icon_plus_minus = true) 
  { 
    $this->stri_destination_zone=$stri_destination_zone; 
    $this->bool_trash_option=$bool_trash_option;
    $this->bool_icon_plus_minus=$bool_icon_plus_minus;   
  }
 
  //**** setter ****************************************************************
  public function setCheckbox_class($value){$this->stri_checkbox_class=$value;}
  public function setDestination_zone($value){$this->stri_destination_zone=$value;}
  public function setTrash_option($value){$this->bool_trash_option=$value;}
  public function setSubmit_title($value){$this->stri_submit_title=$value;}
  public function setIcon_plus_minus($value){$this->bool_icon_plus_minus=$value;}
  
  //**** getter ****************************************************************
   public function getCheckbox_class(){return $this->stri_checkbox_class;}
   public function getDestination_zone(){return $this->stri_destination_zone;}
   public function getTrash_option(){return $this->bool_trash_option;}
   public function getSubmit_title(){return $this->stri_submit_title;}
   public function getIcon_plus_minus(){return $this->bool_icon_plus_minus;}
  
  //**** public method ********************************************************* 

  //fonction jquery permettant le drop des checkbox
  public function jqueryValue(){
     //clonage de chaque option s�lectionn�e vers la zone de destination          
    $stri_jquery = 	'<script>
                        $( "#submit_checkbox_list" ).click(function() {
                          $("input[type=\'checkbox\']:checked").each(function(){
                          var checkbox = $(this).parent().parent().parent().find("li");
                            if(!$(this).is(":disabled")){
                              
                              var clone = checkbox.clone(); 
                           
                              clone.appendTo("#'.$this->stri_destination_zone.' ol");
                              $(this).attr("disabled", true);
                              checkbox.draggable("disable");
                            }
                            
                            
                            
                         ';
                         
    //ajout de l'icone "+/-" permettant d'afficher (ou non) le contenu de l'option
     if($this->bool_icon_plus_minus == true){ 
      $stri_jquery .= ' $(clone).find( ".option-header" ).append( "<span class=\'ui-icon ui-icon-minusthick\' style=\'float: right;\'></span>");
                    		$(clone).find(".option-header .ui-icon").parents( ".option:first" ).find( ".option-content" ).toggle();
                    		
                         $(clone).find(".option-header .ui-icon").click(function() {
                        	$( this ).toggleClass( "ui-icon-plusthick" ).toggleClass( "ui-icon-minusthick" );
                    			$( this ).parents( ".option:first" ).find( ".option-content" ).toggle();
  		                  });';
  	 }
  	 
     //ajout de l'icone "poubelle" et de sa fonctionnalit� de suppression de l'option en cas de clic
    if($this->bool_trash_option == true){
      $stri_jquery .= '$(clone).find( ".option-header" ).append( "<span class=\'ui-icon ui-icon-trash\' style=\'float: right;\'></span>");
                      
                       $(clone).find(".ui-icon-trash").click(function(){
                           $(this).parent().parent().parent().remove();//suppression de l\'option dans la liste
                           $(checkbox).parent().parent().find("input[type=\'checkbox\']").attr("disabled", false);
                           $(checkbox).parent().parent().find("input[type=\'checkbox\']").attr("checked", false);
                           checkbox.draggable("enable");
                       });
                        '; 
     }  
      
      $stri_jquery .= ' });
                     }); 
                          
                    </script>'; 
                 
	   return $stri_jquery;
  }
  
   
  public function htmlValue()
  {
      //creation du bouton permettant d'ajouter les options s�lectionn�es � la zone de destination
      $stri_res = '<img src="images/module/PNG/arrow-right-032x032.png" id="submit_checkbox_list" title="'.$this->stri_submit_title.'"/>';

      return $stri_res.$this->jqueryValue();
  }
  
  //**** method for serialization **********************************************
  public function __sleep() 
  {
    //s�rialisation de la classe 
    $this->arra_sauv['destination_zone']= $this->stri_destination_zone;
    $this->arra_sauv['submit_title']= $this->stri_submit_title;
    $this->arra_sauv['icon_plus_minus']= $this->bool_icon_plus_minus;
    $this->arra_sauv['trash_option']= $this->bool_trash_option;
    
    return array('arra_sauv');
  }
  
  public function __wakeup() 
  {
    //d�s�rialisation de la classe 

    $this->stri_destination_zone= $this->arra_sauv['destination_zone'];
    $this->stri_submit_title= $this->arra_sauv['submit_title'];
    $this->bool_icon_plus_minus= $this->arra_sauv['icon_plus_minus'];
    $this->bool_trash_option= $this->arra_sauv['trash_option'];
    
    $this->arra_sauv = array();
  } 
}

?>
