<?php
/**
 * The template to display the widgets area in the header
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

// Header sidebar
$muji_header_name = muji_get_theme_option('header_widgets');
$muji_header_present = !muji_is_off($muji_header_name) && is_active_sidebar($muji_header_name);
if ($muji_header_present) { 
	muji_storage_set('current_sidebar', 'header');
	$muji_header_wide = muji_get_theme_option('header_wide');
	ob_start();
	if ( is_active_sidebar($muji_header_name) ) {
		dynamic_sidebar($muji_header_name);
	}
	$muji_widgets_output = ob_get_contents();
	ob_end_clean();
	if (!empty($muji_widgets_output)) {
		$muji_widgets_output = preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $muji_widgets_output);
		$muji_need_columns = strpos($muji_widgets_output, 'columns_wrap')===false;
		if ($muji_need_columns) {
			$muji_columns = max(0, (int) muji_get_theme_option('header_columns'));
			if ($muji_columns == 0) $muji_columns = min(6, max(1, substr_count($muji_widgets_output, '<aside ')));
			if ($muji_columns > 1)
				$muji_widgets_output = preg_replace("/<aside([^>]*)class=\"widget/", "<aside$1class=\"column-1_".esc_attr($muji_columns).' widget', $muji_widgets_output);
			else
				$muji_need_columns = false;
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo !empty($muji_header_wide) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<div class="header_widgets_inner widget_area_inner">
				<?php 
				if (!$muji_header_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($muji_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'muji_action_before_sidebar' );
				muji_show_layout($muji_widgets_output);
				do_action( 'muji_action_after_sidebar' );
				if ($muji_need_columns) {
					?></div>	<!-- /.columns_wrap --><?php
				}
				if (!$muji_header_wide) {
					?></div>	<!-- /.content_wrap --><?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
?>