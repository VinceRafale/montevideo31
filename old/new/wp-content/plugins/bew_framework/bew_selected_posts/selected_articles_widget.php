<?php





class dst_selected_articles_Widget extends WP_Widget {


	private $dst_checkboxes;
	
	
	

	function  dst_selected_articles_Widget() {
		$widget_ops = array('classname' => 'selected-articles-widget', 'description' => __( "Displays articles selected by their IDs") );
		$this->WP_Widget('selected-articles-widget', __('Selected Articles Widget'), $widget_ops);
		$this->alt_option_name = 'selected-articles-widget';
		
		$this->dst_checkboxes = array(
	
		'display_title' => __('Display articles title'),
		'display_content' => __('Display articles content'),
		'display_tb' => __('Display articles thumbnail'),
		'display_date' => __('Display date and hour of post\'s publication'),
		'excerpt_only' => __('Show excerpt only')
		
		);
		

				
		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}


	
	function widget($args, $instance) {
	
		
	
		extract($args);
		
		$title = apply_filters('widget_title', empty($instance['title']) ? false : $instance['title'], $instance, $this->id_base);
		
		if(empty($instance['title'])) $title = false;
		
	
	
		


		
		$custom_class_widget = '';
		$custom_class_widget = isset($instance['custom_class']) ? $instance['custom_class'] : '' ;
			
		$before_widget = str_replace('widget-container', 'widget-container '.$custom_class_widget, $before_widget);

		$begining = $before_widget.($title ? $before_title . $title . $after_title : "");
		
		$args = array(
		
						'slugs' => $instance['cat_slugs'],
						'posts_per_page' => $number,
						'display_title' => $instance['display_title'],
						'display_tb' => $instance['display_tb'],
						'display_content' => $instance['display_content'],
						'display_date' => $instance['display_date'],
						'custom_class' => $instance['custom_class'],
						'excerpt_only' => $instance['excerpt_only'],
						'transient_time' => 600,
						'disable_transient' => current_user_can('edit_posts')
						
		);
	
    	
		
		$dst_sa = new dstSelectedArticlesAbstract($args);
		
		if($dst_sa) echo $begining.$dst_sa.$after_widget;
			

	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		
		$instance['cat_slugs'] = $new_instance['cat_slugs'];
		$instance['custom_class'] = $new_instance['custom_class'];
		
		foreach($this->dst_checkboxes as $id => $label) $instance[$id] = $new_instance[$id];
		
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries']) )
			delete_option('widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		//delete_transient('dst_taw_Widget');
	}


	

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		if ( !isset($instance['number']) || !($number = (int) $instance['number']) )
			$number = 5;
			
		
		if (isset($instance['cat_slugs'])) $cat_slugs = $instance['cat_slugs']; else $cat_slugs = "";
		
	
		
		$custom_class= isset($instance['custom_class']) ? $instance['custom_class'] : '';
		
		
		$classname= isset($instance['classname']) ? $instance['classname'] : '';
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
        
        <p><label for="<?php echo $this->get_field_id('cat_slugs'); ?>"><?php _e('Display posts from categories:'); ?></label><br />

        <small><?php _e('Separate category slugs with blanks'); ?></small>
		<textarea id="<?php echo $this->get_field_id('cat_slugs'); ?>" name="<?php echo $this->get_field_name('cat_slugs'); ?>" ><?php echo $cat_slugs; ?></textarea>
        </p>
        
        
       
       
       	<?php foreach ($this->dst_checkboxes as $id => $label): ?>
        
        
        	<p>
			<?php echo $label; ?>
            
            <input type="hidden" name="<?php echo $this->get_field_name($id); ?>" id="<?php echo $this->get_field_name($id); ?>" value="0" />
            <input type="checkbox" name="<?php echo $this->get_field_name($id);  ?>" id="<?php echo $this->get_field_name($id); ?>"  value="1" <?php 
			
				if(!($instance[$id] === '0') ) echo ' checked="checked" ';
			
			
			 ?> />
            </p>
        
        
        <?php endforeach; ?>         
        
        
        <p><label for="<?php echo $this->get_field_id('custom_class'); ?>"><?php _e('Widget class:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('custom_class'); ?>" name="<?php echo $this->get_field_name('custom_class'); ?>" type="text" value="<?php echo $custom_class; ?>" /></p>
<?php
	} 
} 
        
add_action('widgets_init', 'register_dst_selected_articles_Widget');

function register_dst_selected_articles_Widget() { error_reporting(1);  register_widget('dst_selected_articles_Widget'); }
