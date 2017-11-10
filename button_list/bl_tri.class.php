<?php
/*******************************************************************************
Create Date  : 14/11/2014
 ----------------------------------------------------------------------
 Class name  : bl_tri
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Liste de bouton permettant de lancer le tri
 
********************************************************************************/
class bl_tri extends button_list {
   
//**** Attributs ****************************************************************
	
	//*** 01 Attributs  ***********************************************************
	 protected $stri_nom_champ;
        protected $stri_fonction_tri;
        protected $stri_width_img;
//**** Methodes *****************************************************************

//**** Setter ****************************************************************
        public function setWidthImg($stri_value){    $this->stri_width_img = $stri_value; }
        public function setActif($bool_value)   {
            //Initialisation
            $this->stri_class_css_table = '';
            if ($bool_value)
            { $this->stri_class_css_table = 'button_list_table_actif'; }
        }
            
  
//**** Getter ****************************************************************   
        public function getNomChamp(){return $this->stri_nom_champ; }
//*** 01 Constructor **********************************************************
	
	/*******************************************************************************
	* Constructeur principal
	* 
	* Parametres : 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function __construct($stri_nom_champ, $stri_fonction_tri, $stri_file_include, $stri_width_img='32x32') 
	{ 
	  $this->stri_nom_champ=$stri_nom_champ;
            $this->stri_fonction_tri = $stri_fonction_tri;
            $this->stri_width_img = $stri_width_img;


            $stri_dirname = dirname(__FILE__);
            $stri_root = $_SERVER['DOCUMENT_ROOT'];
            $stri_path = str_replace($stri_root, '', $stri_dirname);

            parent::__construct(_VOIR_ACTION_DISPONIBLE);
            //parent::__construct();
            $this->stri_class_css = "bl_tri";


            $obj_button1 = $this->addButton('bt_tri_croissant', 'asc', _ACTION_TRI_CROISSANT, $stri_path . "/images/increase_" . $this->stri_width_img . ".png");
            $obj_button1->setOnclick("$stri_fonction_tri('asc','" . $this->stri_nom_champ . "', '" . $stri_file_include . "' );");
            $obj_button2 = $this->addButton('bt_tri_decroissant', 'desc', _ACTION_TRI_DECROISSANT, $stri_path . "/images/decrease_" . $this->stri_width_img . ".png");
            $obj_button2->setOnclick("$stri_fonction_tri('desc','" . $this->stri_nom_champ . "', '" . $stri_file_include . "');");
            $obj_button2->setDual($obj_button1);
            
    }

//*** 02 Autres méthodes ******************************************************

}

?>
