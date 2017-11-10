<?php
/*******************************************************************************
Create Date : 20/08/2009
 ----------------------------------------------------------------------
 Class name : select_bascule
 Version : 1.0
 Author : Alex Escot
 Description : principe 2 select, permet de transferer en javascript les informations dans l'autre select
               ou inversement de les enelever
                
********************************************************************************/

include_once("select.class.php");
include_once("table.class.php");

class select_bascule {

  /*attribute*******************************************************************/
  public $obj_select1;
  public $obj_select2;  
  public $bool_table_haut_bas = true; // pour l'inhiber le passer à false
  public $titre_select;
  /* constructor****************************************************************/
  function __construct($name_select1,$name_select2) {
    $this->obj_select1 = new easy_select($name_select1);
      $this->obj_select1->setOndblclick("ajout();");    
    
    $stri_crochet=(strpos($name_select2, '[]')!==false)?"":"[]";
    $this->obj_select2 = new select($name_select2.$stri_crochet);
       $this->obj_select2->setOndblclick("suppr();");
       
    //par défault id = nom
    $this->obj_select1->setId($name_select1);
    $this->obj_select2->setId($name_select2);
    
    //par default size=10
    $this->setSize(10);
    
    //largeur par défault
    $this->setStyle("width: 255px");
    
    //multiselection par default
    $this->setMultiple(true);
  }
  
  /*setter**********************************************************************/  
  
  /*getter**********************************************************************/
  public function getSelect1(){return $this->obj_select1;}
  public function getSelect2(){return $this->obj_select2;}
  public function getTableHautBas(){return $this->bool_table_haut_bas;}

  /*private method**************************************************************/
  public function addOptionSelect1($valeur,$name) {
    $obj_option=$this->obj_select1->addoption($valeur,$name);     
    
  }
  
  public function addOptionSelect2($valeur,$name) {
    $this->obj_select2->addoption($valeur,$name);
  }
  
  public function setSize($value) {
    $this->obj_select1->setSize($value);
    $this->obj_select2->setSize($value);
  }
  
  public function setStyle($value) {
    $this->obj_select1->setStyle($value);
    $this->obj_select2->setStyle($value);
  }
  
  public function setMultiple($bool) {
    $this->obj_select1->setMultiple($bool);
    $this->obj_select2->setMultiple($bool);
  }
  
  public function setIdSelect2($value) {
    $this->obj_select2->setId($value);    
  }  
  
  public function setIdSelect1($value) {
    $this->obj_select1->setId($value);    
  } 
  public function setTitreSelect($value) {
    $this->titre_select=$value;}
   /*************************************************************
   Permet de construire les options du premier select grâce à 
   une requête SQL
  
   Paramètres : string : la requete sql
                int : le numéro de colonne qui sert à trouver la valeur des options
                int : le numéro de colonne qui sert à trouver le libellé des options
                obj : un objet pour appliquer une méthode à l'ensemble des libellés
                string : le nom de la méthode à appliquer ou le nom d'un fonction php
   Retour 	   : string : le code html 	
    
  **************************************************************/     
  public function makeSelect1BySql($stri_sql,$int_col_value=0,$int_col_label=0,$obj="",$func="") {
  
   $obj_query=new querry_select($stri_sql);
   return $this->obj_select1->makeQuerryToSelect($obj_query,$int_col_value,$int_col_label,$obj,$func);
   
  }
  
  
  /*************************************************************
   ANCIENNE METHODE QUI Permet de créer le code html de l'objet.
  
   Paramètres : aucun
   Retour 	   : string : le code html 	
    
  **************************************************************/     
  public function htmlValue2() {
    //javascript
    $javascriptValue = $this->createJavascript();
    
    $obj_table=new table();
    $obj_table->setWidth('100%');
    $obj_table->setBorder(0);
        
    //création des tableaux d'images
    $img_ajout=new img("images/module/fleche_add.gif");
    $img_ajout->setOnclick('ajout()');
    $img_ajout->setId('img_ajout');
    $img_suppr=new img("images/module/fleche_del.gif");
    $img_suppr->setOnclick('suppr()');
    $img_suppr->setId('img_suppr');
    $img_haut=new img("images/module/ma_fleche_haut.gif");
    $img_haut->setOnclick('haut()');
    $img_bas=new img("images/module/ma_fleche_bas.gif");
    $img_bas->setOnclick('bas()');
    
    $table_img_haut_bas=new table();
    $table_img_haut_bas_tr=new tr();
    $table_img_haut_bas_tr->addTd($img_haut->htmlValue());
    $table_img_haut_bas_tr1=new tr();
    $table_img_haut_bas_tr1->addTd($img_bas->htmlValue());

    $table_img_haut_bas->insertTr($table_img_haut_bas_tr);
    $table_img_haut_bas->insertTr($table_img_haut_bas_tr1);
    $table_img_haut_bas->setBorder('0');

    $table_img=new table();
    $table_img_tr=new tr();
    $table_img_tr->addTd($img_ajout->htmlValue());
    $table_img_tr1=new tr();
    $table_img_tr1->addTd($img_suppr->htmlValue());

    $table_img->insertTr($table_img_tr);
    $table_img->insertTr($table_img_tr1);
    $table_img->setBorder(0);    
    
    //tout selectionner , deselectionner
    $o_font_selectionner=new a("#",_TH_SELECT_ALL);
    $o_font_selectionner->setId("font_select_all");
    $o_font_selectionner->setOnclick("javascript:selectall(true);");
    
    $o_font_deselectionner=new a("#",_TH_DESELECT_ALL);
    $o_font_deselectionner->setId("font_deselect_all");
    $o_font_deselectionner->setOnclick("javascript:selectall(false);");
    $o_font_deselectionner->setStyle("display:none");
    
    $obj_tr = new tr();
    $td = $obj_tr->addTd($o_font_selectionner->htmlValue().$o_font_deselectionner->htmlValue());
    $obj_table->insertTr($obj_tr);   
    
    //premier select
    $obj_tr = new tr();
    $td = $obj_tr->addTd($this->obj_select1->htmlValue());
    $td->setAlign("left");
    $td->setWidth("20%");
    
    //image de transfert  
    $td = $obj_tr->addTd($table_img->htmlValue());    
    $td->setAlign('center');
    $td->setWidth("15%");
    
    //second select
    $td=$obj_tr->addTd($this->obj_select2->htmlValue());
    $td->setWidth("30%");
    $td->setValign("bottom");
    
    //tableau image pour ordonner les informations dans le second select
    $td=$obj_tr->addTd($table_img_haut_bas->htmlValue());      
    $td->setAlign('left');
    
    $obj_table->insertTr($obj_tr);    
    return $obj_table->htmlValue().$javascriptValue;  
  
  }
  
  //méthode qui crée le javascript nécessaire
  public function createJavascript() {
    $obj_js=new javascripter();
    $obj_js->addFunction("
    
    function ajout()
      {
        obj_select_1=document.getElementById('".$this->obj_select1->getId()."');
        obj_select_2 =document.getElementById('".$this->obj_select2->getId()."');
      
        
        if(obj_select_1.multiple == false)
        {
          index=obj_select_1.selectedIndex        
          value= obj_select_1.options[index].value;
          name= obj_select_1.options[index].text;        
          obj_select_2.options[obj_select_2.options.length] = new Option(name,value);
        }
        else        
        {
          
          for(var i=0; i< obj_select_1.options.length; i++)
          {
            if(obj_select_1.options[i].selected == true)
            {
              value = obj_select_1.options[i].value;
              name = obj_select_1.options[i].text;
              obj_select_2.options[obj_select_2.options.length] = new Option(name,value);              
            }
          }         
        }        
      }      
      
      
      function suppr()
      {
        var compteur = 0;
        obj_select_2 = document.getElementById('".$this->obj_select2->getId()."');
        for (var i=0; i < obj_select_2.options.length; i++)
        {
          if(obj_select_2.options[i].selected == true)
          {
            compteur++;
            obj_select_2.removeChild( obj_select_2.options[i]);
            i--;
          }
        }
        //suppression sans selection
        if(compteur == 0)
        {
          obj_select_2.removeChild(obj_select_2.options[0])
        }       
      }
     
     
     function haut()
      {
        obj_select_2 =document.getElementById('".$this->obj_select2->getId()."');
        index=obj_select_2.selectedIndex;
        if(index>0){
        value_tampon=obj_select_2.options[index-1].value;
        name_tampon= obj_select_2.options[index-1].text;
        obj_select_2.options[index-1] = new Option((obj_select_2.options[index].text),(obj_select_2.options[index].value));
        obj_select_2.options[index] = new Option(name_tampon,value_tampon);   
        obj_select_2.options[index-1].selected=true;
        }
      }
      
      
     function bas()
      {
        obj_select_2 =document.getElementById('".$this->obj_select2->getId()."');
        index=obj_select_2.selectedIndex;
        if(index<obj_select_2.length-1){ 
        value_tampon=obj_select_2.options[index+1].value;
        name_tampon= obj_select_2.options[index+1].text;
        obj_select_2.options[index+1] = new Option((obj_select_2.options[index].text),(obj_select_2.options[index].value));
        obj_select_2.options[index] = new Option(name_tampon,value_tampon);
        obj_select_2.options[index+1].selected=true;
        }    
      }
      
      
    function selectall(bool)
    {
      obj_font_selectionner = document.getElementById('font_select_all');
      obj_font_deselectionner = document.getElementById('font_deselect_all');
      if(bool == true)
      {
        obj_select_1=document.getElementById('".$this->obj_select1->getId()."');
        var nb = obj_select_1.options.length;
        for(i=0;i<nb;i++)
        {
          obj_select_1.options[i].selected=bool;
        }
        ajout();
        obj_font_selectionner.style.display = 'none';
        obj_font_deselectionner.style.display = '';
      }
      else
      {
        obj_select_2 =document.getElementById('".$this->obj_select2->getId()."');
        var nb = obj_select_2.options.length;
        for(i=0;i<nb;i++)
        {
          obj_select_2.options[i].selected=true;
        }
        suppr();
        obj_font_selectionner.style.display = '';
        obj_font_deselectionner.style.display = 'none';
      }
    }
      
    function selectAllToSend()
    {
       obj_select_2 =document.getElementById('".$this->obj_select2->getId()."');
      for (i=0; i<obj_select_2.options.length; i++) { 
        obj_select_2.options[i].selected = true;
        }

    }  
    ".'
     //RS : ajout du comportement de sélection automatique sur submit
    
     $("#'.$this->obj_select2->getId().'").parents("form").submit(function() {
     $("#'.$this->obj_select2->getId().' option").attr("selected",true);
    });
     
    '
    );
    
      return $obj_js->javascriptValue();
      
  }
  
  
 public function select1Html()
 { 
    $obj_table = new table();
    $obj_tr = $obj_table->addTr();
    $td = $obj_tr->addTd($this->obj_select1->htmlValue());

  return $obj_table->htmlValue();
 } 
 
 public function select2Html()
 { 
    $obj_table = new table();
    $obj_tr = $obj_table->addTr();

  return $obj_table->htmlValue();
 }  

 public function transfertHtml()
 {
    //création des tableaux d'images
    $img_ajout=new img("images/module/fleche_add.gif");
    $img_ajout->setOnclick('ajout()');
    $img_ajout->setId('img_ajout');
    $img_suppr=new img("images/module/fleche_del.gif");
    $img_suppr->setOnclick('suppr()');
    $img_suppr->setId('img_suppr');


    $table_img=new table();
    $table_img_tr=new tr();
    $table_img_tr->addTd($img_ajout->htmlValue());
    $table_img_tr1=new tr();
    $table_img_tr1->addTd($img_suppr->htmlValue());

    $table_img->insertTr($table_img_tr);
    $table_img->insertTr($table_img_tr1);
    $table_img->setBorder(0);  
 return $table_img->htmlValue(); 
 }
public function hautbasHtml()
{
    $img_haut=new img("images/module/ma_fleche_haut.gif");
    $img_haut->setOnclick('haut()');
    $img_bas=new img("images/module/ma_fleche_bas.gif");
    $img_bas->setOnclick('bas()');
    
    $table_img_haut_bas=new table();
    $table_img_haut_bas_tr=new tr();
    $table_img_haut_bas_tr->addTd($img_haut->htmlValue());
    $table_img_haut_bas_tr1=new tr();
    $table_img_haut_bas_tr1->addTd($img_bas->htmlValue());

    $table_img_haut_bas->insertTr($table_img_haut_bas_tr);
    $table_img_haut_bas->insertTr($table_img_haut_bas_tr1);
    $table_img_haut_bas->setBorder('0');

   return  $table_img_haut_bas->htmlValue();
}

public function selectbasculejavaHtml()
{
  $javascriptValue = $this->createJavascript();
  return $javascriptValue;
}
public function htmlValue()
{
  $java = $this->selectbasculejavaHtml();
  $obj_table = new table();
  
    $obj_tr = $obj_table->addTr();
    
      $obj_tr->addTd($this->selectionallornotHtml());
  
    
    $obj_tr = $obj_table->addTr();
  
      $td1=$obj_tr->addTd($this->obj_select1->htmlValue());
        $td1->setAlign("left");
        $td1->setWidth("20%"); 
      
      $td2=$obj_tr->addTd($this->transfertHtml());
        $td2->setAlign('center');
        $td2->setWidth("15%");
      
      $td4=$obj_tr->addTd($this->obj_select2->htmlValue());
        $td4->setWidth("30%");
        $td4->setValign("bottom");
      
      $td3=$obj_tr->addTd($this->hautbasHtml());
        $td3->setAlign('left');
  
  $obj_table->setWidth('100%');
  return $obj_table->htmlValue().$java;  
}

public function selectionallornotHtml()
{
    //tout selectionner , deselectionner
    $o_font_selectionner=new a("#",_TH_SELECT_ALL);
    $o_font_selectionner->setId("font_select_all");
    $o_font_selectionner->setOnclick("javascript:selectall(true);");
    
    $o_font_deselectionner=new a("#",_TH_DESELECT_ALL);
    $o_font_deselectionner->setId("font_deselect_all");
    $o_font_deselectionner->setOnclick("javascript:selectall(false);");
    $o_font_deselectionner->setStyle("display:none");

    return $o_font_selectionner->htmlValue().$o_font_deselectionner->htmlValue();
}


  
}


?>
