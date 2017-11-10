<?php
/*******************************************************************************
Create Date :24 /06/2009
 ----------------------------------------------------------------------
 Class name : imageAdaptator
 Version : 1.0
 Author : Rémy Soleillant
 Description : Permer de faire différent traitement sur les images afin qu'elles 
               soient compatible avec la génération de pdf
********************************************************************************/

class html2pdf_imageAdaptator  
{
  //**** attribute *************************************************************
  protected $stri_src; //La source d'origine de l'image
  protected $stri_adapted_src; //La source de l'image adaptée
  static protected $arra_know; //Tableau des images déjà traitées 
  //**** constructor ***********************************************************
   
   /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe rules_applicator   
   *                        
   **************************************************************/         
  function __construct($stri_src) 
  {
   $this->stri_src=$stri_src;
   $this->stri_adapted_src=$stri_src;
  }  
 
  //**** setter ****************************************************************
  public function setRules($value){$this->arra_rules=$value;}
    
  //**** getter ****************************************************************
  public function getRules(){return $this->arra_rules;}  
 
  
  //**** public method *********************************************************
   /*************************************************************
   *
   *  Permet d'uniformiser les images
   *  Paramètres : string	nom du fichier image source    
   *  Retour : string le chemin de l'image à utiliser   
   *                        
   **************************************************************/         
  	public function adaptImage()
		{
		 $stri_image_name=basename($this->stri_src);
     $arra_know=self::$arra_know;
     
     if(substr($this->stri_src,0,5)=="file:")
     {
      return $_SERVER["DOCUMENT_ROOT"]."/images/module/BUT_P_clair.gif";//on remplace les images local par _une image invisible
     }
     
     if(isset($arra_know[$stri_image_name]))
     {//on a déjà traitée cette image, on ne fait pas deux fois le même traitement 
      return $arra_know[$stri_image_name];
     } 
      
		 $this->adaptPath();
		 //$this->adaptSize(120);
		 $this->adaptExtension();
		 $this->adaptAlphaChannel();
		 
		 $arra_know[$stri_image_name]=$this->stri_adapted_src;
		 self::$arra_know=$arra_know;

		 return $this->stri_adapted_src;
    }
  
   /*************************************************************
   *
   *  Permet d'uniformiser le chemin de l'image
   *  Paramètres : aucun
   *  Retour : string le chemin de l'image à utiliser   
   *                        
   **************************************************************/         
  	public function adaptPath()
		{
		  $src=$this->stri_adapted_src;
      
      //pour ne pas avoir les url en absolu en http
      $src=str_replace(array('http://10.10.66.107/','https://internal.savoyeline.com/'), '',$src);
      
    
      //on met les images en chemin absolu
		  if(substr($src,0,1)=="/" )
		  {
       $src=$_SERVER["DOCUMENT_ROOT"].substr($src,1);
      }
		  //décode de l'url de la source
      $src=urldecode($src);
      //on indique la source adaptée
      $this->stri_adapted_src=$src;
      return $src;
    }
   
   /*************************************************************
   *
   *  Permet de convertir l'extension de l'image si elle n'est pas supportée
   *  par le pdf   
   *  Paramètres : aucun
   *  Retour : string le chemin de l'image à utiliser   
   *                        
   **************************************************************/         
  	public function adaptExtension()
  	{
  	  $src=$this->stri_adapted_src;//Le chemin de l'image
      $arra_not_allowed=array(".bmp");//Tableau des extension non permise
  	  $arra_extension=array(".gif"=>1,".jpg"=>2,".jpeg"=>2,".png"=>3,".bmp"=>6);//Correspondance entre type d'image et extension
      $stri_extension=strtolower(strrchr($this->stri_adapted_src,"."));//Recherche de l'extension
      $stri_name=basename($this->stri_adapted_src,$stri_extension);//Extraction du nom du fichier
      $stri_image_path=dirname($src);//le chemin du fichier
      
      $arra_info=getimagesize($src);//récupération des informations sur le fichier
      //vérification du type de fichier
      $int_real_type=$arra_info[2];
      $int_type=$arra_extension[$stri_extension];
     
      //cas des images non supportées

      if(in_array($int_real_type,$arra_extension)===false)
      {trigger_error(_ERROR_IMG_FORMAT_NOT_SUPORTED." ($src)", E_USER_ERROR);}
      
      //cas où le nom du fichier porte une extension qui n'est pas le type réel de l'image. Exemple monimage.bmp est en fait monimage.png
      if($int_real_type!=$int_type)
      {//on copie l'image en changeant seulement l'extension

       $stri_real_type=array_search($int_real_type, $arra_extension); //Recherche de l'extension réelle
       $stri_final_path=$stri_image_path."/".$stri_name.$stri_real_type;
       $bool_res=copy($src,$stri_final_path);//Copie de l'image avec la nouvelle extension
       if($bool_res===false)
       {trigger_error(_ERROR_IMG_CONVERSION_FAILED." ($src)", E_USER_ERROR);}
       $this->stri_adapted_src=$stri_final_path;//L'image à utilisée est la copie
       return $this->stri_adapted_src;
      }
      
      //l'extension n'est pas interdite, aucun traitement supplémentaire nécessaire
      if(!(in_array(strtolower($stri_extension),$arra_not_allowed )))
      {

      return $this->stri_adapted_src;}
      
      //Arrivé ici, on lance une conversion de l'image bmp au format jpg
      
       $stri_final_path=$stri_image_path."/".$stri_name.".jpg";//Nom de l'image à utiliser. 
       $image = $this->ImageCreateFromBMP($src);//Ouverture d'une ressource
       if($image===false)//si la ressource n'a pu être obtenue, on ne pourra pas faire la conversion
       {trigger_error(_ERROR_IMG_CONVERSION_FAILED." ($src)", E_USER_ERROR);}
       imagejpeg($image, $stri_final_path);//Création de l'image jpg
       $this->stri_adapted_src=$stri_final_path;//On change le chemin de l'image à utiliser
       return $this->stri_adapted_src;
    }
		
   
   /*************************************************************
   *
   *  Permet d'adapter la taille de l'image en fonction de sa largeur; 
   *  Créer une image redimentionnée si nécessaire.   
   *  Paramètres : int	largeur maximale de l'image  
   *  Retour : string le chemin de l'image à utiliser   
   *                        
   **************************************************************/         
  	public function adaptSize($int_max_width)
		{
		  $src=$this->stri_adapted_src;
		  $arra_info=getimagesize($src);
		  $int_width=$arra_info[0];
		  
		  //si l'image ne dépasse pas la largeur maximale
		  if($int_max_width>=$int_width)
		  {
		   //taille ok, pas de traitement nécessaire
       $this->stri_adapted_src=$src;
       return $src;
      }
      
      //calcul du facteur de réduction sur la largeur
      $int_reduce_factor=$int_max_width/$int_width;
      $stri_final_path=dirname($src)."/mini_".basename ($src); 
      
      // vous pouvez travailler en url relative aussi: img.jpg
      $x = $int_max_width; // largeur a redimensionner
      $y = round($arra_info[1]*$int_reduce_factor,0); //application du facteur de réduction sur la hauteur
      //$x=$arra_info[0];
      $y=$arra_info[1];
      
      //echo "facteur de réduction $int_reduce_factor, x : ".$arra_info[0]."=> $x, y :".$arra_info[1]."=> $y <br />";
      $percent=0.5;
     
      // Calcul des nouvelles dimensions
      list($width, $height) = getimagesize($src);
      $new_width = $width * $int_reduce_factor;
      $new_height = $height * $int_reduce_factor;
      
      // Redimensionnement
      $image_p = imagecreatetruecolor($new_width, $new_height);
      $image = imagecreatefromjpeg($src);            
      $bool = imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
      $bool2 = imagejpeg($image_p, $stri_final_path, 100);
      //var_dump($bool2);
      //echo '<br />'.$bool2;
      $this->stri_adapted_src=$stri_final_path;
       
      
       return $stri_final_path;
		}
		
  	 /*************************************************************
     *
     *  Permet d'adapter la taille de l'image en fonction de sa largeur; 
     *  Paramètres :  int largeur actuel du style css
     *                int	largeur maximale de l'image  
     *  Retour : int la largeur à utiliser   
     *                        
     **************************************************************/           
			public function resizeStyleWidth($int_width,$int_max_width)
			{
       if($int_width<=$int_max_width)
       {return $int_width;}   
       //on dépasse la largeur maximale
       return $int_max_width;
      }
  
  /*************************************************************
   *
   *  Permet de supprimer la transparence alpha d'une image 
   *  Paramètres : aucun  
   *  Retour : string le chemin de l'image à utiliser   
   *                        
   **************************************************************/         
  	public function adaptAlphaChannel()
		{ 
  	 $src=$this->stri_adapted_src;
  	 $arra_info=getimagesize($src);
  	$stri_extension=strtolower(strrchr($this->stri_adapted_src,"."));//Recherche de l'extension
      $stri_name=basename($this->stri_adapted_src,$stri_extension);//Extraction du nom du fichier
      $stri_image_path=dirname($src);//le chemin du fichier
  	$stri_final_path=$stri_image_path."/".$stri_name.".jpg";//Nom de l'image à utiliser. 
  
	   
	  //si l'image n'est pas un png, aucun traitemen nécessaire
    if($arra_info[2] !== IMAGETYPE_PNG)
    {return $src;}
    

      
    //On va remplacer la transparence dans l'image par du blanc
    $srcImg = imagecreatefrompng($src);///Ouverture de l'image d'origine



    //si l'image n'est pas en truecolor, aucun traitement nécessaire
    if(!imageistruecolor($srcImg))
    {return $src;}

    $thumbImg = ImageCreateTrueColor($arra_info[0], $arra_info[1]);//Création d'une image vierge de même taille que la source
 
    imagealphablending($thumbImg, true);//Activation de la couche alpha
    imagesavealpha($thumbImg,true);
    imagefill($thumbImg,0,0,imagecolorallocatealpha($thumbImg, 255, 255, 255, 127));//On remplit la miniature avec un blanc opaque
    
    imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $arra_info[0], $arra_info[1], $arra_info[0], $arra_info[1]);//On copie l'image source vers destination
		imagejpeg($thumbImg,$stri_final_path);//On convertit l'image au format jpg  
    $this->stri_adapted_src=$stri_final_path;//On change le chemin de l'image à utiliser
    return $this->stri_adapted_src;
    
		}
      
  /*********************************************/
  /* Fonction: ImageCreateFromBMP              */
  /* Author:   DHKold                          */
  /* Contact:  admin@dhkold.com                */
  /* Date:     The 15th of June 2005           */
  /* Version:  2.0B                            */
  /*********************************************/

 public function ImageCreateFromBMP($filename)
  {
   //Ouverture du fichier en mode binaire
     if (! $f1 = fopen($filename,"rb")) return FALSE;
  
   //1 : Chargement des ent?tes FICHIER
     $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1,14));
     if ($FILE['file_type'] != 19778) return FALSE;
  
   //2 : Chargement des ent?tes BMP
     $BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
                   '/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
                   '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1,40));
     $BMP['colors'] = pow(2,$BMP['bits_per_pixel']);
     if ($BMP['size_bitmap'] == 0) $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
     $BMP['bytes_per_pixel'] = $BMP['bits_per_pixel']/8;
     $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
     $BMP['decal'] = ($BMP['width']*$BMP['bytes_per_pixel']/4);
     $BMP['decal'] -= floor($BMP['width']*$BMP['bytes_per_pixel']/4);
     $BMP['decal'] = 4-(4*$BMP['decal']);
     if ($BMP['decal'] == 4) $BMP['decal'] = 0;
  
   //3 : Chargement des couleurs de la palette
     $PALETTE = array();
     if ($BMP['colors'] < 16777216)
     {
      $PALETTE = unpack('V'.$BMP['colors'], fread($f1,$BMP['colors']*4));
     }
  
   //4 : Cr?ation de l'image
     $IMG = fread($f1,$BMP['size_bitmap']);
     $VIDE = chr(0);
  
     $res = imagecreatetruecolor($BMP['width'],$BMP['height']);
     $P = 0;
     $Y = $BMP['height']-1;
     while ($Y >= 0)
     {
      $X=0;
      while ($X < $BMP['width'])
      {
       if ($BMP['bits_per_pixel'] == 24)
          $COLOR = unpack("V",substr($IMG,$P,3).$VIDE);
       elseif ($BMP['bits_per_pixel'] == 16)
       { 
          $COLOR = unpack("n",substr($IMG,$P,2));
          $COLOR[1] = $PALETTE[$COLOR[1]+1];
       }
       elseif ($BMP['bits_per_pixel'] == 8)
       { 
          $COLOR = unpack("n",$VIDE.substr($IMG,$P,1));
          $COLOR[1] = $PALETTE[$COLOR[1]+1];
       }
       elseif ($BMP['bits_per_pixel'] == 4)
       {
          $COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
          if (($P*2)%2 == 0) $COLOR[1] = ($COLOR[1] >> 4) ; else $COLOR[1] = ($COLOR[1] & 0x0F);
          $COLOR[1] = $PALETTE[$COLOR[1]+1];
       }
       elseif ($BMP['bits_per_pixel'] == 1)
       {
          $COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
          if     (($P*8)%8 == 0) $COLOR[1] =  $COLOR[1]        >>7;
          elseif (($P*8)%8 == 1) $COLOR[1] = ($COLOR[1] & 0x40)>>6;
          elseif (($P*8)%8 == 2) $COLOR[1] = ($COLOR[1] & 0x20)>>5;
          elseif (($P*8)%8 == 3) $COLOR[1] = ($COLOR[1] & 0x10)>>4;
          elseif (($P*8)%8 == 4) $COLOR[1] = ($COLOR[1] & 0x8)>>3;
          elseif (($P*8)%8 == 5) $COLOR[1] = ($COLOR[1] & 0x4)>>2;
          elseif (($P*8)%8 == 6) $COLOR[1] = ($COLOR[1] & 0x2)>>1;
          elseif (($P*8)%8 == 7) $COLOR[1] = ($COLOR[1] & 0x1);
          $COLOR[1] = $PALETTE[$COLOR[1]+1];
       }
       else
          return FALSE;
       imagesetpixel($res,$X,$Y,$COLOR[1]);
       $X++;
       $P += $BMP['bytes_per_pixel'];
      }
      $Y--;
      $P+=$BMP['decal'];
     }
  
   //Fermeture du fichier
     fclose($f1);
  
   return $res;
  }
}




?>
