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
    	$myrows = $wpdb->get_results( "SELECT * FROM $table WHERE type='".bp_enum_value_donation."' AND archive=0 ORDER BY name ASC" );
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
    			<input type="hidden" name="bp_product_bookable" value="no"  >
    			<table class="b_prods_tab">
    				<tr >
    					<td rowspan=2 class="b_prods_tab_c1">Montant du don ( en &#128; ) :
    					<?= cerfa_available($product->cerfa) ?>
    					<input type="text" name="bp_product_price" id="donation_input" ></td>
    					<td class="b_prods_tab_c2"></td>
    					<td class="b_prods_tab_c3"></td>
    					<td class="b_prods_tab_c4"></td>
    					<td rowspan=2 class="b_prods_tab_c5"><input type="submit" name="submit" value="Don" id="donation_submit"></td>
    				</tr>
    				</form>
    			</table>
			</div>
		<?php
	    }
	    // retrieve souscription products
    	$myrows = $wpdb->get_results( "SELECT * FROM $table WHERE type='".bp_enum_value_subscription."' AND archive=0 ORDER BY name ASC" );
    	// display only if there is some product in this category
	    if( !empty($myrows) ){
	    	?><h3>Souscriptions :</h3><?php
	    }
	    // instanciate array for not buyable products
	    $notBuyable = array();
	    $k = 0;
	    
	    foreach($myrows as $product){
	    	if( display_product_item($product) == "nok"){
	    	 	$k++;
	    	 	$notBuyable[$k] = $product;
	    	 }
	    }
	    
	    foreach( $notBuyable as $product ){
	    	display_soldout_item($product);
	    }
	    
	    
	    // retrieve classics products
    	$myrows = $wpdb->get_results( "SELECT * FROM $table WHERE type='".bp_enum_value_classic."' AND archive=0 ORDER BY name ASC" );
    	// display only if there is some product in this category
	    if( !empty($myrows) ){
	    	?><h3>Activit&eacute;s communautaires :</h3><?php
	    }
	    // instanciate array for not buyable products
	    $notBuyable = array();
	    $k = 0;
	    
	    foreach($myrows as $product){
	    	 if( display_product_item($product) == "nok"){
	    	 	$k++;
	    	 	$notBuyable[$k] = $product;
	    	 }
	    }
	    
	    foreach( $notBuyable as $product ){
	    	display_soldout_item($product);
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
	jQuery(".quantity_input").change(function(){
	 items_nbr = jQuery(this).attr("value");
	 price_item = jQuery(this).parent().prev().children().text();
	 final_value = parseInt(price_item*items_nbr);
	 jQuery(this).parent().next().children().text(final_value)
	});
	
	// blur input donation field
	jQuery('#donation_submit').click(function(){
		
		// get donation value
		var donationValue = jQuery('#donation_input').val();
		// replace "," by "." if the user did enter one ","
		donationValue = donationValue.replace(/,/g,'.');
		// update input field 
		jQuery('#donation_input').val(donationValue);
		// process input field potential errors
		if ( jQuery('#donation_input').val() == "" ){
			alert("Ce champ ne peut pas \352tre vide.");
			return false;
		}else if( isNaN($('#donation_input').val()) ){
				alert("Ce champ ne peut contenir que des nombres.");
				return false;
		}
		
			
	}); 
	
	// test if product is available (reservation)
	jQuery('.product_buy_button').click(function(){
		
		// retrieve current product id
		var productId = jQuery(this).attr('id');
		// get hidden field which indicate whether there isn't any available booking ("na") whether there is available booking and in this case, the number of available places
		var bookable = jQuery("#avai"+productId.substring(3) ).val();
		// if there is available booking
		if( bookable != "na" ){
			// parse available tickets to int
			var available = parseInt(bookable);
			// get the requested number of tickets
			var requested = jQuery("#pla"+productId.substring(3) ).val();
			// if customer ask more tickets than there is available, error
			if( available < requested ){
				alert("Il n'y a pas assez de places disponibles.")
				return false;
			}
		}
					
	}); 
	
	

});
</script>
