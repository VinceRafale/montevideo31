<?php
/*
Template Name: page calendrier evenements 
*/

// Page display
get_header();
?>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/js/jquery.pajinate.min.js"></script>
    

<div id="container">
	<div id="content" role="main">
	
		<!-- Display title and custom text content -->
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1 class="entry-title"><?php the_title(); ?></h1>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</div>
		<?php endwhile; ?>
		
		<?php 
		// display calendar
		echo do_shortcode( '[google-calendar-events id="3" max="0" ]' ); 
		?>
		
		<div id="even_pagination_container">
			<!-- Title of the subpage -->
			<h1 class="entry-title">Liste des Offices :</h1>
			<?php 
			// display list
			echo do_shortcode( '[google-calendar-events id="9" type="list"]' );
			?>
			<div class="even_pagination_navigation"></div>
		</div>
		

</div><!-- #content -->
<?php get_sidebar(); ?>
<div class="clear"></div>
</div><!-- #container -->


<script type="text/javascript">
jQuery(document).ready(function() {

	// pagination liste evenements 
	jQuery('#even_pagination_container').pajinate({
		items_per_page : 15,
		show_first_last : false,
		item_container_id : '.gce-list',
		nav_panel_id : '.even_pagination_navigation',
		nav_label_prev : 'Précédent',
		nav_label_next : 'Suivant'			
	});
	
	
	

});
</script>
<?php get_footer(); ?>