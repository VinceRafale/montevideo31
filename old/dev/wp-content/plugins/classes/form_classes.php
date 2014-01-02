<?php

	include_once('clonefish/clonefish.php');
	include_once('clonefish/messages_en.php');
	
	
	$dst_form_custom_layouts = array(
	
		'rowbyrow' => Array(
		
			'container'  => '%s',
			'element'    => "<div %errorstyle% ><label for=\"%id%\">%displayname% <span class='prefix'>%prefix%</span></label> %erroricon%<br />\n%element%<span class='postfix'>%postfix%</span>%errordiv%</div>\n\n",
			'errordiv'   => '<div id="%divid%" style="display: none; visibility: hidden; padding: 2px 5px 2px 5px; background-color: #d03030; color: white;"></div>',
			'buttonrow'  => '%s',
			'button'     => '<button type="submit" ><span>%s</span></button>',
			
		  ),
		  
		  
		  'tabular' => Array(
		  
			'container'  => "<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\">\n%s\n</table>\n",
			'element'    => "<tr %errorstyle%><td width=\"120\" align=\"right\"><label for=\"%id%\">%displayname%</label></td><td width=\"7\" class=\"center\">%erroricon%</td><td>%prefix%%element%%postfix%%errordiv%</td></tr>\n",
			'errordiv'   => '<div id="%divid%" style="display: none; visibility: hidden; padding: 2px 5px 2px 5px; background-color: #d03030; color: white;"></div>',
			'buttonrow'  => '<tr><td colspan="2"></td><td>%s</td></tr>',
			'button'     => '<button type="submit" ><span>%s</span></button>',
			
		  ),
	 
	 );

	$dst_messagecontainerlayout = "<div class=\"errors\">%s</div>\n";
	$dst_messagelayout          = "<div class=\"error\">%s</div>\n";

 	$dst_errorstyle      = ' class="error" ';

  	$dst_formopenlayout  = "<form enctype=\"multipart/form-data\" target=\"%target%\" id=\"%id%\" name=\"%name%\" action=\"%action%\" %onsubmit% method=\"%method%\">\n";
 	$dst_formcloselayout = "</form>\n";
	
	
	
	