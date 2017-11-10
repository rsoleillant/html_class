<?php
/*******************************************************************************
Create Date : 2/11/2007
 ------------------------------------------------------------------------------
 Class name : Address
 Version : 1.0
 Author : Emilie Merlat
 Description : permet de gérer le carnet d'adresses
 Doc : \\stpr0341\Partage_hotline\Analyse_fonctionnel_asisline\mod_mail\man_techn_mail.txt
*******************************************************************************/

class Address 
{
  //**** attribute *************************************************************
  protected $bool_cc=true;                        // => la partie copie carbone est affichée par défaut (part cc)
  protected $bool_bcc=true;                       // => la partie copie cachée est affichée par défaut (part bcc)  

  protected $obj_lb_address;                      // => titre du formulaire
  protected $obj_lb_group;                        // => label du groupe (group's label)
  protected $obj_lb_user;                         // => label des utilisateurs (user's label)
  protected $obj_lb_search;                       // => label de recherche (search's label)
  protected $obj_lb_to;                           // => label du destinataire principal (label "to")    
  protected $obj_lb_cc;                           // => label de la copie carbone (label "cc")
  protected $obj_lb_bcc;                          // => label de la copie cachée (label "bcc")
  protected $obj_text_search;                     // => textbox de recherche (search's textbox)
  protected $obj_select_group;                    // => liste des groupes mail  (group's select)
  protected $obj_select_user;                     // => liste des mails "utilisateur" (user's select)
  protected $obj_select_to;                       // => liste des adresses des destinataires principaux (select of "to")
  protected $obj_select_cc;                       // => liste des adresses des copies carbone (select of "cc")
  protected $obj_select_bcc;                      // => liste des adresses des copies cachées (select of "bcc")
  protected $obj_query_group;                     // => requete des groupes mail
  protected $stri_query_user;                     // => requete des utilisateurs
  protected $obj_bt_add_to;                       // => le bouton ajouter un destinataire principal (add "to")
  protected $obj_bt_add_cc;                       // => le bouton ajouter une copie carbone (add "cc")
  protected $obj_bt_add_bcc;                      // => le bouton ajouter une copie cachée (add "bcc")
  protected $obj_bt_remove_to;                    // => le bouton enlever un destinataire principal (delete "to")
  protected $obj_bt_remove_cc;                    // => le bouton enlever une copie carbone (delete "cc")
  protected $obj_bt_remove_bcc;                   // => le bouton enlever une copie cachée (delete "bcc")
  protected $obj_bt_ok;                           // => le bouton valider (button ok)
  protected $obj_bt_cancel;                       // => le bouton annuler (button cancel)
  protected $obj_img_detail;                       // => le bouton visualisant les personnes qui sont dans le groupe
  protected $obj_form_address;                    // => le formulaire des adresses (addresses form)
  
  public $arra_sauv;                              // => tableau pour la sérialisation / désérialisation (serialization/ unserialization)

  //**** constructor ***********************************************************
  public function __construct()
  {    
    $this->stri_query_user="SELECT num_user, nom ||' '|| prenom n, email FROM user_user where email<>' ' ORDER BY n";
    //$this->constructor();    
  }


  //**** getter  *************************************************************
  public function getObject($obj_name)
  {
    //getter générique
    return $this->$obj_name;
  }
  
    
  //**** setter ****************************************************************
  public function setObject($obj_name,$new_obj)
  {
    //getter générique
    $this->$obj_name=$new_obj;  
  }
  
  public function setPostPart($stri_attribute)
  {
    //modifie l'état d'affichage de la partie passée en paramètre
    //@param $stri_attribute => le nom de l'attribut ("cc", "bcc")
    
    //tableau des valeurs pouvant être passées en paramètre
    $arra_attribute=array("cc", "bcc");
    
    //si la valeur du paramètre est correct    
    if(in_array($stri_attribute,$arra_attribute))
    {
      //construction de l'attribut
      $stri_complete_attribute="bool_".$stri_attribute;
      
      //changement d'état de l'attribut
      if($this->$stri_complete_attribute)
      {
        $this->$stri_complete_attribute=false;
      }
      else
      {
        $this->$stri_complete_attribute=true;
      }
    }
    else
    {
      //la valeur du paramètre n'est pas correcte.
      echo("<script>alert('Le paramètre stri_attribute ne peut prendre que les valeurs suivantes : cc, bcc);</script>");
    }
  }


  //**** private method ********************************************************
  public function constructor()
  {
    //permet de créer l'interface lors de la création de l'objet mais aussi lors de la désérialisation
   // echo"<br />Au constructeur du carnet. Ma session est :".$_SESSION['MAIL_OBJ_MAIL']." isset: ";var_dump(isset($_SESSION['MAIL_OBJ_MAIL']));    
    //**** font ****
    $this->obj_lb_address=new font(_TH_INSERT_ADDRESS,true);
    $this->obj_lb_address->setSize(3);
    $this->obj_lb_group=new font(_TH_GROUP,true);
    $this->obj_lb_user=new font(_TH_USER,true);
    $this->obj_lb_search=new font (_TH_BEGIN_BY);
    $this->obj_lb_to=new font (_TH_MAIL_TO,true);
    $this->obj_lb_cc=new font (_TH_MAIL_CC,true);
    $this->obj_lb_bcc=new font (_TH_MAIL_BCC,true);
    
    //**** text ****
    $this->obj_text_search=new text("text_search");
    $this->obj_text_search->setSize(29);
    $this->obj_text_search->setOnKeyUp("search();");
    
    //**** select ****
    $this->obj_select_group=new select("select_group[]");
    $this->obj_select_user=new select("select_user[]");
    $this->obj_select_to=new select("select_to[]");
    $this->obj_select_cc=new select("select_cc[]");
    $this->obj_select_bcc=new select("select_bcc[]");
    // define query
    $this->obj_query_group=new querry_select("SELECT id_group, nom_group FROM mail_groupe ORDER BY nom_group");
    $obj_query_user=new querry_select($this->stri_query_user);
    // define value of listbox and label
    $this->obj_select_group->makeQuerryToSelect($this->obj_query_group,0,1);
    $this->obj_select_user->makeQuerryToSelect($obj_query_user,2,1);
    //attribute
    $this->obj_select_group->setStyle("width:100%;");
    $this->obj_select_user->setStyle("width:100%;");
    $this->obj_select_to->setStyle("width:100%;");
    $this->obj_select_cc->setStyle("width:100%;");
    $this->obj_select_bcc->setStyle("width:100%;");
    $this->obj_select_group->setMultiple(true);
    $this->obj_select_user->setMultiple(true);
    $this->obj_select_to->setMultiple(true);
    $this->obj_select_cc->setMultiple(true);
    $this->obj_select_bcc->setMultiple(true);
    $this->obj_select_group->setSize(8);
    $this->obj_select_user->setSize(10);
    $this->obj_select_to->setSize(7);
    $this->obj_select_cc->setSize(7);
    $this->obj_select_bcc->setSize(7);
    $this->obj_select_to->setId("select_to");
    $this->obj_select_cc->setId("select_cc");
    $this->obj_select_bcc->setId("select_bcc");
    $this->obj_select_group->setId("select_group");
    $this->obj_select_user->setId("select_user");
               
    //**** button ****
    $this->obj_img_add_to=new img("images/module/fleche_add.gif");
    $this->obj_img_add_cc=new img("images/module/fleche_add.gif");
    $this->obj_img_add_bcc=new img("images/module/fleche_add.gif");
    $this->obj_img_rm_to=new img("images/module/fleche_del.gif");
    $this->obj_img_rm_cc=new img("images/module/fleche_del.gif");
    $this->obj_img_rm_bcc=new img("images/module/fleche_del.gif");
    $this->obj_img_add_to->setTitle(_BT_ADD_TO);
    $this->obj_img_add_cc->setTitle(_BT_ADD_CC);
    $this->obj_img_add_bcc->setTitle(_BT_ADD_BCC);
    $this->obj_img_rm_to->setTitle(_BT_REMOVE_TO);
    $this->obj_img_rm_cc->setTitle(_BT_REMOVE_CC);
    $this->obj_img_rm_bcc->setTitle(_BT_REMOVE_BCC);
    $this->obj_img_add_to->setAlt(_BT_ADD_TO);
    $this->obj_img_add_cc->setAlt(_BT_ADD_CC);
    $this->obj_img_add_bcc->setAlt(_BT_ADD_BCC);
    $this->obj_img_rm_to->setAlt(_BT_REMOVE_TO);
    $this->obj_img_rm_cc->setAlt(_BT_REMOVE_CC);
    $this->obj_img_rm_bcc->setAlt(_BT_REMOVE_BCC);
    $this->obj_img_add_to->setStyle("cursor:pointer;");
    $this->obj_img_add_cc->setStyle("cursor:pointer;");
    $this->obj_img_add_bcc->setStyle("cursor:pointer;");
    $this->obj_img_rm_to->setStyle("cursor:pointer;");
    $this->obj_img_rm_cc->setStyle("cursor:pointer;");
    $this->obj_img_rm_bcc->setStyle("cursor:pointer;");
    $this->obj_img_add_to->setOnclick("add_address('select_to');");
    $this->obj_img_add_cc->setOnclick("add_address('select_cc');");
    $this->obj_img_add_bcc->setOnclick("add_address('select_bcc');");
    $this->obj_img_rm_to->setOnclick("remove_address('select_to')"); 
    $this->obj_img_rm_cc->setOnclick("remove_address('select_cc')");
    $this->obj_img_rm_bcc->setOnclick("remove_address('select_bcc')");
    
    $this->obj_bt_ok=new button("bt_ok", _BT_OK);
    $this->obj_bt_ok->setOnClick("transfert_address(); window.close();");
    $this->obj_bt_cancel=new button("bt_cancel", _BT_CANCEL);
    $this->obj_bt_cancel->setOnClick("window.close();");
    $this->obj_img_detail=new img("images/module/view.gif");
    $this->obj_img_detail->setAlt(_BT_SEE_DETAIL);
    $this->obj_img_detail->setTitle(_BT_SEE_DETAIL);
    $this->obj_img_detail->setOnClick("see_detail();");
    
    //**** form ****
    $this->obj_form_address=new form("modules.php?op=modload&name=Mail&file=add_address","POST");
    $this->obj_form_address->setName("form_address");
  }
  
  private function javascripter()
  {
    //les actions javascript du formulaire des adresses (form's javascript)
    $stri_res="
    <script language=javascript>
      
      var id_group=0;       
      function see_detail()
      {
        //la fonction permet de vérifier le formulaire et d'envoyer le formulaire sur une autre page
        //@return : void
        
        //vérifie qu'au moins un groupe a été sélectionné (verify if group is selected)
        var trouve=verif_group_selected();
        
        if(trouve==true)
        {
          //ouvre une autre page (ouvre an other page)
          var stri_url='modules.php?op=modload&name=Mail&file=see_detail_group&id_group='+id_group;      
          window.open(stri_url, 'Detail','width=300, height=400, resizable=yes, scrollbars=yes');          
        }
        else
        {
          alert('"._MSG_ERROR_ADDRESS_GROUP_NOT_SELECTED."');
        }
      }
      
      function verif_group_selected()
      {
        //verifie si la liste groupe a une option sélectionnée (verify an option is selected in group's select)
        //@return : [boolean] => true : une option est sélectionnée (an option is selected)
        //                       false : aucune option n'est sélectionnée (any option is selected)
      
        //récupère les données sélectionnées dans la liste (get selected data in the list)
        obj_select_group = document.getElementById('select_group');
        var arra_group=get_data_select(obj_select_group);
      
        var int_nb=arra_group.length;         //compte le nb de ligne du tableau (count row in array)
        if(int_nb>0)                          //s'il existe des lignes (if row exists)
        {
          id_group=arra_group[0].value;
          return true;
        }
        return false;
      } 
  
      function create_string(int_nb,obj,stri_id)
      {
        //permet de créer la chaine des adresses sélectionnées pour les mettre dans le textbox de destination de l'email (create string of address to put in textbox)
        //@param : int_nb => nombre d'option dans le select (number of option in the select)
        //@param : obj => la liste de destination ou sont stockés les adresses à ajouter dans le textbox (select of deliver)
        //@param : stri_id => l'id de la liste déroulante ou l'on va afficher les adresses (select of email)
        //@return : void
        
        //récupère la valeur de la liste de destination de l'email (get value's select)
        var stri_temp=window.opener.document.getElementById(stri_id).value;
        //si des adresses sont déjà inscrites, alors on ajoute à la fin de l'adresse une virgule (add comma if value exists)
        if(stri_temp!='')
        {
          stri_temp=stri_temp+',';
        }
        //pour chaque option (foreach option)
        for(i=0;i<int_nb;i++)
        {
          //ajoute à une chaine temporaire le nom + une virgule si ce n'est pas la dernière option (add name to string)
          if(i==int_nb-1)
          {
            stri_temp=stri_temp + obj[i].value;
          }
          else
          {
            stri_temp=stri_temp + obj[i].value + ',';
          }
        }  
        
        //ajoute la chaine complète au mail (add to mail)
        window.opener.document.getElementById(stri_id).value=stri_temp;              
      }
      
      function transfert_address() 
      {
        //permet d'afficher les adresses choisies dans le textbox de destination de l'email (post address selected in the textbox of email)
        //@return : void
        
        obj_select_to = document.getElementById('select_to');     //recupère les listes de destination (get select)
        obj_select_cc = document.getElementById('select_cc');
        obj_select_bcc = document.getElementById('select_bcc');
        int_nb_to=obj_select_to.length;                           //compte le nb de personne (count option in the select)
        int_nb_cc=obj_select_cc.length;                           
        int_nb_bcc=obj_select_bcc.length;                         
        
        if(int_nb_to>0)
        {
          //forme la chaine des adresses (create string of addresses)
          stri_to=create_string(int_nb_to,obj_select_to,'id_to');
        }
        
        if(int_nb_cc>0)
        {
          //forme la chaine des adresses (create string of addresses)
          stri_cc=create_string(int_nb_cc,obj_select_cc,'id_cc');
        }
        
        if(int_nb_bcc>0)
        {
          //forme la chaine des adresses (create string of addresses)
          stri_bcc=create_string(int_nb_bcc,obj_select_bcc,'id_bcc');
        }
                                
      }
            
      function search()
      {
        //recherche rapide d'une personne (search quickly a user)
        //@return : void 
        
        stri_search=document.form_address.text_search.value;      //recupère le texte saisi (get text)
        int_nb_search=stri_search.length;                         //compte la longueur du texte (count text's length)
        //obj_select_user=document.form_address.select_user;        //récupère la liste des personnes (get select)        
        obj_select_user=document.getElementById('select_user');
        int_nb_user=obj_select_user.length;                       //compte le nb de personne (count option in the select)
        obj_select_user.selectedIndex = -1;                       //désélectionne les options (unselect)
        bool_find=false;
        
        
        i=0;
        while(i<int_nb_user && !bool_find)                                            //pour chaque option (foreach option)
        {
          var stri_extract=obj_select_user.options[i].text.substr(0,int_nb_search);   //extrait les premieres lettres de l'option, égale à la longueur du mot saisi dans la recherche (extract string in the option)
        
          if(stri_extract.toUpperCase()==stri_search.toUpperCase())                   //compare la chaine saisie avec celle extraite (match to string)
          {
            bool_find=true;
          }
          i++;
        }
        if(bool_find)                                       //si la chaine a été trouvée alors on sélectionne l'option (if string is found then select option)
          obj_select_user.options[i-1].selected=true;
        else
          obj_select_user.selectedIndex = -1;               //sinon aucune option est sélectionnée  (else any option is selected)
      }
      
      function add_address(stri_select)
      {
        //permet d'ajouter une adresse dans un des destinataires (principaux ou copies) (add an address in object to, cc or bcc)
        //@param : stri_select => l'objet de destination (object to, cc, bcc)
        
        //récupère les données sélectionnées dans les listes (get selected data in the list)
        obj_select_group = document.getElementById('select_group');
        var arra_group=get_data_select(obj_select_group);
                
        //var obj_select_user = document.form_address.select_user;
        obj_select_user=document.getElementById('select_user');        
        var arra_user=get_data_select(obj_select_user);
        var bool_exist=false;
        
        //ajoute les valeurs dans la liste stri_select (add value in the select)
        add(arra_group,stri_select);  
        add(arra_user,stri_select);
        
        //enleve les sélections dans les listes (unselect in the select)
        obj_select_group.selectedIndex = -1;
        obj_select_user.selectedIndex = -1;  
      }
      
      function remove_address(stri_select)
      {
        //supprime l'adresse dans le select stri_select (delete email in the select)
        //@param : stri_select => liste dans laquelle la valeur doit être supprimée (select to delete)
        //@return : void
        
        var s=document.getElementById(stri_select);           //récupère la liste (get select)
        var arra_user=get_data_select(s);                     //récupère les données dans la liste (get selected data in the list)
        
        for(a in arra_user)                                   //pour chaque option (foreach option)
        {
          int_i=get_index(s,arra_user[a].value);              //récupère l'index de l'option (get option's index) 
          s.options[int_i] = null;                            //efface l'option (clear option)
        }         
      }
      
      function get_data_select(obj_select)
      {
        //récupère les données sélectionnées dans les listes (get selected data in the list)
        //@param : obj_select => liste déroulante (select)
        //@return : arra_result => tableau des options sélectionnées (array of selected options)        

        var arra_result= new Array();                    //création du tableau (create array)
        var int_nb_result=obj_select.options.length;     //compte le nombre d'options dans la liste (count select's option)
      
        for (var i=0; i<int_nb_result; i++)              //pour chaque option (foreach option)
        {
          if (obj_select.options[i].selected)            //récupère l'option sélectionnée (get selected option)    
          {
            arra_result.push(obj_select.options[i]);     //ajoute dans le tableau (add in array)
          }
        }
        
        return arra_result;             
      }  
           
      function get_index(obj_select,int_value)
      {
        //obtient le numéro d'index de la valeur (get index)
        //@param : obj_select => la liste dans laquelle je recherche une valeur (select to search value)
        //@param : int_value => la valeur que je recherche (value)
        //@return : l'index de l'option (option's index)
        
        int_nb_result=obj_select.length;                 //compte le nb d'option (count option)
        for (var i=0; i<int_nb_result; i++)              //pour chaque option (foreach option)
        {
          //si la valeur est sélectionnée et a la valeur du paramètre, alors l'index est retourné (if value is selected and is parameter's value, then return index)
          if (obj_select.options[i].selected && obj_select.options[i].value==int_value)    
          {
            return i;
          }
        }
        return -1; 
      }
            
      function verify_option(int_value)
      {
        //vérifie si l'option int_value existe dans une des listes de destination (verify if option exists in the select)      
        //@param : int_value => valeur de l'option (option's value)
        //@return : true => l'option a été trouvée (option is found)
        //          false => l'option n'existe pas (option does not exist)
        
        //si l'option est à -1, cad la valeur par défaut, alors elle apparait comme déjà insérée dans une liste (if option is -1 --value by default-- then option appears like insered in the list )
        if(int_value!=-1)                                         
        {
          //vérifie que l'option n'existe pas dans la liste de destination principale (verify if option exists in the select to)
          var obj_select_to = document.form_address.select_to;        //recupère liste des destinataires principaux (get select to)
          var int_nb_to=obj_select_to.length;                         //compte le nb d'option dans la liste (count option in the select)
          
          for(i=0;i<int_nb_to;i++)                                    //pour chaque option (foreach option)
          {
            if(obj_select_to.options[i].value == int_value)           //vérifie que la valeur n'existe pas. (verify value does not exist)
            {
              return true;                                            //si oui, return true (if yes, return true)
            }
          }
          
          obj_select_cc=document.getElementById('select_cc');         //recupère liste des copies carbone (get select cc)
          if(obj_select_cc!=null)                                     //si l'objet existe (if object exists)
          {
            //vérifie que l'option n'existe pas dans la liste de copie carbone (verify if option exists in the select cc)
            //var obj_select_cc = document.form_address.select_cc;    
            var int_nb_cc=obj_select_cc.length;                       //compte le nb d'option dans la liste (count option in the select)
            
            for(i=0;i<int_nb_cc;i++)                                  //pour chaque option (foreach option)
            {
              if(obj_select_cc.options[i].value == int_value)         //vérifie que la valeur n'existe pas. (verify value does not exist)
              {
                return true;                                          //si oui, return true (if yes, return true)
              }
            }
          }
          
          obj_select_bcc=document.getElementById('select_bcc');       //recupère liste des copies cachées (get select bcc)
          if(obj_select_bcc!=null)                                    //si l'objet existe (if object exists)
          {
            //vérifie que l'option n'existe pas dans la liste de copie cachée (verify if option exists in the bcc)
            //var obj_select_bcc = document.form_address.select_bcc;    
            var int_nb_bcc=obj_select_bcc.length;                     //compte le nb d'option dans la liste (count option in the select)
            
            for(i=0;i<int_nb_bcc;i++)                                 //pour chaque option (foreach option)
            {
              if(obj_select_bcc.options[i].value == int_value)        //vérifie que la valeur n'existe pas. (verify value does not exist)
              {
                return true;                                          //si oui, return true (if yes, return true)
              }
            }
          }
        }
        else
        {
          return true;                                            
        }
        return false;
      }   

      function add(arra, select)
      {
        //ajoute la valeur dans le select (add value in the select)
        //@param : arra => tableau des options sélectionnées (array of selected options)
        //@param : select => la liste ds laquelle il faut ajouter la valeur (select to add value) 
        //@return : void
        
        var int_nb=arra.length;         //compte le nb de ligne du tableau (count row in array)
        if(int_nb>0)                    //si il existe des lignes, alors on ajoute les valeurs (if row exists then add value)
        {
          for(a in arra)                //pour chaque option (foreach option)
          {
            bool_exist=verify_option(arra[a].value);      //vérifie que l'option n'existe pas dans une liste de destination (verify option does not exist in the select)
            if(!bool_exist)                               //si elle n'a pas déjà été saisie alors on ajoute la valeur (if option does not exist then add value)  
            {
                                        //alert('l option n existe pas dans la liste to');
              obj_option = new Option(arra[a].text,arra[a].value);    //création de l'option (create option)
              var s=document.getElementById(select);            //récupère la liste (get select)
              s.options[s.length] = obj_option;                       //ajoute l'option à la liste (add option)  
            }
          }
        }
      }
      
      
    </script>";
    return $stri_res;
  }
  

  //**** public method *********************************************************  
  public function htmlValue()
  {
      
    //- Get JS and CSS
    $stri_js_and_css = (new js_loader())->htmlValue();
    
    //initialisation de tous les objets nécessaires si ca n'a pas déjà été fait
    $this->constructor();
      
    //renvoie le formulaire de pièces jointes
    $obj_html_table=new table();
    
    $temp_tr0=$obj_tr0=new tr();
    $temp_td1=$obj_tr0->addTd($this->obj_lb_address->htmlValue());
    $temp_td1->setAlign("center");
    $temp_td1->setClass("titre1 entete");
    $temp_td1->setColspan(3);
    $obj_html_table->insertTr($obj_tr0);
    
    $temp_tr0=$obj_tr0=new tr();
    $temp_tr0->setHeight(25);
    $obj_html_table->insertTr($obj_tr0);
    
    
    $temp_tr0=$obj_tr0=new tr();
    //$temp_td1=$obj_tr0->addTd($this->obj_lb_address->htmlValue());
    $temp_td1=$obj_tr0->addTd();
    $temp_td2=$obj_tr0->addTd($this->obj_form_address->getStartBalise());
    $temp_td3=$obj_tr0->addTd($this->obj_lb_to->htmlValue());
    //$temp_td1->setAlign("left");
    $temp_td3->setAlign("left");
    $temp_td3->setVAlign("bottom");
    $temp_td3->setClass("titre2 entete");
    $temp_tr0->setHeight("2");
    $obj_html_table->insertTr($obj_tr0);
    
    
    $obj_tr1=new tr();
    $temp_td1=$obj_tr1->addTd($this->obj_lb_group->htmlValue()." ".$this->obj_img_detail->htmlValue());
    $temp_td2=$obj_tr1->addTd($this->obj_img_add_to->htmlValue());
    $temp_td3=$obj_tr1->addTd($this->obj_select_to->htmlValue());
    $temp_td3->setRowspan(4);
    $temp_td1->setAlign("left");
    $temp_td2->setAlign("right");
    $temp_td3->setAlign("center");
    $temp_td1->setValign("bottom");
    $temp_td2->setValign("bottom");
    $temp_td3->setValign("top");
    $temp_td1->setWidth("45%");
    $temp_td1->setClass("titre2 entete");
    $temp_td2->setWidth("10%");
    $temp_td3->setWidth("45%");
    $obj_html_table->insertTr($obj_tr1);
  
    $obj_tr2=new tr();
    $temp_td1=$obj_tr2->addTd($this->obj_select_group->htmlValue());
    $temp_td2=$obj_tr2->addTd($this->obj_img_rm_to->htmlValue());
    $temp_td1->setRowspan(5);
    $temp_td1->setAlign("center");
    $temp_td1->setValign("top");
    $temp_td2->setAlign("right");
    $temp_td2->setValign("top");
    $obj_html_table->insertTr($obj_tr2);
    
    $obj_tr3=new tr();
    $temp_td2=$obj_tr3->addTd("&nbsp;");
    $obj_html_table->insertTr($obj_tr3);
    
    $obj_tr4=new tr();
    $temp_td2=$obj_tr4->addTd("&nbsp;");
    $obj_html_table->insertTr($obj_tr4);

    $stri_title_cc=($this->bool_cc)?$this->obj_lb_cc->htmlValue():"&nbsp;";      
    $obj_tr5=new tr();
    $temp_td2=$obj_tr5->addTd("&nbsp;");
    $temp_td3=$obj_tr5->addTd($stri_title_cc);
    $temp_td3->setAlign("left");
    $temp_td3->setVAlign("bottom");
    $temp_td3->setClass("titre2 entete");
    $obj_html_table->insertTr($obj_tr5);
    
    $stri_img_add_cc=($this->bool_cc)?$this->obj_img_add_cc->htmlValue():"&nbsp;";
    $stri_select_cc=($this->bool_cc)?$this->obj_select_cc->htmlValue():"&nbsp;";
    $obj_tr6=new tr();
    $temp_td2=$obj_tr6->addTd($stri_img_add_cc);
    $temp_td3=$obj_tr6->addTd($stri_select_cc);
    $temp_td3->setRowspan(4);
    $temp_td2->setAlign("right");
    $temp_td3->setAlign("center");
    $temp_td2->setValign("bottom");
    $temp_td3->setValign("top");
    $obj_html_table->insertTr($obj_tr6);
    
    $stri_img_rm_cc=($this->bool_cc)?$this->obj_img_rm_cc->htmlValue():"&nbsp;";
    $obj_tr7=new tr();
    $temp_td1=$obj_tr7->addTd($this->obj_lb_user->htmlValue());
    $temp_td2=$obj_tr7->addTd($stri_img_rm_cc);
    $temp_td2->setAlign("right");
    $temp_td1->setAlign("left");
    $temp_td1->setValign("bottom");
    $temp_td1->setClass("titre2 entete");
    $temp_td2->setValign("top");
    $obj_html_table->insertTr($obj_tr7);
    
    
    $this->obj_text_search->setPlaceholder($this->obj_lb_search->getValue());
            
            
    $obj_tr8=new tr();
    //$temp_td1=$obj_tr8->addTd($this->obj_lb_search->htmlValue()." ".$this->obj_text_search->htmlValue());
    $temp_td1=$obj_tr8->addTd($this->obj_text_search->htmlValue());
    $temp_td2=$obj_tr8->addTd("&nbsp;");
    $temp_td1->setAlign("left");
    $temp_td1->setValign("bottom");
    $obj_html_table->insertTr($obj_tr8);
    
    $obj_tr9=new tr();
    $temp_td1=$obj_tr9->addTd($this->obj_select_user->htmlValue());
    $temp_td2=$obj_tr9->addTd("&nbsp;");
    $temp_td1->setRowspan(6);
    $temp_td1->setAlign("center");
    $temp_td1->setValign("top");
    $obj_html_table->insertTr($obj_tr9);
    
    $stri_title_bcc=($this->bool_bcc)?$this->obj_lb_bcc->htmlValue():"&nbsp;";
    $obj_tr10=new tr();
    $temp_td2=$obj_tr10->addTd("&nbsp;");
    $temp_td3=$obj_tr10->addTd($stri_title_bcc);
    $temp_td3->setAlign("left");
    $temp_td3->setVAlign("bottom");
    $temp_td3->setClass("titre2 entete");
    $obj_html_table->insertTr($obj_tr10);

    $stri_img_add_bcc=($this->bool_bcc)?$this->obj_img_add_bcc->htmlValue():"&nbsp;";
    $stri_select_bcc=($this->bool_bcc)?$this->obj_select_bcc->htmlValue():"&nbsp;";
    $obj_tr11=new tr();
    $temp_td2=$obj_tr11->addTd($stri_img_add_bcc);
    $temp_td3=$obj_tr11->addTd($stri_select_bcc);
    $temp_td3->setRowspan(4);
    $temp_td2->setAlign("right");
    $temp_td3->setAlign("center");
    $temp_td2->setVAlign("bottom");
    $temp_td3->setVAlign("top");
    $obj_html_table->insertTr($obj_tr11);
    
    $stri_img_rm_cc=($this->bool_bcc)?$this->obj_img_rm_bcc->htmlValue():"&nbsp;";
    $obj_tr12=new tr();
    $temp_td2=$obj_tr12->addTd($stri_img_rm_cc);
    $temp_td2->setAlign("right");
    $temp_td2->setVAlign("top");
    $obj_html_table->insertTr($obj_tr12);

    $obj_tr13=new tr();
    $temp_td2=$obj_tr13->addTd("&nbsp;");
    $obj_html_table->insertTr($obj_tr13);

    $obj_tr14=new tr();
    $temp_td2=$obj_tr14->addTd("&nbsp;");
    $obj_html_table->insertTr($obj_tr14);

    $obj_tr15=new tr();
    $temp_td1=$obj_tr15->addTd("&nbsp;");
    $temp_td2=$obj_tr15->addTd("&nbsp;");
    $temp_td3=$obj_tr15->addTd("&nbsp;");
    $obj_html_table->insertTr($obj_tr15);
        
    
    $this->obj_bt_ok->setClass('button');
    $this->obj_bt_cancel->setClass('button');
            
    $obj_tr16=new tr();
    $temp_td2=$obj_tr16->addTd($this->obj_bt_ok->htmlValue()."  ".$this->obj_bt_cancel->htmlValue().$this->obj_form_address->getEndBalise());
    $temp_td2->setAlign("center");
    $temp_td2->setColspan(3);
    $obj_html_table->insertTr($obj_tr16);
    
    //$obj_html_table->setCellspacing(0);
    //$obj_html_table->setCellpadding(0);
    $obj_html_table->setWidth("100%");
    $obj_html_table->setBorder(0);
    
    $stri_res=$this->javascripter();
    
    //- CSS
    $stri_res.=$stri_js_and_css;
    
    $stri_res.=$obj_html_table->htmlValue();
    
    
    
    
    return $stri_res;    
  }
  
  //**** method of serialization ***********************************************
  public function __sleep() 
  {
    //sérialisation de la classe address
    $this->arra_sauv['cc']= $this->bool_cc;
    $this->arra_sauv['bcc']= $this->bool_bcc;
    $this->arra_sauv['query_user']= $this->stri_query_user;
    
    return array('arra_sauv');
  }
  
  public function __wakeup() 
  {  
    //désérialisation de la classe address 
    $this->constructor();
    $this->bool_cc= $this->arra_sauv['cc'] ;
    $this->bool_bcc= $this->arra_sauv['bcc'] ;
    $this->stri_query_user= $this->arra_sauv['query_user'];
    //$this->arra_sauv = array();    
  } 
}
?>
