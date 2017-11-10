<?php
/*******************************************************************************
Create Date  : 19/11/2015
 ----------------------------------------------------------------------
 Class name  : rgraph_pie
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Permet de manipuler le graphe de type pie de la bibliothèque rgraph
               doc js : http://www.rgraph.net/docs/pie.html
********************************************************************************/
class rgraph_pie {
   
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
   $stri_default_id="rgraph_pie_".str_replace('.','_',microtime(true));
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
   $this->stri_canva_style="border : solid black 1px;";
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
    //- tableau de nombre
    if(is_numeric($arra_to_linearise[0]))
    {
      $stri_linear=implode(", ",$arra_to_linearise);
      $stri_res="[".$stri_linear."]";
      return $stri_res;
    }
  
     //- tableau de chaînes
     $stri_linear=implode("', '",$arra_to_linearise);
     $stri_res="['".$stri_linear."']";
     
     return $stri_res;
  }
  
   /*******************************************************************************
	* Permet de linéariser une données variante
	* Parametres : $mixed_to_linearise : la donnée à linéraiser
	* Retour :  string : le code js pour la déclaration d'un tableau                              
	*******************************************************************************/
  public function lineariseMixed($mixed_to_linearise)
  {
    //- cas numérique
    if(is_numeric($mixed_to_linearise))
    {return $mixed_to_linearise;}
    
    //- cas tableau
    if(is_array($mixed_to_linearise))
    {
      $arra_key=array_keys($mixed_to_linearise);
      
      //- tableau simple
      if(is_numeric($arra_key[0]))
      {return $this->lineariseArray($mixed_to_linearise);}
      
      //- tableau associatif
    }
    
    //- cas boolen
    if(is_bool($mixed_to_linearise))
    {return $mixed_to_linearise;}
    
    //- cas chaine
    return "'".$mixed_to_linearise."'";
  }
  
 /*******************************************************************************
	* Permet de créer les éléments dom html et js permettant l'affichage du graphe
	* Parametres : aucun
	* Retour :  string                              
	*******************************************************************************/
  public function htmlValue()
  {
   
   //- linéarisation des infos de base
   $stri_label=$this->lineariseArray($this->arra_label);
   $stri_data=$this->lineariseArray($this->arra_data);
   
   
   //- linéraisation des autres propriétés
   $arra_to_linearise=array();
   foreach($this->arra_properties as $stri_propriete=>$mixed_value)
   {
     $stri_liear="obj_pie.Set('$stri_propriete',".$this->lineariseMixed($mixed_value).");";
     $arra_to_linearise[]=$stri_liear;
   }
   $stri_other_properties=implode("\n", $arra_to_linearise);
    
   //- construction de l'instruction d'affichage
   $stri_draw_instruction=$this->stri_drawing_methode."()";
   //-- dessin par une animation
   if($this->stri_drawing_methode!="Draw")
   {
     $stri_draw_instruction=$this->stri_drawing_methode."({frames:".$this->int_nb_frame."})";
   } 
    
   //- construction du js  
   $obj_javascripter=new javascripter();
    $obj_javascripter->addFunction("

            //- Initialisation du graphe
            var obj_pie = new RGraph.Pie('".$this->stri_id."',".$stri_data.");          
                obj_pie.Set('chart.labels', $stri_label); 
            
             
            //- définition d'autres propriétés 
             $stri_other_properties
          
            //- lancement du dessin du graphe
            //obj_pie.Draw();
              obj_pie.$stri_draw_instruction ;                                             
      ");       
     
 
  	 //- construction du résultat de retour
     $stri_js=$obj_javascripter->javascriptValue(); 
     $stri_res=' <canvas id="'.$this->stri_id.'" width="'.$this->int_width.'" height="'. $this->int_height.'" style="'.$this->stri_canva_style.'">[No canvas support]</canvas>';
     
     return  $stri_res.$stri_js;
  	}
}



?>
