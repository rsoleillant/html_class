<?php
/*******************************************************************************
  Create Date : 12/07/2010
  ----------------------------------------------------------------------
  Class name : button_custom
  Version : 1.0
  Author : Yoann Frommelt
  Description : élément html <button></button>
  Source : http://www.w3schools.com/tags/tag_button.asp
  
  modification button_custom
********************************************************************************/

  
class button_custom {
   
  /*attribute***********************************************/
  protected $bool_disabled=false;   // Specifies that a button should be disabled
  protected $stri_name="";          // Specifies the name for a button
  protected $stri_type="submit";          // Specifies the type of a button button/reset/submit 
  protected $stri_value="";         // Specifies the underlying value of a button
  protected $stri_accesskey="";     // Specifies a keyboard shortcut to access an element
  //protected $stri_dir="ltr";        // Specifies the text direction for the content in an element
  protected $stri_id="";            // Specifies a unique id for an element
  protected $stri_class="";         // Specifies a unique id for an element
  //protected $stri_lang="";          // Specifies a language code for the content in an element
  protected $stri_style="";         // Specifies an inline style for an element
  protected $int_tabindex="";       // Specifies the tab order of an element
  protected $stri_title="";         // Specifies extra information about an element
  protected $stri_onblur="";        // Script to be run when an element loses focus
  protected $stri_onclick="";       // Script to be run on a mouse click
  protected $stri_ondblclick="";    // Script to be run on a mouse double-click
  protected $stri_onfocus="";       // Script to be run when an element gets focus
  protected $stri_onmousedown="";   // Script to be run when mouse button is pressed
  protected $stri_onmousemove="";   // Script to be run when mouse pointer moves
  protected $stri_onmouseout="";    // Script to be run when mouse pointer moves out of an element
  protected $stri_onmouseover="";   // Script to be run when mouse pointer moves over an element
  protected $stri_onmouseup="";     // Script to be run when mouse button is released
  protected $stri_onkeydown="";     // Script to be run when a key is pressed
  protected $stri_onkeypress="";    // Script to be run when a key is pressed and released
  protected $stri_onkeyup="";       // Script to be run when a key is released
  protected $obj_style_bouton;      

  
  /* constructor***************************************************************/
  function __construct($name,$type="defaut",$label="1@b31_1pr0b4b13") {
    $this->stri_name=$name;
    $bool_label_vide = ($label == "") ? true : false;
    $this->loadCSS($bool_label_vide);
    
    $type = ($type == "") ? "defaut" : $type;
    
    $this->stri_class = "button_custom ";
    
    $a_button_definition = array();
    
    // Rechercher
    $a_button_definition["search"]["class"]="positive";
    $a_button_definition["search"]["label"]=_BUTTON_CUSTOM_LABEL_SEARCH;
    $a_button_definition["search"]["image"]="images/kit-fugue/magnifier-left.png";
    $a_button_definition["search"]["type"]="submit";
    
    // Réinitialiser
    $a_button_definition["raz"]["class"]="negative";
    $a_button_definition["raz"]["label"]=_BUTTON_CUSTOM_LABEL_RAZ;
    $a_button_definition["raz"]["image"]="images/kit-fugue/switch.png";
    $a_button_definition["raz"]["type"]="reset";
    
    // Ajouter
    $a_button_definition["add"]["class"]="positive";
    $a_button_definition["add"]["label"]=_BUTTON_CUSTOM_LABEL_ADD;
    $a_button_definition["add"]["image"]="images/kit-fugue/tick.png";
    $a_button_definition["add"]["type"]="submit";
    
    // Supprimer
    $a_button_definition["delete"]["class"]="negative";
    $a_button_definition["delete"]["label"]=_BUTTON_CUSTOM_LABEL_DELETE;
    $a_button_definition["delete"]["image"]="images/kit-fugue/cross.png";
    $a_button_definition["delete"]["type"]="submit";
    
    // Annuler
    $a_button_definition["cancel"]["class"]="negative";
    $a_button_definition["cancel"]["label"]=_BUTTON_CUSTOM_LABEL_CANCEL;
    $a_button_definition["cancel"]["image"]="images/kit-fugue/cross.png";
    $a_button_definition["cancel"]["type"]="button";
    
    // Metre à jour
    $a_button_definition["update"]["class"]="defaut";
    $a_button_definition["update"]["label"]=_BUTTON_CUSTOM_LABEL_UPDATE;
    $a_button_definition["update"]["image"]="images/kit-fugue/arrow-circle.png";
    $a_button_definition["update"]["type"]="submit";
    
    // Défaut
    $a_button_definition["defaut"]["class"]="defaut";
    $a_button_definition["defaut"]["label"]= _BUTTON_CUSTOM_LABEL_DEFAUT;
    $a_button_definition["defaut"]["image"]="images/kit-fugue/exclamation-button.png";
    $a_button_definition["defaut"]["title"]=_BUTTON_CUSTOM_LABEL_DEFAUT_TITLE;
    $a_button_definition["defaut"]["type"]="button";
    

    
    
    $o_img_icon = new img($a_button_definition[$type]["image"]);
    
    $this->stri_class .= $a_button_definition[$type]["class"];
    $this->stri_value = $o_img_icon->htmlValue();
    $this->stri_value .=($label == "1@b31_1pr0b4b13")?$a_button_definition[$type]["label"] : $label;
    $this->stri_title = $a_button_definition[$type]["title"];
    $this->stri_type = $a_button_definition[$type]["type"];
    
  }
  
  
  /*setter*********************************************************************/
  public function setDisabled($value){$this->bool_disabled=$value;}
  public function setName($value){$this->stri_name=$value;}
  public function setType($value){$this->stri_type=$value;}
  public function setValue($value){$this->stri_value=$value;}
  public function setAccesskey($value){$this->stri_accesskey=$value;}
  public function setDir($value){$this->stri_dir=$value;}
  public function setId($value){$this->stri_id=$value;}
  public function setClass($value){$this->stri_class=$value;}
  public function setLang($value){$this->stri_lang=$value;}
  public function setStyle($value){$this->stri_style=$value;}
  public function setTabindex($value){$this->int_tabindex=$value;}
  public function setTitle($value){$this->stri_title=$value;}
  public function setOnblur($value){$this->stri_onblur=$value;}
  public function setOnclick($value){$this->stri_onclick=$value;}
  public function setOndblclick($value){$this->stri_ondblclick=$value;}
  public function setOnfocus($value){$this->stri_onfocus=$value;}
  public function setOnmousedown($value){$this->stri_onmousedown=$value;}
  public function setOnmousemove($value){$this->stri_onmousemove=$value;}
  public function setOnmouseout($value){$this->stri_onmouseout=$value;}
  public function setOnmouseover($value){$this->stri_onmouseover=$value;}
  public function setOnmouseup($value){$this->stri_onmouseup=$value;}
  public function setOnkeydown($value){$this->stri_onkeydown=$value;}
  public function setOnkeypress($value){$this->stri_onkeypress=$value;}
  public function setOnkeyup($value){$this->stri_onkeyup=$value;}

  /*getter**********************************************************************/
  public function getDisabled(){return $this->bool_disabled;}
  public function getName(){return $this->stri_name;}
  public function getType(){return $this->stri_type;}
  public function getValue(){return $this->stri_value;}
  public function getAccesskey(){return $this->stri_accesskey;}
  public function getDir(){return $this->stri_dir;}
  public function getId(){return $this->stri_id;}
  public function getClass(){return $this->stri_class;}
  public function getLang(){return $this->stri_lang;}
  public function getStyle(){return $this->stri_style;}
  public function getTabindex(){return $this->int_tabindex;}
  public function getTitle(){return $this->stri_title;}
  public function getOnblur(){return $this->stri_onblur;}
  public function getOnclick(){return $this->stri_onclick;}
  public function getOndblclick(){return $this->stri_ondblclick;}
  public function getOnfocus(){return $this->stri_onfocus;}
  public function getOnmousedown(){return $this->stri_onmousedown;}
  public function getOnmousemove(){return $this->stri_onmousemove;}
  public function getOnmouseout(){return $this->stri_onmouseout;}
  public function getOnmouseover(){return $this->stri_onmouseover;}
  public function getOnmouseup(){return $this->stri_onmouseup;}
  public function getOnkeydown(){return $this->stri_onkeydown;}
  public function getOnkeypress(){return $this->stri_onkeypress;}
  public function getOnkeyup(){return $this->stri_onkeyup;}

  
  /*other method****************************************************************/
  public function loadCSS($bool_label_vide) {
    global $bgcolor2,$bgcolor3,$bgcolor5;
    $this->obj_style_bouton = new css();
    $this->obj_style_bouton->addClass("
    .button_custom {
      display:inline;
      margin:0 1 0 1;
      padding:5px 10px 5px 7px !important;
      font-size:100%;
      line-height:130%;
      text-decoration:none;
      cursor:pointer;
      width:auto;
      overflow:visible;
      -webkit-border-radius: 2px;
      -moz-border-radius: 2px;
      border-radius: 2px;
      background-color:$bgcolor3;
      border:1px solid $bgcolor2;
      border-top:1px solid $bgcolor5;
      border-left:1px solid $bgcolor5;
    }
    .button_custom img {
      margin:0 3px -3px 0 !important;
      padding:0;
      border:none;
      width:16px;
      height:16px;
    }
    .defaut:hover{
      background-color:#dff4ff;
      border:1px solid #c2e1ef;
      color:#336699;
    }
    .positive:hover{
      background-color:#E6EFC2;
      border:1px solid #C6D880;
      color:#529214;
    }
    .negative:hover{
      background:#fbe3e4;
      border:1px solid #fbc2c4;
      color:#d12f19;
    }");
  }
  
  
  public function htmlValue() {
    $stri_res=$this->obj_style_bouton->cssValue();
    
    $stri_res.="<button ";
    $stri_res.=($this->bool_disabled) ? ' disabled ' : "";
    $stri_res.=($this->stri_name!="") ? ' name="'.$this->stri_name.'"' : "";
    $stri_res.=' type="'.$this->stri_type.'"';
    $stri_res.=($this->stri_accesskey!="")? ' accesskey="'.$this->stri_accesskey.'"':"";
    //$stri_res.=($this->stri_dir!="")? ' dir="'.$this->dir.'"':"";
    $stri_res.=($this->stri_id!="") ? ' id="'.$this->stri_id.'"' : "";  
    $stri_res.=($this->stri_class!="") ? ' class="'.$this->stri_class.'"' : "";
    $stri_res.=($this->stri_style!="") ? ' style="'.$this->stri_style.'"' : "";
    $stri_res.=($this->int_tabindex!="") ? ' tabindex="'.$this->int_tabindex.'"' : ""; 
    $stri_res.=($this->stri_title!="") ? ' title="'.$this->stri_title.'"' : "";
    $stri_res.=($this->stri_onblur!="") ? ' onblur="'.$this->stri_onblur.'"' : "";
    $stri_res.=($this->stri_onclick!="") ? ' onclick="'.$this->stri_onclick.'"' : "";
    $stri_res.=($this->stri_ondblclick!="") ? ' ondblclick="'.$this->stri_ondblclick.'"' : "";
    $stri_res.=($this->stri_onfocus!="") ? ' onfocus="'.$this->stri_onfocus.'"' : "";
    $stri_res.=($this->stri_onmousedown!="") ? ' onmousedown="'.$this->stri_onmousedown.'"' : "";
    $stri_res.=($this->stri_onmousemove!="") ? ' onmousemove="'.$this->stri_onmousemove.'"' : "";
    $stri_res.=($this->stri_onmouseout!="") ? ' onmouseout="'.$this->stri_onmouseout.'"' : "";
    $stri_res.=($this->stri_onmouseover!="") ? ' onmouseover="'.$this->stri_onmouseover.'"' : "";
    $stri_res.=($this->stri_onkeydown!="") ? ' onkeydown="'.$this->stri_onkeydown.'"' : "";
    $stri_res.=($this->stri_onkeypress!="") ? ' onkeypress="'.$this->stri_onkeypress.'"' : "";
    $stri_res.=($this->stri_onkeyup!="") ? ' onkeyup="'.$this->stri_onkeyup.'"' : "";
    $stri_res.=">".$this->stri_value."</button>";
    return $stri_res;
  }
}

?>
