<?php
/*******************************************************************************
Create Date  : 19/11/2015
 ----------------------------------------------------------------------
 Class name  : rgraph
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Classe abstraite partagée par l'ensemble des classe rgraph
 
********************************************************************************/
abstract class rgraph {
   
//**** Attributs ****************************************************************
	protected $stri_id;
  protected $int_width;
  protected $int_height; 
  protected $arra_data;               //Les données servant à construire le graphe
  protected $arra_label;              //Les libellés des zones de données  
  protected $stri_drawing_methode;    //Méthode pour dessiner le graphe
  protected $int_nb_frame;            //Nombre de frame utilisé dans le cas d'un dessin par animation  
  
  protected $stri_canva_style;        //Le style du canva à utiliser  
  protected $arra_properties;         //Les autres propriétés du graphe

	//*** 01 Attributs  ***********************************************************
	 


//*** 01 Constructor **********************************************************
	
	/*******************************************************************************
	* Constructeur principal
	* 
	* Parametres : 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function __construct($stri_id="",$arra_label="",$arra_data="") 
	{ 
	  //- affectation par defaut
   $stri_default_id="rgraph_".str_replace('.','_',microtime(true));
   $arra_default_data=array();
   $arra_default_label=array();
   
   //- hydratation
   $this->stri_id     =($stri_id=="")   ?$stri_default_id:$stri_id      ;
   $this->arra_data   =($arra_data=="") ?$arra_default_data:$arra_data  ; 
   $this->arra_label  =($arra_label=="")?$arra_default_label:$arra_label;  
   
   //- hydratation par défaut
   $this->int_width=470;
   $this->int_height=300;
   $this->stri_drawing_methode="Draw";
   $this->int_nb_frame=60;
	}
	
  //**** Methodes *****************************************************************
  
//**** Setter ****************************************************************
	public function setId($value){$this->stri_id=$value;}
  public function setWidth($value){$this->int_width=$value;}
  public function setHeight($value){$this->int_height=$value;}
  public function setData($value){$this->arra_data=$value;}
  public function setLabel($value){$this->arra_label=$value;}
  public function setDrawingMethode($value){$this->stri_drawing_methode=$value;}
  public function setNbFrame($value){$this->int_nb_frame=$value;}
  public function setCanvaStyle($value){$this->stri_canva_style=$value;}

  
//**** Getter ****************************************************************   
	public function getId(){return $this->stri_id;}
  public function getWidth(){return $this->int_width;}
  public function getHeight(){return $this->int_height;}
  public function getData(){return $this->arra_data;}
  public function getLabel(){return $this->arra_label;}
  public function getDrawingMethode(){return $this->stri_drawing_methode;}
  public function getNbFrame(){return $this->int_nb_frame;}
  public function getCanvaStyle(){return $this->stri_canva_style;}


//*** 02 Autres méthodes ******************************************************
	 /*******************************************************************************
	* Permet d'ajouter une propriété javascript au graph
	* Parametres : $stri_propertie : le nom de la propriété
	*              $mixed_value : la valeur à assigner
	* Retour :  aucun                              
	*******************************************************************************/
	public  function addProperties($stri_propertie,$mixed_value) 
	{ 
	  $this->arra_properties[$stri_propertie]=$mixed_value;
	}
  
 /*******************************************************************************
	* Permet de linéariser un tableau simple à une dimension
	* Parametres : $arra_to_linearise : le tableau à linéariser
	* Retour :  string : le code js pour la déclaration d'un tableau                              
	*******************************************************************************/
  public function lineariseArray($arra_to_linearise)
  {
    return json_encode($arra_to_linearise);
  }
  
   /*******************************************************************************
	* Permet de linéariser une données variante
	* Parametres : $mixed_to_linearise : la donnée à linéraiser
	* Retour :  string : le code js pour la déclaration d'un tableau                              
	*******************************************************************************/
  public function lineariseMixed($mixed_to_linearise)
  {
    return json_encode($mixed_to_linearise);
  }
 /*******************************************************************************
	* Permet de linéariser l'ensemble des propriétés supplémentaires
	* Parametres : aucun
	* Retour :  string : le code js                             
	*******************************************************************************/
  public function lineariseProperties()
  {
      //- linéraisation des autres propriétés
     $arra_to_linearise=array();
     foreach($this->arra_properties as $stri_propriete=>$mixed_value)
     {
       $stri_liear='obj_graphe.Set("'.$stri_propriete.'",'.$this->lineariseMixed($mixed_value).');';
       $arra_to_linearise[]=$stri_liear;
     }
     $stri_other_properties=implode("\n", $arra_to_linearise);
      
    return $stri_other_properties;
  }
  
  
}

?>
