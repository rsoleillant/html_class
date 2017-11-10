<?php
/*******************************************************************************
Create Date : 02/06/2006
 ----------------------------------------------------------------------
 Class name : onglet
 Version : 2.2
 Author : Rémy Soleillant
 Description : onglets pour menu
********************************************************************************/

include_once("submit.class.php");
class onglet extends submit
{
  //**** attribute *************************************************************
  protected $arra_page=array();      //=> tableau des pages rattachées à cet onglet
  protected $bool_selected=false;    //=> permet de connaitre si l'onglet est sélectionné -true- ou pas -false-
  protected $stri_active_class;      //=> nom de la classe css à utiliser pour l'onglet actif
  protected $stri_inactive_class;    //=> nom de la classe css à utiliser pour l'onglet inactif
  protected $stri_active_src;        //=> le chemin de l'image à utiliser pour l'onglet actif
  protected $stri_inactive_src;      //=> le chemin de l'image à utiliser pour l'onglet inactif
  
  protected $stri_disabled_class;   //=>nom de la classe css a utiliser pour les onglets disabled
  protected $stri_disabled_src;   //=>chemin de l'image a utiliser pour les onglets disabled
  
  protected $bool_personnalisable=true;   //- L'onglet peut être personnalisé (masqué) ?
  
  public $arra_sauv=array();         //=> tableau de serialisation
  
  //**** constructor ***********************************************************
  function __construct($name,$value,$act_class,$inact_class,$act_src,$inact_src) 
  {
    //construit l'objet onglet
    //@param : $name =>  [string] le nom de l'objet onglet
    //@param : $value => [string] le libellé de l'onglet
    //@param : $act_class => [string] nom de la classe css à utiliser pour l'onglet actif
    //@param : $inact_class => [string] nom de la classe css à utiliser pour l'onglet inactif
    //@param : $act_src => [string] le chemin de l'image à utiliser pour l'onglet actif
    //@param : $inact_src => [string] le chemin de l'image à utiliser pour l'onglet inactif
    //@return : void
      
      
    /**
     * Style onglet jQuery UI
     */
    $act_class.=" ui-tabs-nav ui-state-default ui-corner-top ui-tabs-active ui-state-active";
    $inact_class.=" ui-state-default ui-corner-top";

    
    $this->stri_name=$name;
    $this->stri_value=$value;
    $this->stri_type='submit';
    $this->stri_active_class=$act_class;
    $this->stri_inactive_class=$inact_class;
    $this->stri_active_src=$act_src;
    $this->stri_inactive_src=$inact_src;    
    $this->setStyle("cursor:pointer");
    
    $this->stri_disabled_src='includes/bars.gif';
    //$this->stri_disabled_class='tab_disabled';
    //$this->stri_disabled_class='tab_disabled ui-state-disabled ui-corner-top';
    $this->stri_disabled_class='ui-state-disabled tab_inactif ui-state-default ui-corner-top';
  }
  
  //**** setter ****************************************************************
  public function setSelected($bool)
  {
    //permet de sélectionner l'onglet
    //@param : $bool => true : onglet sélectionné
    //                  false : onglet non sélectionné
    //@return : void
    
    if(is_bool($bool))
      {$this->bool_selected=$bool;}
    else
      {echo("<script>alert('bool_selected doit etre de type boolean');</script>");}
  }
  
  public function setPersonnalisable($value)  {      $this->bool_personnalisable = $value;  }


  //**** getter ****************************************************************
  public function getPersonnalisable(){return $this->bool_personnalisable;}
  public function getValue(){return $this->stri_value;}
  public function getPage(){return $this->arra_page;} 
  public function getIemePage($int){return $this->arra_page[$int]['page'];}
  public function getIemeAction($int){return $this->arra_page[$int]['action'];}
  public function getName(){return $this->stri_name;}
  
  public function getPageByAction($action)
  {
    //permet de récupérer le script à l'action $action
    //@param : $action => le nom de l'action associée au script
    //@return : [string] => le script à inclure
    
    for($i=0;$i<count($this->arra_page);$i++)
    {
      if($this->arra_page[$i]['action']==$action){return $this->arra_page[$i]['page'];}
    }
    return $this->arra_page[0]['page'];
  } 
  
  public function getMultiPageByAction($action)
  {
    //renvoie un tableau de page correspondant à $action, si aucune page trouvée, renvoie la première page
    //@param : $action => le nom de l'action associée aux différents scripts
    //@return : $arra_temp => tableau des scripts à inclure
    
    $arra_temp=array();
    for($i=0;$i<count($this->arra_page);$i++)
    {
      if($this->arra_page[$i]['action']==$action){$arra_temp[$i]=$this->arra_page[$i]['page'];}
    }
    if(count($arra_temp)==0){$arra_temp[0]=$this->arra_page[0]['page'];}
    return $arra_temp;
  } 
  
  public function getSelected(){return $this->bool_selected;} 
  public function getActiveClass(){return $this->stri_active_class;} 
  public function getInactiveClass(){return $this->stri_inactive_class;} 
  public function getActiveSrc(){return $this->stri_active_src;} 
  public function getInactiveSrc(){return $this->stri_inactive_src;} 
  
  //**** public method *********************************************************
  public function addPage($stri_page,$stri_action)
  {
    //ajoute un script associé à une action 
    //@param : $stri_page => le chemin complet du script à inclure (stri_page must be the complete way)
    //@param : $stri_action => le nom de l'action associé au script
    
    $i=count($this->arra_page);
    $arra_temp['page']=$stri_page;
    $arra_temp['action']=$stri_action;
    $this->arra_page[$i]=$arra_temp;
  }
  
  public function insertPage()
  {
    //inclus tous les fichiers associés à l'onglet -attention : utiliser que dans des cas précis-
    //@return : void
    
    for($i=0;$i<count($this->arra_page);$i++){ include_once($this->arra_page[$i]['page']); };  
  }

  public function display()
  {
    //Gestion du style du curseur car surcharge dans les conditions ci-dessous
    $this->setStyle( preg_replace('/cursor\s*:\s*pointer/', '', $this->getStyle()));
    
    
    //renvoie le code html pour l'affichage de l'onglet
    //@return : [string] => code HTML de l'onglet
    if($this->bool_selected)
    {
      $this->setClass($this->stri_active_class);
      $this->setSrc($this->stri_active_src);
      //echo $this->getName()."<br />";
      $this->setStyle($this->getStyle().'; cursor: normal; box-shadow: 0px 0px 4px 0px black;');    //Gestion du style des onglet like jquery Tabs
    }
    elseif($this->bool_disabled)
    {
    //cas ou l'onget est desactivé
    
      $this->setClass($this->stri_disabled_class);
      $this->setSrc('modules/Hotline/images/onglet_off.gif');      
      //$this->setOnMouseOver('$(this).addClass(\'ui-state-hover\');');           //Gestion du style des onglet like jquery Tabs
      //$this->setOnMouseOut('$(this).removeClass(\'ui-state-hover\');');
      $this->setStyle($this->getStyle().'; box-shadow: 1px 1px 1px 0px lightblue; cursor: normal;');
    }
    else
    {
      $this->setClass($this->stri_inactive_class);
      $this->setSrc($this->stri_inactive_src);
      $this->setOnMouseOver('$(this).addClass(\'ui-state-hover\');');           //Gestion du style des onglet like jquery Tabs
      $this->setOnMouseOut('$(this).removeClass(\'ui-state-hover\');');
      $this->setStyle($this->getStyle().'; box-shadow: 1px 1px 1px 0px lightblue; cursor: pointer;');
    }
    
    //Gestion du style par défaut des onglets
    $this->setStyle($this->getStyle().' height: 28px; margin-top: 2px;');
    
    
    return $this->htmlValue();
  }
  
  //**** method for serialization **********************************************
  public function __sleep() 
  {
    //sérialise l'onglet 
    $this->arra_sauv['name']  = $this->stri_name;
    $this->arra_sauv['value']  = $this->stri_value;
    $this->arra_sauv['type']  = $this->stri_type;
    $this->arra_sauv['active_class']  = $this->stri_active_class;
    $this->arra_sauv['inactive_class']  = $this->stri_inactive_class;
    $this->arra_sauv['active_src']  = $this->stri_active_src;
    $this->arra_sauv['inactive_src']  = $this->stri_inactive_src;
    $this->arra_sauv['selected']  = $this->bool_selected;
    for($i=0;$i<count($this->arra_page);$i++)
    {
    $arra_temp[$i]['page']=$this->arra_page[$i]['page'];
    $arra_temp[$i]['action']=$this->arra_page[$i]['action'];
    }
    $this->arra_sauv['arra_page']=$arra_temp;
    
    return array('arra_sauv');
  }
  
  public function __wakeup() 
  {
    //désérialise l'onglet 
    $this->stri_name= $this->arra_sauv['name'];
    $this->stri_value= $this->arra_sauv['value'];
    $this->stri_type= $this->arra_sauv['type'];
    $this->stri_active_class= $this->arra_sauv['active_class'];
    $this->stri_inactive_class= $this->arra_sauv['inactive_class'];
    $this->stri_active_src= $this->arra_sauv['active_src'];
    $this->stri_inactive_src= $this->arra_sauv['inactive_src'];
    $this->bool_selected= $this->arra_sauv['selected'];
    $arra_temp=$this->arra_sauv['arra_page'];
    $nbr_object=count($arra_temp);
    for($i=0;$i<$nbr_object;$i++)
    {
      $this->arra_page[$i]['page']=$arra_temp[$i]['page'];
      $this->arra_page[$i]['action']=$arra_temp[$i]['action'];
    }
    $this->arra_sauv = array();
  } 
}

?>
