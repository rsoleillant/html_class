<?php
/*******************************************************************************
Create Date : 29/05/2006
 ----------------------------------------------------------------------
 Class name : javascripter
 Version : 1.1
 Author : Rémy Soleillant
 Description : permet de stocker du code javascript
********************************************************************************/
class javascripter {
   
   /*attribute***********************************************/
   
   protected $arra_function;
   protected $arra_file=array();          //fichiers contenant du code javascript 
   protected $arra_object;
   public $arra_sauv=array();
   
   protected static $arra_file_in=array(); //tab des fichiers ext js déjà inclus
   protected static $arra_function_included;//Tab des fonctions déjà incluses
  /* constructor***************************************************************/
  function __construct() {}
  
  
  /*setter*********************************************************************/
  public function setIemeFucntion($int,$jsfunction)
  {
    $this->arra_function[$int]=$jsfunction;
  }

  public function setFunction($arra)
  {
    $this->arra_function=$arra;
  }


  /*getter**********************************************************************/ 
  public function getIemeFunction($int)
  {return $this->arra_function[$int];} 
  
  public function getFunction()
  {return $this->arra_function;}
  
  public function getFile(){return $this->arra_file;}
  
  /*other method****************************************************************/
  public function resetMemory()
  {
    self::$arra_file_in=array();
  }
  
  public function addFunction($jsfunction)
  {
   $nbr=count($this->arra_function);
   $this->arra_function[$nbr]=$jsfunction;
   
  }
  //Pour ne pas mettre deux fois la même fonction
  public function addFunctionOnce($jsfunction)
  {   
   if(!in_array($jsfunction,self::$arra_function_included))
   {
    self::$arra_function_included[]=$jsfunction;
    return $this->addFunction($jsfunction);
   }
  
   return false;
  }
  
  public function addFile($src)
  {
   $this->arra_file[count($this->arra_file)]=$src;
  }  
  
  public function javascriptValue()
  {
      
      
    //Gestion du cache navigayteur web 
    //$int_num_version = defined('__CACHE_CONTROL_VERSION') ? __CACHE_CONTROL_VERSION : date('Ymd');
    $stri_num_version = defined('__CACHE_CONTROL_VERSION') ? '?version='.__CACHE_CONTROL_VERSION : '';
    
    
    
   $stri_res="";
   foreach($this->arra_file as $src)
   {
     if (!in_array($src, self::$arra_file_in))
     { 
         //Gestion des espaces blanc en fin de chaines.
       $src = rtrim($src);
       
       $stri_res.='<script type="text/javascript" src="'.$src.$stri_num_version.'"></script>';
       self::$arra_file_in[count(self::$arra_file_in)] = $src;
     }
   }
   if ($this->arra_function) // si tableau non vide
   {  
     $stri_res.="<script language=\"javascript\">";
     for($i=0;$i<count($this->arra_function);$i++)  {
       $stri_res.=" ".$this->arra_function[$i]; 
       } 
     $stri_res.="</script>";
   }
   return $stri_res;
  }
  
  public function save($file)
  {
   $stream=fopen("$file","w+");
   $chaine=serialize($this);
   $ok=fwrite($stream, $chaine);
   return $ok;
  }
  
  public function load($file)
  {
   $stream=file_get_contents($file);
   $obj=unserialize($stream);
   if(get_class($obj)=="javascripter")
   {
   $this->setFunction($obj->getFunction());
   return true;
   }
   return false;
  }

  /* method for serialization **************************************************/
  public function __sleep() 
  {
    for($i=0;$i<count($this->arra_function);$i++)
    {
      $chaine=$this->arra_function[$i]; 
      $arra_temp[$i]= $chaine;
    }
    $this->arra_sauv['arra_file']= implode("_|_",$this->arra_file);
    $this->arra_sauv['arra_function']=$arra_temp;
    return array('arra_sauv');
  }
  
  public function __wakeup() 
  {
    $arra_temp=$this->arra_sauv['arra_function'];
    $nbr_object=count($arra_temp);
    for($i=0;$i<$nbr_object;$i++)
    {
      $this->arra_function[$i]= stripslashes($arra_temp[$i]);
    }
    $this->arra_file= explode("_|_",$this->arra_sauv['arra_file']);
    $this->arra_sauv = array();
  }    
}

?>
