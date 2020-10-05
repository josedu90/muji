<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

// Page (category, tag, archive, author) title

if ( muji_need_page_title() ) {
	muji_sc_layouts_showed('title', true);
	muji_sc_layouts_showed('postmeta', true);
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Post meta on the single post
						if ( is_single() )  {
							?><div class="sc_layouts_title_meta"><?php
								muji_show_post_meta(apply_filters('muji_filter_post_meta_args', array(
									'components' => muji_array_get_keys_by_value(muji_get_theme_option('meta_parts')),
									'counters' => muji_array_get_keys_by_value(muji_get_theme_option('counters')),
									'seo' => muji_is_on(muji_get_theme_option('seo_snippets'))
									), 'header', 1)
								);
							?></div><?php
						}
						
						// Blog/Post title
						?><div class="sc_layouts_title_title"><?php
							$muji_blog_title = muji_get_blog_title();
							$muji_blog_title_text = $muji_blog_title_class = $muji_blog_title_link = $muji_blog_title_link_text = '';
							if (is_array($muji_blog_title)) {
								$muji_blog_title_text = $muji_blog_title['text'];
								$muji_blog_title_class = !empty($muji_blog_title['class']) ? ' '.$muji_blog_title['class'] : '';
								$muji_blog_title_link = !empty($muji_blog_title['link']) ? $muji_blog_title['link'] : '';
								$muji_blog_title_link_text = !empty($muji_blog_title['link_text']) ? $muji_blog_title['link_text'] : '';
							} else
								$muji_blog_title_text = $muji_blog_title;
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr($muji_blog_title_class); ?>"><?php
								$muji_top_icon = muji_get_category_icon();
								if (!empty($muji_top_icon)) {
									$muji_attr = muji_getimagesize($muji_top_icon);
									?><img src="<?php echo esc_url($muji_top_icon); ?>" alt="<?php esc_attr_e( 'Site icon', 'muji' ); ?>" <?php if (!empty($muji_attr[3])) muji_show_layout($muji_attr[3]);?>><?php
								}
								echo wp_kses_post($muji_blog_title_text);
							?></h1>
							<?php
							if (!empty($muji_blog_title_link) && !empty($muji_blog_title_link_text)) {
								?><a href="<?php echo esc_url($muji_blog_title_link); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html($muji_blog_title_link_text); ?></a><?php
							}
							
							// Category/Tag description
							if ( is_category() || is_tag() || is_tax() ) 
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
		
						?></div><?php
	
						// Breadcrumbs
						?><div class="sc_layouts_title_breadcrumbs"><?php
							do_action( 'muji_action_breadcrumbs');
						?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>