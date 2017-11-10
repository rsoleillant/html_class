<?php
/*******************************************************************************
Create Date : 12/01/2012

 ----------------------------------------------------------------------
 Class name : tr_pattern
 Version : 1.0
 Author : Rémy Soleillant
 Description : Le tr pour la classe table_pattern
********************************************************************************/

class tr_pattern extends tr
{   
  //**** attribute *************************************************************
  protected $int_debut;     //L'indice de début à partir duquel appliquer le modèle
  protected $int_fin;       //L'indice de fin jusqu'auquel appliquer le modèle
  protected $stri_methode;  //La méthode à utiliser pour vérifier si le modèle s'applique
  protected $stri_resume ;   //Le résumé sur le tr à afficher dans un td
  
  //**** constructor ***********************************************************
  function __construct() 
  {
  
  }
  
  
  /*setter*********************************************************************/
  public function setDebut($value){$this->int_debut=$value;}
  public function setFin($value){$this->int_fin=$value;}
  public function setMethode($value){$this->stri_methode=$value;}
  public function setResume($value){$this->stri_resume=$value;}

  
  /*getter**********************************************************************/
  public function getDebut(){return $this->int_debut;}
  public function getFin(){return $this->int_fin;}
  public function getMethode(){return $this->stri_methode;}
  public function getResume(){return $this->stri_resume;}

 
  //**** public method *********************************************************
  
/*************************************************************
  Permet d'ajouter un td au modèle
 
 Paramètres : mixed : int : l'indice de début à partir duquel le modèle va s'appliquer
                      string "end" : fait référence au dernier indice possible de la table sur laquelle on appliquera le modèle
                      string "end-X" : le Xième élément avant le dernier 
              mixed : int : l'indice de fin jusqu'auquel le modèle va s'appliquer
                      string "end" : fait référence au dernier indice possible de la table sur laquelle on appliquera le modèle 
                      string "end-X" : le Xième élément avant le dernier 
              string : le nom de la méthode à appliquer pour tester le tr
 Retour : obj td_pattern : le td nouvellement ajouté
 
 Exemple : $obj_tr=$obj_table_pattern->addTr();
               $obj_td=$obj_tr->addTd();
                  $obj_td->setClass("ma_classe_css"); : va appliquer la classe css sur le td du même indice que le td du modèle
           
           $obj_tr=$obj_table_pattern->addTr();
               $obj_td=$obj_tr->addTd("end-5","end","isDivisibleByTwo"); 
                $obj_td->setStyle("background-color:red;"); : va appliquer le style css sur les 5 dernier td du tr si le td vérifie la méthode isDivisibleByTwo          
  **************************************************************/      
  public function addTd($int_debut="",$int_fin="",$stri_methode="")
  {
   //gestion des paramètres
    $int_indice=count($this->arra_td);
    $int_debut=($int_debut=="")?$int_indice:$int_debut; //par défaut indice dans la collection
    $int_fin=($int_fin=="")?$int_debut:$int_fin; //par défaut la fin vaut le début
     
   //construction du résumé
   $stri_resume=$this->constructResume();
 
   $stri_resume=$this->constructResume();
     
   //ajout du td
    $obj_td=new td_pattern($stri_resume);
    $this->arra_td[]=$obj_td;
   
   //enregistrement des paramètres du modèle
    $obj_td->setDebut($int_debut);
    $obj_td->setFin($int_fin);
    $obj_td->setMethode($stri_methode);
   
   return $obj_td; 
  }
  
   /*************************************************************
  Permet de construire le résumé du tr qui s'affichera si on
  affiche le pattern.
  Le résumé montre sur quels tr le modèle va être appliqué 
  ainsi que les différents attribut qui seront appliqués
 
 Paramètres : aucun
 Retour : string : le résumé
  **************************************************************/     
  public function constructResume()
  {
    $stri_plage=($this->int_debut==$this->int_fin)?$this->int_fin:$this->int_debut."-".$this->int_fin;
    $stri_resume.="Tr : ".$stri_plage." ".$this->stri_methode;
    $arra_attribut=$this->getModelAttribut();
    
    $stri_resume."Attribut :";
    foreach($arra_attribut as $stri_attribut)
    {
      $stri_resume.="<p>$stri_attribut : ".$this->$stri_attribut."</p>";
    }
    
   
    return $stri_resume;
  }
  
    /*************************************************************
  Pour obtenir la liste des attribut qui devront être appliqué
  sur le tr cible
 
 Paramètres : aucun
 Retour : array : la liste des attributs non vide
  **************************************************************/  
  public function getModelAttribut()
  {
     $arra_attribut=get_object_vars($this);
   
   $arra_ignore=array("int_debut","int_fin","stri_resume","arra_td","stri_methode");
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
  Surcharge de la méthode htmlValue.
  Permet d'ajouter automatiquement un td si le tr est vide
 
 Paramètres : aucun
 Retour : string : le code html
  **************************************************************/  
  public function htmlValue()
  {
    if(count($this->arra_td)==0)//si aucun td dans le tr
    {
     $this->addTd();
    }
    return parent::htmlValue();
  }
  

 /*************************************************************
  Permet d'appliquer le modèle actuel sur un tr
 
 Paramètres : obj tr : le tr sur lequel on veut appliquer le modèle
 Retour : aucun
  **************************************************************/         
 public function applyPattern(tr $obj_tr)
 {
   //application des attributs sur le tr
   $arra_attribut=$this->getModelAttribut();
   foreach($arra_attribut as $stri_attribut)
   {
    $stri_base=str_replace(array("int_","stri_"),"",$stri_attribut);
    $stri_base=str_replace("_","",$stri_base);
    $stri_setter="set".$stri_base;
    
    $obj_tr->$stri_setter($this->$stri_attribut);
   }
   
   //application du modèle sur les td
   $arra_td_a_traiter=$obj_tr->getTd();
   $int_fin_max=count($arra_td_a_traiter);
    
    foreach($this->arra_td as $obj_td_modele)
    {    
      //on regarde sur combien de td on doit effectuer le traitement
      $int_debut=$obj_td_modele->getDebut();
      $int_fin=$obj_td_modele->getFin();
      $stri_methode=$obj_td_modele->getMethode();
      
      $int_fin=$this->evalEnd($int_fin,$int_fin_max);
      $int_debut=$this->evalEnd($int_debut,$int_fin_max);
      
     
      for($i=$int_debut;$i<=$int_fin;$i++)
      { 
        $obj_td_a_traiter=$arra_td_a_traiter[$i];//on récupère le td sur lequel poser le modèle               
        if($stri_methode!=="")
         {
          if($this->$stri_methode($i,$obj_td_a_traiter))
          {
           $obj_td_modele->applyPattern($obj_td_a_traiter);
          }
         }
         else
         {$obj_td_modele->applyPattern($obj_td_a_traiter);}
       
      }     
    }
 }
   /*************************************************************
  Permet de calculer l'indice de fin à partir de l'expression
  représentant la fin et du nombre maximal de tr contenu dans la table cible
 
 Paramètres : mixed : int : un indice numérique de fin
                      string "end" : fait référence au dernier indice possible de la table sur laquelle on appliquera le modèle
                      string "end-X" : le Xième élément avant le dernier 
             
              int : l'indice de fin maximal (le nombre de tr dans la table sur laquelle appliquer le pattern)
 Retour : int : l'indice de fin réelle à utiliser
  **************************************************************/      
  public function evalEnd($stri_fin_th,$stri_fin_max)
  { 
    if(is_numeric($stri_fin_th))//si la fin est un nombre, aucun traitement
    {return $stri_fin_th;}
    
    $arra_data=explode("-",$stri_fin_th);
    
    $int_correction=($arra_data[1]!="")?$arra_data[1]+1:1;
   
    $int_fin=$stri_fin_max-$int_correction; 
  
    return $int_fin;  
  }

    /*************************************************************
  Permet de tester si le td est paire
 
 Paramètres : int : l'indice où se trouve le td dans la collection             
              obj td : le td sur lequel porte le test
 Retour : bool : true  : le td vérifie le test
                 false : le td ne vérifie pas le test
  **************************************************************/   
  public function isDivisibleByTwo($int_indice,$obj_td)
  {    
    return ($int_indice%2==0)?true:false;
  
  }
  
    /*************************************************************
  Permet de tester si le td est impaire
 
 Paramètres : int : l'indice où se trouve le td dans la collection             
              obj td : le td sur lequel porte le test
 Retour : bool : true  : le td vérifie le test
                 false : le td ne vérifie pas le test
  **************************************************************/ 
  public function isNotDivisibleByTwo($int_indice,$obj_td)
  {
    return ($int_indice%2==1)?true:false;
  
  }
  
   /*************************************************************
  Permet de tester si le td contient un libellé
 
 Paramètres : int : l'indice où se trouve le td dans la collection             
              obj td : le td sur lequel porte le test
 Retour : bool : true  : le td vérifie le test
                 false : le td ne vérifie pas le test
  **************************************************************/ 
  public function isLibelle($int_indice,$obj_td)
  {
    
    $mixed_value=$obj_td->getValue();
    
    if(is_array($mixed_value))
    {
     $mixed_value=$mixed_value[0];
    }
    
    if(is_object($mixed_value))
    {
       if(get_class($mixed_value)=="font")
       {return true;}
    }
    
    return false;
  }
}
?>
