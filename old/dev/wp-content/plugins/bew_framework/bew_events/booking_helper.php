<?php


class bewBookingsHelper{


		public $table;

		function __construct(){
		
			global $wpdb;
			global $bew_bookings_table;

			
			$this->table = $wpdb->prefix.$bew_bookings_table;
		
		}

		
		function get_bookings($args){
		
			global $wpdb;
		
			if(isset($args['deleted'])) $where = ' WHERE deleted = 1 '; else $where = ' WHERE deleted = 0 ';
			
			if(isset($args['user_id'])) $where .= " AND user_id = ".$args['user_id'];
			if(isset($args['event_id'])) $where .= " AND event_id = ".$args['event_id'];
			
			$sql = "SELECT * FROM {$this->table} ".$where;
		
			$bookings = $wpdb->get_results($wpdb->prepare($sql));
		
			if(!is_array($bookings)) return false;
			
			foreach($bookings as $k => $bk){
			
				$bookings[$k]->metadata = unserialize($bookings[$k]->metadata);
			
			}
			
			return $bookings;
		
		}
	
		
		
		
		
		function book($event_id, $user_id, $metadata){
			
			global $wpdb;
		
			$sql = "SELECT id FROM {$this->table} WHERE event_id = $event_id AND user_id = $user_id AND deleted = 0";
			$result = $wpdb->get_var($wpdb->prepare($sql));
			
			if($result) return bew_admin_message('This event is already booked for this user.', 'error');
			
			
			$data = array('event_id' => $event_id, 'user_id' => $user_id, 'metadata' => serialize($metadata));
			

			
			$format = array('%d', '%d', '%s');
		
			if($wpdb->insert( $this->table, $data, $format )) return true;
			
			bew_message('Booking failed, please contact site admin.', 'error');
			
			return false;
			
			
			
		
		}
		
		
		function unbook($ids = array(), $args = array()){  //array of booking id or event_id and user_id
		
			
			
			global $wpdb;
			
			$success = true;
			
			if(is_array($args)){
			
			
				$bk = $this->get_bookings($args);
				
				if(is_array($bk)) foreach($bk as $b) $ids[] = $b->id;
		
			
			}
			
			if(!(sizeof($ids) > 0)) wp_die('Nothing to unbook.');
		
			
			foreach ($ids as $booking_id){
			
				$data = array('deleted' => 1);
				$where = array('id' => $booking_id);
				$format = array('%d');
				$where_format = array('%d'); 
			
				if(!$wpdb->update( $this->table, $data, $where, $format, $where_format )) $success = false;
			
			}
			
			return $success;
		
		}
		
		
}

if(isset($_REQUEST['bew_cancel_booking'])) add_action('init', 'bew_cancel_event_booking');

function bew_cancel_event_booking(){

	$bew = new bewBookingsHelper();

	if(isset($_REQUEST['bew_cancel_booking'])) $bew->unbook($_REQUEST['bew_cancel_booking']);
	
		
	
}






if((isset($_REQUEST['bew_book_event_id']) && !empty($_REQUEST['bew_book_user_email']))  ) add_action('init', 'bew_book_event');


function bew_book_event(){


	


	$event_id = $_REQUEST['bew_book_event_id'];
	
	if(isset($_REQUEST['bew_book_user_email'])){
		
		if(!is_email($_REQUEST['bew_book_user_email'])) return bew_admin_message('You did not provide a valid email address.', 'error');
		$user = get_user_by_email($_REQUEST['bew_book_user_email']);
		
		if(!isset($user->ID)) return bew_admin_message('This email address was not found in the database. User does not exist, or email address is incorrect.', 'error');
		
		else $user_id = $user->ID;
	
	}
	
	if(isset($_REQUEST['bew_book_user_id'])) $user_id = $_REQUEST['bew_book_user_id'];
	
	if(isset($_REQUEST['bew_book_comments'])) $metadata['Comments'] = $_REQUEST['bew_book_comments'];
	
	$bew = new bewBookingsHelper();
	
	if($bew->book($event_id, $user_id, $metadata)) bew_admin_message('Booking done.', 'message');

}



if(isset($_REQUEST['bwbook'])) add_action('template_redirect', 'bew_book_single_event');

function bew_book_single_event(){

	global $current_user;
	
	if(!($current_user->ID > 0)) return bew_message(sprintf('You must <a href="%s">login</a> or <a href="%s">register</a> to book an event.', apply_filters('bew_login'), apply_filters('bew_register')), 'message');
	
	global $wp_query;
	
	if($wp_query->post_count == 1 && $wp_query->post->ID > 0) {
	
	$bew = new bewBookingsHelper();
	
	if($_REQUEST['bwbook'] == 'yes' && $bew->book($wp_query->post->ID, $current_user->ID , array() )) bew_message('Your booking has been taken into account.', 'message');
	
	if($_REQUEST['bwbook'] == 'no' && $bew->unbook(array(), array('event_id' => $wp_query->post->ID, 'user_id' =>$current_user->ID ))) bew_message('Your booking has been canceled.', 'message');
	
	elseif($_REQUEST['bwbook'] == 'no')  bew_message('Error canceling your booking, please contact site administration.', 'error');
	
	}
	
}

