<?php
/*
Template Name: Atos Moyen payement
*/

/* 	NOTE IMPORTANTE : 
	
	La page wordpress utilisant ce template doit absolument avoir un slug égal à "bp_page_moyen_paiement"

*/

?>


<?php
// Get wordpress page id by slug
function get_ID_by_slug($page_slug) {	
    $page = get_page_by_path($page_slug);
    if ($page) {
    	$url = get_permalink( $page->ID );
        return $url;
    } else {
        return null;
    }
}
?>


<?php
	// Check this page is called by the product display page only
	if (empty( $_POST['bp_product_name'] )){
		// Display error message
		echo "Vous n'avez pas le droit d'être sur cette page blabla";
	}else{
		
		// retrieve product Id (to put it in "caddie" field )
		$product_id = $_POST['bp_product_id'];
		
		// if not donation : quantity
		if( $_POST['bp_product_type'] == bp_enum_value_donation  ){
			$quantity = "1";
		}else{
			$quantity = $_POST['bp_product_quantity'];
		}
		// if cerfa 
		if($_POST['bp_product_cerfa'] ){
			$cerfa = "oui";
		}else{
			$cerfa = "non";
		}
		
		// retrieve product name
		$product_name = $_POST['bp_product_name'] ;
		
		// price : *100
		$price = 100*$quantity*$_POST['bp_product_price'];
		
		// fill caddie value
		$caddie = base64_encode( $product_id."_".$product_name."_".$quantity."_".$cerfa );
		
		// get templates pages URLs
		$cancel_return_url = get_ID_by_slug('bp_page_produits');
		$normal_return_url = get_ID_by_slug('bp_page_retour_paiement');
		$automatic_url = get_ID_by_slug('bp_page_automatic_response');
		
		// path to exec file
		$path_bin = "/homez.395/synagogud/atos/request";
		
		// parameters
		$parm="merchant_id=078466181100017";
		$parm="$parm merchant_country=fr";
		$parm="$parm amount=".$price;
		$parm="$parm currency_code=978";
		$parm="$parm pathfile=/homez.395/synagogud/atos/pathfile";
		//		Si aucun transaction_id n'est affecté, request en génère
		//		un automatiquement à partir de heure/minutes/secondes
		//		Référez vous au Guide du Programmeur pour
		//		les réserves émises sur cette fonctionnalité
		$parm="$parm normal_return_url=".$normal_return_url;
		$parm="$parm cancel_return_url=".$cancel_return_url;
		$parm="$parm automatic_response_url=".$automatic_url;
		$parm="$parm language=fr";
		$parm="$parm payment_means=CB,2,VISA,2,MASTERCARD,2,AMEX,2,";
		$parm="$parm header_flag=yes";
		$parm="$parm capture_day=";
		$parm="$parm capture_mode=";
		$parm="$parm bgcolor=";
		$parm="$parm block_align=";
		$parm="$parm block_order=";
		$parm="$parm textcolor=";
		$parm="$parm receipt_complement=";
		$parm="$parm caddie=".$caddie;
		$parm="$parm customer_id=";
		$parm="$parm customer_email=";
		$parm="$parm customer_ip_address=";
		$parm="$parm data=";
		$parm="$parm return_context=";
		$parm="$parm target=";
		$parm="$parm order_id=";
		$parm="$parm normal_return_logo=";
 		$parm="$parm cancel_return_logo=";
 		$parm="$parm submit_logo=";
// 		$parm="$parm logo_id=";
// 		$parm="$parm logo_id2=";
// 		$parm="$parm advert=";
// 		$parm="$parm background_id=";
// 		$parm="$parm templatefile=";


		//	Appel du binaire request
		$result=exec("$path_bin $parm");
	
		//	sortie de la fonction : $result=!code!error!buffer!
		//	    - code=0	: la fonction génère une page html contenue dans la variable buffer
		//	    - code=-1 	: La fonction retourne un message d'erreur dans la variable error
		//	On separe les differents champs et on les met dans une variable tableau
		$tableau = explode ("!", "$result");
	
		//	récupération des paramètres
		$code = $tableau[1];
		$error = $tableau[2];
		$message = $tableau[3];
	
		//  analyse du code retour
	  	if (( $code == "" ) && ( $error == "" ) )
	 	{
	  		print ("<BR><CENTER>erreur appel request</CENTER><BR>");
	  		print ("executable request non trouve $path_bin");
	 	}
		//	erreur, affiche le message d'erreur
		else if ($code != 0){
			print ("<center><b><h2>Erreur appel API de paiement.</h2></center></b>");
			print ("<br><br><br>");
			print (" message erreur : $error <br>");
		}
		//	OK, affiche le formulaire HTML
		else {
			# OK, affichage du mode DEBUG si activé
			//print (" $error <br>");
			//echo "<p> Choisissez votre mode de réglement : </p>";
			print ("  $message <br>");
			$previousPage = get_ID_by_slug('bp_page_produits');
			print ("<a href=".$previousPage.">Revenir &#224; la page pr&#233;c&#233;dente>></a>");
		}
		
	
	
	}// fin du if test sur isset formulaire page précédente


?>
