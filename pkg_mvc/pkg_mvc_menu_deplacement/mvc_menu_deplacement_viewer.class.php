<?php
/*******************************************************************************
Create Date  : 2016-10-04
 ------------------------------------------------------------------------------
 Class name  : mvc_menu_deplacement_viewer
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Vue du pattern MVC pour le modèle mvc_menu_deplacement_viewer
 
********************************************************************************/
class mvc_menu_deplacement_viewer extends mvc_std_viewer{
   
//**** Attributs ****************************************************************
	protected $obj_mvc_menu_deplacement;  //Le modèle à représenter
	 

//**** Methodes *****************************************************************  
 //*** constructor *************************************************************
	
	/*******************************************************************************
	* 
	* 
	* Parametres :  
	* Retour : objet de la classe  grt_projet_viewer                         
	*******************************************************************************/
	public  function __construct(mvc_menu_deplacement $obj_mvc_menu_deplacement) 
	{ 
    parent::__construct();
		$this->obj_mvc_menu_deplacement=$obj_mvc_menu_deplacement;  //affectation du modèle    		  
	}
//*** getter ******************************************************************
	public  function getMvcMenuDeplacement() {	return $this->obj_mvc_menu_deplacement;}
	public  function getModel() {	return $this->obj_mvc_menu_deplacement;}
	public  function getManager() {	return $this->obj_mvc_menu_deplacement->getManager();}

//*** setter ******************************************************************

//*** représentations partielles ***********************************************


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
    unset($arra_bouton['actionBack']);
    
    
    return  $arra_bouton;
  }  	
 /*******************************************************************************
	*Permet d'obtenir la représentation sous forme de tr pour être incluse dans un loader
	*                                                                                                               
	* Parametres : aucun
	* Retour : obj tr                    
	*******************************************************************************/         
  public function toTrForLoader()
  {   
     $obj_model=$this->obj_mvc_menu_deplacement;
     
     $obj_tr=new tr();    
     $obj_tr->setClass('std_viewer__toTrForLoader '.get_class($obj_model));
      $obj_td=$obj_tr->addTd($obj_model->getMmdId());            
      $obj_td=$obj_tr->addTd($obj_model->getMmdMmId());            
      $obj_td=$obj_tr->addTd($obj_model->getMmdMvcCible());            
      $obj_td=$obj_tr->addTd($obj_model->getMmdMvcAttribut());            
      $obj_td=$obj_tr->addTd($obj_model->getMmdDestinationMvc());            
      $obj_td=$obj_tr->addTd($obj_model->getMmdDestinationId());            
      $obj_td=$obj_tr->addTd($obj_model->getMmdDestinationViewer());            
      $obj_td=$obj_tr->addTd($obj_model->getMmdViewerMethode());            	

    //- ajout d'info de traçabilité
    $obj_tr->addData('model','mvc_menu_deplacement');
    $obj_tr->addData('idmvc',$obj_model->getIdMvc());
    $obj_tr->addData('pk','int_mmd_id');
     
     return $obj_tr;
  }
  
 /*******************************************************************************
	*Permet d'obtenir la représentation sous forme de tr contenant des champs éditable
	*pour être incluse dans un loader gérant la mise à jour de masse.
	*                                                                                                               
	* Parametres : aucun
	* Retour : obj tr                    
	*******************************************************************************/         
  public function constructTableForMasseLoader()
  {   
     $obj_model=$this->obj_mvc_menu_deplacement;
    
    //- construction des représentation
    $obj_mmd_id                = new text('mvc_menu_deplacement__int_mmd_id[]',$this->obj_mvc_menu_deplacement->getMmdId())                                ;         
    $obj_mmd_mm_id             = new text('mvc_menu_deplacement__int_mmd_mm_id[]',$this->obj_mvc_menu_deplacement->getMmdMmId())                           ;         
    $obj_mmd_mvc_cible         = new text('mvc_menu_deplacement__stri_mmd_mvc_cible[]',$this->obj_mvc_menu_deplacement->getMmdMvcCible())                  ;         
    $obj_mmd_mvc_attribut      = new text('mvc_menu_deplacement__stri_mmd_mvc_attribut[]',$this->obj_mvc_menu_deplacement->getMmdMvcAttribut())            ;         
    $obj_mmd_destination_mvc   = new text('mvc_menu_deplacement__stri_mmd_destination_mvc[]',$this->obj_mvc_menu_deplacement->getMmdDestinationMvc())      ;         
    $obj_mmd_destination_id    = new text('mvc_menu_deplacement__stri_mmd_destination_id[]',$this->obj_mvc_menu_deplacement->getMmdDestinationId())        ;         
    $obj_mmd_destination_viewer= new text('mvc_menu_deplacement__stri_mmd_destination_viewer[]',$this->obj_mvc_menu_deplacement->getMmdDestinationViewer());         
    $obj_mmd_viewer_methode    = new text('mvc_menu_deplacement__stri_mmd_viewer_methode[]',$this->obj_mvc_menu_deplacement->getMmdViewerMethode())        ;           
    
    //- Positionnement des éléments de la table 1
		$obj_table_1 = new table();
        $obj_tr = $obj_table_1->addTr(); 
            $obj_td = 	$obj_tr->addTd($obj_mmd_id)                ;
            $obj_td = 	$obj_tr->addTd($obj_mmd_mm_id)             ;
            $obj_td = 	$obj_tr->addTd($obj_mmd_mvc_cible)         ;
            $obj_td = 	$obj_tr->addTd($obj_mmd_mvc_attribut)      ;
            $obj_td = 	$obj_tr->addTd($obj_mmd_destination_mvc)   ;
            $obj_td = 	$obj_tr->addTd($obj_mmd_destination_id)    ;
            $obj_td = 	$obj_tr->addTd($obj_mmd_destination_viewer);
            $obj_td = 	$obj_tr->addTd($obj_mmd_viewer_methode)    ;                 

    $obj_table_1->setClass('mvc_menu_deplacement_viewer__constructTableForMasseLoader');
    
     return $obj_table_1;
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
    $obj_table=parent::constructTableForHeader("images/mvc_atomique.png");
       
     return $obj_table;	
	}
  
   //Permet de construire une chaîne js pour effectuer le déplacement sur les donées portées par le modèle
  public function constructJsDeplacement()
  {
     //- récupération des données du déplacement
     $stri_mmd_mvc_cible           =  $this->obj_mvc_menu_deplacement->getMmdMvcCible();
  	 $stri_mmd_mvc_attribut        =  $this->obj_mvc_menu_deplacement->getMmdMvcAttribut();
  	 $stri_mmd_destination_mvc     =  $this->obj_mvc_menu_deplacement->getMmdDestinationMvc();
  	 $stri_mmd_destination_id      =  $this->obj_mvc_menu_deplacement->getMmdDestinationId();
  	 $stri_mmd_destination_viewer  =  $this->obj_mvc_menu_deplacement->getMmdDestinationViewer();
  	 $stri_mmd_viewer_methode      =  $this->obj_mvc_menu_deplacement->getMmdViewerMethode();
        
     //- création du déplacement 
     $stri_js="mvc_std_viewer.moveTo('$stri_mmd_mvc_cible','$stri_mmd_mvc_attribut','$stri_mmd_destination_mvc','$stri_mmd_destination_id','$stri_mmd_destination_viewer','$stri_mmd_viewer_methode');";
      
     return $stri_js;                       
  }
  
//*** représentations complètes ************************************************
	
 /*******************************************************************************
	* Pour obtenir la table représentant le modèle
	*                                                                                                               
	* Parametres : aucun
	* Retour : obj table                    
	*******************************************************************************/
	public  function constructTableForMain() 
	{ 		
    //- construction des représentation
    $obj_mmd_id = new text('mvc_menu_deplacement__int_mmd_id',$this->obj_mvc_menu_deplacement->getMmdId());
    $obj_font_mmd_id = new font(_LIB_MMD_ID);
           
    $obj_mmd_mm_id = new text('mvc_menu_deplacement__int_mmd_mm_id',$this->obj_mvc_menu_deplacement->getMmdMmId());
    $obj_font_mmd_mm_id = new font(_LIB_MMD_MM_ID);
           
    $obj_mmd_mvc_cible = new text('mvc_menu_deplacement__stri_mmd_mvc_cible',$this->obj_mvc_menu_deplacement->getMmdMvcCible());
    $obj_font_mmd_mvc_cible = new font(_LIB_MMD_MVC_CIBLE);
           
    $obj_mmd_mvc_attribut = new text('mvc_menu_deplacement__stri_mmd_mvc_attribut',$this->obj_mvc_menu_deplacement->getMmdMvcAttribut());
    $obj_font_mmd_mvc_attribut = new font(_LIB_MMD_MVC_ATTRIBUT);
           
    $obj_mmd_destination_mvc = new text('mvc_menu_deplacement__stri_mmd_destination_mvc',$this->obj_mvc_menu_deplacement->getMmdDestinationMvc());
    $obj_font_mmd_destination_mvc = new font(_LIB_MMD_DESTINATION_MVC);
           
    $obj_mmd_destination_id = new text('mvc_menu_deplacement__stri_mmd_destination_id',$this->obj_mvc_menu_deplacement->getMmdDestinationId());
    $obj_font_mmd_destination_id = new font(_LIB_MMD_DESTINATION_ID);
           
    $obj_mmd_destination_viewer = new text('mvc_menu_deplacement__stri_mmd_destination_viewer',$this->obj_mvc_menu_deplacement->getMmdDestinationViewer());
    $obj_font_mmd_destination_viewer = new font(_LIB_MMD_DESTINATION_VIEWER);
           
    $obj_mmd_viewer_methode = new text('mvc_menu_deplacement__stri_mmd_viewer_methode',$this->obj_mvc_menu_deplacement->getMmdViewerMethode());
    $obj_font_mmd_viewer_methode = new font(_LIB_MMD_VIEWER_METHODE);
                 
		
		//- Positionnement des éléments de la table 1
		$obj_table_1 = new table();
        $obj_tr = $obj_table_1->addTr();
            $obj_td=$obj_tr->addTd($this->constructTableForHeader());
            $obj_td->setColspan(2);
        $obj_tr = $obj_table_1->addTr();
      			$obj_td = 	$obj_tr->addTd($this->obj_mvc_menu_deplacement->getManager()->getHtmlMessage());
      				$obj_td->setColspan(2);
      				$obj_td->setAlign("center");
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_mmd_id);
            $obj_td = 	$obj_tr->addTd($obj_mmd_id);
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_mmd_mm_id);
            $obj_td = 	$obj_tr->addTd($obj_mmd_mm_id);
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_mmd_mvc_cible);
            $obj_td = 	$obj_tr->addTd($obj_mmd_mvc_cible);
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_mmd_mvc_attribut);
            $obj_td = 	$obj_tr->addTd($obj_mmd_mvc_attribut);
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_mmd_destination_mvc);
            $obj_td = 	$obj_tr->addTd($obj_mmd_destination_mvc);
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_mmd_destination_id);
            $obj_td = 	$obj_tr->addTd($obj_mmd_destination_id);
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_mmd_destination_viewer);
            $obj_td = 	$obj_tr->addTd($obj_mmd_destination_viewer);
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_mmd_viewer_methode);
            $obj_td = 	$obj_tr->addTd($obj_mmd_viewer_methode);               		
     
    return $obj_table_1;
	}
	


 
}

?>
