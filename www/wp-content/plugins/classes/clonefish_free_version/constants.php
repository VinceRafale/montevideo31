<?php

define('CF_EMAIL', '/^[\._0-9A-Za-z-]+@[0-9A-Za-z][-0-9A-Za-z\.]*\.[a-zA-Z]{2,4}$/' );
define('CF_URL', 
        "/^(https?:\/\/)" 
        . "(([0-9A-Za-z_!~*'().&=+$%-]+: )?[0-9A-Za-z_!~*'().&=+$%-]+@)?" //user@ 
        . "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP- 199.194.52.184 
        . "|" // allows either IP or domain 
        . "([0-9A-Za-z_!~*'()-]+\.)*" // tertiary domain(s)- www. 
        . "([0-9A-Za-z][0-9A-Za-z-]{0,61})?[0-9A-Za-z]\." // second level domain 
        . "[A-Za-z]{2,6})" // first level domain- .com or .museum 
        . "(:[0-9]{1,4})?" // port number- :80 
        . "((\/?)|" // a slash isn't required if there is no file name 
        . "(\/[0-9A-Za-z_!~*'().;?:@&=+$,%#-]+)+\/?)$/"
);

?>