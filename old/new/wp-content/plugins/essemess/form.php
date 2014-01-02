<?php 
// Delete a SMS
if( !empty($_POST['delete_essemess']) ){

	$idToDelete = (int)$_POST["delete_essemess"];
	$idToDelete--; // decrement because of the empty <=> 0 value in php
	echo $idToDelete;
	$messages =  json_decode(get_option("essemess_json_messages",NULL));
	unset( $messages[$idToDelete] );
	update_option("essemess_json_messages",json_encode($messages));
}


$token_api =  get_option("essemess_token_api",NULL);
$messages =  json_decode(get_option("essemess_json_messages",NULL));
$adu = new adu_groups();
?>


<div class="adminDiv">

    <h2>Envoyer un message à un groupe</h2>

	<form name="SendNewSms" action="#" method="POST">
		1. Choisissez un groupe
		<select name="GroupSms">
			<option value=''>------</option>
			<?php foreach( $adu->groups as $key => $group){ ?>
				<option value='<?= $key ?>' ><?php echo stripslashes($group["name"]); ?></option>
			<?php } ?>
		</select>
		<br/><br/>
		2. Sélectionnez le message à envoyer
		<select name="msgToSend">
			<option value=''>------</option>
			<?php foreach($messages as $key => $message){ ?>
				<option value='<?= $key+1 ?>' ><?php echo stripslashes($message); ?></option>
			<?php }?>
		</select>
		<br/><br/>
		<input type="submit" name="submit" class="button-primary" value="Enregistrer un nouvel envoi">
	</form>
	<?php
	if( !empty($_POST["GroupSms"]) && !empty($_POST["msgToSend"]) ){
		$essemessLogString = essemessSend( $_POST["msgToSend"] );
		echo $essemessLogString;
	}
	?>
</div>


<div class="adminDiv">
	<h2>Ajouter un message</h2>
	<form method="post" action="#">
		Entrez le texte du message :
		<div class="input">
			<input name="message_content" type="text" id="new_message_input" class="fLeft" size="150"/>
		</div>
		Caractères restants :<span id="counter">160</span>&nbsp;&nbsp;<span id="counter_alert"></span>
		<br/><br/>
		<input type="submit" name="submit" class="button-primary" value="Enregistrer le message">
	</form>
	
	<br/><br/>
	
	<h2>Gestion des messages</h2>
	
	<table>
	<?php
	foreach($messages as $key =>$message){
		?>
		<tr>
			<td><?php echo stripslashes($message); ?></td>
			<td>
				<form action="#" method="POST">
					<input type='hidden' name='delete_essemess' value='<?= $key+1 ?>' />
					<input type='submit' name='submit' value='Supprimer' />
				</form>
			</td>
		</tr>
	<?php }?>
	</table>
</div>

<div class="adminDiv">
	
	<h2>Log des SMS envoyés</h2>
	<h3>Choisissez un fichier de log</h3>
	<form name="ListLogFiles" action="#" method="POST">
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
		<input type="submit" name="submit" class="button-primary" value="Ouvrir le fichier">
	</form>
	<?php
	/* 
	 * Display log file 
	 */
	if( !empty($_POST["logList"]) ){

		$file = $_POST["logList"];
		// open file
		$row = 1;
		if (($handle = fopen($file, "r")) !== FALSE) {
			// statistics var
			$log_success = 0;
			$log_unsuccess = -1; // -1 because there is a header 
			$logTable = "<table id=\"logTable\">";
			// loop on each line of the file
		    while ( ($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		    	// output table string
				$logTable .= "<tr>";
				$logTable .= 	"<td>".$data[0]."</td>";
				$logTable .= 	"<td>".$data[1]."</td>";
				$logTable .= 	"<td>".$data[2]."</td>";
				$logTable .= 	"<td>".$data[3]."</td>";
				$logTable .= 	"<td>".$data[4]."</td>";
				$logTable .= "</tr>";
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
			$logTable .= "</table>";
		    // echo results
			echo "<h3>Statistiques du fichier</h3>";
			echo "Sms envoyés : ".$log_success."<br/>";
			echo "Sms échoués : ".$log_unsuccess;
			echo "<h3>Contenu du fichier log</h3>";
			echo $logTable;
		
		}else{
			echo "Impossible d'ouvrir le csv de log";
		}
	}
	?>
</div>

<div class="adminDiv">
    <h2>Clé d'API Orange</h2>
    <div class="blockForm fLeft">
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
    </div>
</div>


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
<style type="text/css">
	.adminDiv{
		border:gray 2px solid;
		margin:10px;
		padding:10px;
		-webkit-border-radius: 10px;
		-moz-border-radius: 10px;
		border-radius: 10px;
	}
	
	.sendSmsAction{
		width:100%;
		padding-bottom:10px;
	}
	
	#counter_alert{
		color: red;
		font-style: bold;
	}
	
	#logTable{
		border:gray 1px solid;
		border-collapse:collapse;
		text-align:left;
		padding:5px;
	}
	
	#logTable tr, #logTable td{
		border:gray 1px solid;
		padding:5px;
	}
	
	h2{
		margin : 0 0 20px 0;
	}
	
	#new_message_input{
		width:100%;
	}
</style>
