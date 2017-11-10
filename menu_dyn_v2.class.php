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
  protected $arra_group;            //Pour pouvoir avoir des groupes d'onglets
   
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
    $this->arra_group=array();
      
      
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
 
 } 
 /*
  /Permet de regrouper les options à l'intérieur d'une liste
   @param : $lib =>  Correspond au titre du groupe
 */
 public function addGroup($lib)
 {
    
    $nb=count($this->arra_onglet);
    $nb--;
    $nb_group=count($this->arra_group);
    $this->arra_group[$nb_group]["libelle"]=$lib;   //Ajout du libelé du nouveau groupe
    $this->arra_group[$nb_group]["dernier_membre"]=-1;
    if($nb>-1)                                     // Si ce n'est pas la premier groupe
    {$this->arra_group[$nb_group-1]["dernier_membre"]=$nb;} // On ajout au groupe d'avant le numéro de sa dernière option
    
  }
  
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
    
    
    //- pour garder l'ordre d'origine d'affichage quel que soit l'ordre d'enregistrement des pools
    $arra_onglet_name=array();
    foreach($arra_onglet as $obj_onglet)
    {$arra_onglet_name[]=$obj_onglet->getName();}     
    $this->arra_menu_perso=array_intersect($arra_onglet_name,$this->arra_menu_perso);
    
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
     }
     
     //- cas de selection forcé d'un onglet
     if(isset($_POST['menu_dyn_v2__select_onglet']))
     { 
       parent::setSelectedOnglet($_POST['menu_dyn_v2__select_onglet']);       
       $this->arra_active[$arra_key[0]]=$_POST['menu_dyn_v2__select_onglet'];  
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
      $img_menu_perso->setClass('infobulle bt_commerce');
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
     
    //- initialisation du mode groupe 
    $int_nb_group=count($this->arra_group); 
    // Test du mode groupe  : on est en mode groupe s'il y a au moins un groupe
    $bool_gr=($int_nb_group>0)?true:false;
    //nombre de boucle principale à faire. En mode groupe c'est le nombre de groupe, sinon 1                          
    $int_nb_boucle=($bool_gr)?$int_nb_group:1;
    //compte le nombre d'option
    $int_nb_option=count($arra_onglet);   
    //initialisation de l'option traitée
    $int_opt=0;
    
     //pour chaque groupe ou juste une fois si on est pas en mode groupe
    for($i=0;$i<$int_nb_boucle;$i++)
    {
     //calcul du dernier membre du groupe
     $dernier_membre=(($bool_gr)&&($i+1<$int_nb_group))?$this->arra_group[$i]["dernier_membre"]:$int_nb_option;
     //ouverture de groupe
     $stri_res.=($bool_gr)?"<optgroup label='".$this->arra_group[$i]["libelle"]."'>":"";       
              
      //- ajout à la table du groupe
       $obj_tr=$obj_table_all_onglet->addTr();
          $obj_td=$obj_tr->addTd($this->arra_group[$i]["libelle"]);
            $obj_td->setColspan(2);
            $obj_td->setClass('titre3');
      
       //- ajout des onglets
       while(($int_opt<=$dernier_membre)&&($int_opt<$int_nb_option))  
       {
         //-- récupération de l'onglet
         $obj_onglet=$arra_onglet[$int_opt];         
         $int_opt++;
         
          //-- récupération de la valeur de l'onglet
          $stri_value=$obj_onglet->getValue();
          $stri_name=$obj_onglet->getName();
          
          //-- lien sur l'onglet
          $obj_a=new a('#',$stri_value);
            $obj_a->setOnclick('menu_dyn_v2.selectOnglet($(this));');
           
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
            $obj_tr->addTd($obj_a);
       }                                   
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
  }
  
  
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
 
 
}
?>

