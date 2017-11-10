<?php
/*******************************************************************************
Create Date : 14/06/2006
 ----------------------------------------------------------------------
 Class name : text_and_select
 Version : 1.0
 Author : Rémy Soleillant
 Description : un champ de saisie couplé à une liste déroulante
********************************************************************************/
include_once("select.class.php");
include_once("text.class.php");
include_once("javascripter.class.php");
include_once("table.class.php");

class text_and_select {
   
   /*attribute***********************************************/
  
   protected $obj_select;
   protected $obj_text;
   protected $obj_javacripter;
   protected $stri_form_name;
   public $arra_sauv=array();
   
   /* constructor***************************************************************/
   function __construct($form_name,$obj_select,$obj_text) {
      $this->stri_form_name=$form_name;
      $this->obj_select=$obj_select;
       $this->obj_text=$obj_text;
       $this->setTextKeypress(); 
       $this->setSelectChange();                             
      $this->obj_javascripter=new javascripter();
     
      $this->obj_javascripter->addFunction("
      function detectTouche(e)
      {
        if(parseInt(navigator.appVersion) >=4)
        {
          if(navigator.appName == 'Netscape')
          { // Pour Netscape, firefox, ... 
            return String.fromCharCode(e.which);
          }
          else
          { // pour Internet Explorer
            return String.fromCharCode(e.keyCode);
          }
        }
      }");
     $this->obj_javascripter->addFunction("
      function find_nearest(chaine,chaine_inf,chaine_sup)
          {
           var car_chaine,car_inf,car_sup,i=0;
           car_chaine=chaine.substr(i,1);
           car_inf=chaine_inf.substr(i,1);
           car_sup=chaine_sup.substr(i,1);
           while((car_chaine==car_inf)&&(car_chaine==car_sup)&&(i<chaine.length))
            {
            i++;
            car_chaine=chaine.substr(i,1);
            car_inf=chaine_inf.substr(i,1);
            car_sup=chaine_sup.substr(i,1);
            }
           var ecart1=car_chaine.charCodeAt(0)-car_inf.charCodeAt(0);
           var ecart2=car_sup.charCodeAt(0)-car_chaine.charCodeAt(0);
           if(ecart1<ecart2)
           {return 1;}
           else
           {return 2;}
          }
     ");  
     
    $this->obj_javascripter->addFunction("
      function find_in_array(chaine,tableau)
        {
          var i=0;
          while((chaine>tableau[i])&&(i<tableau.length))
          {i++;}
          if(i==0)
          {return 0;}
          if(i==tableau.length)
          {return i-1;}
          if(find_nearest(chaine,tableau[i-1],tableau[i])==1)
          {return i-1;}
          else
          {return i;}
        }");
      $this->obj_javascripter->addFunction("
      function find_option(obj0,obj,e)
          {//alert('fontion 1 ok');
           var chaine,i,option;
           var tableau=new Array();
           chaine=obj0.value+detectTouche(e);
           chaine=chaine.toUpperCase();
           for(i=0;i<obj.options.length;i++)
           {tableau[i]=obj.options[i].value;}
           
           option=find_in_array(chaine,tableau);
           obj.selectedIndex=option;
          }");
   
   }
  
  
  
   /*setter*********************************************************************/
  public function setSelect($obj)
  {
    if(is_object($obj))
    {
     if(get_class($obj)=="select")
    $this->obj_select=$obj;
    }  
  }
  
  public function setText($obj)
  {
    if(is_object($obj))
    {
     if(get_class($obj)=="text")
    $this->obj_text=$obj;
    $this->setTextKeypress();
    }
  }
  
  private function setTextKeypress()
  {
   $this->obj_text->setOnkeypress("find_option(this,document.".
                                    $this->stri_form_name.".".
                                    $this->obj_select->getName().",event)");
   // $this->obj_text->setOnkeypress("alert('touche pressé')");
   $this->obj_text->setStyle("width:146px");
  }
  
  public function setSelectChange()
  {
   $this->obj_select->setStyle("width:146px");
  }
  /*getter**********************************************************************/
  public function getSelect()
  {return $this->obj_select;}
  
  public function getText()
  {return $this->obj_text;}
  
  public function getJavascripter()
  {return $this->obj_javascripter;}
  /*other method****************************************************************/
  public function htmlValue()
  {
   
   $obj_html_table=new table();
   $obj_tr1=new tr();
   $obj_tr1->addTd($this->obj_text->htmlValue());
   $obj_tr2=new tr();
   $obj_tr2->addTd($this->obj_select->htmlValue());
   $obj_html_table->insertTr($obj_tr1);
   $obj_html_table->insertTr($obj_tr2);
   
   $obj_html_table->setCellspacing(0);
   $obj_html_table->setCellpadding(0);
   $obj_html_table->setBorder(0);
   $stri_res=$this->obj_javascripter->javascriptValue().$obj_html_table->htmlValue();
   return $stri_res;
  }
  
}

?>
