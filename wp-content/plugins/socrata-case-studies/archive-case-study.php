<div class="container page-padding">
	<div class="row">
		<div class="col-sm-9">
			<div class="row">
				<div class="col-sm-12">
					<h3 class="archive-title"><a href="/case-studies/">Case Studies</a>: <?php single_cat_title(); ?></h3>
					<hr/>
				</div>

				<?php while ( have_posts() ) : the_post();  ?>
				<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'post-thumbnail' ); $url = $thumb['0']; ?>
				<div class="col-sm-6 col-lg-4">
					<div class="card">
						<div class="card-image hidden-xs">
							<?php if ( has_post_thumbnail() ) { ?>
								<img src="<?=$url?>" class="img-responsive">
							<?php
							} else { ?>
								<img src="/wp-content/uploads/no-image.png" class="img-responsive">
							<?php
							}
							?>							
							<a href="<?php the_permalink() ?>"></a>
						</div>
						<div class="card-text truncate">
		                  <p class="categories"><small><?php case_study_the_categories(); ?><small></p>
		                  <h4><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
		                  <?php the_excerpt(); ?> 
		                </div>
					</div>
				</div>

				<?php endwhile; ?>

				<?php if (function_exists("pagination")) {pagination($additional_loop->max_num_pages);} ?>

			</div>
		</div>
      <div class="col-sm-3">
        <?php
          //list terms in a given taxonomy using wp_list_categories  (also useful as a widget)
          $orderby = 'name';
          $show_count = 0; // 1 for yes, 0 for no
          $pad_counts = 0; // 1 for yes, 0 for no
          $hide_empty = 1;
          $hierarchical = 1; // 1 for yes, 0 for no
          $taxonomy = 'case_study_segment';
          $title = 'Segment';

          $args = array(
            'orderby' => $orderby,
            'show_count' => $show_count,
            'pad_counts' => $pad_counts,
            'hide_empty' => $hide_empty,
            'hierarchical' => $hierarchical,
            'taxonomy' => $taxonomy,
            'title_li' => '<h5>'. $title .'</h5>'
          );
        ?>
        <ul class="category-nav">
          <?php wp_list_categories($args); ?>
        </ul>
        <?php
          //list terms in a given taxonomy using wp_list_categories  (also useful as a widget)
          $orderby = 'name';
          $show_count = 0; // 1 for yes, 0 for no
          $pad_counts = 0; // 1 for yes, 0 for no
          $hide_empty = 1;
          $hierarchical = 1; // 1 for yes, 0 for no
          $taxonomy = 'case_study_product';
          $title = 'Product';

          $args = array(
            'orderby' => $orderby,
            'show_count' => $show_count,
            'pad_counts' => $pad_counts,
            'hide_empty' => $hide_empty,
            'hierarchical' => $hierarchical,
            'taxonomy' => $taxonomy,
            'title_li' => '<h5>'. $title .'</h5>'
          );
        ?>
        <ul class="category-nav">
          <?php wp_list_categories($args); ?>
        </ul>
        <?php echo do_shortcode('[newsletter-sidebar]'); ?>
      </div>

	</div>
</div>