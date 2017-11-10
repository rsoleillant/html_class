<?php
/*******************************************************************************
Create Date  : 19/11/2015
 ----------------------------------------------------------------------
 Class name  : ma_classe
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Représentation d'un graphe bar dans la bibliothèque rgraph
 
********************************************************************************/
class rgraph_bar extends rgraph{
   
//**** Attributs ****************************************************************

//**** Methodes *****************************************************************

//**** Setter ****************************************************************
	
  
//**** Getter ****************************************************************   

//*** 01 Constructor **********************************************************
	
	/*******************************************************************************
	* Constructeur principal
	* 
	* Parametres : 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function  __construct($stri_id="",$arra_label="",$arra_data="") 
	{ 
	  parent::__construct($stri_id,$arra_label,$arra_data); 
	}
	

//*** 02 Autres méthodes ******************************************************
	
	/*******************************************************************************
	* Permet de créer les éléments dom html et js permettant l'affichage du graphe
	* Parametres : aucun
	* Retour :  string                              
	*******************************************************************************/
  public function htmlValue()
  {
   
   //- linéarisation des infos de base
   $stri_label=$this->lineariseArray($this->arra_label);
   $stri_data=$this->lineariseArray($this->arra_data);   
      
   //- linéraisation des autres propriétés
   $stri_other_properties=$this->lineariseProperties();
      
   //- construction de l'instruction d'affichage
   $stri_draw_instruction=$this->stri_drawing_methode."()";
   //-- dessin par une animation
   if($this->stri_drawing_methode!="Draw")
   {
     $stri_draw_instruction=$this->stri_drawing_methode."({frames:".$this->int_nb_frame."})";
   } 
   
   //- construction du js  
   $obj_javascripter=new javascripter();
    $obj_javascripter->addFunction('

            //- Initialisation du graphe
            var obj_graphe = new RGraph.Bar({id:"'.$this->stri_id.'",data:'.$stri_data.'});          
                obj_graphe.Set("chart.labels", '.$stri_label.'); 
            
             
            //- définition autres propriétés 
             '.$stri_other_properties.'
          
            //- lancement du dessin du graphe          
            obj_graphe.'.$stri_draw_instruction.' ;   
                                              
      ');       
     
   
  	 //- construction du résultat de retour
     $stri_js=$obj_javascripter->javascriptValue(); 
     $stri_res=' <canvas id="'.$this->stri_id.'" width="'.$this->int_width.'" height="'. $this->int_height.'" style="'.$this->stri_canva_style.'">[No canvas support]</canvas>';
      /*echo "<pre>";
    var_dump(htmlentities($stri_js));
    echo "</pre>";  */
     return  $stri_res.$stri_js;
  	}
}

?>
