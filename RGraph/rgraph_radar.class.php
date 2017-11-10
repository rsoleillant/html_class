<?php
/*******************************************************************************
Create Date : 27/02/2013
 ----------------------------------------------------------------------
 Class name : rgraph_radar
 Version : 1.0
 Author : Rémy Soleillant
 Description : Intégration du graphe radar de la bibliothèque rgraph
 
********************************************************************************/
class rgraph_radar{
   
   //**** attribute ************************************************************
    protected $stri_id;                           //L'identifiant du graphe
    protected $arra_data;                         //Les données à représenter
    protected $int_width;
    protected $int_height; 
    protected $stri_class;                        //La classe css
    protected $stri_style;                        //Le style css du canvas
    
    protected $bool_accumulative;
    protected $bool_annotatable;
    protected $stri_annotate_color;
    protected $stri_axes_color;
    protected $stri_background_circles;
    protected $stri_background_circles_color;
    protected $bool_background_circles_poly;
    protected $int_background_circles_spacing;
    protected $stri_centerx;
    protected $stri_centery;
    protected $int_circle;
    protected $stri_circle_fill;
    protected $stri_circle_stroke;
    protected $stri_colors;
    protected $stri_colors_alpha;
    protected $arra_contextmenu;
    protected $stri_events_click;
    protected $stri_events_mousemove;
    protected $int_gutter_bottom;
    protected $int_gutter_left;
    protected $int_gutter_right;
    protected $int_gutter_top;
    protected $stri_highlight_fill;
    protected $int_highlight_point_radius;
    protected $stri_highlight_stroke;
    protected $bool_highlights;
    protected $bool_highlights_color;
    protected $int_highlights_radius;
    protected $arra_key;
    protected $stri_key_background;
    protected $stri_key_color_shape;
    protected $stri_key_colors;
    protected $stri_key_halign;
    protected $int_key_linewidth;
    protected $stri_key_position;
    protected $bool_key_position_graph_boxed;
    protected $bool_key_position_gutter_boxed;
    protected $stri_key_position_x;
    protected $stri_key_position_y;
    protected $bool_key_rounded;
    protected $bool_key_shadow;
    protected $stri_key_shadow_blur;
    protected $stri_key_shadow_color;
    protected $int_key_shadow_offsetx;
    protected $int_key_shadow_offsety;
    protected $arra_labels;
    protected $stri_labels_axes;
    protected $stri_labels_background_fill;
    protected $bool_labels_boxed;
    protected $int_labels_offset;
    protected $int_linewidth;
    protected $int_numxticks;
    protected $int_numyticks;
    protected $stri_radius;
    protected $bool_resizable;
    protected $stri_resize_handle_background;
    protected $int_scale_decimals;
    protected $stri_scale_point;
    protected $stri_scale_round;
    protected $stri_scale_thousand;
    protected $stri_strokestyle;
    protected $stri_text_color;
    protected $stri_text_font;
    protected $int_text_size;
    protected $stri_text_size_scale;
    protected $stri_title;
    protected $arra_tooltips;
    protected $int_ymax;

  
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
  }
 
  //**** setter ****************************************************************
  public function setId($value){$this->stri_id=$value;}
  public function setData($value){$this->arra_data=$value;}
  public function setWidth($value){$this->int_width=$value;}
  public function setHeight($value){$this->int_height=$value;}
  public function setClass($value){$this->stri_class=$value;}
  public function setStyle($value){$this->stri_style=$value;}

  
  public function setAccumulative($value){$this->bool_accumulative=$value;}
  public function setAnnotatable($value){$this->bool_annotatable=$value;}
  public function setAnnotateColor($value){$this->stri_annotate_color=$value;}
  public function setAxesColor($value){$this->stri_axes_color=$value;}
  public function setBackgroundCircles($value){$this->stri_background_circles=$value;}
  public function setBackgroundCirclesColor($value){$this->stri_background_circles_color=$value;}
  public function setBackgroundCirclesPoly($value){$this->bool_background_circles_poly=$value;}
  public function setCenterx($value){$this->stri_centerx=$value;}
  public function setCentery($value){$this->stri_centery=$value;}
  public function setCircle($value){$this->int_circle=$value;}
  public function setCircleFill($value){$this->stri_circle_fill=$value;}
  public function setCircleStroke($value){$this->stri_circle_stroke=$value;}
  public function setBackgroundCirclesSpacing($value){$this->int_background_circles_spacing=$value;}
  public function setColors($value){$this->stri_colors=$value;}
  public function setColorsAlpha($value){$this->stri_colors_alpha=$value;}
  public function setContextmenu($value){$this->arra_contextmenu=$value;}
  public function setEventsClick($value){$this->stri_events_click=$value;}
  public function setEventsMousemove($value){$this->stri_events_mousemove=$value;}
  public function setGutterBottom($value){$this->int_gutter_bottom=$value;}
  public function setGutterLeft($value){$this->int_gutter_left=$value;}
  public function setGutterRight($value){$this->int_gutter_right=$value;}
  public function setGutterTop($value){$this->int_gutter_top=$value;}
  public function setHighlightFill($value){$this->stri_highlight_fill=$value;}
  public function setHighlightPointRadius($value){$this->int_highlight_point_radius=$value;}
  public function setHighlightStroke($value){$this->stri_highlight_stroke=$value;}
  public function setHighlights($value){$this->bool_highlights=$value;}
  public function setHighlightsColor($value){$this->bool_highlights_color=$value;}
  public function setHighlightsRadius($value){$this->int_highlights_radius=$value;}
  public function setKey($value){$this->arra_key=$value;}
  public function setKeyBackground($value){$this->stri_key_background=$value;}
  public function setKeyColorShape($value){$this->stri_key_color_shape=$value;}
  public function setKeyColors($value){$this->stri_key_colors=$value;}
  public function setKeyHalign($value){$this->stri_key_halign=$value;}
  public function setKeyLinewidth($value){$this->int_key_linewidth=$value;}
  public function setKeyPosition($value){$this->stri_key_position=$value;}
  public function setKeyPositionGraphBoxed($value){$this->bool_key_position_graph_boxed=$value;}
  public function setKeyPositionGutterBoxed($value){$this->bool_key_position_gutter_boxed=$value;}
  public function setKeyPositionX($value){$this->stri_key_position_x=$value;}
  public function setKeyPositionY($value){$this->stri_key_position_y=$value;}
  public function setKeyRounded($value){$this->bool_key_rounded=$value;}
  public function setKeyShadow($value){$this->bool_key_shadow=$value;}
  public function setKeyShadowBlur($value){$this->stri_key_shadow_blur=$value;}
  public function setKeyShadowColor($value){$this->stri_key_shadow_color=$value;}
  public function setKeyShadowOffsetx($value){$this->int_key_shadow_offsetx=$value;}
  public function setKeyShadowOffsety($value){$this->int_key_shadow_offsety=$value;}
  public function setLabels($value){$this->arra_labels=$value;}
  public function setLabelsAxes($value){$this->stri_labels_axes=$value;}
  public function setLabelsBackgroundFill($value){$this->stri_labels_background_fill=$value;}
  public function setLabelsBoxed($value){$this->bool_labels_boxed=$value;}
  public function setLabelsOffset($value){$this->int_labels_offset=$value;}
  public function setLinewidth($value){$this->int_linewidth=$value;}
  public function setNumxticks($value){$this->int_numxticks=$value;}
  public function setNumyticks($value){$this->int_numyticks=$value;}
  public function setRadius($value){$this->stri_radius=$value;}
  public function setResizable($value){$this->bool_resizable=$value;}
  public function setResizeHandleBackground($value){$this->stri_resize_handle_background=$value;}
  public function setScaleDecimals($value){$this->int_scale_decimals=$value;}
  public function setScalePoint($value){$this->stri_scale_point=$value;}
  public function setScaleRound($value){$this->stri_scale_round=$value;}
  public function setScaleThousand($value){$this->stri_scale_thousand=$value;}
  public function setStrokestyle($value){$this->stri_strokestyle=$value;}
  public function setTextColor($value){$this->stri_text_color=$value;}
  public function setTextFont($value){$this->stri_text_font=$value;}
  public function setTextSize($value){$this->int_text_size=$value;}
  public function setTextSizeScale($value){$this->stri_text_size_scale=$value;}
  public function setTitle($value){$this->stri_title=$value;}
  public function setTooltips($value){$this->arra_tooltips=$value;}
  public function setYmax($value){$this->int_ymax=$value;}

  
  //**** getter ****************************************************************
  public function getId(){return $this->stri_id;}
  public function getData(){return $this->arra_data;}
  public function getWidth(){return $this->int_width;}
  public function getHeight(){return $this->int_height;}
  public function getClass(){return $this->stri_class;}
  public function getStyle(){return $this->stri_style;}

  public function getAccumulative(){return $this->bool_accumulative;}
  public function getAnnotatable(){return $this->bool_annotatable;}
  public function getAnnotateColor(){return $this->stri_annotate_color;}
  public function getAxesColor(){return $this->stri_axes_color;}
  public function getBackgroundCircles(){return $this->stri_background_circles;}
  public function getBackgroundCirclesColor(){return $this->stri_background_circles_color;}
  public function getBackgroundCirclesPoly(){return $this->bool_background_circles_poly;}
  public function getBackgroundCirclesSpacing(){return $this->int_background_circles_spacing;}
  public function getCenterx(){return $this->stri_centerx;}
  public function getCentery(){return $this->stri_centery;}
  public function getCircle(){return $this->int_circle;}
  public function getCircleFill(){return $this->stri_circle_fill;}
  public function getCircleStroke(){return $this->stri_circle_stroke;}
  public function getColors(){return $this->stri_colors;}
  public function getColorsAlpha(){return $this->stri_colors_alpha;}
  public function getContextmenu(){return $this->arra_contextmenu;}
  public function getEventsClick(){return $this->stri_events_click;}
  public function getEventsMousemove(){return $this->stri_events_mousemove;}
  public function getGutterBottom(){return $this->int_gutter_bottom;}
  public function getGutterLeft(){return $this->int_gutter_left;}
  public function getGutterRight(){return $this->int_gutter_right;}
  public function getGutterTop(){return $this->int_gutter_top;}
  public function getHighlightFill(){return $this->stri_highlight_fill;}
  public function getHighlightPointRadius(){return $this->int_highlight_point_radius;}
  public function getHighlightStroke(){return $this->stri_highlight_stroke;}
  public function getHighlights(){return $this->bool_highlights;}
  public function getHighlightsColor(){return $this->bool_highlights_color;}
  public function getHighlightsRadius(){return $this->int_highlights_radius;}
  public function getKey(){return $this->arra_key;}
  public function getKeyBackground(){return $this->stri_key_background;}
  public function getKeyColorShape(){return $this->stri_key_color_shape;}
  public function getKeyColors(){return $this->stri_key_colors;}
  public function getKeyHalign(){return $this->stri_key_halign;}
  public function getKeyLinewidth(){return $this->int_key_linewidth;}
  public function getKeyPosition(){return $this->stri_key_position;}
  public function getKeyPositionGraphBoxed(){return $this->bool_key_position_graph_boxed;}
  public function getKeyPositionGutterBoxed(){return $this->bool_key_position_gutter_boxed;}
  public function getKeyPositionX(){return $this->stri_key_position_x;}
  public function getKeyPositionY(){return $this->stri_key_position_y;}
  public function getKeyRounded(){return $this->bool_key_rounded;}
  public function getKeyShadow(){return $this->bool_key_shadow;}
  public function getKeyShadowBlur(){return $this->stri_key_shadow_blur;}
  public function getKeyShadowColor(){return $this->stri_key_shadow_color;}
  public function getKeyShadowOffsetx(){return $this->int_key_shadow_offsetx;}
  public function getKeyShadowOffsety(){return $this->int_key_shadow_offsety;}
  public function getLabels(){return $this->arra_labels;}
  public function getLabelsAxes(){return $this->stri_labels_axes;}
  public function getLabelsBackgroundFill(){return $this->stri_labels_background_fill;}
  public function getLabelsBoxed(){return $this->bool_labels_boxed;}
  public function getLabelsOffset(){return $this->int_labels_offset;}
  public function getLinewidth(){return $this->int_linewidth;}
  public function getNumxticks(){return $this->int_numxticks;}
  public function getNumyticks(){return $this->int_numyticks;}
  public function getRadius(){return $this->stri_radius;}
  public function getResizable(){return $this->bool_resizable;}
  public function getResizeHandleBackground(){return $this->stri_resize_handle_background;}
  public function getScaleDecimals(){return $this->int_scale_decimals;}
  public function getScalePoint(){return $this->stri_scale_point;}
  public function getScaleRound(){return $this->stri_scale_round;}
  public function getScaleThousand(){return $this->stri_scale_thousand;}
  public function getStrokestyle(){return $this->stri_strokestyle;}
  public function getTextColor(){return $this->stri_text_color;}
  public function getTextFont(){return $this->stri_text_font;}
  public function getTextSize(){return $this->int_text_size;}
  public function getTextSizeScale(){return $this->stri_text_size_scale;}
  public function getTitle(){return $this->stri_title;}
  public function getTooltips(){return $this->arra_tooltips;}
  public function getYmax(){return $this->int_ymax;}

   
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
    $stri_config.=($this->bool_accumulative)?"graphe.Set('chart.accumulative',true);\n":"";
    $stri_config.=($this->bool_annotatable)?"graphe.Set('chart.annotatable',true);\n":"";
    $stri_config.=($this->stri_annotate_color!='')?"graphe.Set('chart.annotate.color','".$this->stri_annotate_color."');\n":"";
    $stri_config.=($this->stri_axes_color!='')?"graphe.Set('chart.axes.color','".$this->stri_axes_color."');\n":"";
    $stri_config.=($this->stri_background_circles!='')?"graphe.Set('chart.background.circles','".$this->stri_background_circles."');\n":"";
    $stri_config.=($this->stri_background_circles_color!='')?"graphe.Set('chart.background.circles.color','".$this->stri_background_circles_color."');\n":"";
    $stri_config.=($this->bool_background_circles_poly)?"graphe.Set('chart.background.circles.poly',true);\n":"";
    $stri_config.=($this->int_background_circles_spacing!='')?"graphe.Set('chart.background.circles.spacing',".$this->int_background_circles_spacing.");\n":"";
    $stri_config.=($this->stri_centerx!='')?"graphe.Set('chart.centerx',".$this->stri_centerx.");\n":"";
    $stri_config.=($this->stri_centery!='')?"graphe.Set('chart.centery','".$this->stri_centery."');\n":"";
    $stri_config.=($this->int_circle!='')?"graphe.Set('chart.circle','".$this->int_circle."');\n":"";
    $stri_config.=($this->stri_circle_fill!='')?"graphe.Set('chart.circle.fill','".$this->stri_circle_fill."');\n":"";
    $stri_config.=($this->stri_circle_stroke!='')?"graphe.Set('chart.circle.stroke','".$this->stri_circle_stroke."');\n":"";
    $stri_config.=($this->stri_colors!='')?"graphe.Set('chart.colors',".$this->stri_colors.");\n":"";
    $stri_config.=($this->stri_colors_alpha!='')?"graphe.Set('chart.colors.alpha','".$this->stri_colors_alpha."');\n":"";
    $stri_config.=(count($this->arra_contextmenu)>0)?"graphe.Set('chart.contextmenu', ['".join("','",$this->arra_contextmenu)."']);\n":"";
    $stri_config.=($this->stri_events_click!='')?"graphe.Set('chart.events.click','".$this->stri_events_click."');\n":"";
    $stri_config.=($this->stri_events_mousemove!='')?"graphe.Set('chart.events.mousemove','".$this->stri_events_mousemove."');\n":"";
    $stri_config.=($this->int_gutter_bottom!='')?"graphe.Set('chart.gutter.bottom','".$this->int_gutter_bottom."');\n":"";
    $stri_config.=($this->int_gutter_left!='')?"graphe.Set('chart.gutter.left','".$this->int_gutter_left."');\n":"";
    $stri_config.=($this->int_gutter_right!='')?"graphe.Set('chart.gutter.right','".$this->int_gutter_right."');\n":"";
    $stri_config.=($this->int_gutter_top!='')?"graphe.Set('chart.gutter.top','".$this->int_gutter_top."');\n":"";
    $stri_config.=($this->stri_highlight_fill!='')?"graphe.Set('chart.highlight.fill','".$this->stri_highlight_fill."');\n":"";
    $stri_config.=($this->int_highlight_point_radius!='')?"graphe.Set('chart.highlight.point.radius','".$this->int_highlight_point_radius."');\n":"";
    $stri_config.=($this->stri_highlight_stroke!='')?"graphe.Set('chart.highlight.stroke','".$this->stri_highlight_stroke."');\n":"";
    $stri_config.=($this->bool_highlights)?"graphe.Set('chart.highlights',true);\n":"";
    $stri_config.=($this->bool_highlights_color)?"graphe.Set('chart.highlights.color',true);\n":"";
    $stri_config.=($this->int_highlights_radius!='')?"graphe.Set('chart.highlights.radius','".$this->int_highlights_radius."');\n":"";
    $stri_config.=(count($this->arra_key)>0)?"graphe.Set('chart.key', ['".join("','",$this->arra_key)."']);\n":"";
    $stri_config.=($this->stri_key_background!='')?"graphe.Set('chart.key.background','".$this->stri_key_background."');\n":"";
    $stri_config.=($this->stri_key_color_shape!='')?"graphe.Set('chart.key.color.shape','".$this->stri_key_color_shape."');\n":"";
    $stri_config.=($this->stri_key_colors!='')?"graphe.Set('chart.key.colors','".$this->stri_key_colors."');\n":"";
    $stri_config.=($this->stri_key_halign!='')?"graphe.Set('chart.key.halign','".$this->stri_key_halign."');\n":"";
    $stri_config.=($this->int_key_linewidth!='')?"graphe.Set('chart.key.linewidth','".$this->int_key_linewidth."');\n":"";
    $stri_config.=($this->stri_key_position!='')?"graphe.Set('chart.key.position','".$this->stri_key_position."');\n":"";
    $stri_config.=($this->bool_key_position_graph_boxed)?"graphe.Set('chart.key.position.graph.boxed',true);\n":"";
    $stri_config.=($this->bool_key_position_gutter_boxed)?"graphe.Set('chart.key.position.gutter.boxed',true);\n":"";
    $stri_config.=($this->stri_key_position_x!='')?"graphe.Set('chart.key.position.x','".$this->stri_key_position_x."');\n":"";
    $stri_config.=($this->stri_key_position_y!='')?"graphe.Set('chart.key.position.y','".$this->stri_key_position_y."');\n":"";
    $stri_config.=($this->bool_key_rounded)?"graphe.Set('chart.key.rounded',true);\n":"";
    $stri_config.=($this->bool_key_shadow)?"graphe.Set('chart.key.shadow',true);\n":"";
    $stri_config.=($this->stri_key_shadow_blur!='')?"graphe.Set('chart.key.shadow.blur','".$this->stri_key_shadow_blur."');\n":"";
    $stri_config.=($this->stri_key_shadow_color!='')?"graphe.Set('chart.key.shadow.color','".$this->stri_key_shadow_color."');\n":"";
    $stri_config.=($this->int_key_shadow_offsetx!='')?"graphe.Set('chart.key.shadow.offsetx','".$this->int_key_shadow_offsetx."');\n":"";
    $stri_config.=($this->int_key_shadow_offsety!='')?"graphe.Set('chart.key.shadow.offsety','".$this->int_key_shadow_offsety."');\n":"";
    $stri_config.=(count($this->arra_labels)>0)?"graphe.Set('chart.labels', ['".join("','",$this->arra_labels)."']);\n":"";
    $stri_config.=($this->stri_labels_axes!='')?"graphe.Set('chart.labels.axes','".$this->stri_labels_axes."');\n":"";
    $stri_config.=($this->stri_labels_background_fill!='')?"graphe.Set('chart.labels.background.fill','".$this->stri_labels_background_fill."');\n":"";
    $stri_config.=($this->bool_labels_boxed)?"graphe.Set('chart.labels.boxed',true);\n":"";
    $stri_config.=($this->int_labels_offset!='')?"graphe.Set('chart.labels.offset',".$this->int_labels_offset.");\n":"";
    $stri_config.=($this->int_linewidth!='')?"graphe.Set('chart.linewidth','".$this->int_linewidth."');\n":"";
    $stri_config.=($this->int_numxticks!='')?"graphe.Set('chart.numxticks','".$this->int_numxticks."');\n":"";
    $stri_config.=($this->int_numyticks!='')?"graphe.Set('chart.numyticks','".$this->int_numyticks."');\n":"";
    $stri_config.=($this->stri_radius!='')?"graphe.Set('chart.radius','".$this->stri_radius."');\n":"";
    $stri_config.=($this->bool_resizable)?"graphe.Set('chart.resizable',true);\n":"";
    $stri_config.=($this->stri_resize_handle_background!='')?"graphe.Set('chart.resize.handle.background','".$this->stri_resize_handle_background."');\n":"";
    $stri_config.=($this->int_scale_decimals!='')?"graphe.Set('chart.scale.decimals','".$this->int_scale_decimals."');\n":"";
    $stri_config.=($this->stri_scale_point!='')?"graphe.Set('chart.scale.point','".$this->stri_scale_point."');\n":"";
    $stri_config.=($this->stri_scale_round!='')?"graphe.Set('chart.scale.round','".$this->stri_scale_round."');\n":"";
    $stri_config.=($this->stri_scale_thousand!='')?"graphe.Set('chart.scale.thousand','".$this->stri_scale_thousand."');\n":"";
    $stri_config.=($this->stri_strokestyle!='')?"graphe.Set('chart.strokestyle','".$this->stri_strokestyle."');\n":"";
    
    
    $stri_config.=($this->stri_text_color!='')?"graphe.Set('chart.text.color','".$this->stri_text_color."');\n":"";
    $stri_config.=($this->stri_text_font!='')?"graphe.Set('chart.text.font','".$this->stri_text_font."');\n":"";
    $stri_config.=($this->int_text_size!='')?"graphe.Set('chart.text.size','".$this->int_text_size."');\n":"";
    $stri_config.=($this->stri_text_size_scale!='')?"graphe.Set('chart.text.size.scale','".$this->stri_text_size_scale."');\n":"";
    $stri_config.=($this->stri_title!='')?"graphe.Set('chart.title','".$this->stri_title."');\n":"";
    $stri_config.=(count($this->arra_tooltips)>0)?"graphe.Set('chart.tooltips', ['".join("','",$this->arra_tooltips)."']);\n":"";
    $stri_config.=($this->int_ymax!='')?"graphe.Set('chart.ymax','".$this->int_ymax."');\n":"";
    
    
             
  	$obj_javascripter->addFunction("
  	$(function()
  	{
    
         //Création du graphe
          var graphe = new RGraph.Radar('".$this->stri_id."', [".join(", ",  $this->arra_data)."]);
          
             //graphe.Set('chart.labels.axes', 'n');
             //graphe.Set('chart.labels.background.fill','red');
            // graphe.Set('chart.labels.boxed',true);
          
          //Configuration du graphe
          $stri_config
             
          
          //Construction de l'affichage du graphe
          graphe.Draw();    
          window.rgraph_radar=graphe;
                   
                      
                  

          
  	});
    ");
  	 
      $stri_class=($this->stri_class!="")?'class="'.$this->stri_class.'"':"";
      $stri_style=($this->stri_style!="")?'style="'.$this->stri_style.'"':"";
     
  		$stri_res=' <canvas id="'.$this->stri_id.'" width="'.$this->int_width.'" height="'.$this->int_height.'" '.$stri_class.' '.$stri_style.'>[No canvas support]</canvas>';
  		return  $stri_res.$obj_javascripter->javascriptValue();
  }
 

 
}

?>
