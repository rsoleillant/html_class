<?php
/*******************************************************************************
Create Date : 29/05/2006
 ----------------------------------------------------------------------
 Class name : data_controler
 Version : 1.2
 Author : Rémy Soleillant
 Description : permet de controler si une donnée est d'un certain type
 Update : le 20 fev 2008
********************************************************************************/

class data_controler 
{   
  //**** attribute *************************************************************
  protected $stri_object_type;            //=> ??????
  protected $bool_all_control_ok=true;    //=>permet de savoir si la donnée composée de plusieurs parties est bien du bon type 
  
  
  //**** constructor ***********************************************************
  function __construct() 
  {
    //construit l'objet data_controler
    //@return : void
    
    $this->stri_object_type="data_controler";
  }
  
  
  //**** setter ****************************************************************
    
  
  //**** getter ****************************************************************
  public function getOk(){return $this->bool_all_control_ok;}
  
  
  //**** public method *********************************************************
  public  function isInteger($integer) 
  { 
    //verifie que $integer est un entier
    //@param : $integer => un entier
    //@return : true => c'est conforme 
    //          false => c'est incorrect
    
    $integer=trim($integer);
    return(preg_match("/[^0-9]-/", $integer))? false : true;
  }
  
  public function isFloat($float , $comma='.') 
  {
    //verifie que $float est un décimal
    //@param : $float => un décimal
    //@param : $comma => le symbole séparant l'entier de la mantisse
    //@return : true => c'est conforme 
    //          false => c'est incorrect
    
    $float = trim($float);
    if (preg_match("/[^0-9".$comma."]/", $float)){return false;}
    //trop de virgules
    return (count(explode($comma, $float)) >2) ? false : true;
  }
  
  public function isBorned($int,$binf,$bsup)
  {
    //vérifie que $int est compris entre $binf et $bsup
    //@param : $int => un nombre
    //@param : $binf => le nombre minimum
    //@param : $bsup => le nombre maximum
    //@return : true => c'est conforme 
    //          false => c'est incorrect
    
    if(!$this->isInteger($int)){return false;}
    return(($int>=$binf)&($int<=$bsup)) ? true : false;
  }
  
  public function isTime($time)
  {
    //verifie que $time est une horaire
    //@param : $time => un horaire sous le format HH24:MM:SS
    //@return : true => c'est conforme 
    //          false => c'est incorrect
    
    $bool=false;
    $arra_token=explode(":", $time);
    $nbr_token=count($arra_token);
    if(($nbr_token==3)&(strlen($arra_token[0])==2)&(strlen($arra_token[1])==2)&(strlen($arra_token[2])==2))
    {
      return(($this->isInteger($arra_token[0]))&($this->isBorned($arra_token[0],0,23))& 
      ($this->isInteger($arra_token[1]))&($this->isBorned($arra_token[1],0,59))&
      ($this->isInteger($arra_token[2]))&($this->isBorned($arra_token[2],0,59))) ? true : false;
    } 
    return $bool;
  } 
  
  public function isUntypedDate($date)
  {
    //vérification non sensible au type de date
    //@param : $date =>
    //@return : true => il s'agit d'une date courte ou longue
    //          false => ce n'est pas une date
    
    if($this->isShortDate($date))return true;
    return($this->isFullDate($date))? true : false;
  }
  
  public function isShortDate($date)
  {
    //vérifie si c'est une date au format DD/MM/YYYY
    //@param : $date => date au format DD/MM/YYYY
    //@return : true => c'est conforme 
    //          false => c'est incorrect
    
    if($date==""){return true;}//cas où la date nulle
    
    $arra_temp=explode("/",$date);
    $day=$arra_temp[0];
    $mouth=$arra_temp[1];
    $year=$arra_temp[2];
    return (($this->isBorned($day,1,31))&
            ($this->isBorned($mouth,1,12))&
            ($this->isBorned($year,1000,9999))) ? true : false;
  }
 
  public function isFullDate($date)
  { 
    //permet de vérifier si une date est au format long DD/MM/YYYY_HH:MM:SS
    //@param : $date => date au format DD/MM/YYYY_HH24:MM:SS
    //@return : true => c'est conforme 
    //          false => c'est incorrect
   
    $arra_temp=explode("/",$date);
    $day=$date{0}.$date{1};
    $mouth=$date{3}.$date{4};
    $year=$date{6}.$date{7}.$date{8}.$date{9};
    $time=substr($date, 11) ;
    
    if(!$this->isTime($time)){return false;}
    
    $bool_borned=(($this->isBorned($day,1,31))&
                ($this->isBorned($mouth,1,12))&
                ($this->isBorned($year,1000,9999)))? true : false;
                
    
    return $bool_borned;
  } 
  
  public function isFullDateV1($date)
  { 
    //permet de vérifier si une date est au format long DD/MM/YYYY_HH:MM:SS
    //@param : $date => date au format DD/MM/YYYY_HH24:MM:SS
    //@return : true => c'est conforme 
    //          false => c'est incorrect
   
    $arra_temp=explode("/",$date);
    $day=$arra_temp[0];
    $mouth=$arra_temp[1];
    $arra_temp2=explode("_",$arra_temp[2]);
    $year=$arra_temp2[0];
    $time=$arra_temp2[1];
    if(!$this->isTime($time)){return false;}
    return (($this->isBorned($day,1,31))&
            ($this->isBorned($mouth,1,12))&
            ($this->isBorned($year,1000,9999)))? true : false;
  }
  
  public function isIp($data)
  {
    //vérifie si $data est une adresse IP
    //@param : $data => une adresse IP au format XX.XX.XX.XX
    //@return : true => c'est conforme 
    //          false => c'est incorrect
    
    $arra_token=explode(".",$data);
    $res=true;
    if(count($arra_token)!=4){return false;}
    foreach($arra_token as $token)
    {
      if(!($this->isBorned($token,0,255))){$res=false;}
    }
    return $res;
  }
  
  public function controle($data, $stri_type)
  {   
    //vérifie si la donnée $data est au format $stri_type
    //@param : $data => une donnée
    //@param : $stri_type => le type de la donnée
    //@return : true => la donnée est bien typée 
    //          false => la donnée n'est pas correct
    
    $bool_correct_type=false;

    switch($stri_type)
    {
      case "integer":
        if($this->isInteger($data)){$bool_correct_type=true;}
        else{$this->bool_all_control_ok=false;}
      break;
      
      case "float":
        if($this->isFloat($data)){$bool_correct_type=true;}
        else{$this->bool_all_control_ok=false;}
      break;
      
      case "time":
        if($this->isTime($data)){$bool_correct_type=true;}
        else{$this->bool_all_control_ok=false;}
      break;
      
      case "untypeddate":
        //date non typé: soit au format cour, soit au format long
        if($this->isUntypedDate($data)){$bool_correct_type=true;}
        else{$this->bool_all_control_ok=false;}
      break;
      
      case "date":
        //date au format long avec l'heure
        if($this->isFullDate($data)){$bool_correct_type=true;}
        else{$this->bool_all_control_ok=false;}       
      break;
      
      case "sdate":
        //date au format cour, sans l'heure
        if($this->isShortDate($data)){$bool_correct_type=true;}
        else{$this->bool_all_control_ok=false;}
      break;
      case "autodate":
        //autorisation des dates null
        if($data=="")
        {$bool_correct_type=true;}
        else
        {
          //format de date détecté automatiquement
          $obj_date=new date();
           $mixed_ok=$obj_date->detectAndMatchFormat($data);
           if($mixed_ok!==false)
           {$bool_correct_type=$mixed_ok;}
           else
           {$this->bool_all_control_ok=false;}
        }
      
      break;
      
      case "string":
        $bool_correct_type=true;
      break;
      
      case "ip":
        if($this->isIp($data)){$bool_correct_type=true;}
        else{$this->bool_all_control_ok=false;}
      break;
       
      default:
        echo("<script>alert('Type $stri_type not treated');</script>");
        $bool_correct_type=false;
      break;    
    }
    
    return($bool_correct_type!==false)? $bool_correct_type : false;
  } 
}
?>
