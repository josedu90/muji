<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

if (muji_sidebar_present()) {
	ob_start();
	$muji_sidebar_name = muji_get_theme_option('sidebar_widgets');
	muji_storage_set('current_sidebar', 'sidebar');
	if ( is_active_sidebar($muji_sidebar_name) ) {
		dynamic_sidebar($muji_sidebar_name);
	}
	$muji_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($muji_out)) {
		$muji_sidebar_position = muji_get_theme_option('sidebar_position');
		?>
		<div class="sidebar <?php echo esc_attr($muji_sidebar_position); ?> widget_area<?php if (!muji_is_inherit(muji_get_theme_option('sidebar_scheme'))) echo ' scheme_'.esc_attr(muji_get_theme_option('sidebar_scheme')); ?>" role="complementary">
			<div class="sidebar_inner">
				<?php
				do_action( 'muji_action_before_sidebar' );
				muji_show_layout(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $muji_out));
				do_action( 'muji_action_after_sidebar' );
				?>
			</div><!-- /.sidebar_inner -->
		</div><!-- /.sidebar -->
		<?php
	}
}
?>