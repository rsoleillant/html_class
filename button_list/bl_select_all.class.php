<?php
/*******************************************************************************
Create Date  : 13/11/2014
 ----------------------------------------------------------------------
 Class name  : bl_select_all
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Liste de bouton permettant de sélectionner ou déselectionner un ensemble de checkbox
 
********************************************************************************/
class bl_select_all extends button_list {
   
//**** Attributs ****************************************************************
	
	//*** 01 Attributs  ***********************************************************
	 protected $stri_nom_cb    ;   //Le nom des checkbox à contrôler

//**** Methodes *****************************************************************

//**** Setter ****************************************************************
	
  
//**** Getter ****************************************************************   

//*** 01 Constructor **********************************************************
	
	/*******************************************************************************
	* Constructeur principal
	* 
	* Parametres : 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function __construct($stri_nom_cb) 
	{ 
	  $this->stri_nom_cb=$stri_nom_cb; 
   
   
    $stri_dirname=dirname(__FILE__);
    $stri_root=$_SERVER['DOCUMENT_ROOT'];
    $stri_path=str_replace($stri_root,'',$stri_dirname); 
    
    parent::__construct(_VOIR_ACTION_DISPONIBLE);
    $this->stri_class_css="bl_select_all";
        
  
     $obj_button1=$this->addButton('bt_check_all','',_ACTION_TOUT_SELECTIONNER,$stri_path."/images/checkbox_checked.png");
         $obj_button1->setOnclick("bl_select_all.selectAll('".$stri_nom_cb."');");
      $obj_button2=$this->addButton('bt_uncheck_all','',_ACTION_TOUT_DESELECTIONNER,$stri_path."/images/checkbox_empty.png");
         $obj_button2->setOnclick("bl_select_all.unselectAll('".$stri_nom_cb."');");
    $obj_button2->setDual($obj_button1); 
	}
	

//*** 02 Autres méthodes ******************************************************

}

?>
