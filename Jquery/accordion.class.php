<?php
/*******************************************************************************
Create Date : 03/11/2011
 ----------------------------------------------------------------------
 Class name : accordion
 Version : 1.0
 Author : Lucie Prost
 Description : élément jquery "accordion"
 Permet de créer les éléments accordéons d'une liste d'accordéon. 
 
 (Pour modifier les css et/ou parametre "width" placer dans une div et modifier le css de la div)
********************************************************************************/
class accordion extends serialisable {
   
   //**** attribute ************************************************************
   
   protected $stri_name= "";
   protected $arra_accordion=null; 
   protected $bool_collapsible='true';      
   public $arra_sauv;

  
  //**** constructor ***********************************************************
  function __construct($stri_name) 
  { 
    $this->stri_name=$stri_name;
  }
 
  //**** setter ****************************************************************
  public function setName($value){$this->stri_name=$value;}
  public function setCollapsible($value){$this->bool_collapsible=$value;}

  
  //**** getter ****************************************************************
   public function getName(){return $this->stri_name;} 
   public function getCollapsible(){return $this->bool_collapsible;}

   
   //**** public method *********************************************************
  
  // Ajout d'un accordion, paramertes de construction de l'accordion :
  // - name : stri, nom de l'accordion
  // - id : stri, id de l'accordion
  // - content : stri, contenu de l'onglet
  public function addElement($name, $id, $content)
  {
    $stri_element = "<h3><a href='#'>".$name."</a></h3>";
    
    $stri_element .= '<div style="box-shadow: 0px 0px 3px rgba(0,0,0,0.7) inset;" id="'.$id.'">'.$content.'</div>';

    $this->arra_accordion[] = $stri_element;
    
    return $stri_element;
  }
  
  
  public function jqueryValue()
  {       
      $stri_jquery = "<script>
        //déclaration d'accordéon
      	$(function() {
      		$('#".$this->stri_name."').accordion({
                        active: false,
                        event: 'click',
                        heightStyle: 'content',
                        clearStyle: true,
                        collapsible: ".$this->bool_collapsible."       //ajout Romain le 08-02-13  || Permet de réduire un h3 de l'accordion sur click
                        

      		});
           
                
      	});
      </script>";
      
      return $stri_jquery;
  }
  
  public function htmlValue($bool_jquery=true)
  {
      $stri_res = "<div id='".$this->stri_name."' class='accordion' >";
      if($this->arra_accordion){
        foreach($this->arra_accordion as $element){
            $stri_res .= $element;
        }
      }
       
       
      $stri_res .= "</div>";
       

      if ($bool_jquery==true)
      {
            return $this->jqueryValue().$stri_res;
      }
      else
      {
            return $stri_res;
      }
      
  }
  
  //**** method for serialization **********************************************
  public function __sleep() 
  {
    //sérialisation de la classe 
    $this->arra_sauv['name']= $this->stri_name;

    return array('arra_sauv');
  }
  
  public function __wakeup() 
  {
    //désérialisation de la classe 

    $this->stri_name= $this->arra_sauv['name'];

    $this->arra_sauv = array();
  } 
}

?>
