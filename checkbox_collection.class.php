<?php
/*******************************************************************************
Create Date : 25/07/2006
 ----------------------------------------------------------------------
 Class name : checkbox_collection
 Version : 1.1
 Author : Rémy Soleillant
 Description : permet de gérer une collection d'objet de type checkbox
********************************************************************************/
include_once('checkbox.class.php');
include_once('font.class.php');
include_once('table.class.php');

class checkbox_collection 
{ 
  //**** attribute *************************************************************
  protected $arra_checkbox=array();         //=>tableau contenant les différents objets checkbox
  protected $stri_name="";                  //=>le nom générique de tous les checkbox
  protected $obj_font;                      //=>le libellé de la collection
  protected $stri_data_type="string";       //=>le type de données contenu dans les checkbox
  protected $int_nbr_checkbox_by_line=100;  //=>le nombre de checkbox affiché par ligne

  public $arra_sauv=array();                //=> tableau pour la serialisation
  
  
  //**** constructor ***********************************************************
  function __construct($name,$data_type="string") 
  {
    //construit l'objet collection de checkbox
    //@param : $name => le nom de l'objet collection checkbox
    //@param : $data_type =>
    //@return : void
    
    $this->stri_name=$name;
    $this->stri_data_type=$data_type;
    $this->obj_font=new font();      
  }
  
  
  //**** setter ****************************************************************
  public function setFont($obj_font){$this->obj_font=$obj_font;}
  public function setNumberCheckboxByLine($int){$this->int_nbr_checkbox_by_line=$int;}
  public function setSelectedCheckboxByValue($value)
  { 
    //coche un checkbox en fonction de sa valeur
    //@param : $value => la valeur d'un checkbox de la collection
    //@return : void
    $i=0; 
    while($i<count($this->arra_checkbox)&&($this->arra_checkbox[$i]['checkbox']->getValue()!=$value)){$i++;}
    if($i<count($this->arra_checkbox)){$this->arra_checkbox[$i]['checkbox']->setChecked(true);}
  }
  
  public function setSelectedCheckboxByArray($arra_value)
  {//coche tous les checkbox dont la valeur est contenu dans le tableau passé en paramètre
   //@param : $arra_value=> tableau des différentes valeurs à cocher
   foreach($arra_value as $stri_value)
   {
    $this->setSelectedCheckboxByValue($stri_value);
   }
  }
  
  public function setDisabled($bool_disabled)
  {//désactive/active toutes les cases à cocher
   //@param : bool 
   foreach($arra_value as $stri_value)
   {
    $this->setDisabled($bool_disabled);
   }
  }
  //**** getter ****************************************************************
  public function getIemeCheckbox($int){return $this->arra_checkbox[$int]['checkbox'];}
  public function getCheckbox(){return $this->arra_checkbox;}
  public function getName(){return $this->stri_name;}
  
    
  //**** public method *********************************************************
  
  //applique le on chance sur toutes les checkbox
  public function setOnChangeAll($value) {
    
    for($i=0;$i<count($this->arra_checkbox);$i++)
    {
      $obj_checkbox = $this->arra_checkbox[$i]['checkbox'];
      $obj_checkbox->setOnchange($value);
    }
  }
  
  
  public function selectAll()
  {
    //coche tous les checkbox de la collection
    //@return : void
    
    foreach($this->arra_checkbox as $arra_temp){$arra_temp['checkbox']->setChecked(true);}
  }
    
  public function addCheckbox($value,$label)
  {
    // ajoute un checkbox à la collection
    //@param : $value => [string] : la valeur du checkbox qui sera envoyée
    //@param : $label => [string] : le libellé du checkbox
    //@return : $obj_checkbox
    
    $obj_checkbox=new checkbox($this->stri_name,$value);
    $nbr=count($this->arra_checkbox);
    $this->arra_checkbox[$nbr]['checkbox']= $obj_checkbox;
    $this->arra_checkbox[$nbr]['label']= $label;
    $obj_checkbox->setDataType($this->stri_data_type);
    return $obj_checkbox;
  }
  
  public function constructTable()
  {
   //permet de construire le tableau html contenant les checkbox
   //@return : object table
    $html_table=new table();
    $html_table->setCellspacing(0);
    $html_table->setCellpadding(0);
    $html_table->setBorder(0);
    $i=0;
    $j=0;
    while($j<count($this->arra_checkbox))
    { 
      $tr=new tr();
      $i=0;
      while(($i<$this->int_nbr_checkbox_by_line)&&($i+$j<count($this->arra_checkbox)))
      {
        $arra_temp=$this->arra_checkbox[$j+$i];
        $font=$this->obj_font;
        $font->setValue($arra_temp['label']);
        $tr->addTd($arra_temp['checkbox']->htmlValue());
        $tr->addTd($font->htmlValue());
        $i++;   
      }
      //$i checkbox have been treated
      $html_table->insertTr($tr);
      $j+=$i;
    }
    return $html_table ;
  }
  
   //Pour construire la collection à partir du dico constante
  public function makeDicoToCollection($stri_table,$stri_champ,$obj="",$func="")
  {
   $stri_sql="  SELECT nom,Decode(type_valeur,'N',valeur_num,valeur_char) val
                FROM dico_constante dc, dico_lexique dl
                WHERE dc.num_constante=dl.num_constante
                    AND dl.nom_table='".strtoupper($stri_table)."'
                    AND dl.nom_champ='".strtoupper($stri_champ)."' 
                ORDER By VAL        
                ";
   $obj_query=new querry_select($stri_sql);
   $arra_res=$obj_query->execute();
   
   $stri_case=(is_object($obj))?"object_":"";
   $stri_case=($func!="")?"function":"";
   
   
   foreach($arra_res as $arra_one_res)
   {
     switch($stri_case)
     {
       case "object_function"://il faut appliquer une méthode d'un objet 
         $this->addCheckbox($arra_one_res[1],$obj->$func($arra_one_res[0]));
       break;
       case "function"://il faut appliquer une fonction sur le libellé 
         $this->addCheckbox($arra_one_res[1],$func($arra_one_res[0]));
       break;
       default: //pas de traitement particulier
         $this->addCheckbox($arra_one_res[1],$arra_one_res[0]);
     }
   }   
      
  }
  
  public function htmlValue()
  {
    //affiche la collection des cases à cocher
    //@return : [string] => renvoie le code HTML
    
    $html_table=$this->constructTable();
    return $html_table->htmlValue();
  }  
}

?>
