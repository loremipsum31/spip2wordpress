<?php
// some constants
define('ALO_EM_OPT_METAKEY','alo_easymail_optin_setting');

//============= Widget functions ==============================================


/**
 * Show the widget form for registered/pubblic
 *
 * param 	id		div id, useful to distinguish forms (in page, in widget...)
 */
function alo_em_show_widget_form ( ) {
	global $user_ID, $user_email, $wpdb;
	
	// If registerd user check if subscriber
	$subscriber_id = alo_em_is_subscriber($user_email);
	
	// prepare mailing lists table
	$lists_msg 	= ( alo_em_translate_option ( alo_em_get_language (), 'alo_em_custom_lists_msg',false) !="")? alo_em_translate_option ( alo_em_get_language (), 'alo_em_custom_lists_msg',false) : __("You can also sign up for specific lists", "alo-easymail");  
    $mailinglists = alo_em_get_mailinglists( 'public' );
    $lists_table = "";
    if ( $mailinglists ) {
	    $user_lists = alo_em_get_user_mailinglists ( $subscriber_id );
	  	$lists_table .= "<div class='alo_easymail_lists_table'>" . $lists_msg ."<div id='newsletters'>"; 
		foreach ( $mailinglists as $list => $val ) {
			$checked = ( $user_lists && in_array ( $list, $user_lists )) ? "checked='checked'" : "";
			$checkbox_js = "";
			$lists_table .= "<input type='checkbox' name='alo_em_form_lists[]' id='alo_em_form_list_$list' value='$list' $checked $checkbox_js class='input-checkbox' /><label for='alo_em_form_list_$list'>" . alo_em_translate_multilangs_array ( alo_em_get_language(), $val['name'], true ) . "</label>\n";
		}
		$lists_table .= "</div></div>\n";
	}
	
	$preform_msg	= ( alo_em_translate_option ( alo_em_get_language (), 'alo_em_custom_preform_msg',false) !="")? alo_em_translate_option ( alo_em_get_language (), 'alo_em_custom_preform_msg',false) : false; 
    $preform_html 	= ( $preform_msg ) ? "<div class='alo_easymail_preform_msg'>" . $preform_msg . "</div>\n" : ""; 
    	
	$disclaimer_msg	= ( alo_em_translate_option ( alo_em_get_language (), 'alo_em_custom_disclaimer_msg',false) !="")? alo_em_translate_option ( alo_em_get_language (), 'alo_em_custom_disclaimer_msg',false) : false; 
    $disclaimer_html = ( $disclaimer_msg ) ? "<div class='alo_easymail_disclaimer'>" . $disclaimer_msg . "</div>\n" : ""; 
    	
	$alo_em_opt_email	= ( isset($_POST['alo_em_opt_email']) ) ? stripslashes($_POST['alo_em_opt_email']) : "";
	$html = "<div id='alo_em_widget_loading' class='alo_em_widget_loading' style='display:none;'><img src='".ALO_EM_PLUGIN_URL."/images/wpspin_light.gif' alt='' style='vertical-align:middle' /> ". __("sending...", "alo-easymail") ."</div>\n";
	$html .= "<div id='alo_easymail_widget_feedback'></div>\n";
	$html .= "<h2>".__("Stay Connected", "alo-easymail")."</h2>\n";
	$html .= __("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque suscipit consequat felis tristique euismod. Aliquam lacus metus, porta sed rutrum ac, lacinia sed nunc.", "alo-easymail");
	$html .= "<form name='alo_easymail_widget_form' id='alo_easymail_widget_form' class='alo_easymail_widget_form alo_easymail_widget_form_public' method='post' action='' onsubmit='alo_em_pubblic_form();return false;'>\n";
	$html .= $preform_html;        
	$html .= "    <label for='opt_email'>".__("I wish to subscribe/unsubscribe", "alo-easymail")."</label>\n";
	$html .= "    <input type='text' name='alo_em_opt_email' value='' id='opt_email' class='input-text' />\n";      
	$html .= $lists_table; // add lists table     
	$html .= $disclaimer_html;	
	$html .= "<input type='submit' name='submit' value='".__("Subscribe", "alo-easymail")."' class='input-submit' />\n";
	$html .= "</form>\n";    
    
    // and output it
    return $html;

}

//============= Widget Class ==============================================
class ALO_Easymail_Widget extends WP_Widget {
    // this constructor cannot be __construct!! causes 500 server error
	function ALO_Easymail_Widget() {
		/* Widget settings. NOTE: Class name must be lower case*/
		$widget_ops = array( 'classname' => 'alo_easymail_widget', 'description' => __('Allow users to opt in/out of email', 'alo-easymail') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'alo-easymail-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'alo-easymail-widget', __('ALO Easymail Widget', 'alo-easymail'), $widget_ops, $control_ops );
	}

	/**
	 * Display the widget on the screen.
	 */
	/* args array
        [name] => Sidebar 1
        [id] => sidebar-1
        [description] => 
        [before_widget] => <li id="example-widget-4" class="widget example">
        [after_widget] => </li>
        [before_title] => <h2 class="widgettitle">
        [after_title] => </h2>
        [widget_id] => example-widget-4
        [widget_name] => Example Widget
    */	 
	function widget( $args, $instance ) {
        global $user_ID, $user_email, $wpdb;
        
		extract( $args );
        
        // add ALO: hide the widget in subscriber page
        if ( is_page(get_option('alo_em_subsc_page')) ) return;
        
 		// Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		
        // Get the the user's optin setting
        if (alo_em_is_subscriber($user_email)){
            $optin_checked = "checked='checked'";            
            $optout_checked = "";            
        }
        else{
            $optin_checked = "";            
            $optout_checked = "checked='checked'";            
        }        
		
		// Before widget (defined by themes). 
		echo $before_widget;

		// Display the widget title if one was input (before and after defined by themes). 
		if ( $title )

			echo $before_title . $title . $after_title;

        // get the message optin/out messages
        // mod ALO: we need them also outside widget
		$optin_msg = get_option('alo_em_optin_msg');
		$optout_msg = get_option('alo_em_optout_msg');
        //$optin_msg = $instance['alo_easymail_optin_msg'];
        //$optout_msg = $instance['alo_easymail_optout_msg'];
        
        // add ALO: print the form
        echo alo_em_show_widget_form ();
        
        // and output it
        //echo $html;
		
		// After widget (defined by themes). 
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Strip tags for title and name to remove HTML
		$instance['title'] = strip_tags( $new_instance['title'] );
		//$instance['alo_easymail_optin_msg'] = strip_tags( $new_instance['alo_easymail_optin_msg'] );
		//$instance['alo_easymail_optout_msg'] = strip_tags( $new_instance['alo_easymail_optout_msg'] );
		
		// add ALO: add option text to use form outside widget
		//update_option( "alo_em_optin_msg", strip_tags( $new_instance['alo_easymail_optin_msg']) /*$instance['alo_easymail_optin_msg']*/ );
		//update_option( "alo_em_optout_msg", strip_tags( $new_instance['alo_easymail_optout_msg']) /*$instance['alo_easymail_optout_msg']*/ );
		
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Newsletter', 'alo-easymail') );
		$instance = wp_parse_args( (array) $instance, $defaults ); 

		$html = "";
		$html .= "\r\n".'<!-- Widget Title: Text Input -->';
		$html .= "\r\n".'<p>';
		$html .= "\r\n".'	<label for="'.$this->get_field_id( 'title' ).'">Title</label>';
		$html .= "\r\n".'	<input id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" value="'.$instance['title'].'" style="width:100%;" />';
		$html .= "\r\n".'</p>';
		
		/*
		$html .= "\r\n".'<!-- alo_easymail_optin_msg: Text Input -->';
		$html .= "\r\n".'<p>';
		$html .= "\r\n".'	<label for="'.$this->get_field_id( 'alo_easymail_optin_msg' ).'">Optin Message</label>';
		$html .= "\r\n".'	<input id="'.$this->get_field_id( 'alo_easymail_optin_msg' ).'" name="'.$this->get_field_name( 'alo_easymail_optin_msg' ).'" value="'.$instance['alo_easymail_optin_msg'].'" style="width:100%;" />';
		$html .= "\r\n".'</p>';
		
		$html .= "\r\n".'<!-- alo_easymail_optout_msg: Text Input -->';
		$html .= "\r\n".'<p>';
		$html .= "\r\n".'	<label for="'.$this->get_field_id( 'alo_easymail_optout_msg' ).'">Optout Message</label>';
		$html .= "\r\n".'	<input id="'.$this->get_field_id( 'alo_easymail_optout_msg' ).'" name="'.$this->get_field_name( 'alo_easymail_optout_msg' ).'" value="'.$instance['alo_easymail_optout_msg'].'" style="width:100%;" />';
		$html .= "\r\n".'</p>';
		*/
				
		echo $html;

	}
}//=========== End  Widget Class ==========================================
?>
