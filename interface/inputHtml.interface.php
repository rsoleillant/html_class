<?php
/*******************************************************************************
Create Date : 13/02/2013
 ----------------------------------------------------------------------
 File name : inputHtml
 File type : interface : d�finition d'une interface 
 Version : 1.0
 Author : R�my Soleillant
 Description : Repr�sente tout les types d'input (input, select, image ...)
********************************************************************************/
interface inputHtml
{
    public function getValue();             //pour r�cup�rer la valeur
    public function setValue($stri_value);  //pour modifier la valeur
    public function getName();              //pour r�cup�rer le nom
    public function setName($stri_value);   //pour d�finir le nom
}

?>