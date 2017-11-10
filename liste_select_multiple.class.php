<?php
/*******************************************************************************
Create Date : 25/11/2009
 ----------------------------------------------------------------------
 Class name : liste_select_multiple
 Version : 1.0
 Author : Yoann Frommelt
 Description : Créé un objet composé de 2 listes selecte pour faire passé des informations d'une liste a l'autre
********************************************************************************/

class liste_select_multiple {
  //**** attribute ***********************************************************
  protected $obj_recherche_rapide; //Contien l'input text de recherche rapide
  protected $obj_liste_option_tout; // Contien la liste de toute les options proposées a l'utilisateur
  protected $obj_liste_option_select; // Contien la listedes options choisie par l'utilisateur qui vont etes transmise en $POST
  protected $obj_img_fleche_horizontale; // Contien les fleches qui permettent de passé un element d'une liste a l'autre
  protected $obj_img_fleche_verticale; // Contien les fleches qui permettent de chager l'ordre des elements dans la liste
  protected $obj_javascripter; // Contien le JS qui gere toute les actions sur l'ecran

 
  //**** constructor ********************************************************  
  function __construct($stri_liste_option_select_nom = "liste_option_select_nom") 
  /*************************************************************
  *
  * parametres : 
  * retour : objet de la classe listes_select_multiple
  *                        
  **************************************************************/  
  {
    $this->initialise($stri_liste_option_select_nom);
  }
 
  //**** setter *************************************************************
  public function setRechercheRapide($value){$this->obj_recherche_rapide=$value;}
  public function setListeOptionTout($value){$this->obj_liste_option_tout=$value;}
  public function setListeOptionSelect($value){$this->obj_liste_option_select=$value;}
  public function setImgFlecheHorizontale($value){$this->obj_img_fleche_horizontale=$value;}
  public function setImgFlecheVerticale($value){$this->obj_img_fleche_verticale=$value;}
  public function setJavascripter($value){$this->obj_javascripter=$value;}


  //**** getter *************************************************************
  public function getRechercheRapide(){return $this->obj_recherche_rapide;}
  public function getListeOptionTout(){return $this->obj_liste_option_tout;}
  public function getListeOptionSelect(){return $this->obj_liste_option_select;}
  public function getImgFlecheHorizontale(){return $this->obj_img_fleche_horizontale;}
  public function getImgFlecheVerticale(){return $this->obj_img_fleche_verticale;}
  public function getJavascripter(){return $this->obj_javascripter;}
  
  //**** methode *************************************************************
  
  public function initialise($stri_liste_option_select_nom)
  /*************************************************************
  Initialise tout les objets de la class
 
  Paramètres : $stri_liste_option_select_nom : nom de la liste d'option select
  Retour : aucun
  **************************************************************/  
  {
    $obj_javascripter=new javascripter();
    $obj_javascripter->addFunction("<!--
    
  //========================= Fonctions Generales ==================================//
  
  function verify_option(int_value) {
    //vérifie si l'option int_value existe dans une des listes de destination     
    //@param : int_value => valeur de l'option
    //@return : true => l'option a été trouvée
    //          false => l'option n'existe pa
    
    //si l'option est à -1, cad la valeur par défaut, alors elle apparait comme déjà insérée dans une liste
    if(int_value!=-1) {
      //vérifie que l'option n'existe pas dans la liste de destination principale
      var obj_choix_fait = document.getElementById('choix_fait');    //recupère liste des choix fait
      var int_nb_choix_fait = obj_choix_fait.length;                 //compte le nb d'option dans la liste
      
      for(i=0;i<int_nb_choix_fait;i++) {                               //pour chaque option
        if(obj_choix_fait.options[i].value == int_value) {      //vérifie que la valeur n'existe pas.
          return true;                                        //si oui, return true
        }
      }
    }
    else {
      return true;                                            
    }
    return false;
  }
    
  function get_data_select(obj_select) {
    //récupère les données sélectionnées dans les listes (get selected data in the list)
    //@param : obj_select => liste déroulante (select)
    //@return : arra_result => tableau des options sélectionnées (array of selected options)        

    var arra_result= new Array();                    //création du tableau (create array)
    var int_nb_result=obj_select.options.length;     //compte le nombre d'options dans la liste (count select's option)
  
    for (var i=0; i<int_nb_result; i++) {             //pour chaque option (foreach option)
      if (obj_select.options[i].selected) {           //récupère l'option sélectionnée (get selected option) 
        arra_result.push(obj_select.options[i]);     //ajoute dans le tableau (add in array)
      }
    }
    return arra_result;             
  }
  
  function get_index(obj_select,int_value) {
    //obtient le numéro d'index de la valeur
    //@param : obj_select => la liste dans laquelle je recherche une valeur (select to search value)
    //@param : int_value => la valeur que je recherche (value)
    //@return : l'index de l'option (option's index)
    
    int_nb_result=obj_select.length;                 //compte le nb d'option (count option)
    for (var i=0; i<int_nb_result; i++) {             //pour chaque option (foreach option)
      //si la valeur est sélectionnée et a la valeur du paramètre, alors l'index est retourné 
      if (obj_select.options[i].selected && obj_select.options[i].value==int_value) {
        return i;
      }
    }
    return -1; 
  }
      
  //========================= Recherche Rapide ==================================//
  
  function search() {
    //recherche rapide d'une personne
    //@return : void 
    stri_search = document.getElementById('text_search').value; //recupère le texte saisi
    int_nb_search=stri_search.length; //compte la longueur du texte     
    obj_select_user=document.getElementById('choix_possible');
    int_nb_user=obj_select_user.length; //compte le nb de personne
    obj_select_user.selectedIndex = -1; //désélectionne les options
    bool_find=false;  
    i=0;
    while(i<int_nb_user && !bool_find) { //pour chaque option (foreach option) 
      stri_extract=obj_select_user.options[i].text.substr(0,int_nb_search); //extrait les premieres lettres de l'option, égale à la longueur du mot saisi dans la recherche
    
      if(stri_extract.toUpperCase()==stri_search.toUpperCase()) { //compare la chaine saisie avec celle extraite
        bool_find=true;
      }
      i++;
    }
    if(bool_find) { //si la chaine a été trouvée alors on sélectionne l'option
      obj_select_user.options[i-1].selected=true;
    }
    else {
      obj_select_user.selectedIndex = -1; //sinon aucune option est sélectionnée
    }
  }
  
  //=========================  Ajout  ==================================//
  
  function ajout() {
    //récupère les données sélectionnées dans la liste
    obj_choix_possible = document.getElementById('choix_possible');   
    obj_choix_fait = document.getElementById('choix_fait');
    
    arra_result = get_data_select(obj_choix_possible);
   
    var bool_exist = false;
    
    //ajoute les valeurs dans la liste stri_select
    var int_nb = arra_result.length;         //compte le nb de ligne du tableau
    
    if(int_nb > 0) {                   //si il existe des lignes, alors on ajoute les valeurs
      //for(a in arra_result) {                //pour chaque option
      for(var a=0;a<arra_result.length;a++) {                //pour chaque option
        bool_exist = verify_option(arra_result[a].value);      //vérifie que l'option n'existe pas dans une liste de destination
        if(!bool_exist) {                              //si elle n'a pas déjà été saisie alors on ajoute la valeur 
        
            value = arra_result[a].value;
            text = arra_result[a].text;
  
            obj_choix_fait.options[obj_choix_fait.options.length] = new Option(text,value); 
        }
      }
    }
    
    //enleve les sélections dans les listes
    obj_choix_possible.selectedIndex = -1; 
  }


  //=========================  Supprimer  ==================================//
  
  function suppr() {
    var obj_choix_fait = document.getElementById('choix_fait');           //récupère la liste (get select)
    var arra_result = get_data_select(obj_choix_fait);                     //récupère les données dans la liste
    
    for(a in arra_result) {    //pour chaque option (foreach option)
      /*int_i = arra_result[a].value;
      int_i = get_index(obj_choix_fait,arra_result[a].value);              //récupère l'index de l'option (get option's index)
      obj_choix_fait.options.[int_i] = null;                            //efface l'option (clear option)
      */
      
      int_i = get_index(obj_choix_fait,arra_result[a].value);              //récupère l'index de l'option (get option's index) 
      obj_choix_fait.options[int_i] = null;                            //efface l'option (clear option)
      
    }         
  }

  //=========================  Haut  ==================================//
  function haut() {
    var obj_choix_fait = document.getElementById('choix_fait');
    var arra_result = get_data_select(obj_choix_fait);
    
    for(a in arra_result) {
      index = get_index(obj_choix_fait,arra_result[a].value);
      if(index > 0){
        value_tampon = obj_choix_fait.options[index-1].value;
        name_tampon = obj_choix_fait.options[index-1].text;
        obj_choix_fait.options[index-1] = new Option((obj_choix_fait.options[index].text),(obj_choix_fait.options[index].value));
        obj_choix_fait.options[index] = new Option(name_tampon,value_tampon);   
        obj_choix_fait.options[index-1].selected = true;
      }
    }
  }
  
  //=========================  Bas  ==================================//
  function bas() {
    var obj_choix_fait = document.getElementById('choix_fait');
    var arra_result = get_data_select(obj_choix_fait);
    
    /*for(a in arra_result) {
      index = get_index(obj_choix_fait,arra_result[a].value);
      if(index < obj_choix_fait.length-1 && index!=-1) {
        alert(index);
        value_tampon = obj_choix_fait.options[index+1].value;
        name_tampon = obj_choix_fait.options[index+1].text;
        obj_choix_fait.options[index+1] = new Option((obj_choix_fait.options[index].text),(obj_choix_fait.options[index].value));
        obj_choix_fait.options[index] = new Option(name_tampon,value_tampon);
        obj_choix_fait.options[index+1].selected = true;
      }
    }*/
    //alert(arra_result.length);
    for (var i=arra_result.length-1; i!=-1; i--) {
      index = get_index(obj_choix_fait,arra_result[i].value);
      //alert(i);
      if(index < document.getElementById('choix_fait').length-1 && index!=-1) {
        value_tampon = obj_choix_fait.options[index+1].value;
        name_tampon = obj_choix_fait.options[index+1].text;
        obj_choix_fait.options[index+1] = new Option((obj_choix_fait.options[index].text),(obj_choix_fait.options[index].value));
        obj_choix_fait.options[index] = new Option(name_tampon,value_tampon);
        obj_choix_fait.options[index+1].selected = true;
      }
    }
  }
  
  //=========================  Envoi  ==================================//
  
  // La fonction envoi est automatiquement appelé sur l'action submit
  // Si il n'existe pas de bouton de type submit vous devez intergrer l'appel a la fonction envoi() sur le bouton d'envoi du formulaire
  
  function envoi() {
    var select = document.getElementById('choix_fait');
    select.multiple=true;
    for (i=0; i<select.options.length; i++) { 
      select.options[i].selected = true;
    } 
  }
  
  // initialise l'event d'envoi
  var form=document.getElementById('choix_fait').form;
  if(form.addEventListener) {
    form.addEventListener('submit', envoi, false); //pour firefox
  }
  else {
    form.attachEvent('onsubmit', envoi); //pour ie -_-
  } 
         
// -->");
    $this->obj_javascripter = $obj_javascripter;
    
    // ---------  Partie recherche  ---------
    
    // a definir par le developpeur  : define('_SELECT_MULTIPLE_LABEL_RECHERCHE','Recherche rapide	: ');
    $obj_label_search=new font(_SELECT_MULTIPLE_LABEL_RECHERCHE);
    // Textbox  
    $obj_text_search = new text('text_search');
    $obj_text_search->setId('text_search');
    $obj_text_search->setOnKeyUp('search()');
    
    $obj_table_recherche_rapide = new table();
    $obj_table_recherche_rapide->setBorder('0');
      $obj_table_recherche_rapide_tr = new tr();
        $obj_table_recherche_rapide_tr->addTd($obj_label_search->htmlValue()); 
        $obj_table_recherche_rapide_tr->addTd($obj_text_search->htmlValue());
      $obj_table_recherche_rapide->insertTr($obj_table_recherche_rapide_tr);
    
    $this->obj_recherche_rapide = $obj_table_recherche_rapide;
    
    // ---------  Felches  ---------
    $obj_img_fleche_ajout=new img("images/module/fleche_add.gif");
    $obj_img_fleche_ajout->setOnclick('ajout()');
    $obj_img_fleche_suppr=new img("images/module/fleche_del.gif");
    $obj_img_fleche_suppr->setOnclick('suppr()');
    $obj_img_fleche_haut=new img("images/module/ma_fleche_haut.gif");
    $obj_img_fleche_haut->setOnclick('haut()');
    $obj_img_fleche_bas=new img("images/module/ma_fleche_bas.gif");
    $obj_img_fleche_bas->setOnclick('bas()');

    $obj_table_img_verticale = new table();
    $obj_table_img_verticale_tr_haut=new tr();
      $obj_table_img_verticale_tr_haut->addTd($obj_img_fleche_haut->htmlValue());
    $obj_table_img_verticale->insertTr($obj_table_img_verticale_tr_haut);
    $obj_table_img_verticale_tr_bas=new tr();
      $obj_table_img_verticale_tr_bas->addTd($obj_img_fleche_bas->htmlValue());
    $obj_table_img_verticale->insertTr($obj_table_img_verticale_tr_bas);
    $obj_table_img_verticale->setBorder('0');
    
    $this->obj_img_fleche_verticale = $obj_table_img_verticale;
    
    $obj_table_img_horizontale=new table();
    $obj_table_img_horizontale_tr_ajout=new tr();
      $obj_table_img_horizontale_tr_ajout->addTd($obj_img_fleche_ajout->htmlValue());
    $obj_table_img_horizontale->insertTr($obj_table_img_horizontale_tr_ajout);
    $obj_table_img_horizontale_tr_suppr=new tr();
      $obj_table_img_horizontale_tr_suppr->addTd($obj_img_fleche_suppr->htmlValue());
    $obj_table_img_horizontale->insertTr($obj_table_img_horizontale_tr_suppr);
    $obj_table_img_horizontale->setBorder('0');
    
    $this->obj_img_fleche_horizontale = $obj_table_img_horizontale;
    
    // ---------  Liste option tout  ---------
    $obj_select_liste_option_tout=new select('choix_possible');
    $obj_select_liste_option_tout->setId('choix_possible');
    $obj_select_liste_option_tout->setSize(10);
    $obj_select_liste_option_tout->setStyle("width: 255px");
    $obj_select_liste_option_tout->setOndblclick('ajout()');
    $obj_select_liste_option_tout->setMultiple(true);
    $this->obj_liste_option_tout = $obj_select_liste_option_tout;
    
    // ---------  Liste option select  ---------
    $obj_select_liste_option_select=new select($stri_liste_option_select_nom."[]");
    $obj_select_liste_option_select->setId('choix_fait');
    $obj_select_liste_option_select->setSize(10);
    $obj_select_liste_option_select->setStyle("width: 255px");
    $obj_select_liste_option_select->setOndblclick('suppr()');
    $obj_select_liste_option_select->setMultiple(true);
    $this->obj_liste_option_select = $obj_select_liste_option_select;
  }
  
  public function addOption($stri_option_value, $stri_option_label)
  /*************************************************************
  Permet d'ajouter une opption a la liste qui propose tout les elements
 
  Paramètres : $stri_option_value : valeur qui ser transmise en $_POST
                $stri_option_label : label qui sera afficher à l'utilisateur
  Retour : aucun
  **************************************************************/  
  {
    $this->obj_liste_option_tout->addOption($stri_option_value,$stri_option_label);
  }
  
  public function addOptionSelected($stri_option_value, $stri_option_label, $stri_title="")
  /*************************************************************
  Permet d'ajouter une opption a la liste que l'utilisateur renseigne
 
  Paramètres : $stri_option_value : valeur qui ser transmise en $_POST
                $stri_option_label : label qui sera afficher à l'utilisateur
                $stri_title : balise title sur option
  Retour : aucun
  **************************************************************/  
  {
    $obj_option = $this->obj_liste_option_select->addOption($stri_option_value,$stri_option_label);
    $obj_option->setTitle($stri_title);
  }
  
  public function addGroupe($stri_groupe_nom)
  /*************************************************************
  Permet d'ajouter un groupe a la liste qui propose tout les elements
 
  Paramètres : $stri_groupe_nom : nom du groupe
  Retour : aucun
  **************************************************************/  
  {
    $this->obj_liste_option_tout->addGroup($stri_groupe_nom);
  }
  
  public function constructFromSql($stri_query_sql)
  /*************************************************************
  Ajout les options au select a partir d'une requette SQL
 
  Paramètres : $stri_query_sql : requette SQL
  Retour : aucun
  **************************************************************/  
  {
    $obj_query_select = new querry_select($stri_query_sql);
    $arra_result_query_select = $obj_query_select->execute("indice");
    //echo"<pre>";var_dump($arra_sesult_query_select);echo"</pre>";
    if ($obj_query_select->getNumberCol() == 1) {
      foreach ($arra_result_query_select as $arra_one_result_query_select) {
        $this->addOption($arra_one_result_query_select[0],$arra_one_result_query_select[0]);
      }
    }
    else {
      foreach ($arra_result_query_select as $arra_one_result_query_select) {
        $this->addOption($arra_one_result_query_select[0],$arra_one_result_query_select[1]);
      }
    }
  }
  
  public function constructFromSqlSelected($stri_query_sql, $bool_title=false)
  /*************************************************************
  Permet de lancer les opération de maintenance
 
  Paramètres : $stri_query_sql : requette SQL
                $bool_title : boolean pour afficher ou non une balise title
  Retour : aucun
  **************************************************************/  
  {
    $obj_query_select = new querry_select($stri_query_sql);
    $arra_result_query_select = $obj_query_select->execute("indice");
    //echo"<pre>";var_dump($arra_sesult_query_select);echo"</pre>";
    if ($bool_title) {
      if ($obj_query_select->getNumberCol() == 1) {
        foreach ($arra_result_query_select as $arra_one_result_query_select) {
          $this->addOptionSelected($arra_one_result_query_select[0],$arra_one_result_query_select[0],$arra_one_result_query_select[0]);
        }
      }
      else {
        foreach ($arra_result_query_select as $arra_one_result_query_select) {
          $this->addOptionSelected($arra_one_result_query_select[0],$arra_one_result_query_select[1],$arra_one_result_query_select[1]);
        }
      }
    }
    else {
      if ($obj_query_select->getNumberCol() == 1) {
        foreach ($arra_result_query_select as $arra_one_result_query_select) {
          $this->addOptionSelected($arra_one_result_query_select[0],$arra_one_result_query_select[0]);
        }
      }
      else {
        foreach ($arra_result_query_select as $arra_one_result_query_select) {
          $this->addOptionSelected($arra_one_result_query_select[0],$arra_one_result_query_select[1]);
        }
      }
    }
  }
  
  public function htmlValue()
  /*************************************************************
  Permet de lancer les opération de maintenance
 
  Paramètres : aucun
  Retour : tableau en html + code javascript
  **************************************************************/  
  {

    $obj_table_affichage = new table();
    $obj_table_affichage->setBorder(0);
      
      $obj_table_affichage_tr_recherche = new tr();
        $obj_table_affichage_tr_recherche->addTd($this->obj_recherche_rapide->htmlValue())->setColspan(4);
      $obj_table_affichage->insertTr($obj_table_affichage_tr_recherche);
      
      $obj_table_affichage_tr_listes = new tr();
        $obj_table_affichage_tr_listes->addTd($this->obj_liste_option_tout->htmlValue());
        $obj_table_affichage_tr_listes->addTd($this->obj_img_fleche_horizontale->htmlValue());
        $obj_table_affichage_tr_listes->addTd($this->obj_liste_option_select->htmlValue());
        $obj_table_affichage_tr_listes->addTd($this->obj_img_fleche_verticale->htmlValue());
      $obj_table_affichage->insertTr($obj_table_affichage_tr_listes);

    return $obj_table_affichage->htmlValue().$this->obj_javascripter->javascriptValue();
  
  }
  

}
?>
