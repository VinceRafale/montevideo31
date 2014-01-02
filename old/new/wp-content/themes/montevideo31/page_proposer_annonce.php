<?php
/*
Template Name: Proposez petite annonce 
*/

global $current_user;

// if user is logged, get user infos
if( is_user_logged_in() ) {
	get_currentuserinfo();	
}

$missingField = false;
$saved = false;
$technicalIssue = false;

// if form submited
if( !empty( $_POST['action'] ) &&  $_POST['action'] == "new_post") {
	
	
	$missingFieldList = "<ul>";
	
	// validation
	if ( !empty($_POST['title']) ) {
		$title =  $_POST['title'];
	} else {
		$missingFieldList .= "<li>Titre de l'annonce</li>";
		$missingField = true;
	}
	if ( !empty($_POST['content']) ) {
		$content = $_POST['content'];
	} else {
		$missingFieldList .= "<li>Contenu de l'annonce</li>";
		$missingField = true;
	}
	if ( !empty($_POST['price']) ) {
		if( is_numeric($_POST['price']) ){
			$price = $_POST['price'];
		}else{
			$missingFieldList .= "<li>Le prix doit &#234;tre une valeur num&#233;rique.</li>";
			$missingField = true;
		}
		
	} else {
		$missingFieldList .= "<li>Prix</li>";
		$missingField = true;
	}
	
	if( is_user_logged_in() ) {
		$user_id = $current_user->ID;
	}else{
		$missingFieldList .= "<li>Veuillez vous connecter au site pour enregistrer l'annonce.</li>";
		$missingField = true;
	}
	
	// if there is no validation error, then we save the post
	if( !$missingField ){
		// query args
		$new_post = array(
		'post_author' 	=>	$user_id,
		'post_title'	=>	$title,
		'post_content'	=>	$content,
		'post_category'	=>	array('36'),  // Usable for custom taxonomies too
		'post_status'	=>	'pending',
		'post_type'		=>	'petite_annonce'  
		);
		// execute query
		$pid = wp_insert_post($new_post);
		// handle query result
		if( !$pid ){
			// post not saved
			$saved = false;
			$technicalIssue = true;	
		}else{
			// post saved
			$saved = true;
			add_post_meta( $pid, 'annonce_prix', $price );
		}
		
	}else{
		// format the missig field string to display error
		$missingFieldList .= "</ul>";
	}
	
	
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
		if( $saved ){
			?>
			<div class="frontend_publish_success">
			La petite annonce a &#233;t&#233; enregistr&#233;e.<br/>
			Elle va &#234;tre soumise &#224; relecture avant parution sur le site.
			</div>
			<?php
		}else{
		
			if( $technicalIssue ){
				?>
				<div class="frontend_publish_error">
					En raison d'un probl&#232;me technique, la petite annonce n'a pas &#233;t&#233; enregistr&#233;.<br/>
					Merci de contacter l'administrateur du site.
				</div>
				<?php
			}
		
			if( $missingField ){
				?>
				<div class="frontend_publish_error">
					La petite annonce n'a pas &#233;t&#233; enregistr&#233;e parce qu'un ou plusieurs champs sont manquant :
					<?= $missingFieldList ?>
				</div>
			<?php } ?>
			<form  method="post" action="#" enctype="multipart/form-data">
				<?php
				// echo login status
				if( !is_user_logged_in() ) {
		 			// user not logged
		 			$loginUrl = get_ID_by_slug('connexion');
		 			$signupUrl = get_ID_by_slug('inscription');
					?>
					<div class="frontend_publish_info">
						Vous n'&#234;tes pas connect&#233; au site. <br/>
						Il est n&#233;cessaire d'avoir un compte sur le site pour publier une petite annonce.<br/>
						Veuillez donc vous <a href="<?= $loginUrl ?>">Connecter</a> ou vous <a href="<?= $signupUrl ?>">Inscrire</a>.						</div>
					<?php
		 		}else{
					// user logged ?>
					<div class="frontend_publish_info">
						Vous &#234;tes connect&#233; au site.<br/>
						L'email de contact affich&#233; sera : <?= $current_user->user_email ?>.
					</div>
				<?php 
				} ?>
				<br/>
				<input type="hidden" name="action" value="new_post" />
				
				<!-- post name -->
				<fieldset class="frontend_publish_form" >
					<label for="title">Titre de l'annonce :</label>
					<input type="text" id="title" value="<?= $title ?>" tabindex="5" name="title" />
				</fieldset>
				
				<!-- price -->
				<fieldset class="frontend_publish_form" >
					<label for="title">Prix (en euros) :</label>
					<input type="text"  value="<?= $price ?>" tabindex="5" name="price" />
				</fieldset>
			
				
				<!-- post Content -->
				<fieldset class="frontend_publish_form">
					<label for="content">Contenu de l'annonce :</label>
					<textarea id="" tabindex="15" name="content" cols="80" rows="10"><?= $content ?></textarea>
				</fieldset>
			
				<fieldset class="submit">
					<input type="submit" value="Soumettre l'annonce" tabindex="40" id="submit" name="submit" />
				</fieldset>
			</form>
		<?php
		} ?>

</div><!-- #content -->
<?php get_sidebar(); ?>
<div class="clear"></div>
</div><!-- #container -->
<?php get_footer(); ?>