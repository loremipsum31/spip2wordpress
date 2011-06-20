<?
/*
Plugin Name: CSAW Event Table
Plugin URI: http://example.com/wordpress-plugins/my-plugin
Description: Add events to tables in text editor by using a shortcode. Used exclusively for accessibiliteweb.com
Version: 1.0
Author: Rocío Alvarado 
Author URI: http://ra-creations.ca
License: GPLv2
*/

/*  Copyright 2011  Rocío Alvarado  (email : ralvarado@accessibiliteweb.com)

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
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*if(is_admin()){
require_once( dirname( __FILE__ ).'/includes/admin.php' );
}*/
register_activation_hook( __FILE__, 'csaw_et_install' );

function csaw_et_install(){
	global $wpdb;
	global $csaw_et_db_version;

	$table_name = $wpdb->prefix . "event-table";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = "CREATE TABLE " . $table_name . " (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		event_start INT(10) ZEROFILL NOT NULL,
		event_end INT(10) ZEROFILL NOT NULL,
		event_title VARCHAR(80) NOT NULL,
		event_type VARCHAR(10) NOT NULL,
		event_desc VARCHAR(250) NULL,
		event_city VARCHAR(40) NULL,
		event_language VARCHAR(10) NULL,
		event_cost VARCHAR(10) NULL,
		UNIQUE KEY id (id)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'csaw_et_db_version', $csaw_et_db_version );
	} // End check to see if Table exists in the DB, if not, then create it.
	$installed_ver = get_option( 'csaw_et_db_version' );

	if( $installed_ver != $csaw_et_db_version ) {
		$sql = "CREATE TABLE " . $table_name . " (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		event_start INT(10) ZEROFILL NOT NULL,
		event_end INT(10) ZEROFILL NOT NULL,
		event_title VARCHAR(80) NOT NULL,
		event_type VARCHAR(10) NOT NULL,
		event_desc VARCHAR(250) NULL,
		event_city VARCHAR(40) NULL,
		event_language VARCHAR(10) NULL,
		event_cost VARCHAR(10) NULL,
		UNIQUE KEY id (id)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		update_option( 'csaw_et_db_version', $csaw_et_db_version );
	}// end Check DB version and possibly upgrade it
}

// Create the admin menu
add_action('admin_menu', 'csaw_et_menu');

function csaw_et_menu() {
	add_menu_page( __( 'Events to Table' ), __( 'Events' ), 'manage_options', __FILE__, 'csaw_et_options', plugins_url( '/images/wp-icon.png', __FILE__ ) );
}

function csaw_et_options() {
	if ( !current_user_can('manage_options') )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
?>	

	<div id="csaw_et_admin_wrap">
		<h2 id="plugin-title">Event to Table</h2>
		<form method="post">
		<label for="event_type"><?php _e('Type of Event:'); ?></label> 
			<select id="event_type" name="event_type">
				<option value="expert" selected="selected"><?php _e('Expert'); ?></option>
				<option value="wcag">WCAG</option>
			</select>
		<label for="event_start_date"><?php _e('Start Date:'); ?></label> <input type="text" id="event_start_date" name="event_start_date" />
		<label for="event_end_date"><?php _e('End Date:'); ?></label> <input type="text" id="event_end_date" name="event_end_date" />
		<label for="event_title"><?php _e('Title:'); ?></label> <input type="text" id="event_title" name="event_title" />
		<label class="wcag" for="event_city"><?php _e('City:'); ?></label> <input type="text" id="event_city" name="event_city" />
		<label class="wcag" for="event_language"><?php _e('Language:'); ?></label> <input type="text" id="event_language" name="event_language" />
		<label class="wcag" for="event_cost"><?php _e('Cost:'); ?></label> <input type="text" id="event_cost" name="event_cost" />
		
		<input name="event_submit" type="submit" title="<?php _e('Submit event'); ?>" value="<?php _e('Submit'); ?>" />
		</form>
	</div>

<?php
}

register_deactivation_hook( __FILE__, 'csaw_et_deactivate' );

function csaw_et_deactivate(){
	deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
}
$plugin_url = plugin_dir_url(__FILE__);
wp_enqueue_script( 'csaw_et_script', $plugin_url.'js/event-type.js' );
wp_enqueue_style( 'csaw_et_style', $plugin_url.'css/event-style.css' );
?>
