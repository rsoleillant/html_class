<?php

/*******************************************************************************
Create Date : 01/01/2009
 ----------------------------------------------------------------------
 Class name : xls_report_generator
 Version : 1.0
 Author : R�my Soleillant
 Description : 
********************************************************************************/
//utilise classe xml_excel

class xls_report_generator extends report_generator 
{
 //**** attribute *************************************************************
  protected $obj_xls;                 //L'objet creer_excel_xml � utiliser pour la g�n�ration du rapport
  protected $bool_opened_line=false;  //sert � savoir si la ligne courrante � �t� ouverte
  
  protected $stri_style="s25";        //le style de la cellule courrante
  protected $stri_type="String";               //le type de la cellule courrante
  protected $float_line_heigth="40.75";
  /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe file_reader_writer   
   *                        
   **************************************************************/         
  function __construct($sql,$title="",$sub_title="",$file_name="fichier")   
  {
 
   report_generator:: __construct($sql,$title,$sub_title,$file_name) ;
   $stri_file_name=$this->stri_path."".$this->stri_file_name;
   //$stri_file_name=$file_name;  

  //echo $stri_file_name;
    $this->obj_xls=new xml_excel($stri_file_name,$this->nb_col,$this->nb_line+2,60,61,0);
   }   

 //**** setter *************************************************************
 
 //**** getter *************************************************************
 
 //**** other method *******************************************************
 
   /*************************************************************
  Permet d'ajouter une ligne au rapport
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function addLine($stri_line)
  { 
   
    $xml=$this->obj_xls;
    $xml->entrer($this->float_line_heigth);
    $xml->ligne($stri_line,'String',$this->stri_style,true,$this->nb_col-1);
    $xml->sortir();
  }

 

 /*************************************************************
  Permet d'ajouter une cellule au rapport.
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function addCell($stri_contain)
  {
  
   $xml=$this->obj_xls;
   //$xml->ligne($stri_contain,'String',$this->stri_style);
    
   $xml->ligne($stri_contain,$this->stri_type,$this->stri_style);

  }

 

 /*************************************************************
  Permet de cr�er le fichier contenant le rapport.
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function makeFile()
  {
   $xml=$this->obj_xls;
   if($this->bool_opened_line)
   {
    $xml->sortir();
    $this->bool_opened_line=false;
   }
  
  $res = $xml->creerlefichier();
  
  }
  
  /*************************************************************
  Permet de cr�er une nouvelle ligne
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function newLine()
  {
   $xml=$this->obj_xls;
   if(!$this->bool_opened_line)
   {
    $xml->entrer($this->float_line_heigth);
    $this->bool_opened_line=true;
   }
   else
    {
     $xml->sortir();
     $xml->entrer($this->float_line_heigth);
    } 
  }
  
  /*************************************************************
  M�thode g�n�rique permettant de g�n�rer la partie r�sultat du  rapport
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public  function generateResultPart()
  {
   
   $arra_type_colonne=array();
   $arra_style_colonne=array("DateTime"=>"S_datetime","String"=>"s25","Number"=>"s25");//pour g�rer les style de repr�sentation sp�cifique 
   
   //pose des donn�es
   foreach($this->arra_res as $arra_one_res)
   {
    foreach($arra_one_res as $stri_key=>$stri_res)
    {
     //- v�rification de typage de la colonne
     $stri_type=$this->checkExcelType($stri_res);//v�rification sur chaque cellule
     
     //- v�rification avec effet m�moire : probl�me si une donn�e erron�e au milieu de donn�e num�rique
     /*$stri_type=$arra_type_colonne[$stri_key];//par d�faut, on essais de r�cup�rer le type d�j� calcul� 
     if(!isset($arra_type_colonne[$stri_key]))//si le type de colonne n'est pas d�termin�
     {
        if($stri_res=='')//si pas d'info, type string mais pas d'enregistrement du type colonne
        {$stri_type="String";}
        else
        {
          $stri_type=$this->checkExcelType($stri_res);
          $arra_type_colonne[$stri_key]=$stri_type;//d�finition du type de la colonne
        }
     }*/
        
     $this->stri_type=$stri_type;//d�finition du type de la cellule courrante
     $this->stri_style=$arra_style_colonne[$stri_type];
    
     $this->addCell($stri_res);
    }
    $this->newLine();    
   }
  }
  
   /*************************************************************
  Permet de v�rifier le type excel de donn�e
  
  parametres : string : la chaine � tester 			         
  retour : string : le type de chaine         
  **************************************************************/     
  public  function checkExcelType($stri_valeur)
  {
     
   if(is_numeric($stri_valeur))
   {
    return "Number";
   }
   $obj_date=new date();
   if($obj_date->detectAndMatchFormat($stri_valeur)=="YYYY-MM-DD")//s'il s'agit d'une date
   {
    return "DateTime";
   }
   return "String";
  }

  
  /*************************************************************
  Permet de prendre en compte les dimensions des colonnes.
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public  function sizeColumn()
  {
  // la taille des cellules est d�termin�e automatiquement
  }
  
  /*************************************************************
  Permet d'appliquer un style de pr�sentation dont le num�ro est pass� en param�tre
  
  parametres : 	$int_num_style : le num�ro du style � appliquer		         
  retour :          
  **************************************************************/     
  public  function applyStyle($int_num_style)
  {
   $this->stri_style="s25";
   switch($int_num_style)
   {
    case 1: //titre
    $this->stri_style="s23";
    break;
    case 2: //sous titre
    $this->stri_style="s21";
    break;
    case 3: //entete
    $this->stri_style="s24";
    break;
    case 4: //ligne du rapport
    $this->stri_style="s21";

    break;

   }
  }
}
?>
