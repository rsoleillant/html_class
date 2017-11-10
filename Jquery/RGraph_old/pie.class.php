<?php
/*******************************************************************************
Create Date : 04/12/2012
 ----------------------------------------------------------------------
 Class name : pie
 Version : 1.0
 Author : Mathieu TENA
 Description : element RGRAPH "pie" (permet de crÃ©er un graphique en camembert )
********************************************************************************/
class pie extends serialisable {
   
   //**** attribute ************************************************************
    
   protected $arra_data=null;  // tableau des donnÃ©es
   protected $arra_labels=null;  // tableau des labels
   protected $arra_tootlip=null;  // tableau des labels

  
  //**** constructor ***********************************************************
  function __construct($arra_data,$arra_labels,$arra_tooltips) 
  { 	
      echo "fichier :".__FILE__." ligne :".__LINE__."</br>";
      
	$this->arra_data = $arra_data;
         $this->arra_labels = $arra_labels;
         $this->arra_tootlip = $arra_tooltips;
  }
 
  //**** setter ****************************************************************

  
  //**** getter ****************************************************************

  
  //**** public method ********************************************************* 
  
   
   
	public function htmlValue()
	{
	
	
	//echo"<pre>".var_dump($this->arra_labels);

	$obj_javascripter=new javascripter();
		 
	$obj_javascripter->addFunction("
    $(function()
	{
        // Create the Pie chart
        var pie = new RGraph.Pie('cvs', [".join(", ",  $this->arra_data)."]);
		RGraph.Effects.Pie.RoundRobin(pie, null, function () {pie.split(0, 20);})
        pie.Set('chart.labels', ['".join("', '",  $this->arra_labels)."']);
        pie.Set('chart.tooltips', ['".join("', '",  $this->arra_tootlip)."']);
        pie.Set('chart.tooltips.event', 'onmousemove');
        pie.Set('chart.text.color', '#aaa');
        pie.Set('chart.exploded', []);
        pie.Set('chart.radius', 100);
        
pie.Set('chart.linewidth', 5);
pie.Set('chart.shadow', true);
pie.Set('chart.shadow.offsetx', 0);
pie.Set('chart.shadow.offsety', 0);
pie.Set('chart.shadow.blur', 25);
       

        pie.Draw();        
        
            
        // Add the click listener for the third segment
        pie.onclick = function (e, shape)
        {
            if (!pie.Get('chart.exploded') || !pie.Get('chart.exploded')[shape['index']]) {
                pie.split(shape['index'], 25);
            }
            
            e.stopPropagation();
        }
        
        // Add the mousemove listener for the third segment
        pie.onmousemove = function (e, shape)
        {
            e.target.style.cursor = 'pointer';
        }

        // Add the window click listener that resets the Pie chart
        pie.canvas.onclick_rgraph = function (e)
        {
            pie.Set('chart.exploded', []);
            RGraph.Redraw();
        }
    });
");
	  
		$stri_res=' <canvas id="cvs" width="400" height="180">[No canvas support]</canvas>';
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
