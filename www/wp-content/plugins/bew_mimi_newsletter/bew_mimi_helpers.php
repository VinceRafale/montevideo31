<?php

function bewmimi_retrieve_lists($user){
	
	$url = 'http://api.madmimi.com/audience_lists/lists.xml?username='.$user['username'].'&api_key='.$user['api'];
	// call
	$response = wp_remote_get($url);
	// handle response
	if(!is_wp_error($response) && isset($response['response']['code']) && $response['response']['code'] == 200) {
	    $xml = simplexml_load_string( maybe_serialize($response['body']) );
	    if($xml && is_object($xml) && sizeof($xml->list) > 0) {
	    	foreach($xml->list as $l) {
	        	$a = $l->attributes();
	    		$listArray[] = strtolower(htmlentities($a['name']));
	        }
	    	return $listArray;
	    }
	}
	return false;
}

function bewmimi_add_list($user, $list){
	if( $list == ""){
		return false;
	}
	
	$url = 'http://api.madmimi.com/audience_lists/';
	$response = wp_remote_post( $url, array(
		'method' => 'POST',
		'redirection' => 5,
		'blocking' => true,
		'headers' => array(),
		'body' => array('name' => $list, 'username' => $user['username'], 'api_key' => $user['api']  ),
	    )
	);
	
	if( is_wp_error( $response ) ) {
		return false;
	} else {
		if( $response['response']['code'] == 200){
			return true;
		}else{
			return false;
		}
	}
}


function bewmimi_retrieve_list_members( $user, $list ){

	if( $list == ""){
		return false;
	}
	$url = 'http://api.madmimi.com/audience_lists/'.$list.'/members.xml?username='.$user['username'].'&api_key='.$user['api'];
	$response = wp_remote_get($url);
	if(!is_wp_error($response) && isset($response['response']['code']) && $response['response']['code'] == 200) {
	
		$xml = simplexml_load_string( maybe_serialize($response['body']) );
	  	
	  		  	
	  	foreach( $xml->member as $membre){
	  	
	  		
	  		foreach($xml->list as $l) {
	  			$a = $l->attributes();
	    		$lists[] = strtolower(htmlentities($a['email']));
	        	echo strtolower(htmlentities($a['email']));
	        }
	  	
	  	
	  		//$lists[] = $membre->email;
	  	}
	  	return $lists;
	}else{
		return "erreur";
	}

	

}

function bewmimi_get_list_audience($user, $list){
	if( $list == ""){
		return false;
	}
	
	$url = 'http://api.madmimi.com/audience_lists/'.$list.'/members.xml?username='.$user['username'].'&api_key='.$user['api'];
	
	$response = wp_remote_get($url);
	if(!is_wp_error($response) && isset($response['response']['code']) && $response['response']['code'] == 200) {
	
		
	    $xml = simplexml_load_string( maybe_serialize($response['body']) );
	  	foreach( $xml->member as $membre){
	  		foreach($xml->list as $l) {
	  			var_dump($l);
	        	$a = $l->attributes();
	    		$lists[] = strtolower(htmlentities($a['email']));
	        }
	  	
	  	
	  		//$lists[] = $membre->email;
	  	}
	  	return $lists;
	}else{
		return "erreur";
	}
	
}


function bewmimi_add_single_user( $user, $mail, $list ){

	$url = 'http://api.madmimi.com/audience_lists/'.$list.'/add' ;
	$response = wp_remote_post( $url, array(
		'method' => 'POST',
		'redirection' => 5,
		'blocking' => true,
		'headers' => array(),
		'body' => array('email' => $mail, 'username' => $user['username'], 'api_key' => $user['api']  ),
	    )
	);
	
	if( is_wp_error( $response ) ) {
		return false;
	} else {
		if( $response['response']['code'] == 200){
			return true;
		}else{
			return false;
		}
	}
	
}


function bewmimi_add_group_to_mimi( $usersArray, $mmUser, $groupName ){
	
	$csv_data = "email,add_list\n";
	foreach( $usersArray as $singleuser ){
		$csv_data .= '"';
		$csv_data .= "{$singleuser}\",";
		$csv_data .= '"'.$groupName.'"';
        $csv_data .= "\n";
	}
	
	$url = 'http://api.madmimi.com/audience_members' ;
	$response = wp_remote_post( $url, array(
		'method' => 'POST',
		'redirection' => 5,
		'blocking' => true,
		'headers' => array(),
		'body' => array( 'csv_file'=> $csv_data, 'username' => $mmUser['username'], 'api_key' => $mmUser['api']  ),
	    )
	);
	
	if( is_wp_error( $response ) ) {
		return false;
	} else {
		if( $response['response']['code'] == 200){
			return true;
		}else{
			return false;
		}
	}
}

function bewmimi_remove_unsubscribed_user( $userEmail, $mmUser  ){

	///audience_lists/remove_all?email={email_to_remove} [POST]
	$url = 'http://api.madmimi.com/audience_lists/remove_all' ;
	$response = wp_remote_post( $url, array(
		'method' => 'POST',
		'redirection' => 5,
		'blocking' => true,
		'headers' => array(),
		'body' => array( 'email'=> $userEmail, 'username' => $mmUser['username'], 'api_key' => $mmUser['api']  ),
	    )
	);
	
	if( is_wp_error( $response ) ) {
		return false;
	} else {
	
		if( $response['response']['code'] == 200){
			return true;
		}else{
			return false;
		}
	}


}
