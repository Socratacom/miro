<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

?>

<!doctype html>
<html <?php language_attributes(); ?>>
  <?php get_template_part('templates/head'); ?>
  <body <?php body_class(); ?>>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TT7CHX" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!--[if IE]>
      <div class="alert alert-warning">
        <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sage'); ?>
      </div>
    <![endif]-->

    <div class="frame-left" aria-hidden="true"></div>
    <div class="frame-right" aria-hidden="true"></div>
    <div class="frame-top" aria-hidden="true"></div>
    <div class="frame-bottom" aria-hidden="true"></div>
    
    <?php
      do_action('get_header');
      get_template_part('templates/header');
    ?>
  
    <main class="main">
      <?php include Wrapper\template_path(); ?>
    </main><!-- /.main -->

    <?php
      do_action('get_footer');
      get_template_part('templates/footer');
      wp_footer();
    ?>

  </body>
</html>
