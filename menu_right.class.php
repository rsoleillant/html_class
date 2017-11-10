<?php
/*******************************************************************************
Create Date : 19/10/2008
 ----------------------------------------------------------------------
 Class name : menu_right
 Version : 1.0
 Author : R�my Soleillant
 Description : Permet d'ajouter la notion de droit sur les onglets d'un menu
********************************************************************************/

class menu_right extends menu_dyn
{
  //**** attribute *************************************************************
   private $arra_right=array();//tableau des droits de l'utilisateur courrant sur les onglets
   protected $stri_module;//le module o� se trouve le menu
   protected $stri_id_menu;//l'identifiant du menu
   protected $stri_not_allowed_msg; //Le message qui doit s'afficher lorsque l'utilisateur n'a aucun droit sur le menu 
  //**** constructor ***********************************************************
   
   /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe menu_right   
   *                        
   **************************************************************/         
  function __construct($url, $act_class, $inact_class, $act_src, $inact_src,$call=__FILE__) 
  {
      
    //construit l'objet menu
    //@param : $url => le chemin relatif par lequel on va acc�der au menu
    //@param : $act_class => nom de la classe css � utiliser pour l'onglet actif
    //@param : $inact_class => nom de la classe css � utiliser pour les onglets inactifs
    //@param : $act_src => le chemin de l'image � utiliser pour l'onglet actif
    //@param : $inact_src => le chemin de l'image � utiliser pour les onglets inactifs
    //@return : void
    global $ModName;
    parent::__construct($url, $act_class, $inact_class, $act_src, $inact_src,$call);//appel au constructeur parent
     
    $this->stri_module=$ModName;
    $this->stri_id_menu="menu";
    $this->stri_not_allowed_msg="You are not allowed access this page !";
  }
  //**** setter ****************************************************************
 
    
  //**** getter ****************************************************************
 
  
  //**** public method *********************************************************
  
  /*************************************************************
   * Permet de calculer les diff�rents droits de l'utilisateur sur les onglets 
   * parametres : aucun
   * retour : aucun
   *                        
   **************************************************************/         
  
   /*************************************************************
   * Permet de savoir si l'utilisateur � les droits sur un onglet particulier
   * parametres : string : le nom de l'onglet
   *             
   * retour : bool : true  => l'utilisateur est autoris� � acc�der � l'onglet
   *                 false => l'utilisateur n'est pas autoris� � acc�der � l'onglet         
   **************************************************************/      
  public function isAllowed($stri_onglet_name)
  {             
    if(pnSecAuthAction(0, $this->stri_module.'::', '::', ACCESS_EDIT))//si l'utilisateur est admin sur le module
    {return true;}
    //echo 'pnSecAuthAction(0, '.$this->stri_module.'::, '.$this->stri_id_menu.':'.$stri_onglet_name.':,' .ACCESS_EDIT.')<br />';
    return pnSecAuthAction(0, $this->stri_module.'::', $this->stri_id_menu.':'.$stri_onglet_name.':', ACCESS_EDIT);
  }
  
 /*************************************************************
 * Surcharge de la m�thode m�re pour ajouter le calcul de droit sur l'onglet
 * Param�tres : string : le nom de l'onglet 
 *              string : la valeur de l'onglet
 * retour : obj onglet : l'objet onglet ajout�        
 **************************************************************/      
  public function addOnglet($name,$value)
  {
   $this->arra_right[]=$this->isAllowed($name);//on regarde si l'utilisateur � les droits sur l'onglet
   return parent::addOnglet($name,$value);
  }
  
 /*************************************************************
 * Permet de supprimer les onglets sur lesquels l'utilisateur n'a pas de droit
 * avant de renvoyer la repr�sentation html du menu 
 * parametres : aucun
 *             
 * retour : string : le code html      
 **************************************************************/      
  public function htmlValue($type_retour = 'string')
  {
  
   $arra_onglet_allowed=array();//on part du principe qu'aucun onglet n'est accessible
   foreach($this->arra_onglet as $key=>$obj_onglet)
   {
    if($this->arra_right[$key])//si l'utilisateur a le  droit sur l'onglet, on le supprime
    {
     $arra_onglet_allowed[]=$obj_onglet;
    }
   }
   if(count($arra_onglet_allowed)==0)//si aucun onglet autoris�
   {
    die($this->stri_not_allowed_msg);
   }
   $this->arra_onglet=$arra_onglet_allowed;//on met dans le tableau d'onglet seulement ceux autoris�s
   return parent::htmlValue();//appel de la m�thode de la classe m�re pour obtenir le code html
  }
}




?>
