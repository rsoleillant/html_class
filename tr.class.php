<?php
/*******************************************************************************
Create Date : 22/05/2006
Update Date : 25/01/2008 EM - ajout fonction getNumberTd()
 ----------------------------------------------------------------------
 Class name : tr
 Version : 1.2
 Author : Rémy Soleillant
 Description : élément html <tr>
********************************************************************************/

class tr extends serialisable
{   
  //**** attribute *************************************************************
  protected $stri_bgcolor="";
  protected $stri_align="";
  protected $stri_valign="";
  protected $int_height=""; //20
  protected $stri_onmouseover;
  protected $stri_onclick;
  protected $stri_ondblclick="";
  protected $stri_onmouseout;
  protected $stri_id="";
  protected $stri_style="";
  protected $arra_td;
  protected $stri_class="";
  protected $stri_title="";
  protected $arra_data;                 //attribut data  

  public $arra_sauv;

  
  //**** constructor ***********************************************************
  function __construct() 
  {
    $this->arra_td=null;
  }
  
  
  //**** setter ****************************************************************
  public function setBgcolor($color)
  {$this->stri_bgcolor=$color;}
  
  public function setOnmouseover($value)
  {$this->stri_onmouseover=$value;}
  
  public function setOnmouseout($value)
  {$this->stri_onmouseout=$value;}
  
  public function setOnclick($value)
  {$this->stri_onclick=$value;}
  
   public function setOndblclick($value)
  {
    $this->stri_ondblclick=$value;
  }
  
  
  public function setHeight($int)
  {
    if(is_numeric ($int))
    {
      $this->int_height=$int;
    }
    else
    {
      echo("<script>alert('int_height doit etre de type entier');</script>");
    }
  }
  
  public function setValign($stri_valign)
  {
    if(($stri_valign=='top')||($stri_valign=='middle')||($stri_valign=='bottom')||($stri_valign=='baseline'))
    {
      $this->stri_valign=$stri_valign;
    }
    else
    {
      echo("<script>alert('type  stri_valign incorect');</script>");
    }
  }
  
  public function setAlign($stri_align)
  {
    if(($stri_align=='left')||($stri_align=='right')||($stri_align=='center')||($stri_align=='justify')||($stri_align=='char '))
    {$this->stri_align=$stri_align;}
    else
    {echo("<script>alert('type  stri_align incorect');</script>");}
  }
  
  public function setClass($value)
  {
    $this->stri_class=$value;
  }
  public function setTitle($value)
  {
    $this->stri_title=$value;
  }
  
  public function setId($value)
  {$this->stri_id=$value;}
  
  public function setStyle($value)
  {$this->stri_style=$value;}
  
   //Permet d'obtenir le tr ou td portant l'id passé en paramètre
  public function getElementById($stri_id)
  {
   if($this->stri_id==$stri_id)//si l'élément cherché est la table
   {return $this;}
   
   foreach($this->arra_td as $obj_td)
   {
    $mixed_res=$obj_td->getElementById($stri_id);
    if(is_object($mixed_res))
    {return $mixed_res;}
    
   }
   
   return false;
  }
  public function setTd($value){$this->arra_td=$value;}
  public function addData($stri_name,$value){$this->arra_data[$stri_name]=$value; } 
  //**** getter ****************************************************************
  public function getBgcolor()
  {return $this->stri_bgcolor;}
  
  public function getHeight()
  {return $this->int_height;}
  
  public function getAlign()
  {return $this->stri_align;}
  
  public function getValign()
  {return $this->stri_valign;}
  
  public function getTd() 
  {return $this->arra_td;}
  
  public function getIemeTd($int) 
  {return $this->arra_td[$int];}
  
  public function getId()
  {return $this->stri_id;}
  
  public function getStyle()
  {return $this->stri_style;}
  
   public function getClass()
  {return $this->stri_class;}
  
  public function getTitle()
  {return $this->stri_title;}
  
  public function getOndblclick()
  {return $this->stri_ondblclick;}
  //**** public method *********************************************************
  /**
   * @return td
   */
  public function addTd($value,$is_object=false)
  {
    //ajoute une cellule à une ligne (add cell to row)
    //@param : $value => contenu de la cellule (cell's value)
    //@param : $is_object => 
    //@return : [object] la cellule (cell)
   if((is_object($value))&&(get_class($value)=="td" ))//si on ajoute directement un objet td
   {
      $i=count($this->arra_td); 
      $this->arra_td[$i]=$value;
      return $this->arra_td[$i];
   }  
   
    $i=count($this->arra_td);
    $this->arra_td[$i]=new td($value,$is_object);
    return $this->arra_td[$i];
  }
  
  public function addTdBeforeV1($value,$int_indice=0)
  {
   //- découpe du tableau de td
   $arra_td_part1=array_slice($this->arra_td,0, $int_indice);
   $arra_td_part2=array_slice($this->arra_td,$int_indice); 
   
   //- ajout d'un nouveau td
   $arra_td_part1[]=new td($value);
   
   //- fusion des tableau
   $arra_td=array_merge($arra_td_part1,$arra_td_part2);
   
   $this->arra_td=$arra_td;
  }
  
  public function addTdBefore($value,$int_indice=0)
  {
   //- découpe du tableau de td
   $arra_td_part1=array_slice($this->arra_td,0, $int_indice);
   $arra_td_part2=array_slice($this->arra_td,$int_indice); 
   
   //- ajout d'un nouveau td
   $obj_td=new td($value);
   $arra_td_part1[]= $obj_td;
   
   //- fusion des tableau
   $arra_td=array_merge($arra_td_part1,$arra_td_part2);
   
   $this->arra_td=$arra_td;
   
   return $obj_td;
  }
  
  public function deleteTd($int_td)
  {
    //supprime une cellule
    //@param : $int_td => le numéro de cellule à supprimer
    //@return : bool : si la cellule à bien été supprimée
  
    if(isset($this->arra_td[$int_td]))//si la cellule existe
    { 
      
      //on remet la numérotation des td d'aplomb
      $int_nb_td=count($this->arra_td);
      for($i=$int_td+1;$i<$int_nb_td;$i++)
      {
       $this->arra_td[$i-1]=$this->arra_td[$i];
      }
      unset($this->arra_td[$int_nb_td-1]);
      return true;
    }
    
    return false;
  }
  
  
  public function getNumberTd()
  {
    //permet de connaitre le nombre de cellule que contient la ligne
    //@return : $int_nb => le nombre de cellule (cell's number)
    
    $int_nb=count($this->arra_td);
    
    return $int_nb;
  }
  
  public function htmlValue()
  {
      //- construction de l'attribut data
    $arra_data=array();
    foreach($this->arra_data as $stri_name=>$stri_value)
    {
      $arra_data[]='data-'.$stri_name.'="'.$stri_value.'"';
    }
    $stri_data=implode(' ', $arra_data) ;
 
 
    //affiche la ligne (post row)
    //@return : $stri_res => ligne en html (html row)
    
    $stri_res="<tr ";
    // START - EM MODIF 10-07-2007 
      //- ajout de l'attribut data 
    $stri_res.=' '.$stri_data.' ';   
    $stri_res.=(!empty($this->stri_id))?" id=\"".$this->stri_id."\" ":"";
    $stri_res.=($this->stri_class!="")? " class=\"".$this->stri_class."\"" : "";
    $stri_res.=($this->stri_title!="")? " title=\"".$this->stri_title."\"" : "";
    $stri_res.=(!empty($this->stri_style))?" style=\"".$this->stri_style."\" ":"";  
    //$stri_res.=" height=\"".$this->int_height."\" ";
    $stri_res.=($this->int_height!="")? " height=\"".$this->int_height."\" " : "";
    $stri_res.=($this->stri_bgcolor!="")? " bgcolor=\"".$this->stri_bgcolor."\" " : "";
    $stri_res.=($this->stri_valign!="")? " valign=\"".$this->stri_valign."\" " : "";
    $stri_res.=($this->stri_align!="")? " align=\"".$this->stri_align."\" " : "";         
    $stri_res.=($this->stri_onclick!="")? " onclick=\"".$this->stri_onclick."\" " : "";
    $stri_res.=($this->stri_onmouseover!="")? " onmouseover=\"".$this->stri_onmouseover."\" " : "";           
    $stri_res.=($this->stri_onmouseout!="")? " onmouseout=\"".$this->stri_onmouseout."\" " : "";
    $stri_res.=($this->stri_ondblclick!='')? ' ondblclick="'.$this->stri_ondblclick.'" ' : ''; 
    $stri_res.=">";
    // END - EM MODIF 10-07-2007
    
    $nbr_td=$this->getNumberTd();
    for($i=0;$i<$nbr_td;$i++)
    {
      $stri_res.=$this->arra_td[$i]->htmlValue();
    }
    
    $stri_res.=" </tr>";
    return $stri_res;
  }
  
  
  //**** method for serialization **********************************************
 /* public function __sleep() 
  {
    $this->arra_sauv['bgcolor=""']= $this->stri_bgcolor;
    $this->arra_sauv['align=""']= $this->stri_align;
    $this->arra_sauv['valign=""']= $this->stri_valign;
    $this->arra_sauv['height']= $this->int_height;
    $this->arra_sauv['onmouseover']= $this->stri_onmouseover;
    $this->arra_sauv['onclick']= $this->stri_onclick;
    $this->arra_sauv['onmouseout']= $this->stri_onmouseout;
    $this->arra_sauv['id']= $this->stri_id;
    $this->arra_sauv['style']= $this->stri_style;
    
    $arra_temp=array();
    foreach( $this->arra_td as $key=>$obj_td)
    {$arra_temp[$key]=serialize($obj_td);}
    $this->arra_sauv['arra_td']= $arra_temp;
    
    return array('arra_sauv');
  }
   
  public function __wakeup() 
  {
    $this->stri_bgcolor= $this->arra_sauv['bgcolor'];
    $this->stri_align= $this->arra_sauv['align'];
    $this->stri_valign= $this->arra_sauv['valign'];
    $this->int_height= $this->arra_sauv['height'];
    $this->stri_onmouseover= $this->arra_sauv['onmouseover'];
    $this->stri_onclick= $this->arra_sauv['onclick'];
    $this->stri_onmouseout= $this->arra_sauv['onmouseout'];
    $this->stri_id= $this->arra_sauv['id'];
    $this->stri_style= $this->arra_sauv['style'];
    
    $arra_temp=array();
    foreach($this->arra_sauv['arra_td'] as $key=>$stri_td)
    {$arra_temp[$key]=unserialize($stri_td);}
    $this->arra_td= $arra_temp;
    
    $this->arra_sauv = array();
  } */
  
  //Other method:
  //add by Y.M:
  public function  alternateColor($int_deb,$color1,$color2,$int_step=1)
  {/* permet d'alterner la couleur des colonnes d'une ligne (addTd) entre $color1 et 
    $color2 à partir de la ligne $int_deb. L'alternance se fait tout les $int_step
    */
   
    $int_alternate=0;
    $stri_color=$color1;
    for($i=$int_deb;$i<count($this->arra_td);$i++)
    {
      
      if($int_alternate==$int_step)
      {
        $stri_color=($stri_color==$color1)?$color2:$color1;
        $int_alternate=0;
      }
     
      $this->arra_td[$i]->setBgcolor($stri_color);
     
     $int_alternate++; 
    }  
  }
  
  public function makeActionOnTr($onclick,$color){
  
  $this->stri_onclick=$onclick;
  $this->stri_onmouseover="this.style.cursor='pointer'; this.bgColor = '$color';";
  $this->stri_onmouseout="this.bgColor = '".$this->stri_bgcolor."';";
  
  }
  
  
  
  
}
?>
