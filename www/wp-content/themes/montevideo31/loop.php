<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<div id="nav-above" class="navigation">
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyten' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
	</div><!-- #nav-above -->
<?php endif; ?>

<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( 'Not Found', 'twentyten' ); ?></h1>
		<div class="entry-content">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->
<?php endif; ?>

<?php
	/* Start the Loop.
	 *
	 * In Twenty Ten we use the same loop in multiple contexts.
	 * It is broken into three main parts: when we're displaying
	 * posts that are in the gallery category, when we're displaying
	 * posts in the asides category, and finally all other posts.
	 *
	 * Additionally, we sometimes check for whether we are on an
	 * archive page, a search page, etc., allowing for small differences
	 * in the loop on each template without actually duplicating
	 * the rest of the loop that is shared.
	 *
	 * Without further ado, the loop:
	 */ ?>
<?php while ( have_posts() ) : the_post(); ?>

		<?php
		
		// Call the fonction in function.php which check if the current user is allowed to see the post
		if( !is_user_allowed_to_see_this() ){
			// echo nothing
		}else{
			// echo the post
			
		//	if( !is_this_post_a_petite_annonce() ){
			
				?>
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		        	<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					
					<?php if ( (is_archive() || is_search()) && !$GLOBALS['force_full_content']) : // Only display excerpts for archives and search. ?>
						<div class="entry-summary">
							<!-- If a thumbnail exists, display it on the right -->
							<div class="montev-thumbnail">
								<a href="<?php the_permalink(); ?>" >
								<?php
								if ( has_post_thumbnail()) {
									the_post_thumbnail('thumbnail');
								}
							 	?>
						 		</a>
							</div>
							<!-- The excerpt of the post -->
							<?php the_excerpt(); ?>
						</div><!-- .entry-summary -->
					<?php else : ?>
						<div class="entry-content">
							<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?>
							<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
						</div><!-- .entry-content -->
					<?php endif; ?>
					
					<!-- Add a "without comments" class into change the sprite if there is no "Reagissez!" button -->
					<div class="entry-utility <?php if(get_comments_number() > 0){ echo 'with-comments'; } if( !comments_open() ){ echo 'without-comments'; } ?>"  >
		            
		            	<span class="entry-info">
						<?php twentyten_posted_on(); ?>
		                </span> 
		                
		                <?php
		                // display "Reagissez!" link only if comments are allowed for this post
		               	if( comments_open() ){ ?>
		               		<span class="comments-link"><a href="<?php comments_link(); ?>"><?php _e('R&eacute;agissez !'); ?> </a></span>
		               	<?php 
		               	} ?>	                
		                <?php if(get_comments_number() > 0): ?>
		                <a href="<?php echo get_comments_link(); ?>" class="comments-number">
							<?php comments_number('', '<span class="number">1</span> '.__('Comment'), '<span class="number">%</span> '.__('Comments')); ?>
		                </a>
		                <?php endif; ?>
		                
					</div><!-- .entry-utility -->
				</div><!-- #post-## -->
		
				<?php comments_template( '', true ); ?>
		<?php
		//	} // end of if it is a petite annonce or not
		
		} // end of is user allowed to see this
		?>
<?php endwhile; // End the loop. Whew. ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<div id="nav-below" class="navigation">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyten' ) ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
				</div><!-- #nav-below -->
<?php endif; ?>
