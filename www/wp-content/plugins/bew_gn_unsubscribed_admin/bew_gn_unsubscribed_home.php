<!-- Container -->
<div class="wrap" style="max-width:950px !important;">
	
	<h2>Utilisateurs désinscrits du site</h2>
	<div id="poststuff" style="margin-top:10px;">
		<?php 
		$wp_user_search = new WP_User_Query( array( 'meta_key' => 'unsubscribed_site', 'meta_value' => 1, 'fields' => 'all_with_meta' ) );
		$unsubscribed = $wp_user_search->get_results();
		if( empty($unsubscribed)){
		?>
			<div class="error">
				<strong>Il n'y a aucun utilisateur désinscrit du site.</strong>
			</div>
		<?php
		}else{
			?>
			<div class="postbox">
				<h3 class='hndle'>
					<span>Liste</span>
				</h3>
				<div class="inside">
					
					<table class="widefat" >
					<thead>
					<tr>
						<th scope="col">Pseudonyme</th>
						<th scope="col">Email</th>
					</tr>
					</thead>
					<tbody>
					<?php
					foreach( $unsubscribed as $user_unsub ){ ?>
						<tr>
	    					<td><?= $user_unsub->display_name ?></td>
							<td><?= $user_unsub->user_email ?></td>
						</tr>
	    			<?php
	    			} ?>
					</tbody>
					</table>
					
				</div><!-- End of inside class -->
			</div>
		<?php } ?>	
	</div><!-- End of poststuff (style container) -->
</div><!-- End of container -->

