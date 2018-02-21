<?php
/**
 * Template Name: Fluid Template
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', 'page-fluid'); ?>
<?php endwhile; ?>
