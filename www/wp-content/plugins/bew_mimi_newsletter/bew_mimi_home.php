<?php 
	global $wpdb;
	
	// mad mimi ids
	$bm_user['username'] = "compta@montevideo31.com";
	$bm_user['api'] = "dd7a8d47843fb93ad47b523e1b0d5e75";
	require('bew_mimi_helpers.php');
   	
   	$groupList = array();
   	
   	// Create WP groups arrays
   	$adu = new adu_groups();
   	foreach( $adu->groups as $key => $value ){			
   		$usersInGroup[$key] = array();
		$countInGroup[$key] = 0;
		// fill the array which contains groups ids	
		$groupList[] = $key;
	}
	
   	// Retrieve user's groups and email
   	$query =  "SELECT a.meta_value, b.user_email FROM $wpdb->usermeta a, $wpdb->users b WHERE a.meta_key = 'adu_groups' AND b.ID = a.user_id";
	$allUsers = $wpdb->get_results( $query, ARRAY_A );

	// Fill groups array with user data
	foreach ( $allUsers as $user ){
		// unserialize user's groups
		$user_groups = unserialize($user['meta_value']);
		// foreach group, update group's arrays
		foreach( $user_groups as $key => $value ){
			$countInGroup[$value] = $countInGroup[$value] + 1 ;
	    	array_push( $usersInGroup[$value], $user['user_email']  );
	    }
	} 
	
	// display var 
	$step1 = true;
	$step2 = false;
	
	
	// Page controller : handle form requests
	switch($_REQUEST['action']){ 
		
		case 'synchro':
			
			// retrieve mad mimi lists
			$bm_lists = bewmimi_retrieve_lists($bm_user);
				
			// init error var
			$synchro_error = false;
			
			// loop on wordpress groups 
			foreach( $groupList as $group   ){
				
				// log
				$messages[] = "<p><strong>Groupe : ".$group."</strong>";
				
				// check if there is a corresponding madmimi list
				if( in_array($group, $bm_lists) ){
					$messages[] = "Liste correspondante trouv&#233;e";
				}else{ 
	
					$messages[] = "Pas de liste correspondante : cr&#233;ation d'une nouvelle liste";
					// create new list
					$state = bewmimi_add_list($bm_user, $group );
					if( !$state ){
						$messages[] = "Probl&#232;	me : Impossible de synchroniser avec Madmimi";
						$synchro_error = true;
						continue;
					}
				}
				
				// update users
				$state = bewmimi_add_group_to_mimi( $usersInGroup[$group], $bm_user, $group );
				if( !$state ){
					$messages[] = "La synchronisation des utilisateurs pour ce groupe a &#233;chou&#233;.";
					$synchro_error = true;
				}else{
					$messages[] = "Synchronisation effectu&#233;e.";
				}
				
				
				// delete unsubscribed users from all lists, just in case
				$wp_user_search = new WP_User_Query( array( 'meta_key' => 'unsubscribed_site', 'meta_value' => 1, 'fields' => 'all_with_meta' ) );
				$unsubscribed = $wp_user_search->get_results();
				foreach( $unsubscribed as $user_unsub ){
					$state = bewmimi_remove_unsubscribed_user( $user_unsub->user_email, $bm_user);
					if( !$state ){
						$synchro_error = true;
						$messages[] = "La synchronisation des utilisateurs désinscris a &#233;chou&#233;.";
					}
	    		}
				
				// log
				$messages[] = "</p>";
			}
			
			// update display vars
			$step1 = false;
			$step2 = true;
			
		break;
		
	}  //endswitch; 
?>

<!-- Container -->
<div class="wrap" style="max-width:950px !important;">
	
	<div class="icon32" style="background:transparent url(../wp-content/plugins/bew_mimi_newsletter/icon_mimi_big.png) no-repeat;"><br></div>
	

	<h2>Newsletter</h2>
	<div id="poststuff" style="margin-top:10px;">
		
		<!-- Display log -->
		<?php 
		if ( !empty($messages) ){ ?>
			<div id="message" class="updated">
				<?php
				foreach ( $messages as $msg )
					echo "<p>$msg</p>"; ?>
	    	</div>
	    	<?php
	   	 	if( $synchro_error ){
	    		?>
	    		<div class="error">
	    			<p>
	    			<strong>La synchronisation d'un ou plusieurs groupes d'utilisateurs a &#233;chou&#233;.</strong>
	    			<br/>
	    			Merci de reporter cette erreur au support technique.
	    			</p>
	    		</div>
	    		<?php
	    	}
	    	 
		}
		?> 
		
		
		
		
		<?php
		if( $step1 ){
		?>
		<!-- Ajouter un produit -->
		<div class="postbox">
			<h3 class='hndle'>
				<span>Synchroniser les groupes d'utilisateurs</span>
			</h3>
			<div class="inside">
				
				Cliquer <a href="/wp-admin/admin.php?page=bew_mimi_menu&action=synchro">ici</a> pour synchroniser les groupes d'utilisateur avec le service Madmimi.
			</div><!-- End of inside class -->
		</div>
		<?php
		}
		?>
		
		<?php 
		if ($step2){
		?>
		<!-- Rédiger la newsletter -->
		<div class="postbox">
			<h3 class='hndle'>
				<span>R&#233;daction de la newsletter</span>
			</h3>
			<div class="inside">
			
				Cliquer sur le lien suivant pour acc&#233;der au service Madmimi :
				
				<a target="_blank" href="http://madmimi.com/" >madmimi >></a>
				
			</div><!-- End of inside class -->
		</div>

		
		<?php
		}
		?>
				
		
	</div><!-- End of poststuff (style container) -->
</div><!-- End of container -->
