<?php
/*
Template Name: Inscription aux alertes SMS depuis sidebar 
*/


// Page display
get_header();

global $current_user;


$saved = false;
$technicalIssue = false;
$state = false;
$errorMessage = false;
// if form submited
if( !empty($_POST['bew_user_adu_user_mobile']) ) {
	
	$tel_nb = $_POST['bew_user_adu_user_mobile'];
	// is user logged ?
	if( is_user_logged_in() ) {
		get_currentuserinfo();	
		$state = update_user_meta( $current_user->ID, 'adu_user_mobile', $tel_nb );
		if( $state ){
			$saved = true;
		}else{
			$technicalIssue = true;
		}
		
	}else{
		// user not logged : redirect to connexion page and save telephon nb in session
		session_start();
		$_SESSION['alert_sms'] = $tel_nb;
		// redirect to login
		$loginUrl = get_ID_by_slug('connexion');
		header('Location: '.$loginUrl);
	}
	
}elseif( isset($_SESSION['alert_sms']) ){
	get_currentuserinfo();	
	$state = update_user_meta( $current_user->ID, 'adu_user_mobile', $_SESSION['alert_sms'] );
	if( $state ){
		$saved = true;
	}else{
		$technicalIssue = true;
	}
}else{
	$errorMessage = true;
}





?>

<div id="container">
	<div id="content" role="main">
	
		<?php
		if( $state ){
			?>
			<div class="frontend_publish_success">
			Votre num&#233;ro a &#233;t&#233; enregistr&#233;.<br/>
			Vous pour d&#233;sinscrire de ce service, vous pouvez soit envoyer le message "Stop" au num&#233;ro &#233;metteur du message, ou bien aller dans votre page profil.
			</div>
			<?php
		}
			
		if( $technicalIssue ){
			?>
			<div class="frontend_publish_error">
					En raison d'un probl&#232;me technique, votre num&#233;ro n'a pas &#233;t&#233; enregistr&#233;.<br/>
					Merci de contacter l'administrateur du site.
			</div>
			<?php
		}
		if( $errorMessage ){
			?>
			<div class="frontend_publish_error">
					Vous n'avez pas le droit d'acc&#233;der directement ˆ cette page.
			</div>
			<?php
		}
		?>
		
</div><!-- #content -->
<?php get_sidebar(); ?>
<div class="clear"></div>
</div><!-- #container -->
<?php get_footer(); ?>