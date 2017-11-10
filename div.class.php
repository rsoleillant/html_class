<?php
/*******************************************************************************
Create Date : 31/12/2007
 ----------------------------------------------------------------------
 Class name : div
 Version : 1.0
 Author : Rémy Soleillant
 Description : élément html <div>
********************************************************************************/
class div {
   
  //**** attribute ************************************************************
  
  protected $stri_id="";             //id de la balise div
  protected $stri_style="";          //style de la balise div
  protected $stri_contain="";        //contenu de la balise div
  protected $stri_name;              //le nom de la div
  protected $stri_onclick;
  protected $stri_ondblclick;
  protected $stri_onmouseover;
  protected $stri_onmouseout;
  protected $stri_onmousedown;
  protected $stri_onmouseup;
  protected $stri_onmouseenter;
  protected $stri_onmouseleave;
  protected $stri_class;             //class css de la div
  public $arra_sauv=array();         //tableau pour la sérialisation
  
  //**** constructor ***********************************************************
  function __construct($id,$contain) 
  {
    //construit l'objet div
    //@param : $id => identificateur de la div
    //@param : $contain => contenu de la div
    //@return : void
    $this->stri_id=$id;
    $this->stri_contain=$contain;
  }
  
  //**** public method *********************************************************
  public function getStartBalise()
  {
    //insère la balise de debut de formulaire
    //@return : $stri_res => html
    
    $stri_res="<div ";
    $stri_res.=($this->stri_id!="")? "id=\"".$this->stri_id."\" " : "";
    $stri_res.=($this->stri_style!="")? "style=\"".$this->stri_style."\" " : "";
    
    $stri_res.=" >";
    return $stri_res;
  }

  //insère la balise de fin de formulaire
  public function getEndBalise(){return "</div>";}
  
  
  
  
  //**** setter ****************************************************************
  public function setId($value){$this->stri_id=$value;}				
  public function setStyle($value){$this->stri_style=$value;}				
  public function setContain($value){$this->stri_contain=$value;}	
  public function setClass($value){$this->stri_class=$value;}
  public function setName($value){$this->stri_name=$value;}
  public function setOnclick($value){$this->stri_onclick=$value;}
  public function setOndblclick($value){$this->stri_ondblclick=$value;}
  public function setOnmouseover($value){$this->stri_onmouseover=$value;}
  public function setOnmouseout($value){$this->stri_onmouseout=$value;}
  public function setOnmousedown($value){$this->stri_onmousedown=$value;}
  public function setOnmouseup($value){$this->stri_onmouseup=$value;}
  public function setOnmouseenter($value){$this->stri_onmouseenter=$value;}
  public function setOnmouseleave($value){$this->stri_onmouseleave=$value;}

  //**** getter ****************************************************************
  public function getId(){return $this->stri_id;}
  public function getStyle(){return $this->stri_style;}
  public function getContain(){return $this->stri_contain;}
  public function getName(){return $this->stri_name;}
  public function getOnclick(){return $this->stri_onclick;}
  public function getOndblclick(){return $this->stri_ondblclick;}
  public function getOnmouseover(){return $this->stri_onmouseover;}
  public function getOnmouseout(){return $this->stri_onmouseout;}
  public function getOnmousedown(){return $this->stri_onmousedown;}
  public function getOnmouseup(){return $this->stri_onmouseup;}
  public function getOnmouseenter(){return $this->stri_onmouseenter;}
  public function getOnmouseleave(){return $this->stri_onmouseleave;}

  
  //**** public method *********************************************************
  public function addContain($contain)
  {
    //permet d'ajouter du contenu a celui déjà existant 
    //@param : $contain => contenu supplémentaire à inserer dans la div
    //@return : void
    
    $this->stri_contain.=$contain;
  }  
  
  public function htmlValue()
  {
    //affiche l'objet div
    //@return : $stri_res => code HTML du div
    $stri_res="<div ";
    
    $stri_res.=((string)$this->stri_id!="")? " id=\"".$this->stri_id."\" " : "";
    $stri_res.=((string)$this->stri_style!="")?" style=\"".$this->stri_style."\" " : "";
    $stri_res.=((string)$this->stri_name!="")?" name=\"".$this->stri_name."\" " : "";
    $stri_res.=((string)$this->stri_class!="")?" class=\"".$this->stri_class."\" " : "";
    $stri_res.=($this->stri_ondblclick!="")? " ondblclick=\"".$this->stri_ondblclick."\" " : ""; 
    $stri_res.=($this->stri_onmouseover!="")? " onmouseover=\"".$this->stri_onmouseover."\" " : "";           
    $stri_res.=($this->stri_onmouseout!="")? " onmouseout=\"".$this->stri_onmouseout."\" " : "";
    $stri_res.=($this->stri_onclick!="")? " onclick=\"".$this->stri_onclick."\" " : "";
    $stri_res.=($this->stri_onmousedown!='')? ' onmousedown="'.$this->stri_onmousedown.'" ' : '';           
    $stri_res.=($this->stri_onmouseup!='')? ' onmouseup="'.$this->stri_onmouseup.'" ' : '';
    $stri_res.=($this->stri_onmouseenter!='')? ' onmouseenter="'.$this->stri_onmouseenter.'" ' : '';           
    $stri_res.=($this->stri_onmouseleave!='')? ' onmouseleave="'.$this->stri_onmouseleave.'" ' : '';
    

 
 
    $stri_res.=">".$this->stri_contain."</div>";
    
    return $stri_res;
  }
 
  
  //**** method for serialization **********************************************
  public function __sleep() 
  {
    //sérialisation de la classe div
    $this->arra_sauv['id']= $this->stri_id;
    $this->arra_sauv['style']= $this->stri_style;
    $this->arra_sauv['contain']= $this->stri_contain;

    return array('arra_sauv');
  }

  public function __wakeup() 
  {  
    //désérialisation de la classe div
    $this->stri_id= $this->arra_sauv['id'];
    $this->stri_style= $this->arra_sauv['style'];
    $this->stri_contain= $this->arra_sauv['contain'];

    $this->arra_sauv = array();
  }
}
?>
