<?php
/*******************************************************************************
Create Date : 17/07/2017
 ----------------------------------------------------------------------
 Class name : ultra_select.class.php
 Version : 1.0
 Author : ROBERT Romain
 Description : Select polymorphe permettant de retrouver facielement une option dans une longue liste 
 *              - Représente ses options sous la forme de case à cocher     => Multiple : True
 *              - Représente ses options sous la forme de radio bouton      => Multiple : False
 * 
 *              La classe s'appuie également sur les fichier ultra_select.class.[js|css]
********************************************************************************/

class ultra_select extends select  
{
  //**** attribute *************************************************************
   public static $int_nb_instance=0;        //Le nombre d'instance déjà créé
   public static $arra_int_idx_post;		//Le nombre de select ayant le même nom
   
   public $bool_option_addable; 

   //**** constructor ***********************************************************
   

  /*************************************************************
   *    Constructeur de la classe ultra_select
   * Améliore la multi sélection dans le cas ou l'attribut bool_multiple est à true
   * 
   * parametres : 
   * retour : objet de la classe ultra_select   
   *                        
   **************************************************************/         
  function __construct($stri_name,$bool_multiple=true, $bool_option_addable=false) 
  { 
      //- Constructeur parent
      parent::__construct($stri_name);
      
      //- Select multiple
      $this->setMultiple($bool_multiple);
      
      //- Option ajoutable ?
      $this->bool_option_addable = $bool_option_addable;
      
      //- Initialisation index données post
      if (!isset(self::$arra_int_idx_post[$this->stri_name]))
      { self::$arra_int_idx_post[$this->stri_name]=0; }
      
      
  }  
 
  //**** setter ****************************************************************
  public function setOptionAdd($bool_value)
  {
      $this->bool_option_addable = $bool_value;
  }
  
  //- Setter largeur ultra_select
    public function setWidth($stri_value)
    {

        foreach (['max-width', 'min-width', 'width'] as $stri_attr)
        {  $this->stri_style .= $stri_attr.': '.$stri_value.'; '; }
    }


    //**** getter ****************************************************************

  
  //**** private method *********************************************************

  
  
  /*************************************************************
  * Surcharge la méthode afin de forcer la sélection de l'option à false
   
   * @param type $value
   * @param type $label
   * @param type $stri_data_image
   * 
   * @return NULL
   *                        
   **************************************************************/          
  public function addGroup($stri_libelle)
  {
      //- Appel constructeur parent
      //parent::addGroup($lib);
      
      //- Workaround pour satisfaire select.class.php
      $this->addOption('', '');
      
      //- Nombre d'élement
      $int_nb_option = count($this->arra_option);
      $int_nb_group = count($this->arra_group);
      
      //- Ajout de l'information dans array
      $this->arra_group[]['libelle'] = $stri_libelle;
      
      //- Si présence d'un groupe
      if (isset($this->arra_group[$int_nb_group-1]))
      {
          //- Ajout les informations sur le nombre d'options auparavant ajoutées
          $this->arra_group[$int_nb_group-1]['option'] = $int_nb_option-1;
          
      }
      
  }
  
  
  
  /*************************************************************
  * Surcharge la méthode afin de forcer la sélection de l'option à false
   
   * @param type $stri_value
   * @param type $stri_label
   * @param type $stri_data_image
   * 
   * @return NULL
   *                        
   **************************************************************/          
  public function addOption($stri_value, $stri_label, $stri_data_image = "")
  {
      //- Appel constructeur parent
      $obj_option = parent::addOption($stri_value, $stri_label, $stri_data_image);
      
      //- Traitement spéciale sur l'option retournée
      $obj_option->setSelected(false);
      
  }
  
  /*************************************************************
   * 
   * Gestion appel selectMultipleOption() sur select non multiple 
   * @param type $arra_value
   * 
   **************************************************************/
  public function selectMultipleOption($arra_value)
  {
        //- Méthode par défault
      $stri_method = 'selectMultipleOption';
      
      //- Test si les données sont un Array
      if (!is_array($arra_value))
      { $stri_method = 'selectOption'; }
      
      //- Appel parent
      parent::$stri_method($arra_value);
  }
  
    /*************************************************************
   * 
   * Gestion appel selectOption() sur select multiple 
   * @param type $stri_value
   * 
   **************************************************************/
  public function selectOption($stri_value)
  {
        //- Méthode par défault
      $stri_method = 'selectOption';
      
      //- Test si les données sont un Array
      if (is_array($stri_value))
      { $stri_method = 'selectMultipleOption'; }
      
      //- Appel parent
      parent::$stri_method($stri_value);
  }


  /**
   * Construction du nom de l'input 
   * Gestion des collection... 
   * 
   * @param type $bool_option_add
   * @return string
   */
  private function constructName($bool_option_add)
  {
      
        //- Détection collection dans le nom.
        $bool_name_multiple = preg_match('/\[\]/', $this->stri_name);
      
        //- Suppréssion nom post collection
        $stri_name = (preg_replace('/\[\]/', '', $this->stri_name));
        
        //- Traitement spécial pour l'option d'ajout
        if ($bool_option_add)
        { $stri_name = $stri_name.'_add'; }
        
        //- Nom par défaut
        $stri_name_for_post = $stri_name;
        
        $stri_idx = self::$arra_int_idx_post[$this->stri_name];
        //$stri_idx='';
        
        //- Déduction nom final 
        if ($this->bool_multiple)
        {
            //- Multiple ?
            $stri_name_for_post = $stri_name.'['.$stri_idx.'][]';
            
            //- Multiple mais pas de crochet 
            if (!$bool_name_multiple)
            { $stri_name_for_post = $stri_name.'[]'; }
            
        }
        else if ($bool_name_multiple)
        {
            //- Input prévu pour masse loader ? 
            //$stri_name_for_post = $stri_name.'['.$stri_idx.']';
            $stri_name_for_post = $stri_name.'[]';
        }
        
        return $stri_name_for_post;
        
      
  }




  /*************************************************************
   * Méthode de construction d'une option 
   * 
   * @param type $int_i             //- Utilisée dans l'identifiant
   * @param type $obj_option        //- Un obj option stocké dans la collection
   * @param type $bool_option_add   //- Booléan pour traiment spécial option d'ajout
   * 
   * @return String                 //- Un input et un label à intégrer dans un LI
   *                        
   **************************************************************/          
  private function constructOneLi($int_i, $obj_option, $bool_option_add=false)
  {
        //- construction identifiant
        $stri_id = 'ultra_select_'.self::$int_nb_instance.'__'.$int_i;
        

        //- Nom des données POST
        $stri_name_for_post = $this->constructName($bool_option_add);

        
        //- Bascule déterminant le type d'input à instancier
        //$stri_input_type = ($this->bool_multiple) ? 'checkbox' : 'radio';
        $stri_input_type = ($this->bool_multiple) ? 'checkbox' : 'checkbox';

        //- Gestion multiple ou non 
        //- Instancie la checkbox | bouton radio
        $obj_mixed_input = new $stri_input_type($stri_name_for_post, $obj_option->getValue());

        //- Nom de class CSS
        $stri_class_input = 'ultra_select__option_checkbox';
        $stri_class_input .= ($bool_option_add) ? ' ultra_select__option_checkbox_add' : '';
        $stri_class_input .= (!$this->bool_multiple) ? ' ultra_select__option_radio ' : '';
		
        //- JS Select simple
        $stri_onchange = (!$this->bool_multiple) ? "setTimeout(function(){ ultra_select.collapseAll('', '');},100); ": '';
        
        //- Personnalisation de l'input
        $obj_mixed_input->setId($stri_id);
            $obj_mixed_input->setClass($stri_class_input.' '.$this->stri_class.' '.$obj_option->getClass());
            
            //- Evenement ultra_select
            $obj_mixed_input->setOnchange($this->stri_onchange.'; ultra_select.updateSelected($(this)); '.$stri_onchange  );
            
            //- Evenement select et option
            $obj_mixed_input->setOnclick($this->stri_onclick.'; '.$obj_option->getOnClick());
            $obj_mixed_input->setOnMouseOver($this->stri_onmouseover.'; '.$obj_option->getOnMouseover());
            $obj_mixed_input->setOnMouseOut($this->stri_onmouseout);
            $obj_mixed_input->setOnBlur($this->stri_onblur);
            $obj_mixed_input->setOnFocus($this->stri_onfocus);
            
            
            $obj_mixed_input->setOnInvalid('ultra_select.onInvalid(event, $(this) );');
            //$obj_mixed_input->setRequired(($this->bool_required & $obj_option->getRequired()) ? true : false);
            $obj_mixed_input->setRequired($this->bool_required);
            $obj_mixed_input->setDisabled(($this->bool_disabled | $obj_option->getDisabled()) ? true : false);

        

        //- Option sélectionnée ? 
        if ($obj_option->getSelected())
        {  $obj_mixed_input->setChecked(true);  }


        //- Classe CSS pour le label
        $stri_class_label = 'ultra_select__option_label';
        $stri_class_label .= ($bool_option_add) ? ' ultra_select__option_label_add' : '';

        //- Construction du label pour ergonomie
        //$stri_label = '<label class="'.$stri_class_label.'" for="'.$stri_id.'">'.$obj_option->getLabel().'</label>';
        $stri_label = '<label class="'.$stri_class_label.'" onclick="$(this).prev().click();">'.$obj_option->getLabel().'</label>';

		//- Corection du HTML afin de poster l'attribut value="" dans le DOM
		//- Si non, une checkbox coché envoie la valeur "on" en données POST
		
		$stri_input = $obj_mixed_input->htmlValue();
		preg_match('/^((?!value=).)*$/',$stri_input,$arra_match);
		if ($arra_match[0])
		{
			$stri_input = preg_replace('/type="checkbox"/','type="checkbox" value=""',$stri_input);
		}
		
		 		
        //- Retour string
        return $stri_input . $stri_label;
        
  }
  
   /*************************************************************
   * Méthode de construction d'une option 
   * 
   * @param type $int_i             //- Utilisée dans l'identifiant
   * @param type $obj_option        //- Un obj option stocké dans la collection
   * @param type $bool_option_add   //- Booléan pour traiment spécial option d'ajout
   * 
   * @return String                 //- Un input et un label à intégrer dans un LI
   *                        
   **************************************************************/          
  
  /**
   * Méthode pour construire la ligne du groupe
   * 
   * @param type $stri_libelle_groupe
   */
  private function constructOneLiForGroup($int_i , $stri_libelle_groupe)
  {
      
      if ($stri_libelle_groupe)
      {
          $stri_id = 'ultra_select__group_'.$int_i.'_'.self::$int_nb_instance;
          
          $obj_checkbox = new checkbox('','');
            $obj_checkbox->setId($stri_id); 
            $obj_checkbox->setClass('ultra_select__group_checkbox'); 
            $obj_checkbox->setOnClick('ultra_select.toggleCheckboxGroup($(this));') ;
          
          $stri_label = '<label for="'.$stri_id.'" class="ultra_select__group_label">'.$stri_libelle_groupe.'</label>';
          
          return ($this->bool_multiple) ? $obj_checkbox->htmlValue().$stri_label : $stri_label;
      }
      
  }
  
  

  /*************************************************************
   * Permet de déterminer si l'option par défaut doit être activée
   *  
   * parametres :  aucun
   * @return : string : le code HTML
   *                        
   **************************************************************/          
  private function isSelected()
  {
   
      //- Recherche nom et index 
      preg_match('/(\w*)(\[(\w*)\])?/', $this->constructName(), $arra_match);
      $stri_name = $arra_match[1];
      $stri_idx = $arra_match[3];


      //- Select simple
      if (!$this->bool_multiple)
      {
          //- Verification du POST
          if (isset($_POST[$stri_name]) && $_POST[$stri_name] !== '')
          { return false; }
      }
      
      
      //- Select multiple
      if (isset($_POST[$stri_name]) && is_array($_POST[$stri_name]))
      {
          //- Parcours le POST
          foreach ($_POST[$stri_name][$stri_idx] as $int_idx=>$stri_value)
          {
              //- Vérifcation 
              if ($stri_value !== '')
              { return false; }
          }
      }

      //- Si une valeur existe, retourne false
      //- Si non, on coche l'option par défaut
      return ($this->getSelectedOptionValue()) ? false : true;
      
       
      
  }
  
  

  /*************************************************************
   * Procédure de constsruction des options pour le select   * 
   * parametres :  aucun
   * @return : string : le code HTML
   *                        
   **************************************************************/          
  private function constructOptions()
  {
       
         
        //- Conteneur général des options
        $obj_ul = new ul('ultra_select_'.self::$int_nb_instance, 'ultra_select__ul');
            $obj_ul->setStyle('height: 0px;');
        
            
            
        //- Option sélectionné par défaut 
        $obj_option = new option('',__LIB_CHOIX);
            $obj_option->setClass('ultra_select__option_default');
            $obj_option->setSelected($this->isSelected());
            $obj_option->setDisabled($this->bool_disabled);
            
        //- L'item 
        $obj_li = $obj_ul->addLi('', '', $this->constructOneLi('default', $obj_option) );
            //$obj_li->setClass('ultra_select__option');
            $obj_li->setStyle('display: none;');
        $obj_ul->addContain($obj_li->htmlValue());


            
            
        //- Nombre d'option non null
        $int_i = 1;

        
        //- Aucun groupe ?
        if (!$this->arra_group)
        { 
            //-Initilisation par défaut
            $this->arra_group[] = ['',count($this->arra_option)]; 
        }
        
        //- Index option de départ
        $int_idx_option_start = 0;
        
        //- Pour chaque groupe
        foreach ($this->arra_group as $int_idx => $arra_one_group)
        {
            //- Libelle et nombre d'option
            $stri_libelle_group = $arra_one_group['libelle'];
            $int_idx_option_max = $arra_one_group['option'];
            //- Déduction si plusieurs groupe
            $int_idx_option_max = ($int_idx_option_max) ? $int_idx_option_max : count($this->arra_option)-1;
            //- Déduction premier groupe 
            $int_idx_option_start = ($int_idx_option_max) ? $int_idx_option_start : $int_idx_option_max-$int_idx_option_start;
            
            //- Conteneur du groupe
            $obj_ul_group = new ul('','ultra_select__group', $this->constructOneLiForGroup($int_idx, $stri_libelle_group) );
            
            
            
            //- Parcours les options 
            //foreach ($this->arra_option as $obj_option)
            for ($i=$int_idx_option_start; $i<=$int_idx_option_max; $i++)
            {
                //- L'option 
                $obj_option = $this->arra_option[$i];
                
                //- Le faites votre choix est géré en JS
                if ($obj_option->getValue() != '')
                {
                    //- L'item 
                    $obj_li = $obj_ul->addLi('', '', $this->constructOneLi($int_i, $obj_option) );
                        $obj_li->setClass('ultra_select__option');

                    //- Pose dans le conteneur parent
                    //$obj_ul->addContain($obj_li->htmlValue());
                    $obj_ul_group->addContain($obj_li->htmlValue());

                    $int_i++;
                }
            }
            $int_idx_option_start = $i;
            
            $obj_ul->addContain($obj_ul_group->htmlValue());
            
        }
        
        
        $stri_font = ($this->bool_option_addable) ? __LIB_ULTRA_SELECT_NO_RESULT_ADD : __LIB_ULTRA_SELECT_NO_RESULT;
        
        //- Font aucun résultat
        $obj_font = new font($stri_font,true);
            $obj_font->setColor('red');
        
        $stri_option_add = ($this->bool_option_addable) ? '<br/>'.$this->constructOneLi($int_i, (new option('', '')), true) : '';
        $stri_style = (!$this->bool_option_addable) ? 'text-align: center;' : '';
        
        //- Pose no result avec option d'ajout
        $obj_li = $obj_ul->addLi('', '',$obj_font->htmlValue().$stri_option_add);
            $obj_li->setClass('ultra_select__option_no_result ultra_select__option');
            //$obj_li->setStyle('text-align: center;display: none; ');
            $obj_li->setStyle('display: none; '.$stri_style);
        $obj_ul->addContain($obj_li->htmlValue());
        

        
        //- font option ajoutés
        $obj_font = new font(__LIB_ULTRA_SELECT_OPTION_ADD,true);
            $obj_font->setColor('orange');
        
        $obj_ul_option_add = new ul('','ultra_select__option_add', $obj_font->htmlValue().'<br/>' );
            $obj_ul_option_add->setStyle('display: none; ');
        $obj_ul->addContain($obj_ul_option_add->htmlValue());
        
            
        
        
        return $obj_ul;
      
  }
  
  
  //**** public method *********************************************************
 
  
  /*************************************************************
   * Permet d'afficher la liste déroulante au format HTML
           
   * parametres :  aucun
   * retour : string : le code HTML, JS & CSS
   *                        
   **************************************************************/          
  public function htmlValue()
  { 
  
	
      
     //- Input de recherche
     $obj_input_search = new text('','');
        $obj_input_search->setClass('ultra_select__input_search');
        $obj_input_search->setPlaceholder(__LIB_ULTRA_SELECT_FILTRER);
        $obj_input_search->setOnkeyup('ultra_select.filterOptions( $(this));');
     
    //- La sélection utilisateur 
    $obj_div_user_selection = new div('',__LIB_CHOIX);
        $obj_div_user_selection->setClass('ultra_select__selection_div');
        
    
    //- Class CSS
    $stri_class = 'ultra_select ultra_select__collapse';
    $stri_class .= ($this->bool_multiple) ? ' ultra_select__multiple' : '';
    
        
     //- Conteneur principal
     $obj_table = new table();
     $obj_table->setClass($stri_class);
     $obj_table->setData('method_toggle','expand');
     
        $obj_tr    = $obj_table->addTr();
            $obj_tr->setClass('ultra_select__search');
            $obj_tr->setStyle('display: none;');
            $obj_td    = $obj_tr->addTd($obj_input_search);
                
        $obj_tr    = $obj_table->addTr();
            $obj_tr->setClass('ultra_select__selection');
            $obj_td    = $obj_tr->addTd($obj_div_user_selection);
        
        $obj_tr    = $obj_table->addTr();
            $obj_tr->setClass('ultra_select__options');
            $obj_tr->setStyle('display: none;');


        //- Rattachement du UL dans un table
        $obj_td    = $obj_tr->addTd($this->constructOptions());
        

        //- Engloble la table dans un wrapper
        $obj_div_wrapper = new div($this->stri_id ,$obj_table->htmlValue());
            $obj_div_wrapper->setName($this->stri_name);
            $obj_div_wrapper->setClass('ultra_select__wrapper '.$this->stri_name);
            $obj_div_wrapper->setOnclick('ultra_select.expand(event, $(this));');
            //$obj_div_wrapper->setOnmouseenter('ultra_select.clearTimer(event, $(this));');
            
            //$obj_div_wrapper->setOnmouseleave('ultra_select.collapse(event, $(this));');
            
            $obj_div_wrapper->setStyle($this->stri_style);
        
        //- Concaténation 
        $stri_return .= $obj_div_wrapper->htmlValue();

        
        
        //- Incrémentation 
        self::$int_nb_instance++;
        self::$arra_int_idx_post[$this->stri_name]++;
        
        //- Retour du HTML
        return $stri_return;
             
  }
}

/*******
 * Code extra classe
 * Inclus depuis autoload html.pkg.php
 * 
 */

 //- CSS
$obj_css = new css();
$obj_css->addClass(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/includes/classes/html_class/ultra_select/ultra_select.class.css'));

//- Javascripter
$obj_js = new javascripter();
    $obj_js->addFunction(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/includes/classes/html_class/ultra_select/ultra_select.class.js'));

//- Concaténation des informations
echo $obj_css->cssValue().$obj_js->javascriptValue();




?>
