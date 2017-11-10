<?php
/*******************************************************************************
Create Date : 12/07/2007
 ----------------------------------------------------------------------
 Class name : select
 Version : 1.0
 Author : Rémy Soleillant
 Description : Bascule entre un select et un champ de saisi text
********************************************************************************/


class select_and_text 
{
   
   /*attribute***********************************************/
   protected $obj_select ;      //La liste déroulante
   protected $obj_text ;        //Le champ de saisi
   protected $obj_img ;         //L'image permettant la bascule entre les deux champs
   protected $obj_javascripter; //Le conteneur de code javascript
   protected $stri_width;       //La taille des objets en style css        
 
   static $int_nb_instance;     //Pour connaître le nombre d'instance de la classe
   protected $int_id;           //Pour que chaque instance dispose d'un id propre
   /* constructor***************************************************************/
   public function __construct($stri_name,$stri_value) 
   { 
     //construction de l'indentifiant
     self::$int_nb_instance++;
     $this->int_id=self::$int_nb_instance;
     
     $this->stri_width="100px";
     
     $this->initSubObject($stri_name,$stri_value);
     $this->initJavascript();
   }
  
  /*setter*********************************************************************/
  public function setWidth($value){$this->stri_width=$value;}
  public function setValue($value){ $this->selectOption($value);}
  public function setName($value){$this->obj_text->setName($value);}
  public function setDisabled($value)
  {
   $this->obj_text->setDisabled($value);
   $this->obj_select->setDisabled($value);
  }
  /*getter**********************************************************************/
  public function getSelect(){return $this->obj_select;}
  public function getText(){return $this->obj_text;}
  public function getImg(){return $this->obj_img;}
  public function getJavascripter(){return $this->obj_javascripter;}
  public function getId(){return $this->int_id;}
  public function getWidth(){return $this->stri_width;}

  
  
  /*other method****************************************************************/  
  /*************************************************************
  Permet d'initialiser les différents sous-objets
  Paramètres : string : le nom est post qui doit être transmis
               string : la valeur de l'objet
  Retour : aucun
          
  **************************************************************/   
  public function initSubObject($stri_name,$stri_value="")
  {
     //construction des différents sous-objets
     $stri_id_select="sat_select_".$this->int_id;
     $obj_select=new select("");
        $obj_select->setId($stri_id_select);
        $obj_select->setStyle("width:".$this->stri_width.";");        
        $obj_select->setOnchange("actualiseTextValue(this);");         
        
     $stri_id_text="sat_text_".$this->int_id;
     $obj_text=new text($stri_name,$stri_value);
        $obj_text->setId($stri_id_text);
        $obj_text->setStyle("width:".$this->stri_width.";"); 
        
     $obj_img=new img("images/add_out.gif");
        $obj_img->setStyle("cursor:pointer;margin:3px;");
        $obj_img->setId("sat_img_".$this->int_id);
        $obj_img->setOnclick("switch_select_and_text(this);");    
     
     //gestion de la valeur et de la première option
     if($stri_value!="")
     {$obj_select->addOption($stri_value,$stri_value);}
     
     //passage des sous-objets en attribut   
     $this->obj_select=$obj_select;
     $this->obj_text=$obj_text;
     $this->obj_img=$obj_img;   
  }
  
   /*************************************************************
  Permet d'initialiser les différents sous-objets
  Paramètres : aucun
  Retour : aucun
          
  **************************************************************/   
  public function initJavascript()
  {    
    $obj_javascripter=new javascripter();
    $obj_javascripter->addFunction("
    //Permet de passer de la liste déroulante au champ texte et inversement
     function switch_select_and_text(img)
     {
       //récupération de l'indice d'identification
        var last_underscore=img.id.lastIndexOf('_');
        var int_id=img.id.substring(last_underscore+1);
      
       var select=document.getElementById('sat_select_'+int_id);
       var text=document.getElementById('sat_text_'+int_id); 
       var td_select=select.parentNode;
       var td_text=text.parentNode;
     
       if(td_select.style.display=='none')//si la liste déroulante est cachée
       {
         td_select.style.display='';
         td_text.style.display='none';
         text.value=select.value;
       }
       else
       {  
         td_select.style.display='none';
         td_text.style.display='';
         text.value='';
       }
     }
    ");
    
    
    $obj_javascripter->addFunction("
     //Permet de mettre dans le champ text la valeur de la liste déroulante
     function actualiseTextValue(select)
     {
      //récupération de l'indice d'identification
        var last_underscore=select.id.lastIndexOf('_');
        var int_id=select.id.substring(last_underscore+1);
      
       var select=document.getElementById('sat_select_'+int_id);
       var text=document.getElementById('sat_text_'+int_id);
       
       text.value=select.value;
     }
    ");
    
       
   $this->obj_javascripter=$obj_javascripter;  
  }
 
   /*************************************************************
  Permet d'ajouter une option à la liste déroulante
  Paramètres :string : la valeur de l'option
              string : le libelle de l'option
  Retour : obj option : l'option rajoutée
          
  **************************************************************/  
   public function addOption($stri_valeur,$stri_libelle)
   {
    return $this->obj_select->addOption($stri_valeur,$stri_libelle);
   }
   
  /*************************************************************
  Permet d'ajouter des options à partir d'une requête SQL.
  Si la requête à une seule champ, la valeur et le libellé sont les même.
  Si la requête à plusieurs champ, le premier est la valeur, le deuxième le libellé
  et les autres champs sont ignorés.
  Paramètres :string : la requête SQL
  Retour : aucun
          
  **************************************************************/  
   public function addOptionBySql($stri_sql)
   {
    $obj_query=new querry_select($stri_sql);
    
    $arra_res=$obj_query->execute();
    
    $int_indice_valeur=0;
    $int_incide_libelle=1;
    if(count($arra_res[0])==1)//si la requête n'a qu'un champ
    { 
     $int_indice_valeur=0;
     $int_incide_libelle=0;
    }
    
    foreach($arra_res as $arra_one_res)
    {
      $this->addOption($arra_one_res[$int_indice_valeur],$arra_one_res[$int_incide_libelle]);
    }
   }    
   
    /*************************************************************
  Permet d'ajouter des options à partir d'un tableau. Les clefs du
  tableau sont les valeurs et le contenu les libellés
  Paramètres :array : le tableau des données
  Retour : aucun
          
  **************************************************************/  
   public function addOptionByArray($arra_data)
   {  
    foreach($arra_data as $stri_value=>$stri_libelle)
    { 
      $this->addOption($stri_value,$stri_libelle);
    }
   }  
      
  /*************************************************************
  Permet de sélectionner une option
  Paramètres :string : la valeur de l'option à sélectionner
              
  Retour : aucun
          
  **************************************************************/  
   public function selectOption($stri_valeur)
   {
    $res=$this->obj_select->selectOption($stri_valeur);
    $this->obj_text->setValue($stri_valeur);
   }
 
   /*************************************************************
  Permet d'obtenir le code HTML de l'objet
  Paramètres :aucun
  Retour : string : le code HTML
          
  **************************************************************/  
  public function htmlValue()
  { 
    $obj_table=new table();
    $obj_table->setWidth('100%');
      $obj_tr=$obj_table->addTr();
        $obj_td=$obj_tr->addTd($this->obj_text->htmlValue());
          $obj_td->setStyle("display:none;width:95%");
          $obj_td->setId("sat_td_text_".$this->int_id);
        $obj_td=$obj_tr->addTd($this->obj_select->htmlValue());
          $obj_td->setId("sat_td_select_".$this->int_id);
          $obj_td->setStyle("width:95%");
        $obj_td = $obj_tr->addTd($this->obj_img->htmlValue());
            $obj_td->setStyle("width:5%");
     
        
    return $this->obj_javascripter->javascriptValue().$obj_table->htmlValue();
  }
  
  //**** clonage  *********************************************************
  public function __clone()
  {
     self::$int_nb_instance++;
     $this->int_id=self::$int_nb_instance;
     
     $stri_name=$this->obj_text->getName();
     $stri_value=$this->obj_text->getValue();
     $arra_option=$this->obj_select->getOption();
    
     $this->initSubObject($stri_name,$stri_value);
     $this->obj_select->setOption($arra_option);
     $this->obj_javascripter=new javascripter();    
  }
}
?>
