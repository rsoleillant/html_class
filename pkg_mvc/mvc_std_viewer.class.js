/*******************************************************************************
Create Date : 20/06/2013
 ----------------------------------------------------------------------
 Class name : ma_classe
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de représenter  en js la classe php ma_classe.
               Cette classe est un MVC en un seul fichier
*******************************************************************************/
function mvc_std_viewer(mixed_json)
{
 

} 

//**** Méthodes statiques ****************************************************** 
/**
 *Permet de préparer l'ajout à la collection en activant le modèle de référence du loader
 *@param : stri_loader : le loader auquel ajouter un élément
 **/  
mvc_std_viewer.prepareAdd=function(stri_loader)
{
  //- récupération de la référence du loader
  var obj_ref=$('.'+stri_loader).filter('.model_reference');
   
 //- activation de la référence
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
 *Permet de préparer à la suppression d'un élément de la collection
 *@param  : obj_button : le boutton de supression sur lequel on a cliqué
 *@param  : event      : l'évènement déclencheur  
 **/  
mvc_std_viewer.prepareDelete=function(obj_button,event)
{
 //- stoppage de propagation de l'événement
  event.stopPropagation();
  
 //- récupération du tr dans lequel trouver les données
 var obj_tr=obj_button.closest('tr');
     obj_tr.css('display','none'); 
    
 //- récupération de la valeur de pk  et du modèle
 var val_pk=obj_tr.data('idmvc');
 var stri_model=obj_tr.data('model');
 var stri_pk=obj_tr.data('pk');
 
 //- création de la donnée à supprimer
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
 *Permet d'effectuer un déplacement sur un mvc
 *@param : stri_cible_mvc : le mvc dans lequel se déplacer
 *@param : stri_cible_attribut : l'attribut dans lequel faire le déplacement
 *@param : stri_dest_mvc       : le nom du mvc où aller
 *@param : mixed_dest_mvc_id   : l'identifiant du mvc à charger
 *@param : stri_dest_viewer    : (facultatif) le viewer à utiliser
 *@param : stri_dest_viewer_method : (facultatif) la méthode du viewer à utiliser
 **/  
mvc_std_viewer.moveTo=function(stri_cible_mvc,stri_cible_attribut,stri_dest_mvc,mixed_dest_mvc_id,stri_dest_viewer,stri_dest_viewer_method)
{
   //- construction de la base de l'identifiant des données en post
   var stri_base_id=stri_cible_mvc+'__'+stri_cible_attribut;
   
   //- récupération de la position actuelle
   var stri_actu_dest_mvc=$('input[name="'+stri_base_id+'__mvc_model"]').val();
   var mixed_actu_dest_mvc_id=$('input[name="'+stri_base_id+'__mvc_id"]').val(); 
   var stri_actu_dest_viewer= $('input[name="'+stri_base_id+'__mvc_viewer"]').val();   
   var stri_actu_dest_viewer_method=$('input[name="'+stri_base_id+'__mvc_viewer_methode"]').val();     
   
   //- vérification que l'on ne reste pas sur place                        
   var bool_sur_place=false;//par défaut on ne fait pas du sur place
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
   var arra_input=$('.'+stri_cible_mvc).filter(':input'); //récupération de l'ensemble des données de positionnement dans le mvc
   if(!bool_sur_place)//si on ne fait pas du sur place
   {
     var arra_clone_input=arra_input.clone();//clonage des input
     var obj_tr=$('input[name="'+stri_base_id+'__mvc_model"]').closest('tr');
    
     //-- transformation des données de base en données d'historique
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
   
   //- mise à jour des données obligatoire
   $('input[name="'+stri_base_id+'__mvc_model"]').val(stri_dest_mvc);
   $('input[name="'+stri_base_id+'__mvc_id"]').val(mixed_dest_mvc_id);    
            
   //- mise à jour des données facultative
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
 *Permet d'effectuer un retour arrière sur un mvc
 *@param : stri_cible_mvc : le mvc dans lequel faire un retour arrière
 **/  
mvc_std_viewer.backFor=function(stri_cible_mvc)
{  
   //- récupération des données d'historique
   var arra_tr=$('.constructTableForOneMoveHisto__'+stri_cible_mvc);
     
   for(var i=0;i<arra_tr.length;i++)
   {
    var obj_tr=$(arra_tr[i]);
      //-- récupération des input
      var arra_input=obj_tr.find('input');
      
      for(var j=0;j<arra_input.length;j++)
      {
        var obj_input=$(arra_input[j]);
        var stri_name=obj_input.attr('name');
            stri_name=stri_name.replace('histo_','');
            stri_name=stri_name.replace('[]','');
            
        //--- bascule de valeur entre historique et donnée de base
        $('input[name="'+stri_name+'"]').val(obj_input.val());    
      }
      
      //-- dépilage de l'historique
      obj_tr.remove();
   }  
}

/**
 *Permet de préparer les données de l'interface pour effectuer un tri sur un loader
 *@param : obj_img : l'image sur laquelle on a cliquée
 **/  
mvc_std_viewer.prepareSort=function(obj_img)
{  
  //- récupération du type de tri
  var stri_tri=obj_img.attr('class');
  var arra_class = stri_tri.split(' ');
  
  //- tableau de correspondance des changement
  var arroc_changement={}
      arroc_changement['none']='asc';
      arroc_changement['asc']='desc';
      arroc_changement['desc']='none';
      
  //- récupération du nouveau tri
  var stri_new_tri='';
  
  for (var int_i=0; int_i<=arra_class.length-1; int_i++)
  {
      var stri_class = arra_class[int_i];
      
      //- récupération du nouveau tri
      if (arroc_changement[stri_class])
      { stri_new_tri=arroc_changement[stri_class]; }
      
  }
  
  //- changement de la valeur du tri
  obj_img.closest('.table_champ_viewer').find('input[name="table_champ__stri_tc_valeur[]"]').val(stri_new_tri);
  
  //- changement de l'icône
  obj_img.attr('src','images/tri_'+stri_new_tri+'.png');  
  
}

