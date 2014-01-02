<style type="text/css">
    <?php include_once 'style.css'; ?>
</style>
<script type="text/javascript">
<?php include_once 'tablesorter.js'; ?>
</script>
<script type="text/javascript">
jQuery(document).ready(function(){
    message_string = jQuery("#new_message_input").attr("value");
    jQuery("#counter").text(160-message_string.length);
   jQuery("#new_message_input").keyup(function(){
       message_string = jQuery("#new_message_input").attr("value");
       jQuery("#counter").text(160-message_string.length);
   }); 
   jQuery("#modify_token").click(function(){
       value = jQuery("#token_value span").text();
       jQuery("#tokenValue").hide();
       jQuery("#updateToken").show();
   })
});
</script>
<style type="text/css">
	#MessageSending,#SendNewMsg,#settings{
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
</style>

<div id="SendNewMsg">
    <h2>Envoyer un message à un groupe</h2>
	<form name="SendNewSms" action="#" method="POST">
		<div class="sendSmsAction">
			<h3>I. Choisissez un groupe</h3>
			<select name="GroupSms">
				<option value=''>------</option>
				<?php
				foreach($adu->groups as$key => $group){
					echo "<pre>";
					var_dump($group);
					echo "</pre>";
					echo "<option value='$key'>".$group["name"]."</option>";
				}
			
				?>
			</select>
		</div>
		<div class="sendSmsAction">
			<h3>II. Séléctionnez le message à envoyer</h3>
				<select name="msgToSend">
					<option value=''>------</option>
					<?php
					foreach($messages as$key => $message){
						echo "<option value='$key'>".$message."</option>";
					}
						
					?>
				</select>
		</div>
		<input type="submit" name="submit" class="button-primary" value="Enregistrer un nouvel envoi">
	</form>
</div>


<div id="MessageSending">
<h2>gestion des Messages</h2>

<form method="post" action="#">
<div class="blockForm">
    <div class="intitule">Entrez le texte du message</div>
    <div class="input">
        <input name="message_content" type="text" id="new_message_input" class="fLeft" size="150"/>
    </div>
    <div id="counter">
        160
    </div>
</div>


<p>
<input type="submit" name="submit" class="button-primary" value="Enregistrer le message">
</p>
<table>
	<?php
	 foreach($messages as $key =>$message)
		echo "<tr>
				<td>
					$key
				</td>
				<td>
					$message
				</td>
				<td>
					<a href='?page=manage-sms&del_msg=$key'>
						<input type='button' value='Supprimer' />
					</a>
				</td>
			  </tr>";
	?>
</table>
</form>
</div>





<div id="settings">
    <h2>Paramètres du plugin</h2>
    <div class="blockForm fLeft">
        <div class="intitule2 fLeft">Votre clé d'api Orange</div>
        <div class="input2 fLeft" id="token_value">
            
            <?php if($token_api==NULL){ ?>
                <form method="post" action="options.php">
                <?php wp_nonce_field('update-options'); ?>
                    <input type="text" name="update_token" />
                    <input type="submit" name="submit" class="button-primary" value="Enregistrer">
                </form>
            <?php
            }else{?>
            <div id="tokenValue">
                <span><?= $token_api ?></span>
            
            <input type="button" value="modifier" id="modify_token" />
            </div>
            <div id="updateToken" style="display:none">
                <form method='post' action='options.php'>
                <?php wp_nonce_field('update-options'); ?>
                <input type='text' name='update_token' value='<?= $token_api ?>' />
                <input type='submit' value='enregistrer' />
                </form>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="blockForm fLeft">
        <div class="intitule2 fLeft">Choisissez le n° d'envoi</div>
        <div class="input2 fLeft">
            <select name="from">
            			<option value="20345">20345 (Orange France)</option>
            			<option value="38100">38100 (multi-opérateur France)</option>
            			<option value="967482">967482 (Orange GB)</option>
            			<option value="447797805210">447797805210 (international)</option>
            			</select>
        </div>
    </div>
</div>

