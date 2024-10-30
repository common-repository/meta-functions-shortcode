<div class="wrap">
<h2> <?php __('Meta Functions Shortcode Options') ?></h2>

<form name="mfs_form" id="mfs_form" method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<table class="form-table">
<tr><td>Existing Functions:</td><td>
<?php

$allfunctions = array_keys($opt_val);

$this->mfs_create_select_options("mfs_function_names", $allfunctions, $mfs_function_name);

$deleteonclick = "delete_onclick();";
$newonclick = "new_onclick();";
?>
</td><td><input type="button" name='mfs_delete_function' id='mfs_delete_function' value="Delete" onclick="<?=$deleteonclick ?>"/>
<input type="button" name='mfs_new_function' id='mfs_new_function' value="New" onclick="<?=$newonclick ?>"/></td>
</tr>

<tr valign="top">
<td><?php _e("Function Name"); ?></td> 
<td><input type="text" id="<?php echo 'mfs_function_name'; ?>" name="<?php echo 'mfs_function_name'; ?>" value="<?php echo $mfs_function_name; ?>" size="20"></td>
</tr>

<tr valign="top">
<td><?php _e("Function Parameter Names"); ?></td> 
<td><input type="text" id="<?php echo 'mfs_function_args'; ?>" name="<?php echo 'mfs_function_args'; ?>" value="<?php echo $mfs_function_args; ?>" size="20"></td>
<td>Comma separated list of arguments. <br>
For example: <code>link_url,link_caption</code><br>
The resulting shortcode function will be [meta func="functionname" link_url='' link_caption='']<td>
</tr>

<tr valign="top">
<td><?php _e("Function Result Code"); ?></td> 
<td><textarea type="text" id="<?php echo 'mfs_function_result'; ?>" name="<?php echo 'mfs_function_result'; ?>" size="20"><?php echo stripslashes($mfs_function_result); ?></textarea></td>
<td>Use the parameter names above to insert the values of the parameters.<br>
 e.g. <code>The caption:{link_caption} The URL: {link_url}</code>
<br>In HTML Code use ' instead of " !!!<td>
</tr>

</table>
<hr />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save Current Function') ?>" />
</p>

</form>
</div>