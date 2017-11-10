/*******************************************************************************
Create Date  : 2016-09-20
 ----------------------------------------------------------------------
 Plug-in name  : liveTimeStamp
 Version     : 1.0
 Author      : ROBERT Romain
 Description : Plug-in jQuery permettant de gérer de manières globale le calcul sur 
               les dates dynamique 
 
               Initialiation du plug-in en javascript : 
               
               $('.mon_selecteur').liveTimeStamp(); 
                        
********************************************************************************/
(function($) {

   
    /** Référence vers les spans livetimestamp  **/
    var global_reference = [];
    
    /** Timer   **/
    var tm_livetimestamp = null;
    
    /** Méthods **/
    var methods = 
    {
        
        /**
         *  Initialisation 
         * @param {type}    // Une collection d'attribut CSS 
         */
        init: function() 
        {
            
            //Pour chaque élement correspondant
            this.each(function()
            {

                //Référence vers l'élement du DOM
                var Me = $(this);
                /*console.log(Me);
                console.log(Me.data('livetimestamp'));
                */

                //- Mise en cache 
                global_reference.push(Me);
                
                
                
            });
            
            
            //Ajout des listener (évenement)
            methods.__construct();

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
            tm_livetimestamp = setInterval(function()
            {
                for (var i=0; i<=global_reference.length -1; i++)
                {
                    var obj_ref = global_reference[i];
                    $(obj_ref).liveTimeStamp('refresh');
                }
                
                
            },30000);
           
           
       },
       
       refresh : function()
       {
           
           //- Référence vers un span
           var obj_ref = this;
           
           //- Le timestamp du span
           var stri_timestamp = obj_ref.data('livetimestamp');
           
           //- Actuel timestamp
           var stri_timestamp_now = Math.round(new Date().getTime()/1000);
           
           //- Calcul différence
           var int_diff = stri_timestamp_now - stri_timestamp;

           //var int_days = Math.round(int_diff/86400)+1;
           var int_days = Math.round(int_diff/86400);
           
           
           //- Conversion en minutes si inférieur à 1 jour
           //if (int_days == 1 && int_diff < 86400)
           if (int_diff < 86400)
           {
               var arra_data = [];
               
               //arra_data['days'] = Math.round(Math.floor(int_diff/86400));
               arra_data['days'] = Math.floor((int_diff/86400));
               if (arra_data['days'])
               { int_diff = int_diff % 86400; }
               
               
               //arra_data['hours'] = Math.round(Math.floor(int_diff/3600));
               arra_data['hours'] = Math.floor((int_diff/3600));
               if (arra_data['hours'])
               { int_diff = int_diff % 3600; }
               
               
               //arra_data['minutes'] = Math.round(Math.floor(int_diff/60));
               arra_data['minutes'] = Math.floor((int_diff/60));
               if (arra_data['minutes'])
               { int_diff = int_diff % 60; }

               arra_data['seconds'] = int_diff;


               //- Déduction de la méthode de rafraichissement
               var stri_method = (obj_ref.hasClass('livetimestamp__full_date')) ? 'refreshTitle' : 'refreshHtml';
               
               
               
               if (arra_data['hours'] == 0 && arra_data['minutes']<1)
               {
                   obj_ref.liveTimeStamp(stri_method , window.pnlang['__LIB_LESS_ONE_MINUTE'] );
               }
               else if (arra_data['hours'] == 0)
               {
                   obj_ref.liveTimeStamp(stri_method , arra_data['minutes'] + ' '+window.pnlang['__LIB_MINUTES']);
               }
               else
               {
                   obj_ref.liveTimeStamp(stri_method , arra_data['hours'] + ' '+window.pnlang['__LIB_HEURES'] +' '+ arra_data['minutes'] + ' '+window.pnlang['__LIB_MINUTES']);
               }
               
               
               
               
               
           }


       },
       
       /**
        *   Mise à jour du contenu 
        *     
        * @param {type} stri_value
        * @returns {undefined}
        */
       refreshHtml: function(stri_value)
       {
           //- Valeur actuelle
           var stri_origin_value = $(this).html();
           if (stri_origin_value != stri_value)
           {
               //- Performe une annimation et change la valeur
                $(this).fadeOut(function()
                {
                    $(this).html( stri_value ).fadeIn();
                });
               
           }
            
       },
       
       /**
        *   Mise à jour du title
        * 
        * @param {type} stri_value
        * @returns {undefined}
        */
       refreshTitle : function(stri_value)
       {
            //- Fermeture du tootlip
            $(this).tooltip('close');
            //- Changement de la value
            $(this).attr('title', window.pnlang['__LIB_IL_Y_A'] + ' : '+ stri_value);
            //- Ré-instanciation du tooltip
            $(this).tooltip();
       }
       
       
       
       
        
    };

    
    /**
     * Ratachement du plug-in liveTimeStamp à l'objet jQuery
     * 
     * @param {string} method
     * @returns {aucun}
     */
    $.fn.liveTimeStamp = function(method) 
    {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.liveTimeStamp plug-in ');
        }

    };

})(jQuery);