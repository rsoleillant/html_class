<?php
/*******************************************************************************
Create Date : 05/12/2007
 ------------------------------------------------------------------------------
 Class name : Parameter_field
 Version : 1.1
 Author : Emilie Merlat
 Description : permet de gérer les paramètres de type champ de la table GEN_PARAMETRE
*******************************************************************************/

class parameter_field extends parameter 
{
  //**** attribute *************************************************************
  protected $stri_value="NULL";             //valeur du paramètre récupéré dans le $_POST
  protected $stri_value_default="NULL";     //valeur par defaut du paramètre


  //**** constructor ***********************************************************
  public function __construct($stri_param,$int_user,$Modname,$stri_category,$stri_value,$stri_value_default)
  {    
    //classe mère
    $this->stri_parameter=$stri_param;
    $this->int_user=$int_user;   
    $this->stri_module=$Modname;
    $this->stri_category=$stri_category;
    //classe fille
    $this->stri_value=$stri_value;
    $this->stri_value_default=$stri_value_default;
    
    $this->loadCache();//RS : optimisation avec gestion de cache
   
  }
  
  //**** setter ****************************************************************
  public function setValue($stri_value)
  {
    $this->stri_value=$stri_value;
  }
    
  public function setValueDefault($stri_value)
  {
    $this->stri_value_default=$stri_value;
  }
    
  //**** getter ****************************************************************
  public function getValue()
  {
    return $this->stri_value;
  }
  
  public function getValueDefault()
  {
    return $this->stri_value_default;
  }
  

  //**** private method ********************************************************
  
/* public function getValueDB()
  {
   if(pnusergetvar("uid")==1323)
   {return $this->getValueDBWithBDD();}
   
   return $this->getValueDBCache();
  }*/
  
  public function getValueDB()
  {

   $int_id_data=$this->createId($this->int_user,$this->stri_module,$this->stri_category);
   $mixed_valeur=self::$arra_cache[$int_id_data][$this->stri_parameter];
  
   return $mixed_valeur;//on retourne la valeur contenue dans le cache
  }
    public function getCache(){
      $int_id_data=$this->createId($this->int_user,$this->stri_module,$this->stri_category);
      $mixed_valeur=self::$arra_cache[$int_id_data];
      return $mixed_valeur;
  }
  
  //méthode originale de récupération des données en requetant dans la BDD
  public function getValueDBWithBDD()
  { 
  
    //récupère la valeur du paramètre dans la BDD
    //@return : $arra_result[0][0] => valeur du paramètre 
    
    //récupère les valeurs du parametre
    $sql="SELECT valeur
          FROM gen_parametre
          WHERE id_param='".$this->stri_parameter."'
          AND num_user=".$this->int_user."
          AND id_module='".$this->stri_module."'
          AND categorie='".$this->stri_category."'"; 
    $obj_query=new querry_select($sql);
        //echo $obj_query->generateSql();
    $arra_result=$obj_query->execute();

    return $arra_result[0][0];
  }
  

  private function setLastValue()
  {
    //permet de récupérer la valeur actuelle pour l'affichage
    //@return : void
 
    if(parameter::getValueDBExist())
    {
  
      //un paramètre est stocké dans la BDD
      if($this->stri_value==$this->stri_value_default)
      {
        //la valeur du paramètre correspond à la valeur par defaut
        $this->stri_value="NULL";
      }
      elseif($this->stri_value=="NULL")
      {
        //aucune valeur dans le POST alors récupère la valeur dans la BDD
        $this->stri_value=$this->getValueDB();
      }
    }
    else
    {
 
 
      //aucun paramètre n'est stocké dans la BDD
      if($this->stri_value==$this->stri_value_default or $this->stri_value=="NULL")
      {
       
        $this->stri_value="NULL";
      }
    }
  }
  
  public function updateField()
  {                    
    $this->setLastValue();
    
    $int_id_record=$this->createId($this->int_user,$this->stri_module,$this->stri_category);//il faut mettre à jour le cache
     
    
    //si la valeur actuelle est nulle
    if($this->stri_value=="NULL")
    {
        
      //s'il existe des données dans la base
   
      if(parameter::getValueDBExist())
      {   
        //echo"<br />Suppression du paramètre dans la BDD";
        //supprime le paramètre de la BDD
        $query_delete=new querry_delete("gen_parametre");
        $query_delete->addKey("id_param",$this->stri_parameter);
        $query_delete->addKey("num_user",$this->int_user,"integer");
        $query_delete->addKey("id_module",$this->stri_module);
        $query_delete->addKey("categorie",$this->stri_category);
        $query_delete->execute();
       
        unset(self::$arra_cache[$int_id_record][$this->stri_parameter]);//on supprime aussi la valeur du cache
      }   
    }
    else
    { 
      //la valeur est différent de nulle
      if(parameter::getValueDBExist())
      {  
      
      
        if($this->stri_value<>$this->getValueDB())
        {      
          $today=date("d/m/Y_H:i:s");
          $query_update=new querry_update("gen_parametre");
          $query_update->addField("valeur",$this->stri_value);
          $query_update->addField("mdate",$today,"date");
          $query_update->addKey("id_param",$this->stri_parameter);
          $query_update->addKey("num_user",$this->int_user,"integer");
          $query_update->addKey("id_module",$this->stri_module);
          $query_update->addKey("categorie",$this->stri_category);
          $stri_res=$query_update->execute();
          self::$arra_cache[$int_id_record][$this->stri_parameter]=$this->stri_value;//on met le cache à jour
         
        
        }
      }
      else
      {     
        $query_insert=new querry_insert("gen_parametre");
        $query_insert->addField("id_param",$this->stri_parameter);
        $query_insert->addField("num_user",$this->int_user,"integer");
        $query_insert->addField("id_module",$this->stri_module);
        $query_insert->addField("categorie",$this->stri_category);
        $query_insert->addField("valeur",$this->stri_value);
        $query_insert->addField("muser",$this->int_user,"integer");
        $stri_res=$query_insert->execute();
        self::$arra_cache[$int_id_record][$this->stri_parameter]=$this->stri_value;//on met le cache à jour
    
      }
    }
     
    return $stri_res;    
  }
  
  
  public function update()
  {
      $query_update=new querry_update("gen_parametre");
      $query_update->addField("valeur",$this->stri_value);
      $query_update->addKey("id_param",$this->stri_parameter);
      $query_update->addKey("num_user",$this->int_user,"integer");
      $query_update->addKey("id_module",$this->stri_module);
      $query_update->addKey("categorie",$this->stri_category);
      $stri_res=$query_update->execute();    
  } 

  public function insert()
  {
      $query_insert=new querry_insert("gen_parametre");
        $query_insert->addField("id_param",$this->stri_parameter);
        $query_insert->addField("num_user",$this->int_user,"integer");
        $query_insert->addField("id_module",$this->stri_module);
        $query_insert->addField("categorie",$this->stri_category);
        $query_insert->addField("valeur",$this->stri_value);
        $query_insert->addField("muser",$this->int_user,"integer");
        $stri_res=$query_insert->execute();
        
        //echo $query_insert->generateSql();
  }
}


?>
