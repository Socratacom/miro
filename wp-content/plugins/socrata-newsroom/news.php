<?php
/*
Plugin Name: Socrata Newsroom
Plugin URI: http://fishinglounge.com/
Description: This plugin adds the Newsroom for press releases.
Version: 1.0
Author: Michael Church
Author URI: http://fishinglounge.com/
License: GPLv2
*/


// REGISTER POST TYPE
add_action( 'init', 'news_post_type' );

function news_post_type() {
  register_post_type( 'news',
    array(
      'labels' => array(
        'name' => 'Newsroom',
        'singular_name' => 'Newsroom',
        'add_new' => 'Add New Post',
        'add_new_item' => 'Add New Post',
        'edit' => 'Edit Post',
        'edit_item' => 'Edit Post',
        'new_item' => 'New Post',
        'view' => 'View',
        'view_item' => 'View Post',
        'search_items' => 'Search Posts',
        'not_found' => 'Not found',
        'not_found_in_trash' => 'Not found in Trash',
        'parent' => 'Parent Socrata News'
      ),      
      'description' => 'Add press releases and customer news',
      'supports' => array( 'title','thumbnail'),
      'public' => true,
      'show_ui' => true,
      'show_in_menu' => 'editorial-content',
      'rewrite' => array('with_front' => false, 'slug' => 'newsroom-article')
    )
  );
}

// TAXONOMIES
add_action( 'init', 'create_news_taxonomies', 0 );
function create_news_taxonomies() {
  register_taxonomy(
    'news_category',
    'news',
    array (
    'labels' => array(
    'name' => 'Newsroom Category',
    'add_new_item' => 'Add New Category',
    'new_item_name' => "New Category"
  ),
    'show_ui' => true,
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
    'rewrite' => array('with_front' => false, 'slug' => 'newsroom')
    )
  );
}

// Print Taxonomy Categories
function news_the_categories() {
    // get all categories for this post
    global $terms;
    $terms = get_the_terms($post->ID , 'news_category');
    // echo the first category
    echo $terms[0]->name;
    // echo the remaining categories, appending separator
    for ($i = 1; $i < count($terms); $i++) {echo ', ' . $terms[$i]->name ;}
}

// Template Paths
add_filter( 'template_include', 'news_single_template', 1 );
function news_single_template( $template_path ) {
  if ( get_post_type() == 'news' ) {
    if ( is_single() ) {
      // checks if the file exists in the theme first,
      // otherwise serve the file from the plugin
      if ( $theme_file = locate_template( array ( 'single-news.php' ) ) ) {
        $template_path = $theme_file;
      } else {
        $template_path = plugin_dir_path( __FILE__ ) . 'single-news.php';
      }
    }
    if ( is_archive() ) {
      // checks if the file exists in the theme first,
      // otherwise serve the file from the plugin
      if ( $theme_file = locate_template( array ( 'archive-news.php' ) ) ) {
        $template_path = $theme_file;
      } else {
        $template_path = plugin_dir_path( __FILE__ ) . 'archive-news.php';
      }
    }
  }
  return $template_path;
}

// Custom Body Class
add_action( 'body_class', 'news_body_class');
function news_body_class( $classes ) {
  if ( is_page('newsroom') || get_post_type() == 'news' && is_single() || get_post_type() == 'news' && is_archive() )
    $classes[] = 'news';
  return $classes;
}

// CUSTOM EXCERPT
function news_excerpt() {
  global $post;
  $text = rwmb_meta( 'news_wysiwyg' );
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

add_filter( 'rwmb_meta_boxes', 'news_register_meta_boxes' );
function news_register_meta_boxes( $meta_boxes )
{
  $prefix = 'news_';

  $meta_boxes[] = array(
    'title'  => __( 'News Details', 'news' ),
    'post_types' => array( 'news' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'fields' => array(
      // HEADING
      array(
        'type' => 'heading',
        'name' => __( 'Featured News', 'news' ),
        'id'   => 'fake_id', // Not used but needed for plugin
      ),
      // CHECKBOX
      array(
        'name' => __( 'Featured', 'news' ),
        'id'   => "{$prefix}featured",
        'desc' => __( 'Yes. This is a featured article.', 'news' ),
        'type' => 'checkbox',
        // Value can be 0 or 1
        'std'  => 0,
      ),
      // HEADING
      array(
        'type' => 'heading',
        'name' => __( 'External News Info', 'news' ),
        'id'   => 'fake_id', // Not used but needed for plugin
      ),
      // TEXT
      array(
        'name'  => __( 'Source', 'news' ),
        'id'    => "{$prefix}source",
        'desc' => __( 'Eample: Wall Street Journal', 'news' ),
        'type'  => 'text',
      ),
      // URL
      array(
        'name' => __( 'Source URL', 'news' ),
        'id'   => "{$prefix}url",
        'desc' => __( 'Include the http:// or https://', 'news' ),
        'type' => 'url',
      ),
      // IMAGE ADVANCED (WP 3.5+)
      array(
        'name'             => __( 'Logo', 'news' ),
        'id'               => "{$prefix}logo",
        'desc' => __( 'NOT FOR PRESS RELEASES', 'news' ),
        'type'             => 'image_advanced',
        'max_file_uploads' => 1,
      ),
      // HEADING
      array(
        'type' => 'heading',
        'name' => __( 'Press Release Content', 'news' ),
        'id'   => 'fake_id', // Not used but needed for plugin
      ),
      array(
        'name'    => __( 'Press Release Content', 'news' ),
        'id'      => "{$prefix}wysiwyg",
        'type'    => 'wysiwyg',
        // Set the 'raw' parameter to TRUE to prevent data being passed through wpautop() on save
        'raw'     => false,
        // Editor settings, see wp_editor() function: look4wp.com/wp_editor
        'options' => array(
          'textarea_rows' => 15,
          'teeny'         => true,
          'media_buttons' => false,
        ),
      ),
    )
  );
  return $meta_boxes;
}


// Shortcode [newsroom-posts]
function newsroom_posts($atts, $content = null) {
  ob_start();
  ?>

  <section class="section-padding">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h1 class="margin-bottom-0 font-light">Newsroom</h1>
          <h3 class="margin-bottom-60">Press releases, articles, and more</h3>
        </div>
      </div>
      <div class="row hidden-lg">
        <div class="col-sm-12 margin-bottom-30">
          <div class="padding-15 background-light-grey-4">
            <ul class="filter-bar">
                <li><?php echo facetwp_display( 'facet', 'solution_dropdown' ); ?></li>
              <li><?php echo facetwp_display( 'facet', 'segment_dropdown' ); ?></li>
              <li><?php echo facetwp_display( 'facet', 'product_dropdown' ); ?></li>
              <li><?php echo facetwp_display( 'facet', 'newsroom_categories_dropdown' ); ?></li>
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
            <button type="button" data-toggle="collapse" data-target="#type">Category</button>
            <div id="type" class="collapse in"><?php echo facetwp_display( 'facet', 'newsroom_categories' ); ?></div>
          </div>
          <div class="alert alert-info margin-bottom-30">
            <p><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Media Contact:</strong> <a href="mailto:press@socrata.com">press@socrata.com</a></p>
          </div> 
          <div class="alert alert-info margin-bottom-30">
            <p><strong>Media professionals:</strong> get brand assets, executive headshots and bios, and company background.</p>
            <p class="margin-bottom-0"><a href="/media-kit/2017-media-kit/">View Media Kit</a></p>
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
            <?php echo facetwp_display( 'template', 'newsroom' ); ?>
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
add_shortcode('newsroom-posts', 'newsroom_posts');