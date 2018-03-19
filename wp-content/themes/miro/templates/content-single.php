<?php 
  $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'square' );
  $url = $thumb['0'];
  $img_id = get_post_thumbnail_id(get_the_ID());
  $alt_text = get_post_meta($img_id , '_wp_attachment_image_alt', true);
?>
<?php while (have_posts()) : the_post(); ?>
<section class="section-padding">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-10 m-auto">
				<h1 class="display-4 mb-3"><?php the_title(); ?></h1>
				<div class="d-flex flex-row align-items-center mb-5">
					<div>
						<?php  global $post;
							$author_id=$post->post_author;
							foreach( get_coauthors() as $coauthor ): echo get_avatar( $coauthor->user_email, '60' ); 
							endforeach;
						?>                	
          </div>
					<div class="pl-2">
						<div><span class="text-muted">Article by</span> <span class="text-medium"><?php echo $coauthor->display_name; ?></span></div>
						<div class="text-muted">Updated <?php the_time('F j, Y g:i a T') ?></div>
					</div>
				</div>
				<div class="mb-5"><?php echo do_shortcode('[addthis]');?></div>
				
			</div>			
		</div>
	</div>
</section>
	<!--<div class="d-none d-lg-block feature-image" style="background-image:url(<?php echo $url;?>);"></div>	
	<section class="section-padding">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12 col-lg-6 ml-lg-auto">
					<div class="pl-md-5 pr-md-5">
						<h1 class="display-4 mb-3"><?php the_title(); ?></h1>
						<div class="d-flex flex-row align-items-center mb-5">
							<div>
								<?php  global $post;
									$author_id=$post->post_author;
									foreach( get_coauthors() as $coauthor ): echo get_avatar( $coauthor->user_email, '60' ); 
									endforeach;
								?>                	
              </div>
							<div class="pl-2">
								<div><span class="text-muted">Article by</span> <span class="text-medium"><?php echo $coauthor->display_name; ?></span></div>
								<div class="text-muted">Updated <?php the_time('F j, Y g:i a T') ?></div>
							</div>
						</div>
						<div class="mb-5"><?php echo do_shortcode('[addthis]');?></div>
						<?php the_content(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>-->
 
<?php endwhile; ?>
