<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

$muji_blog_style = explode('_', muji_get_theme_option('blog_style'));
$muji_columns = empty($muji_blog_style[1]) ? 1 : max(1, $muji_blog_style[1]);
$muji_expanded = !muji_sidebar_present() && muji_is_on(muji_get_theme_option('expand_content'));
$muji_post_format = get_post_format();
$muji_post_format = empty($muji_post_format) ? 'standard' : str_replace('post-format-', '', $muji_post_format);
$muji_animation = muji_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_chess post_layout_chess_'.esc_attr($muji_columns).' post_format_'.esc_attr($muji_post_format) ); ?>
	<?php echo (!muji_is_off($muji_animation) ? ' data-animation="'.esc_attr(muji_get_animation_classes($muji_animation)).'"' : ''); ?>>

	<?php
	// Add anchor
	if ($muji_columns == 1 && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="post_'.esc_attr(get_the_ID()).'" title="'.the_title_attribute( array( 'echo' => false ) ).'" icon="'.esc_attr(muji_get_post_icon()).'"]');
	}

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	muji_show_post_featured(
		array(
			'class' => $muji_columns == 1 ? 'muji-full-height' : '',
			'show_no_image' => true,
			'thumb_bg' => true,
			'thumb_size' => muji_get_thumb_size(
				strpos(muji_get_theme_option('body_style'), 'full')!==false
					? ( $muji_columns > 1 ? 'huge' : 'original' )
					: (	$muji_columns > 2 ? 'big' : 'huge')
			)
		)
	);

	?>
	<div class="post_inner">
		<div class="post_inner_content">
			<div class="post_header entry-header">
				<div class="cat-block"><?php

					do_action('muji_action_before_post_meta');

					// Post meta
					$muji_components = muji_array_get_keys_by_value(muji_get_theme_option('meta_parts'));
					$muji_counters = muji_array_get_keys_by_value(muji_get_theme_option('counters'));
					$muji_post_meta = empty($muji_components)
						? ''
						: muji_show_post_meta(apply_filters('muji_filter_post_meta_args', array(
								'components' => $muji_components,
								'counters' => $muji_counters,
								'seo' => false,
								'echo' => false
							), $muji_blog_style[0], $muji_columns)
						);
					muji_show_layout($muji_post_meta);

					?>
				</div><?php

				do_action('muji_action_before_post_title');

				// Post title
				the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );

				// Post meta
				$muji_components = muji_array_get_keys_by_value(muji_get_theme_option('meta_parts'));
				$muji_counters = muji_array_get_keys_by_value(muji_get_theme_option('counters'));
				$muji_post_meta = empty($muji_components)
					? ''
					: muji_show_post_meta(apply_filters('muji_filter_post_meta_args', array(
							'components' => $muji_components,
							'counters' => $muji_counters,
							'seo' => false,
							'echo' => false
						), $muji_blog_style[0], $muji_columns)
					);
				muji_show_layout($muji_post_meta);

				?>
			</div><!-- .entry-header -->
	
		    <div class="post_content entry-content">
				<div class="post_content_inner">
					<?php

					$muji_show_learn_more = !in_array($muji_post_format, array('link', 'aside', 'status', 'quote'));
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

					?>
				</div>
				<?php

				// Post meta
				if (in_array($muji_post_format, array('link', 'aside', 'status', 'quote'))) {
					muji_show_layout($muji_post_meta);
				}

				// More button
				if ( $muji_show_learn_more ) {
					?><p><a class="more-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'muji'); ?></a></p><?php
				}

				?>
			</div><!-- .entry-content -->
	    </div>
	</div><!-- .post_inner -->

</article>