<?php
/**********************************************************************************/
/*  Titre : menu_dyn_v2.php
/*  Auteur : Rémy Soleillant
/*  Création : 02/01/2010
/*  Description : Permet d'afficher un nombre d'onglet prédéfinie sur la barre de menu
/*  Héritage : menu.class.php
/**********************************************************************************/

class menu_dyn_v2 extends menu
{
   
  //**** attribute *************************************************************
  protected $nb_onglet;             //définie le nombre d'onglet visible, par défaut 5
  protected $arra_info_onglet;      //information concernant les onglet (display,lien)
  protected $arra_onglet=array();   //tableau de tous les onglets du menu
  protected $arra_active;           //tableau contenant les données de base permetant de retrouver l'onglet actif 
  static private $int_menu_dyn;     //le nombre d'objet menu qui ont été créés
  protected $bool_hide_menu=false;  //par défaut on affiche le menu
  
  protected $arra_menu_perso;       //Liste des onglets à affichés
  protected $bool_managed;          //Pour indiquer si le traitement a déjà été fait 
  //**** getter ****************************************************************
  public function getHideMenu() { return $this->bool_hide_menu; } 
  public function getMenuPerso(){return $this->arra_menu_perso;}

  //**** setter ****************************************************************
  public function setHideMenu($x) { $this->bool_hide_menu = $x; } 
  
   
  //**** constructor ***********************************************************
  /****************************************************************************
  /*  fonction construct()
  /*  Description : permet de lancer la construction d'un menu_dyn à partir de la classe mère menu
  /*  Paramètre : $url => le chemin relatif par lequel on va accèder au menu 
  /*              $act_class => nom de la classe css à utiliser pour l'onglet actif 
  /*              $inact_class => nom de la classe css à utiliser pour les onglets inactifs
  /*              act_src => le chemin de l'image à utiliser pour l'onglet actif 
  /*              $inact_src => le chemin de l'image à utiliser pour les onglets inactifs
  /*              $nb_onglet => le nombre d'onglet à afficher par défaut
  /*  retour :  void
  *****************************************************************************/
  function __construct($url, $act_class, $inact_class, $act_src, $inact_src,$call=__FILE__,$nb_onglet = "10") 
  {
      
    $this->bool_managed=false; 
      
      
    $int_num_args=func_num_args();
    if($int_num_args<2)//si aucun paramètre n'a été passé
    {
      $int_id=crc32($url);
      $stri_file=$url;
      if($int_num_args==0)//détection automatique du script appellant
      {
       $temp=get_included_files(); 
       $stri_file=array_pop($temp);
       $int_id=crc32($stri_file);
      
      }//génération d'un identifiant pour le menu basé sur le script appellant
      
      $url=$_SERVER['REQUEST_URI'];
      
      
      $act_class="tab_actif";
      $inact_class="tab_inactif";
      $act_src="modules/Hotline/images/onglet_on.gif";
      $inact_src="modules/Hotline/images/onglet_off.gif";
      
      parent::__construct($url, $act_class, $inact_class, $act_src, $inact_src,$call=__FILE__);
      $arra_file=get_included_files();
      
      $this->autoBuild($stri_file);
     
      //new menu_dyn("modules.php?op=modload&name=$ModName&file=index&action=initialisation2","tab_actif","tab_inactif","modules/Hotline/images/onglet_on.gif","modules/Hotline/images/onglet_off.gif");
    }
    else
    {
      parent::__construct($url, $act_class, $inact_class, $act_src, $inact_src,$call=__FILE__);
    }
    
   
    $this->nb_onglet=$nb_onglet;                 
  }


   /*****************************
  *Permet de construire un identifiant pour le menu
  *param : aucun
  *retour string : l'identifiant du menu
  ******************************/       
 public function constructId()
 {
     $stri_index=$this->stri_url;
    //construction de l'identifiant du menu
    $stri_index=strtr($stri_index,".&=?/- ","_______");
    
   return $stri_index;
  /* 
  /// $stri_index=array_pop(get_included_files()); //génération de l'id basé sur le script appellant
   $arra_file=get_included_files();
  $stri_index=$arra_file[count($arra_file)-1];
    
   //$stri_index=crc32($stri_index);
    //$stri_index=crypt($stri_index,"unesupergrandeclef");
    //$stri_index=$stri_index.$stri_index.$stri_index; 
   
  //construction de l'identifiant du menu
   $stri_index=strtr($stri_index," .&=?/-homecladivpw'","_______phomecladiv8"); //remplacement de caractère pour éviter de transmettre en claire un chemin du serveur
   $stri_index=substr($stri_index,0,75);//limitation de la taille de l'id au delà de laquel le js ne fonctionne plus
     
   return $stri_index;*/
 } 
  /*****************************
  *Permet d'afficher le menu et de faire l'inclusion des fichiers de 
  *l'onglet actif  
  *param : aucun
  *retour string : l'identifiant du menu
  ******************************/       
/* public function autoDisplay($bool_display=true)
 { 
  $stri_html= $this->htmlValue();
  if($bool_display)
  {echo $stri_html;}

  //pour afficher ou masquer le menu
  $this->hideMenu($this->bool_hide_menu);  
  
  $obj_onglet_actif=$this->getActiveOnglet();

  //inclusion du fichier index associé à l'onglet sélectionné
  //capture du buffer
  ob_start();
  ob_flush();
  flush();
   foreach ($obj_onglet_actif->getMultiPageByAction($action) as $arra_page)
  {include_once($arra_page);}
  $stri_html.=ob_get_contents();
  ob_end_clean();  
  
  return $stri_html;  
 
 }  */
  /*
    Permet de lancer le traitement d'enregistrement et de chargement des onglets sélectionné
  
  */
  public function manage()
  {
     global $ModName;
   
     //- récupération de tous les onglets
     $arra_onglet = $this->getOnglet();
        
     //- on incrémente le compteur permettant de savoir le nombre de menu_dyn créé
     //  permet par la suite d'identifier certains éléments de chaque objet menu_dyn
    self::$int_menu_dyn++;
    
    //- utilitaire de gestion des valeurs de la collection         
    $obj_parameter_stat_v2 = new parameter_state_v2("menu_perso_".self::$int_menu_dyn, pnuserGetVar("uid"), $ModName );     
    
    //- récupération des valeurs existantes en base
    $this->arra_menu_perso = $obj_parameter_stat_v2->load();
   
    //- lancement du traitement
    if(isset($_POST['actionMenuDyn'])&&($_POST['actionMenuDyn']==self::$int_menu_dyn))
    {
      //- déduction des onglets à supprimer
      $arra_to_delete=array_diff($this->arra_menu_perso,$_POST['menu_personnalise']);
      
      //- déduction des onglest à ajouter
      $arra_to_add=array_diff($_POST['menu_personnalise'],$this->arra_menu_perso);
      
      //- enregistrement des nouveaux onglets
      foreach($arra_to_add as $stri_onglet_name)
      { 
        $obj_parameter_stat_v2->insert($stri_onglet_name); 
      }
     
      //- suppression des onglets plus d'actualité
      foreach($arra_to_delete as $stri_onglet_name)
      { 
        $obj_parameter_stat_v2->delete($stri_onglet_name); 
      } 
      
      //- actualisation des valeurs existantes en base
      $this->arra_menu_perso = $obj_parameter_stat_v2->getCategorie();
      
      
    }
    
    //- gestion d'initialisation des onglets affiché
    if(count( $this->arra_menu_perso)==0)
    {  
      foreach($arra_onglet as $obj_onglet)
      {
        $this->arra_menu_perso[]= $obj_onglet->getName();
      }
    } 
    
    
     //- gestion de l'onglet actif
     $arra_key=array_keys($this->arra_active);
     $stri_active_onglet=$this->arra_active[$arra_key[0]];
     
     if(!in_array($stri_active_onglet, $this->arra_menu_perso))
     {
       //- déselection par défaut
       foreach($arra_onglet as $obj_onglet)
       {$obj_onglet->setSelected(false);}
        
       //- sélection du premier onglet visible
       $arra_menu_perso_copy=$this->arra_menu_perso;
       $stri_premier_onglet=array_shift($arra_menu_perso_copy);
       parent::setSelectedOnglet($stri_premier_onglet);       
       $this->arra_active[$arra_key[0]]=$stri_premier_onglet;
       $obj_onglet_actif = $this->getActiveOnglet(); 
   
     }
     
    //- marquage du traitement comme fait
    $this->bool_managed=true;        
  }
  
  //Permet de récupérer un onglet en fonction de sa valeur
  public function getOngletByValue($stri_value)
  {
    foreach($this->getOnglet() as $obj_onglet)
    {
      if($obj_onglet->getValue()==$stri_value)
      {return $obj_onglet;}
    }
    
    return false;
  }
  
    //Permet de récupérer un onglet en fonction de sa valeur
  public function getOngletByName($stri_name)
  {
    foreach($this->getOnglet() as $obj_onglet)
    {
      if($obj_onglet->getName()==$stri_name)
      {return $obj_onglet;}
    }
    
    return false;
  }

  /**
   * Permet de construire l'interface servant à sélectionner les onglets visibles
   *    
   * àreturn : obj table : la table représentant l'interface
   **/        
    public function constructTableForAllOnglet()
    {  
      global $bgcolor2;
               
      //- récupération de tous les onglets
      $arra_onglet = $this->getOnglet();
  
      //- récupération des noms des onglets visible
      $arra_onglet_visible=$this->arra_menu_perso;
    
    //image permettant d'envoyer le formulaire de menu perso
    $img_menu_perso = new img("images/module/PNG/commerce-032x032.png");
      $img_menu_perso->setClass('infobulle');
      $img_menu_perso->setTitle(_MENU_PERSONNALISE);
      $img_menu_perso->setStyle('cursor:pointer');
      $img_menu_perso  ->setOnclick("menu_dyn_v2.sendForm($(this),".self::$int_menu_dyn.");"); 
       
       //- initialisatin de la table 
       $obj_table_all_onglet=new table();
       $obj_table_all_onglet->setClass('constructTableForAllOnglet');
       $obj_table_all_onglet->setStyle("display:none; position:absolute; background-color:white; border:solid $bgcolor2 2px; z-index : 100; border-radius: 7px; box-shadow: 1px 3px 5px 1px;");
 
       //- entete et bouton de validation
          $obj_tr=$obj_table_all_onglet->addTr();
            $obj_tr->addTd($img_menu_perso);
            $obj_tr->addTd(_MENU_PERSONNALISE);
            $obj_tr->setStyle("font-size:12px;");
         $obj_tr->setClass('titre2');
         
       //- fonctionnalité de coche / décoche
        $obj_a_all = new a('#', _SELECT_DESELECT_ALL);
            $obj_a_all->setOnclick('menu_dyn_v2.check_unchek_menu($(this)); return false;');  
          $obj_tr=$obj_table_all_onglet->addTr();
            $obj_td=$obj_tr->addTd($obj_a_all);
              $obj_td->setColspan(2);
               
      //- construction de la liste de tous les onglets
      foreach($arra_onglet as $obj_onglet)
      {
        //-- récupération de la valeur de l'onglet
        $stri_value=$obj_onglet->getValue();
        $stri_name=$obj_onglet->getName();
         
        //-- représentation de l'onglet
        $obj_cb=new checkbox("menu_personnalise[]",$stri_name);
        
        //-- gestion du coche
        if(in_array($stri_name, $arra_onglet_visible))
        {
           $obj_cb->setChecked(true);
        }
        
        //-- ajout à la table
        $obj_tr=$obj_table_all_onglet->addTr();
          $obj_tr->addTd($obj_cb);
          $obj_tr->addTd($stri_value);
      }                         
          
      return $obj_table_all_onglet;       
    }
  
 
  /****************************************************************************
  /*  fonction htmlValue()
  /*  Description : permet d'afficher le code html
  /*  Paramètre : aucun 
  /*  retour :  javascript et tableau html des onglets
  *****************************************************************************/
  public function htmlValue($type_retour = 'string')
  { 
    global $bgcolor2,$ModName;
    $nb_onglet = $this->nb_onglet;
    
    //- lancement du traitement
    if(!$this->bool_managed)
    {
      $this->manage();
    }
    
    //- récupération de tous les onglets
    $arra_onglet = $this->getOnglet();

    //- récupération des noms des onglets visible
    $arra_menu_perso=$this->arra_menu_perso;

    //- filtrage des onglets visibles
    $arra_onglet_visible=array();
    foreach($arra_menu_perso as $stri_valeur_onglet)
    {
      //-- récupération de l'onglet
      $obj_onglet=$this->getOngletByName($stri_valeur_onglet);
      
      //-- ajout à la liste des visibles
      $arra_onglet_visible[]=$obj_onglet;
    }
   
    //- initialialisation de la table
    $this->arra_onglet=$arra_onglet_visible;    //passage uniquement des onglets visible pour construction parent
    $arra_temp = parent::htmlValue($arra_tableau = array());//si htmlValue paramétré par un tableau alors il retourne le table html et le javascript associé 
    $this->arra_onglet=$arra_onglet; //restauration de tous les onglets
    $obj_table = $arra_temp["obj_table"];
    $obj_table->setBorder(0);
    $obj_table->setCellspacing(0);
    $obj_table->setCellpadding(0);
      $obj_tr = $obj_table->getIemeTr(0); //on récupère la ligne des onglets    
    $stri_js = $arra_temp["js"];
   
    //- construction de l'interface permettant de voir tous les onglets                
    //- image permettant d'afficher la div invisible
    $obj_img_ajout_ong = new img("images/add_down.png");
    $obj_img_ajout_ong->setOnclick("menu_dyn_v2.displayListeOnglet($(this));");
    $obj_img_ajout_ong->setStyle("cursor:pointer;");
    $obj_img_ajout_ong->setTitle(__LIB_DOWN_UP_MENU);
    $obj_img_ajout_ong->setClass('infobulle');
    $obj_img_ajout_ong->setHeight("25px");
    $obj_img_ajout_ong->setWidth("25px");
  
    $obj_tr->addTd($obj_img_ajout_ong);
    $obj_tr->addTd($this->constructTableForAllOnglet());
    
    //- ajout du js
    $obj_javascripter=new javascripter();
        $obj_javascripter->addFile("includes/classes/html_class/menu_dyn_v2.class.js");

    

    return $obj_table->htmlValue().$obj_javascripter->javascriptValue();
     /*
    for($i = 0 ; $i<count($arra_onglet) ; $i++){$arra_info_onglet[$i]["display"] = "none";}//on défini les onglet comme non visible
  
  
   
       
   
    echo "<pre>perso";
    var_dump($arra_menu_perso);
    echo "</pre>";
    
    //si il n'y a pas de menu personnalisé   
    if($arra_menu_perso[0] == ""){  
      if(isset($_POST["hid_onglet_".self::$int_menu_dyn]) AND $_POST["hid_onglet_".self::$int_menu_dyn] != null )//si on clic sur un lien de la div alors on réinitialise l'affichage des onglets.
      {                                                                    
    
        //modif LP 22/02/2012
       foreach($this->arra_onglet as $i=>$arra_onglet)
      {
          if($arra_onglet->getValue() == $_POST["hid_onglet_".self::$int_menu_dyn]){
              $_SESSION['display_onglet_'.self::$int_menu_dyn] = $i;     //on place en session la position de l'onglet
              $arra_info_onglet = $this->showOnglet($arra_info_onglet, $i);   //on affiche l'onglet souhaité
              $title = $arra_onglet->getName();
              parent::forceSelectedOnglet($title);//on force la selection de l'onglet 
          }
        }
        
      }
      else if($_SESSION['display_onglet_'.self::$int_menu_dyn] != "")//si click sur un onglet alors on affiche uniquement les onglets précédent
      {         
        $arra_info_onglet = $this->showOnglet($arra_info_onglet, $_SESSION['display_onglet_'.self::$int_menu_dyn]);
      }
      else
      {   
              
        for($i = 0; $i<$nb_onglet; $i++)//si on vient d'arriver sur la page alors on affiche le nombre d'onglet par défaut
        {
          $arra_info_onglet[$i]["display"] = "''";         
        }
            
      }
    }else{   //si il y a un menu personnalisé  
      if(isset($_POST["hid_onglet_".self::$int_menu_dyn]) AND $_POST["hid_onglet_".self::$int_menu_dyn] != null ) //si on clic sur un lien de la div alors on réinitialise l'affichage des onglets.
      {                                     
  
        foreach($this->arra_onglet as $i=>$arra_onglet)
        {
          if($arra_onglet->getValue() == $_POST["hid_onglet_".self::$int_menu_dyn]){
              $_SESSION['display_onglet_'.self::$int_menu_dyn] = $i;
              $arra_info_onglet[$i]["display"] = "''";
              $title = $arra_onglet->getName();
              parent::forceSelectedOnglet($title);//on force la selection de l'onglet 
          }
        }
      }
      else if($_SESSION['display_onglet_'.self::$int_menu_dyn] != "")//si click sur un onglet alors on l'affiche meme si il ne fait pas partie des onglets personnalisés
      {         
        $arra_info_onglet[$_SESSION['display_onglet_'.self::$int_menu_dyn]]["display"] = "''";
      }
  
  
      foreach($arra_menu_perso as $menu_perso){
        foreach($this->arra_onglet as $i=>$arra_onglet)
        {
          if($menu_perso == $arra_onglet->getValue()){
              $arra_info_onglet[$i]["display"] = "''";
          }
        }
      }
      
    }
 

   
    
    //on récupère les données de la classe mère
    $arra_temp = parent::htmlValue($arra_tableau = array());//si htmlValue paramétré par un tableau alors il retourne le table html et le javascript associé 
    $obj_table = $arra_temp["obj_table"];
    $obj_table->setBorder(0);
    $obj_table->setCellspacing(0);
    $obj_table->setCellpadding(0);
      $obj_tr = $obj_table->getIemeTr(0); //on récupère la ligne des onglets
     
    $stri_js = $arra_temp["js"];

    $arra_onglet = $this->getOnglet();
    $int_onglet = count($arra_onglet);//nb d'onglet créé pour le menu courant

    $obj_form = new form("modules.php?op=modload&name=$ModName&file=index","POST","","form_onglet");
    $obj_form->setId('form_onglet_'.self::$int_menu_dyn);//on créé un formulaire pour chaque nouveau menu
    
    $obj_hid = new hidden("hid_onglet_".self::$int_menu_dyn,"");
    $obj_hid->setId("hid_onglet_".self::$int_menu_dyn);
    if(isset($_POST["hid_onglet_".self::$int_menu_dyn])){$obj_hid->setValue($_POST["hid_onglet_".self::$int_menu_dyn]);}

    
    // création du lien pour la div invisible 
    foreach($this->arra_onglet as $i=>$arra_onglet)
    {
      $arra_info_onglet[$i]["titre"] = $arra_onglet->getValue();//on récupère leur valeur
      $obj_a = new a("#",$arra_info_onglet[$i]["titre"],false);
      
      //LP : modif value de obj_hid le 22/02/2012
      // $i -> $arra_onglet[$i]->getValue() 
            //alert('document.getElementById(\'".$obj_hid->getId()."\').value=\'".$arra_onglet->getValue()."\';document.getElementById(\'form_onglet_".self::$int_menu_dyn."\').submit();');
      $obj_a->setOnclick("

      
      document.getElementById('".$obj_hid->getId()."').value='".$arra_onglet->getValue()."';document.getElementById('form_onglet_".self::$int_menu_dyn."').submit();");

      //LP 22/02/2012 :  if($i == $_SESSION['display_onglet_'.self::$int_menu_dyn]) $obj_a->setStyle('font-weight: bold;');
      if($arra_onglet == parent::getActiveOnglet()) $obj_a->setStyle('font-weight: bold;');
  
      $arra_info_onglet[$i]["lien"] = $obj_a->htmlValue();
    }

    //on place les liens dans une variable et on affiche... ou pas les onglets
    /*$stri_onglet_invisible .= "<ul style='list-style-type:none; padding-left:5px; padding-right:5px; margin:0;'><li>";
    for($i = 2 ; $i<$int_onglet+3 ; $i++)
    {
      $obj_td = $obj_tr->getIemeTd($i);
      $obj_td->setStyle('display:'.$arra_info_onglet[$i-2]["display"].';');
      $stri_onglet_invisible .= $arra_info_onglet[$i-2]["lien"]."<br />";
    }*/
      /*
    //image permettant d'envoyer le formulaire de menu perso
    $img_menu_perso = new img("images/module/PNG/commerce-032x032.png");
      $img_menu_perso->setClass('infobulle');
      $img_menu_perso->setTitle(_MENU_PERSONNALISE);
      $img_menu_perso->setStyle('cursor:pointer');
      $img_menu_perso  ->setOnclick(" 
        var obj_form=$('#form_onglet_".self::$int_menu_dyn."');
        var obj_action=document.createElement('input');
            obj_action.type='hidden';
            obj_action.name='actionMenuDyn';
            obj_action.value=".self::$int_menu_dyn.";
            obj_form.append(obj_action);             
        obj_form.submit();
        "); 
        
        
    $obj_table_onglet_invisible = new table();
      $obj_tr_onglet_invisible = $obj_table_onglet_invisible->addTr();
      $obj_tr_onglet_invisible->setClass("titre2");
      $obj_tr_onglet_invisible->setStyle("font-size:12px;");
         $obj_td_onglet_invisible = $obj_tr_onglet_invisible->addTd($img_menu_perso->htmlValue());
         $obj_td_onglet_invisible->setWidth("20%");
         $obj_td_onglet_invisible = $obj_tr_onglet_invisible->addTd(_MENU_LIEN);
            $obj_td_onglet_invisible->setAlign('center');
            
            
           
            //- Menu selectionner tous
            $obj_a_all = new a('#', _SELECT_DESELECT_ALL);
                $obj_a_all->setOnclick('check_unchek_menu($(this)); return false;');
            
            
            $obj_tr_onglet_invisible = $obj_table_onglet_invisible->addTr();
            $obj_tr_onglet_invisible->setHeight(30);
                $obj_td_onglet_invisible = $obj_tr_onglet_invisible->addTd($obj_a_all);
                    $obj_td_onglet_invisible->setAlign('center');
                    $obj_td_onglet_invisible->setColspan(2);
           
            
            
    $stri_onglet_invisible .= "<ul style='list-style-type:none; padding-left:5px; padding-right:5px; margin:0;'><li>";

   $counter = 0;
    foreach($this->arra_onglet as $i=>$arra_onglet)
    {
      $obj_td = $obj_tr->getIemeTd($counter+2);      //récupération de l'onglet correspondant
      $counter++;
      $obj_td->setStyle('display:'.$arra_info_onglet[$i]["display"].';');   //affichage ou non de l'onglet

      $obj_checkbox = new checkbox("menu_personnalise[]", $arra_onglet->getValue());   //une checkbox par onglet, permettant de l'ajouter au menu perso
        $obj_checkbox->setOnclick("              
        if($(this).is(':checked') ){
        } else {
          $('<input type=\'hidden\' name=\'non_checked\' value=\'".$arra_onglet->getValue()."\' />').prependTo($(this).parent());
        }
        ");   
      if($arra_info_onglet[$i]["display"] == "''" AND in_array($arra_onglet->getValue(), $arra_menu_perso)){    
        //Si on affiche l'onglet et qu'il fait parti du menu personnalisé, on sélectionne la checkbox
        $obj_checkbox->setChecked(true);
      }
      
      //ajout des checkboxes et liens au tableau
      $obj_tr_onglet_invisible = $obj_table_onglet_invisible->addTr();
      if($counter%2 == 1){            //couleur du fond (1 ligne sur 2)
         $obj_tr_onglet_invisible->setStyle("background-color:#DBD2DA");
      }
      $obj_checkbox->setId('item_menu_'.$counter);
      
      $obj_font = new font($arra_info_onglet[$i]['titre']);
      $obj_font->setOnmouseover("this.style='cursor:pointer'");
      
       $obj_td_onglet_invisible = $obj_tr_onglet_invisible->addTd($obj_checkbox->htmlValue());
       //$obj_td_onglet_invisible = $obj_tr_onglet_invisible->addTd($arra_info_onglet[$i]["lien"]);
       $obj_td_onglet_invisible = $obj_tr_onglet_invisible->addTd('<label for="item_menu_'.$counter.'">'.$obj_font->htmlValue().'</label>');
    }
 
     
    $stri_onglet_invisible .= $obj_table_onglet_invisible->htmlValue();
 
    //on masque la div contenant le tableau de lien et checkboxes
    $obj_div_ajout_ong = new div("calque",$obj_form->getStartBalise().$stri_onglet_invisible.$obj_hid->htmlValue().$obj_form->getEndBalise());
    $obj_div_ajout_ong->setId("div_".self::$int_menu_dyn);
    $obj_div_ajout_ong->setStyle("display:none; position:absolute; background-color:white; border:solid $bgcolor2 2px; z-index : 100; border-radius: 7px; box-shadow: 1px 3px 5px 1px;");
    
    //image permettant d'afficher la div invisible
    //$obj_img_ajout_ong = new img("images/module/connexion_v2_bis/icontexto-valid_add_mdp.png");
    $obj_img_ajout_ong = new img("images/add_down.png");
    $obj_img_ajout_ong->setOnclick("afficher_div('".$obj_div_ajout_ong->getId()."',this);");
    $obj_img_ajout_ong->setStyle("cursor:pointer;");
    $obj_img_ajout_ong->setTitle(__LIB_DOWN_UP_MENU);
    $obj_img_ajout_ong->setClass('infobulle');
    $obj_img_ajout_ong->setHeight("25px");
    $obj_img_ajout_ong->setWidth("25px");
    
    
    $obj_table_onglet = new table();
    $obj_table_onglet->setBorder(0);
    $obj_table_onglet->setCellpadding(0);
    $obj_table_onglet->setCellspacing(0); 
     $obj_table_onglet->setId($this->getId()."_table_onglet"); 
      $obj_tr = $obj_table_onglet->addTr();
        $obj_td = $obj_tr->addTd($obj_table->htmlValue().$stri_js);
        $obj_td = $obj_tr->addTd($obj_img_ajout_ong->htmlValue().$obj_div_ajout_ong->htmlValue());

    $obj_javascripter = new javascripter();
    //$obj_javascripter->addFile("includes/classes/jquery/jquery-1.4.2.min.js");
    $obj_javascripter->addFunction("
    

    /***
    *
    * //- Méthode d'affichage du menu personnalisé
    * // -Apport amélioration Romain le 26/10/2015 
    *
    
    function afficher_div(calque,obj_img)
    {
      //document.getElementById(calque).style.display=='none'? $('#'+calque).show('slow') : $('#'+calque).hide('slow');

      //Gestion du chemin de l'image
      stri_src = (document.getElementById(calque).style.display=='none')? 'images/add_up.png' : 'images/add_down.png';
    
      //Gestion de l'action sur click du bouton
      $(obj_img).fadeOut('fast',function()
      {
        //Selecteur sur la div 
        var selector = $('#'+calque);
        
        //Masque ou affiche la DIV
        $(selector).toggle('slow');
        
        //Toggle de l'image
        $(this).attr('src',stri_src).fadeIn();
        
      });
      
    }
    

    /***
    *
    * //- Méthode de selection parmis toutes les options du menu
    * // -Apport amélioration Romain le 26/02/2016
    *
  
    function check_unchek_menu(obj_a)
    {
    
        var obj_selector = $(obj_a).closest('table').find('input[type=\"checkbox\"]');

        
        if (!window.bool_menu_option_checked)
        {
            $(obj_selector).attr('checked','checked');
            window.bool_menu_option_checked = true;
        }
        else
        {
            $(obj_selector).removeAttr('checked');
            window.bool_menu_option_checked = false;
        }
        
        
        return;
        
    }



    ");

    /***********************************************************************
    if($this->bool_hide_menu == true){ 
    $obj_javascripter->addFunction('
      $("#'.$this->getId().'_table_onglet").hide("block");
    ');  
    }
    ***********************************************************************/
    
   /* for($i=0; $i < count($arra_onglet); $i++){
    echo "<pre>";
    var_dump($arra_onglet[$i]);
    echo "</pre>";
          echo " _____ ";
          echo "<pre>";
          var_dump($arra_info_onglet[$i]);
          echo "</pre>";
          echo "<br/><br/><br/>";
    }
       */
    /*
    $this->arra_info_onglet = $arra_info_onglet;
    return $stri_js.$obj_javascripter->javascriptValue().$obj_table_onglet->htmlValue();  */
  }
  
  /****************************************************************************
  /*  fonction showOnglet()
  /*  Description : permet d'afficher ou non les onglets du menu dynamique,
  /*                en fonction du nombre d'onglet à afficher (par défaut 10).
  /*  Paramètre : $arra_info_onglet => lien d'affichage d'un onglet
  /*                                   état display destiné à l'affichage de l'onglet 
  /*                                   titre de l'onglet
  /*  retour : $arra_info_onglet.
  *****************************************************************************/
/*  public function showOnglet($arra_info_onglet, $int_onglet_select)
  {
    $nb_onglet = $this->nb_onglet;
    
    $nb_avap = ($nb_onglet-1);
    if($nb_avap%2 == 0)//nb pair
    {
      $nb_avap = $nb_avap/2;
      $nb_dbt = $int_onglet_select-$nb_avap;
      $nb_fin = $int_onglet_select+$nb_avap+1;
      for($i = $nb_dbt; $i<$nb_fin; $i++)
      {
        if($arra_info_onglet[$i]["display"] == null && $i > $int_onglet_select) {$nb_dbt = $nb_dbt-1;}
        else if($arra_info_onglet[$i]["display"] == null && $i < $int_onglet_select) {$nb_fin = $nb_fin+1;}
      }
      for($i = $nb_dbt; $i<$nb_fin; $i++)
      {
        $arra_info_onglet[$i]["display"] = "''";
      }
    }
    else //nb impaire
    {
      $nb_avap = ($nb_avap+1)/2;
      $nb_dbt = $int_onglet_select-$nb_avap;
      $nb_fin = $int_onglet_select+$nb_avap+1;
      for($i = $nb_dbt; $i<$nb_fin; $i++)
      {
        if($arra_info_onglet[$i]["display"] == null && $i > $int_onglet_select) {$nb_dbt = $nb_dbt-1;}
        else if($arra_info_onglet[$i]["display"] == null && $i < $int_onglet_select) {$nb_fin = $nb_fin+1;}
      }
      for($i = $nb_dbt; $i<$nb_fin; $i++)
      {
        $arra_info_onglet[$i]["display"] = "''";
      }
    }
    return $arra_info_onglet;
  } */
  
   /****************************************************************************
  /*  Permet d'ajouter un nouvel onglet au menu
  /*  
  /*  Paramètre :string : le nom de l'onglet
                 string : la valeur affichée de l'onglet
      Retour : obj onglet : l'onglet nouvellement ajouté
  *****************************************************************************/
  /*public function addOnglet($name,$value)
  {
    $name=str_replace(array(" ","-","."), '_',$name);
    return parent::addOnglet($name,$value);
  }*/
  
  /****************************************************************************
  /*  fonction autoBuild()
  /*  Description : permet de construire automatiquement un menu dynamique,
  /*                en fonction du répertoire courant et des sous répertoire
  /*  Paramètre : $current_directory => adresse du répertoire courant
  *****************************************************************************/
  public function autoBuild($current_directory)
  {
    
     //définition du répertoire à parcourir
    $stri_parent_directory=dirname($current_directory);
    
    //recherche des sous répertoires
    $arra_directory=array();
    $ressource = opendir($stri_parent_directory); 
    
    //parcours de tous les fichiers présent dans le dossier parent
    while ($stri_file_name = readdir($ressource)) 
    {
      $stri_current_directory=$stri_parent_directory."/".$stri_file_name;
      //si le fichier courrant est un répertoire 
      if(is_dir($stri_current_directory))
      {
       //on ne s'interesse pas aux répertoires . et ..
       if(!(($stri_file_name==".")||($stri_file_name=="..")))
       {$arra_directory[]=$stri_file_name;}
      } 
    }
    
    //tri des répertoire par ordre alphabétique
    sort($arra_directory);
      
      
      //ajout d'un onglet par répertoire
    foreach($arra_directory as $stri_directory)
    {
     if(is_file("$stri_parent_directory/$stri_directory/index.php"))
     {
      //ajout de l'onglet
      $obj_onglet=$this->addOnglet("onglet_".$stri_directory,constante::constant($stri_directory));
  
      $obj_onglet->setId($stri_directory);
        
      //l'onglet route sur le fichier index.php contenu dans le sous répertoire
      $obj_onglet->addPage("$stri_parent_directory/$stri_directory/index.php","default");
      
     }
    }
    


 }
 
  
 
 /**************************
  *Permet de passer automatiquement à l'onglet suivant du menu
  *param :  $int_id_contruct_next => id permettant de construire l'étape suivante en lien avec l'étape actuelle
  *retour : aucun
  */   
 /* function automaticNextStep($int_id_construct_next)
  {
    //creation de la session contenant l'id permettant de construire l'étape suivante
    $_SESSION['com_id_contrat'] = $int_id_construct_next;
    
                   
    //récupération du nom de l'onglet suivant
    $arra_onglet = $this->getOnglet();     
    $active_onglet = $this->getActiveOnglet();   
         
    foreach($arra_onglet as $i => $onglet){    
      if($onglet == $active_onglet){
        $stri_next_onglet_name = $arra_onglet[$i+1]->getName();              
      }
    }     
                                                  
    //message + simulation de clic pour passage à l'onglet suivant
    echo "<script>            
             alert(\""._MSG_PASSGE_ETAPE_SUIVANTE."\"); 
             $('input[name$=\"".$stri_next_onglet_name."\"]').click(); 
          </script>";

    
  }
  
  
  
  // Permet de masquer le menu du module (utilisé avec le passage automatique à l'onglet suivant)
  // param : $bool_hide_menu : booleen, true pour masquer le menu
  // retour : aucun
  public function hideMenu($bool_hide_menu){
    if($bool_hide_menu == true){ 
     echo '<script> 
               document.getElementById("'.$this->getId().'_table_onglet").style.display = "none";
           </script>';
    }
  }*/
}
?>

