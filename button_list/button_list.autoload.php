<?php

//ajout d'une fonction en tant que fonction d'autoload
spl_autoload_register("loadButtonList"); 
     
//définition d'une fonction d'autoload
function loadButtonList($stri_class)
{  
  $stri_path=dirname(__FILE__);
  if (is_file($stri_path."/$stri_class.class.php"))
  {
      $bool_res=include_once($stri_path."/$stri_class.class.php");
  }
  
  
 //- autoload en js
 if(is_file("$stri_path/$stri_class.class.js"))
 { 
   $obj_autoload_js=new javascripter();//un javascripter pour charger automatiquement les classe js
   $stri_relative_path=str_replace($_SERVER['DOCUMENT_ROOT'],'', $stri_path);
   $obj_autoload_js->addFile($stri_relative_path.'/'.$stri_class.'.class.js');
   echo $obj_autoload_js->javascriptValue();
 } 
}
if(function_exists('pnUserGetLang'))
{
  include_once("pnlang/".pnUserGetLang()."/user.php");
}

?>