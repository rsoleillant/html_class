<?php
/*******************************************************************************
Create Date : 22/05/2006
 ----------------------------------------------------------------------
 Class name : select
 Version : 1.1.3
 Author : Rémy Soleillant
 Description : élément html <select>
********************************************************************************/

include_once("includes/html_class.php");
class select_to_text 
{
   
   /*attribute***********************************************/
   protected $o_select = select; // objet de type select formant l'objet select_to_text
   protected $o_text = text; // objet de type text formant l'objet select_to_text 
   protected $o_img = img;
   protected $s_style = '';
   protected $s_id = '';
   protected $s_hidden = 'text';
   
   
   /* constructor***************************************************************/
   public function __construct($o_select = select, $o_text = text, $o_img = img) 
   {
    if(is_object($o_select))
    {             
     $this->o_select = $o_select;
     $this->o_text = $o_text;  
     $this->o_img = $o_img;    
    }
    else
    {  
     $this->o_select=new select($o_select);
      $this->o_select->setId($o_select);
     $this->o_text = new text($o_select);
       $this->o_text->setId("text_".$o_select);  
     $this->o_img = new img("images/add_out.gif");    
    }     
   }
  
   /*setter*********************************************************************/
   public function setObjSelect($o_select)
   {  $this->o_select = $o_select;  }
  
   public function setObjText($o_text)
   {  $this->o_text = $o_text;  }
   
   public function setObjImg($o_img)
   {  $this->o_img = $o_img;  }
   
   public function setStyle($s_value)
   {  $this->s_style = $s_value;  }

   public function setId($s_value)
   {  $this->s_id = $s_value;  }
   
   public function setHidden($s_value)
   {  $this->s_hidden = $s_value;  }

   /*getter**********************************************************************/
   public function getObjSelect()
   {  return $this->o_select;  }
  
   public function getObjText()
   {  return $this->o_text;  }
   
   public function getObjImg()
   {  return $this->o_img;  }
  
   public function getStyle()
   {  return $this->s_style;  }
  
   public function getId()
   {  return $this->s_id;  }
   
   public function getHidden()
   {  return $this->s_hidden;  }

   /* method for serialization **************************************************/
   /*clonage *******************************************************************/
  
  /*other method****************************************************************/  
  public function htmlValue()
  {
    $o_javascripter=new javascripter();
   /* $o_javascripter->addFunction
    ("
      function change (s_id_select, s_id_text, s_name_select, s_name_text)
      {
        if(document.getElementById(s_id_select).style.display=='none')
        {
          document.getElementById(s_id_select).name = s_name_select;
          document.getElementById(s_id_text).name = '';
          document.getElementById(s_id_select).style.display='inline';
          document.getElementById(s_id_text).style.display='none';
        }
        else
        {
          document.getElementById(s_id_select).name = '';
          document.getElementById(s_id_text).name = s_name_text;
          document.getElementById(s_id_select).style.display='none';
          document.getElementById(s_id_text).style.display='inline';
        }
      }
    "); */
    
    $o_javascripter->addFunction
    ('  
      function change (obj_img)      
      {
        var parent=$(obj_img).parent("span"); //récupération du span parent
        var select=parent.children("select"); //on récupère le select
        var text=parent.children("input");    //on récupère le champ text
        
       if(select.css("display")=="none")
       {         
          select.attr("name",text.attr("name")); 
          text.attr("name","");      
          select.css("display","inline");
          text.css("display","none");
       }
       else
       {
          text.attr("name",select.attr("name")); 
          select.attr("name","");         
          select.css("display","none");
          text.css("display","inline");
       }         
      }
    ');
    
    // récup des id si existe, sinon définition auto
    if ($this->o_select->getId() === '')
    {
      $this->o_select->setId('select');
      $s_id_select = 'select';
    }
    else
      $s_id_select = $this->o_select->getId();
    
    if ($this->o_text->getId() === '')
    {
      $this->o_text->setId('text');
      $s_id_text = 'text';
    }
    else
      $s_id_text = $this->o_text->getId();

    // surcharge des attributs des éléments select, text et image    
    // surcharge des styles    
    if ($this->s_hidden != 'text')
    {
      $s_diplay_select = ' display: none;'; 
      $s_diplay_text = ' display: inline;';
    } 
    else
    {
      $s_diplay_select = ' display: inline;'; 
      $s_diplay_text = ' display: none;';
    } 
    $s_temp = $this->o_select->getStyle();
    $this->o_select->setStyle($s_temp.$s_diplay_select);
    $s_temp = $this->o_text->getStyle();
    $this->o_text->setStyle($s_temp.$s_diplay_text);
    $s_temp = $this->o_img->getStyle();
    $this->o_img->setStyle($s_temp.' cursor: pointer;');
    // surcharge evenement onclick d'une image
    $s_temp = $this->o_img->getOnclick();
    //$this->o_img->setOnclick($s_temp." change('".$s_id_select."','".$s_id_text."','".$this->o_select->getName()."','".$this->o_text->getName()."');");
    $this->o_img->setOnclick($s_temp." change(this);");
   
    if( $this->o_text->getValue()=="")//si la valeur n'est pas porté par le text
    {$this->o_text->setName('');}
    else
    {$this->o_select->setName('');}
    
    
    // définition des param propre au select_to_text    
    $s_param = ($this->s_id==='')?'':' id="'.$this->s_id.'"';
    $s_param .= ($this->s_style==='')?'':' style="'.$this->s_style.'"';
    return '<span'.$s_param.'>'
                .$o_javascripter->javascriptValue()
                .$this->o_select->htmlValue()
                .$this->o_text->htmlValue()
                .' '.$this->o_img->htmlValue()
           .'</span>';
  }
  
  public function makeSqlToSelect($stri_sql,$obj="",$func="")
  {
   return $this->o_select->makeSqlToSelect($stri_sql,$obj,$func);
  } 
  
  public function selectOption($value)
  {
   return $this->o_select->selectOption($value);
  }
}
?>
