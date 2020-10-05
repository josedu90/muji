<?php
/**
 * The Header: Logo and main menu
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js scheme_<?php
										 // Class scheme_xxx need in the <html> as context for the <body>!
										 echo esc_attr(muji_get_theme_option('color_scheme'));
										 ?>">
<head>
	<?php wp_head(); ?>
</head>

<body <?php	body_class(); ?>>

	<?php wp_body_open(); ?>

	<?php do_action( 'muji_action_before_body' ); ?>

	<div class="body_wrap">

		<div class="page_wrap"><?php
			
			// Desktop header
			$muji_header_type = muji_get_theme_option("header_type");
			if ($muji_header_type == 'custom' && !muji_is_layouts_available())
				$muji_header_type = 'default';
			get_template_part( "templates/header-{$muji_header_type}");

			// Side menu
			if (in_array(muji_get_theme_option('menu_style'), array('left', 'right'))) {
				get_template_part( 'templates/header-navi-side' );
			}
			
			// Mobile menu
			get_template_part( 'templates/header-navi-mobile');
			?>

			<div class="page_content_wrap">

				<?php if (muji_get_theme_option('body_style') != 'fullscreen') { ?>
				<div class="content_wrap">
				<?php } ?>

					<?php
					// Widgets area above page content
					muji_create_widgets_area('widgets_above_page');
					?>				

					<div class="content">
						<?php
						// Widgets area inside page content
						muji_create_widgets_area('widgets_above_content');
						?>				
