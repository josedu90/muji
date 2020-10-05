<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

$muji_post_format = get_post_format();
$muji_post_format = empty($muji_post_format) ? 'standard' : str_replace('post-format-', '', $muji_post_format);
$muji_animation = muji_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_excerpt post_format_'.esc_attr($muji_post_format) ); ?>
	<?php echo (!muji_is_off($muji_animation) ? ' data-animation="'.esc_attr(muji_get_animation_classes($muji_animation)).'"' : ''); ?>
	><?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	muji_show_post_featured(array( 'thumb_size' => muji_get_thumb_size( strpos(muji_get_theme_option('body_style'), 'full')!==false ? 'full' : 'big' ) ));

	?>
	<div class="post_header entry-header">
		<div class="cat-block"><?php

			do_action('muji_action_before_post_meta');

			// Post meta
			$muji_components = muji_array_get_keys_by_value(muji_get_theme_option('meta_parts'));
			$muji_counters = muji_array_get_keys_by_value(muji_get_theme_option('counters'));

			if (!empty($muji_components))
				muji_show_post_meta(apply_filters('muji_filter_post_meta_args', array(
						'components' => $muji_components,
						'counters' => $muji_counters,
						'seo' => false
					), 'excerpt', 1)
				);

			?>
		</div>
		<?php

		if (get_the_title() != '') {
			do_action('muji_action_before_post_title');

			// Post title
			the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
		}

		do_action('muji_action_before_post_meta');

		// Post meta
		$muji_components = muji_array_get_keys_by_value(muji_get_theme_option('meta_parts'));
		$muji_counters = muji_array_get_keys_by_value(muji_get_theme_option('counters'));

		if (!empty($muji_components))
			muji_show_post_meta(apply_filters('muji_filter_post_meta_args', array(
					'components' => $muji_components,
					'counters' => $muji_counters,
					'seo' => false
				), 'excerpt', 1)
			);

		?>
	</div><!-- .post_header -->
	<?php
	
	// Post content
	?>
	<div class="post_content entry-content"><?php

		if (muji_get_theme_option('blog_content') == 'fullpost') {

			// Post content area
			?><div class="post_content_inner"><?php
			the_content( '' );
			?></div><?php

			// Inner pages
			wp_link_pages( array(
				'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'muji' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'muji' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );

		} else {

			$muji_show_learn_more = !in_array($muji_post_format, array('link', 'aside', 'status', 'quote'));

			// Post content area
			?><div class="post_content_inner"><?php
			if (has_excerpt()) {
				the_excerpt();
			} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
				the_content( '' );
			} else if (in_array($muji_post_format, array('link', 'aside', 'status'))) {
				the_content();
			} else if ($muji_post_format == 'quote') {
				if (($quote = muji_get_tag(get_the_content(), '<blockquote>', '</blockquote>'))!='')
					muji_show_layout(wpautop($quote));
				else
					the_excerpt();
			} else if (substr(get_the_content(), 0, 4)!='[vc_') {
				the_excerpt();
			}
			?></div><?php

			// More button
			if ( $muji_show_learn_more ) {
				?>
				<div class="post-footer">
				    <a class="more-btn" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'muji'); ?></a>
				    <?php

				    do_action('muji_action_before_post_meta');

				    // Post meta
				    $muji_components = muji_array_get_keys_by_value(muji_get_theme_option('meta_parts'));
				    $muji_counters = muji_array_get_keys_by_value(muji_get_theme_option('counters'));

				    if (!empty($muji_components))
					    muji_show_post_meta(apply_filters('muji_filter_post_meta_args', array(
							    'components' => $muji_components,
							    'counters' => $muji_counters,
							    'seo' => false
						    ), 'excerpt', 1)
					    );

				    ?>
				</div><?php
			}

		}
		?></div><!-- .entry-content -->
</article>