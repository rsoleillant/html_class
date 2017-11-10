/*******************************************************************************
Create Date : 12/07/2017
 ----------------------------------------------------------------------
 Class name : ultra_select
 Version : 1.0
 Author : ROBERT Romain 
 Description : Permet de représenter  en js la classe php ultra_select.
********************************************************************************/
function ultra_select(mixed_json)
{
  //** Attributs simples ************************************************
  
  //**** Constructeur *************************************************** 
  this.construct=function(mixed_json)
  {
   
  }
  this.construct(mixed_json);
} 

//** Attributs statiques *******************************************************



//**** Initialisation DOM ******************************************************
$(function()
{
    //- Déclenche l'action change sur l'ensemble des option sélectionnée afin 
    //- d'actualiser le tooltip utililsateur dès que le DOM est chargé
    
    $('.ultra_select').each(function()
    {
        $(this).find('.ultra_select__option_checkbox:checked').first().trigger('change');
    });
    
    
});


//**** Méthodes statiques ******************************************************

/**
 *  Méthode permettant d'ouvrir ou de fermer un ultra_select
 *  
 * @param {type} obj_tr 
 * @returns {unresolved}
 */
ultra_select.toggle =function(event, obj_div)
{
    //- Référence vers la table
    var obj_ultra_select = $(obj_div).find('.ultra_select');
    
    //- Méthode d'expand ou collapse
    var stri_method = obj_ultra_select.data('method_toggle');
    
    //- Appel méthode
    return ultra_select[stri_method](obj_div);
    
}

/**
 * Méthode d'ouverture d'un ultra_select
 * 
 * @param {type} obj_ultra_select
 * @returns {undefined}
 */
ultra_select.expand= function(event, obj_ultra_select)
{

    //- Disable la reouverture
    obj_ultra_select.prop('onclick','');
    obj_ultra_select.unbind('click');
    
    $(obj_ultra_select).stop(true,true);
    
    //- Cible la table
    obj_ultra_select = $(obj_ultra_select).find('.ultra_select');
    //- Activation event
    obj_ultra_select.bind('mouseenter',ultra_select.clearTimer);
    
    //- Gestion des z-index. Le dernier cliqué passe devant
    window.int_ultra_select__zindex=(! window.int_ultra_select__zindex) ?  10 : window.int_ultra_select__zindex+1 ;
    obj_ultra_select.css('z-index',window.int_ultra_select__zindex);
    
    
    //- Ajout d'une classe CSS
    $(obj_ultra_select).removeClass('ultra_select__collapse').addClass('ultra_select__expand');

    
    //- Défintion des sélecteurs
    var arra_selector_hide = $(obj_ultra_select).find('.ultra_select__selection');
    var arra_selector_show = $(obj_ultra_select).find('.ultra_select__search').add($(obj_ultra_select).find('.ultra_select__options'));
    
    //- Autre sélecteur
    var selector_animate=$(obj_ultra_select).find('.ultra_select__ul');
    var selector_search=$(obj_ultra_select).find('.ultra_select__input_search');
    
    //- Masque 
    $(arra_selector_hide).hide(90,function()
    {
        //- Affiche
        $(arra_selector_show).show(90);
        
        //- Animation
        //$(selector_animate).animate({height: "300px"},100);
        $(selector_animate).animate({height: $(obj_ultra_select).width()},100);
        
        //- Focus sur l'input de recherche
        if (!$(selector_search).is(':focus'))
        { $(selector_search).focus(); }
        
    });

    //- Changement attribut data méthode
    $(obj_ultra_select).data('method_toggle','collapse');
    
    
    
    //- Event en commun 
    $(".ultra_select__option_checkbox").add($(selector_search))
    //- Raz Event
    .unbind('keydown')
    //- Pose event
    .keydown(function(event)
    {
        /*
        Escape : 27
        Enter : 13
        */
       //- Détection fermeture select
        if (in_array([27, 13], event.keyCode))
        {
            //- Ferme le select
            ultra_select.collapse(event, $(this).closest('.ultra_select__wrapper'));  
            //- Stop l'event 
            event.stopPropagation();
            return false;
        }
        
        
    });
    
    
    //- Gestion des event keypress depuis l'input de receherche 
    $(selector_search).keydown(function(event)
    {
        //- si fleche haut, bas
        if (in_array([40,38,9], event.keyCode))
        {
            //- Cilble la première checkbox parmis les options non filtrés
            var arra_obj_li = $(obj_ultra_select).find('.ultra_select__option').filter(function()
            {
                if ($(this).data('filter_match') == 'match' || $(this).css('display')!=='none')
                { return $(this); }
            });
            
            //- Un filtre est réalisé ? 
            if ($(arra_obj_li).length == 0)
            { arra_obj_li = $(obj_ultra_select).find('.ultra_select__option_no_result'); }

            //- Déduction méthode de parcours du DOM
            var stri_method = (event.keyCode == 38) ? 'last' : 'first';
            
            //- Performe le focus
            $(arra_obj_li)[stri_method]().find('.ultra_select__option_checkbox').focus();
            
            //- Stop le traitement
            event.stopPropagation();
            return false;
        }
        
    });
    
    //- Event depuis la checkbox
    $(".ultra_select__option_checkbox").keydown(function(event)
    {
        //console.log(event.keyCode);
        
        if (in_array([40,38,9], event.keyCode))
        {
            //- Référence vers la checkbox keypressé
            var Me = $(this);
            
            //- Recherche l'ensemble des options non filtrés et donc visible
            var arra_obj_li = $(this).closest('.ultra_select__ul').find('.ultra_select__option').not('.ultra_select__option_checkbox_add').filter(function()
            {
                //- Condition si visible dans DOM
                if ( $(this).css('display') != 'none')
                { return $(this); }
            });
            
            //- Référence vers l'input qui doit êter focusé
            var obj_selector;
            
            //- Parcours des options non filtrés
            for (var i=0; i<arra_obj_li.length; i++)
            {
                //- L'option en cours
                var obj_li = arra_obj_li[i];
                
                //- Recherche sa checkbox
                var obj_selector_checkbox  = $(obj_li).find('.ultra_select__option_checkbox');
                
                //- Détection de l'instance en cours dans la collection
                if (obj_selector_checkbox.is( Me ))
                { 
                    
                    //- Déduction de l'index à utiliser
                    var int_idx = ( event.keyCode == 38) ?  i-1 : i+1;
                    
                    //- Récupération du li 
                    var obj_selector_li = $(arra_obj_li[int_idx]);
                    
                    
                    //- Retour départ ? 
                    if (arra_obj_li.length == int_idx )
                    { obj_selector_li = $(arra_obj_li[0]);   }
                    
                    //- Direction fin de liste ?
                    if (int_idx < 0)
                    {  obj_selector_li = $(arra_obj_li[$(arra_obj_li).length-1]); }
                    
                    //- Récupération de l'input
                    obj_selector = $(obj_selector_li).find('.ultra_select__option_checkbox'); 
                    
                    break;
                }
            }

            //- Focus sur l'input
            $(obj_selector).focus();
            
        }
        else
        {
            /*
            Escape : 27
            Backspace : 8
            Space : 32
            Shift : 16
            Control : 17
            Alt : 18
            Enter : 13
            */
            //console.log(event.key+' : '+event.keyCode);

            var arra_key_interdite = [16, 17, 18, 8, 27, 32, 13];
            var arra_key_spe = [8];
            
            var arra_key_autorise = [27, 32, 13];

            //- On autorise la touche espace
            if (in_array(arra_key_autorise, event.keyCode))
            { return true; }
            
            if (in_array(arra_key_spe, event.keyCode))
            { 
                //- Gestion du backspace (effacement chariot)
                var stri_value = $(selector_search).val();
                stri_value = stri_value.substr(0, stri_value.length-1);
                
                //- Définition
                $(selector_search).val( stri_value ).focus();
            }            
            
            
            //- On vérifie que la touche n'est pas spéciale
            if (!in_array(arra_key_interdite, event.keyCode))
            { 
                //$(selector_search).val( event.key ).focus(); 
                $(selector_search).val( $(selector_search).val() + event.key ).focus(); 
            }
             
            
        }
        
        //- Stop l'event dans tous les cas
        event.stopPropagation();
        return false;
        

    });
    
    
    //- Event pour femeture générale via souris
    $('body').bind('click',function(event)
    {
        //- Fermeture générale
        ultra_select.collapseAll(event); 
        
    });
    
    
    
}

/**
 * Méthode sur onclick body 
 * 
 * @param {type} event
 * @returns {Boolean}
 */
ultra_select.collapseAll= function(event)
{

    //- Si c'est un ultra_select de cliqué
    if ($(event.target).hasClass('ultra_select__selection_div') || $(event.target).closest('.ultra_select').length != 0)
    { return false; }
    
    //- Sinon, traitement affichage
    var arra_obj_selector = $('.ultra_select__expand').closest('.ultra_select__wrapper');
    for (var i=0; i<$(arra_obj_selector).length;i++)
    {
        //- Femeture ultra_select
        var obj_ultra_select = arra_obj_selector[i];
        ultra_select.collapse(event,$(obj_ultra_select));
    }
     
    //- Puis on désactive le click de fermeture général
    $('body').unbind('click');
    
}



/**
 * Méthode fermeture d'un ultra_select
 * @param {type} obj_ultra_select
 * @returns {undefined}
 */

ultra_select.collapse= function(event, obj_ultra_select)
{
 
    //- Réactive l'ouverture
    //obj_ultra_select.attr('onclick','ultra_select.expand(event, $(this));');
    obj_ultra_select.click(function()
    {
        ultra_select.expand(event , $(this));
    });
    

    //- Cible la table
    obj_ultra_select = $(obj_ultra_select).find('.ultra_select');
    //- On désactive l'event de fermeture
    $(obj_ultra_select).unbind('mouseenter');

    //- Ajout d'une classe CSS
    $(obj_ultra_select).removeClass('ultra_select__expand').addClass('ultra_select__collapse');

    //- Arret des annimation dans le cas de plusieurs click
    $(obj_ultra_select).stop(true,true);

     //- Défintion des sélecteurs
    var arra_selector_show = $(obj_ultra_select).find('.ultra_select__selection');
    var arra_selector_hide = $(obj_ultra_select).find('.ultra_select__search').add($(obj_ultra_select).find('.ultra_select__options'));

    //- Autre sélecteur
    var selector_animate=$(obj_ultra_select).find('.ultra_select__ul');


    //- On masque le champ de recherche et les données
    $(selector_animate).animate({height: "0px"},90,function()
    {
        //- Masque
        $(arra_selector_hide).hide();

        //- Et on affiche la sélection utilisateur
        $(arra_selector_show).show();

    });


    //- Changement attribut data méthode
    $(obj_ultra_select).data('method_toggle','expand');

   
    
    
}
/*
 * 
//- Méthode with timer pour fermeture 

ultra_select.collapse= function(event, obj_ultra_select)
{
    
    //- Timer pour fermeture 
    //- Evite de cloturé le select dans le cas d'un mouseout, mouseover sur le meme elements
    tm_collapse = setTimeout(function()
    {
        //- Réactive l'ouverture
        obj_ultra_select.attr('onclick','ultra_select.expand(event, $(this));');
    
        //- Cible la table
        obj_ultra_select = $(obj_ultra_select).find('.ultra_select');
        //- On désactive l'event de fermeture
        $(obj_ultra_select).unbind('mouseenter');

        //- Ajout d'une classe CSS
        $(obj_ultra_select).removeClass('ultra_select__expand').addClass('ultra_select__collapse');

        //- Arret des annimation dans le cas de plusieurs click
        $(obj_ultra_select).stop(true,true);
        
         //- Défintion des sélecteurs
        var arra_selector_show = $(obj_ultra_select).find('.ultra_select__selection');
        var arra_selector_hide = $(obj_ultra_select).find('.ultra_select__search').add($(obj_ultra_select).find('.ultra_select__options'));

        //- Autre sélecteur
        var selector_animate=$(obj_ultra_select).find('.ultra_select__ul');


        //- On masque le champ de recherche et les données
        $(selector_animate).animate({height: "0px"},90,function()
        {
            //- Masque
            $(arra_selector_hide).hide();

            //- Et on affiche la sélection utilisateur
            $(arra_selector_show).show('slow');
                
        });
        

        //- Changement attribut data méthode
        $(obj_ultra_select).data('method_toggle','expand');
        
        
    },500, obj_ultra_select);
    
    
}
*/

/**
 * Timer pour la fermeture 
 * 
 * @type type
 */
/*var tm_collapse;
ultra_select.clearTimer= function()
{
    if (tm_collapse)
    {
        clearTimeout(tm_collapse);
    }
}
*/




    
    
/**
 * Méthode appelé lors d'un changement d'état d'une checkbox (ultra_select multiple)
 * 
 * @param {type} obj_checkbox
 * @returns {undefined}
 */
ultra_select.updateSelected = function(obj_checkbox)
{
    
    
    //- Ref vers la table
    var obj_ultra_select =  $(obj_checkbox).closest('.ultra_select');
    
    //- Ref vers le UL
    var obj_ul = $(obj_ultra_select).find('.ultra_select__ul');
    
    //- State
    var bool_select_multiple = $(obj_ultra_select).hasClass('ultra_select__multiple') ;
    var bool_checkbox_added = $(obj_checkbox).hasClass('ultra_select__option_checkbox_added');
    
    //- Gestion required
    if ($(obj_checkbox).is(':checked') && !$(obj_checkbox).hasClass('ultra_select__option_default'))
    { 
        //- On a une option de coché
        $(obj_ultra_select).find('.ultra_select__option_checkbox').removeProp('required'); 
        
        //- désactivation option par défaut
        $(obj_ultra_select).find('.ultra_select__option_default').prop({disabled:'disabled'}).removeProp('checked'); 
        
    }
    else if ($(obj_ultra_select).find('.ultra_select__option_checkbox:checked').length == 0)
    {
        //- Réactivation required ?
        $(obj_ultra_select).find('.ultra_select__option_checkbox').prop('required','required');
        
        //- activation option par défaut
        $(obj_ultra_select).find('.ultra_select__option_default').prop({checked: true}).removeProp('disabled'); 
    }
    
    
    //- Gestion option ajoutée manuellement dans les select non multiples
    if (!bool_select_multiple )
    {   
        //- Autorise qu'une seule sélection
        $(obj_ultra_select).find('.ultra_select__option_added').find('.ultra_select__option_checkbox').prop('checked',false); 
    }
    
    //- Gestion checkbox ajouté
    if (bool_checkbox_added || !bool_select_multiple)
    {
        var bool_checked = $(obj_checkbox).is(':checked');
        if (!bool_select_multiple )
        { $(obj_ultra_select).find('.ultra_select__option').find('.ultra_select__option_checkbox').prop('checked',false); }
        
        $(obj_checkbox).prop('checked',bool_checked);
    }
    
    
    
    //- Gestion des options ajoutables 
    if ($(obj_checkbox).hasClass('ultra_select__option_checkbox_add'))
    {
        //- Détection d'un nouvel ajout
        ultra_select.appendNewOption(obj_ultra_select, obj_checkbox);
    }
    
    
    //- RAZ
    $(obj_ul).find('.ultra_select__option_selected').removeClass('ultra_select__option_selected');
   
    
    
    //- Référence vers le LI de l'option 
    var obj_li = $(obj_checkbox).closest('.ultra_select__option');
    
    //- Déduction méthode CSS
    var stri_method = $(obj_checkbox).is(':checked') ? 'addClass' : 'removeClass';
    
    //- Appel 
    $(obj_li).find('.ultra_select__option_label')[stri_method]('ultra_select__option_selected');
    $(obj_li).closest('.ultra_select__group').addClass('ultra_select__group_selected');
    
    
    //- Gestion select simple
    if (!bool_select_multiple)
    {
        $(obj_ul).find('.ultra_select__group_selected').removeClass('ultra_select__group_selected');
        $(obj_li).closest('.ultra_select__group').addClass('ultra_select__group_selected');
		
    }
    
	//- Gestion selection group 
    if ($(obj_ul).find('.ultra_select__option_checkbox:checked').length==0)
    { $(obj_ul).find('.ultra_select__group_selected').removeClass('ultra_select__group_selected'); }


    //- Mise a jour sélection user,
    var arra_obj_checkbox = $(obj_ul).find('.ultra_select__option_checkbox:checked').not('.ultra_select__option_default');
    
    //- Stockage tetmporaire des options sélectionnées
    var arra_libelle = new Array();
    
    //- Parcours
    for (var i=0; i<$(arra_obj_checkbox).length; i++)
    {
        //- Ajout du libelle
        arra_libelle.push($(arra_obj_checkbox[i]).parent().find('.ultra_select__option_label').html());
    }
    //- Création du libelle complet
    var stri_libelle_user = arra_libelle.join(', ');
    
    
    
    //- Construction de l'infobulle 
    var stri_infobulle  = $(arra_obj_checkbox).length+window.pnlang['__LIB_ULTRA_SELECT__NB_SELECTED']+arra_libelle.join('<br/> - ');
    
    //- Gestion aucun choix
    if (arra_libelle.length==0)
    {
        stri_libelle_user = window.pnlang['__LIB_ULTRA_SELECT__CHOIX'];
        stri_infobulle = window.pnlang['__LIB_ULTRA_SELECT__CHOIX'];
    }
    
    //- Taille maximum
    stri_infobulle = (stri_infobulle.length>= 500) ? stri_infobulle.substr(0,500)+' .....<br/> - ..... ': stri_infobulle;
    
    
    //- Définition du tooltip
    $(obj_ultra_select).find('.ultra_select__selection_div')
            .html(stri_libelle_user)
            .tooltip({ track: true })
            .prop('title',stri_infobulle).tooltip('option','content',stri_infobulle);
    
    
    
}


/**
 * Méthode d'ajout d'une option au sein d'un select
 * 
 * @param {type} obj_ultra_select
 * @param {type} obj_checkbox
 * @returns {Boolean}
 */
ultra_select.appendNewOption = function(obj_ultra_select, obj_checkbox)
{
    //- Méssage de confimation
    if (!confirm(window.pnlang['__LIB_ULTRA_SELECT_ALERT_ADD_OPTION']))
    {
        //- On annule ?
        $(obj_checkbox).prop('checked', false);
        return false;
    }
    else
    {
        //- On accepte ?
        
        //- Clone la nouvelle option
        var obj_li_temp = $(obj_checkbox).closest('.ultra_select__option').clone();
        //- Flag
        $(obj_li_temp).addClass('ultra_select__option_added');
        
        //- Raz attribut d'ajout
        $(obj_li_temp).find('.ultra_select__option_checkbox').removeClass('ultra_select__option_checkbox_add').addClass('ultra_select__option_checkbox_added');
        $(obj_li_temp).removeClass('ultra_select__option_no_result').find('.ultra_select__option_label').removeClass('ultra_select__option_label_add');
         
        //- RAZ HTML inutiles
        $(obj_li_temp).find('font, br').remove();
        //- Activation du onclick sur le clone
        $(obj_li_temp).find('label').prop('for','').click(function()
        {
            $(this).prev().trigger('click');
        });
        

        //- Pose dans DOM
        $(obj_ultra_select).find('.ultra_select__option_add').show().append(obj_li_temp);
        
        //- Puis on décoche la checkbox d'origine
        $(obj_checkbox).prop('checked', false);
        
        var obj_input_search = $(obj_ultra_select).find('.ultra_select__input_search');
        
        //- RAZ input de recherche
        $(obj_input_search).val('').focus();
        
        //- Gestion des select non multiple 
        if (!$(obj_ultra_select).hasClass('ultra_select__multiple'))
        {
            //- Désactive la sélection dans les option disponible et garde uniquement celle ajoutée manuellement
            $(obj_ultra_select).find('.ultra_select__option').not('.ultra_select__option_added').find('.ultra_select__option_checkbox').prop('checked',false);
        }
        
        //- Déclechement recherche
        ultra_select.filterOptions(obj_input_search);
        
        //- Attente retour filterOptions(); => 25ms.
        setTimeout(function()
        {
            //- Ajoute les event keypress sur les options ajoutées
            ultra_select.expand(window.event, obj_ultra_select);

            //- Focus
            $(obj_li_temp).find('.ultra_select__option_checkbox_added').focus();
            
        },50);
        
        
        
        

    }
    
}



//- Timer pour performance dans la recherche
window.ultra_select__tm_filter_options;

/**
 * Méthode pour filtrer les options disponlbiles dans une liste déroulante
 * 
 * @param {type} obj_input
 * @returns {undefined}
 */
ultra_select.filterOptions = function( obj_input)
{
    
    //- RAZ ancienne recherche 
    if (window.ultra_select__tm_filter_options)
    { clearTimeout(ultra_select__tm_filter_options); }
    
    //- Timer pour performance 
    window.ultra_select__tm_filter_options = setTimeout(function ()
    {
        //- Valeur recherché 
        var stri_search = $(obj_input).val();
        stri_search = convertSpecialChar(stri_search);


        //- Réf vers l'ultra select
        var obj_ultra_select = $(obj_input).closest('.ultra_select');

        //- Les options disponibles
        var arra_obj_li = $(obj_ultra_select).find('.ultra_select__option').not('.ultra_select__option_no_result');

        //- Méthode permettant de masquer ou d'affichager l'option
        var stri_method;
        var stri_match;

        //- On masque les groupes
        $(obj_ultra_select).find('.ultra_select__group').hide();


        //- Parcours des options
        for (var i=0; i<$(arra_obj_li).length; i++)
        {
            //- Méthode par défaut
            stri_method='show';
            stri_match = 'match';

            //- L'option en cours
            var obj_li = arra_obj_li[i];

            //- RegExp pour réaliser le matching
            var obj_regexp = new RegExp(stri_search,'i');

            //- Valeur de l'option
            var stri_value_option = convertSpecialChar($(obj_li).find('.ultra_select__option_label').html());

            //- Matching
            var arra_match = stri_value_option.match(obj_regexp);

            //- Si pas de correspondance 
            if (!arra_match)
            {
                //- On masque l'option
                stri_method = 'hide';
                stri_match = 'no_match';
            }
            
            //- Appel dynamique [show | hide]
            $(obj_li)[stri_method]();

            //- Flag
            $(obj_li).data('filter_match',stri_match);


        }


        //- Gestion affichage des groupes
        $(obj_ultra_select).find('.ultra_select__option').filter(function()
        {
            //- Si l'option est affiché, on affiche son groupe
            if ($(this).css('display')!=='none')
            { $(this).closest('.ultra_select__group').show(); }
        });


        //- Gestion "aucun résultats" (si nombre de no_match == nombre d'option )
        var arra_no_match = $(obj_ultra_select).find('.ultra_select__option').filter(function()
        {
            //- Recherche les li contenant l'attribut data 
            if ($(this).data('filter_match') == 'no_match')
            { return this; }
        });

        //- Traitement, si le nombre de li == le nombre qui ne match pas 
        stri_method = 'hide';
        if (arra_no_match.length == arra_obj_li.length)
        { stri_method='show'; }

        //- Appel méthode
        $(obj_ultra_select).find('.ultra_select__option_no_result')[stri_method]();

        //- Mise à jour de l'option ajoutable
        $(obj_ultra_select).find('.ultra_select__option_checkbox_add').val(stri_search);
        $(obj_ultra_select).find('.ultra_select__option_label_add').html(stri_search);

        //- Flag
        $(obj_ultra_select).find('.ultra_select__option_added').show();
    
    
    },25);
   
    
    
    
}


/**
 * Méthode pour cocher/décocher l'ensemble des options d'un groupe
 * 
 * @param {type} obj_checkbox
 * @returns {undefined}
 */
ultra_select.toggleCheckboxGroup = function(obj_checkbox)
{
    
    //- Référence vers le group 
    var obj_selector_group = $(obj_checkbox).closest('.ultra_select__group');
    
    //- Recherche les options non filtrés
    var arra_checkbox = $(obj_selector_group).find('.ultra_select__option').filter(function()
    {
        if ($(this).css('display')!=='none')
        { return $(this); }
        
    }).find('input');
    
    //- Toggle checkbox 
    $(arra_checkbox).prop('checked',  $(obj_checkbox).is(':checked'));

    //- On trigger le onChange sur le premier élement pour performance
    $(arra_checkbox).first().trigger('change');
    
    
}



//- Gestion du oninvalid sur Ultra_Select
document.addEventListener('invalid', 
    function(e)
    {
        //- Permet de ne pas afficher l'infobulle d'erreur car les checkboxs sont masqués par défaut
        if ($(e.target).hasClass('ultra_select__option_checkbox'))
        {
            e.preventDefault();   
            return false;
        }
    }
, true);

window.arra_oninvalid=new Array();
window.tm_oninvalid;
ultra_select.onInvalid = function(event,  obj_checkbox)
{
    
    //- L'ultra select de référence
    var obj_ultra_select = $(obj_checkbox).closest('.ultra_select__wrapper');
    //- Son Nom
    var stri_id = $(obj_ultra_select).prop('class');

    //- Si il n'a pas été actualisé
    if (!in_array(window.arra_oninvalid, stri_id ))
    {
        //- On l'ouvre
        ultra_select.expand(event,$(obj_ultra_select));
        
        //- Et on le sauvegarde
        window.arra_oninvalid.push(stri_id);
        
    }
    
    
    //- Gestion RAZ
    if (window.tm_oninvalid)
    { clearTimeout(window.tm_oninvalid); }
    window.tm_oninvalid = setTimeout(function()
    { window.arra_oninvalid=new Array();  },1000);
    
    
}




/**
 * Méthode de clonnage d'un ultra_select en JS.
 * Permet d'incrementer l'index des données réceptionnées en POST
 * 
 * 
 * @param {type} obj_ultra_select
 * @returns {undefined}
 */
ultra_select.clone= function(obj_ultra_select)
{
    if (! $(obj_ultra_select).hasClass('ultra_select__wrapper'))
    { alert('Référence vers l\'ultra_select incorrect. Merci de cibler ultra_select__wrapper'); }
    
    var stri_name = $(obj_ultra_select).prop('name');
    
    //- Nombre max d'ultra_select 
    var int_max_ultra_select = $('.ultra_select__wrapper[name="'+stri_name+'"]').length + 1;
    
    //- Recherche ses inputs
    var arra_input = $(obj_ultra_select).find('input').not('.ultra_select__input_search');
    
    //- Recupération de leurs noms
    var stri_name = $(arra_input[0]).attr('name');

    //- Remplacement index
    $(arra_input).attr('name',stri_name.replace(/\[\w*\]/, '['+int_max_ultra_select+']'));

    
}



/**
 * Méthode non utilisée. POC remplacement ajaxMultiSelect échoué pour cause de temps.
 * 
 * @param {type} stri_name
 * @param {type} stri_id
 * @param {type} obj_checkbox
 * @returns {undefined}
 */
ultra_select.nextSelect= function(stri_name, stri_id , obj_checkbox)
{
    /*
    console.log(stri_name);
    console.log(stri_id);
    console.log(obj_checkbox);
    */

    $.ajax({
      type: "POST",
      url: "includes/classes/html_class/ultra_select.class.php",
      data: { 
          ultra_select__ajax: "1", 
          ultra_select__id: stri_id, 
          ultra_select__name: stri_name, 
          ultra_select__value: $(obj_checkbox).val()
      }
    })
    .done(function( stri_json ) {

        //- Parse le json
        var obj_json = JSON.parse(stri_json);
        
        //- obj_json.selector => Le sélecteur déduie les via arra_select en PHP 
        //- obj_json.html     => Le contenue du select à poser dans le DOM
        //- Remplace l'ultra select 
        $('.'+obj_json.selector).replaceWith(obj_json.html);

    });
     
}


/**
 * Autorise la recherche sur les caractères similaire
 * 
 * @param {type} stri_value
 * @returns {string}
 */
function convertSpecialChar(stri_value)
{
    
    stri_value = stri_value.replace(/é/g, 'e');
    stri_value = stri_value.replace(/è/g, 'e');
    stri_value = stri_value.replace(/à/g, 'a');
    
    return stri_value;
    
}


//Transposition de la fonction in_array PHP en javascript
function in_array(tableau, p_val) 
{

  for(var i = 0, l = tableau.length; i < l; i++) {
  
      if(tableau[i] == p_val) {
          rowid = i;
         
          return true;
      }
  }
  return false;
}
   
   
   
   
