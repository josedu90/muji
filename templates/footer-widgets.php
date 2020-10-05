<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0.10
 */

// Footer sidebar
$muji_footer_name = muji_get_theme_option('footer_widgets');
$muji_footer_present = !muji_is_off($muji_footer_name) && is_active_sidebar($muji_footer_name);
if ($muji_footer_present) { 
	muji_storage_set('current_sidebar', 'footer');
	$muji_footer_wide = muji_get_theme_option('footer_wide');
	ob_start();
	if ( is_active_sidebar($muji_footer_name) ) {
		dynamic_sidebar($muji_footer_name);
	}
	$muji_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($muji_out)) {
		$muji_out = preg_replace("/<\\/aside>[\r\n\s]*<aside/", "</aside><aside", $muji_out);
		$muji_need_columns = true;
		if ($muji_need_columns) {
			$muji_columns = max(0, (int) muji_get_theme_option('footer_columns'));
			if ($muji_columns == 0) $muji_columns = min(4, max(1, substr_count($muji_out, '<aside ')));
			if ($muji_columns > 1)
				$muji_out = preg_replace("/<aside([^>]*)class=\"widget/", "<aside$1class=\"column-1_".esc_attr($muji_columns).' widget', $muji_out);
			else
				$muji_need_columns = false;
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo !empty($muji_footer_wide) ? ' footer_fullwidth' : ''; ?> sc_layouts_row sc_layouts_row_type_normal">
			<div class="footer_widgets_inner widget_area_inner">
				<?php 
				if (!$muji_footer_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($muji_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'muji_action_before_sidebar' );
				muji_show_layout($muji_out);
				do_action( 'muji_action_after_sidebar' );
				if ($muji_need_columns) {
					?></div><!-- /.columns_wrap --><?php
				}
				if (!$muji_footer_wide) {
					?></div><!-- /.content_wrap --><?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
?>