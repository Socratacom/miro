<?php
	$current = $post->ID;     
	$parent = $post->post_parent;     
	$grandparent_get = get_post($parent);     
	$grandparent = $grandparent_get->post_parent; 
	$asset_url = rwmb_meta( 'downloads_link' );
	$cover = rwmb_meta( 'downloads_asset_image', 'size=medium' );
	$resources = rwmb_meta( 'downloads_resource_group' );
?>

<section class="section-padding">
	<div class="container">
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1">
				<h1 class="text-center font-light margin-bottom-60">Thank you for your interest in <?php if ($root_parent = get_the_title($grandparent) !== $root_parent = get_the_title($current)) {echo get_the_title($grandparent); }else {echo get_the_title($parent); }?></h1>
				<?php if ( !empty( $cover ) ) { ?><p class="text-center margin-bottom-60"><img src="<?php foreach ( $cover as $image ) { echo $image['url']; } ?>" style="box-shadow: 5px 5px 15px #999;"><?php } ?>
				<p class="text-center"><a href="<?php echo $asset_url; ?>" target="_blank" class="btn btn-primary btn-lg">Download Here</a></p>
			</div>
		</div>
	</div>
</section>

<?php if ( ! empty( $resources ) ) {

	echo '<section class="section-padding background-light-grey-4"><div class="container"><div class="row no-float text-center"><div class="col-sm-12"><h2 class="text-center font-light margin-bottom-60">You may also be interested in...</h2></div>';
	
  foreach ( $resources as $resource ) {
    $title = isset( $resource['downloads_resource_title'] ) ? $resource['downloads_resource_title'] : '';
    $description = isset( $resource['downloads_resource_description'] ) ? $resource['downloads_resource_description'] : '';
    $link = isset( $resource['downloads_resource_link'] ) ? $resource['downloads_resource_link'] : '';
    $btn_txt = isset( $resource['downloads_resource_button_text'] ) ? $resource['downloads_resource_button_text'] : '';
  ?>

  <div class="col-sm-6 col-md-4 col-lg-3" style="vertical-align: text-top;">
    <div class="card margin-bottom-30 match-height">
      <div class="card-body text-left">
      	<h5><?php echo $title; ?></h5>
      	<?php if ( !empty( $description ) ) { ?><p class="margin-bottom-15 font-normal" style="line-height:normal;"><?php echo $description; ?></p><?php } ?>
      </div>
      <div class="card-footer padding-15 text-left">
      	<?php if ( !empty( $btn_txt ) ) { ?> 
      		<a href="<?php echo $link;?>" class="btn btn-default" style="position:relative; height:auto; width:auto;"><?php echo $btn_txt; ?></a>
      	<?php } else { ?> 
      		<a href="<?php echo $link;?>" class="btn btn-default" style="position:relative; height:auto; width:auto;">Read More</a>
      	<?php } ?>
      </div>
    </div>
  </div>

  <?php }

	echo '</div></div></section>';

} ?> 