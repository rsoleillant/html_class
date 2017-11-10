<?php
/*******************************************************************************
Create Date : 05/12/2012
 ----------------------------------------------------------------------
 Class name : bar
 Version : 1.0
 Author : Mathieu TENA
 Description : element RGRAPH "bar" (permet de créer un graphique en bar )
********************************************************************************/
class bar extends serialisable {
   
   //**** attribute ************************************************************
    
   protected $arra_data=null;  // tableau des donnÃ©es
   protected $arra_labels=null;  // tableau des labels
   protected $arra_legend=null;  // tableau des legendes
   protected $arra_photo=null;  // tableau des photos

  
  //**** constructor ***********************************************************
  function __construct($arra_data,$arra_labels,$arra_legend,$arra_photo) 
  { 	
	$this->arra_data = $arra_data;
    $this->arra_labels = $arra_labels;
    $this->arra_legend = $arra_legend;
    $this->arra_photo = $arra_photo;
  }
 
  //**** setter ****************************************************************

  
  //**** getter ****************************************************************

  
  //**** public method ********************************************************* 
  
   
   
	public function htmlValue()
	{
	
	// var_dump($this->arra_labels);
	// echo"<pre>".join("', '", $this->arra_labels);

	$obj_javascripter=new javascripter();

	$obj_javascripter->addFunction("
	$(function()
	{
	  var bar = new RGraph.Bar('cvsbar', [".join(", ",  $this->arra_data)."])
            bar.Set('grouping', 'stacked');
            bar.Set('labels', ['".join("', '", $this->arra_labels)."']);
            bar.Set('labels.above', true);
            bar.Set('labels.above.decimals', 2);
            bar.Set('linewidth', 2);
            bar.Set('strokestyle', 'white');
            bar.Set('colors', ['#905696','#96565C','#5C9656','#969056','#569690','#565C96','#E3C981','#9DE381','#E3C681','#81E3C6']);
            bar.Set('shadow', true);
            bar.Set('shadow.offsetx', 1);
            bar.Set('shadow.offsety', 1);
            bar.Set('shadow.blue', 5);
            bar.Set('hmargin', 25);
            bar.Set('gutter.left', 45);
            bar.Set('background.grid.vlines', false);
            bar.Set('background.grid.border', false);
            bar.Set('axis.color', '#ccc');
            bar.Set('noyaxis', true);
            bar.Set('key', ['".join("', '",  $this->arra_legend)."']);
            bar.Set('key.position', 'gutter');
            bar.Set('key.position.x', 50);
            bar.Set('key.position.y', 20);
            bar.Set('key.colors', ['#905696','#96565C','#5C9656','#969056','#569690','#565C96','#E3C981','#9DE381','#E3C681','#81E3C6']);
            
            bar.ondraw = function (obj)
            {
                for (var i=0; i<obj.coords.length; ++i) {
                    obj.context.fillStyle = 'white';
                    RGraph.Text(obj.context, 'Verdana', 10, obj.coords[i][0] + (obj.coords[i][2] / 2), obj.coords[i][1] + (obj.coords[i][3] / 2),obj.data_arr[i].toString(),'center', 'center', null,null,null,true);
                }
            }
            bar.Set('chart.tooltips', ['".join("', '",  $this->arra_photo)."']);
            // This draws the chart
			RGraph.Effects.Bar.Grow(bar);
	});
");
	  
		$stri_res=' <canvas id="cvsbar" width="500" height="280">[No canvas support]</canvas>';
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

		$this->stri_id= $this->arra_sauv['id'];
		$this->arra_zone_draggable= $this->arra_sauv['zone_draggable'];
		$this->stri_zone_dropable= $this->arra_sauv['zone_dropable'];
		$this->bool_trash_option= $this->arra_sauv['trash_option'];
		$this->bool_icon_plus_minus= $this->arra_sauv['icon_plus_minus'];
    
		$this->arra_sauv = array();
	} */
}

?>
