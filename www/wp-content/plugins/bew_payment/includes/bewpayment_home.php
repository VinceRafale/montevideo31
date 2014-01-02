 <link rel="stylesheet" type="text/css" href="<?php home_url(); ?>/wp-content/plugins/bew_payment/includes/mediaprint.css" media="print" />
 

<?php 
    global $wpdb;
    
    $table = get_option('bew_payment_products_table_name');  
    
    
	// if add product form has been sent
	if( !empty( $_POST['bewp_product_name'] ) ) {
	
		// delete price if it is a donation
		if( $_POST['bewp_product_type'] == bp_enum_value_donation ){
			$_POST['bewp_product_price'] = null;
			$_POST['bewp_product_reservations'] = 0;
		}
		// get checkbox cerfa value
		if( $_POST['bewp_product_cerfa'] == 'on' ){
			$_POST['bewp_product_cerfa'] = true;
		}else{
			$_POST['bewp_product_cerfa'] = false;
		}
		// get checkbox limited tickets : if not checked nb tickets = 0
		if( $_POST['bewp_reservation_trigger'] != 'on' ){
			$_POST['bewp_product_reservations'] = 0;
		}
		
		// SQL query
		$status = $wpdb->query( $wpdb->prepare("INSERT INTO $table 
		(name, type, price, cerfa, archive, group_id, tickets) VALUES ( %s, %s, %d, %d, %d, %s, %d )", 
		array( 
			$_POST['bewp_product_name'], 
			$_POST['bewp_product_type'], 
			$_POST['bewp_product_price'], 
			$_POST['bewp_product_cerfa'], 
			false, 
			$_POST['bewp_product_groupid'], 	
			$_POST['bewp_product_reservations'] ) ) 
		);
		if(!$status){
			$errors = "<strong>Erreur d'enregistrement.</strong><br/>";
		}else{
		
			$messages[] = "<strong>Produit enregistr&eacute;.</strong>";
			//$logAdd = "Produit enregistr&eacute;.";
		}
	}
	
	// if delete form has been sent
	if( !empty($_POST['archive_productId']) ) {
	
		$id = $_POST['archive_productId'];
		// SQL query
		$status = $wpdb->update( $table, array( 'archive' => true ), array( 'ID' => $id ), array( '%d'), array( '%d' ) );
		if(!$status){
			$errors = "<strong>Erreur d'enregistrement. </strong><br/>";
		}else{
			$messages[] = "<strong>Produit archiv&#233;.</strong>";
		}
		
	}
	
	// if a user modify a product 
	if( !empty( $_POST['edit_p_id'] ) ) {
		$id = $_POST['edit_p_id'];
		// get checkbox cerfa value
		if( $_POST['edit_p_cerfa'] == 'on' ){
			$_POST['edit_p_cerfa'] = true;
		}else{
			$_POST['edit_p_cerfa'] = false;
		}
		// get checkbox archive value
		if( $_POST['edit_p_archive'] == 'on' ){
			$_POST['edit_p_archive'] = true;
		}else{
			$_POST['edit_p_archive'] = false;
		}
		
		// SQL query
		$status = $wpdb->update( 
			$table, 
			array( 'name' => $_POST['edit_p_name'], 'type' => $_POST['edit_p_type'], 'price' => $_POST['edit_p_price'], 'cerfa' => $_POST['edit_p_cerfa'], 'archive' => $_POST['edit_p_archive'], 'group_id' => $_POST['edit_product_groupid'] ), 
			array( 'ID' => $id ), 
			array( '%s', '%s', '%d', '%d', '%d', '%s'), 
			array( '%d' ) );
			
		if(!$status){
			$errors = "<strong>Erreur d'enregistrement. </strong><br/>";
		}else{
			$messages[] = "<strong>Produit enregistr&eacute;.</strong>";
			
		}
		
	}
	
	// if archive
	if( !empty($_POST['update_productId']) ) {
		echo "ok";
		$id = $_POST['update_productId'];
		// SQL query
		$status = $wpdb->update( $table, array( 'archive' => true ), array( 'ID' => $id ), array( '%d'), array( '%d' ) );
		if(!$status){
			$errors = "<strong>Erreur, impossible d'archiver le produit.</strong><br/>";
		}else{
			$messages[] = "<strong>Produit archiv&eacute;.</strong>";
		}
		
	}
	
	// if Cerfa mail copy has been sent
	if( !empty($_POST['bewp_cerfa_copy']) ) {
		update_option('bew_payment_cerfa_copy', $_POST['bewp_cerfa_copy'] ); 
		$messages[] = "<strong>Modification enregistr&eacute;e.</strong>";
			
	}
	// if Cerfa mail from has been sent
	if( !empty($_POST['bewp_cerfa_from']) ) {
		update_option('bew_payment_cerfa_from', $_POST['bewp_cerfa_from'] ); 
		$messages[] = "<strong>Modification enregistr&eacute;e.</strong>";		
	}

	
?>



<!-- Container -->
<div class="wrap" style="max-width:950px !important;">
	
	<div class="icon32" id="icon-products"><br></div>
	

	<h2>Gestion des produits</h2>
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
		}
		 
		if ( isset($errors) ) { ?>
			<div class="error">
				<ul>
				<?php
				echo "<li>$errors</li>\n"; ?>
				</ul>
			</div>
		<?php 
		}?>
		
		
		
		
		<!-- Ajouter un produit -->
		<div class="postbox">
			<h3 class='hndle'>
				<span>Ajouter un produit</span>
			</h3>
			<div class="inside">
				
				<form name="bewpay_add_form" method="POST" action="#">  
        			<p>
        			<label for="bewp_product_name">Nom du produit : </label>
        			<input type="text" name="bewp_product_name">
        			</p>
        			<p>
        			<label for="bewp_product_type">Type de produit : </label>
        			<select name="bewp_product_type">
						<option value="<?php echo bp_enum_value_subscription; ?>">
							<?php echo bp_enum_value_subscription; ?>
						</option>
						<option value="<?php echo bp_enum_value_donation; ?>">
							<?php echo bp_enum_value_donation; ?>
						</option>
						<option value="<?php echo bp_enum_value_classic; ?>">
							<?php echo bp_enum_value_classic; ?>
						</option>
					</select>
					</p>
					<p>
					<label for="bewp_product_groupid">Groupe associ&eacute; : </label>
					<?php $adu = new adu_groups(); ?>
        			<select name="bewp_product_groupid">
        			<?php
					foreach ( $adu->groups as $group_id => $group_options){ ?>
						<option value="<?php echo $group_id; ?>"><?php echo $group_options['name']; ?></option>
					<?php } ?>
					</select>
					<span style="color:#CC0000; display:block;">
						Nota Bene : les utilisateurs ayant achet&#233; ce produit seront ajout&#233; dans le groupe s&#233;lectionn&#233; au terme de l'achat.
					</span>
					</p>
					<p>
					<label for="bewp_product_price">Prix du produit (laisser vide si donation) : </label>
					<input type="text" name="bewp_product_price">
					</p>  
        			<p>
        			<input type=checkbox name="bewp_product_cerfa" checked   > 
        			<label for="bewp_product_cerfa">G&#233;n&#233;ration de Cerfa ? (case coch&#233;e = oui )</label>
        			</p>
        			<p>
        			<input type=checkbox name="bewp_reservation_trigger" id="bewp_reservation_trigger"   >
        			<label for="bewp_reservation_trigger">Fixer un nombre de places ? (case coch&#233;e = oui )</label>  			
        			</p>
        			<p id="bewp_reservation_input">
        				<label for="bewp_product_reservations">
        				Rentrer le nombre de places disponibles pour cet &#233;v&#232;nement :
        				</label>
        				<input type="text" name="bewp_product_reservations">
        				( attention, cette valeur n'est pas &#233;ditable une fois le produit enregistr&#233; )
        			</p>
        			<input type="submit" name="submit" class="button-primary" value="Enregistrer" >  
    			</form>  
   				
			</div><!-- End of inside class -->
		</div>
		
		<!-- Lister et édition de produit -->
		<div class="postbox">
			<h3 class='hndle'>
				<span>Liste des produits</span>
			</h3>
			<div class="inside">
				
				<?php
				// retrieve products
				$myrows = $wpdb->get_results( "SELECT * FROM $table ORDER BY name ASC" );
			    ?>
				
				<table class="widefat" style="">
					<thead>
					<tr>
						<th scope="col">Nom du produit :</th>
						<th scope="col">Type :</th>
						<th scope="col" style="width:40px;">Prix :</th>
						<th scope="col">Cerfa ?</th>
						<th scope="col" style="width:55px;">Archive :</th>
						<th scope="col">Groupe :</th>
						<th scope="col">Places :</th>
						<th scope="col"></th>
						<th scope="col"></th>
					</tr>
					</thead>
					<tbody>
					<?php
					foreach($myrows as $product){
			    	?>
				    	<tr class="alternate">
				    		<td><?= ucfirst( stripslashes($product->name) ) ?></td>
				    		<td><?= ucfirst( $product->type ) ?></td>
				    		<td><?= $product->price ?> &#128;</td>
				    		<?php 
				    		// cerfa
					    	if( $product->cerfa == 1 ){
					    		?><td>Oui</td><?php
					    	}else{
					    		?><td>Non</td><?php
					    	}
					    	// archive
					    	if( $product->archive == 1){
					    		?><td>Archiv&#233;</td><?php
					    	}else{
					    		?><td>Actif</td><?php
					    	}
					    	// groupe associé
					    	$groups = new adu_groups();
					    	?>
				    		<td>
				    			<?= $groups->group_name($product->group_id) ?>
				    		</td>
				    		<td>
								<?php
								// nombre de places
								if( (int)$product->tickets > 0 ){
									echo $product->tickets;
								}else{
									echo "Illimit&#233;e";
								}
								?>
							</td>
				    		<td>
						    	<form name="EditBewProduct" action="#editproduct" method="POST">
						    	<input type="hidden" name="edit_productId" value="<?= $product->id; ?>" >
						    	<input type="submit" name="submit" value="Modifier">
								</form>
							</td>
						    <td>
						    	<form name="EditBewProduct" action="#" method="POST">
						    	<input type="hidden" name="archive_productId" value="<?= $product->id; ?>" >
						    	<input type="submit" name="submit"  value="Archiver">
								</form>
							</td>
						</tr>
			    	<?php
			    	}
			    	?>
				</tbody>
				</table>
				<?php
				//}
				?>
			</div><!-- End of inside class -->
		</div>

		<?
		if( !empty( $_POST['edit_productId'] ) ) {
		?>		
		<a name="editproduct"></a>
		<!-- Edit product -->
		<div class="postbox">
			
			<h3 class='hndle'>
				<span>Modifier un produit :</span>
			</h3>
			<div class="inside">
				<?php
				$id = $_POST['edit_productId'];
				$prod = $wpdb->get_row("SELECT * FROM $table WHERE id = '".$id."' ");
				?>
				<table class="widefat" style="">
				<thead>
				<tr>
					<th scope="col">Nom du produit :</th>
					<th scope="col">Type de produit :</th>
					<th scope="col" >Prix :</th>
					<th scope="col">Cerfa ?</th>
					<th scope="col" style="width:58px;">Archiv&#233; ?</th>
					<th scope="col">Groupe :</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<form name="ModifyBewProduct" action="#" method="POST">
					<input type="hidden" name="edit_p_id" value="<?php echo $prod->id; ?>" >
					<td>
						<input type=text name="edit_p_name" value="<?php echo stripslashes($prod->name); ?>"  >
					</td>
					<td>
						<select name="edit_p_type" >
						<option value="<?php echo bp_enum_value_subscription; ?>" <?php if( $prod->type == bp_enum_value_subscription ){ echo "selected"; } ?> >
								<?php echo bp_enum_value_subscription; ?>
						</option>
						<option value="<?php echo bp_enum_value_donation; ?>" <?php if( $prod->type == bp_enum_value_donation ){ echo "selected"; } ?> >
								<?php echo bp_enum_value_donation; ?>
						</option>
						<option value="<?php echo bp_enum_value_classic; ?>" <?php if( $prod->type == bp_enum_value_classic ){ echo "selected"; } ?> >
								<?php echo bp_enum_value_classic; ?>
						</option>
						</select>
					</td>
					<td>
						<input type=text name="edit_p_price" value="<?php echo $prod->price; ?>" style="width:45px;"  >
					</td>
					<td>
						<input type=checkbox name="edit_p_cerfa" <?php if( $prod->cerfa ){ echo "checked"; } ?> >
					</td>
					<td>
						<input type=checkbox name="edit_p_archive" <?php if( $prod->archive ){ echo "checked"; } ?> >
					</td>
					<td>
						<?php $adu = new adu_groups(); ?>
	        			<select name="edit_product_groupid">
	        			<?php
						foreach ( $adu->groups as $group_id => $group_options){ ?>
							<option value="<?php echo $group_id; ?>"><?php echo $group_options['name']; ?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" name="submit" value="Enregistrer les modifications">
					</td>
				</tr>
				</form>
				</tbody>
				</table>
				
												
			</div><!-- End of inside class -->
		</div>

		<?php
		}
		?>


		



		<!-- Réservations -->
		<div class="postbox">
			<h3 class='hndle'>
				<span>R&#233;servations</span>
			</h3>
			<div class="inside">
				
				<?php 
				$table_booking = get_option('bew_payment_reservations');  
				$table_products = get_option('bew_payment_products_table_name');  
			    // retrieve list of products which have a booking
			    $myrows = $wpdb->get_results( "SELECT 
				    b.id,
				    b.name
				    FROM $table_booking a, $table_products b  
				    WHERE a.product_id = b.id
				    GROUP BY b.id 
				    ORDER BY b.name ASC " );
			    
			    if( $myrows == NULL ){
			    	?>Il n'y a aucune r&#233;servation enregistr&#233;e<?php
			    }else{
			    	?>
			    	<p>
			    		Cliquer sur le nom d'un produit pour voir les r&#233;servations :
					</p>
					<ul>
					<?php 
					foreach( $myrows as $product){
						?>
						<li><a href="#" rel="#ov<?= $product->id ?>" class="booking_overlay_trigger">
							<?= ucfirst( $product->name ) ?>
						</a>
						</li>
						<div class="r_overlay" id="ov<?= $product->id ?>" >
						
							<span class="r_overlay_title" >R&#233;servations pour : <?= ucfirst( $product->name ) ?></span>
							<a href="#" class="r_overlay_print" id="ov<?= $product->id ?>" >Cliquer ici pour imprimer cette liste</a>
							<br/>
							<br/>
							
							<table class="widefat" style="">
							<thead>
							<tr>
								<th scope="col">Nom</th>
								<th scope="col">Email</th>
								<th scope="col">Nombre de places r&#233;serv&#233;es</th>
							</tr>
							</thead>
							<tbody>
							<?php
							$productId = $product->id;
							$myrows = $wpdb->get_results( "SELECT 
								a.tickets,
								a.name,
								a.email
							    FROM $table_booking a, $table_products b  
							    WHERE a.product_id = b.id
							    AND a.product_id = $productId
							 " );
					  
							foreach( $myrows as $book){
								?>
								<tr>
									<td><?= $book->name ?></td>
									<td><?= $book->email ?></td>
									<td><?= $book->tickets ?></td>
								</tr>
								<?php
							}
							?>
							</tbody>
							</table>
						</div>
						<?php
					}
					?>
					</ul>
					
					
				<?php
			    }// end of if no reservation
			    ?>
								
			</div><!-- End of inside class -->
		</div>

		<!-- Email de contact Cerfa -->
		<div class="postbox">
			<h3 class='hndle'>
				<span>Adresses Email</span>
			</h3>
			<div class="inside">
				
				<form name="SetCerfaMail" action="#" method="POST" >
    			<label for="bewp_cerfa_copy">Adresse E-mail a laquelle sont envoy&#233; une copie des Paiements : </label>
				<?php
				$mailCerfa = get_option('bew_payment_cerfa_copy');  
    			if(isset($mailCerfa)){
    				?><input type="text" name="bewp_cerfa_copy" value="<?= $mailCerfa ?>" ><?php
    			}else{ 
    				?><input type="text" name="bewp_cerfa_copy" ><?php
    			}
				?>
				<input type="submit" name="submit" value="Modifier">
   				</form>
				
				<form name="SetCerfaMail" action="#" method="POST" >
    			<label for="bewp_cerfa_from">Adresse E-mail depuis laquelle sont envoy&#233;s les Email de Cerfa : </label>
				<?php
				$mailCerfa = get_option('bew_payment_cerfa_from');  
    			if(isset($mailCerfa)){
    				?><input type="text" name="bewp_cerfa_from" value="<?= $mailCerfa ?>" ><?php
    			}else{ 
    				?><input type="text" name="bewp_cerfa_from" ><?php
    			}
				?>
				<input type="submit" name="submit" value="Modifier">
   				</form>
				
												
			</div><!-- End of inside class -->
		</div>

		<!-- Log des paiements -->
		<div class="postbox">
			<h3 class='hndle'>
				<span>Log des paiements</span>
			</h3>
			<div class="inside">
				
				<?php
    			$table_logs = get_option('bew_payment_logs_table_name');  
    			$table_products = get_option('bew_payment_products_table_name');  
    			$table_users = $wpdb->prefix."users";
    			$myrows = $wpdb->get_results( "SELECT 
				    a.payment_date ,
				    a.atos_customer_id ,
				    a.atos_transaction_id ,
				    a.amount,
				    b.name,
				    c.display_name,
				    c.user_email
				    FROM $table_logs a, $table_products b, $table_users c  
				    WHERE a.product_id = b.id AND a.user_id = c.ID
				    ORDER BY payment_date DESC" );
				?>
				<div style="max-height:600px; overflow-y:auto;">
					<table class="widefat" >
					<thead>
					<tr>
						<th scope="col">Date</th>
						<th scope="col">Identifiant Client ATOS</th>
						<th scope="col">Identifiant Transaction ATOS</th>
						<th scope="col">Montant</th>
						<th scope="col">Nom du produit</th>
						<th scope="col">Nom du client</th>
						<th scope="col">Email du client</th>
					</tr>
					</thead>
					<tbody>
					<?php
					foreach($myrows as $log){ ?>
	    				<tr>
	    					<td><?= $log->payment_date ?></td>
							<td><?= $log->atos_customer_id ?></td>
							<td><?= $log->atos_transaction_id ?></td>
							<td><?= $log->amount ?> &#128;</td>
							<td><?= ucfirst( $log->name ) ?></td>
							<td><?= ucfirst( $log->display_name ) ?></td>
							<td><?= $log->user_email ?></td>
						</tr>
	    			<?php
	    			} ?>
					
					
					
					</tbody>
					</table>
				</div>
				
				<div class="even_pagination_navigation"></div>				
											
			</div><!-- End of inside class -->
		</div>
		
		
		
		
		
		
		
		

		
		
		
		
	</div><!-- End of poststuff (style container) -->
</div><!-- End of container -->

<script type="text/javascript">
jQuery(document).ready(function() {
	
	// add a product : hide booking input by default
	jQuery('#bewp_reservation_input').hide();
	
	
	// show / hide 
	jQuery('#bewp_reservation_trigger').click (function (){
		
		var thisCheck = jQuery(this);
		if (thisCheck.is(':checked')){
		
			jQuery('#bewp_reservation_input').show();
			
		}else{
			jQuery('#bewp_reservation_input').hide();
		}
	
	});
	
	

	
	
	// overlay for bookings
	jQuery('.booking_overlay_trigger[rel]').overlay({
		left:'left'
	});
		
	// Reservation print link
	jQuery('.r_overlay_print').click (function (){
			
		id = jQuery(this).attr("id");
	//	jQuery(".r_overlay#"+id).printElement({printMode:'popup'});
		
		jQuery(".r_overlay#"+id).jqprint({ 
			debug:true
		});
		
		//('#divOpera').jqprint({ operaSupport: true });
		
	});	

});
</script>