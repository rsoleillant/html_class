<?php
/*******************************************************************************
Create Date : 06/08/2012
 ----------------------------------------------------------------------
 Class name : pagination
 Version : 1.0
 Author : Rémy Soleillant
 Description :  Permet de créer des liens de pagination
 
********************************************************************************/
class pagination{
   
   //**** attribute ************************************************************
   protected $int_nb_res=8;           //Le nombre total de résultat
   protected $int_lig_par_page;     //Le nombre d'enregistrement affiché par page  
   protected $int_limit_index;      //L'indice courant de pagination 

   protected $stri_name;            //Le nom des post (dans le cas de plusieur pagination sur la meme page)
  
  //**** constructor ***********************************************************
 /*************************************************************
 *
 * parametres : 
 * retour : objet de la classe 
 *                        
 **************************************************************/    
  function __construct($int_nb_res=9,$int_lig_par_page=30,$stri_name='actionPagination') 
  {  
     
    $this->int_nb_res=$int_nb_res;
      
    if (isset($_SESSION[$stri_name]))
    {
        $this->int_limit_index=($_SESSION[$stri_name]>0)?$_SESSION[$stri_name]:0;
    }else
    {
        $this->int_limit_index=($_POST[$stri_name]>0)?$_POST[$stri_name]:0;
    }
    
     
    $this->int_lig_par_page=$int_lig_par_page;
    
    $this->stri_name=$stri_name;
  }
 
  //**** setter ****************************************************************
  public function setNbRes($value){$this->int_nb_res=$value;}
  public function setLigParPage($value){$this->int_lig_par_page=$value;}
  public function setLimitIndex($value){$this->int_limit_index=$value;}

  
  //**** getter ****************************************************************
  public function getNbRes(){return $this->int_nb_res;}
  public function getLigParPage(){return $this->int_lig_par_page;}
  public function getLimitIndex(){return $this->int_limit_index;}
 
   
   //**** public method *********************************************************
  
  
 /*************************************************************
 *
 * parametres : string : l'identifiant de l'instance
 * retour : objet de la classe calendrier_projet   
 *                        
 **************************************************************/    
  public function htmlValue()
  {  
     //- construction de la pagination
    $obj_table_pagination=new table();
    $obj_table_pagination->setAlign('center');
      $obj_tr_pagination=$obj_table_pagination->addTr();
   // $obj_table_pagination->setWidth("100%");
     $obj_javascripter=new javascripter();
     $obj_javascripter->addFunction("
		      function transmitPagination_".$this->stri_name."(obj_a)
		      {
                        //création d'un formulaire
		        var form=document.createElement('form');
		            form.method='post';
		       
                        
                        
		        //rattachement des données supplémentaire
		        var mvc=document.createElement('input');
		            mvc.name='".$this->stri_name."';
		            mvc.value=obj_a.attr('class');
		        $(form).append(mvc);
                        
		        
		        //rattachement du formulaire
		        $('body').append(form);
		        
		        //envoi du formulaire
		        form.submit();
          }
          ");    
    $int_indice_page=1;

    
    //$int_nb_record_max=20*$this->int_lig_par_page;
    //$int_nb_record_max=60*$this->int_lig_par_page;
    
    //Utilisation de la constante PHP INF (Infini pour gérer un nombre de résultat plus que conséquent)
    $int_nb_record_max=INF*$this->int_lig_par_page;

    
    $int_nb_max_lien=$this->int_nb_res;
    if($this->int_nb_res>$int_nb_record_max)//s'il y a trop de page
    {
     $int_nb_max_lien=$int_nb_record_max;
    }
    
    //calcul de l'indice de départ 
    //$this->int_limit_index

    
    
    /*for($i=0;$i<$int_nb_max_lien;$i+=$this->int_lig_par_page)
    {
      $obj_a=new a("#",$int_indice_page);
        $obj_a->setClass($i);
        $obj_a->setName($this->stri_name);
        $obj_a->setOnClick("transmitPagination_".$this->stri_name."($(this))");
      if($i==$this->int_limit_index)//si on est sur la page courrante
      {$obj_a->setStyle("font-weight:bold;color:cornflowerblue;");}
        
      $obj_tr_pagination->addTd($obj_a);
      $int_indice_page++;
    }
    
    */
    
    //Gestion d'une pagination moins complète avec prise en charge d'une echelle
    for($i=0;$i<$int_nb_max_lien;$i+=$this->int_lig_par_page)
    {
      $obj_a=new a("#",$int_indice_page);
        $obj_a->setClass($i);
        $obj_a->setName($this->stri_name);
        $obj_a->setOnClick("transmitPagination_".$this->stri_name."($(this))");
      if($i==$this->int_limit_index)//si on est sur la page courrante
      {$obj_a->setStyle("font-weight:bold;color:cornflowerblue;");}
        
      //Pose dans un array
      $arra_obj_a[] = $obj_a;
      $int_indice_page++;
    }
    
    //Nombre de numéro de pagnination visible entre l'index sélectionnée
    $int_nb_page_avant = 10;
    $int_nb_page_apres =  $int_nb_page_avant * 2 ;
    
    //Calcul du nombre de pagination dispo
    $int_nb_obj_a = count($arra_obj_a);
    
    //Déduction du numéro de page  de départ
    $int_idx_selected = $this->int_nb_res - $this->int_limit_index;
    $int_idx_selected = floor($int_idx_selected / $this->int_lig_par_page);
    $int_start_idx = $int_nb_obj_a - $int_idx_selected - $int_nb_page_avant;
    $int_start_idx = ($int_start_idx<=0)?0:$int_start_idx;

    
    //Gestion affichage départ
    if ($int_start_idx>0)
    { $obj_tr_pagination->addTd($arra_obj_a[0]); }
    
    if ($int_start_idx>1 && $this->int_nb_res>0)       
    { 
        $obj_tr_pagination->addTd('...'); 
        $int_start_idx--;
    }
    
    //Pose des numéro de page visible ( de -10 à +10)
    for($int_i=$int_start_idx; $int_i<=$int_start_idx+$int_nb_page_apres;$int_i++)
    { 
        $obj_a = $arra_obj_a[$int_i];
        
        //- Si l'objet existe
        if (is_object($obj_a))
        { $obj_tr_pagination->addTd($obj_a);  }
    }
    
    
    
    //Gestion affichage fin
    $int_stop_idx = $int_start_idx+$int_nb_page_apres;
    if ($int_stop_idx+1 < $int_nb_obj_a)
    {
        //Pose ...
        if ($int_stop_idx+2 <$int_nb_obj_a)
        { $obj_tr_pagination->addTd('...'); }
        
        $obj_a = $arra_obj_a[$int_nb_obj_a-1];
        
        //- Si l'objet existe
        if (is_object($obj_a))
        {
            //Pose deniere pagination
            $obj_tr_pagination->addTd($obj_a); 
        }
        
        
    }

      
    
   
   return $obj_javascripter->javascriptValue().$obj_table_pagination->htmlValue();
  }

  
}

?>
