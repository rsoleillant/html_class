<?php
/*******************************************************************************
Create Date  : 2016-10-04       
 ----------------------------------------------------------------------
 Class name  : mvc_menu_deplacement_loader
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Loader permettant de gérer une collection de modèle mvc_menu_deplacement. 
 
********************************************************************************/
class mvc_menu_deplacement_loader  extends mvc_std_loader {
   
//**** Attributs ****************************************************************
	protected $int_id_mvc         ;  //L'identifiant du mvc  (la donnée partagée par l'ensemble des modèles de la collection)
  

	//*** Attributs modèle ********************************************************
	protected $arra_mvc_menu_deplacement    ;  //Le tableau des modèles contenu dans le loader
	
		  
	//***  Roles  *****************************************************************
  protected $bool_recherche;        //Si le loader gère une recherche
  protected $bool_tri;              //Si le loader gère le tri sur les résultats
  protected $bool_insert;           //Si le loader permet l'ajout à la collection
  protected $bool_update;           //Si le loader gère directement la mise à jour
  protected $bool_delete;           //Si le loader gère la suppression
  protected $bool_pagination;       //Si le loader gère la pagination des résultats
  protected $bool_selection;        //Si le loader gère la sélection d'une sous partie de sa collection
    
  //*** Autres attributs ********************************************************
  protected $obj_model_reference  ;  //Le modèle de référence servant à l'ajout dans la collection     

	//*** Liens MVC ***************************************************************
	protected $obj_manager;
	protected $obj_viewer;	 

//**** Methodes *****************************************************************


//*** 01 Constructor **********************************************************
	
 /*******************************************************************************
	* Construteur polymorphe
	* 
	* Parametres : $stri_mixed : array() : l'ensemble de la collection de modèle
	*                            int : chargement par défaut sur identifiant du mvc principal
	*                            ""  : chargement par défaut              
	* Retour : Aucun                         
	*******************************************************************************/
	public  function __construct($stri_mixed, $bool_manage=true) 
	{            
      //- personnalisation des rôles
      $this->bool_insert=true;
      $this->bool_recherche=false;
      $this->bool_tri=false;                  
      $this->bool_update=false;
      $this->bool_delete=false;
      $this->bool_pagination=false;
      $this->bool_selection=false; 
      
     //- Déclaration de manager
		$this->obj_manager=new mvc_menu_deplacement_loader_manager($this);//initialisation du manager

		//- Déclaration du viewer
		$this->obj_viewer=new mvc_menu_deplacement_loader_viewer($this);//initialisation du viewer
		
		//- Lancement du traitement
		if($bool_manage)
		{  
		 $this->obj_manager->manage(); //lancement des traitement
		}
		
    //- Chargement par défaut des MVC dynamique        
		if($this->int_id_mvc=="")//si aucun chargement n'a été fait
		{           		
      $stri_construct=(is_array($stri_mixed))?"construct1":"construct2";//choix du constructeur      
		  $this->$stri_construct($stri_mixed);//lancement du constructeur  
		} 
    
     //- gestion du modèle de référence
     $this->obj_model_reference=new mvc_menu_deplacement('',false);
     $this->obj_model_reference->getViewer()->setDisabled(true);//désactivation de l'ensemble des champs
    
     //- transmission des fk
     $this->obj_model_reference->setMmdMmId($this->getIdMvc());
     
     //- configuration du modèle de référence
     $this->obj_model_reference->getViewer()->setMainMethod('constructTableForMain');
	}	               
	
  /*******************************************************************************
	* Constructeur complet
	* 
	* Parametres : $arra_attribute : L'ensemble des résultats / l'ensemble des modèles de la collection 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function construct1($arra_attribute) 
	{ 
		//- Affectation de la collection
		$this->arra_mvc_menu_deplacement=$arra_attribute;  
	}
	
	
	/*******************************************************************************
	* Constructeur par identifiant
	* 
	* Parametres : $int_id : L'identifiant du MVC permettant de charger l'ensemble de la collection 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function construct2($int_id) 
	{ 
		//- récupération de l'identifiant du mvc
		$this->int_id_mvc=$int_id;			
		
		//- Chargement des données
		$this->load();  
	}
//**** Setter ****************************************************************
  public  function setIdMvc($mixed_value)   {	$this->int_id_mvc = $mixed_value  ;}
  public  function setManager($mixed_value) {	$this->obj_manager = $mixed_value ;}
  public  function setViewer($mixed_value)  {	$this->obj_viewer = $mixed_value  ;}
 
  //Transmission de données 
  public  function setMmdDestinationId($value)    
  { 
    foreach($this->arra_mvc_menu_deplacement as $obj_mvc_menu_deplacement)
    {    
      $obj_mvc_menu_deplacement->setMmdDestinationId($value)    ;
    }
  }
 
 //Permet de changer le viewer à partir d'un nom de classe et de la méthode de représentation principal
  public  function changeViewer($stri_viewer,$stri_viewer_main_methode="constructTableForMain")
  {
     $obj_viewer=new $stri_viewer($this);
     $obj_viewer->setMainMethod($stri_viewer_main_methode);
     $this->setViewer($obj_viewer);
  }
  
  public function setRecherche($value)      { $this->bool_recherche=$value      ;}
  public function setTri($value)            { $this->bool_tri=$value            ;}
  public function setInsert($value)         { $this->bool_insert=$value         ;}
  public function setUpdate($value)         { $this->bool_update=$value         ;}
  public function setDelete($value)         { $this->bool_delete=$value         ;}
  public function setPagination($value)     { $this->bool_pagination=$value     ;}
  public function setSelection($value)      { $this->bool_selection=$value      ;}
//**** Getter ****************************************************************   
  public  function getCollection()  {	return $this->arra_mvc_menu_deplacement   ;}
  public  function getIdMvc()       {	return $this->int_id_mvc  ;}	
  public  function getManager()     {	return $this->obj_manager ;}
  public  function getViewer()      {	return $this->obj_viewer  ;}
  
  public function getRecherche()            { return $this->bool_recherche      ;}
  public function getTri()                  { return $this->bool_tri            ;}
  public function getInsert()               { return $this->bool_insert         ;}
  public function getUpdate()               { return $this->bool_update         ;}
  public function getDelete()               { return $this->bool_delete         ;}
  public function getPagination()           { return $this->bool_pagination     ;}
  public function getSelection()            { return $this->bool_selection      ;}
  public function getModelReference()       { return $this->obj_model_reference ;}
  
  //Permet d'obtenir la liste des champs utilisés pour les tri
   public  function getChampTri()  
   {
    $arra_champ=array();    
              $arra_champ[]='mmd_id';                                          
          $arra_champ[]='mmd_mm_id';                                          
          $arra_champ[]='mmd_mvc_cible';                                          
          $arra_champ[]='mmd_mvc_attribut';                                          
          $arra_champ[]='mmd_destination_mvc';                                          
          $arra_champ[]='mmd_destination_id';                                          
          $arra_champ[]='mmd_destination_viewer';                                          
          $arra_champ[]='mmd_viewer_methode';                                          

    return $arra_champ; 
   
   } 

//*** 02 Partie modèle ********************************************************
	/*******************************************************************************
	* Permet d'ajout un élément à la collection
	* 
	* Parametres : $obj_modele : le modèle à ajouter 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function addToCollection($obj_modele) 
	{ 
   	$this->arra_mvc_menu_deplacement[]=$obj_modele;   
  }
  
  /*******************************************************************************
	* Permet de supprimer de la collection un modèle (ne supprime pas en base)
	* 
	* Parametres : $obj_modele : le modèle à supprimer 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function deleteFromCollection($obj_modele) 
	{ 
     //- parcours des éléments de la collection
     $arra_collection=array(); 
     foreach($this->getCollection() as $int_indice=>$obj_model_from_collection)
     {
       if($obj_modele!==$obj_model_from_collection)
       {
         $arra_collection[]=$obj_model_from_collection;
       }
     }    
     //- actualisation de la collection
     $this->arra_mvc_menu_deplacement=$arra_collection; 
  }  
  
  
   /*******************************************************************************
	* Permet d'obtenir un élément de la collection à partir de la valeur de son idmvc 
	* 
	* Parametres : Acun
	* Retour : $obj_modele : le modèle cherché si trouvé
	*          false : le modèle n'a pas été trouvé                           
	*******************************************************************************/
	public  function getElementCollectionByIdMvc($stri_id_mvc) 
	{ 
     foreach($this->getCollection() as $obj_model)
     {
       if($obj_model->getIdMvc()==$stri_id_mvc)
       {return $obj_model;}
     }
     
     return false;
  }
  
	/*******************************************************************************
	* Permet de charger la collection
	* 
	* Parametres : aucun 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function load() 
	{        
    //- chargement des champs de tri
    $this->loadChampTri();      
    
    //- Optimisation pour ne pas recalculer les résultats
    $arra_collection=$this->getCollection();
   
		/*if(count($arra_collection)>0)//si le chargement a déjà été effectué
		{return $arra_collection;} */
		
		//- sql de chargement	
		$stri_sql=$this->constructSql() ;
				
		//- traitement du chargement
		$obj_query=new querry_select($stri_sql);
		    $arra_res=$obj_query->execute("assoc");//exécution de la recherche
		       
    //- initialisation de la collection
    $this->arra_mvc_menu_deplacement=array();
    
		//- traitement pour collection
		foreach($arra_res as $int_key=>$arra_one_res)
		{    
      //-- instancation du modèle avec blocage du management
		  $obj_modele=new mvc_menu_deplacement($arra_one_res,false);

      //-- ajout à la collection
      $this->addToCollection($obj_modele);  
		}  
         
		return $this->arra_mvc_menu_deplacement;
		  
	}
	/*******************************************************************************
	* Permet de construire le sql de chargement
	* 
	* Parametres : $int_id_mvc : L'identifiant du MVC permettant de charger toute la collection 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function constructSql() 
	{ 
		//- Construction de la requête sql
		$stri_sql=" SELECT  *
            		FROM mvc_menu_deplacement
            	  WHERE mmd_mm_id='".$this->getIdMvc()."'
            		"; 
    
   //- construction du order by   
   if($this->bool_tri)
   {
     $stri_sql=$this->constructOrderBy($stri_sql); 
   }           
  
   //- pagination du sql
   if($this->bool_pagination)
   {
     $stri_sql=$this->constructPaginationSql($stri_sql);      
   }
    
    return $stri_sql; 
	}
	
  //***  Méthodes de redirection *************************************************
	
  
  
	/*******************************************************************************
	* Redirection vers la méthode htmlValue du viewer
	* 
	* Parametres : $stri_retour : le type de retour souhaité [form,html,table]
	* Retour : mixed : dépend du paramètres $stri_retour :
	*                  form  : string , le code html encapsulé dans un formulaire
	*                  html  : string , le code html à afficher
	*                  table : obj classe table , la table à utiliser                         
	*******************************************************************************/
	public  function htmlValue($stri_retour="html") 
	{ 
		return $this->obj_viewer->htmlValue($stri_retour);    
	} 
}

?>
