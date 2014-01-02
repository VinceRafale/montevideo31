<style type="text/css">
	#BewPaymentAdd,#BewPaymentList,#BewPaymentCerfa, #BewPaymentLog{
		border:gray 2px solid;
		margin:10px;
		padding: 0 10px 10px 10px;
		-webkit-border-radius: 10px;
		-moz-border-radius: 10px;
		border-radius: 10px;
	}
	
	#bewPaymentLogTableContainer{
		height:400px;
		overflow-y: scroll;
		width:840px;
	}
	
	.ListProductsTable{
		border:gray 1px solid;
		border-collapse:collapse;
		text-align:left;
		padding:5px;
	}
	
	.ListProductsTable tr, .ListProductsTable td{
		border:gray 1px solid;
		padding:5px;
	}	
	
	.ListProductsTable th{
		border:gray 1px solid;
		padding:5px;
		color:white;
		background-color: #0086CB;
		font-weight: normal;
		font-size: 13px;
		text-align: center;
	}
	
	</style>

<?php 
    global $wpdb;
    
    $table = get_option('bew_payment_products_table_name');  
    
    
	// if add product form has been sent
	if( !empty( $_POST['bewp_product_name'] ) ) {
		// delete price if it is a donation
		if( $_POST['bewp_product_type'] == bp_enum_value_donation ){
			$_POST['bewp_product_price'] = null;
		}
		// get checkbox cerfa value
		if( $_POST['bewp_product_cerfa'] == 'on' ){
			$_POST['bewp_product_cerfa'] = true;
		}else{
			$_POST['bewp_product_cerfa'] = false;
		}
		// SQL query
		$status = $wpdb->query( $wpdb->prepare("INSERT INTO $table (name, type, price, cerfa, archive, group_id) VALUES ( %s, %s, %d, %d, %d, %s )", array( $_POST['bewp_product_name'], $_POST['bewp_product_type'], $_POST['bewp_product_price'], $_POST['bewp_product_cerfa'], false, $_POST['bewp_product_groupid'] ) ) );
		if(!$status){
			$logAdd = "Erreur d'enregistrement <br/>";
			$logAdd .= $wpdb->print_error();
		}else{
			$logAdd = "Produit enregistr&eacute;.";
		}
	}
	
	// if delete form has been sent
	if( !empty($_POST['archive_productId']) ) {
	
		$id = $_POST['archive_productId'];
		// SQL query
		$status = $wpdb->update( $table, array( 'archive' => true ), array( 'ID' => $id ), array( '%d'), array( '%d' ) );
		if(!$status){
			$logDelete = "Erreur : <br/>";
			$logDelete .= $wpdb->print_error();
		}else{
			$logDelete = "Produit archiv&#233;.";
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
			array( 'name' => $_POST['edit_p_name'], 'type' => $_POST['edit_p_type'], 'price' => $_POST['edit_p_price'], 'cerfa' => $_POST['edit_p_cerfa'], 'archive' => $_POST['edit_p_archive'] ), 
			array( 'ID' => $id ), 
			array( '%s', '%s', '%d', '%d', '%d'), 
			array( '%d' ) );
			
		if(!$status){
			$logEdit = "Aucune modification effectu&eacute;e.";
			$logEdit .= $wpdb->print_error();
		}else{
			$logEdit = "Produit enregistr&eacute;.";
		}
		
	}
	
	// if delete form has been sent
	if( !empty($_POST['update_productId']) ) {
	
		$id = $_POST['update_productId'];
		// SQL query
		$status = $wpdb->update( $table, array( 'archive' => true ), array( 'ID' => $id ), array( '%d'), array( '%d' ) );
		if(!$status){
			$logDelete = "Erreur : <br/>";
			$logDelete .= $wpdb->print_error();
		}else{
			$logDelete = "Produit archiv&#233;.";
		}
		
	}
	
	// if Cerfa mail form has been sent
	if( !empty($_POST['bewp_cerfa_copy']) ) {
		update_option('bew_payment_cerfa_copy', $_POST['bewp_cerfa_copy'] ); 
		
	}
	// if Cerfa mail form has been sent
	if( !empty($_POST['bewp_cerfa_from']) ) {
		update_option('bew_payment_cerfa_from', $_POST['bewp_cerfa_from'] ); 
		
	}


	

?>

<?php echo "<h2>Gestion des produits </h2>"; ?>

<!-- Ajouter un produit -->
<div id="BewPaymentAdd">  
    <?php echo "<h3>Ajouter un produit</h3>"; ?>  
    
  	<form name="bewpay_add_form" method="POST" action="#">  
        <p><?php echo "Nom du produit : "; ?><input type="text" name="bewp_product_name" size="20"></p>
        <p><?php echo "Type de produit : "; ?>
        <select name="bewp_product_type">
			<option value="<?php echo bp_enum_value_subscription; ?>"><?php echo bp_enum_value_subscription; ?></option>
			<option value="<?php echo bp_enum_value_donation; ?>"><?php echo bp_enum_value_donation; ?></option>
			<option value="<?php echo bp_enum_value_classic; ?>"><?php echo bp_enum_value_classic; ?></option>
		</select></p>
		
		<p><?php 
		echo "Groupe associ&#233; : "; 
		$adu = new adu_groups();
		?>
        <select name="bewp_product_groupid">
        	<?php
			foreach ( $adu->groups as $group_id => $group_options){
				?>
				<option value="<?php echo $group_id; ?>"><?php echo $group_options['name']; ?></option>
				<?php
			}
			?>
		</select>
		<span style="color:#CC0000; display:block;">NB : les utilisateurs ayant achet&#233; ce produit seront ajout&#233; dans le groupe s&#233;lectionn&#233; au terme de l'achat.</span>
		</p>
		
		
		
		
		
		
		<p><?php echo "Prix du produit (laisser vide si donation) : "; ?><input type="text" name="bewp_product_price" size="20"></p>  
        <p><input type=checkbox name="bewp_product_cerfa" checked   >G&#233;n&#233;rer un Cerfa ? (case coch&#233;e = oui )</p>
        <input type="submit" name="submit" class="button-primary" value="Enregistrer" >  
    </form>  
    <?php // display log
    if( !empty( $_POST['bewp_product_name'] ) ) {
		echo "<h4>" . $logAdd . "</h4>";
		
	}?>
    
</div>
<!-- Lister et supprimer un produit -->
<div id="BewPaymentList">

    <?php echo "<h3>Liste des produits</h3>"; ?>
    <!-- table liste produits -->
    <table class="ListProductsTable">
    	<!-- table header -->
    	<tr>
			<th>Nom du produit</th>
			<th>Type</th>
			<th>Prix (&#128;)</th>
			<th>G&#233;n&#233;ration de Cerfa ?</th>
			<th>Archive</th>
			<th>Groupe associ&#233;</th>
		</tr>
	    <?php
	    // retrieve products
    	$myrows = $wpdb->get_results( "SELECT * FROM $table ORDER BY name ASC" );
	    foreach($myrows as $product){
	    	?>
	    	<tr>
	    		<td><?= stripslashes($product->name) ?></td>
	    		<td><?= $product->type ?></td>
	    		<td><?= $product->price ?></td>
	    	<?php // cerfa
	    	if( $product->cerfa == 1 ){
	    		echo "<td>oui</td>";
	    	}else{
	    		echo "<td>non</td>";
	    	}
	    	// archive
	    	if( $product->archive == 1){
	    		echo "<td>Archiv&#233;</td>"; 
	    	}else{
	    		echo "<td>Actif</td>";
	    	}
	    	// groupe associé
	    	$groups = new adu_groups();
	    	?>
	    		<td><?= $groups->group_name($product->group_id) ?></td>
	    		<td style="border:none;" >
			    	<form name="EditBewProduct" action="#" method="POST">
			    		<input type="hidden" name="edit_productId" value="<?php echo $product->id; ?>" >
			    		<input type="submit" name="submit" class="button-primary" value="Modifier ce produit">
					</form>
					</td>
			    <td style="border:none;" >
			    	<form name="EditBewProduct" action="#" method="POST">
			    		<input type="hidden" name="archive_productId" value="<?php echo $product->id; ?>" >
			    		<input type="submit" name="submit" class="button-primary" value="Archiver ce produit">
					</form>
				</td>
			</tr>
	    <?php
	    }
	    ?>
    </table>
    <?php // display log
    if( !empty( $_POST['archive_productId'] ) ) {
		echo "<h4>" . $logDelete . "</h4>";
	}
	if( !empty( $_POST['edit_p_id'] ) ) {
		echo "<h4>" . $logEdit . "</h4>";
	}
	
	if( !empty( $_POST['edit_productId'] ) ) {
		$id = $_POST['edit_productId'];
		// SQL query
		$prod = $wpdb->get_row("SELECT * FROM $table WHERE id = '".$id."' ");
		?>
		<h4>Modifier le produit :</h4>
		<table class="ListProductsTable">
			<tr>
				<th>Nom du produit :</th>
				<th>Type de produit :</th>
				<th>Prix :</th>
				<th>Cerfa ?</th>
				<th>Archiv&#233; ?</th>
			</tr>
			<tr>
				<form name="ModifyBewProduct" action="#" method="POST">
				<input type="hidden" name="edit_p_id" value="<?php echo $prod->id; ?>" >
				<td><input type=text name="edit_p_name" value="<?php echo stripslashes($prod->name); ?>"  ></td>
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
				<td><input type=text name="edit_p_price" value="<?php echo $prod->price; ?>"  ></td>
				<td><input type=checkbox name="edit_p_cerfa" <?php if( $prod->cerfa ){ echo "checked"; } ?> ></td>
				<td><input type=checkbox name="edit_p_archive" <?php if( $prod->archive ){ echo "checked"; } ?> ></td>
				<td><input type="submit" name="submit" class="button-primary" value="Enregistrer les modifications"></td>
			</tr>
			</form>
		</table>
		<?php
	}
	?>
 
</div>  

<!-- Email de contact Cerfa -->
<div id="BewPaymentCerfa">

    <?php
    echo "<h3>Adresse E-mail a laquelle sont envoy&#233; une copie des Paiements</h3>";
    $mailCerfa = get_option('bew_payment_cerfa_copy');  
    ?>
    <form name="SetCerfaMail" action="#" method="POST" >
    <?php
    if(isset($mailCerfa)){
    	echo "<input type=\"text\" name=\"bewp_cerfa_copy\" value='".$mailCerfa."'r >";
    }else{ 
    	echo "<input type=\"text\" name=\"bewp_cerfa_copy\" >";
    }
    ?>
    <input type="submit" name="submit" class="button-primary" value="Modifier">
    <?php
    echo "<h3>Adresse E-mail depuis laquelle sont envoy&#233;s les Email de Cerfa</h3>";
    $mailCerfa = get_option('bew_payment_cerfa_from');  
    ?>
    <form name="SetCerfaMail" action="#" method="POST" >
    <?php
    if(isset($mailCerfa)){
    	echo "<input type=\"text\" name=\"bewp_cerfa_from\" value='".$mailCerfa."'r >";
    }else{ 
    	echo "<input type=\"text\" name=\"bewp_cerfa_from\" >";
    }
    ?>
    <input type="submit" name="submit" class="button-primary" value="Modifier">
    

	 
</div>  
<!-- Log des opérations -->
<div id="BewPaymentLog">

    <?php
    echo "<h3>Log des paiements effectu&#233;s</h3>";
    ?>
    <div id="bewPaymentLogTableContainer">
    <table class="ListProductsTable" id="bewPaymentLogTable">
    	<!-- table header -->
    	<thead>
        	<tr>
				<th>Date</th>
				<th>Atos Customer Id</th>
				<th>Atos Transaction Id</th>
				<th>Montant</th>
				<th>Nom du produit</th>
				<th>Nom du client</th>
				<th>Email du client</th>
			</tr>
        </thead>
        
        
        
        	<tbody>

    	
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
    foreach($myrows as $log){
    	?>
    	<tr>
    		<td><?= $log->payment_date ?></td>
    		<td><?= $log->atos_customer_id ?></td>
    		<td><?= $log->atos_transaction_id ?></td>
    		<td><?= $log->amount ?>&#128;</td>
    		<td><?= $log->name ?></td>
    		<td><?= $log->display_name ?></td>
    		<td><?= $log->user_email ?></td>
    	</tr>
    	<?php
    }
	?>
		</tbody>
		
	</table>
	</div>
</div>  





<script type="text/javascript">
jQuery(document).ready(function() {
	/*
	var options = {
              rowsPerPage : 5,
              firstArrow : (new Image()).src="/wp-content/plugins/bew_payment/images/pagination_first.png",
              prevArrow : (new Image()).src="/wp-content/plugins/bew_payment/images/pagination_previous.png",
              nextArrow : (new Image()).src="/wp-content/plugins/bew_payment/images/pagination_next.png",
              lastArrow : (new Image()).src="/wp-content/plugins/bew_payment/images/pagination_last.png",
            }


	jQuery('#bewPaymentLogTable').tablePagination(options);	
	*/

});
</script>