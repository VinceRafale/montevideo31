<?php
/*
Template Name: Effacer une petite annonce 
*/

global $current_user;

// if user is logged, get user infos
if( is_user_logged_in() ) {
	get_currentuserinfo();	
}

$deleted = false;

if( !empty($_POST['delete_annonce_user_id']) ){
	
	$annonceUserId = $_POST['delete_annonce_user_id'];
	$annonceID = $_POST['delete_annonce_id'];
	$annonceTitle = $_POST['delete_annonce_title'];
}

// if form submited
if( !empty( $_POST['action'] ) &&  $_POST['action'] == "delete") {
	$annonceId = $_POST['annonce_id'];
	wp_delete_post( $annonceId, true );
	$deleted = true;
} 


// Page display
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
		// echo login status
		if( !is_user_logged_in() ) {
 			// user not logged ?>
			<div class="frontend_publish_error">
				Vous n'avez pas l'autorisation d'acc&#233;der &#224; cette page sans &#234;tre connect&#233; au site.
			</div>
			<?php
 		}else{
 		
 			if( $deleted ){
 				?>
				<div class="frontend_publish_success">
				La petite annonce a &#233;t&#233; effac&#233;e.<br/>
				Cliquer <a href="/mon-profil">ici</a> pour revenir &#224; la page pr&#233;c&#233;dente.
				</div>
				<?php
 			}else{
				// user logged 
				// test if the logged user comes from the right item
				if( $annonceUserId == $current_user->ID ){
					?>
					<br/>
					<form  method="post" action="#" enctype="multipart/form-data">
					<input type="hidden" name="action" value="delete" />
					<input type="hidden" name="annonce_id" value="<?= $annonceID ?>" />
					&nbsp;Voulez vous supprimer l'annonce suivante : <i>"<?= $annonceTitle ?>"</i> ?			
					<fieldset class="submit">
						<input type="submit" value="Confirmer la suppression" tabindex="40" id="submit" name="submit" />
					</fieldset>
					</form>
	
				<?php 
	
				}else{
				?>
					<div class="frontend_publish_error">
						Vous n'avez pas l'autorisation d'acc&#233;der &#224; cette page.
					</div>
					<?php
				}
 			}
		} 
		?>

</div><!-- #content -->
<?php get_sidebar(); ?>
<div class="clear"></div>
</div><!-- #container -->

<?php get_footer(); ?>