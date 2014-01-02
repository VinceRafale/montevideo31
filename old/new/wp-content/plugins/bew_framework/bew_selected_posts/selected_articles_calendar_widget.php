<?php



 

class bewCalendarWidget extends WP_Widget {


	private $dst_checkboxes;
	
	
	

	function  bewCalendarWidget() {
	
		$widget_ops = array('classname' => 'bew-calendar-widget', 'description' => __( "Customizable calendar widget") );
		$this->WP_Widget('bew-calendar-widget', __('Bew Calendar Widget'), $widget_ops);
		$this->alt_option_name = 'bew-calendar-widget';
		
		$this->dst_checkboxes = array(
	
		'display_links' => __('Add links to events'),
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
		
		$custom_args = isset($instance['custom_args']) ? $instance['custom_args']: '';
	
    	
		
		$bew = new bewCalendar($custom_args);
		
		if($bew) echo $begining.$bew.$after_widget;
			

	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['custom_class'] = $new_instance['custom_class'];
		$instance['custom_args'] = $new_instance['custom_args'];
		
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
		
			
		
		if (isset($instance['custom_args'])) $custom_args = $instance['custom_args']; else $custom_args = "";
		
	
		
		$custom_class= isset($instance['custom_class']) ? $instance['custom_class'] : '';
		
		
		$classname= isset($instance['classname']) ? $instance['classname'] : '';
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        
        <p><label for="<?php echo $this->get_field_id('custom_args'); ?>"><?php _e('Custom arguments:'); ?></label><br />

		<textarea id="<?php echo $this->get_field_id('custom_args'); ?>" name="<?php echo $this->get_field_name('custom_args'); ?>" ><?php echo $custom_args; ?></textarea>
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
        
add_action('widgets_init', 'register_bewCalendarWidget');

function register_bewCalendarWidget() { error_reporting(1);  register_widget('bewCalendarWidget'); }
