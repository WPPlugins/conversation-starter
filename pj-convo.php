<?php
/*
Plugin Name: Conversation Starter
Plugin URI: http://www.pixeljar.net/491/conversation-manager/
Description: This plugin prompts readers to answer a question in your comments.
Author: brandondove, jeffreyzinn, STDestiny, vegasgeek, drewstrojny
Version: 1.2
Author URI: http://www.pixeljar.net

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

if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
if ( ! defined( 'CONVO_URL' ) )
      define( 'CONVO_URL', WP_PLUGIN_URL. '/conversation-starter' );
if ( ! defined( 'CONVO_DIR' ) )
      define( 'CONVO_DIR', WP_PLUGIN_DIR . '/conversation-starter' );

	function pj_convo_starter_add_model_meta()
	{
		add_meta_box( 'pj_convo_starter_metabox', __('Conversation Starter'), 'pj_convo_starter_metabox', 'post', 'normal', 'high' );
	}
	
	function pj_convo_starter_metabox()
	{
		global $post;
		
		$promptext = get_post_meta($post->ID, 'prompt', true);
		
		if (empty($promptext)) {
			global $convo_starter;
			$promptext = $convo_starter->defaultPromptText(); // "What do you think about this post?";
		}
		 
		?>
	    <label for="promptext"><?php echo __("Use this text to prompt the readers:" ); ?></label><br />
	    <textarea rows="5" cols="35" name="promptext" id="promptext"><?php echo $promptext; ?></textarea><br />
	    
	    <?php
	}
	
	function pj_convo_starter_save_model_meta($post_id, $post)
	{
		// Is the user allowed to edit the post or page?
		if ( 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post->ID ))
			return $post->ID;
		} else {
			if ( !current_user_can( 'edit_post', $post->ID ))
			return $post->ID;
		}
	
		$mydata['prompt'] = $_POST['promptext'];
		
		foreach ($mydata as $key => $value) { //Let's cycle through the $mydata array!
			if( $post->post_type == 'revision' ) return; //don't store custom data twice
			$value = implode(',', (array)$value); //if $value is an array, make it a CSV (unlikely)
			if(get_post_meta($post->ID, $key, FALSE)) { //if the custom field already has a value
				update_post_meta($post->ID, $key, $value);
			} else { //if the custom field doesn't have a value
				add_post_meta($post->ID, $key, $value);
			}
			if(!$value) delete_post_meta($post->ID, $key); //delete if blank
		}
	}
	
	/* Use the admin_menu action to define the custom boxes */
	add_action('admin_menu', 'pj_convo_starter_add_model_meta');
	add_action('save_post',  'pj_convo_starter_save_model_meta', 1, 2);
	add_action('activate_conversation-starter/pj-convo.php', array(&$convo_starter, 'activateMe'));
	add_action('wp_head', 'pj_convo_starter_head_intercept');
	add_action('wp_footer', 'pj_convo_starter_foot_intercept');
	function pj_convo_starter_head_intercept() 
	{
		echo '<meta name="generator" content="Think-Press, Conversation Starter v1.1" />';
	}
	function pj_convo_starter_foot_intercept ()
	{
		global $post;
		echo '<script language="javascript" src="'.get_option('home').'/index.php?conversation-starter=frontend_js&convo-id='.$post->ID.'"></script>';	
	}
	
	
	/*----------------------------
	FRONTEND CSS & JS files
	*/
	function pj_convo_parse_request($wp) {
	    // only process requests with "my-plugin=ajax-handler"
	    if (array_key_exists('conversation-starter', $wp->query_vars) 
	            && $wp->query_vars['conversation-starter'] == 'frontend_css') {
			include(CONVO_DIR.'/stylesheets/frontend.php');
	        die();
	    } else if (array_key_exists('conversation-starter', $wp->query_vars) 
	            && $wp->query_vars['conversation-starter'] == 'frontend_js') {
			include(CONVO_DIR.'/javascripts/frontend.php');
	        die();
		}
	}
	add_action('wp', 'pj_convo_parse_request');
	
	function pj_convo_query_vars($vars) {
	    $vars[] = 'conversation-starter';
	    $vars[] = 'convo-id';
	    return $vars;
	}
	add_filter('query_vars', 'pj_convo_query_vars');



	/*----------------------------
	ADMIN Page functionality
	*/
	require ('PluginCore/extend.php');

?>
