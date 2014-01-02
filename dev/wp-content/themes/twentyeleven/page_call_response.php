<?php
/*
Template Name: Atos Retour payement
*/

/* 	NOTE IMPORTANTE : 
	
	La page wordpress utilisant ce template doit absolument avoir un slug égal à "bp_page_retour_paiement"
	Librairies recquises >> à la racine /cerfa/ >>> html2mailer et /phpmailer/ v5.1
*/

	
	// Check this page is called by the product display page only
	if (empty( $HTTP_POST_VARS[DATA] )){
		// Display error message
		echo "Vous n'avez pas le droit d'être sur cette page blabla";
	}else{
	
		// récupération de la variable cryptée DATA
		$message="message=$HTTP_POST_VARS[DATA]";
		
		// chemin des executables
		$pathfile="pathfile=/homez.395/synagogud/atos/pathfile";
		$path_bin = "/homez.395/synagogud/atos/response";
	
		// Appel du binaire response
		$result=exec("$path_bin $pathfile $message");
	
		//	Sortie de la fonction : !code!error!v1!v2!v3!...!v29
		//		- code=0	: la fonction retourne les donnŽes de la transaction dans les variables v1, v2, ...
		//				: Ces variables sont dŽcrites dans le GUIDE DU PROGRAMMEUR
		//		- code=-1 	: La fonction retourne un message d'erreur dans la variable error
		//	on separe les differents champs et on les met dans une variable tableau
		$tableau = explode ("!", $result);
	
		//	Récupération des données de la réponse
		$code = $tableau[1];
		$error = $tableau[2];
		$merchant_id = $tableau[3];
		$merchant_country = $tableau[4];
		$amount = $tableau[5];
		$transaction_id = $tableau[6];
		$payment_means = $tableau[7];
		$transmission_date= $tableau[8];
		$payment_time = $tableau[9];
		$payment_date = $tableau[10];
		$response_code = $tableau[11];
		$payment_certificate = $tableau[12];
		$authorisation_id = $tableau[13];
		$currency_code = $tableau[14];
		$card_number = $tableau[15];
		$cvv_flag = $tableau[16];
		$cvv_response_code = $tableau[17];
		$bank_response_code = $tableau[18];
		$complementary_code = $tableau[19];
		$complementary_info = $tableau[20];
		$return_context = $tableau[21];
		$caddie = base64_decode($tableau[22]);
		$receipt_complement = $tableau[23];
		$merchant_language = $tableau[24];
		$language = $tableau[25];
		$customer_id = $tableau[26];
		$order_id = $tableau[27];
		$customer_email = $tableau[28];
		$customer_ip_address = $tableau[29];
		$capture_day = $tableau[30];
		$capture_mode = $tableau[31];
		$data = $tableau[32];
	
		//  analyse du code retour
	
	  	if (( $code == "" ) && ( $error == "" ) ){
	  		print ("<BR><CENTER>erreur appel response</CENTER><BR>");
	  		print ("executable response non trouve $path_bin");
	 	}
	
		//	Erreur, affiche le message d'erreur
		else if ( $code != 0 ){
			print ("<center><b><h2>Erreur appel API de paiement.</h2></center></b>");
			print ("<br><br><br>");
			print (" message erreur : $error <br>");
		}
	
		// PAYMENT VALIDATED
		else {
			//print (" $error <br>");
			
			/* 
			 *	Process transmitted datas
			 */
			
			// convert date to SQL date
			$payment_datetime = substr($payment_date, 0, 4) . "-" . substr($payment_date, 4, 2) . "-". substr($payment_date, 6, 2)." ".substr($payment_time, 0, 2) . ":" . substr($payment_time, 2, 2) . ":". substr($payment_time, 4, 2) ;
			// explode caddie 
			$caddieTemp = explode("_", $caddie);
			$product_id = $caddieTemp[0];
			$product_name = $caddieTemp[1];
			$product_quantity = $caddieTemp[2];
			$product_cerfa = $caddieTemp[3];
			// convert amount
			$amount = $amount/100;
			// TO DO : GET LOGGED USER ID !!!!
			$user_id = 1;
			
			global $wpdb;
    		// get table name
			$table = get_option('bew_payment_logs_table_name');  
			// SQL query
			$status = $wpdb->query( $wpdb->prepare("INSERT INTO $table 
				(user_id, product_id, amount, payment_date, atos_customer_id, atos_transaction_id ) 
				VALUES ( %d, %d, %d, %s, %s, %s )", 
				array( 
					$user_id,
					$product_id,
					$amount,
					$payment_datetime,
					$customer_id,
					$transaction_id
				) ) );
			// check SQL query status
			if(!$status){
				//echo "Erreur : <br/>";
				//$wpdb->print_error();
			}else{
				
				// Récapitulatif
				echo "<p>R&#233;capitulatif du paiement :</p>";
				echo "Produit achet&#233; : ".$product_name."<br/>";
				echo "Quantit&#233; : ".$product_quantity."<br/>";
				echo "Prix de la transaction : ".$amount."  &#128;<br/>";
				echo "Date de la transaction : ".$payment_datetime."<br/>";
				echo "N° de transaction : ".$transaction_id."<br/>";
				echo "N° de client : ".$customer_id."<br/>";
				
				
				// Cerfa
				if ( $product_cerfa == "oui" ){
					
					$cerfa_pdf_path = $_SERVER['DOCUMENT_ROOT'].'/cerfa/generated/montevideo_cerfa_'.$transaction_id.'.pdf';
					$customer_firstname = ucfirst("gabriel");
					$customer_lastname = ucfirst("nau");
					$customer_street_number = "33";
					$customer_street_name = ucwords("philibert delorme");
					$customer_postal_code = "75017";
					$customer_city = ucfirst("Paris");
					$order_amount = $amount;
					$order_amount_words = "deux cents euros";
					$order_date_day = substr($payment_date, 6, 2);
					$order_date_month = substr($payment_date, 4, 2);
					$order_date_year = substr($payment_date, 0, 4);
					
					ini_set("display_errors",1);
					ob_start();
					include($_SERVER['DOCUMENT_ROOT'].'/cerfa/base2.php');
					$content = ob_get_clean();
				
					// conversion HTML => PDF
					require_once($_SERVER['DOCUMENT_ROOT'].'/cerfa/html2pdf.class.php');
					try
					{
						$html2pdf = new HTML2PDF('P', 'A4', 'fr', false, 'ISO-8859-15');
						$html2pdf->pdf->SetDisplayMode('fullpage');
						$html2pdf->writeHTML($content );
						$html2pdf->Output($cerfa_pdf_path, 'F');
					}
					catch(HTML2PDF_exception $e) { echo $e; }
				
				
				}
				
				
				
				// Build Email
				$mail_from = get_option('bew_payment_cerfa_from');  
				$mail_copy = get_option('bew_payment_cerfa_copy');  
				$mail_user = "geraud@bewculture.com";		
				$mail_subject = "Synagogue Montevideo - Reçu de paiement";
				require_once($_SERVER['DOCUMENT_ROOT'].'/phpmailer/class.phpmailer.php'); 
				$mail = new PHPMailer(true); 
				try {
					$mail->AddReplyTo($mail_from, 'Synagogue Montevideo');
				  	$mail->AddAddress($mail_copy ); // mailto montevideo a copy
					//$mail->AddAddress($mail_user ); // mailto the user
				  	$mail->SetFrom($mail_from, 'Synagogue Montevideo');
				  	$mail->Subject = $mail_subject;
				  	$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; 
				  	$mail->MsgHTML("blabla");
				  	// add cerfa if needed
				  	if ( $product_cerfa == "oui" ){
				  		$mail->AddAttachment($cerfa_pdf_path);
				  	}
				  	$mail->Send();
				  	echo "Un email a été envoyé à l'adresse ".$mail_user." avec le récapitulatif du paiement.";
				} catch (phpmailerException $e) {
					echo $e->errorMessage(); //Pretty error messages from PHPMailer
				} catch (Exception $e) {
					echo $e->getMessage(); //Boring error messages from anything else!
				}
			


				
				
								
								
				
						
				
				
			}

		
		}
	
		

	
	
	
	
	}
	
?>
