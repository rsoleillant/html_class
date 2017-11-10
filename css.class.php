<?php
/*******************************************************************************
Create Date : 20/08/2009
 ----------------------------------------------------------------------
 Class name : css
 Version : 1.1
 Author : Yannick MARION & Yoann Frommelt
 Description : permet de stocker du style css
 
 Modif class css.class.php
********************************************************************************/
class css {
   
   /*attribute***********************************************/
   
   protected $arra_class;
   protected $arra_file=array();//fichiers contenant du code css
   protected static $arra_file_in=array(); //tab des fichiers ext css déjà inclus
   
  /* constructor***************************************************************/
  function __construct() {}
  
  
  /*other method****************************************************************/
  public function addClass($class) {
   /*$nbr=count($this->arra_class);
   $this->arra_class[$nbr]=$class;*/
   $this->arra_class[]=$class;
  }
  
  public function addFile($src) {
    $this->arra_file[count($this->arra_file)]=$src;
    $this->arra_file[]=$src;
  }  
  
  public function cssValue() {
      
    //Gestion du cache navigateur web 
    //$int_num_version = __CACHE_CONTROL_VERSION;
    //Gestion du cache navigayteur web 
    $stri_num_version = defined('__CACHE_CONTROL_VERSION') ? '?version='.__CACHE_CONTROL_VERSION : '';
    
    
    $stri_res="";
    foreach($this->arra_file as $src) {
      if (!in_array($src, self::$arra_file_in)) {
        //Gestion des espaces blanc en fin de chaines.
        $src = rtrim($src);
        $stri_res.='<link rel="stylesheet" type="text/css" href="'.$src.$stri_num_version.'"/>';
        self::$arra_file_in[count(self::$arra_file_in)] = $src;
      }
    }
    if ($this->arra_class) {
      $stri_res.='<style  type="text/css">';
      for($i=0;$i<count($this->arra_class);$i++) {
        $stri_res.=" ".$this->arra_class[$i];
      }
      $stri_res.="</style>";
    }
    return $stri_res;
  }
}
?>
