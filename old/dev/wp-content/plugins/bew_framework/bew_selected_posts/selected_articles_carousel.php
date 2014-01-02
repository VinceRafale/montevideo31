<?php



class dstSelectedPostsCarousel extends dstSelectedArticlesAbstract {
		
		
			public $tabs_html;
			public $slides_html;
			
			
			
			function __construct($args){
				
				
				$extend = array(
					
					'display_tb_in_content' => false,
					'small_tb_size' => array('60', '60', true),
					'normal_tb_size' => array('200', '200', true),
					
				);
				
				
				return parent::__construct($args, false, $extend);
			
			
			}
				
				
			
			function render(){
			
				
				
				$begining .= "<div class='".$this->args['custom_class']."'>";
				
				
				$first_img = 0;
	
						// boucle sur les requetes par categories
						
				foreach($this->queries as $q){
						
					$r = new WP_Query($q);
							
						if ($r->have_posts()) :
							
						$this->html.= $begining;
						$begining = "";
					?>
					
							
							<?php  
							
							// boucle sur la requete en cours
							while ($r->have_posts()) : $r->the_post(); 
							
								$this->render_post_html();
							
							?>

						<?php endwhile; endif;?>
						
				<?php } // foreach $queries as $q ?>
                
                		
				
						<?php if($begining == ""):
                        
						$this->html.= "<div class='tabs'> $this->tabs_html  </div>";
						$this->html.= "<div class='slides'> $this->slides_html  </div>";
                        
						$this->html.= "</div>";
						
						 endif; 
					
					
					
					
					
					
					return true;
			
			
			}
			
	
			
			
			function render_post_html(){
			
				

				if($this->args['display_title'] || $this->args['display_content'] || ($this->args['display_tb'] && has_post_thumbnail()) ): ob_start(); ?>
                
								
					                                   
                  
               
                  <a class="tab<?php if(empty($this->tabs_html)) echo ' current'; ?>" href="<?php the_permalink(); ?>">
                  
							
                            
                            <?php 
                            if($this->args['display_tb']): 
                                if ( has_post_thumbnail(get_the_ID() ) ) {
                            ?>
                            
                   
                     
                                <?php 
                                    
                                        the_post_thumbnail($this->small_tb_size);
                              
                                ?>
                          
                        
                                 
                            <?php 
                                } 
                            endif;
                            ?>
                            
                            <?php  
                            if($this->args['display_title']):
                                if ( get_the_title() ) { 
                            ?>
                                <span class="post_title"><?php the_title(); ?></span>
                            <?php 
                                } 
                            endif; 
                            ?>
                 </a>        
        
                    
                <?php $this->tabs_html .= ob_get_clean(); ob_start(); ?>
                    
                    
                 <div class="slide <?php if(empty($this->slides_html)) echo 'slide-first'; ?> <?php echo get_post_meta(get_the_ID(), 'custom_class', true) ?>" >
                    		   <?php if(current_user_can('manage_options')) edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' );?>
                             <?php  
                            if($this->args['display_title']):
                                if ( get_the_title() ) { 
                            ?>
                            
                            <?php 
							
							$no_tb_in_content = get_post_meta(get_the_ID(), 'no_tb_in_content', true);
							
							if($this->display_tb_in_content  && !$no_tb_in_content): ?>
                            
                            	<div class="post_featured_image">
                                
                                <?php the_post_thumbnail($this->normal_tb_size); ?>
                                
                                </div>
                            
                            <?php endif; ?>
                            
                                <h3 class="post_title"><?php the_title(); ?></h3>
                            <?php 
                                } 
                            endif; 
                            ?>
                    
							<?php if($this->args['display_content']): ?>
                            
                            <div class="post_content">
                            
                            
                            
                                <?php 
                                
        
                                
                                if($this->excerpt_only) the_excerpt(); else the_content(); 
                                
                                ?>
                            </div>
                            
                            <?php endif; ?>
                  
                  </div>  
                  
                  <?php $this->slides_html .= ob_get_clean(); ?>
   
   					
                    <?php endif; 

				}

		
} // end class 




