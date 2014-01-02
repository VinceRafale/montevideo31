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
	// Add the blog name.
	bloginfo( 'name' );
	// Add the blog description for the home/front page.
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
	
	//wp_head(); 
	wp_deregister_script('jquery');
	wp_register_script('jquery', ("https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"), false, '1.6.4');
	wp_enqueue_script('jquery');

?>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url' ); ?>" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_directory') ?>/js/tipsy/tipsy.css"></script>
<link rel="shortcut icon" href="<?php bloginfo( 'stylesheet_directory' ); ?>/favicon.ico" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>  -->
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/js/sidebar_inputs.js" ></script>
	<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	if( is_home() ){
		?>
		<script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/js/jquery.tools.min.js">
		</script><script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/js/mv31.home.js"></script>
		<?php
	}
	?>
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/js/cufon-yui.js"></script>
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/js/bold.js"></script>
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/js/normal.js"></script>
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/js/tipsy/jquery.tipsy.js"></script>
    
    <script type="text/javascript">
    
		Cufon.replace('h1.entry-title', { fontFamily: 'normal' });
		Cufon.replace('.propose_article_text', { fontFamily: 'bold' });
		Cufon.replace('h3.widget-title', { fontFamily: 'bold' });
    	Cufon.replace('h2', { fontFamily: 'normal' });
		Cufon.replace('.carousel h3.post_title', { fontFamily: 'normal' });
		//Cufon.replace('.carousel .tab .post_title', { fontFamily: 'normal' });
		Cufon.replace('li.selected-article-trio > div.post_title', { fontFamily: 'bold' });
		Cufon.replace('a.trio_big_link', { fontFamily: 'normal' });
    
    </script>
	<script type="text/javascript">
    	function confirmation() {
			var answer = confirm("Êtes vous certain d'appliquer les choix cochés ci-dessus?")
			if (answer){
				return true;
			}else{
				return false;
			}
		}
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
			<span class="top_tools"><?php _e('Language:'); ?></span>
            <a href="#" class="flag top_tools"><span>Fran&ccedil;ais</span></a>
            <a href="#" class="flag top_tools" rel="tipsy" title="Available Soon..."><span>English</span></a>
            <a href="<?php echo bloginfo('url') ?>/feed" class="rss top_tools"><?php _e('RSS Feed'); ?></a>
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
                <div id="top_sidebar"><?php dynamic_sidebar('top-sidebar'); ?></div>

				
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