<?php
/*******************************************************************************
Create Date : 22/05/2006
 ----------------------------------------------------------------------
 Class name : a
 Version : 1.1
 Author : Rémy Soleillant
 Description : élément html <a>
 
 Modif branche 01 
********************************************************************************/
class a {
   
   //**** attribute ************************************************************
   
   protected $stri_alt="";        //=>le nom de remplacement du lien html
   protected $stri_href;          //=>le chemin sur lequel le lien va pointer
   protected $stri_name="";       //=>le nom de l'objet lien
   protected $stri_target="";     //=>la cible du lien -_blank : sur un nouvel onglet,...-
   protected $stri_title="";      //=>le titre explication du lien lorsque l'on passe la souris dessus le lien
   protected $stri_value="";      //=>le libellé du lien
   protected $stri_onclick="";    //=>les évenements javascript sur le clic de souris du lien
   protected $stri_style="";      //=>le style de la balise
   protected $stri_id="";
   protected $stri_class="";
   protected $stri_type="";       //Modification 23/01/2013 rajout du type
   public $arra_sauv=array();     //tableau pour la sérialisation
   protected $stri_download='';
  
  //**** constructor ***********************************************************
  function __construct($url,$value,$is_obj=false) 
  {
    //construit l'objet a
    //@param : $url => le chemin sur lequel le lien va pointer
    //@param : $value => le libellé du lien
    //@param : is_obj => true : si $value est un objet
    //                   false : si $value n'est pas un objet
    //                   [ex : $obj_img=new img("monimage.jpg");
    //                         $obj_a=new a("monurl.html",$obj_img->htmlValue(),true)
    //                   ]
    //@return : void
   
    
    $this->stri_href=$url;
    $this->stri_value=htmlentities($value,ENT_COMPAT, 'ISO-8859-1');
    if($is_obj)
    {$this->stri_value=$value;}
    
   
  }
 
  //**** setter ****************************************************************
  public function setAlt($value){$this->stri_alt=$value;}  
  public function setOnclick($value){$this->stri_onclick=$value;}
  public function setValue($value){$this->stri_value=$value;}
  public function setTitle($value){$this->stri_title=$value;}
  public function setName($value){$this->stri_name=$value;}
  public function setHref($value){$this->stri_href=$value;}
  public function setTarget($value){$this->stri_target=$value;}
  public function setStyle($value){$this->stri_style=$value;}
  public function setId($value){$this->stri_id=$value;}
  public function setClass($value){$this->stri_class=$value;}
  public function setType($value){$this->stri_type=$value;}
  public function setDownload($value){$this->stri_download=$value;}
  //**** getter ****************************************************************
  public function getAlt(){return $this->stri_alt;}
  public function getOnclick(){return $this->stri_onclick;}
  public function getTitle(){return $this->stri_title;}
  public function getHref(){return $this->stri_href;}
  public function getName(){return $this->stri_name;} 
  public function getValue(){return $this->stri_value;} 
  public function getTarget(){return $this->stri_target;}
  public function getStyle($value){return $this->stri_style;}
  public function getClass($value){return $this->stri_class;}
  public function getType($value){return $this->stri_type;}
  public function getId(){return $this->stri_id;}
  public function getDownload(){return $this->stri_download;}
  
  //**** public method *********************************************************
  public function htmlValue()
  {
    //affiche le lien en html
    //@return : [string] => le lien sous forme HTML
    
    $stri_res="<a";
    $stri_res.=($this->stri_href!="")? " href='".$this->stri_href."' " : "";
    $stri_res.=($this->stri_id!="")? " id='".$this->stri_id."' " : "";
    $stri_res.=((string)$this->stri_alt!="")? " alt='".$this->stri_alt."' " : "";
    $stri_res.=((string)$this->stri_title!="")? " title='".$this->stri_title."' " : "";
    $stri_res.=($this->stri_name!="")? " name='".$this->stri_name."' " : "";            
    $stri_res.=(!empty($this->stri_target))?" target=\"".$this->stri_target."\" " : "";
    $stri_res.=($this->stri_onclick!="")?" onclick=\"".$this->stri_onclick."\" " : "";
    $stri_res.=($this->stri_style!="")?" style=\"".$this->stri_style."\" " : "";
    $stri_res.=($this->stri_class!="")?" class=\"".$this->stri_class."\" " : "";
    $stri_res.=($this->stri_type!="")? " type='".$this->stri_type."' " : "";
    $stri_res.=($this->stri_download!="")? " download='".$this->stri_download."' " : "";
    $stri_res.=">".$this->stri_value."</a>";
    
    return $stri_res;
  }
  
  //**** method for serialization **********************************************
  public function __sleep() 
  {
    //sérialisation de la classe a
    $this->arra_sauv['alt']= $this->stri_alt;
    $this->arra_sauv['href']= $this->stri_href;
    $this->arra_sauv['name']= $this->stri_name;
    $this->arra_sauv['target']= $this->stri_target;
    $this->arra_sauv['title']= $this->stri_title;
    $this->arra_sauv['value']= $this->stri_value;
    $this->arra_sauv['onclick']= $this->stri_onclick;
    $this->arra_sauv['style']= $this->stri_style;
    $this->arra_sauv['class']= $this->stri_class;
    $this->arra_sauv['type']= $this->stri_type;
    return array('arra_sauv');
  }
  
  public function __wakeup() 
  {
    //désérialisation de la classe a
    $this->stri_alt= $this->arra_sauv['alt'];
    $this->stri_href= $this->arra_sauv['href'];
    $this->stri_name= $this->arra_sauv['name'];
    $this->stri_target= $this->arra_sauv['target'];
    $this->stri_title= $this->arra_sauv['title'];
    $this->stri_value= $this->arra_sauv['value'];
    $this->stri_onclick= $this->arra_sauv['onclick'];
    $this->stri_style= $this->arra_sauv['style'];
    $this->stri_class= $this->arra_sauv['class'];
    $this->stri_type= $this->arra_sauv['type'];
    $this->arra_sauv = array();
  } 
}

?>
