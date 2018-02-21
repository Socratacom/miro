<?

$prefix = 'socrata_videos_';

$fields = array(	
	array( // Single checkbox
		'label'	=> 'Featured Video', // <label>
		'desc'	=> 'Yes. This is a featured video.', // description
		'id'	=> $prefix.'featured', // field id and name
		'type'	=> 'checkbox' // type of field
	),
	array( // Text Input
		'label'	=> 'YouTube ID', // <label>
		'desc'	=> 'Exclude the "https://youtu.be/"', // description
		'id'	=> $prefix.'id', // field id and name
		'type'	=> 'text' // type of field
	),
	array(
	    'label' => 'Video Description',
	    'desc'  => '',
	    'id'    => 'editorField',
	    'type'  => 'editor',
	    'sanitizer' => 'wp_kses_post',
	    'settings' => array(
	        'textarea_name' => 'editorField'
	    )
	),	
);

// Get and return the values for the URL and description
function get_socrata_videos_meta() {
  global $post;
  $socrata_videos_featured = get_post_meta($post->ID, 'socrata_videos_featured', true); // 0
  $socrata_videos_id = get_post_meta($post->ID, 'socrata_videos_id', true); // 1
  $editorField = get_post_meta($post->ID, 'editorField', true); // 2

  return array(
	$socrata_videos_featured,
	$socrata_videos_id,
	$editorField
  );
}

/**
 * Instantiate the class with all variables to create a meta box
 * var $id string meta box id
 * var $title string title
 * var $fields array fields
 * var $page string|array post type to add meta box to
 * var $js bool including javascript or not
 */
$socrata_videos_box = new socrata_videos_custom_add_meta_box( 'socrata_videos_box', 'Video Details', $fields, 'socrata_videos', true );


