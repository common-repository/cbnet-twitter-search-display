<?php /* widget_cbnet_plugin_twittersearch */
/**
 * Plugin Name: cbnet Twitter Search Display
 * Plugin URI: http://www.chipbennett.net/wordpress/plugins/cbnet-twitter-search-display/
 * Description: A widget that displays tweets from any Twitter search.
 * Version: 1.0
 * Author: chipbennett
 * Author URI: http://www.chipbennett.net/
 *
 * License:       GNU General Public License, v2 (or newer)
 * License URI:  http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * Thanks to Justin Tadlock, as well as Otto and the other fine folks at
 * the WPTavern forum (www.wptavern.org/forum) for help with this plugin
 */

define( CBNETTWITTERWIDGETPATH, WP_PLUGIN_DIR . '/cbnet-twitter-widget/cbnet-twitter-widget.php' );

/**
 * Add function to admin_notices to load.
 * @since 1.0
 */
add_action( 'admin_notices', 'cbnet_twitter_search_admin_notice' );

function cbnet_twitter_search_admin_notice() {
	if ( ! file_exists( CBNETTWITTERWIDGETPATH ) ) {
		echo "<div class='updated' style='background-color:#f66;'><p>" . sprintf( 'The cbnet Twitter Search Display Plugin is obsolete and no longer maintained. <strong>Please <a href="%s">install the cbnet Twitter Widget Plugin</a> in order to continue to receive updates and support.</strong>', cbnet_twitter_plugin_install_path() ) . "</p></div>";
	} else if ( ! is_plugin_active( 'cbnet-twitter-widget/cbnet-twitter-widget.php' ) ) {
		echo "<div class='updated'><p>You have installed, but not activated, the cbnet Twitter Widget Plugin. Please activate the <strong>cbnet Twitter Widget Plugin</strong>, and then add the cbnet Twitter Widget to your sidebar.</p></div>";
	}
}
if ( ! function_exists( 'cbnet_twitter_plugin_install_path' ) ) {
	function cbnet_twitter_plugin_install_path() {
		return 'http://coveredwebservices.com/wp-plugin-install/?plugin=cbnet-twitter-widget';
	}
}

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
if ( ! file_exists( CBNETTWITTERWIDGETPATH ) ) {	
	add_action( 'widgets_init', 'widget_cbnet_plugin_twitterprofile_load_widgets' );
}

/**
 * widget_cbnet_plugin_twittersearch class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */

class widget_cbnet_plugin_twittersearch extends WP_Widget {

    function widget_cbnet_plugin_twittersearch() {
        $widget_ops = array('classname' => 'widget-cbnet-plugin-twittersearch', 'description' => 'cbnet plugin widget to display Twitter favorites tweets' );
        $this->WP_Widget('cbnet_plugin_twittersearch', 'cbnet Twitter Search', $widget_ops);
    }

    function widget( $args, $instance ) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? 'Twitter Search' : $instance['title']);
		$twittersearch =  $instance['twittersearch'];
		$searchtitle = $instance['searchtitle'];
		$searchdesc = $instance['searchdesc'];
		$shellbg = $instance['shellbg'];
		$shellcolor = $instance['shellcolor'];
		$tweetbg = $instance['tweetbg'];
		$tweetcolor = $instance['tweetcolor'];
		$tweetlink = $instance['tweetlink'];
		$live = ($instance['live'] ? 'true' : 'false');
		$scrollbar = ($instance['scrollbar'] ? 'true' : 'false');
		$behavior = $instance['behavior'];
		$interval = $instance['interval'];
		$loop = ($instance['loop'] ? 'true' : 'false');
		$rpp = $instance['rpp'];
		$avatars = ($instance['avatars'] ? 'true' : 'false');
		$timestamp = ($instance['timestamp'] ? 'true' : 'false');
		$hashtags = ($instance['hashtags'] ? 'true' : 'false');
		$widthauto = ($instance['widthauto'] ? 'true' : 'false');
		$width =  ( $widthauto == 'true' ? "'auto'" : $instance['width']); 
		$height =  $instance['height']; 

        echo $before_widget;
        if ( $title )
            echo $before_title . $title . $after_title;
?>
<!-- Begin Twitter search -->
<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'search',
  search: '<?php echo $twittersearch; ?>',
  rpp: <?php echo $rpp; ?>,
  interval: <?php echo $interval; ?>,
  title: '<?php echo $searchtitle; ?>',
  subject: '<?php echo $searchdesc; ?>',
  width: <?php echo $width; ?>,
  height: <?php echo $height; ?>,
  theme: {
    shell: {
      background: '<?php echo $shellbg; ?>',
      color: '<?php echo $shellcolor; ?>'
    },
    tweets: {
      background: '<?php echo $tweetbg; ?>',
      color: '<?php echo $tweetcolor; ?>',
      links: '<?php echo $tweetlink; ?>'
    }
  },
  features: {
    scrollbar: <?php echo $scrollbar; ?>,
    loop: <?php echo $loop; ?>,
    live: <?php echo $live; ?>,
    hashtags: <?php echo $hashtags; ?>,
    timestamp: <?php echo $timestamp; ?>,
    avatars: <?php echo $avatars; ?>,
    behavior: '<?php echo $behavior; ?>'
  }
}).render().start();
</script>
<br />
<!-- End Twitter List -->

<?php
        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance = array( 'live' => 0, 'scrollbar' => 0, 'loop' => 0, 'avatars' => 0, 'timestamp' => 0, 'hashtags' => 0, 'widthauto' => 0);
		foreach ( $instance as $field => $val ) {
			if ( isset($new_instance[$field]) )
				$instance[$field] = '1';
		}
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['twittersearch'] = strip_tags($new_instance['twittersearch']);
        $instance['searchtitle'] = strip_tags($new_instance['searchtitle']);
        $instance['searchdesc'] = strip_tags($new_instance['searchdesc']);
        $instance['shellbg'] = strip_tags($new_instance['shellbg']);
        $instance['shellcolor'] = strip_tags($new_instance['shellcolor']);
        $instance['tweetbg'] = strip_tags($new_instance['tweetbg']);
        $instance['tweetcolor'] = strip_tags($new_instance['tweetcolor']);
        $instance['tweetlink'] = strip_tags($new_instance['tweetlink']);
        $instance['behavior'] = $new_instance['behavior'];
        $instance['interval'] = $new_instance['interval'];
        $instance['rpp'] = $new_instance['rpp'];
        $instance['width'] = $new_instance['width'];
        $instance['height'] = $new_instance['height'];

        return $instance;
    }

    function form( $instance ) {
		$defaults = array( 'title' => 'Twitter Search', 'twittersearch' => 'twittersearch',  'searchtitle' => 'Title',  'searchdesc' => 'Caption', 'shellbg' => '#cccccc', 'shellcolor' => '#ffffff', 'tweetbg' => '#ffffff', 'tweetcolor' => '#444444', 'tweetlink' => '#5588aa', 'scrollbar' => 'true', 'loop' => 'false', 'live' => 'true', 'hashtags' => 'true', 'timestamp' => 'true', 'avatars' => 'true', 'behavior' => 'all', 'interval' => '6000', 'rpp' => '30', 'widthauto' => 'false', 'width' => '150', 'height' => '300' );
        $instance = wp_parse_args( (array) $instance, $defaults );
?>
<p>
<label for="<?php echo $this->get_field_id('title'); ?>">Title (Heading):</label> 
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
</p>
<p>
<strong>General Settings:</strong>
</p>
<p>
<label for="<?php echo $this->get_field_id('twittersearch'); ?>">Search Query:</label> 
<input class="widefat" id="<?php echo $this->get_field_id('twittersearch'); ?>" name="<?php echo $this->get_field_name('twittersearch'); ?>" type="text" value="<?php echo $instance['twittersearch']; ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id('searchtitle'); ?>">Title:</label> 
<input class="widefat" id="<?php echo $this->get_field_id('searchtitle'); ?>" name="<?php echo $this->get_field_name('searchtitle'); ?>" type="text" value="<?php echo $instance['searchtitle']; ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id('searchdesc'); ?>">Caption:</label> 
<input class="widefat" id="<?php echo $this->get_field_id('searchdesc'); ?>" name="<?php echo $this->get_field_name('searchdesc'); ?>" type="text" value="<?php echo $instance['searchdesc']; ?>" />
</p>
<p>
<strong>Advanced Settings - Preferences:</strong>
</p>
<p>
<input class="checkbox" type="checkbox" <?php checked( $instance['live'], true ); ?> id="<?php echo $this->get_field_id( 'live' ); ?>" name="<?php echo $this->get_field_name( 'live' ); ?>" />
<label for="<?php echo $this->get_field_id( 'live' ); ?>">Poll for New Results?</label>
</p>
<p>
<input class="checkbox" type="checkbox" <?php checked( $instance['scrollbar'], true ); ?> id="<?php echo $this->get_field_id( 'scrollbar' ); ?>" name="<?php echo $this->get_field_name( 'scrollbar' ); ?>" />
<label for="<?php echo $this->get_field_id( 'scrollbar' ); ?>">Include Scrollbar?</label>
</p>
<p>
<label for="<?php echo $this->get_field_id( 'behavior' ); ?>"><?php _e('Behavior:', '(load all/loop)'); ?></label> 
<select id="<?php echo $this->get_field_id( 'behavior' ); ?>" name="<?php echo $this->get_field_name( 'behavior' ); ?>" class="widefat" style="width:100%;">
	<option value="all" <?php if ( 'all' == $instance['behavior'] ) echo 'selected="selected"'; ?>>Load All Tweets</option>
	<option value="default" <?php if ( 'default' == $instance['behavior'] ) echo 'selected="selected"'; ?>>Timed Interval</option>
</select>
</p>
<p style="margin-left:15px;">
(Note: these settings only apply if "Timed Interval" is selected.)
</p>
<p style="margin-left:15px;">
<input class="checkbox" type="checkbox" <?php checked( $instance['loop'], true ); ?> id="<?php echo $this->get_field_id( 'loop' ); ?>" name="<?php echo $this->get_field_name( 'loop' ); ?>" />
<label for="<?php echo $this->get_field_id( 'loop' ); ?>">Loop Results?</label>
</p>
<p style="margin-left:15px;">
<label for="<?php echo $this->get_field_id('interval'); ?>">Interval (ms):</label> 
<input class="widefat" id="<?php echo $this->get_field_id('interval'); ?>" name="<?php echo $this->get_field_name('interval'); ?>" type="text" value="<?php echo $instance['interval']; ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id('rpp'); ?>">Number of Tweets:</label> 
<input class="widefat" id="<?php echo $this->get_field_id('rpp'); ?>" name="<?php echo $this->get_field_name('rpp'); ?>" type="text" value="<?php echo $instance['rpp']; ?>" />
</p>
<p>
<input class="checkbox" type="checkbox" <?php checked( $instance['avatars'], true ); ?> id="<?php echo $this->get_field_id( 'avatars' ); ?>" name="<?php echo $this->get_field_name( 'avatars' ); ?>" />
<label for="<?php echo $this->get_field_id( 'avatars' ); ?>">Show Avatars?</label>
</p>
<p>
<input class="checkbox" type="checkbox" <?php checked( $instance['timestamp'], true ); ?> id="<?php echo $this->get_field_id( 'timestamp' ); ?>" name="<?php echo $this->get_field_name( 'timestamp' ); ?>" />
<label for="<?php echo $this->get_field_id( 'timestamp' ); ?>">Show Timestamps?</label>
</p>
<p>
<input class="checkbox" type="checkbox" <?php checked( $instance['hashtags'], true ); ?> id="<?php echo $this->get_field_id( 'hashtags' ); ?>" name="<?php echo $this->get_field_name( 'hashtags' ); ?>" />
<label for="<?php echo $this->get_field_id( 'hashtags' ); ?>">Show Hashtags?</label>
</p>
<p>
<strong>Advanced Settings - Appearance:</strong>
<br />
Note: enter all colors as HEX values (e.g. #ffffff for white)
</p>
<p>
<label for="<?php echo $this->get_field_id('shellbg'); ?>">Shell Background:</label> 
<input class="widefat" id="<?php echo $this->get_field_id('shellbg'); ?>" name="<?php echo $this->get_field_name('shellbg'); ?>" type="text" value="<?php echo $instance['shellbg']; ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id('shellcolor'); ?>">Shell Text:</label> 
<input class="widefat" id="<?php echo $this->get_field_id('shellcolor'); ?>" name="<?php echo $this->get_field_name('shellcolor'); ?>" type="text" value="<?php echo $instance['shellcolor']; ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id('tweetbg'); ?>">Tweet Background:</label> 
<input class="widefat" id="<?php echo $this->get_field_id('tweetbg'); ?>" name="<?php echo $this->get_field_name('tweetbg'); ?>" type="text" value="<?php echo $instance['tweetbg']; ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id('tweetcolor'); ?>">Tweet Text:</label> 
<input class="widefat" id="<?php echo $this->get_field_id('tweetcolor'); ?>" name="<?php echo $this->get_field_name('tweetcolor'); ?>" type="text" value="<?php echo $instance['tweetcolor']; ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id('tweetlink'); ?>">Tweet Links:</label> 
<input class="widefat" id="<?php echo $this->get_field_id('tweetlink'); ?>" name="<?php echo $this->get_field_name('tweetlink'); ?>" type="text" value="<?php echo $instance['tweetlink']; ?>" />
</p>
<p>
<strong>Advanced Settings - Dimensions:</strong>
</p>
<p>
<label for="<?php echo $this->get_field_id('width'); ?>">Width (pixels):</label> 
<input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $instance['width']; ?>" />
</p>
<p style="margin-left:15px;">OR:
<input class="checkbox" type="checkbox" <?php checked( $instance['widthauto'], true ); ?> id="<?php echo $this->get_field_id( 'widthauto' ); ?>" name="<?php echo $this->get_field_name( 'widthauto' ); ?>" />
<label for="<?php echo $this->get_field_id( 'widthauto' ); ?>">Auto Width?</label>
</p>
<p>
<label for="<?php echo $this->get_field_id('height'); ?>">Height (pixels):</label> 
<input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $instance['height']; ?>" />
</p>
<?php
    }
} 
?>