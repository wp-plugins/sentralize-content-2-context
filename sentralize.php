<?php
/*
Plugin Name: Sentralize
Plugin URI: http://sentralize.com
Description: Wordpress integration with Sentralize C-2-C Service
Version:  0.5
Author: Sentralize
Author URI: http://www.sentralize.com
*/

add_option('sentralize_cache_ttl', 10*60);

require_once('sentralize_stream.php');

require_once('sentralize_shortcode.php');

require_once('sentralize_admin.php');


// Check if we have a version of WordPress greater than 2.8
if ( function_exists('register_widget') ) {
		require_once('sentralize_widget.php');
}

?>
