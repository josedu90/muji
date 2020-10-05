<?php
/**
 * The Portfolio template to display the content
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

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_portfolio_'.esc_attr($muji_columns).' post_format_'.esc_attr($muji_post_format).(is_sticky() && !is_paged() ? ' sticky' : '') ); ?>
	<?php echo (!muji_is_off($muji_animation) ? ' data-animation="'.esc_attr(muji_get_animation_classes($muji_animation)).'"' : ''); ?>>
	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	$muji_image_hover = muji_get_theme_option('image_hover');
	// Featured image
	muji_show_post_featured(array(
		'thumb_size' => muji_get_thumb_size(strpos(muji_get_theme_option('body_style'), 'full')!==false || $muji_columns < 3 
								? 'masonry-big' 
								: 'masonry'),
		'show_no_image' => true,
		'class' => $muji_image_hover == 'dots' ? 'hover_with_info' : '',
		'post_info' => $muji_image_hover == 'dots' ? '<div class="post_info">'.esc_html(get_the_title()).'</div>' : ''
	));
	?>
</article>