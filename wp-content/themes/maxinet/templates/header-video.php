<?php
/**
 * The template to display the background video in the header
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0.14
 */
$maxinet_header_video = maxinet_get_header_video();
$maxinet_embed_video = '';
if (!empty($maxinet_header_video) && !maxinet_is_from_uploads($maxinet_header_video)) {
	if (maxinet_is_youtube_url($maxinet_header_video) && preg_match('/[=\/]([^=\/]*)$/', $maxinet_header_video, $matches) && !empty($matches[1])) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr($matches[1]); ?>"></div><?php
	} else {
		global $wp_embed;
		if (false && is_object($wp_embed)) {
			$maxinet_embed_video = do_shortcode($wp_embed->run_shortcode( '[embed]' . trim($maxinet_header_video) . '[/embed]' ));
			$maxinet_embed_video = maxinet_make_video_autoplay($maxinet_embed_video);
		} else {
			$maxinet_header_video = str_replace('/watch?v=', '/embed/', $maxinet_header_video);
			$maxinet_header_video = maxinet_add_to_url($maxinet_header_video, array(
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
			$maxinet_embed_video = '<iframe src="' . esc_url($maxinet_header_video) . '" width="1170" height="658" allowfullscreen="0" frameborder="0"></iframe>';
		}
		?><div id="background_video"><?php maxinet_show_layout($maxinet_embed_video); ?></div><?php
	}
}
?>