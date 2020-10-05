<?php
/**
 * The template 'Style 1' to displaying related posts
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

$muji_link = get_permalink();
$muji_post_format = get_post_format();
$muji_post_format = empty($muji_post_format) ? 'standard' : str_replace('post-format-', '', $muji_post_format);
?><div id="post-<?php the_ID(); ?>" 
	<?php post_class( 'related_item related_item_style_1 post_format_'.esc_attr($muji_post_format) ); ?>><?php
	muji_show_post_featured(array(
		'thumb_size' => apply_filters('muji_filter_related_thumb_size', muji_get_thumb_size( (int) muji_get_theme_option('related_posts') == 1 ? 'huge' : 'big' )),
		'show_no_image' => muji_get_theme_setting('allow_no_image'),
		'singular' => false,
		'post_info' => '<div class="post_header entry-header">'
							. '<div class="post_categories">'.wp_kses_post(muji_get_post_categories('')).'</div>'
							. '<h6 class="post_title entry-title"><a href="'.esc_url($muji_link).'">'.esc_html(get_the_title()).'</a></h6>'
							. (in_array(get_post_type(), array('post', 'attachment'))
									? '<span class="post_date"><a href="'.esc_url($muji_link).'">'.wp_kses_data(muji_get_date()).'</a></span>'
									: '')
						. '</div>'
		)
	);
?></div>