<?php
/*
Plugin Name: Meta Functions Shortcode
Plugin URI: http://wordpress.org/extend/plugins/meta-functions-shortcode
Description: This plugin adds a shortcode of the form [meta func="" name="" alt=""]. Where {name} is the name of a meta tag in your article and {func} is the function to perform on it either (url, img or plain). {alt} is either the caption text of a URL or returned, if the meta is empty or the ALT tag of the image. This version lets you also define your own functions in the admin panel.
Author: Matthias Amacher
Version: 3.4
Author URI: http://www.matthiasamacher.ch
*/
/*  Copyright 2009 Matthias Amacher, Switzerland

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
class MetaFunctionsShortcode {
	var $referer = 'meta_functions_shortcode';
	var $option_name = 'meta_functions_shortcode';
	var $shortcode_name = 'meta';
	var $result_key = 'mfs_function_result';
	var $name_key = 'mfs_function_name';
	var $args_key = 'mfs_function_args';
	var $select_name = 'mfs_function_names';
	var $form_name = 'mfs_form';
	var $mfs_options = array ();

	function init() {
		/* add the shortcode */
		add_shortcode($this->shortcode_name, array (
			& $this,
			'meta_functions_shortcode'
		));
		$this->mfs_options = get_option($this->option_name);

		/* only add admin page if in admin panel */
		if (is_admin()) {
			add_action('admin_menu', array (
				& $this,
				'mfs_add_options'
			));

			add_action('wp_ajax_showmfsfunction', array (
				& $this,
				'mfs_do_show_function'
			));
			add_action('wp_ajax_deletemfsfunction', array (
				& $this,
				'mfs_do_delete_function'
			));
		}
	}
	/** 
	 * This method handler the original shortcode call
	 */
	function meta_functions_shortcode($atts) {
		global $post;
		extract(shortcode_atts(array (
			'func' => 'plain',
			'name' => '',
			'alt' => '',

			
		), $atts));
		if ($name == "") {
			return $this->mfs_docustom($atts);
		}
		$meta = get_post_meta($post->ID, $name, true);
		$meta_function = "meta_functions_shortcode_$func";

		// Check class for object methods
		if (method_exists($this, $meta_function)) {
			return $this-> $meta_function ($meta, $alt);
		}
		// Check outside of the class
		if (function_exists($meta_function)) {
			return $meta_function ($meta, $alt);
		}

		// check for custom functions
		return $this->mfs_docustom($atts);
	}

	/**
	 * Custom functions defined by the user in the options page
	 */
	function mfs_docustom($atts) {
		global $post;
		extract($atts);
		if ($func == "") {
			return "";
		}
		$opts = $this->mfs_options; //get_option($this->option_name);
		if (empty ($opts[$func])) {
			return "(mfs: undefined function)";
		}
		$o = $opts[$func];
		$args = $this->mfs_separate_by($o['mfs_function_args'], ",");
		$result = stripslashes($o['mfs_function_result']);
		$args_values = array ();
		foreach ($args as $arg) {
			$meta = get_post_meta($post->ID, $atts[$arg], true);
			$a = $atts[$arg];
			$result = str_replace('{' . $arg . '}', $meta, $result);
		}
		return $result;
	}
	
	/**
	 * Built-in function img
	 */
	function meta_functions_shortcode_img($meta, $alt = "") {
		if ($meta != "") {
			if ($alt != "") {
				return "<img src='$meta' alt='$alt'/>";
			} else {
				return "<img src='$meta'/>";
			}
		}
		return "";
	}
	
	/**
	 * Built-in function plain
	 */
	function meta_functions_shortcode_plain($meta, $alt = "") {
		if ($meta != "") {
			return $meta;
		} else {
			return $alt;
		}
	}
	
	/**
	 * Built-in function url
	 */
	function meta_functions_shortcode_url($meta, $alt = "") {
		if ($meta != "") {
			if ($alt != "") {
				return "<a href='$meta'>$alt</a>";
			} else {
				return "<a href='$meta'>$meta</a>";
			}
		}
		return "";
	}
	
	/**
	 * Add the options page to the admin panel
	 */
	function mfs_add_options() {
		$name = add_options_page('Meta Functions Shortcode', 'Meta Functions Shortcode', 8, __FILE__, array (
			& $this,
			'mfs_show_options'
		));
		add_action('admin_head-' . $name, array (
			$this,
			'add_javascript'
		));
	}

	/**
	 * 
 	 */
	function add_javascript() {
		$nonce = wp_create_nonce($this->referer);
		echo '<script type="text/javascript">';

		echo 'var form_name = "' . $this->form_name . '";';
		echo 'var select_name = "' . $this->select_name . '";';
		echo 'var nonce = "' . $nonce . '";';
		include ('mfs_scripts.js');
		echo "</script>";
	}

	/**
	 * Display the options page
	 */
	function mfs_show_options() {
		include ('mfs_options_handler.php');
		include ('mfs_options_form.php');
	}
	
	/**
	 * Create the select object to select the functions by name
	 */
	function mfs_create_select_options($option_name, $values, $selected) {
		$onclick = "on_function_select_click();";

		echo '<select style="width:200px;" id="' . $option_name . '" name="' . $option_name . '">';
		foreach ($values as $k => $v) {
			$sel = "";
			if ($selected == $v) {
				$sel = " selected ";
			}
			echo "<option value='$k' onclick='$onclick' $sel>$v</option>\n";
		}
		echo '</select>';
	}

	/**
	 * Ajax callback to insert a function by name into the options panel
	 */
	function mfs_do_show_function() {
		check_ajax_referer($this->referer);

		if (empty ($this->mfs_options)) {
			$this->mfs_options = get_option($this->option_name);
		}

		$o = $this->mfs_options[$_POST['func_name']];
		$o['mfs_function_result'] = stripslashes($o['mfs_function_result']);
		echo json_encode($o);
		die();
	}
	
	/**
	 * Ajax callback to delete the selected function
	 */
	function mfs_do_delete_function() {
		check_ajax_referer($this->referer);

		if (empty ($this->mfs_options)) {
			$this->mfs_options = get_option($this->option_name);
		}

		unset ($this->mfs_options[$_POST['func_name']]);

		update_option($this->option_name, $this->mfs_options);

		$this->mfs_options = get_option($this->option_name);
		foreach ($this->mfs_options as $key => $val) {
			echo json_encode($this->mfs_options[$key]);
			die();
		}
		die();
	}

	/**
	 * Helper function to separate a string by a string
	 * @return An array of strings
	 */
	function mfs_separate_by($string, $char = ",") {
		$result = array ();
		$str = "";
		for ($i = 0; $i < strlen($string); $i++) {
			$c = substr($string, $i, 1);
			if ($c == $char) {
				$result[] = $str;
				$str = "";
			} else {
				$str = $str . $c;
			}
		}
		$result[] = $str;
		return $result;
	}
}

/*
 * Create the Object instance and add the initialization hook
 */
$mfs = new MetaFunctionsShortcode();
add_action('plugins_loaded', array (
	& $mfs,
	'init'
));
?>