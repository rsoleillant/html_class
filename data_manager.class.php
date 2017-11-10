<?php
/*******************************************************************************
Create Date : 19/09/2007
 ----------------------------------------------------------------------
 Class name : data_manager
 Version : 1.0
 Author : Rémy Soleillant
 Description : permet de manipuler des donnée de la base comme un objet. C'est la
               généralisation des classes incident,connexion, astreinteOffre...
********************************************************************************/
include_once("querry_select.class.php");
include_once("querry_insert.class.php");
include_once("querry_update.class.php");
include_once("querry_delete.class.php");
include_once("data_controler.class.php");

class data_manager {
   
   /*attribute***********************************************/
   protected $stri_table_name;//nom de la table dans laquelle trouver les données
   protected $stri_primary_key;//nom de la clef primaire de la table $stri_table_name
   protected $stri_primary_key_value;//valeur de la clef primaire à utiliser pour le chargement des données
   
   protected $arra_simple_attribut=array();//tableau contenant les différentes donnés simple extraite directement à partir de $stri_table
   protected $arra_complex_attribut=array();//tableau contenant les autre objets de type data_manager qui peuvent être utilisés
   protected $arra_default_value=array();//valeur des attributs par défaut
   
   public    $arra_sauv=array();//utilisé lors de la sérialisation
   /*--- structure des tableau ---
    $arra_simple_attribut[name][type]:type de l'attribut name en oracle
                               [type_php]: type de l'attribut en php 
                               [value]: valeur de l'attribut name     
 
    $arra_complex_attribut[name][n°]: nième objet name de type data_manager 
    -------------------------------*/
  
  /* constructor***************************************************************/
   function __construct($table,$pmKey,$pmKeyValue="") {
       $this->stri_table_name=strtoupper($table);
       $this->stri_primary_key=strtoupper($pmKey);
       $this->stri_primary_key_value=$pmKeyValue;
       
   }
 
  /*setter*********************************************************************/
  public function setPrimayKeyValue($value)
  {$this->stri_primary_key_value=$value;}
  
  /*-- setter générique d'attribut ---
    $name: nom de l'attribut
    $value: valeur de l'attribut
   ------------------------------------*/ 
  public function setAttribut($name,$value)
  {
   $this->arra_simple_attribut[$name]['value']=$value;
  }
  
   /*-- setter générique de type d'attribut ---
    $name: nom de l'attribut
    $type: type de l'attribut
   ------------------------------------*/ 
   public function setAttributType($name,$type)
  {
   $this->arra_simple_attribut[$name]['type']=$type;
  }
  
  /*-- setter générique d'attribut complex---
    $name: nom de l'attribut
    les autres paramètres sont les même que ceux du constructeur
   ------------------------------------*/ 
  public function setComplexAttribut($name,$table,$pmKey,$pmKeyValue)
  {
   $obj_dm=new data_manager($table,$pmKey,$pmKeyValue);
   $obj_dm->loadData();
   $int_num=count($this->arra_complex_attribut[$name]);
   $this->arra_complex_attribut[$name][$int_num]=$obj_dm;
  }
  
   /*-- permet de définir les valeur par défaut des attributs---
    le tableau est associatif. Les clefs du tableau sont les noms
    des champs dans la base de donnée
   ------------------------------------*/ 
  public function setDefaultValue($arra_value)
  {
   $this->arra_default_value=$arra_value;
   foreach($arra_value as $key=>$value)
   {
    $this->arra_simple_attribut[$key]['value']=$value;
   }
  }
   /*getter*********************************************************************/
 
  public function getTableName(){return $this->stri_table_name;}
  public function getPrimaryKey(){return $this->stri_primary_key;}
  public function getPrimaryKeyValue(){return $this->stri_primary_key_value;}
 
  /*-- getter générique d'attribut ---
    $name: nom de l'attribut
   ------------------------------------*/ 
  public function getAttribut($name)
  {
   return $this->arra_simple_attribut[$name]['value'];
  }
  
  public function getAttributType($name)
  {
   return $this->arra_simple_attribut[$name]['type'];
  }
  
   /*-- getter générique d'attribut complex ---
    $name: nom de l'attribut
    $num: numéro de l'attribut
   ------------------------------------*/ 
  public function getComplexAttribut($name,$num=0)
  {
   return $this->arra_complex_attribut[$name][$num];
  }
  
    /* method for serialization **************************************************/
   public function __sleep() {
     $this->arra_sauv['table_name']= $this->stri_table_name;
     $this->arra_sauv['primary_key']= $this->stri_primary_key;
     $this->arra_sauv['primary_key_value']= $this->stri_primary_key_value;

     foreach($this->arra_simple_attribut as $key=>$arra_data)
     {$arra_temp[$key]=serialize($arra_data);}
     $this->arra_sauv['arra_simple_attribut']=$arra_temp;
     
     foreach($this->arra_complex_attribut as $key=>$arra_data)
     {
      for($i=0;$i<count($arra_data);$i++)
      {
       $arra_temp[$i]=serialize($arra_data[$i]);
      }
      $arra_temp2[$key]=$arra_temp;
     }
     $this->arra_sauv['arra_complex_attribut']= $arra_temp2;
     $this->arra_sauv['default_value']= $this->arra_default_value;
     
     return array('arra_sauv');
   }
   
  public function __wakeup() {
    $this->stri_table_name= $this->arra_sauv['table_name'];
    $this->stri_primary_key= $this->arra_sauv['primary_key'];
    $this->stri_primary_key_value= $this->arra_sauv['primary_key_value'];

    $arra_temp=$this->arra_sauv['arra_simple_attribut'];
    foreach($arra_temp as $key=>$arra_data)
    {
     $this->arra_simple_attribut[$key]= unserialize($arra_data); 
    }
    
    $arra_temp=$this->arra_sauv['arra_complex_attribut'];
    foreach($arra_temp as $key=>$arra_data)
    {
     foreach($arra_data as $key2=>$obj)
     {
      $this->arra_complex_attribu[$key][$key2]= unserialize($obj);
     }
    }
     $this->arra_default_value= $this->arra_sauv['default_value'];
     $this->arra_sauv = array();
     
   }
  
  /*other method****************************************************************/
  //permet de charger le nom et le type de chaque attribut
  public function preLoadData()
  {
   $obj_query=new querry_select("SELECT column_name,data_type 
                                FROM all_tab_columns 
                                WHERE table_name='".$this->stri_table_name."'");
   $obj_query->execute();
   foreach($obj_query->getResult() as $arra_result)
   {
     $this->arra_simple_attribut[$arra_result[0]]['type']=$arra_result[1];
   }
   
   $this->convertTypeOracleToTypePhp();                             
  }
  
  /***************************
  permet de charger les donnée à partir d'un tableau
  ce tableau est associatif, la clef représente le champ dans la base
  et la valeur le nom du champ html contenant la donnée
   *************************/
  public function loadDataFromForm($arra_form)
  {
   foreach($arra_form as $bdd=>$html)
   {
    $value=(isset($_POST[$html]))?$_POST[$html]:$this->arra_default_value[$bdd];
    $this->arra_simple_attribut[$bdd]['value']=$value;
   }
  }
  
  //permet de charger la valeur des attributs
  public function loadData()
  {
   $this->preLoadData();
   $obj_query=new querry_select("select *
                                from ".$this->stri_table_name."
                                where ".$this->stri_primary_key."='".$this->stri_primary_key_value."'");
  
   $obj_query->execute("assoc");
   foreach($obj_query->getIemeResult(0) as $key=>$value)
   {
    //si c'est une date, on convertit son format
    $value=($this->arra_simple_attribut[$key]['type']=="DATE")?date("d/m/Y_H:i:s",strtotime($value)):$value;
    $this->arra_simple_attribut[$key]['value']=$value;
   }
  }
  
  //permet de convertir les types des attributs d'oracle en php
  private function convertTypeOracleToTypePhp()
  {
   foreach($this->arra_simple_attribut as $key=>$arra_data)
   {
    switch($arra_data['type'])
    {
     case "VARCHAR2":
        $arra_data['type_php']="string";
        break;
     case "NUMBER":
        $arra_data['type_php']="float";
        break;
     case "DATE":
        $arra_data['type_php']="untypeddate";
        break;   
     default:
         $arra_data['type_php']=strtolower($arra_data['type']);
         break;
    }
    $this->arra_simple_attribut[$key]=$arra_data;
   }
  }
  
  //permet de mettre à jour les donnée à partir des valeurs des attributs
  public function update($return="boolean")
  {
   $obj_query_update=new querry_update($this->stri_table_name);
   $obj_query_update->addKey($this->stri_primary_key,$this->stri_primary_key_value);
   foreach($this->arra_simple_attribut as $key=>$arra_attribut)
   {
    $obj_query_update->addField($key,$arra_attribut['value'],$arra_attribut['type_php']);
   }
   //echo $obj_query_update->generateSql()."<br />";
   if($obj_query_update->getCorrectQuerry())
    $bool_res=$obj_query_update->execute();
   else
     $bool_res=false;
   //permet de faire varier le type de retour, utile pour le débugage
   $mixed_res=($return=="boolean")?$bool_res:$obj_query_update->getSql();
   return $mixed_res; 
  }
  
  //permet d'enregistrer les données dans la base, renvoi true en cas de succès, false sinon
  public function insert($return="boolean")
  {
   //on charge les types de donnée qui seront utilisés pour la vérification de la cohérence
   $this->preLoadData();
   $obj_query_insert=new querry_insert($this->stri_table_name);
   //on prend une nouvelle clef primaire
   $int_key=$obj_query_insert->getNewPrimaryKey($this->stri_primary_key);
   $this->stri_primary_key_value=$int_key;
   $this->arra_simple_attribut[$this->stri_primary_key]['value']=$int_key;
  
   
   //on prépare la requête d'insertion
   foreach($this->arra_simple_attribut as $key=>$arra_attribut)
   {
    $obj_query_insert->addField($key,$arra_attribut['value'],$arra_attribut['type_php']);
   }
   //exécution de la requête
   if($obj_query_insert->getCorrectQuerry())
   { $bool_ok= $obj_query_insert->execute();   
   }
   else
   {$bool_ok=false;}
   
    //permet de faire varier le type de retour, utile pour le débugage
   $mixed_res=($return=="boolean")?$bool_ok:$obj_query_insert->generateSql();
   return $mixed_res; 
    
   
  }
  
  //permet d'effectuer un update ou un insert selon que $stri_primary_key_value est défini
  public function autoExecute()
  {
   if($this->stri_primary_key_value=="")
   {
    return $this->insert();
   }
   else
   {
    return $this->update();
   }
  }
  
}   



?>
