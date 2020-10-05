<?php
/**
 * The template for homepage posts with "Excerpt" style
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

muji_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	muji_show_layout(get_query_var('blog_archive_start'));

	?><div class="posts_container"><?php
	
	$muji_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$muji_sticky_out = muji_get_theme_option('sticky_style')=='columns' 
							&& is_array($muji_stickies) && count($muji_stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($muji_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	while ( have_posts() ) { the_post(); 
		if ($muji_sticky_out && !is_sticky()) {
			$muji_sticky_out = false;
			?></div><?php
		}
		get_template_part( 'content', $muji_sticky_out && is_sticky() ? 'sticky' : 'excerpt' );
	}
	if ($muji_sticky_out) {
		$muji_sticky_out = false;
		?></div><?php
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