/*******************************************************************************
Create Date : 23/03/2016
 ----------------------------------------------------------------------
 Class name : mvc_std_loader
 Version : 1.0
 Author : R�my Soleillant
 Description : Permet de repr�senter  en js la classe php mvc_std_loader.
               Cette classe est un MVC en un seul fichier
********************************************************************************/

function mvc_std_loader(mixed_json)
{
  //** Attributs simples ************************************************
 	
  //** Attributs collection *********************************************
  
  //** Autres attributs *************************************************
    
  //**** Constructeur *************************************************** 
  this.construct=function(mixed_json)
  {
   
   
  }
  this.construct(mixed_json);
  

} 

//**** M�thodes statiques ***********************************************
//Permet d'afficher l'interface de tri � partir du boutton sur lequel on a cliqu�
mvc_std_loader.displaySortInterface=function(obj_button)
{
  //- r�cup�ration de l'interface de tri
  var obj_table=$('.mvc_std_loader_viewer__constructTableForSortErgonomie02');
      obj_table.css('display','');
      
      
  //- r�cup�ration des coordonn�es du bouton
  var arra_coord=obj_button.offset();
  

  //- positionnement de la table
  obj_table.offset({top:arra_coord.top,left:arra_coord.left});
  
}


//Permet de changer la valeur d'un tri � partir du select sur lequel on a cliqu�
mvc_std_loader.changeTriErgonomie02=function(obj_select) 
{
   //- r�cup�ration de l'option s�lectionn�e
   var stri_select_val=obj_select.val();
   var obj_option=obj_select.find('option[value="'+stri_select_val+'"]');
   
   //- r�cup�ration des donn�es du tri
   var stri_tc_valeur=obj_option.data('tc_valeur');
   var stri_tc_nom_champ=obj_option.data('tc_nom_champ');
   
   //- r�cup�ration de la table contenant les donn�es du champ de tri
   var obj_table=obj_select.closest('tr').find('.table_champ_viewer__constructTableForMasseLoader');
   
   //- r�cup�ration de l'input contenant le type de tri
   var obj_input=obj_table.find('input[name="table_champ__stri_tc_valeur[]"]');
  
   //- changement de la valeur du tri
   obj_input.val(stri_tc_valeur);
   
   //- r�cup�ration de l'input contenant le nom du champ
   var obj_input=obj_table.find('input[name="table_champ__stri_tc_nom_champ[]"]');
   
   //- changement du nom du champ si on n'as pas choisit l'option aucun tri
   if(stri_tc_valeur!="none")
   {
     obj_input.val(stri_tc_nom_champ);
   }
}

//Permet d'ajouter un crit�re de tri � partir du bouton sur lequel on a cliqu�
mvc_std_loader.addTriErgonomie02=function(obj_button) 
{
  //- r�cup�ration de la table sur laquelle faire l'ajout
  var obj_table=obj_button.closest('.mvc_std_loader_viewer__constructTableForSortErgonomie02');
  
  //- r�cup�ration de la r�f�rence � cloner
  var obj_tr_ref=obj_table.find('.mvc_std_loader_viewer__ref_tri');
  var obj_tr_clone=obj_tr_ref.clone();
      obj_tr_clone.removeClass('mvc_std_loader_viewer__ref_tri');
      
  //- activation des input
  obj_tr_clone.find(':input').attr('disabled',false);    
      
  //- ajout du clone � la table apr�s le bouton de validation des tri
   obj_table.find('.mvc_std_loader__tr_bt_action_sort').before(obj_tr_clone);    
}


//Permet de supprimer un crit�re de tri � partir du bouton sur lequel on a cliqu�
mvc_std_loader.deleteTriErgonomie02=function(obj_button) 
{
  //- r�cup�ration de la table contenant les info sur les champs de tri
  var obj_tr= obj_button.closest('tr');
  var obj_table=obj_tr.find('.table_champ_viewer__constructTableForMasseLoader');
  
  //- r�cup�ration de l'input contenant le type de tri
  var obj_input=obj_table.find('input[name="table_champ__stri_tc_valeur[]"]');
  
  //- changement de la valeur du tri pour none
  obj_input.val('none');
  
  //- masquage du tr
  obj_tr.css('display','none');
  
}

//**** M�thodes ********************************************************* 
mvc_std_loader.prototype=
{
  //**** Partie mod�le *********************************************** 

  //**** Partie gestion de collection ********************************   
  
  
  //**** Partie viewer *********************************************** 

 
  //**** Partie manager **********************************************   
  
 
} 