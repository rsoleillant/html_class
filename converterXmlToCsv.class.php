<?php
/*******************************************************************************
Create Date : 24/04/2008
 ----------------------------------------------------------------------
 Class name : converterXmlToCsv
 Version : 1.1
 Author : Rémy Janisset
 Description : élément html <a>
********************************************************************************/
class converterXmlToCsv{

  //**** attribute ************************************************************
   
    protected $stri_chemin_xml;      //=>le chemin du fichier Xml 
    protected $stri_chemin_csv;      //=>le chemin où va être créer le fichier Csv 


  //**** constructor ***********************************************************
  function __construct($chemin_xml,$chemin_csv) 
  {
    //construit l'objet a
    //@param : $chemin_xml => le chemin du fichier Xml (Ex: modules/Mon_Module/MonFichier.xml)
    //@param : $chemin_csv => le chemin où va être créer le fichier Csv (Ex: modules/Mon_Module/MonFichier.csv)
    //

    $this->stri_chemin_xml=$chemin_xml;
    $this->stri_chemin_csv=$chemin_csv;
  }
  //**** public method ********************************************************* 
  public function convert()
  {
  ini_set('memory_limit','128M');
  $racine=simplexml_load_file($this->stri_chemin_xml);
  $ma_chaine=$this->construct_chaine($racine);

  //Ouverture du fichier
  $fichier = fopen($this->stri_chemin_csv,'w+') ;
  //Ecriture du contenu du buffer
  fwrite($fichier,$ma_chaine);
  //Fermeture du fichier
  fclose($fichier) ;
  }
  
  //**** private function *********************************************************
  //Parcour le fichier xml et assemble les différents ligne pour le fichier csv
  //
  private function construct_chaine($var)
    {    
      foreach($var->children() as $a=>$b)
        {
         if($this->is_final_node($b))
           {
             $chaine=$this->create_chaine($var);
           }
         else
           {
            $chaine.=$this->construct_chaine($b);
           }
        }
      $chaine.="\n";
      return $chaine;
    } 
    
  //Détermin si nous nous trouvons au dernier noeud
  private function is_final_node($xml_node)
    {
      $i=0;
      foreach($xml_node as $key=>$element)
        {$i++;}
      return ($i==0)?true:false;
    }

  //Crée la ligne csv pour chaque noeud fils.
  private function create_chaine($noeud)
    {
      foreach($noeud->children() as $a=>$b)
        {
          $temp=str_replace(";",".,",$b);
          $temp = str_replace("\n"," ",$temp);
          $Ma_Chaine .= $temp.";";         
        }
      $Ma_Chaine=utf8_decode($Ma_Chaine);
      return $Ma_Chaine;
      
    }
}

?>    
    
