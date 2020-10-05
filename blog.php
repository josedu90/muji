<?php
/**
 * The template to display blog archive
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

/*
Template Name: Blog archive
*/

/**
 * Make page with this template and put it into menu
 * to display posts as blog archive
 * You can setup output parameters (blog style, posts per page, parent category, etc.)
 * in the Theme Options section (under the page content)
 * You can build this page in the WordPress editor or any Page Builder to make custom page layout:
 * just insert %%CONTENT%% in the desired place of content
 */

// Get template page's content
$muji_content = '';
$muji_blog_archive_mask = '%%CONTENT%%';
$muji_blog_archive_subst = sprintf('<div class="blog_archive">%s</div>', $muji_blog_archive_mask);
if ( have_posts() ) {
	the_post();
	if (($muji_content = apply_filters('the_content', get_the_content())) != '') {
		if (($muji_pos = strpos($muji_content, $muji_blog_archive_mask)) !== false) {
			$muji_content = preg_replace('/(\<p\>\s*)?'.$muji_blog_archive_mask.'(\s*\<\/p\>)/i', $muji_blog_archive_subst, $muji_content);
		} else
			$muji_content .= $muji_blog_archive_subst;
		$muji_content = explode($muji_blog_archive_mask, $muji_content);
		// Add VC custom styles to the inline CSS
		$vc_custom_css = get_post_meta( get_the_ID(), '_wpb_shortcodes_custom_css', true );
		if ( !empty( $vc_custom_css ) ) muji_add_inline_css(strip_tags($vc_custom_css));
	}
}

// Prepare args for a new query
$muji_args = array(
	'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish'
);
$muji_args = muji_query_add_posts_and_cats($muji_args, '', muji_get_theme_option('post_type'), muji_get_theme_option('parent_cat'));
$muji_page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
if ($muji_page_number > 1) {
	$muji_args['paged'] = $muji_page_number;
	$muji_args['ignore_sticky_posts'] = true;
}
$muji_ppp = muji_get_theme_option('posts_per_page');
if ((int) $muji_ppp != 0)
	$muji_args['posts_per_page'] = (int) $muji_ppp;
// Make a new main query
$GLOBALS['wp_the_query']->query($muji_args);


// Add internal query vars in the new query!
if (is_array($muji_content) && count($muji_content) == 2) {
	set_query_var('blog_archive_start', $muji_content[0]);
	set_query_var('blog_archive_end', $muji_content[1]);
}

get_template_part('index');
?>