<?php

function  bew_event_meta(){


	
		global $post;
		
		$id = $post->ID;
	
	
	
	if($id == 0) return;


?>

	<?php 	$custom_field_keys = get_post_custom_keys();
	
			  foreach ( $custom_field_keys as $key ) {
			  
				$keyt = trim($key);
				
				  if ( '_' == $keyt{0} ) continue;
				  
				  $value = get_post_meta($id, $key, true);
				  $valuet = trim($value);
				  
				  if(!$value || empty($valuet) || is_array($value)) continue;
				  
				  
				  echo '<span class="event_meta"><span class="key">'.ucfirst($key).' :</span> '.$valuet.'</span>';
				  
			  }

	
	 ?> 

	<?php $dispo = get_post_meta($id, '_bew_event_places', true); 
	
		  $bkbew = new bewBookingsHelper();
		  $booked = $bkbew->get_bookings(array('event_id' => $id ) );
		  

		  
		  $available = strval($dispo) - strval(count($booked));
		  
		  global $current_user;
		  
		  if($current_user->ID > 0) {
		  
		  $user_booked = $bkbew->get_bookings(array('event_id' => $id, 'user_id' => $current_user->ID ) );
		  $user_booked = sizeof($user_booked);
		  
		  } else $user_booked = false;
		  
		  
		  
		  ?>
          <?php if($dispo > 0) : ?>
            
          <span class="event_meta event_places"><?php echo $available.' '.__('places restantes'); ?></span>
          
          <?php endif; ?>
          
        <?php if($user_booked): ?>
        
        <span class="event_meta event_booked"><?php echo  __('You have booked')." $user_booked ".__('place(s)'); ?></span>
        <span class="event_meta event_unbook" style="padding:0"><a href="<?php the_permalink(); ?>?bwbook=no" class="button button_red"><?php _e('Cancel'); ?></a></span>
        
        <?php elseif($available > 0) : ?>
          <span class="event_meta event_book" style="padding:0"><a href="<?php the_permalink(); ?>?bwbook=yes" class="button"><?php _e('Book'); ?> &raquo;</a></span>
          
 		<?php endif; ?>
        
<?php 

}