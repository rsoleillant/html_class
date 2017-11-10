<?php
/*******************************************************************************
Create Date  : 2016-10-04
 ----------------------------------------------------------------------
 Class name  : mvc_menu_deplacement_loader_viewer
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Viewer du loader mvc_menu_deplacement_loader
 
********************************************************************************/
class mvc_menu_deplacement_loader_viewer extends mvc_std_loader_viewer{
   
//**** Attributs ****************************************************************
	 protected $obj_mvc_menu_deplacement_loader;
	 

//**** Methodes *****************************************************************
//*** 01 Constructor **********************************************************
  
  /*******************************************************************************
	* 
	* 
	* Parametres :  
	* Retour : objet de la classe  grt_projet_manager                         
	*******************************************************************************/
	public  function __construct(mvc_menu_deplacement_loader $obj_mvc_menu_deplacement_loader) 
	{ 
		parent::__construct();
    $this->obj_mvc_menu_deplacement_loader=$obj_mvc_menu_deplacement_loader;//affectation du mod�le
	}
	

//*** getter ******************************************************************
  public  function getModel() {	return $this->obj_mvc_menu_deplacement_loader;}

//**** Setter ****************************************************************
  

//*** 01 Interface partielle  *************************************************
  //Permet de construire une cha�ne js pour effectuer le d�placement sur les don�es port�es par les mod�les de la collection
  public function constructJsDeplacement()
  {
     $stri_js="";
     foreach($this->obj_mvc_menu_deplacement_loader->getCollection() as $obj_mvc_menu_deplacement)
     {
        $stri_js.=$obj_mvc_menu_deplacement->getViewer()->constructJsDeplacement();
     }
     
     return $stri_js;
  }
	
 /*******************************************************************************
	* Permet de repr�senter une occurence du mod�le de la collection
	* 
	* Parametres : aucun 
	* Retour : obj tr                         
	*******************************************************************************/
  public function toTrForUnit($obj_model)
  {
    
    //- r�cup�ration du loader
     $obj_loader=$this->getModel();
    
    //- r�cup�ration du viewer du mod�le
    $obj_viewer=$obj_model->getViewer();
    
    //- construction d'un tr
		$obj_tr=$obj_viewer->toTrForLoader($arra_getter);//changement de la m�thode de repr�sentation
    
    //- param�trage du comportement de survol
    $obj_tr->setOnmouseOver("$(this).addClass('preSelected');");
    $obj_tr->setOnmouseOut("$(this).removeClass('preSelected');");
      
    //- d�claration de bouton de suppression      
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
    
     
    //- configuration du d�placement
     $obj_tr->setOnclick("mvc_std_viewer.moveTo('email_chargeur_i_main','obj_emplacement_02','mvc_menu_deplacement',".$obj_model->getIdMvc().",'mvc_menu_deplacement_viewer','constructTableForMain');
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
    //- r�cup�ration des boutons par d�faut
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
	* Pour construire le tr pour repr�senter les titres des colonnes
	* 
	* Parametres : aucun
	* Retour : obj table : la table html                         
	*******************************************************************************/
	public  function constructTrForColumn() 
	{ 
    //- r�cup�ration du mod�le
    $obj_model=$this->obj_mvc_menu_deplacement_loader;
  
    
    //- objets de l'interface
    //-- champs � libell�s simple
              $obj_mmd_id =  new font(_LIB_MMD_ID);                                            
          $obj_mmd_mm_id =  new font(_LIB_MMD_MM_ID);                                            
          $obj_mmd_mvc_cible =  new font(_LIB_MMD_MVC_CIBLE);                                            
          $obj_mmd_mvc_attribut =  new font(_LIB_MMD_MVC_ATTRIBUT);                                            
          $obj_mmd_destination_mvc =  new font(_LIB_MMD_DESTINATION_MVC);                                            
          $obj_mmd_destination_id =  new font(_LIB_MMD_DESTINATION_ID);                                            
          $obj_mmd_destination_viewer =  new font(_LIB_MMD_DESTINATION_VIEWER);                                            
          $obj_mmd_viewer_methode =  new font(_LIB_MMD_VIEWER_METHODE);                                            
   
    //-- champs de tri
    
    

    //- pose des ent�tes
    $obj_tr=new tr();
        $obj_tr->setClass('titre3 mvc_menu_deplacement_loader_viewer__constructTrForColumn');
          $obj_td=$obj_tr->addTd($obj_mmd_id);                                            
          $obj_td=$obj_tr->addTd($obj_mmd_mm_id);                                            
          $obj_td=$obj_tr->addTd($obj_mmd_mvc_cible);                                            
          $obj_td=$obj_tr->addTd($obj_mmd_mvc_attribut);                                            
          $obj_td=$obj_tr->addTd($obj_mmd_destination_mvc);                                            
          $obj_td=$obj_tr->addTd($obj_mmd_destination_id);                                            
          $obj_td=$obj_tr->addTd($obj_mmd_destination_viewer);                                            
          $obj_td=$obj_tr->addTd($obj_mmd_viewer_methode);                                             
   
    return  $obj_tr;
  }
	
	/*******************************************************************************
	* Pour construire l'interface html des r�sultats
	* 
	* Parametres : aucun 
	* Retour : obj table : la table html                         
	*******************************************************************************/
	public  function constructTableForResult() 
	{ 
		//- cr�ation des ent�te 
		$obj_table=new table();
		 $obj_tr=$obj_table->addTr();
		 $obj_tr->setClass('titre3');
		$obj_table->setClass('mvc_menu_deplacement_loader_viewer__constructTableForResult');
    
    //- R�cup�ration de la collection de model
    $obj_loader=$this->getModel();
    $arra_collection=$obj_loader->getCollection();
              
    //- Gestion du mod�le de r�f�rence
    if($obj_loader->getInsert())//si la collection � le r�le d'ajout d'�l�ment
    {
      $obj_tr=$obj_table->addTr();
         $obj_tr->addTd($obj_loader->getModelReference());
         $obj_tr->setClass('model_reference '.get_class($obj_loader));
      $obj_tr->setStyle('display:none;');          
    }
    
    //- pose des titres des colonnes
    $obj_tr_titre=$this->constructTrForColumn();
    $obj_table->insertTr($obj_tr_titre);
    
		//- Cas de r�sultat vide
		if(count($arra_collection)==0)
		{
		  $obj_tr=$obj_table->addTr();
		    $obj_tr->setClass('titre3');
		  $obj_tr->addTd(_AUCUN_RESULTAT);
		}
		
		//- Cr�ation des lignes
		foreach($arra_collection as $int_key=>$obj_model)
		{	    		
		  //-- repr�sentation de l'unit�
      $obj_tr=$this->toTrForUnit($obj_model);
		
		 //-- ajout du mod�le dans la table
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
    
    //- si on a activ� la fonctionnalit� de pagination
    if($this->obj_mvc_menu_deplacement_loader->getPagination())
     {
       $obj_tr=$obj_table->addTr();
         $obj_tr->addTd($this->constructTableForPagination());
     }  
         
    return $obj_table;
  }
  

 
}

?>
