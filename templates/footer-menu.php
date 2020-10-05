<?php
/**
 * The template to display menu in the footer
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0.10
 */

// Footer menu
$muji_menu_footer = muji_get_nav_menu(array(
											'location' => 'menu_footer',
											'class' => 'sc_layouts_menu sc_layouts_menu_default'
											));
if (!empty($muji_menu_footer)) {
	?>
	<div class="footer_menu_wrap">
		<div class="footer_menu_inner">
			<?php muji_show_layout($muji_menu_footer); ?>
		</div>
	</div>
	<?php
}
?>