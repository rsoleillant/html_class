<?php
/*******************************************************************************
Create Date : 06/03/2012
 ----------------------------------------------------------------------
 Class name : autocomplete_text
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de construire un champ de saisie avec autocomplétion
********************************************************************************/

class autocomplete_text extends text {
   
   /*attribute***********************************************/
    protected $arra_liste_mot;    //La liste des mot à utiliser pour l'autocomplétion
   
  
  //**** constructor ***********************************************************
   function __construct($name,$value="") {
      parent::__construct($name,$value);
      $this->arra_liste_mot=array(); 
   }
  
  
  //**** setter ****************************************************************
  public function getListeMot(){return $this->arra_liste_mot;}
  

  //**** getter ****************************************************************
  public function setListeMot($value){$this->arra_liste_mot=$value;}
  public function setListeMotBySql($stri_sql)
  {
   $obj_query=new querry_select($stri_sql);
    $arra_res=$obj_query->execute();
    foreach($arra_res as $arra_one_res)
    {
     $this->arra_liste_mot[]=$arra_one_res[0];
    }
  }

 
  
  //**** other method **********************************************************
  public function htmlValue()
  {
   $this->stri_id=($this->stri_id!="")?$this->stri_id:"id_".time();//génération d'un id si besoin
   $stri_js='
   <script>
      	$(function() {
      	
      		var availableTags = [
      			"'.implode('","',$this->arra_liste_mot).'"
      		];
    		function explode( val ) {
    			return val.split( /,\s*/ );
    		}
    		function extractLast( term ) {
    	
    			return explode( term ).pop();
    		}
    
    		$( "#'.$this->stri_id.'" )
    			// don t navigate away from the field on tab when selecting an item
    			.bind( "keydown", function( event ) {
    				if ( event.keyCode === $.ui.keyCode.TAB &&
    						$( this ).data( "autocomplete" ).menu.active ) {
    					event.preventDefault();
    				}
    			})
    			.autocomplete({
    				minLength: 0,
    				source: function( request, response ) {
    					// delegate back to autocomplete, but extract the last term
    					response( $.ui.autocomplete.filter(
    						availableTags, extractLast( request.term ) ) );
    				},
    				focus: function() {
    					// prevent value inserted on focus
    					return false;
    				},
    				select: function( event, ui ) {
    					var terms = explode( this.value );
    					// remove the current input
    					terms.pop();
    					// add the selected item
    					terms.push( ui.item.value );
    					// add placeholder to get the comma-and-space at the end
    					terms.push( "" );
    					this.value = terms.join( ", " );
    					return false;
    				}
    			});
    	});
	 </script>
        ';
        
        
    $stri_html=parent::htmlValue();
    
    return $stri_html.$stri_js;    
  }


  
}

?>
