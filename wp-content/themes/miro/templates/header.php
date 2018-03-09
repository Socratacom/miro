<nav id="siteNav" class="overlay">

	
				

<div class="btn-circle btn-primary d-flex align-items-center overlay-close" >
	<i class="icon-close m-auto"></i>
	<a href="javascript:void(0)" onclick="closeNav()" class="lockscroll"></a>
</div>






  
  <div class="overlay-content">
  	<div class="container">
  		<div class="row justify-content-md-center">
  			<div class="col col-md-2">
				  <p class="text-center">Overlay navigation goes here.Donec ullamcorper nulla non metus auctor fringilla. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p>
				</div>
			</div>
		</div>
	</div>
</nav>





<header class="banner bg-white" role="banner">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-10 m-auto">
				<div class="d-flex align-items-center pt-2 pb-2 pt-sm-5 pb-sm-5">
					<div class="mr-auto">
						<a href="<?php echo home_url('/'); ?>" class="brand header-logo"></a>
					</div>
					<div class="pr-3">
						<div class="dropdown d-none d-sm-block">
							<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Solutions for</button>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
							<a href="#" class="dropdown-item">Transportation</a>
							<a href="#" class="dropdown-item">Finance</a>
							<a href="#" class="dropdown-item">Federal Programs</a>
							</div>
						</div>
					</div>
					<div>
						<a href="javascript:void(0)" class="icon-menu lockscroll" onclick="openNav()"></a>
					</div>
				</div>
			</div>			
		</div>
	</div>
</header>
<span id="trigger-menu"></span>


<div class="btn-circle btn-primary d-flex align-items-center overlay-open" data-aos="fade-zoom-in" data-aos-anchor="#trigger-menu" data-aos-anchor-placement="top-top">
	<i class="icon-menu m-auto"></i>
	<a href="javascript:void(0)" onclick="openNav()" class="lockscroll"></a>
</div>

 
<script>
$( ".lockscroll" ).click(function() {
  $( "body" ).toggleClass( "no-scroll" );
});
</script>
