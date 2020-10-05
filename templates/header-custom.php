<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0.06
 */

$muji_header_css = '';
$muji_header_image = get_header_image();
$muji_header_video = muji_get_header_video();
if (!empty($muji_header_image) && muji_trx_addons_featured_image_override(is_singular() || muji_storage_isset('blog_archive') || is_category())) {
	$muji_header_image = muji_get_current_mode_image($muji_header_image);
}

$muji_header_id = str_replace('header-custom-', '', muji_get_theme_option("header_style"));
if ((int) $muji_header_id == 0) {
	$muji_header_id = muji_get_post_id(array(
												'name' => $muji_header_id,
												'post_type' => defined('TRX_ADDONS_CPT_LAYOUTS_PT') ? TRX_ADDONS_CPT_LAYOUTS_PT : 'cpt_layouts'
												)
											);
} else {
	$muji_header_id = apply_filters('muji_filter_get_translated_layout', $muji_header_id);
}
$muji_header_meta = get_post_meta($muji_header_id, 'trx_addons_options', true);
if (!empty($muji_header_meta['margin']) != '') 
	muji_add_inline_css(sprintf('.page_content_wrap{padding-top:%s}', esc_attr(muji_prepare_css_value($muji_header_meta['margin']))));

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr($muji_header_id); 
				?> top_panel_custom_<?php echo esc_attr(sanitize_title(get_the_title($muji_header_id)));
				echo !empty($muji_header_image) || !empty($muji_header_video) 
					? ' with_bg_image' 
					: ' without_bg_image';
				if ($muji_header_video!='') 
					echo ' with_bg_video';
				if ($muji_header_image!='') 
					echo ' '.esc_attr(muji_add_inline_css_class('background-image: url('.esc_url($muji_header_image).');'));
				if (is_single() && has_post_thumbnail()) 
					echo ' with_featured_image';
				if (muji_is_on(muji_get_theme_option('header_fullheight'))) 
					echo ' header_fullheight muji-full-height';
				if (!muji_is_inherit(muji_get_theme_option('header_scheme')))
					echo ' scheme_' . esc_attr(muji_get_theme_option('header_scheme'));
				?>"><?php

	// Background video
	if (!empty($muji_header_video)) {
		get_template_part( 'templates/header-video' );
	}
		
	// Custom header's layout
	do_action('muji_action_show_layout', $muji_header_id);

	// Header widgets area
	get_template_part( 'templates/header-widgets' );
		
?></header>