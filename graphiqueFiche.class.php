<?php
/*******************************************************************************
Create Date : 14/03/2008
 ----------------------------------------------------------------------
 Class name : graphiqueFiche
 Version : 1.0
 Author : Rémy Soleillant
 Description : élément graphique permettant l'affichage d'un tableau 
              html surmonté d'un titre contenu dans un onglet graphique.
              
********************************************************************************/

include_once("graphiqueOnglet.class.php");
class graphiqueFiche {
   
   /*attribute***********************************************/
   
   protected $stri_title;//texte sur l'onglet de la fiche
   protected $stri_contain;//contenu de la fiche, cela peut être du html
  
  
  /* constructor***************************************************************/
   /*$arra_form is the $_POST before form sending*/
    function __construct($stri_title,$stri_contain) {
       $this->stri_title=$stri_title;
       $this->stri_contain=$stri_contain;
       
       
   }


   /*setter*********************************************************************/
  public function setLibelle($stri_libelle)
  {$this->stri_libelle=$stri_libelle;}
  
  public function setContain($stri_contain)
  {$this->stri_contain=$stri_contain;}
  
  /*getter**********************************************************************/
 
  public function getLibelle()
  { return $this->stri_libelle;} 
  
  public function getContain()
  { return $this->stri_contain;}
   
  
  /*other method****************************************************************/

   /*permet d'inialiser les différents style css utilisée par cet objet
     Cette méthode doit être appellé avant la méthode htmlValue.
     Si plusieurs objet de cett classe sont utilisés, un seul appel à 
     cette méthode est nécessaire
    */
   public function graphiqueInitialisation()
  {
   global $bgcolor1;
   
   $obj_onglet=new graphiqueOnglet("");
   $stri_res=$obj_onglet->graphiqueInitialisation();
   $stri_res.="<style>";
   $stri_res.=".grapiqueFiche
              {
                border: solid 1px black;
                padding : 3px; 
                color:black;
                background-color :  $bgcolor1;
              }";
  
    $stri_res.="</style>";
    return $stri_res;                                                       
  }
  
  /* retourne le code html représentant la fiche*/
  public function htmlValue()
  {
   /*objet du tableau */
   $obj_grOnglet=new graphiqueOnglet($this->stri_title);
   
    $obj_table=new table();
      $obj_tr1=new tr();
        $obj_tr1->addTd($obj_grOnglet->htmlValue());
      $obj_tr2=new tr();
        $obj_td=$obj_tr2->addTd($this->stri_contain);
          $obj_td->setClass("grapiqueFiche");
          
     $obj_table->insertTr($obj_tr1);
     $obj_table->insertTr($obj_tr2);
     
     
     $obj_table->setBorder(0);
     $obj_table->setCellspacing(0);
     $obj_table->setCellpadding(0);
     $obj_table->setWidth("100%");           
    
    return $obj_table->htmlValue(); 
  }
}

?>
