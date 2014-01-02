<?php

if ( !is_admin() ) {
	wp_deregister_script('jquery');
	wp_register_script('jquery', ("https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"), false, '1.6.4');
	wp_enqueue_script('jquery');
    
}


/*
 * CUSTOM CSS FOR CHANGE PASSWORD PAGE
 */

function logingk() {
	echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('template_directory') . '/customlogin.css" />';
}

add_action('login_head', 'logingk');

/* 
 * CHANGE DEFAULT EMAIL FROM
 */

add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');

function new_mail_from($old) {
 return 'acti@montevideo31.com';
}
function new_mail_from_name($old) {
 return 'Synagogue Montévidéo 31';
}


/*
 * PROFIL PAGE : ARCHIVE A PETITE ANNONCE
 */

add_action('wp_ajax_profil_archive_pa', 'ajax_archive_pa'); // link ajax action to below function
 
function ajax_archive_pa() {
	
	$my_post['ID'] = $_POST['postId'];
  	$my_post['post_status'] = 'draft';
	$pid = wp_update_post( $my_post );
	//$response = json_encode( array( 'response' => $pid ) );
	//header( "Content-Type: application/json" );
	echo $response;
	exit;
}


/*
 * 	PRODUCTS PAGE : display product line
 */

function display_product_item($product){

	global $wpdb;
	$isBuyable = true;
	$isBookable = false;
	$productId = $product->id;
	
	// handle réservation 
	if( (int)$product->tickets > 0 ){
		
		$table = get_option('bew_payment_reservations');  
		// retrieve the number of reservations made for this event
		$booked = $wpdb->get_var( $wpdb->prepare("SELECT sum(tickets) FROM $table WHERE product_id=$productId GROUP BY product_id"));
	    
	    // if no result, it means no reservation made
	    if( $booked == NULL ){
	    	$booked = 0;
	    }
	    // calculate available tickets
	    $available =  ((int)$product->tickets)-$booked;
	    if( $available <= 0 ){
	    	$isBuyable = false;
	    	$isBookable = true;
	    }else{
	    	$isBookable = true;
	    }
		
		
	}
	
	if( !$isBuyable ){
		
		return "nok";
		
	}
	
	?>
	<div class="b_prods_container" >
	<?php
	// if the product can be buyed, display form
	if( $isBuyable ){
		?>
		<form method="post" action="<?php echo get_ID_by_slug('bp_page_moyen_paiement'); ?>" >
		<input type="hidden" name="bp_product_name" value="<?= stripslashes($product->name) ?>" >
		<input type="hidden" name="bp_product_type" value="<?php echo $product->type; ?>" >
		<input type="hidden" name="bp_product_id" value="<?php echo $product->id; ?>" >
		<input type="hidden" name="bp_product_cerfa" value="<?php echo $product->cerfa; ?>"  >
		<input type="hidden" name="bp_product_price" value="<?php echo $product->price; ?>"  >
		<?php
		if( $isBookable ){
			?>
			<input type="hidden" name="bp_product_bookable" value="yes"  >
			<?php
		}else{
			?>
			<input type="hidden" name="bp_product_bookable" value="no"  >
			<?php
		}
	}
	
	// display table
	?>
	<table class="b_prods_tab">
		<tr>
			<td rowspan=2 class="b_prods_tab_c1">
				<?php
				echo stripslashes($product->name);
				echo cerfa_available($product->cerfa);
				if( $isBookable ){
					
					if( $isBuyable ){
						?>
						<span class="tickets-red"><br/>Il reste <?= $available ?> place(s) pour cet évènement</span>
						<?php
					}else{
						?>
						<span class="tickets-red-nok"><br/>Ce produit n'est plus disponible</span>
						<?php
					}
					
				}
				?>
			</td>
			<td class="b_prods_tab_c2">Prix :</td>
			<td class="b_prods_tab_c3">Quantit&#233; :</td>
			<td class="b_prods_tab_c4">Total :</td>
			<td rowspan=2 class="b_prods_tab_c5">
			<?php
			if( $isBuyable ){
				?>
				<input type="submit" name="submit" id="sub<?= $product->id ?>" class="product_buy_button" value="Acheter" >
				<?php
				// hidden field for available places js verification
				if( $isBookable ){
					?>
					<input type="hidden" id="avai<?= $product->id ?>" value="<?= $available ?>">
					<?php
				}else{
					?>
					<input type="hidden" id="avai<?= $product->id ?>" value="na">
					
					<?php
				}
			}
			?>
			</td>
		</tr>
		<tr>
			<td><span class="bp_product_initial_price"><?php echo $product->price; ?></span>&#128;</td>
			<td>
				<select name="bp_product_quantity" class="quantity_input" id="pla<?= $product->id ?>">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				</select>
			</td>
			<td>
				<span class="bp_product_total_price"><?php echo $product->price; ?></span>&#128;
			</td>
		</tr>
	</table>
	<?php

	// if the product can be buyed, close form tag
	if( $isBuyable ){
		?>
		</form>	
		<?php
	}
	// close the container div
	?>
	</div>
	<?php
}

/*
 * 	PRODUCTS PAGE : display sold out item
 */
function display_soldout_item($product){

	?>
	<div class="b_prods_container" >
		<table class="b_prods_tab">
			<tr>
				<td rowspan=2 class="b_prods_tab_c1">
				<?php
				echo stripslashes($product->name);
				echo cerfa_available($product->cerfa);
				?>
				<span class="tickets-red-nok"><br/>Ce produit n'est plus disponible</span>
				</td>
				<td class="b_prods_tab_c2">Prix :</td>
				<td class="b_prods_tab_c3">Quantit&#233; :</td>
				<td class="b_prods_tab_c4">Total :</td>
				<td rowspan=2 style="width: 78px;"></td>
			</tr>
			<tr>
				<td><span class="bp_product_initial_price"><?php echo $product->price; ?></span>&#128;</td>
				<td>
					<select name="bp_product_quantity" class="quantity_input" id="pla<?= $product->id ?>">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
					</select>
				</td>
				<td>
					<span class="bp_product_total_price"><?php echo $product->price; ?></span>&#128;
				</td>
			</tr>
		</table>
	</div>	    	
	<?php
}


/*
 * 	PRODUCTS PAGE : display if cerfa available
 */
function cerfa_available( $cerfa ){

	if( $cerfa ){
		?>
		<span class="cerfa-green"><br/>Cerfa disponible</span>
		<?php
	}

}

/*
 * 	PRODUCTS PAGE : display if cerfa available
 */
function reservation_available( $tickets, $product_id ){
	
	/*
	on récupère le nombre de tickets
	
	si le nombre de tickets est égal à zero, alors cela veut dire qu'il n'y a pas de réservation pour ce produit, on n'affiche rien, sauf un champ hidden pour la validation javascript.
	
	si le nombre de tickets est supérieur à zéro, alors cela veut dire qu'il y a une réservation possible, dans ce cas plusieurs possibilités :
	
		- il reste encore des places disponibles, dans ce cas on affiche le nombre de places dispo et on affiche aussi en hidden ce nombre de places (pour la validation javascript)
		- il ne reste plus de place disponible, dans ce cas on affiche "plus de places dispo" et aussi "na" dans le champ hidden, pour la validation (dans ce cas bloquage) javascript
		
		
	*/
	
	
	
	
	
	}

/*
 *	PRODUCT USER INFO UPDATE AJAX
 */
add_action('wp_ajax_product_save_meta', 'ajax_product_update'); // link ajax action to below function
 
function ajax_product_update() {
	
	$state = true;
	$problem = "";
	
	$user = get_current_user_id();
	
	if( !update_user_meta($user, 'adu_user_mobile', $_POST['phone'])){
		$state = false;
		$problem = "tel";
	}
	
	if( !update_user_meta($user, 'last_name', $_POST['lastname'])){
		$state = false;
		$problem .= "nom";
	}
	
	if( !update_user_meta($user, 'first_name', $_POST['firstname'])){
		$state = false;
		$problem .= "prenom";
	}
	
	if( !update_user_meta($user, 'adu_user_adresse', $_POST['adress'])){
		$state = false;
		$problem .= "adresss";
	}
	
	if( !update_user_meta($user, 'adu_user_code_postal', $_POST['postalcode'])){
		$state = false;
		$problem .= "cp";
	}
	
	if( !update_user_meta($user, 'adu_user_ville', $_POST['city'])){
		$state = false;
		$problem .= "ville";
	}
	

	$response = json_encode( array( 'response' => $state ) );
	header( "Content-Type: application/json" );
	echo $response;
	exit;
}



/*
 * 	EVENTS AJAX
 

add_action('wp_ajax_book_event', 'ajax_book_event'); // link ajax action to below function
add_action('wp_ajax_unbook_event', 'ajax_unbook_event'); // link ajax action to below function


// ajax event booking function
function ajax_book_event() {
	// vars init for checking if the update meta will be ok or not
	$participantUpdateSuccess = false;
	$placesUpdateSuccess = false;
	
	// get the post (event) id 
	$postID = $_POST['postID'];
	// get the number of tickets
	$tickets = $_POST['nbTickets'];
	
	// update participants string
	$participants = get_post_meta($postID, 'gn_participants', true);
	$user = get_current_user_id();
	
	// construct array
	$participants[$user] = $tickets;
	
	//$participants // prepend user id to existant string, as he cannot see the booking link if he has already booked this event, there is no validation to do here
    $participantUpdateSuccess = update_post_meta($postID, 'gn_participants', $participants);
	// update nombre de place restant 
	$nb_restant =  get_post_meta($postID, 'gn_nombre_places_restant', true);
	$nb_restant = (int)$nb_restant - (int)$tickets;
	$placesUpdateSuccess = update_post_meta($postID, 'gn_nombre_places_restant', $nb_restant);
	
	// generate the response : if one of the update has failed, return error
	if( !$participantUpdateSuccess || !$placesUpdateSuccess){
		$response = json_encode( array( 'success' => false ) );
	}else{
		$response = json_encode( array( 'success' => true ) );
	}
	header( "Content-Type: application/json" );
	echo $response;
	exit;
}

// ajax event booking function
function ajax_unbook_event() {
	// vars init for checking if the update meta will be ok or not
	$participantUpdateSuccess = false;
	$placesUpdateSuccess = false;
	
	// get the post (event) id 
	$postID = $_POST['postID'];
	
	// update participants string
	$participants = get_post_meta($postID, 'gn_participants', true);
	$user = get_current_user_id();
	
	// construct array
	$tickets = $participants[$user];
	unset( $participants[$user] );
	
	//$participants // prepend user id to existant string, as he cannot see the booking link if he has already booked this event, there is no validation to do here
    $participantUpdateSuccess = update_post_meta($postID, 'gn_participants', $participants);
	// update nombre de place restant 
	$nb_restant =  get_post_meta($postID, 'gn_nombre_places_restant', true);
	$nb_restant = (int)$nb_restant + (int)$tickets;
	$placesUpdateSuccess = update_post_meta($postID, 'gn_nombre_places_restant', $nb_restant);
	
	// generate the response : if one of the update has failed, return error
	if( !$participantUpdateSuccess || !$placesUpdateSuccess){
		$response = json_encode( array( 'success' => false ) );
	}else{
		$response = json_encode( array( 'success' => true ) );
	}
	header( "Content-Type: application/json" );
	echo $response;
	exit;
}

 * 	END OF EVENTS AJAX
 */

// Given a post (an event), check if the event is a
function gn_is_event_past( $post_id ){
	
	$postStatus = get_post( $post_id );
	if( $postStatus->post_status == "publish" ){
		return true;
	}elseif( $postStatus == "future" ){
		return false;
	}
}

// Given a post (an event), check if a user has already booked this event
function has_user_already_booked($post_id, $user_id){
	$participants = get_post_meta($post_id, 'gn_participants', true);
	//$participants = explode(";", $participants);
	if( !empty($participants[$user_id]) ){
		return true;
	}else{
		return false;
	}
}

// display the booking items in a post
function display_event_booking_header($post_id){
	
	// calculate the availables places 
	$nb_places = get_post_meta($post_id, 'gn_nombre_places', true);
	$nb_places_restantes = get_post_meta($post_id, 'gn_nombre_places_restant', true);
	$participants = get_post_meta($post_id, 'gn_participants', true);
	$payant = get_post_meta($post_id, 'gn_payant', true);
	?>
	<div class="event_meta_container" id="event<?= $post_id ?>">
	<?php
	// test if booking is available or not for this event
	if( $nb_places == 0 ){
		// no booking
		?>
		<span class="event_meta_nok" >Il n'y a pas de réservation possible pour cet évènement.</span>
		<?php
	}else{
		// booking available : 
		// test if there is available tickets
		if( $nb_places_restantes == 0){
			// no more tickets
			?>
			<span class="event_meta_nok" >Il n'y a plus de place disponible pour cet évènement.</span>
			<?php
		}else{
			// tickets availables
			?>
			<span class="event_meta_grey" >Nombre de places disponibles : 
				<span id="availableTickets<?= $post_id ?>" ><?= $nb_places_restantes ?></span>
			</span>
			<?php
			// test if user is logged
			if ( is_user_logged_in() ) {
				if( has_user_already_booked( $post_id, get_current_user_id() ) ){
					?>
					<span class="event_meta_ok">Vous êtes inscris à cet évènement</span>
					<span class="event_meta_nok" ><a href="#" class="gn_event_booking_cancel" id='event<?= $post_id ?>'> Se désinscrire </a></span>
					
					<?php
				}else{
					?>
					<div class="event_meta_blue">
						<a href='#' class='gn_event_booking_link' id='link<?= $post_id ?>' >
							S'inscrire à cet évènement
						</a>
						<div class="gn_event_booking_select" id='div<?= $post_id ?>'>
							<select >
								<option value="1" selected>1 place</option>
							  	<option value="2">2 places</option>
							  	<option value="3">3 places</option>
							  	<option value="4">4 places</option>
								<option value="5">5 places</option>
								<option value="6">6 places</option>
								<option value="7">7 places</option>
								<option value="8">8 places</option>
								<option value="9">9 places</option>
								<option value="10">10 places</option>
							</select>
							<a href="#" class="gn_event_booking_validate" id='event<?= $post_id ?>'> Ok </a>
						</div>
					</div>
					<?php
				} // end of if user has already booked the event
			} // end of if user logged (if not, alert displayed in header)
		} // end of if ( available tickets )
	} // end of if ( booking possible for the event)
	?>
	</div>
	<div class="event_meta_ajax_response" id="ajax<?= $post_id ?>"></div>
	<?php
}






/*
 *	Check if the current post (in the loop) is a Petite Annonce
 */
/*
function is_this_post_a_petite_annonce(){
	
	$custom_fields = get_post_custom();
	if( empty( $custom_fields['annonce_prix']['0']) ){
		return false;
	}else{
		return true;
	}
}
*/

/*
 * Return True or False whether the user (logged or not) is allowed to see a content.
 * Called in loop.php
 */

function is_user_allowed_to_see_this(){
	global $post;
	global $current_user;
	$adu = new adu_groups();
	if(!isset($post->ID)) return true;
	$groups = get_post_meta($post->ID, '_adu_acl_groups', true);
	if(in_array('_adu_acl_public_access', $groups)) return true;	
	if(in_array('all_users', $groups) && $current_user->ID > 0) return true;
	$user_groups = get_usermeta($current_user->ID, 'adu_groups');
	$ng = true;
	foreach($groups as $g){
		if(in_array($g, $user_groups)) return $content;
		if(array_key_exists($g, $adu->groups)) $ng = false;
	}
	if($ng) return  true;
	return false;
}


/*
 * Translate post status
 */

function translate_post_status( $status ){
	
	if( $status == "publish" )
		return "Publié";
	if( $status == "pending" )
		return "En attente de relecture";
	if( $status == "draft" )
		return "Archivé";

	
}


/*
 *	Pagination
 */

function theme_pagination(){

	global $wp_query, $wp_rewrite;
	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
	$pagination = array(
		'base' => @add_query_arg('page','%#%'),
		'format' => '',
		'total' => $wp_query->max_num_pages,
		'current' => $current,
                'show_all' => false,
                'end_size'     => 1,
                'mid_size'     => 2,
		'type' => 'list',
		'next_text' => '»',
		'prev_text' => '«'
		);

	if( $wp_rewrite->using_permalinks() )
		$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );

	if( !empty($wp_query->query_vars['s']) )
		$pagination['add_args'] = array( 's' => get_query_var( 's' ) );

	echo paginate_links( $pagination );

}

/*
 *	Excerpt for petites annonces
 */
function annonce_excerpt( $content, $link ){
	$string = $content;
	if( $string != null ){
		$string = substr( $string, 0, 200);
		$string .= "...";
		$string .= "<br/><a href='".$link."' >Lire l'annonce &#187;</a>";
		return $string;
	}else{
		return "Problème";
	}
}






/*
 *	Send a SMS
 */

function bew_send_a_sms( $sms ){

	$accesskey =  get_option("essemess_token_api",NULL);
	$adresse = "http://run.orangeapi.com/sms/sendSMS.xml";
	// shortcode d'émission : 20345 (Orange France), 38100 (Multi-opérateur France), 967482 (Orange UK), 447797805210 (international) ou world (international)
	$from = "38100";
	// activation du SMS long
	$long_text = "true";
	// activation de l'accusé de réception
	//	$ack = "true";
	// type d'encodage
	$content_encoding = "gsm7";
	// From
	$from= "38100";
	// Contenu du SMS
	$message = stripslashes(json_decode(get_option('essemess_welcome_message', NULL) ));
	if( $message == NULL ){
		$message = "Bienvenue sur le site de la Synagogue Montévidéo.";
	}
	$message = urlencode( stripslashes( $message ));
	// send
	$fd = file_get_contents($adresse . "?id=" . $accesskey . "&from=" . $from . "&to=" . $sms . "&content=" . $message . "&long_text=" . $long_text . "&content_encoding=" . $content_encoding);
	// Affichage de la réponse de l'API
	$xml=simplexml_load_string($fd);
	// retour de l'API Orange
	$statut_code = strval($xml->status->status_code);
	$statut_msg = $xml->status->status_msg;
	
	
}


/*
 *	Encode string to avoid SPAM when display email
 */

function encode_email_spam($e)
{
  for ($i = 0; $i < strlen($e); $i++) { $output .= '&#'.ord($e[$i]).';'; }
  return $output;
}


/*
 *	Add thumbnail support
 */
add_theme_support('post-thumbnails');
add_image_size('post-thumbnails', 100, 100, true);

/*
 *	Retrieve page URL with slug page in argument
 */
function get_ID_by_slug($page_slug) {	
    $page = get_page_by_path($page_slug);
    if ($page) {
    	$url = get_permalink( $page->ID );
        return $url;
    } else {
        return "probleme-d-url";
    }
} 




/**
 * TwentyTen functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, twentyten_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'twentyten_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640;

/** Tell WordPress to run twentyten_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'twentyten_setup' );

if ( ! function_exists( 'twentyten_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyten_setup() in a child theme, add your own twentyten_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'twentyten', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'twentyten' ),
		'footer' => __( 'Footer Navigation', 'twentyten' ),
	) );

	// This theme allows users to set a custom background
	add_custom_background();

	// Your changeable header business starts here
	define( 'HEADER_TEXTCOLOR', '' );
	// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
	define( 'HEADER_IMAGE', '%s/images/headers/path.jpg' );

	// The height and width of your custom header. You can hook into the theme's own filters to change these values.
	// Add a filter to twentyten_header_image_width and twentyten_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyten_header_image_width', 940 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyten_header_image_height', 198 ) );

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be 940 pixels wide by 198 pixels tall.
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Don't support text inside the header image.
	define( 'NO_HEADER_TEXT', true );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See twentyten_admin_header_style(), below.
	add_custom_image_header( '', 'twentyten_admin_header_style' );

	// ... and thus ends the changeable header business.

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'berries' => array(
			'url' => '%s/images/headers/berries.jpg',
			'thumbnail_url' => '%s/images/headers/berries-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Berries', 'twentyten' )
		),
		'cherryblossom' => array(
			'url' => '%s/images/headers/cherryblossoms.jpg',
			'thumbnail_url' => '%s/images/headers/cherryblossoms-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Cherry Blossoms', 'twentyten' )
		),
		'concave' => array(
			'url' => '%s/images/headers/concave.jpg',
			'thumbnail_url' => '%s/images/headers/concave-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Concave', 'twentyten' )
		),
		'fern' => array(
			'url' => '%s/images/headers/fern.jpg',
			'thumbnail_url' => '%s/images/headers/fern-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Fern', 'twentyten' )
		),
		'forestfloor' => array(
			'url' => '%s/images/headers/forestfloor.jpg',
			'thumbnail_url' => '%s/images/headers/forestfloor-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Forest Floor', 'twentyten' )
		),
		'inkwell' => array(
			'url' => '%s/images/headers/inkwell.jpg',
			'thumbnail_url' => '%s/images/headers/inkwell-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Inkwell', 'twentyten' )
		),
		'path' => array(
			'url' => '%s/images/headers/path.jpg',
			'thumbnail_url' => '%s/images/headers/path-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Path', 'twentyten' )
		),
		'sunset' => array(
			'url' => '%s/images/headers/sunset.jpg',
			'thumbnail_url' => '%s/images/headers/sunset-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Sunset', 'twentyten' )
		)
	) );
}
endif;

if ( ! function_exists( 'twentyten_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in twentyten_setup().
 *
 * @since Twenty Ten 1.0
 */
function twentyten_admin_header_style() {
?>
<style type="text/css">
/* Shows the same border as on front end */
#headimg {
	border-bottom: 1px solid #000;
	border-top: 4px solid #000;
}
/* If NO_HEADER_TEXT is false, you would style the text with these selectors:
	#headimg #name { }
	#headimg #desc { }
*/
</style>
<?php
}
endif;

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentyten_page_menu_args' );

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since Twenty Ten 1.0
 * @return int
 */
function twentyten_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'twentyten_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Twenty Ten 1.0
 * @return string "Continue Reading" link
 */
function twentyten_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyten_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string An ellipsis
 */
function twentyten_auto_excerpt_more( $more ) {
	return ' &hellip;' . twentyten_continue_reading_link();
}
add_filter( 'excerpt_more', 'twentyten_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function twentyten_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= twentyten_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'twentyten_custom_excerpt_more' );

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Twenty Ten's style.css.
 *
 * @since Twenty Ten 1.0
 * @return string The gallery style filter, with the styles themselves removed.
 */
function twentyten_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'twentyten_remove_gallery_css' );

if ( ! function_exists( 'twentyten_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyten_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '%s <span class="says">says:</span>', 'twentyten' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'twentyten' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'twentyten' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' );
			?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyten' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'twentyten'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;
 
/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override twentyten_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @since Twenty Ten 1.0
 * @uses register_sidebar
 */
function twentyten_widgets_init() { 
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Primary Widget Area', 'twentyten' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) ); 

	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Secondary Widget Area', 'twentyten' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'The secondary widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	
	// same two sidebars for events pages
	register_sidebar( array(
		'name' => __( 'Events Primary Widget Area', 'twentyten' ),
		'id' => 'events-primary-widget-area',
		'description' => __( 'The primary widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Events Secondary Widget Area', 'twentyten' ),
		'id' => 'events-secondary-widget-area',
		'description' => __( 'The secondary widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Top Sidebar', 'twentyten' ),
		'id' => 'top-sidebar',
		'description' => __( 'Top Sidebar for displaying date and chabbat', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-top %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'twentyten' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'twentyten' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 5, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'twentyten' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 6, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Fourth Footer Widget Area', 'twentyten' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
/** Register sidebars by running twentyten_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'twentyten_widgets_init' );

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'twentyten_remove_recent_comments_style' );

if ( ! function_exists( 'twentyten_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post—date/time and author.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_on() {
	
	
	$a_id = get_post();
	$a_id = $a_id->post_author;
	
	if( $a_id == 43 ){
		$a_meta = get_post_custom();
		$a_url = "#";
		$a_name = $a_meta['public_user_name'][0];
	}else{
		$a_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
		$a_name = get_the_author();
	}
	
	if( $a_id == 45){ 
	
		// faire part user : don't display author
		printf( __( '<span class="%1$s">Publié le</span> %2$s', 'twentyten' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		)
	);
	
	
	} else {
		
		// display normal author
		printf( __( '<span class="%1$s">Publié le</span> %2$s <span class="meta-sep">par</span> %3$s', 'twentyten' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			$a_url,
			
			sprintf( esc_attr__( 'Voir tous les articles par %s', 'twentyten' ), $a_name ),
			$a_name
		)
	);
	} 
	
}
endif;

if ( ! function_exists( 'twentyten_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;


add_filter('bew_login', create_function('',"  return '/connexion'; ")  );
add_filter('bew_register', create_function('',"  return '/inscription'; "));
add_filter('bew_profile', create_function('',"  return '/mon-profil'; "));

require_once('plugins/google_calendar.php');

// hide update messages
add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );