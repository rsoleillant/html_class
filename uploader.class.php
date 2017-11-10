<?php
/*******************************************************************************
Create Date : 18/08/2009
 ----------------------------------------------------------------------
 Class name : uploader
 Version : 1.0
 Author : Yannick MARION
 Description : module préconsut pour uploader un fichier sur le serveur (à pofiner)!!
 param:

********************************************************************************/



class uploader{

      protected $stri_path_upload="";                    //Définit le répertoire dans lequel les fichiers seront uploadés
      protected $array_extention_disable= array();       //Définit les extensions interdites.
      protected $int_max_size = 1048576;                     //Taille maximal du fichier (defaut = 1Mo)
      protected $url_script="modules.php?op=modload&name=Connection_v3&file=upload_for_uploader";//Définit l'url du script à lancer dans l'iframe
      protected $obj_js;                                     //$object javascript modifiable                                              
      protected $stri_path_js="";                            //définit le chemin d'un fichier javascript apporté
      protected $obj_css;                                    //object css modifiable
      protected $stri_path_css="modules/OutilsAdmin/entrainement/Yannick/UPLOADER/style/style.css";//Chemin du css originel
      protected $obj_container;                               //object contenant les div                                     
      protected $obj_header=true;                             //définit l'affichage de l'header
      protected $obj_footer=true;                             //définit l'affichage du footer
      protected $obj_loader=true;                             //définit l'affichage du loader

      function __construct($path,$ext,$size){
      
      $this->stri_path_upload=$path;
      $this->array_extention_disable=$ext;
      $this->int_size=$size;
      $this->default_uploader();
      
      }
      
      private function default_uploader(){

      $obj_css = new css();
      $obj_css->addFile($this->stri_path_css);
      $this->css=$obj_css->cssValue();
      
      $obj_js=new javascripter();
      $obj_js->addFunction('function startUpload(){
       
      document.getElementById(\'f1_upload_process\').style.visibility = \'visible\';
      document.getElementById(\'f1_upload_form\').style.visibility = \'hidden\';
      return true;
      }

      function stopUpload(success,Msg){
      var result = "";
      if (success == 1){
         result = \'<span class="msg"><img height="20" src="modules/OutilsAdmin/entrainement/Yannick/style/images/ok.gif">The file was uploaded successfully!<br/>\'+Msg+\'<\/span><br/><br/>\';
      }
      else {
         result = \'<span class="emsg"><img height="20" src="modules/OutilsAdmin/entrainement/Yannick/style/images/nok.gif">There was an error during file upload!<br/>\'+Msg+\'<\/span><br/><br/>\';
      }
      document.getElementById(\'f1_upload_process\').style.visibility = \'hidden\';
      document.getElementById(\'f1_upload_form\').innerHTML = result + \'<label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>\';
      document.getElementById(\'f1_upload_form\').style.visibility = \'visible\';      
      return true;   
      }');
      $this->js=$obj_js->javascriptValue();         
      }
      
      //Permet de définir si on affiche le header ou pas:(true / false)
      public function header($bool){
             if(is_bool($bool)){
               $this->obj_header=$bool;
             }
      }
      //Permet de définir si on affiche le footer ou pas:(true / false)
      public function footer($bool){
             if(is_bool($bool)){
               $this->obj_footer=$bool;
             }
      }
      public function loader($bool){
             if(is_bool($bool)){
               $this->obj_loader=$bool;
             }
      }
      
      public function setUrlScript($url){
      $this->url_script=$url;
      }
      
      public function changeJavascript($stri_js){
        $obj_js=new javascripter();
        $obj_js->addFunction($stri_js);
        $this->js=$obj_js->javascriptValue();
      }
      public function changeJavascriptFile($stri_js){
      $this->stri_path_js=$stri_js;
        $obj_js=new javascripter();
        $obj_js->addFile($this->stri_path_js);
        $this->js=$obj_js->javascriptValue();
      }
      
      public function configCss($stri_css){
        $obj_css=new css();
        $obj_css->addClass($stri_js);
        $this->css=$obj_css->cssValue();
      }
      public function configCssFile($stri_css){
        $obj_css=new css();
        $obj_css->addFile($stri_css);
        $this->css=$obj_css->cssValue();
      }
    
      public function doAsislineColor(){
             $obj_css=new css();
             $obj_css->addFile("themes/".pnUserGetTheme()."/style/style_uploader.css");
             $this->css=$obj_css->cssValue();
      }
      
      public function htmlValue(){
      
      $obj_div_container=new div("container","");
         
         if($this->obj_header){
         //  HEADER
         $obj_div_header=new div("header","");
         $obj_div_header_left=new div("header_left","");
         $obj_div_header_main=new div("header_main","AJAX File Uploader");
         $obj_div_header_right=new div("header_right","");
         $obj_div_header->addContain($obj_div_header_left->htmlValue().
                                     $obj_div_header_main->htmlValue().
                                     $obj_div_header_right->htmlValue());
         $obj_div_container->addContain($obj_div_header->htmlValue());//Header
         }
              /*echo"<pre>";
              var_dump($this->array_extention_disable);
              echo"</pre>";*/
              
              
         //  CONTENT                       
         $obj_div_content=new div("content","");
         
         if($this->obj_loader)
         $p_f1_upload_process="<p id=\"f1_upload_process\" align=\"center\" style=\"visibility:hidden;\">Loading...<br/><img src=\"modules/OutilsAdmin/entrainement/Yannick/style/loader.gif\" /><br/></p>";
         else
         $p_f1_upload_process="<p id=\"f1_upload_process\"></p>";//Pour pas faire une erreur JS
         
         
         $obj_hidden_path=new hidden("uploader_stri_path_upload",$this->stri_path_upload);
         $obj_hidden_size=new hidden("max_size_of_file",$this->int_max_size);
         $stri_ext=implode("_|_",$this->array_extention_disable);
         $obj_hidden_ext=new hidden("ext",$stri_ext);        
         
         $obj_form=new form($this->url_script,"post");
         $obj_form->setEnctype("multipart/form-data");
         $obj_form->setTarget("upload_target");
         $obj_form->setOnSubmit("startUpload();");
         
         $obj_file=new file("myfile",$this->int_max_size);
         
         $obj_submit=new submit("submitBtn","Upload");
         $obj_submit->setClass("sbtn");
         
         $p_f1_upload_form="<p id=\"f1_upload_form\" align=\"center\"><br/>File :".$obj_file->htmlValue().$obj_submit->htmlValue()."</p>";
      
         $obj_iframe=new iframe("upload_target","#");//Permet de lancer l'upload de manière transparente
         $obj_iframe->setId("upload_target");
         $obj_iframe->setStyle("width:0;height:0;border:0px solid #fff;");
         
         $obj_div_content->addContain($obj_form->getStartBalise()
                                      .$p_f1_upload_process
                                      .$p_f1_upload_form
                                      .$obj_iframe->htmlValue()
                                      .$obj_hidden_path->htmlValue()
                                      .$obj_hidden_size->htmlValue()
                                      .$obj_hidden_ext->htmlValue()
                                      .$obj_form->getEndBalise());
         $obj_div_container->addContain("<body>".$obj_div_content->htmlValue()."</body>");//Content
         
         //  FOOTER
         if($this->obj_footer){
         $obj_div_footer=new div("footer","a-SISline");
         
         $obj_div_container->addContain($obj_div_footer->htmlValue());
         }
         $this->container=$obj_div_container->htmlValue();
         /*============== TRADUCTION EN HTML ==============================*/
         /*$this->container='<div id="container">
                     <div id="header">
                        <div id="header_left"></div>
                        <div id="header_main"> AJAX File Uploader</div>
                        <div id="header_right"></div>
                     </div>
                     <div id="content">
                        <form id="upload_form" action="modules.php?op=modload&name=OutilsAdmin&file=upload&action=entrainement" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" >
                             <p id="f1_upload_process">Loading...<br/><img src="modules/OutilsAdmin/entrainement/Yannick/style/loader.gif" /><br/></p>
                             <p id="f1_upload_form" align="center"><br/>
                                File:  
                                       <input name="myfile" type="file" size="30" />
                                
                                
                                       <input type="submit" name="submitBtn" class="sbtn" value="Upload" />
                                
                             </p>
                          <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
                        </form>
                      </div>
             <div id="footer">a-SISline</div>
         </div>
         ';*/
      
      $htmlValue=$this->css
                 .$this->js
                 .$this->container;
      return $htmlValue;      
      }

}

?>
