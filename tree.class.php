<?php
/*******************************************************************************
Create Date : 05/06/2006
 ----------------------------------------------------------------------
 Class name : tree
 Version : 1.0
 Author : Rémy Soleillant
 Description : permet de gérer un arbre n-aire
********************************************************************************/
include_once("table.class.php");
class tree {
   
   /*attribute***********************************************/
   protected $arra_tree=array(); //tableau contenant tous les informations sur les noeuds
   protected $int_root_position; //position de la racine dans l'abre
   protected $int_my_position=0; //position actuelle dans l'abre (id du noeud courrant)
   protected $int_last_id;       //identifiant du noeud le plus grand
   protected $int_num_tree;      //numéro d'abre  
   protected $arra_node_to_delete=array();//tableau contenant la liste des noeud à supprimer
   public $arra_sauv=array();
  
   
   /* constructor***************************************************************/
   function __construct($root_label,$root_position=0,$num_tree) {
      $this->arra_tree[0]['id_parent']=-1;
      $this->arra_tree[0]['label']=$root_label;
      $this->arra_tree[0]['hierarchy_level']=0;
      $this->arra_tree[0]['my_id']=$root_position;
      $this->arra_tree[0]['state']="new";
      $this->int_root_position=$root_position; 
      $this->int_last_id=$root_position;
      $this->int_num_tree=$num_tree; 
   }
   

   
   /*setter*********************************************************************/
  public function setMyPosition($int)
  {$this->int_my_position=$int;}
  public function setPositionToParent()
  {if ($this->int_my_position!=0)
   { $my_position=$this->int_my_position;
     $this->int_my_position=$this->getParentBySoonId($my_position);
   }
  }
  public function setPositionToRoot()
  { $this->int_my_position=0;}
  /*getter**********************************************************************/
  public function getMyPosition()
  {return $this->int_my_position;}
  
  public function getNumTree()
  {return $this->int_num_tree;}  
  
  public function getLabel($position)
  {return $this->arra_tree[$position]['label'];}
  
  /*public function getParent($position)
  {return $this->arra_tree[$position]['id_parent'];}*/
  
  public function getRoot()
  {
      $arra_res['my_id']=$this->arra_tree[0]['my_id'];
      $arra_res['id_parent']=$this->arra_tree[0]['id_parent'];
      $arra_res['label']=$this->arra_tree[0]['label'];
      $arra_res['hierarchy_level']=$this->arra_tree[0]['hierarchy_level'];
      return $arra_res; 
  }
  
  public function getLabelById($id)
  {
   $nbr=count($this->arra_tree);
   $actual_id=0;
   $i=0;
   while(($actual_id!=$id)&&($i<$nbr))
   {
    $i++;
    $actual_id=$this->arra_tree[$i]['my_id'];
   }
   $res="";
   if($i!=$nbr)
   {$res=$this->arra_tree[$i]['label'];}
   return $res;
  }
  
  
  /*permet d'avoir la position du noeud dans le tableau arra_tree à partir
   de l'identifiant du noeud*/
   public function getIndexById($id)
  {
   
   $res=-1;
   foreach($this->arra_tree as $index=>$node)
   {
    $actual_id=$node['my_id'];
    if($actual_id==$id)
    {
     $res=$index;
      return $res;;
    }
   }
   return $res;
  }
  
  
  /* permet d'avoir toutes les info d'un noeud à partir de son identifiant
    renvoi un noeud (tableau) en cas de succès, false sinon 
  */ 
  public function getAllInfoById($id)
  {   
   //si l'id est vide, on renvoi les info de la racine de l'arbre
   if($id=="")
   {return $this->arra_tree[0];}
   //on cherche les infos pour l'id demander
   $res=false;
   foreach($this->arra_tree as $key=>$node)
   {
    $actual_id=$node['my_id'];
    if($actual_id==$id)
    {
     $res=$this->arra_tree[$key];
     return $res;
    }
   }
   return $res;
  }
   
   /*renvoi les infos du noeud parent à partir de l'indentifiant d'un noeud fils*/
   public function getParentBySoonId($id)
  {
   $nbr=count($this->arra_tree);
   $actual_id=0;
   $i=0;
   while(($actual_id!=$id))
   {
    $i++;
    $actual_id=$this->arra_tree[$i]['my_id'];
   }
   $res="";
   if($actual_id=$id)
   {$res=$this->arra_tree[$i]['id_parent'];}
   //echo "l'id du parent est $res<br>";
   return $res;
  }
  public function getMaxHerarchyLevel()
  {
    foreach($this->arra_tree as $soon)
    {$max_level=max($max_level,$soon['hierarchy_level']);}
    return $max_level;
  }
  public function getTree()
  {return $this->arra_tree;}
  
  public function setTree($arra_tree)
  {$this->arra_tree = $arra_tree;}
  
  /*Permet de récupérer un sous arbre à partir de l'identifiant d'un noeud */
  public function getSubTree($int_id,$arra_res=array())
  {
  
   $int_node_index=$this->getIndexById($int_id);
   $arra_info=$this->getAllInfoById($int_id);
   $arra_res[$int_node_index]=$arra_info;
   
   $arra_node=$this->findAllSoon($int_id);
   
   foreach($arra_node as $node)
  {$arra_res=$this->getSubTree($node['my_id'],$arra_res);}
   
    return $arra_res; 
  } 
  /* method for serialization **************************************************/
  public function __sleep() {
     $this->arra_sauv['int_my_position']  = $this->int_my_position;
     $this->arra_sauv['int_last_id']  = $this->int_last_id;
     $this->arra_sauv['int_root_position']  = $this->int_root_position;
     $this->arra_sauv['int_num_tree']  = $this->int_num_tree;
     $this->arra_sauv['arra_node_to_delete']= $this->arra_node_to_delete;
     $nbr=count($this->arra_tree);
     //for($i=0;$i<$nbr;$i++)
     foreach($this->arra_tree as $key=>$arra_data)
     {
       $arra_temp[$key]['my_id']=$arra_data['my_id'];
       $arra_temp[$key]['id_parent']=$arra_data['id_parent'];
       $arra_temp[$key]['label']=$arra_data['label'];
       $arra_temp[$key]['hierarchy_level']=$arra_data['hierarchy_level'];
       $arra_temp[$key]['state']=$arra_data['state'];            
     }
     $this->arra_sauv['arra_tree']=$arra_temp;
     return array('arra_sauv');
   }
  public function __wakeup() 
    {
     $this->int_my_position= $this->arra_sauv['int_my_position'];
     $this->int_last_id= $this->arra_sauv['int_last_id'];
     $this->int_root_position= $this->arra_sauv['int_root_position'];
     $this->int_num_tree= $this->arra_sauv['int_num_tree'];
     $this->arra_node_to_delete= $this->arra_sauv['arra_node_to_delete'];
     $arra_temp=$this->arra_sauv['arra_tree'];
     $nbr_object=count($arra_temp);
     //for($i=0;$i<$nbr_object;$i++)
     $int_index=0;
     foreach($arra_temp as $key=>$arra_data)
     {
       $this->arra_tree[$int_index]['my_id']= $arra_data['my_id'];
       $this->arra_tree[$int_index]['id_parent']= $arra_data['id_parent'];
       $this->arra_tree[$int_index]['label']=  $arra_data['label'];
       $this->arra_tree[$int_index]['hierarchy_level']=  $arra_data['hierarchy_level'];
       $this->arra_tree[$int_index]['state']=  $arra_data['state'];
       $int_index++;
     }
     $this->arra_sauv = array();
     
    }
 
  
  /*other method****************************************************************/
 /* Permet d'ajouter un fils au noeud courrant.
    Renvoi l'identifiant du noeud fils créé
  */  
  public function addSoon($label)
  { $nbr_element=count($this->arra_tree);
    $this->int_last_id++;
    $my_parent_id=$this->int_my_position;
    $arra_temp=$this->getAllInfoById($my_parent_id);
    $my_parent_level=$arra_temp['hierarchy_level'];
    $this->arra_tree[$nbr_element]['my_id']=$this->int_last_id;
    $this->arra_tree[$nbr_element]['id_parent']=$this->int_my_position;
    $this->arra_tree[$nbr_element]['label']=$label;
    $this->arra_tree[$nbr_element]['hierarchy_level']=$my_parent_level+1;
    
    
    $this->arra_tree[$this->int_my_position]['hierarchy_level']+1;
    $this->arra_tree[$nbr_element]['state']="new";
    
    
    usort ($this->arra_tree, array ("tree", "compare_for_tree"));
    return $nbr_element;  
  }
  
    
  public function compare_for_tree($element1, $element2)
  { 
    $a=$element1['hierarchy_level'];
    $b=$element2['hierarchy_level'];
    if ($a == $b) {
      return 0;
    }
    return ($a < $b) ? -1 : 1;
  }
    
  /*Permet de trouver tous les noeuds fils à partir de  l'id du 
    noeud père*/
  public function findAllSoon($int)
  {
   foreach ($this->arra_tree as $index=>$arra_temp)
   { 
    $bool_find=false;
    if($arra_temp['id_parent']==$int)
    { 
     $arra_temp_res['my_id']=$arra_temp['my_id'];
     $arra_temp_res['label']=$arra_temp['label'];
     $arra_temp_res['id_parent']=$arra_temp['id_parent'];
     $arra_temp_res['hierarchy_level']=$arra_temp['hierarchy_level'];
     $arra_temp_res['index_position']=$index;
     $bool_find=true;
    // echo "parent $int, un fils trouvé id ".$arra_temp_res['my_id']." label ".$arra_temp_res['label']."<br>";
    }
    // $arra_res=array();
     if($bool_find)
     {$arra_res[count($arra_res)]=$arra_temp_res;}
   }
   
   return $arra_res;
  }
  
  public function findElementByHierarchy($level)
  {
    foreach ($this->arra_tree as $arra_temp)
   { 
   //var_dump($arra_temp);echo "<br><br>";
    $bool_find=false;
    if($arra_temp['hierarchy_level']==$level)
    { 
      $arra_temp_res['my_id']=$arra_temp['my_id'];
      $arra_temp_res['label']=$arra_temp['label'];
      $arra_temp_res['id_parent']=$arra_temp['id_parent'];
      $arra_temp_res['hierarchy_level']=$arra_temp['hierarchy_level'];
      $bool_find=true;
    }
    if($bool_find)
    {$arra_res[count($arra_res)]=$arra_temp_res;}
  }
  
  return $arra_res;
 } 
  public function createTree()
  {
     
   $arra_res=$this->getRoot();
   $arra_partial_tree[0]['tree']=$this->createPartialTree(0);
   $arra_partial_tree[0]['nbr_join']=0;
   $nbr_element=count($this->arra_tree);
  
   for($i=1;$i<$nbr_element;$i++)
   {
     $my_label=$this->arra_tree[$i]['label'];
     $my_rang=$this->arra_tree[$i]['hierarchy_level'];
     $my_id=$this->arra_tree[$i]['my_id'];
     $my_partial_tree=$this->createPartialTree($my_id);
     $arra_partial_tree[$my_id]['tree']=$my_partial_tree;
     $arra_partial_tree[$my_id]['nbr_join']=0;
     $my_parent_id=$this->arra_tree[$i]['id_parent'];
    
     $parent_tree=$arra_partial_tree[$my_parent_id]['tree'];
     $my_parent_has_join=$arra_partial_tree[$my_parent_id]['nbr_join'];
     //echo "je suis $my_id, label $my_label,rang $my_rang, mon parent a l'id $my_parent_id, mon parent à fusioné $my_parent_has_join fois, je fusion les arbre $my_partial_tree et $parent_tree<br>";
     $this->joinPartialTree($parent_tree,$my_partial_tree,$my_parent_has_join);
     $my_parent_has_join++;
     $arra_partial_tree[$my_parent_id]['nbr_join']=$my_parent_has_join;
    }
   return $arra_partial_tree[0]['tree'];
  }
 
 public function createPartialTree($id_root)
 {
  $arra_soon=$this->findAllSoon($id_root);
  //var_dump($arra_soon);
  if(empty($arra_soon))
  {$arra_soon=array();}
  $nbr_soon=count($arra_soon);
  $obj_table=new table();
  $obj_tr1=new tr();
  $label=$this->getLabelById($id_root);
  $temp_td=$obj_tr1->addTd($label);
  $temp_td->setColspan($nbr_soon);
  $temp_td->setAlign("center");
  $obj_tr2=new tr();
  foreach($arra_soon as $soon)
  {$obj_tr2->addTd($soon['label']);}
  $obj_table->insertTr($obj_tr1);
  if(!empty($arra_soon))
  {$obj_table->insertTr($obj_tr2);}
  return $obj_table;
 }
 
 public function joinPartialTree($table1,$table2,$num_td_to_join)
 {
  //echo "je fusion les table $table1 et $table2 sur le $num_td_to_join<br>";
  //echo "je tente d'accèder à la cellule $num_td_to_join<br>";
  $temp_td=$table1->getCellule(1,$num_td_to_join);
 // echo $table1->htmlValue().$table2->htmlValue()."<br>";
  $temp_td->setValue($table2,true);
  $temp_td->setValign("top");
  return $table1;
 }
 
 public function saveTreeInDatabase()
 {
   foreach($this->arra_tree as $soon)
   {
    //traitement de l'insertion des nouveaux noeuds
    if($soon['state']=="new")
    {  
      $obj_querry_insert=new querry_insert("arbre_connexion");
      $obj_querry_insert->addField("id",$soon['my_id'],"integer");
      $obj_querry_insert->addField("num_arbre",$this->int_num_tree,"integer");
      $obj_querry_insert->addField("id_parent",$soon['id_parent'],"integer");
      $obj_querry_insert->addField("libelle",$soon['label']);
      $obj_querry_insert->addField("niveau_hierarchie",$soon['hierarchy_level'],"integer");
      $obj_querry_insert->execute();
    }
    //traitement de la mise à jour des noeuds
    if($soon['state']=="update")
    {  
      $obj_querry_update=new querry_update("arbre_connexion");
      $obj_querry_update->addKey("id",$soon['my_id'],"integer");
      $obj_querry_update->addKey("num_arbre",$this->int_num_tree,"integer");
      $obj_querry_update->addField("libelle",$soon['label']);
      $obj_querry_update->execute();
    }     
    //echo $obj_querry_insert->generateSql()."<br>";
   }
   
   //suppression des noeuds
   $stri_list_node=implode(",",$this->arra_node_to_delete);
   $stri_sql="delete from arbre_connexion 
              where num_arbre='".$this->int_num_tree."' 
                and id in($stri_list_node)";
  
   if(count($this->arra_node_to_delete))
   {
     $obj_query_delete=new querry_delete("arbre_connexion");
     $obj_query_delete->setSql($stri_sql);
     $obj_query_delete->execute(); 
   }
 }
 
 public function loadTree($num_tree)
 {
  $querry_load=new querry_select("select id,id_parent,libelle,niveau_hierarchie from arbre_connexion where num_arbre=$num_tree order by id");
  $arra_res=$querry_load->execute();
  $this->int_last_id=-1;
  foreach($arra_res as $key=>$soon)
  {
   $arra_temp['state']="exist";
   $arra_temp['my_id']=$soon[0];
   $arra_temp['id_parent']=$soon[1];
   $arra_temp['hierarchy_level']=$soon[3];
   $arra_temp['label']=$soon[2];
   $this->int_last_id=max($this->int_last_id,$soon[0]);
   $this->arra_tree[$key]=$arra_temp;
  }
  $this->int_my_position=0;
  $this->int_num_tree=$num_tree;
  return $this;
 }
 
 /*permet de mettre à jour le libellé du noeud courrant
   renvoi true en cas de succès et false sinon
  */ 
 public function updateNode($newlabel)
 {
  $int_node_index=$this->getIndexById($this->int_my_position);
  $this->arra_tree[$int_node_index]['label']=$newlabel;
  $this->arra_tree[$int_node_index]['state']="update";
 
 }

 /* Permet de supprimer le noeud courrant et tous ces fils */
 public function deleteNode()
 {
  
  $arra_sub_tree=$this->getSubTree($this->int_my_position);
  
  $this->setPositionToParent();
  
  foreach($arra_sub_tree as $node)
  {   
     $index=$this->getIndexById($node['my_id']);
     $this->arra_node_to_delete[]=$node['my_id'];
     unset($this->arra_tree[$index]);
  }

 }
}

?>
