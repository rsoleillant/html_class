<?php
/*******************************************************************************
Create Date : 22/05/2006
 ----------------------------------------------------------------------
 Class name : graph
 Version : 1.1
 Author : Rémy Soleillant
 Description : permet de créer des courbes dans un graphique
********************************************************************************/
@include_once("includes/classes/fpdf153/pdf.php");
class graph {
   
   /*attribute***********************************************/
   
   protected $float_space_x;
   protected $float_space_y;
   protected $int_height_graph;
   protected $int_width_graph;
   protected $float_x_start_point;
   protected $float_y_start_point;
   protected $float_x_end_point;
   protected $float_y_end_point;
   protected $arra_curve;  
  
  
  /* constructor***************************************************************/
   function __construct($height,$width,$space_x=5,$space_y=5) {
       $this->int_height_graph=$height;
       $this->int_width_graph=$width;
       $this->int_space_x=$space_x;
       $this->int_space_y=$space_y;
       
   }
 
   /*setter*********************************************************************/
 
  public function setSpaceX($value)
  {$this->int_space_x=$value;}
  
  public function setSpaceY($value)
  {$this->int_space_y=$value;}
  
  /*getter**********************************************************************/
  public function getSpaceX($value)
  {return $this->int_space_x;}
  
  public function getSpaceY($value)
  {return $this->int_space_y;}
  
  public function getIemeCurve($int)
  {return $this->arra_curve[$int];}
  
  public function getIemePoint($num_curve,$num_point)
  {return $this->arra_curve[$num_curve]['points'][$num_point];}
  /*other method****************************************************************/
  public function addCurve($bgcolor="000:000:000")
  {$this->arra_curve[count($this->arra_curve)]['color']=$bgcolor;}
  
  public function addPoint($num_curve,$x,$y,$label_x,$label_x)
  {$nbr_point=count($this->arra_curve[$num_curve]['points']);
  $this->arra_curve[$num_curve]['points'][$nbr_point]['x']=$x;
  $this->arra_curve[$num_curve]['points'][$nbr_point]['y']=$y;
  $this->arra_curve[$num_curve]['points'][$nbr_point]['label_x']=$label_x;
  $this->arra_curve[$num_curve]['points'][$nbr_point]['label_y']=$label_y;
  //echo "j'ai ajouté le point $x,$y ".$this->arra_curve[$num_curve]['points'][$nbr_point]['x']." ".$this->arra_curve[$num_curve]['points'][$nbr_point]['y']."<br>";
  }
  
  public function addXLabel($value)
  {$this->arra_label_x[count($this->arra_label_x)]=$value;}
  
  public function addYLabel($value)
  {$this->arra_label_y[count($this->arra_label_y)]=$value;}
  
  public function addScale($pdf)
  {
    $pdf->SetLineWidth(0.1);
    $float_x=$this->float_x_start_point;
    $i=0;
    echo "le label a ".count($this->arra_label_x)." valeurs a placer<br>";
    $float_x_scale_unit=$this->int_width_graph/(count($this->arra_label_x));
    while(($float_x<=$this->float_x_end_point)&&($i<count($this->arra_label_x)))
    {$pdf->Line($float_x,$this->float_y_end_point,$float_x,$this->float_y_end_point+2);
     
     $pdf->setXY($float_x-3,$this->float_y_end_point+5);
     $pdf->Cell($this->float_space_x,5,$this->arra_label_x[$i],0,0);
     $float_x+=$float_x_scale_unit;
     $i++;
     echo "boucle $i<br>";
    }
  
  }
  
  public function drawGraph()
  {
   $pdf = new PDF('L');
   $pdf->AliasNbPages();
   $pdf->AddPage();
   //$pdf->Cell(50,5," ",1,1,'',1);  
   $x_start=$pdf->getX();
   $y_start=$pdf->getY();
   $y_end=$y_start+$this->int_height_graph;
   $x_end=$x_start+$this->int_width_graph;
   
   $this->float_x_start_point=$x_start;
   $this->float_y_start_point=$y_start;
   $this->float_x_end_point=$x_end;
   $this->float_y_end_point=$y_end;
   
   $pdf->SetLineWidth(0.5);
   $pdf->Line($x_start,$y_start,$x_start, $y_end);
   $pdf->Line($x_start,$y_end,$x_end,$y_end);
   $arra_one_curve=$this->arra_curve[0];
   $min_x=$arra_one_curve['points'][0]['x'];
   $min_y=$arra_one_curve['points'][0]['y'];
   foreach($arra_one_curve['points'] as $arra_point)
     {
      $max_x=max($max_x,$arra_point['x']);
      $max_y=max($max_y,$arra_point['y']);
      $min_x=min($min_x,$arra_point['x']);
      $min_y=min($min_y,$arra_point['y']);
     }
     //unit are based on the first curve in graph
     $x_unit=$this->int_width_graph/($max_x-$min_x);
     $y_unit=$this->int_height_graph/$max_y;
    $this->float_space_x=$x_unit;
    $this->float_space_y=$y_unit;
    $this->addScale($pdf);
   
    //echo "l'unité en x $x_unit, x min $min_x, x max $max_x<br>";
    //echo "le point maximal en y ".$max_y*$y_unit.", pour la valeur $max_y<br>";
   foreach($this->arra_curve as $arra_one_curve)
   {
     //foreach($arra_one_curve['points'] as $key=>$arra_point)
     $temp_curve=$arra_one_curve['points'];
     //var_dump($temp_curve);
     $nbr_point=count($temp_curve);
   //echo "il y a  $nbr_point a afficher<br>";
   $nbr_point--;
     for($key=$nbr_point;$key>-1;$key--)
     { 
      $arra_point=$temp_curve[$key];
     // var_dump($arra_point);
     $retouche=$nbr_point-$arra_point['x']+1;
      $x_relative=$arra_point['x']*$x_unit;
      $y_relative=$arra_point['y']*$y_unit;
      $x=($x_start)+$x_relative;
      //echo "la taille du graphe est ".$this->int_graph_height."<br>";
      $y=$y_relative-$y_start-$this->int_height_graph;
      $y=-$y;
      if($key==$nbr_point-1)
      {
       $previous_x=$x;
       $previous_y=$y;
      }
      //echo "le x réel".$arra_point['x']." ,le x relatif est ".$x_relative." le x absolut $x <br>";
      echo $arra_point['x']."<br>";
      $pdf->Line($previous_x,$previous_y,$x,$y);
      //echo "j'ajoute une ligne du point ($previous_x,$previous_y) à ($x,$y)<br>";
      $previous_x=$x;
      $previous_y=$y;
     }
   }
   $pdf->Output("modules/Contact/graphe.pdf");
  }
}

?>
