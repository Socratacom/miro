<?php
/*
Plugin Name: Socrata Events
Plugin URI: http://socrata.com/
Description: This plugin manages Socrata Events and Conferences.
Version: 1.0
Author: Michael Church
Author URI: http://socrata.com/
License: GPLv2
*/

add_action( 'init', 'create_socrata_events' );
function create_socrata_events() {
  register_post_type( 'socrata_events',
    array(
      'labels' => array(
        'name' => 'Events',
        'singular_name' => 'Events',
        'add_new' => 'Add New Event',
        'add_new_item' => 'Add New Event',
        'edit' => 'Edit Events',
        'edit_item' => 'Edit Events',
        'new_item' => 'New Event',
        'view' => 'View',
        'view_item' => 'View Event',
        'search_items' => 'Search Events',
        'not_found' => 'Not found',
        'not_found_in_trash' => 'Not found in Trash'
      ),
      'public' => true,
      'menu_position' => 5,
      'supports' => array( 'title', 'thumbnail' ),
      'taxonomies' => array( '' ),
      'menu_icon' => 'dashicons-calendar',
      'has_archive' => false,
      'rewrite' => array('with_front' => false, 'slug' => 'event'),
      'capability_type' => 'post',
    )
  );
}

// TAXONOMIES
add_action( 'init', 'socrata_events_cat', 0 );
function socrata_events_cat() {
  register_taxonomy(
    'socrata_events_cat',
    'socrata_events',
    array(
      'labels' => array(
        'name' => 'Events Category',
        'menu_name' => 'Events Category',
        'add_new_item' => 'Add New Category',
        'new_item_name' => "New Category"
      ),
      'show_ui' => true,
      'show_in_menu' => false,
      'show_tagcloud' => false,
      'hierarchical' => true,
      'sort' => true,      
      'args' => array( 'orderby' => 'term_order' ),
      'show_admin_column' => true,
      'rewrite' => array('with_front' => false, 'slug' => 'events-category'),
    )
  );
}

add_action( 'init', 'socrata_events_region', 0 );
function socrata_events_region() {
  register_taxonomy(
    'socrata_events_region',
    'socrata_events',
    array(
      'labels' => array(
        'name' => 'Region',
        'menu_name' => 'Region',
        'add_new_item' => 'Add New Region',
        'new_item_name' => "New Region"
      ),
      'show_ui' => false,
      'show_in_menu' => false,
      'show_tagcloud' => false,
      'hierarchical' => true,
      'sort' => true,      
      'args' => array( 'orderby' => 'term_order' ),
      'show_admin_column' => true,
      'rewrite' => array('with_front' => false, 'slug' => 'events-region'),
    )
  );
}

// CUSTOM COLUMS FOR ADMIN
add_filter( 'manage_edit-socrata_events_columns', 'socrata_events_edit_columns' ) ;
function socrata_events_edit_columns( $columns ) {
  $columns = array(
    'cb'          => '<input type="checkbox" />',    
    'title'       => __( 'Name' ),
    'category'    => __( 'Category' ),
    'eventdate'   => __( 'Event Date' ),

  );
  return $columns;
}
// Get Content for Custom Colums
add_action("manage_socrata_events_posts_custom_column",  "socrata_events_columns");
function socrata_events_columns($column){
  global $post;

  switch ($column) {    
    case 'eventdate':
      $timestamp = rwmb_meta( 'socrata_events_starttime' ); echo date("F j, Y, g:i a", $timestamp);
      break;
    case 'category':
      $segment = get_the_terms($post->ID , 'socrata_events_cat');
      echo $segment[0]->name;
      for ($i = 1; $i < count($segment); $i++) {echo ', ' . $segment[$i]->name ;}
      break;
  }
}

// Make these columns sortable
add_filter( "manage_edit-socrata_events_sortable_columns", "socrata_events_sortable_columns" );
function socrata_events_sortable_columns() {
  return array(
    'title'       => 'title',
    'category'    => 'category',
    'eventdate'   => 'eventdate',
  );
}

// Template Paths
add_filter( 'template_include', 'socrata_events_single_template', 1 );
function socrata_events_single_template( $template_path ) {
  if ( get_post_type() == 'socrata_events' ) {
    if ( is_single() ) {
      // checks if the file exists in the theme first,
      // otherwise serve the file from the plugin
      if ( $theme_file = locate_template( array ( 'single-events.php' ) ) ) {
        $template_path = $theme_file;
      } else {
        $template_path = plugin_dir_path( __FILE__ ) . 'single-events.php';
      }
    }
  }
  return $template_path;
}

// Print Taxonomy Names
function events_the_categories() {
  // get all categories for this post
  global $terms;
  $terms = get_the_terms($post->ID , 'socrata_events_cat');
  // echo the first category
  echo $terms[0]->name;
  // echo the remaining categories, appending separator
  for ($i = 1; $i < count($terms); $i++) {echo ', ' . $terms[$i]->name ;}
}

// Custom Body Class
add_action( 'body_class', 'socrata_events_body_class');
function socrata_events_body_class( $classes ) {
  if ( get_post_type() == 'socrata_events' && is_single() || get_post_type() == 'socrata_events' && is_archive() )
    $classes[] = 'socrata-events';
  return $classes;
}

// Fixes JS when Yoast enabled and thumbnail disabled
add_action( 'admin_enqueue_scripts', 'socrata_events_box_scripts' );
function socrata_events_box_scripts() {

    global $post;

    wp_enqueue_media( array( 
        'post' => $post->ID, 
    ) );

}


// Metabox
add_filter( 'rwmb_meta_boxes', 'socrata_events_register_meta_boxes' );
function socrata_events_register_meta_boxes( $meta_boxes )
{
  $prefix = 'socrata_events_';

  $meta_boxes[] = array(
    'title'				=> 'EVENT DETAILS',
    'post_types'	=> 'socrata_events',
    'context'			=> 'normal',
    'priority'		=> 'high',
    'validation'	=> array(
      'rules'			=> array(
        "{$prefix}endtime" => array(
            'required'  => true,
        ),
      ),
    ),

    'tabs' => array(
			'overview' 			=> 'Overview',
			'speakers' 			=> 'Speakers',
			'agenda' 				=> 'Agenda',
			'venue' 				=> 'Venue',			
			'registration' 	=> 'Registration',
		),

		// Tab style: 'default', 'box' or 'left'. Optional
		'tab_style' => 'box',
		'geo' => true,
    'fields' => array(

			// HEADING
			array(
				'type' => 'heading',
				'name' => 'Event date and time',
				'tab'  => 'overview',
			),			
			// DATE
			array(
				'name'       => 'Start Date',
				'id'         => "{$prefix}starttime",
				'type'       => 'date',
				'timestamp'  => true,
				'js_options' => array(
					'numberOfMonths'  => 2,
          'showButtonPanel' => true,
				),
				'tab'  => 'overview',
			),
			// DATE
			array(
				'name'       => 'End Date',
				'id'         => "{$prefix}endtime",
				'type'       => 'date',
				'timestamp'  => true,
				'js_options' => array(
					'numberOfMonths'  => 2,
          'showButtonPanel' => true,
				),
				'tab'  => 'overview',
			),
			// TIME
			array(
				'name'       => 'Start Time',
				'id'         => "{$prefix}starttime_1",
				'type'       => 'time',
				'js_options' => array(
					'stepMinute'     => 5,
					'showTimepicker' => true,
					'controlType'    => 'select',
				),
				'tab'  => 'overview',
			),	
			// TIME
			array(
				'name'       => 'End Time',
				'id'         => "{$prefix}endtime_1",
				'type'       => 'time',
				'js_options' => array(
					'stepMinute'     => 5,
					'showTimepicker' => true,
					'controlType'    => 'select',
				),
				'tab'  => 'overview',
			),
			// SELECT
			array(
				'name'	=> 'Time Zone',
				'id'	=> "{$prefix}timezone",
				'type'	=> 'select',
				'options'	=> array(
					'PT' => 'PST',
					'CT' => 'CST',
					'ET' => 'EST',
				),
				'multiple'	=> false,
				'placeholder'	=> 'Select a time zone',
				'select_all_none'	=> false,
				'tab'	=> 'overview',
			),
			// TEXT
      array(
        'name'  => 'Display Date and Time',
        'id'    => "{$prefix}displaydate",
        'desc' 	=> 'OBSOLETE. NO LONGER USED.',
        'type'  => 'text',
        'clone' => false,
				'tab'  	=> 'overview',
      ),
			// HEADING
			array(
				'type' => 'heading',
				'name' => 'Overview Content',
				'tab'  => 'overview',
			),
			// WYSIWYG/RICH TEXT EDITOR
			array(
				'id'      => "{$prefix}wysiwyg",
				'type'    => 'wysiwyg',
				'raw'     => false,
				'options' => array(
					'textarea_rows' => 10,
					'teeny'         => false,
					'media_buttons' => false,
				),
				'tab'  => 'overview',
			),
			// HEADING
			array(
				'type' => 'heading',
				'name' => 'Speaker Content',
				'tab'  => 'speakers',
			),
			// GROUP
			array(
				'id'     => "{$prefix}speakers",
				'type'   => 'group',
				'clone'  => true,
				'sort_clone' => true,
				// Sub-fields
				'fields' => array(
					array(
						'name' => 'Name',
						'id'   => "{$prefix}speaker_name",
						'type' => 'text',
					),
					array(
						'name' => 'Title',
						'id'   => "{$prefix}speaker_title",
						'type' => 'text',
					),
					array(
						'name' => 'Headshot',
						'id'   => "{$prefix}speaker_headshot",
						'desc' => 'Minimum size 300x300 pixels.',
						'type' => 'image_advanced',
						'max_file_uploads' => 1,
					),
					array(
						'name'		=> 'Short Bio',
						'id'      => "{$prefix}speaker_bio",
						'type'    => 'wysiwyg',
						'raw'     => false,
						'options' => array(
							'textarea_rows' => 10,
							'teeny'         => false,
							'media_buttons' => false,
						),
					),
				),
				'tab'  => 'speakers',
			),
			// HEADING
			array(
				'type' => 'heading',
				'name' => 'Agenda Content',
				'tab'  => 'agenda',
			),
			// GROUP
			array(
				'id'     => "{$prefix}agenda",
				'type'   => 'group',
				'clone'  => true,
				'sort_clone' => true,
				// Sub-fields
				'fields' => array(					
					array(
						'name'       => 'Time',
						'id'         => "{$prefix}agenda_time",
						'type'       => 'time',
						'js_options' => array(
							'stepMinute' => 5,
							'controlType'     => 'select',
						),
					),
					array(
						'name'  => 'Title',
						'id'    => "{$prefix}agenda_title",
						'type'  => 'text',
					),
					array(
						'name'  => 'Speaker(s)',
						'id'    => "{$prefix}agenda_speakers",
						'desc'  => 'ie. John Doe, Jane Doe, etc.',
						'type'  => 'text',
						'clone' => true,
					),
					array(
						'name' => 'Description',
						'id'   => "{$prefix}agenda_description",
						'type' => 'textarea',
						'cols' => 20,
						'rows' => 5,
					),					
				),
				'tab'  => 'agenda',
			),
			// HEADING
			array(
				'type' => 'heading',
				'name' => 'Venue Address',
				'desc' => 'As you enter an address, be sure to select an address from the dropdown in order to auto-populate all the address fields.',
				'tab'  => 'venue',
			),
			// TEXT
			array(
				'type' => 'text',
				'name' => 'Venue Name',
				'id'   => "{$prefix}venue",
				'desc' => 'ie. Seattle Convention Center',
				'tab'  => 'venue',
			),
			// TEXT
			array(
				'type' => 'text',
				'name' => 'Booth Number',
				'id'   => "{$prefix}booth",
				'tab'  => 'venue',
			),
			// TEXT
			array(
				'type' => 'text',
				'name' => 'Address',
				'id'   => "{$prefix}address",
				'tab'  => 'venue',
			),
			// TEXT
			array(
				'type' => 'text',
				'name' => 'Street Number',
				'id'   => "{$prefix}street_number",
				'binding' => 'street_number',
				'tab'  => 'venue',
			),
			// TEXT
			array(
				'type' => 'text',
				'name' => 'Route',
				'id'   => "{$prefix}route",
				'binding' => 'route',
				'tab'  => 'venue',
			),
			// TEXT
			array(
				'type' => 'text',
				'name' => 'City',
				'id'   => "{$prefix}locality",
				'binding' => 'locality',
				'tab'  => 'venue',
			),
			// SELECT
			array(
				'type' => 'select',
				'name' => 'State',
				'id'   => "{$prefix}administrative_area_level_1_short",
				'placeholder' => 'Select a State',
				'options' => array(
					'AL' => 'AL',
					'AK' => 'AK',
					'AZ' => 'AZ',
					'AR' => 'AR',
					'CA' => 'CA',
					'CO' => 'CO',
					'CT' => 'CT',
					'DE' => 'DE',
					'DC' => 'DC',
					'FL' => 'FL',
					'GA' => 'GA',
					'HI' => 'HI',
					'ID' => 'ID',
					'IL' => 'IL',
					'IN' => 'IN',
					'IA' => 'IA',
					'KS' => 'KS',
					'KY' => 'KY',
					'LA' => 'LA',
					'ME' => 'ME',
					'MD' => 'MD',
					'MA' => 'MA',
					'MI' => 'MI',
					'MN' => 'MN',
					'MS' => 'MS',
					'MO' => 'MO',
					'MT' => 'MT',
					'NE' => 'NE',
					'NV' => 'NV',
					'NH' => 'NH',
					'NJ' => 'NJ',
					'NM' => 'NM',
					'NY' => 'NY',
					'NC' => 'NC',
					'ND' => 'ND',
					'OH' => 'OH',
					'OK' => 'OK',
					'OR' => 'OR',
					'PA' => 'PA',
					'RI' => 'RI',
					'SC' => 'SC',
					'SD' => 'SD',
					'TN' => 'TN',
					'TX' => 'TX',
					'UT' => 'UT',
					'VT' => 'VT',
					'VA' => 'VA',
					'WA' => 'WA',
					'WV' => 'WV',
					'WI' => 'WI',
					'WY' => 'WY'
				),
				'binding' => 'short:administrative_area_level_1',
				'tab'  => 'venue',
			),
			// NUMBER
			array(
				'type' => 'number',
				'name' => 'Post Code',
				'id'   => "{$prefix}postal_code",
				'binding' => 'postal_code',
				'tab'  => 'venue',
			),
			// TEXT
			array(
				'type' => 'text',
				'name' => 'Geometry',
				'id'   => "{$prefix}geometry",
				'binding' => 'lat + "," + lng',
				'tab'  => 'venue',
			),
			//HEADING
			array(
				'type' => 'heading',
				'name' => 'Event Links',
				'tab'  => 'venue',
			),
			// URL
      array(
      	'name' => 'Venue URL',
        'id'   => "{$prefix}venue_url",
        'desc' => 'Example: http://some-event-site.com',
        'type' => 'url',
				'tab'  => 'venue',
      ),
      // URL
      array(
      	'name' => 'Event URL',
        'id'   => "{$prefix}url",
        'desc' => 'Example: http://some-event-site.com',
        'type' => 'url',
				'tab'  => 'venue',
      ),
			//HEADING
			array(
				'type' => 'heading',
				'name' => 'Community of Practice Region',
				'tab'  => 'venue',
			),
			// TAXONOMY
			array(
				'name'       => 'Region',
				'id'         => "{$prefix}region_taxonomy",
				'type'       => 'taxonomy',
				'clone'      => false,
				'taxonomy'   => 'socrata_events_region',
				'field_type' => 'select_advanced',
				'query_args' => array(),
				'tab'  => 'venue',
			),
			//HEADING
			array(
				'type' => 'heading',
				'name' => 'Eventbrite Information',
				'tab'  => 'registration',
			),
			// URL
			array(
				'name' => 'Eventbrite URL', 'socrata_events_',
				'id'   => "{$prefix}eventbrite_url",
				'desc' => 'ie. https://YOUR-EVENT-URL',
				'type' => 'url',
				'tab'  => 'registration',
			),
			// NUMBER
			array(
				'name' => 'Eventbrite ID',
				'id'   => "{$prefix}eventbrite_id",
				'desc' => 'Copy/paste the ID number at the end of the Eventbrite URL. ie. 1234567890',
				'type' => 'number',
				'min'  => 0,
				'tab'  => 'registration',
			),
			// TEXT
      array(
        'name'  => 'CTA Button Text',
        'id'    => "{$prefix}button_text",
        'desc' 	=> 'Example: Get Tickets',
        'type'  => 'text',
        'clone' => false,
				'tab'  => 'registration',
      ),
    )
  );

  return $meta_boxes;
}

// Shortcode [current-events]
function events_posts($atts, $content = null) {
  ob_start();
  ?>
  <section class="section-padding">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h1 class="font-light margin-bottom-60">Events Calendar</h1>
        </div>

        <?php

        $today = strtotime('today UTC');
        $args = array(
          'post_type' => 'socrata_events',
          'post_status' => 'publish',
          'ignore_sticky_posts' => true,
          'meta_key' => 'socrata_events_starttime',
          'orderby' => 'meta_value_num',
          'order' => 'asc',
          'posts_per_page' => 1,
          'meta_query' => array(
              'relation' => 'AND',
              array(
                'key' => 'socrata_events_endtime',
                'value' => $today,
                'compare' => '>='
              )
            )
        );
        $args2 = array(
          'post_type' => 'socrata_events',
          'post_status' => 'publish',
          'ignore_sticky_posts' => true,
          'meta_key' => 'socrata_events_starttime',
          'orderby' => 'meta_value_num',
          'order' => 'asc',
          'posts_per_page' => 100,
          'offset' => 1,
          'meta_query' => array(
              'relation' => 'AND',
              array(
                'key' => 'socrata_events_endtime',
                'value' => $today,
                'compare' => '>='
              )
            )
        );

        // The Query
        $query1 = new WP_Query( $args );

        if ( $query1->have_posts() ) {          
          echo '<div class="col-sm-12">';
          // The Loop
          while ( $query1->have_posts() ) {
            $query1->the_post();
            $date = rwmb_meta( 'socrata_events_starttime' );
            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'feature-image' );
            $url = $thumb['0'];
            $img_id = get_post_thumbnail_id(get_the_ID());
            $alt_text = get_post_meta($img_id , '_wp_attachment_image_alt', true);
            $city = rwmb_meta( 'socrata_events_locality' );
            $state = rwmb_meta( 'socrata_events_administrative_area_level_1_short' ); ?>
              <div class="feature-event">
                <div class="feature-event-image sixteen-nine img-background" style="background-image:url('<?php echo $url;?>')"></div>
                <div class="feature-event-meta">
                  <div class="date">
                    <div class="day"><?php echo date('j', $date);?></div>
                    <div class="month"><?php echo date('M', $date);?></div>
                  </div>
                  <div class="meta">
                    <div class="category"><?php events_the_categories(); ?></div>
                    <h3 class="title"><?php the_title(); ?></h3>
                    <div class="location"><?php echo $city;?>, <?php echo $state;?></div>
                  </div>
                </div>
                <a href="<?php the_permalink() ?>" class="link"></a>
                <?php echo do_shortcode('[image-attribution]'); ?>
              </div>
            <?php
          }
          wp_reset_postdata();
          echo '</div>';
        } else { ?>
            <div class="col-sm-12">
              <div class="alert alert-info">
                <strong>No events are scheduled at this time.</strong> Do you know of an event we should attend? Suggest an event.
              </div>
            </div>
          <?php
        }

        /* The 2nd Query */
        $query2 = new WP_Query( $args2 );

        if ( $query2->have_posts() ) { ?>
          <div class="col-sm-12 col-md-10 col-md-offset-1">
          <h2 class="font-light margin-bottom-30 padding-bottom-30" style="border-bottom:#ebebeb solid 1px">Additional Events</h2>
          <table class="events-list">
          <?php

          // The 2nd Loop
          while ( $query2->have_posts() ) {
            $query2->the_post();
            $date = rwmb_meta( 'socrata_events_starttime' );
            $city = rwmb_meta( 'socrata_events_locality' );
            $state = rwmb_meta( 'socrata_events_administrative_area_level_1_short' ); ?>

            <tr class="event">
            <td class="date">
            <div class="day"><?php echo date('j', $date);?></div>
            <div class="month"><?php echo date('M', $date);?></div>
            <a href="<?php the_permalink() ?>"></a>
            </td>
            <td class="meta">
            <div class="category"><?php events_the_categories(); ?></div>
            <h3 class="title"><?php the_title(); ?></h3>
            <div class="location"><?php echo $city;?>, <?php echo $state;?></div>   
            <a href="<?php the_permalink() ?>"></a>         
            </td>
            </tr>

            <?php
          }
          wp_reset_postdata(); ?>
          </table>
          </div>
          <?php
        } 

        ?>

      </div>
    </div>
  </section>

  <?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
add_shortcode('current-events', 'events_posts');



// Shortcode [events-map]
function events_map($atts, $content = null) {
  ob_start();
  ?>
    <script>jQuery(function(n){n(".map-button").click(function(){n(".overlay").hide()})});</script>
    <script>
    jQuery(function($) {
        // Asynchronously Load the map API 
        var script = document.createElement('script');
        script.src = "//maps.googleapis.com/maps/api/js?key=AIzaSyD_STOs8I4L5GTLlDIu5aZ-pLs2L69wHMw&callback=initialize";
        document.body.appendChild(script);
    });

    function initialize() {
        var map;
        var bounds = new google.maps.LatLngBounds();
        var mapOptions = {
          scrollwheel: false,
          styles: [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#93d2ec"},{"visibility":"on"}]}]
        };
                        
        // Display a map on the page
        map = new google.maps.Map(document.getElementById("events-map"), mapOptions);
        map.setTilt(45);
            
        // Multiple Markers
        var markers = [
          <?php
            $today = strtotime('today UTC');
            $args = array(
              'post_type' => 'socrata_events',
              'post_status' => 'publish',
              'ignore_sticky_posts' => true,
              'meta_key' => 'socrata_events_starttime',
              'orderby' => 'meta_value_num',
              'order' => 'asc',
              "posts_per_page" => 100,
              "meta_query" => array(    
                array(
                  'key'     => 'socrata_hidden_hide',
                  'value' => '0',
                ),
                'relation' => 'AND',
                array(
                  "key" => "socrata_events_endtime",
                  "value" => "$today",
                  "compare" => ">="
                )
              )
            );
            $query = new WP_Query( $args );

            // The Loop
            while ( $query->have_posts() ) {
              $query->the_post();
              $pin = rwmb_meta( 'socrata_events_geometry' ); { ?>
              ['<?php the_title();?>',<?php echo $pin;?>],
              <?php
              };
            }
            wp_reset_postdata();
          ?>
        ];
                            
        // Info Window Content
        var infoWindowContent = [
        <?php
            $today = strtotime('today UTC');
            $args = array(
              'post_type' => 'socrata_events',
              'post_status' => 'publish',
              'ignore_sticky_posts' => true,
              'meta_key' => 'socrata_events_starttime',
              'orderby' => 'meta_value_num',
              'order' => 'asc',
              "posts_per_page" => 100,
              "meta_query" => array(    
                array(
                  'key'     => 'socrata_hidden_hide',
                  'value' => '0',
                ),
                'relation' => 'AND',
                array(
                  "key" => "socrata_events_endtime",
                  "value" => "$today",
                  "compare" => ">="
                )
              )
            );
            $query = new WP_Query( $args );

            // The Loop
            while ( $query->have_posts() ) {
              $query->the_post();
              $displaydate = rwmb_meta( 'socrata_events_displaydate' );
              $eventsurl = rwmb_meta( 'socrata_events_url' ); { ?>                
                <?php if ( has_term( 'socrata-event','socrata_events_cat' ) ) { ?>
                  ['<small style="text-transform:uppercase;"><?php events_the_categories(); ?></small><br><strong><a href="<?php the_permalink() ?>"><?php the_title();?></a></strong><br><?php echo $displaydate;?>'],<?php }
                  else { ?>
                  ['<small style="text-transform:uppercase;"><?php events_the_categories(); ?></small><br><?php if ( ! empty( $eventsurl ) ) { ?><strong><a href="<?php echo $eventsurl;?>" target="_blank"><?php the_title();?></a></strong> <?php } else { ?><strong><?php the_title();?></strong><?php } ?><br><?php echo $displaydate;?>'],<?php }
                ?>
              <?php
              };
            }
            wp_reset_postdata();
          ?>
        ];
            
        // Display multiple markers on a map
        var infoWindow = new google.maps.InfoWindow(), marker, i;
        
        // Loop through our array of markers & place each one on the map  
        for( i = 0; i < markers.length; i++ ) {
            var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
            bounds.extend(position);
            marker = new google.maps.Marker({
                position: position,
                map: map,
                title: markers[i][0]
            });
            
            // Allow each marker to have an info window    
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infoWindow.setContent(infoWindowContent[i][0]);
                    infoWindow.open(map, marker);
                }
            })(marker, i));

            // Automatically center the map fitting all markers on the screen
            map.fitBounds(bounds);
        }

        // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
        var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
            this.setZoom(3);
            google.maps.event.removeListener(boundsListener);
        });
        
    }
    </script>

  <?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
add_shortcode('events-map', 'events_map');


// COP Query [ ]
function cop_query($atts, $content = null) {
  extract( shortcode_atts( array(
    'region' => '',
    ), $atts ) );
    ob_start();
    $today = strtotime('today UTC');
    $args = array(
    'post_type' => 'socrata_events',
    'socrata_events_cat' => 'community-of-practice',
    'socrata_events_region' => $region,
		'post_status' => 'publish',
    'ignore_sticky_posts' => true,
    'meta_key' => 'socrata_events_starttime',
    'orderby' => 'meta_value_num',
    'order' => 'asc',
    'posts_per_page' => 10,
    'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => 'socrata_events_endtime',
				'value' => $today,
				'compare' => '>='
			)
		)
  );
  
$myquery = new WP_Query( $args );

?>
<div id="<?php echo $region;?>" class="col-sm-12 col-md-10 col-md-offset-1">
<h3 class="margin-bottom-30"><?php $term = get_term_by( 'slug', $region, 'socrata_events_region' ); $name = $term->name; echo $name; ?></h3>
<table class="events-list">
<?php


if($myquery->have_posts()) : 
while($myquery->have_posts()) : 
$myquery->the_post();
$date = rwmb_meta( 'socrata_events_starttime' );
$city = rwmb_meta( 'socrata_events_locality' );
$state = rwmb_meta( 'socrata_events_administrative_area_level_1_short' );
?>

<tr class="event">
<td class="date">
<div class="day"><?php echo date('j', $date);?></div>
<div class="month"><?php echo date('M', $date);?></div>
<a href="<?php the_permalink() ?>"></a>
</td>
<td class="meta" style="border:none;">
<div class="category"><?php events_the_categories(); ?></div>
<h3 class="title"><?php the_title(); ?></h3>
<div class="location"><?php echo $city;?>, <?php echo $state;?></div>   
<a href="<?php the_permalink() ?>"></a>         
</td>
</tr>   

<?php
endwhile;
else: 
?>

<div class="alert alert-info">
No events are scheduled for <strong><?php echo $name;?></strong> at this time.
</div>

<?php
endif;
wp_reset_postdata();

?>
</table>
<hr/>
</div>

<?php

  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
add_shortcode('cop-query', 'cop_query');


// Shortcode [cop-map]
function cop_map($atts, $content = null) {
  ob_start();
  ?>
    <script>jQuery(function(n){n(".map-button").click(function(){n(".overlay").hide()})});</script>
    <script>
    jQuery(function($) {
        // Asynchronously Load the map API 
        var script = document.createElement('script');
        script.src = "//maps.googleapis.com/maps/api/js?key=AIzaSyD_STOs8I4L5GTLlDIu5aZ-pLs2L69wHMw&callback=initialize";
        document.body.appendChild(script);
    });

    function initialize() {
        var map;
        var bounds = new google.maps.LatLngBounds();
        var mapOptions = {
          scrollwheel: false,
          styles: [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#93d2ec"},{"visibility":"on"}]}]
        };
                        
        // Display a map on the page
        map = new google.maps.Map(document.getElementById("events-map"), mapOptions);
        map.setTilt(45);
            
        // Multiple Markers
        var markers = [
          <?php
            $today = strtotime('today UTC');
            $args = array(
              'post_type' => 'socrata_events',
              'socrata_events_cat' => 'community-of-practice',
              'post_status' => 'publish',
              'ignore_sticky_posts' => true,
              'meta_key' => 'socrata_events_starttime',
              'orderby' => 'meta_value_num',
              'order' => 'asc',
              "posts_per_page" => 100,
              "meta_query" => array(    
                array(
                  'key'     => 'socrata_hidden_hide',
                  'value' => '0',
                ),
                'relation' => 'AND',
                array(
                  "key" => "socrata_events_endtime",
                  "value" => "$today",
                  "compare" => ">="
                )
              )
            );
            $query = new WP_Query( $args );

            // The Loop
            while ( $query->have_posts() ) {
              $query->the_post();
              $pin = rwmb_meta( 'socrata_events_geometry' ); { ?>
              ['<?php the_title();?>',<?php echo $pin;?>],
              <?php
              };
            }
            wp_reset_postdata();
          ?>
        ];

        // Info Window Content
        var infoWindowContent = [
        <?php
            $today = strtotime('today UTC');
            $args = array(
              'post_type' => 'socrata_events',
              'socrata_events_cat' => 'community-of-practice',
              'post_status' => 'publish',
              'ignore_sticky_posts' => true,
              'meta_key' => 'socrata_events_starttime',
              'orderby' => 'meta_value_num',
              'order' => 'asc',
              "posts_per_page" => 100,
              "meta_query" => array(    
                array(
                  'key'     => 'socrata_hidden_hide',
                  'value' => '0',
                ),
                'relation' => 'AND',
                array(
                  "key" => "socrata_events_endtime",
                  "value" => "$today",
                  "compare" => ">="
                )
              )
            );
            $query = new WP_Query( $args );

            // The Loop
            while ( $query->have_posts() ) {
              $query->the_post();
              $displaydate = rwmb_meta( 'socrata_events_displaydate' ); { ?>
              ['<small style="text-transform:uppercase;"><?php events_the_categories(); ?></small><br><strong><a href="<?php the_permalink() ?>"><?php the_title();?></a></strong><br><?php echo $displaydate;?>'],
              <?php 
	            };
	          }
            wp_reset_postdata();
          ?>
        ];

        // Display multiple markers on a map
        var infoWindow = new google.maps.InfoWindow(), marker, i;
        
        // Loop through our array of markers & place each one on the map  
        for( i = 0; i < markers.length; i++ ) {
            var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
            bounds.extend(position);
            marker = new google.maps.Marker({
                position: position,
                map: map,
                title: markers[i][0]
            });
            
            // Allow each marker to have an info window    
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infoWindow.setContent(infoWindowContent[i][0]);
                    infoWindow.open(map, marker);
                }
            })(marker, i));

            // Automatically center the map fitting all markers on the screen
            map.fitBounds(bounds);
        }

        // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
        var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
            this.setZoom(4);
            google.maps.event.removeListener(boundsListener);
        });
        
    }
    </script>

  <?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
add_shortcode('cop-map', 'cop_map');