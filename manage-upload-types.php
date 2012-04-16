<?php
/*
Plugin Name: Manage Upload Types
Plugin URI: http://www.madea.net/projects/wordpress/plugins/manage-upload-types
Description: Allows management of allowed upload file extensions.
Version: 0.1
Author: Jeremy Madea
Author URI: http://madea.net/
License: GPL2
*/

/* ***********************************************************************
   ***********************************************************************

   WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING


   This is unreleased ALPHA quality software . . . Use at your own risk!!! 


   WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING

   ***********************************************************************
   ***********************************************************************
*/


/*  Copyright 2012 Jeremy Madea

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


register_activation_hook( __FILE__, 'jm_mut_install' ); 

/**
 * Callback for activation hook.
 * 
 * Adds the jm_mut_mime_types option and initializes it to 
 * contain the currently allowed mime_types.
 * 
*/
function jm_mut_install() { 
    $mime_types = get_allowed_mime_types(); 
    add_option('jm_mut_mime_types', $mime_types); 
}

/**
 * Load mime types array from database.
 * 
 * Gets this plugin's mime_types array if it exists and 
 * adds it otherwise. 
*/
function jm_mut_load_mime_types($mime_types=array()) { 
    $jm_mut_mime_types = get_option( 'jm_mut_mime_types' ); 
    if ($jm_mut_mime_types === false) { 
        add_option( 'jm_mut_mime_types', $mime_types ); 
    } 
    return $jm_mut_mime_types;
}

add_filter( 'upload_mimes', 'jm_mut_load_mime_types');



/**
 * Callback for admin_init
 *
 * Creates the admin section for this plugin.
 *
*/
function jm_mut_settings_api_init() {
	add_settings_section('jm_mut_setting_section',
		'Manage Upload Settings',
		'jm_mut_setting_section_callback',
		'media');
 	
}// jm_mut_settings_api_init()
 
add_action('admin_init', 'jm_mut_settings_api_init');
 
  
 
/**
 * Callback for settings section.
 *
 * Creates the content for this plugin's admin section.
 *
*/
function jm_mut_setting_section_callback() {
	echo '<p>The extensions below are those permitted for uploaded files.</p>' . "\n";
	$jm_mut_mime_types = get_option('jm_mut_mime_types'); 
	echo '<table id="jm_mut_mimetypes_table" '
	   . 'style="border-collapse: collapse; border: 1px solid black;">' . "\n";
	echo '  <tr>'
	.    '<th style="border: 1px solid black; background-color: #f0f0f0;">Extension</th>'
	.    '<th style="border: 1px solid black; background-color: #f0f0f0;">Mime Type</th>'
	.    "</tr>\n";
	foreach ($jm_mut_mime_types as $extension => $mimetype) { 
		echo '  <tr>'
		.    '<td style="border: 1px solid black; padding: .5em;">' . $extension . '</td>'
		.    '<td style="border: 1px solid black; padding: .5em;">' . $mimetype . '</td>'
		.    '<td style="border: 1px solid black; padding: .5em;">'
		.    '<a href="javascript:void(0);" onClick="document.jm_mut_delete(' . "'$extension'" . ')">delete</a></td>'
		.    "</tr>\n";
	}
        echo '<tr>'; 
        echo '<td><input id="jm_mut_add_extension" type="text" /></td>';
        echo '<td><input id="jm_mut_add_mimetype" type="text" /></td>';
        echo '<td style="text-align: center;"><input type="button" value="add" '
	.    'onClick="document.jm_mut_add(jQuery(' . "'#jm_mut_add_extension'" . ').val(), '
	.    'jQuery(' . "'#jm_mut_add_mimetype'" . ').val())"/></td>';
        echo '</tr>' . "\n";
	echo "</table>\n";
        
}


/**
 * Callback for admin_enqueue_scripts
 *
 * Adds code to load our javascript components on the proper admin page. 
 *
*/
function jm_mut_enqueue_scripts($hook) {
        // Only load our javascript on the Settings -> Media admin page.
	if ($hook != 'options-media.php') 
		return;  

	// Javascript is kept in js/jm_mut.js. JQuery is a dependency. 
	wp_enqueue_script( 'jm-mut-js', plugin_dir_url( __FILE__ ) . 'js/jm_mut.js', array( 'jquery' ) );
}

add_action( 'admin_enqueue_scripts', 'jm_mut_enqueue_scripts' );


/**
 * Callback for AJAX when a mime type is deleted.
 *
 *
*/
function jm_mut_delete_type_callback() {
	$extension = $_POST['extension_to_delete'];
        
	// FIXME - We aren't checking that the option exists. 
	$jm_mut_mime_types = get_option( 'jm_mut_mime_types' ); 

	// FIXME - We need to validate $extension
	unset($jm_mut_mime_types[$extension]);

	update_option('jm_mut_mime_types', $jm_mut_mime_types); 

        echo "REMOVED $extension";
	die(); // this is required to return a proper result
}

add_action('wp_ajax_jm_mut_delete_type', 'jm_mut_delete_type_callback');


/**
 * Callback for AJAX when a mime type is added.
 *
 * 
*/
function jm_mut_add_type_callback() {
	$extension = $_POST['extension_to_add'];
	$mimetype  = $_POST['mimetype_to_add'];

	// FIXME - We aren't checking that the option exists. 
	$jm_mut_mime_types = get_option( 'jm_mut_mime_types' ); 

	// FIXME - We need to validate both pieces of data here. 
	$jm_mut_mime_types[$extension] = $mimetype;

	update_option('jm_mut_mime_types', $jm_mut_mime_types); 

        echo "ADDED $extension => $mimetype";
	die(); // this is required to return a proper result
}

add_action('wp_ajax_jm_mut_add_type', 'jm_mut_add_type_callback');

?>
