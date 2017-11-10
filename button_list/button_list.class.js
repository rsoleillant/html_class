/*******************************************************************************
Create Date : 20/06/2013
 ----------------------------------------------------------------------
 Class name : ma_classe
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de représenter  en js la classe php ma_classe.
               Cette classe est un MVC en un seul fichier
********************************************************************************/
  
function button_list(mixed_json)
{
  //** Attributs simples ************************************************
  var stri_title;
  var int_indice_selected  ;

	
  //** Attributs collection *********************************************
  var arra_button_list_unit;  
  
  //** Autres attributs *************************************************
  var obj_view;     //Le jquery servant de vue
   
  //**** Constructeur *************************************************** 
  this.construct=function(mixed_json)
  {
     var obj_json=JSON.parse(mixed_json);
     var arra_key=Object.keys(obj_json);
     obj_json=obj_json[arra_key[0]]; 
    
    this.stri_title           = obj_json.stri_title           ;
    this.int_indice_selected  = obj_json.int_indice_selected  ;

    
    this.arra_button_list_unit=[];
   
    for(var i=0;i<obj_json.arra_button_list_unit.length;i++)
    {
      var obj_button_list_unit=new button_list_unit(obj_json.arra_button_list_unit[i]);
      this.arra_button_list_unit.push(obj_button_list_unit);
      obj_button_list_unit.setButtonList(this);    
    }    

    /*****************************************************************************
     * Gestion évenement par defaut pour masquer menu
     */
    var Me = this;
    $(function()
    {
        $('body').click(function(e)
        {
            //Masque les menus si le click à lieu sur autre chose qu'une arrow_icon
            if (!$(e.target).hasClass('button_list_arrow_icon'))
            {   Me.HideAllList();    }
            
        });
    });
   /**********************************************************************************/
    
  }
  this.construct(mixed_json);
  

} 

//**** Méthodes statiques ***********************************************
//Permet d'obtenir l'instance à partir de l'endroit du button_list sur lequel on a cliqué 
button_list.getInstance=function(obj_clicked)
{
    
  var obj_table= obj_clicked.closest('.button_list');
  
  
  if(obj_table[0].obj_model)//si l'instance est déjà définie
  {
   return obj_table[0].obj_model;
  }
  
  //- ici il faut créer et attacher une nouvelle instance
  var obj_button_list=new button_list(obj_table.find('.button_list__toJson').html());
  obj_table[0].obj_model=obj_button_list;
  obj_table[0].obj_model.obj_view=obj_table;
  
  //- attachement modèle-vue pour les objets de la collection
  var arra_tr=obj_table.find('.button_list_unit__toTr');
  for(var i=0;i<arra_tr.length;i++)
  {
   var obj_tr=arra_tr[i];
   var obj_button_list_unit=obj_button_list.arra_button_list_unit[i];
   obj_button_list_unit.obj_view=obj_tr;
   obj_tr.obj_model=obj_button_list_unit;
  } 
  
  return  obj_button_list;
}

//**** Méthodes ********************************************************* 
button_list.prototype=
{
    
  //**** Partie modèle *********************************************** 
    setIndiceSelected : function(value){this.int_indice_selected=value;},

    getIndiceSelected : function(){return this.int_indice_selected;},


    //permet d'obtenir le bouton sélectionné
    getSelectedButton : function()
    {
      return this.arra_button_list_unit[this.int_indice_selected];
    },
  //**** Partie gestion de collection ********************************   

  //**** Partie viewer *********************************************** 

/*************************************************************
 * Permet d'afficher la liste des boutons disponibles
 * parametres : aucun
 * retour :aucun
 *                        
 **************************************************************/    
  displayOrHideList :  function(stri_display)
  {
      
      
      
    //- gestion de valeur par défaut
    if(!stri_display)
    {var stri_display='';}
    
    //- récupération de la table des options possibles   
    var obj_table_liste=this.obj_view.find('.button_list__constructTableForListButton');
    
    //- récupération de l'icône de flèche
    var obj_img_fleche=this.obj_view.find('.button_list_arrow_icon');
    if (obj_img_fleche.tooltip())
    { obj_img_fleche.tooltip('close'); }
    
    var stri_fleche_src=obj_img_fleche.attr('src');
        
    if((obj_table_liste.css('display')!='none')||(stri_display=='hide'))//si la liste est visible ou si on veux explicitement masquer
    {
      
     //- cas de maquage
     //- changement de l'icône de flèche
      stri_fleche_src=stri_fleche_src.replace("up", "down"); 
      obj_img_fleche.attr('src',stri_fleche_src);

      obj_table_liste.css('display','none');
      
      
      //Suppréssion hover table principale
      this.obj_view.children('.button_list_table_hover').removeClass('button_list_table_hover');
      
      
      
      return;
    }
    

    //RAZ des menus auparavant ouvert
    this.HideAllList();

    //Style du table Ajout hover sur table principale)
    this.obj_view.children('.button_list_table').addClass('button_list_table_hover');


    //- cas d'affichage
    //- affichage de la liste 
    obj_table_liste.css('position', 'absolute');
    obj_table_liste.css('display', '');

    //- changement de l'icône de flèche
    stri_fleche_src = stri_fleche_src.replace("down", "up");
    obj_img_fleche.attr('src', stri_fleche_src);
      
 
    
  }, 
  
  
  
/*************************************************************
 * Permet de masquer les menus
 * parametres : aucun
 * retour :aucun
 *                        
 **************************************************************/    
  HideAllList :  function()
  {
      
    //Suppréssion table active  
    $('.button_list_table_hover').removeClass('button_list_table_hover');
    
    //Masque les tables
    $('.button_list__constructTableForListButton').css('display','none');

    //Restauration etat arrow
    var selector_arrow =  $('.button_list_arrow_icon');
    $(selector_arrow).attr('src', $(selector_arrow).attr('src').replace("up", "down"));
        
  },
  
  
 
 
  //Permet de changer l'action qui s'effectue sur le clic de l'icône
  //@param: le tr de l'option sur laquelle on a cliqué
  changeAction : function(obj_button_list_unit)
  {
      
      
   // var obj_button_list_unit=obj_tr[0].obj_model;
    var int_dual_indice=obj_button_list_unit.getDualIndice();//récupération de l'indice dual
    
    
    if(int_dual_indice!="")
    {
      //-- on utilise le bouton dual pour l'actualisation de l'interface 
      obj_button_list_unit=this.arra_button_list_unit[int_dual_indice];
    }
    
    //- lancement de l'ordre d'actualisation de l'interface
    obj_button_list_unit.actualiseHtmlValueVariant01();  
    
    
  }
 
 
  
  //**** Partie manager **********************************************   
  
 
} 