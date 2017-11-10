<?php
/*******************************************************************************
Create Date : 05/12/2007
 ------------------------------------------------------------------------------
 Class name : html_correcter
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de corriger du html au niveau des balise fermante et ouvrante
*******************************************************************************/

class html_correcter
{
  //**** attribute *************************************************************
  protected $stri_html;       //le html à corriger
  protected $arra_pile;       //la pile des balises ouvertes
  

  //**** constructor ***********************************************************
  function __construct($stri_html)
  {
    $this->stri_html=$stri_html;
  }
  
  //**** setter ****************************************************************
  public function setHtml($value){$this->stri_html=$value;}

  
  //**** getter ****************************************************************
  public function getHtml(){return $this->stri_html;}

  //**** other method **********************************************************
  
 /*************************************************************
 * Permet de corrigé le html
 *  
 *  
 * Parametres : aucun
 * retour : string : le code html corrigé
 *                    
 **************************************************************/ 
  public function correctHtml()
  {
    $bool_end=false;
    $int_cur=0;//un  curseur pour parcourir le texte
    
    $int_nb_element=strlen($this->stri_html);
    
    $bool_ouverture_balise=false;
    $bool_fermeture_balise=false;
    
      
    while($int_cur<$int_nb_element)
    { 
     $stri_car=$this->stri_html{$int_cur};
    // echo htmlentities($stri_car,ENT_COMPAT, 'ISO-8859-1')."<br />";
     if($stri_car=="<")
     {$bool_ouverture_balise=true;}
     
     if($stri_car==">")
     {$bool_fermeture_balise=true;}
     
    /* if($stri_car=="/")
     {$bool_fermeture_balise=true;} */
    
     if($bool_ouverture_balise)
     {
      $stri_balise.=$stri_car;
     }
     
     if($bool_fermeture_balise)
     {
       $bool_ouverture_balise=false;
       $bool_fermeture_balise=false;
      
       $arra_data=$this->analyseBalise($stri_balise);
       echo "balise ".htmlentities($stri_balise,ENT_COMPAT, 'ISO-8859-1')."<br />";
      
       switch($arra_data['type'])
       {
        case "ouvrante":
          //echo "j'empile<br />";
          if($arra_data['tag']=="")
          {
            echo "<pre>null";
            var_dump(htmlentities($stri_car,ENT_COMPAT, 'ISO-8859-1'));
            echo "</pre>";
          }
          $this->arra_pile[]=$arra_data['tag'];
        break;
        case "fermante":
         // echo "je dépile <br />";
          $this->depile($arra_data['tag']);
        break;
        default:
        //echo "j'ignore<br />";
       } 
      
       $stri_balise="";
     }
     
     $int_cur++;
    } 
             
    return $this->stri_html;
  }
  
  /*************************************************************
 * Permet d'analyser une balise
 *  
 *  
 * Parametres : aucun
 * retour : array['fermante'] true/false : pour connaitre s'il s'agit d'une balise fermante ou non
 *               ['tag'] : pour connaitre le tag de la balise (ex table, td ...) 
 *                    
 **************************************************************/ 
  public function analyseBalise($stri_balise)
  { 
    $arra_match=array();
    preg_match("/< *(\/)? *([[:alpha:]_]+)/", $stri_balise, $arra_match);
    
    $arra_res['type']=($arra_match[1]=="/")?"fermante":"ouvrante";
    $arra_res['tag']=$arra_match[2];
    /*echo "balise ".htmlentities($stri_balise,ENT_COMPAT, 'ISO-8859-1')."<br />";
    echo "<pre>";
    var_dump($arra_res);
    echo "</pre>";  */
    $arra_auto_fermante=array("img","input");
  
    if(in_array($arra_res['tag'], $arra_auto_fermante))
    {   // echo "balise ".htmlentities($stri_balise,ENT_COMPAT, 'ISO-8859-1')." est autoformante<br />";
     
     $arra_res['type']="autofermante";
    }
    
     
    return $arra_res;
  }
  
/*************************************************************
 * Permet de dépiler une balise et de détecter s'il y a une erreur
 * de dépilage (balise fermé dans le mauvaise ordre) 
 *  
 *  
 * Parametres : aucun
 * retour : aucun
 *                    
 **************************************************************/ 
  protected function depile($stri_balise)
  { 
   $int_nb_element=count($this->arra_pile);
   
   if($this->arra_pile[$int_nb_element-1]==$stri_balise)//si pas d'erreur de dépilage
   {
    array_pop($this->arra_pile);
    return;
   }
    echo "<pre>";
   var_dump($this->arra_pile);
   echo "</pre>";
   echo "erreur de dépilage, ".$this->arra_pile[$int_nb_element-1]." $stri_balise<br />";
  }
}
?>
