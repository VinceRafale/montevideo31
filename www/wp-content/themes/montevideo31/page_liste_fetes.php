<?php 
/* Template Name: Custom Post Type Archive : Fetes

*/

get_header();

?>

<div id="container">
	<div id="content" role="main">
	<?php
	global $post;
	rewind_posts();
	// Create a new WP_Query() object
	$wpcust = new WP_Query(
		array(
	   		'post_type' => array('les-fetes'),
			'post_status' => array( 'publish', 'future' ),
	        'showposts' => '-1' ,
			'orderby' => 'date', 
			'order' => 'DESC'
			
		)
	);
	// if there is events to display
	if ( $wpcust->have_posts() ){
		
		
		
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
