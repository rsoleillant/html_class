<?php

/* * *****************************************************************************
  Create Date : 10/02/2012
  ----------------------------------------------------------------------
  Class name : debugger
  Version : 1.0
  Author : Romain ROBERT
  Description :  Permet de retracer la pile d'�x�cution du point d'entr�e racine jusqu'a l'appel de cette classe
 * 
 * Forme de retour par d�faut :
 *      - Courriel DOI   => asi-dts-outils-internes@a-sis.com
 *      - Fichier de log => debug.log, plac� dans le r�pertoire ou l'appel � eu lieu
 * 

 * ****************************************************************************** */

class debug {

    //**** attribute ************************************************************

    protected $arra_debug_backtrace;        //- La Stack Trace sous forme Array
    protected $stri_debug_print_backtrace;  //- La Stack Trace sous forme chaine format�
    protected $stri_mail_doi;               //- L'adresse outils interne
    
    protected $stri_path_log;               //- Le chemin ou le log est enregistr�
    protected $stri_final_debug;            //- Le debug envoy� et enregistr� en fichier
    
    

    //**** constructor ***********************************************************

    function __construct()
    {
        
        //- Initialisation
        $this->init();
        
        //- Lecture du debug
        $this->parseDebug();
        
        //- �criture dans log
        $this->log();
        
        //- Envoi courriel
        $this->mail();

        
    }
    
    /**
     * Initialisation des attributs
     */
    private function init()
    {
        
        //- Adresse courriel par d�faut
        //$this->stri_mail_doi = 'asi-dts-outils-internes@a-sis.com';
        $this->stri_mail_doi = 'romain.robert@a-sis.com';
        
        
        /***************************************************************/
        //- Affichage format� 
       
        //- On envoi le buffer
        ob_flush();
        flush();
        //- On g�n�re la stack trace
        debug_print_backtrace();
        
        //- Affectation dans un attribut, puis suppr�ssion du buffer
        $this->stri_debug_print_backtrace = ob_get_contents();
        
        //- On n'affiche pas le debug � l'�cran
        ob_clean();
        
        /**************************************************************/
        //- Affichage brut
        
        //- Affectation du tableau associatif de la pile d'�x�cution
        $this->arra_debug_backtrace = debug_backtrace();
        
    }


    /**
     * Exploitation et formattage du debug
     */
    private function parseDebug()
    {
        
        //- On remonte � l'appel parent
        $arra_debug_parent = $this->arra_debug_backtrace[1];
        
        //- Contexte d'�x�cution
        $stri_file = $arra_debug_parent['file'];
        $int_line = $arra_debug_parent['line'];
         
        //- Chemin ou le log sera enregistr�
        $this->stri_path_log = dirname($stri_file);

        
        $stri_log = '['.date('d/m/Y H:i:s').'] ------------------------------------------------------------------------------------ '.PHP_EOL;
        $stri_log.= PHP_EOL;
        $stri_log.= "//- Appel depuis $stri_file, ligne $int_line".PHP_EOL;
        $stri_log.= PHP_EOL;
        $stri_log.= $this->stri_debug_print_backtrace;
        $stri_log.= PHP_EOL;
        $stri_log.= '//- Trace g�n�r�e par l\'utilisateur : '.pnusergetvar('name',pnusergetvar('uid')).PHP_EOL;
        $stri_log.= PHP_EOL;
        $stri_log.= '-----------------------------------------------------------------------------------------------------------'.PHP_EOL;
        $stri_log.= PHP_EOL.PHP_EOL;
        
        //- Compatibilit� Windows/Linux
        $stri_log = str_replace("\n", "\r\n", $stri_log);
        
        
        $this->stri_final_debug=$stri_log;
        
    }
    
    
    //**** public method *********************************************************
    public function log()
    {
        //- Le chemin ou �crire les fichiers
        $stri_file = $this->stri_path_log.'/debug_log.txt';
        
        //- Les donn�es � ins�rer
        $stri_data = file_get_contents($stri_file).$this->stri_final_debug;
        
        //- Ex�cution
        return file_put_contents($stri_file, $stri_data);
    }
    
    
    public function mail()
    {
        $stri_subject = 'Debug backtrace DOI :: '.date('d/m/Y H:i:s');
        return mail($this->stri_mail_doi, $stri_subject, $this->stri_final_debug);
    }


}

?>
