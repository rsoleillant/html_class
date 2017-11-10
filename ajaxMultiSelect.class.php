<?php
/*******************************************************************************
Create Date : 27/10/2009
 ----------------------------------------------------------------------
 Class name : ajaxMultiSelect
 Version : 1.1
 Author : Rémy Soleillant
 Update : RS 22/11/2011 : ajout de gestion de selection multiple
 Description : Permet de créer des listes déroulantes multiple en ajax
               Cette classe fait automatiquement le lien entre les listes déroulantes html et celles en php
               Elle maintient la cohérence entre ces deux univers grâce à du JS DOM et de l'ajax
********************************************************************************/
//dépendance de la classe sur le fichier js : includes/modalBox.js
include_once($_SERVER['DOCUMENT_ROOT']."includes/classes/html_class/serialisable.class.php");
     
class ajaxMultiSelect extends serialisable 
{
  //**** attribute *************************************************************
  protected $arra_select=array();//Le tableau des différents select
  protected $arra_sql=array();//Le sql permettant de construire les listes
  protected $arra_init_sql=array();//Le sql d'initialisation des listes déroulantes
  protected $arra_libelle=array();//Les libellés à afficher devant les listes déroulantes
  protected $arra_function=array();//Les fonctions qu'il faut appliquer sur les libellés lors de la création des options
  protected $arra_constant_file=array();//Les fichiers de constante de langue à inclure
  protected $stri_internal_id;//L'identifiant de l'objet, pour pouvoir créer plusieurs multiselect sur la meme page
  protected static $int_nb_instance=0;//Le nombre d'instance déjà faite
  protected static $arra_instance=array();    //Pour se rappeler le nombre d'instance pour chaque id
  protected $stri_first_option_label=_OPT_MAKE_CHOICE;//Le libellé de la première option
  protected $bool_delete_temp_file=false;//Marqueur permettant de supprimer le fichier temporaire de l'objet sérialisé
  protected static $bool_init=true;//Pour savoir s'il faut initialiser le js
  protected $stri_style_td = "height:40px;";//attribut style de chaque TD du TABLE
  
  protected $i = 0;
  protected $arra_name;
  
  protected $arra_selected_option;//Tableau pour mémoriser les options à sélectionner lors d'un forçage manuelle de la sélection
  //**** constructor ***********************************************************
   
   /*************************************************************
   *
   * parametres : string : l'identifiant de l'instance
   * retour : objet de la classe rules_applicator   
   *                        
   **************************************************************/         
  function __construct($stri_id="ajaxMultiSelect")
  {   
   $int_nb_instance_id=ajaxMultiSelect::$arra_instance[$stri_id];
   $stri_id_extra=(ajaxMultiSelect::$arra_instance[$stri_id]>0)?"_clone_".$int_nb_instance_id:"";//gestion de l'id provenant de clonage
 
   $this->stri_internal_id=$stri_id.$stri_id_extra;
   
   ajaxMultiSelect::$int_nb_instance++;
   ajaxMultiSelect::$arra_instance[$stri_id]++;
  }  
 
  //**** setter ****************************************************************
  public function setFirstOptionLabel($value){$this->stri_first_option_label=$value;}
  public function setDeleteTempFile($value){$this->bool_delete_temp_file=$value;}
  public function setInternalId($value){$this->stri_internal_id=$value;}
  public function setStriStyleTd($value){$this->stri_style_td=$value;}

  //**** getter ****************************************************************
  public function getSelect($stri_name){return $this->arra_select[$stri_name];}
  public function getSelectSQL($stri_name){return $this->arra_sql[$stri_name];}
  public function getFirstOptionLabel(){return $this->stri_first_option_label;}

  public function getArraSelect(){return $this->arra_select;}
  public function getSql(){return $this->arra_sql;}
  public function getLibelle(){return $this->arra_libelle;}
  public function getFunction(){return $this->arra_function;}
  public function getConstantFile(){return $this->arra_constant_file;}
  public function getInternalId(){return $this->stri_internal_id;}
  public function getTableModel(){return $this->obj_table_model;}
  public function getDeleteTempFile(){return $this->bool_delete_temp_file;}
  public function getStriStyleTd(){return $this->stri_style_td;}

  public function getValueI(){return $this->i;}
  public function getArraName(){return $this->arra_name;}
  //**** public method *********************************************************

 /*************************************************************
 * Permet d'ajouter un fichier de constante à inclure avant de créer les options des selects.
 * Permet notamment la gestion du multilangue 
 * Parametres : string : le fichier à inclure ex : modules/Hotline/pnlang/fra/user.php
 * retour : aucun
 *                        
 **************************************************************/ 
  public function addConstantFile($stri_file_to_include)
  {$this->arra_constant_file[]=$stri_file_to_include;}
  
 /*************************************************************
 * Permet de lancer l'inclusion des fichiers de constantes
 * 
 * Parametres : aucun
 * retour : aucun
 *                        
 **************************************************************/ 
  public function includeFile()
  {
   foreach($this->arra_constant_file as $stri_file)
   {include_once($stri_file);}
  }
  
 /*************************************************************
 * Permet d'ajouter une liste déroulante au multiselect
 * 
 * Parametres : string : le nom du select 
 *              string : le sql permettant de construire les options. 
 *                       Il peut contenir des référence aux selects précédant en indiquant le nom du select entre [] Ex [groupe] fait référence à la valeur du select groupe
 *              string : le libellé qui s'affiche devant la liste déroulante
 *              string : le nom de la classe qui va servir à appliquer une méthode de modification des libellé.
 *                       Fonctionne en complément du paramètres $func
 *              string : le nom de la fonction (ou méthode) qui sera appelé sur les libellé des options. (Pour faire la correspondance avec des constantes php pour le mutilangue)     
 *              bool   : si on doit utiliser un easy select à la place d'un select  
 * retour : obj select : l'objet select créé
 * 
 * Exemple :
 * $obj_am=new ajaxMultiSelect("multiSelect1");
 * $obj_am->addSelect("groupe","select distinct groupe from societe order by groupe");
 * $obj_am->addSelect("site","select distinct site from societe where groupe='[groupe]' order by site");
 *                          
 **************************************************************/ 
  public function addSelect($stri_name,$stri_sql,$stri_libelle="",$obj="",$func="",$bool_easy_select=false)
  {  
   //construction du nouveau select
   $obj_select=($bool_easy_select)?new easy_select($stri_name):new select($stri_name);
   
   $stri_name=str_replace("[]", "", $stri_name);//suppression des crochet dans le nom (cas des select multiple)
   $this->arra_name[]=$stri_name;
   $this->arra_select[$stri_name]=$obj_select;
   $this->arra_sql[$stri_name]=$stri_sql;
   if($stri_libelle!="")
   {$this->arra_libelle[$stri_name]=$stri_libelle;}
   
   if($obj!="")
   {$this->arra_function[$stri_name]['obj']=$obj;}
   
   if($func!="")
   {$this->arra_function[$stri_name]['function']=$func;}
 
   //ajout de l'évènement on change sur le précédant select
   $arra_keys=array_keys($this->arra_select);
   $int_nb_select=count($arra_keys);
   if($int_nb_select>1)
   {
    $obj_select_precedant=$this->arra_select[$arra_keys[$int_nb_select-2]];
    $obj_select_precedant->setOnchange("nextSelect('".$obj_select_precedant->getName()."','".$this->stri_internal_id."',this)");   
   }
   
   return  $this->arra_select[$stri_name];
  }
  
 
 /*************************************************************
 * Permet de connaitre le nombre de champ qu'il à y a dans la clause select du sql
 * passé en paramètres 
 * 
 * Parametres : string : le sql à analyser
 * retour : array(0,0) : il n'y a qu'un seul champ dans la clause qui servira de valeur d'option et de libellé
 *          array(0,1) : il y a deux champ dans la clause, le premier servira de valeur à l'option, le secon de libellé              
 **************************************************************/ 
  private function analyseSql($stri_sql)
  {
   $stri_pos=stripos($stri_sql,"from");//on recherche la position du mot clef from dans le sql
   $stri_select_clause=substr($stri_sql,0,$stri_pos);//extraction de la clause select 
   $int_nb_comma= substr_count($stri_select_clause,",");
   
   if($int_nb_comma==0)
   {return array(0,0);}
   
   return array(0,1);
  }
  
 /*************************************************************
 * Permet d'ajouter les options à un select
 * Cette méthode est exclusivement utilisée pour construire les options de la 
 *toute première liste déroulante 
 * 
 * Parametres : string : le nom du select
 * retour : obj select : l'objet select avec ses options
 **************************************************************/ 
  public function constructSelect($stri_select_name)
  {
    
    $stri_sql= $this->arra_sql[$stri_select_name];//récupération du sql du select
    $obj_query1=new querry_select($stri_sql);//création d'une requête pour ajouter les options
    $arra_analyse=$this->analyseSql($stri_sql);
    $this->arra_select[$stri_select_name]->addOption("",$this->stri_first_option_label);
    
    $obj=(isset($this->arra_function[$stri_select_name]['obj']))?$this->arra_function[$stri_select_name]['obj']:"";
    $function=(isset($this->arra_function[$stri_select_name]['function']))?$this->arra_function[$stri_select_name]['function']:"";
    $this->arra_select[$stri_select_name]->makeQuerryToSelect($obj_query1,$arra_analyse[0],$arra_analyse[1],$obj,$function);//ajout des options
      
    return $this->arra_select[$stri_select_name];
  }
  
 /*************************************************************
 * Permet de construire une liste déroulante en javascript.
 * Cette méthode maintient également le synchronisme entre les objets select en PHP et en Javascript  

 * Parametres : string : nom du select
 * retour : string : le code DOM javascript à executer pour construire la liste déroulante             
 **************************************************************/ 
  public function constructJSSelect($stri_select_name)
  {
   
   $stri_raw_sql= $this->arra_sql[$stri_select_name];//récupération du sql brut
  
   $arra_match=array();
   preg_match_all ('`\[([^]]*)\]`' ,$stri_raw_sql  , $arra_match );//on recherche les références aux autres listes déroulantes
    
   $stri_sql=$stri_raw_sql;
   
   //si on doit executer un traitement particulier sur les libellés (correspondance avec constante php)
   $obj=(isset($this->arra_function[$stri_select_name]['obj']))?$this->arra_function[$stri_select_name]['obj']:"";
   $function=(isset($this->arra_function[$stri_select_name]['function']))?$this->arra_function[$stri_select_name]['function']:"";
  
   foreach($arra_match[1] as $stri_search)//pour chaque référence à une liste précédante
   {
 
    $obj_selected_option=$this->arra_select[$stri_search]->getSelectedOption();
    if(!is_object($obj_selected_option))//si il n'y a pas d'option selectionnée, cas d'erreur bloquant
    { trigger_error ("The selected option have not been found", E_USER_ERROR  );}
    
    $stri_selected_option_value=$obj_selected_option->getValue();//récupération de la valeur de l'option sélectionnée
    $stri_sql=str_replace('['.$stri_search.']', $stri_selected_option_value,$stri_sql);
   }  
 
    $arra_analyse=$this->analyseSql($stri_sql);//on recherche si les options doivent avoir le meme libellé que valeur
         
    
    $obj_query1=new querry_select($stri_sql);
    $arra_res=$obj_query1->execute();//va contenir les données pour la création des options
   
   
   
    //on doit nettoyer les select de leurs options à partir du select sur lequel on à cliqué mais peux ceux d'avant
    $arra_option=array(new option("",$this->stri_first_option_label));
    $arra_keys=array_keys($this->arra_select);
    $arra_keys_flip=array_flip($arra_keys);
    $int_start=$arra_keys_flip[$stri_select_name];
    $int_nb_select=count($this->arra_select);
    $stri_js="";
 
         /*
            $obj_tracer=new tracer(dirname(__FILE__)."/debug.txt");
  $obj_tracer->trace(var_export("select name",true));
  $obj_tracer->trace(var_export($obj_select_recup->getName(),true));    
  $obj_tracer->trace(var_export($arra_keys[$i],true));   */ 
    
    for($i=$int_start;$i<$int_nb_select;$i++)//pour chaque liste déroulante suivant celle sur laquelle l'utilisateur à cliquée
    { 
     $stri_selected_value=$this->arra_select[$arra_keys[$i]]->getSelectedOptionValue();
   
      //nettoyage en javascript
      $obj_select_recup=$this->arra_select[$arra_keys[$i]];
    
      $stri_js.="var select_temp=$('#marqueur').closest('table').find('select[name=\"".$obj_select_recup->getName()."\"]');"; //récup en jquery du select suivant
      $stri_js.="select_temp.attr('id','marqueur_2');";  //marqueur temporaire du select suivant       
      
      $stri_js.="var select_$i=document.getElementById('marqueur_2');";  //récup classique js du select suivant (pour éviter de tout recoder ce qui suit)
      $stri_js.="select_temp.attr('id','');";//suppression des marqueur temporaire
     // $stri_js.="$('#marqueur').attr('id','');";//suppression des marqueur temporaire  
      //$stri_js.="var select_$i=document.getElementsByName('".$obj_select_recup->getName()."')[0];"; //récupération du select à changer     
      $stri_js.="while(select_$i.options.length>0){select_$i.remove(0);}";//suppression des options déjà présentes
     
       //ajout de la première option
       $stri_js.="var option_$i=new Option('".$this->stri_first_option_label."', '', false, false);";//création d'une nouvelle option
     
       //$stri_js.="select_$i.add(option_$i,null);";//ajout de l'option au select, méthode d'ajout standard W3C ne fonctionnant pas sous ie ...
       $stri_js.="select_$i.options[select_$i.options.length]=option_$i;";//ajout de l'option au select
       
      //nettoyage en php avec ajout de la première option
      $this->arra_select[$arra_keys[$i]]->setOption($arra_option);
      //$this->arra_select[$arra_keys[$i]]->selectOption($stri_selected_value);
      $this->selectOption($this->arra_select[$arra_keys[$i]],$stri_selected_value);
    }
    
     $obj_select=$this->arra_select[$stri_select_name];
      //$stri_js.="alert('$stri_select_name');";
      $stri_js.="var select_temp=$('#marqueur').closest('table').find('select[name=\"".$obj_select->getName()."\"]');"; //récup en jquery du select suivant
      $stri_js.="select_temp.attr('id','marqueur_3');";  //marqueur temporaire du select suivant       
      $stri_js.="var select=document.getElementById('marqueur_3');"; //récupération du select à changer
      $stri_js.="select_temp.attr('id','');";//suppression des marqueur temporaire
      //$stri_js.="$('#marqueur').attr('id','');";//suppression des marqueur temporaire     
     //$stri_js.="var select=document.getElementsByName('".$obj_select->getName()."')[0];"; //récupération du select à changer     

   
    $stri_appli_function=($this->arra_function[$stri_select_name]['obj'])?$this->arra_function[$stri_select_name]['obj']."->":""; 
    $stri_appli_function.=(isset($this->arra_function[$stri_select_name]['function']))?$this->arra_function[$stri_select_name]['function']:"";
  
    foreach($arra_res as $key=>$arra_one_res)//pour chaque option à ajouter
    {
     $stri_option_label=($stri_appli_function!="")?$stri_appli_function($arra_one_res[$arra_analyse[1]]):$arra_one_res[$arra_analyse[1]];//détermination du libellé
     
     //ajout de l'option en php
     $obj_select->addOption($arra_one_res[$arra_analyse[0]],$stri_option_label);
     
     //ajout de l'option en javascript
     $stri_js.="var option_$key=new Option('".str_replace("'","\'",$stri_option_label)."', '".str_replace("'","\'",$arra_one_res[$arra_analyse[0]])."', false, false);";//création d'une nouvelle option
     //$stri_js.="select.add(option_$key,null);";//ajout de l'option au select, méthode d'ajout standard W3C ne fonctionnant pas sous ie ...
     $stri_js.="select.options[select.options.length]=option_$key;";//ajout de l'option au select
    }
     //Modif CC 18-07-2013 : Gestion des données du select de recherche
      $stri_js.="var select_alignement=$(select).closest('.main_div').find('select[name=\"alignement\"]');";
      $stri_js.="select_alignement.html('');//vidage des options\n";
      $stri_js.="select_alignement.html($(select).html());//Remplissage des options";

      
    $int_nb_option=count($this->arra_select[$stri_select_name]->getOption());
    $int_selected_index=($int_nb_option==2)?1:0;
    $stri_js.="select.selectedIndex=$int_selected_index;";//sélection de la première option
    
   
    return $stri_js;
  }
  
 /*************************************************************
 * Permet de réinitialiser les listes déroulantes
 *
 * Parametres : aucun
 * retour : aucun
 **************************************************************/ 
  public  function raz()
  {
   //$this->bool_delete_temp_file=true;//on marque l'objet pour qu'il n'utilise plus le fichier temporaire
   //ajaxMultiSelect::purgeTemp($this->stri_internal_id);//suppression de l'objet sérialisé
   foreach($this->arra_select as $obj_select)
   {
    $obj_select->selectOption("");//sélection de la première option pour chaque select
   }

    $this->saveInTemp($this->stri_internal_id);//récupércution de la mise à jour sur la sauvegarde de l'objet
  }
  
 /*************************************************************
 * Permet de représenter en html le multiselect
 * Cette méthode est normalement appellée qu'une seule fois et sert également d'initialisation
 *
 * Parametres : int : le nombre de selecet par ligne
 *              string : l'endroit où mettre les libellés
 *                       left , right, top ou bottom  
 * retour : string : le code html correspondant      
 **************************************************************/ 
  public function htmlValue($int_nb_select_par_ligne=3,$stri_where_libelle="left")
  {
    $_SESSION["langue"]=$_SESSION['PNSVlang'];//sauvegarde du la langue actuel du l'utilisateur car le cms à la réinitialisation va mettre la langue par défaut
   
    $this->includeFile();
    $obj_javascripter=new javascripter();
   
    $obj_javascripter->addFile('includes/modalBox.js');//fichier js contenant les fonctionnalité d'envoi de formulaire en ajax
   
    $stri_form_action=str_replace($_SERVER['DOCUMENT_ROOT'],"", __FILE__) ;//déduction du chemin relatif de la classe
      
   //lancement de la création des options de la liste déroulante
   $obj_javascripter->addFunction("
   function nextSelect(select_name,id_multiselect,obj_select)
   {
     //gestion de l'indentifiant pour le clonage
     var arra_select=document.getElementsByName(obj_select.name);    
    if(id_multiselect.indexOf('_clone_', 0)==-1)//si l'identifiant n'est pas déjà celui d'un clone
    { 
     for(var i=0;i<arra_select.length;i++) //recherche de l'indide du clone
     {
      if((i>0)&&(arra_select[i]==obj_select))//si on a trouvé le select et si ce n'est pas le premier
      {id_multiselect=id_multiselect+'_clone_'+i;
       //alert('creation d un clone '+i);
      }//ajout d'un indicateur de clonage sur l'élément
     }
    } 
   
     
     //création de formulaire pour envoi des données
     var form=document.createElement('form');
     var save_id= obj_select.id;
     obj_select.id='marqueur';
   
     var  select=obj_select.cloneNode(true);
         
          select.value=obj_select.value;
        
     var  hidden=document.createElement('input');
          hidden.type='hidden';
          hidden.value=id_multiselect;
          hidden.name='internal_id'; 
    
     
     form.method='post';
     form.action='".$stri_form_action."';
     form.appendChild(select);
     form.appendChild(hidden);
  
     var JSSelect=sendAjax(form);
       
     eval(JSSelect);
     
     obj_select.id=save_id;//restauration de l'id d'origine
   }
   ");  
    $obj_javascripter->addFunction("
   function razMultiselect(id_multiselect)
   {
     var form=document.createElement('form');
     var  hidden=document.createElement('input');
          hidden.type='hidden';
          hidden.value=id_multiselect;
          hidden.name='internal_id';
     var  hidden2=document.createElement('input');
          hidden2.type='hidden';
          hidden2.value='purge';
          hidden2.name='purge';  
     
     form.method='post';
     form.action='".$stri_form_action."';
     form.appendChild(hidden2);
     form.appendChild(hidden);
  
     var JSSelect=sendAjax(form);

   }
   ");
 
   $obj_existant=ajaxMultiSelect::loadFromTemp($this->stri_internal_id);//chargement de l'objet multiselect
   //vérification que l'objet sérialisé est correct
   $bool_class=(get_class($obj_existant))=="ajaxMultiSelect";//vérification que l'objet existant soit un multiselect
   $bool_delete=($bool_class)?$obj_existant->getDeleteTempFile():false;//vérification que l'objet ne soit pas marqué à supprimé
   $arra_select=($bool_class)?$obj_existant->getArraSelect():false;
       
   $bool_exist_select=count($arra_select)>0;//vérification qu'il y ai des select dans le multiselect
        
    
   $bool_create_select=true;//permet de savoir s'il faut créer les select ou non
   if($bool_class && !$bool_delete && $bool_exist_select)//pour réutilisé l'objet sérialisé, il faut que tout les vérification soit ok
   {     
    $this->arra_select=$obj_existant->getArraSelect();
    $this->arra_sql=$obj_existant->getSql();
    $this->arra_libelle=$obj_existant->getLibelle();
    $this->arra_function=$obj_existant->getFunction();
    $this->arra_constant_file=$obj_existant->getConstantFile();
    $this->stri_internal_id=$obj_existant->getInternalId();
    $bool_create_select=false;//on reprend un objet correct existant, pas besoin de recréer les select
    
    //vérification si l'objet sérialisé doit être mis à jour
    //récupération à partir de la base du nombre d'option qui doivent être dans la première liste
    $arra_key=array_keys($this->arra_sql);
    $stri_sql=$this->arra_sql[$arra_key[0]];
    $stri_verif_sql="SELECT Count(*) from (".$stri_sql.")";
    $obj_query=new querry_select($stri_verif_sql);
    $arra_res=$obj_query->execute();
    $int_nb_option_bdd=$arra_res[0][0];
    //récupération du nombre d'option du premier select
    $obj_premier_select=$this->arra_select[$arra_key[0]];
    
    $int_nb_option_slz=$obj_premier_select->getNumberOption()-1;//calul du nombre d'option dans le select (-1 pour la première option)
     if($int_nb_option_bdd!=$int_nb_option_slz)//si les deux nombre d'option ne sont pas les mêmes, une mise à jour est à faire
     {
      serialisable::purgeTemp($this->stri_internal_id);//suppession du fichier temporaire
      $bool_create_select=true;//on indique qu'il faut recréer les select

      foreach($this->arra_select as $obj_select)
      {$obj_select->setOption(array());}//on vide les options des select pour les réinitialiser  

     }
   }
   
   if($bool_create_select)//s'il faut créer les select, cas de première création ou de réinitialisation
   {     
  
    
    $arra_key=array_keys($this->arra_select);
    $this->constructSelect($arra_key[0]);//construction de la première liste déroulante
       
    $int_nb_option=count($this->arra_select);
   
    for($i=1;$i<$int_nb_option;$i++)//initialisation des autres listes déroulantes avec seulement la première option faite votre choix
    {
     $this->arra_select[$arra_key[$i]]->addOption("",$this->stri_first_option_label);
    }
     //traitement particulier du dernier select
     $obj_last_select=$this->arra_select[$arra_key[$i-1]];
     $obj_last_select->setOnchange("nextSelect('".$obj_last_select->getName()."','".$this->stri_internal_id."',this)");
     
     
    //- utilisation des sql d'initialisation
    $this->initSelectWithInitSql(); 
   }
    
    $arra_post=$_POST;//sauvegarde de ce qui se trouve en post
    //cette boucle est utilisée si on à déclenché une sélection manuelle grâce à la méthode selectOptionForSelect
    //var_dump($this->arra_selected_option);
    foreach($this->arra_selected_option as $stri_select_name=>$stri_value)
    {   
     unset($_POST);
     $_POST[$stri_select_name]=$stri_value;
     $this->ajaxHtmlValue();
    }
   $_POST=$arra_post;//restauration du post d'origine
     
    //on met les listes dans un tableau html
    $obj_table=new table();
    $i=1;
    $obj_tr=($int_nb_select_par_ligne!=1)?$obj_table->addTr():null;
    foreach($this->arra_select as $key=>$obj_select)
    {     
      $int_modulo=($int_nb_select_par_ligne==1)?1:$int_nb_select_par_ligne+1;//pour que le modulo fonctionne avec toutes les valeurs possibles de $int_nb_select_par_ligne
      $stri_libelle=$this->arra_libelle[$key];
      $obj_tr=($i%($int_modulo)==0)?$obj_table->addTr():$obj_tr;//on créer un nouveau tr tout les $int_nb_select_par_ligne éléments
      //$obj_tr->setClass($i);      
        if(($stri_libelle!="")&&($stri_where_libelle=="left"))//placement du libellé à gauche du select
        {$obj_tr->addTd($stri_libelle);}
        
        if(($stri_libelle!="")&&($stri_where_libelle=="top"))//placement du libellé à en haut du select
        {
         $obj_tr->addTd($stri_libelle);
        
         $obj_tr=$obj_table->addTr();
        }        
         $td =$obj_tr->addTd($obj_select->htmlValue());
         $td->setClass($i); 
         $td->setWidth("100%");
		/*$obj_td=$obj_tr->addTd($obj_select->htmlValue());
        //$obj_td->setWidth("100%");
        $obj_td->setValign("top");
        $obj_td->setStyle($this->stri_style_td);*/
        
        if(($stri_libelle!="")&&($stri_where_libelle=="bottom"))//placement du libellé à en bas du select
        {
         $obj_tr=$obj_table->addTr();
         $obj_tr->addTd($stri_libelle);
        }
        
        if(($stri_libelle!="")&&($stri_where_libelle=="right"))//placement du libellé à droite du select
        {$obj_tr->addTd($stri_libelle);}
        
      $i++;
    }
   
    $obj_table->setWidth("100%");
    //$obj_table->setRules("none");
    //$obj_table->setCellspacing(0);
    //$obj_table->setCellpadding(0);
    $obj_table->noWrapForAllTd(true);
    
    $this->saveInTemp($this->stri_internal_id);//A ce stade l'objet multiselect est entièrement construit, on le sauvegarde
     
     //$stri_js=(ajaxMultiSelect::$int_nb_instance==1)?$obj_javascripter->javascriptValue():"";//on pose se js une fois seulement pour toute les instances pour le pas définir plusieurs fois la même fonction js
     $stri_js=(ajaxMultiSelect::$bool_init)?$obj_javascripter->javascriptValue():"";//on pose se js une fois seulement pour toute les instances pour le pas définir plusieurs fois la même fonction js
     ajaxMultiSelect::$bool_init=falses;
   

    return $stri_js.$obj_table->htmlValue();//retour du javascript et de la représentation html
  }
  
   /*************************************************************
 * Permet de sélectionné une option ou une liste d'option dans un select
 *
 * Parametres : obj select : le select sur lequel faire la sélection
 *              mixed : string : la valeur de l'option à sélectionner
 *                      array : le tableau des valeurs à sélectionner  
 * retour : string : le code javascript DOM permettant la construction d'une ou plusieurs listes déroulante     
 **************************************************************/ 
  public function selectOption(select $obj_select,$mixed_option)
  {
   /*     Modif le 24/11/2011 par LP pour choix d'une option par défault dans un multiselect (fait pour : Incidents > Recherche)  :
   
    if(is_string($mixed_option) )
    {  var_dump($obj_select->getName());
     return $obj_select->selectOption($mixed_option);
    }
          */
    if(is_array($mixed_option))
    { 
     foreach($mixed_option as $stri_value)
     {
       $obj_select->selectOption($stri_value);          
     }
     //Modif: dans tous les autres cas on sélectionne l'option (pas seulement pour is_string puisque la valeur peut etre un entier par exemple)    
    }else {
        $obj_select->selectOption($mixed_option);
    }
  }

     
 /*************************************************************
 * Permet de lancer la construction des listes déroulantes en ajax
 * Cette méthode est appellée chaque fois que la valeur d'une liste déroulante change
 *
 * Parametres : bool : permet d'indiquer s'il s'agit d'un appel récursif de la méthode ou de son premier appel. 
 *                     Ce paramètre est interne à la méthode et ne devrait pas être passé lors d'un appel externe. 
 * retour : string : le code javascript DOM permettant la construction d'une ou plusieurs listes déroulante     
 **************************************************************/ 
  public function ajaxHtmlValue($bool_recursive=false)
  {
   if(!$bool_recursive)//inclusion des fichiers de constante php uniquement lors du premier appel
   {$this->includeFile();}
   
   $arra_key_post=array_keys($_POST);//création d'un tableau des clefs de la variable $_POST
   $stri_post_key=$arra_key_post[0];//récupération dynamique du nom de la variable post transmise
 
   
   //le but de cette suite d'instruction est de récupérer le nom de la prochaine liste déroulante à remplir
   $arra_key_select=array_keys($this->arra_select);//récupération des clefs des select  
   $arra_key_select_flip=array_flip($arra_key_select);//inversion entre les clefs et les valeurs
   $int_indice=$arra_key_select_flip[$stri_post_key];//récupération de la position dans le tableau de clefs du select sur lequel on vient de cliquer

   $int_next_select=$int_indice+1;
   $int_nb_select=count($this->arra_select);
       
   //$stri_key_recup=(is_array($_POST[$stri_post_key]))?$stri_post_key."[]":"";    
   $obj_select_clicked=$this->arra_select[$stri_post_key];//récupération du select sur lequel on vient de cliquer
   $obj_last_select=$this->arra_select[$arra_key_select[$int_nb_select-1]];//récupération du dernier select
        
         /*$obj_tracer=new tracer(dirname(__FILE__)."/debug.txt");
        $obj_tracer->trace(var_export($obj_last_select->getName(),true));       
        $obj_tracer->trace(var_export("stri_post_key \n",true));
          $obj_tracer->trace(var_export($stri_post_key ,true));             
        
         */
   $stri_last_select_name=str_replace("[]", "", $obj_last_select->getName());
   if($stri_last_select_name==$stri_post_key)//si on est en train de traiter la dernière liste déroulante
   { 
   // $obj_last_select->selectOption($_POST[$stri_post_key]);// on fait seulement une sélection sur l'objet php de l'option sur laquelle l'utilisateur à cliqué
    $this->selectOption($obj_last_select,$_POST[$stri_post_key]);// on fait seulement une sélection sur l'objet php de l'option sur laquelle l'utilisateur à cliqué
   }
   else //traitement subit par tous les select sauf le dernier
   {    
    //$obj_select_clicked->selectOption($_POST[$stri_post_key]);//sélection de la nouvelle option 
    $this->selectOption($obj_select_clicked,$_POST[$stri_post_key]);//sélection de la nouvelle option 

    $stri_next_select_name=$arra_key_select[$int_next_select];//récupération du nom du prochain select à construire
  
    $stri_js=$this->constructJSSelect($stri_next_select_name);//construction en JS DOM du select
     
     $int_nb_option=count($this->arra_select[$stri_next_select_name]->getOption());//on compte le nombre d'option contenu dans le select
     $stri_selected_value=$this->arra_select[$stri_next_select_name]->getSelectedOptionValue();
     //echo "prochaine valeur $stri_next_select_name/$stri_selected_value";
     if(($int_nb_option==2)&&($int_next_select<$int_nb_select-1))//s'il n'y a qu'une seule option extraite de la base (faites votre choix se trouve déjà dans les options) et si le select traité actuellement n'est pas le dernier
     {
       unset($_POST);//on supprime le $_POST
       $_POST[$stri_next_select_name]=$this->arra_select[$stri_next_select_name]->getIemeOption(1)->getValue();//on recréer le post comme si on venait de cliquer sur la liste déroulante
       $stri_js.=$this->ajaxHtmlValue(true); //lancement récursif de la construction du prochain select
     }
   } 
   
   if(!$bool_recursive)//on ne sauvegarde l'objet que dans le premier appel de la fonction et pas dans les appels récursif (gènère un bug et est inutile)
   {
     $this->saveInTemp($this->stri_internal_id);
   }

   return $stri_js;
 
  }
  
  /*************************************************************
 * Permet de forcer la sélection d'une valeur pour un select donné
 * Cela prend le dessus sur le comportement normal de l'objet 
 *
 * Parametres : string : le nom du select où trouver l'option
 *              string : la valeur de l'option 
 * retour : aucun
 **************************************************************/ 
  public  function selectOptionForSelect($stri_select_name,$stri_option_value)
  {   
 
     $this->arra_selected_option[$stri_select_name]=$stri_option_value;
     /*
     $this->setDeleteTempFile(true);//le forçage de sélection rend la sérialisation inutile (reconstruction complète obligatoire de l'objet)
     $obj_select_groupe=$this->getSelect($stri_select_name);//on récupère le select concerné
     $obj_select_groupe->selectOption($stri_option_value);//on sélectionne la valeur
   */
  }
  
 /*************************************************************
 * Permet d'association un sql de départ à une liste déroulante
 *
 * Parametres : string : le nom du select où trouver l'option
 *              string : le sql à utiliser
 * retour : aucun
 **************************************************************/ 
  public  function setInitSqlForSelect($stri_select_name,$stri_sql)
  {   
    $this->arra_init_sql[$stri_select_name]=$stri_sql;
  }
  
  /**
   * Permet d'initialisation les listes déroulantes qui possède un sql dédié à l'initialisation
   **/
   public function initSelectWithInitSql()
   {
      foreach( $this->arra_init_sql as $stri_select=>$stri_init_sql)
      {
        //- récupération du sql
        $obj_select=$this->arra_select[$stri_select];
        
        //- ajout des options
        $obj_select->makeSqlToSelect($stri_init_sql);
      }
   }     
  
   /*************************************************************
 permet de chercher tout les clones d'un objet et de purger tout les clones de cet objet
 *
 * Parametres : nom de l'objet 
 * retour : aucun
 **************************************************************/ 
  public  function purgeAllClone($stri_extra_id='')
  {
   $stri_file_name=serialisable::constructFileName($stri_extra_id);
   $stri_user_temp_path=serialisable::constructUserTempPath();
   $stri_file=$stri_user_temp_path."/".$stri_file_name;
   $lenght =  strlen($stri_file)-4;//calcule la taille de la chaine -4 (qui correspond a l'extension .slz)
    
    $obj_dir_reader=new file_reader_writer($stri_user_temp_path);
    $arra_file=$obj_dir_reader->readDirectory();
      
     foreach($arra_file as $stri_file2)//parcours le repertoire
     {
     $va=substr_compare($stri_file,$stri_file2,'0',$lenght);
     
     if($va==0)
     { unlink($stri_file2);
        //echo $stri_file." ".$stri_file2." ".$va."<br />";
     }
     }
  
   return false; 
  } 
  
  
  
    public function htmlValueTr($int_nb_select_par_ligne = 3, $stri_where_libelle = "left") {
        $_SESSION["langue"] = $_SESSION['PNSVlang']; //sauvegarde du la langue actuel du l'utilisateur car le cms à la réinitialisation va mettre la langue par défaut

        $this->includeFile();
        $obj_javascripter = new javascripter();

        $obj_javascripter->addFile('includes/modalBox.js'); //fichier js contenant les fonctionnalité d'envoi de formulaire en ajax

        $stri_form_action = str_replace($_SERVER['DOCUMENT_ROOT'], "", __FILE__); //déduction du chemin relatif de la classe
        //lancement de la création des options de la liste déroulante
        $obj_javascripter->addFunction("
   function nextSelect(select_name,id_multiselect,obj_select)
   {
     //gestion de l'indentifiant pour le clonage
     var arra_select=document.getElementsByName(obj_select.name);    
    if(id_multiselect.indexOf('_clone_', 0)==-1)//si l'identifiant n'est pas déjà celui d'un clone
    { 
     for(var i=0;i<arra_select.length;i++) //recherche de l'indide du clone
     {
      if((i>0)&&(arra_select[i]==obj_select))//si on a trouvé le select et si ce n'est pas le premier
      {id_multiselect=id_multiselect+'_clone_'+i;
       //alert('creation d un clone '+i);
      }//ajout d'un indicateur de clonage sur l'élément
     }
    } 
   
     
     //création de formulaire pour envoi des données
     var form=document.createElement('form');
     var save_id= obj_select.id;
     obj_select.id='marqueur';
   
     var  select=obj_select.cloneNode(true);
         
          select.value=obj_select.value;
        
     var  hidden=document.createElement('input');
          hidden.type='hidden';
          hidden.value=id_multiselect;
          hidden.name='internal_id'; 
    
     
     form.method='post';
     form.action='" . $stri_form_action . "';
     form.appendChild(select);
     form.appendChild(hidden);
  
     var JSSelect=sendAjax(form);
       
     eval(JSSelect);
     
     obj_select.id=save_id;//restauration de l'id d'origine
   }
   ");
        $obj_javascripter->addFunction("
   function razMultiselect(id_multiselect)
   {
     var form=document.createElement('form');
     var  hidden=document.createElement('input');
          hidden.type='hidden';
          hidden.value=id_multiselect;
          hidden.name='internal_id';
     var  hidden2=document.createElement('input');
          hidden2.type='hidden';
          hidden2.value='purge';
          hidden2.name='purge';  
     
     form.method='post';
     form.action='" . $stri_form_action . "';
     form.appendChild(hidden2);
     form.appendChild(hidden);
  
     var JSSelect=sendAjax(form);

   }
   ");

        $obj_existant = ajaxMultiSelect::loadFromTemp($this->stri_internal_id); //chargement de l'objet multiselect
        //vérification que l'objet sérialisé est correct
        $bool_class = (get_class($obj_existant)) == "ajaxMultiSelect"; //vérification que l'objet existant soit un multiselect
        $bool_delete = ($bool_class) ? $obj_existant->getDeleteTempFile() : false; //vérification que l'objet ne soit pas marqué à supprimé
        $arra_select = ($bool_class) ? $obj_existant->getArraSelect() : false;

        $bool_exist_select = count($arra_select) > 0; //vérification qu'il y ai des select dans le multiselect


        $bool_create_select = true; //permet de savoir s'il faut créer les select ou non
        if ($bool_class && !$bool_delete && $bool_exist_select) {//pour réutilisé l'objet sérialisé, il faut que tout les vérification soit ok
            $this->arra_select = $obj_existant->getArraSelect();
            $this->arra_sql = $obj_existant->getSql();
            $this->arra_libelle = $obj_existant->getLibelle();
            $this->arra_function = $obj_existant->getFunction();
            $this->arra_constant_file = $obj_existant->getConstantFile();
            $this->stri_internal_id = $obj_existant->getInternalId();
            $bool_create_select = false; //on reprend un objet correct existant, pas besoin de recréer les select
            //vérification si l'objet sérialisé doit être mis à jour
            //récupération à partir de la base du nombre d'option qui doivent être dans la première liste
            $arra_key = array_keys($this->arra_sql);
            $stri_sql = $this->arra_sql[$arra_key[0]];
            $stri_verif_sql = "SELECT Count(*) from (" . $stri_sql . ")";
            $obj_query = new querry_select($stri_verif_sql);
            $arra_res = $obj_query->execute();
            $int_nb_option_bdd = $arra_res[0][0];
            //récupération du nombre d'option du premier select
            $obj_premier_select = $this->arra_select[$arra_key[0]];

            $int_nb_option_slz = $obj_premier_select->getNumberOption() - 1; //calul du nombre d'option dans le select (-1 pour la première option)
            if ($int_nb_option_bdd != $int_nb_option_slz) {//si les deux nombre d'option ne sont pas les mêmes, une mise à jour est à faire
                serialisable::purgeTemp($this->stri_internal_id); //suppession du fichier temporaire
                $bool_create_select = true; //on indique qu'il faut recréer les select

                foreach ($this->arra_select as $obj_select) {
                    $obj_select->setOption(array());
                }//on vide les options des select pour les réinitialiser  
            }
        }

        if ($bool_create_select) {//s'il faut créer les select, cas de première création ou de réinitialisation
            $arra_key = array_keys($this->arra_select);
            $this->constructSelect($arra_key[0]); //construction de la première liste déroulante

            $int_nb_option = count($this->arra_select);

            for ($i = 1; $i < $int_nb_option; $i++) {//initialisation des autres listes déroulantes avec seulement la première option faite votre choix
                $this->arra_select[$arra_key[$i]]->addOption("", $this->stri_first_option_label);
            }
            //traitement particulier du dernier select
            $obj_last_select = $this->arra_select[$arra_key[$i - 1]];
            $obj_last_select->setOnchange("nextSelect('" . $obj_last_select->getName() . "','" . $this->stri_internal_id . "',this)");
        }

        $arra_post = $_POST; //sauvegarde de ce qui se trouve en post
        //cette boucle est utilisée si on à déclenché une sélection manuelle grâce à la méthode selectOptionForSelect
        //var_dump($this->arra_selected_option);
        foreach ($this->arra_selected_option as $stri_select_name => $stri_value) {
            unset($_POST);
            $_POST[$stri_select_name] = $stri_value;
            $this->ajaxHtmlValue();
        }
        $_POST = $arra_post; //restauration du post d'origine
        //on met les listes dans un tableau html
        $obj_tr = new tr();
        $i = 1;
        foreach ($this->arra_select as $key => $obj_select) {
            $stri_libelle = $this->arra_libelle[$key];
            $td = $obj_tr->addTd($stri_libelle);
            $td = $obj_tr->addTd($obj_select->htmlValue());
            $td->setClass($i);
            $td->setWidth("100%");

            $i++;
        }
        $this->saveInTemp($this->stri_internal_id); //A ce stade l'objet multiselect est entièrement construit, on le sauvegarde
        //$stri_js=(ajaxMultiSelect::$int_nb_instance==1)?$obj_javascripter->javascriptValue():"";//on pose se js une fois seulement pour toute les instances pour le pas définir plusieurs fois la même fonction js
        $stri_js = (ajaxMultiSelect::$bool_init) ? $obj_javascripter->javascriptValue() : ""; //on pose se js une fois seulement pour toute les instances pour le pas définir plusieurs fois la même fonction js
        ajaxMultiSelect::$bool_init = falses;


        return array($stri_js,$obj_tr); //retour du javascript et de la représentation html
    }
}



 /*************************************************************
 *     //\\
 *   // ! \\  CODE EXTRAT CLASSE !!!
 *   -------
 *  
 * Permet de rendre la classe "indépendante". 
 ***************************************************************/ 


if(isset($_POST['internal_id']))
{   
 //on ne passe ici que lors d'un envoi en ajax
  set_include_path($_SERVER['DOCUMENT_ROOT']);//modification du path d'inclusion pour retrouver les différents éléments
  include_once ("includes/pnAPI.php");//on est en dehors du CMS, on doit faire l'initialisation minimum
  pnInit();
  include_once("config/user_bd_choice.tintf.php");//pour prendre en compte le typage de données
  include_once("includes/html.pkg.php"); 
  
  //gestion du clonage de l'objet ajaxMultiselect
  $arra_part=explode("_clone_", $_POST['internal_id']);//récup des info sur le clone éventuel
  if(isset($arra_part[1]))//si on a affaire à un clone
  {
    
    ajaxMultiSelect::copyFromTemp($arra_part[0],$_POST['internal_id']);//copie du fichier temp=  
  }
  
  /*
  $obj_tracer=new tracer(dirname(__FILE__)."/debug.txt");
  $obj_tracer->trace(var_export($_POST,true));*/ 
  
  foreach($_POST as $key=>$stri_value)//pour gérer correctent les accents
  {
   if(is_array($stri_value)) //traitement des select multiple 
   {
     foreach($stri_value as $stri_key2=>$stri_data)
     {
       $_POST[$key][$stri_key2]=utf8_decode($stri_data);
     }
   }
   else
   {$_POST[$key]=utf8_decode($stri_value);} //traitement des select simple
  
  }
  
  //RS 10/11/2010 : correction du bug de changement de langue
  $_SESSION['PNSVlang']=$_SESSION["langue"];//restauration de la bonne langue sinon le cms remet celle par défaut
 
 
  $obj_ajaxMultiSelect=ajaxMultiSelect::loadFromTemp($_POST['internal_id']);//chargement de l'objet sérialisé
  if (is_object($obj_ajaxMultiSelect))
  {
      $obj_ajaxMultiSelect->setInternalId($_POST['internal_id']);
 
        /* 
        $obj_tracer=new tracer(dirname(__FILE__)."/debug.txt");
        $obj_tracer->trace(var_export($_POST,true));  */


        if(isset($_POST['purge']))//cas de demande de purge
        {
         $obj_ajaxMultiSelect->raz();
        }
        else
        {//cas de chargement des données
          echo $obj_ajaxMultiSelect->ajaxHtmlValue(); //"affichage" du code JS DOM d'actualisation des listes déroulantes
          ob_flush();
          flush();                            
        }
  }
}


 
?>
