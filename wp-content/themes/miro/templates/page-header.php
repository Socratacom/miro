<?php use Roots\Sage\Titles; ?>

<div class="page-header">
	<?php if (is_author()) { ?>
		<h1 class="m-0"><a href="/blog" class="btn btn-outline-primary mr-3 d-none d-md-inline-block"><i class="icon-ui-arrow-left"></i></a> <?= Titles\title(); ?></h1>
		<?php } else { ?> 
		<h1><?= Titles\title(); ?></h1>
	<?php } ?>
</div>
