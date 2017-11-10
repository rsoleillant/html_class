<?php
/*******************************************************************************
Create Date  : 2016-10-04
 ------------------------------------------------------------------------------
 Class name  : mvc_menu_viewer
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Vue du pattern MVC pour le modèle mvc_menu_viewer
 
********************************************************************************/
class mvc_menu_viewer extends mvc_std_viewer{
   
//**** Attributs ****************************************************************
	protected $obj_mvc_menu;  //Le modèle à représenter
	 

//**** Methodes *****************************************************************  
 //*** constructor *************************************************************
	
	/*******************************************************************************
	* 
	* 
	* Parametres :  
	* Retour : objet de la classe  grt_projet_viewer                         
	*******************************************************************************/
	public  function __construct(mvc_menu $obj_mvc_menu) 
	{ 
    parent::__construct();
		$this->obj_mvc_menu=$obj_mvc_menu;  //affectation du modèle    		  
	}
//*** getter ******************************************************************
	public  function getMvcMenu() {	return $this->obj_mvc_menu;}
	public  function getModel() {	return $this->obj_mvc_menu;}
	public  function getManager() {	return $this->obj_mvc_menu->getManager();}

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
     $obj_model=$this->obj_mvc_menu;
     
     $obj_tr=new tr();    
     $obj_tr->setClass('std_viewer__toTrForLoader '.get_class($obj_model));
      $obj_td=$obj_tr->addTd($obj_model->getMmId());            
      $obj_td=$obj_tr->addTd($obj_model->getMmMenu());            
      $obj_td=$obj_tr->addTd($obj_model->getMmItem());            	

    //- ajout d'info de traçabilité
    $obj_tr->addData('model','mvc_menu');
    $obj_tr->addData('idmvc',$obj_model->getIdMvc());
    $obj_tr->addData('pk','int_mm_id');
     
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
     $obj_model=$this->obj_mvc_menu;
    
    //- construction des représentation
    $obj_mm_id  = new text('mvc_menu__int_mm_id[]',$this->obj_mvc_menu->getMmId())     ;         
    $obj_mm_menu= new text('mvc_menu__stri_mm_menu[]',$this->obj_mvc_menu->getMmMenu());         
    $obj_mm_item= new text('mvc_menu__stri_mm_item[]',$this->obj_mvc_menu->getMmItem());           
    
    //- Positionnement des éléments de la table 1
		$obj_table_1 = new table();
        $obj_tr = $obj_table_1->addTr(); 
            $obj_td = 	$obj_tr->addTd($obj_mm_id)  ;
            $obj_td = 	$obj_tr->addTd($obj_mm_menu);
            $obj_td = 	$obj_tr->addTd($obj_mm_item);                 

    $obj_table_1->setClass('mvc_menu_viewer__constructTableForMasseLoader');
    
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
    $obj_mm_id = new text('mvc_menu__int_mm_id',$this->obj_mvc_menu->getMmId());
    $obj_font_mm_id = new font(_LIB_MM_ID);
           
    $obj_mm_menu = new text('mvc_menu__stri_mm_menu',$this->obj_mvc_menu->getMmMenu());
    $obj_font_mm_menu = new font(_LIB_MM_MENU);
           
    $obj_mm_item = new text('mvc_menu__stri_mm_item',$this->obj_mvc_menu->getMmItem());
    $obj_font_mm_item = new font(_LIB_MM_ITEM);
                 
		
		//- Positionnement des éléments de la table 1
		$obj_table_1 = new table();
        $obj_tr = $obj_table_1->addTr();
            $obj_td=$obj_tr->addTd($this->constructTableForHeader());
            $obj_td->setColspan(2);
        $obj_tr = $obj_table_1->addTr();
      			$obj_td = 	$obj_tr->addTd($this->obj_mvc_menu->getManager()->getHtmlMessage());
      				$obj_td->setColspan(2);
      				$obj_td->setAlign("center");
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_mm_id);
            $obj_td = 	$obj_tr->addTd($obj_mm_id);
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_mm_menu);
            $obj_td = 	$obj_tr->addTd($obj_mm_menu);
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_mm_item);
            $obj_td = 	$obj_tr->addTd($obj_mm_item);               		
     
    return $obj_table_1;
	}
	


 
}

?>
