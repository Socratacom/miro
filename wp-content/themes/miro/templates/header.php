<nav id="siteNav" class="overlay">			

	<a href="javascript:void(0)" onclick="closeNav()" class="icon-ui-close overlay-close btn-circle btn-primary btn-shadow lockscroll"></a>

  <div class="overlay-content">

	  	<div class="container">
	  		<div class="row">
	  			<div class="col-sm-12 mb-5">
						<form role="search" method="get" class="search-form d-flex border" action="<?php echo home_url( '/' ); ?>" >
							<label class="sr-only">Search for:</label>
							<input type="search" class="search-field" placeholder="Search Socrata" value="" name="s" title="Search for:" data-swplive="true" data-swpconfig />
							<button type="submit" class="btn btn-link"><i class="icon-ui-search" aria-hidden="true"></i></button>
						</form>
	  			</div>
	  			<div class="col-sm-6">
	  				<h2>I'm a data decision maker</h2>
					  <p class="mb-4">Are you looking for cost effective data solutions that etiam porta sem malesuada magna mollis euismod. Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
					  <p class="mb-5"><a href="/data-solution-buyer" class="btn btn-primary">Learn More</a></p>
					</div>
	  			<div class="col-sm-6">
	  				<h2>I work with data daily</h2>
					  <p class="mb-4">Do you analyze data to make informed decisions nulla vitae elit libero, a pharetra augue. Sed posuere consectetur est at lobortis.</p>
					  <p class="mb-5"><a href="/data-solution-user" class="btn btn-primary">Learn More</a></p>
					</div>
				
				<hr/>

				<div class="col-sm-12">
					<h3 class="mb-4">Solutions for...</h3>
				</div>

				<div class="col-sm-4">
					<div class="card mb-2 match-height mdc-bg-grey-100">
						<div class="card-body text-center">
							<a href="/transportation" class="d-block p-3"><i class="icon-transportation mdc-text-purple-500 display-3 d-block mb-3"></i><span class="text-uppercase text-medium mdc-text-grey-800">Transportation</span></a>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="card mb-2 match-height mdc-bg-grey-100">
						<div class="card-body text-center">
							<a href="/finance" class="d-block p-3"><i class="icon-money mdc-text-green-500 display-3 d-block mb-3"></i><span class="text-uppercase text-medium mdc-text-grey-800">Finance</span></a>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="card mb-5 match-height mdc-bg-grey-100">
						<div class="card-body text-center">
							<a href="/open-data" class="d-block p-3"><i class="icon-data-grid mdc-text-orange-500 display-3 d-block mb-3"></i><span class="text-uppercase text-medium mdc-text-grey-800">Open Data</span></a>
						</div>
					</div>
				</div>

				<hr/>

				<div class="col-sm-3">
					<h5 class="text-regular text-uppercase border-bottom">Products</h5>
					<?php wp_nav_menu( array( 
            'theme_location' => 'site_nav_products',
            'container'       => 'div'
          ) ); ?>
				</div>
				<div class="col-sm-3">
					<h5 class="text-regular text-uppercase border-bottom">Services</h5>
					<?php wp_nav_menu( array( 
            'theme_location' => 'site_nav_services',
            'container'       => 'div'
          ) ); ?>
				</div>
				<div class="col-sm-3">
					<h5 class="text-regular text-uppercase border-bottom">Resources</h5>
					<?php wp_nav_menu( array( 
            'theme_location' => 'site_nav_resources',
            'container'       => 'div'
          ) ); ?>
				</div>
				<div class="col-sm-3">
					<h5 class="text-regular text-uppercase border-bottom">Company</h5>
					<?php wp_nav_menu( array( 
            'theme_location' => 'site_nav_company',
            'container'       => 'div'
          ) ); ?>
				</div>

			</div>


	</div>
</nav>
<header class="banner bg-white py-2 py-sm-5" role="banner">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-10 m-auto">
				<div class="d-flex align-items-center">
					<div class="mr-auto">
						<a href="<?php echo home_url('/'); ?>" class="brand header-logo"></a>
					</div>
					<div class="pr-3">
						<div class="dropdown d-none d-sm-block">
							<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Solutions for</button>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu">
							<a href="/transportation" class="dropdown-item">Transportation</a>
							<a href="/finance" class="dropdown-item">Finance</a>
							<a href="/open-data" class="dropdown-item">Open Data</a>
							<div class="arrow bottom right"></div>
							</div>
						</div>
					</div>
					<div>
						<a href="javascript:void(0)" class="icon-ui-menu btn-circle btn-outline-primary lockscroll" onclick="openNav()"></a>
					</div>
				</div>
			</div>			
		</div>
	</div>
</header>
<a href="javascript:void(0)" onclick="openNav()" class="icon-ui-menu overlay-menu btn-circle btn-primary btn-shadow lockscroll" data-aos="fade-zoom-in" data-aos-anchor="#trigger-menu" data-aos-anchor-placement="top-top"></a>
<script>$(".lockscroll").click(function(){$("body").toggleClass("no-scroll")})</script>
<script>AOS.init();</script>
