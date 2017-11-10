<?php
/*******************************************************************************
Create Date  : 2015-02-20
 ----------------------------------------------------------------------
 Class name  : header_scrollable
 Version     : 1.0
 Author      : ROBERT Romain
 Description : Entete fixed scrollable 
 
********************************************************************************/
class header_scrollable extends jsonisable{
   
//**** Attributs ****************************************************************
	
	//*** Configuration ***********************************************************
        protected $stri_top = '0px';
        protected $stri_z_index = '1';
        protected $stri_box_shadow = '0px 6px 20px 0 menutext';
        protected $stri_height= '';
        protected $stri_vertical_align= 'middle';
        protected $stri_border_bottom_left_radius = '9px';
        protected $stri_border_bottom_right_radius = '9px';


        //*** Données *****************************************************************
        protected $obj_tr                   ;  //Conteneur TR
        protected $stri_name                ;  //Nom du beandeau 
        protected $stri_style               ;  //Conteneur des configurations ci-dessus
        
        //*** Affichage *****************************************************************
	protected $obj_javascripter         ;  //Conteneur javascript
	 
        //*** Autre *********************************************************************
        protected static $int_nb_instance=0;        //Le nombre d'instance affichée
        protected static $arra_config;              //Les configurations d'un text imputation
	
//**** Methodes *****************************************************************

//**** Setter ****************************************************************
	public  function setTop($stri_top)                  {	$this->stri_top = $stri_top                     ;}
	public  function setZIndex($stri_z_index)           {	$this->stri_z_index = $stri_z_index             ;}
        public  function setBoxShadow($stri_box_shadow)     {	$this->stri_box_shadow = $stri_box_shadow       ;}
        public  function setHeight($stri_height)            {	$this->stri_height = $stri_height               ;}
        public  function setVerticalAlign($stri_valign)            {	$this->stri_vertical_align= $stri_valign               ;}
        public  function setBorderLeftRadius($stri_border_bottom_left_radius) {	$this->stri_border_bottom_left_radius = $stri_border_bottom_left_radius               ;}
        public  function setBorderRightRadius($stri_border_bottom_right_radius) {	$this->stri_border_bottom_right_radius = $stri_border_bottom_right_radius               ;}
        
        
//**** Getter ****************************************************************   
	public  function getName()              {	return $this->stri_name                            ;}
        
	public  function getTop()               {	return $this->stri_top                            ;}
	public  function getZIndex()            {	return $this->stri_z_index                        ;}
        public  function getBoxShadow()         {	return $this->stri_box_shadow                     ;}
        public  function getHeight()            {	return $this->stri_height                         ;}
        public  function getBorderLeftRadius()  {	return $this->stri_border_bottom_left_radius      ;}
        public  function getBorderRightRadius() {	return $this->stri_border_bottom_right_radius     ;}
        
        
        

//*** 01 Constructor **********************************************************
	
	/*******************************************************************************
	* Construction de l'objet
	* 
	* @param type $stri_name        //Attribut name 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function __construct($stri_name, $stri_class ) 
	{ 
            //Déduction du nom 
            $stri_name = ($stri_name=='')?'cloneHeader' : $stri_name;
            
            //Création ID
            $this->stri_name = $stri_name.'__'.self::$int_nb_instance;
            
            //Instanciation Tr
            $this->obj_tr = new Tr();
                $this->obj_tr->setClass($this->stri_name.' '.$stri_class.' header_scrollable');
                $this->obj_tr->setId($this->stri_name);

            //Incrément du nombre d'instance
            self::$int_nb_instance++;
            
	}
        
        
        /*******************************************************************************
	* Ajout d'une colonne 
	* 
	* Parametres : valeur à inséré 
	*******************************************************************************/
	public  function addColonne($stri_value, $stri_title='', $stri_width='', $stri_align='center') 
	{  
            $obj_td = $this->obj_tr->addTd($stri_value); 
                $obj_td->setAlign($stri_align);
                $obj_td->setTitle($stri_title);
                //$obj_td->setStyle("min-width: $stri_width;");
                $obj_td->setWidth($stri_width);
                
            return  $obj_td;
        }
            
        
        
        
        
        
//*** 03 Gestion des traitements **********************************************
	
	/*******************************************************************************
	* Génération du code javascript 
	* 
	* Parametres : aucun 
	* Retour : Code JS                         
	*******************************************************************************/
	public  function jQueryValue() 
	{ 
            
            //Conteneur javescript
            $this->obj_javascripter = new javascripter();
                
                            
            // Initialisation en utilisant un plugin jquery
            $stri_function = " 
                    $(function() 
                    {
                        $('.".$this->stri_name."').headerScrollable();//initialisation en utilisant un plugin jquery
                    }); ";
            
            
            
            $this->obj_javascripter->addFunction($stri_function);
                
            return $this->obj_javascripter->javascriptValue();
	}
	

//*** 04 Méthodes de redirection **********************************************
	
	/*******************************************************************************
	* Retourne l'input au format  HTML
	* 
	* Parametres : aucun 
	* Retour : Code HTML                          
	*******************************************************************************/
	public  function toJson() 
	{ 
            $stri_style = 'position: fixed; ';
            $stri_style .= ($this->stri_top=='') ? '' : 'top: '.$this->stri_top.'; ';
            $stri_style .= ($this->stri_height=='') ? '' : 'height: '.$this->stri_height.'; ';
            $stri_style .= ($this->stri_z_index=='') ? '' : 'z-index: '.$this->stri_z_index.'; ';
            $stri_style .= ($this->stri_box_shadow=='') ? '' : 'box-shadow: '.$this->stri_box_shadow.'; ';
            $stri_style .= ($this->stri_vertical_align=='') ? '' : 'vertical-align: '.$this->stri_vertical_align.'; ';
            $stri_style .= ($this->stri_border_bottom_left_radius=='') ? '' : 'border-bottom-left-radius: '.$this->stri_border_bottom_left_radius.'; ';
            $stri_style .= ($this->stri_border_bottom_right_radius=='') ? '' : 'border-bottom-right-radius: '.$this->stri_border_bottom_right_radius.'; ';
            
            
            
            $this->stri_style = $stri_style;
            
            $obj_div = new div($this->stri_name.'__style_json', $this->stri_style );
            $obj_div->setStyle('display: none;');
            
            
            return $obj_div->htmlValue();
            
            
        }
            
            /*******************************************************************************
	* Retourne l'input au format  HTML
	* 
	* Parametres : aucun 
	* Retour : Code HTML                          
	*******************************************************************************/
	public  function htmlValue() 
	{ 
            $stri_html = $this->toJson() . $this->jQueryValue() . $this->obj_tr->htmlValue();
            
            
            return $stri_html;
	}
        
       
        
 
}

?>
