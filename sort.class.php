<?php
// -----------------------------------------------------------------------------
// Create Date : 16/01/2008
// -----------------------------------------------------------------------------
// Class name : Sort
// Version : 1.0
// Author : Emilie Merlat
// Description : permet de gérer le système de tri sur les entêtes tableau
// -----------------------------------------------------------------------------


include_once("modules/Contrat/includes/class/sorter.class.php");

class sort
{
  //**** attribute *************************************************************
  protected $arra_header=array();        // tableau des entetes (array of header)
                                         //    - en clef : nom de l'entete HTML (key : HTML header's name)
                                         //    - en valeur : l'indice ou le nom associatif de la requete. si false : aucun tri sur cette colonne (value : indice or name of query. if false : any sort)
                                         //    [ex : array("id offre" => 0, "libelle" => 2, "point" => false, "" => false) ]     
  protected $url="";                     // url lors du rechargement de la page (url)
  protected $stri_form_name="form_sort"; // nom du formulaire
  protected $stri_category="";           // nom de la catégorie pour l'enregistrement dans la BDD (categories' name)
  protected $stri_module="";             // nom du module pour l'enregistrement dans la BDD (module's name)
  protected $int_user="";                // l'identifiant de l'utilisateur pour l'enregistrement dans la BDD (user's id)

  static $int_nb_post;                   // compteur permettant de modifier le nom des $_POST lorsqu'il y a plusieurs affichages sur la même page avec des tris ()  

  //**** constructor ***********************************************************
  public function __construct($url,$ModName="",$stri_category="",$int_user="")
  {                   
    //constructeur de l'objet tri (constructor)
    //@param : $url => url lors du rechargement de la page (url)
    //@param : $modname => nom du module si enregistrement du parametre dans la BDD (module's name)
    //@param : $stri_category => nom de la catégorie si enregistrement du parametre dans la BDD (categories' name) 
    //@param : $int_user => identifiant de l'utilisateur (user's ID)
    //@return : void
    
    $this->arra_header=$arra_header;
    $this->url=$url;  
    $this->stri_category=$stri_category;
    $this->stri_module=$ModName;
    $this->int_user=$int_user;
    self::$int_nb_post++;
  }
  
  //**** setter ****************************************************************
 
  public function setHeader($arra_header)
  {
    $this->arra_header=$arra_header;
  }
  
  public function setUrl($url)
  {
    $this->url=$url;
  }
  
  public function setFormName($stri_form_name)
  {
    $this->stri_form_name=$stri_form_name;
  }
  
  //**** getter ****************************************************************
 
  public function getHeader()
  {
    return $this->arra_header;
  }
  
  public function getUrl()
  {
    return $this->url;
  }
  
  public function getFormName()
  {
    return $this->stri_form_name;
  }
  
  //**** public method *********************************************************
  public function constructHeader($obj_tr="",$bool_form=false,$stri_form_name="form_sort")
  {
    //construit la ligne d'entete du tableau HTML (create header)
    //@param : $obj_tr => l'objet ligne s'il existe afin de le completer (object TR if exists)
    //@param : $bool_form => true : créer un formulaire avec le nom $stri_form_name (create a form with the name $stri_form_name)
    //                       false : utiliser le formulaire $stri_form_name
    //@param : $stri_form_name => nom du formulaire s'il existe (form's name if exists)
    //@return : $obj_tr => la ligne d'entete du tableau HTML (header of HTML table)

    $this->stri_form_name=$stri_form_name;
    
    //si aucun objet n'a été passé en paramètre, créer l'objet tr (if any object in parameter then create object tr)
    $obj_tr=($obj_tr=="")? new tr() : $obj_tr;
      
    //si aucun formulaire n'existe (if form doesn't exist)
    if($bool_form)
    {
      //création du formulaire (create form)
      $obj_form=new form($this->url,"POST");
      $obj_form->setName($this->stri_form_name);
    }
    
    //création des hidden (create hidden)
    $obj_hid_name=new hidden("hid_name_".self::$int_nb_post,"");
    $obj_hid_order=new hidden("hid_order_".self::$int_nb_post,"NULL");
    
    //initialisation (init)
    $i=1;
    $int_nb_header=count($this->arra_header);
    
    if($int_nb_header==1)
    {
      //il y a un seul en-tête (one header)
      
      //insère la balise form si aucun formulaire n'existe (add form tag if form doesn't exists)
      $stri_start_form=($bool_form)?$obj_form->getStartBalise():"";
      //insère les hidden (add hidden)
      $stri_start_form.=$obj_hid_name->htmlValue().$obj_hid_order->htmlValue();
      //insère la balise form si aucun formulaire n'existe (add form tag if form doesn't exists)
      $stri_end_form=($bool_form)?$obj_form->getEndBalise():"";
      
      //récupère toutes les clefs du tableau Header (get all key of array header)
      $arra_key=array_keys($this->arra_header);
      //récupère le seul nom de champ d'en-tête (get header's name)
      $stri_header=$arra_key[0];
      $sort=$this->arra_header[$stri_header];
      
      //ajoute l'header dans une nouvelle colonne (put header in HTML table)
      $temp_td=$obj_tr->addTd($stri_start_form.$this->getSort($stri_header,$sort).$stri_end_form);
      $temp_td->setAlign("center");
    }
    else
    {
      //il y a plusieurs en-têtes (many headers)
      $stri_start_form="";
      $stri_end_form="";
      //pour chaque entete html (foreach header)
      foreach($this->arra_header as $stri_header => $sort)
      {
        switch ($i)
        {
          //premier header (first header)
          case 1 : 
            //insère la balise form si aucun formulaire n'existe (add form tag if form doesn't exists)
            $stri_start_form=($bool_form)?$obj_form->getStartBalise():"";
            //insère les hidden (add hidden)
            $stri_start_form.=$obj_hid_name->htmlValue().$obj_hid_order->htmlValue();
          break;
          
          //dernier header (last header)
          case $int_nb_header :
            //insère la balise form si aucun formulaire n'existe (add form tag if form doesn't exists)
            $stri_end_form=($bool_form)?$obj_form->getEndBalise():"";
            $stri_start_form="";
          break;
          
          default:
            $stri_start_form="";
            $stri_end_form="";
          break;            
        }
      
        //ajoute l'header dans une nouvelle colonne (put header in HTML table)
        $temp_td=$obj_tr->addTd($stri_start_form.$this->getSort($stri_header,$sort).$stri_end_form);
        $temp_td->setAlign("center");
        $i++;
      } 
    }
    return $obj_tr;
  }
  
  public function addHeader($stri_header_name,$sort)
  {
    //ajoute un entete HTML à la fin du tableau (add header to table's end)
    //@param : $stri_header_name => nom de l'entete (header's name)
    //@param : $sort => [string] : entete avec tri (header with sort)
    //                  false : entete sans tri (header without sort)
    $this->arra_header[$stri_header_name]=$sort;
  }
  
  public function sort($arra_result,$arra_critere)
  {
    //tri les données de $arra_result à partir des critères $arra_critere (sort data of $arra_result from $arra_critere)
    //@param : $arra_result => tableau à 2 dimensions du résultat d'une requete (array 2 dimension of query) 
    //                          [ex : $arra_result[0][0]="a"  OR  $arra_result[0]["lib"]="a" 
    //                                $arra_result[0][1]="b"      $arra_result[0]["etat"]="b"
    //                                $arra_result[1][0]="a"      $arra_result[1]["lib"]="a"
    //                                $arra_result[1][1]="c"      $arra_result[1]["etat"]="c" ]
    //@param : $arra_critere => tableau à 2 dimensions des critères de tri (array 2 dimensions of critere)
    //                          [ex : $arra_result[0][0]=0    OR  $arra_result[0][0]="lib" 
    //                                $arra_result[0][1]="DESC"   $arra_result[0][1]="DESC"
    //                                $arra_result[1][0]=1        $arra_result[1][0]="etat"
    //                                $arra_result[1][1]="ASC"    $arra_result[1][1]="ASC" ]
    //@return : $arra_res => tableau trié (sorted array)

    $obj_sorter=new sorter($arra_result,$arra_critere);
    $arra_res=$obj_sorter->sort();
    
    return $arra_res;
  }   
  
  public function updateSort($int_user,$stri_value_default)
  {
    //permet d'ajouter dans la table gen_parameter les choix de tri (add order in DB's table gen_parameter)  
    //@param : $int_user => l'identifiant de l'utilisateur (user's id)
    //@param : $stri_value_default => valeur par defaut (default value)
    //@return : void
    
    //self::$int_nb_post permet de garder la valeur même si plusieurs objet sort sont construit dans le script. si 2 objets Sort sont déclarés dans le script, le premier sort aura comme valeur 1 et le second sort aura la valeur 2 car la variable est incrémentée de 1 lorsque l'objet est construit.
    $stri_order=$_POST["hid_order_".self::$int_nb_post];
    $stri_name=$_POST["hid_name_".self::$int_nb_post];
    
    //$this->int_user=$int_user;
    $obj_param=new parameter_field($stri_name,$this->int_user,$this->stri_module,$this->stri_category,$stri_order,$stri_value_default);
    $obj_param->updateField();
  }
  
  
  public function getParameter()
  {
    //récupère sous forme de tableau à 2 dimensions les critères de tri et l'ordre de tri
    //@param : $int_user => l'identifiant de l'utilisateur (user's id)
    //@return : $arra_result

    $sql="SELECT id_param, valeur
          FROM gen_parametre
          WHERE id_module='".$this->stri_module."'
          AND categorie='".$this->stri_category."'
          AND num_user=".$this->int_user."
          ORDER BY mdate";
    $obj_query_select=new querry_select($sql);
    $arra_result=$obj_query_select->execute();
    return $arra_result;
  }
  
  //**** private method ********************************************************
  private function getOrder($sort)
  {
    //récupère l'ordre du paramètre (get order of parameter)
    //@param : $sort => [string] : entete avec tri (header with sort)
    //                  false : entete sans tri (header without sort)
    //@return : $stri_order => ordre du tri ["DESC", "ASC", "NULL"] (order of sort)
    
    if($this->stri_module=="" and $this->stri_category=="")
    {
      $stri_order="NULL";
    }
    else
    {
      $sql="SELECT valeur
            FROM gen_parametre
            WHERE id_module='".$this->stri_module."'
            AND categorie='".$this->stri_category."'
            AND num_user=".$this->int_user."
            AND id_param='".$sort."'";
      $obj_query_select=new querry_select($sql);
      $arra_result=$obj_query_select->execute();
      $stri_order=($obj_query_select->getNumberResult()>0)?$arra_result[0][0]:"NULL";
    }
    
    return $stri_order;
  }
  
  
  private function getSort($stri_header_name, $sort)
  {
    //construit l'interface du tri
    //@param : $stri_header_name => nom de l'entete (header's name) 
    //@param : $sort => [string] : entete avec tri (header with sort)
    //                  false : entete sans tri (header without sort)
    //@return : $obj_table => tableau HTML du tri

    //met dispo le nom du thème utilisé
    global $themeName;
    //create label
    $obj_lb_header=new font($stri_header_name,true);
    
    //si l'entete n'a pas besoin de tri (if any sort)
    if($sort===false)
    {
      //affiche seulement le nom de l'entete (post header's name)
      $stri_html=$obj_lb_header->htmlValue();
    }
    else
    {
      $stri_order=$this->getOrder($sort);

      switch($stri_order)
      {
        case "DESC" :
          $obj_img_desc=new img("themes/$themeName/images/desc_on.png");
          $obj_img_asc=new img("themes/$themeName/images/asc_off.png");
        break;
        case "ASC"  :
          $obj_img_desc=new img("themes/$themeName/images/desc_off.png");
          $obj_img_asc=new img("themes/$themeName/images/asc_on.png");
        break;
        case "NULL" :
          $obj_img_desc=new img("themes/$themeName/images/desc_off.png");
          $obj_img_asc=new img("themes/$themeName/images/asc_off.png");
        break;
      }
      
      $obj_img_null=new img("themes/$themeName/images/middle.png");
      //put attribute
      $obj_img_desc->setTitle(_IMG_DESC);
      $obj_img_null->setTitle(_IMG_ANY_SORT);
      $obj_img_asc->setTitle(_IMG_ASC);
      $obj_img_desc->setAlt(_IMG_DESC);
      $obj_img_null->setAlt(_IMG_ANY_SORT);
      $obj_img_asc->setAlt(_IMG_ASC);
      $obj_img_desc->setStyle("cursor:pointer;");
      $obj_img_null->setStyle("cursor:pointer;");
      $obj_img_asc->setStyle("cursor:pointer;");
      
      $obj_img_asc->setOnclick("
            document.".$this->stri_form_name.".hid_name_".self::$int_nb_post.".value='$sort';
            document.".$this->stri_form_name.".hid_order_".self::$int_nb_post.".value='ASC';
            document.".$this->stri_form_name.".submit();
            ");
      $obj_img_desc->setOnclick("
            document.".$this->stri_form_name.".hid_name_".self::$int_nb_post.".value='$sort';
            document.".$this->stri_form_name.".hid_order_".self::$int_nb_post.".value='DESC';
            document.".$this->stri_form_name.".submit();
      ");
      $obj_img_null->setOnclick("
            document.".$this->stri_form_name.".hid_name_".self::$int_nb_post.".value='$sort';
            document.".$this->stri_form_name.".hid_order_".self::$int_nb_post.".value='NULL';
            document.".$this->stri_form_name.".submit();
      ");
    
      //affiche le nom et le tri de l'entete (post name and sort of header)
      $obj_table=new table();
      $obj_tr1=new tr();
      $obj_tr1->setHeight(1);
      $temp_td1=$obj_tr1->addTd($obj_lb_header->htmlValue());
      $temp_td2=$obj_tr1->addTd("&nbsp;");
      $temp_td3=$obj_tr1->addTd($obj_img_asc->htmlValue());
      $temp_td1->setRowspan(3);
      $temp_td2->setRowspan(3);
      $obj_tr2=new tr();
      $obj_tr2->setHeight(1);
      $temp_td2=$obj_tr2->addTd($obj_img_null->htmlValue());
      $obj_tr3=new tr();
      $obj_tr3->setHeight(1);
      $temp_td2=$obj_tr3->addTd($obj_img_desc->htmlValue());
      $obj_table->insertTr($obj_tr1);
      $obj_table->insertTr($obj_tr2);
      $obj_table->insertTr($obj_tr3);
      $obj_table->setCellspacing(0);
      $obj_table->setCellpadding(0);
      $obj_table->setBorder(0);
      $stri_html=$obj_table->htmlValue();
    }
    return $stri_html;  
  } 
}
?>
