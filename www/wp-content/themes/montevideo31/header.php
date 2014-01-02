<?php
 ob_start(); 
 global $current_user;
 
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<title>
		<?php
		global $page, $paged;
		wp_title( '|', true, 'right' );
		bloginfo( 'name' );
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			echo " | $site_description";
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );
		?>
	</title>
	<?php
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'start_post_rel_link');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
	remove_action('init', 'wp_admin_bar_init');
	wp_head(); 
	?>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url' ); ?>" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_directory') ?>/js/tipsy/tipsy.css">
	</script>
	<link rel="shortcut icon" href="<?php bloginfo( 'stylesheet_directory' ); ?>/favicon.ico" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	
	<?php
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
	?>
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/js/cufon-yui.js"></script>
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/js/bold.js"></script>
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/js/normal.js"></script>
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/js/tipsy/jquery.tipsy.js"></script>
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/js/various_functions.js" ></script>
	
	<?	if( is_home() ){ ?>
		<script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/js/jquery.tools.min.js"></script>
		<script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/js/mv31.home.js"></script>
		<script type="text/javascript">
    	jQuery(document).ready(function() {
		
			//trio_homepage_detail
			jQuery('.cal_desc').tipsy({
				live: true,
				fallback: 'problème',
				opacity: 0.95
			});
			
			jQuery('.amrcol1.lastcol a').tipsy({
				live: true,
				fallback: 'problème',
				opacity: 0.95
			});
			
			jQuery('.cal_desc').click(function(){
				return false;
			});
						
		});
		</script>
	<?php
	}
	?>
	<script type="text/javascript">
	$(document).ready(function() {
        //      console.log("ok");
        // jQuery("#cal_subscribe").click(function(){
        //  jQuery("#popover").toggle();
        // });
        //  
        // var mouse_is_inside = false;
        // 
        // $('#popover').hover(function(){ 
        //          mouse_is_inside=true; 
        //      }, function(){ 
        //          mouse_is_inside=false; 
        //      });
        // 
        //      $("body").mouseup(function(){ 
        //          if(! mouse_is_inside) $('#popover').fadeOut();
        //      });
        //  
		
		// nom du shabbat dans le calendrier header
		var self = jQuery('#top_sidebar_nom_shabbat .gce-page-list .gce-list .gce-feed-14 span');
		if( self.text().length > 11 ){
			self.html( "<a href='#' original-title='" + self.text() + "' id='top_sidebar_nom_shabbat_text' >"+ self.text().substring(0,9) + "...</a>" );
		}
		
		jQuery('#top_sidebar_nom_shabbat_text').tipsy({
			live: true,
			fallback: 'problème',
			opacity: 0.95
		});

	});
	</script>
	
	
	
    <script type="text/javascript">
    	Cufon.replace('h1.entry-title', { fontFamily: 'normal' });
		Cufon.replace('.propose_article_text', { fontFamily: 'bold' });
		Cufon.replace('h3.widget-title', { fontFamily: 'bold' });
    	Cufon.replace('h2', { fontFamily: 'normal' });
		Cufon.replace('.carousel h3.post_title', { fontFamily: 'normal' });
		//Cufon.replace('.carousel .tab .post_title', { fontFamily: 'normal' });
		Cufon.replace('li.selected-article-trio > div.post_title', { fontFamily: 'bold' });
		Cufon.replace('a.trio_big_link', { fontFamily: 'normal' });
		Cufon.replace('#main.gce-list-title', { fontFamily: 'bold' });
		Cufon.replace('.cufon', { fontFamily: 'bold' });
		Cufon.replace('.carroussel_eve-title', { fontFamily: 'normal' });
		Cufon.replace('.gce-widget-grid .gce-calendar th abbr', { fontFamily: 'normal' });
		Cufon.replace('.gce-page-grid .gce-calendar th abbr', { fontFamily: 'normal' });
		Cufon.replace('.faire-part-categorie', { fontFamily: 'normal' });
	</script>
	</head>
<body <?php //body_class(); ?> >
	<style type="text/css" media="screen">
	html { margin-top: 0px !important; }
	* html body { margin-top: 0px !important; }
	</style>
<div id="background">
<div id="wrapper" class="hfeed">
	<div id="header">
		<div id="masthead">
        
        	
        	<div id="top_access" role="navigation">
            
            <div class="top_tools_left">
    		<!--	<span class="top_tools"><?php _e('Language:'); ?></span>
                <a href="#" class="flag top_tools"><span>Fran&ccedil;ais</span></a>
                <a href="#" class="flag top_tools" rel="tipsy" title="Available Soon..."><span>English</span></a>
                -->
                <img id="top_tools_rss" src="<?php bloginfo('stylesheet_directory') ?>/images/rss_icon.png">
                <a href="http://feeds.feedburner.com/Montevideo" target="_blank"  class="rss top_tols"><?php _e('Flux RSS'); ?></a>
                <span class="separator">&nbsp;</span>
                <a href="#" id="cal_subscribe">Inscription aux Calendriers</a>
                <div id="popover">
                    <ul>
                        <li><a href="webcal://www.google.com/calendar/ical/synagoguemontevideo31%40gmail.com/private-390482742d73472808fc505e3797a420/basic.ics">Offices de chabbat - Montev</a></li>
                        <li><a href="webcal://www.google.com/calendar/ical/uc3go4uheplgh05cplvheba9j0%40group.calendar.google.com/private-bd7155dc0e7684faf320b2c8caea573d/basic.ics">Cours - Montev</a></li>
                        <li><a href="webcal://www.google.com/calendar/ical/uvjb9rb17lmm1pbigb9jct4q9o%40group.calendar.google.com/private-8644d4d8c3c4ed8a9cd86d7eafd9fafd/basic.ics">F&#234;tes - Montev</a></li>
                        <li><a href="webcal://www.google.com/calendar/ical/ithc73ihl65emcm2r9f0bgj6e0%40group.calendar.google.com/private-70f48cd67f21a0b6e6394536b643fb07/basic.ics">Horaires Shabbat - Montev</a></li>
                        <li><a href="webcal://www.google.com/calendar/ical/d3i3rvu1d23ks3h2tinev1mhvk%40group.calendar.google.com/public/basic.ics">Horaires des offices - Montev</a></li>
                        <li><a href="webcal://www.google.com/calendar/ical/da9680ge4g97uoqoaesi083v60%40group.calendar.google.com/private-b38ec801431a8bb087f5c6fb15eb6e59/basic.ics">Ev&eacute;nements - Montev</a></li>
                        
                        <li><a href="webcal://www.google.com/calendar/ical/%23hebrew%40group.v.calendar.google.com/public/basic.ics">Dates en H&eacute;breu</a></li>
                        
                    </ul>
                </div>
            
            </div>
            <div class="top_tools_right">
            <?php if(!is_user_logged_in()): ?>
            	<a href="<?php echo apply_filters('bew_register'); ?>" class="top_tools user"><span class="icon_user"></span><?php _e('Register'); ?></a>
            	<span class="separator">&nbsp;</span><a href="<?php echo apply_filters('bew_login'); ?>" class="top_tools user"><span class="icon_connexion"></span><?php _e('Log in'); ?></a>
            <?php else: ?>
            	<?php
				
					if(strlen($current_user->first_name) && strlen($current_user->last_name)) $display_name = $current_user->first_name.' '.$current_user->last_name; else $display_name = $current_user->display_name;
				
				 echo '<span class="top_bar_text">'.__('Bienvenue').'</span>  <a class="display_name top_tools" href="/mon-profil">'.' '.ucfirst($display_name).'</a>';
				 ?><span class="separator">&nbsp;</span><a href="<?php echo apply_filters('bew_profile'); ?>" class="top_tools user"><span class="icon_user"></span><?php _e('Mon profil'); ?></a>
            	<span class="separator">&nbsp;</span><a class="top_tools user" href="<?php echo wp_logout_url( get_bloginfo('url') ); ?>" class="user"><span class="icon_connexion"></span><?php _e('Log out'); ?></a>
            <?php endif; ?>
            
            	<div class="top_tools user facebook-connect">&nbsp;</div>
            
            </div>
     
            </div>
            
            
            </div>
			<div id="branding" role="banner">
				<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
				<<?php echo $heading_tag; ?> id="site-title">
					
						<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><span><?php bloginfo( 'name' ); ?></span></a>
					
				</<?php echo $heading_tag; ?>>
				<div id="site-description"><span><?php bloginfo( 'description' ); ?></span></div>
                <div id="top_sidebar">
                    <?php dynamic_sidebar('top-sidebar'); ?>
                    
                    <div id="top_sidebar_nom_shabbat" class="top_sidebar_item">
                    	<?php
						echo do_shortcode("[google-calendar-events id='14' type='list' title='' max='1' order='desc' ]");
   						?>
   						<div id="top_sidebar_date_shabbat" >
   							<?php
							echo do_shortcode("[google-calendar-events id='16' type='list' title='' max='1' order='desc' ]");
   							?>
   						</div>
					</div>
                    
                    <div id="top_sidebar_middle">
                    
	                    <div id="top_sidebar_debut_shabbat" class="top_sidebar_item">
	                    	Allumage des bougies : 
							<?php   
							echo do_shortcode("[google-calendar-events id='13' type='list' title='' max='1']");
	   						?> 
						</div>
						
						<div id="top_sidebar_chaharit" class="top_sidebar_item">
	                    	Chaharit : 
							<?php   
							echo do_shortcode("[google-calendar-events id='15' type='list' title='' max='1']");
	   						?> 
						</div>
					
                    </div>
                    
					<div id="top_sidebar_minha" class="top_sidebar_item">
                    	Minha : 
						<?php   
						echo do_shortcode("[google-calendar-events id='15' type='list' title='' max='1' order='desc' ]");
   						?> 
					</div>
					
					
					
					<div id="top_sidebar_fin_shabbat" class="top_sidebar_item">
                    	Maariv et fin de Chabbat : 
						<?php
						echo do_shortcode("[google-calendar-events id='13' type='list' title='' max='1' order='desc' ]");
   						?> 
					</div>
					
					
                    
                    
                    <div id="shabbat_schedule">
                        <?php   
    				//	echo do_shortcode("[google-calendar-events id='12' type='list' title=' ' max='2']");
    					?>    					
                    </div>
                </div>

				
			</div><!-- #branding -->

			<div id="access" role="navigation">
			  <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
				<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentyten' ); ?>"><?php _e( 'Skip to content', 'twentyten' ); ?></a></div>
				<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
				<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
			</div><!-- #access -->
		</div><!-- #masthead -->
	</div><!-- #header -->

	<div id="main">
<?php do_action('bew_messages'); ?>