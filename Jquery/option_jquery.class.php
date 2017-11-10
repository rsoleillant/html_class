<?php
/*******************************************************************************
Create Date : 04/11/2011
 ----------------------------------------------------------------------
 Class name : option
 Version : 1.0
 Author : Lucie Prost
 Description : élément jquery "option" 
(zone comprenant un titre et une partie 'contenu' que l'on peut afficher ou cacher grace à une icone '+' ou '-')
********************************************************************************/
class option_jquery extends serialisable {
   
   //**** attribute ************************************************************
   
   protected $stri_name=""; 
   protected $stri_id="";      
   protected $stri_content="";
   protected $bool_checkbox=null;
   protected $bool_plus_minus=null;
   public $arra_sauv;

  
  //**** constructor ***********************************************************
  function __construct($stri_id, $stri_name, $stri_content, $bool_plus_minus=true, $bool_checkbox=false) 
  { 
    $this->stri_id=$stri_id;
    $this->stri_name=$stri_name;
    $this->stri_content=$stri_content;
    $this->bool_checkbox=$bool_checkbox;
    $this->bool_plus_minus=$bool_plus_minus;
  }
 
  //**** setter ****************************************************************
  public function setName($value){$this->stri_name=$value;}
  public function setContent($value){$this->stri_content=$value;}
  public function setId($value){$this->stri_id=$value;}
  public function setCheckbox($value){$this->bool_checkbox=$value;}
  public function setPlus_minus($value){$this->bool_plus_minus=$value;}
  
  //**** getter ****************************************************************
   public function getName(){return $this->stri_name;} 
   public function getContent(){return $this->stri_content;}
   public function getId(){return $this->stri_id;}
   public function getCheckbox(){return $this->bool_checkbox;}
   public function getPlus_minus(){return $this->bool_plus_minus;}
  
  //**** public method ********************************************************* 
  //fonction jquery permettant d'afficher ou de masquer le contenu de l'option
  public function jqueryValue(){

      $stri_jquery .= 	' <script>
	
                            	$(function() {	
                            		$( ".'.$this->stri_id.'" ).addClass( " ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
                            			.find( ".'.$this->stri_id.'-header" )
                            				.addClass( "ui-widget-header_3 ui-corner-all" )
                                    .end();
                                    
                            
                            		$( ".'.$this->stri_id.'-header .ui-icon" ).click(function() {
                            			$( this ).toggleClass( "ui-icon-plusthick" ).toggleClass( "ui-icon-minusthick" );
                            			$( this ).parents( ".'.$this->stri_id.':first" ).find( ".'.$this->stri_id.'-content" ).toggle();
                            		});
                            
                            	});
	                      </script>';
	                      
	   return $stri_jquery;
  }
  
   
  public function htmlValue()
  {
      $stri_res = '<ul style="list-style-type:none; padding:0; margin:0;"><table width="100%"><tr>';
      if($this->bool_checkbox == true){ 
         $checkbox = new checkbox($this->stri_id.'_checkbox', $this->stri_id.'_checkbox');
         $stri_res .= '<td><span class="checkbox">'.$checkbox->htmlValue().'</span></td>';
      }
      $stri_res .= '<td width="98%"><li>
                    <div class="'.$this->stri_id.' option" style="margin: 0 1em 0.5em 0;">
              		      <div class="'.$this->stri_id.'-header option-header" style="margin: 0.3em; padding-bottom: 1px; padding-left: 0.2em;">
                        '.$this->stri_name;
      if($this->bool_plus_minus == true){ 
         $stri_res .= '<span class=\'ui-icon ui-icon-plusthick\' style=\'float: right;\'></span>';
      }
      $stri_res .= '    </div>
              		      <div class="'.$this->stri_id.'-content option-content" style="padding: 0.4em; display:none;" >
                            '.$this->stri_content.'
                            <input type="hidden" name="nom_option[]" value="'.$this->stri_id.'">
                        </div>
                    </div>
                  </li></td></tr></table></ul>';

      return $stri_res.$this->jqueryValue();
  }
  
  //**** method for serialization **********************************************
  public function __sleep() 
  {
    //sérialisation de la classe 
    $this->arra_sauv['name']= $this->stri_name;

    return array('arra_sauv');
  }
  
  public function __wakeup() 
  {
    //désérialisation de la classe 

    $this->stri_name= $this->arra_sauv['name'];

    $this->arra_sauv = array();
  } 
}

?>
