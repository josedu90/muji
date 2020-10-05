<?php
/**
 * The template to display the socials in the footer
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0.10
 */


// Socials
if ( muji_is_on(muji_get_theme_option('socials_in_footer')) && ($muji_output = muji_get_socials_links()) != '') {
	?>
	<div class="footer_socials_wrap socials_wrap">
		<div class="footer_socials_inner">
			<?php muji_show_layout($muji_output); ?>
		</div>
	</div>
	<?php
}
?>