<?php
/*******************************************************************************
Create Date : 18/09/2009
 ----------------------------------------------------------------------
 Class name : excel_xml
 Version : 1.1
 Author : escot alexandre
 Description : Classe pour créer un fichier xml destiné à excel 
********************************************************************************/
 class xml_excel extends xml_excel_v2
 { 
 
 protected $xml_v2;
 //Constructeur PHP5
 function __construct( $nomdufichier,$nb_col,$nb_ligne,$taill_col_defaut='60',$taill_col_spe='61',$nb_col_spe='1')
 {
 ////////////////////////////////////////////////////////////////////////////////////
 //$nomdufichier => contient le chemain et le nom sous lequel le fichier va etre créer
 //$nb_col => correspond au nombre de colonnes pour le fichier xml
 //$nb_ligne => correspond au nombre de ligne pour le fichier xml
 //$taill_col_defaut => défini une taille de colonne par défaut
 //$taill_col_spe => défini une taille de colonne particulière si besoin
 //$nb_col_spe => défini le nombre de colonne auquel sera appliqué la taille spécifique
 //////////////////////////////////////////////////////////////////////////////////////
 
 $this->xml_v2 = new xml_excel_v2($nomdufichier);
 $this->xml_v2->addStyle('<Style ss:ID="s21">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
  <Style ss:ID="En_tete_reserve">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Interior ss:Color="#FF9900" ss:Pattern="Solid"/>   
  </Style>
  <Style ss:ID="s22">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Interior ss:Color="#C0C0C0" ss:Pattern="Solid"/>
  </Style>
    <Style ss:ID="s23">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Interior ss:Color="#CCFFFF" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s24">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Interior ss:Color="#6699FF" ss:Pattern="Solid"/>
  </Style>
   <Style ss:ID="s25">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:Bold="1"/>
   </Style>
   <Style ss:ID="S_datetime">
    <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:Bold="1"/>
    <NumberFormat ss:Format="dd/mm/yyyy"/>
   </Style>');
   
  $this->xml_v2->addFeuille('Feuil1',$nb_col,$nb_ligne,$taill_col_defaut,$taill_col_spe,$nb_col_spe);
  $this->xml_v2->ajoutAttributFeuille('<Column index="2" ss:AutoFitWidth="0" ss:Width="120" ss:Span="'.$nb_col_spe.'"/>');
 }


 //Ouvre une nouvelle ligne
 function entrer($taille_ligne='84.75')
 {
  $this->xml_v2->addLigne($taille_ligne);
 }

 //Ferme la ligne
 function sortir()
 {
  $this->xml_v2->fermerLigne();
 }

 //Crée un cellule
 function ligne( $value,$type='String',$style,$bool=FALSE,$nb_fusion=2)
 {
 
  $this->xml_v2->addCellule($value,$type, $style, $bool, $nb_fusion);
 }

 //Ajoute un commentaire
 function commentaire($contenu)
 {
  //Ecriture dans le buffer du commentaire
  $this->xml_v2->commentaire($contenu);
 }



 //Copie du buffer dans le fichier demandé
 function creerlefichier()
 {
    $this->xml_v2->fermerFeuille();
    $this->xml_v2->creerlefichier();
 }
}
?> 
