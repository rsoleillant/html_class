<?php
/*******************************************************************************
Create Date : 20/11/2007
 ----------------------------------------------------------------------
 Class name : multipage
 Version : 1.1
 Author : Emilie Merlat
 Description : permet de gérer les affichages multipage
********************************************************************************/
include_once("modules/Modif/pnlang/".pnUserGetLang()."/user.php");

class MultiPage 
{
	protected $stri_url;               // url de la page (url of current page)
  protected $stri_sql;               // requete sql (sql query)
  protected $arra_array;             // tableau de resultat d'une requete avec index commençant à 0 (array of data puts in multipage)
  protected $int_limit_row;          // nombre max de ligne par page (max results per page)
	protected $int_limit_page = 20;    // nombre max de pages à afficher dans la navigation (max number of page links)
  protected $int_rows;               // nombre de lignes (number of rows)
	protected $int_pages;              // nombre de pages (number of pages)
	protected $bool_type_sql;          // permet de connaitre si le multipage est initié par du sql ou par le tableau (true => sql ; false => tableau php) 
  protected $int_row_from;           // ligne de début pour la requête (start row for query)
  protected $int_row_to;             // ligne de fin pour la requête (end row for query)
  protected $int_page;               // numéro de la page courante
  protected $int_page_prev;          // numéro de la page précédente (previous page)
	protected $int_page_next;          // numéro de la page suivante (next page)
	//protected $int_start;
	
  protected $obj_lb_result;          // le label titre du résultat (label of result)
  protected $obj_lb_of;              // le label titre pour le résultat header (label for the result)   
  protected $obj_lb_prev;            // la page précédente non disponible (previous page disabled) 
  protected $obj_lb_courant_page;    // la page courante (courant page)
  protected $obj_lb_next;            // la page suivante non disponible (next page disabled)
  protected $obj_form;               // le formulaire (form)
  protected $obj_lb_page;            // le label titre de la page (page's label)
	protected $obj_tb_page;            // le textbox de la page (page's textbox)
	protected $obj_bt_page;            // le bouton pour valider la page (button ok)
	protected $obj_a_prev;             // le lien précédent (link previous)
	protected $obj_a_next;             // le lien suivant (link next)
  protected $obj_a_page;             // le lien des pages (link page)
  //protected $obj_query;            // requête de sélection (select query)
  
  
  //**** constructor ***********************************************************
	public function __construct($url, $result, $limit=10,$dbconn="NULL") 
  {
    //constructeur de l'objet multipage initié par du sql (multipage's constructor)
    //@param : $url => l'url où l'objet multipage est construit (url where multipage is create)
    //@param : $result => le resultat des données à couper soit une requete sql soit un tableau php (result of data to cut : sql query or php array)
    //@param : $limit => le nombre de résultat à afficher par page (number of result to post per page)
    //@param : $dbconn => chaine de connexion
    //@return : void

    //si c'est un tableau
    if(is_array($result))
    {
      //construit l'objet
      $this->__constructArray($url,$result,$limit);
    }
    //si c'est une chaine
    elseif(is_string($result))
    {
      //construit l'objet
      $this->__constructSql($url,$result,$limit,$dbconn);
    }
    else
    {
      //sinon msg d'erreur
      //echo"<script>alert('Erreur classe multipage : l\'objet n\'a pas été construit !');</script>";
      exit;
    }
    
	}
	
	
	private function __constructSql($url, $sql, $limit=10,$dbconn)
	{
	  //constructeur de l'objet multipage initié par du sql (multipage's constructor)
    //@param : $url => l'url où l'objet multipage est construit (url where multipage is create)
    //@param : $sql => la requete utilisant le multipage (query for multipage)
    //@param : $limit => le nombre de résultat à afficher par page (number of result to post per page)
    //@param : $dbconn => chaine de connexion
    //@return : void
    
    //*************************parametre constant*******************************
    $this->bool_type_sql=true;
    $this->stri_url = $url;
		$this->stri_sql = $sql;
		$this->int_limit_row = $limit;
    
    $obj_query=new querry_select($sql);
    $obj_query->execute("indice",$dbconn);
    $this->int_rows = $obj_query->getNumberResult();  //nombre total de ligne dans le resultat (result's number)
    
    $this->initialisation();	
	}
	
	
	private function __constructArray($url, $arra, $limit=10) 
  {
    //constructeur de l'objet multipage initié par un tableau php (multipage's constructor)
    //@param : $url => l'url où l'objet multipage est construit (url where multipage is create)
    //@param : $arra => le tableau utilisant le multipage (array for multipage)
    //@param : $limit => le nombre de résultat à afficher par page (number of result to post per page)
    //@return : void
    
    //*************************parametre constant*******************************
    $this->bool_type_sql=false;
    $this->stri_url = $url;
		$this->arra_array = $arra;
		$this->int_limit_row = $limit;
        
    //nombre total de ligne du tableau
    $this->int_rows = count($this->arra_array);
    
    $this->initialisation();
	}
	

  //**** getter ****************************************************************
  public function getNbMin()
	{return $this->int_row_from;}
	
	public function getNbMax()
	{return $this->int_row_to;}
	
	public function getNbPage()
	{return $this->int_pages;}

 	public function getNumPage()
	{return $this->int_page;}
  //**** public function *******************************************************
  public function getResult($s_type ='indice') 
  {
    //permet de récupérer le résultat de la requête
    //@return : tableau des résultats
    
    //si le multipage est initié par une requete sql (if multipage is initied by sql query)
    if($this->bool_type_sql)
    {
      $obj_query=new querry_select($this->stri_sql);
      $arra_result=$obj_query->limitQuery($this->int_row_from, $this->int_limit_row, $s_type);
    }
    else
    {
      //le multipage est init par un tableau php (multipage is initied by an array)
      //coupe le tableau de telle ligne à telle ligne (cut array from row to row)
      $arra_result=array_slice($this->arra_array,$this->int_row_from,$this->int_limit_row);
    }
    return $arra_result;
	}

	public function getNav() 
  {
    //permet d'afficher la navigation
    //@return : $stri_html : affichage du html de la navigation
    
		$stri_html ="";
    
    //**** START - navigation previous
    //si la page précédente > 1 alors affichage du lien sinon affichage du label (if previous page > 1 then put link else label)
		$stri_html .= ($this->int_page_prev > 0) ? $this->obj_a_prev->htmlValue() : $this->obj_lb_prev->htmlValue();  
		$stri_html .= " | ";
		//**** END - navigation previous
		
		
    //**** DEBUT corps navigator

    // calcule le milieu pour les liens de page dans le but d'avoir la page courante au centre de la navigation (determine the middle of link to post courant page in middle)
		$int_middle = floor($this->int_limit_page / 2);

		// calcule la page minimum à afficher dans les liens (determine the min page)
    $minpage = (($this->int_page - $int_middle)<1) ? 1 : $this->int_page - $int_middle;		

    // calcule la page maximum à afficher dans les liens (determine the max page)
		$maxpage = (($this->int_page + $int_middle)> $this->int_pages)? $this->int_pages : ($this->int_page + $int_middle);

    // pour chaque lien de navigation (post each page)
		foreach (range($minpage, $maxpage) as $i) 
    {
			if ($i == $this->int_page) 
      {     
        // selected page
        $this->obj_lb_courant_page->setValue($i);
				$stri_html .= $this->obj_lb_courant_page->htmlValue(); 
			} 
      else 
      {
        // other page
        $this->obj_a_page->setValue($i);
        $this->obj_a_page->setOnClick("document.form_page.start.value=".$i.";document.form_page.submit();");
    
				$stri_html.=" ".$this->obj_a_page->htmlValue()." ";
			}
		}

    //**** END corps navigation
    
    
    //**** START - navigation next
    //si la page suivante <= nb de pages alors affichage du lien sinon affichage du label (if next page <= number of page then put link else label)
		$stri_html .= " | ";
		$stri_html .= ($this->int_page_next <= $this->int_pages) ? $this->obj_a_next->htmlValue() : $this->obj_lb_next->htmlValue(); 
    //**** END - navigation next

		return $stri_html;
	}


	public function getHeader() 
  {
    //permet d'afficher l'en-tête
    //@return $stri_html : l'affichage html de l'en-tête
    
    $stri_html=$this->obj_lb_result->htmlValue();
    $int_row_from=$this->int_row_from+1;
    $stri_html.=" ".$int_row_from." - ".$this->int_row_to;
    $stri_html.=" ".$this->obj_lb_of->htmlValue();
    $stri_html.=" ".$this->int_rows." ";
		return $stri_html;
	}
	
	public function getPage()
	{
    //permet d'afficher la partie page par textbox
    //@return : $stri_html => l'affichage html du textbox
    	   
    $obj_table=new table();
    $obj_table_tr=new tr();
    $obj_table_tr->addTd($this->obj_form->getStartBalise());
    $obj_table_tr->addTd($this->obj_lb_page->htmlValue());
    $obj_table_tr->addTd($this->obj_tb_page->htmlValue());
    $obj_table_tr->addTd($this->obj_bt_page->htmlValue());
    $obj_table_tr->addTd($this->obj_form->getEndBalise());
    $obj_table->insertTr($obj_table_tr);
    $obj_table->setBorder(0);
    
    
    $stri_html=$this->javascripter();
    $stri_html.=$obj_table->htmlValue();
    return $stri_html;
	}
	
	
	//**** private function ******************************************************
	private function defineData() 
  { 
    //récupère le numéro de la page si existant (get number of page if exists)
	  $this->int_page = isset($_POST['start']) ? (int) $_POST['start'] : null;
    
     
    //si la page de départ n'est pas définie alors initialise à 1 (if page is not defined then init to 1) 
    if ($this->int_page <1){$this->int_page = 1;}
    //si la page de départ est supérieur aux nombres de page du multipage alors initialise à la dernière page du multipage (if page is great then page's number of multipage then init to last page of multipage)
    if ($this->int_page > $this->int_pages){$this->int_page = $this->int_pages;}
    
    //initialise les pages précédentes et suivantes (init next and previous page)
		$this->int_page_prev = $this->int_page -1;      //previous page
		$this->int_page_next = $this->int_page +1;      //next page
		$this->obj_a_prev->setOnClick("document.form_page.start.value=".$this->int_page_prev.";document.form_page.submit();");
    $this->obj_a_next->setOnClick("document.form_page.start.value=".$this->int_page_next.";document.form_page.submit();");
    
		
		//détermine la ligne de début et celle de fin pour la requête (determine intervalle between started rows to ended rows)
    $this->int_row_to = $this->int_page * $this->int_limit_row;                     // ligne de fin (end rows)
		$this->int_row_from = $this->int_row_to - $this->int_limit_row;                 // ligne de début (start rows) 
		
		//si ligne de fin est supérieure au nombre de ligne de la requête alors initialise la ligne de fin à la dernière ligne de la requête (init to last rows of query if end rows > row's number of query)
    if ($this->int_row_to > $this->int_rows){$this->int_row_to = $this->int_rows;}
    //si ligne de début est inférieure à zéro alors initialiser à 1 (init to 1 if start rows <0)
    if ($this->int_row_from < 0){$this->int_row_from = 0;}
    /*
    echo"<br />L'url de la page : stri_url : ";var_dump($this->stri_url);
    echo"<br />La requête sql : stri_sql : ";var_dump($this->stri_sql);
    echo"<br />PARAMETRE CONSTANT <br />Le nombre de lignes à afficher par page : int_limit_row : ";var_dump($this->int_limit_row);
    echo"<br />Le nombre de pages à afficher dans la navigation : int_limit_page : ";var_dump($this->int_limit_page); 
    echo"<br />Le nombre de lignes : int_rows : ";var_dump($this->int_rows);
    echo"<br />Le nombre de pages nécessaire : int_pages : ";var_dump($this->int_pages);   
    echo"<br />PARAMETRE VARIABLE <br />La ligne de début de la requête : int_row_from : ";var_dump($this->int_row_from);
    echo"<br />La ligne de fin de la requête : int_row_to : ";var_dump($this->int_row_to);
    echo"<br />Le numéro de la page courante : int_page : ";var_dump($this->int_page);  
    echo"<br />Le numéro de la page précédente : int_page_prev : ";var_dump($this->int_page_prev);
    echo"<br />Le numéro de la page suivante : int_page_next :";var_dump($this->int_page_next);
    */
  }
  
  private function initialisation()
  {
    //créer l'interface et initialise les données
    //@return : void
    
    // calcule le nombre de pages nécessaire pour le multipage (define the number of page to build multipage)
    $this->int_pages = ceil($this->int_rows / $this->int_limit_row);
    
    //*************************interface****************************************

    //**** label ***************************************************************
    $this->obj_lb_result=new font(_TH_RESULT);
    $this->obj_lb_of=new font(_TH_OF);
    $this->obj_lb_prev=new font(_TH_PREVIOUS);
    $this->obj_lb_next=new font(_TH_NEXT);
    $this->obj_lb_courant_page=new font(1,true);
    //modify attribute
    $this->obj_lb_courant_page->setClass(""); //enleve la class par défault afin de pouvoir mettre en forme le texte
    $this->obj_lb_courant_page->setStyle("font : 11px Lucida Grande, Tahoma, Verdana, sans-serif;");     
    $this->obj_lb_courant_page->setColor("red");
    
    //**** form ****************************************************************
    $this->obj_form=new form($this->stri_url,"POST");
    $this->obj_form->setName("form_page");
    
    //**** textbox *************************************************************
    // label
		$this->obj_lb_page=new font(_TH_PAGE);
		// create textbox
    $this->obj_tb_page=new text("start",$_POST["start"]);
    //modify attribute
    $this->obj_tb_page->setSize(3);
    
    //**** img *****************************************************************
    $this->obj_bt_page=new img("images/module/goto.gif");
    //modify attribute
    $this->obj_bt_page->setOnclick("verif_form_page()");
    $this->obj_bt_page->setStyle("cursor:pointer;");
    $this->obj_bt_page->setTitle(_BUT_GO);
    $this->obj_bt_page->setAlt(_BUT_GO);

    //**** link ****************************************************************    
    $this->obj_a_prev=new a("#",_TH_PREVIOUS);
    $this->obj_a_next=new a("#",_TH_NEXT);
    $this->obj_a_page=new a("#",1);
    // modify attribute
    
    // initialise les données nécessaire au multipage (init data for multipage)
		$this->defineData();    
  }

  private function javascripter()
  {
    //code javascript pour l'interface multipage (javascript for form)
    //@return : $stri_js => retourne le code javascript (return javascript)
    
    $stri_js="<script>
      function verif_form_page()
      {
        //vérification du formulaire (verify form)
        //@return : false => formulaire erroné (error)
        //          void => valide le formulaire (submit form)
        
        // vérifie que le numéro de la page soit bien un chiffre (verify the n° of page is integer)
        if(isNaN(document.form_page.start.value))
        { 
          alert('"._ERROR_FIELD."');                  //envoi du message d'erreur (send error message)
          document.form_page.start.focus();           //met le focus sur le textbox
          return false;
        }
        document.form_page.submit();                  //valide le formulaire (submit form)
      }
      </script>
    ";
    
    return $stri_js;  
  }
  
}
?>

