<?php
/**
 * Et le cartouche ???
 **/

class dico_constante {
    //**** attribute *************************************************************
    protected $stri_table;
    protected $stri_champ;
    protected $int_valeur_num;
    protected $arra_result;
    
    /**
     * Constructor (la valeur -1 indique que l'on veux toutes les constantes correspondant à cette combianaison table/champ)
     * @param string $table
     * @param string $champ
     * @param integer $valeur_num
     */
    function __construct($table,$champ, $valeur_num = -1) 
    {
      $this->stri_table=$table;
      $this->stri_champ=$champ;
      $this->int_valeur_num=$valeur_num;
      $this->arra_result = $this->load();
    }
    
    //**** Setter *************************************************************
    public function setTable ($value){ $this->stri_table = value; }
    public function setChamp ($value){ $this->stri_champ = value; }
    public function setValeurNum ($value){ $this->int_valeur_num = value; }
    
    //**** Getter *************************************************************
    public function getTable (){ return $this->stri_table; }
    public function getChamp (){ return $this->stri_champ; }
    public function getValeurNum (){ return $this->int_valeur_num; }
    
    /**
     * Charge les infos en fonction des parametre fournis par le constructeur
     * @return array
     */
    private function load ()
    {    
        $stri_condition = '';
        if ($this->int_valeur_num !== -1)
        {
            if ($this->int_valeur_num == null AND $this->int_valeur_num !==0)
            {
                $stri_condition = 'valeur_num=null AND ';
            }
            else {
                $stri_condition = 'valeur_num='.$this->int_valeur_num . 'AND ';
            }
        }
        $stri_sql="SELECT nom, description, valeur_num, valeur_char FROM DICO_CONSTANTE
                WHERE ".$stri_condition."num_constante IN (
                SELECT num_constante FROM DICO_LEXIQUE WHERE nom_table='".$this->stri_table."' 
                AND nom_champ='".$this->stri_champ."'
                )                       
        ";
        
        $obj_query_load=new querry_select($stri_sql);
                $arra_res=$obj_query_load->execute("assoc");
        return $arra_res;
    }
    /**
     * Renvoie les informations
     * @return array
     */
    public function getResult ()
    {
        return $this->arra_result;
    }
    
    /** 
     * Permet d'obtenir la description à partir de la valeur num
     **/
     public function getDescriptionByValeurNum($int_valeur_num)
     {
       //- recherche de la valeur num dans les résultats
       foreach($this->arra_result as $arra_one_res)
       {
         if($arra_one_res['VALEUR_NUM']==$int_valeur_num)
         {return $arra_one_res['DESCRIPTION'];}
       }
      
       return false;
     }         
}

?>
