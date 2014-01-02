<?php 
// Delete a SMS
if( !empty($_POST['delete_essemess']) ){

	$idToDelete = (int)$_POST["delete_essemess"];
	$idToDelete--; // decrement because of the empty <=> 0 value in php
	$messages =  json_decode(get_option("essemess_json_messages",NULL), true);
	unset( $messages[$idToDelete] );
	update_option("essemess_json_messages",json_encode($messages));

	$lmessages[] = "<strong>SMS effacé</strong>";

}
	

// Welcome SMS
if(!empty($_POST["bew_welcome_sms"])){
	update_option("essemess_welcome_message",json_encode( $_POST["bew_welcome_sms"] ));
	$lmessages[] = "<strong>SMS enregistré</strong>";

}
	
// send SMS
if( !empty($_POST["GroupSms"]) && !empty($_POST["msgToSend"]) ){
	$lmessages[] = essemessSend( $_POST["msgToSend"] );
}

// Add a SMS message in the database
if(!empty($_POST["message_content"])){
	$textos =  json_decode(get_option("essemess_json_messages",NULL), true);
	$textos[count($textos)]  = $_POST["message_content"];
	update_option("essemess_json_messages",json_encode($textos));
	$lmessages[] = "<strong>Message enregistré</strong>";
}

// save / update API token
if(!empty($_POST["update_token"])){
   update_option("essemess_token_api",$_POST["update_token"]);
   $lmessages[] = "<strong>Clé enregistrée</strong>";
}



$token_api =  get_option("essemess_token_api",NULL);
$textos =  json_decode(get_option("essemess_json_messages",NULL));
$adu = new adu_groups();
?>

<!-- Container -->
<div class="wrap" style="max-width:950px !important;">
	
	<h2>Gestion des SMS</h2>
	<div id="poststuff" style="margin-top:10px;">
		
		<!-- Display log -->
		<?php 
		if ( !empty($lmessages) ){ 
		?>
		<div id="message" class="updated">
			<?php
			foreach ( $lmessages as $msg )
				echo "<p>$msg</p>"; 
				?>
	    </div>
		<?php 
		}
		
		if ( !empty($smsLog) ){ 
		?>
		<div id="message" class="updated">
			<ul>
			<?php echo $msg; ?>
			</ul>
	    </div>
		<?php 
		}
		?>
		
		<!-- Enregistrer SMS -->
		<div class="postbox">
			<h3 class='hndle'>
				<span>Enregistrer un message</span>
			</h3>
			<div class="inside">
				
				<form method="post" action="#">
				<p>
				<label for="message_content" >Texte du message : </label>
				<input name="message_content" type="text" id="new_message_input" style="width:100%;" />
				</p>
				<p>
				Caractères restants :
					<span id="counter">160</span>
					&nbsp;&nbsp;
					<span id="counter_alert"></span>
				</p>
				<input type="submit" name="submit" value="Enregistrer le message">
				</form>
	
			</div><!-- End of inside class -->
		</div>
		
		<!-- Envoyer un SMS -->
		<div class="postbox">
			<h3 class='hndle'>
				<span>Envoyer un message à un groupe</span>
			</h3>
			<div class="inside">
				
				
				<form name="SendNewSms" action="#" method="POST">
				<p>
				<label for="GroupSms">1- Choisissez un groupe : </label>
				<select name="GroupSms">
					<option value=''></option>
					<?php foreach( $adu->groups as $key => $group){ ?>
					<option value='<?= $key ?>' ><?php echo stripslashes($group["name"]); ?></option>
					<?php } ?>
				</select>
				</p>
				<p>
				<label for="msgToSend">2- Sélectionnez le message à envoyer : </label>
				<select name="msgToSend">
				<option value=''></option>
				<?php foreach($textos as $key => $message){ ?>
					<option value='<?= $key+1 ?>' ><?php echo stripslashes($message); ?></option>
				<?php }?>
				</select>
				</p>
				<input type="submit" name="submit" value="Envoyer le SMS">
				</form>
				
			</div><!-- End of inside class -->
		</div>
		
				
		<!-- Gérer les SMS -->
		<div class="postbox">
			<h3 class='hndle'>
				<span>Gestion des messages</span>
			</h3>
			<div class="inside">
				
				<table class="widefat" >
				<thead>
				<tr>
					<th scope="col" style="width:70px;" >Action</th>
					<th scope="col">Message</th>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach($textos as $key =>$message){ ?>
				<tr>
					<td>
						<form action="#" method="POST">
						<input type='hidden' name='delete_essemess' value='<?= $key+1 ?>' />
						<input type='submit' name='submit' value='Supprimer' />
					</form>
					</td>
					<td>
						<?php echo stripslashes($message); ?>
					</td>
				</tr>
				<?php }?>
				</tbody>
				</table>
					
			</div><!-- End of inside class -->
		</div>
				
		<!-- Log -->
		<div class="postbox">
			<h3 class='hndle'>
				<span>Log des SMS envoyés</span>
			</h3>
			<div class="inside">
			
				<form name="ListLogFiles" action="#" method="POST">
				<p>
				<label for="logList">Choisissez un fichier de log : </label>
				<select name="logList">
				<?php 
				// retrieve log file list
				$folder = $_SERVER['DOCUMENT_ROOT']."/logs_sms";
				$p_folder = opendir($folder);
				while ($singleFile = readdir($p_folder)) {
					if ($singleFile != "." && $singleFile != "..") {
						// full path
				    	$filePath = $folder."/".$singleFile;
						// select option value : transforme filename into human date
						$fileTempString = explode("_", $singleFile);
						$selectOptionValue = $fileTempString[2] . "/" . $fileTempString[1] . "/" . $fileTempString[0];
						$fileTempString = explode(".", $fileTempString[3]);
						$selectOptionValue .= " " . $fileTempString[0] . ":" . $fileTempString[1] . ":" . $fileTempString[2];
				  		echo "<option value=".$filePath." >".$selectOptionValue."</option>";
				  	}
				}
				closedir($dossier);
				?>
				</select>
				</p>
				<input type="submit" name="submit" value="Ouvrir le fichier">
				</form>
				
					
				<?php	
	 
				//Display log file 
				if( !empty($_POST["logList"]) ){
					$file = $_POST["logList"];
					// open file
					$row = 1;
					if (($handle = fopen($file, "r")) !== FALSE) {
						// statistics var
						$log_success = 0;
						$log_unsuccess = -1; // -1 because there is a header 
						
						$heureEnvoi = array();
						$heureRetour = array();
						$telephone = array();
						$statutCode = array();
						$statutMsg = array();
						
						$i = 0;
						// loop on each line of the file
					    while ( ($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
					    	// put data in array
					    	$heureEnvoi[$i] = $data[0];
					    	$heureRetour[$i] = $data[1];
					    	$telephone[$i] = $data[2];
					    	$statutCode[$i] = $data[3];
					    	$statutMsg[$i] = $data[4];
					    	$i++;
					    	// statistics
							if( $data[3] == "200" ){
								$log_success ++;
							}else{
								$log_unsuccess ++;
							}
					    }
						// close file
						fclose($handle);
						// close html log table
						?>
						<!-- Display the results and the log table -->
						<p>
							<h3>Statistiques du fichier :</h3>
						</p>
							Sms envoyés : <?= $log_success ?>
						<p>
						</p>
							Sms échoués : <?= $log_unsuccess ?>
						<p>
						
						</p>
						<div style="max-height:500px; overflow-y:auto;">
						<table class="widefat" >
						<thead>
						<tr>
							<th scope="col" ><?= $heureEnvoi[0] ?></th>
							<th scope="col" ><?= $heureRetour[0] ?></th>
							<th scope="col" ><?= $telephone[0] ?></th>
							<th scope="col" ><?= $statutCode[0] ?></th>
							<th scope="col" ><?= $statutMsg[0] ?></th>
						</tr>
						</thead>
						<tbody>
						<?php
						for( $i = 1; $i <= sizeof($heureEnvoi); $i++){ ?>
							<tr>
								<td><?= $heureEnvoi[$i] ?></td>
								<td><?= $heureRetour[$i] ?></td>
								<td><?= $telephone[$i] ?></td>
								<td><?= $statutCode[$i] ?></td>
								<td><?= $statutMsg[$i] ?></td>
							</tr>
						<?php
						} ?>
						</tbody>
						</table>
						</div>
						<?php
					}else{
						?><p>Impossible d'ouvrir le fichier .csv de log</p><?php
					}
				}
				?>
			</div><!-- End of inside class -->
		</div>

		<!-- Adresse mail -->
		<div class="postbox">
			<h3 class='hndle'>
				<span>SMS d'accueil</span>
			</h3>
			<div class="inside">
				
				<form method="post" action="#">
				<p>
				<label for="bew_welcome_sms" >Texte du message d'accueil : </label>
				<?php $welcome = stripslashes(json_decode(get_option('essemess_welcome_message') )); ?>
				<input name="bew_welcome_sms" type="text" id="new_message_input" style="width:100%;" value="<?= $welcome ?>" />
				</p>
				<p>
				Caractères restants :
					<span id="counter">160</span>
					&nbsp;&nbsp;
					<span id="counter_alert"></span>
				</p>
				<input type="submit" name="submit" value="Enregistrer le message">
				</form>
					
			</div><!-- End of inside class -->
		</div>
		
		<!-- Clé API -->
		<div class="postbox">
			<h3 class='hndle'>
				<span>Clé d'API Orange</span>
			</h3>
			<div class="inside">
				
				 Votre clé : 
        <?php if($token_api==NULL){ ?>
            <form method="post" action="options.php">
            <?php wp_nonce_field('update-options'); ?>
                <input type="text" name="update_token" />
                <input type="submit" name="submit" class="button-primary" value="Enregistrer">
            </form>
        <?php }else{ ?>
	        <div id="tokenValue">
	            <span><?php echo $token_api; ?></span>
	        	<input type="button" value="modifier" id="modify_token" />
	        </div>
	        <div id="updateToken" style="display:none">
	            <form method='post' action='#'>
	            <?php wp_nonce_field('update-options'); ?>
	            <input type='text' name='update_token' value='<?= $token_api ?>' />
	            <input type='submit' value='enregistrer' />
	            </form>
	        </div>
        <?php } ?>
					
			</div><!-- End of inside class -->
		</div>

		
		
		
		
	</div><!-- End of poststuff (style container) -->
</div><!-- End of container -->


<script type="text/javascript">
jQuery(document).ready(function(){
	// compteur caractères SMS
    message_string = jQuery("#new_message_input").attr("value");
    jQuery("#counter").text(160-message_string.length);
    jQuery("#new_message_input").keyup(function(){
      message_string = jQuery("#new_message_input").attr("value");
      jQuery("#counter").text(160-message_string.length);
      if( (160-message_string.length)<0 ){
      	jQuery("#counter_alert").text('Vous dépassez le nombre maximal de caractères autorisé.');
      }else{
      	jQuery("#counter_alert").text('');
      }
   }); 
   
   // Edit or create api token
   jQuery("#modify_token").click(function(){
       value = jQuery("#token_value span").text();
       jQuery("#tokenValue").hide();
       jQuery("#updateToken").show();
   })
});
</script>

