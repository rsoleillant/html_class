<?php
/*******************************************************************************
Create Date  : 2016-10-04
 ----------------------------------------------------------------------
 Class name  : mvc_menu_deplacement_manager
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Contrôleur du pattern MVC pour le modèle mvc_menu_deplacement
 
********************************************************************************/
class mvc_menu_deplacement_manager extends mvc_std_manager{
   
//**** Attributs ****************************************************************
	protected $obj_mvc_menu_deplacement;    //Le modèle à manager
	protected static $int_nb_instance=0;                        //Le nombre d'instance de modèle traité  
  
//**** Methodes *****************************************************************  

//*** constructor *************************************************************
	
	/*******************************************************************************
	* 
	* 
	* Parametres :  
	* Retour : objet de la classe  grt_projet_manager                         
	*******************************************************************************/
	public  function __construct(mvc_menu_deplacement $obj_mvc_menu_deplacement) 
	{ 
		parent::__construct();
    $this->obj_mvc_menu_deplacement=$obj_mvc_menu_deplacement;//affectation du modèle
	}
	

//*** getter ******************************************************************
  public  function getModel() {	return $this->obj_mvc_menu_deplacement;}
	public  function getMvcMenuDeplacement() {	return $this->obj_mvc_menu_deplacement;}


//*** traitement **************************************************************
	/*******************************************************************************
	* Méthode générique de lancement des traitement
	*   
	*   Parametres: aucun
	*                   true en cas de succès, 
	*                   false en cas d'erreur
	* 
	* Parametres :  
	* Retour : bool : résultat du traitement                         
	*******************************************************************************/
	public  function manage() 
	{ 
    //- initialisation du résultat du traitement 
		$this->bool_manage_result=true;
     
    //- détection d'actionSave    
    if(isset($_POST['actionSave_x']))//si on fait l'action de sauvegarde
    {              
      if(isset($_POST['mvc_menu_deplacement__int_mmd_id'])) //si on dispose de la clef primaire
      {       
         $this->retrievePostData();//récupération des données
         $this->bool_manage_result=$this->actionSave();      //sauvegarde
      }
    }
    
		//- Retour   
		return $this->bool_manage_result;  
	}
	
	
 /*******************************************************************************
	* Permet de récupérer les données du modèle depuis la variable $_POST
	* 
	* Parametres : aucun 
	* Retour : aucun                         
	*******************************************************************************/
	public  function retrievePostData() 
	{ 
    //- déclaration d'un tableau des champs du modèle 
    $arra_attribut['mvc_menu_deplacement__int_mmd_id']                 ="setMmdId"               ;                                    
    $arra_attribut['mvc_menu_deplacement__int_mmd_mm_id']              ="setMmdMmId"             ;                                    
    $arra_attribut['mvc_menu_deplacement__stri_mmd_mvc_cible']         ="setMmdMvcCible"         ;                                    
    $arra_attribut['mvc_menu_deplacement__stri_mmd_mvc_attribut']      ="setMmdMvcAttribut"      ;                                    
    $arra_attribut['mvc_menu_deplacement__stri_mmd_destination_mvc']   ="setMmdDestinationMvc"   ;                                    
    $arra_attribut['mvc_menu_deplacement__stri_mmd_destination_id']    ="setMmdDestinationId"    ;                                    
    $arra_attribut['mvc_menu_deplacement__stri_mmd_destination_viewer']="setMmdDestinationViewer";                                    
    $arra_attribut['mvc_menu_deplacement__stri_mmd_viewer_methode']    ="setMmdViewerMethode"    ;                                    
    
    //- pour chaque champ
    foreach($arra_attribut as $stri_index_post=>$stri_setter)
    {     
      //- gestion du null pour mise à jour partielle dans le modèle
      $stri_value=(isset($_POST[$stri_index_post]))?$_POST[$stri_index_post]:null;      
      
      //-- récupération en post et passage au modèle
      $this->obj_mvc_menu_deplacement->$stri_setter($_POST[$stri_index_post]);
      
    }  

	}
  
 /*******************************************************************************
	* Permet de récupérer les données du modèle depuis une collection de donnée dans 
	* la variable $_POST
	* 
	* Parametres : aucun 
	* Retour : aucun                         
	*******************************************************************************/
  
	public  function retrievePostDataFromCollection() 
	{ 
       //- déclaration d'un tableau des champs du modèle 
    $arra_attribut['mvc_menu_deplacement__int_mmd_id']                 ="setMmdId"               ;                                    
    $arra_attribut['mvc_menu_deplacement__int_mmd_mm_id']              ="setMmdMmId"             ;                                    
    $arra_attribut['mvc_menu_deplacement__stri_mmd_mvc_cible']         ="setMmdMvcCible"         ;                                    
    $arra_attribut['mvc_menu_deplacement__stri_mmd_mvc_attribut']      ="setMmdMvcAttribut"      ;                                    
    $arra_attribut['mvc_menu_deplacement__stri_mmd_destination_mvc']   ="setMmdDestinationMvc"   ;                                    
    $arra_attribut['mvc_menu_deplacement__stri_mmd_destination_id']    ="setMmdDestinationId"    ;                                    
    $arra_attribut['mvc_menu_deplacement__stri_mmd_destination_viewer']="setMmdDestinationViewer";                                    
    $arra_attribut['mvc_menu_deplacement__stri_mmd_viewer_methode']    ="setMmdViewerMethode"    ;                                    
  
 
    //- pour chaque champ
    foreach($arra_attribut as $stri_index_post=>$stri_setter)
    {     
      //- gestion du null pour mise à jour partielle dans le modèle
      $stri_value=(isset($_POST[$stri_index_post][$int_nb_instance]))?$_POST[$stri_index_post][$int_nb_instance]:null;      
      
      //-- récupération en post et passage au modèle
      $this->obj_->$stri_setter($_POST[$stri_index_post][$int_nb_instance]);    
    } 
    
    //- incrémentation du nombre d'instance traitée
    self::$int_nb_instance++; 	 
      
	}
  
  
 //*** action ******************************************************************
	
	/*******************************************************************************
	* Permet de sauvegarder les données du modèle dans la base 
	*  indiféremment qu'il s'aggisse d'un update ou d'un insert
	*   
	*   Parametres: aucun
	*                     false : échec de la sauvegarde
	* 
	* Parametres :  
	* Retour : bool :   true  : sauvegarde réussie                         
	*******************************************************************************/
	public  function actionSave() 
	{ 
		$bool_ok = true;    
    $bool_ok = $this->obj_mvc_menu_deplacement->save(); //ordre de sauvegarde au modèle
            
    if($bool_ok)
    {$this->addMessage(_MSG_SAVE_OK,"green");}//si la sauvegarde c'est bien passé
    else
    {$this->addMessage(_MSG_SAVE_NOK);}//en cas d'erreur
    
    return $bool_ok;  
	}


 
}

?>
