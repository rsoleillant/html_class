<?php
/*******************************************************************************
Create Date : 01/03/2016
 ----------------------------------------------------------------------
 Class name :  mvc_std_loader_viewer 
 Version : 1.0
 Author : Rémy Soleillant
 Description : Viewer générique des loader pour porter les méthodes communes aux loader viewer
 
********************************************************************************/
abstract class mvc_std_loader_viewer extends mvc_std_viewer{
   
 //**** attribute ************************************************************
  
  //*** 02 Attributs spécifique viewer ******************************************
  
 //**** constructor ***********************************************************
 
 
  //**** setter ****************************************************************

  
  //**** getter ****************************************************************
  
    
  //**** public method *********************************************************

  
   /*******************************************************************************
	* Pour construire l'interface html qui permet de faire de la pagination
	* 
	* Parametres : aucun 
	* Retour : obj table : la table html                         
	*******************************************************************************/
	public  function constructTableForPagination() 
	{   
      //- ajout du css
      $this->obj_css->addFile('/includes/classes/html_class/pkg_mvc/mvc_std_loader_viewer.css');   
      
      //- récupération d'info depuis le modèle
      $obj_model=$this->getModel();
      $int_nb_record= $obj_model->getNbRecord();
      $int_nb_record_by_page=$obj_model->getNbRecordByPage();
      $int_num_page=$obj_model->getNumPage();
            
      //- calcul du nombre de page
      $int_nb_page=ceil($int_nb_record/$int_nb_record_by_page);
      $int_nb_link_visible=10;//Nombre maximum de liens de page visible
      
      //- correction du nombre de page
      $int_nb_page=($int_nb_page==0)?1:$int_nb_page;
      
       //- création des bouton de première et dernière page
      $obj_bt_premier=new submit('actionPagination',1);
        $obj_bt_premier->setClass('bt_pagination');
      $obj_bt_dernier=new submit('actionPagination',$int_nb_page);
        $obj_bt_dernier->setClass('bt_pagination');
        
      //- création des td première et dernière ligne
      $obj_td_premier=new td(array($obj_bt_premier,'[...]'));
      $obj_td_dernier=new td(array('[...]',$obj_bt_dernier));      
         
      //- correction en cas où le nombre de page est inférieur au nombre de lien
      if($int_nb_page<=$int_nb_link_visible)
      {
        $int_nb_link_visible=$int_nb_page;
        $obj_td_premier=new td('');
        $obj_td_dernier=new td('');
      }
     
             
      //- calcul des bornes à afficher
      $int_nb_link_2=($int_nb_link_visible-1)/2;
      $int_borne_inf=floor($int_num_page-$int_nb_link_2);
      $int_borne_sup=ceil($int_num_page+$int_nb_link_2);
      
 
      //- correction des bornes dans les valeurs extrême
      if($int_borne_inf<=1)
      {
        $int_borne_inf=1;
        $int_borne_sup=$int_nb_link_visible;
        $obj_td_premier=new td('');
      }
      
      if($int_borne_sup>=$int_nb_page)
      {
        $int_borne_inf=$int_nb_page-$int_nb_link_visible+1;
        $int_borne_sup=$int_nb_page;
        $obj_td_dernier=new td('');
      }
      
      
      //- création d'un tableau de td pour stocker les liens
      $arra_td=array();
      for($i=$int_borne_inf;$i<=$int_borne_sup;$i++)
      {
        $obj_bt=new submit('actionPagination',$i);
          $obj_bt->setClass('bt_pagination');
        
        $arra_td[$i]=new td($obj_bt);     
      }
          
      //- mise en évidence de la page courrante
      $arra_td[$int_num_page]->getValue()->setClass('bt_pagination bt_pagination_selected');
      
      //- création d'une table pour stocker les td
      $obj_table=new table();        
        $obj_tr=$obj_table->addTr();         
      $obj_table->setClass('mvc_std_loader_viewer__constructTableForPagination');
        
      //- td de libellé de pagination
      $obj_td_libelle=new td(_LIB_PAGINATION);       
        
      //- synthèse des td
      $arra_all_td=array($obj_td_libelle);
      $arra_all_td[]=$obj_td_premier;     
      $arra_all_td=array_merge($arra_all_td,array_values($arra_td));
      $arra_all_td[]=$obj_td_dernier;
    
       
      //- intégration des td dans la table
      $obj_tr->setTd($arra_all_td);
       
      //- création d'une table d'alignement
      $obj_table_alignement=new table();
         $obj_tr=$obj_table_alignement->addTr();
             $obj_td=$obj_tr->addTd($obj_table);
              $obj_td->setAlign('center');
      $obj_table_alignement->setStyle('width:100%;border:solid black 1px;border-radius:5px;background-color:white;');
      $obj_table_alignement->setClass('mvc_std_loader_viewer');
     
       return $obj_table_alignement;
  } 
  
  /*******************************************************************************
	* Pour construire les éléments d'interface dans les colonnes
	* 
	* Parametres : aucun 
	* Retour : obj table : la table html                         
	*******************************************************************************/
	public  function constructTrForColumn() 
	{
      //- initialisation d'une table par défaut
      $obj_table=new table();
      
      //- récupération de l'ergonomie de tri à utiliser
      $obj_loader=$this->getModel();
      $stri_identifiant_ergonomie=$obj_loader->getErgonomieTri();
      
      if($stri_identifiant_ergonomie=="Ergonomie02")
      { 
         $obj_tr=$obj_table->addTr();
            $obj_td=$obj_tr->addTd($this->constructTableForSortErgonomie02());
  
      }
      
      
      return $obj_table;
  }
  
   /*******************************************************************************
	* Pour le select servant dans la gestion des tri ergonomie 02
	* Parametres : aucun 
	* Retour : obj select : la liste déroulante avec les options de tri possible                    
	*******************************************************************************/
	public  function constructSelectForSortErgonomie02() 
	{
    //- récupération des champs de tri
    $obj_loader=$this->getModel();
    $arra_table_champ=$obj_loader->getTableChampTri();
    
    //- initialisation de la liste déroulante des tri
    $obj_select=new select('mvc_std_loader_viewer__constructSelectForSortErgonomie02');
      $obj_select->setOnchange("mvc_std_loader.changeTriErgonomie02($(this));");
    
    //- ajout de l'option sans tri
    $obj_option=$obj_select->addOption("none",_LIB_AUCUN_TRI);
         $obj_option->addData('tc_valeur','none');
    
    //- construction d'une liste déroulante contenant l'ensemble des champs de tri
    foreach($arra_table_champ as $obj_table_champ)
    {
      $stri_nom_champ=$obj_table_champ->getTcNomChamp();
      $stri_libelle_champ=constante::constant('_LIB_'.strtoupper($stri_nom_champ));
      
      $obj_option=$obj_select->addOption($stri_nom_champ." asc",$stri_libelle_champ." "._LIB_CROISSANT);
         $obj_option->addData('tc_valeur','asc');
         $obj_option->addData('tc_nom_champ',$stri_nom_champ);
      $obj_option=$obj_select->addOption($stri_nom_champ." desc",$stri_libelle_champ." "._LIB_DECROISSANT);
         $obj_option->addData('tc_valeur','desc');
         $obj_option->addData('tc_nom_champ',$stri_nom_champ);
    }
    
    return $obj_select;
  }

 /*******************************************************************************
	* Pour construire l'interface html permettant le tri
	* Ergonomie 02 basé sur l'affichage d'une fenêtre permettant d'ajouter des tri  
	* 
	* Parametres : aucun 
	* Retour : obj table : la table html                         
	*******************************************************************************/
	public  function constructTableForSortErgonomie02() 
	{
    //- récupération des champs de tri
    $obj_loader=$this->getModel();
    $arra_table_champ=$obj_loader->getTableChampTri();
    $arra_key=array_keys($arra_table_champ);
    $obj_table_champ_tri_1=$arra_table_champ[$arra_key[0]];
    $stri_to_select=$obj_table_champ_tri_1->getTcNomChamp()." ".$obj_table_champ_tri_1->getTcValeur();     
      
    //- objet de l'interface
    $obj_select_tri=$this->constructSelectForSortErgonomie02();
       $obj_select_tri->selectOption($stri_to_select);
    $obj_select_tri_reference=$this->constructSelectForSortErgonomie02();
    $obj_bt_add_tri=new img('images/PNG/add-032x032.png');
      $obj_bt_add_tri->setTitle(_LIB_AJOUTER_TRI);
      $obj_bt_add_tri->setStyle('cursor:pointer;');
      $obj_bt_add_tri->setOnclick(" mvc_std_loader.addTriErgonomie02($(this));"); 
    $obj_bt_delete_tri=new img('images/PNG/cancel-032x032.png');
      $obj_bt_delete_tri->setTitle(_LIB_SUPPRIMER_TRI);
      $obj_bt_delete_tri->setStyle('cursor:pointer;');
      $obj_bt_delete_tri->setOnclick(" mvc_std_loader.deleteTriErgonomie02($(this));"); 
    $obj_bt_action_sort=new image('actionSort','images/PNG/ok-032x032.png');
      $obj_bt_action_sort->setTitle(_ACTION_TRIER);
    $obj_bt_fermer=new img('images/PNG/cancel-032x032.png');
      $obj_bt_fermer->setTitle(_ACTION_ANNULER);
      $obj_bt_fermer->setStyle('margin-left:50px;cursor:pointer;');
      $obj_bt_fermer->setOnclick("$(this).closest('.mvc_std_loader_viewer__constructTableForSortErgonomie02').css('display','none');");
       
    //- positionnement des éléments de l'interface
     $obj_table=new table();
      $obj_table->setClass('mvc_std_loader_viewer__constructTableForSortErgonomie02');
      $obj_table->setStyle('display:none;position:absolute;');
    
    //-- ajout d'un titre
     $obj_tr=$obj_table->addTr();
        $obj_td=$obj_tr->addTd(_LIB_TRIE_DES_COLONNES);
          $obj_td->setClass('titre2');
          $obj_td->setColspan(3);
          $obj_td->setAlign('center');
             
      
    //-- construction de la référence pour l'ajout de tri
     $stri_tc_valeur_tri_1=  $obj_table_champ_tri_1->getTcValeur();
     $stri_tc_nom_champ_tri_1=$obj_table_champ_tri_1->getTcNomChamp();
     $int_tc_id_table_champ=$obj_table_champ_tri_1->getIdMvc();
     $obj_table_champ_tri_1->setTcValeur('none');
     $obj_table_champ_tri_1->setTcNomChamp('');
     $obj_table_champ_tri_1->setIdMvc('');
     
     $obj_tr=$obj_table->addTr();
         $obj_tr->addTd(_LIB_PUIS_PAR);
         $obj_tr->addTd($obj_select_tri_reference);
         $obj_tr->addTd($obj_bt_delete_tri); 
        $obj_td=$obj_tr->addTd($obj_table_champ_tri_1->getViewer()->constructTableForMasseLoader());
            $obj_td->setStyle('display:none;');    
     $obj_tr->setClass('mvc_std_loader_viewer__ref_tri');
  
     
    //-- désactivation des input de la référence pour non transmission en post
    $obj_table->applyMethode('setDisabled',true);
     
    //-- construction du premier tri
      $obj_table_champ_tri_1->setTcValeur($stri_tc_valeur_tri_1);
      $obj_table_champ_tri_1->setTcNomChamp($stri_tc_nom_champ_tri_1);
      $obj_table_champ_tri_1->setIdMvc($int_tc_id_table_champ);
      /*$obj_tr=$obj_table->addTr();
         $obj_tr->addTd(_LIB_TRIER_PAR);
         $obj_tr->addTd($obj_select_tri);
         $obj_tr->addTd($obj_bt_add_tri);        
         $obj_td=$obj_tr->addTd($obj_table_champ_tri_1->getViewer()->constructTableForMasseLoader());
            $obj_td->setStyle('display:none;');  */
       
     //-- construction des listes déroulantes pour les autres tri existants hormis le premier
     $int_nb_tri_possible=count($arra_key);
     $stri_libelle=_LIB_TRIER_PAR;
     $obj_button=$obj_bt_add_tri;      
     for($i=0;$i<$int_nb_tri_possible;$i++)
     {
       //-- récupération du champ de tri
       $obj_table_champ_tri=$arra_table_champ[$arra_key[$i]];
       $stri_tc_valeur=$obj_table_champ_tri->getTcValeur();
       
              
       if($stri_tc_valeur!="none")//si le tri n'est pas null
       {  
          //-- construction d'un select
          $obj_select_tri=$this->constructSelectForSortErgonomie02();
       
          //-- sélection de l'option
          $stri_to_select=$obj_table_champ_tri->getTcNomChamp()." ".$obj_table_champ_tri->getTcValeur();                    
          $obj_select_tri->selectOption($stri_to_select);
          
          //-- ajout des éléments de tri à la table
          $obj_tr=$obj_table->addTr();
           //$obj_tr->addTd(_LIB_PUIS_PAR);
           $obj_tr->addTd($stri_libelle);
           $obj_tr->addTd($obj_select_tri);
          // $obj_tr->addTd($obj_bt_delete_tri);
           $obj_tr->addTd($obj_button); 
           $obj_td=$obj_tr->addTd($obj_table_champ_tri->getViewer()->constructTableForMasseLoader());
               $obj_td->setStyle('display:none;');   
               
          //- écrasement du bouton et libellé
          $obj_button=$obj_bt_delete_tri;
          $stri_libelle=_LIB_PUIS_PAR;
                 
      }  
     }  
     
     //-- ajout du bouton de lancement du tri
      $obj_tr=$obj_table->addTr();
           $obj_td=$obj_tr->addTd(array($obj_bt_action_sort,$obj_bt_fermer));
              $obj_td->setColspan(3);
              $obj_td->setAlign('center');
      $obj_tr->setClass('mvc_std_loader__tr_bt_action_sort');
                
    return $obj_table;
  } 
  
}

?>
