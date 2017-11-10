<?php
/*******************************************************************************
Create Date : 28/05/2009
 ----------------------------------------------------------------------
 Class name : select_multiple
 Version : 1.0
 Author : R�my Soleillant
 Description : Permet de cr�er une liste d�roulante multiple
               Ce select est bas� sur le principe suivant :
                deux selects  sont utilis�s(un multiple et l'autre non) l'un cachant l'autre
                Le comportement obtenu est le m�me qu'un select avec l'option multiple � true
 
********************************************************************************/

include_once("select.class.php");
class select_multiple extends select {
   
   /*attribute***********************************************/
   public $arra_sauv=array();
  
   
   /* constructor***************************************************************/
   function __construct($name) {
       parent::__construct($name);
       $this->setMultiple(true);
   }  
  
  
   /*setter*********************************************************************/

  
  /*getter**********************************************************************/
 
  
  /*other method****************************************************************/    
  
   /*************************************************************
   Permet de cr�er le code html de l'objet.
  
   Param�tres : aucun
   Retour 	   : string : le code html 	
    
  **************************************************************/     
  public function htmlValue()
  {
   //cr�ation des id pour les diff�rents �l�ments
   $stri_unique_id1="id_".microtime();
   $stri_unique_id2="id_".microtime();
   $stri_unique_id_table="id_".microtime();
   $stri_unique_select_id1="id_".microtime();
   $stri_unique_select_id2="id_".microtime();
  
   //creation d'un premier select simple (sans l'option multiple � true), le second select (multiple=true) est l'objet courrant
   $obj_select1=new select();
   
   //on copie tous les attributs du select courrant afin que les deux objets r�agissent de la m�me mani�re
   $arra_attribute=get_object_vars ($obj_select1);
   foreach($arra_attribute as $stri_attribute=>$mixed_value)
   {$obj_select1->$stri_attribute=$this->$stri_attribute;}  
  
  //On red�fini les attributs qui pourrait poser probl�me
  $obj_select1->setMultiple(false); //Le premier select est un select simple
  $obj_select1->setId($stri_unique_select_id1);//Identifiant n�cessaire
  $obj_select1->stri_name=$stri_unique_select_id1; //Pour �viter les conflits de nom
  $obj_select1->int_size="";//IE est perdu si multiple=false et size!=""
  $this->stri_class="";//La classe est port�e par le premier select car c'est celui qui s'affiche par d�faut
  
  //on met un id au select principal s'il n'en a pas 
  $this->stri_id=($this->stri_id=="")?$stri_unique_select_id2:$this->stri_id;
  
  //recherche de la premi�re option selectionn�e
  $i=0;
  $bool_find=false;
  $int_nb_option=count($this->arra_option);
  $obj_option=$obj_select1->arra_option[0];
  while(($i<$int_nb_option)&&(!$bool_find))
  {
   //si on a trouv�e la premi�re options s�lectionn�e
   if($this->arra_option[$i]->getSelected())
   {
    $bool_find=true;
    $obj_option=$this->arra_option[$i];
   }
   $i++;
  }
  
  //Pour �viter de surcharger le premier select, on enl�ve (presque) toutes les options
  $obj_select1->arra_option=array();
  $obj_select1->arra_option[]=$obj_option; //on garde la premi�re option s�lectionn�e
  $obj_select1->arra_option[]=$this->arra_option[0];//on garde la premi�re option (option vide )
  
   
   //fonction javascript
   $obj_javascripter=new javascripter();
   $obj_javascripter->addFunction("
     /*
      R�cup�re la position r�elle d'un objet dans la page (en tenant compte de tous ses parents)
      IN 	: Obj => Javascript Object ; Prop => Offset voulu (offsetTop,offsetLeft,offsetBottom,offsetRight)
      OUT	: Num�rique => position r�elle d'un objet sur la page.
      */
      var derniere_mesure=-1;
      function GetDomOffset( Obj, Prop ) {
       var res;
      	if(Obj.tagName == 'BODY')
      	{return 0;}
        else
        {
        
         eval('res=Obj.'+Prop+';');
         if(res==derniere_mesure)
         {res=0;}
         else
         {derniere_mesure=res};
         return res+GetDomOffset(Obj.parentNode,Prop);
        }
       
      }");
   
    //javascript de d�claration de variable
    $stri_js ="var tr1=document.getElementById('".$stri_unique_id1."');"; //r�cup�ration du select
    $stri_js.="var tr2=document.getElementById('".$stri_unique_id2."');"; //r�cup�ration du select
    $stri_js.="var select1=document.getElementById('".$stri_unique_select_id1."');"; //r�cup�ration du select
    $stri_js.="var select2=document.getElementById('".$this->stri_id."');"; //r�cup�ration du select
    
   //javascript s'ex�cutant sur l'�v�nement onmouseover 
   $stri_js_over=$stri_js."tr2.style.display='';";//affichage de la liste de s�lection multiple
   $stri_js_over.="tr2.style.top=GetDomOffset( select1, 'offsetTop' )-2;";//on place la deuxi�me liste � la m�me haute que la premi�re, la liste multiple va cacher la liste simple
   $stri_js_over.="select2.style.width=select1.offsetWidth+0.9;";//Pour que la largeur de la liste d�roulante multiple couvre la liste simple
  
   //javascript s'�x�cutant sur le clic de la liste multiple
   $stri_js_click=$stri_js." var index=(select2.selectedIndex!=-1)?select2.selectedIndex:0;";//si on a selectionn� aucune option, on prend la premi�re
   $stri_js_click.="select1.options[0].text=select2.options[index].text;";//on actualise le libell� de la liste simple
   $stri_js_click.="select1.options[0].value=select2.options[index].value;";//on actualise la valeur de la liste simple
   $stri_js_click.="select1.selectedIndex=0;";//on selectionne la premi�re option de la liste simple
  
   //javascript s'�x�cutant sur l'�v�nement onmouseout 
   $stri_js_out=$stri_js."tr2.style.display='none';";//on cache la liste d�roulante multiple
   
   
   //Tableau html qui contient les deux select
   $obj_table=new table();
      $obj_tr1=$obj_table->addTr();//Le premier tr contiendra la liste d�roulante simple
      $obj_tr2=$obj_table->addTr();//Le deuxi�me tr contirendra la liste d�roulante multiple
       
    //configuration de la table html 
    $obj_table->setCellspacing(0);
    $obj_table->setCellpadding(0);
    $obj_table->setBorder(0);
    $obj_table->setWidth("100%");
   
    //configuration des tr 
     $obj_tr2->setStyle("display:none;position:absolute;z-index: 99");//Par d�faut ce tr est invisible
     $obj_tr2->setOnmouseover($stri_js_over);//On garde l'affichage du tr si on est dessus
     $obj_tr2->setOnmouseout($stri_js_out); //On cache le tr si on est plus dessus
     $obj_tr1->setOnmouseover($stri_js_over);//On affiche le deuxi�me tr sur passage du premier
   
    //configuration de la liste d�roulante multiple
    $this->stri_onclick.=$stri_js_click;//Pour tout d�selectionn� si on prend la premi�re option (option vide)
      
    //pose des id sur les diff�rents �l�ments de la table
    $obj_table->setId($stri_unique_id_table);
    $obj_tr1->setId($stri_unique_id1); 
    $obj_tr2->setId($stri_unique_id2);
    
    //on ne remplit les tr que maintenant, pour bien prendre en compte tous les attributs des selects     
    $obj_tr1->addTd($obj_select1->htmlValue());
    $obj_tr2->addTd(parent::htmlValue());
    
    return $obj_javascripter->javascriptValue().$obj_table->htmlValue();
  }
  
  public function addOption($value,$label,$stri_data_image="")
  {
    $option = parent::addOption($value,$label,$stri_data_image);
    $option->setSelected(false);
    return $option; 
  }
  
  
  
  
  
  
  
}

?>
