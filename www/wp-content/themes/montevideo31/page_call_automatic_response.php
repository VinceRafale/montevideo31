<?php
/*
Template Name: Atos Automatic Call Page
*/

/* 	NOTE IMPORTANTE : 
	
	La page wordpress utilisant ce template doit absolument avoir un slug �gal � "bp_page_automatic_response"

*/

?>

<?php

	$message="message=$HTTP_POST_VARS[DATA]";

	$pathfile="pathfile=/homez.395/synagogud/atos/pathfile";
	$path_bin = "/homez.395/synagogud/atos/response";
	$result=exec("$path_bin $pathfile $message");

	//	Sortie de la fonction : !code!error!v1!v2!v3!...!v29
	//		- code=0	: la fonction retourne les donn�es de la transaction dans les variables v1, v2, ...
	//				: Ces variables sont d�crites dans le GUIDE DU PROGRAMMEUR
	//		- code=-1 	: La fonction retourne un message d'erreur dans la variable error


	//	on separe les differents champs et on les met dans une variable tableau

	$tableau = explode ("!", $result);
