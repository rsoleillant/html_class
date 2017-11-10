<?php
/*******************************************************************************
Create Date : 01/02/2011
 ----------------------------------------------------------------------
 Class name : GSAC
 Version : 1.0
 Author : R�my Soleillant
 Description :  Permet de g�rer des listes d�roulants multiple groupe, site , application, contact
********************************************************************************/

class EGSAC
{
  //**** attribute *************************************************************
  protected $bool_societe_non_valide;//Filtre sur les soci�t� non valides; true ont les affiche, false sinon
  protected $obj_ajax_multiselect;//L'objet ajax_muliselect qui va contenir les listes d�roulantes      
  //**** constructor ***********************************************************
   
   /*************************************************************
   *
   * parametres : 
   * retour : objet de la classe rules_applicator   
   *                        
   **************************************************************/         
  function __construct() 
  {
     $this->bool_societe_non_valide=true;
     $this->initialise(); 
  }  
 
  //**** setter ****************************************************************
  public function setSocieteNonValide($value){$this->bool_societe_non_valide=$value;}
  

    
  //**** getter ****************************************************************
  public function getSocieteNonValide(){return $this->bool_societe_non_valide;}
  public function getAjaxMultiselect(){return $this->obj_ajax_multiselect;}
  
 
  
  //**** public method *********************************************************
  /*************************************************************
   * Permet d'initialiser l'ajax_multiselect
   * parametres :   aucun
   * retour : aucun
   *                        
   **************************************************************/ 
  private function initialise()
  {     
    //font 
    $obj_font_company_state=new font(_COMPANY_STATE." : ",true);
    $obj_font_company=new font(_COMPANY." : ",true);
		$obj_font_site=new font(_SITE." : ",true);
		$obj_font_system=new font(_SYSTEM." : ",true);
		$obj_font_contact=new font(_CONTACT." : ",true);
  
      //construction du multiselect
     	$obj_multiselect = new ajaxMultiSelect("egsac");    
      
      //** etat des soci�t�s
       $stri_sql="select 0 valeur,'_ETAT_TOUS' from dual
                  union
                  SELECT valeur_num,'_'||nom 
                  FROM dico_constante dc, dico_lexique dl
                  WHERE nom_table='SOCIETE' AND nom_champ='ETAT'
                        AND dc.num_constante=dl.num_constante";
   
       $obj_select = $obj_multiselect->addSelect("SE_SOCI_ACTIVE",$stri_sql,$obj_font_company_state->htmlValue(),"","constant");
       $obj_select->setClass("pn-hotline-select");     
       
       
       
        //** groupe
        $stri_sql="select distinct groupe 
                   from societe 
                   where decode('[SE_SOCI_ACTIVE]','0','0',etat)='[SE_SOCI_ACTIVE]'  
                   order by groupe";
        //explications de : decode('[SE_SOCI_ACTIVE]','0','0',etat)='[SE_SOCI_ACTIVE]'   
        //  si [SE_SOCI_ACTIVE]!0 le sql sera  etat=[SE_SOCI_ACTIVE] 
        //  si [SE_SOCI_ACTIVE]=0 le sql sera 0=0, on ignore alors la clause sur les �tats
      	$obj_select = $obj_multiselect->addSelect("SE_SOCI",$stri_sql,$obj_font_company->htmlValue());
        $obj_select->setClass("pn-hotline-select");
        
       
        //** site
        $stri_sql="select distinct site 
                    from societe 
                    where     groupe='[SE_SOCI]'
                          and decode('[SE_SOCI_ACTIVE]','0','0',etat)='[SE_SOCI_ACTIVE]' 
                    order by site";                          
        $obj_select = $obj_multiselect->addSelect("SE_SITE",$stri_sql,$obj_font_site->htmlValue());  
        $obj_select->setClass("pn-hotline-select");
        
         
        //** application
        $stri_sql="select distinct application 
                   from societe 
                   where   groupe='[SE_SOCI]' 
                      and site='[SE_SITE]' 
                      and decode('[SE_SOCI_ACTIVE]','0','0',etat)='[SE_SOCI_ACTIVE]'  
                  order by site";     
        $obj_select = $obj_multiselect->addSelect("SE_SYSTEM",$stri_sql,$obj_font_system->htmlValue());
        $obj_select->setClass("pn-hotline-select");
        
        
        //** contact        
        $stri_sql="select c.num_contact,c.nom||' '||c.prenom 
                   from contact c, contact_societe cs, societe s
                   where    s.groupe='[SE_SOCI]'
                        and s.site='[SE_SITE]'
                        and s.application='[SE_SYSTEM]'
                        and c.num_contact=cs.num_contact
                        and cs.id_societe=s.id_societe 
                    order by nom, prenom";
        $obj_select=$obj_multiselect->addSelect("SE_CONTACT",$stri_sql,$obj_font_contact->htmlValue());
        $obj_select->setClass("pn-hotline-select");      


     if(!isset($_SESSION["EGSAC_change"])){
       //pr�selection de 'Tous' pour l'�tat des soci�t�s (12/09/2011)
       $obj_multiselect->selectOptionForSelect("SE_SOCI_ACTIVE",0);

     }

     $this->obj_ajax_multiselect=$obj_multiselect;                                              
  }
 
 
 
 /*************************************************************
 * Permet de savoir si le multi_select � besoin ou non d'une mise
 * � jour 
 * parametres :   aucun
 * retour : bool : true  : il faut actualiser le multi_select
 *                 false : le multiselect est d�j� � jour 
 *                        
 **************************************************************/
 private function needUpdate()
 {
  global $pnconfig;
   //tableau des variables � surveiller
   $arra_param_surveillance=array("PNSVlang"=>'return $_SESSION["PNSVlang"];',"dbuname"=>'return $pnconfig["dbuname"];'); //un changement de valeur indique qu'il faut mettre � jour
  
                                      
   foreach($arra_param_surveillance as $stri_key=>$stri_param)
   {
    $bool_defini=isset($_SESSION['egsac_surveillance'][$stri_key]);//si le param�tre surveill� est d�fini
    $bool_different=($_SESSION['egsac_surveillance'][$stri_key]!=eval($stri_param)); //s'il y a un changement de valeur
          
         
    if($bool_defini&&$bool_different)
    {
     $_SESSION['egsac_surveillance'][$stri_key]=eval($stri_param);//sauvegarde des valeurs des param�tres surveill�s
     return true;
    }
  
    $_SESSION['egsac_surveillance'][$stri_key]=eval($stri_param);//sauvegarde des valeurs des param�tres surveill�s
   }
   $arra_egsac_surveillance=$_SESSION['egsac_surveillance'];
   
  
  return false;
 }
/*************************************************************
 * Permet d'obtenir le html repr�sentant l'objet
 * parametres :   aucun
 * retour : aucun
 *                        
 **************************************************************/ 
 public function htmlValue()
 { 
  $bool_update=$this->needUpdate();

  if($bool_update)//si le multiselect � besoin d'�tre mis � jour
  {
     ajaxMultiSelect::purgeTemp("egsac");//suppression du fichier temporaire
  }

  return $this->obj_ajax_multiselect->htmlValue(1);
 }
}




?>
