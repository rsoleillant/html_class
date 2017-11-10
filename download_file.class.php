<?php

/*******************************************************************************
Create Date : 18/08/2009
 ----------------------------------------------------------------------
 Class name : telechargement
 Version : 1.0
 Author : ALEX ESCOT
 Description : class qui permet de télécharger un fichier
********************************************************************************/

class download_file extends serialisable
{
  private $file_path;
  private $stri_file_name;
  private $arra_header;  
  
  
  function __construct($file_path,$stri_file_name)
  {
    $this->file_path = $file_path;
    $this->stri_file_name = $stri_file_name;
    $this->arra_header[] = "header(Content-Disposition: attachment; filename=$stri_file_name);";  
  }
  
  /* méthode qui permet d'ajouter un header */
  public function addHeader($header)
  {
    $this->arra_header[] = "header($header);";  
  }
  
  /* méthode appelé par la pop-up */
  public function loadDownload()
  {  
    for($i=0;$i<count($this->arra_header);$i++)
    {
      $strheader .= $this->arra_header[$i];   
    }
    $strheader .= "readfile(".$this->file_path."/".$this->stri_file_name.");";
    eval($strheader);  
  }
  
  /* méthode qui ouvre la pop-up avec les headers */
  public function loadPopUp()
  {  
    $obj_form=new form("modules.php?op=modload&name=Outil&file=download.tintf","post");
    $obj_form->setName("form_download");
    
    $obj_javscripter=new javascripter();
    $obj_javscripter->addFunction("
      function launchDownload()
      {
        var body= document.getElementsByTagName('body')[0];
        
        //cloture automatique de la fenetre version IE
        body.onload= function (){window.close()};
                    
        //cloture automatique de la fenetre version firefox
        body.setAttribute('onBlur','window.close()');
        
        //envoi d'un formulaire pour redirection automatique
        document.getElementsByName('form_download')[0].submit();
        
      }");
    
    //redirection pour le téléchargement
    $obj_javscripter->addFunction("launchDownload();");
    
    $this->saveInTemp("download_file");
    echo $obj_form->htmlValue();
    echo $obj_javscripter->javascriptValue();
  }  
}

?>
