<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

$muji_args = get_query_var('muji_logo_args');

// Site logo
$muji_logo_type   = isset($muji_args['type']) ? $muji_args['type'] : '';
$muji_logo_image  = muji_get_logo_image($muji_logo_type);
$muji_logo_text   = muji_is_on(muji_get_theme_option('logo_text')) ? get_bloginfo( 'name' ) : '';
$muji_logo_slogan = get_bloginfo( 'description', 'display' );
if (!empty($muji_logo_image) || !empty($muji_logo_text)) {
	?><a class="sc_layouts_logo" href="<?php echo  esc_url(home_url('/')); ?>"><?php
		if (!empty($muji_logo_image)) {
			if (empty($muji_logo_type) && function_exists('the_custom_logo') && (int) $muji_logo_image > 0) {
				the_custom_logo();
			} else {
				$muji_attr = muji_getimagesize($muji_logo_image);
				echo '<img src="'.esc_url($muji_logo_image).'" alt="'.esc_attr($muji_logo_text).'"'.(!empty($muji_attr[3]) ? ' '.wp_kses_data($muji_attr[3]) : '').'>';
			}
		} else {
			muji_show_layout(muji_prepare_macros($muji_logo_text), '<span class="logo_text">', '</span>');
			muji_show_layout(muji_prepare_macros($muji_logo_slogan), '<span class="logo_slogan">', '</span>');
		}
	?></a><?php
}
?>