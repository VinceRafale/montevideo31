<?php
/*
Plugin Name: Essemess
Plugin URI: http://www.papiersdundev.com/essemess/
Description: Essemess connects your wordpress to Orange API sms. Yous can edit sms, choose the list to send to...
Version: 0.1
Author: mail@geraudmathe.com
Author URI: http://automattic.com/wordpress-plugins/
*/

//define( ESSEMESS_VERSION,"0.1" );

/* Action for activation /deactivation plugin*/
register_activation_hook( __FILE__, 'essemess_activate' );
register_deactivation_hook( __FILE__, 'essemess_deactivate' );

function essemess_activate(){
    add_option("essemess_token_api","");
	add_option("essemess_json_messages",json_encode(array()));
}

function essemess_deactivate(){
    //delete_option("essemess_token_api","");
	delete_option("essemess_json_messages","");
}

// register menu ESSEMESS
if ( is_admin() ){
	add_action('admin_menu', 'essemess_admin_menu');
	function essemess_admin_menu() {
		//add_options_page('Essemess', 'Essemess', 'manage_options', 'manage-sms', 'essemess_admin_panel');
		add_menu_page( 'Envois Sms', 'Envois Sms', 'publish_pages', 'manage-sms', 'essemess_admin_panel' );
	}
}

// Send a SMS
function essemessSend( $essemessMsgKey ){
	// Initialize log String
	$logString = "<h3>Statut de l'envoi :</h3>";
	// clé API 
	$accesskey =  get_option("essemess_token_api",NULL);
	if( empty($accesskey)){
		$logString .= "Vous devez insérer votre clé d'API Orange avant de pouvoir envoyer des SMS.";
		return $logString;
	}
	// adresse de l'API sendSMS
	$adresse = "http://run.orangeapi.com/sms/sendSMS.xml";
	// shortcode d'émission : 20345 (Orange France), 38100 (Multi-opérateur France), 967482 (Orange UK), 447797805210 (international) ou world (international)
	$from = "38100";
	// activation du SMS long
	$long_text = "true";
	// activation de l'accusé de réception
	//	$ack = "true";
	// type d'encodage
	$content_encoding = "gsm7";
	// From
	$from= "38100";
	// Contenu du SMS
	$messages =  json_decode(get_option("essemess_json_messages",NULL));
	var_dump($messages);
	// $content = urlencode( $messages[$_POST["msgToSend"]] );
	$content = urlencode( stripslashes($messages[ $essemessMsgKey - 1 ]) );
	// Préparation fichier sauvegarde CSV
	$csv_filename = $_SERVER['DOCUMENT_ROOT'] . "//logs_sms/". date('Y_m_d_G.i.s'). ".csv";
	if ($f = @fopen($csv_filename, 'w')) {
	  	$csv_header = array("Heure envoi requête", "Heure retour requête", "T&#233;l&#233;phone", "Statut Code", "Statut Message" );
		fputcsv($f, $csv_header,';', '"');
	} else {
		$logString .= "Impossible d'acc&eacute;der au fichier CSV de sauvegarde.<br/> Fichier :" . $csv_filename . "<br/>";
	}
	// préparation des variables de synthèse de log 
	$nb_users_in_group = 0;
	$nb_users_sms_subscribed = 0;
	$nb_sms_success = 0;
	$nb_sms_fail = 0;
	// Loop sur chaque utilisateur du groupe
	$adu = new adu_groups();
	$groupToSend = $adu->get_users_in_group($_POST["GroupSms"]);
	foreach($groupToSend as $user){
		// synthese log
		$nb_users_in_group ++;
		// get phones etc
		$phone = get_user_meta($user, 'adu_user_mobile');
		$unsubscribed = get_user_meta($user, 'unsubscribed_sms');
		if(!empty($phone) && !$unsubscribed){
			// synthese log
			$nb_users_sms_subscribed ++;
			// exécution de la requête> envoi du SMS
			$fd = file_get_contents($adresse . "?id=" . $accesskey . "&from=" . $from . "&to=" . $phone[0] . "&content=" . $content . "&long_text=" . $long_text . "&max_sms=" . $max_sms . "&ack=" . $ack . "&content_encoding=" . $content_encoding);
			// timestamp avant envoi
			$timestamp_before = date('Y_m_d_G.i.s ');
			// Affichage de la réponse de l'API
			$xml=simplexml_load_string($fd);
			// timestamp après envoi
			$timestamp_after = date('Y_m_d_G.i.s ');
			// retour de l'API Orange
			$statut_code = strval($xml->status->status_code);
			$statut_msg = $xml->status->status_msg;
			// log dans fichier xml
			$line = array( $timestamp_before, $timestamp_after, $phone[0], $statut_code, $statut_msg );
			fputcsv($f, $line, ';', '"' );
			// log number of failed/succeed sms
			if( $statut_code == 200 ){
				$nb_sms_success ++;
			}else{
				$nb_sms_fail ++;
			}
		}
	}
	// close CSV log file
	fclose($f);
	// display sending sms result
	$logString .= "Nombre de personnes dans le groupe : " . $nb_users_in_group ."<br/>";
	$logString .= "Nombre d'abonnés aux SMS : " . $nb_users_sms_subscribed ."<br/>";
	$logString .= "Nombre de SMS correctement envoyés : " . $nb_sms_success ."<br/>";
	$logString .= "Nombre de SMS qui ont échoués : " . $nb_sms_fail ."<br/>";
	return $logString;
}

// Add a SMS message in the database
if(!empty($_POST["message_content"])){
	$messages =  json_decode(get_option("essemess_json_messages",NULL));
	var_dump($messages);
	$messages[count($messages)]  = $_POST["message_content"];
	update_option("essemess_json_messages",json_encode($messages));
}

// save / update API token
if(!empty($_POST["update_token"])){
   update_option("essemess_token_api",$_POST["update_token"]);
}

function essemess_admin_panel() {
    include_once "form.php";
}