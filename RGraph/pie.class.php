<?php
/*******************************************************************************
Create Date : 04/12/2012
 ----------------------------------------------------------------------
 Class name : pie
 Version : 1.0
 Author : Mathieu TENA
 Description : element RGRAPH "pie" (permet de cr ©er un graphique en camembert )
********************************************************************************/
class pie extends serialisable {
   
   //**** attribute ************************************************************
    
   protected $arra_data=null;  // tableau des donn ©es
   protected $arra_labels=null;  // tableau des labels
    protected $int_width=470;  // largeur
   protected $int_height=300;  // hauteur

   protected $stri_id="";
  //**** constructor ***********************************************************
  function __construct($arra_data,$arra_labels,$arra_tooltips) 
  { 	
	  $this->arra_data = $arra_data;
    $this->arra_labels = $arra_labels;
    $this->arra_tooltips = $arra_tooltips;
  }
 
  //**** setter ****************************************************************
  public function setId($mixed_value){$this->stri_id=$mixed_value;}
  public function setHeight($mixed_value){$this->int_height=$mixed_value;}
  public function setWidth($mixed_value){$this->int_width=$mixed_value;}
  //**** getter ****************************************************************
  public function getId(){return $this->stri_id;}
  //**** public method ********************************************************* 
  
   
   
public function htmlValue()
{
  if($this->stri_id=="")
  {$this->stri_id="cvs_".str_replace('.','_',microtime(true));}
   
	$obj_javascripter=new javascripter();
	
  $stri_option=" {colors: }";
  
  $obj_javascripter->addFunction("
  $(function()
	{
       //alert($('#".$this->stri_id."')[0].initialized);
       if(!$('#".$this->stri_id."')[0].initialized)
       {  
          // Create the Pie chart
          var pie = new RGraph.Pie('".$this->stri_id."', [".join(", ",  $this->arra_data)."]);
          //RGraph.Effects.Pie.RoundRobin(pie, null, function () {/*pie.Explode(0,20);*/})      
         
         //pie.Set('colors',['#ccf','#aa0','#ff0','#ccf']);
          pie.Set('chart.labels', ['".join("', '",  $this->arra_labels)."']);
		  //pie.Set('chart.tooltips', ".join(", ",  $this->arra_tooltips).");
		  pie.Set('chart.tooltips', ['".join("', '",  $this->arra_tooltips)."']);
          pie.Set('chart.text.color', '#aaa');
          pie.Set('chart.exploded', 5);
          pie.Set('chart.radius', 100);
		  pie.Set('chart.text.size', 7);
		  pie.Set('labels.sticks', 1);
      
          pie.Draw();
                    
        // Add the click listener for the third segment
        /*pie.onclick = function (e, shape)
        {
            if (!pie.Get('exploded') || !pie.Get('chart.exploded')[shape['index']]) {
                pie.Explode(shape['index'], 25);
            }              
            e.stopPropagation();
        } */
             
        // Add the mousemove listener for the third segment
        /*pie.onmousemove = function (e, shape)
        {
            e.target.style.cursor = 'pointer';
        }*/

        // Add the window click listener that resets the Pie chart
        pie.canvas.onclick_rgraph = function (e)
        {
            pie.Set('chart.exploded', []);
            RGraph.Redraw();
        }
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
		//d s rialisation de la classe 

		$this->stri_id=$this->arra_sauv['id'];
		$this->arra_zone_draggable=$this->arra_sauv['zone_draggable'];
		$this->stri_zone_dropable=$this->arra_sauv['zone_dropable'];
		$this->bool_trash_option=$this->arra_sauv['trash_option'];
		$this->bool_icon_plus_minus=$this->arra_sauv['icon_plus_minus'];
    
		$this->arra_sauv = array();
	} */
}

?>
