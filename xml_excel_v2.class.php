<?php

/*******************************************************************************
Create Date : 01/01/2009
 ----------------------------------------------------------------------
 Class name : xml_excel_v2
 Version : 1.0
 Author : Alexandre Escot
 Description : 
********************************************************************************/

class xml_excel_v2 
{
 //**** attribute ***********************************************************
   protected $arra_contenu;//tableau qui contient chaque partie du fichier excel :
  //arra_contenu[0] = paramètre
  //arra_contenu[1] = style
  //arra_contenu[2] = feuille

  protected $nomdufichier;//nom du fichier excel
  protected $bool_color = false;
 
 
 //**** constructor ******************************************************** 

  /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe  xml_excel_v2  
   *                        
   **************************************************************/         
  function __construct($nomdufichier) 
  {
    ////////////////////////////////////////////////////////////////////////////////////
  //$nomdufichier => contient le chemain et le nom sous lequel le fichier va etre créer
  //$nb_col => correspond au nombre de colonnes pour le fichier xml
  //$nb_ligne => correspond au nombre de ligne pour le fichier xml
  //$taill_col_defaut => défini une taille de colonne par défaut
  //$taill_col_spe => défini une taille de colonne particulière si besoin
  //$nb_col_spe => défini le nombre de colonne auquel sera appliqué la taille spécifique
  //////////////////////////////////////////////////////////////////////////////////////
  
   
  $this->nomdufichier = $nomdufichier ;
 
   $this->arra_contenu[0] = '<?xml version="1.0" encoding="iso-8859-1"?'.'>
<?mso-application progid="Excel.Sheet"?'.'>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>rjanisset</Author>
  <LastAuthor>rjanisset</LastAuthor>
  <Created>2008-04-28T13:39:45Z</Created>
  <Company>a-SIS</Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>10230</WindowHeight>
  <WindowWidth>11595</WindowWidth>
  <WindowTopX>480</WindowTopX>
  <WindowTopY>60</WindowTopY>
  <ProtectStructure>False</ProtectStructure>
  <ProtectWindows>False</ProtectWindows>
 </ExcelWorkbook>';
 
 $this->arra_contenu[1] = '<Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom"/>
   <Borders/>
   <Font/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>';
  
  
  }   
 
 //**** setter *************************************************************
 
 //**** getter *************************************************************
 
 //**** other method *******************************************************
 
  /*************************************************************
  ajoute un style a la feuille excel
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function addStyle($str_style)
  {
    $this->arra_contenu[1] .= $str_style;
  }
 

 /*************************************************************
  ajoute une feuille au classeur excel
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function addFeuille($str_name,$nb_col,$nb_ligne,$taill_col_defaut='60',$taill_col_spe='61',$nb_col_spe='1')
  {
  $nb_col_span=$nb_col-1;
  $this->arra_contenu[2] .= '<Worksheet ss:Name="'.$str_name.'">
  <Table ss:ExpandedColumnCount="'.$nb_col.'" ss:ExpandedRowCount="'.$nb_ligne.'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="'.$taill_col_defaut.'">';   
 
  }
 
  
  public function ajoutAttributFeuille($str_attribut)
  {
    $this->arra_contenu[2] .= $str_attribut;
  }
  
  
 /*************************************************************
  créer le fichier excel
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function creerlefichier()
  {  
  //regroupement du tableau de contenu
  $contenu = $this->arra_contenu[0].$this->arra_contenu[1].'</Styles>'.$this->arra_contenu[2].'</Workbook>';
  //Ouverture du fichier
  $fichier = fopen($this->nomdufichier,'w+');
  //Ecriture du contenu du buffer
  $rep=fwrite($fichier,$contenu);  
  //Fermeture du fichier
  fclose($fichier) ;
  //Retour de la réponse, pour pouvoir verifier les erreurs
 
  return $rep;
  }
 

 /*************************************************************
  ferme la feuille
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function fermerFeuille()
  {
   $this->arra_contenu[2] .='</Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Header x:Margin="0.4921259845"/>
    <Footer x:Margin="0.4921259845"/>
    <PageMargins x:Bottom="0.984251969" x:Left="0.78740157499999996"
     x:Right="0.78740157499999996" x:Top="0.984251969"/>
   </PageSetup>
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>18</ActiveRow>
     <ActiveCol>2</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>';
  }
 

 /*************************************************************
  ajoute une ligne dans la feuille excel en cours
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function addLigne($hauteur='84.75')
  { 
    //$taille_ligne => défini la taille de la ligne dans le fichier xml
    if($hauteur=='')
      $this->arra_contenu[2] .= '<Row ss:AutoFitHeight="1" >';
    else
      $this->arra_contenu[2] .= '<Row ss:AutoFitHeight="0" ss:Height="'.$hauteur.'">';
  }
 

 /*************************************************************
  ferme la ligne
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function fermerLigne()
  {
    $this->arra_contenu[2] .= '</Row>\n';
  }
 

 /*************************************************************
  ajoute une cellule dans la ligne courante pour la feuille courante
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function addCellule($value, $type = "string", $style, $bool = false, $nb_fusion = 2)
  { 
  //$value => La valeur qui sera insérer dans la cellule.
  //$style => Style affecter à la cellule
  //$bool (facultatif) => 0 TRUE si il faut fusionner des cellules.
  //$nb_fusion (facultatif) => nombre de cellule à fusionner à celle d'origine.
  // ATTENTION à ne pas dépasser le nombre de colonne défini plus haut.
  //remplacement des caractères < et >  pour qu'ils ne soient pas vu comme des limiteur de balise
  $value=strtr($value,array("<"=>"&lt;",">"=>"&gt;"));
  $var=$style;
  if($bool)
  {
    $this->arra_contenu[2] .= '<Cell ss:MergeAcross="'.$nb_fusion.'" ss:StyleID="'.$var.'"><Data ss:Type="String">'.$value.'</Data></Cell>';
  }
  else
  {
    $this->arra_contenu[2] .= '<Cell ss:StyleID="'.$var.'"><Data ss:Type="'.$type.'">'.$value.'</Data></Cell>';
  }
 
  }
 

 /*************************************************************
  ajoute un commentaire
  
  parametres : 			         
  retour :          
  **************************************************************/     
  public function commentaire($contenu)
  {
    //Ecriture dans le buffer du commentaire
    $this->arra_contenu[2] .= '<!-- '.$contenu.' -->';
  }
 

}
?>
