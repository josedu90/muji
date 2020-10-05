<div class="front_page_section front_page_section_woocommerce<?php
			$muji_scheme = muji_get_theme_option('front_page_woocommerce_scheme');
			if (!muji_is_inherit($muji_scheme)) echo ' scheme_'.esc_attr($muji_scheme);
			echo ' front_page_section_paddings_'.esc_attr(muji_get_theme_option('front_page_woocommerce_paddings'));
		?>"<?php
		$muji_css = '';
		$muji_bg_image = muji_get_theme_option('front_page_woocommerce_bg_image');
		if (!empty($muji_bg_image)) 
			$muji_css .= 'background-image: url('.esc_url(muji_get_attachment_url($muji_bg_image)).');';
		if (!empty($muji_css))
			echo ' style="' . esc_attr($muji_css) . '"';
?>><?php
	// Add anchor
	$muji_anchor_icon = muji_get_theme_option('front_page_woocommerce_anchor_icon');	
	$muji_anchor_text = muji_get_theme_option('front_page_woocommerce_anchor_text');	
	if ((!empty($muji_anchor_icon) || !empty($muji_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_woocommerce"'
										. (!empty($muji_anchor_icon) ? ' icon="'.esc_attr($muji_anchor_icon).'"' : '')
										. (!empty($muji_anchor_text) ? ' title="'.esc_attr($muji_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_woocommerce_inner<?php
			if (muji_get_theme_option('front_page_woocommerce_fullheight'))
				echo ' muji-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$muji_css = '';
			$muji_bg_mask = muji_get_theme_option('front_page_woocommerce_bg_mask');
			$muji_bg_color = muji_get_theme_option('front_page_woocommerce_bg_color');
			if (!empty($muji_bg_color) && $muji_bg_mask > 0)
				$muji_css .= 'background-color: '.esc_attr($muji_bg_mask==1
																	? $muji_bg_color
																	: muji_hex2rgba($muji_bg_color, $muji_bg_mask)
																).';';
			if (!empty($muji_css))
				echo ' style="' . esc_attr($muji_css) . '"';
	?>>
		<div class="front_page_section_content_wrap front_page_section_woocommerce_content_wrap content_wrap woocommerce">
			<?php
			// Content wrap with title and description
			$muji_caption = muji_get_theme_option('front_page_woocommerce_caption');
			$muji_description = muji_get_theme_option('front_page_woocommerce_description');
			if (!empty($muji_caption) || !empty($muji_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				// Caption
				if (!empty($muji_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><h2 class="front_page_section_caption front_page_section_woocommerce_caption front_page_block_<?php echo !empty($muji_caption) ? 'filled' : 'empty'; ?>"><?php
						echo wp_kses($muji_caption, 'muji_kses_content' );
					?></h2><?php
				}
			
				// Description (text)
				if (!empty($muji_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><div class="front_page_section_description front_page_section_woocommerce_description front_page_block_<?php echo !empty($muji_description) ? 'filled' : 'empty'; ?>"><?php
						echo wp_kses(wpautop($muji_description), 'muji_kses_content' );
					?></div><?php
				}
			}
		
			// Content (widgets)
			?><div class="front_page_section_output front_page_section_woocommerce_output list_products shop_mode_thumbs"><?php 
				$muji_woocommerce_sc = muji_get_theme_option('front_page_woocommerce_products');
				if ($muji_woocommerce_sc == 'products') {
					$muji_woocommerce_sc_ids = muji_get_theme_option('front_page_woocommerce_products_per_page');
					$muji_woocommerce_sc_per_page = count(explode(',', $muji_woocommerce_sc_ids));
				} else {
					$muji_woocommerce_sc_per_page = max(1, (int) muji_get_theme_option('front_page_woocommerce_products_per_page'));
				}
				$muji_woocommerce_sc_columns = max(1, min($muji_woocommerce_sc_per_page, (int) muji_get_theme_option('front_page_woocommerce_products_columns')));
				echo do_shortcode("[{$muji_woocommerce_sc}"
									. ($muji_woocommerce_sc == 'products' 
											? ' ids="'.esc_attr($muji_woocommerce_sc_ids).'"' 
											: '')
									. ($muji_woocommerce_sc == 'product_category' 
											? ' category="'.esc_attr(muji_get_theme_option('front_page_woocommerce_products_categories')).'"' 
											: '')
									. ($muji_woocommerce_sc != 'best_selling_products' 
											? ' orderby="'.esc_attr(muji_get_theme_option('front_page_woocommerce_products_orderby')).'"'
											  . ' order="'.esc_attr(muji_get_theme_option('front_page_woocommerce_products_order')).'"' 
											: '')
									. ' per_page="'.esc_attr($muji_woocommerce_sc_per_page).'"' 
									. ' columns="'.esc_attr($muji_woocommerce_sc_columns).'"' 
									. ']');
			?></div>
		</div>
	</div>
</div>