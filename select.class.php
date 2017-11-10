<?php
/*******************************************************************************
Create Date : 22/05/2006
 ----------------------------------------------------------------------
 Class name : select
 Version : 1.2
 Author : Rémy Soleillant
 Description : élément html <select>
********************************************************************************/

//include_once("option.class.php");
class select extends serialisable implements inputHtml{
   
   /*attribute***********************************************/
   protected $stri_name="";
   protected $bool_disabled=false;
   protected $int_size="";
   protected $stri_alt="";
   protected $stri_onfocus="";
   protected $stri_onblur="";
   protected $stri_onmousedown="";
   protected $stri_onmouseup="";
   protected $stri_onchange="";
   protected $stri_onclick="";
   protected $stri_ondblclick="";
   protected $int_tabindex="";
   protected $stri_onmouseover;
   protected $stri_onmouseout;
   protected $bool_multiple=false;
   protected $bool_multiple_checkbox=false;   //Pour mettre le select en mode multiple avec gestion des checkbox
   protected $bool_can_be_empty=false;
   protected $stri_style="";
   protected $stri_data_type="string";
   protected $stri_id="";
   protected $stri_class="";
   protected $arra_option=null;
   protected $arra_group=null;
   protected $stri_title;
   protected $bool_required;
   protected $stri_placeholder;
   protected $arra_data;   
   protected $bool_active_plugin;         //Pour savoir s'il faut ou non activer le plugin permettant de mettre des images dans les options
   protected $mixed_selected_option=null; //L'option sélectionné ou la valeur de l'option à sélectionner   
   protected static $int_nb_instance;     //Le nombre d'instance de la classe
   //public $arra_sauv=array();
  
   
   /* constructor***************************************************************/
   function __construct($name) {
       $this->stri_name=$name;
       $this->bool_active_plugin=false;//Pas de plugin par défaut
   }
  
  
  
   /*setter*********************************************************************/
  public function setTitle($stri_title)
  {
    $this->stri_title = $stri_title;
  } 
   
  public function setMultiple($bool)
  {
    if(is_bool($bool))
    {
      $this->bool_multiple=$bool;
    }
    else
    {
      echo("<script>alert('bool_multiple doit etre de type boolean');</script>");
    }
  }
  public function setOption($arra_option)
  {//permet d'affecter un tableau d'options
   $this->arra_option=$arra_option;
   if(is_object($arra_option[0]))
   {
   $this->mixed_selected_option=$arra_option[0];//sélection de la première option
   $this->mixed_selected_option->setSelected(true);
   }
  }
  
  public function setClass($value)
  {$this->stri_class=$value;}
  
   public function setOnmouseover($value)
  {
    $this->stri_onmouseover=$value;
  }
  
   public function setOnmouseout($value)
  {
    $this->stri_onmouseout=$value;
  }
  
   public function setDataType($value)
  {
    $this->stri_data_type=$value;
  }
  
  public function setAlt($value)
  {
    $this->stri_alt=$value;
  }
  
  public function setId($value)
  {
    $this->stri_id=$value;
  }
  
  public function setStyle($value)
  {
    $this->stri_style=$value;
  }  
  
  public function setOnfocus($value)
  {
    $this->stri_onfocus=$value;
  }
  
  public function setOnblur($value)
  {
    $this->stri_onblur=$value;
  }
  
  public function setOnmousedown($value)
  {
    $this->stri_onmousedown=$value;
  }
  
  public function setOnMouseUp($value)
  {
    $this->stri_onmouseup=$value;
  }
  
  public function setOnchange($value)
  {
    $this->stri_onchange=$value;
  }

  public function setOnclick($value)
  {
    $this->stri_onclick=$value;
  }
  
  public function setOndblclick($value)
  {
    $this->stri_ondblclick=$value;
  }
    
  public function setDisabled($bool)
  {
    if(is_bool($bool))
    {
      $this->bool_disabled=$bool;
    }
    else
    {
      echo("<script>alert('bool_disabled doit etre de type boolean');</script>");
    }
  }
  public function setSize($int)
  {
    if(is_numeric ($int))
    {
      $this->int_size=$int;
    }
    else
    {
      echo("<script>alert('size doit etre de type entier');</script>");
    }
  }
  
   public function setReadonly($bool)
  {
    if(is_bool($bool))
    {
      $this->bool_disabled=$bool;
    }
    else
    {
      echo("<script>alert('bool_disabled doit etre de type boolean');</script>");
    }
  }
  
   public function setCanBeEmpty($bool)
  { if(is_bool($bool))
    {
      $this->bool_can_be_empty=$bool;
    }
    else
    {
      echo("<script>alert('bool_disabled doit etre de type boolean');</script>");
    }
  } 
  
  public function setName($value)
  {$this->stri_name=$value;}
  
  public function setTabIndex($value)
  {$this->int_tabindex=$value;}
  
  public function setValue($value)
  { 
   return $this->selectOption($value);
  }
  public function setActivePlugin($value){$this->bool_active_plugin=$value;}
  public function setRequired($value){$this->bool_required=$value;}
  public function setMultipleCheckbox($value)
  {
    $this->bool_multiple_checkbox=$value;
    $this->bool_multiple=$value;
  }
  public function setPlaceholder($value){$this->stri_placeholder=$value;}

  /*getter**********************************************************************/
  
  public function getOption()
  {return $this->arra_option;}
  
  public function getStyle()
  {return $this->stri_style;}
  
  public function getId()
  {return $this->stri_id;}
  
  public function getDataType()
  {return $this->stri_data_type;}
  
  public function getMultipe()
  {return $this->bool_multiple;}
  
  public function getName()
  {return $this->stri_name;}
  
  public function getClass()
  {return $this->stri_class;}
   
  public function getDisabled()
  {return $this->bool_disabled;}
   
  public function getSize() 
  {return $this->stri_onreset;}
  
   public function getAlt() 
  {return $this->stri_alt;}
  
   public function getOnfocus() 
  {return $this->stri_onfocus;}
  
   public function getOnblur()  
  {return $this->stri_onblur;}
  
   public function getOnmousedown() 
  {return $this->stri_onmousedown;}
  
  public function getOnmouseover() 
  {return $this->stri_onmouseover;}
  
  public function getOnMouseUp() 
  {return $this->stri_onmouseup;}
  
  public function getOnchange() 
  {return $this->stri_onchange;}
  
  public function getOnclick() 
  {return $this->stri_onclick;}
  
  public function getOndblclick() 
  {return $this->stri_ondblclick;}
  
  public function getSelectedOption() 
  {return $this->mixed_selected_option;}
     
  public function getRequired(){return $this->bool_required;}
  
  public function getSelectedOptionValue() 
  {
   $stri_res=is_object($this->mixed_selected_option)?$this->mixed_selected_option->getValue():$this->mixed_selected_option;
   return $stri_res;
  }
  
  public function getValue() 
  {return $this->getSelectedOption()->getValue();}
  
  public function getIemeOption($int)
  {
    if(is_numeric ($int))
    {
      $i=count($this->arra_option);
      if($int<$i)
      {
        return $this->arra_option[$int];
      }
      else 
      {echo("<script>alert('Pas de $int eme option');</script>");}
    
    
    }
  
  }
  
  //permet de retrouver une option à partir de sa valeur
  //Paramètres : string la valeur de l'option cherchée
  //Retour : l'option cherchée , false si non trouvée
  public function getOptionByValue($stri_value)
  {
   
   foreach($this->arra_option as $obj_option)
   {
    if($obj_option->getValue()==$stri_value)
    {return $obj_option;}
   } 
   
   return false;
  }
  
  public function OptionExist($option)
  {
  //fonction qui recherche dans le select si la valeur de l'option existe 
  //si c'est le cas renvoi son libellé
  //echo '<pre>';
  //print_r($this->arra_option);

    for($i=0;$i<count($this->arra_option);$i++)
    {  
      $obj_option = $this->arra_option[$i];
      if($option == $obj_option->getValue())
      { return $obj_option->getLabel(); }
    }
  }
  
  public function getNumberOption()
  {return count($this->arra_option);}
  
   public function getCanBeEmpty()
  {return $this->bool_can_be_empty;}
  
   public function getOnmouseout()
  {return $this->stri_onmouseout;}
   
   public function getActivePlugin(){return $this->bool_active_plugin;}
   public function getMultipleCheckbox(){return $this->bool_multiple_checkbox;}
   public function getPlaceholder(){return $this->stri_placeholder;}

   /*clonage *******************************************************************/
   public function __clone()
   {
     $arra_temp=array();
     foreach($this->arra_option as $key=>$obj_option)
     {$arra_temp[$key]=clone($obj_option);} 
     $this->arra_option=$arra_temp;
     
   }
  
  /*other method****************************************************************/
  public function addGroup($lib)
  {
    //Permet de regrouper les options à l'intérieur d'une liste
    // $lib =>  Correspond au titre du groupepp
    
    $nb=count($this->arra_option);
    $nb--;
    $nb_group=count($this->arra_group);
    $this->arra_group[$nb_group]["libelle"]=$lib;   //Ajout du libelé du nouveau groupe
    $this->arra_group[$nb_group]["dernier_membre"]=-1;
    if($nb>-1)                                     // Si ce n'est pas la premier groupe
    {$this->arra_group[$nb_group-1]["dernier_membre"]=$nb;} // On ajout au groupe d'avant le numéro de sa dernière option
    
  }
  
  public function addOption($value,$label,$stri_data_image="")
  {
    $int_i=count($this->arra_option);
    $option=new option($value,$label);
    $this->arra_option[$int_i]=$option;
   
    if(($int_i==0)&&(is_null($this->mixed_selected_option)))//si on ajoute la toute première option, on la sélectionne
    {
     $option->setSelected(true);
     $this->mixed_selected_option=$option;
    }
    
    if($stri_data_image!="")//si on a une image à mettre dans l'option
    {
     $option->setDataImage($stri_data_image);
     $this->bool_active_plugin=true;//activation du plugin
    }
    return $option; 
  }
  
  /**
   * rajoute des options au select, en fonction des options d'un champ enum d'un table donnée.
   * @param string $stri_nom_table nom de la table en base de données
   * @param string $stri_nom_champ_enum nom du champ de type enum 
   * @param string $stri_prefixe_affichage rajoute un préfixe à chaque option pour l'affichage
   */
  public function addOptionFromEnum($stri_nom_table, $stri_nom_champ_enum, $stri_prefixe_affichage = "_"){
        //récupération des différentes valeurs en base de données
        $sql="
                 SHOW COLUMNS FROM {$stri_nom_table} LIKE '{$stri_nom_champ_enum}'
        ";
        $obj_query = new querry_select($sql);
        $arra_result = $obj_query->execute("assoc");
       
        
        //est-ce bien un champ enum?
        $stri_type = $arra_result[0]["Type"];
        if(substr($stri_type, 0, 4) === "enum"){
                //on récupère chaque valeur
                $arra_explode_valeur = explode(",", $stri_type);
                foreach ($arra_explode_valeur as $stri_value) {
                        $stri_valeur_exacte = substr($stri_value, strpos($stri_value, "'", 0)+1, strrpos($stri_value, "'", 0)- strpos($stri_value, "'", 0)-1);
                        //ajout de l'option
                        $this->addOption($stri_valeur_exacte, constant($stri_prefixe_affichage.$stri_valeur_exacte));
                }
        }
        else{
                outil::dump("ce n'est pas un champ enum");
        }
  }

    public function addData($stri_name,$value){$this->arra_data[$stri_name]=$value;}
  public function htmlValue()
  {
    
    //gestion activation du plugin
    if($this->bool_active_plugin)
    {
      self::$int_nb_instance++;
      $this->stri_class.=" msDropdown";
      
      $this->stri_id=($this->stri_id=="")?"id_select_".str_replace('.','_',microtime(true)):$this->stri_id;//id obligatoire
     /* $stri_res= '<script language="javascript">
                    $(function(){
                       $("#'.$this->stri_id.'").msDropdown();
                    });
                   
              
                  </script>'; */
     if(self::$int_nb_instance<2)
     {
    
      $stri_res= "<script language=\"javascript\">
                    $(function(){
                      var arra_select=$('.msDropdown');
                      for(var i=0;i<arra_select.length;i++)
                      {\$(arra_select[i]).msDropdown();}
                     
                    });
                   
              
                  </script>";
     }
    }   
    
      //- construction de l'attribut data
    $arra_data=array();
    foreach($this->arra_data as $stri_name=>$stri_value)
    {
      $arra_data[]='data-'.$stri_name.'="'.$stri_value.'"';
    }
    $stri_data=implode(' ', $arra_data) ;
    
    $stri_res.="<select ";
            
    // START - EM MODIF 11-07-2007
    $stri_res.=($this->stri_name!="")?" name=\"".$this->stri_name."\" ":"";
    $stri_res.=((string)$this->int_size!="")?" size=\"".$this->int_size."\" ":"";
    $stri_res.=($this->stri_style!="")?" style=\"".$this->stri_style."\" ":"";
    $stri_res.=((string)$this->stri_id!="")?" id=\"".$this->stri_id."\" ":"";
    $stri_res.=((string)$this->stri_alt!="")?" alt=\"".$this->stri_alt."\" ":"";
    $stri_res.=($this->stri_onfocus!="")?" onfocus=\"".$this->stri_onfocus."\" ":"";
    $stri_res.=($this->stri_onblur!="")?" onblur=\"".$this->stri_onblur."\" ":"";
    $stri_res.=($this->stri_onmousedown!="")?" onmousedown=\"".$this->stri_onmousedown."\" ":"";
    $stri_res.=($this->stri_onmouseup!="")?" onmouseup=\"".$this->stri_onmouseup."\" ":"";
    $stri_res.=($this->stri_onmouseover!="")?" onmouseover=\"".$this->stri_onmouseover."\" ":"";
    $stri_res.=($this->stri_onchange!="")?" onchange=\"".$this->stri_onchange."\" ":"";
    $stri_res.=($this->stri_onclick!="")?" onclick=\"".$this->stri_onclick."\" ":"";
    $stri_res.=($this->stri_ondblclick!="")?" ondblclick=\"".$this->stri_ondblclick."\" ":"";
    $stri_res.=((string)$this->int_tabindex!="")?" tabindex=\"".$this->int_tabindex."\" ":"";
    $stri_res.=($this->stri_onmouseover!="")? " onmouseover=\"".$this->stri_onmouseover."\" " : "";           
    $stri_res.=($this->stri_onmouseout!="")? " onmouseout=\"".$this->stri_onmouseout."\" " : "";
    $stri_res.=($this->stri_class!="")? " class=\"".$this->stri_class."\" " : "";
    $stri_res.=($this->bool_disabled)? " disabled ":""; 
    $stri_res.=($this->bool_multiple)? " multiple ":"";
    $stri_res.=($this->stri_title!="")? " title=\"".$this->stri_title."\" " : "";
    $stri_res.=($this->bool_required) ? " required " : "";
      $stri_res.=$stri_data;
    $stri_res.=" >";
    // END - EM MODIF 11-07-2007
  
  
    $int_nb_group=count($this->arra_group); 
    // Test du mode groupe  : on est en mode groupe s'il y a au moins un groupe
    $bool_gr=($int_nb_group>0)?true:false;
    //nombre de boucle principale à faire. En mode groupe c'est le nombre de groupe, sinon 1                          
    $int_nb_boucle=($bool_gr)?$int_nb_group:1;
    //compte le nombre d'option
    $int_nb_option=count($this->arra_option);   
    //initialisation de l'option traitée
    $int_opt=0;

    if(is_string($this->mixed_selected_option))//cas de sélection de l'option avant d'avoir créer les options
    {$this->selectOption($this->mixed_selected_option);}
   
    //pour chaque groupe ou juste une fois si on est pas en mode groupe
    for($i=0;$i<$int_nb_boucle;$i++)
    {
     //calcul du dernier membre du groupe
     $dernier_membre=(($bool_gr)&&($i+1<$int_nb_group))?$this->arra_group[$i]["dernier_membre"]:$int_nb_option;
     //ouverture de groupe
     $stri_res.=($bool_gr)?"<optgroup label='".$this->arra_group[$i]["libelle"]."'>":"";       
     
     //ajout des options
     while(($int_opt<=$dernier_membre)&&($int_opt<$int_nb_option))  
     {
       $stri_res.=$this->arra_option[$int_opt]->htmlValue();   
       $int_opt++;
     } 
     
     //on ferme le groupe si on est en mode groupe     
     $stri_res.=($bool_gr)?"</optgroup>":"";
    }
    
    $N=0;
                                    
    $stri_res.="</select>";
    
   
    if($this->bool_multiple_checkbox)//si mode multiple avec checkbox est activé
    {     
      $obj_css=new css();
         $obj_css->addFile('includes/classes/html_class/Jquery/jquery.multiple.select.css');
     $stri_res.=$obj_css->cssValue();    
     
      $obj_javascripter=new javascripter();
          $obj_javascripter->addFile('includes/classes/html_class/Jquery/jquery.multiple.select.js');
          $obj_javascripter->addFunction(" $('#".$this->stri_id."').multipleSelect({
                                                                        placeholder:'".$this->stri_placeholder."'
                                                                      });");
      $stri_res.=$obj_javascripter->javascriptValue();   
    }
   
    
    return $stri_res;
  }
  
  
  //::Modif Y.M::
  public function makeArrayToSelect($arra_data,$mode=1)
  {
    //met toutes les données du tableau php dans la liste déroulante
    //@param : $arra_data => tableau php à 1 dimension
    //@return : void
    switch($mode){
    case 1:
    //met toutes les données du tableau php dans la liste déroulante
    //@param : $arra_data => tableau php à 1 dimension
    //@return : void
      foreach($arra_data as $stri_data)
      {
        $this->addOption($stri_data,$stri_data);
      }
      break;
    case 2:
    //met toutes les données du tableau php dans la liste déroulante
    //@param : $arra_data => tableau php à 2 dimension
    //@return : void
      for($i=0;$i<sizeof($arra_data);$i++){
         //$this->addOption($arra_data[$i][0],$arra_data[$i][1]);
         $this->addOption($arra_data[$i][0],constante::constante($arra_data[$i][1]));
      }
      break;
    case 3:
    //met toutes les données du tableau php dans la liste déroulante
    //@param : $arra_data => tableau php à 1 dimension
    //@return : void
     foreach($arra_data as $stri_value=>$stri_label)
      {
         $this->addOption($stri_value,$stri_label);
      }
      break;
    default:
      echo "PROBLEME DANS LA CLASS SELECT: DEMANDER YANNICK MARION!!!";
    }
  }
  //::FIN::
  //Permet de créer un select à partir d'une requete sql
  //si la requete à un seul champ, la valeur et le libellé sont ce champ
  //si la requete à plusieurs champs, la valeur est le premier champ, le libellé le deuxième, les autres champs sont ignorés 
  public function makeSqlToSelect($stri_sql,$obj="",$func="")
  {
      
    $obj_query=new querry_select($stri_sql);
    $arra_res=$obj_query->execute();
    
    $int_col_value=0;
    $int_col_label=(count($arra_res[0])>1)?1:0;

    
    return $this->makeQuerryToSelect($obj_query,$int_col_value,$int_col_label,$obj,$func);
  }
  
  public function makeQuerryToSelect($req,$int_col_value,$int_col_label,$obj="",$func="")
  {
    $req->execute();
    
    for($i=0;$i<$req->getNumberResult();$i++)
    {
      $temp=$req->getIemeResult($i);
      $stri_label="";
      if(is_array($int_col_label))
      { //cas label multiple sans passage par $func
        if(empty($obj) and empty($func))
        {
         foreach($int_col_label as $value)
         {$stri_label.=$temp[$value]." ";}
        }
        elseif(empty($obj) and !empty($func))
        {//cas label multiple avec passage par $func mais sans objet, donc pour une fonction
          foreach($int_col_label as $value)
          {$stri_label.=$func($temp[$value])." ";} 
        }
        else
        {//cas label multiple avec passage par $func
          foreach($int_col_label as $value)
          {$stri_label.=$obj->$func($temp[$value])." ";} 
        }
      }
      else
      {
        if(empty($obj) and empty($func))//cas label simple sans passage par $func
        {$stri_label=$temp[$int_col_label];}
        elseif(empty($obj) and !empty($func))//cas label simple avec passage par $func mais sans objet, donc pour une fonction
        {$stri_label=$func($temp[$int_col_label]);}
        else//cas label simple avec passage par $func
        {$stri_label=$obj->$func($temp[$int_col_label]);}
      }
      
      
      
      $this->addOption($temp[$int_col_value],$stri_label);

      /*
      if(empty($obj))
      {$this->addOption($temp[$int_col_value],$temp[$int_col_label]);}
      else
      {$this->addOption($temp[$int_col_value],$obj->$func($temp[$int_col_label]));}
      */
    
   }
   return $this;  
  }
  
  /** 
   * Permet d'associer le premier champs de résultat à la valeur de l'option,
   * le deuxième champs au libellé et les autres champs en data html
   **/
   public function makeSqlWithHtmlDataToSelect($stri_sql)
   {
     //- exécution du sql
     $obj_query=new querry_select($stri_sql);
     $arra_res=$obj_query->execute('assoc');
     
     foreach($arra_res as $arra_one_res)
     {
        //- récupération valeur et libellé
        $stri_valeur=array_shift($arra_one_res);
        $stri_libelle=array_shift($arra_one_res);
        
        //- création de l'option et ajout à la collection
        $obj_option=new option($stri_valeur,$stri_libelle);
        $this->arra_option[]=$obj_option;
        
        //- pose des data html
        foreach($arra_one_res as $stri_key=>$stri_data)
        {
          $obj_option->addData($stri_key,$stri_data);
        }
     }
     
     
   }           
  
  //Pour construire un select à partir du dico constante
  public function makeDicoToSelect($stri_table,$stri_champ,$obj="",$func="")
  {
   $stri_sql="  SELECT nom,Decode(type_valeur,'N',valeur_num,valeur_char) val
                FROM dico_constante dc, dico_lexique dl
                WHERE dc.num_constante=dl.num_constante
                    AND dl.nom_table='".strtoupper($stri_table)."'
                    AND dl.nom_champ='".strtoupper($stri_champ)."' 
                ORDER By VAL        
                ";
   $obj_query=new querry_select($stri_sql);
   
   return $this->makeQuerryToSelect($obj_query,1,0,$obj,$func);
  }
  
  
  public function selectOption($value)
  {
 
   $int_nb_option=count($this->arra_option);
   if($int_nb_option==0)//cas de sélection des options avant de les avoir créées
   {
    $this->mixed_selected_option=$value;
    return "";
   }
 
   for($i=0;$i<$int_nb_option;$i++)
   {
     $obj_option=$this->arra_option[$i];
     
      if($this->bool_multiple==false)//déselection des options
      {
       $obj_option->setSelected(false);
      }        
      
      //modification 06/08/2014
      if($obj_option->getValue().""==$value."")//recherche de l'option sélectionnée
      {
       $obj_option->setSelected(true);
       $this->mixed_selected_option=$obj_option;
      }     
   }
    return $this->mixed_selected_option;
  }
  
  //Permet de sélectionner plusieurs option dans le cas où on est avec un select multiple
  public function selectMultipleOption($arra_value)
  {
    foreach($this->arra_option as $obj_option)
    {
	  if(in_array($obj_option->getValue(),$arra_value))
	  {
		$obj_option->setSelected(true);
	  }
	  else
	  {
		$obj_option->setSelected(false);
	  }	
    }
  }
 /* 
   public function selectMultipleOption($arra_value)
  {
    foreach($this->arra_option as $obj_option)
    {
      foreach($arra_value as $stri_value)
      {
        if($obj_option->getValue()==$stri_value)
        {
           $obj_option->setSelected(true);
        }
		else
		{
			$obj_option->setSelected(false);
		}
      }
    }
  }*/
  
  public function disabledOptionByValue($value)
  {
   for($i=0;$i<count($this->arra_option);$i++)
   {
     $obj_option=$this->arra_option[$i];
          
     if($obj_option->getValue()==$value)
     {
     $obj_option->setDisabled(true);} 
   }
  
  }
  
  //Permet d'ordonner la liste des options par libellé
  //Paramètres : bool : pour dire si oui ou non la première option doit être trié
  public function orderOption($bool_premier_option=false)
  {
   if(!$bool_premier_option)
   {
    $obj_premier_option=array_shift($this->arra_option);//on enlève la première option
   }
   
   usort($this->arra_option, array("select", "cmp_option"));
   
   if(!$bool_premier_option)
   {
    array_unshift($this->arra_option,$obj_premier_option);//on remet la première option
   }
   
  }
  
  /* Ceci est une fonction de comparaison statique des options*/
    static function cmp_option($obj_option1, $obj_option2)
    {
        $stri_libelle1=$obj_option1->getLabel();
        $stri_libelle2=$obj_option2->getLabel();
        
        if ($stri_libelle1 == $stri_libelle2) {
            return 0;
        }
        return ($stri_libelle1 > $stri_libelle2) ? +1 : -1;
    }
 
}

?>
