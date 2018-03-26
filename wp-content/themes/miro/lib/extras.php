<?php

namespace Roots\Sage\Extras;
use Roots\Sage\Setup;

/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  // Add class if sidebar is active
  if (Setup\display_sidebar()) {
    $classes[] = 'sidebar-primary';
  }

  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');

// SHARED TAXONOMIES
add_action( 'init', __NAMESPACE__ . '\\shared_solution', 0 );
function shared_solution() {
  register_taxonomy(
    'solution',
    array('case_study','socrata_videos','socrata_downloads','socrata_webinars','post','news','socrata_logos','socrata_events'),
    array(
      'labels' => array(
        'name' => 'Solution',
        'menu_name' => 'Solution',
        'add_new_item' => 'Add New Solution',
        'new_item_name' => "New Solution"
      ),
      'show_ui' => true,
      'show_in_menu' => false,
      'show_tagcloud' => false,
      'hierarchical' => true,
      'sort' => true,      
      'args' => array( 'orderby' => 'term_order' ),
      'show_admin_column' => false,
      'capabilities'=>array(
        'manage_terms' => 'manage_options',//or some other capability your clients don't have
        'edit_terms' => 'manage_options',
        'delete_terms' => 'manage_options',
        'assign_terms' =>'edit_posts'),
      'rewrite' => array('with_front' => false, 'slug' => 'solution'),
    )
  );
}

add_action( 'init', __NAMESPACE__ . '\\shared_segment', 0 );
function shared_segment() {
  register_taxonomy(
    'segment',
    array('od_directory','case_study','socrata_videos','socrata_downloads','socrata_webinars','post','news','socrata_logos','socrata_events'),
    array(
      'labels' => array(
        'name' => 'Segment',
        'menu_name' => 'Segment',
        'add_new_item' => 'Add New Segment',
        'new_item_name' => "New Segment"
      ),
      'show_ui' => true,
      'show_in_menu' => false,
      'show_tagcloud' => false,
      'hierarchical' => true,
      'sort' => true,      
      'args' => array( 'orderby' => 'term_order' ),
      'show_admin_column' => false,
      'capabilities'=>array(
        'manage_terms' => 'manage_options',//or some other capability your clients don't have
        'edit_terms' => 'manage_options',
        'delete_terms' => 'manage_options',
        'assign_terms' =>'edit_posts'),
      'rewrite' => array('with_front' => false, 'slug' => 'segment'),
    )
  );
}

add_action( 'init', __NAMESPACE__ . '\\shared_product', 0 );
function shared_product() {
  register_taxonomy(
    'product',
    array('case_study','socrata_videos','socrata_downloads','socrata_webinars','post','news','socrata_logos'),
    array(
      'labels' => array(
        'name' => 'Product',
        'menu_name' => 'Product',
        'add_new_item' => 'Add New Product',
        'new_item_name' => "New Product"
      ),
      'show_ui' => true,
      'show_in_menu' => false,
      'show_tagcloud' => false,
      'hierarchical' => true,
      'sort' => true,      
      'args' => array( 'orderby' => 'term_order' ),
      'show_admin_column' => false,
      'capabilities'=>array(
        'manage_terms' => 'manage_options',//or some other capability your clients don't have
        'edit_terms' => 'manage_options',
        'delete_terms' => 'manage_options',
        'assign_terms' =>'edit_posts'),
      'rewrite' => array('with_front' => false, 'slug' => 'product'),
    )
  );
}

// Function to change "posts" to "blog" in the admin side menu
function change_post_menu_label() {
  global $menu;
  global $submenu;
  $menu[5][0] = 'Blog';
  $submenu['edit.php'][5][0] = 'Blog';
  $submenu['edit.php'][10][0] = 'Add Post';
  $submenu['edit.php'][16][0] = 'Tags';
  echo '';
}
add_action( 'admin_menu', __NAMESPACE__ . '\\change_post_menu_label' );

// Function to change post object labels to "blog"
function change_post_object_label() {
  global $wp_post_types;
  $labels = &$wp_post_types['post']->labels;
  $labels->name = 'Blog';
  $labels->singular_name = 'Blog';
  $labels->add_new = 'Add Post';
  $labels->add_new_item = 'Add Post';
  $labels->edit_item = 'Edit Post';
  $labels->new_item = 'Post';
  $labels->view_item = 'View Post';
  $labels->search_items = 'Search Posts';
  $labels->not_found = 'No Posts found';
  $labels->not_found_in_trash = 'No Posts found in Trash';
}
add_action( 'init', __NAMESPACE__ . '\\change_post_object_label' );

// Remove metaboxes from the blog
function remove_blog_meta_boxes() {
  remove_meta_box( 'tagsdiv-post_tag', 'post', 'normal' );
  remove_meta_box( 'formatdiv', 'post', 'normal' );
}
add_action( 'admin_menu', __NAMESPACE__ . '\\remove_blog_meta_boxes' );

// Custom Socrata Admin Menus 
function editorial_menu() {
	add_menu_page(
		'Editorial Content',
		'Editorial Content',
		'read',
		'editorial-content',
		'',
		'dashicons-edit',
		5
	);
} 
add_action( 'admin_menu', __NAMESPACE__ . '\\editorial_menu' );

function socrata_widget_menu() {
	add_menu_page(
		'Socrata Widgets',
		'Socrata Widgets',
		'read',
		'socrata-widgets',
		'',
		'dashicons-admin-generic',
		75
	);
} 
add_action( 'admin_menu', __NAMESPACE__ . '\\socrata_widget_menu' );

// Custom Admin Role
add_role( 'marketing', __( 'Marketing' ),
	array(
		'delete_others_pages' => false,
		'delete_others_posts' => true,
		'delete_pages' => false,
		'delete_posts' => true,
		'delete_private_pages' => false,
		'delete_private_posts' => true,
		'delete_published_pages' => false,
		'delete_published_posts' => true,
		'edit_others_pages' => false,
		'edit_others_posts' => true,
		'edit_pages' => false,
		'edit_posts' => true,
		'edit_private_pages' => false,
		'edit_private_posts' => true,
		'edit_published_pages' => false,
		'edit_published_posts' => true,
		'manage_categories' => true,
		'manage_links' => true,
		'moderate_comments' => true,
		'publish_pages' => false,
		'publish_posts' => true,
		'read' => true,
		'read_private_pages' => false,
		'read_private_posts' => true,
		'upload_files' => true,
	)
);

// Remove Menu Selections for Marketing Role
function remove_admin_bar_links() {
	global $wp_admin_bar, $current_user;
	$user = wp_get_current_user();
	if ( in_array( 'marketing', (array) $user->roles ) ) {
		$wp_admin_bar->remove_menu('updates');          // Remove the updates link
		$wp_admin_bar->remove_menu('comments');         // Remove the comments link
		$wp_admin_bar->remove_menu('new-content');      // Remove the content link
		$wp_admin_bar->remove_menu('wp-logo');          // Remove the WP Logo link
		$wp_admin_bar->remove_menu('wpseo-menu');       // Remove the Yoast SEO menu
	}
}
add_action( 'wp_before_admin_bar_render', __NAMESPACE__ . '\\remove_admin_bar_links' );

function remove_admin_menu() {
	$user = wp_get_current_user();
		if ( in_array( 'marketing', (array) $user->roles ) ) {
		remove_menu_page( 'edit-comments.php' );        // Comments
		remove_menu_page('tools.php');                  // Tools
	}
}
add_action( 'admin_menu', __NAMESPACE__ . '\\remove_admin_menu', 999 );

// Force Embeded Post Images to Use <FIGURE>    
function fb_unautop_4_img( $content )
{ 
	$content = preg_replace( 
		'/<p>\\s*?(<a rel=\"attachment.*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s', 
		'<figure>$1</figure>', 
		$content 
	); 
	return $content; 
} 
add_filter( 'the_content', __NAMESPACE__ . '\\fb_unautop_4_img', 99 );

//Add Responsive Class to Post Images
function WPTime_add_custom_class_to_all_images($content){
	$my_custom_class = "img-fluid";
	$add_class = str_replace('<img class="', '<img class="'.$my_custom_class.' ', $content);
	return $add_class;
}
add_filter('the_content', __NAMESPACE__ . '\\WPTime_add_custom_class_to_all_images');

//Add Custom Image Sizes to Media Uploader
function my_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'sixteen-nine' => __( 'Sixteen Nine' ),
        'sixteen-nine-large' => __( 'Sixteen Nine Large' ),
    ) );
}
add_filter( 'image_size_names_choose', __NAMESPACE__ . '\\my_custom_image_sizes' );


// SHORT CODES

/**
 * Addthis Sharing
 */
function addthis_sharing ($atts, $content = null) {
  ob_start();
  ?>
  <div class="addthis_inline_share_toolbox"></div>
  <?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
add_shortcode('addthis', __NAMESPACE__ . '\\addthis_sharing');

/**
 * Smooth Scrolling
 */
function smooth_scrolling ($atts, $content = null) {
  ob_start();
  ?>
	<script>
	//Smooth Jumplink Scrolling
	var target, scroll;
	$("a[href*=\\#]:not([href=\\#])").on("click", function(e) {
	  if (location.pathname.replace(/^\//,'') === this.pathname.replace(/^\//,'') && location.hostname === this.hostname) {
	  target = $(this.hash);
	  target = target.length ? target : $("[id=" + this.hash.slice(1) + "]");

	  if (target.length) {
	  if (typeof document.body.style.transitionProperty === 'string') {
	  e.preventDefault();

	  var avail = $(document).height() - $(window).height();

	  scroll = target.offset().top - 60;

	  if (scroll > avail) {
	  scroll = avail;
	}
	$("html").css({
	  "margin-top" : ( $(window).scrollTop() - scroll ) + "px",
	  "transition" : "1s ease-in-out"
	  }).data("transitioning", true);
	  } else {
	    $("html, body").animate({
	      scrollTop: scroll
	      }, 1000);
	      return;
	      }
	    }
	  }
	});
	$("html").on("transitionend webkitTransitionEnd msTransitionEnd oTransitionEnd", function (e) {
	  if (e.target === e.currentTarget && $(this).data("transitioning") === true) {
	    $(this).removeAttr("style").data("transitioning", false);
	    $("html, body").scrollTop(scroll);
	    return;
	  }
	});
	</script>
  <?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
add_shortcode('smooth-scrolling', __NAMESPACE__ . '\\smooth_scrolling');