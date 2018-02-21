<?php
/*
Plugin Name: Socrata Hide Posts
Plugin URI: http://socrata.com/
Description: Hides post from landing page query.
Version: 1.0
Author: Michael Church
Author URI: http://socrata.com/
License: GPLv2
*/

// Metabox
add_filter( 'rwmb_meta_boxes', 'socrata_hidden_meta_boxes' );
function socrata_hidden_meta_boxes( $meta_boxes )
{
  $prefix = 'socrata_hidden_';
  
  $meta_boxes[] = array(
    'title'  => __( 'Hide Post', 'socrata_hidden_' ),
    'post_types' => array('socrata_downloads','case_study','socrata_events','socrata_videos','socrata_webinars'),
    'context'    => 'side',
    'priority'   => 'high',
    'fields' => array(
      // CHECKBOX
      array(
        'id'   => "{$prefix}hide",
        'desc' => __( 'Hide this post', 'socrata_hidden_' ),
        'type' => 'checkbox',
        // Value can be 0 or 1
        'std'  => 0,
      ),
    )
  );

  return $meta_boxes;
}