<?php
/*******************************************************************************
Create Date : 14/03/2008
 ----------------------------------------------------------------------
 Class name : graphiqueOnglet
 Version : 1.0
 Author : Rémy Soleillant
 Description : élément graphique permettant l'affichage d'un onglet de taille variable
********************************************************************************/


class graphiqueOnglet {
   
   /*attribute***********************************************/
   
   protected $stri_libelle;//texte affiché à l'interieur de l'onglet
  
  
  /* constructor***************************************************************/
   /*$arra_form is the $_POST before form sending*/
    function __construct($stri_libelle) {
       $this->stri_libelle=$stri_libelle;
       
       
   }


   /*setter*********************************************************************/
  public function setLibelle($stri_libelle)
  {$this->stri_libelle=$stri_libelle;}
  
  /*getter**********************************************************************/
 
  public function getLibelle()
  { return $this->stri_libelle;} 
   
  
  /*other method****************************************************************/

   /*permet d'inialiser les différents style css utilisée par cet objet
     Cette méthode doit être appellé avant la méthode htmlValue.
     Si plusieurs objet de cett classe sont utilisés, un seul appel à 
     cette méthode est nécessaire
    */
   public function graphiqueInitialisation()
  {
   $stri_theme=pnUserGetTheme();
   $stri_res="<style>";
   $stri_res.=".onglet_long1 
                { /*partie gauche de l'onglet */
                  width: 10px; height: 15px;                 
                	margin: 0;                
                	overflow:hidden;
                	font: bold 10px Lucida Grande, Tahoma, Verdana, sans-serif;	
                	color:#000000;
                	text-decoration: none;
                	padding: 7px 0 0 0px;
                	background: url( themes/$stri_theme/images/barleft.gif ) top left no-repeat;
                } ";
    
    $stri_res.=".onglet_long2 
                { /* partie centrale de l'onglet, elle est de longeur variable*/
                  height: 15px;
                	margin: 0;                
                	overflow:hidden;
                	font: bold 12px Lucida Grande, Tahoma, Verdana, sans-serif;	
                	color:#000000;
                	text-decoration: none;
                	padding: 7px 0 0 0px;
                	background: url( themes/$stri_theme/images/barmiddle.gif) top left;
                 }";
    $stri_res.=".onglet_long3 
                { /* partie droite de l'onglet */
                  width: 14px; height: 15px;
                  margin: 0;                
                	overflow:hidden;
                	font: bold 12px Lucida Grande, Tahoma, Verdana, sans-serif;	
                	color:#000000;
                	text-decoration: none;
                	padding: 7px 0 0 0px;
                	background: url( themes/$stri_theme/images/barright.gif ) top left no-repeat;
                 }";             
    $stri_res.="</style>";
    
    return $stri_res;                                                       
  }
  
  /* retourne le code html représentant l'onglet*/
  public function htmlValue()
  {
    $obj_table=new table();
    $obj_tr1=new tr();
    $obj_td=$obj_tr1->addTd(" ");
      $obj_td->setClass("onglet_long1");
    $obj_td=$obj_tr1->addTd($this->stri_libelle);
      $obj_td->setClass("onglet_long2");
    $obj_td=$obj_tr1->addTd(" ");
      $obj_td->setClass("onglet_long3");
    $obj_table->insertTr($obj_tr1);
    $obj_table->setBorder(0);
    $obj_table->setCellspacing(0);
    $obj_table->setCellpadding(0);
    
    return $obj_table->htmlValue(); 
  }
}

?>
