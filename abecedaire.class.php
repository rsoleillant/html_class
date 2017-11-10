<?php
/*******************************************************************************
Create Date : 06/08/2012
 ----------------------------------------------------------------------
 Class name : abecedaire
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de générer un abécédaire transmettant la lettre sur laquelle est cliqué dans
                $_POST['abc'].
 
********************************************************************************/
class abecedaire{
   
   //**** attribute ************************************************************
   
  
  //**** constructor ***********************************************************
 /*************************************************************
 *
 * parametres : 
 * retour : objet de la classe 
 *                        
 **************************************************************/    
  function __construct() 
  { 
    
  }
 
  //**** setter ****************************************************************
 
  
  //**** getter ****************************************************************
   
   
   //**** public method *********************************************************
  
  
 /*************************************************************
 *
 * parametres : string : l'identifiant de l'instance
 * retour : objet de la classe calendrier_projet   
 *                        
 **************************************************************/    
  public function htmlValue()
  {
     //- construction d'un abécédaire
    $obj_table_abc=new table();
      $obj_tr_abc=$obj_table_abc->addTr();
      for($i=65;$i<91;$i++)
      {
        $stri_lettre=chr($i);
        $obj_a=new a("#",$stri_lettre);
          $obj_a->setOnClick("sendLetter($(this));");
          $obj_a->setClass($stri_lettre);
        $obj_tr_abc->addTd($obj_a);
      }
       //ajout de tous les résultat
       $obj_a=new a("#",_TOUS);
          $obj_a->setOnClick("sendLetter($(this));");
          $obj_a->setClass("%");
       $obj_tr_abc->addTd($obj_a);
     
     $obj_javascripter=new javascripter();    
     $obj_javascripter->addFunction("
      function  sendLetter(obj_a)
      {
        //création du formulaire
        var form=document.createElement('form');
            form.method='post';
        //rattachement du formulaire
        $('body').append(form);        
        //création de donnée
        var input=document.createElement('input');
            input.name='abc';
            input.value=obj_a.attr('class');
        var input2=document.createElement('input');
            input2.name='actionLoad_x';
            input2.value='abecedaire';
        //rattachement de la donnée au formulaire
        $(form).append(input);
        $(form).append(input2);
        //envoi du formulaire
        form.submit();
      }
     "); 
       
    return    $obj_javascripter->javascriptValue().$obj_table_abc->htmlValue();
        
  }

 
}

?>
