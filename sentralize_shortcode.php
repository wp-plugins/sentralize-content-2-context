<?php
/**
 * Sentralize Post Tag
 */
function sentralize_tag($atts) {
	extract(shortcode_atts(array(
		'api' => '',
		'post_count' => 5,
		'show_content' => true,
		'show_stream_title' => true,
		'show_source' => true,
		'content_length' => 500
	), $atts));

	return sentralize_stream($api, $post_count, $show_source, $show_content, $show_stream_title, $content_length);
}	

add_shortcode('stream', 'sentralize_tag');

?>
