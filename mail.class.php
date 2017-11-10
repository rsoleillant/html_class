<?php
/*******************************************************************************
Create Date : 17/10/2007
 ------------------------------------------------------------------------------
 Class name : Mail
 Version : 1.1
 Author : Emilie Merlat
 Description : permet de gérer les mails
 Doc : \\stpr0341\Partage_hotline\Analyse_fonctionnel_asisline\mod_mail\man_techn_mail.txt
*******************************************************************************/

include_once("includes/html_class.php");
include_once("address.class.php");
include_once("attachment.class.php");
include_once("includes/classes/phpmailer/class.phpmailer.php");
include_once("modules/Mail/pnlang/".pnUserGetLang()."/user.php");

class Mail extends PHPMailer 
{
  //**** attribute *************************************************************
  protected $bool_sender=true;      // => la partie expéditeur est affichée par défaut (part deliver)
  protected $bool_cc=true;          // => la partie copie carbone est affichée par défaut (part cc)
  protected $bool_bcc=true;         // => la partie copie cachée est affichée par défaut (part bcc)  
  protected $bool_attachment=true;  // => la partie fichiers attachés est affichée par défaut (part attachment)
  protected $bool_mode=true;        // => la partie mode HTML/Text est affichée par défaut (part mode)
  
  protected $stri_allowed="a,b,big,blockquote,br,cite,code,div,em,font,form,h1,h2,h3,h4,h5,h6,hr,i,img,input,li,link,ol,option,p,pre,s,samp,select,small,span,strong,style,sub,sup,table,tbody,td,textarea,tfoot,th,thead,tr,u,ul"; // => les balises autorisés dans les mails (tag allowed in email)
  protected $stri_forbidden="";     // => les balises interdits dans les mails (tag forbidden in email)  
  
  protected $obj_a_to;              // le label titre des destinataires principaux (label of to)
  protected $obj_lb_to;             // le label titre de l'expéditeur (label title of deliver)
  protected $obj_lb_from;           // le label titre de l'expéditeur (label title of deliver)
  protected $obj_lb_from_name;      // le label de l'expéditeur (label of deliver)
  protected $obj_lb_cc;             // le label titre des copies carbone (label title of cc)
  protected $obj_lb_bcc;            // le label titre des copies cachées (label title of bcc)
  protected $obj_lb_subject;        // le label titre de l'objet (label title of cc)
  protected $obj_a_attachment;      // le label titre des fichiers joints (label title of attachment)
  protected $obj_div;               // le div contenant la liste des fichiers join (tag div containing joined files)
  protected $obj_lb_html;           // le label titre du mode HTML (label title of HTML)
  protected $obj_lb_text;           // le label titre du mode Text (label title of mode)
  protected $obj_text_to;           // => le textbox des destinataires principaux (textbox of deliver)
  protected $obj_text_cc;           // => le textbox des copies carbone (textbox of cc)
  protected $obj_text_bcc;          // => le textbox des copies cachées (textbox of bcc)
  protected $obj_text_subject;      // => le textbox de l'objet du mail (textbox of subject)
  protected $obj_textarea_message;  // => le message du mail (message)
  protected $obj_radio_html;        // => la case à cocher pour le mode HTML (HTML mode)
  protected $obj_radio_text;        // => la case à cocher pour le mode Text (Text mode)
  protected $obj_bt_ok;             // => le bouton valider (button ok)
  protected $obj_bt_cancel;         // => le bouton annuler (button cancel)
  protected $obj_form_mail;         // => le formulaire du mail (email's form)
  protected $obj_img_attachment;    // => le bouton permettant d'ajouter des fichiers joints (button add attachment)
  protected $obj_attachment;        // => l'interface des fichiers joints (attachment's interface)
  protected $obj_address;           // => l'interface des adresses (addresses form)
  
  protected $stri_script;           //=> script à executer lorsque le mail a été envoyé
  protected $arra_script_param=array();     //=> tableau contenant les paramètres du script à exécuter
  protected $unique_id_save; //id unique pr la sauvegarde
  
  public $arra_sauv=array();        // => tableau pour la sérialisation / désérialisation (serialization/unserialization)
  
  
  
  //**** constructor ***********************************************************
  function __construct($stri_mail_sender)
  {
    //@param  $stri_mail_sender => mail de l'expéditeur

    // déclaration des objets de l'interface
    $this->obj_lb_from=new font(_TH_MAIL_FROM,true);
        $this->obj_lb_from->setSize(1);
    $this->obj_lb_from_name=new font($stri_mail_sender,true);
        $this->obj_lb_from_name->setSize(1);
    $this->From=$stri_mail_sender;
    $this->FromName=$stri_mail_sender;
    $this->obj_lb_to = new font(_TH_MAIL_TO,true);
        $this->obj_lb_to->setSize(1);
    $this->obj_a_to=new a("#",$this->obj_lb_to->htmlValue(),true);
    $this->obj_a_to->setOnclick("window.open('modules.php?op=modload&name=Mail&file=form_address','Adresse','width=700,height=650,resizable=yes');");
    $this->obj_lb_cc=new font(_TH_MAIL_CC,true);
        $this->obj_lb_cc->setSize(1);
    $this->obj_lb_bcc=new font(_TH_MAIL_BCC,true);
        $this->obj_lb_bcc->setSize(1);
    $this->obj_lb_subject=new font(_TH_MAIL_SUBJECT,true);
    $this->obj_lb_subject->setSize(1);
    $obj_font_attachement = new font(_TH_MAIL_ATTACHMENT,true);
        $obj_font_attachement ->setSize(1);
    
    $this->obj_a_attachment=new a("#",$obj_font_attachement->htmlValue(),true);
    $this->obj_a_attachment->setStyle(' font-size: 15px; vertical-align: middle;');
    //Déclarer dans htmlValue()
    //$this->obj_a_attachment->setOnclick("window.open('modules.php?op=modload&name=Mail&file=form_attachment','Pièces jointes','width=340,height=100,resizable=yes,scrollbars=yes');");
    $this->obj_div=new div('pj',"");
    $this->obj_div->setStyle('display: inline; font-size: 12px;');
    $this->obj_lb_html=new font(_TH_MAIL_HTML);
    $this->obj_lb_text=new font(_TH_MAIL_TEXT);
    $this->obj_text_to=new text("text_to");
    //$this->obj_text_to->setSize(100);
    $this->obj_text_to->setStyle("width: 100%");
    $this->obj_text_to->setId("id_to");
    $this->obj_text_cc=new text("text_cc");
    //$this->obj_text_cc->setSize(100);
    $this->obj_text_cc->setStyle("width: 100%");
    $this->obj_text_cc->setId("id_cc");
    $this->obj_text_bcc=new text("text_bcc");
    //$this->obj_text_bcc->setSize(100);
    $this->obj_text_bcc->setStyle("width: 100%");
    $this->obj_text_bcc->setId("id_bcc");
    $this->obj_text_subject=new text("text_subject");
    //$this->obj_text_subject->setSize(100);
    $this->obj_text_subject->setStyle("width: 100%");
    /*
    $this->obj_textarea_message=new text_arrea("texta_message");
    $this->obj_textarea_message->setRows(15);
    $this->obj_textarea_message->setCols(75);
    */
    $this->obj_textarea_message = new editor('texta_message');
    $this->obj_textarea_message->setToolbar("TinyBare"); 
    //$this->obj_textarea_message->setWidth('620px');
    $this->obj_textarea_message->setHeight("222px");
    
    $this->obj_radio_html=new radio("radio_mode","html");
    $this->obj_radio_html->setChecked(true);
    $this->obj_radio_text=new radio("radio_mode","text");
    $this->obj_bt_ok=new submit("bt_send",_BT_SEND_MAIL);
    $this->obj_bt_cancel=new button("bt_cancel",_BT_CANCEL);
    $this->obj_bt_cancel->setOnClick("submit();");
    //$this->obj_img_attachment=new img("images/add_attach.gif");
    $this->obj_img_attachment=new img("images/demande_pdr/trombone.png");
    //$this->obj_img_attachment->setOnclick("window.open('modules.php?op=modload&name=Mail&file=form_attachment','Pièces jointes','width=340,height=100,resizable=yes');");
    $this->obj_img_attachment->setWidth("16px");
    //$this->obj_img_attachment->setHeight("14px");
    $this->obj_img_attachment->setClass("img_pj");
    
    $this->obj_img_attachment->setStyle("cursor:pointer; vertical-align: middle");
    $this->obj_attachment=new Attachment();
    $this->obj_address=new Address();
    $this->obj_form_mail=new form("modules.php?op=modload&name=Mail&file=send_mail","POST");
    $this->obj_form_mail->setName("form_mail");   
    $this->obj_form_mail->setOnSubmit("return verify_form()");
    
   
    //$stri_serial_mail=serialize($this);                         //sérialise la classe mail
    //echo"Chaine sérialisée à la construction de mail : ";print_r($stri_serial_mail);
    //stocke la sérialisation dans une session
    //utilisation de urlencode pour permettre de déserialiser par la suite les chaines contenant des simples quotes ex: "titre d'une image"
    //$t1=microtime();
    //$_SESSION['MAIL_OBJ_MAIL']=urlencode($stri_serial_mail);    
    //echo"Session à la construction de mail : ";print_r($_SESSION['MAIL_OBJ_MAIL']);
    //usleep(500000); //attente d'une demi seconde
    //usleep(437500); //attente d'une demi seconde
    //$t2=microtime();
    //echo "<br />Temps d'execution : ".($t2-$t1);
    
  }
  
  
  //**** setter ****************************************************************
  public function setAllowed($stri_value)
  {
    //permet d'ajouter les balises html autorisées dans le mail 
    // par defaut : font,a,b,u,i,br sont autorisées
    //@param : $stri_value => les valeurs autorisées
    //@return : void
    
    $this->stri_allowed=$stri_value;
  }
  
  public function setForbidden($stri_value)
  {
    //permet d'ajouter les balises html interdites dans le mail
    //@param : $stri_value => les valeurs interdites
    //@return : void
    
    $this->stri_forbidden=$stri_value;
  }
  
  public function setTo($stri_to)
  {
    //permet d'ajouter l'email principal
    //@param : $stri_bcc => l'email principal
    //@return : void
    
    $this->obj_text_to->setValue($stri_to);
  }
  
  public function setCC($stri_cc)
  {
    //permet d'ajouter la copie carbone 
    //@param : $stri_cc => l'email de la copie carbone
    //@return : void
    
    $this->obj_text_cc->setValue($stri_cc);
  }
  
  public function setBCC($stri_bcc)
  {
    //permet d'ajouter la copie cachée
    //@param : $stri_bcc => l'email de la copie cachée
    //@return : void
    
    $this->obj_text_bcc->setValue($stri_bcc);
  }
  
  public function setSubject($stri_subject)
  {
    //permet d'ajouter l'objet du mail
    //@param : $stri_subject => l'objet du mail
    //@return : void
    
    $this->obj_text_subject->setValue($stri_subject);
  }
  
  public function setMessage($stri_msg)
  {
    //permet d'ajouter le message du mail
    //@param : $stri_subject => le message du mail
    //@return : void
    
    $this->obj_textarea_message->setValue($stri_msg);
  }
  
  public function setMode($stri_mode)
  {
    //permet de cocher les cases concernant le mode de mail
    //@param : $stri_mode => text ou html : mode du mail
    //@return : void
    
    //tableau des valeurs pouvant être passées en paramètre
    $arra_attribute=array("html", "text");
    
    //si la valeur du paramètre est correct    
    if(in_array($stri_mode,$arra_attribute))
    {
      //si le mode est texte alors on coche la case texte
      if(strtolower($stri_mode)=="text")
      {
        $this->obj_radio_html->setChecked(false);
        $this->obj_radio_text->setChecked(true);
        
      }
      else
      {
        $this->obj_radio_html->setChecked(true);
        $this->obj_radio_text->setChecked(false);
      }
    }
    else
    {
      //la valeur du paramètre n'est pas correcte.
      echo("<script>alert('Le paramètre stri_mode ne peut prendre que les valeurs suivantes : html , text);</script>");
    }     
  }
  
  public function setParameterAttachment($stri_server_directory,$stri_file_extension,$int_file_max_size)
  {   
    //modifie les paramètres pour uploader un fichier
    //@param $stri_server_directory => le repertoire de destination des fichiers joints sur le serveur
    //@param $stri_file_extension => les extensions possibles pour les fichiers joints
    //@param $int_file_max_size => la taille maximale du fichier joint en octet
  
    $this->obj_attachment->setParameter($stri_server_directory,$stri_file_extension,$int_file_max_size);
  }
  
  
  public function setSizeMaxFile($int_file_max_size)
  {
    //modifie la taille maximale du fichier à uploader 
    //@param : $int_file_max_size => taille maxi du fichier 
    $this->obj_attachment->setSizeMaxFile($int_file_max_size);
  }


  public function setPostPart($stri_attribute)
  {
    //modifie l'état d'affichage de la partie passée en paramètre
    //@param $stri_attribute => le nom de l'attribut ("sender", "cc", "bcc", "attachment", "mode")
    
    //tableau des valeurs pouvant être passées en paramètre
    $arra_attribute=array("sender", "cc", "bcc", "attachment", "mode");
    
    //si la valeur du paramètre est correct    
    if(in_array($stri_attribute,$arra_attribute))
    {
      //construction de l'attribut
      $stri_complete_attribute="bool_".$stri_attribute;
      
      //si l'attribut est cc ou bcc
      if($stri_attribute=="cc" || $stri_attribute=="bcc")
      {
        //changement d'état de l'attribut au niveau de la classe adresse
        $this->obj_address->setPostPart($stri_attribute);
      }
      
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
      echo("<script>alert('Le paramètre stri_attribute ne peut prendre que les valeurs suivantes : sender, cc, bcc, attachment, mode);</script>");
    }
  }  
  
  public function setScript($script,$arra_script="")
  {//permet d'indique le script qui doit être exécuté après l'envoi du mail
    //@param $script => le chemin complet ou trouver le script à exectuer
    //@param $arra_script => le tableau des paramètres à exécuter
    $this->stri_script=$script; 
    $this->arra_script_param=($arra_script!="")?$arra_script:array();
   
  }
  
  
  //**** getter ****************************************************************
  public function getAllowed()
  {
    return $this->stri_allowed;
  }
  
  public function getForbidden()
  {
    return $this->stri_forbidden;
  }
  
  public function getObject($stri_object)
  {
    //renvoie l'objet de l'interface passée en paramètre 
    //@param $stri_object => le nom de l'objet (lb_from,lb_from_name,a_to,lb_cc,lb_bcc,lb_subject,a_attachment,text_to,text_cc,text_bcc,text_subject,radio_html,radio_text,textarea_message,attachment,address)

    //tableau des valeurs pouvant être passées en paramètre     
    $arra_object=array("lb_from","lb_from_name","a_to","lb_cc","lb_bcc","lb_subject","a_attachment","text_to","text_cc","text_bcc","text_subject","radio_html","radio_text","textarea_message","attachment", "address");

    //si la valeur du paramètre est correct           
    if(in_array($stri_object,$arra_object))
    {
      //construction de l'attribut      
      $stri_complete_object="obj_".$stri_object;
      
      //renvoie l'objet
      return $this->$stri_complete_object;
    }
    else
    {
      //erreur : la valeur en paramètre n'est pas correcte.
      echo("<script>alert('Le paramètre stri_objet ne peut prendre que les valeurs suivantes : lb_from,lb_from_name,a_to,lb_cc,lb_bcc,lb_subject,a_attachment,text_to,text_cc,text_bcc,text_subject,radio_html,radio_text,textarea_message,attachment,address');</script>");
    }  
  }
  
  public function getPostPart($stri_attribute)
  {
    //renvoie l'état d'affichage de la partie passée en paramètre
    //@param $stri_attribute => le nom de l'attribut ("sender", "cc", "bcc", "attachment", "mode")

    //tableau des valeurs pouvant être passées en paramètre        
    $arra_attribute=array("sender", "cc", "bcc", "attachment", "mode");
    
    //si la valeur du paramètre est correct       
    if(in_array($stri_attribute,$arra_attribute))
    {
      //construction de l'attribut      
      $stri_complete_attribute="bool_".$stri_attribute;
      
      //renvoie l'état de l'attribut
      return $this->$stri_complete_attribute;
    }
    else
    {
      //erreur : la valeur en paramètre n'est pas correcte.
      echo("<script>alert('Le paramètre stri_attribute ne peut prendre que les valeurs suivantes : sender, cc, bcc, attachment, mode');</script>");
    }
  }    
  public function getScript()
  {//retourne le script a executer après avoir envoyé le mail
   return $this->stri_script;
  }
  
  public function getScriptParam()
  {//retourne le tableau de paramètres du script a executer après avoir envoyé le mail
   return $this->arra_script_param;
  }
  //**** other method **********************************************************
  public function send($stri_to, $stri_cc, $stri_bcc, $stri_subject, $stri_message, $stri_mode)
  {
    //cette function permet d'envoyer le mail (send email)
    //@param $stri_to => les destinataires principaux (deliver)
    //@param $stri_cc => les copies carbone (cc)
    //@param $stri_bcc => les copies cachées (bcc)
    //@param $stri_subject => l'objet (subject)
    //@param $stri_message => le message (message)
    //@param $stri_mode => le mode HTML / Texte (HTML / Text)
    
    $stri_message=$this->safeHTML($stri_message);                 //vérifie les balises HTML (verify HTML tag)
      
    $this->Mailer="sendmail";                                     //le type de mail (type of email)
   
    $arra_to=explode(",",$stri_to);                                 //Découpe la chaine des destinataires dans un tableau (cut deliver in the array)
  	$this->analyse_mail($arra_to,"to"); 
 	
  	$arra_cc=explode(",",$stri_cc);       
    $this->analyse_mail($arra_cc,"cc"); 
  	
  	$arra_bcc=explode(",",$stri_bcc);                                //Découpe la chaine des copies cachées dans un tableau (cut bcc in the array)
  	$this->analyse_mail($arra_bcc,"bcc"); 
  	
  	$this->Subject=$stri_subject;                                  //l'objet (subject)
  
    if($stri_mode=="text")                                         //si la case Mode Texte est cochée (if Text case is checked)
    {
      $stri_message=str_replace(array("<br />"), array("\n"),$stri_message);//on met des saut de ligne à la place des <br />
      $stri_message=strip_tags($stri_message);//on enlève toutes les balises html
      $this->IsHTML(false);                                        //mode Text  
  	}
  	else
  	{                                                              //mode HTML
  	 $this->IsHTML(true);
     $stri_message=$this->MsgHTML($stri_message,$_SERVER["DOCUMENT_ROOT"]);      //on appel cette méthode afin d'embarquer les images html dans le mail                      
     if(stripos($stri_message,"<br />")===false) // /!\ pour CKEditor  /!\
      $stri_message=nl2br($stri_message);
    }
    
   
    $this->Body=$stri_message;                                     //le corps du message (message)
    
    
   
    //si la partie fichiers joints est disponible    
    if($this->bool_attachment)
    {
      //echo"<br />Des pièces jointes existent :<pre>";print_r($this->obj_attachment->getAttachment());
       
      //pour chaque fichier joint, ils sont ajoutés dans la classe PHPMailer
      foreach($this->obj_attachment->getAttachment() as $stri_name)
      {
        $res=PHPMailer::AddAttachment($this->obj_attachment->getServerDirectory().$stri_name, substr($stri_name,stripos($stri_name,"@")+1,strlen($stri_name)));
      }
    }
    
    //RS 04/04/2011 purge du fichier sérialisé
    $stri_unique_id=(isset($_POST["unique_id_save"]))?$_POST["unique_id_save"]:$_SESSION['unique_id_save']; //RS 01/04/2011 correction pb de transmission de l'id du fichier
    $stri_file_name=$stri_unique_id."_mail.obj";
    $stri_path="modules/Mail/serialized";
    unlink ("$stri_path/$stri_file_name");
     
    //envoie du message 
    $result=PHPMailer::send();   
    if($result)
    {
      //copie les fichiers joints dans le répertoire
      $this->obj_attachment->copy_attachment();
      //supprime le dossier perso temporaire
      $this->obj_attachment->remove_dir();
      $this->getTrace($result); // ajout gr 08012009 : permet de tracer les envoie de mail en BD
     
      $stri_script_to_include=$this->stri_script;
      if($stri_script_to_include!="")
       { 
        $arra_script_param=$this->getScriptParam();//on défini le tableau de paramètre pour le script
        include_once($stri_script_to_include);  
       }     
      return true;
    }            
    
    return false;                  
  }
  
  //**** posting method **********************************************************
  /*public function htmlValue()
  {     


    //- Get JS and CSS
    $stri_js_and_css = (new js_loader())->htmlValue();
 
    $this->unique_id_save = uniqid();
    //renvoie le formulaire de mail
    //foreach($_POST as $index => $valeur) echo "<br>\$_POST['".$index."']=\"".$valeur."\"<br>";
    $obj_html_table=new table();
    
    //- Entete 
    $obj_tr_entete=new tr();
    $temp_td_entete=$obj_tr_entete->addTd((new font(__LIB_MAIL))->htmlValue());
    $temp_td_entete->setAlign("center");
    $temp_td_entete->setClass("titre0 entete");
    $temp_td_entete->setColspan(5);
    $obj_html_table->insertTr($obj_tr_entete);
    
    $obj_tr0=new tr();
    $temp_td1=$obj_tr0->addTd($this->obj_form_mail->getStartBalise());
    $temp_td1->setAlign("center");
    $obj_html_table->insertTr($obj_tr0);
    
    if($this->bool_sender) //si la partie "Expéditeur" est à true
    {
      //la partie "Expéditeur" est affichée
      $obj_tr1=new tr();
      $temp_td1=$obj_tr1->addTd($this->obj_lb_from->htmlValue());
      $temp_td1->setAlign("right");
      $temp_td2=$obj_tr1->addTd($this->obj_lb_from_name->htmlValue());
      $temp_td2->setColspan(3);
      $obj_html_table->insertTr($obj_tr1);
    }
    
    // la partie "Destinataires principaux"
    $obj_tr2=new tr();
    $temp_td1=$obj_tr2->addTd($this->obj_a_to->htmlValue());
    $temp_td1->setAlign("right");
    $temp_td2=$obj_tr2->addTd($this->obj_text_to->htmlValue());
    $temp_td2->setColspan(3);
    $obj_html_table->insertTr($obj_tr2);

    if($this->bool_cc) //si la partie "Copie carbone" est à true
    {
    
      // la partie "Copie carbone" est affichée
      $obj_tr3=new tr();
      $temp_td1=$obj_tr3->addTd($this->obj_lb_cc->htmlValue());
      $temp_td1->setAlign("right");
      $temp_td2=$obj_tr3->addTd($this->obj_text_cc->htmlValue());
      $temp_td2->setColspan(3);
      $obj_html_table->insertTr($obj_tr3);
    }
    
    if($this->bool_bcc) //si la partie "Copie cachée" est à true
    {
      // la partie "Copie cachée" est affichée
      $obj_tr4=new tr();
      $temp_td1=$obj_tr4->addTd($this->obj_lb_bcc->htmlValue());
      $temp_td1->setAlign("right");
      $temp_td2=$obj_tr4->addTd($this->obj_text_bcc->htmlValue());
      $temp_td2->setColspan(3);
      $obj_html_table->insertTr($obj_tr4);
    }

    //la partie "Objet"
    $obj_tr5=new tr();
    $temp_td1=$obj_tr5->addTd($this->obj_lb_subject->htmlValue());
    $temp_td1->setAlign("right");
    $temp_td2=$obj_tr5->addTd($this->obj_text_subject->htmlValue());
    $temp_td2->setColspan(3);
    $obj_html_table->insertTr($obj_tr5);

    if($this->bool_mode)    //si la partie "Mode" est à true
    {
      //la partie "Mode" est affichée
      $obj_tr6=new tr();
      $temp_td1=$obj_tr6->addTd($this->obj_lb_html->htmlValue());
      $temp_td1->setAlign("right");
      $temp_td2=$obj_tr6->addTd($this->obj_radio_html->htmlValue());
      $temp_td2->setAlign("left");
      $temp_td2->setWidth("5%");
      $temp_td3=$obj_tr6->addTd($this->obj_lb_text->htmlValue());
      $temp_td3->setAlign("right");
      $temp_td2->setWidth("10%");
      $temp_td4=$obj_tr6->addTd($this->obj_radio_text->htmlValue());
      $temp_td4->setAlign("left");
      $temp_td4->setWidth("60%");
      $obj_html_table->insertTr($obj_tr6);
      
    }

    if($this->bool_attachment) //si la partie "Fichier joint" est à true
    {
      $this->obj_a_attachment->setOnclick("window.open('modules.php?op=modload&name=Mail&file=form_attachment&id_slz=".$this->unique_id_save."','Pièces jointes','width=340,height=175,resizable=yes,scrollbars=yes');");
      //la partie "Fichier joint" est affichée
      $obj_tr7=new tr();
      $temp_td1=$obj_tr7->addTd($this->obj_img_attachment->htmlValue()." ".$this->obj_a_attachment->htmlValue());
      $temp_td1->setAlign("right");  
      $stri_file="";
      
      if(count($this->obj_attachment->getAttachment())>0)
      {
        foreach($this->obj_attachment->getAttachment() as $filename)
        {
          $stri_file.=$filename."<br />";
        }
      }
      //$temp_td2=$obj_tr7->addTd("&nbsp;<DIV id='pj'>$stri_file</DIV>");
      $this->obj_div->addContain($stri_file);
      $temp_td2=$obj_tr7->addTd("&nbsp;".$this->obj_div->htmlValue());
      
      $temp_td2->setColspan(3);
      $obj_html_table->insertTr($obj_tr7);
    }

    // la partie "Message"
    $obj_tr8=new tr();
    $obj_tr8->addTd("&nbsp;");
    $temp_td1=$obj_tr8->addTd($this->obj_textarea_message->htmlValue());
    $temp_td1->setColspan(4);
    $temp_td1->setAlign("left");
    $obj_html_table->insertTr($obj_tr8);
    
    $o_hidden_id_save = new hidden("unique_id_save",$this->unique_id_save);
    
    //$o_hidden_id_save = new hidden("unique_id_save",$_SESSION['unique_id_save']);
    
    //- Style bouton
    $this->obj_bt_ok->setClass('buton');
    $this->obj_bt_cancel->setClass('buton');
    
    
    // la partie "Envoyer"
    $obj_tr9=new tr();
    $temp_td1=$obj_tr9->addTd($this->obj_bt_ok->htmlValue()." ".$this->obj_bt_cancel->htmlValue().$o_hidden_id_save->htmlValue().$this->obj_form_mail->getEndBalise());   
    $temp_td1->setAlign("center");
    $temp_td1->setColspan(4);
    
    $obj_html_table->insertTr($obj_tr9);
    
    $obj_html_table->setCellspacing(0);
    $obj_html_table->setCellpadding(2);
    $obj_html_table->setBorder(0);
    $obj_html_table->setWidth("100%");
      
    
    
    //$stri_serial_mail=serialize($this);                         //sérialise la classe mail
    //stocke la sérialisation dans une session
    //utilisation de urlencode pour permettre de déserialiser par la suite les chaines contenant des simples quotes ex: "titre d'une image"
        
    $stri_serial_mail_encoded=urlencode($stri_serial_mail);
    $taille_totale=strlen($stri_serial_mail_encoded);
    
   
    
    //$stri_serial_mail_encoded=substr($stri_serial_mail_encoded,0,rand(1000,3245));
    $_SESSION['MAIL_OBJ_MAIL']=$stri_serial_mail_encoded;
    //echo "<pre>";
    //var_dump($_SESSION['MAIL_OBJ_MAIL']);
    
    $this->save();
   
    //sleep(1);    
    
    $stri_res=$this->Javascripter();
    $stri_res.=$obj_html_table->htmlValue();
    return $stri_res.$stri_js_and_css;   
  }
   * 
   * 
   */
  
  public function htmlValue()
  {     


    //- Get JS and CSS
    $stri_js_and_css = (new js_loader())->htmlValue();
    
    
    //- Tooltip
    $this->obj_bt_ok->setTitle(__LIB_SEND_MAIL);
    $this->obj_bt_cancel->setTitle(__LIB_CANCEL_MAIL);
    $this->obj_a_to->setClass('infobulle');
    $this->obj_a_to->setTitle(__LIB_MAIL_TO);
    $this->obj_a_attachment->setClass('infobulle');
    $this->obj_a_attachment->setTitle(__LIB_MAIL_ATTACHEMENT);
    
 
    $this->unique_id_save = uniqid();
    //renvoie le formulaire de mail
    //foreach($_POST as $index => $valeur) echo "<br>\$_POST['".$index."']=\"".$valeur."\"<br>";
    
    //- Table général
    $obj_html_table=new table();
    
    
    //- Style bouton
    //$stri_style = 'width: 100%; padding-top: 1px; padding-bottom: 1px;';
    $stri_style = 'width: 105px;  
        padding: 5px 20px 5px 30px;
        margin: 0 15px;
        text-align: right; 
        background-size: 20px;
        background-position: left center;
        background-repeat: no-repeat;
        background-size: 13px cover;';
    $stri_style_ok = 'background-image: url(images/send.png);';
    $stri_style_cancel = 'background-image: url(images/cancel.png);';
    
    $this->obj_bt_ok->setClass('button infobulle');
    $this->obj_bt_ok->setStyle($stri_style.$stri_style_ok);
    $this->obj_bt_cancel->setClass('button infobulle');
    $this->obj_bt_cancel->setStyle($stri_style.$stri_style_cancel);
    
    //- Table button
    $obj_html_table_button=new table();
    $obj_html_table_button->setWidth('100%');
       $obj_tr = $obj_html_table_button->addTr();
            $obj_td = $obj_tr->addTd($this->obj_bt_ok);
            $obj_tr = $obj_html_table_button->addTr();
            $obj_td = $obj_tr->addTd($this->obj_bt_cancel);
            
    //- Table entete
    $obj_html_table_entete=new table();
    $obj_html_table_entete->setWidth('100%');
    //$obj_html_table_entete->setClass("titre1");
    $obj_html_table_entete->setClass("titre3-3");
    $obj_html_table_entete->setStyle("border-radius: 5px;");
    $obj_tr = $obj_html_table_entete->addTr();
        $obj_td = $obj_tr -> addTd();
        $obj_td->setWidth('15%');
        $obj_td = $obj_tr -> addTd((new font(__LIB_MAIL))->htmlValue());
        $obj_td->setStyle('font-size: 20px; text-align: center;');
        //$obj_td = $obj_tr -> addTd($obj_html_table_button);
        $obj_td = $obj_tr -> addTd();
        $obj_td->setWidth('15%');
    
    
    
    $obj_tr_manager=new tr();
    $temp_td_manager=$obj_tr_manager->addTd('<p class="message message_manger" style="margin-top: 0px;"> </p>');
    $temp_td_manager->setColspan(5);
    //$obj_html_table_entete->insertTr($obj_tr_manager);
    
    
    //- Table destinataire
    $obj_html_table_destinataire=new table();
    $obj_html_table_destinataire->setWidth('100%');
    $obj_html_table_destinataire->setClass('contenu');
        $obj_tr = $obj_html_table_destinataire->addTr();
            $obj_td = $obj_tr->addTd(__LIB_DESTINATIRE);
                $obj_td->setClass('titre2 entete');
                $obj_td->setStyle('font-size: 14px;');
                $obj_td->setColspan(4);
                
    

    //- Table message
    $obj_html_table_message=new table();
    $obj_html_table_message->setWidth('100%');
    $obj_html_table_message->setClass('contenu');
       $obj_tr = $obj_html_table_message->addTr();
            $obj_td = $obj_tr->addTd(__LIB_MESSAGE);
                $obj_td->setClass('titre2 entete');
                $obj_td->setStyle('font-size: 14px;');
                $obj_td->setColspan(4);
                
                
   
    
    
    $this->bool_mode = false;                                           //- Désactivation Romain 
    if($this->bool_mode)    //si la partie "Mode" est à true
    {
      //la partie "Mode" est affichée
      $obj_tr6=new tr();
      //$temp_td1=$obj_tr6->addTd($this->obj_lb_html->htmlValue());
      $temp_td1=$obj_tr6->addTd();
      $temp_td1->setAlign("right");
      //$temp_td2=$obj_tr6->addTd($this->obj_radio_html->htmlValue());
      $temp_td2=$obj_tr6->addTd($this->obj_radio_html->htmlValue().$this->obj_lb_html->htmlValue());
      $temp_td2->setAlign("center");
      //$temp_td2->setWidth("5%");
      //$temp_td3=$obj_tr6->addTd($this->obj_lb_text->htmlValue());
      $temp_td3=$obj_tr6->addTd($this->obj_radio_text->htmlValue().$this->obj_lb_text->htmlValue());
      $temp_td3->setAlign("center");
      //$temp_td2->setWidth("10%");
      //$temp_td4=$obj_tr6->addTd($this->obj_radio_text->htmlValue());
      $temp_td4=$obj_tr6->addTd();
      $temp_td4->setAlign("left");
      //$temp_td4->setWidth("60%");
      $obj_html_table_destinataire->insertTr($obj_tr6);
      
    }
    
    $obj_tr0=new tr();
    $temp_td1=$obj_tr0->addTd($this->obj_form_mail->getStartBalise());
    $temp_td1->setAlign("center");
    $obj_html_table->insertTr($obj_tr0);
    
    if($this->bool_sender) //si la partie "Expéditeur" est à true
    {
      //la partie "Expéditeur" est affichée
      $obj_tr1=new tr();
      $temp_td1=$obj_tr1->addTd($this->obj_lb_from->htmlValue());
      //$temp_td1->setAlign("center");
      //$temp_td1->setClass("contenu");
      $temp_td1->setStyle("width: 80px");
      $temp_td2=$obj_tr1->addTd($this->obj_lb_from_name->htmlValue());
      $temp_td2->setColspan(3);
      $obj_html_table_destinataire->insertTr($obj_tr1);
    }
    
    
    // la partie "Destinataires principaux"
    $this->obj_text_to->setPlaceholder($this->obj_lb_to->getValue());
    
    $obj_tr2=new tr();
    $temp_td1=$obj_tr2->addTd($this->obj_a_to->htmlValue());
    //$temp_td1->setAlign("center");
    //$temp_td1->setClass("contenu");
    $temp_td2=$obj_tr2->addTd($this->obj_text_to->htmlValue());
    $temp_td2->setColspan(3);
    $obj_html_table_destinataire->insertTr($obj_tr2);

    if($this->bool_cc) //si la partie "Copie carbone" est à true
    {
    
    $this->obj_text_cc->setPlaceholder($this->obj_lb_cc->getValue());
        
      // la partie "Copie carbone" est affichée
      $obj_tr3=new tr();
      $temp_td1=$obj_tr3->addTd($this->obj_lb_cc->htmlValue());
      //$temp_td1->setAlign("center");
      //$temp_td1->setClass("contenu");
      $temp_td2=$obj_tr3->addTd($this->obj_text_cc->htmlValue());
      $temp_td2->setColspan(3);
      $obj_html_table_destinataire->insertTr($obj_tr3);
    }
    
    if($this->bool_bcc) //si la partie "Copie cachée" est à true
    {
        
      $this->obj_text_bcc->setPlaceholder($this->obj_lb_bcc->getValue());
        
      // la partie "Copie cachée" est affichée
      $obj_tr4=new tr();
      $temp_td1=$obj_tr4->addTd($this->obj_lb_bcc->htmlValue());
      //$temp_td1->setAlign("center");
      //$temp_td1->setClass("contenu");
      
      $temp_td2=$obj_tr4->addTd($this->obj_text_bcc->htmlValue());
      $temp_td2->setColspan(3);
      $obj_html_table_destinataire->insertTr($obj_tr4);
    }

     
    $this->obj_text_subject->setPlaceholder($this->obj_lb_subject->getValue());
     $this->obj_text_subject->setStyle('width: 100%');
     
    //la partie "Objet"
    $obj_tr5=new tr();
    $obj_tr5->setHeight(30);
    $temp_td1=$obj_tr5->addTd($this->obj_lb_subject->htmlValue());
    $temp_td1->setStyle("width: 80px;");
    $temp_td2=$obj_tr5->addTd($this->obj_text_subject->htmlValue());
    $temp_td2->setColspan(3);
    $obj_html_table_message->insertTr($obj_tr5);

    
    if($this->bool_attachment) //si la partie "Fichier joint" est à true
    {
      $this->obj_a_attachment->setOnclick("window.open('modules.php?op=modload&name=Mail&file=form_attachment&id_slz=".$this->unique_id_save."','Pièces jointes','width=340,height=170,resizable=yes,scrollbars=yes');");
      //la partie "Fichier joint" est affichée
      $obj_tr7=new tr();
      //$temp_td1=$obj_tr7->addTd($this->obj_img_attachment->htmlValue()." ".$this->obj_a_attachment->htmlValue());
      //$temp_td1=$obj_tr7->addTd($this->obj_a_attachment->htmlValue().$this->obj_img_attachment->htmlValue());
      $temp_td1=$obj_tr7->addTd($this->obj_a_attachment->htmlValue());
      $temp_td1->setNowrap(true);  
      //$temp_td1->setAlign("center");  
      //$temp_td1->setClass("contenu");  
      $stri_file="";
      
      if(count($this->obj_attachment->getAttachment())>0)
      {
        foreach($this->obj_attachment->getAttachment() as $filename)
        {
            
          $stri_file.= $filename."<br />";
        }
      }
      //$temp_td2=$obj_tr7->addTd("&nbsp;<DIV id='pj'>$stri_file</DIV>");
      $this->obj_div->addContain($stri_file);
      $temp_td2=$obj_tr7->addTd('<span style="display: none;">'.$this->obj_img_attachment->htmlValue().'</span>'.$this->obj_div->htmlValue());
      
      $temp_td2->setColspan(3);
      $obj_html_table_message->insertTr($obj_tr7);
    }


    
    // la partie "Message"
    $obj_tr8=new tr();
    //$obj_tr8->addTd("&nbsp;");
    $temp_td1=$obj_tr8->addTd($this->obj_textarea_message->htmlValue());
    $temp_td1->setColspan(4);
    $temp_td1->setAlign("left");
    $obj_html_table_message->insertTr($obj_tr8);
    
    $o_hidden_id_save = new hidden("unique_id_save",$this->unique_id_save);
    
    //$o_hidden_id_save = new hidden("unique_id_save",$_SESSION['unique_id_save']);
    
    
    
    
    // la partie "Envoyer"
    $obj_tr9=new tr();
    $temp_td1=$obj_tr9->addTd($this->obj_bt_ok->htmlValue().$this->obj_bt_cancel->htmlValue().$o_hidden_id_save->htmlValue().$this->obj_form_mail->getEndBalise());   
    $temp_td1->setAlign("center");
    $temp_td1->setColspan(4);
    $obj_html_table_message->insertTr($obj_tr9);
    
    
    //- Pose dans table génrale
    $obj_tr = $obj_html_table->addTr();
        $obj_td = $obj_tr->addTd($obj_html_table_entete);
    $obj_tr = $obj_html_table->addTr();
        $obj_td = $obj_tr->addTd($obj_tr_manager);
    $obj_tr = $obj_html_table->addTr();
        $obj_td = $obj_tr->addTd($obj_html_table_destinataire);
    $obj_tr = $obj_html_table->addTr();
        $obj_tr->setHeight(5);
    $obj_tr = $obj_html_table->addTr();
        $obj_td = $obj_tr->addTd($obj_html_table_message);
            
    $obj_html_table->setStyle('padding-left: 15; padding-right: 15; margin-top : -15px;');
    $obj_html_table->setBorder(0);
    $obj_html_table->setWidth("100%");
      
    
    
    //$stri_serial_mail=serialize($this);                         //sérialise la classe mail
    //stocke la sérialisation dans une session
    //utilisation de urlencode pour permettre de déserialiser par la suite les chaines contenant des simples quotes ex: "titre d'une image"
        
    $stri_serial_mail_encoded=urlencode($stri_serial_mail);
    $taille_totale=strlen($stri_serial_mail_encoded);
    
   
    
    //$stri_serial_mail_encoded=substr($stri_serial_mail_encoded,0,rand(1000,3245));
    $_SESSION['MAIL_OBJ_MAIL']=$stri_serial_mail_encoded;
    //echo "<pre>";
    //var_dump($_SESSION['MAIL_OBJ_MAIL']);
    
    $this->save();
   
    //sleep(1);    
    
    $stri_res=$this->Javascripter();
    $stri_res.=$obj_html_table->htmlValue();
    return $stri_js_and_css. $stri_res;   
  }
  
  
  public function save()
  {
    /*-----------------------------------------------------------------------
     Cette fonction permet de sauvegarder dans un fichier l'object courrant 
     en tant qu'objet serialisé.
     Un fichier par utilisateur est créé. Le fichier est placé dans le répertoir
     module/Mail/serialized
     
     retourne true en cas de succès, false sinon
     ------------------------------------------------------------------------*/
       
    $stri_file_name=$this->unique_id_save."_mail.obj";
    $stri_path="modules/Mail/serialized";    //
    //création du fichier de sauvegarde
    $f=fopen("$stri_path/$stri_file_name","w");
    $string= urlencode(serialize($this));  
    $res=fwrite($f, $string);    
    $bool_res=($res>0)?true:false;
    
    
    $_SESSION['unique_id_save']=$this->unique_id_save;//RS 01/04/2011 correction pb de transmission de l'id du fichier
    return $bool_res;
  }
  
  static public function load()
  {
    /*-----------------------------------------------------------------------
     Cette fonction est la fonction dual de save. Elle permet de charger l'objet
     serialisé depuis répertoir module/Mail/serialized
     
     retourne un objet mail en cas de succès et false sinon
     ------------------------------------------------------------------------*/
    //print_r($_POST);
      
      
      
    $stri_unique_id=(isset($_POST["unique_id_save"]))?$_POST["unique_id_save"]:$_SESSION['unique_id_save']; //RS 01/04/2011 correction pb de transmission de l'id du fichier
    $stri_unique_id= (isset($_GET['id_slz']))?$_GET['id_slz']:$stri_unique_id;
    if (isset($_GET['id_slz']))
    {
       $_SESSION['unique_id_save']= $_GET['id_slz'];
    }
    $stri_file_name=$stri_unique_id."_mail.obj";
    $stri_path="modules/Mail/serialized";  
    $f=fopen("modules/Mail/serialized/$stri_file_name","r");
    $stri_serialized=fgets($f);
    $obj_mail=unserialize(urldecode($stri_serialized));


    //unlink ("$stri_path/$stri_file_name");
    return $obj_mail;
  }
  
  public function forceAttachment($fileEmplacement,$fileName,$fileNameViewed="")
  {/** ------------------------------------------------------
    Permet de joindre des fichiers par défaut. 
    $fileEmplacement : le chemin où trouver le fichier sur le serveur (ex: modules/Referent/test)
    $fileName : le nom du fichier se trouvant sur le serveur(ex: 0508_montexte.txt)
    $fileNameViewed : le nom du fichier qui apparait dans le mail (ex: montexte.txt) 
    
   retourne true en cas de succès d'attachement, false sinon
   -------------------------------------------------------- **/
   //si le fichier à attaché ne peut pas être trouvé
   $res=is_file("$fileEmplacement/$fileName");
   if(!$res)
   {return false;}
   $fileNameViewed=($fileNameViewed=="")?$fileName:$fileNameViewed;
   
   $res=PHPMailer::AddAttachment($fileEmplacement."/".$fileName, $fileNameViewed); 
   //$this->obj_div->addContain($fileNameViewed."<br />");
   $this->obj_div->addContain($this->obj_img_attachment->htmlValue().$fileNameViewed."");
   return $res;
  }
  
  //**** private method ********************************************************
  private function analyse_mail($arra_mail,$type)
  {           
    //analyse les tableaux des emails et ajoute les emails dans le phpmailer
    //@param : $arra_mail => tableau simple des adresses ou nom du groupe [ex : $arra_mail[0]=a@a.fr ; $arra_mail[1]=GROUPMAIL ; ...]
    //@param : $type => le type de destination : "to" => destinataires principaux (type of destination : to;cc;bcc)
    //                                           "cc" => copies carbone
    //                                           "bcc" => copies cachées
    //@return : void
  
    foreach ($arra_mail as $stri_mail)
    {
      //si un arobase existe, (if @ is found)
      if(strpos($stri_mail,"@"))  
  	  {
  	    //echo"<br />//c'est un email (it's email)";
          	   
  	    switch ($type)
  	    {
          case "to" :  PHPMailer::AddAddress($stri_mail, "");                    //ajoute les destinataires principaux (add deliver)
          break;
          case "cc" :  PHPMailer::AddCC($stri_mail, "");                         //ajoute les copies carbone (add cc)
          break;
          case "bcc" : PHPMailer::AddBCC($stri_mail, "");                       //ajoute les copies cachées (add bcc)
          break;
        }
      }
      else
      {
        //echo"<br />//c'est un groupe mail (it's groupmail)";
      
        //recherche tous les emails du groupe (search email of groupmail)
        $sql_email="SELECT a.num_user 
                    FROM mail_assignation a, mail_groupe g 
                    WHERE g.nom_group='$stri_mail' 
                    AND a.id_group=g.id_group";
        $obj_query=new querry_select($sql_email);
        $arra_result=$obj_query->execute();
        
        $arra_email=array();
        
        //pour chaque user (foreach user)
        foreach ($arra_result as $arra_row)
        {
          $id_user=$arra_row[0];
        
          //si le num_user contient un "G" (if num_user contents "G")
          if(strpos($id_user,"G")===false)
          {
            //echo"<br />//c'est un utilisateur (it's an user)";
            
            //recherche l'adresse mail de l'utilisateur (search email of user)
            $sql="SELECT email
                  FROM user_user
                  WHERE num_user=$id_user";
          }
          else
          {
            //echo"<br />//c'est un groupe mail (it's groupmail)";
            
            //recherche le nom du groupe (search groupname)
            $sql="SELECT nom_group
                  FROM mail_groupe
                  WHERE id_group='$id_user'";
          }
          $obj_query=new querry_select($sql);
          $arra_result=$obj_query->execute();
          
          //ajoute dans le tableau la valeur (put value in array)
          $arra_email[]=$arra_result[0][0];                           
        }
        
        //envoi à l'analyseur de mail (call function that analyse mail)
        $this->analyse_mail($arra_email,$type);   
      }
    }        
  }
  
  
 /* private function get_email($stri_group)
  {
    //récupère tous les emails du groupe
    //@param : stri_group => nom du groupe
    //@return : tableau des emails
        
    $sql_email="SELECT email 
                FROM user_user u, mail_assignation a, mail_groupe g 
                WHERE nom_group='$stri_group'
                AND a.id_group=g.id_group
                AND u.num_user=a.num_user
                ORDER BY email";
    $obj_query_email=new querry_select($sql_email);
    $arra_result_email=$obj_query_email->execute();
    if(count($arra_result_email)>0)
    {
      return $arra_result_email;
    }
    else
    {
      return false;
    }
  }*/
  
  private function Javascripter()
  {
    //les actions javascript du formulaire des pièces jointes
    $stri_js="
    <script>
    
    function verify_form()
    { 
      //vérification générale du formulaire de mail
 
      var stri_subject=document.form_mail.text_subject.value;         
      var stri_message=document.form_mail.texta_message.value;
 
      if(stri_subject==''){alert('L\'objet du mail est vide !');return false;}
      if(stri_message==''){alert('Le message est vide');return false;}     
    }
  
    </script>";
    
    return $stri_js;
  
  }
  
  private function safeHTML($html)
  {
    /*-----------------------------------------------------------------------
     Créé par Yann Krupa
     Cette fonction permet d'échapper les balises entre <> tout en gardant les balises
     html spécifié dans $allowed. Les balises html se trouvant dans $forbidden seront 
     supprimé du texte.
     
     exemple d'appel: $txt=safeHTML($txt,"font,a,b,u,i,br","form")
     ------------------------------------------------------------------------*/
    //allowed will not be translated
    //forbidden will be striped
    //others will be translated from <> to &lt; &gt;
  	$html=strtr($html,array('#<<<#' => '# <<<#','#>>>#'=>'# >>>#'));
  	$html=strtr($html,array('<' => '#<<<#','>'=>'#>>>#'));
 
  	foreach(explode(',',$this->stri_allowed) as $tag){
  		$html=preg_replace('(#<<<#'.$tag.'(( [^>]*)|()|(/))#>>>#)',"<$tag\$1>",$html);
  		$html=preg_replace('(#<<<#/'.$tag.'#>>>#)',"</$tag>",$html);
  	}
  	
  	if($this->forbidden != ''){
  		foreach(explode(',',$this->forbidden) as $tag){
  			$html=preg_replace('(#<<<#'.$tag.'([^>]*)#>>>#)',"",$html);
  			$html=preg_replace('(#<<<#/'.$tag.'#>>>#)',"",$html);
  		}
  	}
  	$html=strtr($html,array( '#<<<#' => '&lt;','#>>>#' => '&gt;'));
  	  	
  	return $html;	
  }
  
    /** ajout gr : 08/01/2009
     * Permet de tracer l'envoie du mail 
     * @return void
     */
    function getTrace($result) {
      if ($result === true)
      {
        $result=1;
        $error='Aucune erreur';
      }
      else
      {
        $result=0;
        $error=$this->ErrorInfo;
      }
      list($dbconn)=pnDBGetConn();
      $req="select max(NUM_MAIL_TRACE) from MAIL_TRACE";
      if(!$res=$dbconn->Execute($req))
        exit("<font size=2 color=red>SQL ERROR : ".$dbconn->ErrorMsg()."<br />SQL : ".$req."</font>");
      $num=$res->fields[0]+1;
      $req="INSERT INTO mail_trace VALUES (".$num.", ".$result.", '".$error."', '".str_replace("'", "''", $this->Subject)."', '".$this->From."', '".$this->arrayToString($this->to)."', '".$this->arrayToString($this->cc)."', '".$this->arrayToString($this->bcc)."', sysdate, ".pnUserGetVar('uid').")";
      if(!$dbconn->Execute($req))
        exit("<font size=2 color=red>SQL ERROR : ".$dbconn->ErrorMsg()."<br />SQL : ".$req."</font>");
    }

    /** ajout gr : 08/01/2009
     * Permet de transformer tableaux multidimensionnels TO, BCC, CC en chaîne et ne conserve
     * que l'adresse mail      
     * @return chaîne
     */
    function arrayToString($tab) {
    
      $str='';
      for ($i=0, $nb=count($tab); $i<$nb; $i++)
        $str.=$tab[$i][0].', ';
      return substr($str, 0, -2).' '; // supp de la dernière virgule*/
    }
  
  //**** method of serialization ***********************************************
  public function __sleep() 
  {
    //sérialisation de la classe PHPMailer    
    $this->arra_sauv['From']= $this->From;        
    $this->arra_sauv['FromName']= $this->FromName;

    foreach($this->attachment as $key=>$arra_attach)
    {
      $arra_temp[$key]=serialize($arra_attach);
    }
    $this->arra_sauv['attachment']= $arra_temp;
    
    //sérialisation de la classe mail
    $this->arra_sauv['sender']= $this->bool_sender;
    $this->arra_sauv['bool_cc']= $this->bool_cc;
    $this->arra_sauv['bool_bcc']= $this->bool_bcc;   
    $this->arra_sauv['bool_attachment']= $this->bool_attachment;
    $this->arra_sauv['allowed']= $this->stri_allowed;
    $this->arra_sauv['forbidden']= $this->stri_forbidden;
    $this->arra_sauv['text_to']= $this->obj_text_to->getValue();
    $this->arra_sauv['text_cc']= $this->obj_text_cc->getValue();
    $this->arra_sauv['text_bcc']= $this->obj_text_bcc->getValue();
    $this->arra_sauv['text_subject']= $this->obj_text_subject->getValue();
    $this->arra_sauv['textarea_message']= $this->obj_textarea_message->getValue();
    $this->arra_sauv['radio_html']= $this->obj_radio_html->getChecked();
    $this->arra_sauv['radio_text']= $this->obj_radio_text->getChecked();
    $this->arra_sauv['obj_attachment']= serialize($this->obj_attachment);
    $this->arra_sauv['obj_address']= serialize($this->obj_address);
    $this->arra_sauv['stri_script']= $this->stri_script;
    $this->arra_sauv['arra_script_param']= serialize($this->arra_script_param);
    $this->arra_sauv['unique_id_save']= $this->unique_id_save;
    
    
    return array('arra_sauv');
  }
  
  public function __wakeup() 
  {  
    //désérialisation de la classe PHPMailer
    $this->From= $this->arra_sauv['From'];        
    $this->FromName= $this->arra_sauv['FromName'];
  
    foreach($this->arra_sauv['attachment'] as $key=>$arra_attach)
    {
      $arra_temp[$key]=unserialize($arra_attach);
    }
    $this->attachment= $arra_temp;

    //désérialisation de la classe mail 
    $this->bool_sender= $this->arra_sauv['sender'];
    $this->bool_cc= $this->arra_sauv['bool_cc'];
    $this->bool_bcc= $this->arra_sauv['bool_bcc']; 
    $this->stri_allowed= $this->arra_sauv['allowed'];
    $this->stri_forbidden= $this->arra_sauv['forbidden'];  
    $this->bool_attachment= $this->arra_sauv['bool_attachment'];
    $this->obj_text_to=new text("text_to");
    $this->obj_text_to->setSize(80);
    $this->obj_text_to->setId("id_to");
    $this->obj_text_to->setValue($this->arra_sauv['text_to']);
    $this->obj_text_cc=new text("text_cc");
    $this->obj_text_cc->setSize(80);
    $this->obj_text_cc->setId("id_cc");
    $this->obj_text_cc->setValue($this->arra_sauv['text_cc']);
    $this->obj_text_bcc=new text("text_bcc");
    $this->obj_text_bcc->setSize(80);
    $this->obj_text_bcc->setId("id_bcc");
    $this->obj_text_bcc->setValue($this->arra_sauv['text_bcc']);
    $this->obj_text_subject=new text("text_subject");
    $this->obj_text_subject->setSize(80);
    $this->obj_text_subject->setValue($this->arra_sauv['text_subject']);
    $this->obj_textarea_message=new text_arrea("texta_message");
    $this->obj_textarea_message->setRows(15);
    $this->obj_textarea_message->setCols(75);
    $this->obj_textarea_message->setValue($this->arra_sauv['textarea_message']);
    $this->obj_radio_html=new radio("radio_mode","html");
    $this->obj_radio_html->setChecked($this->arra_sauv['radio_html']);    
    $this->obj_radio_text=new radio("radio_mode","text");
    $this->obj_radio_text->setChecked($this->arra_sauv['radio_text']);    
    $this->obj_attachment= unserialize($this->arra_sauv['obj_attachment']);
    $this->obj_address= unserialize($this->arra_sauv['obj_address']);
    $this->stri_script= $this->arra_sauv['stri_script'];
    $this->arra_script_param= unserialize($this->arra_sauv['arra_script_param']);
    $this->unique_id_save= $this->arra_sauv['unique_id_save'];
    $this->arra_sauv = array();    
  }  
}
?>
