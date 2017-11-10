<?php
/*******************************************************************************
Create Date : 07/01/2009
 ----------------------------------------------------------------------
 File name : menu.js.php
 File type : js: conteneur de code javascript 
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de regrouper le code javascript utilisé par la classe "menu"
********************************************************************************/

?>
<script>
/*******************************
* Permet d'ajouter l'évènement click sur un élément 
* quel que soit le navigateur
* *****************************/                 
 function ajout_click(el, myFunction) {
if (el.addEventListener) {
   el.addEventListener ('click',myFunction,false);     
   return '';
    }
else if (el.attachEvent) {
    el.attachEvent ('onclick',myFunction);
    }
else {
    el.onclick = myFunction;
    }
}

/*******************************
* Permet de supprimer l'évènement click sur un élément 
* quel que soit le navigateur
* *****************************/    
function suppr_click(el, myFunction) 
{
if (el.removeEventListener) 
    {
    el.removeEventListener ('click',myFunction,false);
    return '';
    }
else if (el.detachEvent)
   {
    el.detachEvent ('onclick',myFunction);
    }
else {
    el.onclick = '';
    }
}


/****************************
* Permet d'écrire dans un cookie
* nom : nom du cookie
* valeur : valeur du coockie
* 
*source :http://www.actulab.com/ecrire-les-cookies.php  
* ***************************/ 
function EcrireCookie(nom, valeur)
{
//alert('creation du cookie '+nom+' '+valeur);
var argv=EcrireCookie.arguments;
var argc=EcrireCookie.arguments.length;
var expires=(argc > 2) ? argv[2] : null;
var path=(argc > 3) ? argv[3] : null;
var domain=(argc > 4) ? argv[4] : null;
var secure=(argc > 5) ? argv[5] : false;
document.cookie=nom+"="+escape(valeur)+
((expires==null) ? "" : ("; expires="+expires.toGMTString()))+
((path==null) ? "" : ("; path="+path))+
((domain==null) ? "" : ("; domain="+domain))+
((secure==true) ? "; secure" : "");
}

//utilisée lors de la lecture de cookie
function getCookieVal(offset)
{
var endstr=document.cookie.indexOf (";", offset);
if (endstr==-1) endstr=document.cookie.length;
return unescape(document.cookie.substring(offset, endstr));
}
/**********************
*Permet de lire le cookie dont le nom est passé en paramètre
**********************/ 
function LireCookie(nom)
{
var arg=nom+"=";
var alen=arg.length;
var clen=document.cookie.length;
var i=0;
while (i<clen)
{
var j=i+alen;
if (document.cookie.substring(i, j)==arg) return getCookieVal(j);
i=document.cookie.indexOf(" ",i)+1;
if (i==0) break;

}
return null;
}



</script>
