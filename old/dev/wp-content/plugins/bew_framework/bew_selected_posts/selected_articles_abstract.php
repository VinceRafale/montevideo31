<?php


/*
 $dst_sa = new dstSelectedArticlesAbstract($args);
 
 if(!$dst_sa) // erreur
 else{
 
 	echo $dst_sa->html;
 	
 }
 */

		class dstSelectedArticlesAbstract {
		
		
			public $args; // tableau de tous les arguments
			public $queries; // tableau des requetes de sélection des articles à executer
			public $html;
			public $transient_key;
			public $errors;
		
			
			function __construct($args, $defaults = false, $extend = false)
			{
			
					
								
					if(!$defaults)
					
					$defaults = array(
						'slugs' => '',
						'queries' => false, // use slugs for simple query, queries for custom queries.
						'posts_per_page' => -1,
						'display_title' => true,
						'display_tb' => true,
						'display_content' => true,
						'custom_class' => '',
						'excerpt_only' => false,
						'transient_time' => 600,
						'disable_transient' => false,
						'display_date' => false,
						'display_readmore' => false,
						'link_title' => false,
						'link_all' => false,
						'date_format' => 'l n F Y',
						'structure' => 'full', // or 'light'
						
						'post_status' => 'publish', 
						'caller_get_posts' => 1, 
						'cache_results' => false,
						'order' => 'DESC',
						'orderby' => 'ID',
						'start' => 1
						
					);
					
					
					if(is_array($extend)){
					
						foreach($extend as $k => $v) $defaults[$k] = $v; //extension ou réécriture.
					
					}
					
					
					
					
					
					$this->args = wp_parse_args( $args, $defaults );
					
					$this->extract_args();
					
					if(defined('ICL_LANGUAGE_CODE')) $icllang = '_'.ICL_LANGUAGE_CODE; else $icllang = "";
					
					$this->transient_key = get_class()."$icllang_";
					
					foreach ($this->args as $k => $v)  $this->transient_key .= "&{$k}={$v}"; 
					
					//echo $this->transient_key;
					
					
					
					// si transient demandé et dispo
					if(!$this->args['disable_transient'] && $this->get_transient()) return true;
					
	
					
					// sinon on compose le html
					
					if(!$this->build_query()) return false;
					if(!$this->render()) return false;
				
					if(!$this->args['disable_transient']) $this->set_transient();
					
					return true;
					
					

			}
			
			
			function extract_args(){
			
				foreach ($this->args as $a => $v) $this->$a = $v;
			
			
			}
			
			function __toString(){
			
			
				if(!empty($this->errors))
				 foreach($this->errors as $err) $this->html .= "<p>$err</p>";
				return $this->html;
			
			}
			
			function set_transient(){
			
					set_transient($this->transient_key, $this->html, $this->args['transient_time']);
					
			}
			
			
			function get_transient(){
			
					$this->html = get_transient($this->transient_key);
					
					if(!$this->html) return false;
					
					return true;
					
			}
			
			
			function build_query(){
				
				$q = array();
				
				foreach($this->args as $k => $v){
				
					
					$v = trim($v);
					if(!empty($v)) $q[$k] = $v;
				
				
				}
				
				if(is_array($this->args['queries'])){
				
					$this->queries[] = $this->args['queries'];
				
				}
				
				elseif(strlen(trim($this->args['slugs']))>1){
					
					
					
					
					// catégories slugs
					$cat_slugs = explode(',', trim($this->args['slugs']) );
					
					
					
					if(sizeof($cat_slugs) > 0){
						
						foreach ($cat_slugs as $slug) {  
								
							$this->queries[] = array_merge($q, array('category_name' => $slug));	
															
						} // end foreach
							
					} else {
					$this->errors[] .= 'Slugs parameter is incorrect';
					return false;
					}	// end size of
				}
			
			else $this->queries[] = $q;
			
				return true;
}
				
				
			
			
			function render(){
			
			
			
				ob_start();
				
				if($this->structure == 'light')
					$begining .= "<span class='selected_article ".$this->args['custom_class']."'>";
					
				else $begining .= "<div class='".$this->args['custom_class']."'><ul class='selected-articles ".$this->args['custom_class']."' >";
				
				
				$first_img = 0;
	
						// boucle sur les requetes par categories
						
				$count = 0;
						
				foreach($this->queries as $q){
						
					$r = new WP_Query($q);
							
						if ($r->have_posts()) :
							
						echo $begining;
						$begining = "";
					?>
					
							
							<?php  
							
							
							
							// boucle sur la requete en cours
							while ($r->have_posts()) : $r->the_post(); 
							
								$count += 1;
								if( $count < $this->start ) continue;
								
								$row_proc = 'render_post_html_'.$this->structure;
								
								$this->$row_proc();
							
							?>
                            
                            
							
						
						<?php endwhile; endif;?>
						
				<?php } // foreach $queries as $q 
				
				
				
				
						 if($begining == ""):
						
							if($this->structure == 'light') echo '</span>';
							
							else echo '</ul></div>';
						
						 endif; 
					
					
					$this->html = ob_get_clean();
					
					
					
					return true;
			
			
			}
			
			function render_post_html_light(){
			
			
				if($this->link_all ||  ($this->link_title && $this->display_title)) { echo "<a class='post_link' href='"; the_permalink(); echo "'>"; }
			
				if($this->display_title) 
					
					{ echo '<span class="post_title">'; the_title(); echo "</span>"; 
						
						if($this->link_title) echo '</a>';
					}
				
				if($this->display_content && $this->excerpt_only) 
				
					{ echo '<span class="post_content post_excerpt">'; the_excerpt(); echo "</span>"; }
					
				if($this->display_content && !$this->excerpt_only) 
				
					{ echo '<span class="post_content">'; the_content(); echo "</span>"; }
				
				if($this->display_date) 
				
					{ echo '<span class="post_date">'.get_the_time($this->date_format)."</span>"; }
					
				
				if($this->link_all && !$this->link_title) echo '</a>';
			
			
			}
		
			
			function render_post_html_full(){

				if($this->args['display_title'] || $this->args['display_content'] || ($this->args['display_tb'] && has_post_thumbnail()) ): ?>
								
					<?php
                    $meta_values = get_post_meta(get_the_ID(), 'partenaires', true);			
                
                    if($meta_values):
                        $addNewClass = ' bg_color_and_size ';
                        $first_img ++;
                    else:
                        $addNewClass = '';
                    endif; // meta_values
                    
                
                    ?>
                                    
                    <li class="selected-article-<?php echo $this->args['custom_class']; ?> selected-article-<?php the_ID();  ?>">
					<?php if(current_user_can('manage_options')) edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' );?>
                    
                    <?php 
                    if($this->args['display_tb']): 
                        if ( has_post_thumbnail(get_the_ID() ) ) {
                    ?>
                    
                        <div class="post_featured_image tb_size_widget">
                        <?php if($this->link_all ||  $this->link_title) { echo "<a class='post_link_feat_image' href='"; the_permalink(); echo "'>"; }?> 
                        <?php 
                            if($meta_values): 
                                the_post_thumbnail(array('150', '150', false));
                            else:
                                the_post_thumbnail(array('287', '200', true));	
                            endif; 
                        ?>
                        <?php if($this->link_all ||  $this->link_title) { echo "</a>"; } ?>
                        </div>
                         
                    <?php 
                        } 
                    endif;
                    ?>
                    
                    <?php  
                    if($this->args['display_title']):
                        if ( get_the_title() ) { 
						
							if($this->link_all ||  $this->link_title) { echo "<a class='post_link' href='"; the_permalink(); echo "'>"; }
			
                    ?>
                       		 <div class="post_title"><?php the_title(); ?></div> 
                       
                    <?php 
					
							if($this->link_all ||  $this->link_title) { echo "</a>"; }
					
                        } 
                    endif; 
                    ?>
                    <?php if($this->args['display_date']) : ?>
                    <div class="date_time">
                        	
                         <?php the_time($this->date_format); ?>
                           
                     </div>
                     <?php endif; ?>
                    <?php if($this->args['display_content']): ?>
                    <div class="post_content">
                        <?php 
                        
                        //affichage de la date de création du post
                        
                        
                        if($this->args['excerpt_only']) the_excerpt(); else the_content(); 
                        
                        ?>
                    </div>
                    
                    <?php endif; ?>
                    
                    </li>
                    
                    <?php endif; // end affichage contenu li 

				}

		
} // end class 


