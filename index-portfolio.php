<?php
/**
 * The template for homepage posts with "Portfolio" style
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

muji_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	muji_show_layout(get_query_var('blog_archive_start'));

	$muji_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$muji_sticky_out = muji_get_theme_option('sticky_style')=='columns' 
							&& is_array($muji_stickies) && count($muji_stickies) > 0 && get_query_var( 'paged' ) < 1;
	
	// Show filters
	$muji_cat = muji_get_theme_option('parent_cat');
	$muji_post_type = muji_get_theme_option('post_type');
	$muji_taxonomy = muji_get_post_type_taxonomy($muji_post_type);
	$muji_show_filters = muji_get_theme_option('show_filters');
	$muji_tabs = array();
	if (!muji_is_off($muji_show_filters)) {
		$muji_args = array(
			'type'			=> $muji_post_type,
			'child_of'		=> $muji_cat,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 1,
			'hierarchical'	=> 0,
			'exclude'		=> '',
			'include'		=> '',
			'number'		=> '',
			'taxonomy'		=> $muji_taxonomy,
			'pad_counts'	=> false
		);
		$muji_portfolio_list = get_terms($muji_args);
		if (is_array($muji_portfolio_list) && count($muji_portfolio_list) > 0) {
			$muji_tabs[$muji_cat] = esc_html__('All', 'muji');
			foreach ($muji_portfolio_list as $muji_term) {
				if (isset($muji_term->term_id)) $muji_tabs[$muji_term->term_id] = $muji_term->name;
			}
		}
	}
	if (count($muji_tabs) > 0) {
		$muji_portfolio_filters_ajax = true;
		$muji_portfolio_filters_active = $muji_cat;
		$muji_portfolio_filters_id = 'portfolio_filters';
		?>
		<div class="portfolio_filters muji_tabs muji_tabs_ajax">
			<ul class="portfolio_titles muji_tabs_titles">
				<?php
				foreach ($muji_tabs as $muji_id=>$muji_title) {
					?><li><a href="<?php echo esc_url(muji_get_hash_link(sprintf('#%s_%s_content', $muji_portfolio_filters_id, $muji_id))); ?>" data-tab="<?php echo esc_attr($muji_id); ?>"><?php echo esc_html($muji_title); ?></a></li><?php
				}
				?>
			</ul>
			<?php
			$muji_ppp = muji_get_theme_option('posts_per_page');
			if (muji_is_inherit($muji_ppp)) $muji_ppp = '';
			foreach ($muji_tabs as $muji_id=>$muji_title) {
				$muji_portfolio_need_content = $muji_id==$muji_portfolio_filters_active || !$muji_portfolio_filters_ajax;
				?>
				<div id="<?php echo esc_attr(sprintf('%s_%s_content', $muji_portfolio_filters_id, $muji_id)); ?>"
					class="portfolio_content muji_tabs_content"
					data-blog-template="<?php echo esc_attr(muji_storage_get('blog_template')); ?>"
					data-blog-style="<?php echo esc_attr(muji_get_theme_option('blog_style')); ?>"
					data-posts-per-page="<?php echo esc_attr($muji_ppp); ?>"
					data-post-type="<?php echo esc_attr($muji_post_type); ?>"
					data-taxonomy="<?php echo esc_attr($muji_taxonomy); ?>"
					data-cat="<?php echo esc_attr($muji_id); ?>"
					data-parent-cat="<?php echo esc_attr($muji_cat); ?>"
					data-need-content="<?php echo (false===$muji_portfolio_need_content ? 'true' : 'false'); ?>"
				>
					<?php
					if ($muji_portfolio_need_content) 
						muji_show_portfolio_posts(array(
							'cat' => $muji_id,
							'parent_cat' => $muji_cat,
							'taxonomy' => $muji_taxonomy,
							'post_type' => $muji_post_type,
							'page' => 1,
							'sticky' => $muji_sticky_out
							)
						);
					?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} else {
		muji_show_portfolio_posts(array(
			'cat' => $muji_cat,
			'parent_cat' => $muji_cat,
			'taxonomy' => $muji_taxonomy,
			'post_type' => $muji_post_type,
			'page' => 1,
			'sticky' => $muji_sticky_out
			)
		);
	}

	muji_show_layout(get_query_var('blog_archive_end'));

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>