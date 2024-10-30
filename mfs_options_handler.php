<?php

// variables for the field and option names 
$opt_name = 'meta_functions_shortcode';
$hidden_field_name = 'meta_functions_shortcode_submit';

$data_field_names = array (
	'mfs_function_name',
	'mfs_function_result',
	'mfs_function_args'
);

// Read in existing option value from database
$opt_val = get_option($opt_name);

// See if the user has posted us some information
// If they did, this hidden field will be set to 'Y'
if ($_POST[$hidden_field_name] == 'Y') {
	// Read their posted values
	$function_name = $_POST['mfs_function_name'];

	$opt_val[$function_name] = array ();

	foreach ($data_field_names as $key) {
		$opt_val[$function_name][$key] = $_POST[$key];
	}

	// Save the posted value in the database
	update_option($opt_name, $opt_val);

	extract($opt_val[$function_name]);
	
	?>
	<div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div>
	<?php

}
// Now display the options editing screen
?>