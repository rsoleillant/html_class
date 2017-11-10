<?php
/*******************************************************************************
Create Date : 28/1/2012
 ----------------------------------------------------------------------
 Class name : dialog
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet d'afficher des boîtes de dialog en JQuery
               Voir source jqueryui http://api.jqueryui.com/dialog/
********************************************************************************/
class dialog{
   
   //**** Attributs de base ****************************************************
    protected $stri_id;        //L'identifiant de la boite de dialogue
    protected $stri_message;   //Le message de la boite de dialogue
     
   //**** Attributs de configuration *******************************************
    protected $bool_autoOpen;
    protected $stri_buttons;
    protected $stri_closeOnEscape;
    protected $stri_closeText;
    protected $stri_dialogClass;
    protected $stri_disabled;
    protected $stri_draggable;
    protected $int_height;
    protected $stri_hide;
    protected $int_maxHeight;
    protected $int_maxWidth;
    protected $int_minHeight;
    protected $int_minWidth;
    protected $bool_modal;
    protected $int_position;
    protected $bool_resizable;
    protected $stri_show;
    protected $bool_stack;
    protected $stri_title;
    protected $int_width;
    protected $int_zIndex;
   
    //**** Attributs évènement *************************************************
    protected $stri_beforeClose;
    protected $stri_create;
    protected $stri_open;
    protected $stri_focus;
    protected $stri_dragStart;
    protected $stri_drag;
    protected $stri_dragStop;
    protected $stri_resizeStart;
    protected $stri_resize;
    protected $stri_resizeStop;
    protected $stri_close;


  //**** constructor ***********************************************************
 /*************************************************************
 *
 * parametres : 
 * retour : objet de la classe 
 *                        
 **************************************************************/    
  function __construct($stri_titre,$stri_message) 
  { 
        
    $this->bool_autoOpen=false       ;
    $this->stri_show='blind'         ;
    $this->stri_hide='explode'       ;
    $this->stri_title=$stri_titre    ;
    $this->stri_message=$stri_message;
          
    //- génération d'un identifiant
    $this->stri_id="id_dialog_".str_replace('.','_',  microtime(true));
  }
 
  //**** setter ****************************************************************
  public function setId($value){$this->stri_id=$value;}
  public function setMessage($value){$this->stri_message=$value;}
 
  public function setAutoopen($value){$this->bool_autoOpen=$value;}
  public function setButtons($value){$this->stri_buttons=$value;}
  public function setCloseonescape($value){$this->stri_closeOnEscape=$value;}
  public function setClosetext($value){$this->stri_closeText=$value;}
  public function setDialogclass($value){$this->stri_dialogClass=$value;}
  public function setDisabled($value){$this->stri_disabled=$value;}
  public function setDraggable($value){$this->stri_draggable=$value;}
  public function setHeight($value){$this->int_height=$value;}
  public function setHide($value){$this->stri_hide=$value;}
  public function setMaxheight($value){$this->int_maxHeight=$value;}
  public function setMaxwidth($value){$this->int_maxWidth=$value;}
  public function setMinheight($value){$this->int_minHeight=$value;}
  public function setMinwidth($value){$this->int_minWidth=$value;}
  public function setModal($value){$this->bool_modal=$value;}
  public function setPosition($value){$this->int_position=$value;}
  public function setResizable($value){$this->bool_resizable=$value;}
  public function setShow($value){$this->stri_show=$value;}
  public function setStack($value){$this->bool_stack=$value;}
  public function setTitle($value){$this->stri_title=$value;}
  public function setWidth($value){$this->int_width=$value;}
  public function setZindex($value){$this->int_zIndex=$value;}

  public function setBeforeclose($value){$this->stri_beforeClose=$value;}
  public function setCreate($value){$this->stri_create=$value;}
  public function setOpen($value){$this->stri_open=$value;}
  public function setFocus($value){$this->stri_focus=$value;}
  public function setDragstart($value){$this->stri_dragStart=$value;}
  public function setDrag($value){$this->stri_drag=$value;}
  public function setDragstop($value){$this->stri_dragStop=$value;}
  public function setResizestart($value){$this->stri_resizeStart=$value;}
  public function setResize($value){$this->stri_resize=$value;}
  public function setResizestop($value){$this->stri_resizeStop=$value;}
  public function setClose($value){$this->stri_close=$value;}


  //**** getter ****************************************************************
  public function getId(){return $this->stri_id;}
  public function getMessage(){return $this->stri_message;}

  public function getAutoopen(){return $this->bool_autoOpen;}
  public function getButtons(){return $this->stri_buttons;}
  public function getCloseonescape(){return $this->stri_closeOnEscape;}
  public function getClosetext(){return $this->stri_closeText;}
  public function getDialogclass(){return $this->stri_dialogClass;}
  public function getDisabled(){return $this->stri_disabled;}
  public function getDraggable(){return $this->stri_draggable;}
  public function getHeight(){return $this->int_height;}
  public function getHide(){return $this->stri_hide;}
  public function getMaxheight(){return $this->int_maxHeight;}
  public function getMaxwidth(){return $this->int_maxWidth;}
  public function getMinheight(){return $this->int_minHeight;}
  public function getMinwidth(){return $this->int_minWidth;}
  public function getModal(){return $this->bool_modal;}
  public function getPosition(){return $this->int_position;}
  public function getResizable(){return $this->bool_resizable;}
  public function getShow(){return $this->stri_show;}
  public function getStack(){return $this->bool_stack;}
  public function getTitle(){return $this->stri_title;}
  public function getWidth(){return $this->int_width;}
  public function getZindex(){return $this->int_zIndex;}

  public function getBeforeclose(){return $this->stri_beforeClose;}
  public function getCreate(){return $this->stri_create;}
  public function getOpen(){return $this->stri_open;}
  public function getFocus(){return $this->stri_focus;}
  public function getDragstart(){return $this->stri_dragStart;}
  public function getDrag(){return $this->stri_drag;}
  public function getDragstop(){return $this->stri_dragStop;}
  public function getResizestart(){return $this->stri_resizeStart;}
  public function getResize(){return $this->stri_resize;}
  public function getResizestop(){return $this->stri_resizeStop;}
  public function getClose(){return $this->stri_close;}
 
  //**** public method *********************************************************
  
  
 /*************************************************************
 * Pour avoir le code html + javascript de l'objet
 * parametres : aucun
 * retour : string : le code html + javascript
 *                        
 **************************************************************/    
  public function htmlValue()
  {
    
   //- Partie html 
   $stri_html='
    <div id="'.$this->stri_id.'" title="'.$this->stri_titre.'">
      <p>'.$this->stri_message.'</p>
    </div>';
   
   //- Préparation à la configuration du javascript
   $arra_attribut=get_object_vars($this);//récupération des attribut
   $arra_attribut_non_vide=array();
   array_shift($arra_attribut);//suppression de l'id
   array_shift($arra_attribut);//suppression du message
   //les attributs restant sont ceux qui on un sens en jquery
   foreach($arra_attribut as $stri_attribut=>$stri_value)
   {
   
     if($this->$stri_attribut!==null)//si l'attribut n'est pas vide
     {
       //- décomposition de l'attribut
       $int_pos=strpos($stri_attribut, '_');
       $stri_type=substr($stri_attribut,0, $int_pos);
       $stri_jsname=substr($stri_attribut,$int_pos+1);
       
       //- gestion des ' dans la valeur
       $stri_cote="'";
       if($stri_type=="bool")
       {
         $stri_cote="";
         $stri_value=($stri_value)?'true':'false';//conversion booléen en chaine
       }
       
       //- renseignement de l'attribut jquery et de sa valeur  
       $arra_attribut_non_vide[]=$stri_jsname.':'.$stri_cote.$stri_value.$stri_cote;
     
     }
   }
   
   $stri_config=implode(",\n",$arra_attribut_non_vide);
   /*echo "<pre>";
   var_dump($stri_config);
   echo "</pre>";
       */
   
    //- Partie javascript
    $obj_javascripter=new javascripter();
    $obj_javascripter->addFunction("
    $(function() {
        $( '#".$this->stri_id."').dialog(
        {   
          $stri_config
        });
    });
   ");
   
   
   return $stri_html.$obj_javascripter->javascriptValue(); 
  }

 
}

?>
