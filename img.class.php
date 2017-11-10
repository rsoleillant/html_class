<?php
/*******************************************************************************
Create Date : 22/05/2006
 ----------------------------------------------------------------------
 Class name : img
 Version : 1.1
 Author : Rémy Soleillant
 Description : élément html <img>
********************************************************************************/
class img {
   
   /*attribute***********************************************/
   
   protected $stri_src="";
   protected $stri_alt="";  
   protected $stri_height="";  
   protected $stri_width="";
   protected $int_border="0";
   protected $stri_name="";
   protected $stri_onclick="";
   protected $stri_onmouseover="";
   protected $stri_onmouseout="";   
   protected $stri_onmouseup="";
   protected $stri_style="";
   protected $stri_title="";
   protected $stri_id="";
   protected $int_tabindex="";
   protected $stri_class;
   protected $arra_data;              //attribut data  
   public $arra_sauv=array();         //tableau pour la sérialisation
  
  /* constructor***************************************************************/
   function __construct($src) {
       $this->stri_src=$src;
   }
  
   /*setter*********************************************************************/
 
  public function setAlt($value)
  {
    $this->stri_alt=$value;
  }
  
  public function setOnmouseover($value)
  {
    $this->stri_onmouseover=$value;
  }

  public function setOnmouseout($value)
  {
    $this->stri_onmouseout=$value;
  }

  public function setOnmouseup($value)
  {
    $this->stri_onmouseup=$value;
  }
  
  public function setOnclick($value)
  {
    $this->stri_onclick=$value;
  }
  
  public function setStyle($value)
  {
    $this->stri_style=$value;
  }
  
  public function setTitle($value)
  {
    $this->stri_title=$value;
  }
  
  public function setId($value)
  {
    $this->stri_id=$value;
  }
  
  public function setTabIndex($num)
  {$this->int_tabindex=$num;}
  
   public function setName($value)
  {
    $this->stri_name=$value;
  } 
  
  public function setHeight($value)
  {
    $this->stri_height=$value;
  }
  
  public function setWidth($value)
  {
    $this->stri_width=$value;
  }
  
  public function setBorder($int)
  {
    if(is_numeric ($int))
    {
      $this->int_border;
    }
    else
    {
      echo("<script>alert('int_colspan doit etre de type entier');</script>");
    }
  }
  
  public function setClass($value)
  {
    $this->stri_class=$value;
  }  

  public function setSrc($value)
  {
    $this->stri_src=$value;
  }  
 
  
  
  /*getter**********************************************************************/
  public function getAlt()
  {return $this->stri_alt;}
  
  public function getOnmouseover()
  {return $this->stri_onmouseover;}
  
  public function getOnmouseup()
  {return $this->stri_onmouseup;}

  public function getOnmouseout()
  {return $this->stri_onmouseout;}
  
  public function getTitle()
  {return $this->stri_title;}
  
  public function getSrc()
  {return $this->stri_src;}
  
  public function getHeight()
  {return $this->stri_height;}
   
  public function getWidth()
  {return $this->stri_width;}
   
  public function getBorder()
  {return $this->int_border;} 
  
  public function getName()
  {return $this->stri_name;}
  
   public function getStyle()
  {return $this->stri_style;} 
  
  public function getId()
  {return $this->stri_id;}
  
  public function getOnclick()
  {return $this->stri_onclick;}

  public function getClass()
  {return $this->stri_class;}
  
  /*other method****************************************************************/
  public function addData($stri_name,$value){$this->arra_data[$stri_name]=$value;}
  
  public function htmlValue()
  {
    //- construction de l'attribut data
    $arra_data=array();
    foreach($this->arra_data as $stri_name=>$stri_value)
    {
      $arra_data[]='data-'.$stri_name.'="'.$stri_value.'"';
    }
    $stri_data=implode(' ', $arra_data) ;
  
    
    //Gestion du cache navigateur web 
    $int_num_version = __CACHE_CONTROL_VERSION;
    
    
  
  $stri_res="<img ";
   if($this->stri_height!="")
  {$stri_res=$stri_res." height=\"".$this->stri_height."\" ";}
  if($this->stri_width!="")
  {$stri_res=$stri_res." width=\"".$this->stri_width."\" ";}
  $stri_res.=($this->stri_src!="")? " src=\"".$this->stri_src."\" " : "";
  
  
  //$stri_res.=($this->stri_src!="")? ' src="'.$this->stri_src.'?version='.$int_num_version.'" ' : "";
  
  
  $stri_res.=((string)$this->stri_alt!="")? " alt=\"".$this->stri_alt."\" " : "";
  $stri_res.=((string)$this->stri_id!="")? " id=\"".$this->stri_id."\" " : "";
  $stri_res.=((string)$this->int_tabindex!="")?" tabindex=\"".$this->int_tabindex."\" " : "";
  $stri_res.=($this->stri_class!="")?" class=\"".$this->stri_class."\" ":"";
  $stri_res.=((string)$this->stri_title!="")? " title=\"".$this->stri_title."\" " : "";
  $stri_res.=($this->stri_name!="")? " name=\"".$this->stri_name."\" " : "";
  $stri_res.=((string)$this->int_border!="")? " border=\"".$this->int_border."\" " : "";          
  $stri_res.=($this->stri_style!="")? " style=\"".$this->stri_style."\" " : "";
  $stri_res.=($this->stri_onclick!="")? " onclick=\"".$this->stri_onclick."\" " : "";
  $stri_res.=($this->stri_onmouseover!="")? " onmouseover=\"".$this->stri_onmouseover."\" " : "";
  $stri_res.=($this->stri_onmouseout!="")? " onmouseout=\"".$this->stri_onmouseout."\" " : "";
  $stri_res.=($this->stri_onmouseup!="")? " onmouseup=\"".$this->stri_onmouseup."\" " : "";
  $stri_res.=$stri_data;
  
  $stri_res.=">";
  
  return $stri_res;
  }
 
  public function serialise()
  {
    session_start();
    $_SESSION['form']=serialize($this);
    return serialize($this);
  }
  
  public function __sleep() 
  {
    //sérialisation de la classe img
    $this->arra_sauv['src']= $this->stri_src;
    $this->arra_sauv['alt']= $this->stri_alt;
    $this->arra_sauv['height']= $this->stri_height;
    $this->arra_sauv['width']= $this->stri_width;
    $this->arra_sauv['border']= $this->int_border;
    $this->arra_sauv['name']= $this->stri_name;
    $this->arra_sauv['onclick']= $this->stri_onclick;
    $this->arra_sauv['onmouseover']= $this->stri_onmouseover;
    $this->arra_sauv['onmouseout']= $this->stri_onmouseout;
    $this->arra_sauv['onmouseup']= $this->stri_onmouseup;
    $this->arra_sauv['style']= $this->stri_style;
    $this->arra_sauv['title']= $this->stri_title;
    $this->arra_sauv['id']= $this->stri_id;
    $this->arra_sauv['tabindex']= $this->int_tabindex;
    return array('arra_sauv');
  }

  public function __wakeup() 
  {  
    //désérialisation de la classe img
    $this->stri_src=$this->arra_sauv['src'];
    $this->stri_alt=$this->arra_sauv['alt'];
    $this->stri_height=$this->arra_sauv['height'];
    $this->stri_width=$this->arra_sauv['width'];
    $this->int_border=$this->arra_sauv['border'];
    $this->stri_name=$this->arra_sauv['name'];
    $this->stri_onclick=$this->arra_sauv['onclick'];
    $this->stri_onmouseover=$this->arra_sauv['onmouseover'];
    $this->stri_onmouseout=$this->arra_sauv['onmouseover'];
    $this->arra_sauv['onmouseup']= $this->stri_onmouseup;    
    $this->stri_style=$this->arra_sauv['style'];
    $this->stri_title=$this->arra_sauv['title'];
    $this->stri_id=$this->arra_sauv['id'];
    $this->int_tabindex=$this->arra_sauv['tabindex'];
    
    $this->arra_sauv = array();
  }

}

?>
