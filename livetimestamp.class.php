<?php
/*******************************************************************************
Create Date  : 2016-09-20
 ----------------------------------------------------------------------
 Class name  : liveTimeStamp
 Version     : 1.0
 Author      : ROBERT Romain
 Description : Affichage et calcul d'une date de manière dynamique
 
********************************************************************************/
class livetimestamp {
   
//**** Attributs ****************************************************************
	
	//*** Configuration ***********************************************************
        protected $stri_class = 'livetimestamp';
        protected $bool_bold=false;
        protected $bool_underline=false;
        protected $bool_italique=false;
        protected $stri_table_style;

        //*** Données *****************************************************************
        protected $obj_table                ;  //Conteneur Table
        protected $obj_font                 ;  //Conteneur Font
        protected $stri_date                ;  //La date représenter
        protected $stri_timestamp           ;  //Le timestamp 
        
        //*** Affichage *****************************************************************
	protected $obj_javascripter         ;  //Conteneur javascript
	 
        //*** Autre *********************************************************************
        protected static $int_nb_instance=0;        //Le nombre d'instance affichée
	
//**** Methodes *****************************************************************

//**** Setter ****************************************************************
	public  function setDate($stri_date)                  {	$this->stri_date = $stri_date           ;}
        public  function setBold($bool_bold)                  {	$this->bool_bold = $bool_bold           ;}
        public  function setUnderline($bool_underline)        {	$this->bool_underline= $bool_underline  ;}
        public  function setItalique($bool_italique)          {	$this->bool_italique = $bool_italique   ;}
        public  function setTableStyle($stri_style)           {	$this->stri_table_style = $stri_style   ;}
        
        
//**** Getter ****************************************************************   
	
        

//*** 01 Constructor **********************************************************
	
	/*******************************************************************************
	* Construction de l'objet
	* 
	* @param type $stri_date        //La date représenté
	* Retour : Aucun                         
	*******************************************************************************/
	public  function __construct($stri_date) 
	{ 
            
            //- Recherche du troisième caractère pour déterminer s'il s'agit d'une date française ou bdd
            $stri_third=substr($stri_date,2,1);
            
            if($stri_third=="/" || $stri_third=="-")
            {
                //- Date au format français
                //- Correction date format bizarre (Mix anglais/francais)
                $stri_date = str_replace('-', '/', $stri_date);
                $stri_method = 'setValueFromFrenchDate';
            }
            else
            {
                //- Format anglais
                $stri_method = 'setValue';
            }
            
            //- Instanciation de la date
            $obj_date = new date();
                $obj_date->$stri_method($stri_date);
            
            // La date représenté
            $this->stri_date = $obj_date->date('Y-m-d H:i:s');
            
            //- Son timestamp
            $this->stri_timestamp = strtotime($stri_date);
            
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
            
            
            self::$int_nb_instance ++ ;
            
            if (self::$int_nb_instance>1)
            { return; }
            
            //Conteneur javescript
            $this->obj_javascripter = new javascripter();
                
            
            // Initialisation en utilisant un plugin jquery
            $stri_function = " 
                    $(function() 
                    {
                        $('.".$this->stri_class."').liveTimeStamp();//initialisation en utilisant un plugin jquery
                    }); ";
            
            $this->obj_javascripter->addFunction($stri_function);
                
            return $this->obj_javascripter->javascriptValue();
	}
	
        
        /*******************************************************************************
	* Retourne la date au bon format en fonction de la langue configuré
             * 
	* Retour : Code HTML & JS
	*******************************************************************************/
        public  function getDate()                            
        {
            
            //- Déduction de la langue
            $obj_date = new date($this->stri_date);
            switch (pnusergetLang())
            {
                case "fra":
                    $stri_date = $obj_date->date('d/m/Y H:i:s');
                    break;
            
                    default :
                    $stri_date = $obj_date->date('Y-m-d H:i:s');
                    break;
            }
            return $stri_date ;
        }
            

//*** 04 Méthodes de redirection **********************************************
	
            
            /*******************************************************************************
	* Retourne l'input au format  HTML
	* 
	* Parametres : $bool_diff_date : true   => Le : 20/09/2016 09:55:55
             *         $bool_diff_date : false  => Il y a : 1 heures 45 minutes
             * 
	* Retour : Code HTML & JS
	*******************************************************************************/
	public  function htmlValue($bool_diff_date = false) 
	{ 
            
            $stri_date = $this->getDate();
            
            
            //- Déduction des valeurs à utiliser
            $stri_diff_date = date::getDiffDate($this->stri_date);
            //$stri_date = $this->stri_date;
            
            $stri_font_value = ($bool_diff_date) ? $stri_diff_date : $stri_date;
            $stri_title = ($bool_diff_date) ? __LIB_LE. ' : '.$stri_date : __LIB_IL_Y_A . ' : '.$stri_diff_date;
            $stri_flag_class = ($bool_diff_date) ? 'livetimestamp__diff_date' : 'livetimestamp__full_date';
            
            //- Le font 
            $this->obj_font = new font($stri_font_value,  $this->bool_bold, $this->bool_italique, $this->bool_underline);
            
            //Instanciation Table
            $obj_table = new table();
            $obj_table->setStyle('margin: -3px; cursor: default; '.$this->stri_table_style);
                $obj_tr=$obj_table->addTr();
                    $obj_td = $obj_tr->addTd($this->obj_font);
                       $obj_td->setClass($this->stri_class.' infobulle '.$stri_flag_class) ;
                       $obj_td->setTitle($stri_title) ;
                       $obj_td->setData( 'livetimestamp', $this->stri_timestamp ) ;
               
            $this->obj_table = $obj_table;
            
            
            
            return $this->jQueryValue(). $this->obj_table->htmlValue();
            
        }
        
       
        
 
}

?>
