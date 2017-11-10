<?php
/*******************************************************************************
Create Date : 16/04/2012
 ----------------------------------------------------------------------
 Class name : ul
 Version : 1.0
 Author : Mathieu TENA
 Description : élément html <ul>
********************************************************************************/
class ul {
   
  //**** attribute ************************************************************
  
  protected $stri_id="";             //id de la balise ul
  protected $stri_contain="";        //contenu de la balise ul
  protected $stri_class;             //class css de la ul
  protected $arra_li;                //Tableau des li 
  protected $stri_onclick;
  protected $stri_ondblclick;
  protected $stri_onmouseover;
  protected $stri_onmouseout;
  protected $stri_style;
  public $arra_sauv=array();         //tableau pour la sérialisation
  
  //**** constructor ***********************************************************
  function __construct($id,$class,$contain="") 
  {
    //construit l'objet ul
    //@param : $id => identificateur de la ul
    //@param : $contain => contenu de la ul
    //@return : void
    $this->stri_id=$id;
	  $this->stri_class=$class;
    $this->stri_contain=$contain;
  }
  
  //**** public method *********************************************************
  public function getStartBalise()
  {
    //insère la balise de debut de formulaire
    //@return : $stri_res => html
    
    $stri_res="<ul ";
    $stri_res.=($this->stri_id!="")? "id=\"".$this->stri_id."\" " : "";
	$stri_res.=($this->stri_class!="")? "class=\"".$this->stri_class."\" " : "";
    
    $stri_res.=" >";
    return $stri_res;
  }

  //insère la balise de fin de formulaire
  public function getEndBalise(){return "</ul>";}
  
  
  
  
  //**** setter ****************************************************************
  public function setId($value){$this->stri_id=$value;}							
  public function setContain($value){$this->stri_contain=$value;}	
  public function setClass($value){$this->stri_class=$value;}
  public function setOnclick($value){$this->stri_onclick=$value;}
  public function setOndblclick($value){$this->stri_ondblclick=$value;}
  public function setOnmouseover($value){$this->stri_onmouseover=$value;}
  public function setOnmouseout($value){$this->stri_onmouseout=$value;}
  public function setStyle($value){$this->stri_style=$value;}
  public function setLi($value){$this->arra_li=$value;}

  //**** getter ****************************************************************
  public function getId(){return $this->stri_id;}
  public function getContain(){return $this->stri_contain;}
  public function getOnclick(){return $this->stri_onclick;}
  public function getOndblclick(){return $this->stri_ondblclick;}
  public function getOnmouseover(){return $this->stri_onmouseover;}
  public function getOnmouseout(){return $this->stri_onmouseout;}
  public function getStyle(){return $this->stri_style;}
  public function getLi(){return $this->arra_li;}

  
  //**** public method *********************************************************
  public function addContain($contain)
  {
    //permet d'ajouter du contenu a celui déjà existant 
    //@param : $contain => contenu supplémentaire à inserer dans la ul
    //@return : void
    $this->stri_contain.=$contain;
  }  
  
  public function addLi($id,$class,$contain)
  {
    //- gestion du nombre de paramètre
    if(func_num_args()==1)
    {
      $contain=$id;
      $id="";
    }
    
    $obj_li=new li($id,$class,$contain);
    $this->arra_li[]=$obj_li;
    return $obj_li;
  }
  
  public function htmlValue()
  {
    //affiche l'objet ul
    //@return : $stri_res => code HTML du ul
    $stri_contain='';
    foreach( $this->arra_li as $obj_li)
    {
     $stri_contain.=$obj_li->htmlValue();
    }
          
    $stri_final_contain=($this->stri_contain=="")?$stri_contain:$this->stri_contain;

    $stri_res="<ul ";
    
    $stri_res.=((string)$this->stri_id!="")? " id=\"".$this->stri_id."\" " : "";
    $stri_res.=((string)$this->stri_class!="")?" class=\"".$this->stri_class."\" " : "";
    $stri_res.=($this->stri_ondblclick!='')? ' ondblclick="'.$this->stri_ondblclick.'" ' : ''; 
    $stri_res.=($this->stri_onmouseover!='')? ' onmouseover="'.$this->stri_onmouseover.'" ' : '';           
    $stri_res.=($this->stri_onmouseout!='')? ' onmouseout="'.$this->stri_onmouseout.'" ' : '';
    $stri_res.=($this->stri_onclick!='')? ' onclick="'.$this->stri_onclick.'" ' : '';
    $stri_res.=($this->stri_style!='')? ' style="'.$this->stri_style.'" ' : '';

     
    $stri_res.=">".$stri_final_contain."</ul>";
    
    return $stri_res;
  }
 
  
  //**** method for serialization **********************************************
  public function __sleep() 
  {
    //sérialisation de la classe ul
    $this->arra_sauv['id']= $this->stri_id;
    $this->arra_sauv['contain']= $this->stri_contain;

    return array('arra_sauv');
  }

  public function __wakeup() 
  {  
    //désérialisation de la classe ul
    $this->stri_id=$this->arra_sauv['id'];
    $this->stri_contain=$this->arra_sauv['contain'];

    $this->arra_sauv = array();
  }
}
?>
