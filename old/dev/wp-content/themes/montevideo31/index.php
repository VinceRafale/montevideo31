
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
        
        
        	<?php 
			
						$args = array(
		
							'slugs' => 'a-la-une',
							'posts_per_page' => 5,
							'display_title' => 1,
							'display_tb' => 1,
							'display_tb_in_content' => 'no_image',
							'display_content' => 1,
							'normal_tb_size' => array('300', '300', true),
							'display_date' => 0,
							'custom_class' => 'carousel',
							'excerpt_only' => 0,
							'transient_time' => 600,
							'disable_transient' => current_user_can('edit_posts'),
							'orderby' => 'date',
							'order' => 'DESC'
							
						);
	
    	
		
						$dst_sa = new dstSelectedPostsCarousel($args);
		
						if($dst_sa) echo $dst_sa;
        	?>
            
            
        </div>
        
        <div id="faire-parts">
        <span class="fp_title"><?php _e('Announcements', 'twentyten'); ?></span>
      
        <?php 
        			$args = array(
		
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
						
						$dst_sa = new dstSelectedPostsCarousel($args);
		
						if($dst_sa) echo $dst_sa;
        
        ?>
        
        <div class="fp_ctrl">
        <a id="fp_next" href="#previous">&nbsp;</a>
        <a id="fp_prev" href="#next">&nbsp;</a>
        </div>
        <a id="fp_add" href="/proposer-un-article"><?php _e('Publish Yours', 'twentyten'); ?></a>
        </div>
        
        <div id="trio">
        
               <?php 
        			$args = array(
		
							
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
		
						if($dst_sa) echo $dst_sa;
        
        ?>
        
        </div>
        
        
		<div id="container">
			<div id="content" role="main">

			<?php
			/* Run the loop to output the posts.
			 * If you want to overload this in a child theme then include a file
			 * called loop-index.php and that will be used instead.
			 */
			 
			 query_posts('cat=1&post_status=publish');
			 
			 $GLOBALS['force_full_content'] = true;
			 
			 get_template_part( 'loop', 'index' );
			?>
			</div><!-- #content -->
		

<?php get_sidebar(); ?>
<div class="clear"></div>
</div><!-- #container -->
<?php get_footer(); ?>
