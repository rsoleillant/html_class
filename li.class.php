<?php
/*******************************************************************************
Create Date : 16/04/2012
 ----------------------------------------------------------------------
 Class name : li
 Version : 1.0
 Author : Mathieu TENA
 Description : élément html <li>
********************************************************************************/
class li {
   
  //**** attribute ************************************************************
  
  protected $stri_id="";              //id de la balise li
  protected $mixed_contain="";        //contenu de la balise li
  protected $stri_class;              //class css de la li
  protected $stri_onclick;
  protected $stri_ondblclick;
  protected $stri_onmouseover;
  protected $stri_onmouseout;
  protected $stri_style;
  public $arra_sauv=array();         //tableau pour la sérialisation
  
  //**** constructor ***********************************************************
  function __construct($id,$class,$mixed_contain) 
  {
    //construit l'objet li
    //@param : $id => identificateur de la li
    //@param : $class => classe de la li
    //@return : void
    $this->stri_id=$id;
	  $this->stri_class=$class;
    $this->mixed_contain=$mixed_contain;
    
   
  }
  
  //**** public method *********************************************************
  public function getStartBalise()
  {
    //insère la balise de debut de formulaire
    //@return : $stri_res => html
    
    $stri_res="<li ";
    $stri_res.=($this->stri_id!="")? "id=\"".$this->stri_id."\" " : "";
	$stri_res.=($this->stri_class!="")? "class=\"".$this->stri_class."\" " : "";
     
    $stri_res.=" >";
    return $stri_res;
  }

  //insère la balise de fin de formulaire
  public function getEndBalise(){return "</li>";}
  
  
  
  
  //**** setter ****************************************************************
  public function setId($value){$this->stri_id=$value;}							
  public function setContain($value){$this->mixed_contain=$value;}	
  public function setClass($value){$this->stri_class=$value;}
  public function setOnclick($value){$this->stri_onclick=$value;}
  public function setOndblclick($value){$this->stri_ondblclick=$value;}
  public function setOnmouseover($value){$this->stri_onmouseover=$value;}
  public function setOnmouseout($value){$this->stri_onmouseout=$value;}
  public function setStyle($value){$this->stri_style=$value;}

  //**** getter ****************************************************************
  public function getId(){return $this->stri_id;}
  public function getContain(){return $this->mixed_contain;}
  public function getOnclick(){return $this->stri_onclick;}
  public function getOndblclick(){return $this->stri_ondblclick;}
  public function getOnmouseover(){return $this->stri_onmouseover;}
  public function getOnmouseout(){return $this->stri_onmouseout;}
  public function getStyle(){return $this->stri_style;}
  
  
  //**** public method *********************************************************
  public function addContain($contain)
  {
    //permet d'ajouter du contenu a celui déjà existant 
    //@param : $contain => contenu supplémentaire à inserer dans la li
    //@return : void
    $contain=(is_object($contain))?$contain->htmlValue():$contain;   
    $this->mixed_contain.=$contain;
  }  
  
  public function htmlValue()
  {
  
    $stri_contain=(is_object($this->mixed_contain))?$this->mixed_contain->htmlValue():$this->mixed_contain;

    //affiche l'objet li
    //@return : $stri_res => code HTML du li
    $stri_res="<li ";
    
    $stri_res.=((string)$this->stri_id!="")? " id=\"".$this->stri_id."\" " : "";
    $stri_res.=((string)$this->stri_class!="")?" class=\"".$this->stri_class."\" " : "";
    $stri_res.=($this->stri_ondblclick!='')? ' ondblclick="'.$this->stri_ondblclick.'" ' : ''; 
    $stri_res.=($this->stri_onmouseover!='')? ' onmouseover="'.$this->stri_onmouseover.'" ' : '';           
    $stri_res.=($this->stri_onmouseout!='')? ' onmouseout="'.$this->stri_onmouseout.'" ' : '';
    $stri_res.=($this->stri_onclick!='')? ' onclick="'.$this->stri_onclick.'" ' : '';
    $stri_res.=($this->stri_style!='')? ' style="'.$this->stri_style.'" ' : '';

    $stri_res.=">".$stri_contain."</li>";
    
    return $stri_res;
  }
 
  
  //**** method for serialization **********************************************
  public function __sleep() 
  {
    //sérialisation de la classe li
    $this->arra_sauv['id']= $this->stri_id;
    $this->arra_sauv['contain']= $this->stri_contain;

    return array('arra_sauv');
  }

  public function __wakeup() 
  {  
    //désérialisation de la classe li
    $this->stri_id=$this->arra_sauv['id'];
    $this->stri_contain=$this->arra_sauv['contain'];

    $this->arra_sauv = array();
  }
}
?>
