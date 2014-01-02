<?php
/*
Template Name: Liste des petites annonces
*/

// Page display
global $wpdb;
global $more;
$more = 0;

get_header();
?>

<div id="container">
	<div id="content" role="main">
	
	<!-- Display title and custom text content -->
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php if ( is_front_page() ) { ?>
				<h2 class="entry-title"><?php the_title(); ?></h2>
			<?php } else { ?>
				<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php } ?>

			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</div>
		<?php endwhile; ?>			
		
		
		<?php
		$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
		query_posts( array ( 'post_type' => 'petite_annonce', 'paged' => $page, 'showposts' => 5 ) );
		if( !have_posts() ){
			echo "Aucune petite annonce d&#233;pos&#233;e pour le moment.";
		}else{
			?>
			<table id="table_annonces">
				<tr>
					<th scope="col" style="width:160px;">Titre</th>
					<th scope="col" style="width:40px;">Prix</th>
					<th scope="col">Extrait</th>
				</tr>
			<?php
			// loop on petites annonces
			while ( have_posts() ) : the_post();
				$contenu = get_the_content();
				$custom_fields = get_post_custom();
				?>
				<tr>
					<td><a href="<?= the_permalink() ?>" ><?= the_title() ?></a></td>
					<td><?= $custom_fields['annonce_prix']['0'] ?>&#128;</td>
					<td><?= annonce_excerpt($contenu, get_permalink() ) ?></td><!-- Voir function.php -->
				</tr>
				<?php
			endwhile;
			?>
			</table>
			<?php
		}
		
		
		
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