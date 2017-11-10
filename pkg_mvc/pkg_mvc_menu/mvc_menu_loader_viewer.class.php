<?php
/*******************************************************************************
Create Date  : 2016-10-04
 ----------------------------------------------------------------------
 Class name  : mvc_menu_loader_viewer
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Viewer du loader mvc_menu_loader
 
********************************************************************************/
class mvc_menu_loader_viewer extends mvc_std_loader_viewer{
   
//**** Attributs ****************************************************************
	 protected $obj_mvc_menu_loader;
	 

//**** Methodes *****************************************************************
//*** 01 Constructor **********************************************************
  
  /*******************************************************************************
	* 
	* 
	* Parametres :  
	* Retour : objet de la classe  grt_projet_manager                         
	*******************************************************************************/
	public  function __construct(mvc_menu_loader $obj_mvc_menu_loader) 
	{ 
		parent::__construct();
    $this->obj_mvc_menu_loader=$obj_mvc_menu_loader;//affectation du modèle
	}
	

//*** getter ******************************************************************
  public  function getModel() {	return $this->obj_mvc_menu_loader;}

//**** Setter ****************************************************************
  

//*** 01 Interface partielle  *************************************************

	
 /*******************************************************************************
	* Permet de représenter une occurence du modèle de la collection
	* 
	* Parametres : aucun 
	* Retour : obj tr                         
	*******************************************************************************/
  public function toTrForUnit($obj_model)
  {
    
    //- récupération du loader
     $obj_loader=$this->getModel();
    
    //- récupération du viewer du modèle
    $obj_viewer=$obj_model->getViewer();
    
    //- construction d'un tr
		$obj_tr=$obj_viewer->toTrForLoader($arra_getter);//changement de la méthode de représentation
    
    //- paramétrage du comportement de survol
    $obj_tr->setOnmouseOver("$(this).addClass('preSelected');");
    $obj_tr->setOnmouseOut("$(this).removeClass('preSelected');");
      
    //- déclaration de bouton de suppression      
    $obj_image_actionDelete=new img("images/PNG/cancel-048x048.png");
        $obj_image_actionDelete->setClass("action infobulle");
        $obj_image_actionDelete->setTitle(_ACTION_DELETE);
        $obj_image_actionDelete->setOnclick("mvc_std_viewer.prepareDelete($(this),event);");
        $obj_image_actionDelete->setStyle("width:25px;");
        
    //- pose du boutton de suppression
    if($obj_loader->getDelete())
    {
      $obj_tr->addTd($obj_image_actionDelete);
    }    
    
     
    //- configuration du déplacement
    $obj_tr->setOnclick("mvc_std_viewer.moveTo('email_chargeur_i_main','obj_emplacement_02','mvc_menu_deplacement_loader',".$obj_model->getIdMvc().",'mvc_menu_deplacement_loader_viewer','constructTableForMain');
                            $(this).closest('form').submit();                                        
                                        
                          "); 
     
     
     return  $obj_tr;
  }
  
  /*******************************************************************************
  * Pour construire la liste des boutons d'interaction
  * 
  * @param : aucun
  * @return : tableau association nom_bouton=>obj_bouton    
  ******************************************************************************/
  public function constructArrayButton()
  {    
    //- récupération des boutons par défaut
    $arra_bouton=parent::constructArrayButton();
   
    //- suppression des boutons superflus
    unset($arra_bouton['actionBack']); 
    
    return  $arra_bouton;
  }
  
 /*******************************************************************************
	* Pour construire l'interface html du header
	* 
	* Parametres : aucun 
	* Retour : obj table : la table html                         
	*******************************************************************************/
	public  function constructTableForHeader($stri_img="",$stri_titre="") 
	{ 
    //- constructio de l'interface
    $obj_table=parent::constructTableForHeader("images/mvc_loader.png");
       
     return $obj_table;	
	}
  
  /*******************************************************************************
	* Pour construire le tr pour représenter les titres des colonnes
	* 
	* Parametres : aucun
	* Retour : obj table : la table html                         
	*******************************************************************************/
	public  function constructTrForColumn() 
	{ 
    //- récupération du modèle
    $obj_model=$this->obj_mvc_menu_loader;
  
    
    //- objets de l'interface
    //-- champs à libellés simple
    
   
    //-- champs de tri
              $obj_mm_id=  $obj_model->getTableChampTri('mm_id');                                            
          $obj_mm_menu=  $obj_model->getTableChampTri('mm_menu');                                            
          $obj_mm_item=  $obj_model->getTableChampTri('mm_item');                                            
    

    //- pose des entêtes
    $obj_tr=new tr();
        $obj_tr->setClass('titre3 mvc_menu_loader_viewer__constructTrForColumn');
          $obj_td=$obj_tr->addTd($obj_mm_id);                                            
          $obj_td=$obj_tr->addTd($obj_mm_menu);                                            
          $obj_td=$obj_tr->addTd($obj_mm_item);                                             
   
    return  $obj_tr;
  }
	
	/*******************************************************************************
	* Pour construire l'interface html des résultats
	* 
	* Parametres : aucun 
	* Retour : obj table : la table html                         
	*******************************************************************************/
	public  function constructTableForResult() 
	{ 
		//- création des entête 
		$obj_table=new table();
		 $obj_tr=$obj_table->addTr();
		 $obj_tr->setClass('titre3');
		$obj_table->setClass('mvc_menu_loader_viewer__constructTableForResult');
    
    //- Récupération de la collection de model
    $obj_loader=$this->getModel();
    $arra_collection=$obj_loader->getCollection();
              
    //- Gestion du modèle de référence
    if($obj_loader->getInsert())//si la collection à le rôle d'ajout d'élément
    {
      $obj_tr=$obj_table->addTr();
         $obj_tr->addTd($obj_loader->getModelReference());
         $obj_tr->setClass('model_reference '.get_class($obj_loader));
      $obj_tr->setStyle('display:none;');          
    }
    
    //- pose des titres des colonnes
    $obj_tr_titre=$this->constructTrForColumn();
    $obj_table->insertTr($obj_tr_titre);
    
		//- Cas de résultat vide
		if(count($arra_collection)==0)
		{
		  $obj_tr=$obj_table->addTr();
		    $obj_tr->setClass('titre3');
		  $obj_tr->addTd(_AUCUN_RESULTAT);
		}
		
		//- Création des lignes
		foreach($arra_collection as $int_key=>$obj_model)
		{	    		
		  //-- représentation de l'unité
      $obj_tr=$this->toTrForUnit($obj_model);
		
		 //-- ajout du modèle dans la table
		 $obj_table->insertTr($obj_tr);
		} 		      
    
		//- Gestion d'alternance des couleurs
		global $bgcolor1,$bgcolor2,$bgcolor3,$bgcolor4,$bgcolor5;
		$obj_table->setWidth('100%');
		$obj_table->alernateColor(1,$bgcolor1,$bgcolor1);
		  
		return $obj_table;   
	}
		

//*** 02 Interface complette **************************************************
	
 /*******************************************************************************
	* Permet de construire l'interface principal
	* 
	* Parametres : aucun 
	* Retour : obj tr                         
	*******************************************************************************/
  public function constructTableForMain()
  {
    $obj_table=new table();
        $obj_tr=$obj_table->addTr();
           $obj_tr->addTd($this->constructTableForHeader());
         $obj_tr=$obj_table->addTr();
           $obj_tr->addTd($this->constructTableForResult());
    
    //- si on a activé la fonctionnalité de pagination
    if($this->obj_mvc_menu_loader->getPagination())
     {
       $obj_tr=$obj_table->addTr();
         $obj_tr->addTd($this->constructTableForPagination());
     }  
         
    return $obj_table;
  }
  

 
}

?>
