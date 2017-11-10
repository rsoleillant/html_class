/*******************************************************************************
Create Date : 28/01/2015
 ----------------------------------------------------------------------
 Class name : table_with_search
 Version : 2.0
 Author : Rémy Soleillant
 Description : Permet de représenter  en js la classe php table_with_search.              
********************************************************************************/

function table_with_search(mixed_json)
{
  //** Attributs simples ************************************************
  var arra_resultat;          //L'ensemble des résultats de la table
  var arra_resultat_filtre;   //L'ensemble des résultats répondant aux critères de filtres
  var int_nb_ligne_page;      //Le nombre de ligne à afficher par page 
  var arra_column_name;       //Le nom des colonnes    
  var arroc_column_crit;      //Pour mémoriser l'ensemble des critères de filtre par colonne
  var stri_template_onclick;  //L'url sur laquelle se diriger lors du clic. Les données à remplacer sont sous forme de template
    
  //** Attributs collection *********************************************
  
  //** Autres attributs *************************************************
  var obj_view;               //L'objet jquery représentant la vue complette
  var obj_search_interface;   //L'objet jquery représentant l'interface de recherche
  //**** Constructeur *************************************************** 
  this.construct=function(mixed_json)
  {
    var obj_json=JSON.parse(mixed_json);
    
    this.arra_resultat=obj_json.arra_resultat;
    this.arra_resultat_filtre=obj_json.arra_resultat;
    this.int_nb_ligne_page=obj_json.int_nb_ligne_page;
    this.arra_column_name=obj_json.arra_column_name;
    this.arroc_column_crit={};
    this.stri_template_onclick='';
 
  }
  this.construct(mixed_json);
  

} 

//**** Méthodes statiques ***********************************************
//Permet de récupérer l'instance à partir d'un sous-élément graphique appartenant à la table
table_with_search.getInstance=function(obj_jquery)
{
   var obj_table=obj_jquery.closest('.table_with_search');
      
    
   if(obj_table[0].obj_model)//si l'instance est définie
   {
    return obj_table[0].obj_model;
   }
   
   //- récupération des données en json
   var stri_json=obj_table.find('.table_with_search__constructJson').html();
    obj_table.find('.table_with_search__constructJson').css('background-color','pink');

   //- création de l'instance
   var obj_tws=new table_with_search(stri_json);
   
   //- attachement modèle-vue
   obj_table[0].obj_model=obj_tws;
   obj_tws.obj_view=obj_table;
   
   return  obj_tws;
} 

//Utilisé lors du tri des colonnes
table_with_search.compare_croissant=function(element1,element2)
{
  //- récupération des valeur
  var stri_val1=element1[table_with_search.stri_colonne];
  var stri_val2=element2[table_with_search.stri_colonne];
  
  if(stri_val1<stri_val2)
  {return -1;}
  
  if(stri_val1>stri_val2)
  {return 1;}
  
  return 0;
}
table_with_search.compare_decroissant=function(element1,element2)
{
  //- récupération des valeur
  var stri_val1=element1[table_with_search.stri_colonne];
  var stri_val2=element2[table_with_search.stri_colonne];
  
  if(stri_val1<stri_val2)
  {return 1;}
  
  if(stri_val1>stri_val2)
  {return -1;}
  
  return 0;
}

table_with_search.displayCompleteDiv=function(obj_div)
{
  //- création d'une div d'affichage
  var obj_div_affichage=$('#table_with_search__div_affichage');
  if(obj_div_affichage.length==0)//si on ne l'a pas encore créé
  {
    obj_div_affichage=$(document.createElement('div'));
    obj_div_affichage.css('position','absolute');
    obj_div_affichage.css('border','solid black 3px');
    obj_div_affichage.css('border-radius','5px');
    obj_div_affichage.css('background-color','white');
    obj_div_affichage.css('font-size','15px');
    obj_div_affichage.attr('id','table_with_search__div_affichage');
    
  }
  //- affichage de la div
  obj_div_affichage.css('display','');

  //- remplissage de la div d'affichage 
  obj_div_affichage.html(obj_div.html());
  
  //- rattachement de la div d'affichage
  
  obj_div.parent().prepend(obj_div_affichage);
}

table_with_search.hideCompleteDiv=function(obj_div)
{
 $('#table_with_search__div_affichage').css('display','none');
}


//**** Méthodes ********************************************************* 
table_with_search.prototype=
{
  //**** Partie modèle *********************************************** 
  setTemplateOnclick : function(value){this.stri_template_onclick=value;},

  //Permet d'enregistrer un critère de filtre
  setCritere : function(stri_colonne,stri_critere,stri_value)
  {
      //- cas d'initialisation
      if(!this.arroc_column_crit[stri_colonne])
      {
        this.arroc_column_crit[stri_colonne]={};
      }

      //- enregistrement du critère
      this.arroc_column_crit[stri_colonne][stri_critere]=stri_value;
  },
  
  getTemplateOnclick : function(){return this.stri_template_onclick;},
  //Permet de récupérer un critère de filtre
  getCritere(stri_colonne,stri_critere)
  {
    if(!this.arroc_column_crit[stri_colonne])
    {return '';}
    
    if(this.arroc_column_crit[stri_colonne][stri_critere])
    {return this.arroc_column_crit[stri_colonne][stri_critere];}
    
    return '';
  },
  
  //Permet d'avoir le nombre de résultat
  getNbReultat : function()
  {
    return this.arra_resultat.length;
  },
  //**** Partie gestion de collection ********************************   
  
/*************************************************************
 * Permet d'obtenir les valeur distincts pour une colonne donnée
 * @param : le nom de la colonne
 * @return : array : tableau des valeurs 
 **************************************************************/    
  getDistinctValueForColumn :  function(stri_nom_colonne)
  {
    //- recherche des valeur distinct
    var arroc_distinct={};

    for(var i=0;i<this.arra_resultat_filtre.length;i++)
    {
      var stri_value=this.arra_resultat_filtre[i][stri_nom_colonne];
      arroc_distinct[stri_value]=1;    
    }
    
    //- triage des valeur distinct
    var arra_distinct= Object.keys(arroc_distinct);
        arra_distinct.sort();

    return arra_distinct;
  },
  
  
  /*************************************************************
 * Permet de rechercher dans la liste des valueurs distinct
 * @param :  obj_input : le champ de saisie sur lequel on a tappé
 **************************************************************/    
  searchInDistinctValue : function(obj_input)
  {
    //- récupération de la liste des valeurs distincts
    var obj_search_interface=obj_input.closest('.table_with_search__colonne');
    var stri_colonne=obj_search_interface[0].stri_nom_colonne ;
    var arra_distinct=this.getCritere(stri_colonne,'arra_distinct');
    var expression=obj_input.val();
    var regexp=new RegExp(expression, 'i'); 
    var arra_result=[];
    
    //- parcours de l'ensemble des valeurs
    for(var i=0;i<arra_distinct.length;i++)
    {
       if(regexp.test(arra_distinct[i]))
       {
         arra_result.push(arra_distinct[i]);
       }       
    }  
    
    //- actualisation de l'affichage
    this.actualiseDistinctValue(stri_colonne,arra_result);  
  },
   
/*************************************************************
 * Permet de rechercher dans la liste des valueurs distinct
 * @param :  obj_bt : le bouton ok sur lequel on a cliqué
 **************************************************************/    
  searchInResult : function(obj_bt)
  {
    //- récupération de la chaîne cherchée
    var obj_search_interface=obj_bt.closest('.table_with_search__colonne');
    var stri_searched=obj_search_interface.find("input[name='table_with_search__search']").val();
    var regexp=new RegExp(stri_searched, 'i'); 
    var stri_colonne=obj_search_interface[0].stri_nom_colonne;
      
    var arra_result=[];
  
    //- parcours de résultats  et recherche
    for(var i=0;i<this.arra_resultat_filtre.length;i++)
    {
       if(regexp.test(this.arra_resultat_filtre[i][stri_colonne]))
       {
         arra_result.push(this.arra_resultat_filtre[i]);
       }
    }
    
    //- écrasement des résultats filtrés
    this.arra_resultat_filtre=arra_result;
    
    //- actualisation de l'interface global
    this.actualiseView(0);
      
    //- actualisation de la pagination
    this.actualisePagination();
    
    //- actualisation de l'icône de colonne
    this.obj_view.find('.'+stri_colonne).find('.column_icon').attr('src',"images/drop_down_03.png");
       
    //- fermeture de l'interface de recherche
    obj_search_interface.css('display','none');
  },
   
  //**** Partie viewer *********************************************** 

 /*************************************************************
 * Permet d'afficher l'interface de recherche et de filtre
 * de tableau de tr 
 * @param : l'image sur laquelle on a cliqué 
 **************************************************************/    
  displaySearchInterface :  function(obj_img)
  {
    //- récupération du nom de la colonne à afficher
    var stri_colonne=obj_img.closest('td').attr('class');
    
    //- récupération de l'interface de recherche
    var obj_search_interface=this.getCritere(stri_colonne,'search_interface');
    
    if(obj_search_interface=='')//si pas d'interface existante, cas d'initialisation
    {
       //- récupération de la référence
        //var obj_table_search_ref=$('.table_with_search__constructTableForReference');
        var obj_table_search_ref=this.obj_view.find('.table_with_search__constructTableForReference');
        
             
        //- clonage de la référence et rattachement
        var obj_search_interface=obj_table_search_ref.clone();
            obj_table_search_ref.parent().append(obj_search_interface);   
            
        //- mise à jour de la classe css
        obj_search_interface.removeClass('table_with_search__constructTableForReference');
        obj_search_interface.addClass('table_with_search__colonne');
        
        //- récupération des coordonnées de l'image
       // obj_img.css('position','absolute');
      /*  var offset_img=obj_img.offset();
      
        
        //- postionnement de l'interface
        var int_x=offset_img.left-obj_table_search_ref.width();
        var int_y=offset_img.top;
        obj_search_interface.offset({'left':int_x,'top':int_y});    */
        
        //- positionnement dans le DOM
        obj_img.parent().prepend(obj_search_interface);
        
           
        //- sauvegarde de l'interface
        this.setCritere(stri_colonne,'search_interface',obj_search_interface);
        
        //- initialisation des valeurs distincts de la colonne
        var stri_nom_colonne=obj_img.closest('td').attr('class');
        var arra_value=this.getDistinctValueForColumn(stri_nom_colonne);
        this.setCritere(stri_colonne,'arra_distinct_value',arra_value);
      
        //- attachement modèle à l'interface
        obj_search_interface[0].obj_table_with_search=this;
        obj_search_interface[0].stri_nom_colonne=stri_nom_colonne;
        
        //- actualisation de la liste des valeurs distinctes
        this.actualiseDistinctValue(stri_nom_colonne,arra_value);
    }
    
      //- affichage de l'interface de recherche
       $('.table_with_search__colonne').css('display','none');//masquage des éventuelles interfaces précédemment ouvertes
       obj_search_interface.css('display','');  //affichage de l'interface courrante
             
      //- calcul des valeurs distinct sur les résultats précédants
      var arra_distinct=this.getDistinctValueForColumn(stri_colonne);
      
      //- enregistrement des valeurs distincts
      this.setCritere(stri_colonne,'arra_distinct',arra_distinct);  
   
      //- actualisation de l'affichage partie valeur distincts
      this.actualiseDistinctValue(stri_colonne,arra_distinct);
      
      //- réinitialisation de l'entrer du filtre
      obj_search_interface.find('input[name="table_with_search__search"]').val('');
                   
      
  },
  
  /*************************************************************
 * Permet de masquer l'interface de recherche
 * de tableau de tr 
 * @param :obj_jquery : l'élement de l'interface de recherche sur lequel on a cliqué
 **************************************************************/    
  hideSearchInterface :  function(obj_jquery)
  {
      var obj_search_interface=obj_jquery.closest('.table_with_search__colonne');
      obj_search_interface.css('display','none');
  },

   
  /*************************************************************
 * Permet de réinitialiser l'ensemble des critères de recherche
 * de tableau de tr 
 * @param :obj_jquery : l'élement de l'interface de recherche sur lequel on a cliqué
 **************************************************************/    
  resetSearch : function(obj_jquery)
  {
     //- restauration de l'ensemble des résultats
     this.arra_resultat_filtre=this.arra_resultat;
   
     //- restauration de icône 
     this.obj_view.find('.column_icon').attr('src',"images/drop_down_02.png");
     
     //- actualisation de l'affichage
     this.actualiseView(0);
     
     //- actualisation de la pagination
     this.actualisePagination();
     
     //- fermeture de l'interface de recherche
     obj_jquery.closest('.table_with_search__colonne').css('display','none');
  }, 
 
/*************************************************************
 * Permet de trier dans un ordre [croissant|decroissant] la colonne stri_colonne
 * de tableau de tr 
 * @param : stri_ordre  : [croissant|decroissant] 
 * @param :stri_colonne : le nom de la colonne à trier 
 **************************************************************/    
  Trier :  function(stri_ordre,obj_a)
  {
  
    //- récupération du nom de la colonne à afficher
    var obj_search_interface=obj_a.closest('.table_with_search__colonne');
    var stri_colonne=obj_search_interface[0].stri_nom_colonne;
    

    //- passage d'info de la colonne sur laquelle trier
    table_with_search.stri_colonne=stri_colonne;
   
    //- récupération du nom de méthode de tri
    var stri_methode='compare_'+stri_ordre;  
      
    //- triage des résultats
    this.arra_resultat_filtre.sort(table_with_search[stri_methode]);
    
    //- enregistrement du cristère de tri
    this.setCritere(stri_colonne,'tri',stri_ordre);      
        
    
    //- actualisation de la vue générale
    this.actualiseView(0);
    
    //- masquage de l'interface de recherche
    this.hideSearchInterface(obj_a);
      
  },
  
   //Permet de changer de page 
   //@param : le font sur lequel on a cliqué
   paginate : function(obj_font)
   {
     //- réinitialisation de la couleur des pages
     this.obj_view.find('.table_with_search__page').css('color','black');
     obj_font.css('color','red');
    
     //- actualisation de la vue 
     this.actualiseView(obj_font.data('min'));
    
   },
  
  /**  
    Actualisation de l'interface graphique
    @param : int_min : le numéro de ligne minimum à partir duquel afficher
   **/
  actualiseView : function(int_min)
  {
    //- récupération des tr
    //var arra_tr=this.obj_view.find('tr');
    var arra_tr=this.obj_view.find('.tr_resultat');
     
    
    //- actualisation des résultats visible
    for(var i=0;i<this.int_nb_ligne_page-1;i++)
    {
      //-- récupération du tr affiché
      var obj_tr=arra_tr[i+1];
      
      //-- récupération des td
      var arra_td=$(obj_tr).children();
      
      for(var j=0;j<arra_td.length;j++)
      {
        //--- récupération du nom de colonne correspondant
         var stri_colonne=this.arra_column_name[j];
  
         //--- actualisation de la valeur du td
         var stri_value='';
         var obj_td= $(arra_td[j]);
             obj_td.css('display','none');//masquage par défaut 
        
         if(this.arra_resultat_filtre[i+int_min])
         {
          stri_value=this.arra_resultat_filtre[i+int_min][stri_colonne];
        
          //obj_td.html(stri_value);
           obj_td.find('div').first().html(stri_value);
          obj_td.css('display','');  //affichage si des données existes                    
         }               
      }
      
      if(this.stri_template_onclick!='')
      {
        var obj_td=arra_td.last();
        var obj_img=$(document.createElement('img'));
           obj_img.attr('src','images/module/PNG/arrow-right-032x032.png');
           obj_img.css('cursor','pointer');
        var stri_url=this.templateUrl(this.arra_resultat_filtre[i+int_min]);    
            obj_img.attr('onclick','window.open("'+stri_url+'");');
       
        obj_td.html(obj_img);
      }
          
    }             
  },
  
  //Permet d'actualiser l'affichage de la pagination
  actualisePagination : function()
  {
   //- actualisation de la pagination
   //-- récupéréation d'un font de référence
    var obj_td_pagination=this.obj_view.find('.table_with_search__constructPagination');
    var obj_font_ref=obj_td_pagination.find('.table_with_search__page').first();
   
   //-- réinitialisation des pages
   obj_td_pagination.html(''); 
    
   //-- traitement de pages 
    var int_max=0;
    var int_nb_page=Math.ceil(this.arra_resultat_filtre.length/this.int_nb_ligne_page);
  
    for(var i=0;i< int_nb_page-1;i++)
    {
      var int_libelle_page=i+1;
      var int_min=i*this.int_nb_ligne_page;
      var int_max=int_min+this.int_nb_ligne_page-1;
      var obj_font=obj_font_ref.clone();
          obj_font.html(int_libelle_page);
          obj_font.data('min',int_min);
          obj_font.data('max',int_max);
         
      obj_td_pagination.append(' ');
      obj_td_pagination.append(obj_font);
    }
     //- traitement de la dernière page
    var int_min=(int_nb_page-1)*this.int_nb_ligne_page;
    var int_max=this.arra_resultat_filtre.length;
    var int_libelle_page=int_nb_page;
    var obj_font=obj_font_ref.clone();
          obj_font.html(int_libelle_page);
          obj_font.data('min',int_min);
          obj_font.data('max',int_max);         
    obj_td_pagination.append(' ');
    obj_td_pagination.append(obj_font);
  },
  
  //Permet d'actualiser l'affichage de la liste des valeurs distincts  pour une colonne donnée
  actualiseDistinctValue : function(stri_nom_colonne,arra_value)
  {
    var stri_values=arra_value.join("\r\n");
   
    //- récupération de l'interface de recherche dédiée
    var obj_search_interface=this.getCritere(stri_nom_colonne,'search_interface');
        obj_search_interface.find('textarea[name="table_with_search__search_result"]').val(stri_values);
   
  },
 
  //Permet d'initialiser toute la table en js
  initInJs : function(arroc_result)
  {
    //- changement des résultats
    this.arra_resultat=arroc_result;
    this.arra_resultat_filtre=arroc_result;
    
    //- création des header colonnes
    var arra_header=Object.keys(arroc_result[0]);
    var obj_td_ref=this.obj_view.find('.c_ref');
    var obj_tr=obj_td_ref.closest('tr');
        
    this.arra_column_name=[];
    for(var i=0;i<arra_header.length;i++)
    {
     //-- récupération du nom du champ
     var stri_nom_champ=arra_header[i];
     
     //-- enregistrement du nom de colonne
     this.arra_column_name.push(stri_nom_champ);
      
     //-- clonage du header de référence
     var obj_td_clone=obj_td_ref.clone();
     obj_tr.append(obj_td_clone);

     //-- actualisation des valeurs
      obj_td_clone.attr('class',stri_nom_champ);
      var obj_img=obj_td_clone.find('img').detach();
      obj_td_clone.html(stri_nom_champ);
      obj_td_clone.append(obj_img);   
      
      //-- suppression de la référence
      obj_td_ref.remove();
    }
    
    //- création des lignes visibles
    var int_min=Math.min(this.int_nb_ligne_page,arroc_result.length);
    for(var i=0;i<int_min;i++)
    {
      var arroc_one_res=arroc_result[i];
      var arra_key=Object.keys(arroc_one_res);
      var obj_tr=$(document.createElement('tr'));
          obj_tr.addClass('tr_resultat');
      this.obj_view.append(obj_tr);
      
        //-- alternance de couleur
     var stri_color=(i%2==0)?window.color3:window.color1;
     obj_tr.css('background-color',stri_color); 
      
      for(var j=0;j<arra_key.length;j++)
      {
        var obj_td=$(document.createElement('td'));
            obj_td.attr('onmouseover',"table_with_search.displayCompleteDiv($(this).find('.div_limitation'));"); 
            obj_td.attr('onmouseout',"table_with_search.hideCompleteDiv($(this).find('.div_limitation'));");
          
        //- gestion div d'affichage complette
        var obj_div=$(document.createElement('div'));
            obj_div.html(arroc_one_res[arra_key[j]]);
            obj_div.css('max-height','40px');
            obj_div.css('max-width','800px');  
            obj_div.css('overflow','hidden');           
            obj_div.addClass('div_limitation'); 
        //obj_td.html(arroc_one_res[arra_key[j]]);
        obj_tr.append(obj_td);
        obj_td.append(obj_div);
        
      } 
                                                 
      //- création d'une icône pour se déplacer
      if(this.stri_template_onclick!='')
      {
       var obj_img=$(document.createElement('img'));
           obj_img.attr('src','images/module/PNG/arrow-right-032x032.png');
           obj_img.css('cursor','pointer');
       var stri_url=this.templateUrl(arroc_one_res);    
           obj_img.attr('onclick','window.open("'+stri_url+'");');
       var obj_td=$(document.createElement('td'));
           obj_td.append(obj_img);
       obj_tr.append(obj_td);
      }   
    } 
    
    //- gestion de pagination
    var obj_td_pagination=this.obj_view.find('.table_with_search__constructPagination');
        obj_td_pagination.attr('colspan',arra_header.length);
    var obj_tr_pagination=obj_td_pagination.parent().detach();
        this.obj_view.append(obj_tr_pagination);  
        //this.resetSearch(this.obj_view);
        this.actualisePagination();
  },
  
   //Permet de créer l'url réelle sur laquelle se rendre
  templateUrl : function(arroc_result)
  {
    //- récupération des clef
    var arra_key=Object.keys(arroc_result);
    
    var stri_template=this.stri_template_onclick.replace(/&amp;/g,'&');
  
    for(var i=0;i<arra_key.length;i++)
    {
      //-- création du template
      var stri_one_template='['+arra_key[i]+']';
      
      //-- détection de présence
      if(stri_template.indexOf(stri_one_template))
      {
        //--- remplacement du template par sa valeur
        stri_template=stri_template.replace(stri_one_template,arroc_result[arra_key[i]]);
      }
     
    }
    
    return stri_template;
  }
  
 
  //**** Partie manager **********************************************   
  
 
} 