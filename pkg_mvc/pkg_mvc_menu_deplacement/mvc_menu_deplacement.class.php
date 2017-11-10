<?php
/*******************************************************************************
 Create Date  : 2016-10-04
 -------------------------------------------------------------------------------
 Class name   : mvc_menu_deplacement
 Version      : 1.0
 Author       : SOLEILLANT Remy
 Description  : Modèle atomique gérant la classe mvc_menu_deplacement
********************************************************************************/

class mvc_menu_deplacement {
   
//**** Attributs ****************************************************************
	
	//*** Champ base **************************************************************
  	protected $int_mmd_id;
  	protected $int_mmd_mm_id;
  	protected $stri_mmd_mvc_cible;
  	protected $stri_mmd_mvc_attribut;
  	protected $stri_mmd_destination_mvc;
  	protected $stri_mmd_destination_id;
  	protected $stri_mmd_destination_viewer;
  	protected $stri_mmd_viewer_methode;

	//*** Champ automatique ******************************************************
  	protected $int_mmd_crea_uid;
  	protected $date_mmd_crea_date;
  	protected $int_mmd_upd_uid;
  	protected $date_mmd_upd_date;	 
 
	//*** Liens MVC **************************************************************
    protected $obj_manager;
    protected $obj_viewer;

//***  Constructor *************************************************************
 /*******************************************************************************
	* Constructeur polymorphe de la classe grt_projet    
	*                       array : tableau associatif des attributs à affecter
	*               bool : pour lancer ou non la management
	* 
	* Parametres : mixed : integer : l'identifiant de l'instance 
	* Retour : objet de la classe  grt_projet                         
	*******************************************************************************/
	public  function __construct($stri_mixed="",$bool_manage=true) 
	{ 
	  
		//- Déclaration de manager
		$this->obj_manager=new mvc_menu_deplacement_manager($this);//initialisation du manager
		
		//- Déclaration du viewer
		$this->obj_viewer=new mvc_menu_deplacement_viewer($this);//initialisation du viewer
		
		//- Lancement du traitement
		if($bool_manage)
		{
		 $this->obj_manager->manage(); //lancement des traitement
		}
		
		//- Lancement du constructeur
		if($this->getIdMvc()=='')//si le manager n'a pas déjà récupéré les données
		{
		  $stri_construct=(is_array($stri_mixed))?"construct1":"construct2";//choix du constructeur      
		  $this->$stri_construct($stri_mixed);//lancement du constructeur  
		}  
	}

 /*******************************************************************************
	* Constructeur où l'on passe un tableau en clef le nom de l'attribut
	*  à affecter et dans le contenu du tableau, la valeur de l'attribut.
	*  
	*  Exemple : array("NUM_INCIDENT"=>11002369)
	* 
	* Parametres : array : tableau associatif des attributs à affecter 
	* Retour : aucun                      
	*******************************************************************************/
	public  function construct1($arra_attribute) 
	{ 
		  //- affectation des attribut
        $this->int_mmd_id                  = $arra_attribute['MMD_ID']                ;                                       
        $this->int_mmd_mm_id               = $arra_attribute['MMD_MM_ID']             ;                                       
        $this->stri_mmd_mvc_cible          = $arra_attribute['MMD_MVC_CIBLE']         ;                                       
        $this->stri_mmd_mvc_attribut       = $arra_attribute['MMD_MVC_ATTRIBUT']      ;                                       
        $this->stri_mmd_destination_mvc    = $arra_attribute['MMD_DESTINATION_MVC']   ;                                       
        $this->stri_mmd_destination_id     = $arra_attribute['MMD_DESTINATION_ID']    ;                                       
        $this->stri_mmd_destination_viewer = $arra_attribute['MMD_DESTINATION_VIEWER'];                                       
        $this->stri_mmd_viewer_methode     = $arra_attribute['MMD_VIEWER_METHODE']    ;                                       
        $this->int_mmd_crea_uid            = $arra_attribute['MMD_CREA_UID']          ;                                       
        $this->date_mmd_crea_date          = $arra_attribute['MMD_CREA_DATE']         ;                                       
        $this->int_mmd_upd_uid             = $arra_attribute['MMD_UPD_UID']           ;                                       
        $this->date_mmd_upd_date           = $arra_attribute['MMD_UPD_DATE']          ;                                       	       	  
	}
	
	
	/*******************************************************************************
	* Constructeur où l'on passe uniquement l'identifiant de l'objet
	* 
	* Parametres : stri : l'identifiant des données à charger 
	* Retour : objet de la classe  grt_projet                         
	*******************************************************************************/
	public  function construct2($stri_id) 
	{ 
    //- affectation de la pk
    $this->int_mmd_id=$stri_id;                                              
	    
		//- lancement du chargement
		$this->load();  
	}
 
  
//**** Methodes *****************************************************************  
	
	

//*** setter ******************************************************************
    public  function setIdMvc($mixed_value)   {	$this->int_mmd_id = $mixed_value;}   
    public  function setManager($mixed_value) {	$this->obj_manager = $mixed_value;}
    public  function setViewer($mixed_value)  {	$this->obj_viewer = $mixed_value; }

    public  function setMmdId($value)               { $this->int_mmd_id=$value                 ;}
    public  function setMmdMmId($value)             { $this->int_mmd_mm_id=$value              ;}
    public  function setMmdMvcCible($value)         { $this->stri_mmd_mvc_cible=$value         ;}
    public  function setMmdMvcAttribut($value)      { $this->stri_mmd_mvc_attribut=$value      ;}
    public  function setMmdDestinationMvc($value)   { $this->stri_mmd_destination_mvc=$value   ;}
    public  function setMmdDestinationId($value)    { $this->stri_mmd_destination_id=$value    ;}
    public  function setMmdDestinationViewer($value){ $this->stri_mmd_destination_viewer=$value;}
    public  function setMmdViewerMethode($value)    { $this->stri_mmd_viewer_methode=$value    ;}
    public  function setMmdCreaUid($value)          { $this->int_mmd_crea_uid=$value           ;}
    public  function setMmdCreaDate($value)         { $this->date_mmd_crea_date=$value         ;}
    public  function setMmdUpdUid($value)           { $this->int_mmd_upd_uid=$value            ;}
    public  function setMmdUpdDate($value)          { $this->date_mmd_upd_date=$value          ;}
 
//*** getter ******************************************************************
    public  function getIdMvc()   {	return $this->int_mmd_id;}
    public  function getManager() {	return $this->obj_manager;}
    public  function getViewer()  {	return $this->obj_viewer; }
  
    public  function getMmdId()               { return $this->int_mmd_id                 ;}
    public  function getMmdMmId()             { return $this->int_mmd_mm_id              ;}
    public  function getMmdMvcCible()         { return $this->stri_mmd_mvc_cible         ;}
    public  function getMmdMvcAttribut()      { return $this->stri_mmd_mvc_attribut      ;}
    public  function getMmdDestinationMvc()   { return $this->stri_mmd_destination_mvc   ;}
    public  function getMmdDestinationId()    { return $this->stri_mmd_destination_id    ;}
    public  function getMmdDestinationViewer(){ return $this->stri_mmd_destination_viewer;}
    public  function getMmdViewerMethode()    { return $this->stri_mmd_viewer_methode    ;}
    public  function getMmdCreaUid()          { return $this->int_mmd_crea_uid           ;}
    public  function getMmdCreaDate()         { return $this->date_mmd_crea_date         ;}
    public  function getMmdUpdUid()           { return $this->int_mmd_upd_uid            ;}
    public  function getMmdUpdDate()          { return $this->date_mmd_upd_date          ;}
 

//*** Changement de vue *********************************************************
   /*******************************************************************************
  	* Permet de changer le viewer à partir d'un nom de classe et de la méthode de représentation principal
  	* 
  	* Parametres : $stri_viewer : le nom de la classe viewer à utiliser 
  	*              $stri_viewer_main_methode : la méthode principale de représentation du viewer    
  	* Retour : aucun                         
  	*******************************************************************************/
    public  function changeViewer($stri_viewer,$stri_viewer_main_methode="constructTableForMain")
    {
       $obj_viewer=new $stri_viewer($this);
       $obj_viewer->setMainMethod($stri_viewer_main_methode);
       $this->setViewer($obj_viewer);
    }
 	
//*** loader ******************************************************************
	
	/*******************************************************************************
	* Méthode de chargement général des données
	* 
	* Parametres : aucun 
	* Retour : aucun                         
	*******************************************************************************/
	public  function load() 
	{ 	  
		//- Déclaration de requête de chargement      
		$stri_sql="select *
		           from   MVC_MENU_DEPLACEMENT
		           where  MMD_ID='".$this->int_mmd_id."'                                             
               ";
                       
		//- Execution de requête de chargement
		$obj_query_load=new querry_select($stri_sql);
		  	$arra_res=$obj_query_load->execute("assoc");
		  	$arra_one_res=$arra_res[0];
		
		//- Affectation des attributs
		$this->construct1($arra_one_res);  
	}
	

//*** saver *******************************************************************
	
	/*******************************************************************************
	* Méthode pour sauvegarder les données en base
	* 
	* Parametres : aucun 
	* Retour : bool : true  : sauvegarde ok 
	*                 false : problème lors de la sauvegarde                          
	*******************************************************************************/
	public  function save() 
	{ 
   
		//sauvegarde si l'identifiant est non vide
		  if($this->getIdMvc()!="")//si on a un id
		  {$bool_ok=$this->update();}//on lance une mise à jour
		  else
		  {$bool_ok=$this->insert();}//sans id, il s'agit d'une nouvelle option à insérer
		   
		  return $bool_ok;  
	}
		
//*** inserter ****************************************************************
	  	
	/*******************************************************************************
	* Méthode pour inséré les données en base
	* 
	* Parametres : aucun 
	* Retour : bool : true  : sauvegarde ok 
	*          false : problème lors de la sauvegarde                          
	*******************************************************************************/
	public  function insert() 
	{   
    //- Déclaration requête d'insertion
		$obj_query=new querry_insert('mvc_menu_deplacement');
    
		//- Génération de la pk
    $this->int_mmd_id=$obj_query->getNewPrimaryKey('MMD_ID');
    
    //- Ajout des champs à la requête
    $obj_query->addField('MMD_ID',$this->int_mmd_id)                                 ;                                       
    $obj_query->addField('MMD_MM_ID',$this->int_mmd_mm_id)                           ;                                       
    $obj_query->addField('MMD_MVC_CIBLE',$this->stri_mmd_mvc_cible)                  ;                                       
    $obj_query->addField('MMD_MVC_ATTRIBUT',$this->stri_mmd_mvc_attribut)            ;                                       
    $obj_query->addField('MMD_DESTINATION_MVC',$this->stri_mmd_destination_mvc)      ;                                       
    $obj_query->addField('MMD_DESTINATION_ID',$this->stri_mmd_destination_id)        ;                                       
    $obj_query->addField('MMD_DESTINATION_VIEWER',$this->stri_mmd_destination_viewer);                                       
    $obj_query->addField('MMD_VIEWER_METHODE',$this->stri_mmd_viewer_methode)        ;                                       	
		
    //- Affectation des attributs automatiques de traçabilité   
    $this->int_mmd_crea_uid = pnusergetvar("uid");                                       
    $this->date_mmd_crea_date = date("Y-m-d H:i:s");                                       	 	     
    
    //- Ajout à la requête des champs de traçabilité
    $obj_query->addField('MMD_CREA_UID',$this->int_mmd_crea_uid);                                       
    $obj_query->addField('MMD_CREA_DATE',$this->date_mmd_crea_date);                                       	   
    
		//- Execution de l'insertion
    $bool_ok=$obj_query->execute();
		//echo $obj_query->generateSql()."<br />";
		  
		return $bool_ok;  
	}
	
  
//*** updater ****************************************************************
	  	
	/*******************************************************************************
	* Méthode pour mettre à jour les données en base
	* 
	* Parametres : aucun 
	* Retour : bool : true  : sauvegarde ok 
	*          false : problème lors de la sauvegarde                          
	*******************************************************************************/
	public  function update() 
	{ 
    //- dépose des attributs dans un tableau nom_champ=>valeur
    $arra_champ['MMD_ID']                 = $this->int_mmd_id                 ;                                       
    $arra_champ['MMD_MM_ID']              = $this->int_mmd_mm_id              ;                                       
    $arra_champ['MMD_MVC_CIBLE']          = $this->stri_mmd_mvc_cible         ;                                       
    $arra_champ['MMD_MVC_ATTRIBUT']       = $this->stri_mmd_mvc_attribut      ;                                       
    $arra_champ['MMD_DESTINATION_MVC']    = $this->stri_mmd_destination_mvc   ;                                       
    $arra_champ['MMD_DESTINATION_ID']     = $this->stri_mmd_destination_id    ;                                       
    $arra_champ['MMD_DESTINATION_VIEWER'] = $this->stri_mmd_destination_viewer;                                       
    $arra_champ['MMD_VIEWER_METHODE']     = $this->stri_mmd_viewer_methode    ;                                       	
  		
		//- Déclaration requête d'update
		$obj_query=new querry_update('mvc_menu_deplacement');			
    		
		//- Affectation des champs en fonction de leur valeur   
    foreach($arra_champ as $stri_champ=>$stri_value)
    {
      //-- si le champ est null, on ne l'ajoute pas à la requête, cela permet de gérer l'update partiel  
      if(!is_null($stri_value))
      {
       $obj_query->addField($stri_champ, $stri_value);
      }
    }
       
    //- Définition du where
    $obj_query->addKey('MMD_ID',$this->int_mmd_id);                                         
   
    //- Affectation des attributs automatiques de traçabilité   
    $this->int_mmd_upd_uid = pnusergetvar("uid");                                       
    $this->date_mmd_upd_date = date("Y-m-d H:i:s");                                       	 	     
    
    //- Ajout à la requête des champs de traçabilité
    $obj_query->addField('MMD_UPD_UID',$this->int_mmd_upd_uid);                                       
    $obj_query->addField('MMD_UPD_DATE',$this->date_mmd_upd_date);                                       	
      
		//- Execution de l'insertion
    $bool_ok=$obj_query->execute();
		//echo $obj_query->generateSql()."<br />";
		  
		return $bool_ok;  
	}
 
//*** deleter ****************************************************************
 /*******************************************************************************
	* Méthode pour supprimer à partir de la pk
	* 
	* Parametres : aucun 
	* Retour : bool : true  : sauvegarde ok 
	*          false : problème lors de la sauvegarde                          
	*******************************************************************************/
	public  function delete() 
	{ 
     
    //- création de requête de suppression
    $obj_query=new querry_delete('mvc_menu_deplacement');
    
    //- Définition du where
    $obj_query->addKey('MMD_ID',$this->int_mmd_id);                                         

    //echo $obj_query->generateSql()."<br />";
    return  $obj_query->execute();
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

