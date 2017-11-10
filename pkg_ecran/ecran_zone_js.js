/* 
 * Create Date  : 
 *  ----------------------------------------------------------------------
 *  Class name  : 
 *  Version     : 1.0
 *  Author      : Romain ROBERT
 *  Description : 
 */


 //Masque ou affiche la zone cliqué
    function AfficheTr(obj_tr)
    {
        
        $(obj_tr).next().children().slideToggle();
       
       
       $(obj_tr).find('span').toggleClass('ui-icon ui-icon-triangle-1-s ui-icon ui-icon-triangle-1-n');
    }


    
 //Masque ou affiche la zone cliqué
    function AfficheTrV2(obj_tr)
    {
        
       $(obj_tr).next().children().slideToggle();
       
       
       $(obj_tr).find('span').toggleClass('reductAll expandAll');
       
       $(obj_tr).parents('table').first().find('.ecran_zone_v3__img_etc').toggle();
       
    }
    
//Masque ou affiche les zones votre demande (demande intial dans le cas de l'écran details) sans intéragir avec le bouton agrandir
    function showZoneByQuestion()
    {
        $('.reductible.demande_evo__reduct_zone_creation').children().show();
        $('.not_selection.demande_evo__reduct_zone_creation').find('span').removeClass();
        $('.not_selection.demande_evo__reduct_zone_creation').find('span').addClass('ui-icon ui-icon-triangle-1-n');
        $('#demande_evo__reduct_zone_creation').removeClass();
        $('#demande_evo__reduct_zone_creation').addClass('reductAll');
        $('#demande_evo__reduct_zone_creation').removeAttr('onclick');
        $('#demande_evo__reduct_zone_creation').attr("onClick","hideZone_demande_evo__reduct_zone_creation()");

    }
    
//Masque ou affiche les zones échanges sans intéragir avec le bouton agrandir
function showZoneByEchange()
    {
        $('.reductible.demande_evo__reduct_zone_echange').children().show();
        $('.not_selection.demande_evo__reduct_zone_echange').find('span').removeClass();
        $('.not_selection.demande_evo__reduct_zone_echange').find('span').addClass('ui-icon ui-icon-triangle-1-n');
        $('#demande_evo__reduct_zone_echange').removeClass();
        $('#demande_evo__reduct_zone_echange').addClass('reductAll');
        $('#demande_evo__reduct_zone_echange').removeAttr('onclick');
        $('#demande_evo__reduct_zone_echange').attr("onClick","hideZone_demande_evo__reduct_zone_echange()");

    }

//Désactive la sélection sur les en-tete des objets block et objet ecran zone
    $(function()
    {   
        $('.not_selection').disableSelection();
    });
    