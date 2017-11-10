<?php

/*******************************************************************************
Create Date : 01/01/2009
 ----------------------------------------------------------------------
 Class name : pdf_report_generator
 Version : 1.0
 Author : Rémy Soleillant
 Description : 
********************************************************************************/
include_once("includes/classes/fpdf153/pdf.php");

class pdf_report_generator extends report_generator 
{
 //**** attribute *************************************************************
 protected $obj_pdf; //L'objet pdf à utiliser
 protected $int_current_cell; //Le numéro de la cellule courrante
 protected $int_total_size; //La largeur total de toutes les colonnes
 
 protected $stri_border=1;    //Style de bordure de la cellule courrante
 protected $stri_align="L";     //Style d'alignement de la cellule courrante
 protected $bool_fill=false;      //Style coloration de la cellule courrante

  /*************************************************************
  Permet de générer le rapport en pdf
  
  parametres : 			         
  retour :          
  **************************************************************/     
  function __construct($sql,$title="",$sub_title="",$file_name=__FILE__)  
  {
   $this->obj_pdf=new PDF();
   $obj_pdf=$this->obj_pdf;
   $this->int_current_cell=0;
   $obj_pdf->AliasNbPages();
	 $obj_pdf->AddPage();
    
   report_generator:: __construct($sql,$title,$sub_title,$file_name) ;
  }   
 

 //**** setter *************************************************************
 
 //**** getter *************************************************************
 
 //**** other method *******************************************************
  /*************************************************************
  Permet d'ajouter une ligne au rapport
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function addLine($stri_contain)
  {
   $obj_pdf=$this->obj_pdf;
   $obj_pdf->Cell($this->int_total_size,5,$stri_contain,$this->stri_border,1,$this->stri_align,$this->bool_fill);
  }

 

 /*************************************************************
  Permet d'ajouter une cellule au rapport.
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function addCell($stri_contain)
  {
   $obj_pdf=$this->obj_pdf;
   $int_cell_size=$this->arra_column_width[$this->int_current_cell];
   $this->int_current_cell++;
   
   $obj_pdf->Cell($int_cell_size,5,$stri_contain,$this->stri_border,0,$this->stri_align,$this->bool_fill);
  }

 

 /*************************************************************
  Permet de créer le fichier contenant le rapport.
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function makeFile()
  {
   	 $obj_pdf=$this->obj_pdf;
     $obj_pdf->Output($this->stri_path."/".$this->stri_file_name,'F');
  }
  
   /*************************************************************
  Permet de créer une nouvelle ligne
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function newLine()
  {
   $obj_pdf=$this->obj_pdf;
   $this->int_current_cell=0;
   $obj_pdf->Cell($this->int_total_size,5," ",0,1);
  }
  
   /*************************************************************
  Permet de prendre en compte les dimensions des colonnes.
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public  function sizeColumn($arra_size="")
  {
  if($arra_size=="")
   {$arra_size=$this->arra_column_width;}
   
   $this->int_total_size=array_sum($arra_size);
  }
  
   /*************************************************************
  Permet d'appliquer un style de présentation dont le numéro est passé en paramètre
  
  parametres : 	$int_num_style : le numéro du style à appliquer		         
  retour :          
  **************************************************************/     
  public  function applyStyle($int_num_style)
  {
   //style par défaut
   $this->stri_border=0;
   $this->stri_align ="L"; 
   $this->bool_fill=false;
   $this->obj_pdf->setFillColor(0);  
   $this->obj_pdf->SetFontSize(12);
   switch($int_num_style)
   {
    case 1: //titre
    $this->stri_border=0;
    $this->stri_align ="C"; 
    $this->bool_fill=false;
    $this->obj_pdf->SetFontSize(18);  
    break;
    case 2: //sous titre
    $this->stri_border=0;
    $this->stri_align ="C"; 
    $this->bool_fill=false;
    $this->obj_pdf->SetFontSize(16);  
    break;
    case 3: //entete
    $this->stri_border=1;
    $this->stri_align ="L"; 
    $this->bool_fill=1;
    $this->obj_pdf->setFillColor(102,51,255);   
    break;
    case 4: //ligne du rapport
    $this->stri_border=1;
    $this->stri_align ="L"; 
    $this->bool_fill=0; 

    break;

   }
    
   
  }
}
?>
