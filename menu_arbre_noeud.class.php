<?php
/*******************************************************************************
Create Date : 26/03/2012
 ----------------------------------------------------------------------
 Class name : menu_arbre_noeud 
 Version : 1.0
 Author : Rémy Soleillant
 Description : Le noeud de l'arbre utilisé pour générer un menu dynamique
 
********************************************************************************/
class menu_arbre_noeud{
   
   //**** attribute ************************************************************
    protected $arra_feuille;              //Tableau des fichiers feuilles
    protected $arra_noeud_fils;           //Tableau des noeuds fils
    protected $stri_nom;                  //Le nom du noeud
    protected $int_id;                    //L'identifiant du noeud
    protected $bool_visible;              //Boolean pour afficher ou masquer un lien
    protected static $int_nb_instance;    //Pour connaitre le nombre d'instance de noeud
  //**** constructor ***********************************************************
 /*************************************************************
 *
 * parametres : 
 * retour : objet de la classe 
 *                        
 **************************************************************/    
  function __construct($stri_nom) 
  { 
    $this->stri_nom=$stri_nom;
    self::$int_nb_instance++;            //incrémentation du nombre d'instance
    //$this->int_id=self::$int_nb_instance;//le nombre d'instance courrante devient l'identifiant
    $this->createId();//lancement de la création de l'identifiant du noeud
    
    $this->bool_visible=true;           //Par défaut affiché
  }
 
  //**** setter ****************************************************************
  public function setFeuille($value){$this->arra_feuille=$value;}
  public function setNoeudFils($value){$this->arra_noeud_fils=$value;}
  public function setNom($value){$this->stri_nom=$value;}
  public function setVisible($bool_value){$this->bool_visible=$bool_value;}
 
  
  //**** getter ****************************************************************
  public function getFeuille(){return $this->arra_feuille;}
  public function getNoeudFils(){ return $this->arra_noeud_fils; }
  public function getNom(){return $this->stri_nom;}
  public function getId(){return $this->int_id;}
  
  public function getNoeudById($int_id)
  {
   if($this->int_id==$int_id)//si je suis le noeud recherché
   {return $this;}
   
   foreach($this->arra_noeud_fils as $obj_noeud) //recherche parmis les noeuds fils
   {
   
    $mixed_found= $obj_noeud->getNoeudById($int_id);
    if($mixed_found!==false)
    {
     return $mixed_found;
    } 
    
   }
   return false;//le noeud n'a pas été trouvé
  
  }

  
  /*******************************************************************************
* Appelé lors d'un usort sur une collection d'objet demande_evo_echange (principalement le loader d'échange)
* 
* Permet de trier les élement par ordre descroissant en foction de la date d'échange
*******************************************************************************/
public static function orderById($a, $b ) {
    return $a->getId() == $b->getId() ? 0 : ( $a->getId() > $b->getId() ) ? 1 : -1;
}

        
        
        
   /*************************************************************
 * Permet d'obtenir le nom du noeud destiné à l'affichage
 * parametres : aucun
 * retour : string : le nom à afficher
 *                        
 **************************************************************/    
  public function getDisplayName()
  {
    
    $stri_name=basename($this->stri_nom);//récupération du nom
    $stri_short_name=substr($stri_name,2);//suppression du n° de dossier
    $stri_constant=constante::constant('_'.strtoupper($stri_short_name)); //transformation du nom en constante
    
    return $stri_constant;
  } 
   
   //**** other method *********************************************************
 /*************************************************************
 * Permet de construire l'identifiant du noeud à partir
 * de son chemin de dossier.
 * L'identifiant est le début du nom de chaque dossier (avant le _ ) séparé par des .
 * Exemple : 01.02.01    
 * parametres : aucun
 * retour : aucun
 *                        
 **************************************************************/    
  protected function createId()
  {
    if($this->stri_nom=="root")//cas particulier de la racine
    {
     $this->int_id=0;
     return;
    }
    
    //- recherche de la partie significative de l'arboresence du menu
    $int_start=strpos($this->stri_nom,"menu/")+strlen("menu/") ;
    $stri_chemin_relatif=substr($this->stri_nom,$int_start);
    
    //- découpage de l'arborescence relative
    $arra_part=explode("/",$stri_chemin_relatif);
    $arra_id=array();//pour stocker les différentes partie de l'identifiant
    foreach($arra_part as $stri_part)
    {
      //découpage de l'information du dossier
      $arra_info=explode("_",$stri_part);
      $arra_id[]=$arra_info[0];
    }
   
    //- reconstruction de l'identifiant
    $stri_id=implode(".", $arra_id);
    
    $this->int_id=$stri_id;  
  } 
   
 /*************************************************************
 * Permet d'ajouter une feuille au noeud
 * parametres : string : le fichier de feuille
 * retour : obj menu_arbre_noeud : le noeud fils
 *                        
 **************************************************************/    
  public function addFils($stri_nom)
  {
    $obj_fils=new menu_arbre_noeud($stri_nom); 
    $this->arra_noeud_fils[]=$obj_fils;
    
    return $obj_fils;
  }
 
  
 /*************************************************************
 * Permet d'ajouter une feuille au noeud
 * parametres : string : le fichier de feuille
 * retour : aucun
 *                        
 **************************************************************/    
  public function addFeuille($stri_file)
  {
    $this->arra_feuille[]=$stri_file; 
  }

/*************************************************************
 * Permet d'avoir la représentation html du noeud
 * 
 * parametres : aucun
 * retour : string : le code html
 *                        
 **************************************************************/    
  public function htmlValue()
  {
    
   $stri_addresse="#";
   
   $stri_res.=''; 
   
   $bool_fils=(count($this->arra_noeud_fils)>0)?true:false;
   if($bool_fils)
   {$stri_res.='<ul>'."\n";}  //ouverture ul pour mettre l'ensemble des noeuds fils
     
   foreach($this->arra_noeud_fils as $obj_noeud)
   {
      $stri_res.=$obj_noeud->htmlValue(); //pose des noeud fils
   }
   
   if($bool_fils)
   {$stri_res.="\n".'</ul>';}
   
   if(is_file($this->stri_nom."/index.php"))//s'il y a un index dans le dossier
   {   
     //$stri_addresse=$_SERVER['REQUEST_URI']."&id_noeud=".$this->int_id;//on reste à la même adresse mais on transmet l'identifiant du noeud
    $int_pos=strpos($_SERVER['REQUEST_URI'],'&id_noeud');
    $stri_base_url=($int_pos!==false)?substr($_SERVER['REQUEST_URI'], 0, $int_pos):$_SERVER['REQUEST_URI'];//Pour ne pas avoir plusieurs fois les même variables en get
    //$stri_addresse=$stri_base_url."&id_noeud=".$this->int_id."&idmvc[]=ecran_creation&val_pk[]=idmvc[]=asis_client_loader&val_pk[]=";//on reste à la même adresse mais on transmet l'identifiant du noeud
   
    if(is_file($this->stri_nom."/config.php"))
    {include_once($this->stri_nom."/config.php");}//inclusion du fichier de config du noeud
   
    $stri_addresse=$stri_base_url."&id_noeud=".$this->int_id.$stri_extra_url;//on reste à la même adresse mais on transmet l'identifiant du noeud
   
   } 
   
   //condition pour affichage
   if ($this->bool_visible==true)
   {
       //- Nom d'affichage
       $stri_name = $this->getDisplayName();
       if($bool_fils)
       { $stri_name .= '&nbsp;&nbsp; >>';  }
       
       $stri_res='<li>'."\n".'<a href="'.$stri_addresse.'">'.$stri_name.'</a>'.$stri_res."\n".'</li>';//pose du noeud suivi de ses fils
       //$stri_res='<li>'."\n".'<a href="'.$stri_addresse.'">'.$this->getDisplayName().'</a>'.$stri_res."\n".'</li>';//pose du noeud suivi de ses fils
   }
   else
   {
       $stri_res='<li style="display: none;">'."\n".'<a href="'.$stri_addresse.'">'.$this->getDisplayName().'</a>'.$stri_res."\n".'</li>';//pose du noeud suivi de ses fils
   }
   
    
  
  
 
  /* foreach($this->arra_feuille as $stri_file)
   {
    $stri_res.='<li>'.$stri_file.'</li>';
   }  */
   

   // $stri_res.='</li>';
  // echo htmlentities($stri_res,ENT_COMPAT, 'ISO-8859-1')."<br />";
/*  echo "<pre>";
  var_dump($this->getDisplayName());
  echo "</pre><br />"; */
   return $stri_res;
  } 
  
 
}

?>
