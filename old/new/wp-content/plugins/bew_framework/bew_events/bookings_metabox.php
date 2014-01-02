<?php


	function bew_events_booking_metabox(){
	
		global $post;
		
		bew_events_booking_metabox_style();
		$dispo = get_post_meta($post->ID, '_bew_event_places', true);
		$bew = new bewBookingsHelper();
		$bookings = $bew->get_bookings(array('event_id' => $post->ID));
		$count = 0;
		if(is_array($bookings)) 
			$count = sizeof($bookings);
		$base_link = "/wp-admin/post.php?post=$post->ID&action=edit";
		?>
		<h4 style="padding-bottom:5px"><?php  _e('Nombre de place disponibles :'); ?> <input name="bew_book_event_places" size="4" type="text" value="<?php echo $dispo; ?>" /></h4><br/>
		<?php if($dispo === false || $dispo == 0) return; ?>
        <?php if($dispo - $count > 0): ?>
            <?php _e('Book this event for a user (enter user email address)'); ?> :
            	<input name="bew_book_user_email" size="40"   type="text" />
                <input name="bew_book_event_id" type="hidden" value="<?php echo $post->ID; ?>"/>
                <input name="submit" type="submit" class="button" value="<?php _e('Book on this event'); ?>" />
        <?php else: ?>
        <div class="error"><p><?php _e('Too much bookings for this event !'); ?></p></div>
        <?php
		endif; 
		echo '<div class="bookings_list">';
		if($count > 0){
			echo "<h4>$count ".__('Bookings for this event:').'</h4><table>';
			foreach($bookings  as $bk){
				$user_info = get_userdata($bk->user_id);
				$user_link = "/wp-admin/user-edit.php?user_id=$bk->user_id";
				$cancel_link = $_SERVER['REQUEST_URI']."&bew_cancel_booking=$bk->id#bookings";
				$user_name = trim($user_info->first_name.' '.$user_info->last_name);
				if(!empty($user_name)) $user_name = '('.$user_name.')';
				$metas = "";
				if(is_array($bk->metadata)){
					$metas = " - <span class='booking_metas'>";
					foreach($bk->metadata as $meta => $data) $metas .=  '<strong>'.__($meta).' :</strong> '.$data.'<br/>';
					$metas .='</span>';
				}
				echo "<tr class='booking'><td ><a href='$user_link'>$user_info->user_login $user_name</a></td><td> $metas &nbsp; </td> <td><a href='$cancel_link' class='cancel_booking button'>".__('Cancel')."</a></td></tr>";
			}
			echo '</table>';
		}else{
			echo '<h4>'.__('No bookings for this event yet.').'</h4>';
		}
		echo '</div>';
	
	}
	
	
	function bew_events_metaboxes(){
		add_meta_box( 'bookings', __('Bookings'), 'bew_events_booking_metabox', 'events', 'normal');
	}
	
	add_action('admin_menu', 'bew_events_metaboxes');
	
	function bew_events_booking_metabox_style(){
	?>
    	<style type="text/css">
			.bookings_list {padding:0px 0px 10px 0px}
        	.bookings_list h4{background:#EAF2FA; padding:10px}
			.bookings_list table{width:100%;}
			.bookings_list td{border-bottom:1px solid #eee}
			.bookings_list td{padding:7px}
        </style>
	<?php
	}
	
	