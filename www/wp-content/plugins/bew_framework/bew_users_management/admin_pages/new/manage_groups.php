<div class="wrap" style="max-width:950px !important;">
	<div class="icon32" id="icon-users"><br></div>
	<h2>Gestion des Groupes</h2>
	<div id="poststuff" style="margin-top:10px;">
		
		<?		
		global $wpdb;
		$adu_groups = get_option('adu_groups');
		$adu = new adu_groups();
		$displaylist = true;
		
		// create arrays for group users (count, ids)
		foreach( $adu->groups as $key => $value ){
			$usersInGroup[$key] = array();
			$countInGroup[$key] = 0;
		}
		
		// retrieve user's id and groups meta value
		$query =  "SELECT meta_value, user_id FROM $wpdb->usermeta WHERE meta_key = 'adu_groups' ";
		$allUsers = $wpdb->get_results( $query, ARRAY_A );
		
		// loop on each user
		foreach ( $allUsers as $user ){
			// unserialize user's groups
			$user_groups = unserialize($user['meta_value']);
			
			// foreach group, update group's arrays
			foreach( $user_groups as $key => $value ){
				$countInGroup[$value] = $countInGroup[$value] + 1 ;
		    	array_push( $usersInGroup[$value], $user['user_id']  );
		    }
		} 
		
		// page controller : handle form requests
		switch($_REQUEST['action']){ 
			
			case 'new_group':
				$new_group = trim($_REQUEST['new_group']);
				if(empty($new_group)){
					$errors = new WP_Error(
						'adu_missing_new_group_name', 
						__('Veuillez indiquer le nom du nouveau groupe.'));
				}else{
					$result = $adu->create($_REQUEST['new_group']);
					if(is_wp_error($result))   
						$errors = $result;
					else   
						$messages[] = "<strong>".__('Le nouveau groupe a bien été créé.')."</strong>";
				}
			break;
			
			case 'rename':
				
				if(isset($_GET['group_id']) && isset($adu_groups[$_GET['group_id']]) && $_GET['group_id'] != 'all_users'){
					if(isset($_GET['new_name'])){
						$displaylist = true;
						$result = $adu->rename($_GET['group_id'], $_GET['new_name']);
						if(is_wp_error($result))   
							$errors = $result;
							
						else   
							$messages[] = "<strong>".__('Le groupe a bien été renomé.')."</strong>";
						}else{
						$displaylist = false;
						?>
		                <!-- Renommer un groupe -->
						<div class="postbox">
							<h3 class='hndle'>
								<span>Renommer le groupe "<?= $adu->group_name($_GET['group_id']) ?>" </span>
							</h3>
							<div class="inside">
								<form action="" method="get">
								<input type="hidden" name="page" value="users_groups"/>
		                    	<input type="hidden" name="action" value="rename"/>
		                    	<input type="hidden" name="group_id" value="<?= $_GET['group_id'] ?>"/>
			                	<label for="new_name">Nouveau nom : </label>
			                	<input type="text" name="new_name" value="" />
			                	<input type="submit" name="submit" value="Renommer">
		                    	</form>
							</div><!-- End of inside class -->
						</div>
					<?php
					}
				}else{
					echo "Erreur sur la page.";
				}
			break;
			
			case 'delete':
				
				if(isset($_GET['group_id']) && isset($adu_groups[$_GET['group_id']]) && $_GET['group_id'] != 'all_users'){
					if(isset($_GET['confirm'])){
						$result = $adu->delete($_GET['group_id']);
						$displaylist = true;
						if(is_wp_error($result))   
							$errors = $result;
						else   
							$messages[] = "<strong>".__('Le groupe a bien été supprimé.')."</strong>";
					}else{
						$displaylist = false;
						?>
						<!-- Supprimer un groupe -->
						<div class="postbox">
							<h3 class='hndle'>
								<span>Supprimer le groupe "<?= $adu->group_name($_GET['group_id']) ?>" </span>
							</h3>
							<div class="inside">
								<form action="" method="get">
								<input type="hidden" name="page" value="users_groups"/>
		                   	 	<input type="hidden" name="confirm" value="1"/>
		                    	<input type="hidden" name="action" value="delete"/>
		                    	<input type="hidden" name="group_id" value="<?php echo $_GET['group_id'] ?>"/>
								<p>Voulez-vous vraiment supprimer le groupe "<?= $adu->group_name($_GET['group_id']) ?>" ?
								<p/>
								<p>Attention, cette action est irréversible.</p>
								<p>Les utilisateurs de ce groupe ne seront PAS supprimés.</p> 
								</label>
								<br/>
								<input type="submit" name="submit" value="Confirmer la suppression">
		                    	</form>
							</div><!-- End of inside class -->
						</div>
		                <?php
					}
				
				}
			break;
			
			case 'visualise':
				
				if(isset($_GET['group_id']) && isset($adu_groups[$_GET['group_id']]) ){
					$displaylist = false;
					?>
		               <!-- Utilisateurs  d'un groupe -->
						<div class="postbox">
							<h3 class='hndle'>
								<span>Utilisateurs du groupe "<?= $adu->group_name($_GET['group_id']) ?>" </span>
							</h3>
							<div class="inside">
								<table class="widefat" >
								<thead>
								<tr>
									<th scope="col">Email :</th>
									<th scope="col">Nom :</th>
									<th scope="col">Prénom :</th>
									<th scope="col">Pseudonyme :</th>
									<th scope="col">Page profil :</th>
								</tr>
								</thead>
								<tbody>
								<?php 
								foreach( $usersInGroup[$_GET['group_id']] as $key => $value ){ 
									$user = get_userdata((int)$value);
									?>
									<tr>
										<td><?= $user->user_email ?></td>
										<td><?= $user->user_lastname ?></td>
										<td><?= $user->user_firstname ?></td>
										<td><?= $user->user_nicename ?></td>
										<td><a href="/wp-admin/user-edit.php?user_id=<?= $user->ID ?>" >lien »</a></td>
									</tr>
								<?php } ?>
								</tbody>
								</table>
								
								<p><a href="/wp-admin/users.php?page=users_groups">« Revenir à la page précédente</a></p>
							
							</div><!-- End of inside class -->
							
							
						</div>
				<?php
				}else{
					echo "Erreur sur la page.";
				}
			break;

			
			
			
			
			
		}  //endswitch; ?>
		
		<!-- Display log -->
		<?php 
		if ( !empty($messages) ){ ?>
		<div id="message" class="updated">
			<?php
			foreach ( $messages as $msg )
				echo "<p>$msg</p>"; ?>
	    </div>
		<?php 
		}
		 
		if ( isset($errors) && is_wp_error( $errors ) ) { ?>
			<div class="error">
				<ul>
				<?php
				foreach ( $errors->get_error_messages() as $err )
					echo "<li>$err</li>\n"; ?>
				</ul>
			</div>
		<?php 
		}

		
		// Display list of groups
		if( $displaylist){
		?>
			<!-- Ajouter un nouveau groupe -->
			<div class="postbox">
				<h3 class='hndle'>
					<span>Création d'un nouveau groupe</span>
				</h3>
				<div class="inside">
					<form action="" method="post">
					<input type="hidden" name="action" value="new_group"/>
					<label for="new_group">Nom du groupe : </label>
					<input type="text" name="new_group" value="" />
					<input type="submit" name="submit" value="Créer">
	   				</form>
				</div><!-- End of inside class -->
			</div>
	
			<!-- Liste des groupes -->
			<div class="postbox">
				<h3 class='hndle'>
					<span>Liste des groupes</span>
				</h3>
				<div class="inside">
					
					<?php
					
					?>
					<table class="widefat" >
						<thead>
						<tr>
							<th scope="col">Nom du groupe :</th>
							<th scope="col">Utilisateurs :</th>
							<th scope="col"></th>
							<th scope="col"></th>
							<th scope="col"></th>
						</tr>
						</thead>
						<tbody>
						<?php
						// loop on each group
						foreach( $adu->groups as $key => $value ){ ?>
				    	<tr class="alternate">
				    		<td><?= ucfirst( $value['name'] ) ?></td>
				    		<td><?= $countInGroup[$key] ?> Utilisateurs</td>
				    		<td>
				    			<?php
				    			if( $key != "all_users" ){
				    				$ulink = "/wp-admin/users.php?page=users_groups&action=visualise&group_id=$key";
				    			}else{
				    				$ulink = "/wp-admin/users.php";
				    			} ?>
				    			
				    			<span class='edit'>
					    		<a href="<?= $ulink ?>">
					    		Visualiser
					    		</a>
					    		</span>
					    	</td>
				    		<td>
				    			<?php
				    			if( $key != "all_users" ){ ?>
				    				<span class='edit'>
					    			<a href="/wp-admin/users.php?page=users_groups&action=rename&group_id=<?= $key ?>">
					    			Renommer
					    			</a>
					    			</span>
				    			<?php } ?>
				    		</td>
				    <!-- 		<td>
				    			<?php
				    			if( $key != "all_users" ){ ?>
				    				<span class='edit'>
					    			<a href="/wp-admin/users.php?page=users_groups&action=delete&group_id=<?= $key ?>">
					    			Supprimer
					    			</a>
					    			</span>
				    			<?php } ?>
				    		</td>
				    		
				    		
				    		 -->
				    		
				    	</tr>
				    	<?php
				    	} ?>
					</tbody>
					</table>
	
					
				</div><!-- End of inside class -->
			</div>

		<?php
		}
		?>	
	</div><!-- End of poststuff (style container) -->
</div><!-- End of container -->