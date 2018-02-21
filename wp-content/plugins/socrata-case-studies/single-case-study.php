<?php 
  $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'post-image' );
  $url = $thumb['0'];
  $img_id = get_post_thumbnail_id(get_the_ID());
  $alt_text = get_post_meta($img_id , '_wp_attachment_image_alt', true);
  $quote = rwmb_meta( 'case_study_quote' );
  $name = rwmb_meta( 'case_study_name' );
  $title = rwmb_meta( 'case_study_title' );
  $customer = rwmb_meta( 'case_study_customer' );
  $site_name = rwmb_meta( 'case_study_site_name' );
  $site = rwmb_meta( 'case_study_url' );
  $highlight = rwmb_meta( 'case_study_highlight' );
  $headshot = rwmb_meta( 'case_study_headshot', 'size=thumbnail' );
  $content = rwmb_meta( 'case_study_wysiwyg' );
?>

<section class="section-padding">
  <div class="container">
    <div class="row">
      <div class="col-sm-8">
        <h1 class="margin-bottom-15"><?php the_title(); ?></h1>
        <div class="color-grey margin-bottom-15">
          <?php if ( ! empty( $customer ) ) { ?> 
            Customer: <?php echo $customer;?> <?php if ( ! empty( $site ) ) { ?> | Site: <a href="<?php echo $site;?>" target="_blank"><?php echo $site_name;?></a> <?php } ?> 
          <?php }           
          else { ?> 
            <?php if ( ! empty( $site ) ) { ?> Site: <a href="<?php echo $site;?>" target="_blank"><?php echo $site_name;?></a> <?php } ?>
          <?php } ?>
        </div>
        <div class="margin-bottom-30"><?php echo do_shortcode('[addthis]');?></div>
        <div class="margin-bottom-30"><img src="<?php echo $url;?>" <?php if ( ! empty($alt_text) ) { ?> alt="<?php echo $alt_text;?>" <?php } ;?> class="img-responsive"><?php echo do_shortcode('[image-attribution]'); ?></div>
        <?php echo $content;?>
      </div>
      <div class="col-sm-4 hidden-xs">
        <?php if ( ! empty( $quote ) ) { ?>
          <p class="lead">"<?php echo $quote;?>"</p>
          <?php if ( ! empty( $name ) ) { ?>
            <dl class="quote-author">
              <dt><?php echo $name;?><span><?php echo $title;?></span></dt>
              <?php foreach ( $headshot as $image ) {
              echo "<dd><img src='{$image['url']}' width='{$image['width']}' height='{$image['height']}' alt='{$image['alt']}' class='img-circle' /></dd>";
              } ?>
              <?php if ( ! empty( $headshot ) ) { ?> <?php } else { ?> <dd><img src="/wp-content/uploads/no-picture-100x100.png" class="img-circle"></dd><?php } ?>
            </dl>
            <?php } ?>
          <hr>
        <?php } ?>
        <?php if ( ! empty( $highlight ) ) {
          echo '<h5 style="text-transform:uppercase;">Highlights</h5>';
          echo '<ul class="check-mark-list">';
          foreach ( $highlight as $highlights ) { ?> <li><?php echo $highlights;?></li> <?php };
          echo '</ul>';
        } ?>
        <?php echo do_shortcode('[newsletter-sidebar]'); ?> 
        
      </div>
    </div>
  </div>
</section>


