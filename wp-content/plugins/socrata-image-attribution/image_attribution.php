<?php
/*
Plugin Name: Socrata Image Attribution
Plugin URI: http://socrata.com
Description: This plugin adds Image Attribution to the blog.
Version: 1.0
Author: Michael Church
Author URI: http://socrata.com/
License: GPLv2
*/

// Add the Meta Box
function add_attribution_meta_box() {
  $types = array( 'post', 'news', 'case_study', 'stories', 'socrata_events', 'socrata_downloads' );
  foreach( $types as $type ) {
    add_meta_box(
    'custom_meta_box', // $id
    'Feature Image Attribution', // $title
    'show_attribution_meta_box', // $callback
    $type, // $page
    'side', // $context
    'low'); // $priority
  }
}
add_action('add_meta_boxes', 'add_attribution_meta_box');

// Field Array
$prefix = 'custom_';
$custom_attribution_meta_fields = array(
  array(
    'label'=> 'Source',
    'desc'  => 'Usually per license agreement. Ex. Flickr, Bob Smith',
    'id'  => $prefix.'source',
    'type'  => 'text'
  ),
  array(
    'label'=> 'Link',
    'desc'  => 'Source Link if required. Include http://',
    'id'  => $prefix.'link',
    'type'  => 'text'
  ),
);

// The Callback
function show_attribution_meta_box() {
global $custom_attribution_meta_fields, $post;
// Use nonce for verification
echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
// Begin the field table and loop
echo '<table>';
foreach ($custom_attribution_meta_fields as $field) {
  // get value of this field if it exists for this post
  $meta = get_post_meta($post->ID, $field['id'], true);
  // begin a table row with
  echo '<tr>
      <td>
      <label for="'.$field['id'].'" style="display:block; font-weight:bold">'.$field['label'].'</label>';
      switch($field['type']) {
      
      // Text
      case 'text':
        echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" style="width:100%;" /><br /><span class="description"><small>'.$field['desc'].'</small></span>';
      break;
        
      } //end switch
  echo '</td></tr>';
} // end foreach
echo '</table>'; // end table
}

// Save the Data
function save_attribution_meta($post_id) {
    global $custom_attribution_meta_fields;
  // verify nonce
  if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
    return $post_id;
  // check autosave
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
    return $post_id;
  // check permissions
  if ('page' == $_POST['post_type']) {
    if (!current_user_can('edit_page', $post_id))
      return $post_id;
    } elseif (!current_user_can('edit_post', $post_id)) {
      return $post_id;
  }
  // loop through fields and save the data
  foreach ($custom_attribution_meta_fields as $field) {

    $old = get_post_meta($post_id, $field['id'], true);
    $new = $_POST[$field['id']];
    if ($new && $new != $old) {
      update_post_meta($post_id, $field['id'], $new);
    } elseif ('' == $new && $old) {
      delete_post_meta($post_id, $field['id'], $old);
    }
  } // end foreach
}
add_action('save_post', 'save_attribution_meta');

// Get and return the values for the URL and description
function get_attribution_meta() {
  global $post;
  $custom_source = get_post_meta($post->ID, 'custom_source', true);
  $custom_link = get_post_meta($post->ID, 'custom_link', true); 

  return array(
    $custom_source,
    $custom_link
  );
}

// Shortcode 
function shortcode_image_attribution ($atts, $content = null) {
  ob_start();

  $meta = get_attribution_meta(); 
  if ($meta[1]) {
    echo "<div class='image-attribution'>Photo: <a href='$meta[1]' target='_blank'>$meta[0]</a></div>";
  } elseif ($meta[0]) {
    echo "<div class='image-attribution'>Photo: $meta[0]</div>";
  } 

  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
add_shortcode('image-attribution','shortcode_image_attribution');

