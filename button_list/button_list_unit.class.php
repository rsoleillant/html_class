<?php
/*******************************************************************************
Create Date  : 12/11/2014
 ----------------------------------------------------------------------
 Class name  : button_list_unit
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Unité de la collection button_list. Permet de représenter un bouton particulier 
 
********************************************************************************/
class button_list_unit extends jsonisable{
   
//**** Attributs ****************************************************************
	
	//*** 01 Attributs  ***********************************************************
    protected $stri_name;
    protected $stri_value;
    protected $stri_title;
    protected $stri_src;
    protected $bool_change_icone;
   
  //*** 02 gestion du dual  *****************************************************
    protected $obj_button_list;      //La collection dans laquelle l'unité est incluse
    protected $int_my_indice;        //Indice de position de l'instance dans la collection
    protected $int_dual_indice;      //Indide de la position de l'instance dual dans la collection
    
    protected $stri_onclick;  
  
//*** 01 Constructor **********************************************************
	
	/*******************************************************************************
	* Constructeur principal
	* 
	* Parametres : 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function __construct($stri_name,$stri_value,$stri_title,$stri_src) 
	{ 
	   $this->stri_name=$stri_name;
     $this->stri_value=$stri_value;
     $this->stri_title=$stri_title;
     $this->stri_src=$stri_src;
	}
	
//**** Methodes *****************************************************************

//**** Setter ****************************************************************
	    public function setName($value){$this->stri_name=$value;}
      public function setValue($value){$this->stri_value=$value;}
      public function setTitle($value){$this->stri_title=$value;}
      public function setSrc($value){$this->stri_src=$value;}
      public function setChangeIcone($value){$this->bool_change_icone=$value;}
      public function setOnclick($value){$this->stri_onclick=$value;}
      public function setButtonList($value){$this->obj_button_list=$value;}
      public function setMyIndice($value){$this->int_my_indice=$value;}
      public function setDualIndice($value){$this->int_dual_indice=$value;}
      
     //- déclaration de bouton dual permettant d'inverser leur fonctionnalité lors du clic sur l'un ou l'autre
      public function setDual(button_list_unit $obj_button_dual)
      {
        $this->int_dual_indice=$obj_button_dual->getMyIndice();
        $obj_button_dual->setDualIndice($this->int_my_indice);
      }
        
  
//**** Getter ****************************************************************   
      public function getName(){return $this->stri_name;}
      public function getValue(){return $this->stri_value;}
      public function getTitle(){return $this->stri_title;}
      public function getSrc(){return $this->stri_src;}
      public function getChangeIcone(){return $this->bool_change_icone;}
      public function getOnclick(){return $this->stri_onclick;}
      public function getButtonList(){return $this->obj_button_list;}
      public function getMyIndice(){return $this->int_my_indice;}
      public function getDualIndice(){return $this->int_dual_indice;}



//*** 02 Autres méthodes ******************************************************
	
	/*******************************************************************************
	* Permet la représentation miniature 
	* 
	* Parametres : 
	* Retour :                                
	*******************************************************************************/
	public  function htmlValueVariant01() 
	{ 
	   $mixed_rep=new font($this->stri_title);
     if($this->stri_src!="")//si un icône de représentation existe
     {
       $mixed_rep=new img($this->stri_src);
       $mixed_rep->setTitle($this->stri_title);        
       $mixed_rep->setClass('infobulle');        
     }
     
    //$stri_default_click=";event.stopPropagation();";                               //arrêt de propagation de l'événement
    $stri_default_click="event.stopPropagation();";                               //arrêt de propagation de l'événement
    $stri_default_click.='var obj_button_list= button_list.getInstance($(this));';  //récupération de l'instance de la collection
    $stri_default_click.="obj_button_list.displayOrHideList('hide');";             //masquage des options
    $stri_default_click.='obj_button_list.changeAction(obj_button_list.getSelectedButton());';    //changement de l'action à effectuer sur le clic de l'icône principal
   
     
    $mixed_rep->setClass('button_list_unit infobulle');
    //$mixed_rep->setOnclick($this->stri_onclick.$stri_default_click);
    $mixed_rep->setOnclick($stri_default_click.$this->stri_onclick);
    
    return $mixed_rep->htmlValue();
	}
  
  	/*******************************************************************************
	* Permet la représentation à intégrer dans l'ensemble des actions possible 
	* 
	* Parametres : 
	* Retour :                                
	*******************************************************************************/
	public  function toTr() 
	{ 
    //- gestion des actions sur click a effectuer
   // $stri_default_click='button_list.getInstance($(this)).displayOrHideList();';//masquage de la liste
    $stri_default_click='var obj_button_list= button_list.getInstance($(this));';//récupération de l'instance de la collection
    $stri_default_click.='obj_button_list.displayOrHideList();';//masquage de la liste
    $stri_default_click.='obj_button_list.changeAction($(this)[0].obj_model);';//changement de l'action à effectuer sur le clic de l'icône principal
   
    
    //- représentation sous forme de tr
     $obj_tr=new tr();
        $obj_tr->setClass('button_list_unit__toTr');
        $obj_tr->addTd($this->stri_title);
        $obj_tr->setOnclick($stri_default_click.$this->stri_onclick);
    return $obj_tr;    
  }
  
  //fonction de jsonisation pour éviter les référence croisées
  public function toJson()
  {
    $this->obj_button_list="";
    return parent::toJson();
  }
}

?>
