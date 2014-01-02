<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <META http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>fichedescriptiveformulaire_5184.pdf</title>
        <style type="text/css" media="print">.hide{display:none}</style>
    </head>
    <body style="margin:0;padding:0">
		
        <div style="margin:0px;z-index:-1"> 
        
			<img src="<?php echo $_SERVER['DOCUMENT_ROOT'].'/cerfa/cerfa-last.jpg'; ?>" width="750"/>
		
			<div id="transacid" style="position:absolute;z-index:2;margin-top:245px;margin-left:150px">
				<?php echo $order_id; ?>
			</div>
		
			<div id="name" style="position:absolute;z-index:2;margin-top:150px;margin-left:400px">
				<?php echo $customer_firstname." ".$customer_lastname; ?>
			</div>
			
			<div id="street" style="position:absolute;z-index:2;margin-top:180px;margin-left:400px"> 
				<?php echo $customer_adress; ?>
			</div>
			
			<div id="ZipCode" style="position:absolute;z-index:2;margin-top:210px;margin-left:400px">
				<?php echo $customer_zipcode; ?>
			</div>
			
			<div id="City" style="position:absolute;z-index:2;margin-top:210px;margin-left:440px">
				<?php echo $customer_city; ?>
			</div>
			
			<div id="affectation" style="position:absolute;z-index:2;margin-top:380px;margin-left:95px; width:260px;">		
				<?php echo $order_name; ?>
			</div>
			
			<div id="libelle" style="position:absolute;z-index:2;margin-top:380px;margin-left:270px;">
				ACTI
			</div>
			
			<div id="date_header" style="position:absolute;z-index:2;margin-top:380px;margin-left:490px">
				<?php  echo $order_date_day . "/" . $order_date_month . "/" . 								$order_date_year; ?>
			</div>
			
			<div id="mode_Versement" style="position:absolute;z-index:2;margin-top:380px;margin-left:590px">
				Vir.
			</div>
			
			<div id="montant_euros" style="position:absolute;z-index:2;margin-top:380px;margin-left:640px">
				<?php echo $order_amount; ?>
			</div>
			
			<div id="num_recu" style="position:absolute;z-index:2;margin-top:640px;margin-left:600px">
				<?php echo $order_id; ?>
			</div>
			
			<div id="cname" style="position:absolute;z-index:2;margin-top:785px;margin-left:170px">
				<?php echo $customer_firstname . " " . $customer_lastname; ?>
			</div>
			
			<div id="cstreet" style="position:absolute;z-index:2;margin-top:805px;margin-left:170px"> 
				<?php echo $customer_adress; ?>
			</div>
			
			<div id="cZipCode" style="position:absolute;z-index:2;margin-top:825px;margin-left:170px">
				<?php echo $customer_zipcode; ?>
			</div>
			
			<div id="cCity" style="position:absolute;z-index:2;margin-top:845px;margin-left:170px">
				<?php echo $customer_city; ?>
			</div>
			
			<div id="cdatebis" style="position:absolute;z-index:2;margin-top:945px;margin-left:220px">
				<?php echo $order_date_day . "/" . $order_date_month . "/" . 								$order_date_year; ?>
			</div>
			
			<div id="camount" style="position:absolute;z-index:2;margin-top:905px;margin-left:320px">
				***<?php echo $order_amount; ?> Euros
			</div>
			
			<div id="cdate" style="position:absolute;z-index:2;margin-top:973px;margin-left:615px">
				<?php echo $order_date_day . "/" . $order_date_month . "/" . 								$order_date_year; ?>
			</div>
			
		</div>
	</body>
</html>