<?php
/*******************************************************************************
Create Date : 22/05/2006
 ----------------------------------------------------------------------
 Class name : table
 Version : 1.1.3
 Author : Rémy Soleillant
 Description : élément html <table >
********************************************************************************/
//include_once("tr.class.php");
//include_once("td.class.php");
class table extends serialisable
{
   
    /*attribute***********************************************/
    protected $stri_bgcolor="";
    protected $stri_align="";
    protected $stri_width="";
    protected $int_border="";//1
    protected $int_cellspacing="";//2
    protected $int_cellpadding="";//1
    protected $stri_background="";
    protected $stri_bordercolor="";
    protected $stri_bordercolorlight="";
    protected $stri_bordercolordark="";
    protected $stri_class="";
    protected $stri_rules="";
    protected $stri_style="";
    protected $stri_title="";
    protected $stri_id="";
    protected $stri_onmouseover="";
    protected $stri_onmouseup="";
    protected $stri_onclick="";
    protected $stri_ondblclick="";
    protected $stri_onmouseout;
    protected $stri_onmouseleave;
    protected $arra_tr;
    protected $stri_res; 
    protected $arra_data=[];  
   
    /* constructor***************************************************************/
    function __construct() 
    {
        $this->arra_tr =null;
    }
  
  
    /*setter*********************************************************************/
    public function setData($stri_idx ,$stri_value)
    {
        $this->arra_data[$stri_idx] = $stri_value;
    }
  
    
    public function setBgcolor($value)
    {
        $this->stri_bgcolor=$value;
    }
  
    public function setRules($value)
    {
        $this->stri_rules=$value;
    }
  
    public function setStyle($value)
    {
        $this->stri_style=$value;
    }
  
    public function setAlign($stri_align)
    {
         $this->stri_align=$stri_align;
     
    }
  
    public function setId($value)
    {
        $this->stri_id=$value;
    }
  
    public function setTitle($value)
    {
        $this->stri_title=$value;
    }
  
    public function setWidth($value)
    {
        $this->stri_width=$value;
    }
  
    public function setBorder($value)
    {
        $value=($value)?1:$value;
        if(is_numeric($value)) {
            $this->int_border=$value;
        }
        else
        {
            echo("<script>alert('int_border doit etre de type entier');</script>");
        }
    
    }
  
  
    public function setCellspacing($value)
    {
        $this->int_cellspacing=$value;
        /*if(is_numeric ($value))
        {
        $this->int_cellspacing=$value;
        }
        else
        {
        echo("<script>alert('int_cellspacing doit etre de type entier');</script>");
        } */
    
    }
  
    public function setCellpadding($value)
    {
        $this->int_cellpadding=$value;
        /*if(is_numeric ($value))
        {
        $this->int_cellpadding=$value;
        }
        else
        {
        echo("<script>alert('int_cellpadding doit etre de type entier');</script>");
        } */
    
    }
    public function setBackground($value)
    {
        $this->stri_background=$value;
    }
  
    public function setBordercolor($value)
    {
        $this->stri_bordercolor=$value;
    }
  
    public function setBordercolorlight($value)
    {
        $this->stri_bordercolorlight=$value;
    }
  
    public function setBordercolordark($value)
    {
        $this->stri_bordercolordark=$value;
    }
  
    public function setClass($value)
    {
        $this->stri_class=$value;
    }
  
    public function setOnmouseover($value)
    {
        $this->stri_onmouseover=$value;
    }
  
    public function setOnmouseup($value)
    {
        $this->stri_onmouseup=$value;
    }
  
    public function setOnclick($value)
    {
        $this->stri_onclick=$value;
    }
    public function setOnDblclick($value)
    {
        $this->stri_ondblclick=$value;
    }
  
    public function setOnmouseout($value)
    {
        $this->stri_onmouseout=$value;
    }
  
    public function setOnmouseleave($value)
    {
        $this->stri_onmouseleave=$value;
    }
  
    public function setTr($arra_tr)
    {
        $this->arra_tr=$arra_tr;
    }
    /*getter**********************************************************************/
  
    public function getBgcolor()
    {
        return $this->stri_bgcolor;
    }
  
    public function getRules()
    {
        return $this->stri_rules;
    }
  
    public function getStyle()
    {
        return $this->stri_style;
    }
   
    public function getAlign()
    {
        return $this->stri_align;
    }
  
    public function getId()
    {
        return $this->stri_id;
    }
   
    public function getTitle()
    {
        return $this->stri_title;
    }
  
    public function getWidth()
    {
        return $this->stri_width;
    }
  
    public function getBorder()
    {
        return $this->int_border;
    }
  
    public function getCellspacing()
    {
        return $this->int_cellspacing;
    }
   
    public function getCellpadding()
    {
        return $this->int_cellpadding;
    }
  
    public function getBackground()
    {
        return $this->stri_background;
    }
  
    public function getBordercolor()
    {
        return $this->stri_bordercolor;
    }
  
    public function getBordercolorlight()
    {
        return $this->stri_bordercolorlight;
    }
  
    public function getBordercolordark()
    {
        return $this->stri_bordercolordark;
    }
  
    public function getClass()
    {
        return $this->stri_class;
    }
  
    public function getTr()
    {
        return $this->arra_tr;
    }
 
    public function getOnmouseover()
    {
        return $this->stri_onmouseover;
    }
  
    public function getOnmouseup()
    {
        return $this->stri_onmouseup;
    }
  
    public function getOnclick()
    {
        return $this->stri_onclick;
    }
 
    public function getOnmouseout()
    {
        return $this->stri_onmouseout;
    }
 
    public function getIemeTr($int)
    {
        return $this->arra_tr[$int];
    }
 
    /*return an td object*/
    public function getCellule($line,$col)
    {
        $tab=$this->arra_tr[$line];
        if(!empty($tab)) {$res=$tab->getIemeTd($col);
        }
        return $res;
    }
  
    public function getCelluleById($stri_id)
    { 
        foreach($this->arra_tr as $obj_tr)
        {
            $mixed_td=$obj_tr->getTdById($stri_id);
            if(is_object($mixed_td)) {
                return $mixed_td;
            }
        }
    
        return false;
    }
  
    //Pour récupérer un tr à partir de la valeur de son attribut id
    public function getTrById($stri_id)
    {
        foreach($this->arra_tr as $obj_tr)
        {
            if($obj_tr->getId()==$stri_id) {
                return $obj_tr;
            }
        }
    
        return false;
    }
  
    //Permet d'obtenir la table, tr ou td portant l'id passé en paramètre
    public function getElementById($stri_id)
    {
        if($this->stri_id==$stri_id)//si l'élément cherché est la table
        {return $this;
        }
   
        foreach($this->arra_tr as $obj_tr)
        {
            $mixed_res=$obj_tr->getElementById($stri_id);
            if(is_object($mixed_res)) {return $mixed_res;
            }
    
        }
   
        return false;
    }
    /* method for serialization **************************************************/
    /*public function __sleep() {
    $this->arra_sauv['bgcolor']= $this->stri_bgcolor;
    $this->arra_sauv['align']= $this->stri_align;
    $this->arra_sauv['width']= $this->stri_width;
    $this->arra_sauv['border']= $this->int_border;
    $this->arra_sauv['cellspacing']= $this->int_cellspacing;
    $this->arra_sauv['cellpadding']= $this->int_cellpadding;
    $this->arra_sauv['background']= $this->stri_background;
    $this->arra_sauv['bordercolor']= $this->stri_bordercolor;
    $this->arra_sauv['bordercolorlight']= $this->stri_bordercolorlight;
    $this->arra_sauv['bordercolordark']= $this->stri_bordercolordark;
    $this->arra_sauv['id']= $this->stri_id;
    $this->arra_sauv['class']= $this->stri_class;
    $this->arra_sauv['rules']= $this->stri_rules;
    $this->arra_sauv['style']= $this->stri_style;
    $this->arra_sauv['onmouseout']= $this->stri_onmouseout;
    
    $arra_temp=array();
    foreach( $this->arra_tr as $key=>$obj_tr)
    {$arra_temp[$key]=serialize($obj_tr);}
    $this->arra_sauv['arra_tr']= $arra_temp;
    

     
     return array('arra_sauv');
    }
   
    public function __wakeup() {   
    $this->stri_bgcolor= $this->arra_sauv['bgcolor'];
    $this->stri_align= $this->arra_sauv['align'];
    $this->stri_width= $this->arra_sauv['width'];
    $this->int_border= $this->arra_sauv['border'];
    $this->int_cellspacing= $this->arra_sauv['cellspacing'];
    $this->int_cellpadding= $this->arra_sauv['cellpadding'];
    $this->stri_background= $this->arra_sauv['background'];
    $this->stri_bordercolor= $this->arra_sauv['bordercolor'];
    $this->stri_bordercolorlight= $this->arra_sauv['bordercolorlight'];
    $this->stri_bordercolordark= $this->arra_sauv['bordercolordark'];
    $this->stri_id= $this->arra_sauv['id'];
    $this->stri_class= $this->arra_sauv['class'];
    $this->stri_rules= $this->arra_sauv['rules'];
    $this->stri_style= $this->arra_sauv['style'];
    $this->stri_onmouseout= $this->arra_sauv['onmouseout'];
    
    $arra_temp=array();
    foreach($this->arra_sauv['arra_tr'] as $key=>$stri_tr)
    {$arra_temp[$key]=unserialize($stri_tr);}
    $this->arra_tr= $arra_temp;
    
    $this->arra_sauv = array();
     
    }
          */
  
    /*other method****************************************************************/
    public function htmlValue()
    {
        $stri_res=$this->stri_res;
        $stri_res.="<table ";
        // START - EM MODIF 10-07-2007
        $stri_res.=($this->stri_class!="")? " class=\"".$this->stri_class."\"" : "";
        $stri_res.=($this->stri_style!="")? " style=\"".$this->stri_style."\"" : "";
        $stri_res.=($this->stri_bgcolor!="")? " bgcolor=\"".$this->stri_bgcolor."\"" : "";
        $stri_res.=($this->stri_align!="")? " align=\"".$this->stri_align."\"" : "";
        $stri_res.=((string)$this->stri_width!="")? " width=\"".$this->stri_width."\"" : "";
    
        $stri_res.=($this->int_cellspacing!=="")? " cellspacing=\"".$this->int_cellspacing."\"" : "";
        $stri_res.=($this->int_cellpadding!=="")? " cellpadding=\"".$this->int_cellpadding."\"" : "";
    
        //$stri_res.=" cellspacing=\"".$this->int_cellspacing."\"";
        //$stri_res.=" cellpadding=\"".$this->int_cellpadding."\"";
        $stri_res.=($this->stri_background!="")? " background=\"".$this->stri_background."\"" : "";              
        $stri_res.=(!empty($this->stri_id))?" id=\"".$this->stri_id."\" ":"";
        $stri_res.=($this->stri_title)?" title=\"".$this->stri_title."\" ":"";
        $stri_res.=($this->stri_onmouseover!="")? " onmouseover=\"".$this->stri_onmouseover."\" " : "";
        $stri_res.=($this->stri_onmouseup!="")? " onmouseup=\"".$this->stri_onmouseup."\" " : "";
        $stri_res.=($this->stri_onclick!="")? " onclick=\"".$this->stri_onclick."\" " : "";
        $stri_res.=($this->stri_ondblclick!="")? " ondblclick=\"".$this->stri_ondblclick."\" " : "";
        $stri_res.=($this->stri_onmouseout!="")? " onmouseout=\"".$this->stri_onmouseout."\" " : "";
        $stri_res.=($this->stri_onmouseleave!="")? " onmouseleave=\"".$this->stri_onmouseleave."\" " : "";
   
        $stri_res.=($this->int_border>0)? " border=\"".$this->int_border."\" " : "";
   
    
        //- Pose des attributs data
        foreach ($this->arra_data as $stri_idx=>$stri_value)
        {
              $stri_res .= ($stri_value && $stri_idx) ? 'data-'.$stri_idx.'="'.$stri_value.'"' : '';
        }
    
        // END - EM MODIF 10-07-2007
        if($this->int_border>0 and $this->stri_style=="") {
            $stri_res.=" border=\"".$this->int_border."\" ";
            //$stri_res.=($this->int_border!="")? " border=\"".$this->int_border."\"" : "";
            $stri_res.=($this->stri_bordercolor!="")? " bordercolor=\"".$this->stri_bordercolor."\"" : "";
            $stri_res.=($this->stri_bordercolorlight!="")? " bordercolorlight=\"".$this->stri_bordercolorlight."\"" : "";
            $stri_res.=($this->stri_bordercolordark!="")? " bordercolordark=\"".$this->stri_bordercolordark."\"" : "";
        }
        $stri_res.=($this->stri_rules!="")? "rules=\"".$this->stri_rules."\"" : "";          
        $stri_res.=" >";
        // $stri_res.="<tbody>";
        foreach($this->arra_tr as $obj_tr)
        {   
            $stri_res.=$obj_tr->htmlValue();
        }
        /*$nbr_tr=count($this->arra_tr);
        for($i=0;$i<$nbr_tr;$i++)
        {
        $stri_res.=$this->arra_tr[$i]->htmlValue();
        }*/
        //$stri_res.="</tbody>";
        $stri_res.=" </table>";
        return $stri_res;
    }
    public function addTr()
    {
        /*$i=count($this->arra_tr);
        $this->arra_tr[$i] = new tr();
        return $this->arra_tr[$i]; */
    
        $obj_tr=new tr();
        $this->arra_tr[]=$obj_tr;
        return $obj_tr;
    }
  
    public function addTrBefore($int_indice=0)
    {
        //- découpe du tableau de td
        $arra_tr_part1=array_slice($this->arra_tr, 0, $int_indice);
        $arra_tr_part2=array_slice($this->arra_tr, $int_indice); 
   
        //- ajout d'un nouveau tr
        $obj_tr=new tr();
        $arra_tr_part1[]= $obj_tr;
   
        //- fusion des tableau
        $arra_tr=array_merge($arra_tr_part1, $arra_tr_part2);    
        $this->arra_tr=$arra_tr;

        return $obj_tr;
    } 
  
  
    public function insertTr($obj_tr)
    {
        //$i=count($this->arra_tr);
        //$this->arra_tr[$i]=$obj_tr;
        $this->arra_tr[]=$obj_tr;  
    }
  
    public function deleteTr($int_tr)
    {
        //pour supprimer un tr
        //param : int : le numéro du tr à supprimer
        //@return : bool : si le tr à bien été supprimé
        if(isset($this->arra_tr[$int_tr]))//si le tr existe
        { 
      
             //on remet la numérotation des td d'aplomb
             $int_nb_tr=count($this->arra_tr);
            for($i=$int_tr+1;$i<$int_nb_tr;$i++)
             {
                $this->arra_tr[$i-1]=$this->arra_tr[$i];
            }
             unset($this->arra_tr[$int_nb_tr-1]);
             return true;
        }
    
        return false;
    }
  
    /* return an object table*/ 
    public function makeQuerryToHtmlTable($req,$obj="",$func="",$start_display=0)
    {
        $req->execute();
        $int_nb_result=$req->getNumberResult();
        for($i=0;$i<$int_nb_result;$i++)
        {
            $obj_tr=new tr();
            $temp=$req->getIemeResult($i);
            for($j=$start_display;$j<$req->getNumberCol();$j++)
            {     
                if(empty($obj)) {$obj_tr->addTd($temp[$j]);
                }
                else
                {$obj_tr->addTd($obj->$func($temp[$j]));
                }
            }
            $this->insertTr($obj_tr);
        }
        return $this;  
    } 
  
    public function makeArrayToHtmlTable($arra_value)
    {
        foreach($arra_value as $arra_first_dim)
        {
            $obj_tr=new tr();
      
            foreach($arra_first_dim as $arra_second_dim)
            {
                 $obj_tr->addTd($arra_second_dim);
            }
            $this->insertTr($obj_tr);
        }
    }
  
    //
    /*******************************************************************************
    * Pour transformer un tableau associatif en table html clicable
    * 
    *  Parametres: array  : tableau associatif dont les clefs sont les index transmis en post
    *              int    : l'index de colonne à partir duquel afficher les données, les autres seront invisibles  
    *              string : le nom de la fonction js pour transmettre les données
    * Retour : obj font : le message ajouté                         
    *******************************************************************************/
 
    public function makeDataToHtmlTable($arra_data,$int_visible=0,$stri_js_function="transmitData")
    {
        //javascript pour la transmission des valeurs
        $obj_javascripter=new javascripter();      
        $obj_javascripter->addFunction(
            "
      function transmitData(obj_tr)
      {
        //création d'un formulaire
        var form=document.createElement('form');
            form.method='post';
            // form.target='_blank'; //si on veux l'envoi du clic sur une ligne dans une nouvelle page
        
        //rattachement du tr au formulaire
        var obj_tr_clone=$(obj_tr).clone();       
        $(form).append(obj_tr_clone);        
        
        //rattachement des données supplémentaire
        var mvc=document.createElement('input');
            mvc.name='actionLoadDetail';
            mvc.value='1';
        var id_mvc=document.createElement('input');
            id_mvc.name='id_mvc';
            id_mvc.value=$(obj_tr_clone.find('input').get(0)).val(); //transmission de l'idmvc qui est le premier input
        $(form).append(mvc);
        $(form).append(id_mvc);
        
        //rattachement du formulaire
        $('body').append(form);
        
       
        
        
        //envoi du formulaire
        form.submit();
        
         
      }
     
      "
        );
        
         //- création d'un table html
        $obj_table=$this; 
        $obj_tr_entete=$obj_table->addTr();
            $obj_tr_entete->setClass("titre3");
            $obj_tr_entete->setStyle("cursor:pointer");
     
        if(count($arra_data)==0)//si pas de résultat
        {
            $obj_tr_entete->addTd(_AUCUN_RESULTAT);
        }       
            
        //- construction des entêtes
        $arra_entete=array_keys($arra_data[0]);
        foreach($arra_entete as $stri_champ)
        {
             $obj_tr_entete->addTd(constante::constant("_".strtoupper($stri_champ))); 
        }

        //- pose des résultats
        foreach($arra_data as $arra_one_res)
        {           
        
             $obj_tr=$obj_table->addTr();
             $arra_data[]=$arra_one_res['Contact_ID'];//pour la transmission de données lors du clic
            foreach($arra_one_res as $stri_key=>$stri_one_res)
             {
                //hidden pour la transmission des données
                $obj_hidden=new hidden($stri_key, strip_tags($stri_one_res)); 
                $obj_hidden_const=new hidden($stri_key.'_CONST',  constante::constante(strip_tags($stri_one_res))); 
                $obj_tr->addTd(constante::constante($stri_one_res));
                $obj_tr->addTd($obj_hidden->htmlValue().$obj_hidden_const->htmlValue())->setStyle('display:none;');
        
     
            }
        }
        
        //- alternance des couleurs
        global $bgcolor1,$bgcolor2,$bgcolor3,$bgcolor4,$bgcolor5;
                $bgcolor6="#FFFFCE";
                
                $obj_table->alernateColor(1, $bgcolor1, $bgcolor4);
        //- gestion des données invisible
        $arra_tr=$obj_table->getTr();
        for($i=0;$i<$int_visible;$i++)
        {
            foreach($arra_tr as $obj_tr)
            {
                $obj_td=$obj_tr->getIemeTd($i);
                 $obj_td->setStyle('display:none;');
            }
        }
    
        //- tranmission des donnée sur clic des tr
        $obj_table->makeTrSelectionable(1, $stri_js_function."(this);", $bgcolor6, $arra_data, 1);  

        //transmission du javascript
        $this->stri_res=$obj_javascripter->javascriptValue();
    } 

  
    public function alernateColor($int_deb,$color1,$color2,$int_step=1)
    {
        /* permet d'alterner la couleur des lignes du tableau entre $color1 et 
        $color2 à partir de la ligne $int_deb. L'alternance se fait tout les $int_step
        */
   
        $int_alternate=0;
        $stri_color=$color1;
        $int_nb_element=count($this->arra_tr);
        for($i=$int_deb;$i<$int_nb_element;$i++)
        {
      
            if($int_alternate==$int_step) {
                $stri_color=($stri_color==$color1)?$color2:$color1;
                $int_alternate=0;
            }
     
            $this->arra_tr[$i]->setBgcolor($stri_color);
     
            $int_alternate++; 
        }  
    }
  
    //::Modifier par Y.M::
    public function makeTrSelectionable($int_deb,$onclick,$color,$arra_data,$mode=0,$bool_cursor_pointer=true)
    {
        //arra_data: tableau conteant une donnée à transmettre par url pour chaque ligne
    
        $int_nb_element=count($this->arra_tr);
        for($i=$int_deb;$i<$int_nb_element;$i++)
        {
      
            $tr=$this->arra_tr[$i];
            switch($mode){
            case 0://Fait une redirection sur le Onclick:
                $url_onclick=$onclick;
                if($url_onclick!="#") {$tr->setOnclick("location.href='".$url_onclick.$arra_data[$i-$int_deb]."' ");
                }
                break;
            case 1://Permet d'insérer du JS:
                $tr->setOnclick($onclick);
                break;
            default:
                echo "PROBLEME DANS LA CLASS TABLE: Demander à Yannick!!! -_-'";
                break;
            }
            $stri_mouse_pointer = ($bool_cursor_pointer)?"this.style.cursor='pointer';":"";
      
            $tr->setOnmouseover($stri_mouse_pointer." this.bgColor = '$color';");
            $tr->setOnmouseout("this.bgColor = '".$tr->getBgcolor()."'");
      
        }
  
    }
    //::FIN::
  
    public function noWrapForAllTd()
    {
        foreach($this->arra_tr as $tr)
        {
            $arra_td=$tr->getTd();
            foreach($arra_td as $td)
            {$td->setNoWrap(true);
            }
        }
    }
  
  
    /*************************************************************
    Permet de transformer une requête en une table dont les tr
    sont sélectionnables. Les données sur clic des tr sont transmises
    en post et portent le nom du champ dans la base.
 
    Paramètres : string  : le SQL de la requête SQL contenant les données à affichier et à transmettre
             
    Retour : string : code html et javascript de la table
   
    **************************************************************/        
    public function makeSQLToSelectableTable($stri_sql)
    {
        global $bgcolor1,$bgcolor2,$bgcolor3,$bgcolor4,$bgcolor5;
    
        $obj_query=new querry_select($stri_sql);
        $arra_res=$obj_query->execute("assoc");
    
   
        //pose des entêtes
         $obj_tr=$this->addTr();
         $obj_tr->setBgcolor($bgcolor2);
        foreach($arra_res[0] as $stri_field=>$stri_osef)
        {
             $obj_tr->addTd($stri_field);
        } 
  
    
        //pose des tr
        foreach($arra_res as $arra_one_res)
        {
            $obj_tr=$this->addTr();
             $obj_tr->setOnclick("transmitInPost(this);");
       
            //gestion de l'alternance des couleurs
            $stri_color=($stri_color==$bgcolor3)?$bgcolor1:$bgcolor3;
            $obj_tr->setBgcolor($stri_color); 
     
            //gestion de la couleur de sélection du tr
            $obj_tr->setOnmouseover("this.style.cursor='pointer'; this.bgColor = '$bgcolor2';");
            $obj_tr->setOnmouseout("this.bgColor = '$stri_color'");
     
            foreach($arra_one_res as $stri_field=>$stri_value)
            {
                $obj_td=$obj_tr->addTd($stri_value);
      
            }    
        }
    
        //Partie dynamique de l'interface
        $obj_javascripter=new javascripter();
         $obj_javascripter->addFunction(
             "
     function transmitInPost(tr)
     { 
       //création d'un formulaire
       var form=document.createElement('form');
       form.action='".$_SERVER['REQUEST_URI']."';
       form.method='post';
       
       //récupération des ententes
       var arra_entente=tr.parentNode.rows[0].cells;
       
       //récupération des données
       var i;
       var nb_element=tr.cells.length;
       
       for(i=0;i< nb_element; i++)
       {
         var cell=tr.cells[i];
         var value=cell.innerHTML;
         var name=arra_entente[i].innerHTML;
         var input=document.createElement('input');
         
         input.type='hidden';
         input.value=value;
         input.name=name.toLowerCase();
         form.appendChild(input);
       } 
       
       //attachement du formulaire au document
       document.body.appendChild(form);
       
       //envoi du formulaire
       form.submit();
     }
     "
         );
        
        return $obj_javascripter->javascriptValue().$this->htmlValue();
        //
    } 
  
  
    /*************************************************************
    Permet de remplacer les input par des font dans l'ensemble 
    du tableau
 
    Paramètres : aucun
             
    Retour : aucun
   
    **************************************************************/        
    public function replaceInputByFont()
    {
        foreach($this->arra_tr as $obj_tr)
        { 
            $arra_td=$obj_tr->getTd();
            foreach($arra_td as $obj_td)
            {
                $obj_td->replaceInputByFont();
            }
        }
    }
  
    /*************************************************************
    Permet de remplacer les input par des font dans l'ensemble 
    du tableau
 
    Paramètres : aucun
             
    Retour : aucun
   
    **************************************************************/        
    public function replaceInputByHidden()
    {
        foreach($this->arra_tr as $obj_tr)
        { 
            $arra_td=$obj_tr->getTd();
            foreach($arra_td as $obj_td)
            {
                $obj_td->replaceInputByHidden();
            }
        }
    }
  
    /*************************************************************
    Permet d'appliquer une méthode à l'ensemble des contenus des td
    pour qui la méthode est applicable. 
    Ex :  applyMethode("setDisabled",true)
 
    Paramètres : $stri_methode : le nom de la méthode à appliquer
              $mixed_param1 : premier paramètre de la méthode à appliquer
              ... il n'y a pas de limite au nombre de paramètre de a méthode à appliquer
    Retour : array(mixed) : tableau des retours de l'application de la méthode
   
    **************************************************************/        
    public function applyMethode($stri_methode,$mixed_param1)
    {
        $arra_param=func_get_args();//récupération de la liste des paramètres
        $stri_methode=$arra_param[0];
  
        $arra_res=array();
        foreach($this->arra_tr as $obj_tr){ 
            $arra_td=$obj_tr->getTd();
            foreach($arra_td as $obj_td)
            {
                //$arra_one_res=$obj_td->applyMethode($stri_methode,$arra_param);//application de la méthode et récupération du résultat
                $arra_one_res=call_user_func_array(array($obj_td, 'applyMethode'), $arra_param);
      
                $arra_res=array_merge($arra_res, $arra_one_res);//concaténation des résultats
            }
        }
    
        return $arra_res;
    }
}

?>
