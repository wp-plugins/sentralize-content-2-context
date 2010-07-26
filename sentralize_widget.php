<?php
/**
 * Sentralize_Widget Class
 */
class Sentralize_Widget extends WP_Widget {

    /** constructor */
    function Sentralize_Widget() {
        

	$widget_ops = array('classname' => 'Sentralize_Widget', 'description' => __( "Publish sentralize filtered streams") );

	parent::WP_Widget(false, $name = 'Sentralize', $widget_ops);

    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {	
        extract( $args );

	$post_count = $instance['post_count'];
	$content_limit = $instance['content_limit'];
	$show_title = $instance['show_title'];
	$show_content = $instance['show_content'];
	$show_source = $instance['show_source'];
	$count = 0;

	$stream = sentralize_get_stream($instance['stream_key']);

	if(!$stream)
		echo '<h2>Please set API key in Widget Menu</h2>';


        $title = apply_filters('widget_title', $stream->name);
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title && $show_title )
                        echo $before_title . $title . $after_title; ?>
                  <?php
			foreach($stream->data as $item)
			{
				if($count >= $post_count)
					break;
	
				echo '<h3 class="post_title"><a href="'.$item->identifier.'" target="_blank">'.$item->title.'</a></h3>';

				if($show_source)
					echo '<p class="post_source"><small>From: '.$item->source->name.' | '.human_time_diff(strtotime($item->published_at)).' ago.</small></p>';
				
				
				if($show_content)				
					echo '<p class="post_content">'.sentralize_truncate($item->content, $content_limit, '...<a href="'.$item->identifier.'" target="_blank" >Read More</a>').'</p>';

				$count++;
			}
		  ?>
		<?php if($stream) echo '<br \><p class="sentralize_tag"><small>Powered by <a href="http://www.sentralize.com">Sentralize.com</a></small></p>';?>
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
	$instance = $old_instance;
	$instance['stream_key'] = $new_instance['stream_key'];
	$instance['post_count'] = $new_instance['post_count'];
	$instance['content_limit'] = $new_instance['content_limit'];
	$instance['show_title'] = $new_instance['show_title'];
	$instance['show_content'] = $new_instance['show_content'];
	$instance['show_source'] = $new_instance['show_source'];
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {

	$stream_key = esc_attr($instance['stream_key']);
	$count = esc_attr($instance['post_count']);
	$content_limit = esc_attr($instance['content_limit']);
	$show_title = esc_attr($instance['show_title']);
	$show_content = esc_attr($instance['show_content']);
	$show_source = esc_attr($instance['show_source']);


	if ( !isset($instance['post_count']) || !$count = (int) $instance['post_count'] )
		$count = 5;
	
	if ( !isset($instance['content_limit']) || !$content_limit = (int) $instance['content_limit'] )
		$content_limit = 150;
				
        ?>
            <p>
		<label for="<?php echo $this->get_field_id('stream_key'); ?>"><?php _e('Stream Api Key:'); ?> 
			<input class="widefat" id="<?php echo $this->get_field_id('stream_key'); ?>" name="<?php echo $this->get_field_name('stream_key'); ?>" type="text" value="<?php echo $stream_key; ?>" />
		</label><br /><br />

		<label for="<?php echo $this->get_field_id('post_count'); ?>"><?php _e('Post Count:'); ?>
			<input id="<?php echo $this->get_field_id('post_count'); ?>" name="<?php echo $this->get_field_name('post_count'); ?>" type="text" value="<?php echo $count; ?>" />
		</label><br /><br />

		<input class="checkbox" id="<?php echo $this->get_field_id('show_title'); ?>" name="<?php echo $this->get_field_name('show_title'); ?>" type="checkbox" value="1" <?php if($show_title) echo 'checked="checked"'; ?>" />
		<label for="<?php echo $this->get_field_id('show_title'); ?>"><?php _e('Show Stream Title? '); ?></label><br /><br />

		<input class="checkbox" id="<?php echo $this->get_field_id('show_content'); ?>" name="<?php echo $this->get_field_name('show_content'); ?>" type="checkbox" value="1" <?php if($show_content) echo 'checked="checked"'; ?>" />
		<label for="<?php echo $this->get_field_id('show_content'); ?>"><?php _e('Show Content? '); ?></label><br /><br />

		<input class="checkbox" id="<?php echo $this->get_field_id('show_source'); ?>" name="<?php echo $this->get_field_name('show_source'); ?>" type="checkbox" value="1" <?php if($show_source) echo 'checked="checked"'; ?>" />
		<label for="<?php echo $this->get_field_id('show_source'); ?>"><?php _e('Show Source and Timestamp?'); ?></label><br /><br />


		<label for="<?php echo $this->get_field_id('content_limit'); ?>">Content Limit (characters): 
			<input class="widefat" id="<?php echo $this->get_field_id('content_limit'); ?>" name="<?php echo $this->get_field_name('content_limit'); ?>" type="text" value="<?php echo $content_limit; ?>" />
		</label><br /><br />
	   </p>
        <?php 
    }
}

//Add widget to admin panel
add_action('widgets_init', create_function('', 'return register_widget("Sentralize_Widget");'));
?>
