<?php
/*******************************************************************************
Create Date : 11/06/2012
 ----------------------------------------------------------------------
 Class name : menu_arbre
 Version : 1.0
 Author : Rémy Soleillant
 Description : Pour gérer un menu dynamique sous forme d'arbre
 
********************************************************************************/

class menu_arbre{
   
  //**** attribute ************************************************************
  protected $arra_noeud;    //Tableaux des noeuds
  protected $obj_racine;    //La racine de l'arbre 
  
  //**** constructor ***********************************************************
 /*************************************************************
 *
 * parametres : 
 * retour : objet de la classe 
 *                        
 **************************************************************/    
  function __construct() 
  { 
    
  }
 
  //**** setter ****************************************************************
 
  
  //**** getter ****************************************************************
/*************************************************************
 * Permet d'avoir la représentation html de l'arbre
 * 
 * parametres : aucun
 * retour : string : le code html
 *                        
 **************************************************************/  
  public function getNoeudById($int_id)
  {
    return $this->obj_racine->getNoeudById($int_id);
  }
   //**** public method *********************************************************
   
 /*************************************************************
 * Permet d'avoir la représentation html de l'arbre
 * 
 * parametres : aucun
 * retour : string : le code html
 *                        
 **************************************************************/    
  public function htmlValue($int_idx = 0)
  {
   $arra_fils=$this->obj_racine->getNoeudFils();

   
    //Triee la collection d'objet par leurs ID
    $arra_temp = $arra_fils[$int_idx]->getNoeudFils();
    usort($arra_temp,array("menu_arbre_noeud","orderById"));
    //Rédini l'array de fils qui est maintenant trié
    $arra_fils[$int_idx]->setNoeudFils($arra_temp);

  
    //$stri_res.='<ul class="dropdown">'.$this->obj_racine->htmlValue().'</ul>';
   $stri_res='<ul class="dropdown" id="menu_gestion_projet" style="padding:1px;">'."\n";
  
   foreach($arra_fils as $obj_noeud)
   {
     $stri_res.=$obj_noeud->htmlValue();
   }
   $stri_res.="\n".'</ul>';
  
    
   
   
   
   return $stri_res;
  } 
  
 /*************************************************************
 * Permet de charger l'arbre depuis un dossier représentant l'arbre
 * sous forme d'arborescence de fichier.
 * 
 * Une dossier représente un noeud et un fichier représente une feuille   
 * parametres : string : le chemin du répertoire racine de l'arbre
 * retour : aucun
 *                        
 **************************************************************/    
  public function loadByDirectory($stri_dir_root)
  {
    $this->obj_racine=new menu_arbre_noeud("root");  //création de la racine
    $this->loadDirectorty($this->obj_racine,$stri_dir_root); 
  }
  
 /*************************************************************
 * Permet de charger récursivement un dossier
 * 
 *   
 * parametres :  obj menu_arbre_noeud : le noeud auquel rattacher les feuilles 
 *               string : le chemin du répertoire a charger
 * retour : aucun
 *                        
 **************************************************************/    
 public function loadDirectorty($obj_noeud_parent,$stri_directory)
 {
    
    $obj_reader=new file_reader_writer($stri_directory);
    
    $arra_file=$obj_reader->readDirectory();//lecture des fichiers
  
    foreach($arra_file as $stri_file)
    {
        //- On récupere l'extension du fichier
        $arra_info = pathinfo($stri_file);
        if ($arra_info['extension']=='php')
        {
            $obj_noeud_parent->addFeuille($stri_file);//ajout d'une feuille par fichier    
        }
     
    }
    
    $arra_directory=$obj_reader->readDirectory(true);//lecture des répertoires
    
    foreach($arra_directory as $stri_directory)
    {
     
      $obj_noeud=$obj_noeud_parent->addFils($stri_directory);
      $this->loadDirectorty($obj_noeud,$stri_directory); 
    } 
    
    $obj_reader->closeFile();
 
 }
   
  /*************************************************************
 * Permet d'inclures les fichiers feuilles en fonction de l'id noeud
 * sélectionné 
 * parametres : aucun
 * retour : aucun
 *                        
 **************************************************************/    
  public function includeFile()
  {
    if($_GET['id_noeud']>0)//s'il y a un noeud sélectionné
    {
      $_SESSION['id_noeud']=$_GET['id_noeud'];//sauvegarde de l'identifiant du noeud
    }
    
    if($_SESSION['id_noeud']>0)//s'il y a un noeud en session
    {
     $obj_noeud=$this->getNoeudById($_SESSION['id_noeud']);
          
     if (is_a($obj_noeud, "menu_arbre_noeud"))
     {
          $arra_feuille=$obj_noeud->getFeuille();
     
   
            foreach($arra_feuille as $stri_file)
            {
             $bool_ok=include_once($stri_file);
            }     
         
     }
     
    }
     return $bool_ok;
  
  } 
  
 
}

?>
