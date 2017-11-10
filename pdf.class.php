<?php
/*******************************************************************************
Create Date : 28/12/2016
 ----------------------------------------------------------------------
 Class name : PDF  Version : 1.1
 Author : ROBERT Romain
 Description : Permet de représenter un document PDF.
 *              La classe s'appuie sur la librairie et la commande système wkhtmltopdf
 *              Ceci est une classes d'intégration
********************************************************************************/

class pdf {
   
    //**** attribute ************************************************************
    protected $obj_pdf;
    
    
    //**** constructor ***********************************************************
    /**
     * Contructeur 
     * @param type $mixed_url   => //- une url ou un  un chemin vers le fichier HTML
     */
    public function __construct($mixed_url) 
    {
     
        //- Collection de style CSS
        $arra_css = [
                $_SERVER["DOCUMENT_ROOT"].'includes/classes/phpwkhtmltopdf/pdf.css',
                $_SERVER["DOCUMENT_ROOT"].'includes/classes/jquery-ui-1.8.16.custom/css/start/jquery-ui-1.8.16.custom.css'
        ];
        
        //- Quelques options pour  WKHTMLTOPDF
        $arra_option = [
                
                // Make Chrome not complain
                'no-outline',         
                //- Interpréatation JS
                'javascript-delay' => '500',         
                //- Url du proxy
                //'proxy' => 'http://proxy:3129',         
                //- Gestion des erreurs
                'load-error-handling' => 'ignore',         
                'load-media-error-handling' => 'ignore',         
                'ignoreWarnings'=>true,

                //- Les styles CSS
                'user-style-sheet' => $arra_css,
                
                /*'run-script' => array(
                    '/path/to/local1.js',
                    '/path/to/local2.js',
                ), */
                /*
                'replace' => [
                    '{page_cu}' => 'serom1',
                    '{page_nb}' => 'serom2',
                ],*/
        ];

        
        //- On spécifie le namespace utilisé par la librairie wkhtmltopdf
        $this->obj_pdf = new mikehaertl\wkhtmlto\Pdf($mixed_url);
            $this->obj_pdf->setOptions($arra_option);

    }

    //**** setter ****************************************************************

    //**** getter ****************************************************************

    
    //**** public method *********************************************************
    
    
    
    /**
     * Permet d'ajouter une page de couverture au sein du document PDF
     * 
     * @param type $mixed_url
     * @return type
     */
    public function addCover($mixed_url)
    {
        return $this->obj_pdf->addCover($mixed_url);
    }
    
    
    /**
     * Permet l'ajout d'une page au sein du document PDF
     * 
     * @param type $mixed_url
     * @return type
     */
    public function addPage($mixed_url,$arra_options)
    {
        return $this->obj_pdf->addPage($mixed_url,$arra_options);
    }
    
    /**
     * Génère une table de contenues
     * 
     * @return type
     */
    public function addToc()
    {
        return $this->obj_pdf->addToc();
    }
    
    
    /**
     * Méthode pour enregistrer le fichier PDF
     * @param type $stri_destination    => //- Le chemin vers lequel sauvergarder les données
     * @return type
     */
    public function saveAs($stri_destination)
    {
        if (!$this->obj_pdf->saveAs($stri_destination))
        {
            return $this->getError();
        }
    }

 
    //**** method d'affichage *********************************************************
    
    /**
     * Méthode pour enregistrer le fichier PDF
     * @param type $opt_name    => //- Optionnel : 
     *                              Envoi au client sous la forme d'un téléchargement si paramètre renseigné
     *                              Sinon, affichage en ligne
     * @return type
     */
    public function send($opt_name)
    {
        if (!$this->obj_pdf->send($opt_name))
        {
            return $this->getError();
        }
    }
    
    
    /**
     * Get the raw pdf as a string
     * 
     * @return type
     */
    public function toString()
    {
        $stri_content = $this->obj_pdf->toString();
        if ($stri_content === false)
        {
            return $this->getError();
        }
        return $stri_content;
            
    }

    
    /** Gestion des erreurs *************************************************/
    
    public function getError()
    {
        return $this->obj_pdf->getError();
    }
    
    
  
  
}

?>
