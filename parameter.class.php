<?php
/*******************************************************************************
Create Date : 05/12/2007
 ------------------------------------------------------------------------------
 Class name : parameter
 Version : 1.0
 Author : Emilie Merlat
 Description : permet de g�rer les param�tres de la table GEN_PARAMETRE
*******************************************************************************/

class Parameter 
{
  //**** attribute *************************************************************
  protected $stri_parameter;  //nom du param�tre
  protected $int_user;        //identifiant de l'utilisateur
  protected $stri_module;     //nom du module
  protected $stri_category;   //nom de la categorie
  
  protected static $arra_cache=array(); //utilis� pour g�rer le cache
  //**** constructor ***********************************************************
  
  
  //**** setter ****************************************************************
  public function setParameter($stri_param)
  {
    $this->stri_parameter=$stri_param;
  }
  
  public function setUser($int_user)
  {
    if(is_numeric ($int_user))
    {
      $this->int_user=$int_user;
    }
    else
    {
     echo("<script>alert('int_user doit etre de type entier');</script>");
    }
  }
  
  public function setModule($stri_module)
  {
    $this->stri_module=$stri_module;
  }
  
  public function setCategory($stri_category)
  {
    $this->stri_category=$stri_category;
  }
  
  //**** getter ****************************************************************
  public function getParameter()
  {
    return $this->stri_parameter;
  }
  
  public function getUser()
  {
    return $this->int_user;
  }
  
  public function getModule()
  {
    return $this->stri_module;
  }
  
  public function getCategory()
  {
    return $this->stri_category;
  }
  
  //**** other method **********************************************************
  //getValueDBExistWithBDD
  protected function getValueDBExistWithBDD()
  {
    //permet de savoir si des param�tres ont �t� enregistr�s dans la base de donn�es
    //@return : $bool_exists => true : des donn�es existent
    //                          false : aucune donn�e
    
    //r�cup�re les valeurs du parametre
    $sql="SELECT *
          FROM gen_parametre
          WHERE id_param='".$this->stri_parameter."'
          AND num_user=".$this->int_user."
          AND id_module='".$this->stri_module."'
          AND categorie='".$this->stri_category."'"; 
    $obj_query=new querry_select($sql);
    $arra_result=$obj_query->execute();
    
    $bool_exists=(count($arra_result)>0)?true:false;
    
    return $bool_exists;  
  }
  
  protected function getValueDBExist()
  {
    //permet de savoir si des param�tres ont �t� enregistr�s dans la base de donn�es
    //@return : $bool_exists => true : des donn�es existent
    //                          false : aucune donn�e
    
    $int_id_data=$this->createId($this->int_user,$this->stri_module,$this->stri_category);
    //$bool_exist=self::$arra_cache[$int_id_data][$this->stri_parameter]!=="";
    $bool_exist=isset(self::$arra_cache[$int_id_data][$this->stri_parameter]);
    $bool_exist=array_key_exists($this->stri_parameter,self::$arra_cache[$int_id_data]);
    
    return $bool_exist;
  }
  
 /*************************************************************
 * Permet de charger le cache. Le cache va �viter que la recherche
 * de param�tes g�n�re pleins de petites requ�tes qui au final seront 
 * plus consommatrice de ressource qu'une seule grosse requ�te.  
 *  
 * Parametres : aucun
 * retour : aucun
 *                    
 **************************************************************/ 
  protected function loadCache()
  {
   if(count(self::$arra_cache)>0)//si le cache � d�j� �t� charg�, plus rien � faire
   {return "";}
   
    $stri_sql="SELECT id_param,id_module,categorie,valeur 
               FROM gen_parametre
               WHERE num_user=".$this->int_user;
    $obj_query=new querry_select($stri_sql);
    $arra_res=$obj_query->execute();
    $arra_cache=array();
    //pr�paration du tableau pour le cache
    foreach($arra_res as $arra_data)
    {
     $int_id_record=$this->createId($this->int_user,$arra_data[1],$arra_data[2]);//on cr�er un identifiant de donn�es
     $arra_cache[$int_id_record][$arra_data[0]]=$arra_data[3];
      
    }
    
    self::$arra_cache=$arra_cache;
    
    
  }
  
  protected function createId($int_user,$id_module,$categorie)
  {
    $stri_id_record=$int_user.$id_module.$categorie;//on va cr�er un identifiant � partir du num_user,id_module et categorie
    $int_id_record=crc32($stri_id_record);//cr�ation de l'identifiant
    
    return $int_id_record;
  }
}
?>
