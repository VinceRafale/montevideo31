<?php
/*
Template Name: Page fetes
*/

get_header(); ?>

		<div id="container">
			<div id="content" role="main">

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php if ( is_front_page() ) { ?>
						<h2 class="entry-title"><?php the_title(); ?></h2>
					<?php } else { ?>
						<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php } ?>

					<div class="entry-content">
					
						<br/><br/>
					
						<div class="entry-utility">
							Calendrier des fêtes
							<span class="comments-link"><a href="<?= get_permalink( '988' ); ?>" >Accès »</a></span>
						</div>
					
						<br/><br/>
						
						<div class="entry-utility">
							Page consacrée à la Pessah
							<span class="comments-link"><a href="<?= get_permalink( '346' ); ?>" >Accès »</a></span>
						</div>
					
					
					
					</div><!-- .entry-content -->
				</div><!-- #post-## -->

<?php endwhile; ?>

			</div><!-- #content -->
		

<?php get_sidebar(); ?>

<div class="clear"></div>
</div><!-- #container -->
<?php get_footer(); ?>
