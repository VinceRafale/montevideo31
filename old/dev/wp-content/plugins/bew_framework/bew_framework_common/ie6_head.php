<?php

add_action('wp_head', 'dts_ie6_header');

function dts_ie6_header(){
	?>
	<!--[if IE 6]> 

    <script type="text/javascript"> var thisFolder = "<?php bloginfo( 'url' ); ?>/images"; </script>
    <style type="text/css">
    
    	img, div#branding #logo a, .entry-summary p a img, div.tooltip, table#menu_ced tr td.post_status_draft span.fleche { behavior:url("<?php bloginfo( 'url' ); ?>/iepngfix.htc"); }
    	
    	#access ul li.hover  ul { display:block; }
        
    </style>
    
     <script type="text/javascript"> 
     
	
        jQuery(document).ready(function(){
               jQuery("#access ul li").hover(function(){
                       jQuery(this).addClass("hover");
                      
                     },function(){
                       jQuery(this).removeClass("hover");
                       
                     });
   		});

     </script>
     <![endif]-->
	 
	 <?php
}

