
/*******************************************************************************
Create Date  : 13/11/2014
 ----------------------------------------------------------------------
 Class name  : bl_select_all
 Version     : 1.0
 Author      : SOLEILLANT Remy
 Description :  Contient les méthodes de sélection ou déselection de l'ensemble des cb
 
********************************************************************************/
  
function bl_select_all(mixed_json)
{

}

//**** Methodes statiques ******************************************************
bl_select_all.selectAll = function(cb_name)
{ 
 $(':checkbox[name="'+cb_name+'"]').attr('checked',true);
}

bl_select_all.unselectAll = function(cb_name)
{
  $(':checkbox[name="'+cb_name+'"]').attr('checked',false);
}

