<?php
/*******************************************************************************
Create Date : 24/04/2013
 ----------------------------------------------------------------------
 Class name :  mvc_std_viewer 
 Version : 1.0
 Author : Rémy Soleillant
 Description : Viewer générique pour porter les méthodes communes à l'ensemble des viewer
 
********************************************************************************/
abstract class mvc_std_viewer{
   
 //**** attribute ************************************************************
  
  //*** 02 Attributs spécifique viewer ******************************************
	protected $stri_main_method           ;  //La méthode d'affichage à utiliser
	protected $stri_main_table            ;  //La table principale contenant l'interface
	protected $obj_javascripter           ;  //Le conteneur de code javascript
  protected $obj_css                    ;  //Le conteneur de code css 
  protected $bool_disabled              ;  //Si on désactive l'ensemble des champs de saisies 
  
 //**** constructor ***********************************************************
  /*******************************************************************************
	* 
	* Parametres :  
	* Retour :                 
	*******************************************************************************/
	public  function __construct() 
	{ 		
		    $this->stri_main_method="constructTableForMain";        //méthode de représentation par défaut
		    $this->obj_main_table='';                               //initialisation de la table principale
		    $this->obj_javascripter=new javascripter();             //initialisation du conteneur à javascript
        $this->obj_css=new css();                               //initialisation du conteneur de css
        $this->bool_disabled=false;                             //par défaut les champs de saisie sont actifs    
	}
 
  //**** setter ****************************************************************
  public  function setMainMethod($stri_main_method)   {	$this->stri_main_method = $stri_main_method ;}
	public  function setMainTable($stri_main_table)     {	$this->stri_main_table = $stri_main_table   ;}
	public  function setJavascripter($obj_javascripter) {	$this->obj_javascripter = $obj_javascripter ;}
  public  function setCss($obj_css)                   { $this->obj_css=$obj_css                     ;}
  public  function setDisabled($value)                { $this->bool_disabled=$value                 ;}

  
  //**** getter ****************************************************************
  public  function getMainMethod()           {	return $this->stri_main_method          ;}
	public  function getMainTable()            {	return $this->stri_main_table           ;}
	public  function getJavascripter()         {	return $this->obj_javascripter          ;}
  public  function getDisabled()             {  return $this->bool_disabled             ;}
  public  function getCss()                  {  return $this->obj_css                   ;}

 
  


  public abstract function getModel();//Doit retourner le modèle représenté par le viewer
   
   //**** public method *********************************************************
   public abstract function constructTableForMain();//pour construire la table principale. Doit retourner un objet table


  /**
   * Permet d'obtenir la représentation sous forme de tr pour être incluse dans un loader
   * @param  : aucun
   * @return : obj tr   
   **/        
  public function toTrForLoader()
  {   
     $obj_tr=new tr();    
     $obj_tr->setClass('std_viewer__toTrForLoader '.get_class($obj_model));
     return $obj_tr;
  }
  
 /*******************************************************************************
  * Pour construire la liste des boutons d'interaction
  * 
  * @param : aucun
  * @return : tableau association nom_bouton=>obj_bouton    
  ******************************************************************************/
  public function constructArrayButton()
  {
    //- récupération de la classe du modèle représenté
    $stri_model_name=get_class($this->getModel());
  
    //- bouton de sauvegarde
    $obj_image_actionSave=new image("actionSave","images/PNG/ok-048x048.png");
			$obj_image_actionSave->setClass("action infobulle");
			$obj_image_actionSave->setTitle(_ACTION_SAVE);    
      $obj_image_actionSave->setStyle("width:45px;margin:10px;");
   
    //- bouton de retour
    $obj_image_back=new image("actionBack","images/PNG/arrow-left-048x048.png");
			$obj_image_back->setClass("action infobulle");
			$obj_image_back->setTitle(_ACTION_BACK);
      $obj_image_back->setOnclick("mvc_std_viewer.backFor('$stri_model_name');");
      $obj_image_back->setStyle("cursor:pointer;width:45px;margin:10px;");
    
    //- bouton d'ajout
    $obj_image_actionNew=new img("images/PNG/add-048x048.png");
      $obj_image_actionNew->setClass("action infobulle");
      $obj_image_actionNew->setTitle(_ACTION_NEW);
      $obj_image_actionNew->setStyle("width:45px;margin:10px;");
      $obj_image_actionNew->setOnclick("mvc_std_viewer.prepareAdd('$stri_model_name');");
    
    $arra_bouton=array(); 
    $arra_bouton['actionBack']=$obj_image_back;    
    $arra_bouton['actionNew']=$obj_image_actionNew;
    $arra_bouton['actionSave']=$obj_image_actionSave;
    
    return  $arra_bouton;
  }                                                                                            

  /*******************************************************************************
  * Pour obtenir le code HTML représentant la partie header
  *                                                                                            
  * Parametres : string : l'icône à gauche
  *              string : le titre
  *                                        
  * Retour : string : le code HTML                         
  *******************************************************************************/
  public function constructTableForHeader($stri_icone,$stri_titre="")
  {
  
    
    //- construction des objets de l'interface 
    //-- icone  
    	$obj_img_1           = new img("$stri_icone");
		  $obj_img_1->setWidth("60px");

    //-- libellé
      $stri_class=get_class($this->getModel());
      $stri_titre=($stri_titre=="")?constante::constant('_TITLE_'.strtoupper($stri_class)):$stri_titre;//gestion du titre par défaut
      $obj_font_1          = new font($stri_titre);
			$obj_font_1->setSize("5");
			$obj_font_1->setStyle("font-weight: bold;");
	
    //- positionnement des différents objets  de la table 2
		$obj_table_2 = new table();
				$obj_table_2->setWidth("100%");
				$obj_table_2->setClass("mvc_std_viewer titre1 entete ".get_class($this->getModel()));
				$obj_tr = $obj_table_2->addTr();
				if($stri_icone!="")
        {
          $obj_td = 	$obj_tr->addTd($obj_img_1);
          $obj_td->setId("td_entete_image");
            $obj_td->setStyle("width:90px");
        }	
          
         
					$obj_td = 	$obj_tr->addTd($obj_font_1);
          $obj_td->setId("td_entete_libelle");
					$obj_td = 	$obj_tr->addTd($this->constructArrayButton());
          $obj_td->setId("td_entete_bouton");
						$obj_td->setAlign("right"); 
    $obj_table_2->setWidth('100%');
    
    return $obj_table_2; 
  }

  //**** Représentation complette **********************************************
 
 /******************************************************************************
	* Pour obtenir le code HTML représentant le modèle
	*                                                                                                               
	* Parametres : $stri_mode_retour : les retour possible de la méthode [html, form, table]
	*                                                       défaut : html  
	* Retour : string : le code HTML                         
	******************************************************************************/
	public  function htmlValue($stri_mode_retour="html") 
	{ 
            
		
    if($this->obj_main_table!="")//si la table principale à déjà été définie
		{return $this->obj_main_table->htmlValue();} 
		
         
    //- récupération de la méthode principal de représentation
    $stri_metod=$this->stri_main_method;
	     
		//- construction de l'interface
		$this->obj_main_table=$this->$stri_metod();
    
    //- gestion de désactivation
    if($this->bool_disabled)
    {
      $this->obj_main_table->applyMethode('setDisabled',true);
    }
      
    //- gestion de classe css
    $stri_css=$this->obj_main_table->getClass();
    $stri_css.=($stri_css=="")?"":" ";
    $stri_css.=get_class($this);
    $this->obj_main_table->setClass($stri_css);
	 
    //- gestion du mode de retour 
    if($stri_mode_retour==="table")
    {return $this->obj_main_table;}
    
    //- gestion de l'attachement des css
    $stri_css=$this->obj_css->cssValue();
    
		//- encapsulation dans un formulaire
		 if($stri_mode_retour==="form")
      {
       $obj_form=new form();
          $obj_form->setValue($stri_css.$this->obj_main_table->htmlValue().$this->obj_javascripter->javascriptValue());
       return  $obj_form->htmlValue();
      }
		  
      //- par défaut on retourne du html  
		  return $stri_css.$this->obj_main_table->htmlValue().$this->obj_javascripter->javascriptValue();  
	}
}

?>
