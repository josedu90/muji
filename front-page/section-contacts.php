<div class="front_page_section front_page_section_contacts<?php
			$muji_scheme = muji_get_theme_option('front_page_contacts_scheme');
			if (!muji_is_inherit($muji_scheme)) echo ' scheme_'.esc_attr($muji_scheme);
			echo ' front_page_section_paddings_'.esc_attr(muji_get_theme_option('front_page_contacts_paddings'));
		?>"<?php
		$muji_css = '';
		$muji_bg_image = muji_get_theme_option('front_page_contacts_bg_image');
		if (!empty($muji_bg_image)) 
			$muji_css .= 'background-image: url('.esc_url(muji_get_attachment_url($muji_bg_image)).');';
		if (!empty($muji_css))
			echo ' style="' . esc_attr($muji_css) . '"';
?>><?php
	// Add anchor
	$muji_anchor_icon = muji_get_theme_option('front_page_contacts_anchor_icon');	
	$muji_anchor_text = muji_get_theme_option('front_page_contacts_anchor_text');	
	if ((!empty($muji_anchor_icon) || !empty($muji_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_contacts"'
										. (!empty($muji_anchor_icon) ? ' icon="'.esc_attr($muji_anchor_icon).'"' : '')
										. (!empty($muji_anchor_text) ? ' title="'.esc_attr($muji_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_contacts_inner<?php
			if (muji_get_theme_option('front_page_contacts_fullheight'))
				echo ' muji-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$muji_css = '';
			$muji_bg_mask = muji_get_theme_option('front_page_contacts_bg_mask');
			$muji_bg_color = muji_get_theme_option('front_page_contacts_bg_color');
			if (!empty($muji_bg_color) && $muji_bg_mask > 0)
				$muji_css .= 'background-color: '.esc_attr($muji_bg_mask==1
																	? $muji_bg_color
																	: muji_hex2rgba($muji_bg_color, $muji_bg_mask)
																).';';
			if (!empty($muji_css))
				echo ' style="' . esc_attr($muji_css) . '"';
	?>>
		<div class="front_page_section_content_wrap front_page_section_contacts_content_wrap content_wrap">
			<?php

			// Title and description
			$muji_caption = muji_get_theme_option('front_page_contacts_caption');
			$muji_description = muji_get_theme_option('front_page_contacts_description');
			if (!empty($muji_caption) || !empty($muji_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				// Caption
				if (!empty($muji_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><h2 class="front_page_section_caption front_page_section_contacts_caption front_page_block_<?php echo !empty($muji_caption) ? 'filled' : 'empty'; ?>"><?php
						echo wp_kses($muji_caption, 'muji_kses_content' );
					?></h2><?php
				}
			
				// Description
				if (!empty($muji_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><div class="front_page_section_description front_page_section_contacts_description front_page_block_<?php echo !empty($muji_description) ? 'filled' : 'empty'; ?>"><?php
						echo wp_kses(wpautop($muji_description), 'muji_kses_content' );
					?></div><?php
				}
			}

			// Content (text)
			$muji_content = muji_get_theme_option('front_page_contacts_content');
			$muji_layout = muji_get_theme_option('front_page_contacts_layout');
			if ($muji_layout == 'columns' && (!empty($muji_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?><div class="front_page_section_columns front_page_section_contacts_columns columns_wrap">
					<div class="column-1_3">
				<?php
			}

			if ((!empty($muji_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?><div class="front_page_section_content front_page_section_contacts_content front_page_block_<?php echo !empty($muji_content) ? 'filled' : 'empty'; ?>"><?php
					echo wp_kses($muji_content, 'muji_kses_content' );
				?></div><?php
			}

			if ($muji_layout == 'columns' && (!empty($muji_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?></div><div class="column-2_3"><?php
			}
		
			// Shortcode output
			$muji_sc = muji_get_theme_option('front_page_contacts_shortcode');
			if (!empty($muji_sc) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><div class="front_page_section_output front_page_section_contacts_output front_page_block_<?php echo !empty($muji_sc) ? 'filled' : 'empty'; ?>"><?php
					muji_show_layout(do_shortcode($muji_sc));
				?></div><?php
			}

			if ($muji_layout == 'columns' && (!empty($muji_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?></div></div><?php
			}
			?>			
		</div>
	</div>
</div>