== Sentralize ==
Tags: sentralize, widget
Requires at least: 2.8
Tested up to: 3.0
Stable tag: 0.5


== Description ==
Sentralise Wordpress Plugin allows the easy integration of Sentralize Content-to-Context streams


== Features ==
-Widget
-Shortcode
-Template Tag
-Caching


== Installation ==
1. Copy the Sentralize folder into your 'wp-content/plugins' folder in your wordpress installation.

2. Enable the plugin in Admin Panel.

Done!


== Instructions ==

--Widget--
In the Admin Panel under Appearance Widgets

Drag the Sentralize Widget onto the sidebar of your choice, insert your stream API key.


--Shortcode--
In a page or a blog post, insert [stream api={your stream API key here}]

other options include:
post_count (number) 
show_content (true/false)
show_stream_title (true/false)
show_source (true/false)
content_length (number of characters)

eg. [stream api=XXXXXXXXXXXXXX post_count=5 content_length=500 ]

--Template Tag--
<?php echo sentralize_stream($api); ?>

Other options include:
post_count (number) 
show_content (true/false)
show_stream_title (true/false)
show_source (true/false)
content_length (number of characters)

eg. <?php echo sentralize_stream($api, $post_count, $show_source, $show_content, $show_stream_title, $content_length); ?>

