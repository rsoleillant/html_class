<?php
/*******************************************************************************
Create Date :24 /06/2009
 ----------------------------------------------------------------------
 Class name : imageAdaptator
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permer de faire différent traitement sur les images afin qu'elles 
               soient compatible avec la génération de pdf
 Upedate : 16/03/2010 BY Yoann Frommelt
********************************************************************************/
//require_once('includes/classes/html2pdf/html2pdf_v3.29/html2pdf.class.php');
//require_once('includes/classes/html_class/html2pdf_imageAdaptator.class.php');		//RS : classe d'uniformisation des images

class html2pdf_savoyeline extends html2pdf {
  //**** attribute *************************************************************
  protected $arra_tag_autorise=array('a','b','big','blockquote','br','cite','code','div','em','font','form','h1','h2','h3','h4','h5','h6','hr','i','img','input','li','link','ol','option','p','pre','s','samp','select','small','span','strong','style','sub','sup','table','tbody','td','textarea','tfoot','th','thead','tr','u','ul','page','page_header','page_footer');
  //la liste des tags qui sont interdit et qui seront supprimés
  protected $arra_tag_interdit=array('title','o');
  
  protected $arra_police=array("courier","helvetica","times","symbol","times new roman");//RS : les police qui sont traitées

  
  //**** constructor ***********************************************************
   
   /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe rules_applicator   
   *                        
   **************************************************************/
  // version 3.29         
  function __construct($sens = 'P', $format = 'A4', $langue='fr', $marges = array(5, 5, 5, 8)) {
    parent::__construct($sens, $format, $langue, $marges);
  }
  
  // version 4.00
  /*function __construct($sens = 'P', $format = 'A4', $langue='fr', $unicode=true, $encoding='ISO-8859-15', $marges = array(5, 5, 5, 8)) {  
    parent::__construct($sens, $format, $langue, $unicode, $encoding, $marges);
  }*/
 
  //**** setter ****************************************************************
    
  //**** getter ****************************************************************
 
  
  //**** public method *********************************************************
  
  /**
  *  Permet de prendre en compte la liste blanche et la liste noire des tags 
  *  Les tags dans la liste blanche seront interprétés
  *  Ceux dans la liste noire seront supprimés
  *  Ceux qui ne sont ni dans l'un ni dans l'autre sertont remplacé par leur entité html   	 
  *  Paramètres : string : le texte à traiter
  *  Retour : string : le texte protégé
  **/         	 
  public function protectTag($html) {
   
    $stri_tag=implode("|",$this->arra_tag_autorise);
    $stri_res=$html;
    
    //- changement des urls des liens    
    $stri_res=preg_replace('/(href=")([^"]*)(")/', '\1\3', $stri_res);
 
    
    $stri_pattern1="`<([^>]*)>`";
    $stri_res= preg_replace($stri_pattern1,'&lt;$1&gt;',$stri_res);//on remplace les < et les > pour chaque balises
  
    $stri_tag_interdit=implode("|",$this->arra_tag_interdit);
    //$stri_pattern2="`&lt;(/?(".$stri_tag_interdit.") ?[^&]*)&gt;`";
    $stri_pattern2="` &lt;(/?(".$stri_tag_interdit.")[ &].*)gt;`iU";
   
    $stri_res= preg_replace($stri_pattern2,'',$stri_res);//suppression des tags interdit
    
    //&lt;(\/?(title|o)[ &]?[^&]*)&gt;
    
    $stri_pattern3="`&lt;(\/?(".$stri_tag.")( [^&]*)?)&gt;`";
    $stri_res= preg_replace($stri_pattern3,'<\1>',$stri_res);//on remet l'accès au tags autorisés
    
     
 
    return $stri_res;
  }
  
  /** RS : Permet de remplacer les entités html des quotes spéciales par celles des quotes normales
   *  Paramètres : string : le code html à corriger
   *  Retour : string : le code html corrigé   
   **/  
  public function remplaceSpecialQuote($html)
	{
     $arra_source=array("&rdquo;",
                       "&ldquo;",
                       "&rsquo;"
                      );
    $arra_remplace=array("&quot;",
                         "&quot;",
                         "&#39;"
                        );
    
    $html_corrected=str_replace($arra_source,$arra_remplace,$html);
    
    return $html_corrected;
  }
  
  /** RS : Permet d'ajouter automatiquement des espaces au "mot" de plus de 96 caractères
   *  Cela permet à la bibliothèque de couper automatiquement les lignes au lieu de générer une
   *  erreur.     
   *  Paramètres : string : le code html à corriger
   *  Retour : string : le code html corrigé   
   **/  
  public function addSpace($html)
  {
    //on va rajouter des espace dans les phrases comportant + de 96 caractères de suite sans espace
    $stri_pattern="/([^[:space:]]{95,95})([^[:space:]])/";//on regarde une suite de 109 carractères sans espace suivi d'un 110 caractères non espace
    $stri_replace="\\1\\2 ";//on ajoute un espace au 110 caractère
    $html_corrected=preg_replace($stri_pattern,$stri_replace , $html);
   
   return $html_corrected;
  }
  
  function WriteHTML($html, $vue = false) {
    $stri_res=$html;
    
     $stri_res = $this->protectTag($stri_res);
     $stri_res = $this->remplaceSpecialQuote($stri_res);
     $stri_res = $this->addSpace($stri_res);

    return parent::WriteHTML($stri_res, $vue);
  }
    
  
    /**
		* tracer une image
		* 
		* @param	string	nom du fichier source
		* @return	null
		*/	
		function Image($src, $sub_li=false)
		{
      
      //RS : uniformisation des images, chemin, taille, extension
      $obj_image_adaptator=new html2pdf_imageAdaptator($src);
      $src=$obj_image_adaptator->adaptImage();
		  if($this->style->value['width'])
		  {
		   $int_max_width=190;//Taille maximale d'affichage des images
       $this->style->value['width']=$obj_image_adaptator->resizeStyleWidth($this->style->value['width'],$int_max_width);
      }

    return parent::Image($src, $sub_li);
  }
  
  	/**
		* balise : SPAN
		* mode : OUVERTURE
		* 
		* @param	array	paramètres de l'élément de parsing
		* @return	null
		*/	
		//RS : 20/10/2009 correction des police non traitées
		function o_SPAN($param, $other = 'span')
		{ 
		
      if((isset( $param['style']['font-family']) )&&(!in_array(strtolower($param['style']['font-family']),$this->arra_police)))
		  {
        $param['style']['font-family']="Times";        
      }  
      
      if((isset(  $param['face']) )&&(!in_array( strtolower($param['face']),$this->arra_police)))
		  { $param['face']="Times";}  
		  
		  //return true;
		  return parent::o_SPAN($param, $other);

		}
    
    
    function o_B($param, $other = 'span')
		{ 
		  
      if((isset( $param['style']['font-family']) )&&(!in_array(strtolower($param['style']['font-family']),$this->arra_police)))
		  {$param['style']['font-family']="Times";}  
      
      if((isset(  $param['face']) )&&(!in_array( strtolower($param['face']),$this->arra_police)))
		  { $param['face']="Times";}  
		  
		  //return true;
		  return parent::o_B($param, $other);

		}
		
    function o_P($param, $other = 'span')
		{ 
		  
      if((isset( $param['style']['font-family']) )&&(!in_array(strtolower($param['style']['font-family']),$this->arra_police)))
		  {$param['style']['font-family']="Times";}  
      
      if((isset(  $param['face']) )&&(!in_array( strtolower($param['face']),$this->arra_police)))
		  { $param['face']="Times";}  
		  
		  //return true;
		  return parent::o_P($param, $other);

		}		  
    
    function o_DIV($param, $other = 'span')
		{ 
		  
      if((isset( $param['style']['font-family']) )&&(!in_array(strtolower($param['style']['font-family']),$this->arra_police)))
		  {$param['style']['font-family']="Times";}  
      
      if((isset(  $param['face']) )&&(!in_array( strtolower($param['face']),$this->arra_police)))
		  { $param['face']="Times";}  
		  
		  //return true;
		  return parent::o_DIV($param, $other);

		}
		 
    function o_A($param, $other = 'span')
		{ 		    
      if((isset( $param['style']['font-family']) )&&(!in_array(strtolower($param['style']['font-family']),$this->arra_police)))
		  {$param['style']['font-family']="Times";}  
      
      if((isset(  $param['face']) )&&(!in_array( strtolower($param['face']),$this->arra_police)))
		  { $param['face']="Times";}  
		  
		  //return true;
		  return parent::o_A($param, $other);

		}
    
    function o_U($param, $other = 'span')
		{ 		    
      if((isset( $param['style']['font-family']) )&&(!in_array(strtolower($param['style']['font-family']),$this->arra_police)))
		  {$param['style']['font-family']="Times";}  
      
      if((isset(  $param['face']) )&&(!in_array( strtolower($param['face']),$this->arra_police)))
		  { $param['face']="Times";}  
		  
		  //return true;
		  return parent::o_U($param, $other);

		}
    
	 	/**
		* création d'un sous HTML2PDF pour la gestion des tableaux imbriqués
		*
		* @param	HTML2PDF	futur sous HTML2PDF passé en référence pour création
		* @param	integer		marge eventuelle de l'objet si simulation d'un TD
		* @return	null
		*/		
		function createSubHTML(&$sub_html, $cellmargin=0)
		{
			// calcul de la largueur
			if ($this->style->value['width'])
			{
				$marge = $cellmargin*2;
				$marge+= $this->style->value['padding']['l'] + $this->style->value['padding']['r'];
				$marge+= $this->style->value['border']['l']['width'] + $this->style->value['border']['r']['width'];
				$marge = $this->pdf->getW() - $this->style->value['width'] + $marge;
			}
			else
				$marge = $this->margeLeft+$this->margeRight;
			
			//clonage
			$sub_html = new html2pdf_savoyeline(//RS : seule ligne de différente avec la méthode mère, il faudrait un meilleur moyen pour faire de la surcharge partielle...
										$this->sens,
										$this->format,
										$this->langue,
										array($this->defaultLeft,$this->defaultTop,$this->defaultRight,$this->defaultBottom)
									);
			$sub_html->setIsSubPart();
			$sub_html->setEncoding($this->encoding);
			$sub_html->setTestTdInOnePage($this->testTDin1page);
			$sub_html->setTestIsImage($this->testIsImage);
			$sub_html->setDefaultFont($this->defaultFont);
			$sub_html->style->css		= $this->style->css;
			$sub_html->style->css_keys	= $this->style->css_keys;
			$sub_html->pdf->cloneFontFrom($this->pdf);
			
			$sub_html->style->table			= $this->style->table;
			$sub_html->style->value			= $this->style->value;
			$sub_html->style->setOnlyLeft();
			$sub_html->setNewPage($this->format, $this->sens);
			$sub_html->initSubHtml($marge, $this->page, $this->defLIST);
			
			// initialisation des positions et autre
			$sub_html->maxX = 0;
			$sub_html->maxY = 0;
			$sub_html->maxH = 0;
			$sub_html->pdf->setXY(0, 0);
		}
		
		
		/**
		* Passage du padding des listes a 0mm sinon il decal le tableau.
		* @param	
		* @return	paddin
		*/
		function listeGetPadding()	{ return '0mm'; }
		
		
    /*function Output($name = '', $dest = false)
    {
      echo "$var fichier :".__FILE__." ligne :".__LINE__."</br>";
      die("Arret automatique");
    }*/
		
}

?>
