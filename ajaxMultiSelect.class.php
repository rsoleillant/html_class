<?php
/*******************************************************************************
Create Date : 27/10/2009
 ----------------------------------------------------------------------
 Class name : ajaxMultiSelect
 Version : 1.1
 Author : R�my Soleillant
 Update : RS 22/11/2011 : ajout de gestion de selection multiple
 Description : Permet de cr�er des listes d�roulantes multiple en ajax
               Cette classe fait automatiquement le lien entre les listes d�roulantes html et celles en php
               Elle maintient la coh�rence entre ces deux univers gr�ce � du JS DOM et de l'ajax
********************************************************************************/
//d�pendance de la classe sur le fichier js : includes/modalBox.js
include_once($_SERVER['DOCUMENT_ROOT']."includes/classes/html_class/serialisable.class.php");
     
class ajaxMultiSelect extends serialisable 
{
  //**** attribute *************************************************************
  protected $arra_select=array();//Le tableau des diff�rents select
  protected $arra_sql=array();//Le sql permettant de construire les listes
  protected $arra_init_sql=array();//Le sql d'initialisation des listes d�roulantes
  protected $arra_libelle=array();//Les libell�s � afficher devant les listes d�roulantes
  protected $arra_function=array();//Les fonctions qu'il faut appliquer sur les libell�s lors de la cr�ation des options
  protected $arra_constant_file=array();//Les fichiers de constante de langue � inclure
  protected $stri_internal_id;//L'identifiant de l'objet, pour pouvoir cr�er plusieurs multiselect sur la meme page
  protected static $int_nb_instance=0;//Le nombre d'instance d�j� faite
  protected static $arra_instance=array();    //Pour se rappeler le nombre d'instance pour chaque id
  protected $stri_first_option_label=_OPT_MAKE_CHOICE;//Le libell� de la premi�re option
  protected $bool_delete_temp_file=false;//Marqueur permettant de supprimer le fichier temporaire de l'objet s�rialis�
  protected static $bool_init=true;//Pour savoir s'il faut initialiser le js
  protected $stri_style_td = "height:40px;";//attribut style de chaque TD du TABLE
  
  protected $i = 0;
  protected $arra_name;
  
  protected $arra_selected_option;//Tableau pour m�moriser les options � s�lectionner lors d'un for�age manuelle de la s�lection
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
 * Permet d'ajouter un fichier de constante � inclure avant de cr�er les options des selects.
 * Permet notamment la gestion du multilangue 
 * Parametres : string : le fichier � inclure ex : modules/Hotline/pnlang/fra/user.php
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
 * Permet d'ajouter une liste d�roulante au multiselect
 * 
 * Parametres : string : le nom du select 
 *              string : le sql permettant de construire les options. 
 *                       Il peut contenir des r�f�rence aux selects pr�c�dant en indiquant le nom du select entre [] Ex [groupe] fait r�f�rence � la valeur du select groupe
 *              string : le libell� qui s'affiche devant la liste d�roulante
 *              string : le nom de la classe qui va servir � appliquer une m�thode de modification des libell�.
 *                       Fonctionne en compl�ment du param�tres $func
 *              string : le nom de la fonction (ou m�thode) qui sera appel� sur les libell� des options. (Pour faire la correspondance avec des constantes php pour le mutilangue)     
 *              bool   : si on doit utiliser un easy select � la place d'un select  
 * retour : obj select : l'objet select cr��
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
 
   //ajout de l'�v�nement on change sur le pr�c�dant select
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
 * Permet de connaitre le nombre de champ qu'il � y a dans la clause select du sql
 * pass� en param�tres 
 * 
 * Parametres : string : le sql � analyser
 * retour : array(0,0) : il n'y a qu'un seul champ dans la clause qui servira de valeur d'option et de libell�
 *          array(0,1) : il y a deux champ dans la clause, le premier servira de valeur � l'option, le secon de libell�              
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
 * Permet d'ajouter les options � un select
 * Cette m�thode est exclusivement utilis�e pour construire les options de la 
 *toute premi�re liste d�roulante 
 * 
 * Parametres : string : le nom du select
 * retour : obj select : l'objet select avec ses options
 **************************************************************/ 
  public function constructSelect($stri_select_name)
  {
    
    $stri_sql= $this->arra_sql[$stri_select_name];//r�cup�ration du sql du select
    $obj_query1=new querry_select($stri_sql);//cr�ation d'une requ�te pour ajouter les options
    $arra_analyse=$this->analyseSql($stri_sql);
    $this->arra_select[$stri_select_name]->addOption("",$this->stri_first_option_label);
    
    $obj=(isset($this->arra_function[$stri_select_name]['obj']))?$this->arra_function[$stri_select_name]['obj']:"";
    $function=(isset($this->arra_function[$stri_select_name]['function']))?$this->arra_function[$stri_select_name]['function']:"";
    $this->arra_select[$stri_select_name]->makeQuerryToSelect($obj_query1,$arra_analyse[0],$arra_analyse[1],$obj,$function);//ajout des options
      
    return $this->arra_select[$stri_select_name];
  }
  
 /*************************************************************
 * Permet de construire une liste d�roulante en javascript.
 * Cette m�thode maintient �galement le synchronisme entre les objets select en PHP et en Javascript  

 * Parametres : string : nom du select
 * retour : string : le code DOM javascript � executer pour construire la liste d�roulante             
 **************************************************************/ 
  public function constructJSSelect($stri_select_name)
  {
   
   $stri_raw_sql= $this->arra_sql[$stri_select_name];//r�cup�ration du sql brut
  
   $arra_match=array();
   preg_match_all ('`\[([^]]*)\]`' ,$stri_raw_sql  , $arra_match );//on recherche les r�f�rences aux autres listes d�roulantes
    
   $stri_sql=$stri_raw_sql;
   
   //si on doit executer un traitement particulier sur les libell�s (correspondance avec constante php)
   $obj=(isset($this->arra_function[$stri_select_name]['obj']))?$this->arra_function[$stri_select_name]['obj']:"";
   $function=(isset($this->arra_function[$stri_select_name]['function']))?$this->arra_function[$stri_select_name]['function']:"";
  
   foreach($arra_match[1] as $stri_search)//pour chaque r�f�rence � une liste pr�c�dante
   {
 
    $obj_selected_option=$this->arra_select[$stri_search]->getSelectedOption();
    if(!is_object($obj_selected_option))//si il n'y a pas d'option selectionn�e, cas d'erreur bloquant
    { trigger_error ("The selected option have not been found", E_USER_ERROR  );}
    
    $stri_selected_option_value=$obj_selected_option->getValue();//r�cup�ration de la valeur de l'option s�lectionn�e
    $stri_sql=str_replace('['.$stri_search.']', $stri_selected_option_value,$stri_sql);
   }  
 
    $arra_analyse=$this->analyseSql($stri_sql);//on recherche si les options doivent avoir le meme libell� que valeur
         
    
    $obj_query1=new querry_select($stri_sql);
    $arra_res=$obj_query1->execute();//va contenir les donn�es pour la cr�ation des options
   
   
   
    //on doit nettoyer les select de leurs options � partir du select sur lequel on � cliqu� mais peux ceux d'avant
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
    
    for($i=$int_start;$i<$int_nb_select;$i++)//pour chaque liste d�roulante suivant celle sur laquelle l'utilisateur � cliqu�e
    { 
     $stri_selected_value=$this->arra_select[$arra_keys[$i]]->getSelectedOptionValue();
   
      //nettoyage en javascript
      $obj_select_recup=$this->arra_select[$arra_keys[$i]];
    
      $stri_js.="var select_temp=$('#marqueur').closest('table').find('select[name=\"".$obj_select_recup->getName()."\"]');"; //r�cup en jquery du select suivant
      $stri_js.="select_temp.attr('id','marqueur_2');";  //marqueur temporaire du select suivant       
      
      $stri_js.="var select_$i=document.getElementById('marqueur_2');";  //r�cup classique js du select suivant (pour �viter de tout recoder ce qui suit)
      $stri_js.="select_temp.attr('id','');";//suppression des marqueur temporaire
     // $stri_js.="$('#marqueur').attr('id','');";//suppression des marqueur temporaire  
      //$stri_js.="var select_$i=document.getElementsByName('".$obj_select_recup->getName()."')[0];"; //r�cup�ration du select � changer     
      $stri_js.="while(select_$i.options.length>0){select_$i.remove(0);}";//suppression des options d�j� pr�sentes
     
       //ajout de la premi�re option
       $stri_js.="var option_$i=new Option('".$this->stri_first_option_label."', '', false, false);";//cr�ation d'une nouvelle option
     
       //$stri_js.="select_$i.add(option_$i,null);";//ajout de l'option au select, m�thode d'ajout standard W3C ne fonctionnant pas sous ie ...
       $stri_js.="select_$i.options[select_$i.options.length]=option_$i;";//ajout de l'option au select
       
      //nettoyage en php avec ajout de la premi�re option
      $this->arra_select[$arra_keys[$i]]->setOption($arra_option);
      //$this->arra_select[$arra_keys[$i]]->selectOption($stri_selected_value);
      $this->selectOption($this->arra_select[$arra_keys[$i]],$stri_selected_value);
    }
    
     $obj_select=$this->arra_select[$stri_select_name];
      //$stri_js.="alert('$stri_select_name');";
      $stri_js.="var select_temp=$('#marqueur').closest('table').find('select[name=\"".$obj_select->getName()."\"]');"; //r�cup en jquery du select suivant
      $stri_js.="select_temp.attr('id','marqueur_3');";  //marqueur temporaire du select suivant       
      $stri_js.="var select=document.getElementById('marqueur_3');"; //r�cup�ration du select � changer
      $stri_js.="select_temp.attr('id','');";//suppression des marqueur temporaire
      //$stri_js.="$('#marqueur').attr('id','');";//suppression des marqueur temporaire     
     //$stri_js.="var select=document.getElementsByName('".$obj_select->getName()."')[0];"; //r�cup�ration du select � changer     

   
    $stri_appli_function=($this->arra_function[$stri_select_name]['obj'])?$this->arra_function[$stri_select_name]['obj']."->":""; 
    $stri_appli_function.=(isset($this->arra_function[$stri_select_name]['function']))?$this->arra_function[$stri_select_name]['function']:"";
  
    foreach($arra_res as $key=>$arra_one_res)//pour chaque option � ajouter
    {
     $stri_option_label=($stri_appli_function!="")?$stri_appli_function($arra_one_res[$arra_analyse[1]]):$arra_one_res[$arra_analyse[1]];//d�termination du libell�
     
     //ajout de l'option en php
     $obj_select->addOption($arra_one_res[$arra_analyse[0]],$stri_option_label);
     
     //ajout de l'option en javascript
     $stri_js.="var option_$key=new Option('".str_replace("'","\'",$stri_option_label)."', '".str_replace("'","\'",$arra_one_res[$arra_analyse[0]])."', false, false);";//cr�ation d'une nouvelle option
     //$stri_js.="select.add(option_$key,null);";//ajout de l'option au select, m�thode d'ajout standard W3C ne fonctionnant pas sous ie ...
     $stri_js.="select.options[select.options.length]=option_$key;";//ajout de l'option au select
    }
     //Modif CC 18-07-2013 : Gestion des donn�es du select de recherche
      $stri_js.="var select_alignement=$(select).closest('.main_div').find('select[name=\"alignement\"]');";
      $stri_js.="select_alignement.html('');//vidage des options\n";
      $stri_js.="select_alignement.html($(select).html());//Remplissage des options";

      
    $int_nb_option=count($this->arra_select[$stri_select_name]->getOption());
    $int_selected_index=($int_nb_option==2)?1:0;
    $stri_js.="select.selectedIndex=$int_selected_index;";//s�lection de la premi�re option
    
   
    return $stri_js;
  }
  
 /*************************************************************
 * Permet de r�initialiser les listes d�roulantes
 *
 * Parametres : aucun
 * retour : aucun
 **************************************************************/ 
  public  function raz()
  {
   //$this->bool_delete_temp_file=true;//on marque l'objet pour qu'il n'utilise plus le fichier temporaire
   //ajaxMultiSelect::purgeTemp($this->stri_internal_id);//suppression de l'objet s�rialis�
   foreach($this->arra_select as $obj_select)
   {
    $obj_select->selectOption("");//s�lection de la premi�re option pour chaque select
   }

    $this->saveInTemp($this->stri_internal_id);//r�cup�rcution de la mise � jour sur la sauvegarde de l'objet
  }
  
 /*************************************************************
 * Permet de repr�senter en html le multiselect
 * Cette m�thode est normalement appell�e qu'une seule fois et sert �galement d'initialisation
 *
 * Parametres : int : le nombre de selecet par ligne
 *              string : l'endroit o� mettre les libell�s
 *                       left , right, top ou bottom  
 * retour : string : le code html correspondant      
 **************************************************************/ 
  public function htmlValue($int_nb_select_par_ligne=3,$stri_where_libelle="left")
  {
    $_SESSION["langue"]=$_SESSION['PNSVlang'];//sauvegarde du la langue actuel du l'utilisateur car le cms � la r�initialisation va mettre la langue par d�faut
   
    $this->includeFile();
    $obj_javascripter=new javascripter();
   
    $obj_javascripter->addFile('includes/modalBox.js');//fichier js contenant les fonctionnalit� d'envoi de formulaire en ajax
   
    $stri_form_action=str_replace($_SERVER['DOCUMENT_ROOT'],"", __FILE__) ;//d�duction du chemin relatif de la classe
      
   //lancement de la cr�ation des options de la liste d�roulante
   $obj_javascripter->addFunction("
   function nextSelect(select_name,id_multiselect,obj_select)
   {
     //gestion de l'indentifiant pour le clonage
     var arra_select=document.getElementsByName(obj_select.name);    
    if(id_multiselect.indexOf('_clone_', 0)==-1)//si l'identifiant n'est pas d�j� celui d'un clone
    { 
     for(var i=0;i<arra_select.length;i++) //recherche de l'indide du clone
     {
      if((i>0)&&(arra_select[i]==obj_select))//si on a trouv� le select et si ce n'est pas le premier
      {id_multiselect=id_multiselect+'_clone_'+i;
       //alert('creation d un clone '+i);
      }//ajout d'un indicateur de clonage sur l'�l�ment
     }
    } 
   
     
     //cr�ation de formulaire pour envoi des donn�es
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
   //v�rification que l'objet s�rialis� est correct
   $bool_class=(get_class($obj_existant))=="ajaxMultiSelect";//v�rification que l'objet existant soit un multiselect
   $bool_delete=($bool_class)?$obj_existant->getDeleteTempFile():false;//v�rification que l'objet ne soit pas marqu� � supprim�
   $arra_select=($bool_class)?$obj_existant->getArraSelect():false;
       
   $bool_exist_select=count($arra_select)>0;//v�rification qu'il y ai des select dans le multiselect
        
    
   $bool_create_select=true;//permet de savoir s'il faut cr�er les select ou non
   if($bool_class && !$bool_delete && $bool_exist_select)//pour r�utilis� l'objet s�rialis�, il faut que tout les v�rification soit ok
   {     
    $this->arra_select=$obj_existant->getArraSelect();
    $this->arra_sql=$obj_existant->getSql();
    $this->arra_libelle=$obj_existant->getLibelle();
    $this->arra_function=$obj_existant->getFunction();
    $this->arra_constant_file=$obj_existant->getConstantFile();
    $this->stri_internal_id=$obj_existant->getInternalId();
    $bool_create_select=false;//on reprend un objet correct existant, pas besoin de recr�er les select
    
    //v�rification si l'objet s�rialis� doit �tre mis � jour
    //r�cup�ration � partir de la base du nombre d'option qui doivent �tre dans la premi�re liste
    $arra_key=array_keys($this->arra_sql);
    $stri_sql=$this->arra_sql[$arra_key[0]];
    $stri_verif_sql="SELECT Count(*) from (".$stri_sql.")";
    $obj_query=new querry_select($stri_verif_sql);
    $arra_res=$obj_query->execute();
    $int_nb_option_bdd=$arra_res[0][0];
    //r�cup�ration du nombre d'option du premier select
    $obj_premier_select=$this->arra_select[$arra_key[0]];
    
    $int_nb_option_slz=$obj_premier_select->getNumberOption()-1;//calul du nombre d'option dans le select (-1 pour la premi�re option)
     if($int_nb_option_bdd!=$int_nb_option_slz)//si les deux nombre d'option ne sont pas les m�mes, une mise � jour est � faire
     {
      serialisable::purgeTemp($this->stri_internal_id);//suppession du fichier temporaire
      $bool_create_select=true;//on indique qu'il faut recr�er les select

      foreach($this->arra_select as $obj_select)
      {$obj_select->setOption(array());}//on vide les options des select pour les r�initialiser  

     }
   }
   
   if($bool_create_select)//s'il faut cr�er les select, cas de premi�re cr�ation ou de r�initialisation
   {     
  
    
    $arra_key=array_keys($this->arra_select);
    $this->constructSelect($arra_key[0]);//construction de la premi�re liste d�roulante
       
    $int_nb_option=count($this->arra_select);
   
    for($i=1;$i<$int_nb_option;$i++)//initialisation des autres listes d�roulantes avec seulement la premi�re option faite votre choix
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
    //cette boucle est utilis�e si on � d�clench� une s�lection manuelle gr�ce � la m�thode selectOptionForSelect
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
      $obj_tr=($i%($int_modulo)==0)?$obj_table->addTr():$obj_tr;//on cr�er un nouveau tr tout les $int_nb_select_par_ligne �l�ments
      //$obj_tr->setClass($i);      
        if(($stri_libelle!="")&&($stri_where_libelle=="left"))//placement du libell� � gauche du select
        {$obj_tr->addTd($stri_libelle);}
        
        if(($stri_libelle!="")&&($stri_where_libelle=="top"))//placement du libell� � en haut du select
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
        
        if(($stri_libelle!="")&&($stri_where_libelle=="bottom"))//placement du libell� � en bas du select
        {
         $obj_tr=$obj_table->addTr();
         $obj_tr->addTd($stri_libelle);
        }
        
        if(($stri_libelle!="")&&($stri_where_libelle=="right"))//placement du libell� � droite du select
        {$obj_tr->addTd($stri_libelle);}
        
      $i++;
    }
   
    $obj_table->setWidth("100%");
    //$obj_table->setRules("none");
    //$obj_table->setCellspacing(0);
    //$obj_table->setCellpadding(0);
    $obj_table->noWrapForAllTd(true);
    
    $this->saveInTemp($this->stri_internal_id);//A ce stade l'objet multiselect est enti�rement construit, on le sauvegarde
     
     //$stri_js=(ajaxMultiSelect::$int_nb_instance==1)?$obj_javascripter->javascriptValue():"";//on pose se js une fois seulement pour toute les instances pour le pas d�finir plusieurs fois la m�me fonction js
     $stri_js=(ajaxMultiSelect::$bool_init)?$obj_javascripter->javascriptValue():"";//on pose se js une fois seulement pour toute les instances pour le pas d�finir plusieurs fois la m�me fonction js
     ajaxMultiSelect::$bool_init=falses;
   

    return $stri_js.$obj_table->htmlValue();//retour du javascript et de la repr�sentation html
  }
  
   /*************************************************************
 * Permet de s�lectionn� une option ou une liste d'option dans un select
 *
 * Parametres : obj select : le select sur lequel faire la s�lection
 *              mixed : string : la valeur de l'option � s�lectionner
 *                      array : le tableau des valeurs � s�lectionner  
 * retour : string : le code javascript DOM permettant la construction d'une ou plusieurs listes d�roulante     
 **************************************************************/ 
  public function selectOption(select $obj_select,$mixed_option)
  {
   /*     Modif le 24/11/2011 par LP pour choix d'une option par d�fault dans un multiselect (fait pour : Incidents > Recherche)  :
   
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
     //Modif: dans tous les autres cas on s�lectionne l'option (pas seulement pour is_string puisque la valeur peut etre un entier par exemple)    
    }else {
        $obj_select->selectOption($mixed_option);
    }
  }

     
 /*************************************************************
 * Permet de lancer la construction des listes d�roulantes en ajax
 * Cette m�thode est appell�e chaque fois que la valeur d'une liste d�roulante change
 *
 * Parametres : bool : permet d'indiquer s'il s'agit d'un appel r�cursif de la m�thode ou de son premier appel. 
 *                     Ce param�tre est interne � la m�thode et ne devrait pas �tre pass� lors d'un appel externe. 
 * retour : string : le code javascript DOM permettant la construction d'une ou plusieurs listes d�roulante     
 **************************************************************/ 
  public function ajaxHtmlValue($bool_recursive=false)
  {
   if(!$bool_recursive)//inclusion des fichiers de constante php uniquement lors du premier appel
   {$this->includeFile();}
   
   $arra_key_post=array_keys($_POST);//cr�ation d'un tableau des clefs de la variable $_POST
   $stri_post_key=$arra_key_post[0];//r�cup�ration dynamique du nom de la variable post transmise
 
   
   //le but de cette suite d'instruction est de r�cup�rer le nom de la prochaine liste d�roulante � remplir
   $arra_key_select=array_keys($this->arra_select);//r�cup�ration des clefs des select  
   $arra_key_select_flip=array_flip($arra_key_select);//inversion entre les clefs et les valeurs
   $int_indice=$arra_key_select_flip[$stri_post_key];//r�cup�ration de la position dans le tableau de clefs du select sur lequel on vient de cliquer

   $int_next_select=$int_indice+1;
   $int_nb_select=count($this->arra_select);
       
   //$stri_key_recup=(is_array($_POST[$stri_post_key]))?$stri_post_key."[]":"";    
   $obj_select_clicked=$this->arra_select[$stri_post_key];//r�cup�ration du select sur lequel on vient de cliquer
   $obj_last_select=$this->arra_select[$arra_key_select[$int_nb_select-1]];//r�cup�ration du dernier select
        
         /*$obj_tracer=new tracer(dirname(__FILE__)."/debug.txt");
        $obj_tracer->trace(var_export($obj_last_select->getName(),true));       
        $obj_tracer->trace(var_export("stri_post_key \n",true));
          $obj_tracer->trace(var_export($stri_post_key ,true));             
        
         */
   $stri_last_select_name=str_replace("[]", "", $obj_last_select->getName());
   if($stri_last_select_name==$stri_post_key)//si on est en train de traiter la derni�re liste d�roulante
   { 
   // $obj_last_select->selectOption($_POST[$stri_post_key]);// on fait seulement une s�lection sur l'objet php de l'option sur laquelle l'utilisateur � cliqu�
    $this->selectOption($obj_last_select,$_POST[$stri_post_key]);// on fait seulement une s�lection sur l'objet php de l'option sur laquelle l'utilisateur � cliqu�
   }
   else //traitement subit par tous les select sauf le dernier
   {    
    //$obj_select_clicked->selectOption($_POST[$stri_post_key]);//s�lection de la nouvelle option 
    $this->selectOption($obj_select_clicked,$_POST[$stri_post_key]);//s�lection de la nouvelle option 

    $stri_next_select_name=$arra_key_select[$int_next_select];//r�cup�ration du nom du prochain select � construire
  
    $stri_js=$this->constructJSSelect($stri_next_select_name);//construction en JS DOM du select
     
     $int_nb_option=count($this->arra_select[$stri_next_select_name]->getOption());//on compte le nombre d'option contenu dans le select
     $stri_selected_value=$this->arra_select[$stri_next_select_name]->getSelectedOptionValue();
     //echo "prochaine valeur $stri_next_select_name/$stri_selected_value";
     if(($int_nb_option==2)&&($int_next_select<$int_nb_select-1))//s'il n'y a qu'une seule option extraite de la base (faites votre choix se trouve d�j� dans les options) et si le select trait� actuellement n'est pas le dernier
     {
       unset($_POST);//on supprime le $_POST
       $_POST[$stri_next_select_name]=$this->arra_select[$stri_next_select_name]->getIemeOption(1)->getValue();//on recr�er le post comme si on venait de cliquer sur la liste d�roulante
       $stri_js.=$this->ajaxHtmlValue(true); //lancement r�cursif de la construction du prochain select
     }
   } 
   
   if(!$bool_recursive)//on ne sauvegarde l'objet que dans le premier appel de la fonction et pas dans les appels r�cursif (g�n�re un bug et est inutile)
   {
     $this->saveInTemp($this->stri_internal_id);
   }

   return $stri_js;
 
  }
  
  /*************************************************************
 * Permet de forcer la s�lection d'une valeur pour un select donn�
 * Cela prend le dessus sur le comportement normal de l'objet 
 *
 * Parametres : string : le nom du select o� trouver l'option
 *              string : la valeur de l'option 
 * retour : aucun
 **************************************************************/ 
  public  function selectOptionForSelect($stri_select_name,$stri_option_value)
  {   
 
     $this->arra_selected_option[$stri_select_name]=$stri_option_value;
     /*
     $this->setDeleteTempFile(true);//le for�age de s�lection rend la s�rialisation inutile (reconstruction compl�te obligatoire de l'objet)
     $obj_select_groupe=$this->getSelect($stri_select_name);//on r�cup�re le select concern�
     $obj_select_groupe->selectOption($stri_option_value);//on s�lectionne la valeur
   */
  }
  
 /*************************************************************
 * Permet d'association un sql de d�part � une liste d�roulante
 *
 * Parametres : string : le nom du select o� trouver l'option
 *              string : le sql � utiliser
 * retour : aucun
 **************************************************************/ 
  public  function setInitSqlForSelect($stri_select_name,$stri_sql)
  {   
    $this->arra_init_sql[$stri_select_name]=$stri_sql;
  }
  
  /**
   * Permet d'initialisation les listes d�roulantes qui poss�de un sql d�di� � l'initialisation
   **/
   public function initSelectWithInitSql()
   {
      foreach( $this->arra_init_sql as $stri_select=>$stri_init_sql)
      {
        //- r�cup�ration du sql
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
        $_SESSION["langue"] = $_SESSION['PNSVlang']; //sauvegarde du la langue actuel du l'utilisateur car le cms � la r�initialisation va mettre la langue par d�faut

        $this->includeFile();
        $obj_javascripter = new javascripter();

        $obj_javascripter->addFile('includes/modalBox.js'); //fichier js contenant les fonctionnalit� d'envoi de formulaire en ajax

        $stri_form_action = str_replace($_SERVER['DOCUMENT_ROOT'], "", __FILE__); //d�duction du chemin relatif de la classe
        //lancement de la cr�ation des options de la liste d�roulante
        $obj_javascripter->addFunction("
   function nextSelect(select_name,id_multiselect,obj_select)
   {
     //gestion de l'indentifiant pour le clonage
     var arra_select=document.getElementsByName(obj_select.name);    
    if(id_multiselect.indexOf('_clone_', 0)==-1)//si l'identifiant n'est pas d�j� celui d'un clone
    { 
     for(var i=0;i<arra_select.length;i++) //recherche de l'indide du clone
     {
      if((i>0)&&(arra_select[i]==obj_select))//si on a trouv� le select et si ce n'est pas le premier
      {id_multiselect=id_multiselect+'_clone_'+i;
       //alert('creation d un clone '+i);
      }//ajout d'un indicateur de clonage sur l'�l�ment
     }
    } 
   
     
     //cr�ation de formulaire pour envoi des donn�es
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
        //v�rification que l'objet s�rialis� est correct
        $bool_class = (get_class($obj_existant)) == "ajaxMultiSelect"; //v�rification que l'objet existant soit un multiselect
        $bool_delete = ($bool_class) ? $obj_existant->getDeleteTempFile() : false; //v�rification que l'objet ne soit pas marqu� � supprim�
        $arra_select = ($bool_class) ? $obj_existant->getArraSelect() : false;

        $bool_exist_select = count($arra_select) > 0; //v�rification qu'il y ai des select dans le multiselect


        $bool_create_select = true; //permet de savoir s'il faut cr�er les select ou non
        if ($bool_class && !$bool_delete && $bool_exist_select) {//pour r�utilis� l'objet s�rialis�, il faut que tout les v�rification soit ok
            $this->arra_select = $obj_existant->getArraSelect();
            $this->arra_sql = $obj_existant->getSql();
            $this->arra_libelle = $obj_existant->getLibelle();
            $this->arra_function = $obj_existant->getFunction();
            $this->arra_constant_file = $obj_existant->getConstantFile();
            $this->stri_internal_id = $obj_existant->getInternalId();
            $bool_create_select = false; //on reprend un objet correct existant, pas besoin de recr�er les select
            //v�rification si l'objet s�rialis� doit �tre mis � jour
            //r�cup�ration � partir de la base du nombre d'option qui doivent �tre dans la premi�re liste
            $arra_key = array_keys($this->arra_sql);
            $stri_sql = $this->arra_sql[$arra_key[0]];
            $stri_verif_sql = "SELECT Count(*) from (" . $stri_sql . ")";
            $obj_query = new querry_select($stri_verif_sql);
            $arra_res = $obj_query->execute();
            $int_nb_option_bdd = $arra_res[0][0];
            //r�cup�ration du nombre d'option du premier select
            $obj_premier_select = $this->arra_select[$arra_key[0]];

            $int_nb_option_slz = $obj_premier_select->getNumberOption() - 1; //calul du nombre d'option dans le select (-1 pour la premi�re option)
            if ($int_nb_option_bdd != $int_nb_option_slz) {//si les deux nombre d'option ne sont pas les m�mes, une mise � jour est � faire
                serialisable::purgeTemp($this->stri_internal_id); //suppession du fichier temporaire
                $bool_create_select = true; //on indique qu'il faut recr�er les select

                foreach ($this->arra_select as $obj_select) {
                    $obj_select->setOption(array());
                }//on vide les options des select pour les r�initialiser  
            }
        }

        if ($bool_create_select) {//s'il faut cr�er les select, cas de premi�re cr�ation ou de r�initialisation
            $arra_key = array_keys($this->arra_select);
            $this->constructSelect($arra_key[0]); //construction de la premi�re liste d�roulante

            $int_nb_option = count($this->arra_select);

            for ($i = 1; $i < $int_nb_option; $i++) {//initialisation des autres listes d�roulantes avec seulement la premi�re option faite votre choix
                $this->arra_select[$arra_key[$i]]->addOption("", $this->stri_first_option_label);
            }
            //traitement particulier du dernier select
            $obj_last_select = $this->arra_select[$arra_key[$i - 1]];
            $obj_last_select->setOnchange("nextSelect('" . $obj_last_select->getName() . "','" . $this->stri_internal_id . "',this)");
        }

        $arra_post = $_POST; //sauvegarde de ce qui se trouve en post
        //cette boucle est utilis�e si on � d�clench� une s�lection manuelle gr�ce � la m�thode selectOptionForSelect
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
        $this->saveInTemp($this->stri_internal_id); //A ce stade l'objet multiselect est enti�rement construit, on le sauvegarde
        //$stri_js=(ajaxMultiSelect::$int_nb_instance==1)?$obj_javascripter->javascriptValue():"";//on pose se js une fois seulement pour toute les instances pour le pas d�finir plusieurs fois la m�me fonction js
        $stri_js = (ajaxMultiSelect::$bool_init) ? $obj_javascripter->javascriptValue() : ""; //on pose se js une fois seulement pour toute les instances pour le pas d�finir plusieurs fois la m�me fonction js
        ajaxMultiSelect::$bool_init = falses;


        return array($stri_js,$obj_tr); //retour du javascript et de la repr�sentation html
    }
}



 /*************************************************************
 *     //\\
 *   // ! \\  CODE EXTRAT CLASSE !!!
 *   -------
 *  
 * Permet de rendre la classe "ind�pendante". 
 ***************************************************************/ 


if(isset($_POST['internal_id']))
{   
 //on ne passe ici que lors d'un envoi en ajax
  set_include_path($_SERVER['DOCUMENT_ROOT']);//modification du path d'inclusion pour retrouver les diff�rents �l�ments
  include_once ("includes/pnAPI.php");//on est en dehors du CMS, on doit faire l'initialisation minimum
  pnInit();
  include_once("config/user_bd_choice.tintf.php");//pour prendre en compte le typage de donn�es
  include_once("includes/html.pkg.php"); 
  
  //gestion du clonage de l'objet ajaxMultiselect
  $arra_part=explode("_clone_", $_POST['internal_id']);//r�cup des info sur le clone �ventuel
  if(isset($arra_part[1]))//si on a affaire � un clone
  {
    
    ajaxMultiSelect::copyFromTemp($arra_part[0],$_POST['internal_id']);//copie du fichier temp=  
  }
  
  /*
  $obj_tracer=new tracer(dirname(__FILE__)."/debug.txt");
  $obj_tracer->trace(var_export($_POST,true));*/ 
  
  foreach($_POST as $key=>$stri_value)//pour g�rer correctent les accents
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
  $_SESSION['PNSVlang']=$_SESSION["langue"];//restauration de la bonne langue sinon le cms remet celle par d�faut
 
 
  $obj_ajaxMultiSelect=ajaxMultiSelect::loadFromTemp($_POST['internal_id']);//chargement de l'objet s�rialis�
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
        {//cas de chargement des donn�es
          echo $obj_ajaxMultiSelect->ajaxHtmlValue(); //"affichage" du code JS DOM d'actualisation des listes d�roulantes
          ob_flush();
          flush();                            
        }
  }
}


 
?>
