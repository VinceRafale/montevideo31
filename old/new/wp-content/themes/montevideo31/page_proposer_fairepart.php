<?php
/*
Template Name: Proposez votre faire part 
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
		$missingFieldList .= "<li>Titre du faire part</li>";
		$missingField = true;
	}
	if ( !empty($_POST['content']) ) {
		$content = $_POST['content'];
	} else {
		$missingFieldList .= "<li>Contenu du faire part</li>";
		$missingField = true;
	}
	
	$category = $_POST['category'];
	
	if( !is_user_logged_in() ) {
		if ( !empty($_POST['usermail']) ) {
			$usermail = $_POST['usermail'];
		} else {
			$missingFieldList .= "<li>Email</li>";
			$missingField = true;
		}
		
	}
	
	// use the "public user" wordpress user profile
	$user_id = get_userdatabylogin('fairepart_user');
	$user_id = $user_id->ID;
		
	// if there is no validation error, then we save the post
	if( !$missingField ){
		// query args
		$new_post = array(
		'post_author' 	=>	$user_id,
		'post_title'	=>	$title,
		'post_content'	=>	$content,
		'post_type'		=>	'faire-part',
		'post_status'	=>	'pending'
		);
		// execute query
		$pid = wp_insert_post($new_post);
		add_post_meta( $pid, 'fairepart_cat', $category );
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
			Le faire part a &#233;t&#233; enregistr&#233;.<br/>
			Il va &#234;tre soumis &#224; relecture avant parution sur le site.
			</div>
			<?php
		}else{
		
			if( $technicalIssue ){
				?>
				<div class="frontend_publish_error">
					En raison d'un probl&#232;me technique, le faire part n'a pas &#233;t&#233; enregistr&#233;.<br/>
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
						Il est n&#233;cessaire de se <a href="<?= $loginUrl ?>">Connecter</a> ou de remplir le champ suivant :
					</div>
					
					<fieldset class="frontend_publish_author">
						<label for="usermail">Email ( ne sera pas affich&#233; ) ( obligatoire ) :</label>
						<input type="text" value="<?= $usermail ?>" tabindex="5" name="usermail"  />
					</fieldset>	
		 			<?php
		 		}else{
					// user logged
				} ?>
				<br/>
				<input type="hidden" name="action" value="new_post" />
				
				<!-- post name -->
				<fieldset class="frontend_publish_form" >
					<label for="title">Titre du faire part :</label>
					<input type="text" id="title" value="<?= $title ?>" tabindex="5" name="title" />
				</fieldset>
				
				<!-- post category -->
				<fieldset class="frontend_publish_form" >
					<label for="category">&#201;v&#232;nement :</label>
					<select name="category">
						<option value="Naissance"  >Naissance</option>
						<option value="DŽcs">D&#233;c&#232;s</option>
						<option value="Mariage">Mariage</option>
						<option value="Bar Mitzvah">Bar Mitzvah</option>
						<option value="Autre" selected >Autre</option>
					</select>
				</fieldset>
			
				
				<!-- post Content -->
				<fieldset class="frontend_publish_form">
					<label for="content">Texte du faire part :</label>
					<textarea id="" tabindex="15" name="content" cols="80" rows="10"><?= $content ?></textarea>
				</fieldset>
			
				<fieldset class="submit">
					<input type="submit" value="Soumettre le faire part" tabindex="40" id="submit" name="submit" />
				</fieldset>
			</form>
		<?php
		} ?>

</div><!-- #content -->
<?php get_sidebar(); ?>
<div class="clear"></div>
</div><!-- #container -->
<?php get_footer(); ?>