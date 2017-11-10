<?php
/*******************************************************************************
Create Date : 29/05/2006
 ----------------------------------------------------------------------
 Class name : querry
 Version : 1.0
 Author : Rémy Soleillant
 Description : classe mère pour tout type de requete
 Update : le 20 fev 2008
*******************************************************************************/
abstract class querry extends serialisable
{
  /*attribute******************************************************************/
  protected $stri_sql="";  
  protected $arra_dbconn="";
  protected $bool_transaction=false;
    
  /*constructor****************************************************************/
  
  
  //**** setter ****************************************************************
  public function setSql($value){$this->stri_sql=$value;}
  public function setConnection($dbconn){$this->arra_dbconn=$dbconn;}
  
  
  //**** getter ****************************************************************
  public function getSql(){return $this->stri_sql;}
  public function getConnection(){return $this->arra_dbconn;}
  
  
  //**** public method *********************************************************

 
    /**
     * 
     * Méthode générique d'affichage d'un message d'erreur
     * 
     * @param type $stri_error  => L'erreur SQL
     * @param type $stri_errno  => Son numéro
     * @param type $stri_sql    => La requête SQL
     * @return boolean
     */
    public function triggerError($stri_error, $stri_errno, $stri_sql)
    {
        //- Si un erreur est présente
        if ($stri_error)
        {
            //- Construction du méssage
            $stri_message = "SQL ERROR $stri_errno : $stri_error  " . PHP_EOL . " sql text : $stri_sql" . PHP_EOL;

            //- Si administrateur => Afficahge du message
            //- rrobert | rsoleillant | mtena
            if (in_array(pnusergetvar('uid'), [1687, 1323, 1379]))
            {
                echo "<pre><font size=2 color=red>" . $stri_message . "</font></pre>";
            }
            
            //- Pose de l'erreur dans un log 
            $stri_path_sql_error_log = '/var/log/httpd/asisline-sql_error_log';
            $stri_date               = '[' . date('d/m/Y H:i:s') . '] => ';
            //file_put_contents($stri_path_sql_error_log, file_get_contents($stri_path_sql_error_log) . $stri_date . $stri_message);
            file_put_contents($stri_path_sql_error_log, PHP_EOL . $stri_date . $stri_message, FILE_APPEND);

            

            return false;
        }

        return true;
    }
  
}
?>
