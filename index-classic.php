<?php
/**
 * The template for homepage posts with "Classic" style
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

muji_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	muji_show_layout(get_query_var('blog_archive_start'));

	$muji_classes = 'posts_container '
						. (substr(muji_get_theme_option('blog_style'), 0, 7) == 'classic' ? 'columns_wrap columns_padding_bottom' : 'masonry_wrap');
	$muji_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$muji_sticky_out = muji_get_theme_option('sticky_style')=='columns' 
							&& is_array($muji_stickies) && count($muji_stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($muji_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	if (!$muji_sticky_out) {
		if (muji_get_theme_option('first_post_large') && !is_paged() && !in_array(muji_get_theme_option('body_style'), array('fullwide', 'fullscreen'))) {
			the_post();
			get_template_part( 'content', 'excerpt' );
		}
		
		?><div class="<?php echo esc_attr($muji_classes); ?>"><?php
	}
	while ( have_posts() ) { the_post(); 
		if ($muji_sticky_out && !is_sticky()) {
			$muji_sticky_out = false;
			?></div><div class="<?php echo esc_attr($muji_classes); ?>"><?php
		}
		get_template_part( 'content', $muji_sticky_out && is_sticky() ? 'sticky' : 'classic' );
	}
	
	?></div><?php

	muji_show_pagination();

	muji_show_layout(get_query_var('blog_archive_end'));

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>