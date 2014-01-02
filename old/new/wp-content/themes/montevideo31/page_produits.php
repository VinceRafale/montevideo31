<?php
/*
Template Name: Page produits (abonnements)
*/


/* 	NOTE IMPORTANTE : 
	
	La page wordpress utilisant ce template doit absolument avoir un slug Žgal ˆ "bp_page_produits"

*/
?>


<?php
get_header(); 
global $wpdb;
?>

<div id="container">
	<div id="bp_products" role="main">
		<!-- Display text entered in the admin page text editor -->
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if ( is_front_page() ) { ?>
					<h2 class="entry-title"><?php the_title(); ?></h2>
				<?php } else { ?>
					<h1 class="entry-title"><?php the_title(); ?></h1>
				<?php } ?>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</div>
		<?php endwhile; ?>
		
		<?php
	    // get table name
		$table = get_option('bew_payment_products_table_name');  
		// retrieve donation products
    	$myrows = $wpdb->get_results( "SELECT * FROM $table WHERE type='".bp_enum_value_donation."' ORDER BY name ASC" );
	    // display only if there is some product in this category
	    if( !empty($myrows) ){
	    	?><h3>Don &#224; la Synagogue :</h3><?php
	    }
	    foreach($myrows as $product){
	    	?>
	    	<div class="b_prods_container" >
    			<form method="post" action="<?php echo get_ID_by_slug('bp_page_moyen_paiement'); ?>" >
    			<input type="hidden" name="bp_product_name" value="<?php echo $product->name; ?>" >
    			<input type="hidden" name="bp_product_type" value="<?php echo $product->type; ?>" >
    			<input type="hidden" name="bp_product_id" value="<?php echo $product->id; ?>" >
    			<input type="hidden" name="bp_product_cerfa" value="<?php echo $product->cerfa; ?>"  >
    			<input type="hidden" name="bp_product_group_id" value="<?php echo $product->group_id; ?>"  >
    			<input type="hidden" name="bp_product_quantity" value="1"  >
    			
    			<table class="b_prods_tab">
    				<tr >
    					<td rowspan=2 class="b_prods_tab_c1">Montant du don ( en &#128; ) : <input type="text" name="bp_product_price" id="donation_input" ></td>
    					<td class="b_prods_tab_c2"></td>
    					<td class="b_prods_tab_c3"></td>
    					<td class="b_prods_tab_c4"></td>
    					<td rowspan=2 class="b_prods_tab_c5"><input type="submit" name="submit" value="Acheter" id="donation_submit"></td>
    				</tr>
    				</form>
    			</table>
			</div>
		<?php
	    }
	    // retrieve souscription products
    	$myrows = $wpdb->get_results( "SELECT * FROM $table WHERE type='".bp_enum_value_subscription."' ORDER BY name ASC" );
	    // display only if there is some product in this category
	    if( !empty($myrows) ){
	    	?><h3>Souscriptions :</h3><?php
	    }
	    
	    foreach($myrows as $product){
	    	?>
	    	<div class="b_prods_container" >
    			<form method="post" action="<?php echo get_ID_by_slug('bp_page_moyen_paiement'); ?>" >
    			<input type="hidden" name="bp_product_name" value="<?= stripslashes($product->name) ?>" >
    			<input type="hidden" name="bp_product_type" value="<?php echo $product->type; ?>" >
    			<input type="hidden" name="bp_product_id" value="<?php echo $product->id; ?>" >
    			<input type="hidden" name="bp_product_cerfa" value="<?php echo $product->cerfa; ?>"  >
    			<input type="hidden" name="bp_product_price" value="<?php echo $product->price; ?>"  >
    			
    			<table class="b_prods_tab">
    				<tr>
    					<td rowspan=2 class="b_prods_tab_c1"><?= stripslashes($product->name) ?></td>
    					<td class="b_prods_tab_c2">Prix :</td>
    					<td class="b_prods_tab_c3">Quantit&#233; :</td>
    					<td class="b_prods_tab_c4">Total :</td>
    					<td rowspan=2 class="b_prods_tab_c5"><input type="submit" name="submit" value="Acheter" ></td>
    				</tr>
    				<tr>
    					<td><span class="bp_product_initial_price"><?php echo $product->price; ?></span>&#128;</td>
    					<td>
    						<select name="bp_product_quantity" class="quantity_input">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
							</select>
    					</td>
    					<td><span class="bp_product_total_price"><?php echo $product->price; ?></span>&#128;</td>
    					</form>
    				</tr>
    			</table>
			</div>
		<?php
	    }
	    // retrieve products
    	$myrows = $wpdb->get_results( "SELECT * FROM $table WHERE type='".bp_enum_value_classic."' ORDER BY name ASC" );
	    // display only if there is some product in this category
	    if( !empty($myrows) ){
	    	?><h3>Autres :</h3><?php
	    }
	    foreach($myrows as $product){
	    	?>
	    	<div class="b_prods_container" >
    			<form method="post" action="<?php echo get_ID_by_slug('bp_page_moyen_paiement'); ?>" >
    			<input type="hidden" name="bp_product_name" value="<?= stripslashes($product->name) ?>" >
    			<input type="hidden" name="bp_product_type" value="<?php echo $product->type; ?>" >
    			<input type="hidden" name="bp_product_id" value="<?php echo $product->id; ?>" >
    			<input type="hidden" name="bp_product_cerfa" value="<?php echo $product->cerfa; ?>"  >
    			<input type="hidden" name="bp_product_price" value="<?php echo $product->price; ?>"  >
    			
    			<table class="b_prods_tab">
    				<tr>
    					<td rowspan=2 class="b_prods_tab_c1"><?= stripslashes($product->name) ?></td>
    					<td class="b_prods_tab_c2">Prix :</td>
    					<td class="b_prods_tab_c3">Quantit&#233; :</td>
    					<td class="b_prods_tab_c4">Total :</td>
    					<td rowspan=2 class="b_prods_tab_c5"><input type="submit" name="submit" value="Acheter" ></td>
    				</tr>
    				<tr>
    					<td><span class="bp_product_initial_price"><?php echo $product->price; ?></span>&#128;</td>
    					<td>
    						<select name="bp_product_quantity" class="quantity_input">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
							</select>
    					</td>
    					<td><span class="bp_product_total_price"><?php echo $product->price; ?></span>&#128;</td>
    					</form>
    				</tr>
    			</table>
			</div>
		<?php
	    }
	    ?>
		
		<div id="bp_products_creditcards">
			<h3>Moyens de paiements accept&#233;s :</h3>
			<img src="/atos/logo/small_cb.gif">
			<img src="/atos/logo/small_visa.gif">
			<img src="/atos/logo/small_mastercard.gif">
			<img src="/atos/logo/small_amex.gif">
		</div>
	</div><!-- #content -->
		
<?php get_sidebar(); ?>
<div class="clear"></div>
</div><!-- #container -->
<?php get_footer(); ?>


<script type="text/javascript">
jQuery(document).ready(function(){
	
	// quantity select
	$(".quantity_input").change(function(){
	 items_nbr = $(this).attr("value");
	 price_item = $(this).parent().prev().children().text();
	 final_value = parseInt(price_item*items_nbr);
	 $(this).parent().next().children().text(final_value)
	});
	
	// blur input donation field
	$('#donation_submit').click(function(){
	
		if ( $('#donation_input').val() == "" ){
			alert("Ce champ ne peut pas \352tre vide.");
			return false;
		}else if( isNaN($('#donation_input').val()) ){
				alert("Ce champ ne peut contenir que des nombres.");
				return false;
		}
	}); 

});
</script>
