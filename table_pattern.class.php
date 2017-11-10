<?php
/*******************************************************************************
Create Date : 12/01/2012
 ----------------------------------------------------------------------
 Class name : table_pattern
 Version : 1.0
 Author : R�my Soleillant
 Description : Un mod�le pour appliquer des classe css sur une table classique
********************************************************************************/
//include_once("tr.class.php");
//include_once("td.class.php");
class table_pattern extends table{
   
   /*attribute***********************************************/

   
   /* constructor***************************************************************/
   function __construct() {
      
   }
  
  
   /*setter*********************************************************************/
  
  /*getter**********************************************************************/
 

  
  /*other method****************************************************************/
  
 /*************************************************************
  Permet d'ajouter un tr au mod�le
 
 Param�tres : mixed : int : l'indice de d�but � partir duquel le mod�le va s'appliquer
                      string "end" : fait r�f�rence au dernier indice possible de la table sur laquelle on appliquera le mod�le
                      string "end-X" : le Xi�me �l�ment avant le dernier 
              mixed : int : l'indice de fin jusqu'auquel le mod�le va s'appliquer
                      string "end" : fait r�f�rence au dernier indice possible de la table sur laquelle on appliquera le mod�le 
                      string "end-X" : le Xi�me �l�ment avant le dernier 
              string : le nom de la m�thode � appliquer pour tester le tr
 Retour : obj tr_pattern : le tr nouvellement ajout�
 
 Exemple : $obj_tr=$obj_table_pattern->addTr();
               $obj_tr->setClass("ma_classe_css"); : va appliquer la classe css sur le tr du m�me indice que le tr du mod�le
           
           $obj_tr=$obj_table_pattern->addTr("end-5","end","isDivisibleByTwo");
               $obj_tr->setStyle("background-color:red;"); : va appliquer le style css sur les 5 dernier tr de la table si le tr v�rifie la m�thode isDivisibleByTwo          
  **************************************************************/      
  public function addTr($int_debut="",$int_fin="",$stri_methode="")
  { 
     //gestion des param�tres
      $int_indice=count($this->arra_tr);
      $int_debut=($int_debut=="")?$int_indice:$int_debut; //par d�faut indice dans la collection
      $int_fin=($int_fin=="")?$int_debut:$int_fin; //par d�faut la fin vaut le d�but
  
    //construction du r�sum�
     $stri_resume="Tr : $int_debut-$int_fin $stri_methode";
     
    //ajout du tr
    $obj_tr=new tr_pattern();
    $this->arra_tr[$int_indice]=$obj_tr;
     
    //enregistrement des param�tres d'application du mod�le
        $obj_tr->setDebut($int_debut);
        $obj_tr->setFin($int_fin);
        $obj_tr->setMethode($stri_methode);  
        $obj_tr->setResume($stri_resume);
    return $obj_tr;
  }
  
  /*************************************************************
  Pour obtenir la liste des attribut qui devront �tre appliqu�
  sur le tr cible
 
 Param�tres : aucun
 Retour : array : la liste des attributs non vide
  **************************************************************/  
  public function getModelAttribut()
  {
     $arra_attribut=get_object_vars($this);
   
   $arra_ignore=array("int_debut","int_fin","stri_resume","arra_td","stri_methode","arra_tr","arra_data");
   //recherche des attribut non vide
   foreach($arra_attribut as $stri_attribut=>$stri_value)
   {              
    if(($stri_value!="")&&(!in_array($stri_attribut,$arra_ignore)))
    {
     $arra_non_vide[]=$stri_attribut;
    }
   }
   
   return $arra_non_vide;
  }
  
  /*************************************************************
  Permet d'appliquer le mod�le actuel sur une table
 
 Param�tres : obj table : la table sur laquelle on veut appliquer le mod�le
 Retour : aucun
  **************************************************************/      
  public function applyPattern(table $obj_table)
  {
    //application du pattern sur la table
   $arra_attribut=$this->getModelAttribut();
   foreach($arra_attribut as $stri_attribut)
   {    
    $stri_base=str_replace(array("int_","stri_"),"",$stri_attribut);
    $stri_base=str_replace("_","",$stri_base);
    $stri_setter="set".$stri_base;
    $obj_table->$stri_setter($this->$stri_attribut);
   }
   
    $arra_tr_a_traiter=$obj_table->getTr();
    
    $int_i=0;
   
    $int_fin_max=count($arra_tr_a_traiter);
    foreach($this->arra_tr as $obj_tr_modele)
    {
      
      //on regarde sur combien de tr on doit effectuer le traitement
      $int_debut=$obj_tr_modele->getDebut();
      $int_fin=$obj_tr_modele->getFin();
      $stri_methode=$obj_tr_modele->getMethode();
      
     
      $int_fin=$this->evalEnd($int_fin,$int_fin_max);
      $int_debut=$this->evalEnd($int_debut,$int_fin_max);
      
      for($i=$int_debut;$i<=$int_fin;$i++)
      {
       $obj_tr_a_traiter=$arra_tr_a_traiter[$i];//on r�cup�re le tr sur lequel poser le mod�le
       if($stri_methode!=="")
       {
        if($this->$stri_methode($i,$obj_tr_a_traiter))
        {
         $obj_tr_modele->applyPattern($obj_tr_a_traiter);
        }
       }
       else
       {$obj_tr_modele->applyPattern($obj_tr_a_traiter);}
      }     
    }
   
  }
  
 /*************************************************************
  Permet de calculer l'indice de fin � partir de l'expression
  repr�sentant la fin et du nombre maximal de tr contenu dans la table cible
 
 Param�tres : mixed : int : un indice num�rique de fin
                      string "end" : fait r�f�rence au dernier indice possible de la table sur laquelle on appliquera le mod�le
                      string "end-X" : le Xi�me �l�ment avant le dernier 
             
              int : l'indice de fin maximal (le nombre de tr dans la table sur laquelle appliquer le pattern)
 Retour : int : l'indice de fin r�elle � utiliser
  **************************************************************/      
  protected function evalEnd($stri_fin_th,$stri_fin_max)
  { 
    if(is_numeric($stri_fin_th))//si la fin est un nombre, aucun traitement
    {return $stri_fin_th;}
    
    $arra_data=explode("-",$stri_fin_th);
    
    $int_correction=($arra_data[1]!="")?$arra_data[1]+1:1;
   
    $int_fin=$stri_fin_max-$int_correction; 
  
    return $int_fin;  
  }

  /*************************************************************
  Permet de tester sur le tr est paire
 
 Param�tres : int : l'indice o� se trouve le tr dans la collection             
              obj tr : le tr sur lequel porte le test
 Retour : bool : true  : le tr v�rifie le test
                 false : le tr ne v�rifie pas le test
  **************************************************************/      
  public function isDivisibleByTwo($int_indice,$obj_tr)
  {    
    return ($int_indice%2==0)?true:false;
  
  }
  
  /*************************************************************
  Permet de tester sur le tr est impaire
 
 Param�tres : int : l'indice o� se trouve le tr dans la collection             
              obj tr : le tr sur lequel porte le test
 Retour : bool : true  : le tr v�rifie le test
                 false : le tr ne v�rifie pas le test
  **************************************************************/     
  public function isNotDivisibleByTwo($int_indice,$obj_tr)
  {
    return ($int_indice%2==1)?true:false;
  }
    
}

/* Exemple complet

echo "<h1>D�part</h1>";
$obj_table=new table();
$obj_tr=$obj_table->addTr();
   $obj_td=$obj_tr->addTd("main courrante");
      $obj_td->setColspan(3);
$obj_tr=$obj_table->addTr();
   $obj_td=$obj_tr->addTd("Titre");
   $obj_td=$obj_tr->addTd("D�tail du noeud");
   $obj_td=$obj_tr->addTd("Noeud suivant");     
$obj_tr=$obj_table->addTr();
   $obj_td=$obj_tr->addTd("convertisseur");
    $obj_td->setWidth("30%");
    $obj_td->setValign("top");
   $obj_td=$obj_tr->addTd("le d�tail");
     $obj_td->setWidth("20%");
   $obj_td=$obj_tr->addTd("les noeuds suivants");     
     $obj_td->setValign("top");
  $obj_tr=$obj_table->addTr();
    $obj_td=$obj_tr->addTd("une ligne rouge");
       $obj_td->setColspan(3);
  $obj_tr=$obj_table->addTr();
    $obj_td=$obj_tr->addTd("une ligne bleue");       
   $obj_tr=$obj_table->addTr();
    $obj_td=$obj_tr->addTd("une ligne rouge");
       $obj_td->setColspan(3);
  $obj_tr=$obj_table->addTr();
    $obj_td=$obj_tr->addTd("une ligne bleue");
   $obj_tr=$obj_table->addTr();
      $obj_td=$obj_tr->addTd("un td jaune");
      $obj_td=$obj_tr->addTd("un td vert");
      $obj_td=$obj_tr->addTd("un td jaune");
$obj_table->setWidth("100%");
$obj_table->setBorder(1);

echo $obj_table->htmlValue();

echo "<h1>Pattern</h1>";
$obj_pattern=new table_pattern();
  $obj_tr=$obj_pattern->addTr();
   $obj_td=$obj_tr->addTd();
    $obj_td->setClass("contenu");
  $obj_tr=$obj_pattern->addTr();
    $obj_tr->setClass("titre1");
  $obj_tr=$obj_pattern->addTr();
   $obj_td=$obj_tr->addTd("",2);
    $obj_td->setClass("contenu");
  $obj_tr=$obj_pattern->addTr("","end-1","isDivisibleByTwo");
    $obj_tr->setStyle("background-color:blue;");
    $obj_td=$obj_tr->addTd();
       $obj_td->setColspan(3);
  $obj_tr=$obj_pattern->addTr(3,"end-1","isNotDivisibleByTwo");
    $obj_tr->setStyle("background-color:red;");
   $obj_tr=$obj_pattern->addTr("end");
    $obj_td=$obj_tr->addTd("","end","isDivisibleByTwo");
      $obj_td->setBgcolor("yellow");
    $obj_td=$obj_tr->addTd("","end","isNotDivisibleByTwo");
      $obj_td->setBgcolor("green");
echo $obj_pattern->htmlValue();

$obj_pattern->applyPattern($obj_table);

echo "<h1>Resultat</h1>";
echo $obj_table->htmlValue();

*/

?>
