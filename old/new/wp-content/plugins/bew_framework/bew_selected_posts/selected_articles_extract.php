<?php




		class dstSelectedArticlesExtract extends dstSelectedArticlesAbstract {
		
		
		
			
			
				
				
			
			
			function render(){
			
			
				$begining = "<span class='articles-extract $this->custom_class'>";
				
				
	
						// boucle sur les requetes par categories
						
				foreach($this->queries as $q){
						
					$r = new WP_Query($q);
							
						if ($r->have_posts()) :
							
						$this->html .= $begining;
						$begining = "";
	
							foreach ($r->posts as $p) :
							
								if($this->display_title) 
									$this->html .= "<span class='post_title'>$p->post_title</span>";
								if($this->display_date) 
									$this->html .= "<span class='post_date'>".date_i18n($this->date_format, strtotime($p->post_date))."</span>";
								if($this->display_content) 
									$this->html .= "<span class='post_content'>$p->post_content</span>";
									
			
							endforeach;							
						endif;
							 
				
				 } // foreach $queries as $q 
                
                		
				
		
                        
						if($begining ==  "") $this->html .= '</span>';
						
					
					
					
					
					
					
					
					return true;
			
			
			}
			
			

			
			
		

		
} // end class 


