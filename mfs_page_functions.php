<?php
function mfs_create_select_options($option, $values, $selected) {

	$name = "$option";
	$id = "$option";

	/* $onclick = "var selected = this.mfs_form.mfs_function_names.options[this.mfs_form.mfs_function_names.selectedIndex].value;
	 		alert('$action&mfs_function_name=' + selected);";*/
	$onclick = "var selected = mfs_form.mfs_function_names.options[mfs_form.mfs_function_names.selectedIndex].text;" .
	"show_function_d(selected)";

	echo '<select id="' . $name . '" name="' . $name . '">';

	foreach ($values as $k => $v) {
		$sel = "";
		if ($selected == $v) {
			$sel = " selected ";
		}
		echo "<option value='$k' onclick='$onclick' $sel>$v</option>\n";
	}
	echo '</select>';
}
?>


<?php $nonce = wp_create_nonce('meta_functions_shortcode'); ?>
<script  type='text/javascript'>
<!--
function show_function_d(func_name2){
	var fn = func_name2;
	jQuery.ajax({
		type: "post",
		dataType: "json",
		url: "admin-ajax.php",
		data: { action: 'showmfsfunction', func_name: fn, _ajax_nonce: '<?php echo $nonce; ?>' },
		success: function(obj){ //so, if data is retrieved, store it in html			
			//alert(obj);
			
  			document.forms['mfs_form'].mfs_function_name.value = obj.mfs_function_name;
  			document.forms['mfs_form'].mfs_function_args.value = obj.mfs_function_args;
  			document.forms['mfs_form'].mfs_function_result.value = obj.mfs_function_result;
			
		}
		
	}); //close jQuery.ajax(
	
} 
function delete_function_d(func_name2){
	var fn = func_name2;
	jQuery.ajax({
		type: "post",
		dataType: "json",
		url: "admin-ajax.php",
		data: { action: 'deletemfsfunction', func_name: fn, _ajax_nonce: '<?php echo $nonce; ?>' },
		success: function(obj){ //so, if data is retrieved, store it in html			
			// show first entry after delete!
			if (obj!=null){
  			document.forms['mfs_form'].mfs_function_name.value = obj.mfs_function_name;
  			document.forms['mfs_form'].mfs_function_args.value = obj.mfs_function_args;
  			document.forms['mfs_form'].mfs_function_result.value = obj.mfs_function_result;
  			}
  			else {
  			document.forms['mfs_form'].mfs_function_name.value = "";
  			document.forms['mfs_form'].mfs_function_args.value = "";
  			document.forms['mfs_form'].mfs_function_result.value = "";}	
  				
  			}
			
		
		
	}); //close jQuery.ajax(
	
} 
-->
</script>