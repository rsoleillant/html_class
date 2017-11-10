<?php
/*******************************************************************************
Create Date  : 02/08/2016
 ----------------------------------------------------------------------
 Class name  : parameter_state_v2
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Permet de gérer une collection de valeur pour un paramètre donnée stocké 
               dans la table gen_parametre 
 
********************************************************************************/
class parameter_state_v2 {
   
//**** Attributs ****************************************************************
protected $stri_id_param;
protected $int_num_user;
protected $stri_id_module;
protected $arra_categorie;
protected $stri_valeur;
protected $stri_mdate;
protected $int_mentite;
protected $int_muser;
  
	//*** 01 Attributs  ***********************************************************
	 


//*** 01 Constructor **********************************************************
	
	/*******************************************************************************
	* Constructeur principal
	* 
	* Parametres : 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function __construct($stri_id_param,$int_num_user,$stri_id_module) 
	{ 
    //- un paramètre est identifié par son id + num_user + id_module, cela pointe sur une collection de valeur
    $this->stri_id_param=$stri_id_param;
    $this->int_num_user=$int_num_user;
	  $this->stri_id_module=$stri_id_module; 
    
    //- la collection des valeurs possible est porté par le champ categorie
    $this->arra_categorie=array();
    
    //- les champ non utilisé
    $this->stri_valeur=1;
    $this->int_mentite=-1;
	}
	
//**** Methodes *****************************************************************

//**** Setter ****************************************************************
	public function setIdParam($value){$this->stri_id_param=$value;}
  public function setNumUser($value){$this->int_num_user=$value;}
  public function setIdModule($value){$this->stri_id_module=$value;}
  public function setCategorie($value){$this->arra_categorie=$value;}
  public function setValeur($value){$this->stri_valeur=$value;}
  public function setMdate($value){$this->stri_mdate=$value;}
  public function setMentite($value){$this->int_mentite=$value;}
  public function setMuser($value){$this->int_muser=$value;}

  
//**** Getter ****************************************************************   
  public function getIdParam(){return $this->stri_id_param;}
  public function getNumUser(){return $this->int_num_user;}
  public function getIdModule(){return $this->stri_id_module;}
  public function getCategorie(){return $this->arra_categorie;}
  public function getValeur(){return $this->stri_valeur;}
  public function getMdate(){return $this->stri_mdate;}
  public function getMentite(){return $this->int_mentite;}
  public function getMuser(){return $this->int_muser;}

//*** 02 Autres méthodes ******************************************************
	
	/*******************************************************************************
	* Permet de charger les différentes valeurs de la collection
	* 
	* Parametres :  aucun
	* Retour : array : collection des valeurs enregistrées                               
	*******************************************************************************/
	public  function load() 
	{ 
	  //récupère les identifiants de la catégorie
    $sql="SELECT categorie
          FROM gen_parametre 
          WHERE     id_param='".$this->stri_id_param."' 
                AND num_user=".$this->int_num_user."  
                AND id_module='".$this->stri_id_module."'";
         
  
    $obj_query=new querry_select($sql);
    $arra_result=$obj_query->execute("assoc");
    
    $this->arra_categorie=array();
    foreach($arra_result as $arra_one_res)
    {
      $this->arra_categorie[]=$arra_one_res['CATEGORIE'];
    }
    
    return  $this->arra_categorie;  
	}
  
 /*******************************************************************************
	* Permet d'ajouter une nouvelle valeur dans la collection
	* 
	* Parametres :  string : la valeur à ajouter
	* Retour : aucun                     
	*******************************************************************************/
	public  function add($stri_value) 
	{
    $this->arra_categorie[]=$stri_value;
  }
  
  /*******************************************************************************
	* Permet de supprimer une  valeur de la collection
	* 
	* Parametres :  string : la valeur à ajouter
	* Retour : aucun                     
	*******************************************************************************/
	public  function remove($stri_value) 
	{
    if(($key = array_search($stri_value, $this->arra_categorie)) !== false) 
    {
      unset($this->arra_categorie[$key]);
    }
  }
  
   /*******************************************************************************
	* Permet de lancer l'insertion d'une valeur en base
	* 
	* Parametres :  string : la valeur à insérer
	* Retour : aucun                     
	*******************************************************************************/
	public  function insert($stri_categorie) 
	{
      //- ajout à la collection
      $this->add($stri_categorie);
      
      //- champs automatique
      $this->stri_mdate=date('Y-m-d H:i:s');
      $this->int_muser=pnusergetvar('uid');
      
      //- requete d'insertion
      $obj_query_insert=new querry_insert('gen_parametre');
      $obj_query_insert->addField("id_param",$this->stri_id_param);
      $obj_query_insert->addField("num_user",$this->int_num_user,"integer");
      $obj_query_insert->addField("id_module",$this->stri_id_module);
      $obj_query_insert->addField("categorie",$stri_categorie);
      $obj_query_insert->addField('valeur',$this->stri_valeur);
      $obj_query_insert->addField("mdate", $this->stri_mdate,"autodate");
      $obj_query_insert->addField("mentite", $this->int_mentite,"integer");
      $obj_query_insert->addField("muser", $this->int_muser,"integer");
      
     // echo $obj_query_insert->generateSql();
      
      $obj_query_insert->execute();
  }
  
  
    /*******************************************************************************
	* Permet de lancer l'update d'une valeur en base
	* 
	* Parametres :  string : la valeur à insérer
	* Retour : aucun                     
	*******************************************************************************/
	public  function update($stri_categorie) 
	{
      //- ajout à la collection
      $this->remove($stri_categorie);
      $this->add($stri_categorie);
      
      //- champs automatique
      $this->stri_mdate=date('Y-m-d H:i:s');
      $this->int_muser=pnusergetvar('uid');
      
      //- requete d'insertion
      $obj_query_update=new querry_udpate('gen_parametre');
      $obj_query_update->addKey("id_param",$this->stri_id_param);
      $obj_query_update->addKey("num_user",$this->int_num_user,"integer");
      $obj_query_update->addKey("id_module",$this->stri_id_module);
      $obj_query_update->addField("categorie",$stri_categorie);
      $obj_query_update->addField('valeur',$this->stri_valeur);
      $obj_query_update->addField("mdate", $this->stri_mdate,"autodate");
      $obj_query_update->addField("mentite", $this->int_mentite,"integer");
      $obj_query_update->addField("muser", $this->int_muser,"integer");
      
     // echo $obj_query_update->generateSql();
      
      $obj_query_update->execute();
  }
  
    /*******************************************************************************
	* Permet de supprimer une valeur en base
	* 
	* Parametres :  string : la valeur à insérer
	* Retour : aucun                     
	*******************************************************************************/
	public  function delete($stri_categorie) 
	{
      //- suppression de la collection
      $this->remove($stri_categorie);      
      
      //- requete de suppression
      $obj_query_delete=new querry_delete('gen_parametre');
      $obj_query_delete->addKey("id_param",$this->stri_id_param);
      $obj_query_delete->addKey("num_user",$this->int_num_user,"integer");
      $obj_query_delete->addKey("id_module",$this->stri_id_module);
      $obj_query_delete->addKey("categorie",$stri_categorie);
        
      //echo $obj_query_delete->generateSql();
      
      $obj_query_delete->execute();
  }
}

?>
