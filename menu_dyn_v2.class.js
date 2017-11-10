/*******************************************************************************
Create Date : 20/06/2013
 ----------------------------------------------------------------------
 Class name : ma_classe
 Version : 1.0
 Author : R�my Soleillant
 Description : Permet de repr�senter  en js la classe php ma_classe.
               Cette classe est un MVC en un seul fichier
********************************************************************************/

function menu_dyn_v2(mixed_json)
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

//Permet d'afficher la listes des onglets � partir du bouton sur lesquel on a cliqu�
menu_dyn_v2.displayListeOnglet=function(obj_button)
{
  //- r�cup�ration de la table contenant la liste d'onglet
  var obj_table=obj_button.closest('tr').find('.constructTableForAllOnglet');
  
  //- masquage / affichage
  obj_table.toggle('blind','600'); 
} 

//Permet de cocher / d�cocher toutes les checkbox � partir du lien sur lequel on a cliqu� 
menu_dyn_v2.check_unchek_menu=function(obj_a)
{

    var obj_selector = $(obj_a).closest('table').find('input[type="checkbox"]');
   
    if (!window.bool_menu_option_checked)
    {
        $(obj_selector).attr('checked','checked');
        window.bool_menu_option_checked = true;
    }
    else
    {
        $(obj_selector).removeAttr('checked');
        window.bool_menu_option_checked = false;
    }
    
    
    return;
    
}

//Permet d'envoyer un formulaire pour sauvegarde le choix des onglets � afficher
menu_dyn_v2.sendForm=function(obj_button,id_form)
{
  //- cr�ation d'un formulaire
  var obj_form=$(document.createElement('form'));
      obj_form.attr('method','post');
      $('body').append(obj_form);
  
  //- cr�ation de l'action
  var obj_action=document.createElement('input');
      obj_action.type='hidden';
      obj_action.name='actionMenuDyn';
      obj_action.value=id_form;
      obj_form.append(obj_action);             
  
  //- pose des cases � cocher
  //var arra_cb=obj_button.closest('table').find(':checkbox');  
      obj_form.append(obj_button.closest('table').clone());
  
  //- envoi du formulaire    
  obj_form.submit();
}

//Permet de s�lectionner un onglet � partir du lien sur lequel on a cliqu�
menu_dyn_v2.selectOnglet=function(obj_a)
{
  //- coche de la case
  var obj_cb=obj_a.closest('tr').find(':checkbox');
      obj_cb.attr('checked',true);
  
  //- ajout de s�lection automatique de l'onglet
  var obj_hidden=document.createElement('input');
      obj_hidden.type='hidden';
      obj_hidden.name='menu_dyn_v2__select_onglet';
      obj_hidden.value=obj_cb.val();  
  obj_a.closest('td').append(obj_hidden);
      
  //- clic sur le bouton de sauvegarde
  obj_a.closest('table').find('.bt_commerce').click();
         
}

//**** M�thodes ********************************************************* 
menu_dyn_v2.prototype=
{
  //**** Partie mod�le *********************************************** 

  //**** Partie gestion de collection ********************************   
  
  
  
  //**** Partie viewer *********************************************** 

 
  //**** Partie manager **********************************************   
  
 
} 