<?php
/**
 * Theme tags
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */


//----------------------------------------------------------------------
//-- Common tags
//----------------------------------------------------------------------

// Return true if current page need title
if ( !function_exists('muji_need_page_title') ) {
	function muji_need_page_title() {
		return !is_front_page() && apply_filters('muji_filter_need_page_title', true);
	}
}

// Output string with the html layout (if not empty)
// (put it between 'before' and 'after' tags)
// Attention! This string may contain layout formed in any plugin (widgets or shortcodes output) and not require escaping to prevent damage!
if ( !function_exists('muji_show_layout') ) {
	function muji_show_layout($str, $before='', $after='') {
		if (trim($str) != '') {
			printf("%s%s%s", $before, $str, $after);
		}
	}
}

// Return logo images (if set)
if ( !function_exists('muji_get_logo_image') ) {
	function muji_get_logo_image($type='') {
		$logo_image = '';
		if (muji_is_on(muji_get_theme_option('logo_retina_enabled')) && muji_get_retina_multiplier() > 1)
			$logo_image = muji_get_theme_option( 'logo'.(!empty($type) ? '_'.trim($type) : '').'_retina' );
		if (empty($logo_image)) {
			if (empty($type) && function_exists('the_custom_logo')) {
				$logo_image = get_theme_mod('custom_logo');
				if ((int) $logo_image > 0) {
					$image = wp_get_attachment_image_src( $logo_image, 'full' );
					$logo_image = $image[0];
				}
			} else {
				$logo_image = muji_get_theme_option( 'logo'.(!empty($type) ? '_'.trim($type) : '') );
			}
		}
		return muji_remove_protocol_from_url($logo_image, false);
	}
}

// Return header video (if set)
if ( !function_exists('muji_get_header_video') ) {
	function muji_get_header_video() {
		$video = '';
		if (apply_filters('muji_header_video_enable', !wp_is_mobile() && is_front_page())) {
			if (muji_check_theme_option('header_video')) {
				$video = muji_get_theme_option('header_video');
				if ((int) $video > 0) $video = wp_get_attachment_url( $video );
			} else if (function_exists('get_header_video_url')) {
				$video = get_header_video_url();
			}
		}
		return $video;
	}
}


//----------------------------------------------------------------------
//-- Post parts
//----------------------------------------------------------------------

// Show post meta block: post date, author, categories, counters, etc.
if ( !function_exists('muji_show_post_meta') ) {
	function muji_show_post_meta($args=array()) {

		if (is_single() && muji_is_off(muji_get_theme_option('show_post_meta'))) return ' ';	// Space is need!
		
		$args = array_merge(array(
			'components' => 'categories,date,author,counters,share,edit',
			'counters' => 'comments',	//comments,views,likes
			'seo' => false,
			'echo' => true
			), $args);

		if (!$args['echo']) ob_start();

		?><div class="post_meta"><?php
			$components = explode(',', $args['components']);
			foreach ($components as $comp) {
				$comp = trim($comp);
				// Post categories
				if ($comp == 'categories') {
					$cats = get_post_type()=='post' ? get_the_category_list(', ') : apply_filters('muji_filter_get_post_categories', '');
					if (!empty($cats)) {
						?>
						<span class="post_meta_item post_categories"><?php muji_show_layout($cats); ?></span>
						<?php
					}

				// Post date
				} else if ($comp == 'date') {
					$dt = apply_filters('muji_filter_get_post_date',   muji_get_date());
					if (!empty($dt)) {
						?>
						<span class="post_meta_item post_date<?php if (!empty($args['seo'])) echo ' date updated'; ?>"<?php if (!empty($args['seo'])) echo ' itemprop="datePublished"'; ?>><a href="<?php the_permalink(); ?>"><?php echo wp_kses_data($dt); ?></a></span>
						<?php
					}

				// Post author
				} else if ($comp == 'author') {
					$author_id = get_the_author_meta('ID');
					if (empty($author_id) && !empty($GLOBALS['post']->post_author))
						$author_id = $GLOBALS['post']->post_author;
					if ($author_id > 0) {
						$author_link = get_author_posts_url($author_id);
						$author_name = get_the_author_meta('display_name', $author_id);
						?>
						<a class="post_meta_item post_author" rel="author" href="<?php echo esc_url($author_link); ?>">
							<?php echo esc_html__('By', 'muji'); ?> <?php echo esc_html($author_name); ?>
						</a>
						<?php
					}

				// Post counters
				} else if ($comp == 'counters') {
					if ( ($output = muji_get_post_counters($args['counters'])) != '')
						muji_show_layout($output);
					else {
						if (!is_singular() || have_comments() || comments_open()) {
							$post_comments = get_comments_number();
							?>
							<a href="<?php comments_link(); ?>" class="post_meta_item post_counters_item post_counters_comments icon-comment-light"><?php
								?><span class="post_counters_number"><?php
									echo esc_html($post_comments);
								?></span><?php
								?><span class="post_counters_label"><?php
									muji_show_layout(($post_comments > 1) ? esc_html__('Comments', 'muji') :  esc_html__('Comment', 'muji'));
								?></span>
							</a>
							<?php
						}
					}
	
				// Socials share
				} else if ($comp == 'share') {
					muji_show_share_links(array(
							'type' => 'drop',
							'caption' => esc_html__('Share', 'muji'),
							'before' => '<span class="post_meta_item post_share">',
							'after' => '</span>'
						));

				// Edit page link
				} else if ($comp == 'edit') {
					edit_post_link( esc_html__( 'Edit', 'muji' ), '', '', 0, 'post_meta_item post_edit icon-pencil' );
				}
			}
		?></div><!-- .post_meta --><?php
		
		if (!$args['echo']) {
			$rez = ob_get_contents();
			ob_end_clean();
			return $rez;
		} else
			return '';
	}
}

// Show post featured block: image, video, audio, etc.
if ( !function_exists('muji_show_post_featured') ) {
	function muji_show_post_featured($args=array()) {
		$args = array_merge(array(
			'hover' => muji_get_theme_option('image_hover'),	// Hover effect
			'no_links' => false,							// Disable links
			'class' => '',									// Additional Class for featured block
			'post_info' => '',								// Additional layout after hover
			'thumb_bg' => false,							// Put thumb image as block background or as separate tag
			'thumb_size' => '',								// Image size
			'thumb_only' => false,							// Display only thumb (without post formats)
			'show_no_image' => false,						// Display 'no-image.jpg' if post haven't thumbnail
			'seo' => muji_is_on(muji_get_theme_option('seo_snippets')),
			'singular' => is_singular()						// Current page is singular (true) or blog/shortcode (false)
			), $args);

		if ( post_password_required() ) return;

		$thumb_size = !empty($args['thumb_size']) 
						? $args['thumb_size'] 
						: muji_get_thumb_size(is_attachment() || is_single() ? 'full' : 'big');
		$post_format = str_replace('post-format-', '', get_post_format());
		$no_image = !empty($args['show_no_image']) ? muji_get_no_image() : '';
		if ($args['thumb_bg']) {
			if (has_post_thumbnail()) {
				$image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), $thumb_size );
				$image = $image[0];
			} else if ($post_format == 'image') {
				$image = muji_get_post_image();
				if (!empty($image)) 
					$image = muji_add_thumb_size($image, $thumb_size);
			}
			if (empty($image))
				$image = $no_image;
			if (!empty($image))
				$args['class'] .= ($args['class'] ? ' ' : '') . 'post_featured_bg' . ' ' . muji_add_inline_css_class('background-image: url('.esc_url($image).');');
		}

		if ( $args['singular'] ) {
			
			if ( is_attachment() ) {
				?>
				<div class="post_featured post_attachment<?php if ($args['class']) echo ' '.esc_attr($args['class']); ?>">

					<?php
					if (!$args['thumb_bg']) 
						echo wp_get_attachment_image( get_the_ID(), $thumb_size, false, 
													 muji_is_on(muji_get_theme_option('seo_snippets'))
													 	? array('itemprop' => 'image')
														: '');
					?>

					<nav id="image-navigation" class="navigation image-navigation">
						<div class="nav-previous"><?php previous_image_link( false, '' ); ?></div>
						<div class="nav-next"><?php next_image_link( false, '' ); ?></div>
					</nav><!-- .image-navigation -->
				
				</div><!-- .post_featured -->
				
				<?php
				if ( has_excerpt() ) {
					?><div class="entry-caption"><?php the_excerpt(); ?></div><!-- .entry-caption --><?php
				}
	
			} else if ( has_post_thumbnail() || !empty($args['show_no_image']) ) {

				?>
				<div class="post_featured<?php if ($args['class']) echo ' '.esc_attr($args['class']); ?>"<?php
					if ($args['seo']) echo ' itemscope itemprop="image" itemtype="//schema.org/ImageObject"';
				?>>
					<?php
					if (has_post_thumbnail() && $args['seo']) {
						$muji_attr = muji_getimagesize( wp_get_attachment_url( get_post_thumbnail_id() ) );
						?>
						<meta itemprop="width" content="<?php echo esc_attr($muji_attr[0]); ?>">
						<meta itemprop="height" content="<?php echo esc_attr($muji_attr[1]); ?>">
						<?php
					}
					if (!$args['thumb_bg']) {
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( $thumb_size, array(
								'itemprop' => 'url'
								)
							);
						} else if (!empty($no_image)) {
							?><img<?php if ($args['seo']) echo ' itemprop="url"'; ?> src="<?php echo esc_url($no_image); ?>" alt="<?php echo the_title_attribute(); ?>"><?php
						}
					}
					?>
				</div><!-- .post_featured -->
				<?php

			}
	
		} else {
	
			if (empty($post_format)) $post_format='standard';
			$has_thumb = has_post_thumbnail();
			$post_info = !empty($args['post_info']) ? $args['post_info'] : '';

			if ($has_thumb 
				|| !empty($args['show_no_image']) 
				|| (!$args['thumb_only'] && in_array($post_format, array('gallery', 'image', 'audio', 'video')))) {
				?><div class="post_featured <?php
							echo (!empty($has_thumb) || $post_format == 'image' || !empty($args['show_no_image']) 
									? ('with_thumb' . ($args['thumb_only'] 
														|| !in_array($post_format, array('audio', 'video', 'gallery')) 
														|| ($post_format=='gallery' && ($has_thumb || $args['thumb_bg']))
															? ' hover_'.esc_attr($args['hover'])
															: (in_array($post_format, array('video')) ? ' hover_play' : '')
														)
										)
									: 'without_thumb')
									. (!empty($args['class']) ? ' '.esc_attr($args['class']) : '');
								?>"><?php 

				// Put the thumb or gallery or image or video from the post
				if ( $args['thumb_bg'] ) {
					if (!empty($args['hover'])) {
						?><div class="mask"></div><?php
					}
					if (!in_array($post_format, array('audio', 'video'))) {
						muji_hovers_add_icons($args['hover'], array('no_links' => $args['no_links']));
					}

				} else if ( $has_thumb ) {
					the_post_thumbnail( $thumb_size, array(
															) );
					if (!empty($args['hover'])) {
						?><div class="mask"></div><?php
					}
					if ($args['thumb_only'] || !in_array($post_format, array('audio', 'video'))) {
						muji_hovers_add_icons($args['hover'], array('no_links' => $args['no_links']));
					}
	
				} else if ($post_format == 'gallery' && !$args['thumb_only']) {

					if (($output=muji_get_slider_layout(array('thumb_size'=>$thumb_size, 'controls'=>'yes', 'pagination'=>'yes'))) != '')
						muji_show_layout($output);
	
				} else if ($post_format == 'image') {
					$image = muji_get_post_image();
					if (!empty($image)) {
						$image = muji_add_thumb_size($image, $thumb_size);
						?><img src="<?php echo esc_url($image); ?>" alt="<?php echo the_title_attribute(); ?>"><?php
						if (!empty($args['hover'])) {
							?><div class="mask"></div><?php 
						}
						muji_hovers_add_icons($args['hover'], array('no_links' => $args['no_links'], 'image' => $image));
					}
				} else if (!empty($args['show_no_image']) && !empty($no_image)) {
					?><img src="<?php echo esc_url($no_image); ?>" alt="<?php echo the_title_attribute(); ?>"><?php
					if (!empty($args['hover'])) {
						?><div class="mask"></div><?php 
					}
					muji_hovers_add_icons($args['hover'], array('no_links' => $args['no_links']));
				}
				
				// Add audio and video
				if (!$args['thumb_only'] && ($post_format == 'video' || $post_format == 'audio')) {

					$post_content = '';	

					// Put video under the thumb
					if ($post_format == 'video') {
						$video = muji_get_post_video($post_content, false);
						if (empty($video))
							$video = muji_get_post_iframe($post_content, false);
						if (empty($video)) {
							// Only get video from the content if a playlist isn't present.
							$post_content = apply_filters( 'the_content', get_the_content() );
							if ( false === strpos( $post_content, 'wp-playlist-script' ) ) {
								$videos = get_media_embedded_in_content( $post_content, array( 'video', 'object', 'embed', 'iframe' ) );
								if (!empty($videos) && is_array($videos)) {
									$video = muji_array_get_first($videos, false);
								}
							}
						}
						if (!empty($video)) {
							if ( $has_thumb ) {
								$video = muji_make_video_autoplay($video);
								?><div class="post_video_hover" data-video="<?php echo esc_attr($video); ?>"></div><?php 
							}
							?><div class="post_video video_frame"><?php 
								if ( !$has_thumb ) {
									muji_show_layout($video);
								}
							?></div><?php
						}
		
					// Put audio over the thumb
					} else if ($post_format == 'audio') {
						$audio = muji_get_post_audio($post_content, false);
						if (empty($audio))
							$audio = muji_get_post_iframe($post_content, false);
						if (empty($audio)) {
							// Only get video from the content if a playlist isn't present.
							$post_content = apply_filters( 'the_content', get_the_content() );
							if ( false === strpos( $post_content, 'wp-playlist-script' ) ) {
								$audios = get_media_embedded_in_content( $post_content, array( 'audio' ) );
								if (!empty($audios) && is_array($audios)) {
									$audio = muji_array_get_first($audios, false);
								}
							}
						}
						if (!empty($audio)) {
							?><div class="post_audio<?php if (strpos($audio, 'soundcloud')!==false) echo ' with_iframe'; ?>"><?php 
								// Add author and title
								$media_author = muji_get_theme_option('media_author', '', false, get_the_ID());
								$media_title = muji_get_theme_option('media_title', '', false, get_the_ID());
								if ( !empty($media_author) && !muji_is_inherit($media_author) ) {
									?><div class="post_audio_author"><?php muji_show_layout($media_author); ?></div><?php
								}
								if ( !empty($media_title) && !muji_is_inherit($media_title) ) {
									?><h5 class="post_audio_title"><?php muji_show_layout($media_title); ?></h5><?php
								}
								// Display audio
								muji_show_layout($audio); 
							?></div><?php
						}
					}
				}
				
				// Put optional info block over the thumb
				muji_show_layout($post_info);
				?></div><?php
			} else {
				// Put optional info block over the thumb
				muji_show_layout($post_info);
			}
		}
	}
}


// Return path to the 'no-image'
if ( !function_exists('muji_get_no_image') ) {
	function muji_get_no_image($no_image='') {
		static $img = '';
		if (empty($img)) {
			$img = muji_get_theme_option( 'no_image' );
			if (empty($img)) $img = muji_get_file_url('images/no-image.jpg');
		}
		if (!empty($img)) $no_image = $img;
		return $no_image;
	}
}


// Add featured image as background image to post navigation elements.
if ( !function_exists('muji_add_bg_in_post_nav') ) {
	function muji_add_bg_in_post_nav() {
		if ( ! is_single() ) return;
	
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );
		$css      = '';
		$noimg    = muji_get_no_image();
		
		if ( is_attachment() && $previous->post_type == 'attachment' ) return;
	
		if ( $previous ) {
			if ( has_post_thumbnail( $previous->ID ) ) {
				$img = wp_get_attachment_image_src( get_post_thumbnail_id( $previous->ID ), muji_get_thumb_size('med') );
				$img = $img[0];
			} else if (muji_get_theme_setting('allow_no_image'))
				$img = $noimg;
			if ( !empty($img) )
				$css .= '.post-navigation .nav-previous a .nav-arrow { background-image: url(' . esc_url( $img ) . '); }';
			else
				$css .= '.post-navigation .nav-previous a .nav-arrow { background-color: rgba(128,128,128,0.05); border:1px solid rgba(128,128,128,0.1); }'
					 .  '.post-navigation .nav-previous a .nav-arrow:after { top: 0; opacity: 1; }';
		}
	
		if ( $next ) {
			if ( has_post_thumbnail( $next->ID ) ) {
				$img = wp_get_attachment_image_src( get_post_thumbnail_id( $next->ID ), muji_get_thumb_size('med') );
				$img = $img[0];
			} else if (muji_get_theme_setting('allow_no_image'))
				$img = $noimg;
			if ( !empty($img) )
				$css .= '.post-navigation .nav-next a .nav-arrow { background-image: url(' . esc_url( $img ) . '); }';
			else
				$css .= '.post-navigation .nav-next a .nav-arrow { background-color: rgba(128,128,128,0.05); border-color:rgba(128,128,128,0.1); }'
					 .  '.post-navigation .nav-next a .nav-arrow:after { top: 0; opacity: 1; }';
		}
	
		wp_add_inline_style( 'muji-main', $css );
	}
}

// Show related posts
if ( !function_exists('muji_show_related_posts') ) {
	function muji_show_related_posts($args=array(), $style=1, $title='') {
		$args = array_merge(array(
			'ignore_sticky_posts' => true,
			'posts_per_page' => 2,
			'columns' => 0,
			'orderby' => 'rand',
			'order' => 'DESC',
			'post_type' => '',
			'post_status' => 'publish',
			'post__not_in' => array(),
			'category__in' => array()
			), $args);
		
		if (empty($args['post_type'])) $args['post_type'] = get_post_type();

		$taxonomy = $args['post_type'] == 'post' ? 'category' : muji_get_post_type_taxonomy();
				
		$args['post__not_in'][] = get_the_ID();
		
		if (empty($args['columns'])) $args['columns'] = $args['posts_per_page'];
		
		if (empty($args['category__in']) || is_array($args['category__in']) && count($args['category__in']) == 0) {
			$post_categories_ids = array();
			$post_cats = get_the_terms(get_the_ID(), $taxonomy);
			if (is_array($post_cats) && !empty($post_cats)) {
				foreach ($post_cats as $cat) {
					$post_categories_ids[] = $cat->term_id;
				}
			}
			$args['category__in'] = $post_categories_ids;
		}

		if ($args['post_type'] != 'post' && count($args['category__in']) > 0) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy,
					'field' => 'term_taxonomy_id',
					'terms' => $args['category__in']
				)
			);
			unset($args['category__in']);
		}
		
		$query = new WP_Query( $args );
		if ($query->found_posts > 0) {
			?>
			<section class="related_wrap">
				<h3 class="section_title related_wrap_title"><?php
					if (!empty($title))
						echo esc_html($title);
					else
						esc_html_e('You May Also Like', 'muji');
				?></h3>
				<div class="columns_wrap posts_container columns_padding_bottom">
					<?php
					while ( $query->have_posts() ) { $query->the_post();
						?><div class="column-1_<?php echo intval(max(1, min(4, $args['columns']))); ?>"><?php
							 get_template_part('templates/related-posts', $style);
						?></div><?php
					}
					wp_reset_postdata();
					?>
				</div>
			</section>
		<?php
		}
	}
}


// Show portfolio posts
if ( !function_exists('muji_show_portfolio_posts') ) {
	function muji_show_portfolio_posts($args=array()) {
		$args = array_merge(array(
			'cat' => 0,
			'parent_cat' => 0,
			'taxonomy' => 'category',
			'post_type' => 'post',
			'page' => 1,
			'sticky' => false,
			'blog_style' => '',
			'echo' => true
			), $args);

		$blog_style = explode('_', empty($args['blog_style']) ? muji_get_theme_option('blog_style') : $args['blog_style']);
		$style = $blog_style[0];
		$columns = empty($blog_style[1]) ? 2 : max(2, $blog_style[1]);

		if ( !$args['echo'] ) {
			ob_start();

			$q_args = array(
				'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') 
										? array('publish', 'private') 
										: 'publish'
			);
			$q_args = muji_query_add_posts_and_cats($q_args, '', $args['post_type'], $args['cat'], $args['taxonomy']);
			if ($args['page'] > 1) {
				$q_args['paged'] = $args['page'];
				$q_args['ignore_sticky_posts'] = true;
			}
			$ppp = muji_get_theme_option('posts_per_page');
			if ((int) $ppp != 0)
				$q_args['posts_per_page'] = (int) $ppp;

			// Make a new query
			$q = 'wp_query';
			$GLOBALS[$q] = new WP_Query( $q_args );
		}

		// Show posts
		$class = sprintf('portfolio_wrap posts_container portfolio_%s', $columns)
				. ($style!='portfolio' ? sprintf(' %s_wrap %s_%s', $style, $style, $columns) : '');
		if ($args['sticky']) {
			?><div class="columns_wrap sticky_wrap"><?php	
		} else {
			?><div class="<?php echo esc_attr($class); ?>"><?php	
		}
	
		while ( have_posts() ) { the_post(); 
			if ($args['sticky'] && !is_sticky()) {
				$args['sticky'] = false;
				?></div><div class="<?php echo esc_attr($class); ?>"><?php
			}
			get_template_part( 'content', $args['sticky'] && is_sticky() ? 'sticky' : ($style == 'gallery' ? 'portfolio-gallery' : $style) );
		}
		
		?></div><?php
	
		muji_show_pagination();
		
		if (!$args['echo']) {
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
	}
}

// AJAX handler for the muji_ajax_get_posts action
if ( !function_exists( 'muji_ajax_get_posts_callback' ) ) {
	add_action('wp_ajax_muji_ajax_get_posts',			'muji_ajax_get_posts_callback');
	add_action('wp_ajax_nopriv_muji_ajax_get_posts',	'muji_ajax_get_posts_callback');
	function muji_ajax_get_posts_callback() {
		if ( !wp_verify_nonce( muji_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			wp_die();
	
		$id = !empty($_REQUEST['blog_template']) ? wp_kses_data(wp_unslash($_REQUEST['blog_template'])) : 0;
		if ($id > 0) {
			muji_storage_set('blog_archive', true);
			muji_storage_set('blog_mode', 'blog');
			muji_storage_set('options_meta', get_post_meta($id, 'muji_options', true));
		}

		$response = array(
			'error'=>'', 
			'data' => muji_show_portfolio_posts(array(
							'cat' => intval(wp_unslash($_REQUEST['cat'])),
							'parent_cat' => intval(wp_unslash($_REQUEST['parent_cat'])),
							'page' => intval(wp_unslash($_REQUEST['page'])),
							'post_type' => trim(wp_unslash($_REQUEST['post_type'])),
							'taxonomy' => trim(wp_unslash($_REQUEST['taxonomy'])),
							'blog_style' => trim(wp_unslash($_REQUEST['blog_style'])),
							'echo' => false
							)
						)
		);

		if (empty($response['data'])) {
			$response['error'] = esc_html__('Sorry, but nothing matched your search criteria.', 'muji');
		}
		echo json_encode($response);
		wp_die();
	}
}


// Show pagination
if ( !function_exists('muji_show_pagination') ) {
	function muji_show_pagination() {
		global $wp_query;
		// Pagination
		$pagination = muji_get_theme_option('blog_pagination');
		if ($pagination == 'pages') {
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => esc_html__( '<', 'muji' ),
				'next_text' => esc_html__( '>', 'muji' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'muji' ) . ' </span>',
			) );
		} else if ($pagination == 'more' || $pagination == 'infinite') {
			$page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
			if ($page_number < $wp_query->max_num_pages) {
				?>
				<div class="nav-links-more<?php if ($pagination == 'infinite') echo ' nav-links-infinite'; ?>">
					<a class="nav-load-more" href="#" 
						data-page="<?php echo esc_attr($page_number); ?>" 
						data-max-page="<?php echo esc_attr($wp_query->max_num_pages); ?>"
						><span><?php esc_html_e('Load more posts', 'muji'); ?></span></a>
				</div>
				<?php
			}
		} else if ($pagination == 'links') {
			?>
			<div class="nav-links-old">
				<span class="nav-prev"><?php previous_posts_link( is_search() ? esc_html__('Previous posts', 'muji') : esc_html__('Newest posts', 'muji') ); ?></span>
				<span class="nav-next"><?php next_posts_link( is_search() ? esc_html__('Next posts', 'muji') : esc_html__('Older posts', 'muji'), $wp_query->max_num_pages ); ?></span>
			</div>
			<?php
		}
	}
}
?>