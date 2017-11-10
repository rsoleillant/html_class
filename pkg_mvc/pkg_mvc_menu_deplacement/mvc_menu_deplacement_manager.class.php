<?php
/*******************************************************************************
Create Date  : 2016-10-04
 ----------------------------------------------------------------------
 Class name  : mvc_menu_deplacement_manager
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Contr�leur du pattern MVC pour le mod�le mvc_menu_deplacement
 
********************************************************************************/
class mvc_menu_deplacement_manager extends mvc_std_manager{
   
//**** Attributs ****************************************************************
	protected $obj_mvc_menu_deplacement;    //Le mod�le � manager
	protected static $int_nb_instance=0;                        //Le nombre d'instance de mod�le trait�  
  
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
    $this->obj_mvc_menu_deplacement=$obj_mvc_menu_deplacement;//affectation du mod�le
	}
	

//*** getter ******************************************************************
  public  function getModel() {	return $this->obj_mvc_menu_deplacement;}
	public  function getMvcMenuDeplacement() {	return $this->obj_mvc_menu_deplacement;}


//*** traitement **************************************************************
	/*******************************************************************************
	* M�thode g�n�rique de lancement des traitement
	*   
	*   Parametres: aucun
	*                   true en cas de succ�s, 
	*                   false en cas d'erreur
	* 
	* Parametres :  
	* Retour : bool : r�sultat du traitement                         
	*******************************************************************************/
	public  function manage() 
	{ 
    //- initialisation du r�sultat du traitement 
		$this->bool_manage_result=true;
     
    //- d�tection d'actionSave    
    if(isset($_POST['actionSave_x']))//si on fait l'action de sauvegarde
    {              
      if(isset($_POST['mvc_menu_deplacement__int_mmd_id'])) //si on dispose de la clef primaire
      {       
         $this->retrievePostData();//r�cup�ration des donn�es
         $this->bool_manage_result=$this->actionSave();      //sauvegarde
      }
    }
    
		//- Retour   
		return $this->bool_manage_result;  
	}
	
	
 /*******************************************************************************
	* Permet de r�cup�rer les donn�es du mod�le depuis la variable $_POST
	* 
	* Parametres : aucun 
	* Retour : aucun                         
	*******************************************************************************/
	public  function retrievePostData() 
	{ 
    //- d�claration d'un tableau des champs du mod�le 
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
      //- gestion du null pour mise � jour partielle dans le mod�le
      $stri_value=(isset($_POST[$stri_index_post]))?$_POST[$stri_index_post]:null;      
      
      //-- r�cup�ration en post et passage au mod�le
      $this->obj_mvc_menu_deplacement->$stri_setter($_POST[$stri_index_post]);
      
    }  

	}
  
 /*******************************************************************************
	* Permet de r�cup�rer les donn�es du mod�le depuis une collection de donn�e dans 
	* la variable $_POST
	* 
	* Parametres : aucun 
	* Retour : aucun                         
	*******************************************************************************/
  
	public  function retrievePostDataFromCollection() 
	{ 
       //- d�claration d'un tableau des champs du mod�le 
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
      //- gestion du null pour mise � jour partielle dans le mod�le
      $stri_value=(isset($_POST[$stri_index_post][$int_nb_instance]))?$_POST[$stri_index_post][$int_nb_instance]:null;      
      
      //-- r�cup�ration en post et passage au mod�le
      $this->obj_->$stri_setter($_POST[$stri_index_post][$int_nb_instance]);    
    } 
    
    //- incr�mentation du nombre d'instance trait�e
    self::$int_nb_instance++; 	 
      
	}
  
  
 //*** action ******************************************************************
	
	/*******************************************************************************
	* Permet de sauvegarder les donn�es du mod�le dans la base 
	*  indif�remment qu'il s'aggisse d'un update ou d'un insert
	*   
	*   Parametres: aucun
	*                     false : �chec de la sauvegarde
	* 
	* Parametres :  
	* Retour : bool :   true  : sauvegarde r�ussie                         
	*******************************************************************************/
	public  function actionSave() 
	{ 
		$bool_ok = true;    
    $bool_ok = $this->obj_mvc_menu_deplacement->save(); //ordre de sauvegarde au mod�le
            
    if($bool_ok)
    {$this->addMessage(_MSG_SAVE_OK,"green");}//si la sauvegarde c'est bien pass�
    else
    {$this->addMessage(_MSG_SAVE_NOK);}//en cas d'erreur
    
    return $bool_ok;  
	}


 
}

?>
