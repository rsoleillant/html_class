<?php
/*******************************************************************************
Create Date : 22/05/2006
 ----------------------------------------------------------------------
 Class name : table
 Version : 1.1.2
 Author : Rémy Soleillant
 Description : élément html <table >
********************************************************************************/
include_once("tr.class.php");
include_once("td.class.php");
class table extends serialisable{
   
   /*attribute***********************************************/
   protected $stri_bgcolor="";
   protected $stri_align="";
   protected $stri_width="";
   protected $int_border=1;
   protected $int_cellspacing=2;
   protected $int_cellpadding=1;
   protected $stri_background="";
   protected $stri_bordercolor="";
   protected $stri_bordercolorlight="";
   protected $stri_bordercolordark="";
   protected $stri_class="";
   protected $stri_rules="";
   protected $stri_style="";
   protected $stri_id="";
   protected $stri_onmouseover="";
   protected $stri_onmouseup="";
   protected $stri_onclick="";
   protected $stri_onmouseout;
   protected $arra_tr;
      
   
   /* constructor***************************************************************/
   function __construct() {
       $this->arra_tr =null;
   }
  
  
   /*setter*********************************************************************/
  public function setBgcolor($value)
  {
    $this->stri_bgcolor=$value;
  }
  
  public function setRules($value)
  {
    $this->stri_rules=$value;
  }
  
  public function setStyle($value)
  {
    $this->stri_style=$value;
  }
  
  public function setAlign($stri_align)
  {
     $this->stri_align=$stri_align;
     
  }
  
  public function setId($value)
  {$this->stri_id=$value;}
  
  public function setWidth($value)
  {
    $this->stri_width=$value;
  }
  
   public function setBorder($value)
  {
    if(is_numeric ($value))
    {
      $this->int_border=$value;
    }
    else
    {
     echo("<script>alert('int_border doit etre de type entier');</script>");
    }
    
  }
  
  
   public function setCellspacing($value)
  {
    if(is_numeric ($value))
    {
      $this->int_cellspacing=$value;
    }
    else
    {
      echo("<script>alert('int_cellspacing doit etre de type entier');</script>");
    }
    
  }
  
   public function setCellpadding($value)
  {
    if(is_numeric ($value))
    {
      $this->int_cellpadding=$value;
    }
    else
    {
      echo("<script>alert('int_cellpadding doit etre de type entier');</script>");
    }
    
  }
   public function setBackground($value)
  {
    $this->stri_background=$value;
  }
  
   public function setBordercolor($value)
  {
    $this->stri_bordercolor=$value;
  }
  
   public function setBordercolorlight($value)
  {
    $this->stri_bordercolorlight=$value;
  }
  
   public function setBordercolordark($value)
  {
    $this->stri_bordercolordark=$value;
  }
  
   public function setClass($value)
  {
    $this->stri_class=$value;
  }
  
  public function setOnmouseover($value)
  {
    $this->stri_onmouseover=$value;
  }
  
  public function setOnmouseup($value)
  {
    $this->stri_onmouseup=$value;
  }
  
  public function setOnclick($value)
  {
    $this->stri_onclick=$value;
  }
  
  public function setOnmouseout($value)
  {$this->stri_onmouseout=$value;}
  
  public function setTr($arra_tr)
  {$this->arra_tr=$arra_tr;}
  /*getter**********************************************************************/
  
  public function getBgcolor()
  {return $this->stri_bgcolor;}
  
  public function getRules()
  {return $this->stri_rules;}
  
  public function getStyle()
  {return $this->stri_style;}
   
  public function getAlign()
  {return $this->stri_align;}
  
  public function getId()
  {return $this->stri_id;}
  
  public function getWidth()
  {return $this->stri_width;}
  
  public function getBorder()
  {return $this->int_border;}
  
  public function getCellspacing()
  {return $this->int_cellspacing;}
   
  public function getCellpadding()
  {return $this->int_cellpadding;}
  
  public function getBackground()
  {return $this->stri_background;}
  
  public function getBordercolor()
  {return $this->stri_bordercolor;}
  
  public function getBordercolorlight()
  {return $this->stri_bordercolorlight;}
  
  public function getBordercolordark()
  {return $this->stri_bordercolordark;}
  
  public function getClass()
  {return $this->stri_class;}
  
  public function getTr()
  {return $this->arra_tr;}
 
  public function getOnmouseover()
  {return $this->stri_onmouseover;}
  
  public function getOnmouseup()
  {return $this->stri_onmouseup;}
  
  public function getOnclick()
  {return $this->stri_onclick;}
 
  public function getOnmouseout()
  {return $this->stri_onmouseout;}
 
  public function getIemeTr($int)
  {return $this->arra_tr[$int];}
 
 /*return an td object*/
  public function getCellule($line,$col)
  {$tab=$this->arra_tr[$line];
   if(!empty($tab))
   {$res=$tab->getIemeTd($col);}
   return $res;
  }
 /* method for serialization **************************************************/
   public function __sleep() {
    $this->arra_sauv['bgcolor']= $this->stri_bgcolor;
    $this->arra_sauv['align']= $this->stri_align;
    $this->arra_sauv['width']= $this->stri_width;
    $this->arra_sauv['border']= $this->int_border;
    $this->arra_sauv['cellspacing']= $this->int_cellspacing;
    $this->arra_sauv['cellpadding']= $this->int_cellpadding;
    $this->arra_sauv['background']= $this->stri_background;
    $this->arra_sauv['bordercolor']= $this->stri_bordercolor;
    $this->arra_sauv['bordercolorlight']= $this->stri_bordercolorlight;
    $this->arra_sauv['bordercolordark']= $this->stri_bordercolordark;
    $this->arra_sauv['id']= $this->stri_id;
    $this->arra_sauv['class']= $this->stri_class;
    $this->arra_sauv['rules']= $this->stri_rules;
    $this->arra_sauv['style']= $this->stri_style;
    $this->arra_sauv['onmouseout']= $this->stri_onmouseout;
    
    $arra_temp=array();
    foreach( $this->arra_tr as $key=>$obj_tr)
    {$arra_temp[$key]=serialize($obj_tr);}
    $this->arra_sauv['arra_tr']= $arra_temp;
    

     
     return array('arra_sauv');
   }
   
  public function __wakeup() {   
    $this->stri_bgcolor= $this->arra_sauv['bgcolor'];
    $this->stri_align= $this->arra_sauv['align'];
    $this->stri_width= $this->arra_sauv['width'];
    $this->int_border= $this->arra_sauv['border'];
    $this->int_cellspacing= $this->arra_sauv['cellspacing'];
    $this->int_cellpadding= $this->arra_sauv['cellpadding'];
    $this->stri_background= $this->arra_sauv['background'];
    $this->stri_bordercolor= $this->arra_sauv['bordercolor'];
    $this->stri_bordercolorlight= $this->arra_sauv['bordercolorlight'];
    $this->stri_bordercolordark= $this->arra_sauv['bordercolordark'];
    $this->stri_id= $this->arra_sauv['id'];
    $this->stri_class= $this->arra_sauv['class'];
    $this->stri_rules= $this->arra_sauv['rules'];
    $this->stri_style= $this->arra_sauv['style'];
    $this->stri_onmouseout= $this->arra_sauv['onmouseout'];
    
    $arra_temp=array();
    foreach($this->arra_sauv['arra_tr'] as $key=>$stri_tr)
    {$arra_temp[$key]=unserialize($stri_tr);}
    $this->arra_tr= $arra_temp;
    
    $this->arra_sauv = array();
     
   }
  
  
  /*other method****************************************************************/
  public function htmlValue()
  {
    $stri_res="<table ";
    // START - EM MODIF 10-07-2007
    $stri_res.=($this->stri_class!="")? " class=\"".$this->stri_class."\"" : "";
    $stri_res.=($this->stri_style!="")? " style=\"".$this->stri_style."\"" : "";
    $stri_res.=($this->stri_bgcolor!="")? " bgcolor=\"".$this->stri_bgcolor."\"" : "";
    $stri_res.=($this->stri_align!="")? " align=\"".$this->stri_align."\"" : "";
    $stri_res.=((string)$this->stri_width!="")? " width=\"".$this->stri_width."\"" : "";
    $stri_res.=" cellspacing=\"".$this->int_cellspacing."\"";
    $stri_res.=" cellpadding=\"".$this->int_cellpadding."\"";
    $stri_res.=($this->stri_background!="")? " background=\"".$this->stri_background."\"" : "";              
    $stri_res.=(!empty($this->stri_id))?" id=\"".$this->stri_id."\" ":"";
    $stri_res.=($this->stri_onmouseover!="")? " onmouseover=\"".$this->stri_onmouseover."\" " : "";
    $stri_res.=($this->stri_onmouseup!="")? " onmouseup=\"".$this->stri_onmouseup."\" " : "";
    $stri_res.=($this->stri_onclick!="")? " onclick=\"".$this->stri_onclick."\" " : "";
    $stri_res.=($this->stri_onmouseout!="")? " onmouseout=\"".$this->stri_onmouseout."\" " : "";
   
    
    // END - EM MODIF 10-07-2007
    if($this->int_border>0 and $this->stri_style=="")
    {
      $stri_res.=" border=\"".$this->int_border."\" ";
      //$stri_res.=($this->int_border!="")? " border=\"".$this->int_border."\"" : "";
      $stri_res.=($this->stri_bordercolor!="")? " bordercolor=\"".$this->stri_bordercolor."\"" : "";
      $stri_res.=($this->stri_bordercolorlight!="")? " bordercolorlight=\"".$this->stri_bordercolorlight."\"" : "";
      $stri_res.=($this->stri_bordercolordark!="")? " bordercolordark=\"".$this->stri_bordercolordark."\"" : "";
    }
    $stri_res.=($this->stri_rules!="")? "rules=\"".$this->stri_rules."\"" : "";          
    $stri_res.=" >";
   // $stri_res.="<tbody>";
    foreach($this->arra_tr as $obj_tr)
    {   
     $stri_res.=$obj_tr->htmlValue();
    }
    /*$nbr_tr=count($this->arra_tr);
    for($i=0;$i<$nbr_tr;$i++)
    {
    $stri_res.=$this->arra_tr[$i]->htmlValue();
    }*/
    //$stri_res.="</tbody>";
    $stri_res.=" </table>";
    return $stri_res;
  }
  public function addTr()
  {
    $i=count($this->arra_tr);
    $this->arra_tr[$i] = new tr();
    return $this->arra_tr[$i];
  }
  public function insertTr($obj_tr)
  {
    $i=count($this->arra_tr);
    $this->arra_tr[$i]=$obj_tr; 
  }
 /* return an object table*/ 
  public function makeQuerryToHtmlTable($req,$obj="",$func="",$start_display=0)
  {
    $req->execute();
    for($i=0;$i<$req->getNumberResult();$i++)
    {
      $obj_tr=new tr();
      $temp=$req->getIemeResult($i);
      for($j=$start_display;$j<$req->getNumberCol();$j++)
      {     
        if(empty($obj))
        {$obj_tr->addTd($temp[$j]);}
        else
        {$obj_tr->addTd($obj->$func($temp[$j]));}
      }
    $this->insertTr($obj_tr);
   }
   return $this;  
  } 
  
  public function makeArrayToHtmlTable($arra_value)
  {
    foreach($arra_value as $arra_first_dim)
    {
      $obj_tr=new tr();
      
      foreach($arra_first_dim as $arra_second_dim)
      {
         $obj_tr->addTd($arra_second_dim);
      }
      $this->insertTr($obj_tr);
    }
  } 
  
  
  public function alernateColor($int_deb,$color1,$color2,$int_step=1)
  {/* permet d'alterner la couleur des lignes du tableau entre $color1 et 
    $color2 à partir de la ligne $int_deb. L'alternance se fait tout les $int_step
    */
   
    $int_alternate=0;
    $stri_color=$color1;
    for($i=$int_deb;$i<count($this->arra_tr);$i++)
    {
      
      if($int_alternate==$int_step)
      {
        $stri_color=($stri_color==$color1)?$color2:$color1;
        $int_alternate=0;
      }
     
      $this->arra_tr[$i]->setBgcolor($stri_color);
     
     $int_alternate++; 
    }  
  }
  
  //::Modifier par Y.M::
  public function makeTrSelectionable($int_deb,$onclick,$color,$arra_data,$mode=0)
  {//arra_data: tableau conteant une donnée à transmettre par url pour chaque ligne
    for($i=$int_deb;$i<count($this->arra_tr);$i++)
    {
      
      $tr=$this->arra_tr[$i];
      switch($mode){
        case 0://Fait une redirection sur le Onclick:
          $url_onclick=$onclick;
           if($url_onclick!="#")
              {$tr->setOnclick("location.href='".$url_onclick.$arra_data[$i-$int_deb]."' ");}
           break;
        case 1://Permet d'insérer du JS:
          $tr->setOnclick($onclick);
          break;
        default:
            echo "PROBLEME DANS LA CLASS TABLE: Demander à Yannick!!! -_-'";
            break;
      }
      
      $tr->setOnmouseover("this.style.cursor='pointer'; this.bgColor = '$color';");
      $tr->setOnmouseout("this.bgColor = '".$tr->getBgcolor()."'");
      
    }
  
  }
  //::FIN::
  
  public function noWrapForAllTd()
  {
   foreach($this->arra_tr as $tr)
   {
     $arra_td=$tr->getTd();
     foreach($arra_td as $td)
     {$td->setNoWrap(true);}
   }
  }
}

?>
