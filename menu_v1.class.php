<?php
/*******************************************************************************
Create Date : 02/06/2006
 ----------------------------------------------------------------------
 Class name : menu
 Version : 1.2.1
 Author : Rémy Soleillant
 Description : gère des onglets
 Update : 22/04/2008
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
  public $arra_sauv=array();        //=> tableau de serialisation
  
  //**** constructor ***********************************************************
  function __construct($url, $act_class, $inact_class, $act_src, $inact_src) 
  {
    //construit l'objet menu
    //@param : $url => le chemin relatif par lequel on va accèder au menu
    //@param : $act_class => nom de la classe css à utiliser pour l'onglet actif
    //@param : $inact_class => nom de la classe css à utiliser pour les onglets inactifs
    //@param : $act_src => le chemin de l'image à utiliser pour l'onglet actif
    //@param : $inact_src => le chemin de l'image à utiliser pour les onglets inactifs
    //@return : void
    
    $this->stri_url=$url;
    $this->stri_active_class=$act_class;
    $this->stri_inactive_class=$inact_class;
    $this->stri_active_src=$act_src;
    $this->stri_inactive_src=$inact_src;
    
   
  }
 
  //**** setter ****************************************************************
  public function setUrl($stri_url){$this->stri_url=$stri_url;}
  public function setExtratHtml($stri_value){$this->stri_extra_html=$stri_value;} 

  //**** getter ****************************************************************
  public function getOnglet(){return $this->arra_onglet;} 
  public function getActiveClass(){return $this->stri_active_class;} 
  public function getInactiveClass(){return $this->stri_inactive_class;} 
  public function getActiveSrc(){return $this->stri_active_src;} 
  public function getInactiveSrc(){return $this->stri_inactive_src;}
  public function getUrl(){return $this->stri_url;}  
  public function getExtratHtml(){return $this->stri_extra_html;}
  
  
  //**** public method *********************************************************
  public function getIemeOnglet($int){return $this->arra_onglet[$int];}

  public function getActiveOnglet()
  {
    //renvoie l'onglet qui est sélectionné
    //@return : [obj] => l'onglet sélectionné
    
    for($i=0;$i<count($this->arra_onglet);$i++)
    {
      if($this->arra_onglet[$i]->getSelected()){return $this->arra_onglet[$i];}
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
          $stri_index=$this->stri_url."selected_onglet";
          unset($_POST['selected_onglet']);
          $_SESSION[$stri_index]=$name;
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
  
  public function forceSelectedOnglet($str_name)
  {
   //Cette méthode permet de forcer un onglet à être selectioné.
   //Elle doit être appelée avant la méthode htmlValue
   //@param : $str_name => le nom de l'onglet
   //@return : void
   
   
    $stri_index=$this->stri_url."selected_onglet";
    $stri_index=strtr($stri_index,".&=?/","_____");
    $_POST[$stri_index]=$str_name;
    
    
  }
  
  public function htmlValue()
  {
    //affiche le menu
    //@return : [string] => le menu sous forme html
    
    $stri_index=$this->stri_url."selected_onglet";
    //construction de l'identifiant du menu
    $stri_form=strtr($this->stri_url."_form",".&=?/-","______");
    $stri_index=strtr($stri_index,".&=?/-","______");
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
    if(isset($_POST[$stri_index]))
    {$_SESSION[$stri_index]=$_POST[$stri_index];}
    
    //la variable de session est définie si on ne viens pas de cliquer sur un onglet
    if(isset($_SESSION[$stri_index]))
      {$this->setSelectedOnglet($_SESSION[$stri_index]);}
    else
      {$this->setSelectedOnglet($this->arra_onglet[0]->getName());}
        
    for($i=0;$i<count($this->arra_onglet);$i++)
    {
      $this->arra_onglet[$i]->setOnclick("document.".$stri_form.".clicked.value='clicked';document.".$stri_form.".".$stri_index.".value=this.name");
      $tr->addTd($this->arra_onglet[$i]->display());
    }
    $stri_extra=($this->stri_extra_html!="")?$this->stri_extra_html:"";
    
    $tr->addTd($stri_extra.$form->getEndBalise());
    $html_table->setCellspacing(0);
    $html_table->setCellpadding(0);
    $html_table->setClass("pn-normal");
    $html_table->setBorder(0);
    $html_table->insertTr($tr);
    
    return $html_table->htmlValue();
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
   $stri_index=$this->stri_url."selected_onglet";
   $stri_index=strtr($stri_index,".&=?/-","______");
   //si on a passé en post un onglet, cela signifie que l'on viens de cliquer
    //sur un onglet ou qu'on l'on a utiliser la méthode forceSelectedOnglet
    if(isset($_POST[$stri_index]))
    {$_SESSION[$stri_index]=$_POST[$stri_index];}
    
    //la variable de session est définie si on ne viens pas de cliquer sur un onglet
    if(isset($_SESSION[$stri_index]))
      {$this->setSelectedOnglet($_SESSION[$stri_index]);}
    else
      {$this->setSelectedOnglet($this->arra_onglet[0]->getName());}
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
