<?php
/* Woocommerce support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if (!function_exists('muji_woocommerce_theme_setup1')) {
	add_action( 'after_setup_theme', 'muji_woocommerce_theme_setup1', 1 );
	function muji_woocommerce_theme_setup1() {

		add_theme_support( 'woocommerce', array( 'product_grid' => array( 'max_columns' => 4 ) ) );

		// Next setting from the WooCommerce 3.0+ enable built-in image zoom on the single product page
		add_theme_support( 'wc-product-gallery-zoom' );

		// Next setting from the WooCommerce 3.0+ enable built-in image slider on the single product page
		add_theme_support( 'wc-product-gallery-slider' ); 

		// Next setting from the WooCommerce 3.0+ enable built-in image lightbox on the single product page
		add_theme_support( 'wc-product-gallery-lightbox' );

		add_filter( 'muji_filter_list_sidebars', 	'muji_woocommerce_list_sidebars' );
		add_filter( 'muji_filter_list_posts_types',	'muji_woocommerce_list_post_types');
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if (!function_exists('muji_woocommerce_theme_setup3')) {
	add_action( 'after_setup_theme', 'muji_woocommerce_theme_setup3', 3 );
	function muji_woocommerce_theme_setup3() {
		if (muji_exists_woocommerce()) {
		
			// Section 'WooCommerce'
			muji_storage_set_array_before('options', 'fonts', array_merge(
				array(
					'shop' => array(
						"title" => esc_html__('Shop', 'muji'),
						"desc" => wp_kses_data( __('Select parameters to display the shop pages', 'muji') ),
						"priority" => 80,
						"type" => "section"
						),

					'products_info_shop' => array(
						"title" => esc_html__('Products list', 'muji'),
						"desc" => '',
						"type" => "info",
						),
					'shop_mode' => array(
						"title" => esc_html__('Shop style', 'muji'),
						"desc" => wp_kses_data( __('Select style for the products list', 'muji') ),
						"std" => 'thumbs',
						"options" => array(
							'thumbs'=> esc_html__('Grid', 'muji'),
							'list'	=> esc_html__('List', 'muji'),
						),
						"type" => "select"
						),
					'shop_hover' => array(
						"title" => esc_html__('Hover style', 'muji'),
						"desc" => wp_kses_data( __('Hover style on the products in the shop archive', 'muji') ),
						"std" => 'shop',
						"options" => apply_filters('muji_filter_shop_hover', array(
							'none' => esc_html__('None', 'muji'),
							'shop' => esc_html__('Icons', 'muji'),
							'shop_buttons' => esc_html__('Buttons', 'muji')
						)),
						"type" => "select"
						),

					'single_info_shop' => array(
						"title" => esc_html__('Single product', 'muji'),
						"desc" => '',
						"type" => "info",
						),
					'stretch_tabs_area' => array(
						"title" => esc_html__('Stretch tabs area', 'muji'),
						"desc" => wp_kses_data( __('Stretch area with tabs on the single product to the screen width if the sidebar is hidden', 'muji') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'show_related_posts_shop' => array(
						"title" => esc_html__('Show related products', 'muji'),
						"desc" => wp_kses_data( __("Show section 'Related products' on the single product page", 'muji') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'related_posts_shop' => array(
						"title" => esc_html__('Related products', 'muji'),
						"desc" => wp_kses_data( __('How many related products should be displayed on the single product page?', 'muji') ),
						"dependency" => array(
							'show_related_posts_shop' => array(1)
						),
						"std" => 3,
						"options" => muji_get_list_range(1,9),
						"type" => "select"
						),
					'related_columns_shop' => array(
						"title" => esc_html__('Related columns', 'muji'),
						"desc" => wp_kses_data( __('How many columns should be used to output related products on the single product page?', 'muji') ),
						"dependency" => array(
							'show_related_posts_shop' => array(1)
						),
						"std" => 3,
						"options" => muji_get_list_range(1,4),
						"type" => "select"
						)
				),
				muji_options_get_list_cpt_options('shop')
			));
		}
	}
}


// Add section 'Products' to the Front Page option
if (!function_exists('muji_woocommerce_front_page_options')) {
	if (!MUJI_THEME_FREE) add_filter( 'muji_filter_front_page_options', 'muji_woocommerce_front_page_options' );
	function muji_woocommerce_front_page_options($options) {
		if (muji_exists_woocommerce()) {

			$options['front_page_sections']['std'] .= (!empty($options['front_page_sections']['std']) ? '|' : '') . 'woocommerce=1';
			$options['front_page_sections']['options'] = array_merge($options['front_page_sections']['options'], 
																	array(
																		'woocommerce' => esc_html__('Products', 'muji')
																		)
																	);
			$options = array_merge($options, array(
			
				// Front Page Sections - WooCommerce
				'front_page_woocommerce' => array(
					"title" => esc_html__('Products', 'muji'),
					"desc" => '',
					"priority" => 200,
					"type" => "section",
					),
				'front_page_woocommerce_layout_info' => array(
					"title" => esc_html__('Layout', 'muji'),
					"desc" => '',
					"type" => "info",
					),
				'front_page_woocommerce_fullheight' => array(
					"title" => esc_html__('Full height', 'muji'),
					"desc" => wp_kses_data( __('Stretch this section to the window height', 'muji') ),
					"std" => 0,
					"refresh" => false,
					"type" => "checkbox"
					),
				'front_page_woocommerce_paddings' => array(
					"title" => esc_html__('Paddings', 'muji'),
					"desc" => wp_kses_data( __('Select paddings inside this section', 'muji') ),
					"std" => 'medium',
					"options" => muji_get_list_paddings(),
					"refresh" => false,
					"type" => "switch"
					),
				'front_page_woocommerce_heading_info' => array(
					"title" => esc_html__('Title', 'muji'),
					"desc" => '',
					"type" => "info",
					),
				'front_page_woocommerce_caption' => array(
					"title" => esc_html__('Section title', 'muji'),
					"desc" => '',
					"refresh" => false,
					"std" => wp_kses_data(__('This text can be changed in the section "Products"', 'muji')),
					"type" => "text"
					),
				'front_page_woocommerce_description' => array(
					"title" => esc_html__('Description', 'muji'),
					"desc" => wp_kses_data( __("Short description after the section's title", 'muji') ),
					"refresh" => false,
					"std" => wp_kses_data(__('This text can be changed in the section "Products"', 'muji')),
					"type" => "textarea"
					),
				'front_page_woocommerce_products_info' => array(
					"title" => esc_html__('Products parameters', 'muji'),
					"desc" => '',
					"type" => "info",
					),
				'front_page_woocommerce_products' => array(
					"title" => esc_html__('Type of the products', 'muji'),
					"desc" => '',
					"std" => 'products',
					"options" => array(
									'recent_products' => esc_html__('Recent products', 'muji'),
									'featured_products' => esc_html__('Featured products', 'muji'),
									'top_rated_products' => esc_html__('Top rated products', 'muji'),
									'sale_products' => esc_html__('Sale products', 'muji'),
									'best_selling_products' => esc_html__('Best selling products', 'muji'),
									'product_category' => esc_html__('Products from categories', 'muji'),
									'products' => esc_html__('Products by IDs', 'muji')
									),
					"type" => "select"
					),
				'front_page_woocommerce_products_categories' => array(
					"title" => esc_html__('Categories', 'muji'),
					"desc" => esc_html__('Comma separated category slugs. Used only with "Products from categories"', 'muji'),
					"dependency" => array(
						'front_page_woocommerce_products' => array('product_category')
					),
					"std" => '',
					"type" => "text"
					),
				'front_page_woocommerce_products_per_page' => array(
					"title" => esc_html__('Per page', 'muji'),
					"desc" => wp_kses_data( __('How many products will be displayed on the page. Attention! For "Products by IDs" specify comma separated list of the IDs', 'muji') ),
					"std" => 3,
					"type" => "text"
					),
				'front_page_woocommerce_products_columns' => array(
					"title" => esc_html__('Columns', 'muji'),
					"desc" => wp_kses_data( __("How many columns will be used", 'muji') ),
					"std" => 2,
					"type" => "text"
					),
				'front_page_woocommerce_products_orderby' => array(
					"title" => esc_html__('Order by', 'muji'),
					"desc" => wp_kses_data( __("Not used with Best selling products", 'muji') ),
					"std" => 'date',
					"options" => array(
									'date' => esc_html__('Date', 'muji'),
									'title' => esc_html__('Title', 'muji')
									),
					"type" => "switch"
					),
				'front_page_woocommerce_products_order' => array(
					"title" => esc_html__('Order', 'muji'),
					"desc" => wp_kses_data( __("Not used with Best selling products", 'muji') ),
					"std" => 'desc',
					"options" => array(
									'asc' => esc_html__('Ascending', 'muji'),
									'desc' => esc_html__('Descending', 'muji')
									),
					"type" => "switch"
					),
				'front_page_woocommerce_color_info' => array(
					"title" => esc_html__('Colors and images', 'muji'),
					"desc" => '',
					"type" => "info",
					),
				'front_page_woocommerce_scheme' => array(
					"title" => esc_html__('Color scheme', 'muji'),
					"desc" => wp_kses_data( __('Color scheme for this section', 'muji') ),
					"std" => 'inherit',
					"options" => array(),
					"refresh" => false,
					"type" => "switch"
					),
				'front_page_woocommerce_bg_image' => array(
					"title" => esc_html__('Background image', 'muji'),
					"desc" => wp_kses_data( __('Select or upload background image for this section', 'muji') ),
					"refresh" => '.front_page_section_woocommerce',
					"refresh_wrapper" => true,
					"std" => '',
					"type" => "image"
					),
				'front_page_woocommerce_bg_color' => array(
					"title" => esc_html__('Background color', 'muji'),
					"desc" => wp_kses_data( __('Background color for this section', 'muji') ),
					"std" => '',
					"refresh" => false,
					"type" => "color"
					),
				'front_page_woocommerce_bg_mask' => array(
					"title" => esc_html__('Background mask', 'muji'),
					"desc" => wp_kses_data( __('Use Background color as section mask with specified opacity. If 0 - mask is not used', 'muji') ),
					"std" => 1,
					"max" => 1,
					"step" => 0.1,
					"refresh" => false,
					"type" => "slider"
					),
				'front_page_woocommerce_anchor_info' => array(
					"title" => esc_html__('Anchor', 'muji'),
					"desc" => wp_kses_data( __('You can select icon and/or specify a text to create anchor for this section and show it in the side menu (if selected in the section "Header - Menu".', 'muji'))
								. '<br>'
								. wp_kses_data(__('Attention! Anchors available only if plugin "ThemeREX Addons is installed and activated!', 'muji')),
					"type" => "info",
					),
				'front_page_woocommerce_anchor_icon' => array(
					"title" => esc_html__('Anchor icon', 'muji'),
					"desc" => '',
					"std" => '',
					"type" => "icon"
					),
				'front_page_woocommerce_anchor_text' => array(
					"title" => esc_html__('Anchor text', 'muji'),
					"desc" => '',
					"std" => '',
					"type" => "text"
					)
			));
		}
		return $options;
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('muji_woocommerce_theme_setup9')) {
	add_action( 'after_setup_theme', 'muji_woocommerce_theme_setup9', 9 );
	function muji_woocommerce_theme_setup9() {
		
		add_filter( 'muji_filter_merge_styles',							'muji_woocommerce_merge_styles' );
		add_filter( 'muji_filter_merge_styles_responsive',				'muji_woocommerce_merge_styles_responsive' );
		add_filter( 'muji_filter_merge_scripts',							'muji_woocommerce_merge_scripts');

		if (muji_exists_woocommerce()) {
			add_action( 'wp_enqueue_scripts', 								'muji_woocommerce_frontend_scripts', 1100 );
			add_filter( 'muji_filter_get_post_info',		 				'muji_woocommerce_get_post_info');
			add_filter( 'muji_filter_post_type_taxonomy',				'muji_woocommerce_post_type_taxonomy', 10, 2 );
			add_action( 'muji_action_override_theme_options',			'muji_woocommerce_override_theme_options');
			if (!is_admin()) {
				add_filter( 'muji_filter_detect_blog_mode',				'muji_woocommerce_detect_blog_mode');
				add_filter( 'muji_filter_get_post_categories', 			'muji_woocommerce_get_post_categories');
				add_filter( 'muji_filter_allow_override_header_image',	'muji_woocommerce_allow_override_header_image');
				add_filter( 'muji_filter_get_blog_title',				'muji_woocommerce_get_blog_title');
				add_action( 'muji_action_before_post_meta',				'muji_woocommerce_action_before_post_meta');
				add_action( 'pre_get_posts',								'muji_woocommerce_pre_get_posts');
				add_filter( 'muji_filter_localize_script',				'muji_woocommerce_localize_script');
			}
		}
		if (is_admin()) {
			add_filter( 'muji_filter_tgmpa_required_plugins',			'muji_woocommerce_tgmpa_required_plugins' );
		}

		// Add wrappers and classes to the standard WooCommerce output
		if (muji_exists_woocommerce()) {

			// Remove WOOC sidebar
			remove_action( 'woocommerce_sidebar', 						'woocommerce_get_sidebar', 10 );

			// Remove link around product item
			remove_action('woocommerce_before_shop_loop_item',			'woocommerce_template_loop_product_link_open', 10);
			remove_action('woocommerce_after_shop_loop_item',			'woocommerce_template_loop_product_link_close', 5);
			
			// Remove link around product category
			remove_action('woocommerce_before_subcategory',				'woocommerce_template_loop_category_link_open', 10);
			remove_action('woocommerce_after_subcategory',				'woocommerce_template_loop_category_link_close', 10);
			
			// Open main content wrapper - <article>
			remove_action( 'woocommerce_before_main_content',			'woocommerce_output_content_wrapper', 10);
			add_action(    'woocommerce_before_main_content',			'muji_woocommerce_wrapper_start', 10);
			// Close main content wrapper - </article>
			remove_action( 'woocommerce_after_main_content',			'woocommerce_output_content_wrapper_end', 10);		
			add_action(    'woocommerce_after_main_content',			'muji_woocommerce_wrapper_end', 10);

			// Close header section
			add_action(    'woocommerce_after_main_content',			'muji_woocommerce_archive_description', 1);
			add_action(    'woocommerce_before_shop_loop',				'muji_woocommerce_archive_description', 5 );
			add_action(    'woocommerce_no_products_found',				'muji_woocommerce_archive_description', 5 );

			// Add theme specific search form
			add_filter(    'get_product_search_form',					'muji_woocommerce_get_product_search_form' );

			// Change text on 'Add to cart' button
			add_filter(    'woocommerce_product_add_to_cart_text',		'muji_woocommerce_add_to_cart_text' );
			add_filter(    'woocommerce_product_single_add_to_cart_text','muji_woocommerce_add_to_cart_text' );

			// Add list mode buttons
			add_action(    'woocommerce_before_shop_loop', 				'muji_woocommerce_before_shop_loop', 10 );

			// Open product/category item wrapper
			add_action(    'woocommerce_before_subcategory_title',		'muji_woocommerce_item_wrapper_start', 9 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'muji_woocommerce_item_wrapper_start', 9 );
			// Close featured image wrapper and open title wrapper
			add_action(    'woocommerce_before_subcategory_title',		'muji_woocommerce_title_wrapper_start', 20 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'muji_woocommerce_title_wrapper_start', 20 );

			// Add tags before title
			add_action(    'woocommerce_before_shop_loop_item_title',	'muji_woocommerce_title_tags', 30 );

			// Wrap product title to the link
			add_action(    'the_title',									'muji_woocommerce_the_title');
			// Wrap category title to the link
	
			// New way: WooCommerce 3.2.2+
			add_action(		'woocommerce_before_subcategory_title',		'muji_woocommerce_before_subcategory_title', 22, 1 );
			add_action(		'woocommerce_after_subcategory_title',		'muji_woocommerce_after_subcategory_title', 2, 1 );

			// Close title wrapper and add description in the list mode
			add_action(    'woocommerce_after_shop_loop_item_title',	'muji_woocommerce_title_wrapper_end', 7);
			add_action(    'woocommerce_after_subcategory_title',		'muji_woocommerce_title_wrapper_end2', 10 );
			// Close product/category item wrapper
			add_action(    'woocommerce_after_subcategory',				'muji_woocommerce_item_wrapper_end', 20 );
			add_action(    'woocommerce_after_shop_loop_item',			'muji_woocommerce_item_wrapper_end', 20 );

			// Add product ID into product meta section (after categories and tags)
			add_action(    'woocommerce_product_meta_end',				'muji_woocommerce_show_product_id', 10);
			
			// Set columns number for the product's thumbnails
			add_filter(    'woocommerce_product_thumbnails_columns',	'muji_woocommerce_product_thumbnails_columns' );

			// Decorate price
			add_filter(    'woocommerce_get_price_html',				'muji_woocommerce_get_price_html' );
			
			// Add 'Out of stock' label
			add_action(    'muji_action_woocommerce_item_featured_link_start', 'muji_woocommerce_add_out_of_stock_label' );

	
			// Detect current shop mode
			if (!is_admin()) {
				$shop_mode = muji_get_value_gpc('muji_shop_mode');
				if (empty($shop_mode) && muji_check_theme_option('shop_mode'))
					$shop_mode = muji_get_theme_option('shop_mode');
				if (empty($shop_mode))
					$shop_mode = 'thumbs';
				muji_storage_set('shop_mode', $shop_mode);
			}
		}
	}
}

// Theme init priorities:
// Action 'wp'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)
if (!function_exists('muji_woocommerce_theme_setup_wp')) {
	add_action( 'wp', 'muji_woocommerce_theme_setup_wp' );
	function muji_woocommerce_theme_setup_wp() {
		if (muji_exists_woocommerce()) {
			// Set columns number for the related products
			if ((int) muji_get_theme_option('show_related_posts') == 0 || (int) muji_get_theme_option('related_posts') == 0) {
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
			} else {
				add_filter(    'woocommerce_output_related_products_args',	'muji_woocommerce_output_related_products_args' );
				add_filter(    'woocommerce_related_products_columns',		'muji_woocommerce_related_products_columns' );
			}
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'muji_woocommerce_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('muji_filter_tgmpa_required_plugins',	'muji_woocommerce_tgmpa_required_plugins');
	function muji_woocommerce_tgmpa_required_plugins($list=array()) {
		if (muji_storage_isset('required_plugins', 'woocommerce')) {
			$list[] = array(
					'name' 		=> muji_storage_get_array('required_plugins', 'woocommerce'),
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				);
		}
		return $list;
	}
}


// Check if WooCommerce installed and activated
if ( !function_exists( 'muji_exists_woocommerce' ) ) {
	function muji_exists_woocommerce() {
		return class_exists('Woocommerce');
	}
}

// Return true, if current page is any woocommerce page
if ( !function_exists( 'muji_is_woocommerce_page' ) ) {
	function muji_is_woocommerce_page() {
		$rez = false;
		if (muji_exists_woocommerce())
			$rez = is_woocommerce() || is_shop() || is_product() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_cart() || is_checkout() || is_account_page();
		return $rez;
	}
}

// Detect current blog mode
if ( !function_exists( 'muji_woocommerce_detect_blog_mode' ) ) {
	//Handler of the add_filter( 'muji_filter_detect_blog_mode', 'muji_woocommerce_detect_blog_mode' );
	function muji_woocommerce_detect_blog_mode($mode='') {
		if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy())
			$mode = 'shop';
		else if (is_product() || is_cart() || is_checkout() || is_account_page())
			$mode = 'shop';
		return $mode;
	}
}

// Override options with stored page meta on 'Shop' pages
if ( !function_exists('muji_woocommerce_override_theme_options') ) {
	//Handler of the add_action( 'muji_action_override_theme_options', 'muji_woocommerce_override_theme_options');
	function muji_woocommerce_override_theme_options() {
		// Remove ' || is_product()' from the condition in the next row
		// if you don't need to override theme options from the page 'Shop' on single products
		if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_product()) {
			if (($id = muji_woocommerce_get_shop_page_id()) > 0)
				muji_storage_set('options_meta', get_post_meta($id, 'muji_options', true));
		}
	}
}

// Return current page title
if ( !function_exists( 'muji_woocommerce_get_blog_title' ) ) {
	//Handler of the add_filter( 'muji_filter_get_blog_title', 'muji_woocommerce_get_blog_title');
	function muji_woocommerce_get_blog_title($title='') {
		if (!muji_exists_trx_addons() && muji_exists_woocommerce() && muji_is_woocommerce_page() && is_shop()) {
			$id = muji_woocommerce_get_shop_page_id();
			$title = $id ? get_the_title($id) : esc_html__('Shop', 'muji');
		}
		return $title;
	}
}


// Return taxonomy for current post type
if ( !function_exists( 'muji_woocommerce_post_type_taxonomy' ) ) {
	//Handler of the add_filter( 'muji_filter_post_type_taxonomy',	'muji_woocommerce_post_type_taxonomy', 10, 2 );
	function muji_woocommerce_post_type_taxonomy($tax='', $post_type='') {
		if ($post_type == 'product')
			$tax = 'product_cat';
		return $tax;
	}
}

// Return true if page title section is allowed
if ( !function_exists( 'muji_woocommerce_allow_override_header_image' ) ) {
	//Handler of the add_filter( 'muji_filter_allow_override_header_image', 'muji_woocommerce_allow_override_header_image' );
	function muji_woocommerce_allow_override_header_image($allow=true) {
		return is_product() ? false : $allow;
	}
}

// Return shop page ID
if ( !function_exists( 'muji_woocommerce_get_shop_page_id' ) ) {
	function muji_woocommerce_get_shop_page_id() {
		return get_option('woocommerce_shop_page_id');
	}
}

// Return shop page link
if ( !function_exists( 'muji_woocommerce_get_shop_page_link' ) ) {
	function muji_woocommerce_get_shop_page_link() {
		$url = '';
		$id = muji_woocommerce_get_shop_page_id();
		if ($id) $url = get_permalink($id);
		return $url;
	}
}

// Show categories of the current product
if ( !function_exists( 'muji_woocommerce_get_post_categories' ) ) {
	//Handler of the add_filter( 'muji_filter_get_post_categories', 		'muji_woocommerce_get_post_categories');
	function muji_woocommerce_get_post_categories($cats='') {
		if (get_post_type()=='product') {
			$cats = muji_get_post_terms(', ', get_the_ID(), 'product_cat');
		}
		return $cats;
	}
}

// Add 'product' to the list of the supported post-types
if ( !function_exists( 'muji_woocommerce_list_post_types' ) ) {
	//Handler of the add_filter( 'muji_filter_list_posts_types', 'muji_woocommerce_list_post_types');
	function muji_woocommerce_list_post_types($list=array()) {
		$list['product'] = esc_html__('Products', 'muji');
		return $list;
	}
}

// Show price of the current product in the widgets and search results
if ( !function_exists( 'muji_woocommerce_get_post_info' ) ) {
	//Handler of the add_filter( 'muji_filter_get_post_info', 'muji_woocommerce_get_post_info');
	function muji_woocommerce_get_post_info($post_info='') {
		if (get_post_type()=='product') {
			global $product;
			if ( $price_html = $product->get_price_html() ) {
				$post_info = '<div class="post_price product_price price">' . trim($price_html) . '</div>' . $post_info;
			}
		}
		return $post_info;
	}
}

// Show price of the current product in the search results streampage
if ( !function_exists( 'muji_woocommerce_action_before_post_meta' ) ) {
	//Handler of the add_action( 'muji_action_before_post_meta', 'muji_woocommerce_action_before_post_meta');
	function muji_woocommerce_action_before_post_meta() {
		if (!is_single() && get_post_type()=='product') {
			global $product;
			if ( $price_html = $product->get_price_html() ) {
				?><div class="post_price product_price price"><?php muji_show_layout($price_html); ?></div><?php
			}
		}
	}
}
	
// Enqueue WooCommerce custom styles
if ( !function_exists( 'muji_woocommerce_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'muji_woocommerce_frontend_scripts', 1100 );
	function muji_woocommerce_frontend_scripts() {
			if (muji_is_on(muji_get_theme_option('debug_mode')) && muji_get_file_dir('plugins/woocommerce/woocommerce.js')!='')
				wp_enqueue_script( 'muji-woocommerce', muji_get_file_url('plugins/woocommerce/woocommerce.js'), array('jquery'), null, true );
	}
}
	
// Merge custom styles
if ( !function_exists( 'muji_woocommerce_merge_styles' ) ) {
	//Handler of the add_filter('muji_filter_merge_styles', 'muji_woocommerce_merge_styles');
	function muji_woocommerce_merge_styles($list) {
		if (muji_exists_woocommerce()) {
			$list[] = 'plugins/woocommerce/_woocommerce.scss';
		}
		return $list;
	}
}


// Merge responsive styles
if ( !function_exists( 'muji_woocommerce_merge_styles_responsive' ) ) {
	//Handler of the add_filter('muji_filter_merge_styles_responsive', 'muji_woocommerce_merge_styles_responsive');
	function muji_woocommerce_merge_styles_responsive($list) {
		if (muji_exists_woocommerce()) {
			$list[] = 'plugins/woocommerce/_woocommerce-responsive.scss';
		}
		return $list;
	}
}

// Merge custom scripts
if ( !function_exists( 'muji_woocommerce_merge_scripts' ) ) {
	//Handler of the add_filter('muji_filter_merge_scripts', 'muji_woocommerce_merge_scripts');
	function muji_woocommerce_merge_scripts($list) {
		if (muji_exists_woocommerce()) {
			$list[] = 'plugins/woocommerce/woocommerce.js';
		}
		return $list;
	}
}



// Add WooCommerce specific items into lists
//------------------------------------------------------------------------

// Add sidebar
if ( !function_exists( 'muji_woocommerce_list_sidebars' ) ) {
	//Handler of the add_filter( 'muji_filter_list_sidebars', 'muji_woocommerce_list_sidebars' );
	function muji_woocommerce_list_sidebars($list=array()) {
		$list['woocommerce_widgets'] = array(
											'name' => esc_html__('WooCommerce Widgets', 'muji'),
											'description' => esc_html__('Widgets to be shown on the WooCommerce pages', 'muji')
											);
		return $list;
	}
}




// Decorate WooCommerce output: Loop
//------------------------------------------------------------------------

// Add query vars to set products per page
if (!function_exists('muji_woocommerce_pre_get_posts')) {
	//Handler of the add_action( 'pre_get_posts', 'muji_woocommerce_pre_get_posts' );
	function muji_woocommerce_pre_get_posts($query) {
		if (!$query->is_main_query()) return;
		if ($query->get('wc_query') == 'product_query') {
			$ppp = get_theme_mod('posts_per_page_shop', 0);
			if ($ppp > 0)
				$query->set('posts_per_page', $ppp);
		}
	}
}


// Before main content
if ( !function_exists( 'muji_woocommerce_wrapper_start' ) ) {
	//Handler of the add_action('woocommerce_before_main_content', 'muji_woocommerce_wrapper_start', 10);
	function muji_woocommerce_wrapper_start() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			<article class="post_item_single post_type_product">
			<?php
		} else {
			?>
			<div class="list_products shop_mode_<?php echo esc_attr(!muji_storage_empty('shop_mode') ? muji_storage_get('shop_mode') : 'thumbs'); ?>">
				<div class="list_products_header">
			<?php
			muji_storage_set('woocommerce_list_products_header', true);
		}
	}
}

// After main content
if ( !function_exists( 'muji_woocommerce_wrapper_end' ) ) {	
	//Handler of the add_action('woocommerce_after_main_content', 'muji_woocommerce_wrapper_end', 10);
	function muji_woocommerce_wrapper_end() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			</article><!-- /.post_item_single -->
			<?php
		} else {
			?>
			</div><!-- /.list_products -->
			<?php
		}
	}
}

// Close header section
if ( !function_exists( 'muji_woocommerce_archive_description' ) ) {
	//Handler of the add_action('woocommerce_after_main_content', 'muji_woocommerce_archive_description', 1);
	//Handler of the add_action( 'woocommerce_before_shop_loop',	'muji_woocommerce_archive_description', 5 );
	//Handler of the add_action( 'woocommerce_no_products_found',	'muji_woocommerce_archive_description', 5 );
	function muji_woocommerce_archive_description() {
		if (muji_storage_get('woocommerce_list_products_header')) {
			?>
			</div><!-- /.list_products_header -->
			<?php
			muji_storage_set('woocommerce_list_products_header', false);
			remove_action('woocommerce_after_main_content', 'muji_woocommerce_archive_description', 1);
		} else if (!is_singular())
			get_template_part( 'content', 'none-search' );
	}
}

// Add list mode buttons
if ( !function_exists( 'muji_woocommerce_before_shop_loop' ) ) {
	//Handler of the add_action( 'woocommerce_before_shop_loop', 'muji_woocommerce_before_shop_loop', 10 );
	function muji_woocommerce_before_shop_loop() {
		?>
		<div class="muji_shop_mode_buttons"><form action="<?php echo esc_url(muji_get_current_url()); ?>" method="post"><input type="hidden" name="muji_shop_mode" value="<?php echo esc_attr(muji_storage_get('shop_mode')); ?>" /><a href="#" class="woocommerce_thumbs icon-th" title="<?php esc_attr_e('Show products as thumbs', 'muji'); ?>"></a><a href="#" class="woocommerce_list icon-th-list" title="<?php esc_attr_e('Show products as list', 'muji'); ?>"></a></form></div><!-- /.muji_shop_mode_buttons -->
		<?php
	}
}

// Number of columns for the shop streampage
if ( !function_exists( 'muji_woocommerce_loop_shop_columns' ) ) {
	//Handler of the add_filter( 'loop_shop_columns', 'muji_woocommerce_loop_shop_columns' );
	function muji_woocommerce_loop_shop_columns($cols) {
		return max(2, min(4, muji_get_theme_option('blog_columns')));
	}
}

// Add column class into product item in shop streampage
if ( !function_exists( 'muji_woocommerce_loop_shop_columns_class' ) ) {
	//Handler of the add_filter( 'post_class', 'muji_woocommerce_loop_shop_columns_class' );
	//Handler of the add_filter( 'product_cat_class', 'muji_woocommerce_loop_shop_columns_class', 10, 3 );
	function muji_woocommerce_loop_shop_columns_class($classes, $class='', $cat='') {
		global $woocommerce_loop;
		if (is_product()) {
			if (!empty($woocommerce_loop['columns'])) {
				$classes[] = ' column-1_'.esc_attr($woocommerce_loop['columns']);
			}
		} else if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) {
			$classes[] = ' column-1_'.esc_attr(max(2, min(4, muji_get_theme_option('blog_columns'))));
		}
		return $classes;
	}
}


// Open item wrapper for categories and products
if ( !function_exists( 'muji_woocommerce_item_wrapper_start' ) ) {
	//Handler of the add_action( 'woocommerce_before_subcategory_title', 'muji_woocommerce_item_wrapper_start', 9 );
	//Handler of the add_action( 'woocommerce_before_shop_loop_item_title', 'muji_woocommerce_item_wrapper_start', 9 );
	function muji_woocommerce_item_wrapper_start($cat='') {
		muji_storage_set('in_product_item', true);
		$hover = muji_get_theme_option('shop_hover');
		?>
		<div class="post_item post_layout_<?php echo esc_attr(is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ? muji_storage_get('shop_mode') : 'thumbs'); ?>">
			<div class="post_featured hover_<?php echo esc_attr($hover); ?>">
				<?php do_action('muji_action_woocommerce_item_featured_start'); ?>
				<a href="<?php echo esc_url(is_object($cat) ? get_term_link($cat->slug, 'product_cat') : get_permalink()); ?>">
				<?php
				do_action('muji_action_woocommerce_item_featured_link_start');
	}
}

// Open item wrapper for categories and products
if ( !function_exists( 'muji_woocommerce_open_item_wrapper' ) ) {
	//Handler of the add_action( 'woocommerce_before_subcategory_title', 'muji_woocommerce_title_wrapper_start', 20 );
	//Handler of the add_action( 'woocommerce_before_shop_loop_item_title', 'muji_woocommerce_title_wrapper_start', 20 );
	function muji_woocommerce_title_wrapper_start($cat='') {
				do_action('muji_action_woocommerce_item_featured_link_end');
				?></a><?php
				if (($hover = muji_get_theme_option('shop_hover')) != 'none') {
					?><div class="mask"></div><?php
					muji_hovers_add_icons($hover, array('cat'=>$cat));
				}
				do_action('muji_action_woocommerce_item_featured_end');
				?>
			</div><!-- /.post_featured -->
			<div class="post_data">
				<div class="post_data_inner">
					<div class="post_header entry-header">
					<?php
					do_action('muji_action_woocommerce_item_header_start');
	}
}


// Display product's tags before the title
if ( !function_exists( 'muji_woocommerce_title_tags' ) ) {
	//Handler of the add_action( 'woocommerce_before_shop_loop_item_title', 'muji_woocommerce_title_tags', 30 );
	function muji_woocommerce_title_tags() {
		global $product;
		muji_show_layout(wc_get_product_tag_list( $product->get_id(), ', ', '<div class="post_tags product_tags">', '</div>' ));
	}
}

// Wrap product title to the link
if ( !function_exists( 'muji_woocommerce_the_title' ) ) {
	//Handler of the add_filter( 'the_title', 'muji_woocommerce_the_title' );
	function muji_woocommerce_the_title($title) {
		if (muji_storage_get('in_product_item') && get_post_type()=='product') {
			$title = '<a href="'. esc_url(get_permalink()).'">'.esc_html($title).'</a>';
		}
		return $title;
	}
}

// Wrap category title to the link: open tag
if ( !function_exists( 'muji_woocommerce_before_subcategory_title' ) ) {
	//Handler of the add_action( 'woocommerce_before_subcategory_title', 'muji_woocommerce_before_subcategory_title', 10, 1 );
	function muji_woocommerce_before_subcategory_title($cat) {
		if (muji_storage_get('in_product_item') && is_object($cat)) {
			?><a href="<?php echo esc_url(get_term_link($cat->slug, 'product_cat')); ?>"><?php
		}
	}
}

// Wrap category title to the link: close tag
if ( !function_exists( 'muji_woocommerce_after_subcategory_title' ) ) {
	//Handler of the add_action( 'woocommerce_after_subcategory_title', 'muji_woocommerce_after_subcategory_title', 10, 1 );
	function muji_woocommerce_after_subcategory_title($cat) {
		if (muji_storage_get('in_product_item') && is_object($cat)) {
			?></a><?php
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'muji_woocommerce_title_wrapper_end' ) ) {
	//Handler of the add_action( 'woocommerce_after_shop_loop_item_title', 'muji_woocommerce_title_wrapper_end', 7);
	function muji_woocommerce_title_wrapper_end() {
			do_action('muji_action_woocommerce_item_header_end');
			?>
			</div><!-- /.post_header -->
		<?php
		if (muji_storage_get('shop_mode') == 'list' && (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) && !is_product()) {
		    $excerpt = apply_filters('the_excerpt', get_the_excerpt());
			?>
			<div class="post_content entry-content"><?php muji_show_layout($excerpt); ?></div>
			<?php
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'muji_woocommerce_title_wrapper_end2' ) ) {
	//Handler of the add_action( 'woocommerce_after_subcategory_title', 'muji_woocommerce_title_wrapper_end2', 10 );
	function muji_woocommerce_title_wrapper_end2($category) {
			do_action('muji_action_woocommerce_item_header_end');
			?>
			</div><!-- /.post_header -->
		<?php
		if (muji_storage_get('shop_mode') == 'list' && is_shop() && !is_product()) {
			?>
			<div class="post_content entry-content"><?php muji_show_layout($category->description); ?></div><!-- /.post_content -->
			<?php
		}
	}
}

// Close item wrapper for categories and products
if ( !function_exists( 'muji_woocommerce_close_item_wrapper' ) ) {
	//Handler of the add_action( 'woocommerce_after_subcategory', 'muji_woocommerce_item_wrapper_end', 20 );
	//Handler of the add_action( 'woocommerce_after_shop_loop_item', 'muji_woocommerce_item_wrapper_end', 20 );
	function muji_woocommerce_item_wrapper_end($cat='') {
				?>
				</div><!-- /.post_data_inner -->
			</div><!-- /.post_data -->
		</div><!-- /.post_item -->
		<?php
		muji_storage_set('in_product_item', false);
	}
}

// Change text on 'Add to cart' button
if ( !function_exists( 'muji_woocommerce_add_to_cart_text' ) ) {
    //Handler of the add_filter( 'woocommerce_product_add_to_cart_text',	'muji_woocommerce_add_to_cart_text' );
    //Handler of the add_filter( 'woocommerce_product_single_add_to_cart_text','muji_woocommerce_add_to_cart_text' );
    function muji_woocommerce_add_to_cart_text($text='') {
        global $product;
        $product_type = $product->get_type();
        switch ( $product_type ) {
            case 'external':
                return $product->get_button_text();
                break;
            default:
                return esc_html__('Buy now', 'muji');
        }
    }
}

// Add label 'out of stock'
if ( ! function_exists( 'muji_woocommerce_add_out_of_stock_label' ) ) {
	//Handler of the add_action( 'muji_action_woocommerce_item_featured_link_start', 'muji_woocommerce_add_out_of_stock_label' );
	function muji_woocommerce_add_out_of_stock_label() {
		global $product;
		$cat = muji_storage_get( 'in_product_category' );
		if ( empty($cat) || ! is_object($cat) ) {
			if ( is_object( $product ) && ! $product->is_in_stock() ) {
				?>
				<span class="outofstock_label"><?php esc_html_e( 'Out of stock', 'muji' ); ?></span>
				<?php
			}
		}
	}
}


// Decorate price
if ( !function_exists( 'muji_woocommerce_get_price_html' ) ) {
	//Handler of the add_filter(    'woocommerce_get_price_html',	'muji_woocommerce_get_price_html' );
	function muji_woocommerce_get_price_html($price='') {
		if (!is_admin() && !empty($price)) {
			$sep = get_option('woocommerce_price_decimal_sep');
			if (empty($sep)) $sep = '.';
			$price = preg_replace('/([0-9,]+)(\\'.trim($sep).')([0-9]{2})/', '\\1<span class="decimals_separator">\\2</span><span class="decimals">\\3</span>', $price);
		}
		return $price;
	}
}



// Decorate WooCommerce output: Single product
//------------------------------------------------------------------------

// Add WooCommerce specific vars into localize array
if (!function_exists('muji_woocommerce_localize_script')) {
	//Handler of the add_filter( 'muji_filter_localize_script','muji_woocommerce_localize_script' );
	function muji_woocommerce_localize_script($arr) {
		$arr['stretch_tabs_area'] = !muji_sidebar_present() ? muji_get_theme_option('stretch_tabs_area') : 0;
		return $arr;
	}
}

// Add Product ID for the single product
if ( !function_exists( 'muji_woocommerce_show_product_id' ) ) {
	//Handler of the add_action( 'woocommerce_product_meta_end', 'muji_woocommerce_show_product_id', 10);
	function muji_woocommerce_show_product_id() {
		$authors = wp_get_post_terms(get_the_ID(), 'pa_product_author');
		if (is_array($authors) && count($authors)>0) {
			echo '<span class="product_author">'.esc_html__('Author: ', 'muji');
			$delim = '';
			foreach ($authors as $author) {
				echo  esc_html($delim) . '<span>' . esc_html($author->name) . '</span>';
				$delim = ', ';
			}
			echo '</span>';
		}
		echo '<span class="product_id">'.esc_html__('Product ID: ', 'muji') . '<span>' . get_the_ID() . '</span></span>';
	}
}

// Number columns for the product's thumbnails
if ( !function_exists( 'muji_woocommerce_product_thumbnails_columns' ) ) {
	//Handler of the add_filter( 'woocommerce_product_thumbnails_columns', 'muji_woocommerce_product_thumbnails_columns' );
	function muji_woocommerce_product_thumbnails_columns($cols) {
		return 4;
	}
}

// Set products number for the related products
if ( !function_exists( 'muji_woocommerce_output_related_products_args' ) ) {
	//Handler of the add_filter( 'woocommerce_output_related_products_args', 'muji_woocommerce_output_related_products_args' );
	function muji_woocommerce_output_related_products_args($args) {
		$args['posts_per_page'] = (int) muji_get_theme_option('show_related_posts') 
										? max(0, min(9, muji_get_theme_option('related_posts'))) 
										: 0;
		$args['columns'] = max(1, min(4, muji_get_theme_option('related_columns')));
		return $args;
	}
}

// Set columns number for the related products
if ( !function_exists( 'muji_woocommerce_related_products_columns' ) ) {
	//Handler of the add_filter('woocommerce_related_products_columns', 'muji_woocommerce_related_products_columns' );
	function muji_woocommerce_related_products_columns($columns) {
		$columns = max(1, min(4, muji_get_theme_option('related_columns')));
		return $columns;
	}
}


// Filter price step
if ( ! function_exists( 'muji_woocommerce_price_filter_widget_step' ) ) {
    add_filter('woocommerce_price_filter_widget_step', 'muji_woocommerce_price_filter_widget_step');
    function muji_woocommerce_price_filter_widget_step( $step = '' ) {
        $step = 1;
        return $step;
    }
}



// Decorate WooCommerce output: Widgets
//------------------------------------------------------------------------

// Search form
if ( !function_exists( 'muji_woocommerce_get_product_search_form' ) ) {
	//Handler of the add_filter( 'get_product_search_form', 'muji_woocommerce_get_product_search_form' );
	function muji_woocommerce_get_product_search_form($form) {
		return '
		<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
			<input type="text" class="search_field" placeholder="' . esc_attr__('Search for products &hellip;', 'muji') . '" value="' . get_search_query() . '" name="s" /><button class="search_button" type="submit">' . esc_html__('Search', 'muji') . '</button>
			<input type="hidden" name="post_type" value="product" />
		</form>
		';
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (muji_exists_woocommerce()) { require_once MUJI_THEME_DIR . 'plugins/woocommerce/woocommerce-styles.php'; }
?>