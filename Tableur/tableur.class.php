<?php

/*******************************************************************************
Create Date : 25/05/2011
 ----------------------------------------------------------------------
 Class name : tableur
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de représenter une interface sous forme d'un tableur
********************************************************************************/

class tableur extends serialisable
{
 //**** attribute ***********************************************************
  protected $arra_colonne;//Les colonnes du tableur
  protected $arra_ligne;//Les lignes du tableur

  protected $stri_color1;//Couleur des entêtes
  protected $stri_color2;//Couleur des lignes en visu agrandie
  protected $bool_editable; //Pour savoir si le tableur est éditable ou non

//**** constructor ******************************************************** 

  /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe  tableur  
   *                        
   **************************************************************/         
  function __construct() 
  {
    $this->stri_color1="#66CDAA";
    $this->stri_color2="skyblue";
    
    $this->bool_editable=true;
  }   
 
 //**** setter *************************************************************
  public function setEditable($value){$this->bool_editable=$value;}
 //**** getter *************************************************************
  public function getEditable(){return $this->bool_editable;}
  public function getIemeColonne($int_num_col){return $this->arra_colonne[$int_num_col];}
  
 //**** other method *******************************************************
  /*************************************************************
  Permet de construire le code HTML et Javascript du tableur
 
 Paramètres : aucun
 Retour : string : le code HTML de l'interface
          
  **************************************************************/     
  public function htmlValue()
  {
    //ajout de la dernière ligne vide pour créer de nouveaux tr
    if($this->bool_editable)
    {$this->addLastLine();}
   
    //image pour affichage complet ou réduit
    $obj_img_afficher=new img("images/tout_afficher.gif");
      $obj_img_afficher->setStyle("cursor:pointer;");
      $obj_img_afficher->setWidth("25px");
      $obj_img_afficher->setOnclick("switchDisplayAllLine(this);");
      $obj_img_afficher->setTitle(_MSG_AFFICHAGE_COMPLET_REDUIT);
        
    $obj_table=new table();
      $obj_table->setBorder(1);
      $obj_table->setWidth("100%");
       $obj_tr=$obj_table->addTr();
       $obj_tr->addTd($obj_img_afficher->htmlValue());//Premier td permettant de basculer l'affichage en complet ou réduit
    
    //Objets pour le filtrage
     $obj_img_filtrer=new img("images/filtrer.gif");
        $obj_img_filtrer->setWidth("30px");
        $obj_img_filtrer->setStyle("cursor:pointer;");
     $obj_bt_filtre=new button("bt_ok",_OK);
      
     
    //image permettant de cocher, décocher
    $obj_img_cocher=new img("modules/Hotline/images/cocher.jpg");
      $obj_img_cocher->setWidth("15px");
      $obj_img_cocher->setStyle("cursor:pointer");
      $obj_img_cocher->setTitle(_CHECK_ALL);
    $obj_img_decocher=new img("modules/Hotline/images/decocher.jpg");
      $obj_img_decocher->setWidth("15px");
      $obj_img_decocher->setStyle("cursor:pointer");
      $obj_img_decocher->setTitle(_UNCHECK_ALL);
     
     
     $obj_table_valeur_filtre=new table();
        $obj_tr_filtre=$obj_table_valeur_filtre->addTr();
          $obj_tr_filtre->addTd(_TH_FILTRER)->setColspan(2);  
        $obj_tr_filtre=$obj_table_valeur_filtre->addTr();
          $obj_tr_filtre->addTd($obj_img_cocher)->setAlign("right"); 
          $obj_tr_filtre->addTd($obj_img_decocher);
        $obj_tr_filtre=$obj_table_valeur_filtre->addTr();
          $obj_td_filtre=$obj_tr_filtre->addTd(" ");
          $obj_td_filtre->setColspan(2);            
        $obj_tr_filtre_bouton=$obj_table_valeur_filtre->addTr();
          $obj_tr_filtre_bouton->addTd($obj_bt_filtre)->setColspan(2)  ; //ajout du bouton ok dans le tableau des filtres
      
     $obj_table_valeur_filtre->setStyle("background-color:white; border-style:solid;border-width:1px;border-color:black;position:absolute;display:none; ");
     //$obj_table_valeur_filtre->setStyle("background-color:white; border-style:solid;border-width:1px;border-color:black;position:absolute;");
    
          //  $obj_td_filtre->setStyle("height:10px;overflow-y:auto;");
    //on pose les entêtes des colones
    foreach($this->arra_colonne as $num_col=>$obj_colonne)
    {
      $stri_id_td="tdFiltre_".($num_col+1);
      $obj_table_valeur_filtre->setId("filtreColonne_".($num_col+1));
      
      //passage des actions avec identifiant de la colonne
      $obj_img_filtrer->setOnclick("switchFiltre(document.getElementById('tdEntete_".($num_col+1)."'));"); //on remonte dans l'arborescence jusqu'au premier td de la colonne
      $obj_img_filtrer->setId("img_filtre_".($num_col+1));
      $obj_img_cocher->setOnclick("checkAll('cb_col_".($num_col+1)."',true);");
      $obj_img_decocher->setOnclick("checkAll('cb_col_".($num_col+1)."',false);");
      $obj_bt_filtre->setOnclick("filtre(document.getElementById('tdEntete_".($num_col+1)."'));");
      
     
      $obj_td_filtre->setId($stri_id_td);
    
     
      $obj_table_filtre=new table();
        $obj_tr_filtre=$obj_table_filtre->addTr();
            $obj_tr_filtre->addTd($obj_colonne->getNom());
            $obj_td=$obj_tr_filtre->addTd($obj_img_filtrer->htmlValue().$obj_table_valeur_filtre->htmlValue());
              $obj_td->setAlign("right");
              
               
      $obj_table_filtre->setStyle("margin:0;padding:0;");
      $obj_table_filtre->setWidth("100%");
      $obj_td=$obj_tr->addTd($obj_table_filtre->htmlValue());
         $obj_td->setStyle("background-color:{$this->stri_color1};"); 
         $obj_td->setId("tdEntete_".($num_col+1));
         
         if(!$obj_colonne->getVisible()) //si la colonne est masquée
         {$obj_td->setStyle("display:none;");}
      
    }
        
    //on pose les lignes du tableur
    foreach($this->arra_ligne as $stri_key=>$obj_ligne)
    {
      $obj_tr=$obj_table->addTr();
      
      $arra_cellule=$obj_ligne->getCellule();
      //affichage du numéro de ligne
      $obj_premier_td=$obj_tr->addTd($stri_key);
       $obj_premier_td->setWidth("20px;");
       $obj_premier_td->setStyle("background-color:{$this->stri_color1};cursor:pointer;");
       $obj_premier_td->setonClick("switchDisplay(this);"); 
                        
      foreach($arra_cellule as $obj_cellule)
      {
        $obj_td=$obj_tr->addTd($obj_cellule->constructTd());
       
        
        if(!($obj_cellule->getColonne()->getEditable())&&($this->bool_editable))//si la colonne est non éditable, mais le tableur l'est
        {$obj_td->setBgcolor("silver");}
      }
      
      //pose du td permettant la suppression de la ligne
      if($this->bool_editable) //la suppression est possible uniquement si le tableur est éditable
      {
       $obj_img_delete=new img("images/module/PNG/cancel-032x032.png");
        $obj_img_delete->setStyle("cursor:pointer;");
        $obj_img_delete->setOnclick("envoiInformationSuppression(this.parentNode.parentNode);");
        $obj_img_delete->setTitle(_MSG_SUPPIMER_LIGNE);
       $obj_dernier_td=$obj_tr->addTd($obj_img_delete->htmlValue());
      } 
    }
    
    //Modification du td avec le numéro de ligne pour la dernière ligne qui permet l'ajout de données
   if($this->bool_editable)
   {
    $obj_premier_td->setStyle("background-color:yellow;cursor:pointer;");
    $obj_premier_td->setonClick("");
    $obj_dernier_td->setValue("");
   }
    
   
    //dynamique de l'interface
    $stri_path=str_replace($_SERVER['DOCUMENT_ROOT'],"",  dirname(__FILE__));
    $obj_javascripter=new javascripter();
   
    //gestion de l'intialisation des calendriers
    js_calendar::resetInstance(); 
    $obj_js_calendar=new js_calendar();
    $arra_calendar_file=$obj_js_calendar->getJavascripter()->getFile();
    foreach($arra_calendar_file as $stri_file)
    {$obj_javascripter->addFile($stri_file);} 
    
     $obj_javascripter->addFunction("
     var color1='{$this->stri_color1}';
     var color2='{$this->stri_color2}';
     ");//déclaration des variables de couleur
     $obj_javascripter->addFile("includes/fonction_commune.js");   
     $obj_javascripter->addFile($stri_path."/tableur.js");
    
     //gestion de l'initialisation des select_and_text
     $obj_select_and_text=new select_and_text();
     $obj_js_select_and_text=$obj_select_and_text->getJavascripter();
      
     $stri_js=$obj_javascripter->javascriptValue().$obj_js_select_and_text->javascriptValue();
    
    
    return $stri_js.$obj_table->htmlValue();
  }
 

 /*************************************************************
  Permet d'ajouter une colonne au tableur
 
 Paramètres : string : le nom de la colonne
              array(valeur=>libelle) : tableau des valeurs possible
              array : tableau des valeurs par défaut
 Retour : obj tableur_colonne : la nouvelle colonne
  
          
  **************************************************************/     
  public function addColumn($stri_nom,$arra_valeur_possible=array(),$arra_valeur_defaut="")
  {
    $obj_colone=new tableur_colonne($stri_nom,$arra_valeur_possible,$arra_valeur_defaut);
    
    $obj_colone->setEditable($this->bool_editable);
    $this->arra_colonne[]=$obj_colone;
    
    return $obj_colone;
  }
 

 /*************************************************************
  Permet d'ajouter une ligne
 
 Paramètres : string : la valeur de la première cellule
 	            string : la valeur de la deuxième cellule
 		           ...
             
 Retour : obj tableur_ligne : la ligne nouvellement ajoutée
      
  **************************************************************/     
  public function addLine()
  {
    $arra_args=func_get_args();
    
    if(count($arra_args)!=count($this->arra_colonne))//si on n'a pas passé le bon nombre de cellule à construire
    {
     trigger_error("Wrong number of argument in addLine method. You must specifiy one value for each column (".count($this->arra_colonne)." columns total)",E_USER_ERROR);
    }
    
    $obj_ligne=new tableur_ligne();//création de la ligne
    $this->arra_ligne[]=$obj_ligne;
    
    foreach($arra_args as $key=>$stri_arg)//création des cellules dans la lignes
    {
     $obj_cellule=$obj_ligne->addCell($stri_arg);
     
     //rattachement de la cellule à sa ligne et sa colonne
     $obj_cellule->setLigne($obj_ligne);
     
     $obj_colone=$this->arra_colonne[$key];
     $obj_cellule->setColonne($obj_colone);
     $obj_colone->addCell($obj_cellule);

    }
    
    return $obj_ligne;
  }
 
   /*************************************************************
  Permet d'ajouter une ligne
 
 Paramètres : array : tableau contenant l'ensemble des données pour chaque colonne
             
 Retour : obj tableur_ligne : la ligne nouvellement ajoutée
      
  **************************************************************/     
  public function addLineArray($arra_data)
  {
    
    if(count($arra_data)!=count($this->arra_colonne))//si on n'a pas passé le bon nombre de cellule à construire
    {
     trigger_error("Wrong number of argument in addLine method. You must specifiy one value for each column (".count($this->arra_colonne)." columns total)",E_USER_ERROR);
    }
    
  
    
    $obj_ligne=new tableur_ligne();//création de la ligne
    $this->arra_ligne[]=$obj_ligne;
    
    $int_key=0; 
    foreach($arra_data as $stri_arg)//création des cellules dans la lignes
    {
     $obj_cellule=$obj_ligne->addCell($stri_arg);
     
     //rattachement de la cellule à sa ligne et sa colonne
     $obj_cellule->setLigne($obj_ligne);
     
     $obj_colone=$this->arra_colonne[$int_key];
    
     $obj_cellule->setColonne($obj_colone);
     $obj_colone->addCell($obj_cellule);
     $int_key++;
    }
    
     return $obj_ligne;
  }
 
   /*************************************************************
  Permet de convertir les résultats d'une requête SQL en tableur
 
 Paramètres :string : le sql
 
 Retour : obj tableur : l'objet tableur
      
  **************************************************************/     
  public function makeSQLToTableur($stri_sql)
  {
    $obj_query=new querry_select($stri_sql);
    $arra_res=$obj_query->execute("assoc");
    
    //déclaration des colones
    foreach($arra_res[0] as $stri_nom_champ=>$stri_osef)
    {
     $this->addColumn($stri_nom_champ);
    }
    
    //déclaration des lignes
    foreach($arra_res as $arra_one_res)
    {
      $stri_parametre='"'.implode('","', $arra_one_res).'"';
     
      $this->addLineArray($arra_one_res);
    }
    
    return $this;
  }

   /*************************************************************
  Permet de rendre l'ensemble des colonnes éditable ou non
 
 Paramètres :bool :  true   => toutes les colonnes sont éditables
                     false  => acucune colonne n'est éditable
 
 Retour : aucun
  **************************************************************/     
  public function makeEditable($bool_editable)
  {
   foreach($this->arra_colonne as $obj_colonne)
   {
    $obj_colonne->setEditable($bool_editable);
   }
  }
  
    /*************************************************************
  Permet d'ajouter la dernière ligne au tableur. Cette ligne
  permet de créer de nouveaux enregistrements.
 
 Paramètres :aucun
 Retour : aucun
  **************************************************************/     
  public function addLastLine()
  {
    //gestion des valeur par défaut
    $arra_valeur_defaut=array();
    foreach($this->arra_colonne as $obj_colonne)
    {
      $arra_valeur_defaut[]=$obj_colonne->getValeurDefaut();
    }
    $obj_line=$this->addLineArray($arra_valeur_defaut);
    //$obj_line=$this->addLineArray(array_fill(0,count($this->arra_colonne),""));
   
    
    $arra_cell=$obj_line->getCellule();
    
    foreach($arra_cell as $obj_cell)
    {
     $obj_td=$obj_cell->getTd();
     $obj_td->setOnclick("enableEdition(this);");//pour pouvoir ajouter une nouvelle ligne
     $obj_edition=$obj_cell->getEdition();
     $obj_edition->setDisabled(true);
    }
  } 
  
  /*************************************************************
  Permet de créer un html simple du tableur en vu d'être exporté
 
 Paramètres :string : type de retour html ou table
 Retour : string : le code html
  **************************************************************/     
  public function simpleHtmlValue($stri_return_type="html")
  {
    $obj_table=new table();
      $obj_tr=$obj_table->addTr();
    //pose des entête
    foreach($this->arra_colonne as $num_col=>$obj_colonne)
    {
       if($obj_colonne->getVisible())
       {$obj_tr->addTd($obj_colonne->getNom());}
    }
    
    
    //on pose les lignes du tableur
    foreach($this->arra_ligne as $stri_key=>$obj_ligne)
    {
      $obj_tr=$obj_table->addTr();
      $arra_cellule=$obj_ligne->getCellule();
                        
      foreach($arra_cellule as $obj_cellule)
      {
       if($obj_cellule->getColonne()->getVisible())
       {$obj_tr->addTd($obj_cellule->getLibelle());}
        
      }
    }
  
   
   return  ($stri_return_type=="table")?$obj_table:$obj_table->htmlValue();
  } 
  
   /*************************************************************
  Permet de convertir le tableur en fichier excel
 
 Paramètres :string : le chemin complet où sauvegarder le fichier
 Retour : 
  **************************************************************/     
  public function convertToExcel($stri_file)
  {
    $obj_table=$this->simpleHtmlValue("table");
    
    $obj_table_xls=new table_xls($obj_table,$stri_file,"Livret_machine");
    $obj_table_xls->setMaxLg(115);
    
    return $obj_table_xls->converti();
  }

 //**** serialisation *******************************************************
 public function __wakeup()
 {
   parent::__wakeup();
  
   foreach($this->arra_colonne as $obj_colonne)
   {
     
    foreach($this->arra_ligne as $key=>$obj_ligne)
    {
      $obj_cellule=$obj_colonne->getIemeCellule($key);
      $obj_ligne->addCell($obj_cellule);
   
    }
   }
   
 }
}

?>
