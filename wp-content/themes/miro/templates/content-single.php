<?php 
  $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'sixteen-nine-large' );
  $url = $thumb['0'];
  $img_id = get_post_thumbnail_id(get_the_ID());
  $alt_text = get_post_meta($img_id , '_wp_attachment_image_alt', true);
	$id = get_post_thumbnail_id();
	$src = wp_get_attachment_image_src( $id, 'full' );
	$srcset = wp_get_attachment_image_srcset( $id, 'full' );
	$sizes = wp_get_attachment_image_sizes( $id, 'full' );
	$alt = get_post_meta( $id, '_wp_attachment_image_alt', true);
?>
<?php while (have_posts()) : the_post(); ?>
<section class="py-5">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-10 m-auto">
				<h1 class="display-3 mb-3"  data-aos="fade" data-aos-easing="ease-in-sine" data-aos-duration="600"><?php the_title(); ?></h1>
				<div class="d-flex flex-row align-items-center mb-5" data-aos="fade" data-aos-easing="ease-in-sine" data-aos-duration="600" data-aos-delay="200">
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
				<div class="mb-5" data-aos="fade" data-aos-easing="ease-in-sine" data-aos-duration="600" data-aos-delay="400"><?php echo do_shortcode('[addthis]');?></div>
				<p class="d-none d-lg-block"><a href="#start" class="icon-down-arrow display-4"></a></p>
				
			</div>			
		</div>
	</div>
</section>
<section class="sixteen-nine mdc-bg-blue-grey-500 d-none d-lg-block" style="background-image:url(<?php echo $url;?>); background-repeat:no-repeat; background-position: center; background-size:cover;"></section>

<section id="start" class="py-5">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-10 col-lg-8 m-auto">
				<p class="mb-5 d-block d-lg-none"><img src="<?php echo esc_attr( $id );?>" srcset="<?php echo esc_attr( $srcset ); ?>" sizes="<?php echo esc_attr( $sizes );?>" alt="<?php echo esc_attr( $alt );?>" class="img-fluid" /></p>
				<?php the_content(); ?>
			</div>			
		</div>
	</div>
</section>

<?php echo do_shortcode('[smooth-scrolling]');?>

 
<?php endwhile; ?>
