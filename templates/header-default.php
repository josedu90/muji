<?php
/**
 * The template to display default site header
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

$muji_header_css = '';
$muji_header_image = get_header_image();
$muji_header_video = muji_get_header_video();
if (!empty($muji_header_image) && muji_trx_addons_featured_image_override(is_singular() || muji_storage_isset('blog_archive') || is_category())) {
	$muji_header_image = muji_get_current_mode_image($muji_header_image);
}

?><header class="top_panel top_panel_default<?php
					echo !empty($muji_header_image) || !empty($muji_header_video) ? ' with_bg_image' : ' without_bg_image';
					if ($muji_header_video!='') echo ' with_bg_video';
					if ($muji_header_image!='') echo ' '.esc_attr(muji_add_inline_css_class('background-image: url('.esc_url($muji_header_image).');'));
					if (is_single() && has_post_thumbnail()) echo ' with_featured_image';
					if (muji_is_on(muji_get_theme_option('header_fullheight'))) echo ' header_fullheight muji-full-height';
					if (!muji_is_inherit(muji_get_theme_option('header_scheme')))
						echo ' scheme_' . esc_attr(muji_get_theme_option('header_scheme'));
					?>"><?php

	// Background video
	if (!empty($muji_header_video)) {
		get_template_part( 'templates/header-video' );
	}
	
	// Main menu
	if (muji_get_theme_option("menu_style") == 'top') {
		get_template_part( 'templates/header-navi' );
	}

	// Mobile header
	if (muji_is_on(muji_get_theme_option("header_mobile_enabled"))) {
		get_template_part( 'templates/header-mobile' );
	}
	
	// Page title and breadcrumbs area
	get_template_part( 'templates/header-title');

	// Header widgets area
	get_template_part( 'templates/header-widgets' );

	// Display featured image in the header on the single posts
	// Comment next line to prevent show featured image in the header area
	// and display it in the post's content
	get_template_part( 'templates/header-single' );

?></header>