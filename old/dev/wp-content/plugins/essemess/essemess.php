<?php
/*
Plugin Name: Essemess
Plugin URI: http://www.papiersdundev.com/essemess/
Description: Essemess connects your wordpress to Orange API sms. Yous can edit sms, choose the list to send to...
Version: 0.1
Author: mail@geraudmathe.com
Author URI: http://automattic.com/wordpress-plugins/
*/


define(ESSEMESS_VERSION,"0.1");
/* Action for activation /deactivation plugin*/

register_activation_hook( __FILE__, 'essemess_activate' );
register_deactivation_hook( __FILE__, 'essemess_deactivate' );



function essemess_activate(){
    add_option("essemess_token_api","");
	add_option("essemess_json_messages",json_encode(array()));
}

function essemess_deactivate(){
    delete_option("essemess_token_api","");
	delete_option("essemess_json_messages","");
}

/* End of Action for activation /deactivation plugin*/

/*Deleting messages*/
if(!empty($_GET["page"])&&!empty($_GET["del_msg"])){
	$idToDelete = (int)$_GET["del_msg"];
	$messages =  json_decode(get_option("essemess_json_messages",NULL));
	unset($messages[$idToDelete]);
	update_option("essemess_json_messages",json_encode($messages));
}
/*
First we add the link in he menu
*/
if ( is_admin() ){

	/* Call the html code */
	add_action('admin_menu', 'essemess_admin_menu');

	function essemess_admin_menu() {
	add_options_page('Essemess', 'Essemess', 'manage_options', 'manage-sms', 'essemess_admin_panel');
	add_menu_page( 'Envois Sms', 'Envois Sms', 'publish_pages', 'manage-sms', 'essemess_admin_panel' );
	}
}
/*
sending sms
*/

if(!empty($_POST["GroupSms"])&&!empty($_POST["msgToSend"])){
	$accesskey =  get_option("essemess_token_api",NULL);
	$from= "38100";
	$messages =  json_decode(get_option("essemess_json_messages",NULL));
	$content = urlencode($messages[$_POST["msgToSend"]]);
	$adu = new adu_groups();
	$groupToSend = $adu->get_users_in_group($_POST["GroupSms"]);
	foreach($groupToSend as $user){
		$phone = get_user_meta($user, 'adu_user_mobile');
		$unsubscribed = get_user_meta($user, 'unsubscribed_sms');
		if(!empty($phone) && !$unsubscribed){
			$url_sms = "http://sms.beta.orange-api.net/sms/sendSMS.xml?id=" . $accesskey . "&from=" . $from . "&to=" . $phone . "&content=" . $content;
			$output = file_get_contents($url_sms);
		}
	}
}

/*Adding a message record*/
if(!empty($_POST["message_content"])){
	$messages =  json_decode(get_option("essemess_json_messages",NULL));
	$messages[count($messages)]  = $_POST["message_content"];
	update_option("essemess_json_messages",json_encode($messages));
}

if(!empty($_REQUEST['api'])&&!empty($_REQUEST['to'])&&!empty($_REQUEST['from'])&&!empty($_REQUEST['content'])){
	echo "jkdfjklsdjlgklh";
	$to = $_REQUEST['to']; //Numéro du shortcode
	$content = urldecode($_REQUEST['content']); //Contenu du SMS
	$time = date(DATE_RFC822); //Horodatage
	mail("gmathe08@gmail.com","nouveau désabonnement",
		$_REQUEST['api']."<br />".$_REQUEST['to']."<br />".$_REQUEST['from']."<br />".$_REQUEST['content']);
}
/*
saving or updating the token-api
*/
if(!empty($_POST["update_token"])){
   
   update_option("essemess_token_api",$_POST["update_token"]);
}



function warning($message) {
	echo "
	<div class='updated fade'><p><strong>".__($message)."</strong></p></div>
	";
}

function essemess_admin_panel() {
    $token_api =  get_option("essemess_token_api",NULL);
	$messages =  json_decode(get_option("essemess_json_messages",NULL));
    if($token_api==NULL){
        warning("Vous devez insérer votre clé d'api orange avant de pouvoir envoyer des sms");
    }  
    $adu = new adu_groups();
        include_once "form.php";
} 
?>