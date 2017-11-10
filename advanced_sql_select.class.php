<?php
/*******************************************************************************
Create Date : 12/05/2010
 ----------------------------------------------------------------------
 Class name : advanced_sql_select
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet l'analyse et la construction de sql pour une requête complexe de type select
********************************************************************************/

class advanced_sql_select  
{
  //**** attribute *************************************************************
  protected $arra_select;//Les éléments de la clause select
  protected $arra_from;//Les éléments de la clause from
  protected $arra_where;//Les éléments de la clause where
  protected $arra_group_by;//Les éléments de la clause group by
  protected $arra_having; //Les éléments de la clause having
  protected $arra_order_by;//Les éléments de la clause order by
  
  protected $int_num_sub_query=0;//Utilisé pour numéro les sous requêtes
  protected $arra_sub_query;//Tableau contenant le sql des sous requête
  protected $arra_obj_sub_query;//Tableau contenant des objets advanced_sql_select correspondant aux sous requêtes
  protected $arra_plan;//Utiliser pour visualiser les imbrications de requêtes
  //**** constructor ***********************************************************
   
   /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe advanced_sql_select   
   *                        
   **************************************************************/         
  function __construct() 
  {}
 
  //**** setter ****************************************************************
  public function setSelect($value){$this->arra_select=$value;}
  public function setFrom($value){$this->arra_from=$value;}
  public function setWhere($value){$this->arra_where=$value;}
  public function setGroupBy($value){$this->arra_group_by=$value;}
  public function setHaving($value){$this->arra_having=$value;}
  public function setOrderBy($value){$this->arra_order_by=$value;}


    
  //**** getter ****************************************************************
  public function getSelect(){return $this->arra_select;}
  public function getFrom(){return $this->arra_from;}
  public function getWhere(){return $this->arra_where;}
  public function getGroupBy(){return $this->arra_group_by;}
  public function getHaving(){return $this->arra_having;}
  public function getOrderBy(){return $this->arra_order_by;}

  public function getNumSubQuery(){return $this->int_num_sub_query;}
  public function getSubQuery(){return $this->arra_sub_query;}

  public function getObjSubQuery($int_num_query){return $this->arra_obj_sub_query[$int_num_query];}
  
  //**** public method *********************************************************
  
   /*************************************************************
   * Permet d'ajouter un élément à une clause. 
   * parametres : string : le nom de la clause [select, from, where , group by, having ,order by]
   *              string : l'élément à ajouter.
   * retour : bool : true  => l'élément à été ajouté
   *                 false => l'élément n'a pas été ajouté   
   *                        
   **************************************************************/         
  public function addToClause($stri_clause,$stri_add)
  {
   $arra_key_word=array("select","from","where","group by","having","order by");//liste des clauses présentes dans une requête
    if(!in_array($stri_clause,$arra_key_word))//si on a pas passé le bon paramètre
    {return false;}
   
   //ajout à l'attibut en utilisant un système de référence 
   $arra_attribute["select"]= &$this->arra_select;
   $arra_attribute["from"]= &$this->arra_from; 
   $arra_attribute["where"]= &$this->arra_where; 
   $arra_attribute["group by"]= &$this->arra_group_by; 
   $arra_attribute["having"]= &$this->arra_shaving; 
   $arra_attribute["order by"]= &$this->arra_order_by; 
    
   $arra_attribute[$stri_clause][]=$stri_add;
   
   return true;
  }
  
  
   /*************************************************************
   * Permet de détecter les sous requêtes et de les remplacer par 
   * des références   
   * parametres :  aucun
   * retour : string : le sql avec des références à la place des sous requêtes 
   *                        
   **************************************************************/    
  private function analyseSubQuery($stri_sql)
  {
 
  
   $int_nb_select=substr_count($stri_sql,"select");//on compte le nombre de select
   if($int_nb_select==1)//s'il n'y a qu'un seul select, aucune requête imbriqué n'est présente
   {return $stri_sql;}
   
   $int_start=strrpos($stri_sql,"select");//on recherche la position du dernier select
   $int_stop=strpos($stri_sql,")",$int_start);//on recherche la position de la prochaine parenthèse fermante
   
   $stri_extract=substr($stri_sql,$int_start,$int_stop-$int_start) ;//extraction à partir du select jusqu'à la prochaine parenthèse fermante
   
   $int_nb_closer=substr_count($stri_extract,")");//on compte le nombre de parenthèse fermante dans l'extrait
   $int_nb_opener=substr_count($stri_extract,"(");//on compte le nombre de parenthèse ouvrante dans l'extrait
   
   //on recherche la position de la parenthèse fermante de la sous requête
   while(($int_nb_opener+1!=$int_nb_closer)&&($secu<100))//pour être équilibré, on doit avoir une parenthèse fermante en plus (correspondant à l'ouvrante de la sous requête) 
   {
    $secu++;
    $int_stop=strpos($stri_sql,")",$int_stop+1);//on recherche la position de la prochaine parenthèse fermante
    $stri_extract=substr($stri_sql,$int_start,$int_stop+1-$int_start) ;//extraction à partir du select jusqu'à la prochaine parenthèse fermante
    
    $int_nb_closer=substr_count($stri_extract,")");//actualisation du nombre de fermante
    $int_nb_opener=substr_count($stri_extract,"(");//actualisation du nombre d'ouvrante
  
   }
   
   //arrivé ici, on a trouvé la position de la parenthèse fermante de le sous requête
   $int_lg=$int_stop-$int_start-1;//calcul de la longeur à extraire
   $stri_sub_query=substr($stri_sql,$int_start,$int_lg) ;//extraction de la sous requete
   $this->arra_sub_query[$this->int_num_sub_query]=$stri_sub_query;
  
   $stri_replace="[sub query --".$this->int_num_sub_query."--]";
   $this->int_num_sub_query++;
   
   $stri_sql=substr_replace($stri_sql,$stri_replace,$int_start,$int_lg);//remplacement de la sous-requête par une référence
 
   return $this->analyseSubQuery($stri_sql);//récursivité pour traité la sous requête suivante
  
  }
  
    
   /*************************************************************
   * Permet de décomposer une requête simple (sans sous requête).
   *    
   * parametres :  le sql à décomposer
   * retour :aucun 
   *                        
   **************************************************************/
  private function analyseSimpleQuery($stri_sql)
  {
   $int_start=stripos($stri_sql, "select");

   $arra_key_word=array("select","from","where","group by","having","order by");//liste des clauses présentes dans une requête
   
   $arra_clause=array();
   //on recherche les clauses présentes dans la requêtes
   foreach($arra_key_word as $stri_key_word)
   {
    if(stripos($stri_sql,$stri_key_word)!==false)//si le mot clef à été trouvé
    {$arra_clause[]=$stri_key_word;}//on ajoute le mot clef à la liste des clauses
   }
   
   $int_nb_key_word=count($arra_clause);
   for($i=0;$i<$int_nb_key_word;$i++)//extraction des éléments de chaque clause
   {
    $stri_key_word=$arra_clause[$i];//le mot clef actuel
    $stri_next_key_word=$arra_clause[$i+1];//le mot clef suivant
    
    $int_start=stripos($stri_sql,$stri_key_word)+strlen($stri_key_word);//calcul du départ de l'extraction
    $int_pos_next_key_word=stripos($stri_sql,$stri_next_key_word);//on recherche la position du prochain mot clef
    //si le mot clef suivant est défini, la longeur  est la distance entre le deuxième mot clef et le premier,
    // sinon c'est la longeur total de la chaine(on va extraire jusqu'à la fin de la chaine) 
    $int_len=($int_pos_next_key_word!==false)?$int_pos_next_key_word-$int_start:strlen($stri_sql);
    
    $stri_extraction=substr($stri_sql,$int_start,$int_len);//extraction des éléments de la clause
    
    $stri_separator=(in_array($stri_key_word,array("where","having")))?" and ":", ";//recherche du séparateur d'élément en fonction du type de clause 
    $arra_element=$this->decomposeElement($stri_extraction,$stri_separator);
    
    $stri_attribute="arra_".$stri_key_word;
    $this->$stri_attribute=$arra_element;//on renseigne l'attribut correspondant à la clause que l'on traite
    
   }
   
  }
  

  /*************************************************************
   * permet de décomposer les différents éléments séparés par un séparateur
   * passé en paramètre   
   *    
   * parametres : string : les éléments à décomposer
   *              string : le séparateur à utiliser   
   * retour :array : le tableau des différents éléments 
   *                        
   **************************************************************/
  private function decomposeElement($stri_elements,$stri_separator=", ")
  {
   $stri_elements_clean=strtr($stri_elements,array("\r\n"=>"","\n"=>"","  "=>" "));//nettoyage des autres caractères parasites
   $stri_elements_clean=trim($stri_elements_clean);//suppression des espaces parasites
   
   //$arra_res=explode($stri_separator, strtolower($stri_elements_clean));//décomposition
   
   //Modif RB
   $arra_key_word = array("select","from","where","group by","having","order by");  
   $arra_key_word_to_replace = array("SELECT","FROM","WHERE","GROUP BY","HAVING","ORDER BY");
   $stri_sql = str_replace($arra_key_word_to_replace,$arra_key_word,$stri_elements_clean);
   
   $arra_res = explode($stri_separator, $stri_sql);//décomposition
      
   return $arra_res; 
  }
  

  /*************************************************************
   * Permet d'analyser et de décomposer une requête select  
   * Il faut faire appel à cette méthode avant de pouvoir compléter
   * le SQL       
   * parametres : string : le sql à analyser
   *             
   * retour : aucun
   *                        
   **************************************************************/
  public function analyseQuery($stri_sql)
  {   
   //Modif RB
   $arra_key_word = array("select","from","where","group by","having","order by");  
   $arra_key_word_to_replace = array("SELECT","FROM","WHERE","GROUP BY","HAVING","ORDER BY");
   $stri_sql = str_replace($arra_key_word_to_replace,$arra_key_word,$stri_sql);
   
   //$stri_sql=strtolower($stri_sql);
   
   $stri_simple_query=$this->analyseSubQuery($stri_sql);
   
   $this->analyseSimpleQuery($stri_simple_query);
   
   foreach($this->arra_sub_query as $key=>$stri_sub_query)
   {
 
     $obj_ass=new advanced_sql_select();
     $obj_ass->analyseQuery($stri_sub_query);
     $this->arra_obj_sub_query[$key]= $obj_ass;  
   } 
  
  }
 
  /*************************************************************
   * Permet de lancer la reconstruction du sql sans intégrer les sous requêtes
   * parametres : aucun
   *             
   * retour : string : du sql simple sans sous requêtes
   *                        
   **************************************************************/
 private function rebuildPartialSql()
 {
   $arra_key_word=array("select","from","where","group by","having","order by");//liste des clauses présentes dans une requête
   $arra_separator=array("select"=>", ","from"=>", ","where"=>" and ","group by"=>", ","having"=>" and ","order by"=>", ");
   $stri_res="";
   
   //reconstruction des éléments simple
   foreach($arra_key_word as $stri_key_word)//pour chaque clause
   {
    $bool_first=true;
    $stri_separator=$arra_separator[$stri_key_word];
    $stri_attribute="arra_".$stri_key_word;
    
    
    foreach($this->$stri_attribute as $key=>$stri_element)//pour chaque élément de la clause
    {
      if($bool_first)//cas de pose du premier élément
      {
        $stri_res.=" ".$stri_key_word." ";
      }
      $stri_separator=$arra_separator[$stri_key_word];      
      
      if($bool_first)
      {
       $stri_separator="";
       $bool_first=false;
      }
      $stri_res.=$stri_separator.$stri_element;        
    }
   }
  
 
   return $stri_res;
 }
 
/*************************************************************
 * Permet de lancer la reconstruction complète du SQL
 * parametres : aucun
 *             
 * retour : string : le sql complet avec les sous requêtes
 *                        
 **************************************************************/
 public function rebuildSql()
 {
   $stri_res=$this->rebuildPartialSql();
   //pour la construction du "plan" de la requête
   $arra_color=array("#33ff33","blue","red","purple");
   $int_num_color=-1;
   
   //reconstruction des références aux sous-requêtes
   $stri_masque="[sub query --";
   $stri_fin_masque="--]";
   $int_pos=strpos($stri_res,$stri_masque);//on recherche si l'élément contient une référence à une sous requête
   while(($int_pos!==false)&&($secu<100))
   {
    $secu++;
    
    //on va remplacer la référence par le sql correspondant
    $int_pos_stop=strpos($stri_res,$stri_fin_masque);//recherche la fin de la première référence
    $stri_before=substr($stri_res,0,$int_pos);//extraction de ce qui se trouve avant la référence
    $stri_after=substr($stri_res,$int_pos_stop+3);//extraction de ce qui se trouve après la référence
    $stri_reference=substr($stri_res,$int_pos,$int_pos_stop-$int_pos+3);//extraction de la référence
    
    //pour avoir les étapes de construction de la requête
    $int_num_color=($int_num_color>3)?0:$int_num_color+1;
    $stri_color=$arra_color[$int_num_color];
    $stri_plan=str_replace($stri_reference,"<font color='$stri_color'>".$stri_reference."</font>", $stri_res);
    $this->arra_plan[]=$stri_plan;
    
    //récupération du numéro de référence de la sous requête
    $arra_token=explode("--",$stri_reference);
    $int_num_sql=$arra_token[1];
    $obj_sub_query=$this->arra_obj_sub_query[$int_num_sql];
    
    $stri_sub_sql=$obj_sub_query->rebuildPartialSql();//reconstruction de la sous requête
    
    $stri_res=$stri_before.$stri_sub_sql.$stri_after;//remplacement dans la requête original
    $int_pos=strpos($stri_res,$stri_masque);//recherche d'une nouvelle référence
    $stri_plan=$stri_before."(<font color='$stri_color'>".$stri_sub_sql."</font>)".$stri_after;
    $this->arra_plan[]=$stri_plan;
   }   
  
   return $stri_res;
 }
 
 
 /*************************************************************
 * Permet de visualiser graphiquement le "plan de construction" de la requête.
 * Utile lors du debug pour cibler la partie de la requête qui doit être modifiée   
 * parametres : aucun
 *             
 * retour : string : code html du plan
 *                        
 **************************************************************/
 public function getHtmlPlan()
 {
   $obj_table=new table();
   
   foreach($this->arra_plan as $stri_requete)
   {
     $obj_tr=$obj_table->addTr();
      $obj_tr->addTd($stri_requete);
   }
  
  return $obj_table->htmlValue();
 }
 
}




?>
