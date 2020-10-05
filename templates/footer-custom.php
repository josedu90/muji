<?php
/**
 * The template to display default site footer
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0.10
 */

$muji_footer_id = str_replace('footer-custom-', '', muji_get_theme_option("footer_style"));
if ((int) $muji_footer_id == 0) {
	$muji_footer_id = muji_get_post_id(array(
												'name' => $muji_footer_id,
												'post_type' => defined('TRX_ADDONS_CPT_LAYOUTS_PT') ? TRX_ADDONS_CPT_LAYOUTS_PT : 'cpt_layouts'
												)
											);
} else {
	$muji_footer_id = apply_filters('muji_filter_get_translated_layout', $muji_footer_id);
}
$muji_footer_meta = get_post_meta($muji_footer_id, 'trx_addons_options', true);
if (!empty($muji_footer_meta['margin']) != '') 
	muji_add_inline_css(sprintf('.page_content_wrap{padding-bottom:%s}', esc_attr(muji_prepare_css_value($muji_footer_meta['margin']))));
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr($muji_footer_id); 
						?> footer_custom_<?php echo esc_attr(sanitize_title(get_the_title($muji_footer_id))); 
						if (!muji_is_inherit(muji_get_theme_option('footer_scheme')))
							echo ' scheme_' . esc_attr(muji_get_theme_option('footer_scheme'));
						?>">
	<?php
    // Custom footer's layout
    do_action('muji_action_show_layout', $muji_footer_id);
	?>
</footer><!-- /.footer_wrap -->
