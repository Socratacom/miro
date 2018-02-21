<?php
/*
Plugin Name: Socrata Leadership
Plugin URI: http://socrata.com/
Description: This plugin manages Executives and Advisors.
Version: 1.0
Author: Michael Church
Author URI: http://socrata.com/
License: GPLv2
*/

add_action( 'init', 'create_socrata_leadership' );
function create_socrata_leadership() {
  register_post_type( 'socrata_leadership',
    array(
      'labels' => array(
        'name' => 'Leadership',
        'singular_name' => 'Leadership',
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
      'description' => 'Manages the leadership team',
      'supports' => array( 'title', 'thumbnail' ),
      'public' => true,
      'show_ui' => true,
      'show_in_menu' => 'socrata-widgets',
      'capabilities' => array(
				'edit_post'          => 'update_core',
				'read_post'          => 'update_core',
				'delete_post'        => 'update_core',
				'edit_posts'         => 'update_core',
				'edit_others_posts'  => 'update_core',
				'delete_posts'       => 'update_core',
				'publish_posts'      => 'update_core',
				'read_private_posts' => 'update_core'
			),
    )
  );
}

// TAXONOMIES
add_action( 'init', 'socrata_leadership_taxonomie', 0 );
function socrata_leadership_taxonomie() {
  register_taxonomy(
    'socrata_leadership_type',
    'socrata_leadership',
    array(
      'labels' => array(
        'name' => 'Type',
        'menu_name' => 'Type',
        'add_new_item' => 'Add New',
        'new_item_name' => "New Type"
      ),
      'show_ui' => true,
      'show_tagcloud' => false,
      'hierarchical' => true,
      'sort' => true,      
      'args' => array( 'orderby' => 'term_order' ),
      'show_admin_column' => true,
      'rewrite' => array('with_front' => false, 'slug' => 'leadership-type'),
    )
  );
}

// CUSTOM EXCERPT
function leadership_excerpt() {
  global $post;
  $text = rwmb_meta( 'leadership_wysiwyg' );
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

// METABOXES
add_filter( 'rwmb_meta_boxes', 'socrata_leadership_register_meta_boxes' );
function socrata_leadership_register_meta_boxes( $meta_boxes )
{
  $prefix = 'leadership_';

  $meta_boxes[] = array(
    'title'         => 'Profile Info',   
    'post_types'    => 'socrata_leadership',
    'context'       => 'normal',
    'priority'      => 'high',

    'fields' => array(
      // TEXT
      array(
        'name'  => esc_html__( 'Title', 'leadership_' ),
        'id'    => "{$prefix}title",
        'type'  => 'text',
      ),
      // URL
      array(
        'name' => esc_html__( 'Twitter', 'leadership_' ),
        'id'   => "{$prefix}twitter",
        'type' => 'url',
      ),
      // URL
      array(
        'name' => esc_html__( 'Linked In', 'leadership_' ),
        'id'   => "{$prefix}linkedin",
        'type' => 'url',
      ),
      // IMAGE ADVANCED (WP 3.5+)
      array(
        'name'              => __( 'Headshot', 'leadership_' ),
        'id'                => "{$prefix}headshot",
        'desc'              => __( 'Minimum size 300x300 pixels.', 'leadership_' ),
        'type'              => 'image_advanced',
        'max_file_uploads'  => 1,
      ),
    ),
  );

  $meta_boxes[] = array(
    'title'         => 'Bio',   
    'post_types'    => 'socrata_leadership',
    'context'       => 'normal',
    'priority'      => 'high',
    'fields' => array(
      // WYSIWYG/RICH TEXT EDITOR
      array(
        'id'      => "{$prefix}wysiwyg",
        'type'    => 'wysiwyg',
        // Set the 'raw' parameter to TRUE to prevent data being passed through wpautop() on save
        'raw'     => false,
        // Editor settings, see wp_editor() function: look4wp.com/wp_editor
        'options' => array(
          'textarea_rows' => 15,
          'teeny'         => false,
          'media_buttons' => false,
        ),
      ),
    ),
  );

  return $meta_boxes;
}


// Shortcode [leadership type=”SLUG”]

function leadership_profiles($atts, $content = null) {
  extract( shortcode_atts( array(
    'type' => '',
    ), $atts ) );
    ob_start();
    $args = array(
			'post_type' => 'socrata_leadership',
			'socrata_leadership_type' => $type,
			'posts_per_page' => 100,
			'orderby' => 'date',
			'order'   => 'asc',
  );
  
	$myquery = new WP_Query( $args );

	// The Loop
  while ( $myquery->have_posts() ) { $myquery->the_post(); 
  $title = rwmb_meta( 'leadership_title' );
  $twitter = rwmb_meta( 'leadership_twitter' );
  $linkedin = rwmb_meta( 'leadership_linkedin' );
  $headshot = rwmb_meta( 'leadership_headshot', 'size=medium' );
  $bio = rwmb_meta( 'leadership_wysiwyg' );
  $id = get_the_ID();
  ?>

  <div class="col-xs-6 col-sm-3 col-md-2">
  	<div class="profile text-center margin-bottom-30 match-height">
  		<div class="headshot margin-bottom-15" style="background-image:url(<?php foreach ( $headshot as $image ) { echo $image['url']; } ?>);">
  			<a href="#" data-toggle="modal" data-target="#<?php echo $id;?>" class="link"></a>
        </div>
       <h6 class="name margin-bottom-5"><?php the_title(); ?></h6>
       <div class="title"><?php echo $title; ?></div>
       <?php if ( ! empty( $linkedin ) ) { ?> <a href="<?php echo $linkedin; ?>" target="_blank" style="padding:0 5px; position:relative; height:auto; width:auto;"><i class="fa fa-linkedin"></i></a> <?php }; ?><?php if ( ! empty( $twitter ) ) { ?> <a href="<?php echo $twitter; ?>" target="_blank" style="padding:0 5px; position:relative; height:auto; width:auto;"><i class="fa fa-twitter"></i></a> <?php }; ?>
  	</div>
  </div>

	<div id="<?php echo $id;?>" class="modal leadership-modal" tabindex="-1" role="dialog" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	    	<button type="button" data-dismiss="modal"><i class="icon-close"></i></button>
	      <div class="modal-body">
	      	<div class="padding-30 bio">
	      		<div class="text-center margin-bottom-30">
		      		<div class="headshot margin-bottom-15" style="background-image:url(<?php foreach ( $headshot as $image ) { echo $image['url']; } ?>)"></div>
		      		<h5 class="margin-bottom-5"><?php the_title(); ?></h5>
		      		<div class="margin-bottom-5"><?php echo $title; ?></div>
		      		<?php if ( ! empty( $linkedin ) ) { ?> <a href="<?php echo $linkedin; ?>" target="_blank" style="padding:0 5px; position:relative; height:auto; width:auto; font-size:16px;"><i class="fa fa-linkedin"></i></a> <?php }; ?><?php if ( ! empty( $twitter ) ) { ?> <a href="<?php echo $twitter; ?>" target="_blank" style="padding:0 5px; position:relative; height:auto; width:auto; font-size:16px;"><i class="fa fa-twitter"></i></a> <?php }; ?>
		      		</div>
	        	<?php echo $bio; ?>
	      	</div>
	      </div>
	    </div>
	  </div>
	</div>









  <?php } wp_reset_postdata(); ?>

  <?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
add_shortcode('leadership', 'leadership_profiles');


