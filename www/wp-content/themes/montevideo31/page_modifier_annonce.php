<?php
/*
Template Name: Modifier une petite annonce 
*/

global $current_user;

// if user is logged, get user infos
if( is_user_logged_in() ) {
	get_currentuserinfo();	
}

$missingField = false;
$saved = false;
$technicalIssue = false;

if( !empty($_POST['edit_annonce_user_id']) ){
	
	$annonceUserId = $_POST['edit_annonce_user_id'];
	$annonceID = $_POST['edit_annonce_id'];
	$annonceTitle = $_POST['edit_annonce_title'];
	$annoncePrice = $_POST['edit_annonce_price'];
	$annonceContent = $_POST['edit_annonce_content'];
	
}

// if form submited
if( !empty( $_POST['action'] ) &&  $_POST['action'] == "new_post") {
	
	
	$annonceUserId = $current_user->ID;
	$annonceId = $_POST['annonce_id'];
	
	$missingFieldList = "<ul>";
	
	// validation
	if ( !empty($_POST['title']) ) {
		$title =  $_POST['title'];
	} else {
		$missingFieldList .= "<li>Titre de l'annonce</li>";
		$missingField = true;
	}
	if ( !empty($_POST['frontpostcontent']) ) {
		$content = $_POST['frontpostcontent'];
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
		/*
		$new_post = array(
		'ID'			=>	$_POST['annonce_id'],
		'post_author' 	=>	$user_id,
		'post_title'	=>	$title,
		'post_content'	=>	$content,
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
			update_post_meta( $pid, 'annonce_prix', $price );
		}
		
		*/
		$my_post = array();
  		$my_post['ID'] = $_POST['annonce_id'];
  		$my_post['post_content'] = $content;
  		$my_post['post_title'] = $title;
  		$my_post['post_status'] = 'pending';
		$pid = wp_update_post( $my_post );
		// handle query result
		if( !$pid ){
			// post not saved
			$saved = false;
			$technicalIssue = true;	
		}else{
			// post saved
			$saved = true;
			update_post_meta( $pid, 'annonce_prix', $price );
		}
		
	}else{
		// format the missig field string to display error
		$missingFieldList .= "</ul>";
	}
	
	
} 


// Page display
get_header();
?>

<script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/js/ckeditor/ckeditor.js"></script>

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
			 
				<?php
				// echo login status
				if( !is_user_logged_in() ) {
		 			// user not logged
		 			?>
					<div class="frontend_publish_error">
						Vous n'avez pas l'autorisation d'accéder à cette page sans être connecté au site.
					</div>
					<?php
		 		}else{
					// user logged 
					// test if the logged user comes from the right item
					if( $annonceUserId == $current_user->ID ){
						?>
						<div class="frontend_publish_info">
							L'email de contact affich&#233; sera : <?= $current_user->user_email ?>.
						</div>
					
						<br/>
						<form  method="post" action="#" enctype="multipart/form-data">
						<input type="hidden" name="action" value="new_post" />
						<input type="hidden" name="annonce_id" value="<?= $annonceID ?>" />
						
						<!-- post name -->
						<fieldset class="frontend_publish_form" >
							<label for="title">Titre de l'annonce :</label>
							<input type="text" id="title" value="<?= $annonceTitle ?>" tabindex="5" name="title" />
						</fieldset>
						
						<!-- price -->
						<fieldset class="frontend_publish_form" >
							<label for="title">Prix (en euros) :</label>
							<input type="text"  value="<?= $annoncePrice ?>" tabindex="5" name="price" />
						</fieldset>
					
						
						<!-- post Content -->
						<fieldset class="frontend_publish_form">
							<label for="frontpostcontent">Contenu de l'annonce :</label>
							<textarea id="" tabindex="15" name="frontpostcontent" cols="80" rows="10"><?= stripslashes($annonceContent) ?></textarea>
						</fieldset>
					
						<fieldset class="submit">
							<input type="submit" value="Modifier l'annonce" tabindex="40" id="submit" name="submit" />
						</fieldset>
						</form>

					<?php 

					}else{
						?>
						<div class="frontend_publish_error">
							Vous n'avez pas l'autorisation d'accéder à cette page.
						</div>
						<?php
					}
					
					
									
									
									
				} 
		} ?>

</div><!-- #content -->
<?php get_sidebar(); ?>
<div class="clear"></div>
</div><!-- #container -->


<script type="text/javascript">
//<![CDATA[
	CKEDITOR.replace( 'frontpostcontent',
	{
		toolbar :
		[
			{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike' ] },
			{ name: 'clipboard', items : [ 'Paste','PasteText','PasteFromWord'] },
			{ name: 'paragraph', items : [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter','JustifyRight','JustifyBlock','-' ] }	
		]
	});
//]]>
</script>




<?php get_footer(); ?>