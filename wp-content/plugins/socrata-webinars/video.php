<?php 
$today = strtotime('today UTC');
$date = strtotime(rwmb_meta( 'webinars_starttime' ));
$video = rwmb_meta( 'webinars_video' );
$slides = rwmb_meta( 'webinars_asset_slide_deck' );
$speakers = rwmb_meta( 'webinars_speakers' );
?>

<?php 
	if($date > $today) { ?>
		<section class="section-padding">
			<div class="container">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="alert alert-info"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>This on demand webinar is not yet available</strong>. Register for the <a href="<?php the_permalink() ?>"><?php the_title(); ?></a> webinar or <a href="/webinars">view all webinars</a>.</div>
					</div>
				</div>
			</div>
		</section>
	<?php } 
;?>

<?php 
	if($date <= $today) { ?>

		<?php if ( ! empty( $video ) ) { ?>
				<section class="background-black">
				  <div class="container">
				    <div class="row">
				      <div class="col-sm-12">
						<div class="embed-responsive embed-responsive-16by9"> 
						  <iframe src="//fast.wistia.net/embed/iframe/<?php echo $video;?>?seo=false" allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen></iframe>
						</div>        
				      </div>
				    </div>
				  </div>
				</section>
				<script src="//fast.wistia.net/assets/external/E-v1.js" async></script>
				<section class="section-padding">
					<div class="container">
						<div class="row">
							<div class="col-sm-8">
								<h1 class="font-light"><?php the_title(); ?></h1>
								<?php echo rwmb_meta( 'webinars_wysiwyg' );?>

								<?php if ( ! empty( $speakers ) ) { ?>
								<hr>
								<h3>Speakers</h3>
								<?php foreach ( $speakers as $speaker_value ) {
								$id = uniqid();
								$name = isset( $speaker_value['webinars_speaker_name'] ) ? $speaker_value['webinars_speaker_name'] : '';
								$title = isset( $speaker_value['webinars_speaker_title'] ) ? $speaker_value['webinars_speaker_title'] : '';
								$images = isset( $speaker_value['webinars_speaker_headshot'] ) ? $speaker_value['webinars_speaker_headshot'] : array();
								foreach ( $images as $image ) {
									$image = RWMB_Image_Field::file_info( $image, array( 'size' => 'thumbnail' ) );
								} 
								?>

								<div class="col-sm-3">
									<div class="match-height margin-bottom-30">
										<div class="text-center margin-bottom-15">
											<?php if (! empty( $images )) echo "<img src='$image[url]' class='img-responsive img-circle'>"; else echo "<img src='/wp-content/uploads/no-picture-100x100.png' class='img-responsive img-circle'>";?>
										</div>
										<h5 class="text-center margin-bottom-0"><?php echo $name;?></h5>
										<div class="text-center margin-bottom-0"><small><i><?php echo $title;?></i></small></div>
									</div>
								</div>


								<?php } ;?>					





									<?php }
								;?>
							</div>
							<div class="col-sm-4 hidden-xs">
								<?php if ( ! empty( $slides ) ) { ?> 
									<p><a href="<?php echo $slides;?>" target="_blank" class="btn btn-primary btn-block btn-lg"><i class="fa fa-download" aria-hidden="true"></i> Download Slides</a></p>
									<?php }
								;?>
								<?php echo do_shortcode('[newsletter-sidebar]'); ?>   
							</div>
						</div>
					</div>
				</section>

				<?php 
				$custom_taxterms = wp_get_object_terms( $post->ID, 'solution', array('fields' => 'ids') );
				$args = array(
				'post_type' => 'socrata_webinars',
				'post_status' => 'publish',
				'posts_per_page' => 3, // you may edit this number
				'orderby' => 'rand',
				'tax_query' => array(
				    array(
				        'taxonomy' => 'solution',
				        'field' => 'id',
				        'terms' => $custom_taxterms
				    )
				),
				'post__not_in' => array ($post->ID),
				);
				$related_items = new WP_Query( $args );
				// loop over query
				if ($related_items->have_posts()) : { ?>
				<section class="section-padding background-light-grey-4">
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						<h2 class="text-center margin-bottom-60">Related webinars</h2>
					</div>
				</div>
				<div class="row row-centered">
					<?php
					}
					while ( $related_items->have_posts() ) : $related_items->the_post();
					$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'post-image-small' );
					$url = $thumb['0'];
					$today = strtotime('today UTC');
					$date = strtotime(rwmb_meta( 'webinars_starttime' ));
					?>				   
					  <div class="col-md-4 col-centered">
					    <div class="thumbnail">
				          <?php
				            if ( ! empty( $thumb ) ) { ?>
				              <a href="<?php the_permalink() ?>"><img src="<?php echo $url;?>" class="img-responsive" /></a>
				              <?php
				            }     
				            else { ?>
				              <a href="<?php the_permalink() ?>"><img src="/wp-content/uploads/no-image.png" class="img-responsive" /></a>
				              <?php
				            }
				          ?>
					      <div class="caption">
					      	<?php if($date > $today) echo "<p class='margin-bottom-0 color-secondary text-uppercase text-semi-bold'><small>Upcoming Webinar</small></p>" ;?>
					      	<?php if($date <= $today) echo "<p class='margin-bottom-0 color-secondary text-uppercase text-semi-bold'><small>On Demand Webinar</small></p>" ;?>
					        <h4 class="margin-bottom-15"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="link-black"><?php the_title(); ?></a></h4>
					        <p class="margin-bottom-0"><a href="<?php the_permalink(); ?>">Learn more <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></a></p>
					      </div>
					    </div>
					  </div>
						<?php
						endwhile; { ?>			
					</div>
				</div>
			</div> 
		</section>
				<?php 
				}
				endif;
				// Reset Post Data
				wp_reset_postdata(); ?>
			<?php }
			else { ?>
				<section class="section-padding">
					<div class="container">
						<div class="row">
							<div class="col-sm-10 col-sm-offset-1">
								<div class="alert alert-info"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>We are currently processing this webinar</strong>. Please check back later.</div>
							</div>
						</div>
					</div>
				</section>
			<?php } 
		;?>
	<?php } 
;?>