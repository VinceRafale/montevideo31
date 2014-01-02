<?php
/*
Template Name: Proposez votre article 
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
		$missingFieldList .= "<li>Titre de l'article</li>";
		$missingField = true;
	}
	if ( !empty($_POST['content']) ) {
		$content = $_POST['content'];
	} else {
		$missingFieldList .= "<li>Contenu de l'article</li>";
		$missingField = true;
	}
	
	if( !is_user_logged_in() ) {
		// check there is an author and email	
		if ( !empty($_POST['username']) ) {
			$pseudo = $_POST['username'];
		} else {
			$missingFieldList .= "<li>Auteur de l'article</li>";
			$missingField = true;
		}
		if ( !empty($_POST['usermail']) ) {
			$usermail = $_POST['usermail'];
		} else {
			$missingFieldList .= "<li>Email</li>";
			$missingField = true;
		}
		// use the "public user" wordpress user profile
		$user_id = get_userdatabylogin('public_user');
		$user_id = $user_id->ID;
		
	}else{
		$user_id = $current_user->ID;
	}
	
	// if there is no validation error, then we save the post
	if( !$missingField ){
		// query args
		$new_post = array(
		'post_author' 	=>	$user_id,
		'post_title'	=>	$title,
		'post_content'	=>	$content,
		'post_category'	=>	array($_POST['cat']),  // Usable for custom taxonomies too
		'post_status'	=>	'pending',
		'post_type'		=>	'post'  
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
			// if it is an unregistered user, then save the pseudo and name in custom meta fields
			if ( !is_user_logged_in() ){
				add_post_meta( $pid, 'public_user_name', $pseudo );
				add_post_meta( $pid, 'public_user_mail', $usermail );
			}
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
			L'article a &#233;t&#233; enregistr&#233;.<br/>
			Il va &#234;tre soumis &#224; relecture avant parution sur le site.
			</div>
			<?php
		}else{
		
			if( $technicalIssue ){
				?>
				<div class="frontend_publish_error">
					En raison d'un probl&#232;me technique, l'article n'a pas &#233;t&#233; enregistr&#233;.<br/>
					Merci de contacter l'administrateur du site.
				</div>
				<?php
			}
		
			if( $missingField ){
				?>
				<div class="frontend_publish_error">
					L'article n'a pas &#233;t&#233; enregistr&#233; parce qu'un ou plusieurs champs sont manquant :
					<?= $missingFieldList ?>
				</div>
			<?php } ?>
			<form  method="post" action="#" enctype="multipart/form-data">
				<?php
				// echo login status
				if( !is_user_logged_in() ) {
		 			// user not logged
		 			$loginUrl = get_ID_by_slug('connexion');
					?>
					<div class="frontend_publish_info">
						Vous n'&#234;tes pas connect&#233; au site. <br/>
						Il est n&#233;cessaire de se <a href="<?= $loginUrl ?>">Connecter</a> ou de remplir les champs suivants :
					</div>
					
					<fieldset class="frontend_publish_author">
						<label for="username">Nom ( affich&#233; ) ( obligatoire ) :</label>
						<input type="text" value="<?= $pseudo ?>" tabindex="5" name="username"  />
					</fieldset>				
		 			<fieldset class="frontend_publish_author">
						<label for="usermail">Email ( ne sera pas affich&#233; ) ( obligatoire ) :</label>
						<input type="text" value="<?= $usermail ?>" tabindex="5" name="usermail"  />
					</fieldset>	
		 			<?php
		 		}else{
					// user logged ?>
					<div class="frontend_publish_info">
						Vous &#234;tes connect&#233; au site.<br/>
						L'auteur de l'article affich&#233; sera : <?= $current_user->user_login ?>.
					</div>
				<?php 
				} ?>
				<br/>
				<input type="hidden" name="action" value="new_post" />
				<!-- post name -->
				<fieldset class="frontend_publish_form" >
					<label for="title">Titre de l'article :</label>
					<input type="text" id="title" value="<?= $title ?>" tabindex="5" name="title" class="frontend_publish_form_input" />
				</fieldset>
			
				<!-- post Category -->
				<fieldset class="frontend_publish_form">
					<label for="cat">Cat&#233;gorie de l'article :</label>
					<br/>
					<?php wp_dropdown_categories( 'tab_index=10&taxonomy=category&hide_empty=0' ); ?>
				</fieldset>
			
				<!-- post Content -->
				<fieldset class="frontend_publish_form">
					<label for="content">Corps de l'article :</label>
					<textarea class="frontend_publish_form_input" tabindex="15" name="content" cols="80" rows="10"><?= $content ?></textarea>
				</fieldset>
			
				<fieldset class="submit">
					<input type="submit" value="Soumettre l'article" tabindex="40" id="submit" name="submit" />
				</fieldset>
			</form>
		<?php
		} ?>

</div><!-- #content -->
<?php get_sidebar(); ?>
<div class="clear"></div>
</div><!-- #container -->
<?php get_footer(); ?>