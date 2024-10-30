=== Meta Functions Shortcode ===
Contributors: Noby
Donate link: http://www.matthiasamacher.ch/wordpress/meta-functions-shortcode
Tags: meta, custom fields, shortcode, plugin
Requires at least: 2.7
Tested up to: 2.7.1
Version: 3.4
Stable tag: trunk

The “Meta Functions Shortcode” Plugin installs the Shortcode ‘meta’. With it you can access the custom fields of your articles by a shortcode.

== Description ==

The “Meta Functions Shortcode” Plugin installs the Shortcode ‘meta’. With it you can access the custom fields of your articles by a simple shortcode.

Shortcode Syntax: <code>[meta func="" name="" alt=""]</code>

The shortcode has three functions yet:

1. func=”url”
      Displays a link using your custom field in “name” as the link target and “alt” as the caption. If “alt” is empty the link target is used as the caption.
1. func=”plain”
      Simply displays the custom field in “name” as plain text. If the field is empty it displays the text in “alt”.
1. func=”img”
      Inserts the image at the url in your custom field “name” into the article. The “alt” argument is used as the images alternative text attribute.
1. More functions in later versions of the plugin… Or extend the plugin on your own. See Extending the Plugin (under Other Notes) for details.

= Example =

<code>[meta func="url" name="download_url" alt="Download here"]</code>

This little code in your article displays a link using your custom field “download_url” as the link target and “Download here” as the caption. If you leave the “alt” argument empty then it uses the link target as the caption.

The resulting html code is:

<code><a href='{download_url}'>{alt}</a></code>

== Extending the Shortcode ==

= Method 1: Hardcoded =
You can extend the plugin by adding the PHP function

<code>function meta_functions_shortcode_{func}($meta, $alt="") {
       return "your desired text or generated html content";
}</code>

by writing your own plugin file with the function in it. Be sure to replace {func} with your desired function name (will result in the parameter “func” in the shortcode). $meta will be the value of the custom field and $alt the “alt” parameter.

The function should return the text by which the shortcode will be replaced. For example the “_plain” function simply returns $meta or $alt if $meta is empty.

= Method 2: In the admin panel =

This method is more convenient. You can add new functions in the admin panel. For this go to the options page called "Meta Functions Shortcode".
Here you can enter a new function name, specify parameter names and set the output html code with the parameters.

Example:

* Function Name: link
* Function Parameter Names: link_url, link_caption
* Function Result Code: <code><a href='{link_url}'>{link_caption}</a></code> 

If you now enter the shortcode [meta func="link" link_url="download_url" link_caption="download_caption"] then in the article/page 
it will be replaced by <code><a href='contents of custom field download_url'><i>contents of custom field download_caption</i></a></code>.

== Frequently Asked Questions ==

If you have questions or comments on the plugin, go to [www.MatthiasAmacher.ch](http://www.matthiasamacher.ch/wordpress/meta-functions-shortcode "Authors Blog")
 and wirte a comment on the article.


== Screenshots ==

1. This is a screenshot of the extionsion mechanism in the admin panel.


== Installation ==

Install it like every other wordpress plugin:

1. Upload the directory `meta-functions-shortcode` to the `/wp-content/plugins/` directory
1. Needed files: `meta-functions-shortcode.php`, `mfs_options_form.php`, `mfs_options_handler.php` and `mfs_scripts.js` (must be in the same directory)
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use it in your Wordpress articles or where your shortcodes are allowed
