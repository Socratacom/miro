<?php
/*
Plugin Name: Socrata Case Studies
Plugin URI: http://socrata.com/
Description: This plugin manages Case Studies.
Version: 1.0
Author: Michael Church
Author URI: http://Socrata.com/
License: GPLv2
*/


// REGISTER POST TYPE
add_action( 'init', 'case_study_post_type' );

function case_study_post_type() {
  register_post_type( 'case_study',
    array(
      'labels' => array(
        'name' => 'Case Studies',
        'singular_name' => 'Case Study',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Case Study',
        'edit' => 'Edit',
        'edit_item' => 'Edit Case Study',
        'new_item' => 'New Case Study',
        'view' => 'View',
        'view_item' => 'View Case Studies',
        'search_items' => 'Search Case Studies',
        'not_found' => 'Not found',
        'not_found_in_trash' => 'Not found in Trash',
        'parent' => 'Parent Case Study'
      ),
      'description' => 'Add customer case study content',
      'supports' => array( 'title','thumbnail'),
      'public' => true,
      'show_ui' => true,
      'show_in_menu' => 'editorial-content',
      'rewrite' => array('with_front' => false, 'slug' => 'case-study')
    )
  );
}

// MENU ICON
//Using Dashicon Font http://melchoyce.github.io/dashicons/
add_action( 'admin_head', 'add_case_study_icon' );
function add_case_study_icon() { ?>
  <style>
    #adminmenu .menu-icon-case_study div.wp-menu-image:before {
      content: '\f123';
    }
  </style>
  <?php
}

// Template Paths
add_filter( 'template_include', 'case_study_single_template', 1 );
function case_study_single_template( $template_path ) {
  if ( get_post_type() == 'case_study' ) {
    if ( is_single() ) {
      // checks if the file exists in the theme first,
      // otherwise serve the file from the plugin
      if ( $theme_file = locate_template( array ( 'single-case-study.php' ) ) ) {
        $template_path = $theme_file;
      } else {
        $template_path = plugin_dir_path( __FILE__ ) . 'single-case-study.php';
      }
    }
    if ( is_archive() ) {
      // checks if the file exists in the theme first,
      // otherwise serve the file from the plugin
      if ( $theme_file = locate_template( array ( 'archive-case-study.php' ) ) ) {
        $template_path = $theme_file;
      } else {
        $template_path = plugin_dir_path( __FILE__ ) . 'archive-case-study.php';
      }
    }
  }
  return $template_path;
}

// Custom Body Class
add_action( 'body_class', 'case_study_body_class');
function case_study_body_class( $classes ) {
  if ( is_page('case-studies') || get_post_type() == 'case_study' && is_single() )
    $classes[] = 'case-study';
  return $classes;
}

// CUSTOM EXCERPT
function case_studies_excerpt() {
  global $post;
  $text = rwmb_meta( 'case_study_wysiwyg' );
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

// DASHBOARD WIDGET
require_once( plugin_dir_path( __FILE__ ) . '/widget.php' );
class Case_Study_Widget {
 
  function __construct() {
      add_action( 'wp_dashboard_setup', array( $this, 'add_case_study_dashboard_widget' ) );
  }

  function add_case_study_dashboard_widget() {
    global $custom_case_study_dashboard_widget;
 
    foreach ( $custom_case_study_dashboard_widget as $widget_id => $options ) {
      wp_add_dashboard_widget(
          $widget_id,
          $options['title'],
          $options['callback']
      );
    }
  } 
}
 
$wdw = new Case_Study_Widget();

// Metabox
add_filter( 'rwmb_meta_boxes', 'case_study_register_meta_boxes' );
function case_study_register_meta_boxes( $meta_boxes )
{
  $prefix = 'case_study_';

  $meta_boxes[] = array(
    'title'  => __( 'Case Study Meta', 'case_study_' ),
    'post_types' => array( 'case_study' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'fields' => array(
      // TEXT
      array(
        'name'  => __( 'Customer', 'case_study_' ),
        'id'    => "{$prefix}customer",
        'type'  => 'text',
      ),
      // TEXT
      array(
        'name'  => __( 'Site Name', 'case_study_' ),
        'id'    => "{$prefix}site_name",
        'type'  => 'text',
      ),
      // URL
      array(
        'name' => __( 'URL', 'case_study_' ),
        'id'   => "{$prefix}url",
        'desc' => __( 'Include the http:// or https://', 'case_study_' ),
        'type' => 'url',
      ),
    ),
  );

  $meta_boxes[] = array(
    'title'         => 'Highlights',   
    'post_types'    => 'case_study',
    'context'       => 'normal',
    'priority'      => 'high',
    'fields' => array(
      // TEXT
      array(
        'name'  => esc_html__( 'Highlight', 'case_study_' ),
        'id'    => "{$prefix}highlight",
        'type'  => 'text',
        'clone' => true,
      ),
    ),
  );

  $meta_boxes[] = array(
    'title'         => 'Pull Quote',   
    'post_types'    => 'case_study',
    'context'       => 'normal',
    'priority'      => 'high',
    'fields' => array(
      // TEXT
      array(
        'name'  => __( 'Name', 'case_study_' ),
        'id'    => "{$prefix}name",
        'type'  => 'text',
      ),
      // TEXT
      array(
        'name'  => __( 'Title', 'case_study_' ),
        'id'    => "{$prefix}title",
        'type'  => 'text',
      ),
      // IMAGE ADVANCED (WP 3.5+)
      array(
        'name'             => __( 'Headshot', 'case_study_' ),
        'id'               => "{$prefix}headshot",
        'type'             => 'image_advanced',
        'max_file_uploads' => 1,
      ),
      // TEXTAREA
      array(
        'name' => esc_html__( 'Quote', 'case_study_' ),
        'id'   => "{$prefix}quote",
        'type' => 'textarea',
        'cols' => 20,
        'rows' => 3,
      ),
    ),
  );

  $meta_boxes[] = array(
    'title'         => 'Case Study Content',   
    'post_types'    => 'case_study',
    'context'       => 'normal',
    'priority'      => 'high',
      'fields' => array(
        array(
        'id'      => "{$prefix}wysiwyg",
        'type'    => 'wysiwyg',
        // Set the 'raw' parameter to TRUE to prevent data being passed through wpautop() on save
        'raw'     => false,
        // Editor settings, see wp_editor() function: look4wp.com/wp_editor
        'options' => array(
          'textarea_rows' => 15,
          'teeny'         => false,
          'media_buttons' => true,
        ),
      ),
    ),
  );

  return $meta_boxes;
}


// Shortcode [case-study-posts]
function case_study_posts($atts, $content = null) {
  ob_start();
  ?>
  <section class="section-padding">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h1 class="margin-bottom-0 font-light">Case Studies</h1>
          <h3 class="margin-bottom-60">Celebrating customer success</h3>
        </div>
      </div>
      <div class="row hidden-lg">
        <div class="col-sm-12 margin-bottom-30">
          <div class="padding-15 background-light-grey-4">
            <ul class="filter-bar">
                <li><?php echo facetwp_display( 'facet', 'solution_dropdown' ); ?></li>
              <li><?php echo facetwp_display( 'facet', 'segment_dropdown' ); ?></li>
              <li><?php echo facetwp_display( 'facet', 'product_dropdown' ); ?></li>
              <li><button onclick="FWP.reset()" class="btn btn-primary"><i class="fa fa-undo" aria-hidden="true"></i></button></li>
            </ul>
          </div>          
        </div>
      </div>
      <div class="row">
        <div class="col-lg-3 hidden-xs hidden-sm hidden-md facet-sidebar">
          <button onclick="FWP.reset()" class="btn btn-primary btn-block margin-bottom-30"><i class="fa fa-undo" aria-hidden="true"></i> Reset Filters</button>
          <div class="filter-list">
            <button type="button" data-toggle="collapse" data-target="#solution">Solution</button>
            <div id="solution" class="collapse in"><?php echo facetwp_display( 'facet', 'solution' ); ?></div>
            <button type="button" data-toggle="collapse" data-target="#segment">Segment</button>
            <div id="segment" class="collapse in"><?php echo facetwp_display( 'facet', 'segment' ); ?></div>
            <button type="button" data-toggle="collapse" data-target="#product">Product</button>
            <div id="product" class="collapse in"><?php echo facetwp_display( 'facet', 'products' ); ?></div>
          </div>            
        </div>
        <div class="col-sm-12 col-lg-9">
          <div class="row">
            <div class="col-sm-12 margin-bottom-30">
              <ul class="list-table">
                <li><small>Showing: <?php echo do_shortcode('[facetwp counts="true"]') ;?></small></li>
                <li class="text-right"><?php echo do_shortcode('[facetwp sort="true"]') ;?></li>
              </ul>
            </div>
            <?php echo facetwp_display( 'template', 'case_studies' ); ?>
            <div class="col-sm-12 margin-top-30">
              <ul class="list-table">
                <li><small>Showing: <?php echo do_shortcode('[facetwp counts="true"]') ;?></small></li>
                <li class="text-right"><?php echo do_shortcode('[facetwp per_page="true"]') ;?></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php echo do_shortcode('[match-height]');?>
  <script>!function(n){n(function(){FWP.loading_handler=function(){}})}(jQuery);</script>
  <?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
add_shortcode('case-study-posts', 'case_study_posts');





