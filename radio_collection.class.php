<?php
/*******************************************************************************
Create Date : 19/06/2006
 ----------------------------------------------------------------------
 Class name : radio_collection
 Version : 1.1
 Author : Rémy Soleillant
 Description : permet de gérer une collection d'objet de type radio
********************************************************************************/
include_once('radio.class.php');
include_once('font.class.php');
include_once('table.class.php');

class radio_collection extends input 
{
   
   /*attribute***********************************************/
   protected $arra_radio=array();
   protected $stri_name="";
   protected $obj_font;
   protected $stri_data_type="string";
   protected $int_nbr_radio_by_line=100;
   public $arra_sauv=array();
   
   protected static $int_nb_radio=0;
   protected static $int_nb_instance=0;






   /* constructor***************************************************************/
   function __construct($name,$data_type="string") {
       $this->stri_name=$name;
       $this->stri_data_type=$data_type;
       $this->obj_font=new font('');      
   }
  
  
   /*setter*********************************************************************/
  public function setFont($obj_font)
  {$this->obj_font=$obj_font;}
  
  
   public function setNumberRadioByLine($int)
   {$this->int_nbr_radio_by_line=$int;}
   
  public function setSelectedRadioByValue($value)
  { 
   $i=0;
   $bool_find=false;
   $int_nb_radio= count($this->arra_radio);
  // while($i<count($this->arra_radio)&&($this->arra_radio[$i]['radio']->getValue()!=$value))
   while(($i<$int_nb_radio)&&(!$bool_find))//recherche de l'option sélectionnée
   {
    $bool_find=$this->arra_radio[$i]['radio']->getValue()==$value;
    $i++;
   }
   
   if($bool_find)
   {$this->arra_radio[$i-1]['radio']->setChecked(true);}
   
   return $bool_find;
  }
  
  public function setOnchange($value)
  {
    $i=0;
    $int_nb_radio= count($this->arra_radio);

    while(($i<$int_nb_radio))
    {
      $this->arra_radio[$i]['radio']->setOnchange($value);
      $i++;
    }
  }
  
  public function setTitle($value)
  {
    $i=0;
    $int_nb_radio= count($this->arra_radio);

    while(($i<$int_nb_radio))
    {
      $this->arra_radio[$i]['radio']->setTitle($value);
      $i++;
    }
  }
  
  /*getter**********************************************************************/
  
  public function getIemeRadio($int)
  {return $this->arra_radio[$int];}
 
  public function getRadio()
 {return $this->arra_radio;}
   
   public function setDisabled($bool_disabled)
   {  
    foreach($this->arra_radio as $arra_data)
    { 
     $obj_radio=$arra_data['radio'];
     $obj_radio->setDisabled($bool_disabled);
    }
   }
   /* method for serialization **************************************************/

  /*  public function __sleep() 
  {
     $this->arra_sauv['stri_name']  = $this->stri_name;
     $this->arra_sauv['stri_type']  = $this->stri_type;
     $this->arra_sauv['stri_value']  = $this->stri_value;
     $this->arra_sauv['bool_checked']  = $this->bool_checked; 
     $this->arra_sauv['stri_alt']  = $this->stri_alt;
     $this->arra_sauv['stri_onfocus']  = $this->stri_onfocus;
     $this->arra_sauv['stri_onblur']  = $this->stri_onblur;
     $this->arra_sauv['stri_onselect']  = $this->stri_onselect;
     $this->arra_sauv['stri_onchange']  = $this->stri_onchange;
     $this->arra_sauv['int_tabindex']  = $this->int_tabindex;
     $this->arra_sauv['bool_readonly']  = $this->bool_readonly;
     return array('arra_sauv');
   }
   
    public function __wakeup() 
  {
     $this->stri_src  = $this->arra_sauv['src'];
     $this->stri_name= $this->arra_sauv['stri_name'];
     $this->stri_type= $this->arra_sauv['stri_type'];
     $this->stri_value= $this->arra_sauv['stri_value'];
     $this->bool_checked= $this->arra_sauv['bool_checked'];
     $this->stri_alt= $this->arra_sauv['stri_alt'];
     $this->stri_onfocus= $this->arra_sauv['stri_onfocus'];
     $this->stri_onblur= $this->arra_sauv['stri_onbur'];
     $this->stri_onselect= $this->arra_sauv['stri_onselect'];
     $this->stri_onchange= $this->arra_sauv['stri_onchange'];
     $this->int_tabindex = $this->arra_sauv['int_tab_index'];
     $this->bool_readonly= $this->arra_sauv['bool_readonly'];
     $this->arra_sauv = array();
     
   }*/
 

  
  /*other method****************************************************************/
  public function addRadio($value,$label)
  {
    $obj_radio=new radio($this->stri_name,$value);
    $nbr=count($this->arra_radio);
    $this->arra_radio[$nbr]['radio']=$obj_radio;
    $this->arra_radio[$nbr]['label']=$label;
    $obj_radio->setDataType($this->stri_data_type);
    return  $this->arra_radio[$nbr]['radio'];
  }
  
  
  public function htmlValue($int_cell_spacing = 0, $int_cell_padding=0)
  {
  $html_table=new table();
  $html_table->setCellspacing($int_cell_spacing);
  $html_table->setCellpadding($int_cell_padding);
  $html_table->setBorder(0);
  $i=0;
  $j=0;
  
  while($j<count($this->arra_radio))
  { 
   $tr=new tr();
   while(($i<$this->int_nbr_radio_by_line)&&($i+$j<count($this->arra_radio)))
   {
    $arra_temp=$this->arra_radio[$i+$j];
    $font=$this->obj_font;
    $font->setValue($arra_temp['label']);
    
    //$tr->addTd($arra_temp['radio']->htmlValue());
    //$tr->addTd($font->htmlValue());

    //- Obj radio
    $obj_radio = $arra_temp['radio'];
            
    //Création d'un ID basé sur nombre d'instance créer
    $stri_id = 'radio_'.self::$int_nb_radio.'_'.$arra_temp['label'];
    ($obj_radio->getId()=='') ? $obj_radio->setId($stri_id) : null;

    $stri_class = (strpos($obj_radio->getClass(), 'css-checkbox') !== FALSE) ? 'css-label' : null;
    $obj_td = $tr->addTd($obj_radio->htmlValue().'<label class="'.$stri_class.'" for="'.$stri_id.'">'.$font->htmlValue().'</label>');
    
    $i++;   
    self::$int_nb_radio++;
   }
   //$i radio have been treated
   $html_table->insertTr($tr);
   $j+=$i;
   $i=0;
  } 
  

  if (self::$int_nb_instance == 0)
  {
  
  $stri_style = "<style>
      
        input[type=radio].css-checkbox {
            position:absolute; 
            z-index:-1000;
            display: none;
        }

        input[type=radio].css-checkbox + label.css-label {
            padding-left:26px;
            height:21px; 
            display:inline-block;
            line-height:21px;
            background-repeat:no-repeat;
            background-position: 0 0;
            vertical-align:middle;
            cursor:pointer;
            //font-size: 12px;

        }

        input[type=radio].css-checkbox:checked + label.css-label {
            background-position: 0 -21px;
        }

        label.css-label {
            background-image:url(images/css_checkbox.png);
        }</style>";
  }

    return $stri_style .  $html_table->htmlValue();
 }
 
  /*************************************************************
  Permet de créer les différents boutons radio à partir 
  d'une requête sql
 
 Paramètres : string  : la requête sql à 1 ou 2 champs
              obj : l'objet à utiliser pour appliquer la méthode $func
              string : si $obj est non vide : la méthode à appliquer aux résultats de la requêtes
                       si $obj est vice : la fonction à appliquer aux résultats de le requêtes
 Retour : aucun
      
  **************************************************************/     

 public function makeSqlToRadio($stri_sql,$obj="",$func="")
  {
    $obj_query=new querry_select($stri_sql);
    $arra_res=$obj_query->execute();
    $int_col_value=0;
    $int_col_label=(count($arra_res[0])>1)?1:0;
    
    foreach($arra_res as $arra_one_res)
    {
     $stri_value=$arra_one_res[$int_col_value];
     $stri_label=$arra_one_res[$int_col_label];
     //application des traitement éventuels sur les labels
     
     if(is_object($obj)&& method_exists($obj,$func))
     {$stri_label=$obj->$func($stri_label);}
     
     if(($obj=="")&&($func!=""))
     {$stri_label=$func($stri_label);}
    
     $this->addRadio($stri_value,$stri_label);//ajout du bouton radio
    }
  } 
  
}

?>
