<?php
if(isset($_POST["action"])&&$_POST["action"]=="bew_register_newsletter")
add_action("admin_init","bew_register_newsletter");

function bew_register_newsletter(){
    $S = new adu_groups();
    $mailContent = get_post($_POST["template"]);

    if($S->group_exists($_POST["groupName"])){
        $Users = $S->get_users_in_group($_POST["groupName"],true);
        $BMailer = new BEW_Mailer();
        foreach($Users as $user){
            $defaults = array(
				"date_entered" => time(),
				"user_id" => $user->ID,		
				"user_emails" => $user->user_email,
				"subject" => $mailContent->post_title,
				"body_text" => strip_tags($mailContent->post_content),
				"body_html" => $mailContent->post_content,
				"send_on" => time(),
				"additionnal_headers" => '',
				"send_attempts" => 0,
				"meta" => false,
				"post_id" => $_POST["template"],
				"errors"=>""
			
			);
            $BMailer->insert_email($defaults);
        }
    }
    
    //use the_title and the content()for insertion in tabl
}
?>
