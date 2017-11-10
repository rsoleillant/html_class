<?php
/*******************************************************************************
Create Date  : 2016-10-04
 ----------------------------------------------------------------------
 Class name  : mvc_menu_imbrication_viewer
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Viewer du MVC mvc_menu_imbrication de type imbrication 
 
********************************************************************************/
class  mvc_menu_imbrication_viewer extends mvc_std_viewer{
   
//**** Attributs ****************************************************************
	
	//*** 01 Attributs MVC ********************************************************
	protected $obj_mvc_menu_imbrication ;  //Le modèle à représenter
	
	 

//**** Methodes *****************************************************************

//*** 01 Constructor **********************************************************
	
	/*******************************************************************************
	* Constructeur principal
	* 
	* Parametres : $obj_grt_projet_imbrication : Le modèle à représenter 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function __construct(mvc_menu_imbrication $obj_mvc_menu_imbrication) 
	{ 
		parent::__construct();
    $this->obj_mvc_menu_imbrication=$obj_mvc_menu_imbrication;        //affectation du modèle    
			  
	}

//**** Setter ****************************************************************
  
//**** Getter ****************************************************************   
	public  function getModel()                {	return $this->obj_mvc_menu_imbrication;}
  public  function getMvcMenuImbrication() {	return $this->obj_mvc_menu_imbrication;}
	
	

//*** 02 Interfaces partielles ************************************************
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
    unset($arra_bouton['actionNew']);
    
    //- récupération du modèle
    $obj_model=$this->getModel();
    
    //- s'il n'y a pas d'historique de déplacement 
    if(!$obj_model->getHasHisto())
    {
         unset($arra_bouton['actionBack']);
    }           
    
    return  $arra_bouton;
  }
	
  //Permet de construire une table sur les déplacements possible
  public function constructTableForPossibleMove()
  {
  /*  //- si un déplacement est possible
    $obj_mvc02=$this->obj_grt_projet_imbrication->getMvc02();
    if(!is_object($obj_mvc02))
    {return array();}
    
    $int_id_mvc02=$obj_mvc02->getIdMvc();
    if($int_id_mvc02=="")//si aucun identifiant pour le mvc principal
    {return array();}
    
    //- déclaration des onglets
    $arra_onglet['grt_model_atomique_loader']=new button("grt_projet_imbrication_viewer__constructTableForPossibleMove",_ATOMIQUE);
    $arra_onglet['grt_model_imbrication_loader']=new button("grt_projet_imbrication_viewer__constructTableForPossibleMove",_IMBRICATION);
    $arra_onglet['grt_model_loader_loader']=new button("grt_projet_imbrication_viewer__constructTableForPossibleMove",_LOADER);
  
    //- gestion de sélection de l'onglet actif
    
    $str_classe_mvc02=get_class($obj_mvc02);
    if(!is_object( $arra_onglet[$str_classe_mvc02]))
    {return array();}
       
    $obj_onglet_actif=$arra_onglet[$str_classe_mvc02];
    $obj_onglet_actif->setClass('std_viewer_imbrication onglet_actif');
    $obj_onglet_actif->setStyle('background-color:green;'); 
              
    return  $arra_onglet;   */
  }
  
  //Permet de construire les informations de déplacement
  public function constructTableForMove()
  {    
    //- récupération des informations cible
    $obj_model=$this->getModel();
    $stri_cible_mvc=get_class($obj_model);
   
    //- déclaration du table html   
     $obj_table=new table();
      $obj_tr=$obj_table->addTr();
        $obj_tr->addTd(get_class($this));
     $obj_table->setBorder(1);
     $obj_table->setClass(get_class($this));
     
    //- pose des infos de déplacement en post 
    $obj_tr=$obj_table->addTr();
        $obj_tr->addTd($this->constructArrayForOneMove('obj_mvc_menu','getMvcMenu'));
        $obj_tr->addTd($this->constructTableForOneMoveHisto('obj_mvc_menu'));
                                                                                                       
    $obj_tr=$obj_table->addTr();
        $obj_tr->addTd($this->constructArrayForOneMove('obj_mvc_menu_deplacement_loader','getMvcMenuDeplacementLoader'));
        $obj_tr->addTd($this->constructTableForOneMoveHisto('obj_mvc_menu_deplacement_loader'));
                                                                                                        
   
  
     $obj_table->setStyle('display:none;');
     return $obj_table;
  }
  
  //Permet de construire l'interface contenant les informations sur un seul déplacement
  public function constructArrayForOneMove($stri_cible_mvc,$stri_getter)
  {
 
    //- récupération des information de destination
    $obj_model=$this->getModel();    
    $obj_mvc=$obj_model->$stri_getter();
    
    $stri_destination_mvc_model="";
    $stri_destination_mvc_id="";
    $stri_destination_mvc_viewer= "";
    $stri_destination_mvc_viewer_methode="";
    if(is_object($obj_mvc))//si un mvc est chargé 
    {
      $obj_mvc_viewer=$obj_mvc->getViewer();
      $stri_destination_mvc_model=get_class($obj_mvc);
      $mixed_destination_mvc_id=$obj_mvc->getIdMvc();
  
      $stri_destination_mvc_id=$mixed_destination_mvc_id;
      $stri_destination_mvc_viewer= get_class($obj_mvc_viewer);
      $stri_destination_mvc_viewer_methode=$obj_mvc_viewer->getMainMethod();
    }          
       
    //- construction de l'interface
    $stri_base_id='mvc_menu_imbrication__'.$stri_cible_mvc;
    
    $obj_text_destination_mvc_model          = new text($stri_base_id.'__mvc_model',$stri_destination_mvc_model);
      $obj_text_destination_mvc_model->setClass('mvc_menu_imbrication');
    $obj_text_destination_mvc_id             = new text($stri_base_id.'__mvc_id',$stri_destination_mvc_id);
      $obj_text_destination_mvc_id->setClass('mvc_menu_imbrication');
    $obj_text_destination_mvc_viewer         = new text($stri_base_id.'__mvc_viewer',$stri_destination_mvc_viewer);
      $obj_text_destination_mvc_viewer->setClass('mvc_menu_imbrication');
    $obj_text_destination_mvc_viewer_methode = new text($stri_base_id.'__mvc_viewer_methode',$stri_destination_mvc_viewer_methode);
      $obj_text_destination_mvc_viewer_methode->setClass('mvc_menu_imbrication');
    return array($obj_text_destination_mvc_model,$obj_text_destination_mvc_id,$obj_text_destination_mvc_viewer,$obj_text_destination_mvc_viewer_methode); 
  }
  
   //Permet de construire les informations d'historique de déplacement
  public function constructTableForOneMoveHisto($stri_cible_mvc)
  {
     
     $stri_histo_base='histo_mvc_menu_imbrication';     
     $stri_base_id=$stri_histo_base.'__'.$stri_cible_mvc;
     $obj_table=new table();
     $obj_model=$this->getModel();
     foreach($_POST[$stri_base_id.'__mvc_id'] as $int_key=>$stri_osef)
     {
          //- création des champs d'historique
          $obj_text_destination_mvc_model          = new text($stri_base_id.'__mvc_model[]',$_POST[$stri_base_id.'__mvc_model'][$int_key]);
            $obj_text_destination_mvc_model->setClass($stri_histo_base);
          $obj_text_destination_mvc_id             = new text($stri_base_id.'__mvc_id[]',$_POST[$stri_base_id.'__mvc_id'][$int_key]);
            $obj_text_destination_mvc_id->setClass($stri_histo_base);
          $obj_text_destination_mvc_viewer         = new text($stri_base_id.'__mvc_viewer[]',$_POST[$stri_base_id.'__mvc_viewer'][$int_key]);
            $obj_text_destination_mvc_viewer->setClass($stri_histo_base);
          $obj_text_destination_mvc_viewer_methode = new text($stri_base_id.'__mvc_viewer_methode[]',$_POST[$stri_base_id.'__mvc_viewer_methode'][$int_key]);
            $obj_text_destination_mvc_viewer_methode->setClass($stri_histo_base);
            
          //- mise en interface
          $obj_tr=$obj_table->addTr();
            $obj_tr->addTd(array($obj_text_destination_mvc_model,$obj_text_destination_mvc_id,$obj_text_destination_mvc_viewer,$obj_text_destination_mvc_viewer_methode));            
     }
     if(is_object($obj_tr))
     {
       $obj_tr->setClass('constructTableForOneMoveHisto__mvc_menu_imbrication');  
     }
      
    
     return $obj_table;
  }
  
  //Pour passer en disabled l'ensemble des champs des mvc imbriqués
  public function setDisabled($bool_disabled)
  {
     //- récupération des attributs
     $obj_model=$this->getModel();
       
     //- traitement des attribut
    if(is_object($obj_model->getMvcMenu()))
    {
      $obj_model->getMvcMenu()->getViewer()->setDisabled($bool_disabled);
    }
    
    if(is_object($obj_model->getMvcMenuDeplacementLoader()))
    {
      $obj_model->getMvcMenuDeplacementLoader()->getViewer()->setDisabled($bool_disabled);
    }
     
   

  }
  
  //Pour construire un tr destiné à être intégré dans un loader
  public function toTrForLoader()
  {
    //- construction du tr parent
    $obj_tr=parent::toTrForLoader();
    $arra_all_td=array();
    
    //- concaténation des tr pour chaque attribut d'imbrication
    $obj_model=$this->getModel();
    
  $obj_tmp_tr=$obj_model->getMvcMenu()->getViewer()->toTrForLoader();
      $arra_td=$obj_tmp_tr->getTd();
      $arra_all_td=array_merge($arra_all_td,$arra_td);
      
  $obj_tmp_tr=$obj_model->getMvcMenuDeplacementLoader()->getViewer()->toTrForLoader();
      $arra_td=$obj_tmp_tr->getTd();
      $arra_all_td=array_merge($arra_all_td,$arra_td);
       
    
    //- placement de l'ensemble des td dans le tr
    $obj_tr->setTd($arra_all_td);
    
    return $obj_tr;
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
    $obj_table=parent::constructTableForHeader("images/mvc_imbrication.png");
       
     return $obj_table;	
	} 
//*** 03 Interfaces complette *************************************************
	/**
	 * représentation sous forme d'onglet 
	 **/
   public function toOnglet()
   {
      //- gestion actif / inactif
      $stri_css_actif= ($this->obj_mvc_menu_imbrication->getSelected())?"ui-state-active":"ui-state-default";
      
      //- construction du bouton 
      $stri_mm_item=constante::constant($this->obj_mvc_menu_imbrication->getMvcMenu()->getMmItem()) ;
      $obj_button=new button('bt',$stri_mm_item);
         //$obj_button->setClass($stri_css_actif.' ui-corner-top mvc_menu_imbrication clickable button');        
         $obj_button->setClass($stri_css_actif.' mvc_menu_imbrication clickable button');        
         $obj_button->addData('id_mvc',$this->obj_mvc_menu_imbrication->getIdMvc());
       
      return $obj_button;
   }     
  
  
	/*******************************************************************************
	* Pour construire l'interface principale
	* 
	* Parametres : aucun
	* Retour : obj_table : la table contenant l'ensemble de l'interface        
	*******************************************************************************/
	public  function constructTableForMain() 
	{ 
	
		//- Construction des objets de la table 1
    $obj_model=$this->obj_mvc_menu_imbrication;
		$obj_message_manager=$obj_model->getManager()->getHtmlMessage();
		
		//- Positionnement des parties fixes de l'interface
		$obj_table_1 = new table();
		$obj_table_1->setWidth("100%");
		$obj_tr = $obj_table_1->addTr();
			$obj_td = 	$obj_tr->addTd($this->constructTableForHeader());
		 $obj_tr = $obj_table_1->addTr();				
        $obj_td = 	$obj_tr->addTd($this->constructTableForMove());  
		$obj_tr = $obj_table_1->addTr();
			$obj_td = 	$obj_tr->addTd($obj_message_manager);
				$obj_td->setWidth("100%");
				$obj_td->setAlign("center");
		
		
		//- Positionnement des MVC
    $obj_tr = $obj_table_1->addTr();
			 $obj_td = 	$obj_tr->addTd($obj_model->getMvcMenu());
    $obj_tr = $obj_table_1->addTr();
			 $obj_td = 	$obj_tr->addTd($obj_model->getMvcMenuDeplacementLoader());      
	
    /*$obj_tr = $obj_table_1->addTr();
		   $obj_td = 	$obj_tr->addTd($this->constructTableForPossibleMove()); */  
		
		
		//- affectation de la table principale
		$this->obj_main_table=$obj_table_1;
	
		  
		return $this->obj_main_table;  
	}
  

}

?>
