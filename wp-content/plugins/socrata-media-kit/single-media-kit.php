<?php 
  $about_content = rwmb_meta( 'media_kit_about_socrata_content' );
  $highlights = rwmb_meta( 'media_kit_highlight_group' );
  $news = rwmb_meta( 'media_kit_news_group' );
  $downloads = rwmb_meta( 'media_kit_downloads_group' );
  $timeline = rwmb_meta( 'media_kit_timeline_group' );
?>
<style>
.essential-links ul li {font-size:14px; border-bottom:#d1d1d1 solid 1px; margin:0; padding:10px 0; position:relative; padding-left:30px;}
.essential-links .message:before {font-family: 'icomoon'; content:"\e938"; position:absolute; top:10px; left:0; font-size: 20px;}
.essential-links .twitter:before {font-family: 'icomoon'; content:"\e93b"; position:absolute; top:10px; left:0; font-size: 20px;}
.essential-links .linkedin:before {font-family: 'icomoon'; content:"\e93c"; position:absolute; top:10px; left:0; font-size: 20px;}
.essential-links .youtube:before {font-family: 'icomoon'; content:"\e93a"; position:absolute; top:10px; left:0; font-size: 20px;}
</style>
<div class="container">
  <div class="row">
    <div class="col-sm-3 hidden-xs" style="padding-top:80px;">
      <div id="sidebar" style="padding:15px 15px 15px 0; border-right:#d1d1d1 solid 1px;">
        <ul class="no-bullets">
          <li><a href="#section-2" class="font-semi-bold">Coverage</a></li>
          <li><a href="#section-3" class="font-semi-bold">Branding</a></li>
          <li><a href="#section-4" class="font-semi-bold">Digital Assets</a></li>
          <li><a href="#section-5" class="font-semi-bold">Company Timeline</a></li>
        </ul>
      </div>        
    </div>
    <div class="col-sm-9">
      <!-- Section 1 -->
      <section class="section-padding">
        <div class="row">
          <div class="col-sm-12">
            <h1 class="font-light"><?php the_title(); ?></h1>
          </div>
          <div class="col-sm-7 col-md-8">
            
            <?php echo $about_content;?>          
          </div>
          <div class="col-sm-5 col-md-4">
            <div class="essential-links">
              <h5 class="text-uppercase">Essential Links</h5>
              <ul class="no-bullets">
                <li class="message"><span class="text-uppercase font-semi-bold">Press Contact</span><br><a href="mailto:press@socrata.com" class="font-normal">press@socrata.com</a></li>
                <li class="twitter"><span class="text-uppercase font-semi-bold">Twitter</span><br><a href="https://twitter.com/socrata" target="_blank" class="font-normal">@socrata</a></li>
                <li class="twitter"><span class="text-uppercase font-semi-bold">CEO Twitter</span><br><a href="https://twitter.com/kmerritt" target="_blank" class="font-normal">@kmerritt</a></li>
                <li class="linkedin"><span class="text-uppercase font-semi-bold">Linked In</span><br><a href="https://www.linkedin.com/company-beta/428169/" target="_blank" class="font-normal">View Profile</a></li>
                <li class="youtube"><span class="text-uppercase font-semi-bold">YouTube</span><br><a href="https://www.youtube.com/user/socratavideos" target="_blank" class="font-normal">Watch Videos</a></li>
              </ul>
            </div>
          </div>         
        </div>
      </section>
      <!-- Section 2 -->
      <section id="section-2" class="section-padding" style="border-top:#d1d1d1 solid 1px;">
        <div class="row">
          <div class="col-sm-12">
            <h2 class="margin-bottom-60">Coverage</h2>
          </div>
          <div class="col-sm-12 col-md-8">
            <?php if ( ! empty( $highlights ) ) {
              foreach ( $highlights as $highlight ) {
                $title = isset( $highlight['media_kit_highlight_title'] ) ? $highlight['media_kit_highlight_title'] : '';
                $content = isset( $highlight['media_kit_highlights_content'] ) ? $highlight['media_kit_highlights_content'] : '';
                $images = isset( $highlight['media_kit_highlight_thumbnail'] ) ? $highlight['media_kit_highlight_thumbnail'] : array();
                  foreach ( $images as $image ) {
                    $image = RWMB_Image_Field::file_info( $image, array( 'size' => 'post-image' ) );
                  } 
              ?>

                <div style="background-image:url(<?php echo $image['url'];?>); background-position:top; background-size:contain; background-repeat:no-repeat; padding:15px;">
                  
                  <div class="background-white padding-30" style="margin-top:35%;">
                    <h3 class="font-light margin-bottom-15"><?php echo $title;?></h3>
                    <?php echo $content;?>
                  </div>
                </div>

              <?php }
            } ?>           
          </div>
          <div class="col-sm-12 col-md-4">
            <h5 class="text-uppercase">In the News</h5>
            <?php if ( ! empty( $news ) ) {
              foreach ( $news as $article ) {
                $title = isset( $article['media_kit_news_title'] ) ? $article['media_kit_news_title'] : '';
                $source = isset( $article['media_kit_news_source'] ) ? $article['media_kit_news_source'] : '';
                $url = isset( $article['media_kit_news_url'] ) ? $article['media_kit_news_url'] : '';
                $date = isset( $article['media_kit_news_date'] ) ? $article['media_kit_news_date'] : '';
                $images = isset( $article['media_kit_news_thumbnail'] ) ? $article['media_kit_news_thumbnail'] : array();
                  foreach ( $images as $image ) {
                    $image = RWMB_Image_Field::file_info( $image, array( 'size' => 'full-width-ratio' ) );
                  } 
              ?>

                <div class="media margin-bottom-30">
                  <div class="media-left hidden-xs">
                    <div class="sixteen-nine" style="background-image:url(<?php echo $image['url'];?>); background-size:contain; background-repeat:no-repeat; background-position:top; position:relative; width:60px; margin-top:5px;"></div>
                  </div>
                  <div class="media-body">
                    <a href="<?php echo $url;?>" target="_blank" class="font-semi-bold"><?php echo $title;?></a><br>
                    <small><?php echo $source;?> | <?php echo $date;?></small>
                  </div>
                </div>         

              <?php }
            } ?>     
          </div>
        </div>
      </section>
      <!-- Section 3 -->
      <section id="section-3" class="section-padding" style="border-top:#d1d1d1 solid 1px;">
        <div class="row">
          <div class="col-sm-12">
            <h2 class="margin-bottom-60">Branding</h2>
          </div>
          <div class="col-sm-6 col-md-8">
            <img src="/wp-content/uploads/media-kit-socrata-logo.png" class="img-responsive margin-bottom-60">
          </div>          
          <div class="col-sm-6 col-md-4">
            <ul class="no-bullets margin-bottom-60">
              <li><a href="https://socrata.com/wp-content/uploads/Socrata-Logos-1.zip" target="_blank" class="btn btn-primary btn-block"><i class="fa fa-download" aria-hidden="true"></i> Download Logos</a></li>
              <li><a href="https://socrata.com/wp-content/uploads/Socrata_brand_guide_May2016.pdf" target="_blank" class="btn btn-primary btn-block"><i class="fa fa-download" aria-hidden="true"></i> Download Guidelines</a></li>
            </ul>            
          </div>
          <div class="col-sm-12 col-md-8">            
            <h3>The Socrata Logo</h3>
            <ul>
              <li>The Socrata logo consists of two elements: a wordmark and a graphic device, or glyph.</li>
              <li>The Socrata logo should never be altered, nor standalone as text or an image without the distinctive blue rings glyph.</li>
              <li>The blue rings and orange wedge are a simple, yet powerful, graphical component. The glyph may be used only in instances where the complete logo is used nearby or on another piece of the same collateral, but it should never be used to replace the full logo.</li>
              <li>Additional information can be found in our <a href="/wp-content/uploads/Socrata_brand_guide_May2016.pdf" target="_blank">Brand Guidelines</a>.</li>
              <li>If you have any questions regarding the Socrata brand please contact: <a href="mailto:marketing@socrata.com">marketing@socrata.com</a></li>
            </ul>            
          </div>
        </div>
      </section>
      <!-- Section 4 -->
      <section id="section-4" class="section-padding" style="border-top:#d1d1d1 solid 1px;">
        <div class="row">
          <div class="col-sm-12">
            <h2 class="margin-bottom-60">Digital Assets</h2>
          </div>
          <div class="col-sm-12 col-md-6 margin-bottom-60">
            <div class="text-center margin-bottom-30"><img src="/wp-content/uploads/Kevin-Merritt-Headshot-sm.jpg" class="img-responsive img-circle"></div>
            <h4 class="text-center margin-bottom-0">Kevin Merritt</h4>
            <p class="text-center font-semi-bold">Founder and CEO</p>
            <p>Kevin founded Socrata because he recognized that the massive amounts of data in the world could be put to use in people’s everyday lives to make them better. Kevin had previous successes as an entrepreneur. In 2002 Kevin founded MessageRite, which developed and provided a web-based email archiving service. In 2004, FrontBridge Technologies acquired MessageRite where Kevin functioned as Chief Technology Officer (CTO). In 2005, Microsoft acquired FrontBridge. At Microsoft, Kevin served as Software Architect and Director of Operations before leaving to found Socrata in 2007. At heart, Kevin is a software entrepreneur with a passion for making customers elated by delivering great software as a service.</p>
            <p><a href="/wp-content/uploads/Kevin-Merritt-Headshot.png" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download Headshot</a></p>
          </div>
          <div class="col-sm-12 col-md-6 margin-bottom-60">
            <div class="text-center margin-bottom-30"><img src="/wp-content/uploads/Saf-Rabah-Headshot-sm.jpg" class="img-responsive img-circle"></div>
            <h4 class="text-center margin-bottom-0">Saf Rabah</h4>
            <p class="text-center font-semi-bold">Chief Product Officer</p>
            <p>Are you curious about all of the ways that open data can make the world a better place? Talk to Saf. An enthusiastic proponent of Socrata’s visionary customers, Safouen (Saf) has 17 years of technology product management and marketing experience in the enterprise software, internet services and communications industries. He served most recently as Marketing Director at Canadian telecommunications company, TELUS, leading the successful market introduction of cloud-based customer experience solutions that achieved 300 percent revenue growth over two years. Prior to working at TELUS, Saf was Director of eBusiness at customer relationship management software company Pivotal, which reached $65 million in revenue in five years and was acquired by CDC Software in 2003. Saf holds an executive MBA from Simon Fraser University.</p>
            <p><a href="/wp-content/uploads/Saf-Rabah-Headshot.png" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download Headshot</a></p>
          </div>
          <div class="col-sm-12">
            <h3>Downloads</h3>
            <table class="table table-striped">
              <tbody>
              <?php
                if ( !empty( $downloads ) ) {
                  foreach ( $downloads as $asset ) {
                    $title = isset( $asset['media_kit_download_asset_title'] ) ? $asset['media_kit_download_asset_title'] : '';
                    $files = isset( $asset['media_kit_download_asset'] ) ? $asset['media_kit_download_asset'] : '';
                    foreach ( $files as $file ) {
                      $file = RWMB_File_Field::file_info( $file, '' ); { ?>
                        <tr>
                          <td class="font-semi-bold" style="vertical-align:middle"><?php echo $title;?></td>
                          <td class="text-right"><a href="<?php echo $file['url'];?>" target="_blank" class="btn btn-sm btn-primary" style="padding:5px 10px;">Download</a></td>
                        </tr>
                      <?php }          
                    }
                  }
                }
              ?>
              </tbody>
            </table>            
          </div>
        </div>
      </section>
      <!-- Section 5 -->
      <section id="section-5" class="section-padding" style="border-top:#d1d1d1 solid 1px;">
        <div class="row">
          <div class="col-sm-12">
            <h2 class="margin-bottom-60">Company Timeline</h2>
            <?php if ( ! empty( $timeline ) ) {
              echo "<ul class='timeline'>";
              foreach ( $timeline as $date ) { 
                $year = isset( $date['media_kit_timeline_year'] ) ? $date['media_kit_timeline_year'] : '';
                $content = isset( $date['media_kit_timeline_content'] ) ? $date['media_kit_timeline_content'] : '';
                { ?>

                  <li>
                    <div class="timeline-badge"></div>
                    <div class="timeline-panel">
                      <div class="timeline-heading">
                        <h4 class="timeline-title margin-bottom-15"><?php echo $year;?></h4>
                      </div>
                      <div class="timeline-body">
                        <?php echo $content;?>
                      </div>
                    </div>
                  </li>

                <?php }
              }
              echo "</ul>";
            } ?>     
          </div>
        </div>
      </section>

    </div>
  </div>
</div>

<script type="text/javascript">
  $(function() {
    var offset = $("#sidebar").offset();
    var topPadding = 140;
    $(window).scroll(function() {
      if ($(window).scrollTop() > offset.top) {
        $("#sidebar").stop().animate({
          marginTop: $(window).scrollTop() - offset.top + topPadding
        });
        } else {
        $("#sidebar").stop().animate({
          marginTop: 0
        });
      };
    });
  });
</script>