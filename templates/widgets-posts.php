<?php
/**
 * The template to display posts in widgets and/or in the search results
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

$muji_post_id    = get_the_ID();
$muji_post_date  = muji_get_date();
$muji_post_title = get_the_title();
$muji_post_link  = get_permalink();
$muji_post_author_id   = get_the_author_meta('ID');
$muji_post_author_name = get_the_author_meta('display_name');
$muji_post_author_url  = get_author_posts_url($muji_post_author_id, '');

$muji_args = get_query_var('muji_args_widgets_posts');
$muji_show_date = isset($muji_args['show_date']) ? (int) $muji_args['show_date'] : 1;
$muji_show_image = isset($muji_args['show_image']) ? (int) $muji_args['show_image'] : 1;
$muji_show_author = isset($muji_args['show_author']) ? (int) $muji_args['show_author'] : 1;
$muji_show_counters = isset($muji_args['show_counters']) ? (int) $muji_args['show_counters'] : 1;
$muji_show_categories = isset($muji_args['show_categories']) ? (int) $muji_args['show_categories'] : 1;

$muji_output = muji_storage_get('muji_output_widgets_posts');

$muji_post_counters_output = '';
if ( $muji_show_counters ) {
	$muji_post_counters_output = '<span class="post_info_item post_info_counters">'
								. muji_get_post_counters('comments')
							. '</span>';
}


$muji_output .= '<article class="post_item with_thumb">';

if ($muji_show_image) {
	$muji_post_thumb = get_the_post_thumbnail($muji_post_id, muji_get_thumb_size('tiny'), array(
		'alt' => the_title_attribute( array( 'echo' => false ) )
	));
	if ($muji_post_thumb) $muji_output .= '<div class="post_thumb">' . ($muji_post_link ? '<a href="' . esc_url($muji_post_link) . '">' : '') . ($muji_post_thumb) . ($muji_post_link ? '</a>' : '') . '</div>';
}

$muji_output .= '<div class="post_content">'
			. ($muji_show_categories 
					? '<div class="post_categories">'
						. muji_get_post_categories()
						. $muji_post_counters_output
						. '</div>' 
					: '')
			. '<h6 class="post_title">' . ($muji_post_link ? '<a href="' . esc_url($muji_post_link) . '">' : '') . ($muji_post_title) . ($muji_post_link ? '</a>' : '') . '</h6>'
			. apply_filters('muji_filter_get_post_info', 
								'<div class="post_info">'
									. ($muji_show_date 
										? '<span class="post_info_item post_info_posted">'
											. ($muji_post_link ? '<a href="' . esc_url($muji_post_link) . '" class="post_info_date">' : '') 
											. esc_html($muji_post_date) 
											. ($muji_post_link ? '</a>' : '')
											. '</span>'
										: '')
									. ($muji_show_author 
										? '<span class="post_info_item post_info_posted_by">' 
											. esc_html__('by', 'muji') . ' ' 
											. ($muji_post_link ? '<a href="' . esc_url($muji_post_author_url) . '" class="post_info_author">' : '') 
											. esc_html($muji_post_author_name) 
											. ($muji_post_link ? '</a>' : '') 
											. '</span>'
										: '')
									. (!$muji_show_categories && $muji_post_counters_output
										? $muji_post_counters_output
										: '')
								. '</div>')
		. '</div>'
	. '</article>';
muji_storage_set('muji_output_widgets_posts', $muji_output);
?>