<?php
/*******************************************************************************
Create Date : 29/05/2006
 ----------------------------------------------------------------------
 Class name : querry_insert
 Version : 1.2
 Author : Rémy Soleillant
 Description : permet de gérer une requette de type insert
 Update : le 20 fev 2008
********************************************************************************/

include_once("querry.class.php");
include_once("querry_select.class.php");
class querry_insert extends querry {
   
   /*attribute***********************************************/
   
   protected $arra_field=null;
   protected $stri_table_name;
   protected $bool_correct_querry=true;
   protected $stri_error_msg="";
  
  /* constructor***************************************************************/
  function __construct($string,$dbconn="NULL") 
  {
    //construit l'objet querry_insert (create object querry_insert)
    //@param : $string : nom de la table dans laquelle on souhaite faire une insertion (table's name for insert)
    //@param : $dbconn : chaine de connexion -- A SAISIR SEULEMENT POUR LES TRANSACTIONS --(connection -- put only for transaction --)
    //@return : void
    
    $this->stri_table_name=$string;
    $this->arra_dbconn=($dbconn=="NULL")?pnDBGetConn():$dbconn;
    $this->bool_transaction=($dbconn=="NULL")?false:true;
  }
  
   /*setter*********************************************************************/
  public function setFieldByName($field_name,$field_value)
  {
     $i=0;
   while(($field_name!=$this->arra_field[$i]['field'])&($i<count($this->arra_field)))
   {$i++;}
   $this->arra_field[$i]['value']=$field_value;
  }
  
  /*getter**********************************************************************/
 
  public function getField()
  {
    return $this->arra_field;
  }
  
  public function setField($arra_field)
  {
    $this->arra_field = $arra_field;
  }
   
   
  public function getFieldByName($string)
  {
   $i=0;
   while(($string!=$this->arra_field[$i]['field'])&($i<count($this->arra_field)))
   {$i++;}
   return $this->arra_field[$i];
  } 
  
  
   public function getTable()
  {
    return $this->stri_table_name;
  } 

  public function getCorrectQuerry()
  {return $this->bool_correct_querry;}
  
  
  public function getNewPrimaryKey($field)
  { //génération de la requête, le rand sert à éviter que cette requete soit mise en cache
    /*$req=new querry_select("SELECT $field ,".rand(0,100)."
                            FROM "._SUPER_USER_BDD.".".$this->stri_table_name."  
                            WHERE ROWNUM<2 
                            ORDER BY $field DESC 
    
                          "); */
     $req=new querry_select("SELECT max($field) ,".rand(0,100)."
                             FROM "._SUPER_USER_BDD.".".$this->stri_table_name."  
                            ");
    $req->execute();
    $temp=$req->getIemeResult(0);
    $res=$temp[0]+1;

    return $res;
  }
  /*other method****************************************************************/  
  public function addField($stri_name,$stri_value,$stri_type_value="string")
  { 
    $obj_controler=new data_controler();
   
    //gestion des entiers qui peuvent être null
     if((($stri_value=="")&&($stri_type_value=="integer")))
     {
      $stri_type_value="string";
     }
    
    /*echo"<br> object controlé $stri_value de type $stri_type_value résultat";
    var_dump($obj_controler->controle($stri_value,$stri_type_value));
    echo "<br>"; */
    
    //permet de remplacer certain caractère spéciaux
     $arra_replace=array("’"=>"'","€"=>"E","–"=>"-");
    $stri_value=strtr($stri_value, $arra_replace);

    //remplacement des simples cotes échapées par une simple cote
    $stri_value=str_replace("''","'",$stri_value);
    //échappement sql des simples cotes
    $stri_value=str_replace("'","''",$stri_value);
    
    $mixed_controle=$obj_controler->controle($stri_value,$stri_type_value);
   
    if($mixed_controle!==false) //si le controle est ok
    {  
     $arra_temp['field']=$stri_name;
     $arra_temp['value']="'".$stri_value."'";
     $int_nb_field=count($this->arra_field);
     if($stri_type_value=="time")
     {$arra_temp['value']="to_date('01/01/2000_".$stri_value."', 'DD/MM/YYYY_HH24:MI:SS')";}
     if(($stri_type_value=="date")||($stri_type_value=="untypeddate"))
     //si la date est longue ou non typée, on insère avec le format long
     //{$arra_temp['value']="to_date('".$stri_value."', 'DD/MM/YYYY')";}
     {$arra_temp['value']="to_date('".$stri_value."', 'DD/MM/YYYY_HH24:MI:SS')";}
     if($stri_type_value=="sdate")
     {$arra_temp['value']="to_date('".$stri_value."', 'DD/MM/YYYY')";}
     
     if($stri_type_value=="autodate")
     {
      $arra_temp['value']="to_date('".$stri_value."', '".$mixed_controle."')";
     }
    
     //{$arra_temp['value']="to_date('".$stri_value."', 'DD/MM/YYYY_HH24:MI:SS')";}     
     if($stri_type_value=='integer')
     {$arra_temp['value']=$stri_value;}
     
      $arra_temp['value']=(($stri_value=="")&&($stri_type_value!="string"))?"null":$arra_temp['value']; //RS 30/11/2011 ajout de gestion de null sur les champs non string
     
     
     $this->arra_field[$int_nb_field]=$arra_temp;
    }
    else
    {$this->bool_correct_querry=false;}  
  }
  
  public function generateSql()
  {
    //genere la requete d'insertion pour le debug
    //@return : [string] => requete d'insertion
    
    //initialisation
    $insert_into="INSERT INTO ".$this->stri_table_name." ";
    $field="(";
    $value="VALUES (";
    
    //construit la partie CHAMP et VALUES de la requete
    $int_nb_field=count($this->arra_field);
    for($i=0;$i<$int_nb_field-1;$i++)
    {
      $field.=$this->arra_field[$i]['field'].", ";
      $value.=$this->arra_field[$i]['value'].", ";
    }
    $field.=$this->arra_field[$i]['field'].") ";
    $value.=$this->arra_field[$i]['value'].")";
    
    //concatene toutes les parties de la requete
    $this->stri_sql=$insert_into.$field.$value;
    
    return $this->stri_sql;
  }
  
  public function execute()
  {
    $bool_ok=false; 
    $this->stri_sql=$this->generateSql();
    
    //si la requete fait partie d'une transaction, on utilise une connexion différente d'une requete normale (put connection)
    $dbconn=($this->bool_transaction)?$this->arra_dbconn:$this->arra_dbconn[0];
        
    $result = $dbconn->Execute($this->stri_sql);
    $bool_ok=($dbconn->ErrorNo()!=0)? false : true;
    
    if(!$bool_ok){
    $this->stri_error_msg = $dbconn->ErrorMsg();
    }
    
    if (!$result)
    {
        $this->triggerError($dbconn->ErrorMsg(), $dbconn->ErrorNo(), $this->stri_sql);
    }
    
    /*//si un rapport doit être effectué
    if($this->bool_log)
    {
      $f=fopen("modules/Contrat/includes/rapport.log","a");
      $today=date("d/m/Y_H:i:s");
      $stri_t=($this->bool_transaction)? "[Transaction]" : "";
      $stri_r=($bool_ok)?"Reussi":"Erreur";
      $string="\n INSERT $stri_t le $today $stri_r => ".$this->stri_sql;
      fwrite($f, $string);
      fclose($f);
    }*/
    
    return $bool_ok;
  }
  
  
  public function getError(){
    if(!empty($this->stri_error_msg))
      echo "<font size=2 color=red>SQL ERROR : ".$this->stri_error_msg."  <br>  sql : $this->stri_sql </font>";

  }
  
  public function getErrorMessage()
  {  
    return $this->stri_error_msg;
  }

}
?>
