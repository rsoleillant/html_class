<?php
/*******************************************************************************
Create Date : 17/08/2009
 ----------------------------------------------------------------------
 Class name : iframe
 Version : 1.0
 Author : Yannick MARION
 Description : élément html <iframe>
********************************************************************************/
class iframe {
   
  //**** attribute ************************************************************
  
  protected $stri_id="";                //Identifiant de l'iframe
  protected $stri_name="";              //nom de la balise iframe
  protected $stri_src="";               //source du contenu de l'iframe
  protected $bool_frameborder=0;        //active/désacrive la bordure entre les cadres (0,1)
  protected $int_marginwidth=0;         //définit l'espacement horizontal dans le cadre entre la bordure et le contenu 
  protected $int_marginheight=0;        //définit l'espacement vertical dans le cadre entre la bordure et le contenu
  protected $stri_scrolling="auto";     //détermine la présence d'une barre de défilement (auto, yes, no )
  protected $stri_align="left";         //DEPRECIER. Contrôle l'alignement ( left, center, right, justify )
  protected $int_height=20;             //hauteur du cadre
  protected $int_width=10;              //largeur du cadre
  protected $stri_style="";             //Définit le style css de l'iframe
 
 
  //**** constructor ***********************************************************
  function __construct($name,$src) 
  {
    //construit l'objet iframe
    //@param : $name => nom de la iframe
    //@param : $src => source du contenu
    //@return : void
    $this->stri_name=$name;
    $this->stri_src=$src;
  }
  
  
  
  //**** setter ****************************************************************
  public function setId($value){$this->stri_id=$value;}			
  public function setName($value){$this->stri_name=$value;}				
  public function setSrc($value){$this->stri_src=$value;}		
  		
  public function setFrameBorder($bool){
  if($bool!==0 && $bool!==1)
    {
      $this->bool_frameborder=$bool;
    }
    else
    {
      echo("<script>alert('bool_frameborder doit etre 0 ou 1');</script>");
    }
  }		
  
  public function setMarginwidth($value){
  if(is_numeric ($value))
    {
      $this->int_marginwidth=$value;
    }
    else
    {
     echo("<script>alert('int_marginwidth doit etre de type entier');</script>");
    }
  }
  
  public function setMarginheight($value){
  if(is_numeric ($value))
    {
      $this->int_marginheight=$value;
    }
    else
    {
     echo("<script>alert('int_marginheight doit etre de type entier');</script>");
    }
  }
  
  public function setScrolling($value){$this->stri_scrolling=$value;}
  public function setAlign($value){$this->stri_align=$value;}
  
  public function setHeight($value){
  if(is_numeric ($value))
    {
      $this->int_height=$value;
    }
    else
    {
     echo("<script>alert('int_height doit etre de type entier');</script>");
    }
  }
  public function setWidth($value){
  if(is_numeric ($value))
    {
      $this->int_width=$value;
    }
    else
    {
     echo("<script>alert('int_width doit etre de type entier');</script>");
    }
  }		
  public function setStyle($value){$this->stri_style=$value;}

  
  //**** getter ****************************************************************
  public function getId(){return $this->stri_id;}
  public function getName(){return $this->stri_name;}
  public function getSrc(){return $this->stri_src;}
  public function getFrameBorder(){return $this->bool_frameborder;}
  public function getMarginwidth(){return $this->int_marginwidth;}
  public function getMarginheight(){return $this->int_marginheight;}
  public function getScrolling(){return $this->stri_scrolling;}
  public function getAlign(){return $this->stri_align;}
  public function getHeight(){return $this->int_height;}
  public function getWidth(){return $this->int_width;}
  
  

  
  
  
  public function htmlValue()
  {
    //affiche l'objet iframe
    //@return : $stri_res => code HTML du iframe
    
    $stri_res="<iframe ";
    $stri_res.=((string)$this->stri_id!="")? " id=\"".$this->stri_id."\" " : "";
    $stri_res.=((string)$this->stri_name!="")? " name=\"".$this->stri_name."\" " : "";
    $stri_res.=((string)$this->stri_src!="")?" src=\"".$this->stri_src."\" " : "";
    $stri_res.=($this->bool_frameborder!="")?" frameborder=\"".$this->bool_frameborder."\" " : "";
    $stri_res.=((int)$this->int_marginwidth!="")?" marginwidth=\"".$this->int_marginwidth."\" " : "";
    $stri_res.=((int)$this->int_marginheight!="")?" marginheight=\"".$this->int_marginheight."\" " : "";
    $stri_res.=((string)$this->stri_scrolling!="")?" scrolling=\"".$this->stri_scrolling."\" " : "";
    $stri_res.=((string)$this->stri_align!="")?" align=\"".$this->stri_align."\" " : "";
    $stri_res.=((int)$this->int_height!="")?" height=\"".$this->int_height."\" " : "";
    $stri_res.=((int)$this->int_width!="")?" width=\"".$this->int_width."\" " : "";
    $stri_res.=((string)$this->stri_style!="")?" style=\"".$this->stri_style.";\" " : "";
    $stri_res.="></iframe>";
    
    
    return $stri_res;
  }
 
  
  //Les méthodes de sérialisation n'ont pas été écrite volontairement...
}
?>
