<?php
/*******************************************************************************
Create Date  : 2016-10-04
 ----------------------------------------------------------------------
 Class name  : mvc_menu_i_loader_manager
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Viewer du loader mvc_menu_i_loader        
 
********************************************************************************/

class mvc_menu_i_loader_manager extends mvc_std_loader_manager{
   
//**** Attributs ****************************************************************
	  protected $obj_mvc_menu_i_loader;

//*** 01 Constructor **********************************************************
 /*******************************************************************************
	* 
	* 
	* Parametres :  
	* Retour : objet de la classe  grt_projet_manager                         
	*******************************************************************************/
	public  function __construct(mvc_menu_i_loader $obj_mvc_menu_i_loader) 
	{ 
		parent::__construct();
    $this->obj_mvc_menu_i_loader=$obj_mvc_menu_i_loader;//affectation du modèle
	}
		 

//**** Methodes ****************************************************************

//**** Setter ******************************************************************
  
//**** Getter ******************************************************************   
  public  function getModel() {	return $this->obj_mvc_menu_i_loader;}
	public  function getMvcMenuILoader() {	return $this->mvc_menu_i_loader;}
  	
//*** Autres méthodes **********************************************************
 
 /*******************************************************************************
	* Méthode de lancement des traitements
	* Parametres : aucun 
	* Retour :  mixed                
	*******************************************************************************/
  public function manage()
  {  
      //- lancement du traitement parent
     /* parent::manage();
      
      if(isset($_POST['actionDelete']))
      {
        return $this->actionDelete();
      }
      
      if(isset($_POST['actionAdd']))
      {
        $obj_model=$this->actionAdd();      //ajout à la collection                  
        $obj_model->getManager()->manage();//propagation de l'ordre de management (récupération des données post + sauvegarde en base)
        return $obj_model;
      } */ 
      
      //- gestion de la sélection
      if(isset($_POST['mvc_menu_i_loader__selected']))
      { 
        $this->obj_mvc_menu_i_loader->setSelected($_POST['mvc_menu_i_loader__selected']);    
      }
  }
    
   /*******************************************************************************
  	* Permet d'ajouter un seul modèle à la collection. N'ajoute pas en base
  	* Parametres : aucun 
  	* Retour :  obj model de la classe [model_name]               
  	*******************************************************************************/
    public function actionAdd()
    {       
         //- instanciation pour sauvegarde dans la collection
         $obj_model=new mvc_menu_imbrication('',false);   
         
         //- ajout du modèle à la collection
         $this->obj_mvc_menu_i_loader->addToCollection($obj_model); 
          
         //- retour du modèle ajouté
         return  $obj_model;        
    }
    
   /*******************************************************************************
  	* Permet de supprimer des modèles de la collection (supprime en base)
  	* Parametres : aucun 
  	* Retour :  bool : true si la suppression c'est bien passée, false sinon
  	*******************************************************************************/
    public function actionDelete()
    {    
       
         
         //- récupération des info post
         $stri_post_name='mvc_menu_imbrication____delete';
         $bool_ok=true;
         $obj_loader= $this->obj_mvc_menu_i_loader;
                
         foreach($_POST[$stri_post_name] as $stri_val_pk)
         {
            //- création d'un sous-modèle
            $obj_sous_modele=new mvc_menu_imbrication(array(''=>$stri_val_pk),false);
            
            //- ordre de suppression de la base
            $bool_ok=$obj_sous_modele->delete()&&$bool_ok;
            
            //- suppression de la collection
            $obj_loader->getElementCollectionByIdMvc($stri_val_pk);
            $obj_loader->deleteFromCollection($obj_model);  
         }
         
         //- ajout du message de fin de traitement
         if($bool_ok)
         {
          $this->addMessage(_MSG_DELETE_OK,"green");
          return true;
         }
          
          $this->addMessage(_MSG_DELETE_NOT_OK,"red");
          return false;
       
    }
    

}

?>
