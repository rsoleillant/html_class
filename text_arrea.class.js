/*******************************************************************************
Create Date : 20/06/2013
 ----------------------------------------------------------------------
 Class name : ma_classe
 Version : 1.0
 Author : R�my Soleillant
 Description : Permet de repr�senter  en js la classe php ma_classe.
               Cette classe est un MVC en un seul fichier
********************************************************************************/
function text_arrea(mixed_json)
{
  //** Attributs simples ************************************************
  
  //**** Constructeur *************************************************** 
  this.construct=function(mixed_json)
  {
  
   
  }
  this.construct(mixed_json);
} 
//** Attributs statiques *******************************************************
text_arrea.arra_toSave=[];//tableau d'objet jquery des textarea � sauvegarder  

//**** M�thodes statiques ******************************************************
//Permet d'intialiser la fonctionnalit� de sauvegarde automatique
text_arrea.initAutoSave = function(obj_textarea)
{
   //- on enl�ve l'initialisation pour ne pas la refaire
   var stri_onmouseover=obj_textarea.attr('onmouseover');
   stri_onmouseover=stri_onmouseover.replace('text_arrea.initAutoSave($(this));','');
   obj_textarea.attr('onmouseover',stri_onmouseover);

   //- cr�ation de l'ic�ne de restauration
   var obj_img=document.createElement('img');
       obj_textarea.parent().append(obj_img);    
       obj_img.src='images/bouee_sauvetage.png';     
       obj_img.title= window.pnlang._MSG_RESTAURER;
       obj_img.obj_textarea=obj_textarea;//attachement du textearea et de l'image 
       obj_img=$(obj_img);     
       obj_img.bind('mouseout',function(){$(this).css('opacity',0.3);});
       obj_img.bind('mouseover',function(){$(this).css('opacity',1);});
       obj_img.bind('click',function(){text_arrea.restaure(this.obj_textarea);}); 
       obj_img.css('width','30px');
       obj_img.css('opacity','0.3');
       //obj_img.css('position','relative');
       obj_img.css('cursor','pointer');
  
   
   //- placement de l'image
   var arra_coord=obj_textarea.offset();
   var int_left=arra_coord.left;
   var int_top=obj_textarea.height()+arra_coord.top-obj_img.height();
   //obj_img.offset({top:int_top,left:int_left});
    
   //- enregistrement dans la liste � sauvegarder
   text_arrea.arra_toSave.push(obj_textarea);
   
   //- lancement d'une sauvegarde
   text_arrea.save();    
} 

//Permet de sauvegarder le contenu de l'�diteur
text_arrea.save =function()
{
 for ( var key=0; key<text_arrea.arra_toSave.length;key++ )//pour chaque instance 
 {                            
    var obj_textarea=text_arrea.arra_toSave[key];    //r�cup�ration de l'instance       
    var stri_txt=obj_textarea.val();         //r�cup�ration des donn�es contenues dans l'�diteur
  
    var save_index='textarea_'+obj_textarea.attr('name'); 
    if(stri_txt!='')
    {
     localStorage.setItem(save_index,stri_txt);  //sauvegarde en locale   
    }     
 } 
  window.setTimeout(text_arrea.save,10000);//lancement de sauvegarde r�guli�rement
}

 //Permet de restaurer le contenu de l'�diteur
text_arrea.restaure = function(obj_textarea)
{                                                 
      var save_index='textarea_'+obj_textarea.attr('name');
      obj_textarea.val(localStorage.getItem(save_index));                
}
    

