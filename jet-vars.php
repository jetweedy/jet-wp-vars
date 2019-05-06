<?php
/**
 * @package JET Vars
 * @version 1.6
 */
/*
Plugin Name: JET Vars
Description: Enables variable storage and reference in content.
Author: Jonathan Tweedy
Version: 1.0
Author URI: http://jonathantweedy.com
*/


function jet_vars( $atts, $cont ){
	$content = "[no var specified]";
	if (isset($atts['var'])) {
		$json = get_option('jet_vars_json');
		$data = json_decode($json);
		if (isset($data->{$atts['var']})) {
			$content = $data->{$atts['var']};
		} else {
			$content = "[no val found]";
		}
	}
	return $content;
}
add_shortcode( 'jet-var', 'jet_vars' );


function jet_vars_fun() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized user');
    }
    if (isset($_POST['jet_vars_json'])) {
		$json = $_POST['jet_vars_json'];
		$json = stripslashes($json);
		$test = json_decode($json);
		if($test === null) {
			print "
				<p style='color:red;'>Invalid JSON</p>
			";
		} else {
			update_option('jet_vars_json', $json);
		}
    }
    $value = get_option('jet_vars_json', '{}');
	$json = json_encode($value);
	print "
		<style>
		".file_get_contents(__DIR__."/jet-vars.css")."
		</style>
		<h1>JET Variables</h1>
		<form method=\"POST\">
			<input type='hidden' name='_jet_vars_json' />
			<textarea name='jet_vars_json' id='jet_vars_json' style='width:99%; height:300px;'>".$value."</textarea>
			<table id='jet_vars_table'><thead>
				<tr><th>Key</th><th>Value</th></tr>
			</thead><tbody id='jet_vars'></tbody>
			</table>
	";
	$options = json_decode($value);
	print "
			<input type='submit' value='Save' class='button button-primary button-large'>
		</form>
		<script>
		var JETVARS = JSON.parse(" . $json . ");	
		".file_get_contents(__DIR__."/jet-vars.js")."
		</script>
	";
	
}


add_action('admin_menu', 'jet_vars_options_page_create');
function jet_vars_options_page_create() {
    $page_title = 'JET Variables';
    $menu_title = 'JET Variables';
    $capability = 'edit_posts';
    $menu_slug = 'jet_vars';
    $function = 'jet_vars_fun';
    $icon_url = '';
    $position = 100;
    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
}

