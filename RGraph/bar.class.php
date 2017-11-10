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
   protected $int_width=450;  // largeur
   protected $int_height=300;  // hauteur
   protected $stri_grouping=false;  // groupement des valeurs
   protected $stri_label=true;  // label

  
  //**** constructor ***********************************************************
  function __construct($arra_data,$arra_labels,$arra_legend,$arra_photo) 
  { 	
	$this->arra_data = $arra_data;
    $this->arra_labels = $arra_labels;
    $this->arra_legend = $arra_legend;
    $this->arra_photo = $arra_photo;
   
  }
 
  //**** setter ****************************************************************
  public function setHeight($mixed_value){$this->int_height=$mixed_value;}
  public function setGrouping($mixed_value){$this->stri_grouping=$mixed_value;}
  public function setLabel($mixed_value){$this->stri_label=$mixed_value;}
  public function setWidth($mixed_value){$this->int_width=$mixed_value;}
  public function setId($mixed_value){$this->stri_id=$mixed_value;}
  
  //**** getter ****************************************************************
  public function getId(){return $this->stri_id;}
  
  //**** public method ********************************************************* 
  

  
  //**** public method ********************************************************* 
  
   
   
	public function htmlValue()
	{
	
	if($this->stri_id=="")
	{$this->stri_id="cvsbar_".str_replace('.','_',microtime(true));}
	
	$obj_javascripter=new javascripter();

	$obj_javascripter->addFunction("
	$(function()
	{
		if(!$('#".$this->stri_id."')[0].initialized)
		{  		  
			var bar = new RGraph.Bar('".$this->stri_id."', [".join(", ",  $this->arra_data)."])
           
            bar.Set('labels', ['".join("', '", $this->arra_labels)."']);
           if('".$this->stri_label."'==true)
			{
				bar.Set('labels.above', true);
				bar.Set('labels.above.angle', 90);
			}
			if('".$this->stri_grouping."'==true) bar.Set('grouping', 'stacked');
            bar.Set('linewidth', 1);
            bar.Set('strokestyle', 'white');
            bar.Set('colors', ['#905696','#96565C','#5C9656','#969056','#569690','#565C96','#E3C981','#9DE381','#E3C681','#81E3C6']);
            bar.Set('key', ['".join("','", $this->arra_legend)."']);
			bar.Set('chart.key.position', 'gutter');
			bar.Set('chart.key.interactive', true);
			bar.Set('chart.gutter.left', 70);
			bar.Set('key.position.x', 50);
			bar.Set('chart.key.interactive', true);
            bar.Set('key.position.y', 0);
			
            bar.Set('background.grid.vlines', false);
            bar.Set('background.grid.hlines', false);
            bar.Set('background.grid.border', false);
            bar.Set('axis.color', '#ccc');	
			 //bar.Set('variant', '3d');
             bar.Set('strokestyle', 'transparent');
			
			
            bar.Set('key.colors', ['#905696','#96565C','#5C9656','#969056','#569690','#565C96','#E3C981','#9DE381','#E3C681','#81E3C6']);
            
            // This draws the chart
		//RGraph.Effects.Bar.Grow(bar);
		bar.Draw();
		}
		$('#".$this->stri_id."')[0].initialized=true;      	
	});
");
	  
		$stri_res=' <canvas id="'.$this->stri_id.'" width="'.$this->int_width.'" height="'. $this->int_height.'">[No canvas support]</canvas>';
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
