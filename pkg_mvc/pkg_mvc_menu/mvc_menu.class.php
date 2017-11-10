<?php
/*******************************************************************************
 Create Date  : 2016-10-04
 -------------------------------------------------------------------------------
 Class name   : mvc_menu
 Version      : 1.0
 Author       : SOLEILLANT Remy
 Description  : Modèle atomique gérant la classe mvc_menu
********************************************************************************/

class mvc_menu {
   
//**** Attributs ****************************************************************
	
	//*** Champ base **************************************************************
  	protected $int_mm_id;
  	protected $stri_mm_menu;
  	protected $stri_mm_item;

	//*** Champ automatique ******************************************************
  	protected $int_mm_crea_uid;
  	protected $date_mm_crea_date;
  	protected $int_mm_upd_uid;
  	protected $date_mm_upd_date;	 
 
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
		$this->obj_manager=new mvc_menu_manager($this);//initialisation du manager
		
		//- Déclaration du viewer
		$this->obj_viewer=new mvc_menu_viewer($this);//initialisation du viewer
		
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
        $this->int_mm_id         = $arra_attribute['MM_ID']       ;                                       
        $this->stri_mm_menu      = $arra_attribute['MM_MENU']     ;                                       
        $this->stri_mm_item      = $arra_attribute['MM_ITEM']     ;                                       
        $this->int_mm_crea_uid   = $arra_attribute['MM_CREA_UID'] ;                                       
        $this->date_mm_crea_date = $arra_attribute['MM_CREA_DATE'];                                       
        $this->int_mm_upd_uid    = $arra_attribute['MM_UPD_UID']  ;                                       
        $this->date_mm_upd_date  = $arra_attribute['MM_UPD_DATE'] ;                                       	       	  
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
    $this->int_mm_id=$stri_id;                                              
	    
		//- lancement du chargement
		$this->load();  
	}
 
  
//**** Methodes *****************************************************************  
	
	

//*** setter ******************************************************************
    public  function setIdMvc($mixed_value)   {	$this->int_mm_id = $mixed_value;}   
    public  function setManager($mixed_value) {	$this->obj_manager = $mixed_value;}
    public  function setViewer($mixed_value)  {	$this->obj_viewer = $mixed_value; }

    public  function setMmId($value)      { $this->int_mm_id=$value        ;}
    public  function setMmMenu($value)    { $this->stri_mm_menu=$value     ;}
    public  function setMmItem($value)    { $this->stri_mm_item=$value     ;}
    public  function setMmCreaUid($value) { $this->int_mm_crea_uid=$value  ;}
    public  function setMmCreaDate($value){ $this->date_mm_crea_date=$value;}
    public  function setMmUpdUid($value)  { $this->int_mm_upd_uid=$value   ;}
    public  function setMmUpdDate($value) { $this->date_mm_upd_date=$value ;}
 
//*** getter ******************************************************************
    public  function getIdMvc()   {	return $this->int_mm_id;}
    public  function getManager() {	return $this->obj_manager;}
    public  function getViewer()  {	return $this->obj_viewer; }
  
    public  function getMmId()      { return $this->int_mm_id        ;}
    public  function getMmMenu()    { return $this->stri_mm_menu     ;}
    public  function getMmItem()    { return $this->stri_mm_item     ;}
    public  function getMmCreaUid() { return $this->int_mm_crea_uid  ;}
    public  function getMmCreaDate(){ return $this->date_mm_crea_date;}
    public  function getMmUpdUid()  { return $this->int_mm_upd_uid   ;}
    public  function getMmUpdDate() { return $this->date_mm_upd_date ;}
 

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
		           from   MVC_MENU
		           where  MM_ID='".$this->int_mm_id."'                                             
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
		$obj_query=new querry_insert('mvc_menu');
    
		//- Génération de la pk
    $this->int_mm_id=$obj_query->getNewPrimaryKey('MM_ID');
    
    //- Ajout des champs à la requête
    $obj_query->addField('MM_ID',$this->int_mm_id)     ;                                       
    $obj_query->addField('MM_MENU',$this->stri_mm_menu);                                       
    $obj_query->addField('MM_ITEM',$this->stri_mm_item);                                       	
		
    //- Affectation des attributs automatiques de traçabilité   
    $this->int_mm_crea_uid = pnusergetvar("uid");                                       
    $this->date_mm_crea_date = date("Y-m-d H:i:s");                                       	 	     
    
    //- Ajout à la requête des champs de traçabilité
    $obj_query->addField('MM_CREA_UID',$this->int_mm_crea_uid);                                       
    $obj_query->addField('MM_CREA_DATE',$this->date_mm_crea_date);                                       	   
    
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
    $arra_champ['MM_ID']   = $this->int_mm_id   ;                                       
    $arra_champ['MM_MENU'] = $this->stri_mm_menu;                                       
    $arra_champ['MM_ITEM'] = $this->stri_mm_item;                                       	
  		
		//- Déclaration requête d'update
		$obj_query=new querry_update('mvc_menu');			
    		
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
    $obj_query->addKey('MM_ID',$this->int_mm_id);                                         
   
    //- Affectation des attributs automatiques de traçabilité   
    $this->int_mm_upd_uid = pnusergetvar("uid");                                       
    $this->date_mm_upd_date = date("Y-m-d H:i:s");                                       	 	     
    
    //- Ajout à la requête des champs de traçabilité
    $obj_query->addField('MM_UPD_UID',$this->int_mm_upd_uid);                                       
    $obj_query->addField('MM_UPD_DATE',$this->date_mm_upd_date);                                       	
      
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
    $obj_query=new querry_delete('mvc_menu');
    
    //- Définition du where
    $obj_query->addKey('MM_ID',$this->int_mm_id);                                         

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

