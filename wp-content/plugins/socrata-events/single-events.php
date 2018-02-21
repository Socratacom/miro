<?php 
/*$displaydate = rwmb_meta( 'socrata_events_displaydate' );*/
$today = strtotime('today UTC');
$startdate = rwmb_meta( 'socrata_events_starttime' );
$enddate = rwmb_meta( 'socrata_events_endtime' );
$starttime = rwmb_meta( 'socrata_events_starttime_1' );
$endtime = rwmb_meta( 'socrata_events_endtime_1' );
$timezone = rwmb_meta( 'socrata_events_timezone' );
$eventbrite = rwmb_meta( 'socrata_events_eventbrite_url' );
$eventbrite_id = rwmb_meta( 'socrata_events_eventbrite_id' );
$eventbrite_btn = rwmb_meta( 'socrata_events_button_text' );
$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'feature-image' );
$url = $thumb['0'];
$content = rwmb_meta( 'socrata_events_wysiwyg' );
$speakers = rwmb_meta( 'socrata_events_speakers' );
$agenda = rwmb_meta( 'socrata_events_agenda' );
$geometry = rwmb_meta( 'socrata_events_geometry' );
$venue = rwmb_meta( 'socrata_events_venue' );
$street_number = rwmb_meta( 'socrata_events_street_number' );
$route = rwmb_meta( 'socrata_events_route' );
$city = rwmb_meta( 'socrata_events_locality' );
$state = rwmb_meta( 'socrata_events_administrative_area_level_1_short' );
$zip = rwmb_meta( 'socrata_events_postal_code' );
$venue_website = rwmb_meta( 'socrata_events_venue_url' );
$website = rwmb_meta( 'socrata_events_url' );
$booth = rwmb_meta( 'socrata_events_booth' );
$region = rwmb_meta( 'socrata_events_region_taxonomy' );
?>

<?php if($enddate < $today) { ?>
  <section class="section-padding">
    <div class="container">
      <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
          <div class="alert alert-info"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>This event has expired</strong>. <a href="/events">Return to events</a>.</div>
        </div>
      </div>
    </div>
  </section>
  <?php } 
;?>

<?php if($enddate >= $today) { ?>
  <section class="section-padding">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h1 class="font-light margin-bottom-15"><?php the_title(); ?></h1>
          <?php if($startdate == $enddate) { ?>
          	<h3><?php echo date('l, F j', $enddate); ?><?php if ( !empty ( $starttime ) ) { ?>, <?php echo date('g:i a', strtotime($starttime));?><?php if ( !empty ( $endtime ) ) { ?> - <?php echo date('g:i a', strtotime($endtime));?><?php } ?><?php if ( !empty ( $timezone ) ) { ?> <?php echo $timezone; ?><?php } ?><?php } elseif ( !empty ( $endtime ) ) { ?>, <?php echo date('g:i a', strtotime($endtime));?><?php if ( !empty ( $timezone ) ) { ?> <?php echo $timezone; ?><?php } ?><?php } ?></h3>
          	<?php } else { ?>
          	<h3><?php if ( !empty ( $startdate ) ) { ?><?php echo date('l, F j', $startdate); ?> - <?php echo date('l, F j', $enddate); ?><?php if ( !empty ( $starttime ) ) { ?>, <?php echo date('g:i a', strtotime($starttime));?><?php if ( !empty ( $endtime ) ) { ?> - <?php echo date('g:i a', strtotime($endtime));?><?php } ?><?php if ( !empty ( $timezone ) ) { ?> <?php echo $timezone; ?><?php } ?><?php } elseif ( !empty ( $endtime ) ) { ?>, <?php echo date('g:i a', strtotime($endtime));?><?php if ( !empty ( $timezone ) ) { ?> <?php echo $timezone; ?><?php } ?><?php } ?><?php } else { ?><?php echo date('l, F j', $enddate); ?><?php if ( !empty ( $starttime ) ) { ?>, <?php echo date('g:i a', strtotime($starttime));?><?php if ( !empty ( $endtime ) ) { ?> - <?php echo date('g:i a', strtotime($endtime));?><?php } ?><?php if ( !empty ( $timezone ) ) { ?> <?php echo $timezone; ?><?php } ?><?php } elseif ( !empty ( $endtime ) ) { ?>, <?php echo date('g:i a', strtotime($endtime));?><?php if ( !empty ( $timezone ) ) { ?> <?php echo $timezone; ?><?php } ?><?php } ?><?php } ?></h3>
          	<?php } 
          ?>
          <div class="margin-bottom-60"><?php echo do_shortcode('[addthis]');?></div>
					<?php if ( !empty ( $eventbrite ) ) { ?>
					<div>
					<!-- Noscript content for added SEO -->
					<noscript><a href="<?php echo $eventbrite;?>" rel="noopener noreferrer" target="_blank"></noscript>
					<!-- You can customize this button any way you like -->
					<button id="eventbrite-widget-modal-trigger-1" class="btn btn-lg btn-primary" type="button"><?php if ( !empty ( $eventbrite_btn ) ) echo $eventbrite_btn; else echo "Register" ?></button>
					<noscript></a><?php if ( !empty ( $eventbrite_btn ) ) echo $eventbrite_btn; else echo "Register" ?></noscript>
					<script src="https://www.eventbrite.com/static/widgets/eb_widgets.js"></script>
					<script type="text/javascript">
					    var exampleCallback = function() {
					        console.log('Order complete!');
					    };
					    window.EBWidgets.createWidget({
					        widgetType: 'checkout',
					        eventId: '<?php echo $eventbrite_id;?>',
					        modal: true,
					        modalTriggerElementId: 'eventbrite-widget-modal-trigger-1',
					        onOrderComplete: exampleCallback
					    });
					</script>
					<?php } else { ?>
					<div><a href="#meta" class="reveal-delay"><i class="icon-32 icon-down-arrow color-primary" aria-hidden="true"></i></a></div>
					<?php } ?>
        </div>      
      </div>
    </div>
  </section>
  <div class="event-feature-image" style="width:100%; background-image:url(<?php echo $url;?>); background-repeat: no-repeat; background-position: center; background-size: cover; position:relative;">
    <div style="position:absolute; bottom:5px; right:15px; display:inline-block; color:#fff;"><?php echo do_shortcode('[image-attribution]'); ?></div>
  </div>
  <section id="meta" class="section-padding">
  	<div class="container">
  		<div class="row">
  			<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
  				<?php echo $content;?>
  				<!-- Begin Speakers -->
  				<?php if ( ! empty( $speakers ) ) { ?>
						<h3 class="margin-bottom-60 margin-top-60">Speakers</h3>
						<div class="row">
							<?php foreach ( $speakers as $speaker_value ) {
								$id = uniqid();
								$name = isset( $speaker_value['socrata_events_speaker_name'] ) ? $speaker_value['socrata_events_speaker_name'] : '';
								$title = isset( $speaker_value['socrata_events_speaker_title'] ) ? $speaker_value['socrata_events_speaker_title'] : '';
								$bio = isset( $speaker_value['socrata_events_speaker_bio'] ) ? $speaker_value['socrata_events_speaker_bio'] : '';
								$images = isset( $speaker_value['socrata_events_speaker_headshot'] ) ? $speaker_value['socrata_events_speaker_headshot'] : array();
								foreach ( $images as $image ) { $image = RWMB_Image_Field::file_info( $image, array( 'size' => 'thumbnail' ) ); } 
								?>
								<div class="col-sm-6 col-lg-4">
									<div class="card margin-bottom-30 match-height" style="padding:0;">
										<div class="card-body">
											<div class="text-center margin-bottom-15">
												<?php if (! empty( $images )) echo "<img src='$image[url]' class='img-responsive img-circle'>"; else echo "<img src='/wp-content/uploads/no-picture-100x100.png' class='img-responsive img-circle'>";?>
											</div>
											<h5 class="text-center"><?php echo $name;?></h5>
											<div class="text-center" style="line-height:1em;"><small><i><?php echo $title;?></i></small></div>
										</div>
										<a href="#" data-toggle="modal" data-target="#<?php echo $id;?>"></a>
									</div>
								</div>
								<div id="<?php echo $id;?>" class="modal leadership-modal" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<button type="button" data-dismiss="modal"><i class="icon-close"></i></button>
											<div class="modal-body">
												<div class="padding-30 bio">
													<div class="text-center margin-bottom-30">
														<p class="text-center"><?php if (! empty( $images )) echo "<img src='$image[url]' class='img-circle' >"; else echo "<img src='/wp-content/uploads/no-picture-100x100.png' class='img-circle'>";?></p>
														<h5 class="margin-bottom-5"><?php echo $name; ?></h5>
														<div class="margin-bottom-5"><?php echo $title; ?></div>
													</div>
													<?php echo $bio; ?>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php } 
							?>
						</div>
					<?php }
					?>
					<!-- End Speakers -->
					<!-- Begin Agenda -->
					<?php if ( !empty ( $agenda ) ) { ?>
						<h3 class="margin-bottom-60 margin-top-60">Agenda</h3>
						<?php foreach ( $agenda as $agenda_value ) {
						$time = isset( $agenda_value['socrata_events_agenda_time'] ) ? $agenda_value['socrata_events_agenda_time'] : '';
						$title = isset( $agenda_value['socrata_events_agenda_title'] ) ? $agenda_value['socrata_events_agenda_title'] : '';
						$speakers = isset( $agenda_value['socrata_events_agenda_speakers'] ) ? $agenda_value['socrata_events_agenda_speakers'] : '';
						$description = isset( $agenda_value['socrata_events_agenda_description'] ) ? $agenda_value['socrata_events_agenda_description'] : '';
						?> 
							<div class="row" style="border-top:#d6d6d6 solid 1px; padding:30px 0;">
								<div class="col-sm-3">
									<h3 class="margin-bottom-15 mdc-text-orange-500"><?php echo date('g:i a', strtotime($time));?></h3>
								</div>
								<div class="col-sm-9">
									<h4 style="margin-top:10px;"><?php echo $title;?></h4>
									<p><?php echo $description;?></p>
									<?php if ( ! empty( $speakers ) ) { ?><h5 class="margin-bottom-5">Speakers</h5><ul style="margin:0"><?php foreach ( $speakers as $speaker ) echo "<li>$speaker</li>"; ?></ul><?php } ?>
								</div>
							</div>
						<?php } ?>					
					<?php } 
					?>
					<!-- End Agenda -->
  			</div>
  		</div>
  	</div>
  </section>
  <?php if ( !empty ( $geometry ) ) { ?>
  <section class="mdc-bg-blue-grey-500">
  	<div class="container-fluid" style="padding:0;">
  		<div class="row no-gutters">
  			<div class="col-sm-6">
  				<div id="map" class="hidden-xs"></div>
  			</div>
  			<div class="col-sm-6">
  				<div class="event-venue-address">
  					<h5 class="text-uppercase color-white margin-bottom-15">Venue</h5>
  					<div class="address margin-bottom-15"><?php if ( !empty ($venue)) { ?><?php echo $venue;?><?php } ?><?php if ( !empty ( $street_number ) ) { ?><br/><?php echo $street_number;?><?php } ?><?php if ( !empty ( $route ) ) { ?> <?php echo $route;?><?php } ?><?php if ( !empty ( $city ) ) { ?><br/><?php echo $city;?><?php } ?><?php if ( !empty ( $state ) ) { ?>, <?php echo $state;?><?php } ?><?php if ( !empty ( $zip ) ) { ?> <?php echo $zip;?><?php } ?></div>
  					<?php if ( !empty ( $booth ) ) { ?><p class="color-white font-normal margin-bottom-30">Booth No.: <?php echo $booth;?><?php } ?>
  					<?php if ( !empty ( $venue_website ) ) { ?>
  					<div class="helpful-links">
  						<h5 class="text-uppercase color-white margin-bottom-15">Helpful Links</h5>
	  					<ul class="no-bullets" style="margin:0 0 30px 0;">
	  						<li style="margin-bottom:5px;"><a href="<?php echo $venue_website;?>" target="_blank" rel="noopener noreferrer" class="mdc-text-light-blue-400 font-normal">Visit Venue Website</a></li>
	  						<?php if ( !empty ( $website ) ) { ?><li><a href="<?php echo $website;?>" target="_blank" rel="noopener noreferrer" class="mdc-text-light-blue-400 font-normal">Visit Event Website</a></li><?php } ?>
	  					</ul>
  					</div>
  					<?php } elseif ( !empty ( $website ) ) { ?>
  					<div class="helpful-links">
  						<h5 class="text-uppercase color-white margin-bottom-15">Helpful Links</h5>
	  					<ul class="no-bullets" style="margin:0 0 30px 0;">
	  						<li style="margin-bottom:5px;"><a href="<?php echo $website;?>" target="_blank" rel="noopener noreferrer" class="mdc-text-light-blue-400 font-normal">Visit Event Website</a></li>
	  					</ul>
	  				</div>
  					<?php } ?>
  					<?php if ( !empty ( $eventbrite ) ) { ?>
						<div>
						<!-- Noscript content for added SEO -->
						<noscript><a href="<?php echo $eventbrite;?>" rel="noopener noreferrer" target="_blank"></noscript>
						<!-- You can customize this button any way you like -->
						<button id="eventbrite-widget-modal-trigger-2" class="btn btn-lg btn-light mdc-text-blue-grey-500" type="button"><?php if ( !empty ( $eventbrite_btn ) ) echo $eventbrite_btn; else echo "Register" ?></button>
						<noscript></a><?php if ( !empty ( $eventbrite_btn ) ) echo $eventbrite_btn; else echo "Register" ?></noscript>
						<script src="https://www.eventbrite.com/static/widgets/eb_widgets.js"></script>
						<script type="text/javascript">
						    var exampleCallback = function() {
						        console.log('Order complete!');
						    };
						    window.EBWidgets.createWidget({
						        widgetType: 'checkout',
						        eventId: '<?php echo $eventbrite_id;?>',
						        modal: true,
						        modalTriggerElementId: 'eventbrite-widget-modal-trigger-2',
						        onOrderComplete: exampleCallback
						    });
						</script>
						<?php } else { ?>
						<div><a href="#meta" class="reveal-delay"><i class="icon-32 icon-down-arrow color-primary" aria-hidden="true"></i></a></div>
						<?php } ?>
  				</div>
  			</div>
  		</div>
  	</div>
  </section>
  <section class="section-padding mdc-bg-grey-200">
  	<div class="container">
  		<div class="row">
  			<div class="col-sm-6">
  				<?php if ( !empty ( $region ) ) { ?>
  				<h3>Contact Us</h3>
  				<p>Have a question about this event or want to host one in your city? Let us know by filling out this form.</p>
  				<?php } else { ?>
  				<h3>Contact Us</h3>
  				<p>Have a question about this event or want meet with us? Let us know by filling out this form.</p>
  				<?php } ?>  				
  			</div>
  			<div class="col-sm-6">
  				<?php if ( !empty ( $region ) ) { ?>
  				<iframe id="formIframe" style="width: 100%; border: 0;" src="https://go.pardot.com/l/303201/2018-01-29/2fkmp" scrolling="no"></iframe><script>iFrameResize({log:true}, '#formIframe')</script>
  				<?php } else { ?>
  				<iframe id="formIframe" style="width: 100%; border: 0;" src="https://go.pardot.com/l/303201/2018-01-29/2fkp1" scrolling="no"></iframe><script>iFrameResize({log:true}, '#formIframe')</script>
  				<?php } ?>  				
  			</div>
  		</div>
  	</div>
  </section>
	<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyD_STOs8I4L5GTLlDIu5aZ-pLs2L69wHMw&callback=initialize"></script>
  <script type="text/javascript">
    // When the window has finished loading create our google map below
    google.maps.event.addDomListener(window, 'load', init);

    function init() {
      // Basic options for a simple Google Map
      // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
      var mapOptions = {
      		scrollwheel: false,
          zoom: 17,

          // The latitude and longitude to center the map (always required)
          center: new google.maps.LatLng(<?php echo $geometry;?>), // New York

          styles: [{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#e0efef"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"hue":"#1900ff"},{"color":"#c0e8e8"}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":100},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"visibility":"on"},{"lightness":700}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#7dcdcd"}]}]
      };

      var mapElement = document.getElementById('map');
      var map = new google.maps.Map(mapElement, mapOptions);
      var marker = new google.maps.Marker({
          position: new google.maps.LatLng(<?php echo $geometry;?>),
          map: map,
          title: '<?php echo $venue;?>'
      });
    }
  </script>


  <?php } 
  ?>
<?php } 
?>