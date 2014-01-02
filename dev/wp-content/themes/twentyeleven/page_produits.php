<?php
/*
Template Name: Page produits (abonnements)
*/


/* 	NOTE IMPORTANTE : 
	
	La page wordpress utilisant ce template doit absolument avoir un slug Žgal ˆ "bp_page_produits"

*/

get_header();
?>


<style type="text/css">
	
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


<table id="ListProductsTable">
    	<!-- table header -->
    	
    	<?php
	    // Retrieve products
   		global $wpdb;
    	// get table name
		$table = get_option('bew_payment_products_table_name');  
		// retrieve products
    	$myrows = $wpdb->get_results( "SELECT * FROM $table ORDER BY name ASC" );
	    foreach($myrows as $product){
	    	// create form
    		echo "<p><form method=\"post\" action=".get_ID_by_slug('bp_page_moyen_paiement')." >";
    		echo "<input type=\"hidden\" name=\"bp_product_name\" value='".$product->name."'>";
    		echo "<input type=\"hidden\" name=\"bp_product_type\" value='".$product->type."'>";
    		echo "<input type=\"hidden\" name=\"bp_product_id\" value='".$product->id."'>";
    		echo "<input type=\"hidden\" name=\"bp_product_cerfa\" value='".$product->cerfa."'>";
	    	// table
	    	echo "<tr>";
	    	echo "<td>".$product->name."</td>";
	    	echo "<td>";
	    	// if donation, echo input, else echo price
	    	if( $product->type == bp_enum_value_donation ){
	    		// Donation : echo input field
				echo "Valeur de la donation : <input type=\"text\" name=\"bp_product_price\" id='donation_input' >&#128;";
	    	}else{
	    		// Subscription or classic : echo price
				echo " Prix: ".$product->price." &#128; ";
				// Subscription or classic : echo quantity input
				echo "Quantit&#233; : ";
				?>
				<select name="bp_product_quantity" id="quantity_input">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
				</select>
				<span id="total_amount"><?php echo $product->price; ?></span>
				<?php
				// hidden filed price for passing parameter
				echo "<input type=\"hidden\" name=\"bp_product_price\" value='".$product->price."'>";
	    	}
	    	echo "</td>";
	    	echo "<td>";
	    	echo "<input type=\"submit\" name=\"submit\" class=\"button-primary\" value=\"Acheter\" ></form></p>";
			
	    	echo "</td></tr>";
	    	
	    	
	    	
	    }
	    ?>
</table>
<?php
// Get wordpress page id by slug
function get_ID_by_slug($page_slug) {	
    $page = get_page_by_path($page_slug);
    if ($page) {
    	$url = get_permalink( $page->ID );
        return $url;
    } else {
        return null;
    }
}
?>



<script type="text/javascript">
jQuery(document).ready(function(){
	
	

});
</script>
