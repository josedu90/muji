<?php
/**
 * The template to display Admin notices
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0.1
 */
 
$muji_theme_obj = wp_get_theme();
?>
<div class="update-nag" id="muji_admin_notice">
	<h3 class="muji_notice_title"><?php
		// Translators: Add theme name and version to the 'Welcome' message
		echo esc_html(sprintf(__('Welcome to %1$s v.%2$s', 'muji'),
				$muji_theme_obj->name . (MUJI_THEME_FREE ? ' ' . esc_html__('Free', 'muji') : ''),
				$muji_theme_obj->version
				));
	?></h3>
	<?php
	if (!muji_exists_trx_addons()) {
		?><p><?php echo wp_kses_data(__('Attention! Plugin "ThemeREX Addons is required! Please, install and activate it!', 'muji')); ?></p><?php
	}
	?><p>
		<a href="<?php echo esc_url(admin_url().'themes.php?page=muji_about'); ?>" class="button button-primary"><i class="dashicons dashicons-nametag"></i> <?php
			// Translators: Add theme name
			echo esc_html(sprintf(__('About %s', 'muji'), $muji_theme_obj->name));
		?></a>
		<?php
		if (muji_get_value_gp('page')!='tgmpa-install-plugins') {
			?>
			<a href="<?php echo esc_url(admin_url().'themes.php?page=tgmpa-install-plugins'); ?>" class="button button-primary"><i class="dashicons dashicons-admin-plugins"></i> <?php esc_html_e('Install plugins', 'muji'); ?></a>
			<?php
		}
		if (function_exists('muji_exists_trx_addons') && muji_exists_trx_addons() && class_exists('trx_addons_demo_data_importer')) {
			?>
			<a href="<?php echo esc_url(admin_url().'themes.php?page=trx_importer'); ?>" class="button button-primary"><i class="dashicons dashicons-download"></i> <?php esc_html_e('One Click Demo Data', 'muji'); ?></a>
			<?php
		}
		?>
        <a href="<?php echo esc_url(admin_url().'customize.php'); ?>" class="button button-primary"><i class="dashicons dashicons-admin-appearance"></i> <?php esc_html_e('Theme Customizer', 'muji'); ?></a>
		<span> <?php esc_html_e('or', 'muji'); ?> </span>
        <a href="<?php echo esc_url(admin_url().'themes.php?page=theme_options'); ?>" class="button button-primary"><i class="dashicons dashicons-admin-appearance"></i> <?php esc_html_e('Theme Options', 'muji'); ?></a>
        <a href="#" class="button muji_hide_notice"><i class="dashicons dashicons-dismiss"></i> <?php esc_html_e('Hide Notice', 'muji'); ?></a>
	</p>
</div>