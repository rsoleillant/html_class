<?php
/*******************************************************************************
Create Date  : 01/03/2016
 ----------------------------------------------------------------------
 Class name  : mvc_std_loader
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Le contrôleur standard pour les loader 
 
********************************************************************************/
abstract class mvc_std_loader_manager extends mvc_std_manager{
   
//**** Attributs ****************************************************************
	
  //*** Attributs pour la pagination ********************************************
		 
//*** 01 Constructor **********************************************************


//**** Setter ****************************************************************
	
  
//**** Getter ****************************************************************   
 
//**** Methodes *****************************************************************
  
	

//*** 02 Autres méthodes ******************************************************
	 /*******************************************************************************
	* Méthode de lancement des traitements
	* Parametres : aucun 
	* Retour :  mixed                
	*******************************************************************************/
  public function manage()
  {           
      //- détection d'utilisation de la pagination
      if(isset($_POST['actionPagination']))
      {
        $this->getModel()->setNumPage($_POST['actionPagination']);
      }
     
      //- gestion des tri des colonnes
      if(isset($_POST['actionSort_x']))
      {        
        return $this->actionSort();
      }
  }
  
   /*******************************************************************************
  	* Permet de gérer l'enregistrement des tri sur les colonnes
  	* Parametres : aucun 
  	* Retour :  obj model de la classe [model_name]               
  	*******************************************************************************/
    public function actionSort()
    {  
      //- enregistrement des valeurs des tri
      foreach($_POST['table_champ__int_tc_id_table_champ'] as $int_key=>$osef)
      {
        //- test si c'est un tri sur le modèle concerné
        $stri_model_class=get_class($this->getModel());
                                               
        if(($_POST['table_champ__stri_tc_model'][$int_key]==$stri_model_class)&&($_POST["table_champ__stri_tc_role"][$int_key]=="tri"))
        {                                                                    
            //-- récupération de l'instance de table_champ
            $obj_table_champ=new table_champ('',false);
              $obj_table_champ->getManager()->retrievePostDataFromCollection($int_key);
             
          
            //-- gestion de la sauvegarde
            $stri_save_method=($obj_table_champ->getTcValeur()=='none')?'delete':'save';
            $obj_table_champ->$stri_save_method();
             
        }
      }
    }
    
}

?>
