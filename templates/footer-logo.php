<?php
/**
 * The template to display the site logo in the footer
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0.10
 */

// Logo
if (muji_is_on(muji_get_theme_option('logo_in_footer'))) {
	$muji_logo_image = '';
	if (muji_is_on(muji_get_theme_option('logo_retina_enabled')) && muji_get_retina_multiplier() > 1)
		$muji_logo_image = muji_get_theme_option( 'logo_footer_retina' );
	if (empty($muji_logo_image)) 
		$muji_logo_image = muji_get_theme_option( 'logo_footer' );
	$muji_logo_text   = get_bloginfo( 'name' );
	if (!empty($muji_logo_image) || !empty($muji_logo_text)) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if (!empty($muji_logo_image)) {
					$muji_attr = muji_getimagesize($muji_logo_image);
					echo '<a href="'.esc_url(home_url('/')).'"><img src="'.esc_url($muji_logo_image).'" class="logo_footer_image" alt="' . esc_attr__( 'Site logo', 'muji' ) . '"'.(!empty($muji_attr[3]) ? ' ' . wp_kses_data($muji_attr[3]) : '').'></a>' ;
				} else if (!empty($muji_logo_text)) {
					echo '<h1 class="logo_footer_text"><a href="'.esc_url(home_url('/')).'">' . esc_html($muji_logo_text) . '</a></h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
?>