<?php
/*******************************************************************************
Create Date : 24/01/2013
 ----------------------------------------------------------------------
 Class name : RGraph_funnel
 Version : 1.0
 Author : Rémy Soleillant
 Description :  Permet de représenter un graphe de type jauge
                dans la bibliothèque RGraph
                http://www.rgraph.net/examples/fuel.html
 
********************************************************************************/
class rgraph_fuel{
   
  //**** attribute ************************************************************
  protected $stri_id;
  protected $int_width;
  protected $int_height; 
  protected $int_value;     //La valeur de la jauge
  protected $int_min_value; //La valeur min de la jauge
  protected $int_max_value; //La valeur max de la jauge
 
  protected $bool_adjustable;
  protected $bool_annotatable;
  protected $stri_annotate_color;
  protected $stri_centerx;
  protected $stri_centery;
  protected $arra_colors;
  protected $stri_contextmenu;
  protected $int_gutter_bottom;
  protected $int_gutter_left;
  protected $int_gutter_right;
  protected $int_gutter_top;
  protected $arra_icon;
  protected $bool_icon_redraw;
  protected $int_labels_count;
  protected $stri_labels_empty;
  protected $stri_labels_full;
  protected $stri_needle_color;
  protected $stri_radius;
  protected $bool_resizable;
  protected $stri_resize_handle_background;
  protected $int_scale_decimals;
  protected $bool_scale_visible;
  protected $stri_text_color;
  protected $stri_text_font;
  protected $stri_units_post;
  protected $stri_units_pre;
  protected $bool_zoom_background;
  protected $int_zoom_delay;
  protected $float_zoom_factor;
  protected $bool_zoom_fade_in;
  protected $bool_zoom_fade_out;
  protected $int_zoom_frames;
  protected $stri_zoom_hdir;
  protected $bool_zoom_shadow;
  protected $stri_zoom_vdir;


  
  //**** constructor ***********************************************************
 /*************************************************************
 *
 * parametres : 
 * retour : objet de la classe 
 *                        
 **************************************************************/    
  function __construct($stri_id,$int_value=0,$int_min_value=0, $int_max_value=100) 
  { 
   $this->stri_id=$stri_id;
   $this->int_width=200;
   $this->int_height=200; 
   $this->int_min_value=$int_min_value;
   $this->int_max_value=$int_max_value;
   $this->int_value=$int_value;
  
   
  }
 
  //**** setter ****************************************************************
  public function setId($value){$this->stri_id=$value;}
  public function setWidth($value){$this->int_width=$value;}
  public function setHeight($value){$this->int_height=$value;}
  public function setData($value){$this->arra_data=$value;}
  public function setValue($value){$this->int_value=$value;}
  public function setMaxValue($value){$this->int_max_value=$value;}
  public function setMinValue($value){$this->int_min_value=$value;}

  public function setAdjustable($value){$this->bool_adjustable=$value;}
  public function setAnnotatable($value){$this->bool_annotatable=$value;}
  public function setAnnotateColor($value){$this->stri_annotate_color=$value;}
  public function setCenterx($value){$this->stri_centerx=$value;}
  public function setCentery($value){$this->stri_centery=$value;}
  public function setColors($value){$this->arra_colors=$value;}
  public function setContextmenu($value){$this->stri_contextmenu=$value;}
  public function setGutterBottom($value){$this->int_gutter_bottom=$value;}
  public function setGutterLeft($value){$this->int_gutter_left=$value;}
  public function setGutterRight($value){$this->int_gutter_right=$value;}
  public function setGutterTop($value){$this->int_gutter_top=$value;}
  public function setIcon($value){$this->arra_icon=$value;}
  public function setIconRedraw($value){$this->bool_icon_redraw=$value;}
  public function setLabelsCount($value){$this->int_labels_count=$value;}
  public function setLabelsEmpty($value){$this->stri_labels_empty=$value;}
  public function setLabelsFull($value){$this->stri_labels_full=$value;}
  public function setNeedleColor($value){$this->stri_needle_color=$value;}
  public function setRadius($value){$this->stri_radius=$value;}
  public function setResizable($value){$this->bool_resizable=$value;}
  public function setResizeHandleBackground($value){$this->stri_resize_handle_background=$value;}
  public function setScaleDecimals($value){$this->int_scale_decimals=$value;}
  public function setScaleVisible($value){$this->bool_scale_visible=$value;}
  public function setTextColor($value){$this->stri_text_color=$value;}
  public function setTextFont($value){$this->stri_text_font=$value;}
  public function setUnitsPost($value){$this->stri_units_post=$value;}
  public function setUnitsPre($value){$this->stri_units_pre=$value;}
  public function setZoomBackground($value){$this->bool_zoom_background=$value;}
  public function setZoomDelay($value){$this->int_zoom_delay=$value;}
  public function setZoomFactor($value){$this->float_zoom_factor=$value;}
  public function setZoomFadeIn($value){$this->bool_zoom_fade_in=$value;}
  public function setZoomFadeOut($value){$this->bool_zoom_fade_out=$value;}
  public function setZoomFrames($value){$this->int_zoom_frames=$value;}
  public function setZoomHdir($value){$this->stri_zoom_hdir=$value;}
  public function setZoomShadow($value){$this->bool_zoom_shadow=$value;}
  public function setZoomVdir($value){$this->stri_zoom_vdir=$value;}

  //**** getter ****************************************************************
  public function getId(){return $this->stri_id;}
  public function getWidth(){return $this->int_width;}
  public function getHeight(){return $this->int_height;}
  public function getData(){return $this->arra_data;}
  public function getValue(){return $this->int_value;}
  public function getMaxValue(){return $this->int_max_value;}
  public function getMinValue(){return $this->int_min_value;}

  public function getAdjustable(){return $this->bool_adjustable;}
  public function getAnnotatable(){return $this->bool_annotatable;}
  public function getAnnotateColor(){return $this->stri_annotate_color;}
  public function getCenterx(){return $this->stri_centerx;}
  public function getCentery(){return $this->stri_centery;}
  public function getColors(){return $this->arra_colors;}
  public function getContextmenu(){return $this->stri_contextmenu;}
  public function getGutterBottom(){return $this->int_gutter_bottom;}
  public function getGutterLeft(){return $this->int_gutter_left;}
  public function getGutterRight(){return $this->int_gutter_right;}
  public function getGutterTop(){return $this->int_gutter_top;}
  public function getIcon(){return $this->arra_icon;}
  public function getIconRedraw(){return $this->bool_icon_redraw;}
  public function getLabelsCount(){return $this->int_labels_count;}
  public function getLabelsEmpty(){return $this->stri_labels_empty;}
  public function getLabelsFull(){return $this->stri_labels_full;}
  public function getNeedleColor(){return $this->stri_needle_color;}
  public function getRadius(){return $this->stri_radius;}
  public function getResizable(){return $this->bool_resizable;}
  public function getResizeHandleBackground(){return $this->stri_resize_handle_background;}
  public function getScaleDecimals(){return $this->int_scale_decimals;}
  public function getScaleVisible(){return $this->bool_scale_visible;}
  public function getTextColor(){return $this->stri_text_color;}
  public function getTextFont(){return $this->stri_text_font;}
  public function getUnitsPost(){return $this->stri_units_post;}
  public function getUnitsPre(){return $this->stri_units_pre;}
  public function getZoomBackground(){return $this->bool_zoom_background;}
  public function getZoomDelay(){return $this->int_zoom_delay;}
  public function getZoomFactor(){return $this->float_zoom_factor;}
  public function getZoomFadeIn(){return $this->bool_zoom_fade_in;}
  public function getZoomFadeOut(){return $this->bool_zoom_fade_out;}
  public function getZoomFrames(){return $this->int_zoom_frames;}
  public function getZoomHdir(){return $this->stri_zoom_hdir;}
  public function getZoomShadow(){return $this->bool_zoom_shadow;}
  public function getZoomVdir(){return $this->stri_zoom_vdir;}



   
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
    $stri_config.=($this->bool_adjustable)?"graphe.Set('chart.adjustable',true);\n":"";
    $stri_config.=($this->bool_annotatable)?"graphe.Set('chart.annotatable',true);\n":"";
    $stri_config.=($this->stri_annotate_color!='')?"graphe.Set('chart.annotate.color','".$this->stri_annotate_color."');\n":"";
    $stri_config.=($this->stri_centerx!='')?"graphe.Set('chart.centerx','".$this->stri_centerx."');\n":"";
    $stri_config.=($this->stri_centery!='')?"graphe.Set('chart.centery','".$this->stri_centery."');\n":"";
    $stri_config.=(count($this->arra_colors)>0)?"graphe.Set('chart.colors', ['".join("','",$this->arra_colors)."']);\n":"";
    $stri_config.=($this->stri_contextmenu!='')?"graphe.Set('chart.contextmenu','".$this->stri_contextmenu."');\n":"";
    $stri_config.=($this->int_gutter_bottom!='')?"graphe.Set('chart.gutter.bottom','".$this->int_gutter_bottom."');\n":"";
    $stri_config.=($this->int_gutter_left!='')?"graphe.Set('chart.gutter.left','".$this->int_gutter_left."');\n":"";
    $stri_config.=($this->int_gutter_right!='')?"graphe.Set('chart.gutter.right','".$this->int_gutter_right."');\n":"";
    $stri_config.=($this->int_gutter_top!='')?"graphe.Set('chart.gutter.top','".$this->int_gutter_top."');\n":"";
    $stri_config.=(count($this->arra_icon)>0)?"graphe.Set('chart.icon', ['".join("','",$this->arra_icon)."']);\n":"";
    $stri_config.=($this->bool_icon_redraw)?"graphe.Set('chart.icon.redraw',true);\n":"";
    $stri_config.=($this->int_labels_count!='')?"graphe.Set('chart.labels.count','".$this->int_labels_count."');\n":"";
    $stri_config.=($this->stri_labels_empty!='')?"graphe.Set('chart.labels.empty','".$this->stri_labels_empty."');\n":"";
    $stri_config.=($this->stri_labels_full!='')?"graphe.Set('chart.labels.full','".$this->stri_labels_full."');\n":"";
    $stri_config.=($this->stri_needle_color!='')?"graphe.Set('chart.needle.color','".$this->stri_needle_color."');\n":"";
    $stri_config.=($this->stri_radius!='')?"graphe.Set('chart.radius','".$this->stri_radius."');\n":"";
    $stri_config.=($this->bool_resizable)?"graphe.Set('chart.resizable',true);\n":"";
    $stri_config.=($this->stri_resize_handle_background!='')?"graphe.Set('chart.resize.handle.background','".$this->stri_resize_handle_background."');\n":"";
    $stri_config.=($this->int_scale_decimals!='')?"graphe.Set('chart.scale.decimals','".$this->int_scale_decimals."');\n":"";
    $stri_config.=($this->bool_scale_visible)?"graphe.Set('chart.scale.visible',true);\n":"";
    $stri_config.=($this->stri_text_color!='')?"graphe.Set('chart.text.color','".$this->stri_text_color."');\n":"";
    $stri_config.=($this->stri_text_font!='')?"graphe.Set('chart.text.font','".$this->stri_text_font."');\n":"";
    $stri_config.=($this->stri_units_post!='')?"graphe.Set('chart.units.post','".$this->stri_units_post."');\n":"";
    $stri_config.=($this->stri_units_pre!='')?"graphe.Set('chart.units.pre','".$this->stri_units_pre."');\n":"";
    $stri_config.=($this->bool_zoom_background)?"graphe.Set('chart.zoom.background',true);\n":"";
    $stri_config.=($this->int_zoom_delay!='')?"graphe.Set('chart.zoom.delay','".$this->int_zoom_delay."');\n":"";
    $stri_config.=($this->float_zoom_factor!='')?"graphe.Set('chart.zoom.factor','".$this->float_zoom_factor."');\n":"";
    $stri_config.=($this->bool_zoom_fade_in)?"graphe.Set('chart.zoom.fade.in',true);\n":"";
    $stri_config.=($this->bool_zoom_fade_out)?"graphe.Set('chart.zoom.fade.out',true);\n":"";
    $stri_config.=($this->int_zoom_frames!='')?"graphe.Set('chart.zoom.frames','".$this->int_zoom_frames."');\n":"";
    $stri_config.=($this->stri_zoom_hdir!='')?"graphe.Set('chart.zoom.hdir','".$this->stri_zoom_hdir."');\n":"";
    $stri_config.=($this->bool_zoom_shadow)?"graphe.Set('chart.zoom.shadow',true);\n":"";
    $stri_config.=($this->stri_zoom_vdir!='')?"graphe.Set('chart.zoom.vdir','".$this->stri_zoom_vdir."');\n":"";


    
  	$obj_javascripter->addFunction("                                                    
  	$(function()
  	{ 
        var graphe = new RGraph.Fuel('".$this->stri_id."',".$this->int_min_value.",".$this->int_max_value.",".$this->int_value.");
        // Configure the progress bar to appear as requested.
        $stri_config
        
        // Now call the .Draw() method to draw the chart.
       // graphe.Draw();
      RGraph.Effects.Fuel.Grow(graphe);
  	});
    ");
  	 
  		$stri_res=' <canvas id="'.$this->stri_id.'" width="'.$this->int_width.'" height="'.$this->int_height.'">[No canvas support]</canvas>';
  		return  $stri_res.$obj_javascripter->javascriptValue();
  }
 
}

?>
