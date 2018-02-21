<?php
  $content = rwmb_meta( 'downloads_wysiwyg' );
  $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'feature-image' );
	$url = $thumb['0'];
  $cover = rwmb_meta( 'downloads_asset_image', 'size=small' );
  $gated = rwmb_meta( 'downloads_gated' );
?>

<section class="section-padding">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h1 class="font-light margin-bottom-15"><?php the_title(); ?></h1>        
        <div class="margin-bottom-60"><?php echo do_shortcode('[addthis]');?></div>
        <div><a href="#meta" class="reveal-delay"><i class="icon-32 icon-down-arrow color-primary" aria-hidden="true"></i></a></div>
      </div>      
    </div>
  </div>
</section>
<div class="event-feature-image" style="width:100%; background-image:url(<?php echo $url;?>); background-repeat: no-repeat; background-position: center; background-size: cover; position:relative;">
	<div style="position:absolute; bottom:0px; right:0px; display:inline-block; color:#fff; padding:2px 10px; font-size:13px;"><?php echo do_shortcode('[image-attribution]'); ?></div>
</div>
<section id="meta" class="section-padding">
	<div class="container">
		<div class="row">
			<div class="col-sm-7 col-md-6 col-md-offset-1">				
      	<div class="color-grey margin-bottom-15 text-uppercase"><?php downloads_the_categories(); ?></div>
      	<?php echo $content;?>
			</div>
			<div class="col-sm-5 col-md-4">
				<div class="padding-30 background-light-grey-4">
					<h4>Get the <?php downloads_the_categories(); ?></h4>

					<?php if ( ! empty( $gated ) ) { ?>

						<?php if ( !empty( $cover ) ) { ?>
							<div class="media margin-bottom-30">
								<div class="media-left">
									<img src="<?php foreach ( $cover as $image ) { echo $image['url']; } ?>" style="box-shadow: 3px 3px 5px #999; width:60px;">
								</div>
								<div class="media-body">
									<p>Download <i><?php the_title(); ?></i> on the next page by filling out the form below.</p>
								</div>
							</div>
						<?php } else { ?> 
							<p>Download <i><?php the_title(); ?></i> on the next page by filling out the form below.</p>
						<?php } ?>	

						<iframe id="formIframe" style="width: 100%; border: 0;" src="https://go.pardot.com/l/303201/2017-11-30/tgyl?Hidden_Redirect_Field=<?php echo get_permalink( $post->ID ); ?>thank-you" scrolling="no"></iframe>
						<script>iFrameResize({log:true}, '#formIframe')</script>
					<?php } else { ?>

						<?php if ( !empty( $cover ) ) { ?>
							<div class="media margin-bottom-30">
								<div class="media-left">
									<img src="<?php foreach ( $cover as $image ) { echo $image['url']; } ?>" style="box-shadow: 3px 3px 5px #999; width:60px;">
								</div>
								<div class="media-body">
									<p>Download <i><?php the_title(); ?></i> on the next page by clicking the button below.</p>
								</div>
							</div>
						<?php } else { ?> 
							<p>Download <i><?php the_title(); ?></i> on the next page by clicking the button below.</p>
						<?php } ?>	

						<div>
							<a href="<?php echo get_permalink( $post->ID ); ?>/thank-you" class="btn btn-primary btn-block">Get the <?php downloads_the_categories(); ?></a>
						</div>
					<?php } ?>

				</div>				
			</div>
		</div>
	</div>
</section>
<script>window.sr=ScrollReveal({reset:!1}),sr.reveal(".reveal",{duration:500}),sr.reveal(".reveal-delay",{duration:500,delay:500}),sr.reveal(".box-reveal",{duration:1e3,delay:500},50);</script>