<?php
/**
 * The template 'Style 2' to displaying related posts
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

$muji_link = get_permalink();
$muji_post_format = get_post_format();
$muji_post_format = empty($muji_post_format) ? 'standard' : str_replace('post-format-', '', $muji_post_format);
?><div id="post-<?php the_ID(); ?>" 
	<?php post_class( 'related_item related_item_style_2 post_format_'.esc_attr($muji_post_format) ); ?>><?php
	muji_show_post_featured(array(
		'thumb_size' => apply_filters('muji_filter_related_thumb_size', muji_get_thumb_size( (int) muji_get_theme_option('related_posts') == 1 ? 'huge' : 'related' )),
		'show_no_image' => muji_get_theme_setting('allow_no_image'),
		'singular' => false
		)
	);
	?><div class="post_header entry-header"><div class="related_post_meta"><?php
		if ( in_array(get_post_type(), array( 'post', 'attachment' ) ) ) {
			?><span class="post_date"><a href="<?php echo esc_url($muji_link); ?>"><?php echo  muji_get_date(); ?></a></span><?php
		}
			// Post meta
			if (!muji_sc_layouts_showed('postmeta') && muji_is_on(muji_get_theme_option('show_post_meta'))) {
				muji_show_post_meta(apply_filters('muji_filter_post_meta_args', array(
						'components' => 'counters',
						'counters' => 'likes',
						'seo' => false
					), 'single', 1)
				);
			}
		?></div>
		<h5 class="post_title entry-title"><a href="<?php echo esc_url($muji_link); ?>"><?php the_title(); ?></a></h5>
	</div>
</div>