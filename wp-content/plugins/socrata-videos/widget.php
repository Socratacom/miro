<?php

$custom_socrata_videos_dashboard_widget = array(
    'socrata_videos_dashboard_widget' => array(
    'title' => 'Videos',
    'callback' => 'socrata_videos_dashboardWidgetContent'
    )
);

 function socrata_videos_dashboardWidgetContent() {

    $args = array(
      'post_type' => 'socrata_videos',
    );
    $myquery = new WP_Query($args);
    echo "<div style='text-align:center; padding:30px;'><p style='font-size:50px; margin:0;'>$myquery->found_posts <span style='font-size:14px;'>Videos</span></p></div>";
    echo "<a href='/wp-admin/post-new.php?post_type=socrata_videos' style='background-color:#3498db; padding:5px 10px; color:#ffffff; border-radius:2px;'>Add New</a>";
    wp_reset_postdata();

}