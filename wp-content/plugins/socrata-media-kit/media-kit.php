<?php
/*
Plugin Name: Socrata Media Kit
Plugin URI: http://socrata.com/
Description: This plugin manages the media kit.
Version: 1.0
Author: Michael Church
Author URI: http://Socrata.com/
License: GPLv2
*/

// REGISTER POST TYPE
add_action( 'init', 'media_kit_post_type' );

function media_kit_post_type() {
  register_post_type( 'media_kit',
    array(
      'labels' => array(
        'name' => 'Media Kit',
        'singular_name' => 'Media Kit',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Item',
        'edit' => 'Edit',
        'edit_item' => 'Edit Media Kit',
        'new_item' => 'New Media Kit',
        'view' => 'View',
        'view_item' => 'View Media Kit',
        'search_items' => 'Search Media Kit',
        'not_found' => 'Not found',
        'not_found_in_trash' => 'Not found in Trash',
        'parent' => 'Parent Media Kit'
      ),      
      'description' => 'Manages the media kit page',
      'supports' => array( 'title' ),
      'public' => true,
      'show_ui' => true,
      'show_in_menu' => 'socrata-widgets',
      'rewrite' => array('with_front' => false, 'slug' => 'media-kit'),
    )
  );
}

// Template Paths
add_filter( 'template_include', 'media_kit_single_template', 1 );
function media_kit_single_template( $template_path ) {
  if ( get_post_type() == 'media_kit' ) {
    if ( is_single() ) {
      // checks if the file exists in the theme first,
      // otherwise serve the file from the plugin
      if ( $theme_file = locate_template( array ( 'single-media-kit.php' ) ) ) {
        $template_path = $theme_file;
      } else {
        $template_path = plugin_dir_path( __FILE__ ) . 'single-media-kit.php';
      }
    }
  }
  return $template_path;
}

// Custom Body Class
add_action( 'body_class', 'media_kit_body_class');
function media_kit_body_class( $classes ) {
  if ( get_post_type() == 'media_kit' && is_single() )
    $classes[] = 'media-kit';
  return $classes;
}

// CUSTOM EXCERPT
function media_kit_excerpt() {
  global $post;
  $text = rwmb_meta( 'media_kit_wysiwyg' );
  if ( '' != $text ) {
    $text = strip_shortcodes( $text );
    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]>', $text);
    $excerpt_length = 20; // 20 words
    $excerpt_more = apply_filters('excerpt_more', ' ' . ' ...');
    $text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
  }
  return apply_filters('get_the_excerpt', $text);
}

// Metabox
add_filter( 'rwmb_meta_boxes', 'media_kit_register_meta_boxes' );
function media_kit_register_meta_boxes( $meta_boxes )
{
  $prefix = 'media_kit';

  $meta_boxes[] = array(
    'title'  => __( 'HIGHLIGHTS', 'media_kit' ),
    'post_types' => array( 'media_kit' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'fields' => array(
      // HEADING
      array(
        'type' => 'heading',
        'name' => esc_html__( 'Instructions', 'media_kit' ),
        'desc' => esc_html__( 'Add highligh content such as Press Releases, Case Studies, Blog Posts, etc.', 'media_kit' ),
      ),
      // DIVIDER
      array(
        'type' => 'divider',
      ),
      // GROUP
      array(
        'id'     => "{$prefix}_highlight_group",
        'type'   => 'group',
        'clone'  => true,
        'sort_clone' => true,
        'fields' => array(
          // TEXT
          array(
            'name'  => __( 'Title', 'media_kit' ),
            'id'    => "{$prefix}_highlight_title",
            'type'  => 'text',
          ),
          // WYSIWYG/RICH TEXT EDITOR
          array(
            'name'    => esc_html__( 'Content', 'media_kit' ),
            'id'      => "{$prefix}_highlights_content",
            'type'    => 'wysiwyg',
            'raw'     => false,
            'options' => array(
              'textarea_rows' => 4,
              'teeny'         => false,
              'media_buttons' => false,
            ),
          ),
          // IMAGE ADVANCED - RECOMMENDED
          array(
            'name'  => esc_html__( 'Thumbnail', 'media_kit' ),
            'id'    => "{$prefix}_highlight_thumbnail",
            'type'  => 'image_advanced',
            'force_delete'     => false,
            'max_file_uploads' => 1,
            'max_status'       => true,
          ),
        ),
      ),
    ),
  );

  $meta_boxes[] = array(
    'title'  => __( 'IN THE NEWS', 'media_kit_' ),
    'post_types' => array( 'media_kit' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'fields' => array(
      // HEADING
      array(
        'type' => 'heading',
        'name' => esc_html__( 'Instructions', 'media_kit' ),
        'desc' => esc_html__( 'Add links to outside sources such as Route Fifty, Forbes, etc.', 'media_kit' ),
      ),
      // DIVIDER
      array(
        'type' => 'divider',
      ),
      // GROUP
      array(
        'id'     => "{$prefix}_news_group",
        'type'   => 'group',
        'clone'  => true,
        'sort_clone' => true,
        'fields' => array(
          // TEXT
          array(
            'name'  => __( 'Title', 'media_kit' ),
            'id'    => "{$prefix}_news_title",
            'type'  => 'text',
          ),          
          // TEXT
          array(
            'name'  => __( 'Source', 'media_kit' ),
            'id'    => "{$prefix}_news_source",
            'type'  => 'text',
          ),
          // URL
          array(
            'name' => esc_html__( 'Source URL', 'media_kit' ),
            'id'   => "{$prefix}_news_url",
            'type' => 'url',
          ),
          // DATE
          array(
            'name'       => esc_html__( 'Date Published', 'media_kit' ),
            'id'         => "{$prefix}_news_date",
            'type'       => 'date',
            // jQuery date picker options. See here http://api.jqueryui.com/datepicker
            'js_options' => array(
              'dateFormat'      => esc_html__( 'd MM, yy', 'media_kit' ),
              'changeMonth'     => true,
              'changeYear'      => true,
              'showButtonPanel' => true,
            ),
          ),
          // IMAGE ADVANCED - RECOMMENDED
          array(
            'name'  => esc_html__( 'Thumbnail', 'media_kit' ),
            'id'    => "{$prefix}_news_thumbnail",
            'type'  => 'image_advanced',
            'force_delete'     => false,
            'max_file_uploads' => 1,
            'max_status'       => true,
          ),
        ),
      ),
    ),
  );

  $meta_boxes[] = array(
    'title'  => __( 'DOWNLOADS', 'media_kit_' ),
    'post_types' => array( 'media_kit' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'fields' => array(
      // HEADING
      array(
        'type' => 'heading',
        'name' => esc_html__( 'Instructions', 'media_kit' ),
        'desc' => esc_html__( 'Upload downloads such as Datasheets, Solution Sheets, etc.', 'media_kit' ),
      ),
      // DIVIDER
      array(
        'type' => 'divider',
      ),
      // GROUP
      array(
        'id'     => "{$prefix}_downloads_group",
        'type'   => 'group',
        'clone'  => true,
        'sort_clone' => true,
        'fields' => array(
          // TEXT
          array(
            'name'  => __( 'Asset Title', 'media_kit' ),
            'id'    => "{$prefix}_download_asset_title",
            'type'  => 'text',
          ),
          // FILE ADVANCED (WP 3.5+)
          array(
            'name'             => esc_html__( 'File Advanced Upload', 'media_kit' ),
            'id'               => "{$prefix}_download_asset",
            'type'             => 'file_advanced',
            'max_file_uploads' => 1,
            'mime_type'        => 'application',
            'desc' => __( 'Downloadable file (ie. PDF)', 'media_kit' ),
          ),
        ),
      ),
    ),
  );

  $meta_boxes[] = array(
    'title'  => __( 'TIMELINE', 'media_kit' ),
    'post_types' => array( 'media_kit' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'fields' => array(
      // HEADING
      array(
        'type' => 'heading',
        'name' => esc_html__( 'Instructions', 'media_kit' ),
        'desc' => esc_html__( 'Add a significant point in our company timeline', 'media_kit' ),
      ),
      // DIVIDER
      array(
        'type' => 'divider',
      ),
      // GROUP
      array(
        'id'     => "{$prefix}_timeline_group",
        'type'   => 'group',
        'clone'  => true,
        'sort_clone' => true,
        'fields' => array(
          // NUMBER
          array(
            'name' => esc_html__( 'Year', 'media_kit' ),
            'id'   => "{$prefix}_timeline_year",
            'type' => 'number',
            'min'  => 4,
          ),
          // WYSIWYG/RICH TEXT EDITOR
          array(
            'name'    => esc_html__( 'Content', 'media_kit' ),
            'id'      => "{$prefix}_timeline_content",
            'type'    => 'wysiwyg',
            'raw'     => false,
            'options' => array(
              'textarea_rows' => 4,
              'teeny'         => false,
              'media_buttons' => false,
            ),
          ),
        ),
      ),
    ),
  );

  $meta_boxes[] = array(
    'title'  => __( 'ABOUT SOCRATA', 'media_kit_' ),
    'post_types' => array( 'media_kit' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'fields' => array(
      // HEADING
      array(
        'type' => 'heading',
        'name' => esc_html__( 'Instructions', 'media_kit' ),
        'desc' => esc_html__( 'Enter content about Socrata that media organizations should know.', 'media_kit' ),
      ),
      // WYSIWYG/RICH TEXT EDITOR
      array(
        'id'      => "{$prefix}_about_socrata_content",
        'type'    => 'wysiwyg',
        'raw'     => false,
        'options' => array(
          'textarea_rows' => 4,
          'teeny'         => false,
          'media_buttons' => true,
        ),
      ),
    ),
  );

  return $meta_boxes;
}
