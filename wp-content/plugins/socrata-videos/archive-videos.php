<div class="container page-padding">
  <div class="row">
    <div class="col-sm-12">
      <h3 class="margin-bottom-15"><a href="/videos">Videos</a>: <?php single_cat_title(); ?></h3>          
    </div>
  </div>
  <hr/>
  <div class="row">        

        <?php while ( have_posts() ) : the_post();  ?>

        <div class="col-sm-6 col-lg-3">
        <article class="card-depriciated card-video">
        <div class="card-image">
        <img src="https://img.youtube.com/vi/<?php $meta = get_socrata_videos_meta(); echo $meta[1]; ?>/mqdefault.jpg" class="img-responsive">
        <a class="link" href="<?php the_permalink() ?>"></a>
        </div>
        <div class="card-text truncate">
        <p class="categories"><?php videos_the_categories(); ?></p>
        <h4><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
        <?php $meta = get_socrata_videos_meta(); if ($meta[2]) {echo "$meta[2]";} ?>
        </div>
        </article>
        </div>

        <?php endwhile; ?>

        <?php if (function_exists("pagination")) {pagination($additional_loop->max_num_pages);} ?>

  </div>
</div>

