<?php
/**
 * The template to display the background video in the header
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0.14
 */
$muji_header_video = muji_get_header_video();
$muji_embed_video = '';
if (!empty($muji_header_video) && !muji_is_from_uploads($muji_header_video)) {
	if (muji_is_youtube_url($muji_header_video) && preg_match('/[=\/]([^=\/]*)$/', $muji_header_video, $matches) && !empty($matches[1])) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr($matches[1]); ?>"></div><?php
	} else {
		global $wp_embed;
		if (false && is_object($wp_embed)) {
			$muji_embed_video = do_shortcode($wp_embed->run_shortcode( '[embed]' . trim($muji_header_video) . '[/embed]' ));
			$muji_embed_video = muji_make_video_autoplay($muji_embed_video);
		} else {
			$muji_header_video = str_replace('/watch?v=', '/embed/', $muji_header_video);
			$muji_header_video = muji_add_to_url($muji_header_video, array(
				'feature' => 'oembed',
				'controls' => 0,
				'autoplay' => 1,
				'showinfo' => 0,
				'modestbranding' => 1,
				'wmode' => 'transparent',
				'enablejsapi' => 1,
				'origin' => home_url(),
				'widgetid' => 1
			));
			$muji_embed_video = '<iframe src="' . esc_url($muji_header_video) . '" width="1170" height="658" allowfullscreen="0" frameborder="0"></iframe>';
		}
		?><div id="background_video"><?php muji_show_layout($muji_embed_video); ?></div><?php
	}
}
?>