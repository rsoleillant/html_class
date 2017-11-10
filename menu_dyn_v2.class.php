<?php
/**********************************************************************************/
/*  Titre : menu_dyn_v2.php
/*  Auteur : R�my Soleillant
/*  Cr�ation : 02/01/2010
/*  Description : Permet d'afficher un nombre d'onglet pr�d�finie sur la barre de menu
/*  H�ritage : menu.class.php
/**********************************************************************************/

class menu_dyn_v2 extends menu
{
   
  //**** attribute *************************************************************
  protected $nb_onglet;             //d�finie le nombre d'onglet visible, par d�faut 5
  protected $arra_info_onglet;      //information concernant les onglet (display,lien)
  protected $arra_onglet=array();   //tableau de tous les onglets du menu
  protected $arra_active;           //tableau contenant les donn�es de base permetant de retrouver l'onglet actif 
  static private $int_menu_dyn;     //le nombre d'objet menu qui ont �t� cr��s
  protected $bool_hide_menu=false;  //par d�faut on affiche le menu
  
  protected $arra_menu_perso;       //Liste des onglets � affich�s
  protected $bool_managed;          //Pour indiquer si le traitement a d�j� �t� fait
  protected $arra_group;            //Pour pouvoir avoir des groupes d'onglets
   
  //**** getter ****************************************************************
  public function getHideMenu() { return $this->bool_hide_menu; } 
  public function getMenuPerso(){return $this->arra_menu_perso;}

  //**** setter ****************************************************************
  public function setHideMenu($x) { $this->bool_hide_menu = $x; } 
  
   
  //**** constructor ***********************************************************
  /****************************************************************************
  /*  fonction construct()
  /*  Description : permet de lancer la construction d'un menu_dyn � partir de la classe m�re menu
  /*  Param�tre : $url => le chemin relatif par lequel on va acc�der au menu 
  /*              $act_class => nom de la classe css � utiliser pour l'onglet actif 
  /*              $inact_class => nom de la classe css � utiliser pour les onglets inactifs
  /*              act_src => le chemin de l'image � utiliser pour l'onglet actif 
  /*              $inact_src => le chemin de l'image � utiliser pour les onglets inactifs
  /*              $nb_onglet => le nombre d'onglet � afficher par d�faut
  /*  retour :  void
  *****************************************************************************/
  function __construct($url, $act_class, $inact_class, $act_src, $inact_src,$call=__FILE__,$nb_onglet = "10") 
  {
      
    $this->bool_managed=false; 
    $this->arra_group=array();
      
      
    $int_num_args=func_num_args();
    if($int_num_args<2)//si aucun param�tre n'a �t� pass�
    {
      $int_id=crc32($url);
      $stri_file=$url;
      if($int_num_args==0)//d�tection automatique du script appellant
      {
       $temp=get_included_files(); 
       $stri_file=array_pop($temp);
       $int_id=crc32($stri_file);
      
      }//g�n�ration d'un identifiant pour le menu bas� sur le script appellant
      
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
  /Permet de regrouper les options � l'int�rieur d'une liste
   @param : $lib =>  Correspond au titre du groupe
 */
 public function addGroup($lib)
 {
    
    $nb=count($this->arra_onglet);
    $nb--;
    $nb_group=count($this->arra_group);
    $this->arra_group[$nb_group]["libelle"]=$lib;   //Ajout du libel� du nouveau groupe
    $this->arra_group[$nb_group]["dernier_membre"]=-1;
    if($nb>-1)                                     // Si ce n'est pas la premier groupe
    {$this->arra_group[$nb_group-1]["dernier_membre"]=$nb;} // On ajout au groupe d'avant le num�ro de sa derni�re option
    
  }
  
  /*
    Permet de lancer le traitement d'enregistrement et de chargement des onglets s�lectionn�
  
  */
  public function manage()
  {
     global $ModName;
   
     //- r�cup�ration de tous les onglets
     $arra_onglet = $this->getOnglet();
        
     //- on incr�mente le compteur permettant de savoir le nombre de menu_dyn cr��
     //  permet par la suite d'identifier certains �l�ments de chaque objet menu_dyn
    self::$int_menu_dyn++;
    
    //- utilitaire de gestion des valeurs de la collection         
    $obj_parameter_stat_v2 = new parameter_state_v2("menu_perso_".self::$int_menu_dyn, pnuserGetVar("uid"), $ModName );     
    
    //- r�cup�ration des valeurs existantes en base
    $this->arra_menu_perso = $obj_parameter_stat_v2->load();      
   
    //- lancement du traitement
    if(isset($_POST['actionMenuDyn'])&&($_POST['actionMenuDyn']==self::$int_menu_dyn))
    {
      //- d�duction des onglets � supprimer
      $arra_to_delete=array_diff($this->arra_menu_perso,$_POST['menu_personnalise']);
      
      //- d�duction des onglest � ajouter
      $arra_to_add=array_diff($_POST['menu_personnalise'],$this->arra_menu_perso);
      
      //- enregistrement des nouveaux onglets
      foreach($arra_to_add as $stri_onglet_name)
      { 
        $obj_parameter_stat_v2->insert($stri_onglet_name); 
      }
     
      //- suppression des onglets plus d'actualit�
      foreach($arra_to_delete as $stri_onglet_name)
      { 
        $obj_parameter_stat_v2->delete($stri_onglet_name); 
      } 
      
      //- actualisation des valeurs existantes en base
      $this->arra_menu_perso = $obj_parameter_stat_v2->getCategorie();
      
      
    }
    
    //- gestion d'initialisation des onglets affich�
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
       //- d�selection par d�faut
       foreach($arra_onglet as $obj_onglet)
       {$obj_onglet->setSelected(false);}
        
       //- s�lection du premier onglet visible
       $arra_menu_perso_copy=$this->arra_menu_perso;
       $stri_premier_onglet=array_shift($arra_menu_perso_copy);
       parent::setSelectedOnglet($stri_premier_onglet);       
       $this->arra_active[$arra_key[0]]=$stri_premier_onglet;      
     }
     
     //- cas de selection forc� d'un onglet
     if(isset($_POST['menu_dyn_v2__select_onglet']))
     { 
       parent::setSelectedOnglet($_POST['menu_dyn_v2__select_onglet']);       
       $this->arra_active[$arra_key[0]]=$_POST['menu_dyn_v2__select_onglet'];  
     }
     
    //- marquage du traitement comme fait
    $this->bool_managed=true;        
  }
  
  //Permet de r�cup�rer un onglet en fonction de sa valeur
  public function getOngletByValue($stri_value)
  {
    foreach($this->getOnglet() as $obj_onglet)
    {
      if($obj_onglet->getValue()==$stri_value)
      {return $obj_onglet;}
    }
    
    return false;
  }
  
    //Permet de r�cup�rer un onglet en fonction de sa valeur
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
   * Permet de construire l'interface servant � s�lectionner les onglets visibles
   *    
   * �return : obj table : la table repr�sentant l'interface
   **/        
    public function constructTableForAllOnglet()
    {  
      global $bgcolor2;
               
      //- r�cup�ration de tous les onglets
      $arra_onglet = $this->getOnglet();
  
      //- r�cup�ration des noms des onglets visible
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
         
       //- fonctionnalit� de coche / d�coche
        $obj_a_all = new a('#', _SELECT_DESELECT_ALL);
            $obj_a_all->setOnclick('menu_dyn_v2.check_unchek_menu($(this)); return false;');  
          $obj_tr=$obj_table_all_onglet->addTr();
            $obj_td=$obj_tr->addTd($obj_a_all);
              $obj_td->setColspan(2);
     
    //- initialisation du mode groupe 
    $int_nb_group=count($this->arra_group); 
    // Test du mode groupe  : on est en mode groupe s'il y a au moins un groupe
    $bool_gr=($int_nb_group>0)?true:false;
    //nombre de boucle principale � faire. En mode groupe c'est le nombre de groupe, sinon 1                          
    $int_nb_boucle=($bool_gr)?$int_nb_group:1;
    //compte le nombre d'option
    $int_nb_option=count($arra_onglet);   
    //initialisation de l'option trait�e
    $int_opt=0;
    
     //pour chaque groupe ou juste une fois si on est pas en mode groupe
    for($i=0;$i<$int_nb_boucle;$i++)
    {
     //calcul du dernier membre du groupe
     $dernier_membre=(($bool_gr)&&($i+1<$int_nb_group))?$this->arra_group[$i]["dernier_membre"]:$int_nb_option;
     //ouverture de groupe
     $stri_res.=($bool_gr)?"<optgroup label='".$this->arra_group[$i]["libelle"]."'>":"";       
              
      //- ajout � la table du groupe
       $obj_tr=$obj_table_all_onglet->addTr();
          $obj_td=$obj_tr->addTd($this->arra_group[$i]["libelle"]);
            $obj_td->setColspan(2);
            $obj_td->setClass('titre3');
      
       //- ajout des onglets
       while(($int_opt<=$dernier_membre)&&($int_opt<$int_nb_option))  
       {
         //-- r�cup�ration de l'onglet
         $obj_onglet=$arra_onglet[$int_opt];         
         $int_opt++;
         
          //-- r�cup�ration de la valeur de l'onglet
          $stri_value=$obj_onglet->getValue();
          $stri_name=$obj_onglet->getName();
          
          //-- lien sur l'onglet
          $obj_a=new a('#',$stri_value);
            $obj_a->setOnclick('menu_dyn_v2.selectOnglet($(this));');
           
          //-- repr�sentation de l'onglet
          $obj_cb=new checkbox("menu_personnalise[]",$stri_name);
          
          //-- gestion du coche
          if(in_array($stri_name, $arra_onglet_visible))
          {
             $obj_cb->setChecked(true);
          }
          
          //-- ajout � la table
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
  /*  Param�tre : aucun 
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
    
    //- r�cup�ration de tous les onglets
    $arra_onglet = $this->getOnglet();

    //- r�cup�ration des noms des onglets visible
    $arra_menu_perso=$this->arra_menu_perso;

    //- filtrage des onglets visibles
    $arra_onglet_visible=array();
    foreach($arra_menu_perso as $stri_valeur_onglet)
    {
      //-- r�cup�ration de l'onglet
      $obj_onglet=$this->getOngletByName($stri_valeur_onglet);
      
      //-- ajout � la liste des visibles
      $arra_onglet_visible[]=$obj_onglet;
    }
   
    //- initialialisation de la table
    $this->arra_onglet=$arra_onglet_visible;    //passage uniquement des onglets visible pour construction parent
    $arra_temp = parent::htmlValue($arra_tableau = array());//si htmlValue param�tr� par un tableau alors il retourne le table html et le javascript associ� 
    $this->arra_onglet=$arra_onglet; //restauration de tous les onglets
    $obj_table = $arra_temp["obj_table"];
    $obj_table->setBorder(0);
    $obj_table->setCellspacing(0);
    $obj_table->setCellpadding(0);
      $obj_tr = $obj_table->getIemeTr(0); //on r�cup�re la ligne des onglets    
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
  /*                en fonction du r�pertoire courant et des sous r�pertoire
  /*  Param�tre : $current_directory => adresse du r�pertoire courant
  *****************************************************************************/
  public function autoBuild($current_directory)
  {
    
     //d�finition du r�pertoire � parcourir
    $stri_parent_directory=dirname($current_directory);
    
    //recherche des sous r�pertoires
    $arra_directory=array();
    $ressource = opendir($stri_parent_directory); 
    
    //parcours de tous les fichiers pr�sent dans le dossier parent
    while ($stri_file_name = readdir($ressource)) 
    {
      $stri_current_directory=$stri_parent_directory."/".$stri_file_name;
      //si le fichier courrant est un r�pertoire 
      if(is_dir($stri_current_directory))
      {
       //on ne s'interesse pas aux r�pertoires . et ..
       if(!(($stri_file_name==".")||($stri_file_name=="..")))
       {$arra_directory[]=$stri_file_name;}
      } 
    }
    
    //tri des r�pertoire par ordre alphab�tique
    sort($arra_directory);
      
      
      //ajout d'un onglet par r�pertoire
    foreach($arra_directory as $stri_directory)
    {
     if(is_file("$stri_parent_directory/$stri_directory/index.php"))
     {
      //ajout de l'onglet
      $obj_onglet=$this->addOnglet("onglet_".$stri_directory,constante::constant($stri_directory));
  
      $obj_onglet->setId($stri_directory);
        
      //l'onglet route sur le fichier index.php contenu dans le sous r�pertoire
      $obj_onglet->addPage("$stri_parent_directory/$stri_directory/index.php","default");
      
     }
    }
    


 }
 
 
}
?>

