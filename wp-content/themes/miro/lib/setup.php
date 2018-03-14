<?php

namespace Roots\Sage\Setup;

use Roots\Sage\Assets;

/**
 * Theme setup
 */
function setup() {
  // Enable features from Soil when plugin is activated
  // https://roots.io/plugins/soil/
  add_theme_support('soil-clean-up');
  add_theme_support('soil-nav-walker');
  add_theme_support('soil-nice-search');
  add_theme_support('soil-jquery-cdn');
  add_theme_support('soil-relative-urls');

  // Make theme available for translation
  // Community translations can be found at https://github.com/roots/sage-translations
  load_theme_textdomain('sage', get_template_directory() . '/lang');

  // Enable plugins to manage the document title
  // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
  add_theme_support('title-tag');

  // Register wp_nav_menu() menus
  // http://codex.wordpress.org/Function_Reference/register_nav_menus
  register_nav_menus([
    'primary_navigation' => __('Primary Navigation', 'sage')
  ]);

  // Enable post thumbnails
  // http://codex.wordpress.org/Post_Thumbnails
  // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
  // http://codex.wordpress.org/Function_Reference/add_image_size
  add_theme_support('post-thumbnails', array('post','socrata_events','case_study','news','socrata_webinars','guest-author','socrata_downloads'));
  set_post_thumbnail_size( 360, 180, array( 'center', 'center')  );
  add_image_size( 'post-image', 850, 400, array( 'center', 'center'));
  add_image_size( 'square', 1200, 1200, array( 'center', 'center')); 
  add_image_size( 'post-image-small', 360, 200, array( 'center', 'center'));
  add_image_size( 'feature-image', 1600, 912, array( 'center', 'center'));
  add_image_size( 'full-width-ratio', 9999, 100 );
  add_image_size( 'sixteen-nine', 720, 405, array( 'center', 'center'));
  add_image_size( 'sixteen-nine-large', 1600, 900, array( 'center', 'center'));

  // Enable post formats
  // http://codex.wordpress.org/Post_Formats
  add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);

  // Enable HTML5 markup support
  // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
  add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

  // Use main stylesheet for visual editor
  // To add custom styles edit /assets/styles/layouts/_tinymce.scss
  add_editor_style(Assets\asset_path('styles/main.css'));
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

/**
 * Register sidebars
 */
function widgets_init() {
  register_sidebar([
    'name'          => __('Primary', 'sage'),
    'id'            => 'sidebar-primary',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);

  register_sidebar([
    'name'          => __('Footer', 'sage'),
    'id'            => 'sidebar-footer',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);
}
add_action('widgets_init', __NAMESPACE__ . '\\widgets_init');

/**
 * Determine which pages should NOT display the sidebar
 */
function display_sidebar() {
  static $display;

  isset($display) || $display = !in_array(true, [
    // The sidebar will NOT be displayed if ANY of the following return true.
    // @link https://codex.wordpress.org/Conditional_Tags
    is_404(),
    is_front_page(),
    is_home(),
    is_page(),
    is_archive(),
    is_single(),
    is_page_template('template-custom.php'),
    is_page_template('template-fluid.php'),
  ]);

  return apply_filters('sage/display_sidebar', $display);
}

/**
 * Theme assets
 */
function assets() {
  wp_enqueue_style('sage/css', Assets\asset_path('styles/main.css'), '', '4.0.26-beta');
  wp_register_style('google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,900', false, null);
	wp_enqueue_style('google-fonts');
  wp_deregister_script('jquery');
	wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js', array(), null, true);
  wp_enqueue_script('sage/js', Assets\asset_path('scripts/main.js'), ['jquery'], '4.0.18-beta', false);
  wp_enqueue_script('bootstrap/js', Assets\asset_path('scripts/bootstrap.bundle.min.js'), ['sage/js'], null, true);
  wp_register_script('addthis', '//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-553f9bc9354d386b', null, true);
  wp_enqueue_script('addthis');
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets', 100);
