<?php
/*******************************************************************************
Create Date : 22/05/2006
 ----------------------------------------------------------------------
 Class name : td
 Version : 1.1
 Author : Rémy Soleillant
 Description : élément html <td>
********************************************************************************/
class td extends serialisable{
   
   /*attribute***********************************************/
   protected $bool_nowrap="";
   protected $stri_bgcolor="";
   protected $int_rowspan=1;
   protected $int_colspan="";
   protected $stri_align="left";
   protected $stri_valign="";
   protected $stri_id="";
   protected $bool_is_object;
   protected $stri_width="";
   protected $mixed_value;
   protected $stri_style;
   protected $stri_onclick;
   protected $stri_ondblclick="";
   protected $stri_onmouseover="";
   protected $stri_onmouseout="";
   protected $stri_onmousedown;
   protected $stri_onmouseup;
   protected $stri_onmouseenter;
   protected $stri_onmouseleave;
   protected $stri_onmousemove;
   protected $stri_title="";
   protected $stri_class="";
   protected $stri_background="";
   protected $arra_data=[];
   
   public $arra_sauv;
   /* constructor***************************************************************/
     /*************************************************************
  Permet de construire un td de plusieurs façon
 
 Paramètres : mixed : string : la valeur à afficher dans le td
                      obj : un objet avec une méthode htmlValue appellé sur htmlValue du td
                      array : un tableau d'objet htmlValue et/ou de string 
 Retour :aucun
  
  **************************************************************/ 
   function __construct($mixed_value) {
   //$this->bool_is_object=(method_exists($stri_value,"htmlValue") && is_object($stri_value))?true:false;
    //$this->bool_is_object=(is_object($stri_value)&& method_exists($stri_value,"htmlValue"))?true:false;
      
    $this->mixed_value = $mixed_value;
       
      
   }
  
  
   /*setter*********************************************************************/
   
  public function setData($stri_idx ,$stri_value)
  {
      $this->arra_data[$stri_idx] = $stri_value;
  }
  
  public function setValue($value,$is_object=false)
  {
    $this->bool_is_object=$is_object;
    $this->mixed_value=$value;
  }
  
   public function setOnclick($value)
  {
    $this->stri_onclick=$value;
  }
  
   public function setWidth($value)
  {
    $this->stri_width=$value;
  }
  
   public function setClass($value)
  {$this->stri_class=$value;}
  
  
  public function setOndblclick($value)
  {
    $this->stri_ondblclick=$value;
  }
  
  public function setOnMouseOver($value)
  {
    $this->stri_onmouseover=$value;
  }
  
  public function setOnMouseOut($value)
  {
    $this->stri_onmouseout=$value;
  }
  
  public function setNowrap($bool)
  {
    if(is_bool($bool))
    {
      $this->bool_nowrap=$bool;
    }
    else
    {
      echo("<script>alert('bool_nowrap doit etre de type boolean');</script>");
    }
  }
  public function setBgcolor($color)
  {
    $this->stri_bgcolor=$color;
  }
  
  public function setColspan($int)
  {
    if(is_numeric ($int))
    {
      $this->int_colspan=$int;
    }
    else
    {
      echo("<script>alert('int_colspan doit etre de type entier');</script>");
    }
  }
  public function setRowspan($int)
  {
    if(is_numeric ($int))
    {
      $this->int_rowspan=$int;
    }
    else
    {
      echo("<script>alert('int_rowspan doit etre de type entier');</script>");
    }
  }
  public function setValign($stri_valign)
  {
     if(($stri_valign=='top')||($stri_valign=='middle')||($stri_valign=='bottom')||($stri_valign=='baseline'))
     {$this->stri_valign=$stri_valign;}
     else
     {echo("<script>alert('type  stri_valign incorect');</script>");}
  
  }
  public function setAlign($stri_align)
  {
    $this->stri_align=$stri_align;
  }
  
  public function setStyle($value)
  {
    $this->stri_style=$value;
  }
  
  public function setId($value)
  {$this->stri_id=$value;}  
  
  public function setTitle($value)
  {$this->stri_title=$value;}
  
  public function setBackground($value)
  {$this->stri_background=$value;} 
    
  public function setOnmousedown($value){$this->stri_onmousedown=$value;}
  public function setOnmouseup($value){$this->stri_onmouseup=$value;}
  public function setOnmouseenter($value){$this->stri_onmouseenter=$value;}
  public function setOnmouseleave($value){$this->stri_onmouseleave=$value;}
  public function setOnmousemove($value){$this->stri_onmousemove=$value;}

  /*getter**********************************************************************/
  public function getNowrap()
  {return $this->bool_nowrap; }
  
  public function getClass()
  {return $this->stri_class;}
  
  public function getBgcolor()
  {return $this->stri_bgcolor;}
  
  public function getRowspan()
  {return $this->int_rowspan;}
  
  public function getColspan()
  {return $this->int_colspan;}
   
  public function getAlign()
  {return $this->stri_align;}
  
  public function getValign()
  {return $this->stri_valign;}
   
  public function getValue() 
  {return $this->mixed_value;}
  
  public function getWidth()
  {return $this->stri_width;}
  
  public function getStyle()
  {return $this->stri_style;}
  
  public function getId()
  {return $this->stri_id;}
  
  public function getTitle()
  {return $this->stri_title;}
  
  public function getBackground()
  {return $this->stri_background;}
                             
  public function getOndblclick()
  {return $this->stri_ondblclick;}
  
  public function getOnclick()
  {return $this->stri_onclick;}
  
  public function getOnmouseover()
  {return $this->stri_onmouseover;}
  
  public function getOnmouseout()
  {return $this->stri_onmouseout;}
  
    //Permet d'obtenir l'élement portant l'id passé en paramètre
  public function getElementById($stri_id)
  {
  //echo "recherché : $stri_id , mon id ".$this->stri_id."<br />";
   if($this->stri_id==$stri_id)//si l'élément cherché est le td
   {return $this;}
    
  
   if((is_object($this->mixed_value))&&(get_class($this->mixed_value)=="table"))//lancement sur contenu table  
   {  
   
    $mixed_res=$this->mixed_value->getElementById($stri_id);
    
    if($mixed_res!==false)
    {return $mixed_res;}
    return false;
   }
   
  
   
   if(is_array($this->mixed_value))
   {
     foreach($this->mixed_value as $mixed_element)
     {
      
      if((is_object($mixed_element))&&(get_class($mixed_element)=="table"))//lancement sur contenu table  
       {
        $mixed_res=$mixed_element->getElementById($stri_id);
        if($mixed_res!==false)
        {return $mixed_res;}
        
       }
          
       if((is_object($mixed_element))&&(method_exists($mixed_element, 'getId')))//lancement sur contenu table  
       {       
          if($mixed_element->getId()==$stri_id)
          {   
            return $mixed_element;
          }
       }
       
     }
     return false;
   }

   if((is_object($this->mixed_value))&&(method_exists($this->mixed_value, 'getId')))//lancement sur contenu table  
   { 
   
      if($this->mixed_value->getId()==$stri_id)
      {   
        return $this->mixed_value;
      }
   }

   return false;
  }
 
  public function getOnmousedown(){return $this->stri_onmousedown;}
  public function getOnmouseup(){return $this->stri_onmouseup;}
  public function getOnmouseenter(){return $this->stri_onmouseenter;}
  public function getOnmouseleave(){return $this->stri_onmouseleave;}
  public function getOnmousemove(){return $this->stri_onmousemove;}

  /*other method****************************************************************/
  /*************************************************************
  Permet de construire la valeur qui sera entre <td> et </td>
 
 Paramètres :aucun
 Retour :string : la valeur  du td
  
  **************************************************************/ 
  
  public function constructValue()
  { 
     if(is_string($this->mixed_value)) //cas standard, l'attribut de valeur est une chaine
     {
    
     return $this->mixed_value;}
     
     if(is_object($this->mixed_value)&& method_exists($this->mixed_value,"htmlValue"))//cas de passage d'un seul objet avec méthode htmlValue 
     {return $this->mixed_value->htmlValue();}
     
     if(is_array($this->mixed_value))//cas d'un tableau d'objet et/ou de string
     {
      $stri_res="";
      foreach($this->mixed_value as $mixed_param)
      {
        if(is_string($mixed_param)) 
        {$stri_res.= $mixed_param;}
        
        if(is_object($mixed_param)&& method_exists($mixed_param,"htmlValue")) 
        {$stri_res.= $mixed_param->htmlValue();}
      }
      
      return $stri_res;
     }
  
   //autre cas normalement non traité
   return $this->mixed_value;
  }
  
  public function htmlValue()
  {
  //START - EM MODIF 10-07-2007  
  /*$stri_res="<td rowspan=\"".$this->int_rowspan."\" colspan=\"".$this->int_colspan.
            "\" align=\"".$this->stri_align."\" valign=\"".$this->stri_valign."\" "; */
  
  $stri_res='<td';
  
  $stri_res.=($this->stri_align!='')? ' align="'.$this->stri_align.'" ' : '';
  $stri_res.=($this->stri_valign!='')? ' valign="'.$this->stri_valign.'" ' : '';
  
  $stri_res.=($this->int_rowspan!=1)? ' rowspan="'.$this->int_rowspan.'" ' : '';
  $stri_res.=($this->int_colspan!=1)? ' colspan="'.$this->int_colspan.'" ' : '';
  $stri_res.=($this->stri_bgcolor!='')? ' bgcolor="'.$this->stri_bgcolor.'" ' : '';          
  
  $stri_res.=($this->stri_id!='')?' id="'.$this->stri_id.'" ':'';
  $stri_res.=($this->stri_title!='')?' title="'.$this->stri_title.'" ':'';
  $stri_res.=($this->stri_background!='')?' background="'.$this->stri_background.'" ':'';
  $stri_res.=($this->stri_class!='')? ' class="'.$this->stri_class.'" ' : '';
  $stri_res.=((string)$this->stri_width!='')? ' width="'.$this->stri_width.'" ' : '';
  $stri_res.=($this->stri_style!='')? ' style="'.$this->stri_style.'" ' : '';
  $stri_res.=($this->stri_ondblclick!='')? ' ondblclick="'.$this->stri_ondblclick.'" ' : ''; 
  $stri_res.=($this->stri_onmouseover!='')? ' onmouseover="'.$this->stri_onmouseover.'" ' : '';           
  $stri_res.=($this->stri_onmouseout!='')? ' onmouseout="'.$this->stri_onmouseout.'" ' : '';
  $stri_res.=($this->stri_onclick!='')? ' onclick="'.$this->stri_onclick.'" ' : '';
  $stri_res.=($this->stri_onmousedown!='')? ' onmousedown="'.$this->stri_onmousedown.'" ' : '';           
  $stri_res.=($this->stri_onmouseup!='')? ' onmouseup="'.$this->stri_onmouseup.'" ' : '';
  $stri_res.=($this->stri_onmouseenter!='')? ' onmouseenter="'.$this->stri_onmouseenter.'" ' : '';           
  $stri_res.=($this->stri_onmouseleave!='')? ' onmouseleave="'.$this->stri_onmouseleave.'" ' : '';
  $stri_res.=($this->stri_onmousemove!='')? ' onmousemove="'.$this->stri_onmousemove.'" ' : '';
  
  //- Pose des attributs data
  foreach ($this->arra_data as $stri_idx=>$stri_value)
  {
      $stri_res .= ($stri_value && $stri_idx) ? 'data-'.$stri_idx.'="'.$stri_value.'"' : '';
  }
 
  //END - EM MODIF 10-07-2007
  if($this->bool_nowrap)
    {$stri_res=$stri_res.' nowrap ';} 
  
  $stri_res=$stri_res.' >';
 
  $stri_res.=$this->constructValue();//construction de la valeur en string
  
  /* 
  
  if($this->bool_is_object)
  {$stri_res.=$this->stri_value->htmlValue();}
  else
  {$stri_res.=$this->stri_value;}
   */
  $stri_res.='</td>';  
  
  return $stri_res;    
   
  }
  
   /*************************************************************
  Permet de remplacer les input par des font dans le td
 
 Paramètres : aucun
             
 Retour : aucun
   
  **************************************************************/        
  public function replaceInputByFont()
  {    
    $stri_class=="non objet";
    $arra_input=array("text","textarea","select","calendar_jquery","text_arrea","slider_jquery");//liste des tag à remplacer
    $obj_converter=new htmlClassConverter();  
    if(is_object($this->mixed_value))
    {
      $stri_class=get_class($this->mixed_value); 
     
      //echo "$stri_class<br />";
      if(in_array($stri_class, $arra_input)) //si on doit faire le changement
      {     
        //echo "conversion<br />";
        /*$stri_value=$this->mixed_value->getValue();//recherche de la valeur de l'input
        $stri_name=$this->mixed_value->getName();
        $obj_font=new font($stri_value);
        $obj_hidden=new hidden($stri_name,$stri_value); */
      
        $obj_font=$obj_converter->toFont($this->mixed_value);
        $obj_hidden=$obj_converter->toHidden($this->mixed_value);
        $this->mixed_value=array($obj_font,$obj_hidden);//remplacement de l'input par un font
      }
      
      if($stri_class=="table")
      {
        $this->mixed_value->replaceInputByFont();//remplacement récursif
      }
      
      $arra_collection=array('radio','checkbox','checkbox_collection');
      if(in_array($stri_class,$arra_collection))
      {
        $stri_src="images/unchecked_128x128.png";
        if($this->mixed_value->getChecked())
        { $stri_src="images/checked_128x128.png";}
         
         $obj_img=new img($stri_src);
          $obj_img->setStyle('width:20px;');
         $obj_hidden=$obj_converter->toHidden($this->mixed_value);
         $this->mixed_value=array($obj_img,$obj_hidden);//remplacement de l'input par un font
      
      }  
    }
    
    if(is_array($this->mixed_value))
    {
      foreach($this->mixed_value as $stri_key=>$mixed_value)
      {
          if(is_object($mixed_value))
           {
            $stri_class=get_class($mixed_value); 
        
            if(in_array($stri_class, $arra_input)) //si on doit faire le changement
            {
                   // echo "$var fichier :".__FILE__." ligne :".__LINE__."</br>";
                 /* $stri_value=$mixed_value->getValue();//recherche de la valeur de l'input
                  //$stri_name=$this->mixed_value->getName();
                  $stri_name=$mixed_value->getName();
                  $obj_font=new font($stri_value);
                  $obj_hidden=new hidden($stri_name,$stri_value);
                                                                 */
                  $obj_font=$obj_converter->toFont($mixed_value);                  
                  $obj_hidden=$obj_converter->toHidden($mixed_value); 
                  $this->mixed_value[$stri_key]=$obj_font;//remplacement de l'input par un font
                  $this->mixed_value[]=$obj_hidden;
              
           
            }
            
            if($stri_class=="table")
            {
              $this->mixed_value->replaceInputByFont();//remplacement récursif
            }
          }
      }
    }
  }
    /*************************************************************
  Permet de remplacer les input par des hidden dans le td
 
 Paramètres : aucun
             
 Retour : aucun
   
  **************************************************************/        
  public function replaceInputByHidden()
  {    
    $stri_class=="non objet";
    $arra_input=array("text","textarea","select","calendar_jquery","text_arrea");//liste des tag à remplacer
    $obj_converter=new htmlClassConverter();  
    if(is_object($this->mixed_value))
    {
      $stri_class=get_class($this->mixed_value); 
   
      if(in_array($stri_class, $arra_input)) //si on doit faire le changement
      {     
        //$obj_font=$obj_converter->toFont($this->mixed_value);
        $obj_hidden=$obj_converter->toHidden($this->mixed_value);
        $this->mixed_value=$obj_hidden;//remplacement de l'input par un font
      }
      
      if($stri_class=="table")
      {
        $this->mixed_value->replaceInputByHidden();//remplacement récursif
      }
      
      $arra_collection=array('radio','checkbox','checkbox_collection');
      if(in_array($stri_class,$arra_collection))
      {
        $stri_src="images/unchecked_128x128.png";
        if($this->mixed_value->getChecked())
        { $stri_src="images/checked_128x128.png";}
         
         //$obj_img=new img($stri_src);
          //$obj_img->setStyle('width:20px;');
         $obj_hidden=$obj_converter->toHidden($this->mixed_value);
         $this->mixed_value=$obj_hidden;//remplacement de l'input par un font
      
      }  
    }
    
    if(is_array($this->mixed_value))
    {
      foreach($this->mixed_value as $stri_key=>$mixed_value)
      {
          if(is_object($mixed_value))
           {
            $stri_class=get_class($mixed_value); 
        
            if(in_array($stri_class, $arra_input)) //si on doit faire le changement
            {
                //$obj_font=$obj_converter->toFont($mixed_value);                  
                $obj_hidden=$obj_converter->toHidden($mixed_value); 
                $this->mixed_value[$stri_key]=$obj_hidden;//remplacement de l'input par un font
                //$this->mixed_value[]=$obj_hidden;
            }
            
            if($stri_class=="table")
            {
              $this->mixed_value->replaceInputByHidden();//remplacement récursif
            }
          }
      }
    }
  }
  
  
 /*************************************************************
  Permet d'appliquer une méthode à l'ensemble des contenus des td
  pour qui la méthode est applicable. 
  Ex :  applyMethode("setDisabled",array(true))
 
 Paramètres : $stri_methode : le nom de la méthode à appliquer
              $mixed_param1 : premier paramètre de la méthode à appliquer
              ... il n'y a pas de limite au nombre de paramètre de a méthode à appliquer
 Retour : array(mixed) : tableau des retours de l'application de la méthode
   
  **************************************************************/        
  public function applyMethode($stri_methode,$mixed_param1)
  {
    //- récupération de paramètres
    $arra_param=func_get_args();//récupération de la liste des paramètres
    $stri_methode=$arra_param[0];
    
    $arra_res=array();
    if(is_array($this->mixed_value))
    {
      
      foreach($this->mixed_value as $mixed_value)
      {
        $arra_parametre=array_merge(array($mixed_value), $arra_param);
    
         if(is_object($mixed_value))
          {                    
            $arra_one_res=call_user_func_array(array($this, 'applyMethodeOnObject'), $arra_parametre);
            if(count($arra_one_res)>0)
            {
             $arra_res=array_merge($arra_res,$arra_one_res);
            }
          }
      }
      
      return  $arra_res;
    }  
    
   
    if(is_object($this->mixed_value))
    {  
       $arra_param=array_merge(array($this->mixed_value), $arra_param);//ajout de l'objet à tester en paramètre        
      return call_user_func_array(array($this, 'applyMethodeOnObject'), $arra_param);
    }
    
    return $arra_res;
  }
  
    public function applyMethodeOnObject($obj_to_test,$stri_methode,$mixed_param1)
    {
  
    
       //- récupération de paramètres
      $arra_param=func_get_args();//récupération de la liste des paramètres
      $obj_to_test=$arra_param[0];     
      $stri_methode=$arra_param[1];
       unset($arra_param[0]);
      
      $arra_res=array();
              
      //- si l'objet peux appliquer la méthode
      if(method_exists($obj_to_test,$stri_methode))
      {       
        unset($arra_param[1]); //pour n'appeler qu'avec les paramètres dédié à la méthode à appliquer 
        $arra_res[]=call_user_func_array(array($obj_to_test, $stri_methode), $arra_param);
        return $arra_res;
      }
    
      //- si l'objet gère la récursivité
      if(method_exists($obj_to_test,'applyMethode'))
      {
        $arra_res[]=call_user_func_array(array($obj_to_test, 'applyMethode'), $arra_param);
        return $arra_res;
      }
  
      return $arra_res;
   }
    
}
?>
