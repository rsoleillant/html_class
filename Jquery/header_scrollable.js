/*******************************************************************************
Create Date  : 2015-02-20
 ----------------------------------------------------------------------
 Plug-in name  : header_scrollable
 Version     : 1.0
 Author      : ROBERT Romain
 Description : Plug-in jQuery permettant de gérer de manières globale
               les entetes fixe 
 
               Initialiation du plug-in en javascript : 
               
               $('.mon_selecteur').headerScrollable({
  
                        "box-shadow" : '0px 6px 2px 0  whitesmoke',
                        "vertical-align" : "middle",
                        "border-bottom-left-radius" : "3px",
                        "border-bottom-right-radius" : "3px",
                        "position" : "fixed", 
                        "top" : "0px"

                        }); 
                        
********************************************************************************/
(function($) {

   
    /** Paramètre **/
    var global_option = [{}];
    
    //Listener défilement dans le fenetre du navigateur WWW
    var scrollHandling = 
    {
        allow : true,

        reallow : function()
        { scrollHandling.allow = true; },

        trigger : function(obj_window)
        {

            //debug("Begin Trigger");

            // Posistion du scroll par rapport au top du navigateur
            var y = $(obj_window).scrollTop();
            // Posistion du scroll par rapport a gauche du navigateur
            var x = $(obj_window).scrollLeft();

            //console.log(global_option.length)
            for (var i=0; i<global_option.length; i++)
            {
                //Lors d'un scroll horizontal et en-tete franchit par scoll vertical
                // Si scroll franchit l'entete
                if (y >= global_option[i].int_top && x == 0  || x > 0 && y >= global_option[i].int_top )
                { methods.cloneHeader(i); }
                
                else if (y <= global_option[i].int_top || y == 0)
                {  methods.releaseHeader(i); }
                
                
            }
            

            //debug("End Trigger");

            return false;


        },
        
        resize : function (obj_window)
        {
            //console.log(global_option.length)
            for (var i=0; i<global_option.length; i++)
            {
                methods.setCloneWidth(i);

                methods.setClonePosition(i);

            }
        },

        delay : 60
    };
            
    /** Méthods **/
    var methods = 
    {
        
        
        /**
         *  Initialisation du bandeau scrollable 
         * @param {type}    // Une collection d'attribut CSS 
         */
        init: function(arra_obj_option) 
        {
            
            //Pour chaque élement correspondant
            this.each(function()
            {

                //Référence vers l'élement du DOM
                var Me = $(this);
                //console.log(arra_obj_option);
                var bool_instance_php = (Me.hasClass('header_scrollable')) ? true : false;

                //Récuperation et déduction de l'ID
                var stri_id = Me.attr('id');
                    stri_id = (stri_id===undefined)? "tr__"+parseInt(global_option.length) : stri_id;
                    stri_id = (stri_id.indexOf('__')==-1) ? stri_id+'__'+parseInt(global_option.length) : stri_id;

                //Déduction du constructeur à utilisé
                var stri_construct = (bool_instance_php) ? '__setStyleById' : '__setStyleByOption';
                
                
                //Découpe de l'ID pour connaitre l'élément parmis la collection
                var arra_part_id = stri_id.split('__');
                var stri_idx = arra_part_id[1];

                //Gestion des index non-defini
                if (!global_option[stri_idx])
                { global_option[stri_idx] = {}; }
                var ref_global_option = global_option[stri_idx];


                //Clonnage et déduction position dans DOM
                //
                
                
                //Initialisation attibuts
                ref_global_option.obj_selecteur_entete = Me;
                ref_global_option.obj_clone_entete = Me.clone(true);
    
                //En déduit les marge et sa position
                //ref_global_option.int_top =  ref_global_option.obj_selecteur_entete.offset().top - parseFloat(ref_global_option.obj_selecteur_entete.css('marginTop').replace('auto', 0));
                ref_global_option.int_top =  $(ref_global_option.obj_selecteur_entete).offset().top - parseFloat($(ref_global_option.obj_selecteur_entete).css('marginTop').replace('auto', 0));
                ref_global_option.bool_cloned = false;
                
            
                
                //Ajout des listener (évenement)
                methods.__construct();


                //Détermine le setter à utilisé pour le style
                arra_obj_option = (bool_instance_php) ? stri_id : arra_obj_option;
                var stri_style = methods[stri_construct](arra_obj_option);

                //Définition ID et style 
                ref_global_option.stri_class = stri_id ;
                ref_global_option.stri_style = stri_style ;
                

            });
            
            //Retourne le clone fraichement modélisé 
            return  global_option[global_option.length-1].obj_clone_entete; 
            

        },
        
        
        /**
         * Initialisation générale des listener
         * 
         * @param {type} ref_global_option // Référence vers la zone mémoire 
         * @param {type} Me                // Référence vers l'input DOM
         * @returns {undefined}
         */
       __construct : function()
       {
           //Timmer
            var tm;
           
             //Listener sur l'évenement de défillment 
            $(window).on('scroll', function (e) {
                
                var obj_window = this;
                
                var int_scroll_top = $(obj_window).scrollTop();
                var int_scroll_left = $(obj_window).scrollLeft();
                
                //Gestion du bug d'affichage dans le cas ou l'entete flottante n'est pas libérée
                //Mais le body est au top du navigateur
                if (int_scroll_top == '0' || int_scroll_left == '0' )
                {
                    //Déclenchement de l'algo
                    tm = methods.start(obj_window);
                }
                
                
                //Condition pour délai
                if (scrollHandling.allow || int_scroll_left > '0')
                {
                    //Déclenchement de l'algo
                    tm = methods.start(obj_window);
                }
                
                
                
                if (tm)
                { clearTimeout(tm); }

                return true;

            });

            //Gestion agrandissement/reduction fenetre
            $(window).resize(function (e)
            {
                //Déclenche le calcul des tailles
                scrollHandling.resize(this);
            });
            
            
            
            /**
             * Gestion cohérence value des inputs présent dans les selecteur entete et selecteur clone
             */
            
            var obj_entete = global_option[global_option.length-1].obj_selecteur_entete;           
            var obj_clone = global_option[global_option.length-1].obj_clone_entete;           

            /*var obj_entete = ref_global_option.obj_selecteur_entete;           
            var obj_clone = ref_global_option.obj_clone_entete;           
           */
          

            //pour chaque input présent dans les entetes
            var arra_selector = obj_entete.add(obj_clone);
            arra_selector.find(':input').change(function()
            {
                
                //Référence vers l'input
                var obj_real_input = this;
                //Sa value
                var stri_value = $(obj_real_input).val();
                //Son nom et le type du tag HTML
                var stri_name = $(obj_real_input).attr('name');
                var stri_type = obj_real_input.nodeName;

                
                //Cible les input concerné
                var obj_input_clone = obj_clone.find(stri_type+'[name="'+stri_name+'"]');
                var obj_input = obj_entete.find(stri_type+'[name="'+stri_name+'"]');
                
                //Défini la value 
                var arra_input = obj_input_clone.add(obj_input);
                
                    arra_input.each(function()
                    { 
                        //- Value type texte
                        if (obj_real_input.type == 'checkbox')
                        { $(this).val(stri_value);  }
                        
                        //- Checkbox
                        if (obj_real_input.type == 'checkbox')
                        {
                            if ($(obj_real_input).is(':checked'))
                            { $(this).prop('checked', true); }
                            else
                            { $(this).prop('checked', false); }
                        }
                    });
                
                
            });
            
            var arra_selector = obj_clone;
            arra_selector.find(':input').click(function()
            {
                //e.preventDefault();
                //e.stopPropagation();
                
                var stri_selector = this.tagName + '[name="'+$(this).attr('name')+'"]';
                    
                $(obj_entete).find(stri_selector).trigger('click');
                
            });
            
            var arra_selector = obj_clone;
            arra_selector.find('.accordion').each(function()
            {
                $(this).accordion({
                        active: false,
                        event: 'click',
                        heightStyle: 'content',
                        clearStyle: false,
                        collapsible: true 
      		});
                
            });
            
            
       },
       
       start : function(obj_window)
       {
           //Update
            scrollHandling.allow = false;
            //Déclenchement de l'algo
            scrollHandling.trigger(obj_window);
            //Permet la réactivation de l'algo
            return setTimeout(scrollHandling.reallow, scrollHandling.delay);
            
       },
       
       /**
        * Permet d'initalisé le bandeau à l'aide d'une instance PHP
        * 
        * @param {type} stri_id                 // Référence vers l'input DOM
        * @returns {undefined}
        */
       __setStyleById : function( stri_id )
       {
           //Rémonte le style caché dans le DOM
           return $('#'+ stri_id+'__style_json').html();
       },
       
       
       /**
        * Permet d'initilisé le beand à l'aide d'option perso
        * 
        * @param {type} arra_obj_option
        * @returns {undefined}
        */
       __setStyleByOption: function( arra_obj_option )
       {
           
           
            //Si aucun parametre
            //Gestion du style par défaut
            if (!arra_obj_option)
            { arra_obj_option = { "position" : "fixed" , "top":"0px", "vertical-align": "middle"}; }


            //Création du style défini en parametre
            var arra_keys = Object.keys(arra_obj_option);
            //console.log(arra_keys)

            var bool_position_find= false;
            //Pose des attributs spécifié en parametre
            var stri_style = '';
            for(var i=0; i<arra_keys.length; i++)
            {
                var stri_attr = arra_keys[i];
                //console.log(arra_keys[i]);
                var stri_value = arra_obj_option[arra_keys[i]];
                //console.log(arra_obj_option[arra_keys[i]]);
                stri_style += stri_attr+': '+stri_value+'; ';
                
                bool_position_find = (stri_attr==="position") ? true : false;
            }

            //Gestion du manques d'informations
            if (!bool_position_find)
            { stri_style+=' position: fixed;'; }

           return stri_style;
       },
       
       
       
        
        
        /*
        * Attache la nouvelle en-tete au DOM
        * 
        * @returns {none}
        * 
        */
       //function cloneHeader(selector)
       cloneHeader : function(stri_idx)
       {
           
           //Référence vers zone mémoire
           var obj = global_option[stri_idx];
           
           var bool_cloned = obj.bool_cloned;
           var obj_selecteur_entete = obj.obj_selecteur_entete;
           var obj_clone_entete = obj.obj_clone_entete;
           var stri_style = obj.stri_style;
                   
           //Condition si déja initialisé
           if (! bool_cloned && !obj_selecteur_entete.hasClass('cloned'))
           {
               //Flag
               obj_selecteur_entete.addClass('cloned');


               //Param du clone
               obj_clone_entete
                   .attr('style', stri_style )
                   .css('text-align','center');
                   //.children('td:first').height('20px');
                   
               //Style du bouton de sauvegarde dispo dans l'entete
                obj_clone_entete.find(':input, .action').css('display','').tooltip();

               //Attache aux DOM
               $('body').after(obj_clone_entete);



                //Gestion clonnage calendrier
                var obj_calendar = obj_clone_entete.find('.calendar_jquery');
                //console.log(obj_calendar);
                if (obj_calendar.length>0)
                {
                    obj_calendar.removeAttr('id');
                    obj_calendar.removeClass('initialized hasDatepicker');
                }
                

               
               //Définition du style du clone
                methods.setCloneStyle(stri_idx);
                
                
                //Taille des cellules
                methods.setCloneWidth(stri_idx);
                
                //- Pose de la backTop
                methods.setLinkBackTop(stri_idx);
            
            
                

           }

            // Définition taille et position dans le DOM
            methods.setClonePosition(stri_idx);
            

           //Affichage
           obj_clone_entete.fadeIn('200');

           //Flag
           bool_cloned = true;


    
    
           return true;

       },
       
        /*
        * Permet de supprimé l'en-tete flotante
        * 
        * @returns {none}
        * 
        */
       releaseHeader : function(stri_idx)
       {

           //Référence vers zone mémoire
           var obj = global_option[stri_idx];
           
           var obj_selecteur_entete = obj.obj_selecteur_entete;
           var obj_clone_entete = obj.obj_clone_entete;
           
           if (!obj_clone_entete)
           { return false; }

           //Se positionne sur l'entete flotante et la masque
           obj_clone_entete.hide();

           //Selecteur vrai entete
           obj_selecteur_entete.removeClass('cloned');


           //- Remove backTop
           $("#header_scrollable__back_top").fadeOut().remove();
           

           return true ;
       },
       
       
    /*
     * Défini une anchre vers le haut de page
     * @returns {none}
     */
    setLinkBackTop: function(stri_idx)
    {
           
        //- Création de la div
        var obj_div = document.createElement("DIV");
            obj_div.id = 'header_scrollable__back_top';
            obj_div.className = 'entete titre2';
            obj_div.innerHTML = '&nbsp; &#8682; Haut de page &#8682; &nbsp; &nbsp; ';
            obj_div.style.position = 'fixed';
            obj_div.style.bottom = '16px';
            obj_div.style.right = '16px';
            obj_div.style.cursor = 'pointer';
            $(obj_div).attr('onclick','$("body").scrollTo(0); ');

        
        //- Ajout au DOM
        $("html").append(obj_div.outerHTML);
        
    },
    
        /*
     * Défini le style des cellules
     * Si un élément ayant la class .action est trouvé, un style est appliqué à la cellule
     * 
     * @returns {none}
     * 
     */
    setCloneStyle: function(stri_idx)
    {
        
            //Référence vers zone mémoire
            var obj = global_option[stri_idx];

            var obj_clone_entete = obj.obj_clone_entete;


            //TEST Seleteur
            if (obj_clone_entete.find('input').length <= 0)
            { return; }

            //obj_clone_entete.children('td').first()
            obj_clone_entete.children('td').find('.action').parent()
                .on('mouseover', function ()
                {
                    $(this).css('background-color', '#F8DA4E');
                })
                .on('mouseout', function ()
                {
                    $(this).css('background-color', 'white');
                })
                .css({
                    border: '1px solid black',
                    "border-radius": '5px',
                    "background-color": 'rgb(255, 255, 255)',
                    "box-shadow": '10px 1px 12px rgb(85, 85, 85)',
                    cursor: 'pointer'
                })
                .click(function(e)
                {
                    //Déduction de l'action 
                    var stri_onclick = $(this).children('.action').first().attr('onclick');

                    //Appel 
                    eval(stri_onclick);
                    
                })
                .find('input').show();



            return ;

    },
    
    
    setClonePosition : function (stri_idx)
    {
        
        //Référence vers zone mémoire
        var obj = global_option[stri_idx];

        var obj_selecteur_entete = obj.obj_selecteur_entete;
        var obj_clone_entete = obj.obj_clone_entete;



        if (!obj_clone_entete || obj_clone_entete.length==0)
        { return false; }

        //Posisiton de l'élément
        var obj_table_position = obj_selecteur_entete.position();

        //Scroll horizontal
        var int_left_scroll = $(window).scrollLeft();

        //En déduit la position left du clone
        var int_left_posistion = obj_table_position.left - int_left_scroll;

        //taille et posisiton sur le DOM
        obj_clone_entete.css( {left: int_left_posistion } );


        
        return ;
    },
    
    
    setCloneWidth : function(stri_idx)
    {
        
        //Référence vers zone mémoire
        var obj = global_option[stri_idx];

        var obj_selecteur_entete = obj.obj_selecteur_entete;
        var obj_clone_entete = obj.obj_clone_entete;
        
        
        //Référence vers l'herder flottant
        if (!obj_clone_entete || obj_clone_entete.length==0)
        { return false; }
        
        var int_margin_right = parseFloat($(obj_selecteur_entete).css('paddingRight').replace('auto', 0));
        var int_margin_left = parseFloat($(obj_selecteur_entete).css('paddingLeft').replace('auto', 0));

        //Taille        
        //var int_width = obj_selecteur_entete.width();
        var int_width = obj_selecteur_entete.width();
            int_width = (window.chrome) ? obj_selecteur_entete.width() +int_margin_right +int_margin_left : int_width;
        //console.log(int_width);
        
        obj_clone_entete.width(int_width);

        
        //Parcours l'entete orginal afin de déterminé la taille de chaque cellule
        var arra_real_td = obj_selecteur_entete.children('td');

         //Stylisation
        var arra_td  = obj_clone_entete.children('td');
         

        //parcours chaque TD du clone
        for(var i=0; i< arra_td.length; i++ )
        {
             
            var obj_td = arra_td[i];
            var obj_real_td = arra_real_td[i];
             
             
            var int_margin_right = parseFloat($(obj_real_td).css('paddingRight').replace('auto', 0));
            var int_margin_left = parseFloat($(obj_real_td).css('paddingLeft').replace('auto', 0));

            var stri_width =  parseInt($(obj_real_td).css('width')) + int_margin_right + int_margin_left;      //Taille de la cellule;
            var stri_min_width = parseInt(stri_width-20);

            var obj_css =  {
                   padding: '1px',
                   margin: '1px',
                   width : stri_width+'px',
                   "min-width" : stri_min_width+'px',
                   "max-width" : stri_width+'px'
               };

            //Border et Padding
            $(obj_td).css( obj_css ).children('td').css(obj_css);



                
        }
         
         
        return ;
        
    }

        
        
    };

    
    /**
     * Ratachement du plug-in headerScrollable à l'objet jQuery
     * 
     * @param {string} method
     * @returns {aucun}
     */
    $.fn.headerScrollable = function(method) 
    {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.headerScrollable plug-in ');
        }

    };

})(jQuery);