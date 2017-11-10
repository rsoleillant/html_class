<?php
/*******************************************************************************
Create Date : 29/05/2006
 ----------------------------------------------------------------------
 Class name : querry_insert
 Version : 1.1
 Author : Rémy Soleillant
 Description : permet de gérer une requette de type update
 Update : le 20 fev 2008
********************************************************************************/

include_once("querry.class.php");
class querry_update extends querry {
   
   /*attribute***********************************************/
   
   protected $arra_field=null;
   protected $arra_key=null;
   protected $stri_table_name;
   protected $bool_correct_querry=true;
  
  /* constructor***************************************************************/
  /* START -- MODIF EM 11-01-2008 */
  function __construct($string,$dbconn="NULL") 
  {
    //construit l'objet querry_update (create object querry_update)
    //@param : $string : nom de la table mise à jour (table's name updated)
    //@param : $dbconn : chaine de connexion -- A SAISIR SEULEMENT POUR LES TRANSACTIONS --(connection -- put only for transaction --)
    //@return : void
    
    $this->stri_table_name=$string;
    $this->arra_dbconn=($dbconn=="NULL")?pnDBGetConn():$dbconn;
    $this->bool_transaction=($dbconn=="NULL")?false:true;
  }
  /* END -- MODIF EM 11-01-2008 */


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
   
  public function getFieldByName($string)
  {
   $i=0;
   while(($string!=$this->arra_field[$i]['field'])&($i<count($this->arra_field)))
   {$i++;}
   return $this->arra_field[$i];
  }
   
  public function getKey()
  {
    return $this->arra_key;
  } 
  
   public function getTable()
  {
    return $this->stri_table_name;
  } 
  
  public function getCorrectQuerry()
  {return $this->bool_correct_querry;}
  
  /*other method****************************************************************/
  public function addField($stri_name,$stri_value,$stri_type_value="string")
  {
    $obj_controler=new data_controler();
    
    //permet de remplacer certain caractère spéciaux
    $arra_replace=array("’"=>"'","€"=>"E","–"=>"-");
    $stri_value=strtr($stri_value, $arra_replace);
    
    //remplacement des simples cotes échapées par une simple cote
    $stri_value=str_replace("''","'",$stri_value);
    //échappement sql des simples cotes
    $stri_value=str_replace("'","''",$stri_value);
    
    //echo"<br />Valeur : $stri_value => Type : $stri_type_value ";var_dump($obj_controler->controle($stri_value,$stri_type_value));
    $mixed_controle=$obj_controler->controle($stri_value,$stri_type_value);
    
    if($mixed_controle!==false) //si le controle est ok
    {
      $arra_temp['field']=$stri_name;
      $arra_temp['value']="'".$stri_value."'";
      $int_nb_field=count($this->arra_field);
      if($stri_type_value=="time"){$arra_temp['value']="to_date('01/01/2000_".$stri_value."', 'DD/MM/YYYY_HH24:MI:SS')";}
      //si la date est de type long ou non typée, on met a jour avec le format long
      if(($stri_type_value=="date")||($stri_type_value=="untypeddate")){$arra_temp['value']="to_date('".$stri_value."', 'DD/MM/YYYY_HH24:MI:SS')";}
      if($stri_type_value=="sdate"){$arra_temp['value']="to_date('".$stri_value."', 'DD/MM/YYYY')";}
      if($stri_type_value=='integer'){$arra_temp['value']=$stri_value;}
      if($stri_type_value=="autodate"){$arra_temp['value']="to_date('".$stri_value."', '".$mixed_controle."')";}
    
      $arra_temp['value']=(($stri_value=="")&&($stri_type_value!="string"))?"null":$arra_temp['value']; //RS 30/11/2011 ajout de gestion de null sur les champs non string
      
      $this->arra_field[$int_nb_field]=$arra_temp;
    }
    else
    {
      $this->bool_correct_querry=false;
    }    
  }
  
  public function addKey($stri_name,$stri_value,$stri_type_value="string",$stri_comparateur="=")
  {
    $obj_controler=new data_controler();
    //echo"<br />KEY--> Valeur : $stri_value => Type : $stri_type_value ";var_dump($obj_controler->controle($stri_value,$stri_type_value));
    //si le controle de la valeur est correct
    if($obj_controler->controle($stri_value,$stri_type_value))
    {
      //ajoute la clef
      $arra_temp['field']=$stri_name;
      $arra_temp['value']=$stri_value;
      $arra_temp['comparateur'] = $stri_comparateur;
      if($stri_type_value=='string'){$arra_temp['value']="'".$stri_value."'";}
      $int_nb_field=count($this->arra_key);
      $this->arra_key[$int_nb_field]=$arra_temp;
    }
    else
    {
      //sinon retourne une erreur sur la requete
      $this->bool_correct_querry=false;
    }
  }
  
  public function generateSql()
  {
    //genere la requete de mise à jour pour le debug
    //@return : [string] => requete de mise à jour
    
    //initialisation
    $update="UPDATE ".$this->stri_table_name." ";
    $set=" SET ";
    $where=" WHERE ";
    
    //construit la partie SET de la requete
    $int_nb_field=count($this->arra_field);
    for($i=0;$i<$int_nb_field-1;$i++)
    {$set.=$this->arra_field[$i]['field']."=".$this->arra_field[$i]['value'].", ";}
    $set.=$this->arra_field[$i]['field']."=".$this->arra_field[$i]['value']." ";
    
    //construit la partie WHERE de la requete
    $int_nb_key=count($this->arra_key);
    for($i=0;$i<$int_nb_key-1;$i++)
    {$where.=$this->arra_key[$i]['field'].$this->arra_key[$i]['comparateur'].$this->arra_key[$i]['value']." AND ";}
    $where.=$this->arra_key[$i]['field'].$this->arra_key[$i]['comparateur'].$this->arra_key[$i]['value']." ";
    
    //concatene toutes les parties de la requete
    $this->stri_sql=$update.$set.$where;
    
    return $this->stri_sql;
  }
  
  public function execute()
  {
    $bool_ok=false;
   // echo"<br />Verif : ";var_dump($this->bool_correct_querry);
  
    if($this->bool_correct_querry)
    {
      $this->stri_sql=$this->generateSql();
      
      //si la requete fait partie d'une transaction, on utilise une connexion différente d'une requete normale (put connection)
      $dbconn=($this->bool_transaction)?$this->arra_dbconn:$this->arra_dbconn[0];  
      $result = $dbconn->Execute($this->stri_sql);
      $bool_ok=($dbconn->ErrorNo()!=0)?false:true;
      
      
      if (!$result)
      {
            //- Trace de l'erreur
            $this->triggerError($dbconn->ErrorMsg(), $dbconn->ErrorNo(), $this->stri_sql);
      }
      /*//si un rapport doit être effectué
      if($this->bool_log)
      {
        $f=fopen("modules/Contrat/includes/rapport.log","a");
        $today=date("d/m/Y_H:i:s");
        $stri_t=($this->bool_transaction)? "[Transaction]" : "";
        $stri_r=($bool_ok)?"Reussi":"Erreur";
        $string="\n UDPATE $stri_t le $today $stri_r => ".str_replace("\n"," ",$this->stri_sql);
        fwrite($f, $string);
        fclose($f);
      }*/
    }
    return $bool_ok;
  } 

}

?>
