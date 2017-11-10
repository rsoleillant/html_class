<?php
class date 
{   
  //**** attribute *************************************************************  
  protected $int_day;
  protected $int_month; 
  protected $int_year;  
  protected $int_hour;
  protected $int_minut;
  protected $int_second;
    
  //**** constructor ***********************************************************
  /*enter a date with format 2000-01-01 08:00:00 or JJ/MM/YYYY HH:MM:SS*/
  function __construct($stri_value) 
  {  
   $stri_third=substr($stri_value,2,1);//recherche du troisi�me caract�re pour d�terminer s'il s'agit d'une date fran�aise ou bdd
    //d�tection si la date est au format 2000-01-01 08:00:00 ou JJ/MM/YYYY HH:MM:SS
   if($stri_third=="/")//date au format fran�ais
    {$this->setValueFromFrenchDate($stri_value);}
    //else 
    //date au format bdd
    if($stri_value{4}=="-")
    {$this->setValue($stri_value);}
    
    if($stri_third==":")//heure uniquement
    {
     $this->setValueFromTime($stri_value);
    }
    /*$this->int_year=substr ($stri_value,0,4);
    $this->int_mouth=substr ($stri_value,5,2);
    $this->int_day=substr ($stri_value,8,2);
    $this->int_hour=substr ($stri_value,11,2);
    $this->int_minut=substr ($stri_value,14,2);
    $this->int_second=substr ($stri_value,17,2);*/
  }
  
  //**** setter ****************************************************************
  public function setYear($int)
  {
    if(is_numeric ($int))
      {$this->int_year=$int; }
    else
      {echo("<script>alert('int_year doit etre de type entier');</script>");}
  }
  
  public function setMouth($int)
  {
    if(is_numeric ($int))
      {$this->int_mouth=$int; }
    else
      {echo("<script>alert('int_mouth doit etre de type entier');</script>");}
  }
  
  public function setDay($int)
  {
    if(is_numeric ($int))
      {$this->int_day=$int; }
    else
      {echo("<script>alert('int_day doit etre de type entier');</script>");}
  }
  
  public function setHour($int)
  {
    if(is_numeric ($int))
      {$this->int_hour=$int; }
    else
      {echo("<script>alert('int_hour doit etre de type entier');</script>");}
  }
  
  public function setMinut($int)
  {
    if(is_numeric ($int))
      {$this->int_minut=$int; }
    else
      {echo("<script>alert('int_minut doit etre de type entier');</script>");}
  }
  
  public function setSecond($int)
  {
    if(is_numeric ($int))
      {$this->int_second=$int; }
    else
      {echo("<script>alert('int_second doit etre de type entier');</script>");}
  }
  
  public function setValue($stri_value)
  {/* permet de r�initialiser la date, $stri_value doit �tre au format
    2000-01-01 08:00:00 */
    
    $this->int_year=substr ($stri_value,0,4);
    $this->int_mouth=substr ($stri_value,5,2);
    $this->int_day=substr ($stri_value,8,2);
    $this->int_hour=substr ($stri_value,11,2);
    $this->int_minut=substr ($stri_value,14,2);
    $this->int_second=substr ($stri_value,17,2);
  }
  
  public function setValueFromFrenchDate($stri_value)
  { /*permet de r�initialiser la date a partir d'une date fran�aise 
    au format JJ/MM/YYYY HH:MM:SS*/
    $this->int_day=substr ($stri_value,0,2);
    $this->int_mouth=substr ($stri_value,3,2); 
    $this->int_year=substr ($stri_value,6,4);
    $this->int_hour=substr ($stri_value,11,2);
    $this->int_minut=substr ($stri_value,14,2);
    $this->int_second=substr ($stri_value,17,2);
  }
  
  public function setValueFromTime($stri_value)
  { /*permet de r�initialiser la date a partir d'une heure
    au format HH:MM:SS*/
         
    $arra_token=explode(":",$stri_value);
    
   
    
    $this->int_day=1;
    $this->int_mouth=1; 
    $this->int_year=1970;
    $this->int_hour=$arra_token[0];
    $this->int_minut=$arra_token[1];
    $this->int_second=$arra_token[2];
    
    
  
  }
      
  //**** getter ****************************************************************
  public function getYear(){return $this->int_year;}
  public function getMouth(){return $this->int_mouth;}
  public function getMonth(){return $this->getMouth();}
  
  public function getDay(){return $this->int_day;}
  public function getHour(){return $this->int_hour;}
  public function getMinut(){return $this->int_minut;}
  public function getSecond(){return $this->int_second;}
  public function getTimeStamp()
  {
    $res=mktime($this->int_hour,$this->int_minut,$this->int_second,$this->int_mouth,$this->int_day,$this->int_year);
    return $res;
  }  
  public function getDate($clef="")
  {
    /* permet d'obtenir des infos sur la date
  
    retour: mixed : tableau par d�faut sinon valeur du tableau dont la clef est pass� en param�tre.
    
    $clef peut prendre les valeurs suivantes:
    
    Clef 	     Description 	                                              Exemple de valeur retourn�e
    "seconds" 	 Repr�sentation num�rique des secondes 	                    0 � 59
    "minutes" 	 Repr�sentation num�rique des minutes 	                    0 � 59
    "hours" 	   Repr�sentation num�rique des heures 	                      0 � 23
    "mday" 	     Repr�sentation num�rique du jour du mois courant 	        1 � 31
    "wday" 	     Repr�sentation num�rique du jour de la semaine courante 	  0 (pour Dimanche) � 6 (pour Samedi)
    "mon" 	     Repr�sentation num�rique du mois 	                        1 � 12
    "year" 	     Ann�e, sur 4 chiffres 	                                    Exemples: 1999 ou 2003
    "yday" 	     Repr�sentation num�rique du jour de l'ann�e 	              0 � 365
    "weekday" 	 Version texte du jour de la semaine 	                      Sunday � Saturday
    "month" 	   Version texte du mois,                                     comme January ou March 	January � December
    0 	Nombre de secondes depuis l'�poque Unix, similaire � la valeur retourn�e par la fonction time et utilis�e par date . 	D�pend du syst�me, typiquement de -2147483648 � 2147483647 .
    
    */
    $int_timestamp=$this->getTimeStamp();
    
    $arra_res= getDate($int_timestamp);
    return ($clef=="")?$arra_res:$arra_res[$clef];
  }
  
  //**** public method *********************************************************
  public function HMS()
  {
    $stri_res=$this->int_hour.":".$this->int_minut.":".$this->int_second;
    return $stri_res;
  }
  
  //permet d'obtenir la date dans le format voulu
  public function date($stri_format)
  {
   $int_timestamp=$this->getTimeStamp();
   
   $stri_res= date($stri_format,$int_timestamp);
   return $stri_res;
  }
  public static function diffDate($date1,$date2)
  {// calcul le nombre de jour qu'il y a de diff�rence entre $date1 et $date2
    // les dates doivent �tre au format JJ/MM/AAAA   ou AAAA-MM-JJ
    //Extraction des donn�es
    //Par d�faut mode fran�ais 
    list($jour1, $mois1, $annee1) = explode('/', $date1); 
    list($jour2, $mois2, $annee2) = explode('/', $date2);
    if(strpos($date1,'-')!==false )//si mode anglais
    {
      list($annee1,$mois1,$jour1) = explode('-', $date1); 
      list($annee2,$mois2,$jour2) = explode('-', $date2);
    } 
     
    //Calcul des timestamp
    $timestamp1 = mktime(0,0,0,$mois1,$jour1,$annee1); 
    $timestamp2 = mktime(0,0,0,$mois2,$jour2,$annee2);
    //calcul du nombre de jour : 
    return abs($timestamp2 - $timestamp1)/86400; 
  }     
  
  
  
  /*******************************************************************************
    *  Version 2
    *  Permet de connaitre le nombre de jour ouvr� entre deux dates
    * 
    * Parametres : string : date de d�but
    *              string : date de fin 
    *               array : By r�f�rence -> bilan du calcul
    * Retour : float: le nombre de jours ouvr�s
    *******************************************************************************/
  public static function diffDateJourOuvre($date1,$date2,&$arra_bilan)
  { 
      
       //Array pour trouver les �carts pour la borne de d�part
      $arra_ecart_depart = array(
                                1=>5,   //Lundi     => +5
                                2=>4,   //Mardi     => +4
                                3=>3,   //Mercredi  => +3
                                4=>2,   //Jeudi     => +2
                                5=>1,   //Vendredi  => +1
                                6=>0,   //Samedi    => +0
                                7=>0);  //Dimanche  => +0);  
                                
    //Array pour trouver les �carts pour la borne d'arriv�e  
    $arra_ecart_arrivee = array(
                                1=>1,   //Lundi     => +1
                                2=>2,   //Mardi     => +2
                                3=>3,   //Mercredi  => +3
                                4=>4,   //Jeudi     => +4
                                5=>5,   //Vendredi  => +5
                                6=>5,   //Samedi    => +0
                                7=>0);   //Dimanche  => +0
        
    
    //Date de debut
    $obj_date_depart=new date($date1);
        $stri_date_depart=$obj_date_depart->date('Y-m-d');
        $int_semaine_depart=$obj_date_depart->date('W');
        $int_month_depart =$obj_date_depart->date('m');
        $int_annee_depart=$obj_date_depart->date('Y');
        $int_jour_date_depart =$obj_date_depart->date('N');

        
    //Date de fin
    $obj_date_arrivee=new date($date2);
        $stri_date_arrivee=$obj_date_arrivee->date('Y-m-d');  
        $int_semaine_arrivee=$obj_date_arrivee->date('W');
        $int_month_arrivee=$obj_date_arrivee->date('m');
        $int_annee_arrivee=$obj_date_arrivee->date('Y');
        $int_jour_date_arrivee =$obj_date_arrivee->date('N');
    
        
        //Borne de depart et d'arriv�e
        

        //Calcul date de debut semaine complette
        $obj_dsd = $obj_date_depart->getLundiSuivant();
        $stri_date_debut_semaine_complet = $obj_dsd;
        if (is_object($obj_dsd))
        {
            $stri_date_debut_semaine_complet = $obj_dsd->date('Y-m-d');
        }
        
        //Calcul date de fin semaine complette
        $obj_fsd = $obj_date_arrivee->getDimanchePrecedant();
        $stri_date_fin_semaine_complet = $obj_fsd;
        if (is_object($obj_fsd))
        {
            $stri_date_fin_semaine_complet = $obj_fsd->date('Y-m-d');
        }
        

        $int_semaine_depart_next = ($int_semaine_depart == 52)? 1 : $int_semaine_depart_next;

        //Si gestion de la meme semaine
        //Ne prends pas en comptes les ecarts
        if ($int_semaine_depart == $int_semaine_arrivee && $int_month_depart == $int_month_arrivee && $int_annee_depart == $int_annee_arrivee )
        {
            
            //Poids de chaque  jour de la semaine
            $arra_week_days_value=array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>5,7=>5);
            
            //Calcul sur les jours de d�part de d'arriv�
            $int_nb_jour_ouvre_total = $arra_week_days_value[$int_jour_date_arrivee] -  $arra_week_days_value[$int_jour_date_depart] ;
            
            //On exclu les week_end
            $arra_days_week_end =array(6,7);
            if ( ! in_array($int_jour_date_depart,$arra_days_week_end) ||  ! in_array($int_jour_date_depart,$arra_days_week_end) )
            {
                $int_nb_jour_ouvre_total++;
            }

        }
        //else if ($int_semaine_depart_next == $int_semaine_arrivee  && $int_annee_depart == $int_annee_arrivee )
        else if ($int_semaine_depart_next == $int_semaine_arrivee   )
        {
            //Poids
            $arra_week_days_arrivee =array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>5,7=>5);
            $arra_week_days_depart =array(1=>5,2=>4,3=>3,4=>2,5=>1,6=>0,7=>0);
            
            //Dans le cas d'une diff�rence sur deux semaine
            $int_nb_jour_ouvre_total =  $arra_week_days_depart[$int_jour_date_depart] +  $arra_week_days_arrivee[$int_jour_date_arrivee] ;
        }
        else
        {
            
            //Dans le cas d'une diff�rence sur plusieur semaine
            
            //Nombre de jour pour les semaines completes
            $int_nb_jour_complet = date::diffDate($stri_date_debut_semaine_complet, $stri_date_fin_semaine_complet)+1;
        
            //Nombre de semaine completes
            $int_nb_semaine_complet = $int_nb_jour_complet / 7;

            //Recherche des �carts pour la borne du d�but et la borne de fin
            $int_ecart_depart = $arra_ecart_depart[$int_jour_date_depart];     
            $int_ecart_arrivee = $arra_ecart_arrivee[$int_jour_date_arrivee]; 
            
            //Calcul nombre de jour ouvr�s complet
            $int_nb_jour_ouvre_complet = $int_nb_semaine_complet * 5;
            $int_nb_jour_ouvre_total = $int_ecart_depart +  $int_nb_jour_ouvre_complet + $int_ecart_arrivee;

            

        }
        
        
        
        //Calcul des jours f�ri�

       //- calcul du nombre d'ann�e diff�rente
       $arra_annee_different=array();
       for($int_annee=$int_annee_depart;$int_annee<=$int_annee_arrivee;$int_annee++)
       {
        $arra_annee_different[]=$int_annee;
       }

       //- gestion des jours feri�s
       $arra_ferie_inclus=array();
       foreach($arra_annee_different as $int_annee)
       {
         $arra_ferie_depart=date::getListeFerie($int_annee);

         //-recherche de jour feri� entre la date de d�part et d'arriv�e
         foreach($arra_ferie_depart as $stri_date_ferie=>$stri_jour)
         {
           if(($stri_date_ferie>=$stri_date_depart)&&($stri_date_ferie<=$stri_date_arrivee))
           {$arra_ferie_inclus[$stri_date_ferie]=$stri_jour;}
         }
       } 

       //- on recherche les jour feri� qui sont sur des jours ouvr�e
       $int_ferie=0;
       foreach($arra_ferie_inclus as $stri_date=>$stri_jour)
       {
         $obj_date_ferie=new date($stri_date);
         $int_jour_ferie=$obj_date_ferie->date('w');
         if(($int_jour_ferie>0)&&($int_jour_ferie<6))//si feri� du lundi au vendredi
         {$int_ferie++;}
       }

       
       $int_nb_jour_ouvre_total=$int_nb_jour_ouvre_total-$int_ferie; 


        /*
        echo "Date Dsc : $stri_date_debut_semaine_complet <br />";
        echo "Date Fsc : $stri_date_fin_semaine_complet <br />";
        echo "Nombre jour complet (multiple de 7 ) : $int_nb_jour_complet <br />";
        echo "Nombre de semaine complete : $int_nb_semaine_complet <br />";
        echo "Ecart d�part : $int_ecart_depart <br />";
        echo "Ecart arriv�e : $int_ecart_arrivee <br />";
        echo "Nombre de jour ouvr�s : $int_nb_jour_ouvre_total <br />";
         * 
         * 
         */

          //- construction d'un bilan
        $arra_bilan["Date D�part "] =  $stri_date_depart;
        $arra_bilan["Date Arriv�e "] =  $stri_date_arrivee;
        $arra_bilan["Date Dsc"] =  $stri_date_debut_semaine_complet;
        $arra_bilan["Date Fsc"] =  $stri_date_fin_semaine_complet;
        $arra_bilan["Nombre jour complet (multiple de 7 )"] =  $int_nb_jour_complet;
        $arra_bilan["Nombre de semaine complete"] =  $int_nb_semaine_complet;
        $arra_bilan["Ecart d�part"] =  $int_ecart_depart;
        $arra_bilan["Ecart arriv�e"] =  $int_ecart_arrivee;
        $arra_bilan["Nombre de jour total"] =  ceil(date::diffDate($stri_date_depart,$stri_date_arrivee))+1;;
        $arra_bilan["Nombre de jour ouvr�s"] =  $int_nb_jour_ouvre_total;
        $arra_bilan["Liste jour f�ri� "] =  $arra_ferie_inclus;



        return round($int_nb_jour_ouvre_total);
    
      
  }
      
      
  //Permet de connaitre le nombre de jour ouvr� entre deux dates
  public static function diffDateJourOuvreV0($date1,$date2,&$arra_bilan)
  { 
     
      
   //echo "d�part $date1 arriv�e $date2<br />";
   $obj_date_depart=new date($date1);
   //var_dump($obj_date_depart);
   $stri_date_depart=$obj_date_depart->date('Y-m-d');
   $int_semaine_depart=$obj_date_depart->date('W');
   $int_annee_depart=$obj_date_depart->date('Y');
   $obj_date_arrivee=new date($date2);
   $stri_date_arrivee=$obj_date_arrivee->date('Y-m-d');
   $int_premier_jour_depart='';
   $int_dernier_jour_depart='';
   $int_semaine_arrivee=$obj_date_arrivee->date('W');
   $int_annee_arrivee=$obj_date_arrivee->date('Y');
   $int_premier_jour_arrivee='';
   $int_dernier_jour_arrivee='';
   
   date::getPremierJourSemaine($int_semaine_depart,$int_annee_depart,$int_premier_jour_depart,$int_dernier_jour_depart);
   date::getPremierJourSemaine($int_semaine_arrivee,$int_annee_arrivee,$int_premier_jour_arrivee,$int_dernier_jour_arrivee);
   
   // date::getPremierJourSemaineByDate($date1,$int_premier_jour_depart,$int_dernier_jour_depart);
    //date::getPremierJourSemaineByDate($date2,$int_premier_jour_arrivee,$int_dernier_jour_arrivee);

   
   //echo "d�part semaine $int_semaine_depart : de  $int_premier_jour_depart � $int_dernier_jour_depart<br />";
   //echo "arriv�e semaine $int_semaine_arrivee : de  $int_premier_jour_arrivee � $int_dernier_jour_arrivee<br />";
    
   //- calcul du nombre total de jour entre le d�part et l'arrivee de chaque  
   $int_nb_jour_total=ceil(date::diffDate($int_premier_jour_depart,$int_dernier_jour_arrivee))+1;  
   //echo "total jour $int_nb_jour_total<br />"; 
   
   $int_nb_semaine=$int_nb_jour_total/7;//calcul du nombre de semaine
   //echo "nb semaine $int_nb_semaine<br />";  
   
   //- calcul de l'�cart entre le d�part et le premier jour de la m�me semaine
   $int_ecart_depart=date::diffDate($stri_date_depart,$int_premier_jour_depart);
   $int_ecart_depart=($int_ecart_depart<6)?$int_ecart_depart:5;
   
   //echo "ecart au d�part entre $stri_date_depart et $int_premier_jour_depart : $int_ecart_depart<br />";
   
   //- calcul de l'�cart entre l'arriv�e et le dernier jour de la m�me semaine
   $int_ecart_arrivee=date::diffDate($stri_date_arrivee,$int_dernier_jour_arrivee);
       $int_ecart_arrivee=$int_ecart_arrivee-2;//pour ne pas compter samedi et dimanche
       $int_ecart_arrivee=($int_ecart_arrivee<0)?-$int_ecart_arrivee:$int_ecart_arrivee;
   
// echo "<br>ecart au arrivee entre $stri_date_arrivee et $int_dernier_jour_arrivee : $int_ecart_arrivee<br />";
   
       //- calcul du nombre de jour ouvree
      $int_nb_jour_ouvre=round($int_nb_semaine*5)-round($int_ecart_depart)-round($int_ecart_arrivee);
   //echo "bilan utilisation round(nb semaine * 5) : ".($int_nb_semaine*5)." - round($int_ecart_depart) - round($int_ecart_arrivee) = $int_nb_jour_ouvre<br />"; 
       
   //$int_nb_jour_ouvre=round($int_nb_jour_total)-round($int_ecart_depart)-round($int_ecart_arrivee);
   //echo "bilan ".($int_nb_jour_total)." - round($int_ecart_depart) - round($int_ecart_arrivee) = $int_nb_jour_ouvre<br />"; 
   
   
   //- calcul du nombre d'ann�e diff�rente
   $arra_annee_different=array();
   for($int_annee=$int_annee_depart;$int_annee<=$int_annee_arrivee;$int_annee++)
   {
    $arra_annee_different[]=$int_annee;
   }
 
   //- gestion des jours feri�s
   $arra_ferie_inclus=array();
   foreach($arra_annee_different as $int_annee)
   {
     $arra_ferie_depart=date::getListeFerie($int_annee);

     //-recherche de jour feri� entre la date de d�part et d'arriv�e
     foreach($arra_ferie_depart as $stri_date_ferie=>$stri_jour)
     {
       if(($stri_date_ferie>=$stri_date_depart)&&($stri_date_ferie<=$stri_date_arrivee))
       {$arra_ferie_inclus[$stri_date_ferie]=$stri_jour;}
     }
   } 
 
   //- on recherche les jour feri� qui sont sur des jours ouvr�e
   $int_ferie=0;
   foreach($arra_ferie_inclus as $stri_date=>$stri_jour)
   {
     $obj_date_ferie=new date($stri_date);
     $int_jour_ferie=$obj_date_ferie->date('w');
     if(($int_jour_ferie>0)&&($int_jour_ferie<6))//si feri� du lundi au vendredi
     {$int_ferie++;}
   }
      
   $int_jour_ouvree=$int_nb_jour_ouvre-$int_ferie; 
   
   //- construction d'un bilan
   $arra_bilan['depart']=$stri_date_depart;
   $arra_bilan['arrivee']=$stri_date_arrivee;
   $arra_bilan['nb jour ouvree']=$int_jour_ouvree;
   $arra_bilan['nb jour total']=ceil(date::diffDate($stri_date_depart,$stri_date_arrivee))+1;
   $arra_bilan['nb jour feri� sur jour ouvree']=$int_ferie;
   $arra_bilan['liste jour ferie']=$arra_ferie_inclus;
    

 
    
   return $int_jour_ouvree;   
  }  
  public function nextDay($date,$format)
  {//renvoi la date du jour (format YYYY-MM-DD) suivant $date au format $format
    list($annee1,$mois1,$jour1) = explode('-', $date);
    $timestamp1 = mktime(0,0,0,$mois1,$jour1,$annee1)+86400;
    return date ($format,$timestamp1);
  }
  
  public function convertFrenchDate($date,$format)
  {//convertit la date $date exprim�e au format JJ/MM/YYYY en $format. 
    //Renvoi la date au format $format2
    list($jour1, $mois1, $annee1) = explode('/', $date); 
    $timestamp1 = mktime(0,0,0,$mois1,$jour1,$annee1); 
    return date($format,$timestamp1);
  }
  
  
  /*******************************************************************************
	*  Permet d'ajouter des jours ouvr� � la date actuelle
  *  Les jours ouvr�e sont tous les jours de la semaine sauf samedi et dimanche
	* Parametres : int : le nombre de jour � ajouter. Si n�gatif, retranche le nombre de jour � la date actuelle
	* Retour : objet date : la nouvelle date obtenue                  
	*******************************************************************************/
  public function ajouteJourOuvre($int_nb_jour)
  {
    
    $int_abs_nb_jour=abs($int_nb_jour); //calcul du nombre de jour � ajouter 
    $int_unite=($int_nb_jour>0)?60*60*24:-60*60*24;//s'il faut ajouter ou retrancher un jour
    $int_jour_ajoute=0;  //initialisation du nombre de jour ouvr� d�j� parcouru
    $int_date_courante=$this->getTimeStamp();  //initialisation de la date de d�part
  
    $int_date_courante+=-$int_unite;  //correction du d�part en fonction si on doit avancer ou reculer

    //- on ajoute le nombre de jour ouvr�e voulu
    while($int_jour_ajoute<$int_abs_nb_jour)//tant qu'on n'a pas ajout� le nombre de jour ouvr� voulu
    {
      $secu++; 
      $int_date_courante+=$int_unite;//calcul du nouveau timestamp
      $int_jour=date('w', $int_date_courante); //jour de la semaine 0 (pour dimanche) � 6 (pour samedi)
      if(($int_jour!=0)&&($int_jour!=6))//si on n'est ni samedi ni dimanche
      {
        $int_jour_ajoute++;
      }
      
    }
         
   //- construction de la date d'arriv�e
   $obj_date=new date(date('d/m/Y',$int_date_courante));
   
   return $obj_date;
  }
  
   /*******************************************************************************
        * !!!!!!ATTENTION: l'heure est mise � 12:00:00 !!!!!!
	*  Permet d'ajouter des jours  � la date actuelle, 
 	* Parametres : int : le nombre de jour � ajouter. Si n�gatif, retranche le nombre de jour � la date actuelle
	* Retour : objet date : la nouvelle date obtenue                  
	*******************************************************************************/
public function ajouteJour($int_nb_jour)
{
        if($int_nb_jour==0)
        {return $this;}

        $int_abs_nb_jour=abs($int_nb_jour); //calcul du nombre de jour � ajouter 
        $int_unite=($int_nb_jour>0)?60*60*24:-60*60*24;//s'il faut ajouter ou retrancher un jour (25H pour pallier le d�calage horaire
        $int_jour_ajoute=0;  //initialisation du nombre de jour ouvr� d�j� parcouru

        //initialisation � midi
        $obj_date_courante = new date($this->date("Y-m-d")." 12:00:00");
        $int_date_temp = $obj_date_courante->getTimeStamp();
        //$int_date_courante=$this->getTimeStamp();  //initialisation de la date de d�part

        //$int_date_courante+=-$int_unite;  //correction du d�part en fonction si on doit avancer ou reculer
        //- on ajoute le nombre de jour voulu
        $obj_date_temp = null;
        while($int_jour_ajoute<$int_abs_nb_jour)//tant qu'on n'a pas ajout� le nombre de jour voulu
        { 
                $int_date_temp += $int_unite;// ajout de 25 heures
                $obj_date_temp = new date(date('Y-m-d',$int_date_temp)." 12:00:00"); //remise � midi
                $int_date_temp = $obj_date_temp->getTimeStamp();
                $int_jour_ajoute++;
        }
       return $obj_date_temp;
}
  
   /*******************************************************************************
	*  Permet d'obtenir la date du jour de la semaine courante pass� en param�tre
  *  
	* Parametres : string : le nom du jour (lundi, mardi ... dimanche)
	* Retour : string : la date du jour demand� au format Y-m-d               
	*******************************************************************************/
  public function getJourSemaine($stri_jour)
  {
    $int_jour_courant=$this->date('w');//r�cup�ration du jour courant 0 dimanche, 6 samedi
    $arra_jour=array("dimanche"=>0,"lundi"=>1,"mardi"=>2,"mercredi"=>3,"jeudi"=>4,"vendredi"=>5,"samedi"=>6);
    $int_jour_voulu=$arra_jour[$stri_jour];
    $int_nb_jour_ecart=$int_jour_voulu-$int_jour_courant;
    if($int_jour_courant==0)//si on est dimanche
     {$int_nb_jour_ecart=-2;}
       
    $int_time=$this->getTimestamp()+$int_nb_jour_ecart*60*60*24;//calcul du timestamp de la date voulue
    return date('Y-m-d',$int_time);
  } 
  
  /**
   * julien BAILLON
   * @return string le jour de la semaine en bon fran�ois
   */
  public function getJourEnFrancais(){
        $int_jour_courant=$this->date('w');
        $arra_jour = array(0=>"dimanche",1=>"lundi",2=>"mardi",3=>"mercredi",4=>"jeudi",5=>"vendredi",6=>"samedi");
        return $arra_jour[$int_jour_courant];
  }
  
  
  
  /*******************************************************************************
	*  Permet d'obtenir la date du premier jour de la semaine pass� en param�tre en donnant la date
  *  
	* Parametres : string :  la date de la semaine
	*     (sortie) int :  le premier jour de la semaine
	*     (sortie) int :  le dernier jour de la semaine   
	* Retour : string : la date du jour demand� au format Y-m-d               
	*******************************************************************************/
  public static function getPremierJourSemaineByDate($stri_date,&$int_premier_jour,&$int_dernier_jour)
  {    
      
     
      
    //- recherche du premier jour de la semaine 1 de l'ann�e
   /* $stri_jour=1;
    $stri_date='0'.$stri_jour.'/01/'.$int_year;    
    $obj_date=new date($stri_date);
    $int_num_semaine=$obj_date->date('W');//r�cup�ration du num�ro de semaine
    while(($int_num_semaine<>1)&&($secu<10))
    {
     $secu++;
     $stri_jour++;
     $stri_date='0'.$stri_jour.'/01/'.$int_year;
     $obj_date=new date($stri_date);
     $int_num_semaine=$obj_date->date('W');
    }
    //- cas o� le premier jour de la semaine 1 se trouve � l'ann�e pr�c�dante
    $obj_date_decembre=new date('31/12/'.($int_year-1));
    if($obj_date_decembre->date("W")==1)
    {
     $secu=0;
     $int_num_semaine=52;
     $stri_jour=24;
     $stri_date=$stri_jour.'/12/'.($int_year-1);   
     while(($int_num_semaine<>1)&&($secu<10))
     {
       $secu++;
       $stri_jour++;
       $stri_date=$stri_jour.'/12/'.($int_year-1);   
       
     }
    }*/
	
	
	 $obj_date=new date($stri_date);
   $int_num_semaine=$obj_date->date('W');
   $int_num_annee=$obj_date->date('Y');
   
 // echo"<br>int_num_semaine".$int_num_semaine." int_num_annee ".$int_num_annee;
   
   /*
    
   $obj_date=new date($stri_date);
   $int_num_semaine=$obj_date->date('W');
    * 
    */
	   
    //- calcul du premier jour de la semaine
    $int_time=$obj_date->getTimeStamp();
    $stri_res=date('Y-m-d',$int_time);
    
    $int_premier_jour=$stri_res;
    
    
    //- calcul du dernier jour de la semaine
    $int_time_dernier=$int_time+6*86400;//ajout de 6 jours
    $int_dernier_jour=date('Y-m-d',$int_time_dernier);
    
    
    //Calcul sur la derniere date dans le cas ou les dates sont a cheval entre deux ann�es
    //Romain le 30/06/2014
    /*$obj_date=new date($int_dernier_jour);
    $int_num_annee=$obj_date->date('Y');
     * 
     */
    
	date::getPremierJourSemaine($int_num_semaine,$int_num_annee,$stri_res1,$int_dernier_jour); 
        
        return $stri_res1;
  }
  
  
  
  
  
  
  
  
  
  
  /*******************************************************************************
	*  Permet d'obtenir la date du premier jour de la semaine pass� en param�tre
  *  
	* Parametres : int :  le num�ro de la semaine
	*              int :  le num�ro de l'ann�e
	*     (sortie) int :  le premier jour de la semaine
	*     (sortie) int :  le dernier jour de la semaine   
	* Retour : string : la date du jour demand� au format Y-m-d               
	*******************************************************************************/
  public static function getPremierJourSemaine($int_semaine,$int_year,&$int_premier_jour,&$int_dernier_jour)
  {    
    //- recherche du premier jour de la semaine 1 de l'ann�e
    $stri_jour=1;
    $stri_date='0'.$stri_jour.'/01/'.$int_year;    
    $obj_date=new date($stri_date);
    $int_num_semaine=$obj_date->date('W');//r�cup�ration du num�ro de semaine
    while(($int_num_semaine<>1)&&($secu<10))
    {
     $secu++;
     $stri_jour++;
     $stri_date='0'.$stri_jour.'/01/'.$int_year;
     $obj_date=new date($stri_date);
     $int_num_semaine=$obj_date->date('W');
    }
    //- cas o� le premier jour de la semaine 1 se trouve � l'ann�e pr�c�dante
    $obj_date_decembre=new date('31/12/'.($int_year-1));
    if($obj_date_decembre->date("W")==1)
    {
     $secu=0;
     $int_num_semaine=52;
     $stri_jour=24;
     $stri_date=$stri_jour.'/12/'.($int_year-1);   
     while(($int_num_semaine<>1)&&($secu<10))
     {
       $secu++;
       $stri_jour++;
       $stri_date=$stri_jour.'/12/'.($int_year-1);   
       $obj_date=new date($stri_date);
       $int_num_semaine=$obj_date->date('W');
     }
    }
    
   
    //- calcul du premier jour de la semaine
    $int_time=$obj_date->getTimeStamp()+($int_semaine-1)*60*60*24*7;
    $stri_res=date('Y-m-d',$int_time);
    
    $int_premier_jour=$stri_res;
   // echo"<br> int premier ".$int_premier_jour;
    //- calcul du dernier jour de la semaine
    $int_time_dernier=$int_time+6*86400;//ajout de 6 jours
    $int_dernier_jour=date('Y-m-d',$int_time_dernier);
    
    
    return $stri_res;
  }
  
  /*******************************************************************************
	*  Permet d'obtenir les periodes d'imputations
	* Parametres : int :  le num�ro du mois
	*              int : le num�ro de l'ann�e  
	* Retour : array : les donn�es sur les periodes d'imputation             
	*******************************************************************************/
  public static function getPeriodeImputation($int_mois,$int_annee)
  {
   //echo "ann�e $int_annee : ";
    //- calcul du n� de jour du premier jour du mois
	 $stri_sql ="SELECT  	Periode_Mois ,	Periode_Du 	,Periode_Au
             FROM asis_periode
             WHERE Periode_Annee = ".$int_annee." and Periode_Mois=".$int_mois;
         $obj_querry_select = new querry_select($stri_sql);
         $arra_res = $obj_querry_select->execute("assoc");
		  
    return  $arra_res;
  }
  
  /*******************************************************************************
	*  Permet d'obtenir les semaines d'une periode d'imputations
	* Parametres : int :  le num�ro du mois
	*              int : le num�ro de l'ann�e  
	* Retour : array : les donn�es sur les periodes d'imputation             
	*******************************************************************************/
  public static function getSemaineOfPeriodeImputation($int_mois,$int_annee)
  {
   //echo "ann�e $int_annee : ";
    //- calcul du n� de jour du premier jour du mois
	 $stri_sql ="SELECT  Periode_Du 	,Periode_Au
             FROM asis_periode
             WHERE Periode_Annee = ".$int_annee." and Periode_Mois=".$int_mois;
         $obj_querry_select = new querry_select($stri_sql);
         $arra_res = $obj_querry_select->execute("assoc");
		 
		$Periode_Du=$arra_res[0]['Periode_Du'];
		$arra_Periode_Du=explode("-", $Periode_Du);
		$Periode_Au=$arra_res[0]['Periode_Au'];
		$arra_Periode_Au=explode("-", $Periode_Au);
		
		$mktime_debut=mktime(0, 0, 0, $arra_Periode_Du[1], $arra_Periode_Du[2], $arra_Periode_Du[0]);
		$mktime_fin=mktime(0, 0, 0, $arra_Periode_Au[1], $arra_Periode_Au[2], $arra_Periode_Au[0]);
		
		for($i=$mktime_debut; $i<=$mktime_fin; $i=$i+604800)
		{
		$arra_week=array("semaine"=>date("W",$i),"annee"=>date("Y",$i));
		}
		//echo"<br>".$Periode_Du;
		//var_dump($arra_week);
		//$week_debut=date("W",mktime(0, 0, 0, $arra_Periode_Du[1], $arra_Periode_Du[2], $arra_Periode_Du[0]));
		// echo"<br>".$Periode_Du."semaine debut".$week_debut;  
		//$week_fin=date("W",mktime(0, 0, 0, $arra_Periode_Au[1], $arra_Periode_Au[2], $arra_Periode_Au[0]));
		//echo"-".$Periode_Au."semaine fin".$week_fin;  
		
		
		//$arra_semaine=array($week_debut,$week_fin);
		  
    return  $arra_week;
  }
  
  /*******************************************************************************
	*  Permet d'obtenir les semaines d'une periode d'imputations
	* Parametres : int :  le num�ro du mois
	*              int : le num�ro de l'ann�e  
	* Retour : array : les donn�es sur les periodes d'imputation             
	*******************************************************************************/
  public static function getPeriodeImputationAndWeeks($int_mois,$int_annee)
  {
   //echo "ann�e $int_annee : ";
    //- calcul du n� de jour du premier jour du mois
	 $stri_sql ="SELECT  Periode_Du 	,Periode_Au
             FROM asis_periode
             WHERE Periode_Annee = ".$int_annee." and Periode_Mois=".$int_mois;
         $obj_querry_select = new querry_select($stri_sql);
         $arra_res = $obj_querry_select->execute("assoc");
		 
		$Periode_Du=$arra_res[0]['Periode_Du'];
		$arra_Periode_Du=explode("-", $Periode_Du);
		$Periode_Au=$arra_res[0]['Periode_Au'];
		$arra_Periode_Au=explode("-", $Periode_Au);
		
		$mktime_debut=mktime(0, 0, 0, $arra_Periode_Du[1], $arra_Periode_Du[2], $arra_Periode_Du[0]);
		$mktime_fin=mktime(0, 0, 0, $arra_Periode_Au[1], $arra_Periode_Au[2], $arra_Periode_Au[0]);
		
                $int_week_number =0;
		for($i=$mktime_debut; $i<=$mktime_fin; $i=$i+604800)
		{
		$arra_week=array("semaine"=>date("W",$i),"annee"=>date("Y",$i));
                $int_week_number++;
		}
		//echo"<br>".$Periode_Du;
		//var_dump($arra_week);
		//$week_debut=date("W",mktime(0, 0, 0, $arra_Periode_Du[1], $arra_Periode_Du[2], $arra_Periode_Du[0]));
		// echo"<br>".$Periode_Du."semaine debut".$week_debut;  
		//$week_fin=date("W",mktime(0, 0, 0, $arra_Periode_Au[1], $arra_Periode_Au[2], $arra_Periode_Au[0]));
		//echo"-".$Periode_Au."semaine fin".$week_fin;  
		
		
		//$arra_semaine=array($week_debut,$week_fin);
	$arra_res[0]["Nombre_semaine"] =  $int_week_number;
	$arra_res[0]["Infos_semaine"][] =  $arra_week;
                
    return  $arra_res;
  }
  
   /*******************************************************************************
	*  Permet d'obtenir la date du premier lundi du mois
	* Parametres : int :  le num�ro du mois
	*              int : le num�ro de l'ann�e  
	* Retour : string : la date du jour demand� au format Y-m-d               
	*******************************************************************************/
  public static function getPremierLundiDuMois($int_mois,$int_annee)
  {
   //echo "premier lundi de $int_mois / $int_annee : ";
    //- calcul du n� de jour du premier jour du mois
    $stri_premier_jour=$int_annee.'-'.$int_mois.'-01';
    $obj_date=new date($stri_premier_jour);
    $int_num_jour= $obj_date->date('w');//- 1 c'est lundi, 0 dimanche
    $arra_decalage=array(0=>1,1=>0,2=>6,3=>5,4=>4,5=>3,6=>2);//d�calage � appliquer
    
    $int_lundi=1+$arra_decalage[$int_num_jour];
    
    $stri_date_lundi=$int_annee.'-'.$int_mois.'-0'.$int_lundi;
   //echo "$stri_date_lundi<br />";  
    return  $stri_date_lundi;
    
    
  }       
 
 /*******************************************************************************
	*  Permet d'obtenir le nombre de jour qu'il y a dans un mois
	* Parametres : int :  le num�ro du mois
	*              int : le num�ro de l'ann�e  
	* Retour : int : le nombre de jour        
	*******************************************************************************/
  public static function getNbrJourDansMois($int_mois,$int_annee)
  {
   $nb_jour = date('t',mktime(0, 0, 0, $int_mois, 1, $int_annee)); //calcul du nombre de jour dans le mois
   return $nb_jour;
  }   
  
  //Permet d'obtenir le dernier jour du mois sous forme Y-m-d
  public function getDernierJourMois($stri_eng_date)
  {
      $obj_date=new date($stri_eng_date);
      $int_year=$obj_date->date('Y');
      $int_month=$obj_date->date('m');
      $int_nb_jour_month=date::getNbrJourDansMois($int_month,$int_year);
      $stri_date_fin=$int_year.'-'.$int_month.'-'.$int_nb_jour_month;
      
      return $stri_date_fin;

  }
  
  /*******************************************************************************
	*  Permet d'obtenir le nom du mois
	* Parametres : int :  le num�ro du mois
	*             
	* Retour : var : le nom du mois        
	*******************************************************************************/
	public static function convert_mois($int_mois,$type='normal')
	{
		if($type=='court')
		{
			$T_Mois=array(1=>_J,2=>_F,3=>_M,4=>_A,5=>_M,6=>_J,7=>_J,8=>_A,9=>_S,10=>_O,11=>_N,12=>_D);
		}
		else
		{
			$T_Mois=array(1=>_JANVIER,2=>_FEVRIER,3=>_MARS,4=>_AVRIL,5=>_MAI,6=>_JUIN,7=>_JUILLET,8=>_AOUT,9=>_SEPTEMBRE,10=>_OCTOBRE,11=>_NOVEMBRE,12=>_DECEMBRE);
		}
		return $T_Mois[$int_mois];
  }
  
  /*******************************************************************************
	*  Permet d'obtenir le nom des 6 mois du trimestre en cours et suivant
	* Parametres : int :  le num�ro du mois
	*             
	* Retour : var : le numero du premier mois du trimestre en cours
	*******************************************************************************/
	public static function getMoisOfTrimestre($int_mois)
	{
		if($int_mois>=1 and $int_mois<=3)
		{
			$arra_mois=array(
			array('mois'=>'1','annee'=>date('Y')),
			array('mois'=>'2','annee'=>date('Y')),
			array('mois'=>'3','annee'=>date('Y')),
			array('mois'=>'4','annee'=>date('Y')),
			array('mois'=>'5','annee'=>date('Y')),
			array('mois'=>'6','annee'=>date('Y')),
			array('mois'=>'7','annee'=>date('Y')),
			array('mois'=>'8','annee'=>date('Y')),
			array('mois'=>'9','annee'=>date('Y')));
		}
		elseif($int_mois>=4 and $int_mois<=6)
		{
			$arra_mois=array(
			array('mois'=>'4','annee'=>date('Y')),
			array('mois'=>'5','annee'=>date('Y')),
			array('mois'=>'6','annee'=>date('Y')),
			array('mois'=>'7','annee'=>date('Y')),
			array('mois'=>'8','annee'=>date('Y')),
			array('mois'=>'9','annee'=>date('Y')),
			array('mois'=>'10','annee'=>date('Y')),
			array('mois'=>'11','annee'=>date('Y')),
			array('mois'=>'12','annee'=>date('Y')));
		}
		elseif($int_mois>=7 and $int_mois<=9)
		{
			$arra_mois=array(
			array('mois'=>'7','annee'=>date('Y')),
			array('mois'=>'8','annee'=>date('Y')),
			array('mois'=>'9','annee'=>date('Y')),
			array('mois'=>'10','annee'=>date('Y')),
			array('mois'=>'11','annee'=>date('Y')),
			array('mois'=>'12','annee'=>date('Y')),
			array('mois'=>'1','annee'=>date('Y')+1),
			array('mois'=>'2','annee'=>date('Y')+1),
			array('mois'=>'3','annee'=>date('Y')+1));
		}
		elseif($int_mois>=10 and $int_mois<=12)
		{
			$arra_mois=array(
			array('mois'=>'10','annee'=>date('Y')),
			array('mois'=>'11','annee'=>date('Y')),
			array('mois'=>'12','annee'=>date('Y')),
			array('mois'=>'1','annee'=>date('Y')+1),
			array('mois'=>'2','annee'=>date('Y')+1),
			array('mois'=>'3','annee'=>date('Y')+1),
			array('mois'=>'4','annee'=>date('Y')+1),
			array('mois'=>'5','annee'=>date('Y')+1),
			array('mois'=>'6','annee'=>date('Y')+1));
			
		}
	    
		return $arra_mois;
  }
  /*******************************************************************************
	*  Permet d'obtenir le nom des 6 mois du trimestre en cours et suivant
	* Parametres : int :  le num�ro du mois
	*             
	* Retour : var : le numero du premier mois du trimestre en cours
	*******************************************************************************/
	public static function getTrimestreV2($int_nb_trimestre)
	{
            
            
            $int_mois=date("n");
            
		if($int_mois>=1 and $int_mois<=3)
		{
                    
                    
                    $arra_mois=array(
			array('trimestre'=>'4','annee'=>date('Y')-1,'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y'),'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y'),'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y'),'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y'),'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+1,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+1,'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y')+1,'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y')+1,'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+2,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+2,'mois'=>array('04','05','06')),
                        array('trimestre'=>'3','annee'=>date('Y')+2,'mois'=>array('07','08','09')),
                        array('trimestre'=>'4','annee'=>date('Y')+2,'mois'=>array('10','11','12')));
                    
		}
		elseif($int_mois>=4 and $int_mois<=6)
		{
                   
                    
                    $arra_mois=array(
			array('trimestre'=>'1','annee'=>date('Y'),'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y'),'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y'),'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y'),'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+1,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+1,'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y')+1,'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y')+1,'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+2,'mois'=>array('01','02','03')),
                        array('trimestre'=>'2','annee'=>date('Y')+2,'mois'=>array('04','05','06')),
                        array('trimestre'=>'3','annee'=>date('Y')+2,'mois'=>array('07','08','09')),
                        array('trimestre'=>'4','annee'=>date('Y')+2,'mois'=>array('10','11','12')),   
                        array('trimestre'=>'1','annee'=>date('Y')+3,'mois'=>array('01','02','03')));
                    
		}
		elseif($int_mois>=7 and $int_mois<=9)
		{
                    
                    $arra_mois=array(
			array('trimestre'=>'2','annee'=>date('Y'),'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y'),'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y'),'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+1,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+1,'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y')+1,'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y')+1,'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+2,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+2,'mois'=>array('04','05','06')),
                        array('trimestre'=>'3','annee'=>date('Y')+2,'mois'=>array('07','08','09')),
                        array('trimestre'=>'4','annee'=>date('Y')+2,'mois'=>array('10','11','12')),
                        array('trimestre'=>'1','annee'=>date('Y')+3,'mois'=>array('01','02','03')),
                        array('trimestre'=>'2','annee'=>date('Y')+3,'mois'=>array('04','05','06')));
                    
		}
		elseif($int_mois>=10 and $int_mois<=12)
		{
                    $arra_mois=array(
			array('trimestre'=>'3','annee'=>date('Y'),'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y'),'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+1,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+1,'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y')+1,'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y')+1,'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+2,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+2,'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y')+2,'mois'=>array('07','08','09')),
                        array('trimestre'=>'4','annee'=>date('Y')+2,'mois'=>array('10','11','12')),
                        array('trimestre'=>'1','annee'=>date('Y')+3,'mois'=>array('01','02','03')),
                        array('trimestre'=>'2','annee'=>date('Y')+3,'mois'=>array('04','05','06')),
                        array('trimestre'=>'3','annee'=>date('Y')+3,'mois'=>array('07','08','09')));
			
		}
                
	    
                for($i=0;$i<$int_nb_trimestre+1;$i++)
                    {
                        $arra_temp[] = $arra_mois[$i];
                    }
                    
                    
		return $arra_temp;
  }
  
   /*******************************************************************************
	*  Permet d'obtenir le nom des 6 mois du trimestre en cours et suivant
	* Parametres : int :  le num�ro du mois
	*             
	* Retour : var : le numero du premier mois du trimestre en cours
	*******************************************************************************/
	public static function getTrimestre()
	{
            
            $int_mois=date("n");
            
		if($int_mois>=1 and $int_mois<=3)
		{
                    $arra_mois=array(
			array('trimestre'=>'4','annee'=>date('Y')-1,'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y'),'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y'),'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y'),'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y'),'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+1,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+1,'mois'=>array('04','05','06')),
			//array('trimestre'=>'3','annee'=>date('Y')+2,'mois'=>array('07','08','09')));
			array('trimestre'=>'3','annee'=>date('Y')+1,'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y')+1,'mois'=>array('10','11','12')));
                    /*
			$arra_mois=array(
			array('trimestre'=>'1','annee'=>date('Y'),'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y'),'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y'),'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y'),'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+1,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+1,'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y')+1,'mois'=>array('07','08','09')),
			//array('trimestre'=>'4','annee'=>date('Y')+1,'mois'=>array('10','11','12')));
			array('trimestre'=>'4','annee'=>date('Y')+1,'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+2),'mois'=>array('01','02','03'));
                     * 
                     */
		}
		elseif($int_mois>=4 and $int_mois<=6)
		{
                    $arra_mois=array(
			array('trimestre'=>'1','annee'=>date('Y'),'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y'),'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y'),'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y'),'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+1,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+1,'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y')+1,'mois'=>array('07','08','09')),
			//array('trimestre'=>'4','annee'=>date('Y')+1,'mois'=>array('10','11','12')));
			array('trimestre'=>'4','annee'=>date('Y')+1,'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+2,'mois'=>array('01','02','03')));
                    /*
			$arra_mois=array(
			array('trimestre'=>'2','annee'=>date('Y'),'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y'),'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y'),'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+1,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+1,'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y')+1,'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y')+1,'mois'=>array('10','11','12')),
			//array('trimestre'=>'1','annee'=>date('Y')+2,'mois'=>array('01','02','03')));
			array('trimestre'=>'1','annee'=>date('Y')+2,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+2,'mois'=>array('04','05','06')));
                     * 
                     */
		}
		elseif($int_mois>=7 and $int_mois<=9)
		{
                    
                    $arra_mois=array(
			array('trimestre'=>'2','annee'=>date('Y'),'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y'),'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y'),'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+1,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+1,'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y')+1,'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y')+1,'mois'=>array('10','11','12')),
			//array('trimestre'=>'1','annee'=>date('Y')+2,'mois'=>array('01','02','03')));
			array('trimestre'=>'1','annee'=>date('Y')+2,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+2,'mois'=>array('04','05','06')));
                    /*
			$arra_mois=array(
			array('trimestre'=>'3','annee'=>date('Y'),'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y'),'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+1,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+1,'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y')+1,'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y')+1,'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+2,'mois'=>array('01','02','03')),
			//array('trimestre'=>'2','annee'=>date('Y')+2,'mois'=>array('04','05','06')));
			array('trimestre'=>'2','annee'=>date('Y')+2,'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y')+2,'mois'=>array('07','08','09')));
                     * 
                     */
		}
		elseif($int_mois>=10 and $int_mois<=12)
		{
                    $arra_mois=array(
			array('trimestre'=>'3','annee'=>date('Y'),'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y'),'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+1,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+1,'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y')+1,'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y')+1,'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+2,'mois'=>array('01','02','03')),
			//array('trimestre'=>'2','annee'=>date('Y')+2,'mois'=>array('04','05','06')));
			array('trimestre'=>'2','annee'=>date('Y')+2,'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y')+2,'mois'=>array('07','08','09')));
                    /*
			$arra_mois=array(
			array('trimestre'=>'4','annee'=>date('Y'),'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+1,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+1,'mois'=>array('04','05','06')),
			array('trimestre'=>'3','annee'=>date('Y')+1,'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y')+1,'mois'=>array('10','11','12')),
			array('trimestre'=>'1','annee'=>date('Y')+2,'mois'=>array('01','02','03')),
			array('trimestre'=>'2','annee'=>date('Y')+2,'mois'=>array('04','05','06')),
			//array('trimestre'=>'3','annee'=>date('Y')+2,'mois'=>array('07','08','09')));
			array('trimestre'=>'3','annee'=>date('Y')+2,'mois'=>array('07','08','09')),
			array('trimestre'=>'4','annee'=>date('Y')+2,'mois'=>array('10','11','12')));
                     * 
                     */
			
		}
	    
		return $arra_mois;
  }
  
  /*******************************************************************************
	*  Permet d'obtenir le numero du trimestre en fonction de la date
	* Parametres : int :  le num�ro du mois
	*             
	* Retour : var : le numero du premier mois du trimestre en cours
	*******************************************************************************/
  public function getTrimestreByDate($stri_date) 
	{	
		//mois en cours par defaut
		
		$mois_en_cours=$this->int_mouth;
		if($mois_en_cours>=1 and $mois_en_cours<=3) $date_mois1_tri=1;
		elseif($mois_en_cours>=4 and $mois_en_cours<=6) $date_mois1_tri=2;
		elseif($mois_en_cours>=7 and $mois_en_cours<=9) $date_mois1_tri=3;
		elseif($mois_en_cours>=10 and $mois_en_cours<=12) $date_mois1_tri=4;
	
		return $date_mois1_tri;
	}
  
  /**
   *  Permet de d�tecter automatiquement le format d'une date
   *  Format d�tect�s : de type DD/MM/YYYY HH24:MI:SS ou
   *                    de type YYYY-MM-DD HH24:MI:SS
   *  Exemple :
   *           
   *    01/12/1900_08:55:02 => DD/MM/YYYY_HH24:MI:SS
   *    01/12/1900 08:55:02 => DD/MM/YYYY HH24:MI:SS
   *    01/12/1900 08:55    => DD/MM/YYYY HH24:MI  
   *    1900-12-01 08:55:02 => YYYY-MM-DD HH24:MI:SS
   *    1900-12-01 08:55    => YYYY-MM-DD HH24:MI         
   */           
  public function autoDetectFormat($stri_date)
  {
   //une date si / ou -
   $bool_date=(strpos($stri_date, "/")!==false)?true:false;
   $bool_date=(strpos($stri_date, "-")!==false)?true:$bool_date;
   
   //un temps si :
   $bool_time=(strpos($stri_date, ":")!==false)?true:false;
   $stri_date_format="";
   if($bool_date)//s'il y a la date dans les donn�es fournies
   {
    $stri_separator=(strpos($stri_date, "_")!==false)?"_":" ";//le sp�rateur est soit _ soit espace
   
    $arra_token=explode($stri_separator, $stri_date);
   
    $stri_token_date=$arra_token[0];
    $stri_token_time=$arra_token[1];
    
    //d�tection du format de la date
    $stri_date_format="YYYY-MM-DD";
    if(strpos($stri_token_date, "/")!==false)
    {$stri_date_format="DD/MM/YYYY";}
    
    
    /*
    $stri_date_separator=(strpos($stri_token_date, "/")!==false)?"/":"-";
    $arra_token_date=explode($stri_date_separator,$stri_token_date);
    
    $stri_token1=array_shift($arra_token_date);
    $stri_token2=array_shift($arra_token_date);
    $stri_token3=array_shift($arra_token_date);*/
    
   
   } 
   
   if($bool_time)//si on a d�tect� des heures
   {
    $stri_token_time=($stri_date_format=="")?$stri_date:$stri_token_time;//on r�cup�re le token repr�sentant l'heure
    $arra_token_time=explode(":",$stri_token_time);
        
    //d�tection du format du temps
    $stri_time_format="HH24:MI:SS";
    if(count($arra_token_time)==2)//s'il n'y a que deux token, les secondes ne sont pas repr�sent�es
    {
     $stri_time_format="HH24:MI";
    }
   }
   
   $stri_separator=(($stri_date_format!="")&&($stri_time_format!=""))?"_":"";//s'il y a les deux parties de la date
   $stri_format=$stri_date_format.$stri_separator.$stri_time_format;
   
   if($stri_format=="")//si pas de format trouv�
   {return false;} 
 
 
   return  $stri_format;
  
  } 
  
  /*
    Permet de savoir si une date respecte ou non un format donn�
  */
  public function dateMatchFormat($stri_date,$stri_format)
  {
    $stri_format=str_replace("HH24", "HH", $stri_format);
    
    $int_len_format=strlen($stri_format);
    $int_len_date=strlen($stri_date);
   
    
    
    if($int_len_format!=$int_len_date)//si la date et le format n'ont pas le m�me nombre de caract�re
    {return false;}
        
    $arra_token=array("DD","MM","YYYY","HH","MI","SS");
     
    //Recherche des token dans la date
    foreach($arra_token as $stri_token)
    {
      $int_start=strpos($stri_format, $stri_token);
      $int_len=strlen($stri_token);
           
      if($int_start!==false) //si le token est pr�sent
      {   
       //extraction du token dans la date
       $stri_find_token=substr($stri_date, $int_start ,$int_len);
       $arra_find_token[$stri_token]=$stri_find_token;
      }
      
    }
    
    $bool_ok=true;
    //v�rification des token
    foreach($arra_find_token as $stri_token=>$stri_find_token)
    {
     switch($stri_token)//test des diff�rents token possibles
     {
      case "YYYY":
        $bool_token_ok=(($stri_find_token>1000)&&($stri_find_token<9999))?true:false;
      break;
      case "MM":
        $bool_token_ok=(($stri_find_token>0)&&($stri_find_token<13))?true:false;
      break;
      case "DD":
        $bool_token_ok=(($stri_find_token>0)&&($stri_find_token<32))?true:false;
      break;
      case "HH":
        $bool_token_ok=(($stri_find_token>=0)&&($stri_find_token<24))?true:false;
      break;
      case "MI":
        $bool_token_ok=(($stri_find_token>=0)&&($stri_find_token<60))?true:false;
      break;
      case "SS":
        $bool_token_ok=(($stri_find_token>=0)&&($stri_find_token<60))?true:false;
      break;
      default;
      $bool_token_ok=true;
     }
    
     $bool_ok=$bool_ok&&$bool_token_ok;//synth�se, un seul token � false, toute la date est false
    }
    
    return $bool_ok;    
   
    
  } 
  
  /**
   *  Permet de d�tecter automatiquement le format de la date
   *  et de dire si elle correspond au format   
   *
   * Param�tres : sting : la date � tester
   * Retour :  string   : format de la date (la date est ok)
   *           bool     : false : la date ne correspond pas         
   */        
  public function detectAndMatchFormat($stri_date)
  {
    $stri_format=$this->autoDetectFormat($stri_date);
    if($stri_format==false)//si pas de format trouv�
    {
     return false; //la date ne peut pas �tre correcte
    }
    
    $bool_ok= $this->dateMatchFormat($stri_date,$stri_format);
    
    return ($bool_ok)?$stri_format:false;
  }
  
   /**
   *  Permet de d'obtenir la liste des jour f�ri� d'une ann�e
   * Param�tres : int : l'ann�e � consid�rer
   * Retour :  array[date]="jour" : la liste des jour f�ri�       
   */        
  public static function getListeFerie($int_annee)
  {
       
    //ann�e courrante
    $an=$int_annee;
    $jour = 3600*24; 
    //calcul du lundi de paque
    $stri_mois_paques = date( "m", easter_date($an)+1*$jour);
    $stri_jour_paques = date( "d", easter_date($an)+1*$jour); 
    $stri_lundi_paques=$int_annee.'-'.$stri_mois_paques.'-'.$stri_jour_paques;
    
    
    //calcul de l'ascencion
    $stri_mois_ascencion = date( "m", easter_date($an)+39*$jour);
    $stri_jour_ascencion = date( "d", easter_date($an)+39*$jour);
    $stri_jeudi_ascencion=$int_annee.'-'.$stri_mois_ascencion.'-'.$stri_jour_ascencion;
  
  
    //calcul pentecote
    $stri_mois_pentecote = date( "m", easter_date($an)+50*$jour);
    $stri_jour_pentecote = date( "d", easter_date($an)+50*$jour);
    $stri_lundi_pentecote=$int_annee.'-'.$stri_mois_pentecote.'-'.$stri_jour_pentecote;
    
   
    //tableau des jour fran�ais 
  
     
     $arra_ferie[$int_annee."-01-01"]="Jour de l'an";         
     $arra_ferie[$stri_lundi_paques]="Lundi de Paques";       
     $arra_ferie[$stri_jeudi_ascencion]="Jeudi de l'ascencion";   
     //$arra_ferie[$stri_lundi_pentecote]=4;    //Lundi_Pentecote
     $arra_ferie[$int_annee."-05-01"]="F�te du travail";                 
     $arra_ferie[$int_annee."-05-08"]="Armistice 39-45";                 
     $arra_ferie[$int_annee."-07-14"]="F�te nationale";                 
     $arra_ferie[$int_annee."-08-15"]="Assomption";                 
     $arra_ferie[$int_annee."-11-01"]="Toussaint";                  
     $arra_ferie[$int_annee."-11-11"]="Armistice 14-18";               
     $arra_ferie[$int_annee."-12-25"]="No�l";                
    
    return  $arra_ferie;
  }
  
  
  /**
   *  Permet de d�termin� lister les jours feri�
   *
   * Param�tres : string : la date � tester au format Y-m-d
   * Retour :  array : tableau des jours feri�s
   */        
  public function listeFerie($stri_date="")
  {
    //- gestion de la date � analyser
    $stri_date=($stri_date=="")?date('Y-m-d'):$stri_date;
    $arra_part=explode('-', $stri_date);
  
  
    //ann�e courrante
    $an=$arra_part[0];
    $jour = 3600*24; 
    //calcul du lundi de paque
    $stri_mois_paques = date( "m", easter_date($an)+1*$jour);
    $stri_jour_paques = date( "d", easter_date($an)+1*$jour); 
    $stri_lundi_paques=$stri_mois_paques."-".$stri_jour_paques;
    
    
    //calcul de l'ascencion
    $stri_mois_ascencion = date( "m", easter_date($an)+39*$jour);
    $stri_jour_ascencion = date( "d", easter_date($an)+39*$jour);
    $stri_jeudi_ascencion=$stri_mois_ascencion."-".$stri_jour_ascencion;
  
  
    //calcul pentecote
    $stri_mois_pentecote = date( "m", easter_date($an)+50*$jour);
    $stri_jour_pentecote = date( "d", easter_date($an)+50*$jour);
    $stri_lundi_pentecote=$stri_mois_pentecote."-".$stri_jour_pentecote;
    
   
     
     $arra_ferie[$an."-01-01"]=_JOUR_DE_L_AN;                      //Jour de l'an
     $arra_ferie[$an."-".$stri_lundi_paques]=_PAQUES;                 // Lundi de Paques
     $arra_ferie[$an."-".$stri_jeudi_ascencion]=_JEUDI_ASCENCION;     //Jeudi de l'ascencion
     //$arra_ferie[$stri_lundi_pentecote]=4;                  //Lundi_Pentecote
     $arra_ferie[$an."-05-01"]=_FETE_DU_TRAVAIL;                   //F�te du travail
     $arra_ferie[$an."-05-08"]=_ARMISTICE_39_45;                   //Armistice 39-45 
     $arra_ferie[$an."-07-14"]=_FETE_NATIONALE;                    //F�te nationale
     $arra_ferie[$an."-08-15"]=_ASSOMPTION;                        //Assomption
     $arra_ferie[$an."-11-01"]=_TOUSSAINT;                         //Toussaint
     $arra_ferie[$an."-11-11"]=_ARMISTICE_14_18;                   //Armistice 14-18
     $arra_ferie[$an."-12-25"]=_NOEL;                              //No�l      
     
     return $arra_ferie;
  }
  
   /**
   *  Permet de d�termin� si une date est feri�
   *
   * Param�tres : string : la date � tester au format d/m/Y
   * Retour :  mixed : false : le jour n'est pas feri�
   *                   int : le code du jour f�ri�         
   */        
  public function estFerie($stri_date="")
  {
    //- gestion de la date � analyser
    $stri_date=($stri_date=="")?date('d/m/Y'):$stri_date;
    $arra_part=explode('/', $stri_date);
    $stri_mY=$arra_part[0].'/'.$arra_part[1]; 
      
    
    //ann�e courrante
    $an=$arra_part[2];
    $jour = 3600*24; 
    //calcul du lundi de paque
    $stri_mois_paques = date( "m", easter_date($an)+1*$jour);
    $stri_jour_paques = date( "d", easter_date($an)+1*$jour); 
    $stri_lundi_paques=$stri_jour_paques."/".$stri_mois_paques;
    
    
    //calcul de l'ascencion
    $stri_mois_ascencion = date( "m", easter_date($an)+39*$jour);
    $stri_jour_ascencion = date( "d", easter_date($an)+39*$jour);
    $stri_jeudi_ascencion=$stri_jour_ascencion."/".$stri_mois_ascencion;
  
  
    //calcul pentecote
    $stri_mois_pentecote = date( "m", easter_date($an)+50*$jour);
    $stri_jour_pentecote = date( "d", easter_date($an)+50*$jour);
    $stri_lundi_pentecote=$stri_jour_pentecote."/".$stri_mois_pentecote;
     
    
   
    //tableau des jour fran�ais 
    /* $arra_ferie[1]="01/01";//Jour de l'an
     $arra_ferie[2]=$stri_lundi_paques;// Lundi de Paques
     $arra_ferie[3]=$stri_jeudi_ascencion;//Jeudi de l'ascencion
     $arra_ferie[4]=$stri_lundi_pentecote;//Lundi_Pentecote
     $arra_ferie[5]="01/05";//F�te du travail
     $arra_ferie[6]="08/05";//Armistice 39-45 
     $arra_ferie[7]= "14/07";//F�te nationale
     $arra_ferie[8] ="15/08";//Assomption
     $arra_ferie[9]="01/11";//Toussaint
     $arra_ferie[10]="11/11";//Armistice 14-18
     $arra_ferie[11]="25/12";//No�l   */
     
     $arra_ferie["01/01"]=1;                  //Jour de l'an
     $arra_ferie[$stri_lundi_paques]=2;       // Lundi de Paques
     $arra_ferie[$stri_jeudi_ascencion]=3;    //Jeudi de l'ascencion
     //$arra_ferie[$stri_lundi_pentecote]=4;    //Lundi_Pentecote
     $arra_ferie["01/05"]=5;                  //F�te du travail
     $arra_ferie["08/05"]=6;                  //Armistice 39-45 
     $arra_ferie["14/07"]=7;                  //F�te nationale
     $arra_ferie["15/08"]=8;                  //Assomption
     $arra_ferie["01/11"]=9;                  //Toussaint
     $arra_ferie["11/11"]=10;                 //Armistice 14-18
     $arra_ferie["25/12"]=11;                 //No�l 
     
     //echo "<br>".$stri_mY;
     if(isset($arra_ferie[$stri_mY]))
     {return $arra_ferie[$stri_mY];}
     
     return false;
  }
    

  
  // Fonction permettant de compter le nombre de jours ouvr�s entre deux dates
function get_nb_open_days($date_start, $date_stop) 
{
	list($dbconn) = pnDBGetConn();
	$arr_bank_holidays = array(); // Tableau des jours feri�s
	// On boucle dans le cas o� l'ann�e de d�part serait diff�rente de l'ann�e d'arriv�e
	$diff_year = date('Y', $date_stop) - date('Y', $date_start);
	
	$year = (int)date('Y', $date_start) + $i;
	
  $date=new date();
 
 // $arr_bank_holidays[]=$date->estFerie($Ferie_Date);
                                     
		//$arr_bank_holidays=stock_jour_ferie($tab,$date_start,$date_stop);
	//print_r($arr_bank_holidays);
	$nb_days_open = 0;
      
  
  // Mettre <= si on souhaite prendre en compte le dernier jour dans le d�compte  //    
while ($date_start <= $date_stop) 
	{
    //echo "<br>".date('d/m/Y',$date_start);
    // Si le jour suivant n'est ni un dimanche (0) ou un samedi (6), ni un jour f�ri�, on incr�mente les jours ouvr�s
		if (!in_array(date('w', $date_start), array(0, 6)) && !in_array(date(date('Y', $date_start).'-m-d', $date_start)) && (!$date->estFerie(date('d/m/Y',$date_start)))) 
		{
			$nb_days_open++;
		}
		$date_start = mktime(date('H', $date_start), date('i', $date_start), date('s', $date_start), date('m', $date_start), date('d', $date_start) + 1, date('Y', $date_start));
	}
  
        
	return $nb_days_open;        
}


  // Fonction permettant de compter le nombre de jours ouvr�s entre deux dates
/*
function get_nb_open_days_V1($date_start, $date_stop) 
{
	list($dbconn) = pnDBGetConn();
	$arr_bank_holidays = array(); // Tableau des jours feri�s
	// On boucle dans le cas o� l'ann�e de d�part serait diff�rente de l'ann�e d'arriv�e
	$diff_year = date('Y', $date_stop) - date('Y', $date_start);
	
	$year = (int)date('Y', $date_start) + $i;
	// Liste des jours feri�s
	$sql="SELECT Ferie_Date FROM asis_Ferie WHERE Ferie_Date between '".date(date('Y', $date_start).'-m-d', $date_start)."' and '".date(date('Y', $date_stop).'-m-d', $date_stop)."'";
  $result=$dbconn->Execute($sql);
	for(;!$result->EOF;$result->MoveNext())
	{
		list($Ferie_Date) = $result->fields;
		$arr_bank_holidays[]=$Ferie_Date;
	}
		//$arr_bank_holidays=stock_jour_ferie($tab,$date_start,$date_stop);
	//print_r($arr_bank_holidays);
	$nb_days_open = 0;
	// Mettre <= si on souhaite prendre en compte le dernier jour dans le d�compte
	while ($date_start <= $date_stop) 
	{
		// Si le jour suivant n'est ni un dimanche (0) ou un samedi (6), ni un jour f�ri�, on incr�mente les jours ouvr�s
		if (!in_array(date('w', $date_start), array(0, 6)) && !in_array(date(date('Y', $date_start).'-m-d', $date_start), $arr_bank_holidays)) 
		{
			$nb_days_open++;
		}
		$date_start = mktime(date('H', $date_start), date('i', $date_start), date('s', $date_start), date('m', $date_start), date('d', $date_start) + 1, date('Y', $date_start));
	}
	return $nb_days_open;
}
 //*/  
        /**
         * julien BAILLON: retourne un objet Date du lundi pr�c�dant, retourne la date actuelle si c'est d�j� un lundi
         * @return \date lundi pr�c�dant
         */
        public function getLundiPrecedant(){
                //calcul du nombre de jours � soustraire
                $int_jour_de_la_semaine = $this->date("N");
                $int_nombre_de_jour_a_soustraire = $int_jour_de_la_semaine-1;
                
                //instanciation de la nouvelle date
                $date_lundi =  new date($this->date('Y-m-d'));
                return $date_lundi->ajouteJour(-$int_nombre_de_jour_a_soustraire);
        }
        
         /**
         * julien BAILLON: retourne un objet Date du lundi pr�c�dant, retourne la date actuelle si c'est d�j� un lundi
         * @return \date lundi pr�c�dant
         */
        public function getLundiSuivant(){
                //calcul du nombre de jours � soustraire
                $int_jour_de_la_semaine = $this->date("N");
                $int_nombre_de_jour_a_ajouter = 7 -$int_jour_de_la_semaine +1;
                
                //instanciation de la nouvelle date
                if ($int_jour_de_la_semaine == 1)
                {
                    return  new date($this->date('Y-m-d'));
                }
                $date_lundi =  new date($this->date('Y-m-d'));
                return $date_lundi->ajouteJour($int_nombre_de_jour_a_ajouter);
        }
        
        /**
         * julien BAILLON: retourne un objet Date du lundi pr�c�dant, retourne la date actuelle si c'est d�j� un lundi
         * @return \date lundi pr�c�dant
         */
        public function getDimancheSuivant(){
            
                //calcul du nombre de jours � soustraire
                $int_jour_de_la_semaine = $this->date("N");
                $int_nombre_de_jour_a_ajouter =  7 -$int_jour_de_la_semaine;
                
                //instanciation de la nouvelle date
                $date_dimanche =  new date($this->date('Y-m-d'));
                return $date_dimanche->ajouteJour($int_nombre_de_jour_a_ajouter);
        }
        
        /**
         * julien BAILLON: retourne un objet Date du lundi pr�c�dant, retourne la date actuelle si c'est d�j� un lundi
         * @return \date lundi pr�c�dant
         */
        public function getDimanchePrecedant(){
            
                //calcul du nombre de jours � soustraire
                $int_jour_de_la_semaine = $this->date("N");
                
                //instanciation de la nouvelle date
                if ($int_jour_de_la_semaine == 7)
                {
                    return  new date($this->date('Y-m-d'));
                }
                $date_dimanche =  new date($this->date('Y-m-d'));
                return $date_dimanche->ajouteJour(-$int_jour_de_la_semaine);
        }
        
        public function __toString() {
                //avec ou sans les minutes?
                if($this->int_hour == 0 && $this->int_minut == 0 && $this->int_second == 0){
                      return $this->date('d/m/Y'); 
                }
                else{
                      return $this->date('d/m/Y H:i:s');  
                }
        }
        
        
        
        /**
         * Conversion d'une date en un libelle 
         * Remonte le temps �coul� => Moins d'une minute, 1 heure 23 minutes, ....
         * 
         * @param type $date1
         * @param type $date2
         * 
         * @return string : Exemples :
                - Moins d'une minutes
                - 26 minutes
                - 1 heure 26 minutes
                - 3 jours ...
         */
        public static function getDiffDate($date1,$date2=null)
        {
            //- Les donn�es
            $stri_date1 = $date1;
            $stri_date2 = ($date2==NULL) ? date('Y/m/d H:i:s') : $date2;


            //Dans le cas d'une comparaison entre deux date
            $s = strtotime($stri_date2)-strtotime($stri_date1);
            //$d = intval($s/86400)+1; 
            $d = intval($s/86400); 

            $diff=self::getTimeDifference($stri_date1, $stri_date2) ;
            //Si ==1 jour -> conversion en minute
            //if ($d==1 && $s < 86400)
            if ($s < 86400)
            {
                $diff=self::getTimeDifference($stri_date1, $stri_date2) ;

                if ($diff['hours'] == 0 && $diff['minutes']<=1)
                {
                    //Si moins d'une minutes
                    return __LIB_LESS_ONE_MINUTE;
                }
                elseif($diff['hours'] == 0)
                {
                    //Uniquement les minutes
                    $stri_diff = sprintf( '%01d '.__LIB_MINUTES, $diff['minutes'] );
                    return $stri_diff;

                }
                $stri_diff = sprintf( '%01d '.__LIB_HEURES.' %02d '.__LIB_MINUTES, $diff['hours'], $diff['minutes'] );
                return $stri_diff;
            }

            return $d.' '. __LIB_DAYS;  //Retourne ex: "2 jours"
            //return $d.' '. __LIB_DAYS .' ' .sprintf( '%01d '.__LIB_HEURES.' %02d '.__LIB_MINUTES, $diff['hours'], $diff['minutes'] );

           
    
        }
       
        
        
        private static function getTimeDifference($start, $end )
        {
            $uts['start']      =    strtotime( $start );
            $uts['end']        =    strtotime( $end );
            if( $uts['start']!==-1 && $uts['end']!==-1 )
            {
                if( $uts['end'] >= $uts['start'] )
                {
                    $diff    =    $uts['end'] - $uts['start'];
                    if( $days=intval((floor($diff/86400))) )
                        $diff = $diff % 86400;
                    if( $hours=intval((floor($diff/3600))) )
                        $diff = $diff % 3600;
                    if( $minutes=intval((floor($diff/60))) )
                        $diff = $diff % 60;
                    $diff    =    intval( $diff );            
                    return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
                }
                else
                {
                    //trigger_error( "Ending date/time is earlier than the start date/time", E_USER_WARNING );
                    
                    $diff    =    $uts['start'] - $uts['end'];
                    if( $days=intval((floor($diff/86400))) )
                        $diff = $diff % 86400;
                    if( $hours=intval((floor($diff/3600))) )
                        $diff = $diff % 3600;
                    if( $minutes=intval((floor($diff/60))) )
                        $diff = $diff % 60;
                    $diff    =    intval( $diff );            
                    return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
                    
                }
            }
            else
            {
                trigger_error( "Invalid date/time data detected", E_USER_WARNING );
            }
            return( false );
        }
        /**
         * Calcule l'interval entre deux date et le renvoie sous forme d'un int dans l'unit� demand�
         * @param string $date1 chaine de caract�re contenant la date au format 'Y/m/d H:i:s'
         * @param string $date2 chaine de caract�re contenant la date au format 'Y/m/d H:i:s'
         * @param string $unite unit� dans laquel sera le r�sultat retourn� ("h" pour heure "i" pour minute "s" pour seconde
         *  "d" pour jour "m" pour moi "y" pour ann�e
         * @return int
         */
        public function diffDateByUnity ($date1, $date2, $unite)
        {
            $this->testDate($date1);
            $this->testDate($date2);
            $int_delta = strtotime($date2) - strtotime($date1);
            
            if ($unite == 'i'){
                return round(($int_delta /60), 2) ;
            }
            else if ($unite == 'h'){
                return round(($int_delta /(60*60)), 2) ;
            }
            else if ($unite == 'd'){
                return round(($int_delta /(60*60*24)), 2) ;
            }
            else if ($unite == 'm'){
                return round(($int_delta /(60*60*24*30)), 2) ;
            }
            else if ($unite == 'y'){
                return round(($int_delta /(60*60*24*30*365)), 2) ;
            }
            else if ($unite == 's'){
                return round(($int_delta), 2) ;
            }
            else
            {
                trigger_error('Unit� fourni non valide');
            }
        }
        
        /**
         * Permet de qu'un string contient une date (avec ou sans heure) au format 'Y/m/d H:i:s'
         * @param string $date date � tester au format 'Y/m/d H:i:s' ou 'Y/m/d'
         */
        public static function testDate($date)
        {
            $tab_temp = explode(' ', $date);
            $int_type = count($tab_temp);
            
            $tab_temp2 = explode('-', $tab_temp[0]);
            if (strlen($tab_temp2[0]) == 10)
            {
                $tab_temp2 = explode('/', $tab_temp[0]);
            }
            
            if (strlen($tab_temp2[0]) != 4 || $tab_temp2[1]>12 || $tab_temp2[1]<0 || $tab_temp2[1]>31 || $tab_temp2[1]<0)
            {
                trigger_error('Date fourni non valide');
            }
            if ($int_type == 2){
                $tab_temp2 = explode(':', $tab_temp[1]);
                if ($tab_temp2[1]>24 && $tab_temp2[1]>60 || $tab_temp2[1]<0 || $tab_temp2[1]>60 && $tab_temp2[1]<0)
                {
                    trigger_error('Date fourni non valide');
                }
            }
        }
        
        /*public static function getDiffDateV1($date1,$date2=null)
        {
            //- Gestion pr�sence de date
            if ($date2!=null)
            {

                //Dans le cas d'une comparaison entre deux date
                $s = strtotime($date2)-strtotime($date1);
                $d = intval($s/86400)+1; 
                //$d = intval($s/86400); 

                //Si ==1 jour -> conversion en minute
                if ($d==1 && $s < 86400)
                {
                    $diff=self::getTimeDifference($date1, $date2) ;

                    if ($diff['hours'] == 0 && $diff['minutes']<=1)
                    {
                        //Si moins d'une minutes
                        return __LIB_ONE_MINTES;
                    }
                    elseif($diff['hours'] == 0)
                    {
                        //Uniquement les minutes
                        $stri_diff = sprintf( '%01d '.__LIB_MINUTES, $diff['minutes'] );
                        return $stri_diff;

                    }
                    $stri_diff = sprintf( '%01d '.__LIB_HEURES.' %02d '.__LIB_MINUTES, $diff['hours'], $diff['minutes'] );
                    return $stri_diff;
                }

                return $d.' '. __LIB_DAYS;  //Retourne ex: "2 jours"


            }
            else
            {

                //Dans le cas d'une comparaison entre la date actuelle et celle pass� en parametre
                $s = strtotime(date('Y/m/d H:i:s'))-strtotime($date1);
                $d = intval($s/86400);  


                $d =($d == 0)?1:$d;

                //Si ==1 jour -> conversion en minute
                if ($d==1 && $s < 86400)
                {
                    // an END time value
                    $end   = date('Y/m/d H:i:s');
                    $diff=self::getTimeDifference($date1, $end) ;

                    if ($diff['hours'] == 0 && $diff['minutes']<=1)
                    {
                        //Si moins d'une minutes
                        return __LIB_LESS_ONE_MINTES;
                    }
                    elseif($diff['hours'] == 0)
                    {
                        //Uniquement les minutes
                        $stri_diff = sprintf( '%01d '.__LIB_MINUTES, $diff['minutes'] );
                        return $stri_diff;

                    }
                    $stri_diff = sprintf( '%01d '.__LIB_HEURES.' %02d '.__LIB_MINUTES, $diff['hours'], $diff['minutes'] );
                    return $stri_diff;
                }

                return $d.' '. __LIB_DAYS;  //Retourne ex: "2 jours"

            }
    
           
    
        }
         *
         */
        
}

?>