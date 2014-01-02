<?php
/*
Template Name: Atos Moyen payement
*/

/* 	NOTE IMPORTANTE : 
	
	La page wordpress utilisant ce template doit absolument avoir un slug égal à "bp_page_moyen_paiement"
	
	La page wordpress d'inscription doit absolument avoir un slug égal à "inscription" et celle de connexion "connexion"

*/

?>
<?php
get_header(); 
?>
<div id="container">
	<div id="content" role="main">
		<?php
		
		// Check this page is called by the product display page only or from login / register
		
		
		if ( empty( $_POST['bp_product_name'])&&( !isset($_SESSION['product_name']) ) ){
			// Display error message
			echo "Vous n'avez pas le droit d'acc&#233;der directement &#224; cette page sans passer par la page qui liste les dons et souscriptions disponibles.";
			
						
		}else{
			
				// Check if the user is logged or not
				if( !is_user_logged_in() ) {
 					//user not logged
					// save current caddie in session
					if( session_id() == "" ){
						session_start();
					}
					
					$_SESSION['product_name'] = $_POST['bp_product_name'];
					$_SESSION['product_id'] = $_POST['bp_product_id'] ;
					// quantity (if donation = 1 )
					$_SESSION['quantity'] = $_POST['bp_product_quantity'];
					// price : *100
					$_SESSION['price']  = 100*$_SESSION['quantity'] *$_POST['bp_product_price'];
					// group id
					$_SESSION['group_id']  = $_POST['bp_product_group_id'];
					// cerfa 
					if($_POST['bp_product_cerfa'] ){
						$_SESSION['cerfa']  = "oui";
					}else{
						$_SESSION['cerfa']  = "non";
					}
					// bookable ?
					$_SESSION['prod_bookable'] = $_POST['bp_product_bookable'];
					
					// redirect to login
					$loginUrl = get_ID_by_slug('connexion');
					header('Location: '.$loginUrl);
				}else{
					
					// user logged
					
					/*
					 *	Retrieve current caddie values from $_POST or $_SESSION
					 */
					if( isset($_SESSION['product_name'] )){
						// retrieve from session
						$product_id = $_SESSION['product_id'];
						$product_name = $_SESSION['product_name'];
						$quantity = $_SESSION['quantity'];
						$price = $_SESSION['price'];
						$cerfa = $_SESSION['cerfa'];
						$group_id = $_SESSION['group_id']; 
						$bookable = $_SESSION['prod_bookable'];
						// delete session vars
						unset($_SESSION['product_id']);
						unset($_SESSION['product_name']);
						unset($_SESSION['quantity']);
						unset($_SESSION['price']);
						unset($_SESSION['cerfa']);
						unset($_SESSION['group_id']);
						unset($_SESSION['prod_bookable']);
						
					}else{
						// retrieve from $_POST
						$product_id = $_POST['bp_product_id'];
						$product_name = $_POST['bp_product_name'] ;
						// quantity : if donation = 1
						$quantity = $_POST['bp_product_quantity'];
						// price : *100
						$price = 100*$quantity*$_POST['bp_product_price'];
						// group id 
						$group_id = $_POST['bp_product_group_id'];
						// cerfa 
						if($_POST['bp_product_cerfa'] ){
							$cerfa = "oui";
						}else{
							$cerfa = "non";
						}
						// product bookable ?
						$bookable = $_POST['bp_product_bookable'];
						
					}
					
					
					/*
					 *	DOUBLE-CHECK THE PRODUCT AVAILABILITY
					 */ 
					
					
					// if product type !donation
					// else :
					
					
					$table = get_option('bew_payment_products_table_name'); 
					$mylink = $wpdb->get_row("SELECT * FROM $table WHERE id = $product_id");
					if( $mylink == NULL ){
						
						// HANDLE ERROR HERE
						//
						//
						echo "problème";
						$productAvailable = false;
						
					}else{
						
						// test if there is limited booking
						if( (int)$mylink->tickets > 0 ){
							
							// retrieve existant bookings
							$tableBooking = get_option('bew_payment_reservations');  
							// retrieve the number of reservations made for this event
							$alreadyBooked = $wpdb->get_var( $wpdb->prepare("SELECT sum(tickets) FROM $tableBooking WHERE product_id=$product_id GROUP BY product_id"));
	    					if( $alreadyBooked == NULL ){
						    	$alreadyBooked = 0;
						    }
						    // calculate available tickets
						    $available =  ((int)$mylink->tickets) - $alreadyBooked - (int)$quantity;
						    if( $available < 0 ){
						    	$productAvailable = false;
						    }else{
						    	$productAvailable = true;
						    }
							
							
						}else{
							$productAvailable = true;
						}
						
					} // end of if sql result
					
					if( !$productAvailable ){				
						?>
						Il n'y a pas assez de places disponibles.
						<br/>
						Revenir à la <a href="<?php echo get_ID_by_slug('bp_page_produits'); ?>" >page précédente</a> 
						<?php
					}else{
						// Check the user adress and phone
						
						global $current_user;
	      				get_currentuserinfo();
	      				
						
						$tel = get_user_meta($current_user->ID, 'adu_user_mobile', true);
						$tel = "0" . substr( $tel, 2);
						if( $tel == "0" ){
							$tel = "";
						}
						
						$lastname = get_user_meta($current_user->ID, 'last_name', true);
						$firstname = get_user_meta($current_user->ID, 'first_name', true);
						$adress = get_user_meta($current_user->ID, 'adu_user_adresse', true);
						$postal = get_user_meta($current_user->ID, 'adu_user_code_postal', true);
						$city = get_user_meta($current_user->ID, 'adu_user_ville', true);
						
						
	      				
	      				?>
	      				<h2>Étape 1 : Vérifiez vos informations personnelles</h2>
	      				
	      				Merci de vérifier vos informations personnelles et de remplir les champs manquants :
	      				
	      				
	      				<span id="product_info_error"></span>
	      				
	      				
	      				
	      				<form method="post" action="#" id="product_info_form">
	    					
	    					<input type="hidden" id="product_info_form_userid" value="<?= $current_user->ID ?>">
	    					
	    					
	    					<table id="personnal_info_form" >
	    						<tr>
	    							<td>
	    								<label for="lastname">Nom :</label>
	    							</td>
	    							<td>
	    								<input type="text" name="lastname" id="product_info_form_lastname" value="<?= $lastname ?>">
	    							</td>
	    							<td>
	    								<a href="#" class="form_help" title="Rentrer votre Nom">?</a>
	    							</td>
	    						</tr>
	    						<tr>
	    							<td>
	    								<label for="firstname">Prénom :</label>
	    							</td>
	    							<td>
	    								<input type="text" name="firstname" id="product_info_form_firstname" value="<?= $firstname ?>">
	    							</td>
	    							<td>
	    								<a href="#" class="form_help" title="Rentrer votre prénom">?</a>
	    							</td>
	    						</tr>
	    						<tr>
	    							<td>
	    								<label for="adress">Adresse :</label>
	    							</td>
	    							<td>
	    								<input type="text" name="adress" id="product_info_form_adress" value="<?= $adress ?>">
	    							</td>
	    							<td>
	    								<a href="#" class="form_help" title="Rentrer votre adresse (voie et numéro de voie)">?</a>
	    							</td>
	    						</tr>
	    						<tr>
	    							<td>
	    								<label for="postalcode">Code postal :</label>
	    							</td>
	    							<td>
	    								<input type="text" name="postalcode" id="product_info_form_cp" value="<?= $postal ?>">
	    							</td>
	    							<td>
	    								<a href="#" class="form_help" title="Rentrer votre Code Postal">?</a>
	    							</td>
	    						</tr>
	    						<tr>
	    							<td>
	    								<label for="city">Ville :</label>
	    							</td>
	    							<td>
	    								<input type="text" name="city" id="product_info_form_city" value="<?= $city ?>">
	    							</td>
	    							<td>
	    								<a href="#" class="form_help" title="Rentrer la ville dans laquelle vous êtes domicilié">?</a>
	    							</td>
	    						</tr>
	    						<tr>
	    							<td>
	    								<label for="phone">Numéro de téléphone :</label>
	    							</td>
	    							<td>
	    								<input type="text" name="phone" id="product_info_form_phone" value="<?= $tel ?>">
	    							</td>
	    							<td>
	    								<a href="#" class="form_help" title="Rentrer votre numéro de téléphone sous la forme 06XXXXXXXX">?</a>
	    							</td>
	    						</tr>
	    						<tr>
	    							<td>
	    							</td>
	    							<td>
	    								<input type="submit" name="submit" id="product_info_form_submit" value="Enregistrer">
	    								
	    								<img src="<?php bloginfo('stylesheet_directory') ?>/images/ajax-loader.gif" id="product_info_form_loader" style="display:none;">
	    							</td>
	    						</tr>
	    					
	    					
	    					</table>
	    				</form>
    					
      				
						<div id="product_info_choice">
						
						<span id="product_info_choice_error"></span>
						<span id="product_info_choice_success"></span>
						
						<h2>Étape 2 : Choisissez votre moyen de paiement</h2>
						
						
	      				<?php
						// Fill the Mercanet caddie with current values
							 
						// fill caddie value
						$caddie = base64_encode( $product_id."#".$product_name."#".$quantity."#".$cerfa."#".$bookable."#".$group_id );
						
						// Prepare and launch Mercanet call_request script
						
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
						//	Si aucun transaction_id n'est affecté, request en génère un automatiquement à partir de heure/minutes/secondes
						//	Référez vous au Guide du Programmeur pour les réserves émises sur cette fonctionnalité
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
				
						//	Appel du binaire request
						$result=exec("$path_bin $parm");
						
						// Handle the return values from the Mercanet request script
						
						//	sortie de la fonction : $result=!code!error!buffer!
						//	    - code=0	: la fonction génère une page html contenue dans la variable buffer
						//	    - code=-1 	: La fonction retourne un message d'erreur dans la variable error
						//	On separe les differents champs et on les met dans une variable tableau
						$tableau = explode ("!", "$result");
					
						//	récupération des paramètres
						$code = $tableau[1];
						$error = $tableau[2];
						$message = $tableau[3];
					
						if (( $code == "" ) && ( $error == "" ) ){
					 		// Error request file not found
					  		print ("<BR><CENTER>Erreur appel API request</CENTER><BR>");
					  		print ("Executable request non trouve $path_bin");
					 	} else if ($code != 0){
							// Error Payment API
							print ("<center><b><h2>Erreur appel API de paiement.</h2></center></b>");
							print ("<br><br><br>");
							print (" message erreur : $error <br>");
						} else {
							// OK
							//print (" $error <br>"); // print DEBUG
							?>
							<div id="product_choice">
								<img src="<?php echo bloginfo('template_directory') ?>/images/product_arrow.png" id='product_choice_arrow'>
								<?php
								print (" $message <br>");
								$previousPage = get_ID_by_slug('bp_page_produits');
								?>
								<a href="<?php echo $previousPage ?>" > &#171; Revenir &#224; la page pr&#233;c&#233;dente</a>
							</div>
						<?php
						}// fin du test sur valeur de retour script request
						
						?>
						</div>
						<?php
					} // end of it there is available  booking (double check with js)
    				
			}// fin du if test sur utilisateur loggé
			
		}// fin du if test sur empty( var POST) pour vérifier qu'on accède pas directement à cette page
		?>
</div><!-- #content -->


<script type="text/javascript" >
jQuery(document).ready(function() {
	
	jQuery('.form_help').tipsy({
		live: true,
		fallback: 'aide',
		opacity: 0.95,
		gravity: 'w'
	});
	
	
	
	jQuery("#product_info_form_submit").click( function(){
		
		// show ajax loader
		//jQuery("#product_info_form_loader").show();
		
		// reinitialize info message 
		jQuery("#product_info_error").html("").hide();
		
		var lastname = jQuery("#product_info_form_lastname").val();
		var firstname = jQuery("#product_info_form_firstname").val();
		var adress = jQuery("#product_info_form_adress").val();
		var cp = jQuery("#product_info_form_cp").val();
		var city = jQuery("#product_info_form_city").val();
		var phone = jQuery("#product_info_form_phone").val();
		
		// test if one of the field is empty
		if( lastname == "" || firstname == "" || adress == "" || cp == "" || city == "" || phone == "" ){
			// append error message
			jQuery("#product_info_error").show().append("Un ou plusieurs champs sont vides.");
			return false;
		}
				
		// Validate postal code : only numbers
		if( isNaN(cp) ){
			jQuery("#product_info_error").show().append("Le code postal ne peut contenir que des nombres.");
			return false;
		}
		
		// Validate phone number
		var prefix = phone.substr(0,2);
		var formatted_prefix = "33";
		var replace = phone.substr(1,9);
		
		if(/^[0-9]{10}$/.test(phone) && (prefix==="06"||prefix==="07") ){
			phone = formatted_prefix+replace;
		}else{
			jQuery("#product_info_error").append("Le num\351ro de t\351l\351phone est invalide.").show();
			return false;
		}
		
		
		// post ajax
		jQuery.post(
		   'wp-admin/admin-ajax.php', 
		   {
				'action':'product_save_meta',
				'lastname' : lastname,
				'firstname' : firstname,
				'adress' : adress,
				'postalcode' : cp,
				'city' : city,
				'phone' : phone
		   }, 
		   function(response, status){
		      	// test if Ajax feedback is Okay
				if(status==="success"){
					// test if book saving is ok
					if( response ){
						// update saved
						
						jQuery("#product_info_choice_success").css('display', 'block').append("Informations enregistrées.");
						jQuery("#product_info_choice").show();
						
					}else{
						// update not saved
						
						jQuery("#product_info_choice_error").css('display', 'block').append("En raison d'un problème technique, vos données n'ont pas pu être enregistrées. <br/>Si l'achat de ce produit donne droit à un Cerfa, merci d'en faire la demande à la Synagogue. ");
						jQuery("#product_info_choice").show();
					}
				}else{
					// Problème Ajax
					
					
				} // end of ajax feedback test
		   }// end of function ajax response
		); // end of function juquery post
		
		
		//jQuery("#product_info_form_loader").hide();
		
		
		
		
		
		return false;
	}); // end of booking link click
	
});
</script>








<?php get_sidebar(); ?>
<div class="clear"></div>
</div><!-- #container -->
<?php get_footer(); ?>