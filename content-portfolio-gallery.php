<?php
/**
 * The Gallery template to display posts
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

$muji_blog_style = explode('_', muji_get_theme_option('blog_style'));
$muji_columns = empty($muji_blog_style[1]) ? 2 : max(2, $muji_blog_style[1]);
$muji_post_format = get_post_format();
$muji_post_format = empty($muji_post_format) ? 'standard' : str_replace('post-format-', '', $muji_post_format);
$muji_animation = muji_get_theme_option('blog_animation');
$muji_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_gallery post_layout_gallery_'.esc_attr($muji_columns).' post_format_'.esc_attr($muji_post_format) ); ?>
	<?php echo (!muji_is_off($muji_animation) ? ' data-animation="'.esc_attr(muji_get_animation_classes($muji_animation)).'"' : ''); ?>
	data-size="<?php if (!empty($muji_image[1]) && !empty($muji_image[2])) echo intval($muji_image[1]) .'x' . intval($muji_image[2]); ?>"
	data-src="<?php if (!empty($muji_image[0])) echo esc_url($muji_image[0]); ?>"
	>

	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	$muji_image_hover = 'icon';
	if (in_array($muji_image_hover, array('icons', 'zoom'))) $muji_image_hover = 'dots';
	$muji_components = muji_array_get_keys_by_value(muji_get_theme_option('meta_parts'));
	$muji_counters = muji_array_get_keys_by_value(muji_get_theme_option('counters'));
	muji_show_post_featured(array(
		'hover' => $muji_image_hover,
		'thumb_size' => muji_get_thumb_size( strpos(muji_get_theme_option('body_style'), 'full')!==false || $muji_columns < 3 ? 'masonry-big' : 'masonry' ),
		'thumb_only' => true,
		'show_no_image' => true,
		'post_info' => '<div class="post_details">'
							. '<h2 class="post_title"><a href="'.esc_url(get_permalink()).'">'. esc_html(get_the_title()) . '</a></h2>'
							. '<div class="post_description">'
								. (!empty($muji_components)
										? muji_show_post_meta(apply_filters('muji_filter_post_meta_args', array(
											'components' => $muji_components,
											'counters' => $muji_counters,
											'seo' => false,
											'echo' => false
											), $muji_blog_style[0], $muji_columns))
										: '')
								. '<div class="post_description_content">'
									. apply_filters('the_excerpt', get_the_excerpt())
								. '</div>'
								. '<a href="'.esc_url(get_permalink()).'" class="theme_button post_readmore"><span class="post_readmore_label">' . esc_html__('Learn more', 'muji') . '</span></a>'
							. '</div>'
						. '</div>'
	));
	?>
</article>