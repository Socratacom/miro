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
    'title'  => 'Homepage Feature Meta',
    'post_types' => 'homepage_feature',
    'context'    => 'normal',
    'priority'   => 'high',
    'fields' => array(
    	// TEXTAREA
			array(
				'name' => 'Subhead',
				'id'   => "{$prefix}subhead",
				'type' => 'textarea',
				'cols' => 20,
				'rows' => 3,
			),   
      // IMAGE ADVANCED (WP 3.5+)
      array(
        'name' => 'Image',
        'id' => "{$prefix}image",
        'type' => 'image_advanced',
        'max_file_uploads' => 1,
      ),
      // SELECT
			array(
				'name' 	=> 'Overlay Color',
				'id'		=> "{$prefix}overlay_color",
				'type'	=> 'select',
				// Array of 'value' => 'Label' pairs
				'options'         => array(
					'mdc-bg-red-500'					=> 'Red',
					'mdc-bg-pink-500' 				=> 'Pink',
					'mdc-bg-purple-500'       => 'Purple',
					'mdc-bg-deep-purple-500'  => 'Deep Purple',
					'mdc-bg-indigo-500' 			=> 'Indigo',
					'mdc-bg-blue-500'     		=> 'Blue',
					'mdc-bg-light-blue-500'   => 'Light Blue',
					'mdc-bg-cyan-500' 				=> 'Cyan',
					'mdc-bg-teal-500'        	=> 'Teal',
					'mdc-bg-green-500'     		=> 'Green',
					'mdc-bg-amber-500' 				=> 'Amber',
					'mdc-bg-orange-500'     	=> 'Orange',
					'mdc-bg-deep-orange-500'  => 'Deep Orange',					
					'mdc-bg-blue-grey-500'    => 'Blue Grey',
				),
				'multiple'        => false,
				'placeholder'     => 'Select a Color',
				'select_all_none' => false,
			),
      // HEADING
			array(
				'type' => 'heading',
				'name' => 'CTAs',
			),
      // GROUP
			array(
			'id' => "{$prefix}ctas",
			'type' => 'group',
			'clone' => true,
			'sort_clone' => true,
				// Sub-fields
				'fields' => array(			
					// TEXT
					array(
						'name'  => 'Button Text',
						'id'    => "{$prefix}btn_text",
						'type'  => 'text',
					),
					// URL
		      array(
		        'name' => 'URL',
		        'id'   => "{$prefix}url",
		        'type' => 'url',
		      ),
		      // CHECKBOX
		      array(
		        'id'   => "{$prefix}target",
		        'desc' => 'Open in new tab',
		        'type' => 'checkbox',
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
	<section id="slide-content-trigger" class="home-slider">
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
				$images = rwmb_meta( 'homepage_feature_image', 'size=sixteen-nine-large' );
				$ctas = rwmb_meta( 'homepage_feature_ctas' );
				$overlay = rwmb_meta( 'homepage_feature_overlay_color' );
			?>
				<div class="slide">
					<div class="slide-content section-padding" data-aos="fade-up" data-aos-easing="ease-in-out-sine" data-aos-duration="600" data-aos-anchor="#slide-content-trigger" data-aos-anchor-placement="top-top" data-aos-offset="-200">
						<div class="container-fluid">
							<div class="row">
								<div class="col-sm-10 m-sm-auto">
									<div class="row">
										<div class="col-md-10 col-lg-8">
											<h1 class="text-white display-3"><?php echo the_title();?></h1>
											<?php if (!empty($subhead)) echo '<h2 class="text-white mb-4">'.$subhead.'</h2>';?>
											<div class="d-flex">
												<?php foreach ( $ctas as $cta_value ) {
													$btn_text = isset( $cta_value['homepage_feature_btn_text'] ) ? $cta_value['homepage_feature_btn_text'] : '';
													$url = isset( $cta_value['homepage_feature_url'] ) ? $cta_value['homepage_feature_url'] : '';
													$target = isset( $cta_value['homepage_feature_target'] ) ? $cta_value['homepage_feature_target'] : '';
												?>
												<div class="pr-2"><a href="<?php echo $url;?>" <?php if ( !empty ( $target ) ) echo 'target="_blank" rel="noopener noreferrer"';?> class="btn btn-outline-light"><span class="text-white"><?php echo $btn_text;?></span></a></div>
												<?php } ;?>												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="image-overlay <?php echo $overlay;?>"></div>
					<div class="background-image" style="background-image:url(<?php foreach ( $images as $image ) { echo $image['url']; } ?>);"></div>
				</div>

			<?php } wp_reset_postdata(); ?>

		</div>

	</section>

	<script>jQuery(function(e){e(".slider").slick({arrows:!0,appendArrows:e(".home-slider"),prevArrow:'<button class="icon-ui-arrow-left toggle-left btn-circle btn-outline-light" data-aos="fade-zoom-in" data-aos-easing="ease-in-out-sine" data-aos-duration="600" data-aos-anchor="#slide-content-trigger" data-aos-anchor-placement="top-top" data-aos-offset="-200"></button>',nextArrow:'<button class="icon-ui-arrow-right toggle-right btn-circle btn-outline-light" data-aos="fade-zoom-in" data-aos-easing="ease-in-out-sine" data-aos-duration="600" data-aos-anchor="#slide-content-trigger" data-aos-anchor-placement="top-top" data-aos-offset="-200"></button>',dots:!0,autoplay:!0,autoplaySpeed:5e3,speed:500,infinite:!0,fade:!0,cssEase:"linear",pauseOnHover:!0,pauseOnDotsHover:!0}),e(".slider").show()});</script>

  <?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
add_shortcode('homepage-features', 'homepage_features');
