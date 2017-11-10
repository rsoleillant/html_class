<?php
/*******************************************************************************
Create Date  : 01/03/2016
 ----------------------------------------------------------------------
 Class name  : mvc_std_loader
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Le modèle standard pour les loader 
 
********************************************************************************/
class mvc_std_loader extends jsonisable{
   
//**** Attributs ****************************************************************
	
  //*** Attributs pour la pagination ********************************************
  protected $int_nb_record;         //Nombre d'enregistrement dans la collection
  protected $int_nb_record_by_page; //Nombre d'enregistrement par page
  protected $int_num_page;          //Numéro de la page de pagination
  
  //*** Attributs pour le tri ***************************************************
  protected $arra_table_champ_tri;                       //Les champs utilisés pour le tri en sql  
  protected $stri_ergonomie_tri;                         //Identifiant de l'ergonomie de tri à utiliser 
		 
//*** 01 Constructor **********************************************************
	
	/*******************************************************************************
	* Constructeur principal
	* 
	* Parametres : 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function __construct() 
	{ 
	  $this->stri_ergonomie_tri="";//Par défaut ergonomie de base  
	} 

//**** Setter ****************************************************************
  public function setNbRecord($value){$this->int_nb_record=$value;}
  public function setNbRecordByPage($value){$this->int_nb_record_by_page=$value;}
  public function setNumPage($value){$this->int_num_page=$value;}
	public function setErgonomieTri($value){$this->stri_ergonomie_tri=$value;}

  
//**** Getter ****************************************************************   
  public function getNbRecord(){return $this->int_nb_record;}
  public function getNbRecordByPage(){return $this->int_nb_record_by_page;}
  public function getNumPage(){return $this->int_num_page;}
  public function getErgonomieTri(){return $this->stri_ergonomie_tri;}

  //
  /**
   *  Permet d'obtenir soit tous les champs de tri soit un champ en particulier
   *  @param : $stri_champ : le nom du champ que l'on veut obtenir ou "" si on les veux tous
   *  @return : mixed : obj table_champ si un champ particulier
   *                    array of table_champ si on veux tous les champs
   *
   **/        
  public  function getTableChampTri($stri_champ="")
  {
    //- getter général
    if($stri_champ=="")
    { return $this->arra_table_champ_tri;}
  
    //- getter d'un champ particulier
    return $this->arra_table_champ_tri[$stri_champ];
  
  }
//**** Methodes dédiées à gestion des tris **************************************
  /**
   * Permet d'ajouter un champ de tri
   * @param : le nom du champ de tri faisant référence à un champ dans la requête du loader
   * @return : obj table_champ : le champ sous forme objet   
   **/        
  public function addChampTri($stri_champ)
  {
    //- création du champ
    $obj_table_champ=new table_champ(array("TC_MODEL"=>get_class($this),"TC_NOM_TABLE"=>"","TC_NOM_CHAMP"=>"$stri_champ","TC_ROLE"=>"tri","TC_UID"=>pnusergetvar('uid'),"TC_VALEUR"=>"none"),false);
    
    //- paramétrage du viewer
    $obj_table_champ->getViewer()->setMainMethod('constructTableForTri'.$this->stri_ergonomie_tri);
    
    //- ajout à la collection 
    //$this->arra_table_champ_tri[$stri_table.'__'.$stri_champ]=$obj_table_champ;
     $this->arra_table_champ_tri[$stri_champ]=$obj_table_champ;
    return $obj_table_champ;
  }
 
 	/*******************************************************************************
	* Permet de charger la collection
	* 
	* Parametres : aucun 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function loadChampTri() 
	{                            
    //- initialisation de la collection de champ de tri
    $this->arra_table_champ_tri=array();
    
    //- chargement des champ de tri existant
    $stri_sql="select *
               from table_champ
               where    tc_model='".get_class($this)."'
                    and tc_uid='".pnusergetvar('uid')."'
                    and tc_role='tri'";
    $obj_query=new querry_select($stri_sql);
    $arra_res=$obj_query->execute('assoc');
      
    foreach($arra_res as $arra_one_res)
    {        
      //- instanciation et paramétrage du champ tri
      $obj_table_champ=new table_champ($arra_one_res,false);      
        $obj_table_champ->getViewer()->setMainMethod('constructTableForTri'.$this->stri_ergonomie_tri);
      
      //- ajout à la collection de champ tri    
      $stri_index_champ=$arra_one_res['TC_NOM_CHAMP'];
      $this->arra_table_champ_tri[$stri_index_champ]=$obj_table_champ;       
    }
  
    //- chargement par défaut des champs non  existant en bdd
    $arra_champ_tri=$this->getChampTri();   
  
    
    foreach($arra_champ_tri as $stri_index)
    {
      //- si le champ n'est pas défini
      if(!isset($this->arra_table_champ_tri[$stri_index]))
      {      
        //-- ajout à la collection avec les valeurs par défaut
        $this->addChampTri($stri_index);  
      }
    }
   
  
  }
  
 
  
 /*******************************************************************************
	* Permet de construire la clause order by du dsql
	* 
	* Parametres : $stri_sql : le sql de base
	* Retour : string : le sql avec la clause order by                         
	*******************************************************************************/
	public function constructOrderBy($stri_sql)
  {                     
      //- classement des champs de tri par ordre de création en base  
    uasort($this->arra_table_champ_tri, array("mvc_std_loader", "cmp_obj_table_champ"));
         
    //- Construction de la clause order by
    $arra_order_by=array();
    $stri_order_by="";
    foreach($this->arra_table_champ_tri as $obj_table_champ)
    {
      if($obj_table_champ->getTcValeur()!='none')//si un tri est existant
      {
         $stri_order_by=" ORDER BY ";
        //$arra_order_by[]=$obj_table_champ->getTcNomTable().".".$obj_table_champ->getTcNomChamp()." ".$obj_table_champ->getTcValeur();
         $arra_order_by[]=$obj_table_champ->getTcNomChamp()." ".$obj_table_champ->getTcValeur();
      }
    }
    $stri_order_by.=implode(', ',$arra_order_by);
     
    //- Ajout au sql
    $stri_sql.="\n $stri_order_by";
  
    return  $stri_sql;
  }
  
  //Permet de trier les champ de tri par ordre de création
   static function cmp_obj_table_champ($obj_table_champ_1, $obj_table_champ_2)
    {
           return -1;   
        $int_id_1 = (int)$obj_table_champ_1->getIdMvc();
        $int_id_2 = (int)$obj_table_champ_2->getIdMvc();
        
        
        //- gestion des id nul
        $int_id_1=($int_id_1=="")?99:$int_id_1;
        $int_id_2=($int_id_2=="")?0:$int_id_2;
              
        if ($int_id_1 == $int_id_2) {
            return 0;
        } 
        
                
        return ($int_id_1 > $int_id_2) ? +1 : -1;
    }
   
 //**** Methodes dédiées à gestion de la pagination *****************************
 /*******************************************************************************
	* Permet de construire la clause order by du dsql
	* 
	* Parametres : $stri_sql : le sql de base
	* Retour : string : le sql paginé                         
	*******************************************************************************/
	public function constructPaginationSql($stri_sql)
  {
      //- comptage du nombre d'enregistrement
      $stri_comptage_sql="select count(*) from ($stri_sql)";
      $obj_query=new querry_select($stri_comptage_sql);
      $arra_res=$obj_query->execute();
      $this->int_nb_record=$arra_res[0][0];
      $this->int_nb_record_by_page=( $this->int_nb_record_by_page!="")? $this->int_nb_record_by_page:10;
            
      $this->int_num_page=($this->int_num_page!="")?$this->int_num_page:1;
      
      //- calcul des bornes à prendre
      $int_borne_inf=(($this->int_num_page-1)*$this->int_nb_record_by_page)+1;
      $int_borne_sup=$this->int_num_page*$this->int_nb_record_by_page;
      
      //- construction du sql de pagination
      $stri_sql_pagination="SELECT *
                            FROM 
                            (
                              SELECT ROWNUM num_row, loader.*
                              FROM 
                              ( $stri_sql ) loader
                            )
                            WHERE num_row BETWEEN $int_borne_inf AND $int_borne_sup";
          
      return $stri_sql_pagination;
  }
  


	

//*** 02 Autres méthodes ******************************************************
	

}

?>
