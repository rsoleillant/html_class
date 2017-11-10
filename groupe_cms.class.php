<?php
/*******************************************************************************
Create Date : 27/09/2008
 ----------------------------------------------------------------------
 Class name : groupe_cms
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de retrouver les membres d'un groupe du CMS
********************************************************************************/

class groupe_cms  
{
  //**** attribute *************************************************************
 
  //**** constructor ***********************************************************
   
   /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe rules_applicator   
   *                        
   **************************************************************/         
  function __construct() 
  {
  
  }  
 
  //**** setter ****************************************************************

  //**** getter ****************************************************************
 
  
  //**** public method *********************************************************
  /*************************************************************
  * Permet de charger la liste des groupes du cms
  * parametres : aucun
  * retour : array(group id=> groupe name) 
  *                        
  **************************************************************/         
  public function loadGroupList()
  {
   $stri_sql="SELECT pn_gid,pn_name FROM mdp_groups ORDER BY pn_name";
   $obj_query=new querry_select($stri_sql);
   
   $arra_res_query=$obj_query->execute();
   $arra_res=array();//on change la structure du tableau de résultat
   
   foreach($arra_res_query as $arra_line)
   {
    $arra_res[$arra_line[0]]=$arra_line[1];
   }
    
    return $arra_res;
  }
  
  /*************************************************************
  * Permet de charger la liste des membres d'un groupe
  * parametres : mixed : integer : l'identifiant du groupe
  *                      string  : le nom du groupe      
  * retour : array[]array(num_user,nom,prenom)
  *                        
  **************************************************************/         
  public function loadGroupMembers($mixed_group,$stri_order="nom,prenom")
  {
   $stri_load_clause=(is_int($mixed_group))?" g.pn_gid=$mixed_group ":" g.pn_name='$mixed_group' ";//clause de chargement en fonction du type de paramètres de la méthode
   $stri_sql="SELECT uu.num_user,uu.nom,uu.prenom,uu.login,uu.titre,uu.email,uu.qualite
              FROM mdp_groups g, mdp_group_membership gm, user_user uu
              WHERE   g.pn_gid=gm.pn_gid
                  and uu.num_user=gm.pn_uid
                  AND $stri_load_clause
              ORDER BY $stri_order";

   $obj_query=new querry_select($stri_sql);
   
   return $obj_query->execute("assoc");
  }
 
 
  /*************************************************************
  * Permet de charger la liste des groupe auxquel un membre appartient
  * parametres : integer : le num_user 
  * retour : array(group id=> groupe name) 
  *                        
  **************************************************************/         
  public function loadMemberGroups($int_num_user)
  {
   $stri_sql="SELECT g.pn_gid,g.pn_name
              FROM mdp_groups g, mdp_group_membership gm, user_user uu
              WHERE   g.pn_gid=gm.pn_gid
                  and uu.num_user=gm.pn_uid
                  AND uu.num_user=$int_num_user
              ORDER BY uu.nom,uu.prenom";
  
   $obj_query=new querry_select($stri_sql);
   
   $arra_query_res=$obj_query->execute();
   
   //changement de structure des résultats
   $arra_res=array();
   foreach($arra_query_res as $arra_line)
   {
    $arra_res[$arra_line[0]]=$arra_line[1];
   }
  
  
   return $arra_res;
  }
 
   /*************************************************************
  * Permet de dire si un membre appartient à un groupe ou non
  * parametres :  integer : le num_user à vérifier
  *               mixed : integer : l'identifiant du groupe
  *                      string  : le nom du groupe      
  * retour : bool : true : l'utilisateur appartient au groupe
  *                 false: l'utilisateur ne fait pas parti du group   
  *                        
  **************************************************************/   
 public function isMemberInGroup($int_num_user,$mixed_group)
 {
   $stri_load_clause=(is_int($mixed_group))?" g.pn_gid=$mixed_group ":" g.pn_name='$mixed_group' ";//clause de chargement en fonction du type de paramètres de la méthode
   
   $stri_sql="SELECT uu.num_user,uu.nom,uu.prenom
              FROM mdp_groups g, mdp_group_membership gm, user_user uu
              WHERE   g.pn_gid=gm.pn_gid
                  and uu.num_user=gm.pn_uid
                  AND $stri_load_clause
                  and uu.num_user=$int_num_user
              ORDER BY uu.nom,uu.prenom";
  
   $obj_query=new querry_select($stri_sql);
   $arra_query_res=$obj_query->execute();
   $int_nb_res=count($arra_query_res);
   $bool_res=($int_nb_res>0)?true:false;
  
   return $bool_res;
   
 }
 

}




?>
