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

load_plugin_textdomain( 'event-table', false, 'event-table/languages' );

global $csaw_et_db_version;
$csaw_et_db_version = "1.0";

/**
 * Installing the plugin
 * 
 * At installation of the plugin, create the database table wp_event_table.
 * If plugin is newer version update this database table.
 */
function csaw_et_install(){
	global $wpdb;	
	$table_name = $wpdb->prefix . "event_table";
	if( $wpdb->get_var("show tables like '$table_name'") != $table_name ) {

		$sql = "CREATE TABLE `". $table_name . "` (
		`event_id` INT(11) NOT NULL AUTO_INCREMENT,
		`event_date` DATE NOT NULL DEFAULT '0000-00-00',
		`event_title` VARCHAR(80) NOT NULL,
		`event_type` VARCHAR(10) NOT NULL,
		`event_city` VARCHAR(40) NULL,
		`event_language` VARCHAR(10) NULL,
		`event_cost` INT(11) NOT NULL,
		PRIMARY KEY (`event_id`)
		) CHARSET=utf8";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		//var_dump(dbDelta($sql, false));
		
		add_option( 'csaw_et_db_version', $csaw_et_db_version );
	} // end check if table exists otherwise create it
	
	$installed_ver = get_option( 'csaw_et_db_version' );

	if( $installed_ver != $csaw_et_db_version ) {
		$sql = "CREATE TABLE `". $table_name . "` (
		`event_id` INT(11) NOT NULL AUTO_INCREMENT,
		`event_date` DATE NOT NULL DEFAULT '0000-00-00',
		`event_title` VARCHAR(80) NOT NULL,
		`event_type` VARCHAR(10) NOT NULL,
		`event_city` VARCHAR(40) NULL,
		`event_language` VARCHAR(10) NULL,
		`event_cost` INT(11) NOT NULL,
		PRIMARY KEY (`event_id`)
		)CHARSET=utf8";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		update_option( 'csaw_et_db_version', $csaw_et_db_version );
	} // end check the version and possibly upgrade it
}
register_activation_hook( __FILE__, 'csaw_et_install' );

// Create the admin menu for events
add_action('admin_menu', 'csaw_et_menu');

function csaw_et_menu() {
	add_menu_page( __( 'Events to Table', 'event-table' ), __( 'Events', 'event-table' ), 'manage_options', __FILE__, 'csaw_et_options', plugins_url( '/images/wp-icon.png', __FILE__ ) );
}

/**
 * Plugin options
 * 
 * Event to Table user interface with form, and if any, the events already
 * registered in the database.
 */
function csaw_et_options() {
	if ( !current_user_can('manage_options') )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'event-table' ) );
	}

	if(isset($_POST['store_event_table'])) {	
		global $wpdb;						
		$table_name = $wpdb->prefix . "event_table";
		$newEvent = array();
		$newEvent['event_date'] = $_POST['event_date'];
		$newEvent['event_title'] = $_POST['event_title'];
		$newEvent['event_type'] = $_POST['event_type'];
		$newEvent['event_city'] = $_POST['event_city'];
		$newEvent['event_language'] = $_POST['event_language'];
		$newEvent['event_cost'] = $_POST['event_cost'];
		
		$wpdb->insert( $table_name, $newEvent );
		$result = $wpdb->insert_id;
		// End check if entry was written successfully to the DB 	
	}
?>	

	<div id="csaw_et_admin_wrap">
		<h2 id="plugin-title"><?php _e('Event to Table', 'event-table'); ?></h2>
		<?php
			if( isset($_POST['store_event_table']) && !$result ) {
				echo '<div id="msg" class="error"><p>'.__('Unfortunately something went wrong...', 'event-table').'</p></div>';
			} 
			elseif( isset($_POST['store_event_table']) && $result )  { 
				echo '<div id="msg" class="updated"><p>'.__('The event was succesfully added!', 'event-table').'</p></div>'; 
			}			
		?>			
		<form method="post">
		<label for="event_type"><?php _e('Type of Event', 'event-table'); ?></label> 
			<select id="event_type" name="event_type">
				<option value="expert" selected="selected"><?php _e('Expert', 'event-table'); ?></option>
				<option value="wcag">WCAG</option>
			</select>
		<label for="event_date"><?php _e('Date', 'event-table'); ?> - format: 0000-00-00</label> 
		<input type="text" class="w8em format-y-m-d divider-dash highlight-days-12" id="event_date" name="event_date" value="" />
		<label for="event_title"><?php _e('Title'); ?></label> 
		<input type="text" id="event_title" name="event_title" />
		<label for="event_cost"><?php _e('Cost', 'event-table'); ?></label> 
		<input type="text" id="event_cost" name="event_cost" />
		<label class="wcag" for="event_city"><?php _e('City', 'event-table'); ?></label> 
		<input type="text" id="event_city" name="event_city" />
		<label class="wcag" for="event_language"><?php _e('Language', 'event-table'); ?></label> 
			<select id="event_language" name="event_language">
				<option value="français" selected="selected"><?php _e('French', 'event-table'); ?></option>
				<option value="English"><?php _e('English', 'event-table'); ?></option>
			</select>		
		
		<input name="store_event_table" type="submit" title="<?php _e('Submit event', 'event-table'); ?>" value="<?php _e('Submit'); ?>" />
		</form>

<?php

/********************* DISPLAY STORED EVENTS *********************/
	global $wpdb;
	$table_name = $wpdb->prefix . "event_table";
	$all_events = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY event_id ", "ARRAY_A" );
	if( count($all_events) > 0 ) { 
		if( isset($_POST['delete']) ) {
			$remove_event = $_POST['id']; 
			$delete_event = $wpdb->query( "DELETE FROM $table_name WHERE `event_id` = $remove_event" );
			$all_events = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY event_id ", "ARRAY_A" ); // reset display of events	
		}
		/* If event is today, delete it. */
		$today = date('Y-m-d');
		$all_dates = $wpdb->get_results( "SELECT `event_date` FROM $table_name WHERE `event_date` LIKE '$today'", "ARRAY_A" );
		if( in_array($today, $all_dates[0], true) ){
			$wpdb->query( "DELETE FROM $table_name WHERE `event_date` LIKE '$today'" );
			$all_events = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY event_id ", "ARRAY_A" ); // reset display of events
		}	
?>
	<h3><?php _e('Active Events:', 'event-table');?></h3>
	
		<?php
			if( isset($_POST['delete']) && !$delete_event ) {
				echo '<div id="msg" class="error"><p>'.__('Unfortunately the event could not be deleted...', 'event-table').'</p></div>';
			} 
			elseif( isset($_POST['delete']) && $delete_event )  { 
				echo '<div id="msg" class="updated"><p>'.__('The event was succesfully deleted!', 'event-table').'</p></div>'; 
			}			
			
			echo '<ul id="active_events">';
			foreach( $all_events as $an_event ) { 
				echo '<li>';
			if ( $an_event['event_type'] == 'expert' ) { ?>
				<div><?php echo '<strong>'.__('Type of Event:', 'event-table').'</strong> '.$an_event['event_type']; ?></div>
				<div><?php echo '<strong>'.__('Date:', 'event-table').'</strong> '.$an_event['event_date']; ?></div>
				<div><?php echo '<strong>'.__('Title:').'</strong> '.$an_event['event_title']; ?></div>	
			<?php } elseif ( $an_event['event_type'] == 'wcag' ) { ?>
				<div><?php echo '<strong>'.__('Type of Event:', 'event-table').'</strong> '.$an_event['event_type']; ?></div>
				<div><?php echo '<strong>'.__('Date:', 'event-table').'</strong> '.$an_event['event_date']; ?></div>
				<div><?php echo '<strong>'.__('Title:').'</strong> '.$an_event['event_title']; ?></div>	
				<div><?php echo '<strong>'.__('City:', 'event-table').'</strong> '.$an_event['event_city']; ?></div>	
				<div><?php echo '<strong>'.__('Language:', 'event-table').'</strong> '.$an_event['event_language']; ?></div>	
				<div><?php echo '<strong>'.__('Cost:', 'event-table').'</strong> '.$an_event['event_cost']; ?></div>
			<?php } ?>			
			<form method="post"><input type="hidden" name="id" value="<?php echo $an_event['event_id'];?>" title="<?php printf('Event ID %d', $an_event['event_id']);?>" /><input type="submit" name="delete" title="<?php printf('Delete event with ID %d', $an_event['event_id']);?>" value="<?php _e('Delete');?>" class="delete" /></form>
		<?php echo '</li>'; } // end foreach 
		echo '</ul>';
		?>
		
	</div><!-- end csaw_et_admin_wrap -->
	<?php } // end if count $all_events

} // end csaw_et_options


/**
 * Adding shortcodes for text editor
 * 
 * Event to Table user interface with form, and if any, the events already
 * registered in the database.
 * 
 * @param	string	$attr	attribute used in the shortcode
 * @return 	string	html table with all events of desired type
 * 
 * Shortcode usage ("type" -> "wcag" or "expert"): 
 * [events_table type="type"]
 */
function csaw_et_table_type( $attr ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "event_table";
	$all_events_expert = $wpdb->get_results( "SELECT * FROM $table_name WHERE `event_type` LIKE 'expert'", "ARRAY_A" );
	$all_events_wcag = $wpdb->get_results( "SELECT * FROM $table_name WHERE `event_type` LIKE 'wcag'", "ARRAY_A" );	
	if( $attr['type'] == 'expert' ) {
		$expert_table = "<table id='expert'>
		<tr><th>".__('Date', 'event-table')."</th><th>".__('Theme', 'event-table')."</th></tr>";
		$count = 0;
		foreach( $all_events_expert as $an_event_expert ) { 
			if( $count % 2 )
				$nombre = 'odd';
			else
				$nombre = 'even';
			$expert_table .= '<tr class="'.$nombre.'"><td>'.$an_event_expert['event_date'].'</td><td>'.$an_event_expert['event_title'].'</td></tr>';
			$count++;
		}
		$expert_table .= "</table>";
		return $expert_table;
	}
	if( $attr['type'] == 'expert-paypal' ) {
		$expert_paypal_table = "<table id='expert'>
		<tr><th>".__('Date', 'event-table')."</th><th>".__('Theme', 'event-table')."</th><th>".__('Register', 'event-table')."</th></tr>";
		$count = 0;
		foreach( $all_events_expert as $an_event_expert_paypal ) { 
			$expert_item_date = str_replace( '-', '', $an_event_expert_paypal['event_date']);
			$expert_paypal_link = 'https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=info@accessibiliteweb.com&item_name='.$an_event_expert_paypal['event_type'].'&item_number='.$expert_item_date .'&amount='.$an_event_expert_paypal['event_cost'].'&currency_code=CAD';
			if( $count % 2 )
				$nombre = 'odd';
			else
				$nombre = 'even';
			$expert_paypal_table .= '<tr class="'.$nombre.'"><td>'.$an_event_expert_paypal['event_date'].'</td><td>'.$an_event_expert_paypal['event_title'].'</td><a href="'.$expert_paypal_link.'">'.__('Register Now with Paypal', 'event-table').'</a></tr>';
			$count++;
		}
		$expert_paypal_table .= "</table>";
		return $expert_paypal_table;
	}
	if( $attr['type'] == 'wcag' ) {
		$wcag_table = "<table id='wcag'>
		<tr><th>".__('Date', 'event-table')."</th><th>".__('City', 'event-table')."</th><th>".__('Language', 'event-table')."</th><th>".__('Cost', 'event-table')."</th></tr>";
		$count = 0;
		foreach( $all_events_wcag as $an_event_wcag ) { 
			if( $count % 2 )
				$nombre = 'odd';
			else
				$nombre = 'even';
			if( $an_event_wcag['event_language'] == 'français' ) 
				$lang = 'fr-FR';
			if( $an_event_wcag['event_language'] == 'English' ) 
				$lang = 'en-CA';	
			$wcag_table .= '<tr class="'.$nombre.'"><td>'.$an_event_wcag['event_date'].'</td><td>'.$an_event_wcag['event_city'].'</td><td lang="'.$lang.'">'.$an_event_wcag['event_language'].'</td><td> $'.$an_event_wcag['event_cost'].'</td></tr>';
			$count++;
		}
		$wcag_table .= "</table>";
		return $wcag_table;		
	}
	if( $attr['type'] == 'wcag-paypal' ) {
		$wcag_paypal_table = "<table id='wcag_paypal'>
		<tr><th>".__('Date', 'event-table')."</th><th>".__('City', 'event-table')."</th><th>".__('Language', 'event-table')."</th><th>".__('Cost', 'event-table')."</th><th>".__('Register', 'event-table')."</th></tr>";
		$count = 0;
		foreach( $all_events_wcag as $an_event_wcag_paypal ) { 
			$item_date = str_replace( '-', '', $an_event_wcag_paypal['event_date']);
			$paypal_link = 'https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=info@accessibiliteweb.com&item_name='.$an_event_wcag_paypal['event_type'].'&item_number='.$item_date .'&amount='.$an_event_wcag_paypal['event_cost'].'&currency_code=CAD';
			if( $count % 2 )
				$nombre = 'odd';
			else
				$nombre = 'even';
			if( $an_event_wcag_paypal['event_language'] == 'français' ) 
				$lang = 'fr-FR';
			if( $an_event_wcag_paypal['event_language'] == 'English' ) 
				$lang = 'en-CA';	
			$wcag_paypal_table .= '<tr class="'.$nombre.'"><td>'.$an_event_wcag_paypal['event_date'].'</td><td>'.$an_event_wcag_paypal['event_city'].'</td><td lang="'.$lang.'">'.$an_event_wcag_paypal['event_language'].'</td><td> $'.$an_event_wcag_paypal['event_cost'].'</td><td><a href="'.$paypal_link.'">'.__('Register Now with Paypal', 'event-table').'</a></td></tr>';
			$count++;
		}
		$wcag_paypal_table .= "</table>";
		return $wcag_paypal_table;	
	}	
}
add_shortcode( 'events_table', 'csaw_et_table_type' );


/**
 * Plugin deactivation
 * 
 * Deactivate plugin and drop table wp_event_table
 */
function csaw_et_deactivate(){
	deactivate_plugins( basename( __FILE__ ) );
	
	global $wpdb;
	$table_name = $wpdb->prefix."event_table";
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
}
register_deactivation_hook( __FILE__, 'csaw_et_deactivate' );

$plugin_url = plugin_dir_url(__FILE__);
wp_enqueue_script( 'csaw_et_script', $plugin_url.'js/event-type.js' );
wp_enqueue_script( 'csaw_et_datepicker_script', $plugin_url.'js/date-picker/js/datepicker.js' );
wp_enqueue_script( 'csaw_et_datecreater_script', $plugin_url.'js/datepicker-create.js' );
wp_enqueue_style( 'csaw_et_style', $plugin_url.'css/event-style.css' );
wp_enqueue_style( 'csaw_et_datepicker_style', $plugin_url.'js/date-picker/css/datepicker.css' );
?>
