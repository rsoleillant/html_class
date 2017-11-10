/*******************************************************************************
Create Date : 20/06/2013
 ----------------------------------------------------------------------
 Class name : ma_classe
 Version : 1.0
 Author : R�my Soleillant
 Description : Permet de repr�senter  en js la classe php ma_classe.
               Cette classe est un MVC en un seul fichier
*******************************************************************************/
function mvc_std_viewer(mixed_json)
{
 

} 

//**** M�thodes statiques ****************************************************** 
/**
 *Permet de pr�parer l'ajout � la collection en activant le mod�le de r�f�rence du loader
 *@param : stri_loader : le loader auquel ajouter un �l�ment
 **/  
mvc_std_viewer.prepareAdd=function(stri_loader)
{
  //- r�cup�ration de la r�f�rence du loader
  var obj_ref=$('.'+stri_loader).filter('.model_reference');
   
 //- activation de la r�f�rence
 obj_ref.css('display','');
 obj_ref.find(':input').attr('disabled',false);  
 
 //- ajout de l'action add
 var obj_input=document.createElement('input');
     obj_input.type='hidden';
     obj_input.name='actionAdd';
     obj_input.value=1;
  obj_ref.closest('form').append(obj_input);
                             
}



/**
 *Permet de pr�parer � la suppression d'un �l�ment de la collection
 *@param  : obj_button : le boutton de supression sur lequel on a cliqu�
 *@param  : event      : l'�v�nement d�clencheur  
 **/  
mvc_std_viewer.prepareDelete=function(obj_button,event)
{
 //- stoppage de propagation de l'�v�nement
  event.stopPropagation();
  
 //- r�cup�ration du tr dans lequel trouver les donn�es
 var obj_tr=obj_button.closest('tr');
     obj_tr.css('display','none'); 
    
 //- r�cup�ration de la valeur de pk  et du mod�le
 var val_pk=obj_tr.data('idmvc');
 var stri_model=obj_tr.data('model');
 var stri_pk=obj_tr.data('pk');
 
 //- cr�ation de la donn�e � supprimer
 var obj_form=obj_tr.closest('form');
 var obj_input=document.createElement('input');
     obj_input.type='hidden';
     obj_input.name=stri_model+'__'+stri_pk+'__delete[]';;
     obj_input.value=val_pk;
 obj_form.append(obj_input);
  
 //- ajout de l'action delete
 var obj_input=document.createElement('input');
     obj_input.type='hidden';
     obj_input.name='actionDelete';
     obj_input.value=1;
  obj_form.append(obj_input);
                             
}

/**
 *Permet d'effectuer un d�placement sur un mvc
 *@param : stri_cible_mvc : le mvc dans lequel se d�placer
 *@param : stri_cible_attribut : l'attribut dans lequel faire le d�placement
 *@param : stri_dest_mvc       : le nom du mvc o� aller
 *@param : mixed_dest_mvc_id   : l'identifiant du mvc � charger
 *@param : stri_dest_viewer    : (facultatif) le viewer � utiliser
 *@param : stri_dest_viewer_method : (facultatif) la m�thode du viewer � utiliser
 **/  
mvc_std_viewer.moveTo=function(stri_cible_mvc,stri_cible_attribut,stri_dest_mvc,mixed_dest_mvc_id,stri_dest_viewer,stri_dest_viewer_method)
{
   //- construction de la base de l'identifiant des donn�es en post
   var stri_base_id=stri_cible_mvc+'__'+stri_cible_attribut;
   
   //- r�cup�ration de la position actuelle
   var stri_actu_dest_mvc=$('input[name="'+stri_base_id+'__mvc_model"]').val();
   var mixed_actu_dest_mvc_id=$('input[name="'+stri_base_id+'__mvc_id"]').val(); 
   var stri_actu_dest_viewer= $('input[name="'+stri_base_id+'__mvc_viewer"]').val();   
   var stri_actu_dest_viewer_method=$('input[name="'+stri_base_id+'__mvc_viewer_methode"]').val();     
   
   //- v�rification que l'on ne reste pas sur place                        
   var bool_sur_place=false;//par d�faut on ne fait pas du sur place
   if(stri_actu_dest_mvc==stri_dest_mvc)
   {
     if(mixed_actu_dest_mvc_id==mixed_dest_mvc_id)
     {
        if(stri_actu_dest_viewer==stri_dest_viewer)
        {
           if(stri_actu_dest_viewer_method==stri_dest_viewer_method)
           {
              bool_sur_place=true;            
           }
        }
     }
   }

   //- historisation
   var arra_input=$('.'+stri_cible_mvc).filter(':input'); //r�cup�ration de l'ensemble des donn�es de positionnement dans le mvc
   if(!bool_sur_place)//si on ne fait pas du sur place
   {
     var arra_clone_input=arra_input.clone();//clonage des input
     var obj_tr=$('input[name="'+stri_base_id+'__mvc_model"]').closest('tr');
    
     //-- transformation des donn�es de base en donn�es d'historique
     for(var i=0;i<arra_clone_input.length;i++)
     {
        //--- modification du nom du post
        var obj_input=$(arra_clone_input[i]);
        var input_name=obj_input.attr('name');
            input_name='histo_'+input_name+'[]';
            obj_input.attr('name',input_name);
            
        //--- modification des classes css
        obj_input.removeClass(stri_cible_mvc);
        obj_input.addClass('temp_histo_'+stri_cible_mvc);
     }
     
     //-- rattachement de l'historique au DOM
     var obj_table=obj_tr.closest('table');
     var obj_new_tr=$(document.createElement('tr'));
     var obj_new_td=$(document.createElement('td'));
         obj_table.append(obj_new_tr); 
         obj_new_tr.append(obj_new_td);
         obj_new_td.append(arra_clone_input); 
   }
   
   //- mise � jour des donn�es obligatoire
   $('input[name="'+stri_base_id+'__mvc_model"]').val(stri_dest_mvc);
   $('input[name="'+stri_base_id+'__mvc_id"]').val(mixed_dest_mvc_id);    
            
   //- mise � jour des donn�es facultative
   if(stri_dest_viewer)
   {
     $('input[name="'+stri_base_id+'__mvc_viewer"]').val(stri_dest_viewer);      
   }
   
   if(stri_dest_viewer_method)
   {
     $('input[name="'+stri_base_id+'__mvc_viewer_methode"]').val(stri_dest_viewer_method);    
   } 
                    
}

/**
 *Permet d'effectuer un retour arri�re sur un mvc
 *@param : stri_cible_mvc : le mvc dans lequel faire un retour arri�re
 **/  
mvc_std_viewer.backFor=function(stri_cible_mvc)
{  
   //- r�cup�ration des donn�es d'historique
   var arra_tr=$('.constructTableForOneMoveHisto__'+stri_cible_mvc);
     
   for(var i=0;i<arra_tr.length;i++)
   {
    var obj_tr=$(arra_tr[i]);
      //-- r�cup�ration des input
      var arra_input=obj_tr.find('input');
      
      for(var j=0;j<arra_input.length;j++)
      {
        var obj_input=$(arra_input[j]);
        var stri_name=obj_input.attr('name');
            stri_name=stri_name.replace('histo_','');
            stri_name=stri_name.replace('[]','');
            
        //--- bascule de valeur entre historique et donn�e de base
        $('input[name="'+stri_name+'"]').val(obj_input.val());    
      }
      
      //-- d�pilage de l'historique
      obj_tr.remove();
   }  
}

/**
 *Permet de pr�parer les donn�es de l'interface pour effectuer un tri sur un loader
 *@param : obj_img : l'image sur laquelle on a cliqu�e
 **/  
mvc_std_viewer.prepareSort=function(obj_img)
{  
  //- r�cup�ration du type de tri
  var stri_tri=obj_img.attr('class');
  var arra_class = stri_tri.split(' ');
  
  //- tableau de correspondance des changement
  var arroc_changement={}
      arroc_changement['none']='asc';
      arroc_changement['asc']='desc';
      arroc_changement['desc']='none';
      
  //- r�cup�ration du nouveau tri
  var stri_new_tri='';
  
  for (var int_i=0; int_i<=arra_class.length-1; int_i++)
  {
      var stri_class = arra_class[int_i];
      
      //- r�cup�ration du nouveau tri
      if (arroc_changement[stri_class])
      { stri_new_tri=arroc_changement[stri_class]; }
      
  }
  
  //- changement de la valeur du tri
  obj_img.closest('.table_champ_viewer').find('input[name="table_champ__stri_tc_valeur[]"]').val(stri_new_tri);
  
  //- changement de l'ic�ne
  obj_img.attr('src','images/tri_'+stri_new_tri+'.png');  
  
}

