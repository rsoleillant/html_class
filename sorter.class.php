<?php
// -----------------------------------------------------------------------------
// Create Date : 16/01/2008
// -----------------------------------------------------------------------------
// Class name : Sorter
// Version : 1.0
// Author : Emilie Merlat
// Description : permet de trier des tableaux � 2 dimensions
// -----------------------------------------------------------------------------

class sorter
{
  //**** attribute *************************************************************
  protected $int_critere;     //=> l'indice du crit�re (id crit�re)
  protected $arra_critere;    //=> tableau � 2 dimensions des crit�res de tri (array 2 dimensions of critere)
  protected $arra_result;     //=> tableau � 2 dimensions du r�sultat d'une requete (array 2 dimension of query)
  
  //**** constructor ***********************************************************
  public function __construct($arra_result,$arra_critere)
  {
    //construit l'objet sorter (create object)
    //@param : $arra_result => tableau � 2 dimensions du r�sultat d'une requete (array 2 dimension of query) 
    //                          [ex : $arra_result[0][0]="a"  OR  $arra_result[0]["lib"]="a" 
    //                                $arra_result[0][1]="b"      $arra_result[0]["etat"]="b"
    //                                $arra_result[1][0]="a"      $arra_result[1]["lib"]="a"
    //                                $arra_result[1][1]="c"      $arra_result[1]["etat"]="c" ]
    //@param : $arra_critere => tableau � 2 dimensions des crit�res de tri (array 2 dimensions of critere)
    //                          [ex : $arra_critere[0][0]=0    OR  $arra_critere[0][0]="lib" 
    //                                $arra_critere[0][1]="DESC"   $arra_critere[0][1]="DESC"
    //                                $arra_critere[1][0]=1        $arra_critere[1][0]="etat"
    //                                $arra_critere[1][1]="ASC"    $arra_critere[1][1]="ASC" ]
    //@return : void
                                       
    $this->arra_critere=$arra_critere;
    $this->arra_result=$arra_result;
    $this->int_critere=0;                     
  }  
  
  //**** public method *********************************************************
  public function sort()
  {
    //permet de trier les donn�es d'un tableau (sort data of array)
    //@return : $this->arra_result => tableau tri� (sorted array)
    if(count($this->arra_critere)>0)
    {
      //appel la fonction de comparaison (call function match)
      usort($this->arra_result,array ("sorter", "match"));    
    }
    
    return $this->arra_result;
  }
  
  
  //**** private ***************************************************************
  private function match($a,$b)
  {
    //permet de comparer 2 valeurs (match 2 values)
    //@param : $a => premiere valeur de comparaison (value 1)
    //@param : $b => deuxi�me valeur de comparaison (value 2)
    //@return : [int] => -1 : ordre correct (order is correct)
    //                    0 : �galit� (equals)
    //                    1 : ordre incorrect (order isn't correct)
    
    //r�cup�re toutes les clefs du tableau crit�re (get all key of array critere)
    $arra_key_critere=array_keys($this->arra_critere);
    //r�cup�re la clef courante (get current key)
    $key_courant=$arra_key_critere[$this->int_critere];
    
    //r�cup�re le nom du crit�re (get critere's name)
    $stri_critere_name=strtoupper($this->arra_critere[$key_courant][0]);
    //r�cup�re l'ordre du crit�re (get critere's order)
    $stri_critere_order=$this->arra_critere[$key_courant][1];
    
    //met en majuscule les valeurs de comparaison (put upper value)
    $stri_a=strtoupper($a[$stri_critere_name]);
    $stri_b=strtoupper($b[$stri_critere_name]);
    
    //si les 2 valeurs sont �gales (2 values is equals)
    if($stri_a == $stri_b)
    {
      //si le crit�re d�passe le nombre de crit�re (if critere > number of critere)
      if(count($this->arra_critere) <= $this->int_critere)
      {
        return 0;
      }

      //passe au crit�re suivant (next critere)      
      $this->int_critere++;
      //tri (sort)
      $res=$this->match($a,$b);
      //revient au crit�re pr�c�dent (previous critere)
      $this->int_critere--;
      return $res;      
    }
    
    //modifie l'operateur de comparaison en fonction de l'ordre du crit�re (update operator switch order) 
    if($stri_critere_order=="DESC")
    {
      return ($stri_a > $stri_b) ? -1 : 1 ;
    }
    else
    {
      return ($stri_a < $stri_b) ? -1 : 1 ;
    }
  }  
}
?>
