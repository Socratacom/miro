<?php
/*
Plugin Name: Socrata Content Discovery Tool
Plugin URI: http://socrata.com
Description: This plugin adds the ability to search multiple post types for related content.
Version: 1.0
Author: Michael Church
Author URI: http://socrata.com/
License: GPLv2
*/

// Shortcode [content-discovery]
function content_discovery_list($atts, $content = null) {
  ob_start();
  ?>



  <section class="section-padding">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h1 class="margin-bottom-0 font-light">Resources</h1>
          <h3 class="margin-bottom-60">Browse content from our Blog, Case Studies, Newsroom, and more</h3>
        </div>
      </div>
      <div class="row hidden-lg">
        <div class="col-sm-12 margin-bottom-30">
          <div class="padding-15 background-light-grey-4">
            <ul class="filter-bar">
              <li><?php echo facetwp_display( 'facet', 'post_type_dropdown' ); ?></li>
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
            <button type="button" data-toggle="collapse" data-target="#resource">Resource</button>
            <div id="resource" class="collapse in"><?php echo facetwp_display( 'facet', 'post_types' ); ?></div>
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
            <?php echo facetwp_display( 'template', 'content_discovery_tool' ); ?>
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
add_shortcode('content-discovery', 'content_discovery_list');