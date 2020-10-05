<?php
/**
 * The Sticky template to display the sticky posts
 *
 * Used for index/archive
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

$muji_columns = max(1, min(3, count(get_option( 'sticky_posts' ))));
$muji_post_format = get_post_format();
$muji_post_format = empty($muji_post_format) ? 'standard' : str_replace('post-format-', '', $muji_post_format);
$muji_animation = muji_get_theme_option('blog_animation');

?><div class="column-1_<?php echo esc_attr($muji_columns); ?>"><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_sticky post_format_'.esc_attr($muji_post_format) ); ?>
	<?php echo (!muji_is_off($muji_animation) ? ' data-animation="'.esc_attr(muji_get_animation_classes($muji_animation)).'"' : ''); ?>
	>

	<?php
	if ( is_sticky() && is_home() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	muji_show_post_featured(array(
		'thumb_size' => muji_get_thumb_size($muji_columns==1 ? 'big' : ($muji_columns==2 ? 'med' : 'avatar'))
	));

	if ( !in_array($muji_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			the_title( sprintf( '<h6 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h6>' );
			// Post meta
			muji_show_post_meta(apply_filters('muji_filter_post_meta_args', array(), 'sticky', $muji_columns));
			?>
		</div><!-- .entry-header -->
		<?php
	}
	?>
</article></div>