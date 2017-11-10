<?php

/* * *****************************************************************************
  Create Date : 22/05/2006
  ----------------------------------------------------------------------
  Class name : input
  Version : 1.3
  Author : Rémy Soleillant
  Description : élément html <input>
 * ****************************************************************************** */

abstract class input extends serialisable {
    /* attribute********************************************** */

    protected $stri_name = "";
    protected $stri_type;
    protected $stri_value = "";
    protected $bool_disabled = false;
    protected $int_size = "";
    protected $stri_alt = "";
    protected $stri_onfocus = "";
    protected $stri_onblur = "";
    protected $stri_onselect = "";
    protected $stri_onchange = "";
    protected $stri_onmouseover = "";
    protected $stri_onmouseout = "";
    protected $stri_onkeypress = "";
  protected $stri_onkeyup="";
   protected $stri_oninvalid="";
    protected $int_tabindex = "";
    protected $stri_data_type = "string";
    protected $stri_title = "";
    protected $stri_style = "";
    protected $stri_id = "";
    protected $stri_class = "";
    protected $bool_can_be_empty = false;
    protected $bool_required;
    protected $stri_placeholder;
    protected $stri_pattern;
    protected $arra_data;
    public $arra_sauv = array();             //tableau pour la sérialisation

    /* setter******************************************************************** */

    public function setName($value) {
        $this->stri_name = $value;
    }

    public function setType($value) {
        $this->stri_type = $value;
    }

  public function setOnkeypress($value)
  {$this->stri_onkeypress=$value;}
  public function setOnkeyup($value)
  {$this->stri_onkeyup=$value;}  
 

    public function setClass($src) {
        $this->stri_class = $src;
    }

    public function setCanBeEmpty($bool) {
        if (is_bool($bool)) {
            $this->bool_can_be_empty = $bool;
        } else {
            echo("<script>alert('bool_disabled doit etre de type boolean');</script>");
        }
    }

    public function setStyle($value) {
        $this->stri_style = $value;
    }

    public function setTitle($value) {
        $this->stri_title = $value;
    }

    public function setValue($value) {
        $this->stri_value = $value;
    }

    public function setId($value) {
        $this->stri_id = $value;
    }

    public function setDataType($value) {
        $this->stri_data_type = $value;
    }

    public function setAlt($value) {
        $this->stri_alt = $value;
    }

    public function setOnfocus($value) {
        $this->stri_onfocus = $value;
    }

    public function setOnmouseover($value) {
        $this->stri_onmouseover = $value;
    }

    public function setOnmouseout($value) {
        $this->stri_onmouseout = $value;
    }

    public function setOnblur($value) {
        $this->stri_onblur = $value;
    }

    public function setOnselect($value) {
        $this->stri_onselect = $value;
    }

    public function setOnchange($value) {
        $this->stri_onchange = $value;
    }
   public function setOnInvalid($value)
  {
    $this->stri_oninvalid=$value;
  }
  

    public function setDisabled($bool) {
        if (is_bool($bool)) {
            $this->bool_disabled = $bool;
        } else {
            echo("<script>alert('bool_disabled doit etre de type boolean');</script>");
        }
    }

    public function setSize($int) {
        if (is_numeric($int)) {
            $this->int_size = $int;
        } else {
            echo("<script>alert('size doit etre de type entier');</script>");
        }
    }

    public function setReadonly($bool) {/* sert a ce que tous les éléments d'un formulaire possède une méthode
      readonly. Utile pour ne pas générer d'erreur lors de l'appel de la méthode
      protectForm d'un objet form */

        return true;
    }

    public function SetTabIndex($num) {
        $this->int_tabindex = $num;
    }

    public function setRequired($value) {
        $this->bool_required = $value;
    }

    public function setPlaceholder($value) {
        $this->stri_placeholder = $value;
    }

    public function setPattern($value) {
        $this->stri_pattern = $value;
    }

    /* getter********************************************************************* */

    public function getType() {
        return $this->stri_type;
    }

    public function getPattern() {
        return $this->stri_pattern;
    }

    public function getOnkeypress() {
        return $this->stri_onkeypress;
    }

    public function getClass() {
        return $this->stri_class;
    }

    public function getOnmouseout() {
        return $this->stri_onmouseout;
    }

    public function getOnmouseover() {
        return $this->stri_onmouseover;
    }

    public function getTitle() {
        return $this->stri_title;
    }

    public function getDataType() {
        return $this->stri_data_type;
    }

    public function getStyle() {
        return $this->stri_style;
    }

    public function getCanBeEmpty() {
        return $this->bool_can_be_empty;
    }

    public function getName() {
        return $this->stri_name;
    }

    public function getValue() {
        return $this->stri_value;
    }

    public function getDisabled() {
        return $this->bool_disabled;
    }

    public function getSize() {
        return $this->int_size;
    }

    public function getAlt() {
        return $this->stri_alt;
    }

    public function getOnfocus() {
        return $this->stri_onfocus;
    }

    public function getOnblur() {
        return $this->stri_onblur;
    }

    public function getOnselect() {
        return $this->stri_onselect;
    }

    public function getOnchange() {
        return $this->stri_onchange;
    }

    public function getId() {
        return $this->stri_id;
    }

    public function getRequired() {
        return $this->bool_required;
    }

    public function getPlaceholder() {
        return $this->stri_placeholder;
    }

    /* other method*************************************************************** */

    public function addData($stri_name, $value) {
        $this->arra_data[$stri_name] = $value;
    }

    public function super_htmlValue() {
        //- construction de l'attribut data
        $arra_data = array();
        foreach ($this->arra_data as $stri_name => $stri_value) {
            $arra_data[] = 'data-' . $stri_name . '="' . $stri_value . '"';
        }
        $stri_data = implode(' ', $arra_data);
        if (is_object($this->stri_value) && method_exists($this->stri_value, 'htmlValue')) {
            $this->stri_value = $this->stri_value->htmlValue();
        }

        $stri_res = "<input ";
        // START - EM MODIF 10-07-2007
        $stri_res.=" type=\"" . $this->stri_type . "\"";
        $stri_res.=($this->stri_name != "") ? " name=\"" . $this->stri_name . "\"" : "";
        $stri_res.=($this->stri_class != "") ? " class=\"" . $this->stri_class . "\"" : "";
        $stri_res.=($this->stri_onmouseover != "") ? " onmouseover=\"" . $this->stri_onmouseover . "\"" : "";
        $stri_res.=($this->stri_onmouseout != "") ? " onmouseout=\"" . $this->stri_onmouseout . "\"" : "";
        $stri_res.=($this->stri_onkeypress != "") ? " onkeypress=\"" . $this->stri_onkeypress . "\"" : "";
  $stri_res.=($this->stri_onkeyup!="") ? " onkeyup=\"".$this->stri_onkeyup."\"" : "";
        $stri_res.=((string) $this->int_tabindex != "") ? " tabindex=\"" . $this->int_tabindex . "\"" : "";
        $stri_res.=($this->stri_style != "") ? " style=\"" . $this->stri_style . "\"" : "";
        $stri_res.=((string) $this->stri_id != "") ? " id=\"" . $this->stri_id . "\"" : "";
        $stri_res.=((string) $this->stri_title != "") ? " title=\"" . $this->stri_title . "\"" : "";
        //$stri_res.=((string)$this->stri_value!="")?" value=\"".$this->stri_value."\"":"";  
        $stri_res.=($this->stri_value != "") ? " value=\"" . $this->stri_value . "\"" : "";
        $stri_res.=((string) $this->int_size != "") ? " size=\"" . $this->int_size . "\"" : "";
        $stri_res.=((string) $this->stri_alt != "") ? " alt=\"" . $this->stri_alt . "\"" : "";
        $stri_res.=($this->stri_onfocus != "") ? " onfocus=\"" . $this->stri_onfocus . "\"" : "";
        $stri_res.=($this->stri_onblur != "") ? " onblur=\"" . $this->stri_onblur . "\"" : "";
        $stri_res.=($this->stri_onselect != "") ? " onselect=\"" . $this->stri_onselect . "\"" : "";
        $stri_res.=($this->stri_onchange != "") ? " onchange=\"" . $this->stri_onchange . "\"" : "";
  $stri_res.=($this->stri_oninvalid!="") ? " oninvalid=\"".$this->stri_oninvalid."\"" : "";
        $stri_res.=($this->stri_placeholder != "") ? " placeholder=\"" . $this->stri_placeholder . "\"" : "";
        $stri_res.=($this->stri_pattern != "") ? " pattern=\"" . $this->stri_pattern . "\"" : "";
        $stri_res.=($this->bool_disabled) ? " disabled " : "";
        $stri_res.=($this->bool_required) ? " required " : "";
        $stri_res.=$stri_data;
        // END - EM MODIF 10-07-2007 
        return $stri_res;
    }

}

?>
