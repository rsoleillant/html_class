<?php
/*******************************************************************************
Create Date : 16/05/2013
 ----------------------------------------------------------------------
 Class name :  jsonisable
 Version : 1.0
 Author : Rémy Soleillant
 Description :  Permet de convertir un objet en chaine json
 
********************************************************************************/
abstract class jsonisable{
   
   //**** attribute ************************************************************
   
  
  
  //**** setter ****************************************************************
 
  
  //**** getter ****************************************************************
   
   
   //**** public method *********************************************************
  /*******************************************************************************
	* Pour convertir une donnée mixed en chaine json
	* 
	* Parametres : aucun
	* Retour : string : le code json                         
	*******************************************************************************/
	public static function mixedToJson($mixed_data) 
	{ 
    if((!is_object($mixed_data))&&(!is_array($mixed_data)))//si c'est une donnée simple
    {          
      return jsonisable::simpleTypeToJson($mixed_data);
    }
    
    if(is_array($mixed_data)) //cas d'un tableau
    {

      return jsonisable::arrayToJson($mixed_data);
    }
    
   
    if(is_object($mixed_data)&&(method_exists($mixed_data,'toJson')))//si c'est un objet jsonisable
    {
        
      return $mixed_data->toJson();
    }
    
  }
  
  
  /*******************************************************************************
	* Pour convertir l'objet en chaine json
	* 
	* Parametres : aucun
	* Retour : string : le code json                         
	*******************************************************************************/
	public  function toJson() 
	{ 
  
    //- récupération des attributs et analyse pour voir ceux à convertir
    $arra_attribut=get_object_vars($this);
    $arra_json_part=array();
     
    //- on supprime viewer et manager de la liste des attribut à convertir en json
    unset($arra_attribut['obj_manager']);
    unset($arra_attribut['obj_viewer']);
     
    foreach($arra_attribut as $stri_attribut=>$mixed_value)
    {
     $arra_json_part[]='"'.$stri_attribut.'":'.jsonisable::mixedToJson($mixed_value);
    }
    
    
    //- conversion json
    $stri_json='{"'.get_class($this).'":{'.implode(',',$arra_json_part).'}}';
  
    return $stri_json;
	}
  
/*******************************************************************************
	* Pour convertir l'objet en chaine json
	* 
	* Parametres : aucun
	* Retour : string : le code json                         
	*******************************************************************************/
	public  function toJsonV0() 
	{ 
  
    //- récupération des attributs et analyse pour voir ceux à convertir
    $arra_attribut=get_object_vars($this);
    
    foreach($arra_attribut as $stri_attribut=>$mixed_value)
    {
      if((!is_object($mixed_value))&&(!is_array($mixed_value)))//si c'est un attribut simple
      {          
        $arra_to_convert[$stri_attribut]=$mixed_value;
      }
      
      if(is_array($mixed_value))
      {
        $arra_to_convert[$stri_attribut]=json_encode($mixed_value); 
      }
     
    }
    
    
    //- conversion json
    $stri_json=json_encode(array(get_class($this)=>$arra_to_convert));
  
    return $stri_json;
	}

 /*******************************************************************************
	* Pour convertir un type simple en json
	* Parametres : aucun
	* Retour : string : le code json                         
	*******************************************************************************/
	public  static function simpleTypeToJson($stri_data) 
	{ 
            //return '"'.str_replace(array('"',"\r\n"),array('\"',""),$stri_data).'"';
            $stri_res='"'.str_replace(array("\'",'\\','"',"\r","\n", "\t","&quot;",),array("'",'\\\\','\"',"","", "    ",'\"'),$stri_data).'"';
            return $stri_res;
  }

  /*******************************************************************************
	* Pour convertir l'objet en chaine json sans disposer de la fonction php
	* json_encode  
	* 
	* Parametres : aucun
	* Retour : string : le code json                         
	*******************************************************************************/
	public  static function arrayToJson($arra_data) 
	{ 
    $arra_jsonpart=array();
    foreach($arra_data as $stri_key=>$mixed_value)
    {
      //$stri_value=(is_array($mixed_value))?jsonisable::arrayToJson($mixed_value):'"'.str_replace('"','\"',$mixed_value) .'"';
     // $arra_jsonpart[]='"'.$stri_key.'":'.jsonisable::mixedToJson($mixed_value);
        $arra_jsonpart[]=jsonisable::mixedToJson($mixed_value);
   
    }
           
   // $stri_json='{'.implode(',',$arra_jsonpart).'}';
     $stri_json='['.implode(',',$arra_jsonpart).']';
    return $stri_json; 
  }
 
}

?>
