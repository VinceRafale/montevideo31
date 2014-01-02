<style type="text/css">
	#BewPaymentAdd,#BewPaymentList,#BewPaymentCerfa, #BewPaymentLog{
		border:gray 2px solid;
		margin:10px;
		padding: 0 10px 10px 10px;
		-webkit-border-radius: 10px;
		-moz-border-radius: 10px;
		border-radius: 10px;
	}
	
	#ListProductsTable{
		border:gray 1px solid;
		border-collapse:collapse;
		text-align:left;
		padding:5px;
	}
	
	#ListProductsTable tr, #ListProductsTable td{
		border:gray 1px solid;
		padding:5px;
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
		$status = $wpdb->query( $wpdb->prepare("INSERT INTO $table (name, type, price, cerfa) VALUES ( %s, %s, %d, %d )", array( $_POST['bewp_product_name'], $_POST['bewp_product_type'], $_POST['bewp_product_price'], $_POST['bewp_product_cerfa'] ) ) );
		if(!$status){
			$logAdd = "Erreur d'enregistrement <br/>";
			$logAdd .= $wpdb->print_error();
		}else{
			$logAdd = "Produit enregistr&#233;.";
		}
	}
	// if delete form has been sent
	if( !empty($_POST['productId']) ) {
	
		$id = $_POST['productId'];
		// SQL query
		$status = $wpdb->query(" DELETE FROM $table WHERE id='$id' ");
		if(!$status){
			$logDelete = "Erreur : <br/>";
			$logDelete .= $wpdb->print_error();
		}else{
			$logDelete = "Produit effac&#233;.";
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
    <table id="ListProductsTable">
    	<!-- table header -->
    	<tr>
			<td>Nom du produit</td>
			<td></td>
			<td></td>
			<td>G&#233;n&#233;rer un Cerfa ?</td>
			<td></td>
		</tr>
	    <?php
	    // retrieve products
    	$myrows = $wpdb->get_results( "SELECT * FROM $table ORDER BY name ASC" );
	    foreach($myrows as $product){
	    	echo "<tr>";
	    	echo "<td>".$product->name."</td>";
	    	echo "<td>".$product->type."</td>";
	    	echo "<td>".$product->price."</td>";
	    	if( $product->cerfa == 1 ){
	    		echo "<td>oui</td>";
	    	}else{
	    		echo "<td>non</td>";
	    	}
	    	echo "<td>";
	    	?>
	    	<form name="DeleteBewProduct" action="#" method="POST">
	    		<input type="hidden" name="productId" value="<?php echo $product->id; ?>" >
	    		<input type="submit" name="submit" class="button-primary" value="Effacer ce produit">
			</form>
	    	<?php
	    	echo "</td></tr>";
	    }
	    ?>
    </table>
    <?php // display log
    if( !empty( $_POST['productId'] ) ) {
		echo "<h4>" . $logDelete . "</h4>";
	}?>
 
</div>  

<!-- Email de contact Cerfa -->
<div id="BewPaymentCerfa">

    <?php
    echo "<h3>Adresse E-mail à laquelle sont envoyé une copie des Paiements</h3>";
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
    echo "<h3>Adresse E-mail depuis laquelle sont envoyé les Email de Cerfa</h3>";
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
<!-- Email de contact Cerfa -->
<div id="BewPaymentLog">

    <?php
    echo "<h3>Log des paiements effectuÈs</h3>";
    ?>
    <table id="ListProductsTable">
    	<!-- table header -->
    	<tr>
			<td>Date</td>
			<td>Atos Customer Id</td>
			<td>Atos Transaction Id</td>
			<td>Montant</td>
			<td>Nom produit</td>
		</tr>
	<?php
    $table_logs = get_option('bew_payment_logs_table_name');  
    $table_products = get_option('bew_payment_products_table_name');  
    
    $myrows = $wpdb->get_results( "SELECT 
    a.payment_date ,
    a.atos_customer_id ,
    a.atos_transaction_id ,
    a.amount,
    b.name
    FROM $table_logs a, $table_products b  
    WHERE a.product_id = b.id
    ORDER BY payment_date DESC" );
    foreach($myrows as $log){
    	echo "<tr>";
    	echo "<td>".$log->payment_date."</td>";
    	echo "<td>".$log->atos_customer_id."</td>";
    	echo "<td>".$log->atos_transaction_id."</td>";
    	echo "<td>".$log->amount."</td>";
    	echo "<td>".$log->name."</td>";
    	
    	echo "</tr>";
    }
	?>
	</table>
</div>  