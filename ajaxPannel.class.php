<?php
/*******************************************************************************
Create Date : 09/02/2012
 ----------------------------------------------------------------------
 Class name : ajaxPannel
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de gérer un panneau d'affichage qui sera chargé en ajax.
 
 Modif ajax pannel
********************************************************************************/
//dépendance de la classe sur le fichier js : includes/modalBox.js
include_once($_SERVER['DOCUMENT_ROOT']."includes/classes/html_class/serialisable.class.php");
     
class ajaxPannel extends serialisable 
{
  //**** attribute *************************************************************
  protected $stri_titre;      //Le titre du panneau
  protected $stri_function;   //La fonction a exécuter lors du traitement ajax
  protected $arra_parameter;  //La liste des paramètres à passer à la fonction $stri_function
  protected $bool_deplier;    //L'état déplié ou non du panneau 
  protected $stri_id;         //L'identifiant du pannel          
  
  //**** constructor ***********************************************************
   
   /*************************************************************
   *
   * parametres : string : l'identifiant de l'instance
   * retour : objet de la classe rules_applicator   
   *                        
   **************************************************************/         
  function __construct($stri_titre,$stri_function,$arra_parameter)
  {   
     $this->stri_titre=$stri_titre;
     $this->stri_function=$stri_function;
     $this->arra_parameter=$arra_parameter;
     
     $this->bool_deplier=false;
     $this->stri_id=$this->constructId();
    
     if(in_array($this->stri_id,$_SESSION['ajaxPannel']))//si l'identifiant est en session
     { 
       $this->bool_deplier=true;
     }
  }  
 
  //**** setter ****************************************************************
  public function setTitre($value){$this->stri_titre=$value;}
  public function setFunction($value){$this->stri_function=$value;}
  public function setParameter($value){$this->arra_parameter=$value;}
  public function setDeplier($value){$this->bool_deplier=$value;}

  //**** getter ****************************************************************
  public function getTitre(){return $this->stri_titre;}
  public function getFunction(){return $this->stri_function;}
  public function getParameter(){return $this->arra_parameter;}
  public function getDeplier(){return $this->bool_deplier;}

  //**** public method *********************************************************
/*************************************************************
 * Permet de construire un identifiant unique à partir des données
 * de l'objet 
 * 
 * Parametres :Aucun
 * retour : string : le code html           
 **************************************************************/ 
  public function constructId($stri_sql)
  {
     $stri_id=$this->stri_titre."_".$this->stri_function;
     $stri_param=implode(",",$this->arra_parameter);
     $stri_id.=$stri_param;
     
     $stri_id=str_replace(array("'"," "), "_",$stri_id);
     
     return $stri_id;
  }
  
/*************************************************************
 * Permet d'avoir l'interface du panneau
 * 
 * Parametres :Aucun
 * retour : string : le code html           
 **************************************************************/ 
  public function htmlValue($bool_css=false)
  {
   global $bgcolor1,$bgcolor2,$bgcolor3,$bgcolor4,$bgcolor5;
   
   
   
   
   //Partie javascript
   $obj_javascripter=new javascripter();
      $obj_javascripter->addFunctionOnce("
      /**
       * Permet d'afficher ou masquer le contenu du panneau
       **/             
      function toggle(obj_img)
      {
       var td=$(obj_img).closest('table').find('.ajaxConteneur');//récupération du td
           //td.toggle();//affichage ou masquage du panneau
             td.css('display','');
           td=$(obj_img).closest('table').find('.zoneDonnee');
           td.toggle();
          
          
        //traitement de l'image
        if($(obj_img).hasClass('plusIcon'))
        {
          $(obj_img).removeClass('plusIcon');
          $(obj_img).addClass('minusIcon');
          $(obj_img).attr('src','images/module/BUT_M.gif');
        }
        else
        {
          $(obj_img).removeClass('minuIcon');
          $(obj_img).addClass('plusIcon');
          $(obj_img).attr('src','images/module/BUT_P.gif');
        }    
           
      }
       
      /**
       * Permet de charger le contenu du panneau
       **/             
      function loadContain(obj_img)
      {  
       
        $(obj_img).addClass('loaded');
        var table=$(obj_img).closest('table');
       
        //création d'un formulaire      
        var form=document.createElement('form');
        form.method='post';
        form.action='modules.php?op=modload&name=Outils&file=ajax&ajaxFile=".__FILE__."';
           
        $(form).append(table.clone()); //rattachement des données au formulaire                 
        
        var param=new Array();
            param['obj_img']=obj_img;
        var after=function(arra_param,stri_reponse)
        {
        
          var obj_img=arra_param['obj_img'];
          var table=$(obj_img).closest('table');
          var td=table.find('.zoneDonnee');
          td.html(stri_reponse); //on remplace le contenu du td par le résultat ajax
          td=$(obj_img).closest('table').find('.ajaxConteneur');//on cache l'image de chargement
          td.css('display','none');
          
           //alert('reponse '+stri_reponse); 
        }    
        
         sendAjax(form,true,after,param); //envoi du form en ajax                
      }
      
       /**
       * Permet d'initialiser pour la définition ou suppresion de la variable de session
       * servant à savoir si le panneau est déplié ou non       
       **/             
      function defineUndefineSession(obj_img)
      { 
        var hidden=$(obj_img).closest('table').find('.id_panel');//récupération de l'hidden du pannel
        //alert('def '+hidden.attr('name')); 
        if(hidden.attr('name')=='id_pannel')//on met pour supprimer la variable de session
        {
             hidden.attr('name','unset_id_pannel');
        }
        else
        {
             hidden.attr('name','id_pannel');
        }
      }
      ");
      
   if($this->bool_deplier)//si on veut le panneau déplié
   {
     $obj_javascripter->addFunction("
     var img= document.getElementById('".$this->constructId()."');
       toggle(img);
       loadContain(img);
     ");
   }   
   
   //Objet de class img
   $obj_img=new img("images/module/BUT_P.gif");
    $obj_img->setWidth("13");
    $obj_img->setOnClick("toggle(this);defineUndefineSession(this);loadContain(this);");
    $obj_img->setClass("plusIcon");
    $obj_img->setId($this->constructId());
    
   $obj_img_load=new img("images/module/loading.gif");
   
   //Objet de classe font
   $obj_font_titre=new font($this->stri_titre,true);
    $obj_font_titre->setOnClick("toggle($(this).parent().find('img'));defineUndefineSession($(this).parent().find('img'));loadContain($(this).parent().find('img'));");
    $obj_font_titre->setStyle("cursor:pointer");
   //Construction des objets de formulaire pour la tranmission des données
   //Objet de class hidden
   $stri_hidden="";
   foreach($this->arra_parameter as $stri_parametre)
   {
    $obj_hidden=new hidden("parametre[]",$stri_parametre);
    $stri_hidden.=$obj_hidden->htmlValue();
   }
    $obj_hidden_function=new hidden("fonction",$this->stri_function);
    
    $stri_name=($this->bool_deplier)?"id_pannel":"unset_id_pannel";
    $obj_hidden_id_pannel=new hidden($stri_name,$this->stri_id);
        $obj_hidden_id_pannel->setClass("id_panel");
    $stri_hidden.=$obj_hidden_id_pannel->htmlValue();
    
    
      //retransmission des variable post existante
   $stri_retransmission="";
   foreach($_POST as $stri_key=>$mixed_value)
   {
    if(is_array($mixed_value)) //cas de transmission de tableau
    {
     foreach($mixed_value as $stri_value)
     {
       $obj_hidden_retransmission=new hidden($stri_key."[]",$stri_value);
        $obj_hidden_retransmission->setDisabled(true);
        $obj_hidden_retransmission->setClass("retransmission");
       $stri_retransmission.=$obj_hidden_retransmission->htmlValue();
     }
    
    }
    else  //cas de transmission simple
    {
      $obj_hidden_retransmission=new hidden($stri_key,$mixed_value);
        $obj_hidden_retransmission->setDisabled(true);    //désactivation pour ne pas transmettre en post classique. En ajax le poste est quand même envoyé
        $obj_hidden_retransmission->setClass("retransmission");
        
      $stri_retransmission.=$obj_hidden_retransmission->htmlValue();
    }    
   }
   $stri_hidden.=$stri_retransmission;
   
   //Objet de class form
   //$obj_form=new form("modules.php?op=modload&name=Outils&file=ajax&ajaxFile=".__FILE__,"post");
    // $obj_form->setValue($stri_hidden.$obj_hidden_function->htmlValue());
     
     
   $obj_table=new table();
   
       if ($bool_css)
       { $obj_table->setClass('contenu'); }
       
      $obj_tr=$obj_table->addTr();
       $obj_td=$obj_tr->addTd(array($obj_img," ",$obj_font_titre));
       
       if ($bool_css)
       //{ $obj_td->setClass('titre3-3 entete'); }
       { $obj_td->setClass('titre3-5'); }
       
       
      $obj_tr=$obj_table->addTr();
       $obj_td=$obj_tr->addTd(array($obj_img_load,$stri_hidden,$obj_hidden_function));
       $obj_td->setAlign("center");
       $obj_td->setStyle("display:none;");
       $obj_td->setClass("ajaxConteneur");
      //$obj_td=$obj_tr->addTd("test");
      $obj_td=$obj_tr->addTd("");
       $obj_td->setAlign("center");
       $obj_td->setStyle("display:none;");
       $obj_td->setClass("zoneDonnee");
    $obj_table->setStyle("background-color:$bgcolor1;");
    $obj_table->setWidth("100%");  
   
   return $obj_table->htmlValue().$obj_javascripter->javascriptValue();    
       
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
   $stri_pos=strpos($stri_sql,"from");//on recherche la position du mot clef from dans le sql
   $stri_select_clause=substr($stri_sql,0,$stri_pos);//extraction de la clause select 
   $int_nb_comma= substr_count($stri_select_clause,",");
   
   if($int_nb_comma==0)
   {return array(0,0);}
   
   return array(0,1);
  }
}

 /*************************************************************
 *     //\\
 *   // ! \\  CODE EXTRAT CLASSE !!!
 *   -------
 *  
 * Permet de rendre la classe "indépendante". 
 ***************************************************************/ 
  // unset($_SESSION['ajaxPannel']);
if($_GET['file']=="ajax")
{  
 //pour gérer correctent les accents 
 foreach($_POST as $key=>$stri_value)
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
   
 if(isset($_POST['id_pannel']))//si on doit déplier le panneau
 {
  //inclusion des autoload des principaux modules
 include_once("modules/Hotline/includes/data_class.php"); //hotline
 include_once("modules/Hotline/pnlang/".pnUserGetLang()."/user.php"); 
 

 
  $stri_function=$_POST['fonction'];//récupération de la fonction a exécuté
  $stri_parametre=implode(",",$_POST['parametre']);//construction de la chaîne de paramètre
   
  
  if(strpos($stri_function,"::"))//si la fonction est une méthode statique 
  {
   $arra_part=explode("::",$stri_function);
   $stri_class=$arra_part[0];
   $stri_method=$arra_part[1];
  
   $stri_res= call_user_func_array(array($stri_class, $stri_method),$_POST['parametre']);

   if($stri_res=="")//call_user_func_array ne génère pas d'erreur si elle ne trouve pas la fonction
   {
    trigger_error("Aucun résultat renvoyé par $stri_function",E_USER_ERROR);
   }
   echo $stri_res;
  }
  else
  {
    echo $stri_function($_POST['parametre'][0],$_POST['parametre'][1]);
  }
  
     
   $_SESSION['ajaxPannel'][]=$_POST['id_pannel'];
 }
      
   
  if(isset($_POST['unset_id_pannel']))//si on doit replier le panneau
  { 
      /*$obj_tracer=new tracer(dirname(__FILE__)."/debug.txt");
     $obj_tracer->trace(var_export($_SESSION['ajaxPannel'],true));  */
    unset($_SESSION['ajaxPannel']);
  }
  
   /*
    if(in_array($this->stri_id,$_SESSION['ajaxPannel']))//si l'identifiant est en session
     {
       $this->bool_deplier=true;
     } */
}
 
?>
