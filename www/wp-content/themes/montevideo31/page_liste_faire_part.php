<?php
/*
Template Name: Liste des faire part
*/

// Page display
global $wpdb;
global $more;
$more = 0;

get_header();
?>

<div id="container">
	<div id="content" role="main">
		<h1 class="page-title">Archives des <span>Faire-parts</span></h1>
		<?php
		$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
		query_posts( array ( 'post_type' => 'faire-part', 'paged' => $page, 'showposts' => 7 ) );
		while ( have_posts() ) : the_post();
			
			$fp_cat = get_post_meta( get_the_ID(), 'fairepart_cat', true);
			?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<!-- TITLE -->
			
			<div>
				<div class="faire-part-categorie">
				<?= $fp_cat ?>
				</div>
				<div class="faire-part-titre" >
					<h2 class=""><?php the_title(); ?></h2>
				</div>
				<div style="clear:both;"></div>
			</div>
			
			
			
			<!-- <span class="faire-part-cat"><?= $fp_cat ?> </span><h2 class=""><?php the_title(); ?></h2>
			 -->
			 
			 
			<!-- CONTENT -->
			<div class="entry-content">
				<?php the_content(); ?>
			
			</div>
		<!-- 	<div class="entry-utility without-comments"></div> -->
			
			
			<!-- End of single Event content -->
		</div>
			
		<!-- 	<div class="slide fairepart-list-title">
				<span class="fp_title">
				<?php
				/*	$fp_cat = get_post_meta( get_the_ID(), 'fairepart_cat', true); 
					if( empty($fp_cat) ){
						echo "Faire Part";
					}else{
						echo $fp_cat;
					} */
					?>
				</span>
				<h3 class="post_title"><?php the_title() ?></h3>
			</div>
			<div class="fairepart-list-content">
			
			</div>
			 -->
						
		<?php	
		endwhile;
		// Reset Query
		//wp_reset_query();
		?>
		<div id="template_pagination">
			<?php theme_pagination(); ?> 
		</div>
		
</div><!-- #content -->
<?php get_sidebar(); ?>
<div class="clear"></div>
</div><!-- #container -->
<?php get_footer(); ?>