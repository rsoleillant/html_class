/*******************************************************************************
Create Date : 20/06/2011
 ----------------------------------------------------------------------
 File name : tableur.js
 Version : 1.0
 Author : Rémy Soleillant
 Description : Partie dynamique de l'interface d'un tableur
********************************************************************************/

//Permet d'obtenir l'indice d'indentification d'un td d'entete
function getTdIndiceId(td)
{
  var arra_element=td.id.split('_'); 
  return arra_element[1];
}

//Permet de filtrer les td à afficher en fonction des filtre qui ont été cochés
var arra_marqueur_filtre=new Array(); //Tableau pour se rappeller des lignes qui sont filtrées
function filtre(td)
{
  //récupération des checbox de filtres
  //var stri_id='cb_col_'+td.cellIndex;
  var stri_id='cb_col_'+getTdIndiceId(td);
  var arra_cb=getElementsByName_iefix('input',stri_id);
  var nb_cb=arra_cb.length;
  var j;


  //récupération des td de la colonne
  var arra_td=getTdForColumn(td);
  
  //*** filtrage des tr
  //recherche des td qui répondent au filtre 
  for(var i in arra_td)//pour chaque cellule
  {
   var bool_ok=false;
   var trIndex=arra_td[i].parentNode.rowIndex;
  
  
   j=0;
   while((j<nb_cb)&&(!bool_ok))//tant qu'on n'a pas trouvé le filtre correspondant à la cellule
   {
    var libelle_td=arra_td[i].lastChild.innerHTML;
        libelle_td=libelle_td.substr(0,20);//récupération du libellé de la cellule
    var libelle_cb=arra_cb[j].value;  //récupération du libellé de la checkbox
        libelle_cb=libelle_cb.substr(0,20);
    
   
    if(libelle_td==libelle_cb)//si le libellé de la cellule correspond à la valeur du filtre
    {
    
     if(!arra_cb[j].checked)//si la case n'est pas cochée
     {
       arra_td[i].parentNode.style.display='none';//on masque la ligne
    
       //ajout d'un marqeur filtre
       var arra_temp=(arra_marqueur_filtre[trIndex])?arra_marqueur_filtre[trIndex]:new Array();
       arra_temp[libelle_td]=1;
       arra_marqueur_filtre[trIndex]=arra_temp;
     }
     else
     {
       //suppression d'un marqeur filtre
       var arra_temp=(arra_marqueur_filtre[trIndex])?arra_marqueur_filtre[trIndex]:new Array(); 
       arra_temp[libelle_td]=0;
       arra_marqueur_filtre[trIndex]=arra_temp;
      
     }
      
      var somme=0;//calcul du nombre de marqueur sur chaque ligne
      for(key in arra_marqueur_filtre[trIndex])
      {
       somme+=arra_marqueur_filtre[trIndex][key];//on compte le nombre de marqueur à 1
      }
       
     
     if(somme==0)//on réaffiche la ligne si elle n'a plus de marqeur filtre
     {arra_td[i].parentNode.style.display='';}      
      
     bool_ok=true;
    }
    j++;
   }
  }

  //*** gestion du changement de l'icône de filtre
  var bool_one_not_cheked=false;//pour savoir si au moins un cb n'est pas cochée
  i=0;
  while((i<nb_cb)&&(!bool_one_not_cheked)) 
  {
  
   if(!arra_cb[i].checked)
   {bool_one_not_cheked=true;}
   
    i++; 
  }
  //récupération de l'image
  //var img=document.getElementById('img_filtre_'+td.cellIndex);
   var img=document.getElementById('img_filtre_'+getTdIndiceId(td));    
  img.src='images/filtrer.gif';
  if(bool_one_not_cheked)
  {img.src='images/filtrer_actif.gif';} 
  
  
  //*** on cache l'affichage des filtres 
  switchFiltre(td);
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
        

//Permet d'afficher ou de cacher les filtres de la colonnes
function switchFiltre(td)
{   
  cellId=getTdIndiceId(td);
 
  //var table=document.getElementById('filtreColonne_'+td.cellIndex);
  var table=document.getElementById('filtreColonne_'+cellId);
       
  //affichage de la table des filtres
  if(table.style.display=='')
  {
    table.style.display='none';
    return;
  }
  table.style.display='';
  
  //création des td de filtre
  //var td_filtre=document.getElementById('tdFiltre_'+td.cellIndex);
  var td_filtre=document.getElementById('tdFiltre_'+cellId); 
  var arra_cb=getAllDifferentValue(td);
  //var stri_id='cb_col_'+td.cellIndex;
  var stri_id='cb_col_'+cellId;
  
  //récupération des valeurs cochées
  //var arra_ancienne_cb=document.getElementsByName(stri_id);
  var arra_ancienne_cb=getElementsByName_iefix('input',stri_id);
  
  var arra_cb_checked=new Array();
  for(var i in arra_ancienne_cb)
  {  
    if(arra_ancienne_cb[i].checked)
    { 
     arra_cb_checked[arra_cb_checked.length]=arra_ancienne_cb[i].value;   
    }
  }
   
  //suppression des anciennes checkbox
  while(td_filtre.hasChildNodes())
  {
   td_filtre.removeChild(td_filtre.firstChild);
  } 
  
  var bool_initialise=true;//on regarde si on est lors de la première création des checkbox
  if(arra_ancienne_cb.length>0)
  {
    bool_initialise=false;
  }
  
  //ajout des checkbox   
  for(var i in arra_cb)
  {
   var cb=document.createElement('input');
       cb.setAttribute('name',stri_id);;
   var font=document.createElement('font');
       font.innerHTML=arra_cb[i]+'<br />';
       cb.setAttribute('type','checkbox');
       cb.value=arra_cb[i];
     
       
     if((in_array(arra_cb_checked,arra_cb[i]))||(bool_initialise))
     {  
      cb.setAttribute('checked',true);
      cb.defaultChecked=true; //IE ...
     }    
       td_filtre.appendChild(cb);
       td_filtre.appendChild(font);
  }
  
}

   
   
   
   
//Permet de supprimer les doublons d'un tableau
function supprimeDoublons(TabInit)
{
  NvTab= new Array();
  var q=0;
  var LnChaine= TabInit.length;
   for(x=0;x<LnChaine;x++)
      {
      for(i=0;i<LnChaine;i++)
          {
          if(TabInit[x]==  TabInit[i] && x!=i) TabInit[i]='faux';
          }
      if(TabInit[x]!='faux'){  NvTab[q] = TabInit[x]; q++}
      }
  return NvTab;
}
    
    
    
//*** Partie pour le filrage des valeurs des cellules d'une colonne
//Permet d'obtenir la liste des td d'une même colone
function getTdForColumn(td)
{
 //var num_colone=td.cellIndex;
 var num_colone=getTdIndiceId(td);
 var table=td.parentNode.parentNode;
 var nb_element=table.rows.length;
 var arra_td=new Array();
 for(var i=1;i<nb_element;i++)
 {
   arra_td[arra_td.length]=table.rows[i].cells[num_colone];
 }

return arra_td; 
}
     
     
     
//Permet de récupérer toutes les valeurs d'une colone à partir du premier td de la colonne
function getAllDifferentValue(td)
{
  var arra_td=getTdForColumn(td);
  var nb_element=arra_td.length;
  var arra_valeur=new Array();
  
  for(var i=0;i<nb_element;i++)
  {     
     var chaine=arra_td[i].lastChild.innerHTML;//accès à la div de visu
     if(chaine.length>20)
     {
      chaine=chaine.substr(0,20)+'[...]';
     } 
    arra_valeur[arra_valeur.length]=chaine;         
  }
  
  arra_valeur.sort();
  arra_valeur=supprimeDoublons(arra_valeur); 
  
  return arra_valeur;
}
   
   
//*** Partie pour l'affichage réduit ou complet des cellules d'une ligne
//Permet de faire un affichage complet ou rétrécie des cellules d'un tableau
function switchDisplay(td)
{
  var obj_tr=td.parentNode;
  var arra_cell=obj_tr.cells;
  var nb_element=arra_cell.length;
  var stri_visible='visible';
  var int_height='';
  var color=color2;//les variables color1 et color2 sont défini dans le script appellant tableur.class.php
 
  if(arra_cell[1].lastChild.style.overflowY=='visible')
  {
   stri_visible='hidden';
   int_height=30;
   color=color1;
  }
  
  td.style.backgroundColor=color;   
     
  for(var i=1;i<nb_element;i++)
  {
    arra_cell[i].lastChild.style.overflowY=stri_visible;
    arra_cell[i].lastChild.style.overflowX=stri_visible;
    arra_cell[i].lastChild.style.height=int_height;
  } 
}

//Permet de basculer l'ensemble des lignes en affichage complet ou réduit
function switchDisplayAllLine(icone_switch)
{
   var table=icone_switch.parentNode.parentNode.parentNode;//on remonte jusqu'à la table
   
   var arra_tr=table.rows;
   var i;
   var nb_element=arra_tr.length;
   
   for(i=1;i<nb_element-1;i++)             //pour chaque ligne
   {
      switchDisplay(arra_tr[i].cells[0]);//on bascule l'affichage
   }
   
   //traitement spécifique de la dernière ligne qui peut être une ligne normal ou d'ajout
   
   var bgcolor=arra_tr[nb_element-1].cells[0].style.backgroundColor;//récupération de la couleur du td

   if(bgcolor!='yellow')//si la couleur n'est pas celle de la ligne d'ajout de nouvelle données
   {
    switchDisplay(arra_tr[nb_element-1].cells[0]);//il s'agit d'une ligne normale, on la switch
   }
}

    
//Pour stopper la propagation d'un événement js, compatible tout navigateur   
function stopPropagation(event)
{
  if (event.stopPropagation) 
  { 
    event.stopPropagation(); 
  } 
  event.cancelBubble = true;
}

     
     
//*** Partie pour la transformation des cellules en mode édition ou visu
//Permet de faire récupérer l'objet d'édition à partir de la div d'édition
function getEdition(obj_div_edition)
{                      
  var obj_edition=obj_div_edition.getElementsByTagName('select')[0];
  if(obj_edition)
  {return obj_edition;}
  
  obj_edition=obj_div_edition.getElementsByTagName('textarea')[0];
  if(obj_edition)
  {return obj_edition;}
  
  obj_edition=obj_div_edition.getElementsByTagName('input')[0];
  return obj_edition;
}

//Permet d'obtenir la valeur contennu dans la div d'édition
function getValue(obj_div_edition)
{  
  var obj_edition=obj_div_edition.getElementsByTagName('select')[0];
  if((obj_edition)&&(obj_edition.name!=""))
  {return obj_edition.options[obj_edition.selectedIndex].text;}
  
  obj_edition=obj_div_edition.getElementsByTagName('textarea')[0];
  if(obj_edition)
  {return obj_edition.value;}
  
  
  obj_edition=obj_div_edition.getElementsByTagName('input')[0];
  return obj_edition.value;
}
    

//Permet de faire passer une cellule du mode d'édition au mode visu et inverserment
var lastCellSwitched='';
function switchCell(td)
{
  if(lastCellSwitched!='')//pour ne pas switcher une cellule s'il y en a déjà une sous forme édition 
  {
   return '';
  }
  
  var div_edition=td.firstChild;
  var div_visu=td.lastChild;
  //var id_edition=td.cellIndex+'_'+td.parentNode.rowIndex;
  var id_edition=getTdIndiceId(td)+'_'+td.parentNode.rowIndex;
  
  if(div_edition.style.display=='none')//si on est dans la forme visu, passage forme édition
  {//passage à la forme édition
    div_edition.style.display='';
    div_visu.style.visibility='hidden';  
    lastCellSwitched=td; 
    var obj_edition=getEdition(div_edition);
    
    setTimeout(function(){obj_edition.focus(); }, 10);//focus en différé car sinon ie plante                    
    //stopPropagation(event); 
  }
  else
  {//passage à la forme visu
    div_edition.style.display='none';
    //div_visu.style.display='';
    div_visu.style.visibility='visible';
    //actualisation de la valeur de la div de visu 
    var valeur=getValue(div_edition);
    
    //div_visu.firstChild.innerHTML=valeur;
    div_visu.innerHTML=valeur; 
  }
  
}
  
//Gestion du retour en forme visu sur clic en dehors de la div d'édition
var int_switch=0;
var marqueur_nouveau=false; 
$('body').click(
                 function() 
                 {
                  
                  if(lastCellSwitched!='')
                  {
                   if(int_switch==0)
                   {int_switch=1}
                   else
                   {
                     int_switch=0;
                     var cell=lastCellSwitched;
                     lastCellSwitched='';
                     switchCell(cell);
                     if(marqueur_nouveau)
                     {
                      ajouteLigne(cell);
                     }
                   }   
                  }
                 }
                  );
//*** Partie ajout d'une nouvelle ligne
//Permet de définir la valeur pour une div de visu
function setValue(obj_div_visu,valeur)
{  
 alert(obj_div_visu.name);
 obj_div_visu.innerHTML=valeur;
}


function enableEdition(td)
{
  marqueur_nouveau=true;
  var edition=getEdition(td.firstChild);
  edition.disabled=false;
  edition.focus();
  initialiseCalendar(edition);
 
}

//Permet d'intialiser les calendriers
//Paramètres : le champ d'édition
function initialiseCalendar(edition)
{
  var img=edition.parentNode.parentNode.getElementsByTagName('img')[0];
  var time=new Date().getTime();
  var rand=Math.floor(Math.random()*100);
  var randId=+time+'-'+rand;
  var id_img='id_img_'+randId;//création d'un id unique
  var id_txt='id_text_'+randId;
  

  if(img)
  {
   //extraction du nom de l'image
   var last_slash=img.src.lastIndexOf('/');
   var image_name=img.src.substring(last_slash+1);
   
   if(image_name=='calendar-month.png')//si l'image est celle du calendrier
   {
    img.id=id_img;
    edition.id=id_txt;
    
    img.style.display='';//affichage de l'image
    Calendar.setup(
      {
          inputField     :    id_txt,    
          ifFormat       :    '%d/%m/%Y',    
          button         :    id_img,  
          align          :    'Bl',          
          singleClick    :     true,
          showsTime      :     false          
      });
   }
   
   if(image_name=='add_out.gif')//cas des champs select_and_text
   {
     var text=edition.parentNode.parentNode.getElementsByTagName('input')[0];
     var select=edition.parentNode.parentNode.getElementsByTagName('select')[0];
     text.disabled=false;
     select.disabled=false;
     text.id=text.id+rand;
     select.id=select.id+rand;
     img.id=img.id+rand;
     
   }      
  }
}

//Permet d'ajouter une nouvelle ligne au tableur
function ajouteLigne(td)
{
 //Récupération de la valeur du td
 var value_td=td.lastChild.innerHTML;
 td.lastChild.innerHTML='';//suppression temporaire de la valeur du td (pour que le clone ai une valeur vide)
 
 var tr=td.parentNode
 var tr_clone=tr.cloneNode(true); //clonage du tr d'ajout de nouvelle ligne
 var table=td.parentNode.parentNode;
 
 td.lastChild.innerHTML=value_td;//restauration de la valeur d'origine du td
 table.appendChild(tr_clone); 
 
 tr_clone.cells[0].innerHTML=table.rows.length-2;//mise à jour du numéro de ligne
 

 
 var arra_cell=tr.cells;
 var nb_element=arra_cell.length-1; //On boucle sur tous les td sauf le dernier qui contient l'image de suppression
 for(i=1;i<nb_element;i++)
 {
  arra_cell[i].onclick=function(){switchCell(this);};//on enlève la fonctionnalité d'ajout de nouvelle ligne
  var edition=getEdition(arra_cell[i].firstChild);//récupération de l'objet d'édition pour la nouvelle ligne
  var img=edition.parentNode.parentNode.getElementsByTagName('img')[0];
  edition.disabled=false;
  initialiseCalendar(edition); 
 }

 //on doit remettre l'objet d'édition de la cellule sur laquel on a cliqué à false pour éviter de transmettre une donnée parasite
  getEdition(tr_clone.cells[td.cellIndex]).disabled=true;
 

 marqueur_nouveau=false;
}

//*** Partie suppression d'une ligne
//Permet d'envoyer les informations de la ligne en vue de sa suppresion
//Paramètres : tr : le tr de la ligne à supprimer
function envoiInformationSuppression(tr_bouton)
{
 //création d'un formulaire
 var form=document.createElement('form');
 form.method='post';
 form.action=document.location.href+'&action=delete';
 
 //création d'une table
 var table=document.createElement('table');
 table.style.display='none';
 
 //clonage du tr qui porte les données
 var tr_clone=tr_bouton.cloneNode(true);
 
 //rattachement du clone portant les données à une table
 table.appendChild(tr_clone);
 
 //rattachement de la table au formulaire
 form.appendChild(table);
 
 //rattachement du formulaire à la page et envoi
 document.body.appendChild(form);
 form.submit();
}    