<?php
/*******************************************************************************
Create Date  : 12/11/2014
 ----------------------------------------------------------------------
 Class name  : button_list
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description :  Permet de gérer une liste de boutons
 
********************************************************************************/
class button_list extends jsonisable{
   
//**** Attributs ****************************************************************
	
	//*** 01 Attributs  ***********************************************************
	  protected $arra_button_list_unit;          //La collection des différents boutton existant
    protected $int_indice_selected  ;          //L'indice du bouton d'action sélectionné 
    protected $stri_title;
    protected $stri_class_css;          
    protected $stri_class_css_table;          
//*** 01 Constructor **********************************************************
	
	/*******************************************************************************
	* Constructeur principal
	* 
	* Parametres : 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function __construct($stri_title) 
	{ 
	   $this->arra_button_list_unit=array();
     $this->stri_title=$stri_title;
     $this->int_indice_selected=0;
	}
	

//**** Methodes *****************************************************************

//**** Setter ****************************************************************
	  public function setTitle($value){$this->stri_title=$value;}
  
//**** Getter ****************************************************************   
    public function getTitle(){return $this->stri_title;}
    public function getIemeButton($int_i){return $this->arra_button_list_unit[$int_i];}

//*** 02 Autres méthodes ******************************************************
	
	/*******************************************************************************
	* Permet d'ajouter un bouton à la liste
	* 
	* Parametres : $stri_name      : nom du bouton
	*              $stri_value     : valeur du bouton
	*              $stri_title     : titre
	*              $stri_src       : src dans le cas d'un bouton image
	* Retour :  obj button_list_unit : le bouton nouvellement ajouté                              
	*******************************************************************************/
	public  function addButton($stri_name,$stri_value,$stri_title,$stri_src) 
	{ 
	   $obj_button=new button_list_unit($stri_name,$stri_value,$stri_title,$stri_src);
     
     $int_indice=count($this->arra_button_list_unit);
     
     $this->arra_button_list_unit[$int_indice]= $obj_button;
     
     //- passage des infos servant à faire le lien pour les unité dual
     $obj_button->setMyIndice($int_indice);
     $obj_button->setButtonList($this);
     
     return $obj_button;
	}
  

  /*******************************************************************************
	* Permet de construire la liste de toutes les actions possibles
	* Retour :  obj table  : la table html                           
	*******************************************************************************/
  public function constructTableForListButton()
  {
     $obj_table=new table();
     $obj_table->setClass('button_list__constructTableForListButton');
     foreach($this->arra_button_list_unit as $obj_button_list_unit)
     {    
       $obj_table->insertTr($obj_button_list_unit->toTr());
     }
     $obj_table->setStyle('display:none;');
   
     return $obj_table;
  }
  
   /*******************************************************************************
	* Permet de construire la représentation html générale
	* Retour :  string : le code html à afficher                    
	*******************************************************************************/                            
  public function htmlValue()
  {
     $stri_dirname=dirname(__FILE__);
     $stri_root=$_SERVER['DOCUMENT_ROOT'];
     $stri_path=str_replace($stri_root,'',$stri_dirname);
     
      
     $obj_css=new css();
     $obj_css->addFile($stri_path.'/button_list.css'); 
     
     //- icone de flèche pour liste déroulante d'action
     $obj_img=new img($stri_path.'/images/button_list_down_10x10_grey.png');
      $obj_img->setClass('button_list_arrow_icon infobulle');
      $obj_img->setTitle($this->stri_title);    
     
     //- premier boutton d'action
     $obj_button_unit=$this->getIemeButton($this->int_indice_selected);
     
     //- config du click sur la table
     $stri_onclick="button_list.getInstance($(this)).displayOrHideList();";
     

     $obj_table=new table();
       $obj_table->setStyle('cursor: pointer;');
       $obj_table->setClass('button_list_table '.$this->stri_class_css_table);
       $obj_tr=$obj_table->addTr();
        $obj_td=$obj_tr->addTd($obj_button_unit->htmlValueVariant01());
           $obj_td->setClass('button_list_ico');
         $obj_td=$obj_tr->addTd($obj_img);
           $obj_td->setClass('button_list_arrow');
        $obj_table->setOnclick($stri_onclick);
        //$obj_table->setBorder(1);
    
     //- div pour le json
     $obj_div_json=new div();
        $obj_div_json->setClass('button_list__toJson');
        $obj_div_json->setContain($this->toJson());
        $obj_div_json->setStyle('display:none;');
       
     $obj_div=new div();
        $obj_div->setContain($obj_table->htmlValue().$this->constructTableForListButton()->htmlValue().$obj_div_json->htmlValue());
        $obj_div->setClass('button_list '.$this->stri_class_css);
        //$obj_div->setStyle('border:solid black 2px;');
        $obj_div->setStyle('position: relative;');
     
       
     return $obj_css->cssValue().$obj_div->htmlValue();
  }
}

?>
