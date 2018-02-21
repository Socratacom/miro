<section class="background-primary-light" style="padding:30px 0;">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="margin-bottom-0 color-primary-alt-2-dark"><?php single_cat_title(); ?></h3>
			</div>
		</div>
	</div>
</section>
<section class="section-padding">
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
				<?php
				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				echo do_shortcode('[ajax_load_more repeater="template_1" post_type="news" taxonomy="news_category" taxonomy_terms="'.$term->slug.'" container_type="ul" css_classes="no-bullets news-list"]'); ?>
			</div>
			<div class="col-sm-4">
		        <?php
		          //list terms in a given taxonomy using wp_list_categories  (also useful as a widget)
		          $orderby = 'name';
		          $show_count = 0; // 1 for yes, 0 for no
		          $pad_counts = 0; // 1 for yes, 0 for no
		          $hide_empty = 1;
		          $hierarchical = 1; // 1 for yes, 0 for no
		          $taxonomy = 'news_category';
		          $title = 'Newsroom Categories';

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
</section>