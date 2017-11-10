<?php
/*******************************************************************************
Create Date : 25/07/2013
 ----------------------------------------------------------------------
 Class name : rgraph_line
 Version : 1.0
 Author : Mathieu TENA
 Description : element RGRAPH "line" (permet de créer un graphique en ligne )
********************************************************************************/
class rgraph_line extends serialisable {
   
   //**** attribute ************************************************************
    
   protected $arra_data=null;  // tableau des donnÃ©es
   protected $arra_labels=null;  // tableau des labels
   protected $arra_tooltips=null;  // tableau des tooltips
   protected $arra_legend=null;  // tableau des legendes

   /*les données de arra_label et arra_tooltips doivent être de la forme
   
   exemple : arra_label[0]=['2013-07-01','2013-07-08','2013-07-15','2013-07-22']
             ....
   */
   protected $stri_id="";
     
  //**** constructor ***********************************************************
  function __construct($arra_data,$arra_labels,$arra_tooltips,$arra_legend) 
  { 	
	  $this->arra_data = $arra_data;
    $this->arra_labels = $arra_labels;
    $this->arra_tooltips = $arra_tooltips;
    $this->arra_legend = $arra_legend;
  }
 
  //**** setter ****************************************************************
  public function setId($mixed_value){$this->stri_id=$mixed_value;}
  
  //**** getter ****************************************************************
  public function getId(){return $this->stri_id;}
  
  //**** public method ********************************************************* 
  
   
   
public function htmlValue()
{
  if($this->stri_id=="")
  {$this->stri_id="cvsline_".str_replace('.','_',microtime(true));}
  
	$obj_javascripter=new javascripter();

	$obj_javascripter->addFunction("
	$(function()
	{ 
      if(!$('#".$this->stri_id."')[0].initialized)
      {  
    	  var line = new RGraph.Line('".$this->stri_id."', ".join(", ",  $this->arra_data).");
        line.Set('chart.grouping', 'stacked');
        line.Set('chart.labels', ".join(", ", $this->arra_labels).");
        line.Set('chart.tooltips', ".join(", ",  $this->arra_tooltips).");
        line.Set('chart.colors', ['#905696','#96565C','#5C9656','#969056','#569690','#565C96','#E3C981','#9DE381','#E3C681','#81E3C6']);
        line.Set('chart.key', ['".join("', '",  $this->arra_legend)."']);
        line.Set('chart.key.colors', ['#905696','#96565C','#5C9656','#969056','#569690','#565C96','#E3C981','#9DE381','#E3C681','#81E3C6']);
		line.Set('chart.key.position', 'gutter');
		
		line.Set('chart.curvy', true);
        line.Set('chart.curvy.tickmarks', true);
        line.Set('chart.curvy.tickmarks.fill', null);
        line.Set('chart.curvy.tickmarks.stroke', '#aaa');
        line.Set('chart.curvy.tickmarks.stroke.linewidth', 2);
        line.Set('chart.curvy.tickmarks.size', 5);
        line.Set('chart.linewidth', 3);
        line.Set('chart.xaxispos', 'bottom');
        line.Set('chart.hmargin', 5);
        line.Set('chart.key.interactive', true);
        line.Set('chart.tickmarks', 'circle');
        line.Set('chart.text.size', 8);
        line.Set('chart.gutter.left', 55);

        
        //RGraph.Effects.Line.jQuery.Trace(line);
	  line.Draw();
      }
      $('#".$this->stri_id."')[0].initialized=true;      			
  
	}); 
");
	  
		$stri_res=' <canvas id="'.$this->stri_id.'" width="480" height="240">[No canvas support]</canvas>';

    return  $stri_res.$obj_javascripter->javascriptValue();
	}
  
	//**** method for serialization **********************************************
	/*public function __sleep() 
	{  
		//serialisation de la classe 
		$this->arra_sauv['id']= $this->stri_id;
		$this->arra_sauv['zone_draggable']= $this->arra_zone_draggable;
		$this->arra_sauv['zone_dropable']= $this->stri_zone_dropable;
		$this->arra_sauv['trash_option']= $this->bool_trash_option;
		$this->arra_sauv['icon_plus_minus']= $this->bool_icon_plus_minus;
    
		return array('arra_sauv');
	}
  
	public function __wakeup() 
	{
		//désérialisation de la classe 

		$this->stri_id=$this->arra_sauv['id'];
		$this->arra_zone_draggable=$this->arra_sauv['zone_draggable'];
		$this->stri_zone_dropable=$this->arra_sauv['zone_dropable'];
		$this->bool_trash_option=$this->arra_sauv['trash_option'];
		$this->bool_icon_plus_minus=$this->arra_sauv['icon_plus_minus'];
    
		$this->arra_sauv = array();
	} */
}

?>
