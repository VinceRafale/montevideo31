<?php



add_filter('the_title', 'dst_selected_articles_in_title', 9999); 



function dst_selected_articles_in_title($content){

	$regex = '#{sa}(.*?){/sa}#s';
	
	$content = preg_replace_callback($regex, 'dst_selected_articles_in_title_cb', $content);

	return $content; 
	
}


function dst_selected_articles_in_title_cb($match){

	
	$match[1] = htmlspecialchars_decode($match[1]);
	$match[1] = html_entity_decode($match[1]);
	
	if(current_user_can('edit_posts')) $match[1].= '&disable_transient=true';
	
	
	
	
	$dst_sa = new dstSelectedArticlesExtract($match[1]); 
	//var_dump($dst_sa);
	$result = (string)$dst_sa;
	
	if(empty($result)) return 'Erreur dans les parametres';
	else return $result;
	
	
	

}