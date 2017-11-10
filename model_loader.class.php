<?php

/*******************************************************************************
Create Date : 17/12/2011
 ----------------------------------------------------------------------
 Class name : model_loader
 Version : 1.0
 Author : SOLEILLANT Remy
 Description : Permet de créer l'interface de chargement d'un modèle
********************************************************************************/

class model_loader 
{
 //**** attribute ***********************************************************
  protected $stri_model;                //Le nom du modèle
  protected $arra_champ_affiche;       //La liste des champs à afficher
  protected $arra_champ_transmis;     //La liste des champs à transmettre en post
  protected $arra_champ_recu;         //La liste des champs reçu
  protected $arra_model;              //La liste des modèles à représenter
  protected $stri_load_sql;           //Le sql de chargement des modèles
   //**** constructor ******************************************************** 

  /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe  mvc_gen _manager  
   *                        
   **************************************************************/         
  function __construct($stri_model) 
  {
   $this->stri_model=$stri_model;
  }   
 
 //**** setter *****************************************************************
  public function setModel($value){$this->stri_model=$value;}
  public function setChampAffiche($value){$this->arra_champ_affiche=$this->arrayUpper($value);}
  public function setChampTransmis($value){$this->arra_champ_transmis=$this->arrayUpper($value);}
  public function setChampRecu($value){$this->arra_champ_recu=$this->arrayUpper($value);}
  public function setArraModel($value){$this->arra_model=$value;}
  public function setLoadSql($value){$this->stri_load_sql=$value;}

 //**** getter *****************************************************************
  public function getModel(){return $this->stri_model;}
  public function getChampAffiche(){return $this->arra_champ_affiche;}
  public function getChampTransmis(){return $this->arra_champ_transmis;}
  public function getChampRecu(){return $this->arra_champ_recu;}
  public function getArraModel(){return $this->arra_model;}
  public function getLoadSql(){return $this->stri_load_sql;}

 //**** Méthode de traitement général ******************************************
  /*************************************************************
  Méthode de mettre en majuscule toutes les éléments d'un tableau
 
 Paramètres: aucun
 Retour : string : le code html
  
  **************************************************************/     
  public function arrayUpper($arra_data)
  {  
   $arra_res=array();
   foreach($arra_data as $stri_data)
   {
    $arra_res[]=strtoupper($stri_data);
   }
   
   return $arra_res;
  }

 
  /*************************************************************
  Méthode d'affichage de l'écran de chargement
 
 Paramètres: aucun
 Retour : string : le code html
  
  **************************************************************/     
  public function buildLoadSql()
  {  
    $stri_select="select ".implode(",", $this->arra_champ_affiche);
    $stri_from="from ".$this->stri_model;
    $stri_where="where ".implode("and", $this->arra_champ_recu);
  }

  
   /*************************************************************
  Permet de retrouver le getter associé au champ
 
 Paramètres: aucun
 Retour : mixed :
                  string : le nom de la méthode 
                  bool : false : la méthode n'a pas été trouvée
  
  **************************************************************/     
 public function retrieveGetter($stri_champ)
 {
  $stri_champ_unif=strtolower(str_replace("_", "", $stri_champ));
  $arra_method=get_class_methods($this->stri_model);
  
  foreach($arra_method as $stri_methode)
  {
   $stri_methode_unif=strtolower(substr($stri_methode,2));
   if($stri_methode_unif==$stri_champ_unif)
   {
    return $stri_methode;
   }
  }
 
  return false;
 }

   /*************************************************************
  Construction du chargeur à partir d'une collection de modèle
 
 Paramètres: aucun
 Retour : obj  : le tableau html
  
  **************************************************************/     
  public function htmlValueByModelCollection()
  {   
    $obj_table=new table();
    //pose des entête
    $obj_tr=$obj_table->addTr();
        $obj_tr->setClass("titre3");
    foreach($this->arra_champ_affiche as $stri_champ)
    {
      $obj_tr->addTd($stri_champ);
    }
    
    //pose des lignes
    foreach($this->arra_model as $obj_sub_model)
    {
       $obj_tr=$obj_table->addTr();
       
       //traitement des champs à afficher  
       foreach($this->arra_champ_affiche as $stri_champ)
       {
        $stri_getter=$this->retrieveGetter($stri_champ);
        $stri_value=$obj_sub_model->$stri_getter();
        
        $obj_last_td=$obj_tr->addTd($stri_value);
       }       
      $stri_last_value=$obj_last_td->getValue();
      $arra_value=array($stri_last_value);
       //traitement des champs à transmettre
       foreach($this->arra_champ_transmis as $stri_champ)
       {
        $stri_getter=$this->retrieveGetter($stri_champ);
        $stri_value=$obj_sub_model->$stri_getter();
        $stri_type=(is_numeric($stri_value))?"int":"stri";
        $obj_hidden=new hidden($this->stri_model."__".$stri_type."_".strtolower($stri_champ),$stri_value);
        $arra_value[]=$obj_hidden;
       }
       
       $obj_last_td->setValue($arra_value);
    }
    
    return  $obj_table;
  }

   /*************************************************************
  Construction du chargeur à partir d'une collection de modèle
 
 Paramètres: aucun
 Retour : obj  : le tableau html
  
  **************************************************************/     
  public function htmlValueBySql()
  {   
    $obj_table=new table();
    //pose des entête
    $obj_tr=$obj_table->addTr();
        $obj_tr->setClass("titre3");
    foreach($this->arra_champ_affiche as $stri_champ)
    {
      $obj_tr->addTd($stri_champ);
    }
    
    //exécution du sql
    $obj_query=new querry_select($this->stri_load_sql);
    $arra_res=$obj_query->execute("assoc");
    
      
    //pose des lignes
    foreach($arra_res as $arra_one_res)
    {
       $obj_tr=$obj_table->addTr();
          
       //traitement des champs à afficher  
       foreach($this->arra_champ_affiche as $stri_champ)
       {
        $stri_value=$arra_one_res[$stri_champ]; 
            
        $obj_last_td=$obj_tr->addTd($stri_value);
        $stri_last_value=$stri_value;
       }       
      //$stri_last_value=$obj_last_td->getValue();
     
      $arra_value=array($stri_last_value);
       //traitement des champs à transmettre
       foreach($this->arra_champ_transmis as $stri_champ)
       {
                  
        $stri_value=$arra_one_res[$stri_champ];
        $stri_type=(is_numeric($stri_value))?"int":"stri";
       
        $obj_hidden=new hidden($this->stri_model."__".$stri_type."_".strtolower($stri_champ),$stri_value);
        $arra_value[]=$obj_hidden;
       }
       
       $obj_last_td->setValue($arra_value);
       
       
    }
    
    return  $obj_table;
  }

     
 /*************************************************************
  Méthode d'affichage de l'écran de chargement
 
 Paramètres: aucun
 Retour : string : le code html
  
  **************************************************************/     
  public function htmlValue()
  {  
   if(count($this->arra_model)>0)//si on doit construire à partir d'une collection de modèle
   {$obj_table=$this->htmlValueByModelCollection();}
  
   if(strlen($this->stri_load_sql)>0)//construction à partir d'un sql de chargement
   {$obj_table=$this->htmlValueBySql();}
   
   
    global $bgcolor1,$bgcolor2,$bgcolor3,$bgcolor4,$bgcolor5;
    $obj_javascripter=new javascripter();
    $obj_javascripter->addFunctionOnce("
    function loadModel(obj_tr)
    {
      var action='".substr($_SERVER['REQUEST_URI'],1)."';
      var form=document.createElement('form');
          form.method='post';
          form.action=action;
      var table=document.createElement('table');
      var clone=obj_tr.cloneNode(true);
      var input_load=document.createElement('input');
          input_load.type='hidden';
          input_load.name='load';
          input_load.value='1';
      table.appendChild(clone); 
      form.appendChild(table);
      form.appendChild(input_load);
      document.body.appendChild(form);
      form.submit(); 
    }
                                            ");
    
    
    $obj_table->alernateColor(1,$bgcolor3,$bgcolor1);          
    $obj_table->makeTrSelectionable(1,"loadModel(this);",$bgcolor2,"",1);
    $obj_table->setWidth("100%");
   
   return $obj_javascripter->javascriptValue().$obj_table->htmlValue();
  }
  

}
?>
