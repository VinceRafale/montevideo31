<?php
/*
Template Name: Desinscription d'un service ou du site
*/

global $current_user;

// Page display
get_header();
?>

<div id="container">
	<div id="content" role="main">
	
	<?php
	// Filter access to this page
	if( ($_POST['unsubscribe_form_action'] != "unsubscribe")||(!is_user_logged_in()) ){
		?>
		<div class="frontend_publish_error">
			Vous n'avez pas l'autorisation d'acc&#233;der directement &#224; cette page.
		</div>
		<?php
	}else{
		
		// get user infos
		get_currentuserinfo();
		
		// IF UNSUBSCRIBE FROM SITE
		if( $_POST['unsubscribe_from_site'] ){
			
			// unsubscribe sms
			if( !get_user_meta($current_user->ID, 'unsubscribed_sms', true) ){
				$smsState = update_user_meta( $current_user->ID, 'unsubscribed_sms', true );
				if( !$smsState ){
					?>
					<div class="frontend_publish_error">
						En raison d'un probl&#232;me technique, nous n'avons pas &#233;t&#233; en mesure de vous d&#233;sinscrire des Alertes SMS.
						<br/>
						Merci de contacter l'administrateur du site.
					</div>
					<?php
				} // end of if on smsState
			}
			
			// --------------
			// unsubscribe NEWSLETTER
			// --------------
			
			
			// unsubscribe from site
			if( !get_user_meta($current_user->ID, 'unsubscribed_site') ){
				$siteState = update_user_meta( $current_user->ID, 'unsubscribed_site', true );
				if( !$siteState ){
					?>
					<div class="frontend_publish_error">
						En raison d'un probl&#232;me technique, nous n'avons pas &#233;t&#233; en mesure de vous d&#233;sinscrire du site.
						<br/>
						Merci de contacter l'administrateur du site.
					</div>
					<?php
				} // end of if on siteState
			}
			
			?>
			<a href="<?php echo wp_logout_url( get_bloginfo('url') ); ?>" title="Logout">Cliquez ici pour vous d&#233;connecter</a>
			<?php			
		}
		
		
		// IF UNSUBSCRIBE FROM SMS
		if( $_POST['unsubscribe_from_sms'] ){
			
			$existingMeta = get_user_meta($current_user->ID, 'unsubscribed_sms', true);
			// test if the user is already ...
			if( $existingMeta ){
				// user is already unsubscribed from this service
				?>
				<div class="frontend_publish_success">
				Vous &#234;tes d&#233;j&#224; d&#233;sabonn&#233; des alertes SMS.
				</div>
				<?php
			}else{
				// unsubscribe the user from the service
				$smsState = update_user_meta( $current_user->ID, 'unsubscribed_sms', true);
				if( $smsState ){
					?>
					<div class="frontend_publish_success">
					Vous &#234;tes maintenant d&#233;sabonn&#233; aux alertes SMS.
					</div>
					<?php
				}else{
					?>
					<div class="frontend_publish_error">
						En raison d'un probl&#232;me technique, nous n'avons pas &#233;t&#233; en mesure de vous d&#233;sinscrire des Alertes SMS.
						<br/>
						Merci de contacter l'administrateur du site.
					</div>
					<?php
				} // end of if on smsState
			} // end of if on already unsubscribed
		}
		
		// IF UNSUBSCRIBE FROM NEWSLETTER
		if( $_POST['unsubscribe_from_newsletter'] ){
		
			echo "out of news";
			
		}
		
		
	}
	?>
	</div><!-- #content -->
<?php get_sidebar(); ?>
<div class="clear"></div>
</div><!-- #container -->
<?php get_footer(); ?>