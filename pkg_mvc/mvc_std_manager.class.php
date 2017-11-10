<?php
/*******************************************************************************
Create Date  : %Date%
 ----------------------------------------------------------------------
 Class name  : ma_classe
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description : 
 
********************************************************************************/
abstract class mvc_std_manager {
   
//**** Attributs ****************************************************************
	 protected $arra_message;      //Liste des messages d'erreur
	 protected $bool_manage_result;//Le r�sultat du management

	//*** 01 Attributs  ***********************************************************
	 

//**** Methodes *****************************************************************

//**** Setter ****************************************************************
	
  
//**** Getter ****************************************************************   
  abstract public  function getModel();
  public  function getMessage() {	return $this->arra_message;}
	public  function getManageResult() {	return $this->bool_manage_result;}

 
//*** 01 Constructor **********************************************************
	/*******************************************************************************
	* 
	* 
	* Parametres :  
	* Retour : objet de la classe  grt_projet_manager                         
	*******************************************************************************/
	public  function __construct() 
	{       
		$this->arra_message=array();//liste de message vide par d�faut  
	}

        
//*** gestion des messages ****************************************************
	
	/*******************************************************************************
	* Permet d'ajouter un message de r�sultat de traitement
	*   Parametres: string : le message � ajouter 
	*               string : la couleur du message
	* 
	* Parametres :  
	* Retour : obj font : le message ajout�                         
	*******************************************************************************/
	public  function addMessage($stri_message,$stri_color="red") 
	{ 
		$obj_font=new font($stri_message);
		        $obj_font->setColor($stri_color);
		    
		$this->arra_message[$stri_message]=$obj_font;
  
		return $obj_font;  
	}
	
	
	/*******************************************************************************
	* Permet de construire le html de l'ensemble des messages r�sultant
	*  du traitement
	* 
	* Parametres : aucun 
	* Retour : string : le code html des message                         
	*******************************************************************************/
	public  function getHtmlMessage() 
	{ 
		$stri_res='<p class="message message_manager" >';
		    foreach($this->getMessage() as $obj_font_message)
		    {
		      $stri_res.=$obj_font_message->htmlValue()."<br/>";
		    }
		    $stri_res.='</p>';
		    
		    return $stri_res;  
	}
/*** traitement **************************************************************
	
	/*******************************************************************************
	* M�thode g�n�rique de lancement des traitement
	*   
	* Parametres: aucun
	*                   
	* Retour : bool : r�sultat du traitement
	*                 true en cas de succ�s, 
	*                 false en cas d'erreur                            
	*******************************************************************************/
	abstract public  function manage(); 
	 	 
	
}

?>
