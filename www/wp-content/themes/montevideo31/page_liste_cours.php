<?php 
/* Template Name: Custom Post Type Archive : Cours

*/

get_header();

?>

<script type="text/javascript" >
jQuery(document).ready(function() {
	
	// AJAX EVENT CALLER
	jQuery(".gn_event_booking_link").click( function(){
		// hide this link
	//	jQuery(this).hide();
		// get the id
		var eventId = this.id.replace('link','');
		// show the select for the number of places
		jQuery(".gn_event_booking_select#div"+eventId).css('display', 'inline');
	});	 
	
	jQuery(".gn_event_booking_cancel").click( function(){
		
		var answer = confirm("Confirmez vous la désinscription à cet évènement ?");
		if (answer){
			// get post id 
			var postId = this.id.replace('event','');
			
			//console.log(postId);
			
			jQuery.post(
			   'wp-admin/admin-ajax.php', 
			   {
					'action':'unbook_event',
					'postID' : postId
			   }, 
			   function(response, status){
			      	// test if Ajax feedback is Okay
					if(status==="success"){
						// test if book saving is ok
						if( response.success ){
							// booking saved
							jQuery("#event"+postId+".event_meta_container").hide();
							jQuery("#ajax"+postId+".event_meta_ajax_response").show().append("Votre désinscription a été prise en compte.");
						}else{
							// booking not saved
							jQuery("#event"+postId+".event_meta_container").hide();
							jQuery("#ajax"+postId+".event_meta_ajax_response").show().append("Votre désinscription n'a pas pu être prise en compte à cause d'un problème technique, merci de contacter l'administrateur du site.");
						}
					}else{
						// Problème Ajax
						jQuery("#event"+postId+".event_meta_container").hide();
						jQuery("#ajax"+postId+".event_meta_ajax_response").show().append("Votre désinscription n'a pas pu être prise en compte à cause d'un problème technique, merci de contacter l'administrateur du site.");
					} // end of ajax feedback test
			   }// end of function ajax response
			); // end of function juquery post
		}
		return false;
	});
	
	
	
	
	
	jQuery(".gn_event_booking_validate").click( function(){
		
		// get post id 
		var postId = this.id.replace('event','');
		
		// get the number of wanted tickets and the number of available tickets
		var nbTickets = jQuery(".gn_event_booking_select#div"+postId+" select").val();
		var nbAvailable = parseInt( jQuery("#availableTickets"+postId).text() );
		
		// test if number of tickets > number of available tickets
		if( nbAvailable < nbTickets ){
			alert("Il ne reste pas assez de places à cet évènement.")
			return false;
		}
		
		jQuery.post(
		   'wp-admin/admin-ajax.php', 
		   {
				'action':'book_event',
				'postID' : postId,
				'nbTickets' : nbTickets
		   }, 
		   function(response, status){
		      	// test if Ajax feedback is Okay
				if(status==="success"){
					// test if book saving is ok
					if( response.success ){
						// booking saved
						jQuery("#event"+postId+".event_meta_container").hide();
						jQuery("#ajax"+postId+".event_meta_ajax_response").show().append("Votre réservation a été enregistrée.");
					}else{
						// booking not saved
						jQuery("#event"+postId+".event_meta_container").hide();
						jQuery("#ajax"+postId+".event_meta_ajax_response").show().append("Votre réservation n'a pas pu être enregistrée à cause d'un problème technique, merci de contacter l'administrateur du site.");
					}
				}else{
					// Problème Ajax
					jQuery("#event"+postId+".event_meta_container").hide();
					jQuery("#ajax"+postId+".event_meta_ajax_response").show().append("Votre réservation n'a pas pu être enregistrée à cause d'un problème technique, merci de contacter l'administrateur du site.");
				} // end of ajax feedback test
		   }// end of function ajax response
		); // end of function juquery post
		return false;
	}); // end of booking link click
	
});
</script>




<div id="container">
	<div id="content" role="main">
	<?php
	global $post;
	rewind_posts();
	// Create a new WP_Query() object
	$wpcust = new WP_Query(
		array(
	   		'post_type' => array('les-cours'),
			'post_status' => array( 'publish', 'future' ),
	        'showposts' => '-1' ,
			'orderby' => 'date', 
			'order' => 'DESC'
			
		)
	);
	// if there is events to display
	if ( $wpcust->have_posts() ){
		
		// Display Warning message : Need to be logged to book
		if ( !is_user_logged_in() ) {
		?>
			<div class="event_meta_container">
			<span class="event_meta_nok" >Il est obligatoire d'être <a href='/inscription' >enregistré </a>sur le site pour s'inscrire à un évènement.</span>
			</div>
		<?php
		}
		
		// Loop on the events
	  	while( $wpcust->have_posts() ) : $wpcust->the_post();
	    ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        	
			<!-- DATE -->
			<div class="event_date">
            	<span class="month"><?php echo the_time('M'); ?>. <?php echo the_time('Y'); ?></span>
                <span class="day"><?php echo the_time('j'); ?></span>
                
            </div>

			<!-- TITLE -->
			<h2 class="entry-title"><a href="<?php //the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			
			<!-- NOMBRE DE PLACES / BOOKING -->
			<?php
			if( !gn_is_event_past( get_the_ID() ) ){
				display_event_booking_header( get_the_ID() );
			}else{
				?>
				<div class="event_meta_container">
					<span class="event_meta_grey" >Évènement passé</span>
				</div>
				<?php
			}
			?>
			<!-- CONTENT -->
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
			
			<!-- End of single Event content -->
		</div>
		<?php
		endwhile;  // close the Loop
	}else{
		?>
		<div id="post-0" class="post error404 not-found">
			<h1 class="entry-title"><?php _e( 'Not Found', 'twentyten' ); ?></h1>
			<div class="entry-content">
				<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		</div>
		<?php
	} // End of if on events
	
	wp_reset_query(); // reset the Loop
	?>
	</div><!-- #content -->
	<?php get_sidebar(); ?>
	<div class="clear"></div>
</div><!-- #container -->
<?php get_footer(); ?>
