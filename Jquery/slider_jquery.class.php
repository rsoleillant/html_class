<?php
/*******************************************************************************
Create Date : 04/11/2011
 ----------------------------------------------------------------------
 Class name : slider_jquery
 Version : 1.0
 Author : Lucie Prost
 Description : élément jquery "slider" 
(curseur simple, permet de sélectionner une donnée)
********************************************************************************/
class slider_jquery extends serialisable {
   
   //**** attribute ************************************************************
    
   protected $stri_id="";              //id du slider
   protected $stri_description="";     //description affichée
   protected $stri_name="";            //nom du slider
   protected $int_step="5";            //pas entre 2 positions du slider
   protected $int_min="0";             //valeur minimum du slider
   protected $int_max="100";           //valeur maximum du slider
   protected $int_default_value="25";  //valeur par défaut
   protected $bool_disabled;            //Pour désactiver les champs 
 
  //**** constructor ***********************************************************
  function __construct()
  {
   $arra_args=func_get_args();
   $int_nb_arg=count($arra_args);
   
   if($int_nb_arg==7)
   {
    $this->__construct1($arra_args[0], $arra_args[1], $arra_args[2], $arra_args[3], $arra_args[4],$arra_args[5], $arra_args[6]);
   }
   
   if($int_nb_arg==2)
   {
    $this->__construct2($arra_args[0], $arra_args[1]);
   }
  }
  
  function __construct1($stri_id, $stri_name, $stri_description, $int_step, $int_min,$int_max, $int_default_value) 
  { 
    $this->stri_id=$stri_id."_id";
    $this->stri_name=$stri_name;
    $this->stri_description=$stri_description;
    $this->int_step = $int_step;
    $this->int_min = $int_min;
    $this->int_max = $int_max;
    $this->int_default_value = $int_default_value;  
  }
  
   
  function __construct2( $stri_name, $int_value) 
  {               
    $this->stri_id="id_".str_replace(".", "_",  microtime(true));
    $this->stri_name=$stri_name;
    $this->stri_description="";
    $this->int_step = 1;
    $this->int_min =  1;
    $this->int_max = 24;
    $this->int_default_value = $int_value;  
  }
 
  //**** setter ****************************************************************
  public function setDescription($value){$this->stri_description=$value;}
  public function setId($value){$this->stri_id=$value;}
  public function setName($value){$this->stri_name=$value;}
  public function setStep($value){$this->int_step=$value;}
  public function setMin($value){$this->int_min=$value;}
  public function setMax($value){$this->int_max=$value;}
  public function setDefault_value($value){$this->int_default_value=$value;}
  public function setDisabled($value){$this->bool_disabled=$value;}
  
  //**** getter ****************************************************************
   public function getDescription(){return $this->stri_description;}
   public function getId(){return $this->stri_id;}
   public function getName(){return $this->stri_name;}
   public function getStep(){return $this->int_step;}
   public function getMin(){return $this->int_min;}
   public function getMax(){return $this->int_max;}
   public function getDefault_value(){return $this->int_default_value;}
   public function getDisabled(){return $this->bool_disabled;}
   
  //**** public method ********************************************************* 
  //fonction jquery permettant d'afficher le slider
  public function jqueryValue(){
  

      $stri_jquery = 	'<script>';
      
      $stri_jquery.="                   
        function is_object (mixed_var) {
            // Returns true if variable is an object  
            // 
            // version: 1109.2015
            // discuss at: http://phpjs.org/functions/is_object    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
            // +   improved by: Legaev Andrey
            // +   improved by: Michael White (http://getsprink.com)
            // *     example 1: is_object('23');
            // *     returns 1: false    // *     example 2: is_object({foo: 'bar'});
            // *     returns 2: true
            // *     example 3: is_object(null);
            // *     returns 3: false
            if (Object.prototype.toString.call(mixed_var) === '[object Array]') {        return false;
            }
            return mixed_var !== null && typeof mixed_var == 'object';
        }  ";
      
      $stri_jquery.="
                                
                         function initSimpleSlider(obj_div)
                         {
                          
                          var slider=obj_div.find('.slider_simple');                                                      
                           
                          //if(is_object(slider.slider( 'option', 'value' )))  //si l initialisation n a jamais été faites
                          if(obj_div.data('initialized'))
                          {
                           return;
                          }
                             
                              obj_div.data('initialized',true);
                              
                              var input=obj_div.find('.slider_valeur');
                              var value=input.attr('value');  //récupération de la valeur
                              var date =new Date();
                              var id='temp_id_'+date.getTime();  //génération d un id
                              input.attr('id',id);
                           
                              slider.slider({
		                        	range: false,
		                        	step: ".$this->int_step.",
		                         	min: ".$this->int_min.",
		                        	max: ".$this->int_max.",
		                         	value: value,
		                        	slide: function( event, ui ) {
			                         	$( '#'+id ).val( ui.value);
		                        	}
	                         	});
                                              
                         }
                          
                          $(function() {                          
		                        $( '#".$this->stri_id."' ).slider({
		                        	range: false,
		                        	step: ".$this->int_step.",
		                         	min: ".$this->int_min.",
		                        	max: ".$this->int_max.",
		                         	value: [ ".$this->int_default_value." ],
		                        	slide: function( event, ui ) {
			                         	$( '#input_".$this->stri_id."' ).val( ui.value);
		                        	}
	                         	});
		                        $( '#input_".$this->stri_id."' ).val( $( '#".$this->stri_id."' ).slider( 'value'));
                          });   
                        </script>";
	                      
	   return $stri_jquery;
  }
  
   
  public function htmlValue()
  {         //$this->stri_id="id_de_test";
      $stri_disabled=($this->bool_disabled)?"disabled":"";           
      $stri_res = '<div  onmouseover="initSimpleSlider($(this));">
                     <label for="input_'.$this->stri_id.'">'.$this->stri_description.'</label>
      	             <input class="slider_valeur" type="text" id="input_'.$this->stri_id.'" name="'.$this->stri_name.'" '.$stri_disabled.' style="border:0; color:#f6931f; font-weight:bold;" />
                     <div class="slider_simple" id="'.$this->stri_id.'" class="param" style="width:300px;"></div>
                   </div>';

      return $stri_res.$this->jqueryValue();
  }
  

}

?>
