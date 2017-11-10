<?php

/* * *****************************************************************************
  Create Date : 10/02/2012
  ----------------------------------------------------------------------
  Class name : debugger
  Version : 1.0
  Author : Romain ROBERT
  Description :  Permet de retracer la pile d'éxécution du point d'entrée racine jusqu'a l'appel de cette classe
 * 
 * Forme de retour par défaut :
 *      - Courriel DOI   => asi-dts-outils-internes@a-sis.com
 *      - Fichier de log => debug.log, placé dans le répertoire ou l'appel à eu lieu
 * 

 * ****************************************************************************** */

class debug {

    //**** attribute ************************************************************

    protected $arra_debug_backtrace;        //- La Stack Trace sous forme Array
    protected $stri_debug_print_backtrace;  //- La Stack Trace sous forme chaine formaté
    protected $stri_mail_doi;               //- L'adresse outils interne
    
    protected $stri_path_log;               //- Le chemin ou le log est enregistré
    protected $stri_final_debug;            //- Le debug envoyé et enregistré en fichier
    
    

    //**** constructor ***********************************************************

    function __construct()
    {
        
        //- Initialisation
        $this->init();
        
        //- Lecture du debug
        $this->parseDebug();
        
        //- Écriture dans log
        $this->log();
        
        //- Envoi courriel
        $this->mail();

        
    }
    
    /**
     * Initialisation des attributs
     */
    private function init()
    {
        
        //- Adresse courriel par défaut
        //$this->stri_mail_doi = 'asi-dts-outils-internes@a-sis.com';
        $this->stri_mail_doi = 'romain.robert@a-sis.com';
        
        
        /***************************************************************/
        //- Affichage formaté 
       
        //- On envoi le buffer
        ob_flush();
        flush();
        //- On génère la stack trace
        debug_print_backtrace();
        
        //- Affectation dans un attribut, puis suppréssion du buffer
        $this->stri_debug_print_backtrace = ob_get_contents();
        
        //- On n'affiche pas le debug à l'écran
        ob_clean();
        
        /**************************************************************/
        //- Affichage brut
        
        //- Affectation du tableau associatif de la pile d'éxécution
        $this->arra_debug_backtrace = debug_backtrace();
        
    }


    /**
     * Exploitation et formattage du debug
     */
    private function parseDebug()
    {
        
        //- On remonte à l'appel parent
        $arra_debug_parent = $this->arra_debug_backtrace[1];
        
        //- Contexte d'éxécution
        $stri_file = $arra_debug_parent['file'];
        $int_line = $arra_debug_parent['line'];
         
        //- Chemin ou le log sera enregistré
        $this->stri_path_log = dirname($stri_file);

        
        $stri_log = '['.date('d/m/Y H:i:s').'] ------------------------------------------------------------------------------------ '.PHP_EOL;
        $stri_log.= PHP_EOL;
        $stri_log.= "//- Appel depuis $stri_file, ligne $int_line".PHP_EOL;
        $stri_log.= PHP_EOL;
        $stri_log.= $this->stri_debug_print_backtrace;
        $stri_log.= PHP_EOL;
        $stri_log.= '//- Trace générée par l\'utilisateur : '.pnusergetvar('name',pnusergetvar('uid')).PHP_EOL;
        $stri_log.= PHP_EOL;
        $stri_log.= '-----------------------------------------------------------------------------------------------------------'.PHP_EOL;
        $stri_log.= PHP_EOL.PHP_EOL;
        
        //- Compatibilité Windows/Linux
        $stri_log = str_replace("\n", "\r\n", $stri_log);
        
        
        $this->stri_final_debug=$stri_log;
        
    }
    
    
    //**** public method *********************************************************
    public function log()
    {
        //- Le chemin ou écrire les fichiers
        $stri_file = $this->stri_path_log.'/debug_log.txt';
        
        //- Les données à insérer
        $stri_data = file_get_contents($stri_file).$this->stri_final_debug;
        
        //- Exécution
        return file_put_contents($stri_file, $stri_data);
    }
    
    
    public function mail()
    {
        $stri_subject = 'Debug backtrace DOI :: '.date('d/m/Y H:i:s');
        return mail($this->stri_mail_doi, $stri_subject, $this->stri_final_debug);
    }


}

?>
