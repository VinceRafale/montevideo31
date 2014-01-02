<?php
/*
Template Name: Atos Moyen payement
*/

/* 	NOTE IMPORTANTE : 
	
	La page wordpress utilisant ce template doit absolument avoir un slug Žgal ˆ "bp_page_moyen_paiement"
	
	La page wordpress d'inscription doit absolument avoir un slug Žgal ˆ "inscription" et celle de connexion "connexion"

*/

?>
<?php
get_header(); 
?>
<div id="container">
	<div id="content" role="main">
		<!-- Display title and custom text content -->
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php if ( is_front_page() ) { ?>
				<h2 class="entry-title"><?php the_title(); ?></h2>
			<?php } else { ?>
				<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php } ?>

			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</div>
		<?php endwhile; ?>
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
					session_start();
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
						// delete session vars
						unset($_SESSION['product_id']);
						unset($_SESSION['product_name']);
						unset($_SESSION['quantity']);
						unset($_SESSION['price']);
						unset($_SESSION['cerfa']);
						
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
					
					}
					 
				
				/*	
				 *	Fill the Mercanet caddie with current values
				 */
					 
				// fill caddie value
				$caddie = base64_encode( $product_id."_".$product_name."_".$quantity."_".$cerfa."_".$group_id );
				
				/*
				 *	Prepare and launch Mercanet call_request script
				 */
				
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
				//	Si aucun transaction_id n'est affectŽ, request en gŽnre un automatiquement ˆ partir de heure/minutes/secondes
				//	RŽfŽrez vous au Guide du Programmeur pour les rŽserves Žmises sur cette fonctionnalitŽ
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
				
				/*
				 *	Handle the return values from the Mercanet request script
				 */
				
				//	sortie de la fonction : $result=!code!error!buffer!
				//	    - code=0	: la fonction gŽnre une page html contenue dans la variable buffer
				//	    - code=-1 	: La fonction retourne un message d'erreur dans la variable error
				//	On separe les differents champs et on les met dans une variable tableau
				$tableau = explode ("!", "$result");
			
				//	rŽcupŽration des paramtres
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
						<a href="<?php echo $previousPage ?>" > << Revenir &#224; la page pr&#233;c&#233;dente</a>
					</div>
				<?php
				}// fin du test sur valeur de retour script request
				
			}// fin du if test sur utilisateur loggŽ
			
			}// fin du if test sur empty( var POST) pour vŽrifier qu'on accde pas directement ˆ cette page
		?>
</div><!-- #content -->


<?php get_sidebar(); ?>
<div class="clear"></div>
</div><!-- #container -->
<?php get_footer(); ?>