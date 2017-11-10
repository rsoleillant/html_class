<?php
/*******************************************************************************
Create Date : 08/10/2008
 ----------------------------------------------------------------------
 Class name : js_calendar.class.php
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de créer un calendrier en javascript
********************************************************************************/

class js_calendar  
{
  //**** attribute *************************************************************
  protected $stri_name;               //Le nom de l'input servant à stocker la date
  protected $stri_label;              //Le label à afficher devant le calendrier
  protected $stri_style_css;          //Le style css des calendriers
  protected $obj_img;                 //L'image du calendrier
  protected $obj_text;                //L'input text utilisé pour stocker la date
  protected $obj_font;                //Le font utilisée pour stocker le label
  protected $obj_table;               //La table html qui va contenir les éléments
  protected $obj_javascripter;        //Permet le stockage du js nécessaire
  protected static $int_nb_instance=0;//Le nombre d'instance de la classe  
  
  //attributs pour settup du calendrier
  protected $stri_ifFormat       = '%d/%m/%Y' ;       //format du calendrier
  protected $stri_align          = 'Bl'       ;       // alignment (defaults to 'Bl')
  protected $stri_singleClick    = "true"     ;       //accessible en un seul clic
  protected $stri_showsTime      = "false"    ;       //avec les heures
  protected $int_id;                                  //identifiant unique du calendrier  
   //**** constructor ***********************************************************
   
   /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe js_calendar   
   *                        
   **************************************************************/         
  function __construct($stri_name,$stri_label,$stri_value) 
  {
   $this->stri_name=$stri_name;
   $this->stri_label=$stri_label;
   js_calendar::$int_nb_instance++;
   $this->int_id=js_calendar::$int_nb_instance;
   $this->stri_style_css='<link rel="stylesheet" type="text/css" media="all" href="includes/classes/jscalendar/calendar-win2k-2.css">';
   $this->constructElement();
   $this->setValue($stri_value);
  }  
 
  //**** setter ****************************************************************
  public function setName($value)
  {
   $this->stri_name=$value;
   if(get_class($this->obj_text)=="text")
   {$this->obj_text->setName($value);}
  }
  public function setLabel($value)
  {
   $this->stri_label=$value;
   if(get_class($this->obj_font)=="font")
   {$this->obj_font->setValue($value);}
  }
  public function setImg($value){$this->obj_img=$value;}
  public function setText($value){$this->obj_text=$value;}
  public function setJavascripter($value){$this->obj_javascripter=$value;}
  public function setFont($value){$this->obj_font=$value;}
  public function setTable($value){$this->obj_table=$value;}

  public function setIfformat($value){$this->stri_ifFormat=$value;}
  public function setAlign($value){$this->stri_align=$value;}
  public function setSingleclick($value){$this->stri_singleClick=$value;}
  public function setShowstime($value){$this->stri_showsTime=$value;}

  
  public function setValue($stri_value){$this->obj_text->setValue($stri_value);}
    
  //**** getter ****************************************************************
  public function getName(){return $this->stri_name;}
  public function getImg(){return $this->obj_img;}
  public function getText(){return $this->obj_text;}
  public function getJavascripter(){return $this->obj_javascripter;}
  public function getFont(){return $this->obj_font;}
  public function getLabel(){return $this->stri_label;}
  public function getTable(){return $this->obj_table;}
  public function getStyleCss(){return $this->stri_style_css;}

  public function getIfformat(){return $this->stri_ifFormat;}
  public function getAlign(){return $this->stri_align;}
  public function getSingleclick(){return $this->stri_singleClick;}
  public function getShowstime(){return $this->stri_showsTime;}

  //**** public method *********************************************************
   /*************************************************************
   * Permet de construire les différents objets de l'interface
   * parametres : aucun
   * retour : aucun
   *                        
   **************************************************************/         
   public function constructElement()
   {
    $stri_res="";
    if(js_calendar::$int_nb_instance==1)//si je js n'a jamais été initialisé
    {$this->initialiseJs();}
    
    //text portant la date
    $obj_text=new text($this->stri_name);
      $obj_text->setSize("9");
    //l'image clickable
    //$obj_img_calendrier = new img("images/module/b_calendar.png");
    $obj_img_calendrier = new img("images/kit-fugue/calendar-month.png");
    $obj_img_calendrier->setStyle('cursor:pointer');
     
    //le label
    $obj_font=new font($this->stri_label,true);
   
   //on met les objets dans leur attribut respectif
   $this->obj_text=$obj_text;
   $this->obj_img=$obj_img_calendrier;
   $this->obj_font=$obj_font;
   $this->obj_table=new table();
  }
 
  /*************************************************************
   * Permet de mettre dans le js l'installation du calendrier
   * parametres : aucun
   * retour : aucun
   *                        
   **************************************************************/       
 public function setupCalendar()
 {
  //pose des id du text et de l'image
  //$this->obj_text->setId("id_text_".$this->stri_name);
  $this->obj_text->setId("id_text_".$this->int_id);
  //$this->obj_img->setId("id_img_".$this->stri_name);
  $this->obj_img->setId("id_img_".$this->int_id);
  
  $obj_js=(get_class($this->obj_javascripter)=="javascripter")?$this->obj_javascripter:new javascripter();
  $this->obj_javascripter=$obj_js;
   //le js d'initialisation du calendrier
    $obj_js->addFunction("
        Calendar.setup(
        {
            inputField     :    '".$this->obj_text->getId()."',     // id of the input field
            ifFormat       :    '".$this->stri_ifFormat."',      // format of the input field
            button         :    '".$this->obj_img->getId()."',  // trigger for the calendar (button ID)
            align          :    '".$this->stri_align."',           // alignment (defaults to 'Bl')
            singleClick    :     ".$this->stri_singleClick.",
            showsTime      :     ".$this->stri_showsTime."          
        });
   ");
 }
 
 public function htmlValue()
 { 
   $this->setupCalendar();
   //on met les éléments dans une table html
   $obj_table=$this->obj_table;
   $obj_table->setWidth('100%');
    $obj_tr=$obj_table->addTr();
      $obj_td = $obj_tr->addTd($this->obj_font->htmlValue());
      $obj_td->setWidth('45%');
      $obj_tr->addTd($this->obj_text->htmlValue());
      $obj_tr->addTd($this->obj_img->htmlValue())->setAlign("left");
   $obj_table->setBorder(0);
    
  return $this->stri_style_css.$obj_table->htmlValue().$this->obj_javascripter->javascriptValue();
 }
 
  /*************************************************************
   * Permet d'initialiser le javascript qui ne doit être exécuté qu'une seule fois
   * parametres : aucun
   * retour : aucun
   *                        
   **************************************************************/       
   public function initialiseJs()
   {
     //traitement de la langue à utiliser
     $arra_langue=array("fra"=>"fr","eng"=>"en","esp"=>"es");//correspondance des langues entre le cms et l'objet js
     $stri_user_langue=pnUserGetLang();
     $stri_langue=(isset($arra_langue[$stri_user_langue]))?$arra_langue[$stri_user_langue]:"fr";//si pas de correspondance de langue, par défaut c'est le français
     
     $obj_js=new javascripter(); 
     $obj_js->addFile('includes/classes/jscalendar/calendar.js');
     $obj_js->addFile('includes/classes/jscalendar/lang/calendar-'.$stri_langue.'.js');    
     $obj_js->addFile('includes/classes/jscalendar/calendar-setup.js');
     $this->obj_javascripter=$obj_js;
   
   }
   
   /*************************************************************
   * Permet de désactiver le calendrier
   * parametres : bool : true  : le calendrier est inactif
   *                     false : le calendrier est actif    
   * retour : aucun
   *                        
   **************************************************************/       
   public function setDisabled($bool)
   {
    $this->obj_text->setDisabled($bool);
    //$stri_style=($bool)?"display : none;":"";
    //$this->obj_img->setStyle($stri_style);
   }
   
    /*************************************************************
   * Permet de réinitialiser le nombre d'instance de la classe
   * parametres :aucun 
   * retour : aucun
   *                        
   **************************************************************/    
   public static function resetInstance()
   {
    self::$int_nb_instance=0;
   }
   
  //**** clonage  *********************************************************
  public function __clone()
  {
     js_calendar::$int_nb_instance++;
     $this->int_id=js_calendar::$int_nb_instance;    
     $this->obj_img=clone($this->obj_img);               
     $this->obj_text=clone($this->obj_text);              
     $this->obj_font=clone($this->obj_font);
     $this->obj_table=clone($this->obj_table);
     $this->obj_javascripter=new javascripter();       
  }


}




?>
