<?php
/*******************************************************************************
Create Date : 12/02/2013
 ----------------------------------------------------------------------
 Class name : htmlObjectFinder 
 Version : 1.0
 Author : R�my Soleillant
 Description :  Permet de rechercher des objets html parmi un ensemble d'autres objets html
 
********************************************************************************/
class htmlObjectFinder{
   
  //**** attribute ************************************************************
  protected $arra_search;             //Les objets dans lesquels chercher
  protected $arra_find;               //Les objets trouv�s   
  
  protected $arra_arbre;              //L'arborescence des objets 
  //**** constructor ***********************************************************
 /*************************************************************
 *
 * parametres : 
 * retour : objet de la classe 
 *                        
 **************************************************************/    
  function __construct($mixed_search) 
  { 
    $this->arra_search=(is_array($mixed_search))?$mixed_search:array($mixed_search);
  }
 
  //**** setter ****************************************************************
  public function setSearch($value){$this->arra_search=$value;}
  public function setFind($value){$this->arra_find=$value;}

  
  //**** getter ****************************************************************
  public function getSearch(){return $this->arra_search;}
  public function getFind(){return $this->arra_find;}
 
  
  //**** M�thode de recherche directe ******************************************
 
 /*************************************************************
 * Permet de rechercher un objet poss�dant un attribut name et dont
 * la valeur est celle pass� en param�tre
 *   
 * parametres : string : l'attribut name que l'on recherche
 * retour : array : tableau d'objet r�pondant � la recherche
 *                        
 **************************************************************/    
  public function getElementsByName($stri_name)
  {
    foreach($this->arra_search as $obj_html)
    { 
      $this->getInternalElementsByMethod($stri_name,$obj_html,"getName","compareExact");
    } 
    
    return  $this->arra_find;
  }
  
   /*************************************************************
 * Permet de rechercher un objet poss�dant un attribut name et dont
 * la valeur est celle pass� en param�tre
 *   
 * parametres : string : l'attribut name que l'on recherche
 * retour : array : tableau d'objet r�pondant � la recherche
 *                        
 **************************************************************/    
  public function getInput()
  {
    foreach($this->arra_search as $obj_html)
    { 
    //array('textarea','text_arrea','text','select','calendar_jquery','checkbox')
      $this->getInternalElementsByInterface("inputHtml",$obj_html);
    } 
    
    return  $this->arra_find;
  }
  
 /*************************************************************
 * Permet de rechercher un objet poss�dant un attribut classe css
 * dont la valeur est celle pass� en param�tre 
 *   
 * parametres : string : l'attribut name que l'on recherche
 * retour : array : tableau d'objet r�pondant � la recherche
 *                        
 **************************************************************/    
  public function getElementsByClassCss($stri_class)
  {
    foreach($this->arra_search as $obj_html)
    { 
      $this->getInternalElementsByMethod($stri_class,$obj_html,"getClass","compareExact");
    } 
    
    return  $this->arra_find;
  }
  
   /*************************************************************
 * Permet de rechercher un objet poss�dant un attribut classe css
 * dont la valeur est diff�rentes de celle pass�e en param�tre 
 *   
 * parametres : string : l'attribut name que l'on recherche
 * retour : array : tableau d'objet r�pondant � la recherche
 *                        
 **************************************************************/    
  public function getElementsByClassCssDifferent($stri_class)
  {
    foreach($this->arra_search as $obj_html)
    { 
      $this->getInternalElementsByMethod($stri_class,$obj_html,"getClass","compareDifferent");
    } 
    
    return  $this->arra_find;
  }
 
/*************************************************************
 * Permet de r�cup�rer tout les �l�ment parent du r�sultat
 * de la recherche 
 *   
 * parametres : 
 * retour : obj : l'objet parent
 *                        
 **************************************************************/    
  public function getParent()
  {
    
     $arra_parent=array();
     foreach($this->arra_search as $obj_html)
     {
     
       $obj_parent=$this->getElementParent($obj_html);    
       $arra_parent[]=$obj_parent;
     }
     
     $this->arra_find=$arra_parent;
     return  $this->arra_find;
  }
 
  //**** M�thode de recherche d�taill�es ************************************** 
/*************************************************************
 * Permet de rechercher un objet poss�dant un attribut name et dont
 * la valeur est celle pass� en param�tre
 *   
 * parametres : string : l'attribut name que l'on recherche
 *              obj : l'objet dans lequel on cherche 
 *              string : la m�thode d'acc�s � l'attribut
 *              string : la m�thode de comparaison � utiliser  
 * retour : array : tableau d'objet r�pondant � la recherche
 *                        
 **************************************************************/    
  public function getInternalElementsByMethod($stri_name,$obj_html,$stri_method_acces,$stri_method_comparaison)
  {  
     
       //- recherche de l'attribut
       if(method_exists($obj_html,$stri_method_acces))//si on est sur un objet qui poss�de l'attribut cherch�
       {
         if($this->$stri_method_comparaison($obj_html->$stri_method_acces(),$stri_name))
         //if($obj_html->$stri_method_acces()==$stri_name)//si l'�l�ment poss�de le nom cherch�
         {$this->arra_find[]=$obj_html;}
    
       }
       
       //- recherche parmi les fils
       $arra_fils=$this->getElementsFils($obj_html);
      
       foreach($arra_fils as $obj_fils)
       {
        $fils_id=spl_object_hash($obj_fils);
        $this->arra_arbre[$fils_id]=$obj_html;//Stockage du p�re
        $this->getInternalElementsByMethod($stri_name,$obj_fils,$stri_method_acces,$stri_method_comparaison);
       }
     
  }
  
/*************************************************************
 * Permet de rechercher un objet poss�dant un attribut name et dont
 * la valeur est celle pass� en param�tre
 *   
 * parametres : string : la classe de l'objet que l'on recherche
 *              obj : l'objet dans lequel on cherche 
 *            
 * retour : array : tableau d'objet r�pondant � la recherche
 *                        
 **************************************************************/    
  public function getInternalElementsByClass($arra_class,$obj_html)
  {  
       //- recherche de l'attribut
       $stri_class=get_class($obj_html);   
       
       if(in_array($stri_class,$arra_class))//si on est sur un objet qui poss�de l'attribut cherch�
       {
          $this->arra_find[]=$obj_html;
       }
       
       //- recherche parmi les fils
       $arra_fils=$this->getElementsFils($obj_html);
      
       foreach($arra_fils as $obj_fils)
       {
        $fils_id=spl_object_hash($obj_fils);
        $this->arra_arbre[$fils_id]=$obj_html;//Stockage du p�re
        $this->getInternalElementsByClass($arra_class,$obj_fils);
       }
     
  }
  
/*************************************************************
 * Permet de rechercher un objet impl�mentant une interface dont
 * la valeur est celle pass� en param�tre
 *   
 * parametres : string : la classe de l'objet que l'on recherche
 *              obj : l'objet dans lequel on cherche 
 *            
 * retour : array : tableau d'objet r�pondant � la recherche
 *                        
 **************************************************************/    
  public function getInternalElementsByInterface($stri_interface,$obj_html)
  {  
       //- recherche de l'attribut
       $stri_class=get_class($obj_html);   
       $arra_implements=class_implements($obj_html);
      
       if(isset($arra_implements[$stri_interface]))//si on est sur un objet qui poss�de l'attribut cherch�
       {
          $this->arra_find[]=$obj_html;
       }
       
       //- recherche parmi les fils
       $arra_fils=$this->getElementsFils($obj_html);
      
       foreach($arra_fils as $obj_fils)
       {
        $fils_id=spl_object_hash($obj_fils);
        $this->arra_arbre[$fils_id]=$obj_html;//Stockage du p�re
        $this->getInternalElementsByInterface($stri_interface,$obj_fils);
       }
     
  }

 //**** M�thode de navigation dans l'arborescence ******************************
 
/*************************************************************
 * Permet de rechercher les objets fils d'un objet p�re
 *   
 * parametres : string : l'attribut name que l'on recherche
 * retour : array : tableau d'objet r�pondant � la recherche
 *                        
 **************************************************************/    
  public function getElementsFils($obj_pere)
  {
    $stri_class=get_class($obj_pere);
    //- la m�thode de recherche des fils d�pend du type de classe 
    switch($stri_class)
    {
      case "table":
        return $obj_pere->getTr();//les fils d'une table sont les tr
      break;
      case "tr":
        return $obj_pere->getTd();//les fils d'un tr sont les td
      break;
      case "td":
        $mixed_value=$obj_pere->getValue();//les fils d'un td est sa valeur       
        return (is_array($mixed_value))?$mixed_value:array($mixed_value);    
      break;  
      case "ul":
        return $obj_pere->getId();
      break; 
      case "li":
      case "div":
        $mixed_value=$obj_pere->getContain();   
        return (is_array($mixed_value))?$mixed_value:array($mixed_value);    
      break; 
     
      //ensemble des cas qui n'ont pas de fils
      case false: //noeud textuel 
      case "text":
      case "select":
      case "hidden":
      case "font":
      case "img":
      case "image":
      case "calendar_jquery":
	  case "slider_jquery":
      case "textarea":
      case "radio":
      case "text_arrea":
      case "checkbox":
      case "rgraph_radar":
         return array();
      break;
      default :

    }
    
    //- arriv� ici, on n'a pas trouv� le moyen d'obtenir les fils dans le switch
    if(method_exists($obj_pere, "getViewer"))//s'il s'agit d'un mod�le
    {
      return array($obj_pere->getViewer()); 
    } 
    
    if(method_exists($obj_pere, "getMainTable"))//s'il s'agit d'un viewer
    {
      $obj_pere->htmlValue();
      $obj_pere_pere=$this->getElementParent($obj_pere);
    
      return array($obj_pere->getMainTable()); 
    }  
    
    //- cas par d�faut o� les fils n'ont pas �t� trouv�
    trigger_error("Les fils de la classe $stri_class n'ont pas �t� d�finis",E_USER_WARNING);
    return array();
  }
  
/*************************************************************
 * Permet de rechercher l'�l�ment parent � partir de l'�l�ment fils
 *   
 * parametres : 
 * retour : obj : l'objet parent
 *                        
 **************************************************************/    
  public function getElementParent($obj_fils)
  {
        $fils_id=spl_object_hash($obj_fils);
        return $this->arra_arbre[$fils_id]; 
  }
  

  
  //**** M�thode de comparaison ************************************************
/*************************************************************
 * Permet de comparer deux chaines de fa�on exact
 * 
 * Parametres : string : l'�l�ment � tester
 *              string : l'�l�ment auxuqel comparer  
 * retour : bool : true  : les deux �l�ments sont identique
 *                 false : les deux �l�ments sont diff�rents
 *                        
 **************************************************************/    
  public function compareExact($stri_element,$stri_comparateur)
  {
    return  $stri_element==$stri_comparateur;
  }
  
  /*************************************************************
 * Permet de comparer deux chaines de fa�on exact
 * 
 * Parametres : string : l'�l�ment � tester
 *              array :  tableau d'�l�ment � comparer
 * retour : bool : true  : les deux �l�ments sont identique
 *                 false : les deux �l�ments sont diff�rents
 *                        
 **************************************************************/    
  public function compareExactInArray($stri_comparateur,$arra_element)
  {
    return  in_array($stri_comparateur, $arra_element);
  }
  
  /*************************************************************
 * Permet de comparer si deux chaine sont diff�rente
 * 
 * Parametres : string : l'�l�ment � tester
 *              string : l'�l�ment auxuqel comparer  
 * retour : bool : true  : les deux sont diff�rents
 *                 false : les deux �l�ments sont identiques
 *                        
 **************************************************************/    
  public function compareDifferent($stri_element,$stri_comparateur)
  {
    return  $stri_element!=$stri_comparateur;
  }
  
 //**** M�thode appliquable aux r�sultats **************************************

/*************************************************************
 * Permet d'appliquer une m�thode � l'ensemble des �l�ment du r�sultat
 * parametres : string : la m�thode � appliquer
 *              string : le param�tre � passer 
 * retour : array : tableau d'objet r�pondant � la recherche
 *                        
 **************************************************************/    
  public function applyMethodToResult($stri_method,$stri_parametre)
  {
    foreach($this->arra_find as $obj_html)
    {
      $obj_html->$stri_method($stri_parametre); 
    }
  }
  
/*************************************************************
 * Permet de r�initialiser les attributs afin de d'enchainer
 * les recherche sur les r�sultat pr�c�dant. 
 * parametres : aucun
 * retour : aucun
 *                        
 **************************************************************/    
  public function chainSearch()
  {
   $this->arra_search=$this->arra_find;
   $this->arra_find=array();
  } 
}

?>
