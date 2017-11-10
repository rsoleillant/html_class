<?php
/*******************************************************************************
Create Date  : 2016-10-04
 -------------------------------------------------------------------------------
 Class name  : mvc_menu_imbrication_manager
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : Manager d'un mod�le mvc_menu_imbrication de type imbrication
 
********************************************************************************/
class mvc_menu_imbrication_manager extends mvc_std_manager {
   
//**** Attributs ****************************************************************
	
	//*** 01 Attributs sp�cifique manager *****************************************
	protected $obj_mvc_menu_imbrication ;  //Le mod�le � manager
	 
  //*** 01 Constructor **********************************************************
	
	/*******************************************************************************
	* Constructeur principal
	* 
	* Parametres : $obj_grt_projet_imbrication :  
	* Retour : Aucun                         
	*******************************************************************************/
	public  function __construct(mvc_menu_imbrication $obj_mvc_menu_imbrication) 
	{ 
		parent::__construct();
    
    //- Affectation du mod�le
		$this->obj_mvc_menu_imbrication=$obj_mvc_menu_imbrication;	
	}
//**** Methodes *****************************************************************

//**** Setter ****************************************************************
	
  
//**** Getter ****************************************************************   
	public  function getModel()                {	return $this->obj_mvc_menu_imbrication;}
  public  function getMvcMenuImbrication() {	return $this->obj_mvc_menu_imbrication;}
  
  //surcharge de la m�thode de base pour remonter l'ensemble des messages
  public function getMessage()
  {
    //- initialisation des messages
    $arra_message=$this->arra_message;
    $obj_model=$this->obj_mvc_menu_imbrication;
    
    //- r�cup�ration des messages de chaque sous-mvc
    if(is_object($obj_model->getMvcMenu()))
    {
      $arra_message_01=$obj_model->getMvcMenu()->getManager()->getMessage();
      $arra_message=array_merge($arra_message,$arra_message_01);
    }
   
      
    if(is_object($obj_model->getMvcMenuDeplacementLoader()))
    {
      $arra_message_01=$obj_model->getMvcMenuDeplacementLoader()->getManager()->getMessage();
      $arra_message=array_merge($arra_message,$arra_message_01);
    }
   
       
      
    return $arra_message;
  }
//**** traitement **************************************************************
	
 /*****************************************************************************
	* M�thode g�n�rique de lancement des traitement
	* 
	* Parametres : aucun 
	* Retour : bool : r�sultat du traitement                            
	*******************************************************************************/
	public  function manage() 
	{ 
		//- Initialisation du r�sultat du traitement
		$this->bool_manage_result=true;
    
    //- d�placement syst�matique
    $this->actionMove();
    
    //- propagation de l'ordre de manage
    $obj_model=$this->obj_mvc_menu_imbrication;
    
    if(is_object($obj_model->getMvcMenu()))
    {
        $obj_model->getMvcMenu()->getManager()->manage();
    }
    
    
    if(is_object($obj_model->getMvcMenuDeplacementLoader()))
    {
        $obj_model->getMvcMenuDeplacementLoader()->getManager()->manage();
    }
    
       
    
	}
 
 /*******************************************************************************
	* Permet de g�rer les d�placement dans le mvc
	* 
	* Parametres : aucun 
	* Retour : Aucun                         
	*******************************************************************************/
	public  function actionMove() 
	{ 
    //- d�claration de structure des mvc du mod�le 
    $arra_data=array();   
    $arra_data[]=array('nom_attribut'=>'obj_mvc_menu','setter'=>'setMvcMenu');    
    $arra_data[]=array('nom_attribut'=>'obj_mvc_menu_deplacement_loader','setter'=>'setMvcMenuDeplacementLoader');     
      
    //- r�cup�ration du mod�le porteur des mvc imbriqu�s
    $obj_model=$this->getModel();
   
    //- pour chaque attribut pouvant conteni un mvc
    foreach($arra_data as $arra_info_attribut)
    {
     $stri_base_id='mvc_menu_imbrication__'.$arra_info_attribut['nom_attribut'];

     if($_POST[$stri_base_id.'__mvc_model']!='')//si on a un mod�le � charger dans cet attribut
     {    
            
         //- r�cup�ration des donn�es post
         $stri_dest_mvc=$_POST[$stri_base_id.'__mvc_model'];
         $mixed_dest_mvc_id=$_POST[$stri_base_id.'__mvc_id'];                  
         $stri_dest_viewer=$_POST[$stri_base_id.'__mvc_viewer'];
         $stri_dest_viewer_method=$_POST[$stri_base_id.'__mvc_viewer_methode'];
        
         //- gestion de transmission d'ordre
         $bool_manage=false;
          
         //- instanciation du mvc
         $obj_mvc=new $stri_dest_mvc($mixed_dest_mvc_id,$bool_manage);
         $obj_mvc->changeViewer($stri_dest_viewer,$stri_dest_viewer_method);  
         
         //- transmission du mvc
         $stri_setter=$arra_info_attribut['setter'];
         $obj_model->$stri_setter($obj_mvc);  
         
         //- marquage d'existance d'historique
         $obj_model->setHasHisto(true); 
         
         //- transmission d'identifiant de mvc 
         $obj_model->setIdMvc($obj_mvc->getIdMvc());          
     }     
    }
  }
}

?>
