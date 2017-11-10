<?php

/*******************************************************************************
Create Date : 02/06/2006
 ----------------------------------------------------------------------
 Class name : menu
 Version : 2.1
 Author : Rémy Soleillant
 Description : gère des onglets
 Update : 16/12/2008
********************************************************************************/
include_once("table.class.php");
include_once("tr.class.php");
include_once("form.class.php");
include_once("hidden.class.php");
include_once("onglet.class.php");
      
class menu{
   
  //**** attribute *************************************************************
   
  protected $arra_onglet=array();   //=> tableau des onglets du menu
  protected $stri_active_class;     //=> nom de la classe css à utiliser pour l'onglet actif
  protected $stri_inactive_class;   //=> nom de la classe css à utiliser pour les onglets inactifs
  protected $stri_active_src;       //=> le chemin de l'image à utiliser pour l'onglet actif
  protected $stri_inactive_src;     //=> le chemin de l'image à utiliser pour les onglets inactifs
  protected $stri_url;              //=> le chemin relatif par lequel on va accèder au menu
  protected $stri_extra_html="";       //=> le code html supplémentaire qui peut être affiché
  protected $bool_multiple_actif=false; //=>booléen indiquant si le menu doit considérer plusieurs onglets actifs par navigateur
  protected $arra_active;             //tableau contenant les données de base permetant de retrouver l'onglet actif
  protected $stri_order_onglet;       //L'ordre d'affichage des onglets
  static protected $int_page_id;            //l'identificateur de la page contenant le menu 
  static private   $int_menu;        //=>le nombre d'objet menu qui ont été créés
  public $arra_sauv=array();        //=> tableau de serialisation
  
  //**** constructor ***********************************************************
  function __construct($url, $act_class, $inact_class, $act_src, $inact_src,$call=__FILE__) 
  {   
    //construit l'objet menu
    //@param : $url => le chemin relatif par lequel on va accèder au menu
    //@param : $act_class => nom de la classe css à utiliser pour l'onglet actif
    //@param : $inact_class => nom de la classe css à utiliser pour les onglets inactifs
    //@param : $act_src => le chemin de l'image à utiliser pour l'onglet actif
    //@param : $inact_src => le chemin de l'image à utiliser pour les onglets inactifs
    //@return : void
    
    menu::$int_menu++;

    $this->stri_url=$url;
    $this->stri_active_class=$act_class;
    $this->stri_inactive_class=$inact_class;
    $this->stri_active_src=$act_src;
    $this->stri_inactive_src=$inact_src;
    
    //purge des anciennes variables de 'session'
    $this->cleanSession();
  }
 
  //**** setter ****************************************************************
  public function setUrl($stri_url){$this->stri_url=$stri_url;}
  
  public function setExtratHtml($stri_value){$this->stri_extra_html=$stri_value;} 
  public function setMultipleActifOnglet($bool)
  {//Permet de disposer de plusieurs onglets actifs par navigateur.
   //ATTENTION ! Ceci ne fonctionnera correctement que si tous les changements
   //de page se font en envoyant un formulaire .
   
   $this->bool_multiple_actif=$bool;
  }
/*************************************************************
 * Permet de choisir l'ordre dans lequel les onglets doivent être triés
 *   
 * parametres : string : le tri des onglet : name : le trie sera effectué sur le nom des onglets
 *                                           value: le trie sera effectué sur la valeur des onglets 
 * retour :aucun
 *                        
 **************************************************************/ 
  public function setOrderOnglet($stri_value)
  {
   $arra_mode_gere=array("value","name");
   $this->stri_order_onglet=(in_array($stri_value,$arra_mode_gere))?$stri_value:"value";
   

  } 
  //**** getter ****************************************************************
  public function getOnglet()
  {  //si le tableau d'onglet doit etre trié, on le retourne trié aussi
     if($this->stri_order_onglet!="")
        uasort($this->arra_onglet, array ("menu", "compare_onglet"));
  
     return $this->arra_onglet;
  } 
  public function getActiveClass(){return $this->stri_active_class;} 
  public function getInactiveClass(){return $this->stri_inactive_class;} 
  public function getActiveSrc(){return $this->stri_active_src;} 
  public function getInactiveSrc(){return $this->stri_inactive_src;}
  public function getUrl(){return $this->stri_url;}  
  public function getExtratHtml(){return $this->stri_extra_html;}
  public function getOrderOnglet()
  {return $this->stri_order_onglet;}
  
  /************************
   *Permet de récupérer l'identifiant du menu
   *Param : aucun   
   *retour string : l'identifiant du menu 
   *************************/       
  public function getId()
 {   
  //si le mode multiple onglet actif n'est pas activé, on renvoi un id 
  //uniquement basé sur l'url
  if(!$this->bool_multiple_actif)
  {return $this->constructId();}
  
  //plusieurs onglets actifs possible
  $id_page=$this->getPageId();
  if($id_page=="")
  {$id_page=$this->createPageId();}
  $stri_id=$this->constructId().$id_page;
           
  return $stri_id;
 }
  //**** public method *********************************************************
  public function getIemeOnglet($int){return $this->arra_onglet[$int];}

  public function getActiveOnglet()
  {
    //renvoie l'onglet qui est sélectionné
    //@return : [obj] => l'onglet sélectionné
    
    for($i=0;$i<count($this->arra_onglet);$i++)
    {
      if($this->arra_onglet[$i]->getSelected())
      {return $this->arra_onglet[$i];}
    }
        
    return $this->arra_onglet[0];
  }   
    
  public function setSelectedOnglet($name)
  {
    //forcer la selection d'un onglet -à utiliser avec précaution-
    //@param : $name => nom de l'onglet
    //@return : void
    
    
 
    if($name!="")
    {
      for($i=0;$i<count($this->arra_onglet);$i++)
      {
        if($this->arra_onglet[$i]->getName()==$name)
        {
          $this->arra_onglet[$i]->setSelected(true); 
          //$stri_index=$this->stri_url."selected_onglet";
          $stri_id=$this->getId();
          $stri_index=$stri_id."selected_onglet";
       
          
          unset($_POST['selected_onglet']);
          $this->writeSession($stri_index,$name);
          
          
        }
        else
        {$this->arra_onglet[$i]->setSelected(false);}
      }
    }
  }
  
  public function addOnglet($name,$value)
  {
    //ajoute un onglet
    //@param : $name => le nom de l'onglet
    //@param : $value => le libellé de l'onglet
    //@return : $obj_onglet => l'onglet sous forme objet
   
    $i=count($this->arra_onglet);
    $obj_onglet=new onglet($name,$value,$this->stri_active_class,$this->stri_inactive_class,$this->stri_active_src,$this->stri_inactive_src);
    $this->arra_onglet[$i]=$obj_onglet;
    
    return $obj_onglet;
  }
  
  public function insertOnglet(onglet $obj_onglet)
  {
    //Autre méthode d'ajout d'un onglet
    //@param : $obj_onglet => onglet à ajouter
    //@return : $obj_onglet => l'onglet sous forme objet
     $i=count($this->arra_onglet);
     $this->arra_onglet[$i]=$obj_onglet;
     return  $this->arra_onglet[$i];
  }
  
  public function forceSelectedOnglet($str_name)
  {
   //Cette méthode permet de forcer un onglet à être selectioné.
   //Elle doit être appelée avant la méthode htmlValue
   //@param : $str_name => le nom de l'onglet
   //@return : void

    for($i=0;$i<count($this->arra_onglet);$i++)
    {
      $this->arra_onglet[$i]->setSelected(false);
    }
  
    $stri_index=$this->getId()."selected_onglet";
     
    $_POST[$stri_index]=$str_name;
    
  }

  public function htmlValue($type_retour="string")
  {
    //affiche le menu
    //@return : [string] => le menu sous forme html
    //          [arra] => le menu retourné en tableau et javascript (utilisé par menu_dyn)
    //récupération de l'id du menu
                        
    //gestion des tris des onglets
    if($this->stri_order_onglet!="")
    {
     uasort($this->arra_onglet, array ("menu", "compare_onglet"));
    }         
      
    $stri_id=$this->getId();
    $stri_form=$stri_id."_form";
    $stri_index=$stri_id."selected_onglet";
      
    $form=new form($this->stri_url,"post","");
    $form->setName($stri_form);
    
    $html_table=new table();
    $tr=new tr();
    $tr->addTd($form->getStartBalise()); 
    
    $hidden=new hidden($stri_index,'');
    $hidden_clicked=new hidden('clicked','');
    
    $tr->addTd($hidden->htmlValue().$hidden_clicked->htmlValue());
   
   
    //si on a passé en post un onglet, cela signifie que l'on viens de cliquer
    //sur un onglet ou qu'on l'on a utiliser la méthode forceSelectedOnglet
    $bool_selected=false;
    if(isset($_POST[$stri_index]))
    {
     $this->writeSession($stri_index,$_POST[$stri_index]);
     $bool_selected=true;    
     
    }
    
    //la variable de session est définie si on ne viens pas de cliquer sur un onglet
    $stri_value=$this->readSession($stri_index);
  
    if(($stri_value!==false)||($bool_selected))
      {$this->setSelectedOnglet($stri_value);}
    else
      { $this->setSelectedOnglet($this->arra_onglet[0]->getName());}

      
    foreach($this->arra_onglet as $i=>$obj_onglet)
    {
      $obj_onglet->setOnclick("document.".$stri_form.".clicked.value='clicked';document.".$stri_form.".".$stri_index.".value=this.name");
      if($obj_onglet->getId()=="")
      {$obj_onglet->setId("onglet".$i);}
      $tr->addTd($obj_onglet->display());
    }
    $stri_extra=($this->stri_extra_html!="")?$this->stri_extra_html:"";

    $tr->addTd($stri_extra.$form->getEndBalise());
    $html_table->setCellspacing(0);
    $html_table->setCellpadding(0);
    $html_table->setClass("pn-normal");
    $html_table->setBorder(0);
    $html_table->insertTr($tr);
    
    $stri_javascript="";
    //si on est en mode multiple onglets, du javascript est nécessaire
    if($this->bool_multiple_actif)
    {
     $stri_javascript=$this->transmitPageId();
    }
    
    //on paramètre le retour, utilisé pour menu_dyn.class.php 
    if($type_retour == "string")
    {return $html_table->htmlValue().$stri_javascript;}
    else
    {
      $arra_return["obj_table"] = $html_table;
      $arra_return["js"] = $stri_javascript;
      return $arra_return;
    }
  }
 
  /*************************************
  *Permet d'actualiser l'onglet actif du menu
  *sans passer par la méthode htmlValue
  *
  *  paramètre : aucun
  *  retour    : aucun
  *  ************************************/            
 public function actualiseActifOnglet()
 {
    //récupération de l'id du menu
    $stri_id=$this->getId();
    $stri_index=$stri_id."selected_onglet";
   //si on a passé en post un onglet, cela signifie que l'on viens de cliquer
    //sur un onglet ou qu'on l'on a utiliser la méthode forceSelectedOnglet
    if(isset($_POST[$stri_index]))
    {
     $this->writeSession($stri_index,$_POST[$stri_index]);
    }
    
    //la variable de session est définie si on ne viens pas de cliquer sur un onglet
    $stri_value=$this->readSession($stri_index);
    if($stri_value!==false)
      {$this->setSelectedOnglet($stri_value);}
    else
      {$this->setSelectedOnglet($this->arra_onglet[0]->getName());}
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
 

 /*******************************
  *Permet de créer un identifiant par page
  *param : aucun
  *retour : int : l'identifiant de la page
  ********************************/       
 private function createPageId()
 {
  //l'id de la page est l'heure à laquelle est elle générée
  //$int_id=date("His");
  
  //- id basé sur le temps avec les millisecondes
  $now = DateTime::createFromFormat('U.u', microtime(true));
  $int_id=$now->format("Hisu");
  
  return $int_id;
 }
 
 /**************************
  *Permet de récupérer l'identifiant de la 
  *page à utiliser
  *param : aucun
  *retour : identifiant de la page
  ***************************/          
 public function getPageId()
 {
  //si on est pas en mode multiple onglets, le pageId est nul
  if(!$this->bool_multiple_actif)
  {return '';}

  if(isset($_POST['pageId']))
  {return $_POST['pageId'] ;}
  
  if(menu::$int_page_id!="")
  {return menu::$int_page_id;}
  //cas de création d'un nouvel id, 
    $int_id= $this->createPageId();
    menu::$int_page_id=$int_id;
    return $int_id;
   
  }
 
 public function transmitPageId()
 {
    $int_page_id=$this->getPageId();
    //output_reset_rewrite_vars();//effacement des réécriture d'url
    output_add_rewrite_var('pageId', $int_page_id);//ajout de l'identifiant de page aux url
 }
 
  /**************************
  *Permet d'écrire une variable dans un environnement permanent
  *param : string : le nom de la variable
  *        string : la valeur de la variable  
  *retour : bool : true  => la variable a bien été écrite
  *                false => problème lors de la sauvegarde de la variable  
  */  
 public function writeSession($stri_name,$stri_value)
 {  
  //si on a déjà écrit dans la variable, on ne l'écrase pas
  if(isset($this->arra_active[$stri_name]))
  {return true;} 
   
     
  //écriture dans la variable
  $this->arra_active[$stri_name]=$stri_value;
  //on ne garde que les 45 dernier carractères du nom
  $stri_short_name=substr($stri_name, -45);
  
  //sauvegarde du la donnée dans la base
  $obj_param_field=new parameter_field($stri_short_name,pnuserGetVar("uid"),"Menu","Cat Menu",$stri_value,"NULL");
  $stri_res=$obj_param_field->updateField();


  return $stri_res;

 }
 
  /**************************
  *Permet de lire une variable dans un environnement permanent
  *param : string : le nom de la variable
  *retour : mixed : la valeur de la variable
  *         false : la variable n'a pas pu être trouvée  
  */  
 public function readSession($stri_name)
 {
  
  //si on à déjà fait la récupération
  if(isset($this->arra_active[$stri_name]))
  {return $this->arra_active[$stri_name];}

   //on ne garde que les 45 dernier carractères du nom
  $stri_short_name=substr($stri_name, -45); 
   
  //on a jamais fait la récupération de donnée
  $obj_param_field=new parameter_field($stri_short_name,pnuserGetVar("uid"),"Menu","Cat Menu","","");
  $stri_value=$obj_param_field->getValueDB();
  
  if($stri_value!="")
  {
   //sauvegarde en mémoire vive pour ne pas rechercher à nouveau dans la base
   $this->arra_active[$stri_name]=$stri_value;
   return $stri_value;
  }
  
  
  return false;
 }
 
  /**************************
  *Permet de purger les variables de session trop vieilles
  *param :  aucun
  *retour : aucun
  */  
 private function cleanSession()
 {
  //suppression des variable de plus de 3 jours
  $stri_sql="DELETE FROM gen_parametre 
              WHERE num_user=".pnuserGetVar("uid")." 
                  AND id_module='Menu' 
                  AND categorie='Cat Menu' 
                  AND mdate+3<SYSDATE
            ";
  $obj_query=new querry_select($stri_sql);
  $obj_query->execute();
 }
 
 function compare_onglet($obj_onglet1, $obj_onglet2) 
  {  
        $stri_methode="get".$this->stri_order_onglet;
        $stri_onglet1=$obj_onglet1->$stri_methode();
        $stri_onglet2=$obj_onglet2->$stri_methode();
        
        if ($stri_methode == $stri_onglet2) {
          return 0;
        }
        return ($stri_onglet1 > $stri_onglet2) ? +1 : -1;
  }
  



  
  //**** method for serialization **********************************************
  public function __sleep() 
  {
    $this->arra_sauv['active_class']  = $this->stri_active_class;
    $this->arra_sauv['inactive_class']  = $this->stri_inactive_class;
    $this->arra_sauv['active_src']  = $this->stri_active_src;
    $this->arra_sauv['inactive_src']  = $this->stri_inactive_src;
    $this->arra_sauv['url']  = $this->stri_url;
    for($i=0;$i<count($this->arra_onglet);$i++)
    {$arra_temp[$i]=serialize($this->arra_onglet[$i]);}
    $this->arra_sauv['arra_onglet']=$arra_temp;
    return array('arra_sauv');
  }
  
  public function __wakeup() 
  {
    $this->stri_active_class= $this->arra_sauv['active_class'];
    $this->stri_inactive_class= $this->arra_sauv['inactive_class'];
    $this->stri_active_src= $this->arra_sauv['active_src'];
    $this->stri_inactive_src= $this->arra_sauv['inactive_src'];
    $this->stri_url= $this->arra_sauv['url'];
    $arra_temp=$this->arra_sauv['arra_onglet'];
    $nbr_object=count($arra_temp);
    for($i=0;$i<$nbr_object;$i++)
    {$this->arra_onglet[$i]= unserialize($arra_temp[$i]);}
    $this->arra_sauv = array();
  }
}

?>
