<?php
/*******************************************************************************
Create Date : 13/02/2013
 ----------------------------------------------------------------------
 File name : inputHtml
 File type : interface : définition d'une interface 
 Version : 1.0
 Author : Rémy Soleillant
 Description : Représente tout les types d'input (input, select, image ...)
********************************************************************************/
interface inputHtml
{
    public function getValue();             //pour récupérer la valeur
    public function setValue($stri_value);  //pour modifier la valeur
    public function getName();              //pour récupérer le nom
    public function setName($stri_value);   //pour définir le nom
}

?>