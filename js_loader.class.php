<?php
/*******************************************************************************
Create Date : 21/04/2010
 ----------------------------------------------------------------------
 Class name : js_loader.class.php
 Version : 1.0
 Author : Yoann Frommelt
 Description : Charge les differents fichiers JavaScript utiliser dans Savoyeline
*********************************************************************************/

class js_loader {
  //**** attribute *************************************************************
  protected $arra_js_files = array(
    'jquery'=> 'includes/classes/jquery-ui-1.10.3.custom/js/jquery-1.9.1.js', 
    'jquery_migrate'=> 'includes/classes/jquery-ui-1.10.3.custom/js/jquery-migrate-1.2.1.min.js', 
    'jquery-ui'=> 'includes/classes/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.js', 
    
    'modalbox'  =>'includes/modalBox.js',
    'dd'=> 'includes/classes/jquery/msdropdown/js/jquery.dd.js',//Script pour image dans select
    'dropdown'=> 'includes/classes/jquery/css3_dropdown/js/jquery.dropdownPlain.js',
    'placeholder'=>'includes/classes/jquery/placeholder/jquery.placeholder.js',
    'scrollTo'=>'includes/classes/jquery/scrollTo/jquery.scrollto.js',
    
    //Mask Input
    //'mask'=>'includes/classes/jquery/jQueryMask/jquery.mask.min.js',
    'mask'=>'includes/classes/jquery/maskedinput/maskedinput.js',
    'mask_autoNumeric'=>'includes/classes/jquery/maskedinput/autoNumeric.js',
    'mask_number'=>'includes/classes/jquery/maskedinput/jquery.numberMask.js',
    
    'ecran_zone'=>'includes/classes/html_class/pkg_ecran/ecran_zone_js.js',
    'header_scrollable'=>'includes/classes/html_class/Jquery/header_scrollable.js',
    'live_timestamp'=>'includes/classes/html_class/Jquery/live_timestamp.js',
    'fonction_commune'=>'includes/fonction_commune.js'

  ); // tableau des fichier JavaScript
  protected $arra_css_files;  //Les feuilles de style css
  protected $obj_javascripter; // Objet javascripter 
  
  //**** constructor ***********************************************************       
  function __construct() {
	
    $this->obj_javascripter = new javascripter();
     $obj_javascripter= $this->obj_javascripter;
    
  //inclusion de la bibliotheque RGRAPH
 $obj_javascripter->addFile("includes/classes/RGraph/libraries/RGraph.common.core.js");
  $obj_javascripter->addFile("includes/classes/RGraph/libraries/RGraph.common.key.js" );
  $obj_javascripter->addFile("includes/classes/RGraph/libraries/RGraph.drawing.marker1.js");
 $obj_javascripter->addFile("includes/classes/RGraph/libraries/RGraph.bar.js");
 $obj_javascripter->addFile("includes/classes/RGraph/libraries/RGraph.line.js");
 $obj_javascripter->addFile("includes/classes/RGraph/libraries/RGraph.modaldialog.js");
 $obj_javascripter->addFile("includes/classes/RGraph/excanvas/excanvas.js");
 $obj_javascripter->addFile("includes/classes/RGraph/libraries/RGraph.common.dynamic.js");
 $obj_javascripter->addFile("includes/classes/RGraph/libraries/RGraph.common.tooltips.js");
 $obj_javascripter->addFile("includes/classes/RGraph/libraries/RGraph.pie.js");
 $obj_javascripter->addFile("includes/classes/RGraph/libraries/RGraph.common.effects.js");

 //Syntax Highlighter
 $obj_javascripter->addFile("includes/classes/SyntaxHighlighter/scripts/shCore.js");
 $obj_javascripter->addFile("includes/classes/SyntaxHighlighter/scripts/shAutoloader.js");
 
  $this->arra_css_files[]="includes/classes/SyntaxHighlighter/styles/shCore.css";
	$this->arra_css_files[]="includes/classes/SyntaxHighlighter/styles/shThemeDefault.css";
 
   $this->arra_css_files[]="includes/classes/jquery-ui-1.8.16.custom/css/start/ui.selectmenu.css";
    $this->arra_css_files[]="includes/classes/jquery-ui-1.8.16.custom/css/start/jquery-ui-1.8.16.custom.css";
    $this->arra_css_files[]="includes/classes/jquery/jquery-ui-1.9.1.custom/css/cupertino/jquery-ui-1.9.1.custom.css";
    
   $this->arra_css_files[]="includes/classes/jquery/css3_dropdown/css/style.css"; //Chargement css pour les menu_arbre
    $this->arra_css_files[]="includes/classes/jquery/msdropdown/dd.css"; //Chargement css pour image dans select
    
  }  
  
  
  //**** setter ****************************************************************
  public function setJsFiles($arra){$this->arra_js_files=$arra;}
  //**** getter ****************************************************************
  public function getJsFiles(){return $this->arra_js_files;}
   
  //**** public method *********************************************************
  
  public function addFile($stri_src_file,$stri_name_file=-1) {
    if($stri_name_file != -1) {
      $this->arra_js_files[$stri_name_file] = $stri_src_file;
    }
    else {
      $this->arra_js_files[count($this->arra_js_files)] = $stri_src_file;
    }
    
  }
  
  public function removeFile($stri_file_name) {
    unset($this->arra_js_files[$stri_file_name]);
  }
         
  public function htmlValue() {
    foreach($this->arra_js_files as $stri_src_file) {
      $this->obj_javascripter->addFile($stri_src_file);
    }
    
    //Gestion du cache navigateur web 
    //$int_num_version = __CACHE_CONTROL_VERSION;
    //Gestion du cache navigayteur web 
    //$int_num_version = defined('__CACHE_CONTROL_VERSION') ? __CACHE_CONTROL_VERSION : date('Ymd');
    $stri_num_version = defined('__CACHE_CONTROL_VERSION') ? '?version='.__CACHE_CONTROL_VERSION : '';
    
    
    $stri_css="";
    foreach($this->arra_css_files as $stri_link)
    {
     $stri_pattern='<link type="text/css" href="'.$stri_link.$stri_num_version.'" rel="Stylesheet">';
     $stri_css.=$stri_pattern;
    }
    return $stri_css.$this->obj_javascripter->javascriptValue();
  }
}

?>
