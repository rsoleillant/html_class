<?php
/*******************************************************************************
Create Date : 21/02/2012
 ----------------------------------------------------------------------
 Class name : calendrier_projet
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permet de créer un calendrier utilisé dans la gestion de projet
********************************************************************************/

     
class calendrier_projet
{

  //**** attribute *************************************************************
  protected $obj_date_debut;     //La date à partir de laquelle construire la calendrier  au format d/m/Y H:i:s ou dérivée (ex d/m/Y)
  protected $obj_date_fin;       //La date de fin du calendrier
  protected $arra_unite_visible;  //Les unités du calendrier que l'on souhaite voir
  private $arra_courrant;       //Utilisé dans l'algo de construction du calendrier pour savoir l'unité courrante
 
 //**** constructor ***********************************************************
   
   /*************************************************************
   *
   * parametres : string : l'identifiant de l'instance
   * retour : objet de la classe calendrier_projet   
   *                        
   **************************************************************/         
  function __construct($stri_date_debut,$stri_date_fin)
  {   
    
    //$this->arra_unite_visible=array("annee","mois","semaine","jour","heure","minute","seconde");
    $this->arra_unite_visible=array("annee","mois","semaine","jour");
    
    $obj_start=new date($stri_date_debut);
    $obj_fin=new date($stri_date_fin);
    
    $this->obj_date_debut=$obj_start;
    $this->obj_date_fin=$obj_fin;
  }  
 
  //**** setter ****************************************************************
    public function setDateDebut($value){$this->obj_date_debut=$value;}
    public function setDateFin($value){$this->obj_date_fin=$value;}
    public function setUniteVisible($value)
    {
     $arra_all_unite=array("annee","mois","semaine","jour","heure","minute","seconde");//toutes les unités possibles
     $arra_diff=array_diff($value,$arra_all_unite);
     if(count($arra_diff)!=0)//s'il y a des différence, c'est qu'on essais d'afficher des unités non correctes
     {
      trigger_error("Only this units are treated : annee, mois, semaine, jour, heure, minute, seconde",E_USER_ERROR);
     }
     $this->arra_unite_visible=$value;
     
    
    
    }
    public function setCourrant($value){$this->arra_courrant=$value;}

  

  //**** getter ****************************************************************
    public function getDateDebut(){return $this->obj_date_debut;}
    public function getDateFin(){return $this->obj_date_fin;}
    public function getUniteVisible(){return $this->arra_unite_visible;}
    public function getCourrant(){return $this->arra_courrant;}


  //**** public method *********************************************************
 /*************************************************************
 * Permet de déterminer le début d'une période
 * 
 * Parametres : 
 *              int : la valeur de l'unité supérieur  que l'on décompose
 *              string : l'unité dans laquelle le paramètre int_courrant est exprimée 
 *              string : l'unité dans laquelle on veut décomposer 
 *                
 * retour : string : le code html
 *                        
 **************************************************************/ 
  private function determineDebut($int_courrant,$stri_macro_unite,$stri_micro_unite)
  {
    $obj_date=$this->obj_date_debut;
    
    if(($stri_macro_unite=="")&&($stri_micro_unite=="annee"))//cas particulier de l'initialisation sur les dates du calendrier
    {return $obj_date->getYear();}
    
    
    $arra_methode_for_unit=array("annee"=>"getYear","mois"=>"getMouth","jour"=>"getDay","heure"=>"getHour","minute"=>"getMinut","seconde"=>"getSecond");  //correspondance entre l'unité voulue et le getter de l'objet date à utiliser
    $arra_depart=array("mois"=>1,"semaine"=>1,"jour"=>1,"heure"=>0,"minute"=>0,"seconde"=>0);
    
    $stri_macro_getter  = $arra_methode_for_unit[$stri_macro_unite];//récupération du getter
    
    $int_depart_calendrier= $obj_date->$stri_macro_getter();
    
    if($int_depart_calendrier==$int_courrant)//si l'année de départ du calendrier est égal à l'année courrante
    {
      $stri_micro_getter=$arra_methode_for_unit[$stri_micro_unite];
      
      $int_depart=$obj_date->$stri_micro_getter();
      
    
    }
    else
    {
      $int_depart=$arra_depart[$stri_micro_unite];
    }
      // echo "pour l'année $int_courrant, départ à $int_depart <br />";
    
    
    return $int_depart;
  }
  
  /*************************************************************
 * Permet de déterminer le nombre de jour qui il y a dans le mois courrant
 * 
 * Parametres : aucun
 *                
 * retour : int : le nombre de jour
 *                        
 **************************************************************/ 
  private function determineNbrJourDansCourrant()
  {
    $obj_date=new date();
      $obj_date->setYear($this->arra_courrant['annee']);
      $obj_date->setMouth($this->arra_courrant['mois']);
      $obj_date->setDay(1);
      $obj_date->setHour(0);
      $obj_date->setMinut(0);
      $obj_date->setSecond(0);
      
    $int_nb=$obj_date->date("t");
    
    //echo "il y a $int_nb jour dans  ".$this->arra_courrant['mois']."/".$this->arra_courrant['annee']."<br />";
      return $int_nb;
  }
      
   /*************************************************************
 * Permet de déterminer le début d'une période
 * 
 * Parametres : 
 *              int : la valeur de l'unité supérieur  que l'on décompose
 *              string : l'unité dans laquelle le paramètre int_courrant est exprimée 
 *              string : l'unité dans laquelle on veut décomposer 
 *                
 * retour : string : le code html
 *                        
 **************************************************************/ 
  private function determineFin($int_courrant,$stri_macro_unite,$stri_micro_unite)
  {
    $obj_date=$this->obj_date_fin;
     
    if(($stri_macro_unite=="")&&($stri_micro_unite=="annee"))//cas particulier de l'initialisation sur les dates du calendrier
    {return $obj_date->getYear();}
  
    $arra_methode_for_unit=array("annee"=>"getYear","mois"=>"getMouth","jour"=>"getDay","heure"=>"getHour","minute"=>"getMinut","seconde"=>"getSecond");  //correspondance entre l'unité voulue et le getter de l'objet date à utiliser
    $arra_fin=array("mois"=>12,"semaine"=>1,"jour"=>$this->determineNbrJourDansCourrant(),"heure"=>23,"minute"=>59,"seconde"=>59);
    
    $stri_macro_getter  = $arra_methode_for_unit[$stri_macro_unite];//récupération du getter
    
    $int_fin_calendrier= $obj_date->$stri_macro_getter();
    
    if($int_fin_calendrier==$int_courrant)//si l'année de départ du calendrier est égal à l'année courrante
    {
      $stri_micro_getter=$arra_methode_for_unit[$stri_micro_unite];
      
      $int_fin=$obj_date->$stri_micro_getter();
      
    
    }
    else
    {
      $int_fin=$arra_fin[$stri_micro_unite];
    }
       //echo "pour l'année $int_courrant, fin à $int_fin <br />";
    
    return $int_fin;
  }
  
   /*************************************************************
 * Permet d'obtenir le code html représentant le calendrier
 * 
 * Parametres : aucun
 * retour : string : le code html
 *                        
 **************************************************************/ 
  public function htmlValue()
  { 
    $arra_nb_seconde=array("seconde"=>1,"minute"=>60,"heure"=>60*60,"jour"=>60*60*24,"semaine"=>60*60*24*7);//calcul des pas en fonction de l'unité
    $arra_format=array("seconde"=>"d/m/Y H:i:s","minute"=>"d/m/Y H:i","heure"=>"d/m/Y H","jour"=>"d/m/Y","semaine"=>"W","mois"=>"m/Y","annee"=>"Y");
    $stri_petit_unite=$this->arra_unite_visible[count($this->arra_unite_visible)-1]; //récupération de l'unité la plus petite
    $int_pas=$arra_nb_seconde[$stri_petit_unite]; //récupération du pas
    
    $int_courant=$this->obj_date_debut->getTimeStamp();
    $int_stop=$this->obj_date_fin->getTimeStamp();
    
    //génération des données
    $arra_data=array();//Tableau pour stocker toute les données
    while($int_courant<=$int_stop)
    {
      foreach($this->arra_unite_visible as $stri_unite)
      { 
       $stri_format=$arra_format[$stri_unite];
       $stri_date=date($stri_format,$int_courant);
       $arra_data[$stri_unite][$stri_date]++;
       
      }
       
      
      $int_courant+=$int_pas;
    }
     
        
    //Rangement des données dans une interface
    $obj_table=new table();
    $obj_table->setBorder(1);
    $obj_table->setId("table_calendrier");
        
    foreach($this->arra_unite_visible as $stri_unite)
    {
      $obj_tr=$obj_table->addTr();
      /*echo "tr pour $stri_unite<br />";
      echo "<pre>";
      var_dump($arra_data[$stri_unite]);
      echo "</pre>";          */
      foreach($arra_data[$stri_unite] as $stri_value=>$int_colspan)
      {
       //echo "pose de $stri_value, colspan $int_colspan<br />";
        $obj_td=$obj_tr->addTd($stri_value);
         $obj_td->setColspan($int_colspan);
         $obj_td->setAlign("center");
      }
    
    }
    /*
    echo "<pre>";
    var_dump($arra_data);
    echo "</pre>";
    echo $stri_petit_unite;
        */
    
    return $obj_table->htmlValue();
  } 
   
   
 /*************************************************************
 * Permet d'obtenir le code html représentant le calendrier
 * 
 * Parametres : aucun
 * retour : string : le code html
 *                        
 **************************************************************/ 
  public function htmlValue_v0()
  { 
   //initialisation du courrant
   $this->arra_courrant=array("annee"=>"","mois"=>1,"semaine"=>1,"jour"=>1,"heure"=>0,"minute"=>0,"seconde"=>0);
    /*
   $obj_start=new date($this->stri_date_debut);
   $obj_fin=new date($this->stri_date_fin);
   //conversion des dates de début et fin en timestamp
   $int_start=$obj_start->getTimestamp();
   $int_fin=$obj_fin->getTimestamp();*/
   
   $obj_start=$this->obj_date_debut;
   $obj_fin=$this->obj_date_fin;
 
   
   //génération des années
   $obj_table=new table();
  
  // $obj_table->setBorder(1);
      $obj_tr=$obj_table->addTr();
      $obj_td=$obj_tr->addTd();
    $arra_td=array($obj_td);
    $arra_new_td=array();
 
   foreach($this->arra_unite_visible as $int_key=>$stri_micro_unite)
   {  
    //echo "traitement de la microunité $stri_micro_unite<br />";
     $stri_macro_unite=$this->arra_unite_visible[$int_key-1];//récupération de l'unité de temps précédante

     //foreach($obj_tr->getTd() as $obj_td)
     
     foreach($arra_td as $obj_td)
     { 
     
       $this->arra_courrant[$stri_macro_unite]=$obj_td->getValue();
       //echo "macro unité $stri_macro_unite<br />"; 
      // echo "valeur macro ". $this->arra_courrant[$stri_macro_unite]."<br />";
     // echo "macro $stri_macro_unite, micro $stri_micro_unite<br />";
      
       $int_debut=$this->determineDebut($this->arra_courrant[$stri_macro_unite],$stri_macro_unite,$stri_micro_unite);
       $int_fin=$this->determineFin($this->arra_courrant[$stri_macro_unite],$stri_macro_unite,$stri_micro_unite);
       
        $obj_sous_table=new table();
       // $obj_sous_table->setBorder(1);
        $obj_sous_table->setStyle("border-collapse:collapse;border-style:solid;border-width:3px;");
        //$obj_sous_table->setStyle("border-colapse:colapse;");
        //$obj_sous_table->setCellspacing(0);
        //$obj_sous_table->setCellpadding(0);
        
        $obj_sous_td1=new td();//pour que ce td soit toujours défini
          if( $this->arra_courrant[$stri_macro_unite]!="")
          {
            $obj_sous_tr1=$obj_sous_table->addTr();
              $obj_sous_td1=$obj_sous_tr1->addTd( $this->arra_courrant[$stri_macro_unite]);//on met l'unité supérieur dans une cellule
              $obj_sous_td1->setAlign("center");
          }
  
        $obj_sous_tr2=$obj_sous_table->addTr();
       
       // echo "début $int_debut<br />";
       $this->arra_courrant[$stri_micro_unite]=$int_debut;
       while($this->arra_courrant[$stri_micro_unite]<=$int_fin)
       { //echo $this->arra_courrant[$stri_micro_unite].", fin $int_fin<br />";
        $obj_sous_tr2->addTd($this->arra_courrant[$stri_micro_unite]);
        $this->arra_courrant[$stri_micro_unite]++;
        
       }
       $this->arra_courrant[$stri_micro_unite]--; 
       
       $obj_sous_td1->setColspan($obj_sous_tr2->getNumberTd());
       
       $obj_td->setValue($obj_sous_table);
       
       $arra_new_td=array_merge($arra_new_td,$obj_sous_tr2->getTd());   
     }
    $obj_tr=$obj_sous_tr2;
    $arra_td=$arra_new_td;
    $arra_new_td=array();  
   
   }
  
 // $obj_td->setId("td_unite");  
      
  $obj_table->setId("table_calendrier");
  return $obj_table->htmlValue();
  
  }
  
 
}


 
?>
