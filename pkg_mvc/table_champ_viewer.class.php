<?php
/*******************************************************************************
Create Date  : 2016-02-29
 ------------------------------------------------------------------------------
 Class name  : table_champ_viewer
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Vue du pattern MVC pour le modèle table_champ_viewer
 
********************************************************************************/
class table_champ_viewer extends mvc_std_viewer{
   
//**** Attributs ****************************************************************
	protected $obj_table_champ;  //Le modèle à représenter
	 

//**** Methodes *****************************************************************  
 //*** constructor *************************************************************
	
	/*******************************************************************************
	* 
	* 
	* Parametres :  
	* Retour : objet de la classe  grt_projet_viewer                         
	*******************************************************************************/
	public  function __construct(table_champ $obj_table_champ) 
	{ 
    parent::__construct();
		$this->obj_table_champ=$obj_table_champ;  //affectation du modèle    		  
	}
//*** getter ******************************************************************
	public  function getTableChamp() {	return $this->obj_table_champ;}
	public  function getModel() {	return $this->obj_table_champ;}
	public  function getManager() {	return $this->obj_table_champ->getManager();}

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
     $obj_model=$this->obj_table_champ;
     
     $obj_tr=new tr();    
     $obj_tr->setClass('std_viewer__toTrForLoader '.get_class($obj_model));
      $obj_td=$obj_tr->addTd($obj_model->getTcIdTableChamp());            
      $obj_td=$obj_tr->addTd($obj_model->getTcModel());            
      $obj_td=$obj_tr->addTd($obj_model->getTcNomTable());            
      $obj_td=$obj_tr->addTd($obj_model->getTcNomChamp());            
      $obj_td=$obj_tr->addTd($obj_model->getTcValeur());            
      $obj_td=$obj_tr->addTd($obj_model->getTcUid());            
      $obj_td=$obj_tr->addTd($obj_model->getTcRole());            	

    //- ajout d'info de traçabilité
    $obj_tr->addData('model','table_champ');
    $obj_tr->addData('idmvc',$obj_model->getIdMvc());
    $obj_tr->addData('pk','int_tc_id_table_champ');
     
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
     $obj_model=$this->obj_table_champ;
    
    //- construction des représentation
    $obj_tc_id_table_champ= new text('table_champ__int_tc_id_table_champ[]',$this->obj_table_champ->getTcIdTableChamp());         
    $obj_tc_model         = new text('table_champ__stri_tc_model[]',$this->obj_table_champ->getTcModel())               ;         
    $obj_tc_nom_table     = new text('table_champ__stri_tc_nom_table[]',$this->obj_table_champ->getTcNomTable())        ;         
    $obj_tc_nom_champ     = new text('table_champ__stri_tc_nom_champ[]',$this->obj_table_champ->getTcNomChamp())        ;         
    $obj_tc_valeur        = new text('table_champ__stri_tc_valeur[]',$this->obj_table_champ->getTcValeur())             ;         
    $obj_tc_uid           = new text('table_champ__int_tc_uid[]',$this->obj_table_champ->getTcUid())                    ;         
    $obj_tc_role          = new text('table_champ__stri_tc_role[]',$this->obj_table_champ->getTcRole())                 ;           
    
    //- Positionnement des éléments de la table 1
		$obj_table_1 = new table();
        $obj_tr = $obj_table_1->addTr(); 
            $obj_td = 	$obj_tr->addTd($obj_tc_id_table_champ);
            $obj_td = 	$obj_tr->addTd($obj_tc_model)         ;
            $obj_td = 	$obj_tr->addTd($obj_tc_nom_table)     ;
            $obj_td = 	$obj_tr->addTd($obj_tc_nom_champ)     ;
            $obj_td = 	$obj_tr->addTd($obj_tc_valeur)        ;
            $obj_td = 	$obj_tr->addTd($obj_tc_uid)           ;
            $obj_td = 	$obj_tr->addTd($obj_tc_role)          ;                 

    $obj_table_1->setClass('pkg_mvc table_champ_viewer__constructTableForMasseLoader');
    
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
	public  function constructTableForTri() 
	{ 
    //- objet de l'interface
    $stri_upper_table=strtoupper($this->obj_table_champ->getTcNomTable());
    $stri_upper_champ=strtoupper($this->obj_table_champ->getTcNomChamp());
    
    $stri_libelle=constante::constant('_LIB_'.$stri_upper_champ);
    $obj_img=new image('actionSort','images/tri_'.$this->obj_table_champ->getTcValeur().'.png');
       $obj_img->setClass($this->obj_table_champ->getTcValeur());
       $obj_img->setOnclick("mvc_std_viewer.prepareSort($(this))");
       $obj_img->setStyle('width:20px;border:solid black 1px;border-radius:4px;background-color:white;');
       $obj_img->setTitle(constante::constant('_TRI_'.strtoupper($this->obj_table_champ->getTcValeur())));
       
    //- placement des éléments de l'int-erface
    $obj_table=$this->constructTableForMasseLoader();
       $arra_tr=$obj_table->getTr();
       $arra_tr[0]->setStyle("display:none;"); 
       $obj_tr=$obj_table->addTr();
          $obj_tr->addTd($stri_libelle);
          $obj_tr->addTd($obj_img);
          
    return $obj_table;
  }
  
  		/*******************************************************************************
	* Pour obtenir la table représentant le modèle en ergonomie 02 représenté par
	* une fenêtre permettant l'ajout de tri  
	*                                                                                                               
	* Parametres : aucun
	* Retour : obj table                    
	*******************************************************************************/
	public  function constructTableForTriErgonomie02() 
	{ 
    //- objet de l'interface
    $stri_upper_table=strtoupper($this->obj_table_champ->getTcNomTable());
    $stri_upper_champ=strtoupper($this->obj_table_champ->getTcNomChamp());
    
    $stri_libelle=constante::constant('_LIB_'.$stri_upper_champ);
    $obj_img=new img('images/tri_'.$this->obj_table_champ->getTcValeur().'.png');
       $obj_img->setClass($this->obj_table_champ->getTcValeur());
       $obj_img->setOnclick("mvc_std_loader.displaySortInterface($(this));");
       $obj_img->setStyle('width:20px;border:solid black 1px;border-radius:4px;background-color:white;');
       $obj_img->setTitle(constante::constant('_TRI_'.strtoupper($this->obj_table_champ->getTcValeur())));
       
    //- placement des éléments de l'int-erface
    $obj_table=new table(); 
       $obj_tr=$obj_table->addTr();
          $obj_tr->addTd($stri_libelle);
          $obj_tr->addTd($obj_img);
          
    return $obj_table;
  }
 /*******************************************************************************
	* Pour obtenir la table représentant le modèle
	*                                                                                                               
	* Parametres : aucun
	* Retour : obj table                    
	*******************************************************************************/
	public  function constructTableForMain() 
	{ 		
    //- construction des représentation
    $obj_tc_id_table_champ = new text('table_champ__int_tc_id_table_champ',$this->obj_table_champ->getTcIdTableChamp());
    $obj_font_tc_id_table_champ = new font(_LIB_TC_ID_TABLE_CHAMP);
           
    $obj_tc_model = new text('table_champ__stri_tc_model',$this->obj_table_champ->getTcModel());
    $obj_font_tc_model = new font(_LIB_TC_MODEL);
           
    $obj_tc_nom_table = new text('table_champ__stri_tc_nom_table',$this->obj_table_champ->getTcNomTable());
    $obj_font_tc_nom_table = new font(_LIB_TC_NOM_TABLE);
           
    $obj_tc_nom_champ = new text('table_champ__stri_tc_nom_champ',$this->obj_table_champ->getTcNomChamp());
    $obj_font_tc_nom_champ = new font(_LIB_TC_NOM_CHAMP);
           
    $obj_tc_valeur = new text('table_champ__stri_tc_valeur',$this->obj_table_champ->getTcValeur());
    $obj_font_tc_valeur = new font(_LIB_TC_VALEUR);
           
    $obj_tc_uid = new text('table_champ__int_tc_uid',$this->obj_table_champ->getTcUid());
    $obj_font_tc_uid = new font(_LIB_TC_UID);
           
    $obj_tc_role = new text('table_champ__stri_tc_role',$this->obj_table_champ->getTcRole());
    $obj_font_tc_role = new font(_LIB_TC_ROLE);
                 
		 
		//- Positionnement des éléments de la table 1
		$obj_table_1 = new table();
        $obj_tr = $obj_table_1->addTr();
            $obj_td=$obj_tr->addTd($this->constructTableForHeader());
            $obj_td->setColspan(2);
        $obj_tr = $obj_table_1->addTr();
      			$obj_td = 	$obj_tr->addTd($this->obj_table_champ->getManager()->getHtmlMessage());
      				$obj_td->setColspan(2);
      				$obj_td->setAlign("center");  
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_tc_id_table_champ);
            $obj_td = 	$obj_tr->addTd($obj_tc_id_table_champ);
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_tc_model);
            $obj_td = 	$obj_tr->addTd($obj_tc_model);
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_tc_nom_table);
            $obj_td = 	$obj_tr->addTd($obj_tc_nom_table);
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_tc_nom_champ);
            $obj_td = 	$obj_tr->addTd($obj_tc_nom_champ);
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_tc_valeur);
            $obj_td = 	$obj_tr->addTd($obj_tc_valeur);
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_tc_uid);
            $obj_td = 	$obj_tr->addTd($obj_tc_uid);
        $obj_tr = $obj_table_1->addTr();
            $obj_td = 	$obj_tr->addTd($obj_font_tc_role);
            $obj_td = 	$obj_tr->addTd($obj_tc_role);               		
     
    return $obj_table_1;
	}
	


 
}

?>
