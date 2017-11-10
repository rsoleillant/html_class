<?php
/*******************************************************************************
Create Date : 06/12/2010
 ----------------------------------------------------------------------
 Class name :  table_xls
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de convertir un tableau html en tableau excel
               Cette version est basé sur la bibliothèque Spreadsheet_Excel_Writer
********************************************************************************/
include_once("includes/classes/Spreadsheet_Excel_Writer/Spreadsheet_Excel_Writer.pkg.php");

class table_xls
{
  //**** attribute *************************************************************
 protected $obj_workbook;     //L'objet servant à générer le xls
 protected $obj_table;        //La table à convertir
 protected $stri_nom_fichier; //Le nom complet du fichier
 protected $stri_nom_feuille; //Le nom de la feuille excel dans laquelle le tableau html va être mis
 protected $int_max_lg=25;    //La largeur maximale d'une colonne excell 
 //**** constructor ***********************************************************
   
   /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe rules_applicator   
   *                        
   **************************************************************/         
  function __construct(table $obj_table,$stri_nom_fichier="",$stri_nom_feuille="") 
  {
    $this->obj_table=$obj_table;
    $this->stri_nom_fichier=($stri_nom_fichier!="")?$stri_nom_fichier:$_SERVER['DOCUMENT_ROOT']."/temp/".pnusergetvar("uid")."/table.xls";
    $this->stri_nom_feuille=($stri_nom_feuille!="")?$stri_nom_feuille:"feuille 1"; 
   
         
    $this->obj_workbook=new Spreadsheet_Excel_Writer($this->stri_nom_fichier);
    $this->obj_workbook->setVersion(8); //pour lever la limite des 255 caractères par cellules
    
  }  
  
  //**** destructor ***********************************************************
  public function __destruct()
  {
    $this->obj_workbook->close(); //on ferme correctement le fichier excel
  }
  
  //**** setter ****************************************************************
  public function setWorkbook($value){$this->obj_workbook=$value;}
  public function setTable($value){$this->obj_table=$value;}
  public function setNomFichier($value){$this->stri_nom_fichier=$value;}
  public function setNomFeuille($value)
  {
   $stri_protected=preg_replace("/[^[:alnum:] ]+/", " ", $value);//excel n'aime pas les caractères spéciaux dans les nom de feuille
   $this->stri_nom_feuille=$stri_protected;
  
  }
  public function setMaxLg($value){$this->int_max_lg=$value;}

    
  //**** getter ****************************************************************
  public function getWorkbook(){return $this->obj_workbook;}
  public function getTable(){return $this->obj_table;}
  public function getNomFichier(){return $this->stri_nom_fichier;}
  public function getNomFeuille(){return $this->stri_nom_feuille;}
  public function getMaxLg(){return $this->int_max_lg;}

 
  
  //**** public method *********************************************************
  /*************************************************************
 * Permet d'établir la correspondance entre les couleurs du CMS
 * et les couleur gérée par la bibliothèque   
 * parametres : string : la couleur à convertir en notation html
 * retour : string : l'index ou le nom de la couleur excel
 *                        
 **************************************************************/ 
  public function correspondanceCouleur($stri_couleur)
  {
   global $bgcolor1,$bgcolor2,$bgcolor3,$bgcolor4,$bgcolor5;
   $arra_color=array("black"=>"black","white"=>"white","#000000"=>"black","#FFFFFF"=>"white",$bgcolor1=>"42",$bgcolor2=>"43",$bgcolor3=>"42",$bgcolor4=>"white",$bgcolor5=>"27");

   if(isset($arra_color[$stri_couleur]))
   {return $arra_color[$stri_couleur];}
   
   return "";
  }
  
  
 /*************************************************************
 * Permet de supprimer le html d'un text
 * parametres : string : le texte à nettoyer
 * retour : string : le texte sans balise html
 *                        
 **************************************************************/ 
  public function protegeTexte($stri_text)
  {   
      //- Gestion des objects
      if (is_object($stri_text))
      {
          if (method_exists($stri_text, 'htmlValue'))
          {
              $stri_text = $stri_text->htmlValue();
          }
      }
      
   //gestion des saut de lignes
   $arra_replace=array("<br />","\r",chr(9),"&#39;");
   $arra_replacement=array("","","\n","'");
   
   $stri_text_ok=str_replace($arra_replace,$arra_replacement,$stri_text);
  
   //suppression des tags html 
   $stri_sans_html=strip_tags($stri_text_ok);
  
   //gestion des entités html
   //$stri_sans_html=html_entity_decode($stri_sans_html);
   $stri_sans_html=html_entity_decode($stri_sans_html,ENT_COMPAT,'ISO-8859-1');
   
   //protection contre les formules
   $stri_sans_html=($stri_sans_html{0}=="=")?" ".$stri_sans_html:$stri_sans_html;//si le texte commence par =, on ajoute un espace pour ne pas considéré le texte comme une formule 
   
   return $stri_sans_html;   
  }
  
  /*************************************************************
 * Permet de créer le fichier excel contenant la table html
 * parametres : aucun
 * retour : string : le chemin du fichier créé
 * 
 *Note : plusieurs instructions html ne sont pas traduites (rowspan, style de bordure ...) Compléter la 
 *       correspondance des style html avec les styles xls au besoin.                         
 **************************************************************/ 
  public function converti()
  {
    $obj_table=$this->obj_table;
    
    $obj_worksheet=$this->obj_workbook->addworksheet($this->stri_nom_feuille);
    
    $arra_largeur_colone=array();//Tableau pour optimiser les largeurs des colonnes
    $arra_obj_style=array();//Pour stocker les différents objet de style et éviter de récéer toujours les mêmes
        
    $arra_tr=$obj_table->getTr();
    
    $int_ligne=0;
    foreach($arra_tr as $obj_tr)
    {
     $arra_td=$obj_tr->getTd();
     $int_colone=0;
    
      
     $stri_color=$obj_tr->getBgcolor(); 
     if($stri_color!="")
      {
        $stri_color=$this->correspondanceCouleur($stri_color);//on doit faire une correspondance entre couleur html et les couleur gérées par la bibliothèque
        $arra_style['FgColor']=$stri_color;
      }
     foreach($arra_td as $obj_td)
     {
      $arra_style=(count($arra_style)>0)?$arra_style:array(); 
      $int_colspan=$obj_td->getColspan()-1;
      $stri_color=$obj_td->getBgcolor();
      $mixed_param=$this->protegeTexte($obj_td->getValue());
      
      $int_lg=strlen($mixed_param);
      $arra_largeur_colone[$int_colone]=($int_lg>$arra_largeur_colone[$int_colone])?$int_lg:$arra_largeur_colone[$int_colone];//on cherche les longeurs de colones maximales
     
      $int_ht=50;
    
      if($stri_color!="")
      {
        $stri_color=$this->correspondanceCouleur($stri_color);//on doit faire une correspondance entre couleur html et les couleur gérées par la bibliothèque
        $arra_style['FgColor']=$stri_color;
      }
     
      $int_border=1;
      if($int_colspan>1)
      {
        $int_ht=12;
        $obj_worksheet->setMerge($int_ligne,$int_colone,$int_ligne,$int_colone+$int_colspan);
        $int_border=0;
      }
      
      //gestion des styles
      //gestion de l'alignement
          $stri_align=$obj_td->getAlign();
          $stri_align=($stri_align!="")?$stri_align:"left";
        $arra_style['Align']=$stri_align;
          $stri_valign=$obj_td->getValign();
          $stri_valign=($stri_valign!="")?$stri_valign:"top";
        $arra_style['Valign']=$stri_valign;
      //style par défaut
        $arra_style['Border']=$int_border;
        $arra_style['BorderColor']='black';
        $arra_style['TextWrap']='';
       
       $stri_id_style=implode("-",$arra_style);
       
       if(isset($arra_obj_style[$stri_id_style]))//si le style est déjà existant, on le récupère
       {$obj_style=$arra_obj_style[$stri_id_style];}
       else   //style non existant, on le créer
       {
          $obj_style= $this->obj_workbook->addformat();
       
          foreach($arra_style as $stri_attribute=>$stri_value)//pour chaque attribut à définir
          {
            $stri_method="set".$stri_attribute; //construction du nom du setter de l'attribut
            $obj_style->$stri_method($stri_value); //set de l'attribut
          }
          
        $arra_obj_style[$stri_id_style]=$obj_style; //on sauvegarde le style pour pouvoir le réutiliser plus tard
       }

      $obj_worksheet->write($int_ligne,$int_colone, $mixed_param,$obj_style);//on ajoute une ligne au document
     
      
      $int_colone++;
     }
   
     //$obj_worksheet->set_row($int_ligne, $int_ht);
    
     $int_ligne++;
    } 
    
    
    //pose des largeurs
    foreach($arra_largeur_colone as $int_num_colone=>$int_lg)
    {
      $int_max_lg=$this->int_max_lg;//on limite la longeur maximale des colonnes
      $int_lg=($int_lg>$int_max_lg)?$int_max_lg:$int_lg;
      $obj_worksheet->setColumn($int_num_colone-1,$int_num_colone,$int_lg); 
    }
        
  
  // $this->obj_workbook->close();
   
   return $this->stri_nom_fichier;
  }
  

}




?>
