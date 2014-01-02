<?php

/*
Plugin Name: BEW Culture framework - Selected posts 
Plugin URI: 
Description: Enables the use of editables posts in the theme, in widgets, or in another post through convenient quicktag.

Author: Adrien Menoret
Author URI: mailto: adrien.menoret@gmail.com 

Version: 1.6
*/


require_once(dirname(realpath(__FILE__)).'/bew_selected_posts/selected_articles_abstract.php');
require_once(dirname(realpath(__FILE__)).'/bew_selected_posts/selected_articles_extract.php');
require_once(dirname(realpath(__FILE__)).'/bew_selected_posts/selected_articles_widget.php');
require_once(dirname(realpath(__FILE__)).'/bew_selected_posts/selected_articles_in_content.php'); 
require_once(dirname(realpath(__FILE__)).'/bew_selected_posts/selected_articles_in_title.php'); 
require_once(dirname(realpath(__FILE__)).'/bew_selected_posts/selected_articles_carousel.php');
require_once(dirname(realpath(__FILE__)).'/bew_selected_posts/selected_articles_calendar.php');
require_once(dirname(realpath(__FILE__)).'/bew_selected_posts/selected_articles_calendar_widget.php');
require_once(dirname(realpath(__FILE__)).'/bew_selected_posts/selected_articles_calendar_in_content.php');