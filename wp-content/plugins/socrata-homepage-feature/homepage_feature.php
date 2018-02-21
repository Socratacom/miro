<?php
/*
Plugin Name: Socrata Homepage Feature
Plugin URI: http://socrata.com/
Description: This plugin manages Homepage hero banners.
Version: 1.0
Author: Michael Church
Author URI: http://Socrata.com/
License: GPLv2
*/

// REGISTER POST TYPE
add_action( 'init', 'homepage_feature_post_type' );

function homepage_feature_post_type() {
  register_post_type( 'homepage_feature',
    array(
      'labels' => array(
        'name' => 'Homepage Feature',
        'singular_name' => 'Homepage Feature',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New',
        'edit' => 'Edit',
        'edit_item' => 'Edit',
        'new_item' => 'New Homepage Feature',
        'view' => 'View',
        'view_item' => 'View',
        'search_items' => 'Search',
        'not_found' => 'Not found',
        'not_found_in_trash' => 'Not found in Trash',
        'parent' => 'Parent Homepage Feature'
      ),
      'description' => 'Adds hero banner images to the homepage',
      'supports' => array( 'title' ),
      'public' => false,
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

// Metabox
add_filter( 'rwmb_meta_boxes', 'homepage_feature_register_meta_boxes' );
function homepage_feature_register_meta_boxes( $meta_boxes )
{
  $prefix = 'homepage_feature_';

  $meta_boxes[] = array(
    'title'  => __( 'Homepage Feature Meta', 'homepage_feature_' ),
    'post_types' => array( 'homepage_feature' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'fields' => array(
    	// TEXTAREA
			array(
				'name' => esc_html__( 'Subhead', 'homepage_feature_'  ),
				'id'   => "{$prefix}subhead",
				'type' => 'textarea',
				'cols' => 20,
				'rows' => 3,
			),   
      // IMAGE ADVANCED (WP 3.5+)
      array(
        'name'             => __( 'Image', 'homepage_feature_' ),
        'id'               => "{$prefix}image",
        'desc'              => __( 'Image size: 1900 x 720', 'homepage_feature_' ),
        'type'             => 'image_advanced',
        'max_file_uploads' => 1,
      ),
      // HEADING
			array(
				'type' => 'heading',
				'name' => esc_html__( 'CTAs', 'homepage_feature_' ),
			),
      // GROUP
			array(
			'id'     => "{$prefix}ctas",
			'type'   => 'group',
			'clone'  => true,
			'sort_clone' => true,
				// Sub-fields
				'fields' => array(			
					// TEXT
					array(
						'name'  => __( 'Button Text', 'homepage_feature_' ),
						'id'    => "{$prefix}btn_text",
						'type'  => 'text',
					),
					// URL
		      array(
		        'name' => __( 'URL', 'homepage_feature_' ),
		        'id'   => "{$prefix}url",
		        'type' => 'url',
		      ),
		      // CHECKBOX
		      array(
		        'id'   => "{$prefix}target",
		        'desc' => __( 'Open in new tab', 'homepage_feature_' ),
		        'type' => 'checkbox',
		        // Value can be 0 or 1
		        'std'  => 0,
		      ),
				),
			),
    ),
  );

  return $meta_boxes;
}


// Shortcode [homepage-features]
function homepage_features($atts, $content = null) {
  ob_start();

  ?>
	<section class="home-masthead">
		<div class="slider">
			<?php
				$args = array(
				'post_type' => 'homepage_feature',
				'posts_per_page' => 3,
				'orderby' => 'date'
				);
				$myquery = new WP_Query($args);
				// The Loop
				while ( $myquery->have_posts() ) { $myquery->the_post(); 
				$subhead = rwmb_meta( 'homepage_feature_subhead' );
				$images = rwmb_meta( 'homepage_feature_image', 'size=full' );
				$ctas = rwmb_meta( 'homepage_feature_ctas' );
			?>
			<div class="slide" style="background-image:url(<?php foreach ( $images as $image ) { echo $image['url']; } ?>);">
				<div class="text">
					<div class="vertical-center">
						<div class="container">
							<div class="row">
								<div class="col-sm-8 col-md-6">
									<h1 class="color-white margin-bottom-15"><?php the_title(); ?></h1>
									<h4 class="color-white text-normal hidden-xs margin-bottom-0"><?php echo $subhead;?></h4>
									<?php if ( ! empty( $ctas ) ) { ?>
									<ul class="cta-list">
										<?php foreach ( $ctas as $cta_value ) {
											$btn_text = isset( $cta_value['homepage_feature_btn_text'] ) ? $cta_value['homepage_feature_btn_text'] : '';
											$url = isset( $cta_value['homepage_feature_url'] ) ? $cta_value['homepage_feature_url'] : '';
											$target = isset( $cta_value['homepage_feature_target'] ) ? $cta_value['homepage_feature_target'] : '';
										?>
										<li><a href="<?php echo $url;?>" <?php if ( !empty ( $target ) ) echo 'target="_blank"';?> class="btn btn-primary outline-white"><?php echo $btn_text;?> <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></a></li>
										<?php } ;?>
									</ul>
									<?php } ;?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } wp_reset_postdata(); ?>
		</div>
	</section>
  <script>jQuery(function(e){e(".slider").slick({arrows:!1,dots:!0,autoplay:!0,autoplaySpeed:5e3,speed:500,infinite:!0,fade:!0,cssEase:"linear",pauseOnHover:!0,pauseOnDotsHover:!0}),e(".slider").show()});</script>
  <?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
add_shortcode('homepage-features', 'homepage_features');
