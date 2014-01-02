<?php



add_filter('the_content', 'dst_selected_articles_in_article', 9999);



function dst_selected_articles_in_article($content){

	// slugs=catslug,catslug,catslug&number=0&display_title=true&display_tb=true&display_content=true&custom_class=class
				
	$regex = '#{sa}(.*?){/sa}#s';
	
	$content = preg_replace_callback($regex, 'dst_selected_articles_in_article_cb', $content);

	return $content; 
	
}


function dst_selected_articles_in_article_cb($match){

	
	$match[1] = htmlspecialchars_decode($match[1]);
	
	if(current_user_can('edit_posts')) $match[1].= '&disable_transient=true';
	
	//var_dump($match);
	
	$dst_sa = new dstSelectedArticlesAbstract($match[1]); 
	
	return (string)$dst_sa;
	
	

}