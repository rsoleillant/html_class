<?php
/* * *****************************************************************************
  Create Date : 23/02/2015
  ----------------------------------------------------------------------
  File name : global.js.php
  Version : 1.0
  Author : Rémy Soleillant
  Description : Permet de déclarer les constantes PHP de langues destinnées à être affichées
  en PHP et javascript. Ces constantes sont utilisées par les classes html
 * ****************************************************************************** */

//- ici définition des constante de langue en php destinée au classes html 
define("_TRI_ASC","Colonne triée croissante");
define("_TRI_DESC","Colonne triée décroissante");
define("_TRI_NONE","Colonne non triée");
define("_LIB_PAGINATION","Pages : ");
define("_LIB_TRIER_PAR","Trier par");
define("_LIB_PUIS_PAR","Puis par");
define("_ACTION_TRIER","Lancer le tri");
define("_LIB_AJOUTER_TRI","Ajouter un tri");
define("_LIB_AUCUN_TRI","Aucun tri");
define("_LIB_DECROISSANT","décroissant");
define("_LIB_CROISSANT","croissant");
define("_ACTION_ANNULER","Annuler");
define("_LIB_TRIE_DES_COLONNES","Trier les colonnes");
define("_LIB_SUPPRIMER_TRI","Supprimer ce tri");

//- Easy_select
define('__LIB_PLACEHOLDER_EASY_SELECT','Filtrer puis, &#8593; et &#8595; pour naviguer.');
define('__LIB_NO_RESULT_EASY_SELECT','Aucun résultat.');

//- LiveTimeStamp
define('__LIB_IL_Y_A',"Il y a");
define('__LIB_LE',"Le");
define('__LIB_LESS_ONE_MINUTE',"Moins d'une minute");
define('__LIB_HEURES',"heures");
define('__LIB_MINUTES',"minutes");
define('__LIB_DAYS',"jours");


//- Ultra select
define('__LIB_ULTRA_SELECT_FILTRER',"Filtrer puis, &#8593; et &#8595; pour naviguer.");
define('__LIB_ULTRA_SELECT_NO_RESULT_ADD',"<i>Option innexistante.<br/> Ajouter manuellement ?</i>");
define('__LIB_ULTRA_SELECT_NO_RESULT',"Option innexistante.");
define('__LIB_ULTRA_SELECT_OPTION_ADD',"Option(s) ajoutée(s) : ");
	
?>
<!-- Constante en JS-->
<script>
    window.pnlang = [];
    window.pnlang._MSG_RESTAURER = "Restaurer le texte sauvegardé";   
    window.pnlang.__LIB_WEBSOCKET_ENABLE = "Notifications en temps réel actuellement <b style=\"color: green\">activé</b>.";
    window.pnlang.__LIB_WEBSOCKET_ENABLE_INFO = "Les fonctionnalités tels que la surveillance incident client, ou le rafraichissement automatique de pool sont actifs.";
    window.pnlang.__LIB_WEBSOCKET_DISABLE = "Notifications en temps réel actuellement <b style=\"color: red\">indisponible</b>.";
    window.pnlang.__LIB_WEBSOCKET_DISABLE_INFO = "Les fonctionnalités tels que la surveillance incident , ou le rafraichissement automatique de pool ne sont pas actifs.";
    window.pnlang.__LIB_OPEN_SURVEILLANCE = "Ouvrir manuellement le pop-up de surveillance incident";

    //- LiveTimeStamp
    window.pnlang.__LIB_IL_Y_A = "Il y a";
    window.pnlang.__LIB_LESS_ONE_MINUTE = "Moins d'une minute";
    window.pnlang.__LIB_HEURES = "heures";
    window.pnlang.__LIB_MINUTES = "minutes";
    window.pnlang.__LIB_DAYS = "jours";
    window.pnlang.__LIB_LE  = "Le";
    
    //- Ultra select
    window.pnlang.__LIB_ULTRA_SELECT__CHOIX  = '-- Faites votre choix --';
    window.pnlang.__LIB_ULTRA_SELECT__NB_SELECTED  = ' option(s) sélectionnée(s) : <br/> - ';
    window.pnlang.__LIB_ULTRA_SELECT__NO_RESULT  = 'Aucun résultat.';
    window.pnlang.__LIB_ULTRA_SELECT_ALERT_ADD_OPTION  = "Confirmez-vous l'ajout de l'option ? ";
    
   
   
   
    
</script>


<!-- Fin Constante JS -->

