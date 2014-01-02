<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <META http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>fichedescriptiveformulaire_5184.pdf</title>
        <style type="text/css" media="print">.hide{display:none}</style>
    </head>
    <body style="margin:0;padding:0">
		
        <div style="margin:0px;z-index:-1"> 
			<img src="<?php echo $_SERVER['DOCUMENT_ROOT'].'/cerfa/cerfaassoc.jpg'; ?>" width="750"/>
			<div id="name" style="position:absolute;z-index:2;margin-top:142px;margin-left:30px">
				<?php echo $customer_firstname." ".$customer_lastname; ?>
			</div>
			<div id="streetNumber" style="position:absolute;z-index:2;margin-top:185px;margin-left:40px">
			</div>
			<div id="streetName" style="position:absolute;z-index:2;margin-top:185px;margin-left:120px">
			</div>
			<div id="ZipCode" style="position:absolute;z-index:2;margin-top:205px;margin-left:104px">
			</div>
			<div id="City" style="position:absolute;z-index:2;margin-top:205px;margin-left:234px">
			</div>
			<div id="object" style="position:absolute;z-index:2;margin-top:247px;margin-left:24px;width:650px;line-height:16px">
			
			</div>
			
			<img src="<?php echo $_SERVER['DOCUMENT_ROOT'].'/cerfa/cerfaassoc-2.jpg'; ?>" width="750"/>
			<div id="GiverName" style="position:absolute;z-index:2;margin-top:50px;margin-left:30px">
				<?php echo $customer_lastname; ?>
			</div>
			<div id="GiverFirstName" style="position:absolute;z-index:2;margin-top:50px;margin-left:375px">
				<?php echo $customer_firstname; ?>
			</div>
			<div id="streetNumber" style="position:absolute;z-index:2;margin-top:103px;margin-left:30px">
			</div>
			<div id="ZipCode" style="position:absolute;z-index:2;margin-top:125px;margin-left:100px">
			</div>
			<div id="City" style="position:absolute;z-index:2;margin-top:125px;margin-left:274px">
			</div>
			<div id="Amount" style="position:absolute;z-index:2;margin-top:223px;margin-left:285px">
				<?php echo $order_amount; ?>
			</div>
			<div id="Amount" style="position:absolute;z-index:2;margin-top:263px;margin-left:200px">
				<?php echo $order_amount_words; ?>
			</div>
			<div id="Date_day" style="position:absolute;z-index:2;margin-top:297px;margin-left:225px">
				<?php echo $order_date_day; ?>
			</div>
			<div id="Date_month" style="position:absolute;z-index:2;margin-top:297px;margin-left:265px">
				<?php echo $order_date_month; ?>
			</div>
			<div id="Date_year" style="position:absolute;z-index:2;margin-top:297px;margin-left:330px">
				<?php echo $order_date_year; ?>
			</div>
		</div>
		
	</body>
</html>