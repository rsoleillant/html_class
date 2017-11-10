<?php
/*******************************************************************************
Create Date : 03/11/2011
 ----------------------------------------------------------------------
 Class name : onglet_list
 Version : 1.0
 Author : Lucie Prost
 Description : élément jquery "onglet_list"  (ensemble d'onglets)
 utilisé pour générer un ou plusieurs onglets
 appelle la classe onglet_jquery avec la méthode addOnglet
 (Pour modifier les css et/ou parametre "width" placer dans une div et modifier le css de la div)
********************************************************************************/
class onglet_list extends serialisable {
   
   //**** attribute ************************************************************
   
   protected $stri_name="";
   protected $arra_onglet=null;
   protected $int_width="";
   protected $stri_tabs_param;  //Les paramètres à passer à la création des onglets
   public $arra_sauv;

  
  //**** constructor ***********************************************************
  function __construct($stri_name) 
  { 
    $this->stri_name=$stri_name;
   
  }
 
  //**** setter ****************************************************************
  public function setName($value){$this->stri_name=$value;}
  public function setWidth($value){$this->int_width=$value;}
  public function setTabsParam($value){$this->stri_tabs_param=$value;}

  //**** getter ****************************************************************
   public function getName(){return $this->stri_name;} 
   public function getWidth(){return $this->int_width;}
   public function getTabsParam(){return $this->stri_tabs_param;}

  //**** public method *********************************************************
  
  // Ajout d'un onglet:
  // parametres de construction de l'onglet :
  // - name : stri, nom de l'onglet
  // - id : int, id de l'onglet
  // - content : stri, contenu de l'onglet
  public function addOnglet($name, $id, $content)
  {
    $obj_onglet = new onglet_jquery($name, $id, $content, $this->stri_name);
    $this->arra_onglet[] = $obj_onglet;
    return $obj_onglet;
  }
  
  //Permet d'ajouter un onglet sous forme objet
  public function addObjOnglet(onglet_jquery $obj_onglet)
  {
     $this->arra_onglet[] = $obj_onglet;
     $obj_onglet->setList_name($this->stri_name);
  }
  
  //script jquery permettant l'interractivité des onglets
  public function jqueryValue()
  {
      $stri_jquery = "<script>                 
          
                  //déclaration d'onglets
                  
                
                
                  function initTabs(obj_div)
                  {
                      obj_div.tabs(".$this->stri_tabs_param.");
                  }
                  
                  initTabs($('#".$this->stri_name."_id'));

        
      </script>";
   /*   $stri_jquery = "<script>
          


$(function() {

        //déclaration d'onglets
         $('.menu_jq').ready(function() {
             initTabs($('.menu_jq'));
        });
      
        function initTabs(obj_div)
        {
           // obj_div.tabs(".$this->stri_tabs_param.");
        }
     
}); 
        
      </script>";   */
      
      
      return $stri_jquery;
  }
 
 //htmlValue, retourne le html de la liste d'onglets + le script jquery 
  public function htmlValue()
  {     //onmouseover='initTabs($(this));'
      //$stri_res = "<div class='menu_jq'  id='magasin'>";
         $stri_res = "<div class='menu_jq'  id='".$this->stri_name."_id'>";
       
      if($this->arra_onglet) {
        $stri_res .= "<ul>";
        foreach($this->arra_onglet as $onglet){
            if($this->getWidth() != ""){
              $stri_res .= "<li style='width:".$this->getWidth()."px; '>".$onglet->htmlValue()."</li>";
            } else {
              $stri_res .= "<li>".$onglet->htmlValue()."</li>";
            }
        }
        $stri_res .= "</ul>"; 
        
      }
      
      foreach($this->arra_onglet as $onglet){
        $stri_res .= "<div id='".$this->stri_name."-".$onglet->getId()."'>".$onglet->getContent()."</div>";
      } 
      
      $stri_res .= "</div>";
      
       
      
      return $stri_res.$this->jqueryValue();
  }
  

}

?>
