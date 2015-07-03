<?php 
/*
* Plugin Name: Giornalismo Story Details
* Plugin URI: http://www.jacobmartella.com/giornalismo/
* Description: Adds custom fields for the Giornalismo WordPress theme for featured photo credit, caption, featured video link and three story highlights.
* Version: 1.0
* Author: Jacob Martella
* Author URI: http://www.jacobmartella.com
* License: GPLv3
*/

//* Load the text domain
load_plugin_textdomain('giornalismo-story-details', false, basename( dirname( __FILE__ ) ) . '/languages' );

//* Add the meta box
function giornalismo_story_details_add_box() {
	add_meta_box('giornalismo-story-details', __('Story Details', 'giornalismo-story-details'), 'giornalismo_story_details_meta_box_cb', 'post', 'normal', 'default');
}
add_action('admin_menu', 'giornalismo_story_details_add_box');

//* Create the actual meta box
function giornalismo_story_details_meta_box_cb() {
	global $post;
	$values = get_post_custom($post->ID);
	if (isset($values['giornalismo_photo_credit'])) { $credit = $values['giornalismo_photo_credit'][0]; } else { $credit = ''; }
	if (isset($values['giornalismo_photo_caption'])) { $caption = $values['giornalismo_photo_caption'][0]; } else { $caption = ''; }
	if (isset($values['giornalismo_featured_video'])) { $video = $values['giornalismo_featured_video'][0]; } else { $video = ''; }
	if (isset($values['giornalismo_highlight_one'])) { $highlight_one = $values['giornalismo_highlight_one'][0]; } else { $highlight_one = ''; }
	if (isset($values['giornalismo_highlight_two'])) { $highlight_two = $values['giornalismo_highlight_two'][0]; } else { $highlight_two = ''; }
	if (isset($values['giornalismo_highlight_three'])) { $highlight_three = $values['giornalismo_highlight_three'][0]; } else { $highlight_three = ''; }

	wp_nonce_field('giornalismo_story_details_nonce', 'meta_box_nonce');

	echo '<p>';
	echo '<label for="giornalismo_photo_credit">' . __('Photo Credit', 'giornalismo-story-details') . '</label>';
	echo '<input type="text" name="giornalismo_photo_credit" id="giornalismo_photo_credit" value="' . $credit .'" class="widefat" />';
	echo '</p>';

	echo '<p>';
	echo '<label for="giornalismo_photo_caption">' . __('Photo Caption', 'giornalismo-story-details') . '</label>';
	echo '<input type="text" name="giornalismo_photo_caption" id="giornalismo_photo_caption" value="' . $caption .'" class="widefat" />';
	echo '</p>';

	echo '<p>';
	echo '<label for="giornalismo_featured_video">' . __('Link to Featured Video (preferably YouTube)', 'giornalismo-story-details') . '</label>';
	echo '<input type="text" name="giornalismo_featured_video" id="giornalismo_featured_video" value="' . $video .'" class="widefat" />';
	echo '</p>';

	echo '<p>';
	echo '<label for="giornalismo_highlight_one">' . __('First Story Highlight', 'giornalismo-story-details') . '</label>';
	echo '<input type="text" name="giornalismo_highlight_one" id="giornalismo_highlight_one" value="' . $highlight_one .'" class="widefat" />';
	echo '</p>';

	echo '<p>';
	echo '<label for="giornalismo_highlight_two">' . __('Second Story Highlight', 'giornalismo-story-details') . '</label>';
	echo '<input type="text" name="giornalismo_highlight_two" id="giornalismo_highlight_two" value="' . $highlight_two .'" class="widefat" />';
	echo '</p>';

	echo '<p>';
	echo '<label for="giornalismo_highlight_three">' . __('Third Story Highlight', 'giornalismo-story-details') . '</label>';
	echo '<input type="text" name="giornalismo_highlight_three" id="giornalismo_highlight_three" value="' . $highlight_three .'" class="widefat" />';
	echo '</p>';

}

//* Save and sanitize the meta box
function giornalismo_story_details_save_box($post_id) {
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'giornalismo_story_details_nonce' ) ) {
		return;
	}
	if( !current_user_can( 'edit_post' ) ) {
		return;
	}

	if(isset($_POST['giornalismo_photo_credit'])) {
        update_post_meta($post_id, 'giornalismo_photo_credit', sanitize_text_field($_POST['giornalismo_photo_credit']));
    }

    if(isset($_POST['giornalismo_photo_caption'])) {
        update_post_meta($post_id, 'giornalismo_photo_caption', sanitize_text_field($_POST['giornalismo_photo_caption']));
    }

    if(isset($_POST['giornalismo_featured_video'])) {
        update_post_meta($post_id, 'giornalismo_featured_video', wp_filter_nohtml_kses($_POST['giornalismo_featured_video']));
    }

    if(isset($_POST['giornalismo_highlight_one'])) {
        update_post_meta($post_id, 'giornalismo_highlight_one', sanitize_text_field($_POST['giornalismo_highlight_one']));
    }

    if(isset($_POST['giornalismo_highlight_two'])) {
        update_post_meta($post_id, 'giornalismo_highlight_two', sanitize_text_field($_POST['giornalismo_highlight_two']));
    }

    if(isset($_POST['giornalismo_highlight_three'])) {
        update_post_meta($post_id, 'giornalismo_highlight_three', sanitize_text_field($_POST['giornalismo_highlight_three']));
    }
}
add_action('save_post', 'giornalismo_story_details_save_box');

?>