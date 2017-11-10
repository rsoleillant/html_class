<?php
/*******************************************************************************
 Create Date  : 2016-02-29
 -------------------------------------------------------------------------------
 Class name   : table_champ
 Version      : 1.0
 Author       : SOLEILLANT Remy
 Description  : Mod�le atomique g�rant la classe table_champ
********************************************************************************/

class table_champ {
   
//**** Attributs ****************************************************************
	
	//*** Champ base **************************************************************
  	protected $int_tc_id_table_champ;
  	protected $stri_tc_model;
  	protected $stri_tc_nom_table;
  	protected $stri_tc_nom_champ;
  	protected $stri_tc_valeur;
  	protected $int_tc_uid;
  	protected $stri_tc_role;

	//*** Champ automatique ******************************************************
  	protected $date_tc_crea_date;
  	protected $int_tc_crea_uid;
  	protected $date_tc_upd_date;
  	protected $int_tc_upd_uid;	 
 
	//*** Liens MVC **************************************************************
    protected $obj_manager;
    protected $obj_viewer;

//***  Constructor *************************************************************
 /*******************************************************************************
	* Constructeur polymorphe de la classe grt_projet    
	*                       array : tableau associatif des attributs � affecter
	*               bool : pour lancer ou non la management
	* 
	* Parametres : mixed : integer : l'identifiant de l'instance 
	* Retour : objet de la classe  grt_projet                         
	*******************************************************************************/
	public  function __construct($stri_mixed="",$bool_manage=true) 
	{ 
	  
		//- D�claration de manager
		$this->obj_manager=new table_champ_manager($this);//initialisation du manager
		
		//- D�claration du viewer
		$this->obj_viewer=new table_champ_viewer($this);//initialisation du viewer
		
		//- Lancement du traitement
		if($bool_manage)
		{
		 $this->obj_manager->manage(); //lancement des traitement
		}
		
		//- Lancement du constructeur
		if($this->getIdMvc()=='')//si le manager n'a pas d�j� r�cup�r� les donn�es
		{
		  $stri_construct=(is_array($stri_mixed))?"construct1":"construct2";//choix du constructeur      
		  $this->$stri_construct($stri_mixed);//lancement du constructeur  
		}  
	}

 /*******************************************************************************
	* Constructeur o� l'on passe un tableau en clef le nom de l'attribut
	*  � affecter et dans le contenu du tableau, la valeur de l'attribut.
	*  
	*  Exemple : array("NUM_INCIDENT"=>11002369)
	* 
	* Parametres : array : tableau associatif des attributs � affecter 
	* Retour : aucun                      
	*******************************************************************************/
	public  function construct1($arra_attribute) 
	{ 
		  //- affectation des attribut
        $this->int_tc_id_table_champ = $arra_attribute['TC_ID_TABLE_CHAMP'];                                       
        $this->stri_tc_model         = $arra_attribute['TC_MODEL']         ;                                       
        $this->stri_tc_nom_table     = $arra_attribute['TC_NOM_TABLE']     ;                                       
        $this->stri_tc_nom_champ     = $arra_attribute['TC_NOM_CHAMP']     ;                                       
        $this->stri_tc_valeur        = $arra_attribute['TC_VALEUR']        ;                                       
        $this->int_tc_uid            = $arra_attribute['TC_UID']           ;                                       
        $this->stri_tc_role          = $arra_attribute['TC_ROLE']          ;                                       
        $this->date_tc_crea_date     = $arra_attribute['TC_CREA_DATE']     ;                                       
        $this->int_tc_crea_uid       = $arra_attribute['TC_CREA_UID']      ;                                       
        $this->date_tc_upd_date      = $arra_attribute['TC_UPD_DATE']      ;                                       
        $this->int_tc_upd_uid        = $arra_attribute['TC_UPD_UID']       ;                                       	       	  
	}
	
	
	/*******************************************************************************
	* Constructeur o� l'on passe uniquement l'identifiant de l'objet
	* 
	* Parametres : stri : l'identifiant des donn�es � charger 
	* Retour : objet de la classe  grt_projet                         
	*******************************************************************************/
	public  function construct2($stri_id) 
	{ 
    //- affectation de la pk
    $this->int_tc_id_table_champ=$stri_id;                                              
	    
		//- lancement du chargement
		$this->load();  
	}
 
  
//**** Methodes *****************************************************************  
	
	

//*** setter ******************************************************************
    public  function setIdMvc($mixed_value)   {	$this->int_tc_id_table_champ = $mixed_value;}   
    public  function setManager($mixed_value) {	$this->obj_manager = $mixed_value;}
    public  function setViewer($mixed_value)  {	$this->obj_viewer = $mixed_value; }

    public  function setTcIdTableChamp($value){ $this->int_tc_id_table_champ=$value;}
    public  function setTcModel($value)       { $this->stri_tc_model=$value        ;}
    public  function setTcNomTable($value)    { $this->stri_tc_nom_table=$value    ;}
    public  function setTcNomChamp($value)    { $this->stri_tc_nom_champ=$value    ;}
    public  function setTcValeur($value)      { $this->stri_tc_valeur=$value       ;}
    public  function setTcUid($value)         { $this->int_tc_uid=$value           ;}
    public  function setTcRole($value)        { $this->stri_tc_role=$value         ;}
    public  function setTcCreaDate($value)    { $this->date_tc_crea_date=$value    ;}
    public  function setTcCreaUid($value)     { $this->int_tc_crea_uid=$value      ;}
    public  function setTcUpdDate($value)     { $this->date_tc_upd_date=$value     ;}
    public  function setTcUpdUid($value)      { $this->int_tc_upd_uid=$value       ;}
 
//*** getter ******************************************************************
    public  function getIdMvc()   {	return $this->int_tc_id_table_champ;}
    public  function getManager() {	return $this->obj_manager;}
    public  function getViewer()  {	return $this->obj_viewer; }
  
    public  function getTcIdTableChamp(){ return $this->int_tc_id_table_champ;}
    public  function getTcModel()       { return $this->stri_tc_model        ;}
    public  function getTcNomTable()    { return $this->stri_tc_nom_table    ;}
    public  function getTcNomChamp()    { return $this->stri_tc_nom_champ    ;}
    public  function getTcValeur()      { return $this->stri_tc_valeur       ;}
    public  function getTcUid()         { return $this->int_tc_uid           ;}
    public  function getTcRole()        { return $this->stri_tc_role         ;}
    public  function getTcCreaDate()    { return $this->date_tc_crea_date    ;}
    public  function getTcCreaUid()     { return $this->int_tc_crea_uid      ;}
    public  function getTcUpdDate()     { return $this->date_tc_upd_date     ;}
    public  function getTcUpdUid()      { return $this->int_tc_upd_uid       ;}
 

//*** Changement de vue *********************************************************
   /*******************************************************************************
  	* Permet de changer le viewer � partir d'un nom de classe et de la m�thode de repr�sentation principal
  	* 
  	* Parametres : $stri_viewer : le nom de la classe viewer � utiliser 
  	*              $stri_viewer_main_methode : la m�thode principale de repr�sentation du viewer    
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
	* M�thode de chargement g�n�ral des donn�es
	* 
	* Parametres : aucun 
	* Retour : aucun                         
	*******************************************************************************/
	public  function load() 
	{ 	  
		//- D�claration de requ�te de chargement      
		$stri_sql="select *
		           from   TABLE_CHAMP
		           where  TC_ID_TABLE_CHAMP='".$this->int_tc_id_table_champ."'                                             
               ";
                       
		//- Execution de requ�te de chargement
		$obj_query_load=new querry_select($stri_sql);
		  	$arra_res=$obj_query_load->execute("assoc");
		  	$arra_one_res=$arra_res[0];
		
		//- Affectation des attributs
		$this->construct1($arra_one_res);  
	}
	

//*** saver *******************************************************************
	
	/*******************************************************************************
	* M�thode pour sauvegarder les donn�es en base
	* 
	* Parametres : aucun 
	* Retour : bool : true  : sauvegarde ok 
	*                 false : probl�me lors de la sauvegarde                          
	*******************************************************************************/
	public  function save() 
	{ 
  
		//sauvegarde si l'identifiant est non vide
		  if($this->getIdMvc()!="")//si on a un id
		  {$bool_ok=$this->update();}//on lance une mise � jour
		  else
		  {$bool_ok=$this->insert();}//sans id, il s'agit d'une nouvelle option � ins�rer
		   
		  return $bool_ok;  
	}
		
//*** inserter ****************************************************************
	  	
	/*******************************************************************************
	* M�thode pour ins�r� les donn�es en base
	* 
	* Parametres : aucun 
	* Retour : bool : true  : sauvegarde ok 
	*          false : probl�me lors de la sauvegarde                          
	*******************************************************************************/
	public  function insert() 
	{ 
    //- D�claration requ�te d'insertion
		$obj_query=new querry_insert('table_champ');
    
		//- G�n�ration de la pk
    $this->int_tc_id_table_champ=$obj_query->getNewPrimaryKey('TC_ID_TABLE_CHAMP');
    
    //- Ajout des champs � la requ�te
    $obj_query->addField('TC_ID_TABLE_CHAMP',$this->int_tc_id_table_champ);                                       
    $obj_query->addField('TC_MODEL',$this->stri_tc_model)                 ;                                       
    $obj_query->addField('TC_NOM_TABLE',$this->stri_tc_nom_table)         ;                                       
    $obj_query->addField('TC_NOM_CHAMP',$this->stri_tc_nom_champ)         ;                                       
    $obj_query->addField('TC_VALEUR',$this->stri_tc_valeur)               ;                                       
    $obj_query->addField('TC_UID',$this->int_tc_uid)                      ;                                       
    $obj_query->addField('TC_ROLE',$this->stri_tc_role)                   ;                                       	
		
    //- Affectation des attributs automatiques de tra�abilit�   
    $this->int_tc_crea_uid = pnusergetvar("uid");                                       
    $this->date_tc_crea_date = date("Y-m-d H:i:s");                                       	 	     
    
    //- Ajout � la requ�te des champs de tra�abilit�
    $obj_query->addField('TC_CREA_DATE',$this->date_tc_crea_date);                                       
    $obj_query->addField('TC_CREA_UID',$this->int_tc_crea_uid);                                       	   
    
		//- Execution de l'insertion
    $bool_ok=$obj_query->execute();
		//echo $obj_query->generateSql()."<br />";
		  
		return $bool_ok;  
	}
	
  
//*** updater ****************************************************************
	  	
	/*******************************************************************************
	* M�thode pour mettre � jour les donn�es en base
	* 
	* Parametres : aucun 
	* Retour : bool : true  : sauvegarde ok 
	*          false : probl�me lors de la sauvegarde                          
	*******************************************************************************/
	public  function update() 
	{ 
    //- d�pose des attributs dans un tableau nom_champ=>valeur
    $arra_champ['TC_ID_TABLE_CHAMP'] = $this->int_tc_id_table_champ;                                       
    $arra_champ['TC_MODEL']          = $this->stri_tc_model        ;                                       
    $arra_champ['TC_NOM_TABLE']      = $this->stri_tc_nom_table    ;                                       
    $arra_champ['TC_NOM_CHAMP']      = $this->stri_tc_nom_champ    ;                                       
    $arra_champ['TC_VALEUR']         = $this->stri_tc_valeur       ;                                       
    $arra_champ['TC_UID']            = $this->int_tc_uid           ;                                       
    $arra_champ['TC_ROLE']           = $this->stri_tc_role         ;                                       	
  		
		//- D�claration requ�te d'update
		$obj_query=new querry_update('table_champ');			
    		
		//- Affectation des champs en fonction de leur valeur   
    foreach($arra_champ as $stri_champ=>$stri_value)
    {
      //-- si le champ est null, on ne l'ajoute pas � la requ�te, cela permet de g�rer l'update partiel  
      if(!is_null($stri_value))
      {
       $obj_query->addField($stri_champ, $stri_value);
      }
    }
       
    //- D�finition du where
    $obj_query->addKey('TC_ID_TABLE_CHAMP',$this->int_tc_id_table_champ);                                         
   
    //- Affectation des attributs automatiques de tra�abilit�   
    $this->int_tc_upd_uid = pnusergetvar("uid");                                       
    $this->date_tc_upd_date = date("Y-m-d H:i:s");                                       	 	     
    
    //- Ajout � la requ�te des champs de tra�abilit�
    $obj_query->addField('TC_UPD_DATE',$this->date_tc_upd_date);                                       
    $obj_query->addField('TC_UPD_UID',$this->int_tc_upd_uid);                                       	
      
		//- Execution de l'insertion
    $bool_ok=$obj_query->execute();
		//echo $obj_query->generateSql()."<br />";
		  
		return $bool_ok;  
	}
 
//*** deleter ****************************************************************
 /*******************************************************************************
	* M�thode pour supprimer � partir de la pk
	* 
	* Parametres : aucun 
	* Retour : bool : true  : sauvegarde ok 
	*          false : probl�me lors de la sauvegarde                          
	*******************************************************************************/
	public  function delete() 
	{ 
     
    //- cr�ation de requ�te de suppression
    $obj_query=new querry_delete('table_champ');
    
    //- D�finition du where
    $obj_query->addKey('TC_ID_TABLE_CHAMP',$this->int_tc_id_table_champ);                                         

    //echo $obj_query->generateSql()."<br />";
    return  $obj_query->execute();
  }

//***  M�thodes de redirection *************************************************


		
	/*******************************************************************************
	* Redirection vers la m�thode htmlValue du viewer
	* 
	* Parametres : $stri_retour : le type de retour souhait� [form,html,table]
	* Retour : mixed : d�pend du param�tres $stri_retour :
	*                  form  : string , le code html encapsul� dans un formulaire
	*                  html  : string , le code html � afficher
	*                  table : obj classe table , la table � utiliser                         
	*******************************************************************************/
	public  function htmlValue($stri_retour="html") 
	{ 
		return $this->obj_viewer->htmlValue($stri_retour);    
	} 
}

?>

