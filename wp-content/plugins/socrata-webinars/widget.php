<?php

$custom_socrata_webinars_dashboard_widget = array(
    'socrata_webinars_dashboard_widget' => array(
    'title' => 'Webinars',
    'callback' => 'socrata_webinars_dashboardWidgetContent'
    )
);

 function socrata_webinars_dashboardWidgetContent() {

    $args = array(
      'post_type' => 'socrata_webinars',
    );
    $myquery = new WP_Query($args);
    echo "<div style='text-align:center; padding:30px;'><p style='font-size:50px; margin:0;'>$myquery->found_posts <span style='font-size:14px;'>Webinars</span></p></div>";
    echo "<a href='/wp-admin/post-new.php?post_type=socrata_webinars' style='background-color:#3498db; padding:5px 10px; color:#ffffff; border-radius:2px;'>Add New</a>";
    wp_reset_postdata();

}