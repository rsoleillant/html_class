<?php
/*******************************************************************************
Create Date : 10/11/2011
 ----------------------------------------------------------------------
 Class name : slider_plage
 Version : 1.2
 Author : Lucie Prost & Rémy Soleillant
  
 Description : élément jquery "slider_plage" 
(double curseur, permet de sélectionner une plage de données)

********************************************************************************/
class slider_plage extends serialisable {
   
   //**** attribute ************************************************************
    
   protected $stri_id="";                    // id du slider
   protected $stri_description="";           // description affichée
   protected $stri_name="";                  // nom du slider
   protected $int_step="5";                  // Pas entre chaque position du curseur
   protected $int_min="0";                   // Valeur minimum du slider
   protected $int_max="100";                 // Valeur maximum du slider
   protected $int_default_value_min="25";    // Valeur minimale par défaut
   protected $int_default_value_max="65";    // Valeur maximale par défaut
   protected $stri_unite="";                 // Unité du curseur ("j" => jour, "h" => heure)
   
   protected $obj_hidden1;                   // Le champ pour transmettre la première valeur
   protected $obj_hidden2;                   // Le champ pour transmettre la deuxième valeur 
   protected $obj_num_hidden1;               // Le premier hidden en valeur numérique
   protected $obj_num_hidden2;               // Le deuxième hidden en valeur numérique
   
   protected $bool_disabled;                  //Pour désactiver les champs     
   public $arra_sauv;

   //**** attribute mise en forme **********************************************
   protected $stri_style_div;                 //Le style qui s'applique à la div du slider
   protected $stri_input_position;            //La position de l'input par rapport au slider [top,left,right,bottom] 
  
  //**** constructor ***********************************************************
   /*************************************************************
  * Constructeur
  * 
  *Parametres :  string : le nom du premier champ
  *              string : la valeur du premier champ
  *              string : le nom du deuxième champ
  *              string : la valeur du deuxième champ
  *              string : la description         
  *Retour : objet de class slider_plage                        
  **************************************************************/        
  function __construct($stri_name1,$stri_val1,$stri_name2,$stri_val2,$stri_description) 
  { 
    //- valeur par défaut
    $this->stri_style_div='width:300px;';
    $this->stri_input_position="top";
    
    //détection automatique du format des données
    if(strpos($stri_val1, ":")!==false)//si : dans les données, il s'agit d'heure
    {
     $this->constructPourHeure($stri_name1,$stri_val1,$stri_name2,$stri_val2,$stri_description); 
    }
    
    if(is_numeric($stri_val1))//si les données sont des jours
    {      
      $this->constructPourJour($stri_name1,$stri_val1,$stri_name2,$stri_val2,$stri_description);       
    } 
    
    $this->bool_disabled=false;
  }
  
 /*************************************************************
  * Pour initialiser complètement l'objet
  *                        
  **************************************************************/        
  function init($stri_id, $stri_name, $stri_description, $int_step, $int_min,$int_max, $int_default_value_min, $int_default_value_max, $stri_unite) 
  { 
    $this->stri_id=$stri_id;
    $this->stri_name=$stri_name;
    $this->stri_description=$stri_description;
    $this->int_step = $int_step;
    $this->int_min = $int_min;
    $this->int_max = $int_max;
    $this->int_default_value_min = $int_default_value_min;
    $this->int_default_value_max = $int_default_value_max; 
    $this->stri_unite = $stri_unite; 
  }
 
  /*************************************************************
  * Pour initialiser l'objet sur une plage de jour
  * Parametres : string : le nom du premier champ
  *              string : la valeur du premier champ
  *              string : le nom du deuxième champ
  *              string : la valeur du deuxième champ
  *              string : la description      
  * Retour :aucun
  *                        
  **************************************************************/       
 public function constructPourJour($stri_name1,$stri_val1,$stri_name2,$stri_val2,$stri_description)
 {
    $float_id=microtime(true);
    $float_id="monid";
    //$this->__construct($float_id,$float_id,_LIB_PLAGE_JOUR,1,$stri_val1,$stri_val2,1,5,"j");
     $this->init($float_id,
                        $stri_name1."_".$stri_name2,
                                     $stri_description,
                                      1,
                                      1,
                                      7,
                                      2,
                                      6,
                                      "j"
                                      );
    $this->obj_hidden1=new hidden("",$this->convertDay($stri_val1)); //pas de nom pour ne pas transmettre la donnée en post
      $this->obj_hidden1->setClass("champ1");
    $this->obj_hidden2=new hidden("",$this->convertDay($stri_val2)); //pas de nom pour ne pas transmettre la donnée en post
      $this->obj_hidden2->setClass("champ2");
      
    $this->obj_num_hidden1=new hidden($stri_name1,$stri_val1);
      $this->obj_num_hidden1->setClass("val_num_champ1");
    $this->obj_num_hidden2=new hidden($stri_name2,$stri_val2);
      $this->obj_num_hidden2->setClass("val_num_champ2");
            
    $this->stri_unite="jour";    
 }
 
  /*************************************************************
  * Pour initialiser l'objet sur une plage d'heure
  * Parametres : string : le nom du premier champ
  *              string : la valeur du premier champ
  *              string : le nom du deuxième champ
  *              string : la valeur du deuxième champ
  *              string : la description          
  * Retour :aucun
  *                        
  **************************************************************/       
 public function constructPourHeure($stri_name1,$stri_val1,$stri_name2,$stri_val2,$stri_description)
 {
    $float_id=microtime(true);
    $float_id="monid"; 
    
   
     
    //$this->__construct($float_id,$float_id,_LIB_PLAGE_JOUR,1,$stri_val1,$stri_val2,1,5,"j");
     $this->init($float_id,                     //stri_id
                 $stri_name1."_".$stri_name2,   //stri_name
                 $stri_description,             //stri_description
                 30,                            //int_step
                 0,                             //int_min
                 1440,                          //int_max
                 420,                           //int_default_value_min
                 1140,                          //int_default_value_max
                 "j"
                  );
    
    

    
    $this->obj_hidden1=new hidden($stri_name1,$stri_val1);
      $this->obj_hidden1->setClass("champ1");
    $this->obj_hidden2=new hidden($stri_name2,$stri_val2);
      $this->obj_hidden2->setClass("champ2");
    
    $this->obj_num_hidden1=new hidden("",$this->convertTime($stri_val1));//pas de nom pour ne pas transmettre la donnée en post
      $this->obj_num_hidden1->setClass("val_num_champ1");
    $this->obj_num_hidden2=new hidden("",$this->convertTime($stri_val2));//pas de nom pour ne pas transmettre la donnée en post
      $this->obj_num_hidden2->setClass("val_num_champ2");
       
    $this->stri_unite="heure";    
 }
 
  //**** setter ****************************************************************
  public function setDescription($value){$this->stri_description=$value;}
  public function setId($value){$this->stri_id=$value;}
  public function setName($value){$this->stri_name=$value;}
  public function setStep($value){$this->int_step=$value;}
  public function setMin($value){$this->int_min=$value;}
  public function setMax($value){$this->int_max=$value;}
  public function setDefault_value_min($value){$this->int_default_value_min=$value;}
  public function setDefault_value_max($value){$this->int_default_value_max=$value;}
  public function setUnite($value){$this->stri_unite=$value;}
  public function setDisabled($value){$this->bool_disabled=$value;}
  public function setStyleDiv($value){$this->stri_style_div=$value;}
  public function setInputPosition($value){$this->stri_input_position=$value;}

  //**** getter ****************************************************************
   public function getDescription(){return $this->stri_description;}
   public function getId(){return $this->stri_id;}
   public function getName(){return $this->stri_name;}
   public function getStep(){return $this->int_step;}
   public function getMin(){return $this->int_min;}
   public function getMax(){return $this->int_max;}
   public function getDefault_value_min(){return $this->int_default_value_min;}
   public function getDefault_value_max(){return $this->int_default_value_max;}
   public function getDisabled(){return $this->bool_disabled;}
   public function getStyleDiv(){return $this->stri_style_div;}
   public function getInputPosition(){return $this->stri_input_position;}

  //**** public method ********************************************************* 
   /*************************************************************
  * Permet de convertir une heure au format 08:15 en centième d'heure 8,25
  * Parametres : string : l'heure à convertir
  * Retour : float : la représentation de l'heure en centième d'heure
  *                        
  **************************************************************/       
 public function convertTimeV0($stri_time)
 {
   $arra_token=explode(":", $stri_time);
   
   $int_heure=$arra_token[0];
   $int_minute=$arra_token[1];
   
   //conversion des minutes en centième d'heure
   $float_minute=round($int_minute*100/60,2);
                 
  $float_res=(float) "$int_heure.$float_minute";
  
  return $float_res;
 } 
  /*************************************************************
  * Permet de convertir une heure au format 08:15 nombre de minute depuis le début 
  * de la journée ex : 08:15 -> 495   
  * Parametres : string : l'heure à convertir
  * Retour : float : la représentation de l'heure en centième d'heure
  *                        
  **************************************************************/       
 public function convertTime($stri_time)
 {
    //- conversion des heure au format 08:30 en nombre de minute 510 depuis le début de la journée 
    $arra_val1=explode(':', $stri_val1);
    $int_tm_val1=$arra_val1[0]*60+$arra_val1[1];
   
   $arra_token=explode(":", $stri_time);
   
   $int_heure=$arra_token[0];
   $int_minute=$arra_token[1];
   
   //- conversion en nombre de minutes
   $int_res=$int_heure*60+$int_minute;
  
  return $int_res;
 } 
 
 

 
   /*************************************************************
  * Permet de convertir un jour de numérique (1) vers sa représentation  (dimanche)
  * Ex : 1 -> lundi
  *      2 -> mardi
  *      7 -> dimanche     
  * Parametres : int : le jour à convertir
  * Retour : string : le jour en lettre
  *                        
  **************************************************************/       
 public function convertDay($int_day)
 {
  $arra_day=array(7=>_JOUR_7,1=>_JOUR_1,2=>_JOUR_2,3=>_JOUR_3,4=>_JOUR_4,5=>_JOUR_5,6=>_JOUR_6);
  return $arra_day[$int_day];
 } 
  
  //fonction jquery permettant d'afficher le slider (slider différent selon si il s'agit de jours ou d'heures)
  public function jqueryValue(){

     $stri_jquery='
     <script>
     
     
      /*
       Pour représenter les données
       Paramètres : mixed : la donnée à représenter
                    string : le type jour ou heure
       Retour : mixed : les jours en chaine ou les heures en numérique
      */
      function representeData(mixed_data,stri_type)
      {
        
        //représentation des jours
        if(stri_type=="jour")
        {
          //var arra_jour=new Array("Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche");
          var arra_jour=new Array();
              arra_jour[1]="'._JOUR_1.'";arra_jour[2]="'._JOUR_2.'"; arra_jour[3]="'._JOUR_3.'";
              arra_jour[4]="'._JOUR_4.'";arra_jour[5]="'._JOUR_5.'";arra_jour[6]="'._JOUR_6.'";arra_jour[7]="'._JOUR_7.'";
              
        
          return arra_jour[mixed_data];
        }
        
          return mixed_data;
       
      }
      
       //Pour convertir une donnée                       
       function convertData(mixed_data,stri_type)
      {
        
      
        //représentation des jours
        if(stri_type=="jour")
        {
         return  representeData(mixed_data,"jour"); //conversion identique à la représentation pour les jour
        }
        
      //représentation des heures
      var stri_data=mixed_data.toString() 
       if(stri_data.indexOf(":")==-1)//si pas de : dans la valeur
       {
        if(mixed_data==1440)//cas particulier du 24h
        {return "23:59";}
       
        //conversion de flotant vers chaine
        var entier=Math.floor(mixed_data/60);
        var minutes=mixed_data-entier*60;
        
      
        if(minutes<10)
        {minutes="0"+minutes;}
        
        if(entier<10)
        {entier="0"+entier;}
          
        return entier+":"+minutes;  
       } 
       
      }
      /* 
       Pour initialiser un slider
       */
      function initSlider(obj_div_mere)
      {
        if($(obj_div_mere).hasClass("initialized"))//si l objet a déjà été initialisé
        {return ;} 
        $(obj_div_mere).find(".param").attr("innerHTML","");//on vide se qui se trouve dans la div du slider      
        $(obj_div_mere).addClass("initialized");         
        var div=$(obj_div_mere).find(".param");
    
         div.slider({
                            			range: true,
                            		  step: '.$this->int_step.',
                                 
                              		min: '.$this->int_min.',
                            			max: '.$this->int_max.',
                            			values: [ '.$this->int_default_value_min.', '.$this->int_default_value_max.' ],
                            
                            	
                                   slide: function( event, ui ) {
                            			 /*var resume=$(event.target).parent().find(".resume"); //champ de résumé
                                   var champ1=$(event.target).parent().find(".champ1"); //premier champ de valeur représentée  (ex 8:15)
                                   var champ2=$(event.target).parent().find(".champ2"); //deuxième champ de valeur représentée
                                   var val_num_champ1=$(event.target).parent().find(".val_num_champ1"); //premier champ de valeur numérique (ex 8.25)
                                   var val_num_champ2=$(event.target).parent().find(".val_num_champ2"); //deuxième champ de valeur numérique
                                   var unit=$(event.target).parent().find("input[name=\'unite\']").val(); //unité utilisé (jour ou heure)
                                   */
                                    
                                   var slider_plage=$(event.target).closest(".slider_plage"); 
                                   var resume=slider_plage.find(".resume"); //champ de résumé
                                   var champ1=slider_plage.find(".champ1"); //premier champ de valeur représentée  (ex 8:15)
                                   var champ2=slider_plage.find(".champ2"); //deuxième champ de valeur représentée
                                   var val_num_champ1=slider_plage.find(".val_num_champ1"); //premier champ de valeur numérique (ex 8.25)
                                   var val_num_champ2=slider_plage.find(".val_num_champ2"); //deuxième champ de valeur numérique
                                   var unit=slider_plage.find("input[name=\'unite\']").val(); //unité utilisé (jour ou heure)
                                 
                                     
                            			 champ1.val(convertData(ui.values[ 0 ],unit));
                            			 champ2.val(convertData(ui.values[ 1 ],unit));
                          
                            			 val_num_champ1.val(ui.values[ 0 ]);
                            			 val_num_champ2.val(ui.values[ 1 ]);
                            			 
                            			 resume.val( champ1.val() + " - " + champ2.val() );
                            			
                                  }
                            		});
                          
       
       //*** L\'initialisation ci-dessous permet de supporter le clonage  
      //initialisation des paramètres du slider
       var val_step=$(obj_div_mere).find(".slider_step").val();
       var val_min=$(obj_div_mere).find(".slider_min").val();
       var val_max=$(obj_div_mere).find(".slider_max").val();
          
       div.slider("option","step",parseInt(val_step));
       div.slider( "option","min",parseInt(val_min));
       div.slider( "option","max",parseInt(val_max));             
      
       //initialisation des valeurs 
       var val_num_champ1=$(obj_div_mere).find(".val_num_champ1"); //premier champ de valeur numérique (ex 8.25)
       var val_num_champ2=$(obj_div_mere).find(".val_num_champ2"); //deuxième champ de valeur numérique
                                  
       var champ1=$(obj_div_mere).find(".champ1");
       var champ2=$(obj_div_mere).find(".champ2");
       var unit=$(obj_div_mere).find("input[name=\'unite\']").val();
                                  
       div.slider( "values" , 0 ,val_num_champ1.val());
       div.slider( "values" , 1 ,val_num_champ2.val());
         
       $(obj_div_mere).find(".resume").val(champ1.val() + " - " + champ2.val()); 
        	
                         	
      }
      
      
     </script> 
     ';
        
    
	   return $stri_jquery;
  }
  
   
  public function htmlValue()
  {                      
   
  $this->obj_hidden1->setDisabled($this->bool_disabled);
  $this->obj_hidden2->setDisabled($this->bool_disabled);
  $this->obj_num_hidden1->setDisabled($this->bool_disabled);
  $this->obj_num_hidden2->setDisabled($this->bool_disabled);
       
  //<div  onmouseover="$(this).one(\'mouseover\',function(){initSlider(this);});">
  $stri_id="time_id_".microtime(true);
  
  $stri_input=' <input type="text" id="'.$this->stri_id.'" class="resume" style="border:0; color:#f6931f; font-weight:bold; width:100px;" />';
  $stri_slider='<div  class="param" style="'.$this->stri_style_div.'">';
  
      
   $obj_table=new table();
   $obj_table->setStyle('width:100%;');
   
   $arra_td=array();//pour piloter les td de position
   //- construction d'une table avec toutes les positions possibles
   $obj_tr=$obj_table->addTr();
      $arra_td['top']=$obj_tr->addTd();
        $arra_td['top']->setColspan(3);
        $arra_td['top']->setStyle('display:none;');
    $obj_tr=$obj_table->addTr();
      $arra_td['left']=$obj_tr->addTd();
        $arra_td['left']->setStyle('display:none;');
      $obj_tr->addTd($stri_slider);
      $arra_td['right']=$obj_tr->addTd();
        $arra_td['right']->setStyle('display:none;');
   $obj_tr=$obj_table->addTr();
      $arra_td['bottom']=$obj_tr->addTd();
        $arra_td['bottom']->setColspan(3);
        $arra_td['bottom']->setStyle('display:none;');  
        
   //- activation de la position
   $obj_td=$arra_td[$this->stri_input_position];//récupération du td
   $obj_td->setValue($stri_input);
   $obj_td->setStyle('width:100px;');
         
                             
   
   $stri_res = '<div  onmouseover="initSlider(this);" id="'.$stri_id.'" class="slider_plage">
                    <label for="'.$this->stri_id.'" >'.$this->stri_description.'</label>
    	              <input type="hidden" name="unite" value="'.$this->stri_unite.'">
                    <input type="hidden" class="slider_min" value="'.$this->int_min.'">
                    <input type="hidden" class="slider_max" value="'.$this->int_max.'">
                    <input type="hidden" class="slider_step" value="'.$this->int_step.'">                   
                   '
                   .$this->obj_hidden1->htmlValue().
                    $this->obj_hidden2->htmlValue().
                    $this->obj_num_hidden1->htmlValue().
                    $this->obj_num_hidden2->htmlValue().
                    $obj_table->htmlValue().
                   
                   ' 
                    </div>
                  </div>
                  ';
   
     //javascript d'initialisation
     $stri_init_js='<script>
                    $(function()
                    {
                      initSlider(document.getElementById("'.$stri_id.'"));//on lance l\'initialisation du slider                    
                    });
                    
                  </script>';
      return $stri_res.$this->jqueryValue().$stri_init_js;
  }
  
  //**** method for serialization **********************************************
  public function __sleep() 
  {
    //sérialisation de la classe 
    $this->arra_sauv['id']= $this->stri_id;
    $this->arra_sauv['description']= $this->stri_description;
    $this->arra_sauv['name']= $this->stri_name;
    $this->arra_sauv['step']= $this->int_step;
    $this->arra_sauv['min']= $this->int_min;
    $this->arra_sauv['max']= $this->int_max;
    $this->arra_sauv['default_value_min']= $this->int_default_value_min;
    $this->arra_sauv['default_value_max']= $this->int_default_value_max;
    $this->arra_sauv['unite']= $this->stri_unite;
    
    return array('arra_sauv');
  }
  
  public function __wakeup() 
  {
    //désérialisation de la classe 

    $this->stri_id= $this->arra_sauv['id'];
    $this->stri_description= $this->arra_sauv['description'];
    $this->stri_name= $this->arra_sauv['name'];
    $this->int_step= $this->arra_sauv['step'];
    $this->int_min= $this->arra_sauv['min'];
    $this->int_max= $this->arra_sauv['max'];
    $this->int_default_value_min= $this->arra_sauv['default_value_min'];
    $this->int_default_value_max= $this->arra_sauv['default_value_max'];  
    $this->stri_unite= $this->arra_sauv['unite']; 
    
    $this->arra_sauv = array();
  } 
}

?>
