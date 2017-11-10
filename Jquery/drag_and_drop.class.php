<?php
/*******************************************************************************
Create Date : 10/11/2011
 ----------------------------------------------------------------------
 Class name : drag_and_drop
 Version : 1.0
 Author : Lucie Prost
 Description : élément jquery "drag and drop" 
(permet de faire glisser un élément <li> appartenant à une div donnée "draggable" vers une div donnée "droppable")
********************************************************************************/
class drag_and_drop extends serialisable {
   
   //**** attribute ************************************************************
    
   protected $arra_zone_draggable=null;  // tableau des zones draggables
   protected $stri_id="";                 // id du drag and drop
   protected $stri_zone_dropable="";      // nom de la zone dropable
   protected $bool_trash_option="";       // booleen, ajout d'une icone "poubelle" une fois l'élément droppé, lors du click sur cette poubelle => suppression de lélément.
   protected $bool_icon_plus_minus="";
   public $arra_sauv;

  
  //**** constructor ***********************************************************
  function __construct($stri_id, $stri_zone_dropable, $bool_trash_option = true, $bool_icon_plus_minus = true) 
  { 
    $this->stri_id=$stri_id;
    $this->stri_zone_dropable=$stri_zone_dropable; 
    $this->bool_trash_option=$bool_trash_option;  
    $this->bool_icon_plus_minus=$bool_icon_plus_minus; 
  }
 
  //**** setter ****************************************************************
  public function setId($value){$this->stri_id=$value;}
  public function setArra_Zone_draggable($value){$this->arra_zone_draggable=$value;}
  public function setZone_dropable($value){$this->stri_zone_dropable=$value;}
  public function setTrash_option($value){$this->bool_trash_option=$value;}
  public function setIcon_plus_minus($value){$this->bool_icon_plus_minus=$value;}
  
  //**** getter ****************************************************************
   public function getId(){return $this->stri_id;}
   public function getZone_draggable(){return $this->arra_zone_draggable;}
   public function getZone_dropable(){return $this->stri_zone_dropable;}
   public function getTrash_option(){return $this->bool_trash_option;}
   public function getIcon_plus_minus(){return $this->bool_icon_plus_minus;}
  
  //**** public method ********************************************************* 
  
  //fonction permettant l'ajout d'une zone draggable (div)
  public function addZoneDraggable($stri_zone_draggable){
       $this->arra_zone_draggable[] = $stri_zone_draggable;
  }
  
  //fonction jquery permettant le drag and drop
  public function jqueryValue(){
    $stri_jquery = 	'<script>
                      $(function() {';
  //initialisation des zones draggables
    foreach($this->arra_zone_draggable as $zone_draggable){
  		$stri_jquery .= 'var d = $( "#'.$zone_draggable.' li").draggable({
  			appendTo: "body",
  			helper: "clone"
  		});';
		}
		
		//drag and drop
    $stri_jquery .= '   
          		$( "#'.$this->stri_zone_dropable.'" ).droppable({	
          			activeClass: "ui-state-default",
          			hoverClass: "ui-state-hover",

          		  accept: "li",
          			drop: function( event, ui ) {
          				$( this ).find( ".placeholder" ).remove();    		
          			
          			  var parent=ui.draggable.parent();

                 var clone= ui.draggable.clone(); 

                  clone.draggable({
              			appendTo: "body",
              			helper: "clone"
              		});
              		clone.draggable("disable");
              		parent.parent().find("input[type=\'checkbox\']").attr("disabled", true);
                  
                  ui.draggable.after(clone);      
                
                  ui.draggable.appendTo("#'.$this->stri_id.' ol"); ';
    
    
       //utilisation de l'icone "+/-" pour afficher/masquer le contenu de l'option clonée
    if($this->bool_icon_plus_minus == true){          		
      $stri_jquery .= ' ui.draggable.find( ".option-header" ).append( "<span class=\'ui-icon ui-icon-minusthick\' style=\'float: right;\'></span>");
                    		ui.draggable.find( ".option-header .ui-icon" ).parents( ".option:first" ).find( ".option-content" ).toggle();
                        
                        ui.draggable.find(".option-header .ui-icon").click(function() {
                    			$( this ).toggleClass( "ui-icon-plusthick" ).toggleClass( "ui-icon-minusthick" );
                    			$( this ).parents( ".option:first" ).find( ".option-content" ).toggle();
                  		}); ';
		}
		
    //utilisation de l'icone "poubelle" pour supprimer une option clonée
    if($this->bool_trash_option == true){
      $stri_jquery .= 'ui.draggable.find( ".option-header" ).append( "<span class=\'ui-icon ui-icon-trash\' style=\'float: right;\'></span>");
                
                       ui.draggable.find(".ui-icon-trash").click(function(){
                           $(this).parent().parent().parent().remove();//suppression de l\'option dans la liste
                           clone.draggable("enable");
                           parent.parent().find("input[type=\'checkbox\']").attr("disabled", false);
                           parent.parent().find("input[type=\'checkbox\']").attr("checked", false);
                       });  ';  
     } 
     
      
      $stri_jquery .= ' }
                    		})
                    	});     
                    </script>'; 
                 
	   return $stri_jquery;
  }
  
   
  public function htmlValue()
  {
  //création de la zone "droppables"
      $stri_res = '<div id="'.$this->stri_id.'" class="ui-widget-content ui-state-default_clair">     
                  		<ol>
                  			
                  		</ol> 
                  </div>
                  <br><br>
                  <div id="'.$this->stri_zone_dropable.'" style="height:100px;text-align:center;" class="ui-widget-content">
                  		  <h3>Faire glisser les options ici pour les ajouter au contrat</h3><br>
                  		  <img src="images/DragAndDrop.gif" >
                  </div>';

      return $stri_res.$this->jqueryValue();
  }
  
  //**** method for serialization **********************************************
  public function __sleep() 
  {  
    //sérialisation de la classe 
    $this->arra_sauv['id']= $this->stri_id;
    $this->arra_sauv['zone_draggable']= $this->arra_zone_draggable;
    $this->arra_sauv['zone_dropable']= $this->stri_zone_dropable;
    $this->arra_sauv['trash_option']= $this->bool_trash_option;
    $this->arra_sauv['icon_plus_minus']= $this->bool_icon_plus_minus;
    
    return array('arra_sauv');
  }
  
  public function __wakeup() 
  {
    //désérialisation de la classe 

    $this->stri_id= $this->arra_sauv['id'];
    $this->arra_zone_draggable= $this->arra_sauv['zone_draggable'];
    $this->stri_zone_dropable= $this->arra_sauv['zone_dropable'];
    $this->bool_trash_option= $this->arra_sauv['trash_option'];
    $this->bool_icon_plus_minus= $this->arra_sauv['icon_plus_minus'];
    
    $this->arra_sauv = array();
  } 
}

?>
