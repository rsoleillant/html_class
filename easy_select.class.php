<?php
/*******************************************************************************
Create Date : 23/11/2010
 ----------------------------------------------------------------------
 Class name : easy_select.class.php
 Version : 1.1
 Author : Rémy Soleillant
 Update date : 27/03/2012 Rémy Soleillant : supporte désormais le clonage
 Description : Select permettant de retrouver facielement une option dans une longue
                liste 
********************************************************************************/

class easy_select extends select  
{
  //**** attribute *************************************************************
   public static $int_nb_instance;//Le nombre d'instance déjà créé
   protected $obj_text; //Le champ de saisie
   protected $obj_div1; //La div contenant le champ de saisie
   protected $obj_div2; //La div contenant le select
   protected $obj_javascripter; //Le javascript de l'objet
   protected $int_max_lg_label=1; //Longueur du label le plus grand
  //**** constructor ***********************************************************
   

  /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe easy_select   
   *                        
   **************************************************************/         
  function __construct($name) 
  { 
     parent::__construct($name);
  }  
 
  //**** setter ****************************************************************
 
    
  //**** getter ****************************************************************

  
  //**** public method *********************************************************
  /*************************************************************
 * Surcharge de la méthode d'ajout d'option.
 * Permet en plus de détecter la longeur, en nombre de caractère, de 
 * l'option avec le libellé le plus grand  
 * 
 * parametres : aucun
 * retour : obj option : l'option ajoutée  
 *                        
 **************************************************************/    
  public function addOption($value,$label,$stri_data_image="")
  {
    $int_lg=strlen($label);
    $this->int_max_lg_label=($int_lg>$this->int_max_lg_label)?$int_lg:$this->int_max_lg_label;
   return parent::addOption($value,$label,$stri_data_image="");
  }
  
   /*************************************************************
   * Permet de construire les spécificités d'une liste simple avec
   * changement de forme   
   * parametres : aucun
   * retour : aucun 
   *                        
   **************************************************************/         
  public function parameterSelectSize1()
  {
     $this->obj_div1->setStyle("display:none;"); //au départ, la div contenant le champ texte n'est pas visible
       
     $this->stri_onclick="formeEvoluer(event,$(this));";//sur clic du select, on fait passer l'objet dans sa forme évolué (champ saisi + liste multiple ) 
     $this->stri_class="easy_select";
     $this->obj_text->setOnclick("stopEvent(event);"); //sur clic du champ texte, on bloque la propagation des événenement pour ne pas revenir à la forme simple
    
    //javascript permettant la transformation de la forme simple vers la forme complexe
    $this->obj_javascripter->addFunction("
      
      /**
       *Permet de faire passer la liste de la forme simple (liste seule)
       *à la forme complexe (liste + text) et vis-versa      
       *
       *Paramètres : id_general : la base de l'identifiant des éléments       
       **/
       var save_width;//Pour se rappeller de la taille original du select      
       var save_color;  // Pour connaitre la courleur d'origine du select
       //function changeForme(id_general)
        function changeForme(obj_select)
       { 
       
         if(obj_select.attr('id')=='')//si on n'a jamais généré d'identifiant pour ce select
         {
          var date=new Date();
          var id='id_'+date.getTime();
          obj_select.attr('id',id);  
         }
        
         //var select=document.getElementById('{$this->stri_id}');
         var select=obj_select[0];
         
         
         //var text=document.getElementById(id_general+'_text');        
         //var div1=document.getElementById(id_general+'_div1');
         //var div2=document.getElementById(id_general+'_div2');
         
         var div1=obj_select.closest('.main_div').find('.div1')[0];
         var div2=obj_select.closest('.main_div').find('.div2')[0]; 
         var text=$(div1).find('.helper_text');        
         
           
        //- Gestion du style 
          
         if(div1.style.display=='none')//passage à la forme complexe
         {
            
            initViewEasySelect();
            
            save_width=select.style.width;
            save_color = select.style.color

            var int_option_length = $(select).find('option').length;
            if (int_option_length < 20)
            { int_option_length = 20;  }
            
            if (int_option_length > 30)
            { int_option_length = 30;  }
            
            var int_option_group_length = $(select).find('optgroup').length;
            int_option_length += int_option_group_length;

            
            //select.size=20;
            $(select).animate({size: int_option_length}, 'fast', function()
            { $(this).attr('size',int_option_length); });


            //- Gestion du style 
            select.style.border='2px solid #a8b7cb';
            select.style.borderBottomLeftRadius='9px';
            select.style.boxShadow='10px 1px 12px rgb(85, 85, 85)';

            select.style.width='30em';
            select.style.color='';
            div1.style.display='';
            div2.style.position='absolute';
            $(div2).css('z-index',1000);
            $(div1).find('select').css('display','none');


            text.focus();
          

          
         }
         else //retour à la forme simple
         {
         
           select.size=1;
           
           select.style.borderBottomLeftRadius='';
           select.style.boxShadow='';
           select.style.color='';
           
           select.style.width=save_width;
           select.style.color=save_color
           
           div1.style.display='none';
           div2.style.position='';
        
         }

       }      
    ");
    
      $this->obj_javascripter->addFunction("
       /**
       *Permet de faire revenir le select à sa forme simple   
       *Appelé sur clic du document
       *Paramètres : id_general : la base de l'identifiant des éléments       
       **/
       //function revientFormeInitial(id_general)
       function revientFormeInitial(obj_select)
       { 
         //var div1=document.getElementById(id_general+'_div1');
         var div1=obj_select.closest('.main_div').find('.div1')[0];
         if(div1.style.display!='none')
         {
          //changeForme('{$this->stri_name}');
            changeForme(obj_select);
         }
        document.onclick=function(){};  
       }
      
       /**
       *Permet de faire passer la liste à la forme complexe (liste + text)     
       *Appelé sur clic de la liste déroulante
       *Paramètres : event : l'événement déclencheur      
       **/
       function formeEvoluer(event,obj_select)
       {      
       
         // document.onclick=function(){revientFormeInitial('{$this->stri_name}');}
          document.onclick=function(){revientFormeInitial(obj_select);}
          //changeForme('{$this->stri_name}');
          changeForme(obj_select);
          

         if (event.stopPropagation) { 
          event.stopPropagation(); 
        } 
        event.cancelBubble = true;
       }
      
       /**
       *Pour arrêter la propagation des événements dans l'arbre DOM  
       *Appelé sur clic du champ texte
       *Paramètres : event : l'événement déclencheur      
       **/
       function stopEvent(event)
       {
       
          if (event.stopPropagation) { 
          event.stopPropagation(); 
        } 
        event.cancelBubble = true;
        
       }


      ");
  }
  
  
  /*************************************************************
   * Permet de construire les évenemetns keyPress dans un easy_select
   * parametres : aucun
   * retour : aucun 
   *                        
   **************************************************************/         
  public function jQueryValue()
  {
      
      
      $this->obj_javascripter->addFunction("
          

        $(function()
        {
            $('.helper_text').keyup(function(e)
            {
                
                //- Référence vers l'easy_select
                //var selector = $(this).closest('.main_div').find('.div2');
                var selector = $(this).closest('.main_div').find('.easy_select');
                    
                //Key Up & Down
                if (e.keyCode == 38 || e.keyCode == 40) 
                {
                    //- Récupère les options disponibles
                    var arra_option = $(selector).find('option');
                    var int_max_option = arra_option.length-1;
                    
                    //- Déduction de l'index à selectionné
                    var int_idx = $(selector).prop('selectedIndex');
                        int_idx = (e.keyCode == 40) ? int_idx+1 : int_idx-1;                //- Element suivant ou précedent
                        int_idx = (int_idx < 0) ? 0 : int_idx;                              //- Valeur min
                        int_idx = (int_idx >= int_max_option) ? int_max_option : int_idx;   //- Valeur max

                    //- Défini l'option selectionné au clavier
                    $(selector).prop('selectedIndex', parseInt(int_idx));
                
                     
                }  
                else if (e.keyCode == 13 || e.keyCode == 9) 
                {
                    //Key Enter
                    $(selector).trigger('change');

                    //Permet de ne pas submit() le formulaire auquelle l'easy select est encapsuler
                    //return false;     
                }
                else if (e.keyCode == 27) 
                {   
                    //Key Echap
                    
                    //Fermeture des selects
                    $(document).click();
                }
                else
                {
                    //- Filtre les options lorsque une touches est enfoncé
                    filtreOption($(this));
                    
                }
                

            }).keypress(function(e)
            {
                //- Référence vers l'easy_select
                var selector = $(this).closest('.main_div').find('.easy_select');
                    
                if (e.keyCode == 13 || e.keyCode == 9) 
                {
                    //Key Enter
                    $(selector).trigger('change');

                    //Permet de ne pas submit() le formulaire auquelle l'easy select est encapsuler
                    return false;     
                }
            });


            $(document).click(function()
            {
                initViewEasySelect();
            });


            //Gestion du simple click sur liste déroulante Internet Explorer
            //Permet de séléectionné la value dans la liste via un seul click
            $('.easy_select').change(function(e)
            {
                //Si Ie 
                if ( $.browser.msie ) {
                    formeEvoluer(e,$(this));
                }
            });

        });

      
        //Maque tous les easy_select dans le body
        function initViewEasySelect()
        {
            //Parcours le DOM et cible tous les easy_select
            $(document).find('.div2').each(function()
            {
                var obj_select = $(this).children();
                revientFormeInitial(obj_select);
                
                //- BUG FIREFOX 47 => Romain le 21/06/2016
                //- Update firefox : Le select à toujours le focus et ne replit les options du menbu déroulant ...
                $(obj_select).blur();

            });
        }

    ");
      
    return;
      
  }
 
  
  /*************************************************************
   * Permet d'afficher le html et le javascript représentant l'objet.
   * L'affichage peut être de deux forme différente :
   * - forme 1 :liste déroulante simple à un seul élément : changement de forme sur clic du select (forme simple => forme complexe)
   * - form 2  :liste avec affichage de plusieurs options (size >1) : pas de changement de forme, le champ texte est toujours affiché (forme complexe)          
   * parametres :  aucun
   * retour : string : le code html et javascript
   *                        
   **************************************************************/          
  public function htmlValue()
  { 
     self::$int_nb_instance++;
    
     
    //construction des idenfiants en se basant sur le nom pour pouvoir avoir plusieurs easy_select sur une seule page  
	$stri_simple_name=str_replace('[]','',$this->stri_name);
    $stri_text_id=$stri_simple_name."_text";
    $stri_select_id=$stri_simple_name."_select";
    
    //modification des attributs de la liste déroulante
    //$this->stri_id=($this->stri_id=="")?$stri_select_id:$this->stri_id;//on met un id par défaut s'il n'y en a pas
   
    //Taille par défaut du select
    //$this->setStyle('min-width: 100%');
   
    //préparation du champ de saisi de la forme complexe
     $this->obj_text=new text($stri_text_id);
        $this->obj_text->setId($stri_text_id);
        //$this->obj_text->setOnKeyUp("filtreOption($(this))");     //- Déporté dans la méthode KeyPress
        $this->obj_text->setAutocomplete("off");
        $this->obj_text->setClass("helper_text");
        //$this->obj_text->setSize(floor($this->int_max_lg_label*1.4));
        //$this->obj_text->setStyle("width:200px");
        //$this->obj_text->setStyle("width:95%");
        $this->obj_text->setStyle(parent::getStyle(). ' ;min-width: 100%;');
        
        
        $this->obj_text->setPlaceholder(__LIB_PLACEHOLDER_EASY_SELECT);
        
        $obj_select_alignement=new select("alignement");
        $obj_select_alignement->setOption($this->arra_option); 
        //$obj_select_alignement->addOption("",str_repeat("a",$this->int_max_lg_label));
        $obj_select_alignement->setStyle("height:0px;visibility:hidden;display:none;".$this->stri_style);

    //partie dynamique  
    $this->obj_javascripter=new javascripter();

      $this->obj_javascripter->addFunction("
      var arra_save_option=new Array();
      var int_time_start = new Date().getTime();
      var int_time_end, int_interval;
      
        /**
         *Permet de filtrer les options qui contiennent
         *le texte saisi par l'utilisateur
         **/                                       
        function filtreOption(obj_text)
        {      
        
         var main_div=obj_text.closest('.main_div');
         var obj_select=main_div.find('.easy_select');//récupération du select portant les options affichées
         var obj_save_select=main_div.find('select[name=\"alignement\"]'); //récupération de la sauvegarde portant l'ensemble des options     
         var expression=obj_text.attr('value');
    
         var regexp=new RegExp(expression, 'i'); 
         
         
         var arra_option=obj_save_select.find('option');  //récupération de l'ensemble des options
         obj_select.empty();//vidage de toutes les options 
         
         var bool_match= false;
         for(var i=0;i<arra_option.length;i++)//pour chaque option provenant de la sauvegarde
         {
           var bool_ok=regexp.test(arra_option[i].text);
           if(bool_ok) //test si l'option répond au filtre
           {
            obj_select.append($(arra_option[i]).clone());//clonage et attachement de l'option au select
            bool_match = true;
           }
         }
         
        //- Si aucun résultat
         if (!bool_match)
         {
            //- Style option 
            var stri_style= 'color: red; text-align: center;';
            var stri_onclick = '$(this).parent().click();';
            
            //- Construction de l'option
            var stri_html_option = '<option style=\"'+stri_style+'\" onclick=\"'+stri_onclick+'\" disabled=\"disabled\">".__LIB_NO_RESULT_EASY_SELECT."</option>';
                
            //- Ajout de l'option
            obj_select.append(stri_html_option);
         }
         
        }
      "
      );
    //une div pour contenir le text
    $this->obj_div1=new div(); 
    //une div pour contenir le select
    $this->obj_div2=new div();
    
      
   //Traitement des spécificités entre la liste à affichage d'un seul élément (liste déroulante classique)
   if($this->int_size<2)
   {$this->parameterSelectSize1();}
 
   
   
    //pour ne poser qu'une seule fois le javacript
    if (self::$int_nb_instance==1)
    {$this->jQueryValue(); }; 

    //- Pose du javascriptValue
    $stri_js=(self::$int_nb_instance==1)?$this->obj_javascripter->javascriptValue():"";    

    
    
    //on complète les div
    //$this->obj_div1->setId($this->stri_name."_div1");
      $this->obj_div1->setClass("div1");
   
    $this->obj_div1->setContain($obj_select_alignement->htmlValue().$this->obj_text->htmlValue());//la div 1 contient le champ de saisi  
    //$this->obj_div2->setId($this->stri_name."_div2");
      
    $this->obj_div2->setContain(parent::htmlValue());//la div 2 contient le select
       $this->obj_div2->setClass("div2");
       
   
   $obj_main_div=new div("", $this->obj_div1->htmlValue().$this->obj_div2->htmlValue().$stri_js);
   $obj_main_div->setClass("main_div");    
   return $obj_main_div->htmlValue();    
  // return $this->obj_div1->htmlValue().$this->obj_div2->htmlValue().$stri_js;   
  }
}




?>
