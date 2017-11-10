<?php
/*******************************************************************************
Create Date  : 27/01/2015
 ----------------------------------------------------------------------
 Class name  : table_with_search
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Permet de construire une table avec champs de recherche et tri intégré au header
 
********************************************************************************/
class table_with_search extends table{
   
//**** Attributs ****************************************************************
	
	//*** 01 Attributs  ***********************************************************
	 protected $int_nb_ligne_page;          //Le nombre de ligne par page à afficher
   protected $stri_color1;                //Première couleur pour l'alternance des couleur
   protected $stri_color2;                //Deuxième couleur d'alternance
   protected $obj_tr_entete;              //Le tr d'entête du tableau
   protected $arra_column_name;           //Tableau simple dimension du nom des colonnes  
//**** Methodes *****************************************************************

//**** Setter ****************************************************************
	
  
//**** Getter ****************************************************************   

//*** 01 Constructor **********************************************************
	
	/*******************************************************************************
	* Constructeur principal
	* 
	* Parametres : 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function __construct() 
	{ 
	   parent::__construct();
     
     //- ajout du header
     $this->obj_tr_entete=$this->addTr();
        $this->obj_tr_entete->setClass('table_with_search_header titre3');
        
     //- limite du nombre de ligne
     $this->int_nb_ligne_page=30;   
	}
	

//*** 02 Autres méthodes ******************************************************
	  /**
	   *    Permet d'ajouter une colonne au header
	   *    @ param : $stri_name: le nom de la colone
	   *    @ param : $stri_libelle : le libelle à afficher
	   *    @param :  $bool_search_actif : si la recherche est activé pour cette colonne
	   *    
	   *    @return : obj : le td ajouté              
	   **/                
  public function addToHeader($stri_name,$stri_libelle,$bool_search_actif=true)
  {
    //- construction de l'image affichant l'interface de recherche
     $obj_img=new img('images/drop_down_02.png');
        $obj_img->setStyle('width:16px;float:right;cursor:pointer;');
        $obj_img->setOnmouseOver("this.save_src=this.src;this.src='images/drop_down_04.png'");
        $obj_img->setOnmouseOut("this.src=this.save_src");
        $obj_img->setOnclick('table_with_search.getInstance($(this)).displaySearchInterface($(this));');
        $obj_img->setClass('column_icon');
 
     
     //- gestion de l'activation de la recherche
     $arra_value=array($stri_libelle);
     $stri_active_name='no-search';
     if($bool_search_actif===true)
     {
      $arra_value[]='&nbsp;&nbsp;&nbsp;';
      $arra_value[]=$obj_img;  
      $stri_active_name=$stri_name;
     
     }
     
     //- construction du td
     $obj_td=$this->obj_tr_entete->addTd($arra_value);
      $obj_td->setClass($stri_name);
      $this->arra_column_name[]=$stri_active_name;
     
     return  $obj_td;
  }
  
  //Permet de construire le tr de pagination
  protected function constructPagination($int_nb_tr_total)
  {
    $int_nb_page=ceil($int_nb_tr_total/$this->int_nb_ligne_page);
       
    //- traitement de pages 
    $int_max=0;
    for($i=0;$i<$int_nb_page-1;$i++)
    {
      $int_libelle_page=$i+1;
      $int_min=$i*$this->int_nb_ligne_page;
      $int_max=$int_min+$this->int_nb_ligne_page-1;
      $obj_font=new font($int_libelle_page);
          $obj_font->addData('min',$int_min);
          $obj_font->addData('max',$int_max);
          $obj_font->setOnclick("table_with_search.getInstance($(this)).paginate($(this));");
          $obj_font->setStyle('cursor:pointer;');
          $obj_font->setClass('table_with_search__page');
      $arra_font[]=' '.$obj_font->htmlValue();
    }
    
    //- traitement de la dernière page
    $int_min=($int_nb_page-1)*$this->int_nb_ligne_page;
    $int_max=$int_nb_tr_total;
    $int_libelle_page=$int_nb_page;
      $obj_font=new font($int_libelle_page);
          $obj_font->addData('min',$int_min);
          $obj_font->addData('max',$int_max);
          $obj_font->setOnclick("table_with_search.getInstance($(this)).paginate($(this));");
          $obj_font->setStyle('cursor:pointer;');
          $obj_font->setClass('table_with_search__page');
    $arra_font[]=' '.$obj_font->htmlValue();
    
    $obj_tr=new tr();
        $obj_td=$obj_tr->addTd($arra_font);
          $obj_td->setColspan(count($this->arra_column_name));
          $obj_td->setAlign('center');
          $obj_td->setClass('contenu table_with_search__constructPagination');
          
    return $obj_tr;
  }
  
  //Permet de construire les données en json
  protected function constructJson()
  {
     //- conversion des tr et td en tableau de données
   $int_nb_tr=count($this->arra_tr);
   $arra_value=array();//tableau pour récupérer l'ensemble des valeurs       
   for($i=1;$i<$int_nb_tr;$i++)
   {
      $obj_tr=$this->arra_tr[$i];
      
      $arra_td=$obj_tr->getTd();
      $arra_temp=array();     
      foreach($arra_td as $int_key=>$obj_td)
      {
        
         //- récupération du nom de colonne
         $stri_colonne= $this->arra_column_name[$int_key];
          
         if($stri_colonne!='no-search') //si la recherche est active sur cette colonne
         {
           //- récupération de la valeur du td
            $stri_value=$obj_td->constructValue();
            
            //- enregistrement temporaire du résultat
            $arra_temp[$stri_colonne]=$stri_value;
            
            //- transmission de la classe
            $obj_td->setClass($stri_colonne);
         } 
      }
      
      //- enregistrement global du résultat
      $arra_value[]=$arra_temp;     
   }
  
    //- ajout des info généréale
    $arra_info['int_nb_ligne_page']=$this->int_nb_ligne_page;
    $arra_info['arra_resultat']=$arra_value;
    $arra_info['arra_column_name']=$this->arra_column_name;
   
    //- conversion en json
    $stri_json=json_encode($arra_info);  
            
    //- création d'un tr
    $obj_tr=new tr();
       $obj_td=$obj_tr->addTd($stri_json);
          $obj_td->setClass('table_with_search__constructJson');
          $obj_tr->setStyle('display:none;');
          
    return $obj_tr;
  }
  
  //construction de la référence pour l'interface de tri / recherche
  public function constructTableForReference()
  {
  
    //- éléments de l'interface
    $obj_font_trier=new font(_LIB_TRIER);
    $obj_font_a_to_z=new font(_LIB_TRIER_DE_A_A_Z);
      $obj_font_a_to_z->setClass('tri_croissant crit_tri clickable_link');
         $obj_font_a_to_z->setOnclick("table_with_search.getInstance($(this)).Trier('croissant',$(this));");                                           
    $obj_img_a_to_z=new img('images/trier_A_a_Z.png'); 
      $obj_img_a_to_z->setStyle('width:16px;');
    $obj_font_z_to_a=new font(_LIB_TRIER_DE_Z_A_A);
       $obj_font_z_to_a->setClass('tri_decroissant crit_tri clickable_link');
       $obj_font_z_to_a->setOnclick("table_with_search.getInstance($(this)).Trier('decroissant',$(this));");   
    $obj_img_z_to_a=new img('images/trier_Z_a_A.png');       
       $obj_img_z_to_a->setStyle('width:16px;');
    $obj_font_rechercher=new font(_LIB_RECHERCHER);
    $obj_input_search=new text('table_with_search__search');
         $obj_input_search->setOnkeyUp("table_with_search.getInstance($(this)).searchInDistinctValue($(this));");   
    $obj_textearea_search_result=new text_arrea('table_with_search__search_result');
      $obj_textearea_search_result->setRows(10);
      $obj_textearea_search_result->setCols(30);
      $obj_textearea_search_result->setReadonly(true);
    $obj_img_loupe=new img('images/PNG/search-032x032.png');
      $obj_img_loupe->setTitle(_MSG_SAISIR_RECHERCHE);
    $obj_bt_ok=new button("bt_ok",_OK);
       $obj_bt_ok->setOnclick("table_with_search.getInstance($(this)).searchInResult($(this));");
    $obj_bt_annuler=new button("bt_cancel",_CANCEL);
         $obj_bt_annuler->setOnclick("table_with_search.getInstance($(this)).hideSearchInterface($(this));");
    $obj_font_supprimer=new font(_LIB_SUPPRIMER_TOUS_FILTRES);
      $obj_font_supprimer->setStyle('font-size:9px;');
      $obj_font_supprimer->setClass('clickable_link');
      $obj_font_supprimer->setOnclick("table_with_search.getInstance($(this)).resetSearch($(this));");
    
    
    //- construction de l'interface
    $obj_table=new table();
      $obj_tr=$obj_table->addTr();
        $obj_td=$obj_tr->addTd($obj_font_trier);
           $obj_td->setClass('titre4');
           $obj_td->setColspan(3);
      $obj_tr=$obj_table->addTr();
        $obj_tr->addTd($obj_img_a_to_z);
        $obj_tr->addTd($obj_font_a_to_z);
       $obj_tr=$obj_table->addTr();
        $obj_tr->addTd($obj_img_z_to_a);
        $obj_tr->addTd($obj_font_z_to_a);
      $obj_tr=$obj_table->addTr();
        $obj_td=$obj_tr->addTd($obj_font_rechercher);
          $obj_td->setClass('titre4');
          $obj_td->setColspan(3);
      $obj_tr=$obj_table->addTr();
        $obj_td=$obj_tr->addTd($obj_input_search);
          $obj_td->setColspan(2);
        $obj_tr->addTd($obj_img_loupe);
      $obj_tr=$obj_table->addTr();
        $obj_td=$obj_tr->addTd($obj_font_supprimer);
          $obj_td->setColspan(2);  
      $obj_tr=$obj_table->addTr();
        $obj_td=$obj_tr->addTd($obj_textearea_search_result);
          $obj_td->setColspan(3);
      $obj_tr=$obj_table->addTr();
        $obj_td=$obj_tr->addTd(array($obj_bt_ok,' ',$obj_bt_annuler));
          $obj_td->setAlign('center');
          $obj_td->setColspan(3);
     $obj_table->setClass('contenu table_with_search__constructTableForReference');
     $obj_table->setStyle('position:absolute;display:none;text-shadow:none;');
     
     //$obj_table->setBorder(1);
           
    return $obj_table;
  } 
  
  
  //Méthode d'affichage principale
  public function htmlValue($stri_mode_retour="html")
  {
    $int_nb_tr=count($this->arra_tr);
       
   //- filtrage du nombre de tr
   $arra_tr=array();
   $int_nb_total_tr=count($this->arra_tr);
   $int_nb_tr=min($this->int_nb_ligne_page,$int_nb_total_tr);
   for($i=0;$i<$int_nb_tr ;$i++)
   {
     $this->arra_tr[$i]->setClass('tr_resultat titre3'); 
     $arra_tr[]=$this->arra_tr[$i];
   }
   
   //- construction de la pagination
   $arra_tr[]=$this->constructPagination($int_nb_total_tr);
      
   //- construction des données json
   $arra_tr[]=$this->constructJson();
      
   //- construction du css
   $obj_css=new css();
      $obj_css->addClass('.clickable_link:hover{
          color:green;
      }');
      $obj_css->addClass('.clickable_link{
          cursor:pointer;       
      }');
   
   //- construction de l'interface de recherche de référence 
    $obj_table_reference=$this->constructTableForReference();
    $obj_tr=new tr();
       $obj_tr->addTd(array($obj_table_reference,$obj_css->cssValue()));
     $arra_tr[]= $obj_tr;
     
   //- limitation des tr
   $this->arra_tr=$arra_tr;

   //- gestion de la classe css
   $this->stri_class=($this->stri_class!='')?$this->stri_class.' table_with_search':'table_with_search';

   if($stri_mode_retour=="html")
   { return parent::htmlValue();}
   
   return $this;
  }
  

}


?>
