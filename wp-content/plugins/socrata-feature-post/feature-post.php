<?php
/*
Plugin Name: Socrata Feature Posts
Plugin URI: http://socrata.com/
Description: Adds feature post to editorial content.
Version: 1.0
Author: Michael Church
Author URI: http://socrata.com/
License: GPLv2
*/

// Metabox
add_filter( 'rwmb_meta_boxes', 'socrata_feature_post_meta_boxes' );
function socrata_feature_post_meta_boxes( $meta_boxes )
{
  $prefix = 'socrata_feature_';
  
  $meta_boxes[] = array(
    'title'  => __( 'Feature Post', 'socrata_hidden_' ),
    'post_types' => array('post','case_study','socrata_videos'),
    'context'    => 'side',
    'priority'   => 'high',
    'fields' => array(
      // CHECKBOX
      array(
        'id'   => "{$prefix}post",
        'desc' => 'Feature this post',
        'type' => 'checkbox',
        // Value can be 0 or 1
        'std'  => 0,
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
    )
  );

  return $meta_boxes;
}

// Featue post [feature-post type="POST TYPE" ]
function feature_post_query($atts, $content = null) {
  extract( shortcode_atts( array(
    'type' => '',
    ), $atts ) );
    ob_start();
    $today = strtotime('today UTC');
    $args = array(
    'post_type' => 'post',
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'desc',
		'posts_per_page' => 1,
		'meta_query' => array(
			array(
				'key' => 'socrata_feature_post',
				'value' => 1,
				'compare' => '='
			)
		)
  );
  
$query = new WP_Query($args);
if($query->have_posts()) : while($query->have_posts()) : $query->the_post();
	$overlay = rwmb_meta( 'socrata_feature_overlay_color' );
	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'sixteen-nine-large' );
  $url = $thumb['0'];
?>


<section class="page-hero">
<div class="hero-content" data-aos="f-out" data-aos-anchor="#trigger-menu" data-aos-anchor-placement="top-top">
<div class="container-fluid">
<div class="row">
<div class="col-sm-12 col-md-8 m-auto text-center">
<h1 class="display-4 text-white mt-5 pt-lg-5 mb-4" data-aos="fade" data-aos-easing="ease-in-sine" data-aos-duration="600"><?php echo the_title();?></h1>
<?php if (is_home()) { ?><p data-aos="fade" data-aos-easing="ease-in-sine" data-aos-duration="600" data-aos-delay="200"><a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-light">Read Article</a></p><?php } ?>
</div>
</div>
</div>
</div>
<div class="explore-more text-center text-white d-none d-md-block" data-aos="f-out" data-aos-anchor="#trigger-menu" data-aos-anchor-placement="top-top">
<p>Explore more</p>
<p class="mb-0"><i class="icon-down-arrow"></i></p>
</div>
<div class="hero-overlay <?php echo $overlay; ?>" data-aos="f-out" data-aos-anchor="#trigger-menu" data-aos-anchor-placement="top-top"></div>
<div class="hero-image" style="background-image:url(<?php echo $url;?>);" data-aos="black-white" data-aos-anchor="#trigger-menu" data-aos-anchor-placement="top-top"></div>
</section>



<?php endwhile; ?>
<?php endif; ?>

<?php

  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
add_shortcode('feature-post', 'feature_post_query');