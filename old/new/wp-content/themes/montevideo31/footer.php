<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>


	</div><!-- #main -->

	<div id="footer" role="contentinfo">
    <div id="retour_link_div">
	<a href="#header" class="retour_en_haut"><?php _e('Retour en haut') ?></a></div>
    <div id="footer_sidebar">

<?php
	/* A sidebar in the footer? Yep. You can can customize
	 * your footer with four columns of widgets.
	 */
	get_sidebar( 'footer' );
?>
</div>

<div id="footer_link"><?php //wp_nav_menu(array( 'container_class' => 'menu-footer', 'theme_location' => 'footer' )); ?><div class="clear"></div></div>
			<div class="clear"></div>
	</div><!-- #footer -->

<div class="clear"></div>
</div><!-- #wrapper -->

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	//wp_footer();
?>

<script type='text/javascript'>
jQuery(document).ready(function() {
   jQuery('a[rel=tipsy]').tipsy({fade: true, gravity: 'n'});
});
</script>

</body>
</html>
