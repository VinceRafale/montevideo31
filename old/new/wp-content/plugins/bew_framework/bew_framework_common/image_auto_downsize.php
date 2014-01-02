<?php



add_filter('image_downsize', 'dst_image_downsize', 20, 3);

 
 
function dst_image_downsize($b, $id, $size){


	

	$img_url = wp_get_attachment_url($id);
	$meta = wp_get_attachment_metadata($id);
	$width = $height = $size_wh = 0;
	$is_intermediate = false; 
	
	
	
	if(is_array($size)){
	
	$size_wh = array('width'=>$size[0], 'height'=>$size[1],  'crop'=> (isset($size[2]) ? $size[2] : false));
		
		if($size[2] == true)  $size[2] = 'c'; else  $size[2] = 'n';
		$size = join('x', $size);
		
		if(function_exists('add_image_size')) add_image_size( $size, $size_wh[0], $size_wh[1], $size_wh[2] );
		
	} else {
	
		//$sizes = get_intermediate_image_sizes(); TODO : add support for wp default image sizes
		global $_wp_additional_image_sizes;
		
		$size_wh =  $_wp_additional_image_sizes[$size];
		
		
	}
		

	$meta = wp_get_attachment_metadata($id);
	


	if(isset($meta['sizes'][$size])){
	// la taille demandée existe
	
		$img_url = str_replace(basename($img_url), $meta['sizes'][$size]['file'], $img_url);
		
		$width = $meta['sizes'][$size]['width'];
		$height = $meta['sizes'][$size]['height'];
		
		$is_intermediate = true;
	
	
	}else{
	
	
	  // it does not exists, so create it ! (here wordpress 3 fallback with the original image)
	
		
		$updir = wp_upload_dir();
		

		
		$file_path = str_replace($updir['baseurl'], $updir['basedir'],$img_url);
		
	
		//if(substr($file_path, 0, 1) == '/' ||substr($file_path, 0, 1) == '\\') $file_path = substr($file_path, 1);
		


		 
		$resized_file = image_resize($file_path, $size_wh['width'], $size_wh['height'], $size_wh['crop'], $size);
	
		if(	!is_wp_error($resized_file) && $resized_file && $info = getimagesize($resized_file))  {
			// update attachement meta to inform wp that a new size is available
			 
			$a_meta = wp_get_attachment_metadata($id);
			
			$new_size_metadata = array('file' => basename($resized_file), 'width' => $info['0'], 'height' => $info['1']);
			
			$a_meta["sizes"][$size] = $new_size_metadata;
			
	
			
			if(wp_update_attachment_metadata($id, $a_meta )){		
		 	 
				// if creation successful, prepare the new intermediate size for output
				
				$img_url = str_replace(basename($img_url), basename($resized_file), $img_url);
				$width = $info['0'];
				$height = $info['1'];
				
				$is_intermediate = true;
			 }
		
		}
	
	}
	if ( !$width && !$height && isset($meta['width'], $meta['height']) ) {
		// if everything above failed (probably image resizing failed due to file permissions, image file size, etc...), just serve the original image
		$width = $meta['width'];
		$height = $meta['height'];
	}

	if ( $img_url) {
		// we have the actual image size, but might need to further constrain it if content_width is narrower
		list( $width, $height ) = image_constrain_size_for_editor( $width, $height, $size );

		return array( $img_url, $width, $height, $is_intermediate );
	}
	

	return false;



}


// template tag retrieving the url of thumbnail of a post

function  get_post_thumbnail_url($post_id, $size, $icon = false){

	global $id;
	$post_id = ( NULL === $post_id ) ? $id : $post_id;
	$post_thumbnail_id = get_post_thumbnail_id( $post_id );
	$src = wp_get_attachment_image_src($post_thumbnail_id, $size, $icon);
	
	return $src;

}