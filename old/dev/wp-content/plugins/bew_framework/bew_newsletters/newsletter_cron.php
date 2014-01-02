<?php

add_action("admin_init","send_newsletter");
if($_GET["action"]=="unsubscribe" && ($_GET["nid"] && $_GET["uid"] && $_GET["token"]))
  add_action("init","unsubscribe_newsletter");

function send_newsletter(){
	global $wpdb;
	$sql = "SELECT * FROM wp_bew_mailer_queue WHERE send_attempts = 0";
	$Result = $wpdb->get_results($sql);
	foreach($Result as $newsletter){
	  $optin = get_user_meta( $newsletter->user_id, "newsletter_unsubscribed",1);//1 if the user unsubscribed
	  echo $newsletter->user_id."|".$newsletter->user_emails."====>".$optin[0]."<br />";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= 'From: Montevideo <montevideo@montevideo31.com>' . "\r\n";
		$content = $newsletter->body_html."<hr>
																				<center>
																					Pour vous désabonner de la newsletter montevideo 31 
																					<a href='http://".$_SERVER["HTTP_HOST"]."?action=unsubscribe&nid=".$newsletter->ID."&uid=".$newsletter->user_id	."&token=".sha1(crc32($newsletter->user_emails))."'>
																						cliquez-ici
																					</a>
																				</center>";
		if($optin==0){
		  mail($newsletter->user_emails, $newsletter->subject, $content, $headers);
			$wpdb->query('UPDATE wp_bew_mailer_queue SET send_attempts=1 WHERE ID='.$newsletter->ID);
		}
		else if($optin ==1){
		  $wpdb->query('UPDATE wp_bew_mailer_queue SET send_attempts=2 WHERE ID='.$newsletter->ID);
		}
	}
}

function unsubscribe_newsletter(){
  //http://synagogue-montevideo31.com/?action=unsubscribe&nid=13&uid=1&token=e09545ed16898a99f883da92ff62506374da2f22
  global $wpdb;
	$email = $wpdb->get_var("SELECT user_emails FROM wp_bew_mailer_queue WHERE ID = ".(int)$_GET["nid"]." AND user_id=".(int)$_GET["uid"]);
	if(sha1(crc32($email))==$_GET["token"]){
	   update_user_meta( (int)$_GET["uid"], "newsletter_unsubscribed", 1 );
	}
}