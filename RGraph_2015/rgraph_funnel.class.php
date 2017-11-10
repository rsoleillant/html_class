<?php
/*******************************************************************************
Create Date : 24/01/2013
 ----------------------------------------------------------------------
 Class name : RGraph_funnel
 Version : 1.0
 Author : Rémy Soleillant
 Description :  Permet de représenter un graphe de type funnel dans la bibliothèque RGraph
                http://www.rgraph.net/docs/funnel.html
 
********************************************************************************/
class rgraph_funnel{
   
  //**** attribute ************************************************************
  protected $stri_id;
  protected $int_width;
  protected $int_height; 
 
  protected $stri_title;
  protected $stri_halign;
  protected $arra_data;
  protected $arra_labels;
  protected $arra_tooltips;
  protected $arra_colors;
  protected $bool_sticks;
  protected $bool_boxed;
  protected $bool_shadow;
  
  //**** constructor ***********************************************************
 /*************************************************************
 *
 * parametres : 
 * retour : objet de la classe 
 *                        
 **************************************************************/    
  function __construct($stri_id,$arra_data) 
  { 
   $this->stri_id=$stri_id;
   $this->int_width=500;
   $this->int_heigth=280; 
   $this->arra_data=$arra_data;
   
   $this->bool_sticks=false;
   $this->bool_boxed=false;
   
  }
 
  //**** setter ****************************************************************
  public function setId($value){$this->stri_id=$value;}
  public function setWidth($value){$this->int_width=$value;}
  public function setHeight($value){$this->int_height=$value;}
  public function setData($value){$this->arra_data=$value;}
  public function setLabels($value){$this->arra_labels=$value;}
  public function setTitle($value){$this->stri_title=$value;}
  public function setTooltips($value){$this->arra_tooltips=$value;}
  public function setColors($value){$this->arra_colors=$value;}
  public function setSticks($value){$this->bool_sticks=$value;}
  public function setBoxed($value){$this->bool_boxed=$value;}
  public function setShadow($value){$this->bool_shadow=$value;}
  public function setHalign($value){$this->stri_halign=$value;}

  
  //**** getter ****************************************************************
  public function getId(){return $this->stri_id;}
  public function getWidth(){return $this->int_width;}
  public function getHeight(){return $this->int_height;}
  public function getData(){return $this->arra_data;}
  public function getLabels(){return $this->arra_labels;}
  public function getTitle(){return $this->stri_title;}
  public function getTooltips(){return $this->arra_tooltips;}
  public function getColors(){return $this->arra_colors;}
  public function getSticks(){return $this->bool_sticks;}
  public function getBoxed(){return $this->bool_boxed;}
  public function getShadow(){return $this->bool_shadow;}
  public function getHalign(){return $this->stri_halign;}

   
   //**** public method *********************************************************
  
  
 /*************************************************************
 *
 * parametres : string : l'identifiant de l'instance
 * retour : objet de la classe calendrier_projet   
 *                        
 **************************************************************/    
  public function htmlValue()
  {  
  	$obj_javascripter=new javascripter();
  
    $stri_config="";
    $stri_config.=($this->stri_title!="")?"funnel.Set('chart.title', '".$this->stri_title."');":"";
    $stri_config.=($this->stri_halign!="")?"funnel.Set('chart.text.halign', '".$this->stri_halign."');":"";   
    $stri_config.=(count($this->arra_labels)>0)?"funnel.Set('chart.labels', ['".join("','",  $this->arra_labels)."']);":"";
    $stri_config.=(count($this->arra_tooltips)>0)?"funnel.Set('chart.tooltips', ['".join("','",  $this->arra_tooltips)."']);":"";
    $stri_config.=(count($this->arra_colors)>0)?"funnel.Set('chart.colors', ['".join("','",  $this->arra_colors)."']);":"";     
    $stri_config.=($this->bool_boxed)?"funnel.Set('chart.text.boxed',true);":"funnel.Set('chart.text.boxed',false);";
    $stri_config.=($this->bool_shadow)?"funnel.Set('chart.shadow',true);":"funnel.Set('chart.shadow',false);";
   
       
  	$obj_javascripter->addFunction("
  	$(function()
  	{
  	     // Create the Funnel chart. Note the the values start at the maximum and decrease to the minimum.
          var funnel = new RGraph.Funnel('".$this->stri_id."', [".join(", ",  $this->arra_data)."]);
          
          // Configure the chart to look as wished.
          $stri_config
       
          // Now call the .Draw() method to draw the chart.
          funnel.Draw();
  	});
    ");
  	 
  		$stri_res=' <canvas id="'.$this->stri_id.'" width="'.$this->int_width.'" height="'.$this->int_height.'">[No canvas support]</canvas>';
  		return  $stri_res.$obj_javascripter->javascriptValue();
  }
 
}

?>
