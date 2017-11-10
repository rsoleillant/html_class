<?php
/*******************************************************************************
Create Date  : 11/12/2012
 ----------------------------------------------------------------------
 Class name  : ecran_contenu
 Version     : 1.0
 Author      : Romain ROBERT
 Description : Permet l'affichage du bloc contenu
                Elle se diférencie de la classe ecran_contenu_no_block car elle définie une en-tete css titre4 et non titre1
 *              et se compose de plusieur objet de type BLOCK (gauche et droit)
 
********************************************************************************/
class ecran_contenu {
   
//**** Attributs ****************************************************************
	protected $stri_titre_entete;
  protected $stri_src_img;
  protected $arra_ico;
  protected $arra_obj_contenu_gauche;
  protected $arra_obj_contenu_droit;
  protected $obj_message;
  protected $obj_javascripter;
  protected $bool_no_details;
  
  protected $bool_table_ico;  //Booléen pour table icone inséré
  protected $bool_exportPDF;  //Booléen pour affichage icone export PDF
  protected $bool_print;      //Booléen pour affichage icone print
  
  protected $stri_action;
  protected $stri_name_form;
  protected $arra_object=null; //Array of object's form, use for javacriptVerification
  protected $stri_pdf_src="";    //Lien pour l'export PDF
 
//**** Methodes *****************************************************************  

//*** constructor *************************************************************
	
	/*******************************************************************************
	* Constructeur du block contenu
	* 
	* Parametres : String : title
  * String : source img entete contenu
	* Table : pour les icônes d'action
	* String : message d'aide qui safficher si pas de table data
	* Table : Pour les données dans l'écran détails       
	* Retour :                          
	*******************************************************************************/
	function __construct($stri_src_img, $stri_title, $arra_ico,$arra_obj_contenu_gauche, $arra_obj_contenu_droit, $obj_message, $bool_no_details=false) 
	{ 
    $this->stri_src_img=$stri_src_img;
		$this->stri_titre_entete=$stri_title;
    $this->arra_ico=$arra_ico;
    $this->arra_obj_contenu_droit=$arra_obj_contenu_droit;
    $this->arra_obj_contenu_gauche=$arra_obj_contenu_gauche;
    $this->obj_message=$obj_message;
    $this->bool_no_details=$bool_no_details;
    $this->obj_javascripter=new javascripter();
    
    $this->stri_action="";
    $this->stri_name_form="form_contenu";
    $this->arra_object=NULL;
    
    $this->bool_table_ico=true; 
    $this->bool_exportPDF=false;
    $this->bool_print=false; 
	}
	

//*** setter ******************************************************************
  public  function setSrcImg($mixed_value) {	$this->stri_src_img = $mixed_value;}
	public  function setTitle($mixed_value) {	$this->stri_titre_entete = $mixed_value;}
	public  function setTableIco($mixed_value) {	$this->arra_ico = $mixed_value;}
  public  function setMessage($mixed_value) { $this->obj_message = $mixed_value;}
  public  function setJavascripter($mixed_value) {$this->obj_javascripter=$mixed_value;}
  public  function setAction($mixed_value) { $this->stri_action = $mixed_value;}
  public  function setNameForm($mixed_value) {$this->stri_name_form=$mixed_value;}
  public  function setArraObject($mixed_value)  {$this->arra_object=$mixed_value;}  
  public function setPdfSrc($mixed_value) {$this->stri_pdf_src = $mixed_value;}
   
//*** getter ******************************************************************
  public  function getSrcImg() {	return $this->stri_src_img;}
	public  function getTitle() {	return $this->stri_titre_entete;}
	public  function getTableIco() {	return $this->arra_ico;}
  public  function getJavascripter() {	return $this->obj_javascripter;}
  public  function getMessage() { return $this->obj_message;}
  public  function getAction() { return $this->stri_action;}
  public  function getNameForm() {return $this->stri_name_form;}
  public  function getArraObject() {return $this->arra_object;}
  public function getPdfSrc() {return $this->stri_pdf_src;}
  
//******Enable**************************************************************
  public  function setEnableTableIco($mixed_value) {	$this->bool_table_ico = $mixed_value;} 
  public  function setEnableExportPDF($mixed_value) {	$this->bool_exportPDF = $mixed_value;}   
  public  function setEnablePrint($mixed_value) { $this->bool_print = $mixed_value;}
  public  function setEnableNoDetails($mixed_value) { $this->bool_no_details = $mixed_value;}
  
//*****Javascript function************************************************
  public function addJsFile($stri_src)
  {
      $this->obj_javascripter->addFile($stri_src);
  }
    //*****Javascript function************************************************
  public function addJsFunc($stri_func)
  {
      $this->obj_javascripter->addFunction($stri_func);
  }
      
        
//*** Méthodes d'affichage ****************************************************
	
	/*******************************************************************************
	* Pour construire l'interface html de recherche
	* 
	* Parametres : aucun 
	* Retour : table : le code html                         
	*******************************************************************************/
	public  function htmlValue($bool_form=true) 
	{         
    //*****Gestion icone print et Export********
    if ($this->bool_print===true)
    {
    $obj_img_print=new img("images/MaJ_graphique/printer20x20.png");
        $obj_img_print->setStyle("cursor:pointer;");      
        $obj_img_print->setBorder("0");
        $obj_img_print->setWidth("20px");
        $obj_img_print->setHeight("20px");
        $obj_img_print->setStyle("margin:1px 2px 1px 2px;");
   
   $currentURL=$_SERVER['REQUEST_URI'];
   //Impression display block =>impression liste plus joli
   if(isset($_GET['block']))
    {
      $currentURL=str_replace("&block","","$currentURL");
    }
    $obj_print=new a("". $currentURL."&print",$obj_img_print->htmlValue(),true); 
    $obj_print->setId("print");
    $obj_print->setTitle(_PRINT);
    $obj_print->setTarget("_blank");
    }
    else
    {$obj_print="";}  
    
    if ($this->bool_exportPDF===true)
    {
    $obj_img_pdf=new img("images/MaJ_graphique/PDF20x20.png");
        $obj_img_pdf->setStyle("cursor:pointer;");      
        $obj_img_pdf->setBorder("0");
        $obj_img_pdf->setStyle("margin:1px 2px 1px 2px;");
    $obj_pdf=new a($this->stri_pdf_src,$obj_img_pdf->htmlValue(),true); 
    $obj_pdf->setId("pdf");
    $obj_pdf->setTarget("_blank");
    $obj_pdf->setTitle("PDF");
    }
    else
    {$obj_pdf="";}
    
    
    //Table Icone
    if ($this->bool_table_ico===true)
    {$obj_display=$this->arra_ico;}
    else
    {$obj_display="";}
   
    //Objet display icone si tableau d'icône
    if(sizeof($obj_display)>1)
    {
      $obj_display_ico=array($obj_pdf,"&nbsp;",$obj_print);
      foreach($obj_display as $key=>$display) //Permet l'affichage de l'array icone
      //passé en paramètre
      {
       $obj_display_ico=array_pad($obj_display_ico,sizeof($obj_display_ico)+1,"&nbsp;");
       $obj_display_ico=array_pad($obj_display_ico,sizeof($obj_display_ico)+1,$display);
      }
    }
    else
    {$obj_display_ico=array($obj_pdf,"&nbsp;",$obj_print,"&nbsp;",$obj_display);}
    
    //Titre de l'entete contenu
    $obj_font_titre=new font($this->stri_titre_entete,true);
        $obj_font_titre->setStyle('font-weight: bold;');
        $obj_font_titre->setSize(5);
                  
    //Image de l'entete contenu
    $obj_img_entete=new img($this->stri_src_img);
        $obj_img_entete->setWidth("45px");
                            
    //Table d'entete contenu Avec : Image - Titre - Tableau ico actions
    $obj_table_entete=new table();
    $obj_table_entete->setWidth("100%");

    if ($this->bool_no_details===true)
        { 
            $obj_table_entete->setClass("titre1 entete"); //Grosse en-tete
            //Image de l'entete contenu
            $obj_img_entete=new img($this->stri_src_img);
                $obj_img_entete->setWidth("60px");
        }
    else 
        { 
            $obj_table_entete->setClass("titre4 entete");  //Défini l'en-tete Titre 4 (plus petite que titre1)
            //Image de l'entete contenu
            $obj_img_entete=new img($this->stri_src_img);
                $obj_img_entete->setWidth("45px");
            $obj_bandeau=new ecran_bandeau();  //Instancie un bandeau composé de : "Bienvenue user     Date" de 
        }
           
            $obj_tr=$obj_table_entete->addTr();
                $obj_td=$obj_tr->addTd($obj_img_entete);
                $obj_td->setStyle('width:90px;');
                $obj_td=$obj_tr->addTd($obj_font_titre);
                $obj_td=$obj_tr->addTd($obj_display_ico);//array($obj_pdf,"&nbsp;",$obj_print,"&nbsp;",$obj_display2));
                $obj_td->setAlign("right");
                
    
    //Contenu gauche
    $obj_table_block_gauche=new table();
        $obj_table_block_gauche->setWidth("100%");
        //parcours des objets block gauche
        foreach ($this->arra_obj_contenu_gauche as $obj_block_gauche)
        {
            //Vérifie que l'objet est de classe ecran_block
            if (is_a($obj_block_gauche, "ecran_block")||is_a($obj_block_gauche, "ecran_zone_v3"))
            {
                //Ajoute une ligne dans le contenu gauche afin de placé le second en dessous
                $obj_tr=$obj_table_block_gauche->addTr();
                    //Ajoute le contenu de l'objet block dans le contenu principale
                    $obj_td=$obj_tr->addTd($obj_block_gauche->htmlValue());
            }
        }
        
        
    //Contenu droit
    $obj_table_block_droit=new table();
        $obj_table_block_droit->setWidth("100%");
        //parcours des objets block droit
        foreach ($this->arra_obj_contenu_droit as $obj_block_droit)
        {
          //Vérifie que l'objet est de classe ecran_block
          if (is_a($obj_block_droit, "ecran_block")||is_a($obj_block_droit, "ecran_zone_v3"))
            {
                //Ajoute une ligne dans le contenu gauche afin de placé le second en dessous
                $obj_tr=$obj_table_block_droit->addTr();
                    //Ajoute le contenu de l'objet block dans le contenu principale
                    $obj_td=$obj_tr->addTd($obj_block_droit->htmlValue());
            }
        }
         
    //Tableau retourner
    $obj_table_main=new table();
    $obj_table_main->setWidth("100%");
        $obj_tr=$obj_table_main->addTr();
            $obj_td=$obj_tr->addTd($obj_bandeau);
                $obj_td->setColspan(2);
         $obj_tr=$obj_table_main->addTr();
            $obj_td=$obj_tr->addTd($obj_table_entete);
                $obj_td->setColspan(2);
        $obj_tr=$obj_table_main->addTr();     
            $obj_td=$obj_tr->addTd($this->obj_message);
                $obj_td->setColspan(2);
                $obj_td->setAlign("center");
                $obj_td->setWidth("100%");
        $obj_tr=$obj_table_main->addTr(); 
        
        //Condition pour affichage menu sans bandeau gauche détails
        if ($this->bool_no_details===false)
            {
                $obj_td=$obj_tr->addTd($obj_table_block_gauche);
                    $obj_td->setWidth('35%');
                    $obj_td->setValign("top");
                $obj_td=$obj_tr->addTd($obj_table_block_droit);
                    $obj_td->setWidth('65%');
                    $obj_td->setValign("top");
            }
        else
            {
                //Dans le cas d'un affichage sans le bandeau détails
                 $obj_td=$obj_tr->addTd($obj_table_block_gauche);
                    $obj_td->setWidth('50%');
                    $obj_td->setValign("top");
                $obj_td=$obj_tr->addTd($obj_table_block_droit);
                    $obj_td->setWidth('50%');
                    $obj_td->setValign("top");
            }
      
            
            if ($bool_form == true)
            {
              //Encapsule le tableau dans un formulaire
                $form=new form($this->stri_action, "post", $obj_table_main->htmlValue() , $this->stri_name_form);

                    $form->setEnctype("multipart/form-data");

                  //08/01/2013 : Gestion champ vide ou mal rempli des objets du formulaire 
                  if (sizeof($this->arra_object) != 0) {
                      foreach ($this->arra_object as $key => $arra_value) {
                          $form->addObject($arra_value);
                      }
                  }

                  return $this->obj_javascripter->javascriptValue() . $form->htmlValue() . $form->javascriptVerification();  
            }
            else
            {
                return  $this->obj_javascripter->javascriptValue() . $obj_table_main->htmlValue();
            }
    
    }
 

 
}

?>
