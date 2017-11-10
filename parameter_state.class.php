<?php

/*******************************************************************************
Create Date : 05/12/2007
 ------------------------------------------------------------------------------
 Class name : Parameter_field
 Version : 1.0
 Author : Emilie Merlat
 Description : permet de gérer les paramètres de type champ de la table GEN_PARAMETRE
*******************************************************************************/

class parameter_state extends parameter 
{
  //**** attribute *************************************************************
  protected $arra_parameter=array();      //tableau des paramètres dans le $_POST


  //**** constructor ***********************************************************
  public function __construct($arra_param,$int_user,$Modname,$stri_category)
  {    
    //classe mère
    $this->int_user=$int_user;   
    $this->stri_module=$Modname;
    $this->stri_category=$stri_category;
    //classe fille
    $this->arra_parameter=$arra_param;
    
    $this->loadCache();
  }
  
  //**** setter ****************************************************************
  public function setParameter($arra_param)
  {
    $this->arra_parameter=$arra_param;
  }
    
  //**** getter ****************************************************************
  public function getParameter()
  {
    return $this->arra_parameter;
  }
  
  //**** other method **********************************************************
  public function update($int_param)
  {
    //permet d'enregistrer un paramètre (this function allows to update the state of onglet)
    //@param : $int_param : identifiant du paramètre (param's ID)
    //@param : $int_user : identifiant de l'utilisateur actuel (courant user)
    //@param : $stri_module : nom du module dans lequel les paramètres sont utilisés
    //@param : $stri_category : nom de la catégorie de filtre
    //@return : void
    
    $this->stri_parameter=$int_param;
    
    $int_id_record=$this->createId($this->int_user,$this->stri_module,$this->stri_category);//il faut mettre à jour le cache
   
    //if param exists then
    if(parameter::getValueDBExist())
    {
      //echo"<br />Suppression du paremètre";
      //delete param on the database        => suppression du paramètre de la base
      $obj_query_delete=new querry_delete('gen_parametre');
      $obj_query_delete->addKey('num_user',$this->int_user);
      $obj_query_delete->addKey('id_param',$this->stri_parameter);
      $obj_query_delete->addKey('categorie',$this->stri_category);
      $obj_query_delete->addKey('id_module',$this->stri_module);
      $obj_query_delete->execute();
      unset(self::$arra_cache[$int_id_record][$this->stri_parameter]);//suppression du cache
    }
    else
    {
      //echo"<br />Ajout du paremètre";
      $obj_query_insert=new querry_insert('gen_parametre');
      $obj_query_insert->addField("id_param",$this->stri_parameter);
      $obj_query_insert->addField("num_user",$this->int_user,"integer");
      $obj_query_insert->addField("id_module",$this->stri_module);
      $obj_query_insert->addField('valeur',1);
      $obj_query_insert->addField("categorie",$this->stri_category);
      $obj_query_insert->addField("muser",$this->int_user,"integer");
      //echo $obj_query_insert->generateSql();
      $obj_query_insert->execute();
      self::$arra_cache[$int_id_record][$this->stri_parameter]=1;//mise à jour du cache
    }
  }
  
  //**** public method *********************************************************
 /* public function getParamDB()
  {
   if(pnusergetvar("uid")==1323)
   {return $this->getParamDBWithBDD();}
   
   return $this->getParamDBWithBDD();
  }*/
  
  //méthode s'apuyant sur le cache pour récupérer les valeurs
  public function getParamDB()
  {
   $int_id_data=$this->createId($this->int_user,$this->stri_module,$this->stri_category);
   
   $arra_param_db=array();
   foreach(self::$arra_cache[$int_id_data] as $id_param=>$valeur)
   {$arra_param_db[]=$id_param;}
   
   return $arra_param_db;
  }
  
  //méthode original requetant sur la BDD
  public function getParamDBWithBDD()
  {
    //récupère l'identifiant des paramètres dans la BDD
    //@return : $arra_param_db => tableau simple des paramètres 
    
    //récupère les identifiants de la catégorie
    $sql="SELECT id_param
          FROM gen_parametre
          WHERE num_user=".$this->int_user."
          AND id_module='".$this->stri_module."'
          AND categorie='".$this->stri_category."'"; 
    $obj_query=new querry_select($sql);
    $arra_result=$obj_query->execute();
    
    $arra_param_db=array();
    foreach($arra_result as $arra_filter)
    {
      $arra_param_db[]=$arra_filter[0];
    }
    
    return $arra_param_db;  
  }
  
  public function getParamDBExist($int_param)
  {
    $this->stri_parameter=$int_param;
    return $this->getValueDBExist();
  }
  
  public function updateState()
  {
    //permet de mettre à jour dans la base de données les paramètres
    //@return : void
    
    //echo"<br /><pre>Les parametres du POST :";print_r($this->arra_parameter);
    //récupère les données de la BDD
    $arra_db=$this->getParamDB();
    //echo"<br /><pre>Les parametres de la BDD :";print_r($arra_db);
    
    //récupère tous les paramètres qui sont dans la bdd mais pas dans le post (get all parameters in the DB and not in $_POST)
    $arra_uncheck=array_diff($arra_db,$this->arra_parameter);
    //echo"<br /><pre>Les parametres ds la BDD et pas ds le POST :";print_r($arra_uncheck);
    
    
    if(count($arra_uncheck)>0)
    {
      foreach($arra_uncheck as $stri_param)
      {
         $this->update($stri_param);
      }
    }
    
    $arra_check=array_diff($this->arra_parameter,$arra_db);
    //echo"<br /><pre>Les parametres ds le POST et pas ds la BDD :";print_r($arra_check);
    
    if(count($arra_check)>0)
    {
      foreach($arra_check as $stri_param)
      {
         $this->update($stri_param);
      }
    }
  }
  
  //Permet de supprimer en base un parametre
  public function deleteInDb()
  {
      $obj_query_delete=new querry_delete('gen_parametre');
      $obj_query_delete->addKey('num_user',$this->int_user);
      $obj_query_delete->addKey('id_param',$this->stri_parameter);
      $obj_query_delete->addKey('categorie',$this->stri_category);
      $obj_query_delete->addKey('id_module',$this->stri_module);
      $obj_query_delete->execute();
      unset(self::$arra_cache[$int_id_record][$this->stri_parameter]);//suppression du cache
  }
  public function deleteInDb2()
  {
      $obj_query_delete=new querry_delete('gen_parametre');
      $obj_query_delete->addKey('num_user',$this->int_user);
      $obj_query_delete->addKey('categorie',$this->stri_category);
      $obj_query_delete->addKey('id_module',$this->stri_module);
      $obj_query_delete->execute();
      //echo $obj_query_delete->generateSql();
      unset(self::$arra_cache[$int_id_record][$this->stri_parameter]);//suppression du cache
  }
}
?>
