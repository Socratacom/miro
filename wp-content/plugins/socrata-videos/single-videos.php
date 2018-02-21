<section class="background-black">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="embed-responsive embed-responsive-16by9">
            <iframe src="https://www.youtube.com/embed/<?php echo rwmb_meta( 'socrata_videos_id' ); ?>?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
        </div>        
      </div>
    </div>
  </div>
</section>
<section class="section-padding">
  <div class="container">
    <div class="row">
      <div class="col-sm-8">
        <h1 class="margin-bottom-15"><?php the_title(); ?></h1>
        <div class="margin-bottom-30"><?php echo do_shortcode('[addthis]');?></div>
        <?php echo rwmb_meta( 'editorField' ); ?>
      </div>
      <div class="col-sm-4 hidden-xs">
        <?php echo do_shortcode('[newsletter-sidebar]'); ?>   
      </div>
    </div>
  </div>
</section>