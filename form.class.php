<?php
/*******************************************************************************
Create Date : 22/05/2006
 ----------------------------------------------------------------------
 Class name : form
 Version : 2.0
 Author : Rémy Soleillant
 Description : élément html <form>
 Update : 21 fev 2008
********************************************************************************/
class form extends serialisable
{   
  //**** attribute *************************************************************
  protected $stri_action="";                  //=> le chemin sur lequel il va pointer apres validation du formulaire
  protected $stri_method;                     //=> le type d'envoi du formulaire POST ou GET
  protected $stri_onsubmit="";                //=> les actions js sur la validation du formulaire
  protected $stri_onreset="";                 //=> les actions js sur le bouton reset
  protected $stri_target="";                  //=> permet de cibler l'envoi du formulaire
  protected $bool_autoComplete=true;          //=> permet gérer l'autocomplétion du formulaire true => on ou par défaut false => off
  protected $stri_name="";                    //=> le nom de l'objet formulaire
  protected $stri_value="";                   //=> le code HTML compris entre les balises <form></form>
  protected $stri_enctype="";                 //=> type de données que le formulaire transmet -permet d'uploader les fichiers-
  protected $arra_object=null;                //=> permet de stocker les objets du formulaire
  protected $arra_object_detail=array();      //=> permet de generer les requetes d'insertion et de mise à jour à partir des objets contenus dans $arra_object 
  protected $bool_protected=false;            //=> permet de verrouiller le formulaire -true- ou le laisser en mode lecture -false-
  protected $stri_id="";                      //=> id de l'élément
  protected $stri_style="";
  
  //public $arra_sauv=array();                  //=> tableau de serialisation
  
  
  //**** constructor ***********************************************************
  function __construct($action,$method="post",$value,$name="") 
  {
    //construit l'objet formulaire
    //@param : $action => le chemin sur lequel il va pointer apres validation du formulaire
    //@param : $method => le type d'envoi du formulaire POST ou GET
    //@param : $value => le code HTML compris entre les balises <form></form>
    //@param : $name => le nom de l'objet formulaire
    $this->stri_value=$value;
    $this->stri_action=$action;
    $this->stri_method=$method;
    $this->stri_name=$name;
  }
  
  
  //**** setter ****************************************************************
  public function setValue($value){$this->stri_value=$value;}  
  public function setEnctype($value){$this->stri_enctype=$value;}  
  public function setProtected($bool) 
  {
    if(is_bool($bool))
    {$this->bool_protected=$bool;}
    else
    {echo("<script>alert('bool_protected doit etre de type boolean');</script>"); }
  }
  
  public function setName($value){$this->stri_name=$value;}  
  public function setAction($value){$this->stri_action=$value;}  
  public function setMethod($value){$this->stri_method=$value;}  
  public function setOnsubmit($value){$this->stri_onsubmit=$value;}  
  public function setOnreset($value){$this->stri_onreset=$value;}  
  public function setTarget($value){$this->stri_target=$value;}  
  public function setAutocomplete($value){$this->bool_autoComplete=$value;}
  public function setId($value){$this->stri_id=$value;}
  public function setStyle($value){$this->stri_style=$value;}
  
  //**** getter ****************************************************************
  public function getAction(){return $this->stri_action;}
  public function getObjectDetail(){return $this->arra_object_detail;}
  public function getProcted(){return $this->bool_protected;}
  public function getMethod(){return $this->stri_method;}
  public function getName(){return $this->stri_name;}
  public function getOnsubmit(){return $this->stri_onsubmit;}
  public function getOnreset(){return $this->stri_onreset;}
  public function getTarget(){return $this->stri_target;}
  public function getAutocomplete(){ return $this->bool_autoComplete;}
  public function getValue(){return $this->stri_value;}
  public function getEnctype(){return $this->stri_enctype;}
  public function getNbrObject(){ return count($this->arra_object);}
  public function getIemeObject($int){return $this->arra_object[$int];}
  public function getObject(){return $this->arra_object;}
  public function getId(){return $this->stri_id;}
  public function getStyle(){return $this->stri_style;}
  
  //**** public method *********************************************************
  public function getStartBalise()
  {
    //insère la balise de debut de formulaire
    //@return : $stri_res => html
    
    $stri_res="<form action=\"".$this->stri_action."\" ";
    
    $stri_res.=($this->stri_method!="")?" method='".$this->stri_method."' ":"";
    $stri_res.=($this->stri_enctype!="")?" enctype=\"".$this->stri_enctype."\" ":"";
    $stri_res.=($this->stri_onsubmit!="")?" onsubmit=\"".$this->stri_onsubmit."\" ":"";
    $stri_res.=($this->stri_onreset!="")?" onreset=\"".$this->stri_onreset."\" ":"";
    $stri_res.=($this->stri_target!="")?" target=\"".$this->stri_target."\" ":"";
    $stri_res.=($this->stri_name!="")?" name=\"".$this->stri_name."\" ":"";
    $stri_res.=(!empty($this->stri_id))?" id=\"".$this->stri_id."\" ":"";
   
    $stri_res.=" >";
    return $stri_res;
  }

  //insère la balise de fin de formulaire
  public function getEndBalise(){return "</form>";}
  
  public function htmlValue()
  {
    //insère le formulaire
    //@return : $stri_res => html    
    $stri_res.="<form action=\"".$this->stri_action."\" ";
    $stri_res.="method=\"".$this->stri_method."\" ";
    $stri_res.=($this->stri_enctype!="")? "enctype=\"".$this->stri_enctype."\" " : "";
    $stri_res.=($this->stri_style!="")? "style=\"".$this->stri_style."\" " : "";
    $stri_res.=($this->stri_onsubmit!="")? "onsubmit=\"".$this->stri_onsubmit."\" " : "";
    $stri_res.=($this->stri_name!="")? "name=\"".$this->stri_name."\" " : "";
    $stri_res.=(!empty($this->stri_target))? "target=\"".$this->stri_target."\" " : "";
    $stri_res.=(!empty($this->stri_id))?" id=\"".$this->stri_id."\" ":"";
    $stri_res.=($this->bool_autoComplete === false)? 'autocomplete="off"': "";
    $stri_res.="> ".$this->stri_value.'</form>';
    return $stri_res;
  }
  
  public function addObject($object,$database_table="",$database_field="",$database_field_type='field',$data_type='string')
  {
    //ajoute un objet au formulaire
    //@param : $object => l'objet à ajouter
    //@param : $database_table => le nom de la table dans lequel le champ sera insere
    //@param : $database_field => le nom du champ dans la BDD
    //@param : $database_field_type => field : champ normal
    //                                 key : champ qui est clef primaire
    //@param : $data_type => le type de la valeur [ex : string, integer, boolean, float ...]
    //@return : void
    
    $i=count($this->arra_object);
    $this->arra_object[$i]=$object;
    if($this->bool_protected){$object->setReadonly(true);}

    $this->arra_object_detail[$i]['database_table']=$database_table;
    $this->arra_object_detail[$i]['database_field']=$database_field;
    $this->arra_object_detail[$i]['data_type']=$data_type;
    $this->arra_object_detail[$i]['database_field_type']=$database_field_type;
  }
  
  public function generateQuerry($type)
  {
    //generer les requetes d'insertion ou de mise à jour
    //@param : $type => insert : requete insertion
    //                  update : requete mise à jour
    //@return : $obj_querry => l'objet query
    
    $stri_table=$this->arra_object_detail[0]['database_table'];
    if($type=="insert")
      {$obj_querry=new querry_insert($stri_table);}
    else 
      {$obj_querry=new querry_update($stri_table);}
    
    foreach($this->arra_object as $key=>$obj)
    {
      $arra_obj_detail=$this->arra_object_detail[$key];
      if(($arra_obj_detail['database_field_type']=="key")&&($type=='update'))
        {$obj_querry->addKey($arra_obj_detail['database_field'],$_POST[$obj->getName()],$arra_obj_detail['data_type']);}
      else
        {$obj_querry->addField($arra_obj_detail['database_field'],$_POST[$obj->getName()],$arra_obj_detail['data_type']);}
    }
    return $obj_querry;
  }
  
  
  public function protectForm()
  {
    //met tous les objets du formulaire en lecture seule
    //@return : void
    
    for($i=0;$i<count($this->arra_object);$i++)
    {
      $this->arra_object[$i]->setReadonly(true);
    }   
  }
  
  public function javascriptVerification()
  {
    //genere le javascript pour verifier les différents éléments du formulaire (generator for javascript data verification)
    //@return : $res => le javascript
        
    $res="<script language=\"javascript\">"; 
      
    $res.=" function control(object,type)";
    $res.=" {";
    $res.="   var data=object.value;";
    $res.="   var res=false;";        
    $res.="   switch (type)";
    $res.="   {";
    $res.="     case \"integer\": ";
    $res.="       if((data>=0)&(data<9999999999))";
    $res.="       {res=true;}";
    $res.="     break;";  
            
    $res.="     case \"time\":";
    $res.="       var h=data.substr(0,2);";
    $res.="       var m=data.substr(3,2);";
    $res.="       var s=data.substr(6,2);";
    $res.="       if((h>=0)&(h<24)&(m>=0)&(m<60)&(s>=0)&(s<60)&(data.length==8))";
    $res.="       {res=true;}";
    $res.="     break;";    
          
    $res.="     case \"date\":"; // de type jj/mm/yyyy
    $res.="       var d=data.substr(0,2);";
    $res.="       var m=data.substr(3,2);";
    $res.="       var y=data.substr(6,4);";
    $res.="       if((d>=1)&(d<=31)&(m>=1)&(m<=12)&(y>=1800)&(y<2040)&(data.length==10))";
    $res.="       {res=true;}";
    $res.="     break;";   
           
    $res.="     case \"string\":";
    $res.="       res=true;";
    $res.="     break;";    
          
    $res.="     case \"mail\":";
    $res.="       if (data.match(/^([a-z0-9_\.\-])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,4})+$/))";
    $res.="       {res=true;}";
    $res.="     break;";

    $res.="     case \"ip\":
                var reg=/^\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]\d{1,3}$/
              
                if(reg.exec(data)!=null)
                {
                  res = true;
                }
                else
                {
                  var tab=data.split('.');
                  var compterreur=0;
                  
                  for(i=0;i<4;i++)
                  {
                    if((tab[i]-'0')>255) compterreur++;
                  }
                  if(compterreur==0) res = false;
                }
                break;";

    $res.="     default :";
    $res.="       alert('test');";
    $res.="     break;";
    $res.="   }";
    $res.="   return res;";
    $res.=" }"; 
    
    
    $res.=" function javascriptVerification()";
    $res.=" {;";    
    //compte le nombre d'objet dans le formulaire
    $int_nb_object=count($this->arra_object);
    for ($i=0 ; $i<$int_nb_object ; $i++)
    {
      //récupère l'objet courant
      $obj =$this->arra_object[$i];      
      //si l'objet ne doit pas être vide alors le javascript effectue deux controles :
      //  - un controle sur l'existance d'une valeur
      //  - un controle sur le type
      if(!$obj->getCanBeEmpty())
      {
          
        //si l'objet est vide alors un message d'erreur est affiché
        //sinon if la valeur de l'objet n'est pas du type indiqué alors un message d'erreur est affiché
        $res.=" if (document.".$this->stri_name.".".$obj->getName().".value==\"\")";
        $res.=" { ";
        $res.="   alert('"._ERROR_VOID_FIELD."');";
        $res.="   document.".$this->stri_name.".".$obj->getName().".focus();";
        $res.="   return false;";
        $res.=" }";
        $res.=" else";
        $res.=" {"; 
        $res.="   if(!control(document.".$this->stri_name.".".$obj->getName().",\"".$obj->getDataType()."\"))";
        $res.="   {";
        $res.="     alert('"._ERROR_INCORECT_TYPE_FIELD."');";
        $res.="     document.".$this->stri_name.".".$obj->getName().".focus();";
        //$res.="     var td = document.".$this->stri_name.".".$obj->getName().".closest('td');";
        //$res.="     $(td).css('background-color', 'red');";
        $res.="     return false;";
        $res.="   }";
        $res.=" }";
      }
      else
      {
        //sinon le javascript effectue un seul controle :
        // - un controle sur le type
        $res.=" if (!control(document.".$this->stri_name.".".$obj->getName().",\"".$obj->getDataType()."\"))";
        $res.=" {";
        $res.="   alert('"._ERROR_INCORECT_TYPE_FIELD."');";
        $res.="   document.".$this->stri_name.".".$this->arra_object[$i]->getName().".focus();";
        $res.="   return false;";
        $res.=" }";
      }
    }    
    $res.="  document.".$this->stri_name.".submit();";
    $res.="}\n";
    
    
    $res.=" function javascriptConfirmDelete(url_for_delete) ";
    $res.=" {";
    $res.="   var ok;";
    $res.="   ok=confirm('"._MA_CONFIRM_DELETE."');";
    $res.="   if(ok)";
    $res.="   {";
    $res.="     document.".$this->stri_name.".action=url_for_delete;";
    $res.="     document.".$this->stri_name.".submit();";
    $res.="   }";
    $res.=" }";   
     
    $res.="</script>";
    
    return $res;
  }
  
  
  
  public function javascriptConfirmDelete()
  {
    // créer une boite de dialogue pour confirmation du formulaire
    //@return : [string] => code js 
    
    $obj_javascripter=new javascripter();
    $obj_javascripter->addFunction("
      function javascriptConfirmDelete(url_for_delete)
      {
        bool ok;
        ok=confirm('"._MA_CONFIRM_DELETE."');
        if(ok)
        {
          document.".$this->stri_name.".action=url_for_delete;
          document.".$this->stri_name.".submit();
        }
      }
    ");
    return $obj_javascripter->javascriptValue();
  } 
  
  
  //**** method for serialization ********************************************** 
 /* public function __sleep() 
  {    
    //sérialisation de la classe form  
    $this->arra_sauv['stri_action']  = $this->stri_action;
    $this->arra_sauv['stri_method']  = $this->stri_method;
    $this->arra_sauv['stri_name']  = $this->stri_name;
    $this->arra_sauv['enctype']= $this->stri_enctype;
    $this->arra_sauv['onsubmit']= $this->stri_onsubmit;
    $this->arra_sauv['onreset']= $this->stri_onreset;
    $this->arra_sauv['target']= $this->stri_target;
    $this->arra_sauv['autocomplete']= $this->bool_autocomplete;
    $this->arra_sauv['value']= $this->stri_value;
    $this->arra_sauv['protected']= $this->stri_protected;
    $this->arra_sauv['id']= $this->stri_id;
    
    for($i=0;$i<count($this->arra_object);$i++)
    {
      $arra_temp[$i]=str_replace("'","#_#",serialize($this->arra_object[$i]));
      $arra_temp2[$i]['database_table']=$this->arra_object_detail[$i]['database_table'];
      $arra_temp2[$i]['database_field']=$this->arra_object_detail[$i]['database_field'];
      $arra_temp2[$i]['data_type']=$this->arra_object_detail[$i]['data_type'];
      $arra_temp2[$i]['database_field_type']=$this->arra_object_detail[$i]['database_field_type'];
    }
    $this->arra_sauv['arra_object']=$arra_temp;
    $this->arra_sauv['arra_object_detail']=$arra_temp2;    
   
    return array('arra_sauv');   
  }  
 
  public function __wakeup() 
  {    
    //désérialisation de la classe form
    $this->stri_name  = $this->arra_sauv['stri_name'];
    $this->stri_action= $this->arra_sauv['stri_action'];
    $this->stri_method= $this->arra_sauv['stri_method'];
    $this->stri_enctype= $this->arra_sauv['enctype'];
    $this->stri_onsubmit= $this->arra_sauv['onsubmit'];
    $this->stri_onreset= $this->arra_sauv['onreset'];
    $this->stri_target= $this->arra_sauv['target'];
    $this->bool_autocomplete= $this->arra_sauv['autocomplete'];
    $this->stri_value= $this->arra_sauv['value'];
    $this->stri_protected= $this->arra_sauv['protected'];
    $this->stri_id= $this->arra_sauv['id'];
    
    $arra_temp=$this->arra_sauv['arra_object'];
    $arra_temp2=$this->arra_sauv['arra_object_detail'];
    $nbr_object=count($arra_temp);
    for($i=0;$i<$nbr_object;$i++)
    {
      $this->arra_object[$i]= unserialize(str_replace("#_#","'",$arra_temp[$i]));
      $this->arra_object_detail[$i]['database_table']=$arra_temp2[$i]['database_table'];
      $this->arra_object_detail[$i]['database_field']=$arra_temp2[$i]['database_field'];
      $this->arra_object_detail[$i]['data_type']=$arra_temp2[$i]['data_type'];
      $this->arra_object_detail[$i]['database_field_type']=$arra_temp2[$i]['database_field_type'];
    }
    $this->arra_sauv = array();
  }  */
}

?>
