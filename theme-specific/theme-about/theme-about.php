<?php
/**
 * Information about this theme
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0.30
 */


// Redirect to the 'About Theme' page after switch theme
if (!function_exists('muji_about_after_switch_theme')) {
	add_action('after_switch_theme', 'muji_about_after_switch_theme', 1000);
	function muji_about_after_switch_theme() {
		update_option('muji_about_page', 1);
	}
}
if ( !function_exists('muji_about_after_setup_theme') ) {
	add_action( 'init', 'muji_about_after_setup_theme', 1000 );
	function muji_about_after_setup_theme() {
		if (get_option('muji_about_page') == 1) {
			update_option('muji_about_page', 0);
			wp_safe_redirect(admin_url().'themes.php?page=muji_about');
			exit();
		}
	}
}


// Add 'About Theme' item in the Appearance menu
if (!function_exists('muji_about_add_menu_items')) {
	add_action( 'admin_menu', 'muji_about_add_menu_items' );
	function muji_about_add_menu_items() {
		$theme = wp_get_theme();
		$theme_name = $theme->name . (MUJI_THEME_FREE ? ' ' . esc_html__('Free', 'muji') : '');
		add_theme_page(
			// Translators: Add theme name to the page title
			sprintf(esc_html__('About %s', 'muji'), $theme_name),
			// Translators: Add theme name to the menu title
			sprintf(esc_html__('About %s', 'muji'), $theme_name),	
			'manage_options',											
			'muji_about',											//menu_slug
			'muji_about_page_builder'								//callback
		);
	}
}


// Load page-specific scripts and styles
if (!function_exists('muji_about_enqueue_scripts')) {
	add_action( 'admin_enqueue_scripts', 'muji_about_enqueue_scripts' );
	function muji_about_enqueue_scripts() {
		$screen = function_exists('get_current_screen') ? get_current_screen() : false;
		if (is_object($screen) && $screen->id == 'appearance_page_muji_about') {
			// Scripts
			wp_enqueue_script( 'jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true );
			
			if (function_exists('muji_plugins_installer_enqueue_scripts'))
				muji_plugins_installer_enqueue_scripts();
			
			// Styles
			wp_enqueue_style( 'fontello-icons',  muji_get_file_url('css/font-icons/css/fontello-embedded.css'), array(), null );
			if ( ($fdir = muji_get_file_url('theme-specific/theme-about/theme-about.css')) != '' )
				wp_enqueue_style( 'muji-about',  $fdir, array(), null );
		}
	}
}


// Build 'About Theme' page
if (!function_exists('muji_about_page_builder')) {
	function muji_about_page_builder() {
		$theme = wp_get_theme();
		?>
		<div class="muji_about">

			<?php do_action('muji_action_theme_about_before_header', $theme); ?>

			<div class="muji_about_header">

				<?php do_action('muji_action_theme_about_before_logo'); ?>

				<div class="muji_about_logo"><?php
					$logo = muji_get_file_url('theme-specific/theme-about/logo.jpg');
					if (empty($logo)) $logo = muji_get_file_url('screenshot.jpg');
					if (!empty($logo)) {
						?><img src="<?php echo esc_url($logo); ?>"><?php
					}
				?></div>

				<?php do_action('muji_action_theme_about_before_title', $theme); ?>
				
				<h1 class="muji_about_title"><?php
					// Translators: Add theme name and version to the 'Welcome' message
					echo esc_html(sprintf(__('Welcome to %1$s %2$s v.%3$s', 'muji'),
											$theme->name,
											MUJI_THEME_FREE ? esc_html__('Free', 'muji') : '',
											$theme->version
										)
								);
				?></h1>

				<?php do_action('muji_action_theme_about_before_description', $theme); ?>

				<div class="muji_about_description">
					<?php
					if (MUJI_THEME_FREE) {
						?><p><?php
							// Translators: Add the download url and the theme name to the message
							echo wp_kses_data(sprintf(__('Now you are using Free version of <a href="%1$s">%2$s Pro Theme</a>.', 'muji'),
														esc_url(muji_storage_get('theme_download_url')),
														$theme->name
														)
												);
							// Translators: Add the theme name and supported plugins list to the message
							echo '<br>' . wp_kses_data(sprintf(__('This version is SEO- and Retina-ready. It also has a built-in support for parallax and slider with swipe gestures. %1$s Free is compatible with many popular plugins, such as %2$s', 'muji'),
														$theme->name,
														muji_about_get_supported_plugins()
														)
												);
						?></p>
						<p><?php
							// Translators: Add the download url to the message
							echo wp_kses_data(sprintf(__('We hope you have a great acquaintance with our themes. If you are looking for a fully functional website, you can get the <a href="%s">Pro Version here</a>', 'muji'),
														esc_url(muji_storage_get('theme_download_url'))
														)
												);
						?></p><?php
					} else {
						?><p><?php
							// Translators: Add the theme name to the message
							echo wp_kses_data(sprintf(__('%s is a Premium WordPress theme. It has a built-in support for parallax, slider with swipe gestures, and is SEO- and Retina-ready', 'muji'),
														$theme->name
														)
												);
						?></p>
						<p><?php
							// Translators: Add supported plugins list to the message
							echo wp_kses_data(sprintf(__('The Premium Theme is compatible with many popular plugins, such as %s', 'muji'),
														muji_about_get_supported_plugins()
														)
												);
						?></p><?php
					}
					?>
				</div>

				<?php do_action('muji_action_theme_about_after_description', $theme); ?>

			</div>

			<?php do_action('muji_action_theme_about_before_tabs', $theme); ?>

			<div id="muji_about_tabs" class="muji_tabs muji_about_tabs">
				<ul>
					<?php do_action('muji_action_theme_about_before_tabs_list', $theme); ?>
					<li><a href="#muji_about_section_start"><?php esc_html_e('Getting started', 'muji'); ?></a></li>
					<li><a href="#muji_about_section_actions"><?php esc_html_e('Recommended actions', 'muji'); ?></a></li>
					<?php do_action('muji_action_theme_about_after_tabs_list', $theme); ?>
				</ul>

				<?php do_action('muji_action_theme_about_before_tabs_sections', $theme); ?>

				<div id="muji_about_section_start" class="muji_tabs_section muji_about_section"><?php
				
					// Install required plugins
					if (!MUJI_THEME_FREE_WP && !muji_exists_trx_addons()) {
						?><div class="muji_about_block"><div class="muji_about_block_inner">
							<h2 class="muji_about_block_title">
								<i class="dashicons dashicons-admin-plugins"></i>
								<?php esc_html_e('ThemeREX Addons', 'muji'); ?>
							</h2>
							<div class="muji_about_block_description"><?php
								esc_html_e('It is highly recommended that you install the companion plugin "ThemeREX Addons" to have access to the layouts builder, awesome shortcodes, team and testimonials, services and slider, and many other features ...', 'muji');
							?></div>
							<?php muji_plugins_installer_get_button_html('trx_addons'); ?>
						</div></div><?php
					}
					
					// Install recommended plugins
					?><div class="muji_about_block"><div class="muji_about_block_inner">
						<h2 class="muji_about_block_title">
							<i class="dashicons dashicons-admin-plugins"></i>
							<?php esc_html_e('Recommended plugins', 'muji'); ?>
						</h2>
						<div class="muji_about_block_description"><?php
							// Translators: Add the theme name to the message
							echo esc_html(sprintf(__('Theme %s is compatible with a large number of popular plugins. You can install only those that are going to use in the near future.', 'muji'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(admin_url().'themes.php?page=tgmpa-install-plugins'); ?>"
						   class="muji_about_block_link button button-primary"><?php
							esc_html_e('Install plugins', 'muji');
						?></a>
					</div></div><?php
					
					// Customizer or Theme Options
					?><div class="muji_about_block"><div class="muji_about_block_inner">
						<h2 class="muji_about_block_title">
							<i class="dashicons dashicons-admin-appearance"></i>
							<?php esc_html_e('Setup Theme options', 'muji'); ?>
						</h2>
						<div class="muji_about_block_description"><?php
							esc_html_e('Using the WordPress Customizer you can easily customize every aspect of the theme. If you want to use the standard theme settings page - open Theme Options and follow the same steps there.', 'muji');
						?></div>
						<a href="<?php echo esc_url(admin_url().'customize.php'); ?>"
						   class="muji_about_block_link button button-primary"><?php
							esc_html_e('Customizer', 'muji');
						?></a>
						<?php if (!MUJI_THEME_FREE) { ?>
							<?php esc_html_e('or', 'muji'); ?>
							<a href="<?php echo esc_url(admin_url().'themes.php?page=theme_options'); ?>"
							   class="muji_about_block_link button"><?php
								esc_html_e('Theme Options', 'muji');
							?></a>
						<?php } ?>
					</div></div><?php
					
					// Documentation
					?><div class="muji_about_block"><div class="muji_about_block_inner">
						<h2 class="muji_about_block_title">
							<i class="dashicons dashicons-book"></i>
							<?php esc_html_e('Read full documentation', 'muji');	?>
						</h2>
						<div class="muji_about_block_description"><?php
							// Translators: Add the theme name to the message
							echo esc_html(sprintf(__('Need more details? Please check our full online documentation for detailed information on how to use %s.', 'muji'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(muji_storage_get('theme_doc_url')); ?>"
						   target="_blank"
						   class="muji_about_block_link button button-primary"><?php
							esc_html_e('Documentation', 'muji');
						?></a>
					</div></div><?php
					
					// Video tutorials
					?><div class="muji_about_block"><div class="muji_about_block_inner">
						<h2 class="muji_about_block_title">
							<i class="dashicons dashicons-video-alt2"></i>
							<?php esc_html_e('Video tutorials', 'muji');	?>
						</h2>
						<div class="muji_about_block_description"><?php
							// Translators: Add the theme name to the message
							echo esc_html(sprintf(__('No time for reading documentation? Check out our video tutorials and learn how to customize %s in detail.', 'muji'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(muji_storage_get('theme_video_url')); ?>"
						   target="_blank"
						   class="muji_about_block_link button button-primary"><?php
							esc_html_e('Watch videos', 'muji');
						?></a>
					</div></div><?php
					
					// Support
					if (!MUJI_THEME_FREE) {
						?><div class="muji_about_block"><div class="muji_about_block_inner">
							<h2 class="muji_about_block_title">
								<i class="dashicons dashicons-sos"></i>
								<?php esc_html_e('Support', 'muji'); ?>
							</h2>
							<div class="muji_about_block_description"><?php
								// Translators: Add the theme name to the message
								echo esc_html(sprintf(__('We want to make sure you have the best experience using %s and that is why we gathered here all the necessary informations for you.', 'muji'), $theme->name));
							?></div>
							<a href="<?php echo esc_url(muji_storage_get('theme_support_url')); ?>"
							   target="_blank"
							   class="muji_about_block_link button button-primary"><?php
								esc_html_e('Support', 'muji');
							?></a>
						</div></div><?php
					}
					
					// Online Demo
					?><div class="muji_about_block"><div class="muji_about_block_inner">
						<h2 class="muji_about_block_title">
							<i class="dashicons dashicons-images-alt2"></i>
							<?php esc_html_e('On-line demo', 'muji'); ?>
						</h2>
						<div class="muji_about_block_description"><?php
							// Translators: Add the theme name to the message
							echo esc_html(sprintf(__('Visit the Demo Version of %s to check out all the features it has', 'muji'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(muji_storage_get('theme_demo_url')); ?>"
						   target="_blank"
						   class="muji_about_block_link button button-primary"><?php
							esc_html_e('View demo', 'muji');
						?></a>
					</div></div>
					
				</div>



				<div id="muji_about_section_actions" class="muji_tabs_section muji_about_section"><?php
				
					// Install required plugins
					if (!MUJI_THEME_FREE_WP && !muji_exists_trx_addons()) {
						?><div class="muji_about_block"><div class="muji_about_block_inner">
							<h2 class="muji_about_block_title">
								<i class="dashicons dashicons-admin-plugins"></i>
								<?php esc_html_e('ThemeREX Addons', 'muji'); ?>
							</h2>
							<div class="muji_about_block_description"><?php
								esc_html_e('It is highly recommended that you install the companion plugin "ThemeREX Addons" to have access to the layouts builder, awesome shortcodes, team and testimonials, services and slider, and many other features ...', 'muji');
							?></div>
							<?php muji_plugins_installer_get_button_html('trx_addons'); ?>
						</div></div><?php
					}
					
					// Install recommended plugins
					?><div class="muji_about_block"><div class="muji_about_block_inner">
						<h2 class="muji_about_block_title">
							<i class="dashicons dashicons-admin-plugins"></i>
							<?php esc_html_e('Recommended plugins', 'muji'); ?>
						</h2>
						<div class="muji_about_block_description"><?php
							// Translators: Add the theme name to the message
							echo esc_html(sprintf(__('Theme %s is compatible with a large number of popular plugins. You can install only those that are going to use in the near future.', 'muji'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(admin_url().'themes.php?page=tgmpa-install-plugins'); ?>"
						   class="muji_about_block_link button button button-primary"><?php
							esc_html_e('Install plugins', 'muji');
						?></a>
					</div></div><?php
					
					// Customizer or Theme Options
					?><div class="muji_about_block"><div class="muji_about_block_inner">
						<h2 class="muji_about_block_title">
							<i class="dashicons dashicons-admin-appearance"></i>
							<?php esc_html_e('Setup Theme options', 'muji'); ?>
						</h2>
						<div class="muji_about_block_description"><?php
							esc_html_e('Using the WordPress Customizer you can easily customize every aspect of the theme. If you want to use the standard theme settings page - open Theme Options and follow the same steps there.', 'muji');
						?></div>
						<a href="<?php echo esc_url(admin_url().'customize.php'); ?>"
						   target="_blank"
						   class="muji_about_block_link button button-primary"><?php
							esc_html_e('Customizer', 'muji');
						?></a>
						<?php esc_html_e('or', 'muji'); ?>
						<a href="<?php echo esc_url(admin_url().'themes.php?page=theme_options'); ?>"
						   class="muji_about_block_link button"><?php
							esc_html_e('Theme Options', 'muji');
						?></a>
					</div></div>
					
				</div>

				<?php do_action('muji_action_theme_about_after_tabs_sections', $theme); ?>
				
			</div>

			<?php do_action('muji_action_theme_about_after_tabs', $theme); ?>

		</div>
		<?php
	}
}


// Utils
//------------------------------------

// Return supported plugin's names
if (!function_exists('muji_about_get_supported_plugins')) {
	function muji_about_get_supported_plugins() {
		return '"' . join('", "', array_values(muji_storage_get('required_plugins'))) . '"';
	}
}

require_once MUJI_THEME_DIR . 'includes/plugins-installer/plugins-installer.php';
?>