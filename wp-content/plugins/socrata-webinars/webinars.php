<?php
/*
Plugin Name: Socrata Webinars
Plugin URI: http://socrata.com/
Description: This plugin manages Socrata webinars.
Version: 1.0
Author: Michael Church
Author URI: http://socrata.com/
License: GPLv2
*/

add_action( 'init', 'create_socrata_webinars' );
function create_socrata_webinars() {
  register_post_type( 'socrata_webinars',
    array(
      'labels' => array(
        'name' => 'Webinars',
        'singular_name' => 'Webinars',
        'add_new' => 'Add New Webinar',
        'add_new_item' => 'Add New Webinar',
        'edit' => 'Edit Webinars',
        'edit_item' => 'Edit Webinars',
        'new_item' => 'New Webinar',
        'view' => 'View',
        'view_item' => 'View Webinar',
        'search_items' => 'Search Webinars',
        'not_found' => 'Not found',
        'not_found_in_trash' => 'Not found in Trash'
      ),            
      'description' => 'Manages upcoming and on demand webinars',
      'supports' => array( 'title','thumbnail'),
      'public' => true,
      'show_ui' => true,
      'show_in_menu' => 'editorial-content',
      'rewrite' => array('with_front' => false, 'slug' => 'webinar')
    )
  );
}

// TAXONOMIES
add_action( 'init', 'socrata_webinars_cat', 0 );
function socrata_webinars_cat() {
  register_taxonomy(
    'socrata_webinars_cat',
    'socrata_webinars',
    array(
      'labels' => array(
        'name' => 'Webinars Stauts',
        'menu_name' => 'Webinars Status',
        'add_new_item' => 'Add New Status',
        'new_item_name' => "New Status"
      ),
      'show_ui' => false,
      'show_in_menu' => true,
      'show_tagcloud' => false,
      'hierarchical' => true,
      'sort' => true,      
      'args' => array( 'orderby' => 'term_order' ),
      'show_admin_column' => true,
      'capabilities'=>array(
        'manage_terms' => 'manage_options',//or some other capability your clients don't have
        'edit_terms' => 'manage_options',
        'delete_terms' => 'manage_options',
        'assign_terms' =>'edit_posts'
      ),
      'rewrite' => array('with_front' => false, 'slug' => 'webinars-category'),
    )
  );
}
// DEFAULT TAXONOMY
function socrata_webinars_default_taxonomy( $post_id ) {
    $current_post = get_post( $post_id );

    // This makes sure the taxonomy is only set when a new post is created
    if ( $current_post->post_date == $current_post->post_modified ) {
        wp_set_object_terms( $post_id, 'upcoming', 'socrata_webinars_cat', true );
    }
}
add_action( 'save_post_socrata_webinars', 'socrata_webinars_default_taxonomy' );

// PRINT TAXONOMY CATEGORIES
function webinars_the_categories() {
  // get all categories for this post
  global $terms;
  $terms = get_the_terms($post->ID , 'socrata_webinars_cat');
  // echo the first category
  echo $terms[0]->name;
  // echo the remaining categories, appending separator
  for ($i = 1; $i < count($terms); $i++) {echo ', ' . $terms[$i]->name ;}
}

// MENU ICON
//Using Dashicon Font https://developer.wordpress.org/resource/dashicons
add_action( 'admin_head', 'add_socrata_webinars_icon' );
function add_socrata_webinars_icon() { ?>
  <style>
    #adminmenu .menu-icon-socrata_webinars div.wp-menu-image:before {
      content: '\f508';
    }
  </style>
  <?php
}

// TEMPLATES
// Endpoint Rewrites
add_action('init', 'socrata_webinars_add_endpoints');
function socrata_webinars_add_endpoints()
{
  add_rewrite_endpoint('webinar-confirmation', EP_PERMALINK);
  add_rewrite_endpoint('webinar-video', EP_PERMALINK);
}
// Template Paths
add_filter( 'template_include', 'socrata_webinars_single_template', 1 );
function socrata_webinars_single_template( $template_path ) {
  if ( get_post_type() == 'socrata_webinars' ) {
    if ( is_single() ) {
      // checks if the file exists in the theme first,
      // otherwise serve the file from the plugin
      if ( $theme_file = locate_template( array ( 'single-webinars.php' ) ) ) {
        $template_path = $theme_file;
      } else {
        $template_path = plugin_dir_path( __FILE__ ) . 'single-webinars.php';
      }
    }
    if ( get_query_var( 'webinar-confirmation' )  ) {
      $template_path = plugin_dir_path( __FILE__ ) . 'confirmation.php';
    }
    if ( get_query_var( 'webinar-video' )  ) {
      $template_path = plugin_dir_path( __FILE__ ) . 'video.php';
    }
  }
  return $template_path;
}
// Template Request
add_filter( 'request', 'socrata_webinars_filter_request' );
function socrata_webinars_filter_request( $vars )
{
  if( isset( $vars['webinar-confirmation'] ) ) $vars['webinar-confirmation'] = true;
  if( isset( $vars['webinar-video'] ) ) $vars['webinar-video'] = true;
  return $vars;
}

// CUSTOM BODY CLASS
add_action( 'body_class', 'socrata_webinars_body_class');
function socrata_webinars_body_class( $classes ) {
  if ( get_post_type() == 'socrata_webinars' && is_single() || get_post_type() == 'socrata_webinars' && is_archive() )
    $classes[] = 'socrata-webinars';
  return $classes;
}

// CUSTOM EXCERPT
function webinars_excerpt() {
  global $post;
  $text = rwmb_meta( 'webinars_wysiwyg' );
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
class Socrata_Webinars_Widget {
 
  function __construct() {
      add_action( 'wp_dashboard_setup', array( $this, 'add_socrata_webinars_dashboard_widget' ) );
  }

  function add_socrata_webinars_dashboard_widget() {
    global $custom_socrata_webinars_dashboard_widget;
 
    foreach ( $custom_socrata_webinars_dashboard_widget as $widget_id => $options ) {
      wp_add_dashboard_widget(
          $widget_id,
          $options['title'],
          $options['callback']
      );
    }
  } 
}
 
$wdw = new Socrata_Webinars_Widget();

// Metabox
add_filter( 'rwmb_meta_boxes', 'socrata_webinars_register_meta_boxes' );
function socrata_webinars_register_meta_boxes( $meta_boxes )
{
  $prefix = 'webinars_';
  $meta_boxes[] = array(
    'title'  => __( 'Webinar Meta', 'webinars_' ),
    'post_types' => 'socrata_webinars',
    'context'    => 'normal',
    'priority'   => 'high',
    'validation' => array(
      'rules'    => array(
        "{$prefix}city" => array(
            'required'  => true,
        ),
        "{$prefix}state" => array(
            'required'  => true,
        ),
        "{$prefix}displaydate" => array(
            'required'  => true,
        ),
        "{$prefix}marketo_registration" => array(
            'required'  => true,
        ),
        "{$prefix}marketo_on_demand" => array(
            'required'  => true,
        ),
        "{$prefix}starttime" => array(
            'required'  => true,
        ),
      ),
    ),
    'fields' => array(
      // HEADING
      array(
        'type' => 'heading',
        'name' => __( 'Webinar Date and Time', 'webinars_' ),
        'id'   => 'fake_id', // Not used but needed for plugin
      ),
      // DATETIME
      array(
        'name'        => __( 'Date', 'webinars_' ),
        'id'          => $prefix . 'starttime',
        'type'        => 'date',
        'timestamp'   => false,
        'js_options' => array(
          'numberOfMonths'  => 1,
          'showButtonPanel' => true,
        ),
      ),      
      // TEXT
      array(
        'name'  => __( 'Display Date and Time', 'webinars_' ),
        'id'    => "{$prefix}displaydate",
        'desc' => __( 'Example: Monday, January 1, 1:00 pm - 2:00 pm PT', 'webinars_' ),
        'type'  => 'text',
        'clone' => false,
      ),      
      // HEADING
      array(
        'type' => 'heading',
        'name' => __( 'Forms', 'webinars_' ),
        'id'   => 'fake_id', // Not used but needed for plugin
      ),
      // URL
      array(
        'name' => esc_html__( 'Registration Form', 'webinars_' ),
        'id'   => "{$prefix}form_registration",
        'desc' => esc_html__( 'http://go.socrata.com/l/303201/...', 'webinars_' ),
        'type' => 'url',
      ),
      // URL
      array(
        'name' => esc_html__( 'On-Demand Form', 'webinars_' ),
        'id'   => "{$prefix}form_on_demand",
        'desc' => esc_html__( 'http://go.socrata.com/l/303201/...', 'webinars_' ),
        'type' => 'url',
      ),
      // HEADING
      array(
        'type' => 'heading',
        'name' => __( 'Downloadable Assets', 'webinars_' ),
        'id'   => 'fake_id', // Not used but needed for plugin
      ),
      // URL
      array(
        'name' => esc_html__( 'Slide Deck Link', 'webinars_' ),
        'id'   => "{$prefix}asset_slide_deck",
        'desc' => esc_html__( 'Include http:// or https://', 'webinars_' ),
        'type' => 'url',
      ),      
      // HEADING
      array(
        'type' => 'heading',
        'name' => __( 'On Demand Webinar Video', 'webinars_' ),
        'id'   => 'fake_id', // Not used but needed for plugin
      ),
      // TEXT
      array(
        'name'  => __( 'Wistia ID', 'webinars_' ),
        'id'    => "{$prefix}video",
        'desc' => __( 'Example: am6nzoa6vv', 'webinars_' ),
        'type'  => 'text',
        'clone' => false,
      ), 
    )
  );

  $meta_boxes[] = array(
    'title'         => 'Content',   
    'post_types'    => 'socrata_webinars',
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

  $meta_boxes[] = array(
    'title'         => 'Webinar Status',   
    'post_types'    => 'socrata_webinars',
    'context'       => 'side',
    'priority'      => 'high',
    'fields' => array(
      // TAXONOMY
        array(
          'id'         => "{$prefix}taxonomy",
          'type'       => 'taxonomy',
          // Taxonomy name
          'taxonomy'   => 'socrata_webinars_cat',
          // How to show taxonomy: 'checkbox_list' (default) or 'checkbox_tree', 'select_tree', select_advanced or 'select'. Optional
          'field_type' => 'radio_list',
          // Additional arguments for get_terms() function. Optional
          'query_args' => array(),
        ),
    ),
  );

  $meta_boxes[] = array(
    'title'         => 'Speakers',   
    'post_types'    => 'socrata_webinars',
    'context'       => 'normal',
    'priority'      => 'high',
      'fields' => array(
        // HEADING
        array(
          'type' => 'heading',
          'name' => __( 'Speaker Info', 'webinars_' ),
          'id'   => 'fake_id', // Not used but needed for plugin
        ),
        array(
        'id'     => "{$prefix}speakers",
        'type'   => 'group',
        'clone'  => true,
        'sort_clone' => true,
        // Sub-fields
        'fields' => array(
          array(
            'name' => __( 'Name', 'webinars_' ),
            'id'   => "{$prefix}speaker_name",
            'type' => 'text',
          ),
          array(
            'name' => __( 'Title', 'webinars_' ),
            'id'   => "{$prefix}speaker_title",
            'type' => 'text',
          ),
          // IMAGE ADVANCED (WP 3.5+)
          array(
            'name'             => __( 'Headshot', 'webinars_' ),
            'id'               => "{$prefix}speaker_headshot",
            'desc' => __( 'Minimum size 300x300 pixels.', 'webinars_' ),
            'type'             => 'image_advanced',
            'max_file_uploads' => 1,
          ),
        ),
      ), 
    ),
  );
  return $meta_boxes;
}


// Shortcode [webinars]
function webinars_posts($atts, $content = null) {
  ob_start();
  ?>


  <section class="section-padding">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h1 class="margin-bottom-0 font-light">Webinars</h1>
          <h3 class="margin-bottom-60">Learn from leading experts</h3>
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
          <div class="filter-list margin-bottom-30">
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
            <?php echo facetwp_display( 'template', 'webinars' ); ?>
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
  <script>!function(n){n(function(){FWP.loading_handler=function(){}})}(jQuery);</script>


  <?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
add_shortcode('webinars', 'webinars_posts');
