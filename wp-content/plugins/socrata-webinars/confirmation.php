<?php 
$displaydate = rwmb_meta( 'webinars_displaydate' );
$today = strtotime('today UTC');
$date = strtotime(rwmb_meta( 'webinars_starttime' ));
$asset_link = rwmb_meta( 'webinars_asset_link' );
$asset_title = rwmb_meta( 'webinars_asset_title' );
$asset_description = rwmb_meta( 'webinars_asset_description' );
$asset_image = rwmb_meta( 'webinars_asset_image', 'size=medium' );
?>

<?php 
	if($date < $today) { ?>
		<section class="section-padding">
			<div class="container">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="alert alert-info"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>Registration for this webinar has expired</strong>. Watch the <a href="<?php the_permalink() ?>"><?php the_title(); ?></a> webinar or <a href="/webinars">view all webinars</a>.</div>
					</div>
				</div>
			</div>
		</section>
	<?php } 
;?>

<?php 
	if($date >= $today) { ?>
		<section class="section-padding">
			<div class="container">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<p class="text-center"><i class="icon-thumb-up color-success icon-100"></i></p>
						<h2 class="text-center margin-bottom-15">Thank you for registering!</h2>
						<h1 class="text-center"><?php the_title(); ?></h1>
						<h3 class="text-center"><?php echo $displaydate; ?></h3>
					</div>
				</div>
			</div>
		</section>
	<?php } 
;?>