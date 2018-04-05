<div class="d-flex flex-row align-items-center entry-meta">
	<div>
		<?php  global $post;
		$author_id=$post->post_author;
		foreach( get_coauthors() as $coauthor ): echo get_avatar( $coauthor->user_email, '40' ); 
		endforeach;
		?>
	</div>
	<div class="pl-2">
		Article by <?php echo $coauthor->display_name; ?><br><time datetime="<?= get_post_time('c', true); ?>"><?= get_the_date(); ?></time>
	</div>
</div>