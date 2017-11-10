<?php
/*******************************************************************************
Create Date : 7/06/2006
 ----------------------------------------------------------------------
 Class name : tableau à double entrée
 Version : 1.1
 Author : Rémy Soleillant
 Description : permet de gérer un tableau à deux entrées
********************************************************************************/
include_once("font.class.php");
include_once("table.class.php");

class double_entry_table extends table{
   
   /*attribute***********************************************/
   protected $arra_horizontal_header=array();
   protected $arra_vertical_header=array();
   protected $arra_element=array();
   protected $obj_font_vertical_style;
   protected $obj_font_horizontal_style;
   protected $stri_vertical_header_color="#FFFFFF";
   protected $stri_horizontal_header_color="#FFFFFF";
   
   /* constructor***************************************************************/
   function __construct($stri_class) {
       $this->stri_class=$stri_class;
       $this->obj_font_vertical_style=new font();
      $this->obj_font_horizontal_style=new font();
       
   }
  
  
   /*setter*********************************************************************/
  public function setFontVerticalStyle($obj_font)
  {$this->obj_font_vertical_style=$obj_font;}
  
  public function setFontHorizontalStyle($obj_font)
  {$this->obj_font_horizontal_style=$obj_font;}
  
  public function setElement($int_x,$int_y,$value)
  {$this->arra_element[$int_x][$int_y]=$value;}
  
  public function setHorizontalHeaderColor($value)
  {$this->stri_horizontal_header_color=$value;}
  
  public function setVerticalHeaderColor($value)
  {$this->stri_vertical_header_color=$value;}
  
  
  public function setElementByHeader($stri_h,$stri_v,$value)
  {
   
   $i=0;
   $j=0;
   $nbr_line=count($this->arra_vertical_header);
   $nbr_colum=count($this->arra_horizontal_header);
  // echo "<br> il y a $nbr_line ligne et $nbr_colum colones<br>";
   while(($i<$nbr_colum)&&($this->arra_horizontal_header[$i]!=$stri_h))
   {$i++; }
   while(($j<$nbr_line)&&($this->arra_vertical_header[$j]!=$stri_v))
   {$j++;}
   
   if(($j<$nbr_line)&&($i<$nbr_colum))
   {$this->setElement($j,$i,$value);
   
    
    return true;
   }
   return false;
  
  }
  /*getter**********************************************************************/
  public function getVerticalHeaderColor()
  {return $this->stri_vertical_header_color;}

  public function getHorizontalHeaderColor()
  {return $this->stri_horizontal_header_color;}
 

  public function getElement($int_x,$int_y)
  {return $tab->arra_element[$int_x][$int_y];}
  
  
  /*other method****************************************************************/
  public function addVerticalHeader($value)
  {$this->arra_vertical_header[count($this->arra_vertical_header)]=$value;}
  
  public function addHorizontalHeader($value)
  {$this->arra_horizontal_header[count($this->arra_horizontal_header)]=$value;}
  
  
  public function myHtmlValue()
  {
    $html_table=new table();
    $tr1=new tr();
    $tr1->addTd("&nbsp;");
    $nbr_colum=count($this->arra_horizontal_header);
    $nbr_line=count($this->arra_vertical_header);
    //creation of the first line: horizontal header
    foreach($this->arra_horizontal_header as $tab)
    { 
     $font=$this->obj_font_horizontal_style;
     $font->setValue($tab);
     $td=$tr1->addTd($font->htmlValue());
     $td->setBgcolor($this->stri_horizontal_header_color);
    }
    $html_table->insertTr($tr1);
    for($i=0;$i<$nbr_line;$i++)
    {
      $tr=new tr();
      $font=$this->obj_font_vertical_style;
      $font->setValue($this->arra_vertical_header[$i]);
      $td=$tr->addTd($font->htmlValue());
      $td->setBgcolor($this->stri_vertical_header_color);
      for($j=0;$j<$nbr_colum;$j++)
      {$tr->addTd($this->arra_element[$i][$j])->setAlign('center');} 
      $html_table->insertTr($tr);
    }
    return $html_table->htmlValue();
  }
 
}
?>
