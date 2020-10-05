<?php
/**
 * Theme Options and override-options support
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0.29
 */


// -----------------------------------------------------------------
// -- Override-options
// -----------------------------------------------------------------

if ( !function_exists('muji_init_options_override') ) {
	add_action( 'after_setup_theme', 'muji_init_options_override' );
	function muji_init_options_override() {
		if ( is_admin() ) {
			add_action('admin_enqueue_scripts',	'muji_add_options_override_scripts');
			add_action('save_post',				'muji_save_options_override');
			add_filter( 'muji_filter_override_options', 'muji_options_override_add_options' );
		}
	}
}



// Load required styles and scripts for admin mode
if ( !function_exists( 'muji_add_options_override_scripts' ) ) {
	//Handler of the add_action("admin_enqueue_scripts", 'muji_add_options_override_scripts');
	function muji_add_options_override_scripts() {
		// If current screen is 'Edit Page' - load font icons
		$screen = function_exists('get_current_screen') ? get_current_screen() : false;
		if (is_object($screen) && muji_allow_options_override(!empty($screen->post_type) ? $screen->post_type : $screen->id)) {
			wp_enqueue_style( 'fontello-icons',  muji_get_file_url('css/font-icons/css/fontello-embedded.css'), array(), null );
			wp_enqueue_script( 'jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true );
			wp_enqueue_script( 'jquery-ui-accordion', false, array('jquery', 'jquery-ui-core'), null, true );
			wp_enqueue_script( 'muji-options', muji_get_file_url('theme-options/theme-options.js'), array('jquery'), null, true );
			wp_localize_script( 'muji-options', 'muji_dependencies', muji_get_theme_dependencies() );
		}
	}
}


// Check if override options is allow
if (!function_exists('muji_allow_options_override')) {
	function muji_allow_options_override($post_type) {
		return apply_filters('muji_filter_allow_options_override', in_array($post_type, array('page', 'post')), $post_type);
	}
}

// Add overriden options
if (!function_exists('muji_options_override_add_options')) {
	add_filter('muji_filter_override_options', 'muji_options_override_add_options');
	function muji_options_override_add_options($list) {
		global $post_type;
		if (muji_allow_options_override($post_type)) {
			$list[] = array(sprintf('muji_override_options_%s', $post_type),
				esc_html__('Theme Options', 'muji'),
				'muji_options_override_show',
				$post_type,
				$post_type=='post' ? 'side' : 'advanced',
				'default'
			);
		}
		return $list;
	}
}

// Callback function to show fields in override options
if (!function_exists('muji_options_override_show')) {
	function muji_options_override_show($post=false, $args=false) {
		if (empty($post) || !is_object($post) || empty($post->ID)) {
			global $post, $post_type;
			$mb_post_id = $post->ID;
			$mb_post_type = $post_type;
		} else {
			$mb_post_id = $post->ID;
			$mb_post_type = $post->post_type;
		}
		if (muji_allow_options_override($mb_post_type)) {
			// Load saved options 
			$meta = get_post_meta($mb_post_id, 'muji_options', true);
			$tabs_titles = $tabs_content = array();
			global $MUJI_STORAGE;
			// Refresh linked data if this field is controller for the another (linked) field
			// Do this before show fields to refresh data in the $MUJI_STORAGE
			foreach ($MUJI_STORAGE['options'] as $k=>$v) {
				if (!isset($v['override']) || strpos($v['override']['mode'], $mb_post_type)===false) continue;
				if (!empty($v['linked'])) {
					$v['val'] = isset($meta[$k]) ? $meta[$k] : 'inherit';
					if (!empty($v['val']) && !muji_is_inherit($v['val']))
						muji_refresh_linked_data($v['val'], $v['linked']);
				}
			}
			// Show fields
			foreach ($MUJI_STORAGE['options'] as $k=>$v) {
				if (!isset($v['override']) || strpos($v['override']['mode'], $mb_post_type)===false || $v['type'] == 'hidden') continue;
				if (empty($v['override']['section']))
					$v['override']['section'] = esc_html__('General', 'muji');
				if (!isset($tabs_titles[$v['override']['section']])) {
					$tabs_titles[$v['override']['section']] = $v['override']['section'];
					$tabs_content[$v['override']['section']] = '';
				}
				$v['val'] = isset($meta[$k]) ? $meta[$k] : 'inherit';
				$tabs_content[$v['override']['section']] .= muji_options_show_field($k, $v, $mb_post_type);
			}
			if (count($tabs_titles) > 0) {
				?>
				<div class="muji_options muji_options_override">
					<input type="hidden" name="options_override_post_nonce" value="<?php echo esc_attr(wp_create_nonce(admin_url())); ?>" />
					<input type="hidden" name="options_override_post_type" value="<?php echo esc_attr($mb_post_type); ?>" />
					<div id="muji_options_tabs" class="muji_tabs">
						<ul><?php
							$cnt = 0;
							foreach ($tabs_titles as $k=>$v) {
								$cnt++;
								?><li><a href="#muji_options_<?php echo esc_attr($cnt); ?>"><?php echo esc_html($v); ?></a></li><?php
							}
						?></ul>
						<?php
							$cnt = 0;
							foreach ($tabs_content as $k=>$v) {
								$cnt++;
								?>
								<div id="muji_options_<?php echo esc_attr($cnt); ?>" class="muji_tabs_section muji_options_section">
									<?php muji_show_layout($v); ?>
								</div>
								<?php
							}
						?>
					</div>
				</div>
				<?php		
			}
		}
	}
}


// Save data from override options
if (!function_exists('muji_save_options_override')) {
	//Handler of the add_action('save_post', 'muji_save_options_override');
	function muji_save_options_override($post_id) {

		// verify nonce
		if ( !wp_verify_nonce( muji_get_value_gp('options_override_post_nonce'), admin_url() ) )
			return $post_id;

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		$post_type = wp_kses_data(wp_unslash(isset($_POST['options_override_post_type']) ? sanitize_text_field($_POST['options_override_post_type']) : sanitize_text_field($_POST['post_type'])));

		// check permissions
		$capability = 'page';
		$post_types = get_post_types( array( 'name' => $post_type), 'objects' );
		if (!empty($post_types) && is_array($post_types)) {
			foreach ($post_types  as $type) {
				$capability = $type->capability_type;
				break;
			}
		}
		if (!current_user_can('edit_'.($capability), $post_id)) {
			return $post_id;
		}

		// Save meta
		$meta = array();
		$options = muji_storage_get('options');
		foreach ($options as $k=>$v) {
			// Skip not overriden options
			if (!isset($v['override']) || strpos($v['override']['mode'], $post_type)===false) continue;
			// Skip inherited options
			if (!empty($_POST['muji_options_inherit_' . $k])) continue;
			// Get option value from POST
			$meta[$k] = sanitize_text_field(isset($_POST['muji_options_field_' . $k]))
							? muji_get_value_gp('muji_options_field_' . $k)
							: ($v['type']=='checkbox' ? 0 : '');
		}
		update_post_meta($post_id, 'muji_options', $meta);
		
		// Save separate meta options to search template pages
		if ($post_type=='page' && !empty($_POST['page_template']) && $_POST['page_template']=='blog.php') {
			update_post_meta($post_id, 'muji_options_post_type', isset($meta['post_type']) ? $meta['post_type'] : 'post');
			update_post_meta($post_id, 'muji_options_parent_cat', isset($meta['parent_cat']) ? $meta['parent_cat'] : 0);
		}
	}
}
?>