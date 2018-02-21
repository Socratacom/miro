<?php 
$displaydate = rwmb_meta( 'webinars_displaydate' );
$today = strtotime('today UTC');
$date = strtotime(rwmb_meta( 'webinars_starttime' ));
$speakers = rwmb_meta( 'webinars_speakers' );
$section_title = rwmb_meta( 'webinars_section_title' );
$form_registration = rwmb_meta( 'webinars_form_registration' );
$form_on_demand = rwmb_meta( 'webinars_form_on_demand' );
$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'post-image' );
$url = $thumb['0'];
$img_id = get_post_thumbnail_id(get_the_ID());
$alt_text = get_post_meta($img_id , '_wp_attachment_image_alt', true);
$video = rwmb_meta( 'webinars_video' );
$content = rwmb_meta( 'webinars_wysiwyg' );
?>


<section class="section-padding">
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
				<h1 class="margin-bottom-15 font-light"><?php the_title(); ?></h1>

				<?php if($date >= $today) { ?><h3><?php echo $displaydate;?></h3> <?php } ?>

				<div class="margin-bottom-30"><?php echo do_shortcode('[addthis]');?></div>
				<div class="margin-bottom-30"><img src="<?php echo $url;?>" <?php if ( ! empty($alt_text) ) { ?> alt="<?php echo $alt_text;?>" <?php } ;?> class="img-responsive"><?php echo do_shortcode('[image-attribution]'); ?></div>
				<?php echo $content;?>

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
			<div class="col-sm-4">
				<?php 
					if($date == $today) { ?>
						<?php
							if ( ! empty( $video ) ) { }
							else { ?>
								<div class="alert alert-info"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>This webinar is scheduled for today</strong>. If you missed the webinar, it will be available on demand soon. Please check back later.</div>								
							<?php
							}
						;?>
					<?php }
					;?>
					<?php 
					if($date < $today) { ?>
						<?php
							if ( ! empty( $video ) ) { }
							else { ?>
								<div class="alert alert-info"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>We are currently processing this webinar</strong>. Please check back later.</div>
							<?php
							}
						;?>
					<?php }
				;?>


				<?php 
				if($date < $today) { ?>
				<?php
				if ( ! empty( $video ) ) { ?>
				<div class="background-light-grey-4 padding-30">
				<h4 class="margin-bottom-15">Watch this webinar</h4>
				<p> Please fill out this form to watch the <i>"<?php the_title(); ?>"</i> webinar.</p>

				<?php if ( ! empty( $form_on_demand ) ) { 
					$str = $form_on_demand;
					$str = preg_replace('#^https?://go.socrata.com/l/303201/#', '', $str);
					?> 
					<iframe id="formIframe" style="width: 100%; border: 0;" src="https://go.pardot.com/l/303201/<?php echo $str;?>" scrolling="no"></iframe>
					<?php } 
				?>

				</div>
				<?php }
				else { }
				;?>
				<?php }
				;?>
				<?php 
				if($date == $today) { ?>
				<?php
				if ( ! empty( $video ) ) { ?>
				<div class="background-light-grey-4 padding-30">
				<h4 class="margin-bottom-15">Watch this webinar</h4>
				<p> Please fill out this form to watch the <i>"<?php the_title(); ?>"</i> webinar.</p>

				<?php if ( ! empty( $form_on_demand ) ) { 
					$str = $form_on_demand;
					$str = preg_replace('#^https?://go.socrata.com/l/303201/#', '', $str);
					?> 
					<iframe id="formIframe" style="width: 100%; border: 0;" src="https://go.pardot.com/l/303201/<?php echo $str;?>" scrolling="no"></iframe>
					<?php } 
				?>

				</div>
				<?php }
				else { ?> 
				<div class="background-light-grey-4 padding-30">
				<h4 class="margin-bottom-15">Register for this webinar</h4>
				<p> Please fill out this form to register for the <i>"<?php the_title(); ?>"</i> webinar.</p>
				
				<?php if ( ! empty( $form_registration ) ) { 
					$str = $form_registration;
					$str = preg_replace('#^https?://go.socrata.com/l/303201/#', '', $str);
					?> 
					<iframe id="formIframe" style="width: 100%; border: 0;" src="https://go.pardot.com/l/303201/<?php echo $str;?>" scrolling="no"></iframe>
					<?php } 
				?>

				</div>
				<?php }
				;?>
				<?php }
				;?>
				<?php 
				if($date > $today) { ?>
				<div class="background-light-grey-4 padding-30">
				<h4 class="margin-bottom-15">Register for this webinar</h4>
				<p> Please fill out this form to register for the <i>"<?php the_title(); ?>"</i> webinar.</p>

				<?php if ( ! empty( $form_registration ) ) { 
					$str = $form_registration;
					$str = preg_replace('#^https?://go.socrata.com/l/303201/#', '', $str);
					?> 
					<iframe id="formIframe" style="width: 100%; border: 0;" src="https://go.pardot.com/l/303201/<?php echo $str;?>" scrolling="no"></iframe>
					<?php } 
				?>

				</div>
				<?php }
				;?>
				
			</div>
		</div>
	</div>
</section>
<script>iFrameResize({log:true}, '#formIframe')</script>

