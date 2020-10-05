<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

						// Widgets area inside page content
						muji_create_widgets_area('widgets_below_content');
						?>				
					</div><!-- </.content> -->

					<?php
					// Show main sidebar
					get_sidebar();

					// Widgets area below page content
					muji_create_widgets_area('widgets_below_page');

					$muji_body_style = muji_get_theme_option('body_style');
					if ($muji_body_style != 'fullscreen') {
						?></div><!-- </.content_wrap> --><?php
					}
					?>
			</div><!-- </.page_content_wrap> -->

			<?php
			// Footer
			$muji_footer_type = muji_get_theme_option("footer_type");
			if ($muji_footer_type == 'custom' && !muji_is_layouts_available())
				$muji_footer_type = 'default';
			get_template_part( "templates/footer-{$muji_footer_type}");
			?>

		</div><!-- /.page_wrap -->

	</div><!-- /.body_wrap -->

	<?php if (muji_is_on(muji_get_theme_option('debug_mode')) && muji_get_file_dir('images/makeup.jpg')!='') { ?>
		<img src="<?php echo esc_url(muji_get_file_url('images/makeup.jpg')); ?>" id="makeup">
	<?php } ?>

	<?php wp_footer(); ?>

</body>
</html>