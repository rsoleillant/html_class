<?php
/*******************************************************************************
Create Date  : 2016-10-04 
 ----------------------------------------------------------------------
 Class name  : mvc_menu_imbrication
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Permet de g�rer l'imbrication et l'enchainement de MVC.
 
********************************************************************************/
class mvc_menu_imbrication {
   
//**** Attributs pour d�placement **********************************************
  	protected $obj_mvc_menu;
  	protected $obj_mvc_menu_deplacement_loader;	

//**** Attributs sp�cifiques imbrication ***************************************
  protected $int_id_mvc         ;  //L'identifiant du mvc maitre
  protected $bool_has_histo     ;  //Pour savoir si un historique en post existe pour ce mod�le 

//**** Int�gration dans loader *************************************************
  protected $bool_selected;


//*** Liens MVC ***************************************************************
  protected $obj_manager;
  protected $obj_viewer;

//*** 01 Constructor **********************************************************
	/*******************************************************************************
	* Construteur polymorphe
	* 
	* Parametres : $stri_mixed : array(classe mvc 1=>id_mvc 1,classe mvc 2 =>id mvc 2)
	*                            int : chargement par d�faut sur identifiant du mvc principal
	*                            ""  : chargement par d�faut              
	* Retour : Aucun                         
	*******************************************************************************/
	public  function __construct($stri_mixed, $bool_manage=true) 
	{ 
		//- r�cup�ration du mod�le
    $stri_model=get_class($this);
    $stri_viewer=$stri_model.'_viewer';
    $stri_manager=$stri_model.'_manager';
    
    //- initialisation d'attributs
    $this->bool_has_histo=false;
    $this->bool_selected=false;
    
    //- D�claration de manager
		$this->obj_manager=new $stri_manager($this);//initialisation du manager
			
		//- D�claration du viewer
		$this->obj_viewer=new $stri_viewer($this);//initialisation du viewer
		
		//- Lancement du traitement
		if($bool_manage)
		{  
		 $this->obj_manager->manage(); //lancement des traitement
		}
		
    //- Chargement par d�faut des MVC dynamique        
		if($this->getIdMvc()=="")//si aucun chargement des sous-mod�les n'a �t� fait	
    {           		
      $stri_construct=(is_array($stri_mixed))?"construct1":"construct2";//choix du constructeur      
		  $this->$stri_construct($stri_mixed);//lancement du constructeur  
		} 
			
	}		 

    
	/*******************************************************************************
	* Constructeur complet � partir d'un tableau d'�l�ment
	* 
	* Parametres : $arra_mvc : array[nom_mvc]=>id_mvc : tableau associatif permettant d'affecter chacun des attributs 
	*              ou
	*              $arra_mvc : array[nom_champ]=>valeur champ                           
	* Retour : Aucun                         
	*******************************************************************************/
	public  function construct1($arra_mvc) 
	{ 
       
    //- r�cup�ration de la premi�re clef
    $arra_key=array_keys($arra_mvc);
    $stri_first_key=$arra_key[0];
    
    if(class_exists($stri_first_key)) //si la clef fait r�f�rence � un nom de classe
    {
      return $this->construct1_1($arra_mvc);
    }  
    
    //- cas de construction en passant l'ensemble des donn�es des mod�les atomiques
   $this->construct1_2($arra_mvc);     
	}
  
 /*******************************************************************************
	* Constructeur complet � partir d'un tableau d'�l�ment
	* 
	* Parametres : $arra_mvc : array[nom_mvc]=>id_mvc          : tableau associatif permettant d'affecter chacun des attributs
	*      ou      $arra_mvc : array[nom_mvc]=>array(data_mcv) : tableau associatif permettant chacun des attributs avec l'ensemble des donn�es du mvc
	*                                      
	* Retour : Aucun                         
	*******************************************************************************/
	public  function construct1_1($arra_mvc) 
	{  

    //- r�cup�ration des cl�es du tableaux
    $arra_key=array_keys($arra_mvc);

    //- affecation d'attributs
    $stri_mvc=array_shift($arra_key);
    $mixed_data=$arra_mvc[$stri_mvc];
    $this->obj_mvc_menu=new $stri_mvc($mixed_data,false);
    
    $stri_mvc=array_shift($arra_key);
    $mixed_data=$arra_mvc[$stri_mvc];
    $this->obj_mvc_menu_deplacement_loader=new $stri_mvc($mixed_data,false);
    	      
  }
	
 /*******************************************************************************
	* � partir d'un tableau de donn�es associatives
	* 
	* Parametres :  $arra_mvc : array[nom_champ]=>valeur champ                 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function construct1_2($arra_mvc) 
	{
    //- affecation d'attributs
    $this->obj_mvc_menu=new mvc_menu($arra_mvc,false);
    
    $this->obj_mvc_menu_deplacement_loader=new mvc_menu_deplacement_loader($this->obj_mvc_menu->getMmId(),false);
    	
    $this->int_id_mvc=$this->obj_mvc_menu->getMmId();
  }
	
	
	/*******************************************************************************
	* Constructeur � partir de l'identifiant du MVC
	* 
	* Parametres : $mxd_id_mvc :  
	* Retour : Aucun                         
	*******************************************************************************/
	public  function construct2($int_id_mvc) 
	{ 
    //- affectation de l'identifiant de mvc
    $this->int_id_mvc=$int_id_mvc;
    			
		//- Chargement des mvc fixes 
		$this->load($int_id_mvc);  
	}
	
//**** Methodes *****************************************************************

//**** Setter ****************************************************************
    public  function setMvcMenu($value){ $this->obj_mvc_menu=$value;}
    public  function setMvcMenuDeplacementLoader($value){ $this->obj_mvc_menu_deplacement_loader=$value;}	  

  public  function setIdMvc($mixed_value)   {	$this->int_id_mvc = $mixed_value     ;} 
  public  function setManager($mixed_value) {	$this->obj_manager = $mixed_value     ;}
  public  function setViewer($mixed_value)  {	$this->obj_viewer = $mixed_value      ;}
  public  function setHasHisto($mixed_value){ $this->bool_has_histo = $mixed_value   ;}
  public  function setSelected($value){$this->bool_selected=$value;}

 //Transmission de donn�es 
  public  function setMmdDestinationId($value)    
  {       
    $this->obj_mvc_menu_deplacement_loader->setMmdDestinationId($value);
  }
  //Permet de changer le viewer � partir d'un nom de classe et de la m�thode de repr�sentation principal
  public  function changeViewer($stri_viewer,$stri_viewer_main_methode="constructTableForMain")
  {
     $obj_viewer=new $stri_viewer($this);
     $obj_viewer->setMainMethod($stri_viewer_main_methode);
     $this->setViewer($obj_viewer);
  }
//**** Getter ****************************************************************   
    public  function getMvcMenu(){ return $this->obj_mvc_menu;}
    public  function getMvcMenuDeplacementLoader(){ return $this->obj_mvc_menu_deplacement_loader;}	 
  
  public  function getIdMvc()   {	return $this->int_id_mvc     ;} 
  public  function getManager() {	return $this->obj_manager     ;}
  public  function getViewer()  {	return $this->obj_viewer      ;}
  public  function getHasHisto() { return $this->bool_has_histo ;}
  public  function getSelected(){return $this->bool_selected;}


//*** 02 Loader ***************************************************************
 
 /*******************************************************************************
	* M�thode de chargement par d�faut des mvc
	* 
	* Parametres : $int_id_mvc : l'identifiant du mvc principal 
	* Retour : aucun                         
	*******************************************************************************/
	public  function load($int_id_mvc) 
	{ 
    //- chargement du mvc maitre
    $this->obj_mvc_menu=new mvc_menu($int_id_mvc,false);
    
    $this->obj_mvc_menu_deplacement_loader=new mvc_menu_deplacement_loader($int_id_mvc,false);
    
    $this->int_id_mvc=$int_id_mvc;
    //- chargement des autres mvc 
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
