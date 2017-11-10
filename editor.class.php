<?php
/*******************************************************************************
Create Date : 18/02/2010
 ----------------------------------------------------------------------
 Class name : editor
 Version : 1.0
 Author : Yoann Frommelt
 Description : permet de créer un objet CKEditor comme un textarrea
********************************************************************************/

class editor extends text_arrea {
   
   //**** attribute ************************************************************
   
   protected $obj_ckeditor; // objet ckeditor
   protected $stri_name = 'ckeditor'; // attribu name de l'editeur
   protected $stri_width=''; // largeur de l'editeur
   protected $stri_height=200; // hauteur de l'editeur
   protected $stri_toolbar = 'MyToolbar';//Basic'; // toolbar de l'editeur
   protected $stri_skin = 'moono_blue';
   protected $stri_base_path = '/includes/classes/ckeditor/ckeditor_4.3/';
   protected $stri_langue = 'fr';
   protected $int_max_length; // longeur du text max pour le compteur de caractere
   protected $int_tabindex=0;
   protected $bool_auto_save; //pour activer la sauvegarde automatique en locale  
  
  //**** constructor ***********************************************************
  function __construct($stri_name='',$stri_value='') {

    if ($stri_name != '') {
      $this->stri_name = $stri_name;
      $this->stri_id = $stri_name;
    }
    if ($stri_value != '') {
      $this->stri_value = $stri_value; 
    }
    
    $this->bool_auto_save=false;//par défaut, pas d'activation de sauvarde automatique
  }
 
  //**** setter ****************************************************************
  public function setName($value) {
    $this->stri_name = $value;
    $this->stri_id = $value;
  }

  public function setWidth($value) {
    $this->stri_width = $value;
   
  }
  public function setHeight($value) {
    $this->stri_height = $value;
  }
  public function setToolbar($value) {
    $this->stri_toolbar = $value;
  }
  public function setSkin($value) {
    $this->stri_skin = $value;
  }
  public function setBasePath($value) {
    $this->stri_base_path=$value;
  }
  public function setLangue($value) {
    if (isset($value)) {
      $this->stri_langue=$value;
    }
    else {
      switch ($_SESSION['PNSVlang']) {
        case 'fra' : 
          $this->stri_langue = 'fr';
          break;
        case 'eng' : 
          $this->stri_langue = 'en';
          break;
        case 'enu' : 
          $this->stri_langue = 'en';
          break;
        case 'spa' : 
          $this->stri_langue = 'es';
          break;
      }
    }
  }
  public function setCompteur($int_max_length=3800) {
    $this->int_max_length=$int_max_length;
  }
  public function setTabindex($value) {
    $this->int_tabindex=$value;
  }
  
  public function setId($value) {
    if($value != $this->stri_id) {
      echo("<script>alert('L\'id de l\'editor doit etre le meme que son Name. Dans le cas contraire cela peut provoquer des perturbations.');</script>");
    }
  }
  public function setAutoSave($value){$this->bool_auto_save=$value;}

  //**** getter ****************************************************************
  public function getCkeditor(){return $this->obj_ckeditor;}
  public function getName(){return $this->stri_name;}
  public function getValue(){return $this->stri_value;}
  public function getWidth(){return $this->stri_width;}
  public function getHeight(){return $this->stri_height;}
  public function getToolbar(){return $this->stri_toolbar;}
  public function getSkin(){return $this->stri_skin;}
  public function getBasePath(){return $this->stri_base_path;}
  public function getLangue(){return $this->stri_langue;}
  public function getClass(){return $this->stri_class;}
  public function getId(){return $this->stri_id;}
  public function getAutoSave(){return $this->bool_auto_save;}
 
  //**** public method *********************************************************
  
  public function JqueryValue() {
  
  
   $obj_javascripter = new javascripter();
   $stri_js=" 
   CKEDITOR.replace('".$this->stri_name."',
   {
   toolbar: '".$this->stri_toolbar."',
   //AllowedContent permet de ne pas filtrer par CKEditor les balises placées en attribut
   allowedContent: true, //Désactive le filtre
   language: '".$this->stri_langue."',
   skin: '".$this->stri_skin."',";
  
   if($this->stri_width!='')
   {$stri_js.="width:'".$this->stri_width."',";}
   
   $stri_js.="
   height:'".$this->stri_height."',
   tabIndex:". $this->int_tabindex.",
   wordcount:
            {
              showWordCount: false,

              // Whether or not you want to show the Char Count
              showCharCount: true,

               // Whether or not to include Html chars in the Char Count
               countHTML: true,";
              if($this->int_max_length!=NULL)
              {
                $stri_js.="    
                // Option to limit the characters in the Editor
                charLimit:".$this->int_max_length.",";
              }
  
    $stri_js.="  
                // Option to limit the words in the Editor
                wordLimit: 'unlimited'
              } 
    }
   );
 ";
   

    
    $obj_javascripter->addFunction($stri_js);
    $obj_javascripter2= new javascripter();
   
  $stri_bt_restaure='';  
  if($this->bool_auto_save)//si la sauvegarde automatique est activée
  {
    $obj_img_restaure=new img('images/bouee_sauvetage.png');
        $obj_img_restaure->setTitle(_HELP_RESTAURE_TEXTE);
        $obj_img_restaure->setStyle('cursor:pointer;width:20px;');
        $obj_img_restaure->setOnclick('editor_restaure();');
    $obj_javascripter2->addFunction("
    //Permet de sauvegarder le contenu de l'éditeur
    function editor_save()
    {
     for ( key in CKEDITOR.instances )//pour chaque instance de l'éditeur
     {                                            
        var obj_editeur=CKEDITOR.instances[key];    //récupération de l'instance
        var stri_txt=obj_editeur.getData();         //récupération des données contenues dans l'éditeur
        var save_index='editeur_'+obj_editeur.name; 
        
        if(stri_txt!='')
        {
         localStorage.setItem(save_index,stri_txt);  //sauvegarde en locale   
        }     
     } 
     window.setTimeout(editor_save,10000);//lancement de sauvegarde régulièrement
    }
    window.setTimeout(editor_save,10000);
    
    //Permet de restaurer le contenu de l'éditeur
    function editor_restaure()
    {
       for ( key in CKEDITOR.instances )//pour chaque instance de l'éditeur
       {                                            
          var obj_editeur=CKEDITOR.instances[key];    //récupération de l'instance          
          var save_index='editeur_'+obj_editeur.name; 
          obj_editeur.setData(localStorage.getItem(save_index));
       }            
    }
    
    ");
      $stri_bt_restaure=$obj_img_restaure->htmlValue();
  }      
   
    
  
  
    return $obj_javascripter->javascriptValue().$obj_javascripter2->javascriptValue().$stri_bt_restaure;
  }
  
  public function htmlValue() 
  {
   $this->setLangue();
   
   $obj_event_js = new javascripter();
    $obj_event_js->addFile($this->stri_base_path."ckeditor.js");
    $obj_event_js->addFile($this->stri_base_path."adapters/jquery.js");
        
    return  $obj_event_js->javascriptValue().parent::htmlValue().$this->JqueryValue();
  
  }
}

?>
