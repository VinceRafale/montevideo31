<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="container">
			<div id="content" role="main">

				<?php
				/* Run the loop for the category page to output the posts.
				 * If you want to overload this in a child theme then include a file
				 * called loop-category.php and that will be used instead.
				 */
				
				
				if( is_single() && ( 'petite_annonce'== get_post_type()  ) ){
					
					// template for "petite annonce" posts
					
					if( have_posts() ){
						the_post(); 
					}
					
					?>
					
					<h1><?= the_title() ?></h1>
					<span id="single_annonce_content">
						<?= the_content(); ?>
					</span>
					<span id="single_annonce_price">
						<?php 
						$custom_fields = get_post_custom();
						echo "Prix : " . $custom_fields['annonce_prix']['0'] ."&#128";
						?>
					</span>
					<span id="single_annonce_contact">
						Adresse email de contact :
						<?php
						$author_id = $post->post_author;
						$author_data = get_userdata( $author_id );
						echo encode_email_spam($author_data->user_email);
						
						
						?>
					</span>
					
					
					
					<?php
				}else{
				 	// normal page display
					get_template_part( 'loop', 'category' );
				}
				?>

			</div><!-- #content -->
            
           

<?php get_sidebar(); ?>

 <div class="clear"></div>
		</div><!-- #container -->
<?php get_footer(); ?>
