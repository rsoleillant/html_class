/*******************************************************************************
Create Date : 20/06/2013
 ----------------------------------------------------------------------
 Class name : button_list_unit
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de représenter  en js la classe php button_list_unit.
           
********************************************************************************/

function button_list_unit(mixed_json)
{
  //** Attributs simples ************************************************
  var stri_name;
  var stri_value;
  var stri_title;
  var stri_src;
  
	var obj_button_list;
  var int_my_indice;
  var int_dual_indice;
  
  var stri_onclick;


  //** Attributs collection *********************************************
  
  //** Autres attributs *************************************************
  var obj_container;     //Le conteneur de l'objet
  
  var obj_view;          //L'objet jquery servant de vue  
  //**** Constructeur *************************************************** 
  this.construct=function(mixed_json)
  {  
    var obj_json=mixed_json.button_list_unit;
    
    this.stri_name  = obj_json.stri_name  ;
    this.stri_value = obj_json.stri_value ;
    this.stri_title = obj_json.stri_title ;
    this.stri_src   = obj_json.stri_src   ;
    
    this.int_my_indice = obj_json.int_my_indice;
    this.int_dual_indice = obj_json.int_dual_indice;
    
    this.stri_onclick = obj_json.stri_onclick;
  }
  this.construct(mixed_json);
  

} 

//**** Méthodes ********************************************************* 
button_list_unit.prototype=
{
  //**** Partie modèle *********************************************** 
    setName : function(value){this.stri_name=value;},
    setValue : function(value){this.stri_value=value;},
    setTitle : function(value){this.stri_title=value;},
    setSrc : function(value){this.stri_src=value;},
    setButtonList : function(value){this.obj_button_list=value;},
    setMyIndice : function(value){this.int_my_indice=value;},
    setDualIndice : function(value){this.int_dual_indice=value;},
    setOnclick : function(value){this.stri_onclick=value;},


    getName : function(){return this.stri_name;},
    getValue : function(){return this.stri_value;},
    getTitle : function(){return this.stri_title;},
    getSrc : function(){return this.stri_src;},
    getButtonList : function(){return this.obj_button_list;},
    getMyIndice : function(){return this.int_my_indice;},
    getDualIndice : function(){return this.int_dual_indice;},
    getOnclick : function(){return this.stri_onclick;},

  //**** Partie gestion de collection ********************************   
  
  //**** Partie viewer *********************************************** 
   //permet d'actualiser l'interface contenant l'icône de la liste
   actualiseHtmlValueVariant01 : function() 
	{ 
            
     var mixed_rep=this.obj_button_list.obj_view.find('.button_list_unit'); 
     /*var  stri_action=this.stri_onclick; 
          stri_action+="event.stopPropagation();";                               //arrêt de propagation de l'événement
          stri_action+='var obj_button_list= button_list.getInstance($(this));';  //récupération de l'instance de la collection
          stri_action+="obj_button_list.displayOrHideList('hide');";             //masquage des options
          stri_action+='obj_button_list.changeAction(obj_button_list.getSelectedButton());';    //changement de l'action à effectuer sur le clic de l'icône principal
          */
     var  stri_action="event.stopPropagation();";                               //arrêt de propagation de l'événement
          stri_action+='var obj_button_list= button_list.getInstance($(this));';  //récupération de l'instance de la collection
          stri_action+="obj_button_list.displayOrHideList('hide');";             //masquage des options
          stri_action+='obj_button_list.changeAction(obj_button_list.getSelectedButton());';    //changement de l'action à effectuer sur le clic de l'icône principal
          stri_action+=this.stri_onclick;
         
   
     if(mixed_rep.attr('src')!="")//si un icône de représentation existe
     {
      
       mixed_rep.attr('src',this.stri_src);
       mixed_rep.attr('title',this.stri_title);
       if (mixed_rep.tooltip())
       {
           mixed_rep.tooltip({content: this.stri_title });
       }
       
     }
     else
     {
       //cas d'un font 
       mixed_rep.html(this.stri_title);
     }
    
     mixed_rep.attr('onclick',stri_action);
   
   
   
     //- actualisation de l'indice de sélection dans la collection
     this.obj_button_list.setIndiceSelected(this.int_my_indice);
     
     $(this.obj_button_list.obj_view).children().addClass('button_list_table_actif');
	 
         
    }
 
  //**** Partie manager **********************************************   
  
 
} 