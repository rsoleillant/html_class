<?php
/*******************************************************************************
Create Date : 22/05/2006
 ----------------------------------------------------------------------
 Class name : file
 Version : 1.0
 Author : Rémy Soleillant
 Description : élément html <input type='file'>
********************************************************************************/
include_once('input.class.php');
include_once('hidden.class.php');

//la classe file hérite de la classe input
class file extends input 
{
  //****attribute***********************************************/
  protected $stri_accept="";      //=> l'extension autorisée
  protected $bool_readonly=false; //=> champ seulement en lecture
  protected $int_max_size;        //=> taille maximale du fichier pouvant être uploadé 
  protected $bool_always_transmit=false;  //Pour permettre de tout le temps transmettre la valeur
  //**** constructor ***********************************************************
  function __construct($name,$max_size=0) 
  {
    //construit l'objet file
    //@param : $name : [string] => le nom de l'objet file
    //@param : $max_size : [string] => la taille maximale du fichier à uploader
    
    $this->stri_value="";
    $this->stri_name=$name;
    $this->stri_type="file";
    $this->int_max_size=$max_size;
    
  }
  
  
  //**** setter ****************************************************************
  public function setAccept($type){$this->stri_accept=$type;}
  public function setMaxSize($value){$this->int_max_size=$value;}  
  public function setReadonly($bool)
  {
    if(is_bool($bool))
    {
      $this->bool_readonly=$bool;
    }
    else
    {
      echo("<script>alert('bool_readonly doit etre de type boolean');</script>");
    }
  }
  
  public function setAlwaysTransmit($value){$this->bool_always_transmit=$value;}
 
  
  //**** getter ****************************************************************
  public function getMaxSize(){return $this->int_max_size;}
  public function getAccept(){return $this->stri_accept;}
  public function getReadonly(){return $this->bool_readonly;}
  public function getAlwaysTransmit(){return $this->bool_always_transmit;}
  
  
  //**** public method *********************************************************
  public function htmlValue()
  {
    //affiche le champ file
    //@return : $stri_res => code HTML du champ file
    $stri_res="";
    if($this->bool_always_transmit)//si la valeur en post doit être retransmise, comportement normal sur un type text mais inhibé pour des raisons de sécurité sur un type file
    {   
     $obj_hidden=new hidden($this->stri_name,$this->stri_value);
      $obj_hidden->setClass("hidden_for_file");//une classe pour ciblé facilement l'hidden depuis le file
      $obj_hidden->setDisabled($this->bool_disabled);//transmission de disabled
    
     $this->stri_name="file_".$this->stri_name; //changement du nom car le post du hidden et du file ne peuvent pas porter le même nom sinon écrasement du post hidden par le file
     $stri_res.=$obj_hidden->htmlValue();
     
    }
    
    $obj_hidden=new hidden("MAX_FILE_SIZE",$this->int_max_size);
    $stri_limit=($this->int_max_size==0)?"":$obj_hidden->htmlValue();
    $stri_res.=$stri_limit."\n".$this->super_htmlValue();
    $stri_res.=($this->stri_accept!="")?" accept=\"".$this->stri_accept."\" ":"";
    $stri_res.=" enctype=\"multipart/form-data\" ";
    $stri_res.=($this->bool_readonly)? " readonly disabled " : "";
    $stri_res.=">"; 
    
   
    
    return $stri_res;
  }
  
  
  //**** method for serialization **********************************************
  public function __sleep() 
  {
    //sérialisation de la classe file et de sa classe mère
    $this->arra_sauv['name']= $this->stri_name;
    $this->arra_sauv['type']= $this->stri_type;
    $this->arra_sauv['value']= $this->stri_value;
    $this->arra_sauv['disabled']= $this->bool_disabled;
    $this->arra_sauv['size']= $this->int_size;
    $this->arra_sauv['alt']= $this->stri_alt;
    $this->arra_sauv['onfocus']= $this->stri_onfocus;
    $this->arra_sauv['onblur']= $this->stri_onblur;
    $this->arra_sauv['onselect']= $this->stri_onselect;
    $this->arra_sauv['onchange']= $this->stri_onchange;
    $this->arra_sauv['onmouseover']= $this->stri_onmouseover;
    $this->arra_sauv['onmouseout']= $this->stri_onmouseout;
    $this->arra_sauv['onkeypress']= $this->stri_onkeypress;
    $this->arra_sauv['tabindex']= $this->int_tabindex;
    $this->arra_sauv['data_type']= $this->stri_data_type;
    $this->arra_sauv['title']= $this->stri_title;
    $this->arra_sauv['style']= $this->stri_style;
    $this->arra_sauv['id']= $this->stri_id;
    $this->arra_sauv['class']= $this->stri_class;
    $this->arra_sauv['can_be_empty']= $this->bool_can_be_empty;
    $this->arra_sauv['accept']= $this->stri_accept;
    $this->arra_sauv['readonly']= $this->bool_readonly;
    $this->arra_sauv['max_size']= $this->int_max_size;
    
    return array('arra_sauv');
  }
  
  public function __wakeup() 
  {
    //désérialisation de la classe file et de sa classe mère
    $this->stri_name= $this->arra_sauv['name'];
    $this->stri_type= $this->arra_sauv['type'];
    $this->stri_value= $this->arra_sauv['value'];
    $this->bool_disabled= $this->arra_sauv['disabled'];
    $this->int_size= $this->arra_sauv['size'];
    $this->stri_alt= $this->arra_sauv['alt'];
    $this->stri_onfocus= $this->arra_sauv['onfocus'];
    $this->stri_onblur= $this->arra_sauv['onblur'];
    $this->stri_onselect= $this->arra_sauv['onselect'];
    $this->stri_onchange= $this->arra_sauv['onchange'];
    $this->stri_onmouseover= $this->arra_sauv['onmouseover'];
    $this->stri_onmouseout= $this->arra_sauv['onmouseout'];
    $this->stri_onkeypress= $this->arra_sauv['onkeypress'];
    $this->int_tabindex= $this->arra_sauv['tabindex'];
    $this->stri_data_type= $this->arra_sauv['data_type'];
    $this->stri_title= $this->arra_sauv['title'];
    $this->stri_style= $this->arra_sauv['style'];
    $this->stri_id= $this->arra_sauv['id'];
    $this->stri_class= $this->arra_sauv['class'];
    $this->bool_can_be_empty= $this->arra_sauv['can_be_empty'];
    $this->stri_accept= $this->arra_sauv['accept'];
    $this->bool_readonly= $this->arra_sauv['readonly'];
    $this->int_max_size= $this->arra_sauv['max_size'];
    $this->arra_sauv = array();
  }
} 
?>
