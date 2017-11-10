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
define("_TRI_ASC","Ascending sorted column");
define("_TRI_DESC","Descending sorted column");
define("_TRI_NONE","Column unsorted");
define("_LIB_PAGINATION","Pages : ");
define("_LIB_TRIER_PAR","Sort by");
define("_LIB_PUIS_PAR","Then by");
define("_ACTION_TRIER","Lauch sort");
define("_LIB_AJOUTER_TRI","Add a sort");
define("_LIB_AUCUN_TRI","No sorting");
define("_LIB_DECROISSANT","Descending");
define("_LIB_CROISSANT","Ascending");
define("_ACTION_ANNULER","Cancel");
define("_LIB_TRIE_DES_COLONNES","Sort column");
define("_LIB_SUPPRIMER_TRI","Delete sort");

//- Easy_select
define('__LIB_PLACEHOLDER_EASY_SELECT','Filter then , &#8593; and &#8595; to navigate.');
define('__LIB_NO_RESULT_EASY_SELECT','No résult.');

//- LiveTimeStamp
define('__LIB_IL_Y_A',"Ago");
define('__LIB_LE',"The");
define('__LIB_LESS_ONE_MINUTE',"Less one minute");
define('__LIB_HEURES',"hours");
define('__LIB_MINUTES',"minutes");
define('__LIB_DAYS',"days");
	

//- Ultra select
define('__LIB_ULTRA_SELECT_FILTRER',"Filter then, &#8593; et &#8595; for navigate.");
define('__LIB_ULTRA_SELECT_NO_RESULT_ADD',"<i>Non-existant option.<br/> Add manually ?</i>");
define('__LIB_ULTRA_SELECT_NO_RESULT',"No results.");
define('__LIB_ULTRA_SELECT_OPTION_ADD',"Added option(s) : ");

?>
<!-- Constante en JS-->
<script>
    window.pnlang = [];
    window.pnlang._MSG_RESTAURER = "Restore the saved text";   
    window.pnlang.__LIB_WEBSOCKET_ENABLE = "Real-time notifications currently<b style=\"color: green\">available</b>.";
    window.pnlang.__LIB_WEBSOCKET_ENABLE_INFO = "The features such as customer incident monitoring, or automatic pool refresh are active.";
    window.pnlang.__LIB_WEBSOCKET_DISABLE = "Realtime notofications currently <b style=\"color: red\">disabled</b>.";
    window.pnlang.__LIB_WEBSOCKET_DISABLE_INFO = "The features such as event monitoring, automatic pool or refresh are not active.";
    window.pnlang.__LIB_OPEN_SURVEILLANCE = "manually open the Incident Monitoring pop-up";

    //- LiveTimeStamp
    window.pnlang.__LIB_IL_Y_A = "Ago";
    window.pnlang.__LIB_LESS_ONE_MINUTE = "Less one minute";
    window.pnlang.__LIB_HEURES = "hours";
    window.pnlang.__LIB_MINUTES = "minutes";
    window.pnlang.__LIB_DAYS = "days";
    window.pnlang.__LIB_LE  = "The";
    
    
    //- Ultra select
    window.pnlang.__LIB_ULTRA_SELECT__CHOIX  = '-- Make choice --';
    window.pnlang.__LIB_ULTRA_SELECT__NB_SELECTED  = ' Added option(s) : <br/> - ';
    window.pnlang.__LIB_ULTRA_SELECT__NO_RESULT  = 'No results.';
    window.pnlang.__LIB_ULTRA_SELECT_ALERT_ADD_OPTION  = "Do you confirm the option ?";
    
   
   
   
    
</script>

<!-- Fin Constante JS -->

