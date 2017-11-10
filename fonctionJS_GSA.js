        
  //------------------------------------------------------------------------------------------
  //Fonctions permettant au javascript généré par l'appel d'un script PHP via AJAX d'être interprété
  
  // setInnerHTML Sécurisé (permet de gérer les javascript ds les script PHP appelé)
  function setInnerHTML(divContent, HTML) {
      divContent.innerHTML=HTML;
      try {
        var All=divContent.getElementsByTagName("*");
        for (var i=0; i<All.length; i++) {
          All[i].id=All[i].getAttribute("id")
          All[i].name=All[i].getAttribute("name")
          //All[i].className=All[i].getAttribute("class")
        }
      } catch (ex) {}
      try {
        var AllScripts=HTML.extractTags("script");
        AllScripts.forEach(function (v) {
          eval(v);
        })
      } catch (ex) {}
      try {
        var AllStyles=HTML.extractTags("style");
        AllStyles.forEach(function (v) {
          var s=document.createStyleSheet()
          s.cssText=v;
          s.enabled=true;
        }, true)
      } catch (ex) {}
  }
  
  String.prototype.extractTags=function(tag) {
      var matchAll = new RegExp('(?:<'+tag+'.*?>)((\n|\r|.)*?)(?:<\/'+tag+'>)', 'img');
      var matchOne = new RegExp('(?:<'+tag+'.*?>)((\n|\r|.)*?)(?:<\/'+tag+'>)', 'im');
      return (this.match(matchAll) || []).map(function(scriptTag) {
        return (scriptTag.match(matchOne) || ['', ''])[1];
      });
    }
  
  //Detect IE5.5+
  version=0;
  if(navigator.appVersion.indexOf("MSIE")!=-1)
  {
  temp=navigator.appVersion.split("MSIE");
  version=parseFloat(temp[1]);
  }
  // NON IE browser will return 0
  if(version>=5.5)
  {
  Object.prototype.forEach=function(delegate, ownpropertiesonly) {
      if (typeof(delegate)=="function") {
          if (this instanceof Array && typeof(ownpropertiesonly)=="undefined") {
              ownpropertiesonly=true;
          }
          for (key in this) {
              var ok = (!ownpropertiesonly);
              if (!ok) {
                  try {
                      ok=this.hasOwnProperty(key)
                  } catch (ex) {}
              }
              if (ok) {
                  try { delegate(this[key], key, this) } catch(e) {
                      // ...
                  }
              }
          }
      }
      return false;
  }
  
  Object.prototype.map=function(iterator) {
      var results = [];
      this.forEach(function(value, index) {
        results.push(iterator(value, index));
      });
      return results;
  }
}
//Fin des fonctions permettant au JS d'etre interprété 
//-----------------------------------------------------------------------  
  

//Fonction de base d'AJAX permettant de créer l'objet permettant les requetes  
function getXhr(){
                      var xhr = null;
	if(window.XMLHttpRequest){ // Firefox et autres
	   xhr = new XMLHttpRequest(); 
	   
	}
	else if(window.ActiveXObject){ // Internet Explorer 
	   try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
	}
	else { // XMLHttpRequest non supporté par le navigateur 
	   alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
	   xhr = false; 
	} 
                      return xhr;
}



//Fonction appelée lorsque la liste déroulante GROUPE a été modifiée

function change(){
	
	//On créé l'objet avec la fonction ci-dessus
	var xhr = getXhr();
		
	// On défini ce qu'on va faire quand on aura la réponse, pour ce faire il faut redéfinir la fonction suivante :
	xhr.onreadystatechange = function(){
		//alert(xhr.readyState);
		// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
		
    if(xhr.readyState == 4 && xhr.status == 200){
		  //Une fois qu'on a toutes les informations on 
    	di = document.getElementById('div_site');
			setInnerHTML (di, xhr.responseText);
			
		}
	}

	// Ici on indique qu'on utilise la méthode POST et on donne la localisation du fichier contenant le script PHP à executer
	xhr.open("POST","./modules.php?op=modload&name=Contrat&file=ajaxSite",true);
	
	
	// ne pas oublier ça pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments, ici l'id du groupe
	//On recupere la valeur 
  idgroupe = document.getElementById('groupe').options[document.getElementById('groupe').selectedIndex].value;
	//alert(idgroupe);
	//Et on l'envoi !
	xhr.send("idGroupe="+idgroupe);

  
}

function changeSite(){

	var xhr= getXhr();
		
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		//alert(xhr.readyState);
		// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			di2 = document.getElementById('appli');
			setInnerHTML (di2, xhr.responseText);
			
		}
	}

	// Ici on indique qu'on utilise la méthode POST et on donne la localisation du fichier contenant le script PHP à executer
		xhr.open("POST","./modules.php?op=modload&name=Contrat&file=ajaxAppli",true);
	
	// ne pas oublier ça pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments ici, l'id du groupe et du site !
  //On recupere les informations 
	idsite = document.getElementById('site').options[document.getElementById('site').selectedIndex].value;
	idgroupe = document.getElementById('groupe').options[document.getElementById('groupe').selectedIndex].value;
  //alert(idsite);
  //On les envoie en les concatenant avec un & 
	xhr.send("idSite="+idsite+"&idGroupe="+idgroupe);

  
  
  
}

