<h3>Utilisateurs désinscrits du site :</h3>
<?php 

// query unsubscribed users
$wp_user_search = new WP_User_Query( array( 'meta_key' => 'unsubscribed_site', 'meta_value' => 1, 'fields' => 'all_with_meta' ) );
$unsubscribed = $wp_user_search->get_results();

if( empty($unsubscribed)){

	echo "Il n'y a aucun utilisateur désinscrit du site.";

}else{
	?>
	<table id="unsubscribed_users_table">
		<tr>
			<th>Pseudonyme</th>
			<th>Email</th>
		</tr>
	<?php
	foreach( $unsubscribed as $user_unsub ){
	?>
		<tr>
			<td><?= $user_unsub->display_name ?></td>
			<td><?= $user_unsub->user_email ?></td>
		</tr>
	
	<?php
	}
	?>
	</table>
	<?php

}


?>

<style type="text/css">
	#unsubscribed_users_table{
		border:gray 1px solid;
		border-collapse:collapse;
		text-align:left;
		padding:5px;
	}
	
	#unsubscribed_users_table tr, #unsubscribed_users_table td{
		border:gray 1px solid;
		padding:5px;
	}	
	
	#unsubscribed_users_table th{
		border:gray 1px solid;
		padding:5px;
		color:white;
		background-color: #0086CB;
		font-weight: normal;
		font-size: 13px;
		text-align: center;
	}
	
	
	
</style>