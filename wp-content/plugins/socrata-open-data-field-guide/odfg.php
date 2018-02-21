<?php
/*
Plugin Name: Socrata Open Data Field Guide
Plugin URI: http://socrata.com/
Description: This plugin manages the Open Data Field Guide.
Version: 1.0
Author: Michael Church
Author URI: http://socrata.com/
License: GPLv2
*/

add_action( 'init', 'create_guide' );
function create_guide() {
  register_post_type( 'guide',
    array(
      'labels' => array(
        'name' => 'Open Data Field Guide',
        'singular_name' => 'Open Data Field Guide',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New',
        'edit' => 'Edit',
        'edit_item' => 'Edit',
        'new_item' => 'New',
        'view' => 'View',
        'view_item' => 'View',
        'search_items' => 'Search',
        'not_found' => 'Not found',
        'not_found_in_trash' => 'Not found in Trash'
      ),
      'description' => 'Manages the Open Data Field Guide',
      'supports' => array( 'title', 'editor' ),
      'public' => true,
      'show_ui' => true,
      'show_in_menu' => 'socrata-widgets',
      'rewrite' => array('with_front' => false, 'slug' => 'open-data-field-guide')
    )
  );
}

// TAXONOMIES
add_action( 'init', 'create_guide_taxonomies', 0 );
function create_guide_taxonomies() {
  register_taxonomy(
    'guide_category',
    'guide',
    array (
    'labels' => array(
    'name' => 'Guide Category',
    'add_new_item' => 'Add New Category',
    'new_item_name' => "New Category"
  ),
    'show_ui' => true,
    'show_tagcloud' => false,
    'hierarchical' => true,
    )
  );
}

// Custom Columns for admin management page
add_filter( 'manage_edit-guide_columns', 'guide_columns' ) ;
function guide_columns( $columns ) {
  $columns = array(
    'cb'       => '<input type="checkbox" />',
    'title'    => __( 'Chapter' ),
    'category' => __( 'Category' ),
  );
  return $columns;
}

add_action( 'manage_guide_posts_custom_column', 'guide_custom_columns', 10, 2 );
function guide_custom_columns( $column, $post_id ) {
  global $post;
  switch ($column) {
    case 'category':
      $category = get_the_terms($post->ID , 'guide_category');
      echo $category[0]->name;
      for ($i = 1; $i < count($category); $i++) {echo ', ' . $category[$i]->name ;}
      break;
  }
}

// REGISTER MENUS
add_action( 'init', 'register_odfg_menu' );
function register_odfg_menu() {
  register_nav_menus(
    array(
        'field_guide' => __( 'Field Guide' )
    )
  );
}


// ENQEUE SCRIPTS
function guide_script_loading() {
  if ( 'guide' == get_post_type() && is_single() || 'guide' == get_post_type() && is_archive() || is_page('open-data-field-guide') ) {
    wp_register_style( 'odfg_styles', plugins_url( 'css/styles.css' , __FILE__ ), false, null );
    wp_enqueue_style( 'odfg_styles' );    
  } 
}
add_action('wp_enqueue_scripts', 'guide_script_loading');

// Template Paths
add_filter( 'template_include', 'guide_single_template', 1 );
function guide_single_template( $template_path ) {
  if ( get_post_type() == 'guide' ) {
    if ( is_single() ) {
      // checks if the file exists in the theme first,
      // otherwise serve the file from the plugin
      if ( $theme_file = locate_template( array ( 'single-guide.php' ) ) ) {
        $template_path = $theme_file;
      } else {
        $template_path = plugin_dir_path( __FILE__ ) . 'single-guide.php';
      }
    }
    if ( is_archive() ) {
      // checks if the file exists in the theme first,
      // otherwise serve the file from the plugin
      if ( $theme_file = locate_template( array ( 'archive-guide.php' ) ) ) {
        $template_path = $theme_file;
      } else {
        $template_path = plugin_dir_path( __FILE__ ) . 'archive-guide.php';
      }
    }
  }
  return $template_path;
}

// Custom Body Class
add_action( 'body_class', 'field_guide_body_class');
function field_guide_body_class( $classes ) {
  if ( is_page('open-data-field-guide') || get_post_type() == 'guide' && is_single() )
    $classes[] = 'guide';
  return $classes;
}


// Shortcode [field-guide-posts]
function field_guide_posts($atts, $content = null) {
  ob_start();
  ?>
<section class="section-padding hero-full background-asbestos overlay-midnight-blue odfg-hero">
<div class="vertical-center">
<div class="container">
<div class="row">
<div class="col-sm-10 col-sm-offset-1">
<h1 class="text-center text-reverse margin-bottom-15">Open Data Field Guide</h1>
<h3 class="text-center text-reverse">A comprehensive guide to ensuring your open data program serves you and your citizens.</h3>
<p class="text-center text-reverse">With Insight From: City of Chicago, City of New York, City of Edmonton, State of Maryland, State of Colorado, Code for America, The World Bank, City of Baltimore, State of Oregon, and <a href="/open-data-guide-chapter/acknowledgements-glossary/">more</a>.</p>
<p class="text-center"><a href="#chapters" class="btn btn-lg btn-primary">Explore Now</a></p>
</div>
</div>
</div>
</div>
</section>
<section id="chapters" class="section-padding">
<div class="container">
<div class="row">
<div class="col-sm-8 col-sm-offset-2">
<h2 class="text-center">Chapters</h2>

<?php $query = new WP_Query('post_type=guide&orderby=desc&showposts=40'); ?>
<?php while ($query->have_posts()) : $query->the_post(); ?>
<div><small><?php $guide_meta = get_guide_meta(); echo "$guide_meta[0]"; ?></small></div>
<h4><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
<?php $guide_meta = get_guide_meta(); echo "<p>$guide_meta[1]</p>"; ?>
<hr/>
<?php endwhile;  wp_reset_postdata(); ?>

</div>
</div>
</div>
</section>
  

  <?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
add_shortcode('field-guide-posts', 'field_guide_posts');
