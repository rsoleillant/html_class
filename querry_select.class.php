<?php
/*******************************************************************************
Create Date : 29/05/2006
 ----------------------------------------------------------------------
 Class name : querry_select
 Version : 2.0
 Author : Rémy Soleillant
 Description : permet de gérer une requette de type select
 Update : le 20 fev 2008
********************************************************************************/
include_once("querry.class.php");
include_once("includes/html.pkg.php");//inclusion de la boîte à outils
class querry_select extends querry {
   
   /*attribute***********************************************/
   
   protected $int_col_number;
   protected $int_res_number;
   protected $stri_table="";
   protected $stri_order_by="";
   protected $int_num_field_to_show=0;
   protected $int_num_field_to_use_value=0;
   protected $arra_partial_sql=array();
   protected $arra_field_where=array();
   protected $arra_field=array(); 
   protected $arra_result;
   public $arra_sauv=array();
  
  /* constructor***************************************************************/
  function __construct($sql,$table="") 
  {
    //construit l'objet querry_select (create object querry_select)
    //@param : $sql => requete sql (sql query)
    //@param : $table => nom de la table dans laquelle on souhaite faire une sélection (table's name for select) 
    //@return : void
    
    $this->stri_sql=$sql;
    $this->stri_table=$table;
  }
  
  
  //**** setter ****************************************************************
  public function setTable($value){$this->stri_table=$table;}
  public function setIemeData($int,$value){$this->arra_field_where[$int]['value']=$value;}
  public function setOrderBy($value){$this->stri_order_by=$value;}
  public function setNumFieldToUseValue($int){$this->int_num_field_to_use_value=$int;}
  public function setNumFieldToShow($int){$this->int_num_field_to_show=$int;}
  
  //*** unsetter ***************************************************************
  //fonction qui déalloue une ligne résultat pour libérer de la mémoire
  public function unsetResult($i)
  {
  unset($this->arra_result[$i]);
  
  
  }
  
  //**** getter ****************************************************************
  public function getIemeData($int){return $this->arra_field_where[$int]['value'];}
  public function getResult(){return $this->arra_result;} 
  public function getNumberResult(){return $this->int_res_number;} 
  public function getNumberCol(){return $this->int_col_number;} 
  public function getIemeResult($int){return $this->arra_result[$int];} 
  public function getUniqueResult()
  {
    $arra_temp=$this->arra_result[0];
    return $arra_temp[0];
  }
  public function getNumFieldToUseValue(){return $this->int_num_field_to_use_value;}
  public function getNumFieldToShow(){return $this->int_num_field_to_show;}
  
  
  //**** public method *********************************************************
  public function addFieldWhere($field,$value)
  {
    $int=count($this->arra_field_where);
    $this->arra_field_where[$int]['field']=$field;
    $this->arra_field_where[$int]['value']=$value;
    //echo "il y a dans field where ".$this->arra_field_where[$int]['field']."<br>";
  }
  
  public function addPartialSql($value)
  {
    $this->arra_partial_sql[count($this->arra_partial_sql)]=$value;
  } 
  
  public function addFieldToShow($field)
  {
    $nbr=count($this->arra_field);
    $this->arra_field[$nbr]=$field;
    //echo "je met en additionannt ".$this->arra_field[$nbr]."<br>";
  }  
  
  public function generateCompleteSql()
  {
    for ($i=0;$i<count($this->arra_partial_sql)-1;$i++)
    {
      $stri_res.=$this->arra_partial_sql[$i].$this->arra_field_where[$i]['value'];
    }
    $stri_res.=$this->arra_partial_sql[$i];
    $this->stri_sql=$stri_res;
    
    return $stri_res;
  }  
  
  public function generateSql()
  {
    //permet de générer le sql (generate sql)
    //@return : $stri_res => chaine sql (sql)
    
    if(!empty($this->arra_partial_sql)){return $this->generateCompleteSql();}
    
    $select="SELECT ";
    
    for($i=0;$i<(count($this->arra_field)-1);$i++)
    {
      $select.=$this->arra_field[$i].", ";
    }
    $select.=$this->arra_field[$i];
    $from=" FROM ".$this->stri_table;
    $where=" WHERE ";
    //echo "arra field where a ".count($this->arra_field_where)." elements <br>";
    for($i=0;$i<(count($this->arra_field_where)-1);$i++)
    {
      $where.=$this->arra_field_where[$i]['field']."='".$this->arra_field_where[$i]['value']."' AND ";
    }
    $where.=$this->arra_field_where[$i]['field']."='".$this->arra_field_where[$i]['value']."' ";
  
    if(!empty($this->stri_order_by))
    {
      $order_by=" ORDER BY ".$this->stri_order_by;
    }
    
    $stri_res=$select.$from.$where.$order_by;
    $this->stri_sql=$stri_res;
    return $stri_res;
  }
  
  
  public function execute($mode="indice",$dbconn="NULL")
  {  
  
   /*if (pnUserGetVar("uid") == "6681") {
      echo $this->stri_sql."<br /><br />";
    }*/
   
	 
   $int_start_traitement=microtime(true);//start chrono
   $res= $this->executeOriginal($mode,$dbconn);
   $int_stop_traitement=microtime(true);//stop chrono
      
    $mdprosid=$_COOKIE['MDPROSID'];
    if($mdprosid=="lejboj9pul690295k1eshim5u5")//traçage uniquement pour un cookie donné, sinon les mesures se mélangent et on peut rien en déduire
    {
      $obj_tracer=new tracer($_SERVER['DOCUMENT_ROOT']."modules/OutilsAdmin/entrainement/Remy/Performances/source/tps_requete_appli.txt");
      $obj_tracer->trace($int_stop_traitement-$int_start_traitement);
    }
  
   
   return $res;
    
  }
    
  public function executeOriginal($mode="indice",$dbconn="NULL")
  {    
    //@param : $mode : indice => permet de retourner un tableau indicé (ex : $tab[0],$tab[1])
    //                 assoc => permet de retourner un tableau associatif (ex: $tab['id'],$tab['name'])
    //@param : dbconn => chaine de connexion
    //@return : $arra_result => tableau des résultats de la requête 
    
    //si la requete fait partie d'une transaction, on utilise une connexion différente d'une requete normale (put connection)
    $bool_t=true;

    if($dbconn=="NULL")
    {
      $dbconn=pnDBGetConn();
      $dbconn=$dbconn[0];
      $bool_t=false;
    }

    //permet de connaitre le mode de la requete
    if($mode=="assoc"){$dbconn->SetFetchMode(ADODB_FETCH_ASSOC);} //mode associatif
    else{$dbconn->SetFetchMode(1);}                               //mode indicé
   
    //si un rapport doit être effectué
    /*if($this->bool_log)
    {
      $f=fopen("modules/Contrat/includes/rapport.log","a");
      $today=date("d/m/Y_H:i:s");
      $stri_t=($bool_t)? "[Transaction]" : "";
      $stri_r=($dbconn->Execute($this->stri_sql))?"Reussi":"Erreur";
      $arra_replace=array("\n"=>" ","\t"=>" ","\r"=>" ","  "=>"");
      $stri_s=strtr($this->stri_sql, $arra_replace);
      $stri_user=$_SESSION["PNSVuid"];
      $string="\n SELECT $stri_t le $today [$stri_user] $stri_r => $stri_s";
      fwrite($f, $string);
      fclose($f);
    }*/
    
        $int_start_traitement = microtime(true); //start chrono
        
        $result = $dbconn->Execute($this->stri_sql);

        $int_stop_traitement = microtime(true); //stop chrono

        $int_time = $int_stop_traitement - $int_start_traitement;

        
        
/*        
        //Débug des temps de réponse
        echo "<pre>";
        var_dump($this->stri_sql);
        echo "Temps requete : " . $int_time;
        echo "</pre>";
        
        echo '<br/>';
        echo '<br/>';

         $_POST['DEBUG'] = $int_time + $_POST['DEBUG'];
 */
        
        

    //message d'erreur si la requete ne fonctionne pas (error message if query is not correct)
    if(!$result)
    {
        
        //- Trace de l'erreur
        $this->triggerError($dbconn->ErrorMsg(), $dbconn->ErrorNo(), $this->stri_sql);
        
      print("<font size=2 color=red>SQL ERROR : ".$dbconn->ErrorMsg()."  <br>  sql : $this->stri_sql </font>");
      exit();
    }
    
        
    $result->EOF=false;
    if($result->RecordCount() ==0) $result->EOF=true;
    $dbconn->SetFetchMode(1); //remet la connexion en mode indicé
       
    $i=0;
    $this->int_col_number=count($result->fields);
    while(!$result->EOF)
    {
       //$str_escaped=str_replace("'","&#39;",$result->fields);
       
       $this->arra_result[$i]=$result->fields;
       //echo "je trouvé ".$result->fields[0]."<br>";
       $result->MoveNext();
       $i++;
     }
      
    //$this->int_res_number=count($this->arra_result);
    $this->int_res_number=$i;
    $result->close();
  
        $int_stop_query=microtime(true);//start chrono
      
   //echo "temps query ".($int_stop_query-$int_start_query)."<br />";
     
 
    return $this->arra_result;
 } 
 
  public function limitQuery($int_from, $int_limit,$mode="indice",$dbconn="")
  {
    //EM 19/11/2007
    //permet de récupèrer les $int_limit résultats d'une requête $stri_sql à partir de la ligne $int_from  (get $int_limit results $stri_sql from $int_from)
    //@param : $stri_sql => la requete sql (sql query)
    //@param : $int_from => ligne à partir de laquelle la requete doit commencer (rows of start)
    //@param : $int_limit => nombre de ligne à retourner (number of rows to return)
    //@return : $arra_result => un tableau des résultats (array of result)
    
    /* START -- MODIF EM 11-01-2008*/
    //list($dbconn) = pnDBGetConn();
    //si la requete fait partie d'une transaction, on utilise une connexion différente d'une requete normale (put connection)
    if($dbconn=="")
    {
      $dbconn=pnDBGetConn();
      $dbconn=$dbconn[0];
    }
    /* END -- MODIF EM 11-01-2008*/
    
    //permet de connaitre le mode de la requete
    if($mode=="assoc")
    {$dbconn->SetFetchMode(ADODB_FETCH_ASSOC);} //mode associatif
    else
    {$dbconn->SetFetchMode(1);}                 //mode indicé    
    
    //message d'erreur si la requete ne fonctionne pas (error message if query is not correct)
    if(!$result=$dbconn->LimitQuery($this->stri_sql,$int_from,$int_limit))
    {
      print("<font size=2 color=red>SQL ERROR : ".$dbconn->ErrorMsg()."  <br>  sql : $this->stri_sql </font>");
      exit();
    }    
    
    $result->EOF=false;
    if($result->RecordCount()==0) $result->EOF=true;
    
    $dbconn->SetFetchMode(1); //remet la connexion en mode indicé
    $i=0;
    $this->int_col_number=count($result->fields);
    while(!$result->EOF)
    {
       $str_escaped=str_replace("'","&#39;",$result->fields);
       $this->arra_result[$i]=$str_escaped;
       //echo "je trouvé ".$result->fields[0]."<br>";
       $result->MoveNext();
       $i++;
     }
    $this->int_res_number=count($this->arra_result);
    $result->close();
    
    return $this->arra_result;
  }

  
  //**** method for serialization **********************************************
  public function __sleep() 
  {
    //méthode de sérialisation (serialization)
    $this->arra_sauv['sql']  = $this->stri_sql;
    $this->arra_sauv['table']  = $this->stri_table;
    $this->arra_sauv['order_by']  = $this->stri_order_by;
    
    for($i=0;$i<count($this->arra_field_where);$i++)
    {
      $arra_temp[$i]['field']=$this->arra_field_where[$i]['field'];
      $arra_temp[$i]['value']=$this->arra_field_where[$i]['value'];
    }
    $this->arra_sauv['arra_field_where']=$arra_temp;
    
    for($i=0;$i<count($this->arra_field);$i++)
    {
      $this->arra_sauv['arra_field'][$i]=$this->arra_field[$i];
    }   
    
    return array('arra_sauv');
   }
   
  public function __wakeup() 
  {
    //methode de désérialisation (unserialization)  
    $this->stri_sql=  $this->arra_sauv['sql'];
    $this->stri_table= $this->arra_sauv['table'];
    $this->stri_order_by= $this->arra_sauv['order_by'];
    
    $arra_temp=$this->arra_sauv['arra_field_where'];
    $nbr_object=count($arra_temp);
    for($i=0;$i<$nbr_object;$i++)
    {
      $this->arra_field_where[$i]['field']=$arra_temp[$i]['field'];
      $this->arra_field_where[$i]['value']=$arra_temp[$i]['value'];
    }
    
    $nbr_object=count($this->arra_sauv['arra_field']);
    for($i=0;$i<$nbr_object;$i++)
    {
      $this->arra_field[$i]=$this->arra_sauv['arra_field'][$i];
    }
    $this->arra_sauv = array();
  }
}
?>
