<?php
/*
Plugin Name: BEW Culture framework - Facebook connect
Plugin URI: 
Description: Allow users to login via their facebook account. Login button can be displayed either with a template tag, or in content using a quicktag.

Author: Adrien Menoret
Author URI: mailto: adrien.menoret@gmail.com 

Version: 1.6
*/

define('FACEBOOK_APP_ID', 'your application id');
define('FACEBOOK_SECRET', 'your application secret');

function get_facebook_cookie($app_id, $application_secret) {
  $args = array();
  parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
  ksort($args);
  $payload = '';
  foreach ($args as $key => $value) {
    if ($key != 'sig') {
      $payload .= $key . '=' . $value;
    }
  }
  if (md5($payload . $application_secret) != $args['sig']) {
    return null;
  }
  return $args;
}

$cookie = get_facebook_cookie(FACEBOOK_APP_ID, FACEBOOK_SECRET);

?>
