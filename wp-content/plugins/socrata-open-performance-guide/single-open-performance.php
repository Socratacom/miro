<section class="section-padding hero-opg-single img-background" style="background-image:url(/wp-content/uploads/opg-hero.jpg);">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-8 col-lg-6">
        <h1 class="margin-bottom-15"><?php the_title(); ?></h1>
        <div class="margin-bottom-30"><?php echo do_shortcode('[addthis]');?></div>
      </div>
    </div>
  </div>
  <div class="bar"></div>
</section>
<?php the_content(); ?>
<section class="background-peter-river opg-nav">
  <div class="container">
    <div class="row no-gutters">
      <div class="col-sm-6">
        

            <?php if(get_adjacent_post(false, '', true)) { }
            else { ?>
            <a href="/open-performance-guide" class="previous-post-button">Open Performance Guide Intro</a>
            <?php
            } ; ?>

          <?php previous_post_link( '%link', '%title', TRUE, '', 'socrata_opg_cat' ); ?>
      
      </div>
      <div class="col-sm-6">      
          <?php next_post_link( '%link', '%title', TRUE, '', 'socrata_opg_cat' ); ?>
      </div>
    </div>
  </div>
</section>