<?php
/*******************************************************************************
Create Date : 17/10/2007
 ------------------------------------------------------------------------------
 Class name : Attachment
 Version : 1.0
 Author : Emilie Merlat
 Description : permet de g�rer les fichiers joints
 Doc : \\stpr0341\Partage_hotline\Analyse_fonctionnel_asisline\mod_mail\man_techn_mail.txt
*******************************************************************************/

class Attachment 
{
  //**** attribute *************************************************************
  protected $obj_file_attachment;                 // => le fichier joint
  protected $obj_upload_file;                     // => objet pour le t�l�chargement de fichier
  protected $obj_bt_ok;                           // => le bouton t�l�charger
  protected $obj_bt_cancel;                       // => le bouton annuler
  protected $obj_form_attachment;                 // => le formulaire des fichiers joints
  protected $arra_attachment;                     // => tableau des pi�ces jointes t�l�charg�es (elles se trouvent dans le dossier tmp)
  protected $stri_url_attachment="modules/Mail/form_joined";  // => la racine sur lequel les fichiers joints seront sauvegard�s
  public $arra_sauv;                              // => tableau pour la s�rialisation / d�s�rialisation
  
  
  //**** constructor ***********************************************************
  public function __construct()
  {  
    $this->constructor();
  }
  
  //**** setter ****************************************************************
  public function setFile($stri_filename)
  {
    $this->arra_attachment[]=$stri_filename;
  }
  
  public function setParameter($stri_server_directory,$stri_file_extension,$int_file_max_size)
  {
    //envoi les param�tres pour uploader un fichier
    //@param $stri_server_directory => le repertoire de destination des fichiers joints sur le serveur
    //@param $stri_file_extension => les extensions possibles pour les fichiers joints
    //@param $int_file_max_size => la taille maximale du fichier joint en octet
      
    $int_len=strlen($stri_server_directory);                       //longueur de la chaine
    $stri_last_char=substr($stri_server_directory,$int_len-1,1);   //extrait le caract�re de fin
    if($stri_last_char=="/"){$stri_server_directory=substr_replace($stri_server_directory,"",$int_len-1,1);}  //enl�ve ce caract�re s'il correspond � un slashe
    $this->stri_url_attachment=$stri_server_directory;
    
    $this->obj_upload_file->setServerDirectory($stri_server_directory);
    $this->obj_upload_file->setExtension($stri_file_extension);
    $this->obj_upload_file->setMaxSize($int_file_max_size);
    $this->obj_file_attachment->setMaxSize($int_file_max_size);
  }
  
  public function setSizeMaxFile($int_file_max_size)
  {
    $this->obj_upload_file->setMaxSize($int_file_max_size);
    $this->obj_file_attachment->setMaxSize($int_file_max_size);
  }
  
  //**** getter ****************************************************************
  public function getFile()
  {
    return $this->obj_file_attachment;
  }
  
  public function getAttachment()
  {
    return $this->arra_attachment;
  }
  
  public function getServerDirectory()
  {
    return $this->obj_upload_file->getServerDirectory();
  }
  
  public function getSizeMaxFile()
  {
    return $this->obj_upload_file->getMaxSize();
  }
  //**** public method *********************************************************
  
  public function htmlValue()
  {
    
    //- Get JS and CSS
    $stri_js_and_css = (new js_loader())->htmlValue();
    
    //renvoie le formulaire de pi�ces jointes
    
    //foreach($_POST as $index => $valeur) echo "<br>\$_POST['".$index."']=\"".$valeur."\"<br>";
    $obj_html_table=new table();
    
    
     //- Entete 
    $obj_tr_entete=new tr();
    $temp_td_entete=$obj_tr_entete->addTd((new font(__LIB_FOIN_FILE))->htmlValue());
    $temp_td_entete->setAlign("center");
    $temp_td_entete->setClass("titre2 entete");
    $temp_td_entete->setColspan(5);
    $obj_html_table->insertTr($obj_tr_entete);
    
    
    $obj_tr0=new tr();
    $temp_td1=$obj_tr0->addTd($this->obj_form_attachment->getStartBalise());
    $temp_td1->setAlign("center");
    $obj_html_table->insertTr($obj_tr0);
    
    $obj_tr1=new tr();
    $obj_tr1->setHeight(75);
    $temp_td1=$obj_tr1->addTd($this->obj_file_attachment->htmlValue());
    $temp_td1->setAlign("center");
    $temp_td1->setClass("contenu");
    $temp_td1->setColspan(2);
    $obj_html_table->insertTr($obj_tr1);
    
    //- Style bouton
    $this->obj_bt_ok->setClass('button');
    $this->obj_bt_cancel->setClass('button');
    
    $obj_tr2=new tr();
    $temp_td1=$obj_tr2->addTd($this->obj_bt_ok->htmlValue());
    $temp_td2=$obj_tr2->addTd($this->obj_bt_cancel->htmlValue().$this->obj_form_attachment->getEndBalise());
    $temp_td1->setAlign("right");
    $temp_td2->setAlign("left");
    $obj_html_table->insertTr($obj_tr2);
    
    $obj_html_table->setCellspacing(0);
    $obj_html_table->setCellpadding(0);
    $obj_html_table->setBorder(0);
    $obj_html_table->setStyle('width: 100%');
    
    $stri_res=$this->Javascripter();
    $stri_res.=$obj_html_table->htmlValue();
   
    return $stri_res.$stri_js_and_css;    
  }
  
  public function copy_attachment()
  {
    //copie les fichiers contenus du dossier temporaire dans le r�pertoire final
    $stri_dir_name=date("Y_m");
    $stri_dir_name=$this->stri_url_attachment."/".$stri_dir_name;
    //echo"<br />Le r�pertoire de stockage :".$stri_dir_name;
    
    //v�rification que le r�pertoire existe
    if(!is_dir($stri_dir_name))
    {
      //sinon le r�pertoire est cr��
      $a=mkdir($stri_dir_name,0777);
      //echo"<br />Le dossier est :";var_dump($a);
    }
    //pour chaque fichier joint, il est copi� dans le r�pertoire final
    foreach($this->arra_attachment as $stri_file_name)
    {
      //echo"<br />le fichier source :".$this->obj_upload_file->getServerDirectory().$stri_file_name;
      //echo"<br />le fichier copi�".$stri_dir_name."/".$stri_file_name;
      $c=copy($this->obj_upload_file->getServerDirectory().$stri_file_name, $stri_dir_name."/".$stri_file_name);
      //echo"<br />la copie est :";var_dump($c);      
    } 
  }
  
  public function remove_dir()
  {
    //cette fonction permet de supprimer le r�pertoire perso temporaire
    $stri_path=$this->obj_upload_file->getServerDirectory();
    //v�rification que le nom du repertoire contient "/" � la fin, sinon on le lui rajoute
    if (substr($stri_path,strlen($stri_path)-1,1) != "/")
    {
      $stri_path .= "/";
    }
    
    //v�rification que le path soit bien un r�pertoire
    if (is_dir($stri_path)) 
    {
      //ouvre le r�pertoire
      $dir = opendir($stri_path);
      
      //tant qu'un fichier existe dans le r�pertoire
      while ($file = readdir($dir)) 
      {
        //supprime le fichier except� les r�pertoires racines
        if ($file != "." && $file != "..") 
        {
          //chemin du fichier
          $stri_file = $stri_path.$file; 
          //efface le fichier
          unlink($stri_file);
        }
      }
      //ferme le r�pertoire
      closedir($dir);
      
      //efface le r�pertoire
      $bool_del=rmdir($stri_path);
    }
    return $bool_del;
  }
  
  public function upload_file($arra_file,$int_user)
  {
    //t�l�charge le fichier joint sur le serveur dans un r�pertoire temporaire
    $stri_file_server_name=$int_user."@".$arra_file["name"];    //cr�e le nom du fichier sur le serveur
    $stri_dir_name=$this->stri_url_attachment."/tmp/";          //cr�e le r�pertoire tmp sur le r�pertoire serveur
    if(!is_dir($stri_dir_name))
    {
      $bool_create_dir=mkdir($stri_dir_name,0777);
    }
    
    $stri_dir_name=$stri_dir_name.$int_user."/";                //cr�e le r�pertoire perso dans le r�pertoire tmp sur le serveur
    if(!is_dir($stri_dir_name))
    {
      $bool_create_dir=mkdir($stri_dir_name,0777);
    }    
    
    //echo"<br />Nom du fichier sur le serveur : ".$stri_file_server_name;
    //echo"<br />Nom du dossier sur le serveur : ".$stri_dir_name;  
    //upload file
    $this->obj_upload_file->setFileServerName($stri_file_server_name);
    $this->obj_upload_file->setServerDirectory($stri_dir_name);
    $bool_upload=$this->obj_upload_file->upload();
    //echo"<br />Fichier upload� : "; var_dump($bool_upload);
    if($bool_upload)
    {
      //ajoute le fichier joint dans un tableau de stockage
      $this->arra_attachment[]=$stri_file_server_name;
    }
    return $bool_upload;
  }
  
  
  //**** private method ********************************************************
  private function constructor()
  {
    //echo"<br />Au constructeur de fichier attach�. Ma session est :".$_SESSION['MAIL_OBJ_MAIL'];
    //permet de cr�er l'interface lors de la cr�ation de l'objet mais aussi lors de la d�s�rialisation
    $this->obj_file_attachment=new file("file_attachment");
    $this->obj_bt_ok=new submit("bt_ok", _BT_OK);
    $this->obj_bt_cancel=new button("bt_cancel", _BT_CANCEL);
    $this->obj_bt_cancel->setOnClick("window.close();");
    $this->obj_form_attachment=new form("modules.php?op=modload&name=Mail&file=add_attachment","POST");
    $this->obj_form_attachment->setName("form_attachment");
    $this->obj_form_attachment->setEnctype("multipart/form-data");      //permet d'upload les fichiers joints
    $this->obj_form_attachment->setOnSubmit("return verify_form()");
    
    $dir_name="modules/Mail/form_joined";  //chemin par d�faut des fichiers joints
    $this->obj_upload_file=new file_uploader("file_attachment","",$dir_name,"",5000);
  
  }
  
  private function Javascripter()
  {
    //les actions javascript du formulaire des pi�ces jointes
    $stri_res="
    <script>
      function verify_form()
      {
        //V�rification g�n�rale du formulaire
        var stri_attachment=document.form_attachment.file_attachment.value;
        if(stri_attachment=='')
        {
          alert('Aucun fichier joint n\'a �t� choisi.');
          return false; 
        }
        //add_attachment();
        return true;     
      }
    </script>
    ";
    return $stri_res;
  }

  
  //**** method of serialization ***********************************************
  public function __sleep() 
  {
    //s�rialisation de la classe attachment
     $this->arra_sauv['file_attachment']= $this->obj_file_attachment->getValue();
      
    $this->arra_sauv['upload_file_srv_dir']= $this->obj_upload_file->getServerDirectory();
    $this->arra_sauv['upload_file_size']= $this->obj_upload_file->getMaxSize();
    
    foreach($this->arra_attachment as $key=>$int_size)
    {
      $arra_temp[$key]= serialize($int_size);
    }
    $this->arra_sauv['arra_attachment']= $arra_temp;

    return array('arra_sauv');     
  }
  
  public function __wakeup() 
  {
    //d�s�rialisation de la classe attachment
    $this->constructor();
    $this->obj_file_attachment->setValue($this->arra_sauv['file_attachment']);
    $this->obj_upload_file->setServerDirectory($this->arra_sauv['upload_file_srv_dir']);
    $this->obj_upload_file->setMaxSize($this->arra_sauv['upload_file_size']);
    
    foreach($this->arra_sauv['arra_attachment'] as $key=>$int_size)
    {
      $arra_temp[$key]= unserialize($int_size);
    }
    $this->arra_attachment= $arra_temp;
    
    $this->arra_sauv = array();       
  }   
}
?>
