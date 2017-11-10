<?php

/*******************************************************************************
Create Date : 12/04/2012
 ----------------------------------------------------------------------
 Class name : connect_ssh
 Version : 1.0
 Author : Xavier SATTLER
 Description : protocole de connection SSH
********************************************************************************/

class connect_ssh {

  private $ssh_host;
  private $ssh_user;
  private $ssh_port;
  private $ssh_passwd;
  private $con = null;
  private $shell_type = 'xterm';
  private $shell = null;
  private $log = '';
  private $tab_array_ssh=array();

    //constructeur ********************************* >>
    function __construct($ssh_host, $ssh_port)
    {
       $this->ssh_host  = $ssh_host;
       $this->ssh_port  = $ssh_port;
  
      $this->con  = ssh2_connect($this->ssh_host, $this->ssh_port);
      
       if( !$this->con ) {
         $this->log .= "Echec Connexion Serveur !";
         echo $this->log;
       }
       //echo "Connexion OK<br>";
    }

    //identification
    function authPassword( $ssh_user = '', $ssh_passwd = '' )
    {
        if( $ssh_user!='' )
        {
          $this->ssh_user  = $ssh_user;
        }
        if( $ssh_passwd!='' )
        {
          $this->ssh_passwd  = $ssh_passwd;
        }
  
        if( !ssh2_auth_password( $this->con, $this->ssh_user, $this->ssh_passwd ) ) {
        $this->log .= "Echec de connexion SSH";
       }
       //echo "SSH OK";
    }

    function openShell( $shell_type = '' )
    {
        if ( $shell_type != '' ) $this->shell_type = $shell_type;
        $this->shell = ssh2_shell( $this->con,  $this->shell_type );
        if( !$this->shell ) $this->log .="Echec connexion Shell !";
    }

    function writeShell( $command = '' )
    {
        fwrite($this->shell, $command."\n");
    }
    //executer une commande SSH sur le serveur
    function cmdExec($cmd, $tab_ssh_mem)
    {
        $argc = func_num_args();
        $argv = func_get_args();
        $this->tab_array_ssh = $tab_ssh_mem;
  
        $cmd = '';
        for( $i=0; $i<$argc ; $i++) {
            if( $i != ($argc-1) ) {
                $cmd .= $argv[$i]." && ";
            }else{
                $cmd .= $argv[$i];
            }
        }
        $stream = ssh2_exec( $this->con, $cmd, $tab_ssh_mem);

        stream_set_blocking( $stream, true );
        
        return fread( $stream, 4096 );
        echo fread( $stream, 4096 );
    }
  
    function getLog()
    {
        return $this->log; 
    }
  }

?>