<?php if (is_home()) { ?> 
	<?php echo do_shortcode('[feature-post type="post"]');?>
	<?php } elseif (is_author()) { ?>
	<section class="pt-5">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-10 mx-auto">
					<?php get_template_part('templates/page', 'header'); ?>
				</div>	
			</div>
		</div>
	</section>
	<?php } else { ?> 
	<section class="py-5">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-10 mx-auto">
					<?php get_template_part('templates/page', 'header'); ?>
				</div>	
			</div>
		</div>
	</section>
<?php } ?> 

<section class="py-5">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-10 pb-5 m-auto d-none d-md-block">
				<div class="d-flex flex-row">
					<div class="mr-auto">
						<?php echo do_shortcode('[facetwp facet="solutions"]');?>
					</div>
					<div>
						<button onclick="FWP.reset()" class="btn btn-primary"><i class="fa fa-undo" aria-hidden="true"></i> Reset</button>
					</div>
				</div>
			</div>
			<div class="col-sm-10 mx-auto">
				<div class="grid-wrapper clearfix" style="margin-left:-15px; margin-right:-15px;">
				<div class="col-sm-6 grid-sizer"></div>
				<?php echo facetwp_display( 'template', 'blog' ); ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 text-center">
				<button class="fwp-load-more btn btn-primary">Load more</button>
			</div>
		</div>
	</div>
</section>


