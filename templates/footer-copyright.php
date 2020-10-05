<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0.10
 */

// Copyright area
?> 
<div class="footer_copyright_wrap<?php
				if (!muji_is_inherit(muji_get_theme_option('copyright_scheme')))
					echo ' scheme_' . esc_attr(muji_get_theme_option('copyright_scheme'));
 				?>">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text"><?php
				$muji_copyright = muji_get_theme_option('copyright');
				if (!empty($muji_copyright)) {
					// Replace {{Y}} or {Y} with the current year
					$muji_copyright = str_replace(array('{{Y}}', '{Y}'), date('Y'), $muji_copyright);
					// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
					$muji_copyright = muji_prepare_macros($muji_copyright);
					// Display copyright
					echo wp_kses_data(nl2br($muji_copyright));
				}
			?></div>
		</div>
	</div>
</div>
