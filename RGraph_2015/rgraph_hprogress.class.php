<?php
/*******************************************************************************
Create Date : 24/01/2013
 ----------------------------------------------------------------------
 Class name : RGraph_funnel
 Version : 1.0
 Author : Rémy Soleillant
 Description :  Permet de représenter un graphe de type barre d'avancement horizontal
                dans la bibliothèque RGraph
                http://www.rgraph.net/docs/hprogress.html
 
********************************************************************************/
class rgraph_hprogress{
   
  //**** attribute ************************************************************
  protected $stri_id;
  protected $int_width;
  protected $int_height; 
  protected $int_value;     //La valeur de la barre de progression
  protected $int_max_value; //La valeur max de la barre
 
  protected $bool_adjustable;
  protected $bool_annotatable;
  protected $stri_annotate_color;
  protected $bool_arrows;
  protected $stri_background_color;
  protected $bool_border_inner;
  protected $arra_colors;
  protected $arra_contextmenu;
  protected $stri_events_click;
  protected $stri_events_mousemove;
  protected $int_gutter_bottom;
  protected $int_gutter_left;
  protected $int_gutter_right;
  protected $int_gutter_top;
  protected $stri_highlight_fill;
  protected $stri_highlight_stroke;
  protected $arra_labels;
  protected $stri_labels_position;
  protected $stri_labels_specific;
  protected $int_numticks;
  protected $int_numticks_inner;
  protected $bool_resizable;
  protected $stri_resize_handle_background;
  protected $int_scale_decimals;
  protected $stri_scale_point;
  protected $stri_scale_thousand;
  protected $bool_shadow;
  protected $int_shadow_blur;
  protected $stri_shadow_color;
  protected $int_shadow_offsetx;
  protected $int_shadow_offsety;
  protected $stri_strokestyle_inner;
  protected $stri_strokestyle_outer;
  protected $stri_text_color;
  protected $stri_text_font;
  protected $stri_text_size;
  protected $bool_tickmarks;
  protected $stri_tickmarks_color;
  protected $bool_tickmarks_inner;
  protected $bool_tickmarks_zerostart;
  protected $stri_title;
  protected $stri_title_background;
  protected $bool_title_bold;
  protected $stri_title_color;
  protected $stri_title_font;
  protected $stri_title_hpos;
  protected $stri_title_size;
  protected $stri_title_vpos;
  protected $arra_tooltips;
  protected $bool_tooltips_coords_page;
  protected $stri_tooltips_css_class;
  protected $stri_tooltips_effect;
  protected $stri_tooltips_override;
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
  function __construct($stri_id,$int_value=0,$int_max_value=100) 
  { 
   $this->stri_id=$stri_id;
   $this->int_width=400;
   $this->int_height=70; 
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
 
  public function setAdjustable($value){$this->bool_adjustable=$value;}
  public function setAnnotatable($value){$this->bool_annotatable=$value;}
  public function setAnnotateColor($value){$this->stri_annotate_color=$value;}
  public function setArrows($value){$this->bool_arrows=$value;}
  public function setBackgroundColor($value){$this->stri_background_color=$value;}
  public function setBorderInner($value){$this->bool_border_inner=$value;}
  public function setColors($value){$this->arra_colors=$value;}
  public function setContextmenu($value){$this->arra_contextmenu=$value;}
  public function setEventsClick($value){$this->stri_events_click=$value;}
  public function setEventsMousemove($value){$this->stri_events_mousemove=$value;}
  public function setGutterBottom($value){$this->int_gutter_bottom=$value;}
  public function setGutterLeft($value){$this->int_gutter_left=$value;}
  public function setGutterRight($value){$this->int_gutter_right=$value;}
  public function setGutterTop($value){$this->int_gutter_top=$value;}
  public function setHighlightFill($value){$this->stri_highlight_fill=$value;}
  public function setHighlightStroke($value){$this->stri_highlight_stroke=$value;}
  public function setLabels($value){$this->arra_labels=$value;}
  public function setLabelsPosition($value){$this->stri_labels_position=$value;}
  public function setLabelsSpecific($value){$this->stri_labels_specific=$value;}
  public function setNumticks($value){$this->int_numticks=$value;}
  public function setNumticksInner($value){$this->int_numticks_inner=$value;}
  public function setResizable($value){$this->bool_resizable=$value;}
  public function setResizeHandleBackground($value){$this->stri_resize_handle_background=$value;}
  public function setScaleDecimals($value){$this->int_scale_decimals=$value;}
  public function setScalePoint($value){$this->stri_scale_point=$value;}
  public function setScaleThousand($value){$this->stri_scale_thousand=$value;}
  public function setShadow($value){$this->bool_shadow=$value;}
  public function setShadowBlur($value){$this->int_shadow_blur=$value;}
  public function setShadowColor($value){$this->stri_shadow_color=$value;}
  public function setShadowOffsetx($value){$this->int_shadow_offsetx=$value;}
  public function setShadowOffsety($value){$this->int_shadow_offsety=$value;}
  public function setStrokestyleInner($value){$this->stri_strokestyle_inner=$value;}
  public function setStrokestyleOuter($value){$this->stri_strokestyle_outer=$value;}
  public function setTextColor($value){$this->stri_text_color=$value;}
  public function setTextFont($value){$this->stri_text_font=$value;}
  public function setTextSize($value){$this->stri_text_size=$value;}
  public function setTickmarks($value){$this->bool_tickmarks=$value;}
  public function setTickmarksColor($value){$this->stri_tickmarks_color=$value;}
  public function setTickmarksInner($value){$this->bool_tickmarks_inner=$value;}
  public function setTickmarksZerostart($value){$this->bool_tickmarks_zerostart=$value;}
  public function setTitle($value){$this->stri_title=$value;}
  public function setTitleBackground($value){$this->stri_title_background=$value;}
  public function setTitleBold($value){$this->bool_title_bold=$value;}
  public function setTitleColor($value){$this->stri_title_color=$value;}
  public function setTitleFont($value){$this->stri_title_font=$value;}
  public function setTitleHpos($value){$this->stri_title_hpos=$value;}
  public function setTitleSize($value){$this->stri_title_size=$value;}
  public function setTitleVpos($value){$this->stri_title_vpos=$value;}
  public function setTooltips($value){$this->arra_tooltips=$value;}
  public function setTooltipsCoordsPage($value){$this->bool_tooltips_coords_page=$value;}
  public function setTooltipsCssClass($value){$this->stri_tooltips_css_class=$value;}
  public function setTooltipsEffect($value){$this->stri_tooltips_effect=$value;}
  public function setTooltipsOverride($value){$this->stri_tooltips_override=$value;}
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

  public function getAdjustable(){return $this->bool_adjustable;}
  public function getAnnotatable(){return $this->bool_annotatable;}
  public function getAnnotateColor(){return $this->stri_annotate_color;}
  public function getArrows(){return $this->bool_arrows;}
  public function getBackgroundColor(){return $this->stri_background_color;}
  public function getBorderInner(){return $this->bool_border_inner;}
  public function getColors(){return $this->arra_colors;}
  public function getContextmenu(){return $this->arra_contextmenu;}
  public function getEventsClick(){return $this->stri_events_click;}
  public function getEventsMousemove(){return $this->stri_events_mousemove;}
  public function getGutterBottom(){return $this->int_gutter_bottom;}
  public function getGutterLeft(){return $this->int_gutter_left;}
  public function getGutterRight(){return $this->int_gutter_right;}
  public function getGutterTop(){return $this->int_gutter_top;}
  public function getHighlightFill(){return $this->stri_highlight_fill;}
  public function getHighlightStroke(){return $this->stri_highlight_stroke;}
  public function getLabels(){return $this->arra_labels;}
  public function getLabelsPosition(){return $this->stri_labels_position;}
  public function getLabelsSpecific(){return $this->stri_labels_specific;}
  public function getNumticks(){return $this->int_numticks;}
  public function getNumticksInner(){return $this->int_numticks_inner;}
  public function getResizable(){return $this->bool_resizable;}
  public function getResizeHandleBackground(){return $this->stri_resize_handle_background;}
  public function getScaleDecimals(){return $this->int_scale_decimals;}
  public function getScalePoint(){return $this->stri_scale_point;}
  public function getScaleThousand(){return $this->stri_scale_thousand;}
  public function getShadow(){return $this->bool_shadow;}
  public function getShadowBlur(){return $this->int_shadow_blur;}
  public function getShadowColor(){return $this->stri_shadow_color;}
  public function getShadowOffsetx(){return $this->int_shadow_offsetx;}
  public function getShadowOffsety(){return $this->int_shadow_offsety;}
  public function getStrokestyleInner(){return $this->stri_strokestyle_inner;}
  public function getStrokestyleOuter(){return $this->stri_strokestyle_outer;}
  public function getTextColor(){return $this->stri_text_color;}
  public function getTextFont(){return $this->stri_text_font;}
  public function getTextSize(){return $this->stri_text_size;}
  public function getTickmarks(){return $this->bool_tickmarks;}
  public function getTickmarksColor(){return $this->stri_tickmarks_color;}
  public function getTickmarksInner(){return $this->bool_tickmarks_inner;}
  public function getTickmarksZerostart(){return $this->bool_tickmarks_zerostart;}
  public function getTitle(){return $this->stri_title;}
  public function getTitleBackground(){return $this->stri_title_background;}
  public function getTitleBold(){return $this->bool_title_bold;}
  public function getTitleColor(){return $this->stri_title_color;}
  public function getTitleFont(){return $this->stri_title_font;}
  public function getTitleHpos(){return $this->stri_title_hpos;}
  public function getTitleSize(){return $this->stri_title_size;}
  public function getTitleVpos(){return $this->stri_title_vpos;}
  public function getTooltips(){return $this->arra_tooltips;}
  public function getTooltipsCoordsPage(){return $this->bool_tooltips_coords_page;}
  public function getTooltipsCssClass(){return $this->stri_tooltips_css_class;}
  public function getTooltipsEffect(){return $this->stri_tooltips_effect;}
  public function getTooltipsOverride(){return $this->stri_tooltips_override;}
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
  $stri_config.=($this->bool_arrows)?"graphe.Set('chart.arrows',true);\n":"";
  $stri_config.=($this->stri_background_color!='')?"graphe.Set('chart.background.color','".$this->stri_background_color."');\n":"";
  $stri_config.=($this->bool_border_inner)?"graphe.Set('chart.border.inner',true);\n":"";
  $stri_config.=(count($this->arra_colors)>0)?"graphe.Set('chart.colors', ['".join("','",$this->arra_colors)."']);\n":"";
  $stri_config.=(count($this->arra_contextmenu)>0)?"graphe.Set('chart.contextmenu', ['".join("','",$this->arra_contextmenu)."']);\n":"";
  $stri_config.=($this->stri_events_click!='')?"graphe.Set('chart.events.click','".$this->stri_events_click."');\n":"";
  $stri_config.=($this->stri_events_mousemove!='')?"graphe.Set('chart.events.mousemove','".$this->stri_events_mousemove."');\n":"";
  $stri_config.=($this->int_gutter_bottom!='')?"graphe.Set('chart.gutter.bottom','".$this->int_gutter_bottom."');\n":"";
  $stri_config.=($this->int_gutter_left!='')?"graphe.Set('chart.gutter.left','".$this->int_gutter_left."');\n":"";
  $stri_config.=($this->int_gutter_right!='')?"graphe.Set('chart.gutter.right','".$this->int_gutter_right."');\n":"";
  $stri_config.=($this->int_gutter_top!='')?"graphe.Set('chart.gutter.top','".$this->int_gutter_top."');\n":"";
  $stri_config.=($this->stri_highlight_fill!='')?"graphe.Set('chart.highlight.fill','".$this->stri_highlight_fill."');\n":"";
  $stri_config.=($this->stri_highlight_stroke!='')?"graphe.Set('chart.highlight.stroke','".$this->stri_highlight_stroke."');\n":"";
  $stri_config.=(count($this->arra_labels)>0)?"graphe.Set('chart.labels', ['".join("','",$this->arra_labels)."']);\n":"";
  $stri_config.=($this->stri_labels_position!='')?"graphe.Set('chart.labels.position','".$this->stri_labels_position."');\n":"";
  $stri_config.=($this->stri_labels_specific!='')?"graphe.Set('chart.labels.specific','".$this->stri_labels_specific."');\n":"";
  $stri_config.=($this->int_numticks!='')?"graphe.Set('chart.numticks','".$this->int_numticks."');\n":"";
  $stri_config.=($this->int_numticks_inner!='')?"graphe.Set('chart.numticks.inner','".$this->int_numticks_inner."');\n":"";
  $stri_config.=($this->bool_resizable)?"graphe.Set('chart.resizable',true);\n":"";
  $stri_config.=($this->stri_resize_handle_background!='')?"graphe.Set('chart.resize.handle.background','".$this->stri_resize_handle_background."');\n":"";
  $stri_config.=($this->int_scale_decimals!='')?"graphe.Set('chart.scale.decimals','".$this->int_scale_decimals."');\n":"";
  $stri_config.=($this->stri_scale_point!='')?"graphe.Set('chart.scale.point','".$this->stri_scale_point."');\n":"";
  $stri_config.=($this->stri_scale_thousand!='')?"graphe.Set('chart.scale.thousand','".$this->stri_scale_thousand."');\n":"";
  $stri_config.=($this->bool_shadow)?"graphe.Set('chart.shadow',true);\n":"";
  $stri_config.=($this->int_shadow_blur!='')?"graphe.Set('chart.shadow.blur','".$this->int_shadow_blur."');\n":"";
  $stri_config.=($this->stri_shadow_color!='')?"graphe.Set('chart.shadow.color','".$this->stri_shadow_color."');\n":"";
  $stri_config.=($this->int_shadow_offsetx!='')?"graphe.Set('chart.shadow.offsetx','".$this->int_shadow_offsetx."');\n":"";
  $stri_config.=($this->int_shadow_offsety!='')?"graphe.Set('chart.shadow.offsety','".$this->int_shadow_offsety."');\n":"";
  $stri_config.=($this->stri_strokestyle_inner!='')?"graphe.Set('chart.strokestyle.inner','".$this->stri_strokestyle_inner."');\n":"";
  $stri_config.=($this->stri_strokestyle_outer!='')?"graphe.Set('chart.strokestyle.outer','".$this->stri_strokestyle_outer."');\n":"";
  $stri_config.=($this->stri_text_color!='')?"graphe.Set('chart.text.color','".$this->stri_text_color."');\n":"";
  $stri_config.=($this->stri_text_font!='')?"graphe.Set('chart.text.font','".$this->stri_text_font."');\n":"";
  $stri_config.=($this->stri_text_size!='')?"graphe.Set('chart.text.size','".$this->stri_text_size."');\n":"";
  $stri_config.=($this->bool_tickmarks)?"graphe.Set('chart.tickmarks',true);\n":"";
  $stri_config.=($this->stri_tickmarks_color!='')?"graphe.Set('chart.tickmarks.color','".$this->stri_tickmarks_color."');\n":"";
  $stri_config.=($this->bool_tickmarks_inner)?"graphe.Set('chart.tickmarks.inner',true);\n":"";
  $stri_config.=($this->bool_tickmarks_zerostart)?"graphe.Set('chart.tickmarks.zerostart',true);\n":"";
  $stri_config.=($this->stri_title!='')?"graphe.Set('chart.title','".$this->stri_title."');\n":"";
  $stri_config.=($this->stri_title_background!='')?"graphe.Set('chart.title.background','".$this->stri_title_background."');\n":"";
  $stri_config.=($this->bool_title_bold)?"graphe.Set('chart.title.bold',true);\n":"";
  $stri_config.=($this->stri_title_color!='')?"graphe.Set('chart.title.color','".$this->stri_title_color."');\n":"";
  $stri_config.=($this->stri_title_font!='')?"graphe.Set('chart.title.font','".$this->stri_title_font."');\n":"";
  $stri_config.=($this->stri_title_hpos!='')?"graphe.Set('chart.title.hpos','".$this->stri_title_hpos."');\n":"";
  $stri_config.=($this->stri_title_size!='')?"graphe.Set('chart.title.size','".$this->stri_title_size."');\n":"";
  $stri_config.=($this->stri_title_vpos!='')?"graphe.Set('chart.title.vpos','".$this->stri_title_vpos."');\n":"";
  $stri_config.=(count($this->arra_tooltips)>0)?"graphe.Set('chart.tooltips', ['".join("','",$this->arra_tooltips)."']);\n":"";
  $stri_config.=($this->bool_tooltips_coords_page)?"graphe.Set('chart.tooltips.coords.page',true);\n":"";
  $stri_config.=($this->stri_tooltips_css_class!='')?"graphe.Set('chart.tooltips.css.class','".$this->stri_tooltips_css_class."');\n":"";
  $stri_config.=($this->stri_tooltips_effect!='')?"graphe.Set('chart.tooltips.effect','".$this->stri_tooltips_effect."');\n":"";
  $stri_config.=($this->stri_tooltips_override!='')?"graphe.Set('chart.tooltips.override','".$this->stri_tooltips_override."');\n":"";
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
         var graphe = new RGraph.HProgress('".$this->stri_id."', $this->int_value, $this->int_max_value);
        
        // Configure the progress bar to appear as requested.
        //graphe.Set('chart.colors', ['red']);
        $stri_config
        // graphe.Set('chart.colors', ['pink', 'red', 'yellow']);
        // Now call the .Draw() method to draw the chart.
        //graphe.Draw();
        RGraph.Effects.HProgress.Grow(graphe);
  	});
    ");
  	 
  		$stri_res=' <canvas id="'.$this->stri_id.'" width="'.$this->int_width.'" height="'.$this->int_height.'">[No canvas support]</canvas>';
  		return  $stri_res.$obj_javascripter->javascriptValue();
  }
 
}

?>
