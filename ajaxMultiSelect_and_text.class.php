<?php
/*******************************************************************************
Create Date : 27/10/2009
 ----------------------------------------------------------------------
 Class name : ajaxMultiSelect_and_select
 Version : 1.0
 Author : Yoan Le Bihan
 Description : Permet de créer des listes déroulantes multiple en ajax + text
               Cette classe fait automatiquement le lien entre les listes déroulantes html et celles en php
               Elle maintient la cohérence entre ces deux univers grâce à du JS DOM et de l'ajax
********************************************************************************/
include_once($_SERVER['DOCUMENT_ROOT']."includes/classes/html_class/ajaxMultiSelect.class.php");
    
class ajaxMultiSelect_and_text extends ajaxMultiSelect 
{
  //redfinition de la methode html value pour permettre de switcher d'un select a un text  
  public function htmlValue($int_nb_select_par_ligne=1,$stri_where_libelle="left")
  {
  //echo "<pre>";var_dump($bool);echo "</pre>";
  $obj_javascripter=new javascripter();
  $obj_javascripter->addFile("includes/ajaxmultiselect_and_textJS.js");//ajoute le fichier js qui contient les fonctions
  echo $obj_javascripter->javascriptValue();
  
  $parent_htmlvalue = ajaxMultiSelect::htmlValue($int_nb_select_par_ligne,$stri_where_libelle);//on appelle la methode parente
  $arra = ajaxMultiSelect::getArraName();//tableau qui contient tout les 'name' des objets 
  $nb_select = count($arra);//nb d'objet
 
  $arra_to_varchar= implode(",",$arra);//on transforme le tableau en chaine de caractere pour le passer dans les parametres de la fonction js

  $table_text = new table();//nouvelle table pour le input text et l'image

  for($i=0;$i<$nb_select;$i++)
  {
    $name=$arra[$i];//name de l'objet
    //$name_class=$arra[$i]."_class_text";
    $obj_text= new text($name);//nouvel objet text 
    $obj_text->setClass($name);//on lui defini une class
    $obj_text->setDisabled(true);//on le met a disabled par default
    $obj_text->setStyle("display:none;width:155px;");//style par default

    //if ($bool==false)
    //{
    $obj_img_add_out = new img("images/add_out.gif");//nouvelle image
    $obj_img_add_out->setTitle("Cliquer ici pour changer la zone de selection en champ texte ou l'inverse");
    $obj_img_add_out->setOnclick("select_to_text('$arra[$i]','$name','$arra_to_varchar');");//si clique sur l'image on declenche la fonction
    $obj_img_add_out->setStyle("cursor:pointer"); 
    //}
    $tr_text=$table_text->addTr();
    $tr_text->addTd($obj_text);//ajoute l'objet text a la table 
    $tr_text->addTd($obj_img_add_out);//ajoute l'objet image a la table
  } 
  
 $table = new table();//nouvelle table pour regrouper la table parent et table_text
 $tr = $table->addTr();
 $tr->addTd($parent_htmlvalue);
 $tr->addTd($table_text); 
 
return $table->htmlValue();
        
  }//fin de la fonction htmlValue()
}//fin de la classe
?>