<?php

/*******************************************************************************
Create Date : 06/01/2009
 ----------------------------------------------------------------------
 Class name : report_generator
 Version : 1.0
 Author : Rémy Soleillant
 Description : Classe mère pour la génération de rapport sous différent format. Le but de la 
classe est de permettre l'extraction de données à partir d'une requête sql ou d'un tableau 
multidimensionnel provenant d'une requête sql effectuée avec la classe query_select.
********************************************************************************/

abstract class report_generator 
{
 //**** attribute *************************************************************
  protected $stri_sql;//Le sql à partir duquel extraire les données du rapport  
  protected $stri_title;//Le titre du rapport à générer  
  protected $stri_sub_title;//Le sous titre du rapport à générer  
  protected $arra_column_name;//Le nom des différentes colones du rapport
  protected $arra_column_width; //Tableau contenant la largeur des différentes colonnes  
  protected $stri_path;//Le chemin ou stocker le rapport  
  protected $stri_file_name;//Le nom du fichier qui contiendra le rapport  
  protected $nb_line; //le nombre total de ligne
  protected $nb_col;  //le nombre total de colonne

  protected $arra_res; //tableau de résultat utilisé pour généré le rapport

  /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe file_reader_writer   
   *                        
   **************************************************************/         
  function __construct($m_valeur,$title="",$sub_title="",$file_name="") 
  {
    $this->stri_title=$title;
    $this->stri_sub_title=$sub_title;
    $this->arra_column_name=array();
    $this->stri_path="";
    $this->stri_file_name=$file_name;

    // $m_valeur est un tableau provenant d'une req avec query_select 
    if(is_array($m_valeur))
    {
      $this->arra_res = $m_valeur;
    }
    // sinon c'est une req à exécuter
    else
    {
      $this->stri_sql=$m_valeur;
      $obj_query_select=new querry_select($this->stri_sql);
      //exécution de la requête
      $this->arra_res=$obj_query_select->execute("assoc");
     
    }
    //calcul du nombre de colonnes
    $arra_one_res=$this->arra_res[0];
    $nb_col=count($arra_one_res);
   
    
    //calcul du nombre de lignes 
    $nb_line=count($this->arra_res)+3;
    $this->nb_line=$nb_line;
    $this->nb_col=$nb_col;
      
    //nom des entete de colonne par défaut
    foreach($this->arra_res[0] as $stri_name=>$stri_value)
    {$this->arra_column_name[]=$stri_name;}
    
    $int_default_size=floor(190/$nb_col);
    //tailles des colonnes par défaut
    for($i=0;$i<$nb_col;$i++)
    {$this->arra_column_width[]=$int_default_size;}
  }   

 //**** setter *************************************************************
  public function setSql($value){$this->stri_sql=$value;}
  public function setTitle($value){$this->stri_title=$value;}
  public function setSubTitle($value){$this->stri_sub_title=$value;}
  public function setColumnName($value){$this->arra_column_name=$value;}
  public function setPath($value){$this->stri_path=$value;}
  public function setFileName($value){$this->stri_file_name=$value;}
  public function setColumnWidth($array)
  {$this->stri_column_width=$array;
   $this->sizeColumn();
  }
 //**** getter *************************************************************
  public function getSql(){return $this->stri_sql;}
  public function getTitle(){return $this->stri_title;}
  public function getSubTitle(){return $this->stri_sub_title;}
  public function getColumnName(){return $this->arra_column_name;}
  public function getPath(){return $this->stri_path;}
  public function getFileName(){return $this->stri_file_name;}
  public function getColumnWidth(){return $this->stri_column_width;}
  public function getNbCol(){return $this->nb_col;}
  public function getNbLine(){return $this->nb_line;}
  
 //**** other method *******************************************************
  
  /*************************************************************
  Méthode générique permettant de générer le rapport. Cette métode doit être surchargée par
 les classes filles.
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public  function generateReport()
  {
  
   //pose du titre et sous titre si différent de null
   $b_change = false;
   if ($this->stri_title !== '')
   {
     $b_change = true;
     $this->applyStyle(1);
     $this->addLine($this->stri_title);
   }
   if ($this->stri_title !== '')
   {
     $b_change = true;
     $this->applyStyle(2);
     $this->addLine($this->stri_sub_title);
   }
   if ($b_change === true)
   {
     $this->applyStyle(0);
     $this->addLine("");
     //pose des entetes de colone
     $this->newLine();
   }
   $this->applyStyle(3);
   foreach($this->arra_column_name as $stri_name)
   {$this->addCell($stri_name);}
   $this->newLine();
   $this->applyStyle(4);
   //pose des données
   $this->generateResultPart();
   /*foreach($this->arra_res as $arra_one_res)
   {
    foreach($arra_one_res as $stri_res)
    {
     $this->addCell($stri_res);
    }
    $this->newLine();    
   }  */
   
   $this->makeFile();
  
   return $this->stri_path.$this->stri_file_name;
  }
  
   /*************************************************************
  Méthode générique permettant de générer la partie résultat du  rapport
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public  function generateResultPart()
  {
   //pose des données
   foreach($this->arra_res as $arra_one_res)
   {
    foreach($arra_one_res as $stri_res)
    {
     $this->addCell($stri_res);
    }
    $this->newLine();    
   }
  }
  
  /*************************************************************
  Permet d'ajouter une ligne au rapport et passe à la ligne suivante
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public abstract function addLine($stri_line);

 

 /*************************************************************
  Permet d'ajouter une cellule au rapport.
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public abstract function addCell($stri_contain);

 

 /*************************************************************
  Permet de créer le fichier contenant le rapport.
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public abstract function makeFile();
  
  /*************************************************************
  Permet de créer une nouvelle ligne
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public abstract function newLine();
 
  /*************************************************************
  Permet de prendre en compte les dimensions des colonnes.
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public abstract function sizeColumn();
 
   /*************************************************************
  Permet d'appliquer un style de présentation dont le numéro est passé en paramètre
  
  parametres : 	$int_num_style : le numéro du style à appliquer		         
  retour :          
  **************************************************************/     
  public abstract function applyStyle($int_num_style);
  /* Style 0 : par défaut
     Style 1 : titre du document
     Style 2 : sous titre du document
     Style 3 : entete des colones du rapport
     Style 4 : ligne du tableau
  */   
}
?>
