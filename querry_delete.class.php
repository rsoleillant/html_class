<?php
/*******************************************************************************
Create Date : 13/06/2006
 ----------------------------------------------------------------------
 Class name : querry_delete
 Version : 1.0
 Author : Rémy Soleillant
 Description : permet de gérer une requette de type delete
 Update : le 20 fev 2008
********************************************************************************/

include_once("querry.class.php");
class querry_delete extends querry {
   
   /*attribute***********************************************/
   
   
   protected $arra_key=null;
   protected $stri_table_name;
   protected $bool_correct_querry=true;
  
  /* constructor***************************************************************/
  /* START -- MODIF EM 11-01-2008 */
  function __construct($string,$dbconn="NULL") 
  {
    //construit l'objet querry_delete (create object querry_delete)
    //@param : $string : nom de la table dans laquelle on souhaite supprimer (table's name for delete)
    //@param : $dbconn : chaine de connexion -- A SAISIR SEULEMENT POUR LES TRANSACTIONS --(connection -- put only for transaction --)
    //@return : void
    
    $this->stri_table_name=$string;
    $this->arra_dbconn=($dbconn=="NULL")?pnDBGetConn():$dbconn;
    $this->bool_transaction=($dbconn=="NULL")?false:true;
  }
  /* END -- MODIF EM 11-01-2008 */
  
  
   /*setter*********************************************************************/
  
  
  /*getter**********************************************************************/
 
 
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
   public function addKey($stri_name,$stri_value,$stri_type_value="string",$stri_operateur="=") {
    $obj_controler=new data_controler();
    if($obj_controler->controle($stri_value,$stri_type_value)) {
      $arra_temp['field']=$stri_name;
      $arra_temp['value']=$stri_value;
      $arra_temp['operateur']=$stri_operateur;
      if($stri_type_value=='string') {
        $arra_temp['value']="'".$stri_value."'";
      }
      $int_nb_field=count($this->arra_key);
      $this->arra_key[$int_nb_field]=$arra_temp;
    }
    else {
      $this->bool_correct_querry=false;
    }
  }
  
  public function generateSql()
  {
    //genere la requete de mise à jour pour le debug
    //@return : [string] => requete de mise à jour
    
    //initialisation
    $delete="DELETE FROM ".$this->stri_table_name." ";
    $where=" WHERE ";
    
    //construit la partie WHERE de la requete
    $int_nb_key=count($this->arra_key);
    for($i=0;$i<$int_nb_key-1;$i++)
    {$where.=$this->arra_key[$i]['field'].$this->arra_key[$i]['operateur'].$this->arra_key[$i]['value']." AND ";}
    $where.=$this->arra_key[$i]['field'].$this->arra_key[$i]['operateur'].$this->arra_key[$i]['value']." ";
    
    //concatene toutes les parties de la requete
    $this->stri_sql=$delete.$where;
    
    return $this->stri_sql;
  }
  
  public function execute()
  {
    $bool_ok=false;
    if($this->bool_correct_querry)
    {
      $this->stri_sql=($this->stri_sql=="")?$this->generateSql():$this->stri_sql;
      
      //si la requete fait partie d'une transaction, on utilise une connexion différente d'une requete normale (put connection)
      $dbconn=($this->bool_transaction)?$this->arra_dbconn:$this->arra_dbconn[0];  
      
      $result = $dbconn->Execute($this->stri_sql);
      $bool_ok=($dbconn->ErrorNo()!=0)? false : true;
      
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
        $string="\n DELETE $stri_t le $today $stri_r => ".$this->stri_sql;
        fwrite($f, $string);
        fclose($f);
      }*/
    }
    return $bool_ok;
  } 

}

?>
