<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query. 
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="carousel">
       	
	       	<div class="carousel">
				<?php 
				// build args array
				$args = array( 
							
					'order' => 'DESC',
					'orderby' => 'date',
					'post_type' => 'post',
					'post_status' => 'publish', 
					'category_name' => 'a-la-une',
					'posts_per_page' => 4
				
				);
				// The Query
				$the_query = new WP_Query( $args );
				?>
				<div class='tabs'> 
					<?php
					// used to indicate the first item
					$tab = "current";
					// The Loop
					while ( $the_query->have_posts() ) : $the_query->the_post();
						?>          
						<a class="tab <?= $tab ?>" href="<?php the_permalink() ?>" >
							<?php
							if ( has_post_thumbnail() ) {
								the_post_thumbnail(array('60', '60', true));
							}
							?>
							<span class="post_title"><?php the_title() ?></span>
						</a>   
						<?
						$tab = "";
					endwhile;
					?>
				</div>
				<div class='slides'>
					<?php
					rewind_posts(); // rewind the post pointer
					// used to indicate the first item
					$slide = "slide-first";
					// The Loop
					while ( $the_query->have_posts() ) : $the_query->the_post();
						?>          
						<div class="slide <?= $slide ?>">
							<div class="post_featured_image">
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail(array('300', '300', true));
								}
								?>
	                        </div>
	                        <h3 class="post_title"><?php the_title() ?></h3>
							<div class="post_content">
							<?php   
								the_excerpt(); 
							?>
							</div>
						</div>
						<?
						$slide = "";
					endwhile;
					
					?>
				</div>
				<?php
				// Reset Post Data
				wp_reset_postdata();
				?>
	     	</div>            
       	</div>
		
		
		 <!-- FAIRE PARTS HOME -->
        <div id="faire-parts">
        	<!-- <span class="fp_title"><?php _e('Announcements', 'twentyten'); ?></span> -->
      		<div class='fp'>
          		<?php 
				// build args array
				$args = array( 
							
					'order' => 'DESC',
					'orderby' => 'date',
					'post_type' => 'faire-part',
					'post_status' => 'publish', 
					'posts_per_page' => 4
				
				);
				// The Query
				$the_query = new WP_Query( $args );
				?>
				<div class='tabs'> 
					<?php
					// used to indicate the first item
					$tab = "current";
					// The Loop
					while ( $the_query->have_posts() ) : $the_query->the_post();
						?>    
						<a class="tab <?= $tab ?>" href="<?php the_permalink() ?>" >
							<span class="post_title"><?php the_title() ?></span>
						</a>   
						<?
						$tab = "";
					endwhile;
					?>
				</div>
				<div class='slides'>
					<?php
					rewind_posts(); // rewind the post pointer
					// used to indicate the first item
					$slide = "slide-first";
					// The Loop
					while ( $the_query->have_posts() ) : $the_query->the_post();
						?>          
						<div class="slide <?= $slide ?>">
							<span class="fp_title ">
								<?php
								$fp_cat = get_post_meta( get_the_ID(), 'fairepart_cat', true); 
								if( empty($fp_cat) ){
									echo "Faire Part";
								}else{
									echo $fp_cat;
								}
								?>
							</span>
						
							<h3 class="post_title"><?php the_title() ?></h3>
							<div class="post_content">
							<?php   
								the_excerpt(); 
							?>
							</div>
						</div>
						<?
						$slide = "";
					endwhile;
					?>
				</div>
				<?php
				// Reset Post Data
				wp_reset_postdata();
				?>
      		</div>
       		<?php 
			/*	$args = array(
				'slugs' => 'faire-parts',
				'posts_per_page' => 20,
				'display_title' => 1,
				'display_tb' => 0,
				'display_content' => 1,
				'display_date' => 0,
				'custom_class' => 'fp',
				'excerpt_only' => 1,
				'transient_time' => 600,
				'disable_transient' => current_user_can('edit_posts')
			);
			//$dst_sa = new dstSelectedPostsCarousel($args);
			if($dst_sa) 
				echo $dst_sa;
			*/
        	?>
	        <div class="fp_ctrl">
	        	<a id="fp_next" href="#previous">&nbsp;</a>
	        	<a id="fp_prev" href="#next">&nbsp;</a>
	        </div>
	        <a id="fp_add" href="<?= get_ID_by_slug('publier-un-faire-part'); ?>">
	        	<?php _e('Proposez le v&#244;tre', 'twentyten'); ?>
	        </a>
	    </div>
	    
	    <!-- TRIO HOME -->
	    <div id="trio">
	    	
	    	<?php 
				// build args array
				$args = array( 
							
					'order' => 'DESC',
					'orderby' => 'date',
					'post_type' => 'post',
					'post_status' => 'publish', 
					'category_name' => 'trio-homepage',
					'posts_per_page' => 3
				
				);
				// The Query
				$the_query = new WP_Query( $args );
				?>
				<div class='trio'> 
					<ul class='selected-articles trio' >
					<?php
					// The Loop
					while ( $the_query->have_posts() ) : $the_query->the_post();
						?>   
						<li class="selected-article-trio">
							<div class="post_title"><?php the_title() ?></div>
							<div class="post_content">
		                    	<p>
		                    	<a class="trio_big_link" href="<?php the_permalink() ?>"><?php the_title() ?></a>
		                    	<span class='selected_article '>					
									<?php //the_content() ?>
								</span>
								</p>
		                	</div>  
						
						</li>
						
						
						 
						<?
					endwhile;
					?>
					</ul>
				</div>
           <?php 
		/*	$args = array(
				'posts_per_page' => 3,
				'display_title' => 1,
				'display_tb' => 0,
				'display_content' => 1,
				'display_date' => 0,
				'meta_key' => 'home_trio',
				'order'=> 'ASC',
				'orderby' => 'meta_value',
				'custom_class' => 'trio',
				'excerpt_only' => 0,
				'transient_time' => 600,
				'disable_transient' => current_user_can('edit_posts')
			);	
			$dst_sa = new dstSelectedArticlesAbstract($args);
			if($dst_sa) 
				echo $dst_sa;
    	*/
    		?>
        </div>
        
        
		<div id="container">
		
			<div id="content" role="main">
			<?php
			query_posts('cat=1&post_status=publish');
			$GLOBALS['force_full_content'] = true;
			get_template_part( 'loop', 'index' );
			?>
			</div><!-- #content -->
		

<?php get_sidebar(); ?>
<div class="clear"></div>
</div><!-- #container -->
<?php get_footer(); ?>
