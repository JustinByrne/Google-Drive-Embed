<?php
/**
 * Plugin Name: Google Drive Folder Embed
 * Plugin URI: https://github.com/JustinByrne/Google-Drive-Folder-Embed
 * Description: This plugin adds the ability to embed a Google Drive folder into a post or page.
 * Version: 2.0.3
 * Author: Justin Byrne
 * Author URI: http://jnm-tech.co.uk
 * License: GPL2
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once( ABSPATH . 'wp-includes/pluggable.php' );

// creating a new plugin instant
new GoogleDriveFolderEmbed();


class GoogleDriveFolderEmbed	{

	public $pluginName;

	public function __construct()	{

		// load_plugin_textdomain( $this->pluginName, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

		if( current_user_can('edit_posts') &&  current_user_can('edit_pages') )	{

			add_action( 'media_buttons_context', array( $this, 'add_my_google_button' ) );

			add_action( 'admin_footer', array( $this, 'add_my_google_popup' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_popup_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_popup_scripts' ) );

			add_shortcode( 'google-drive', array( $this, 'google_drive_func' ) );

		}

		$this->pluginName = str_replace( '/' . basename( __FILE__ ), '', plugin_basename( __FILE__ ) );

	}

	public function add_my_google_button( $context )	{

		$context = '<a href="#" id="insert-google-drive" class="button">Add Google Drive Folder</a>';

		return $context;

	}

	public function add_my_google_popup()	{

		echo '<div id="google-drive-popup" style="display: none;">
			<div id="google-drive-content">
				<div id="google-drive-close-btn" class="button">Close</div>
				<div id="google-drive-form">
					<h3>Add Google Drive Folder</h3>

					<table>
						<tr>
							<td><label for="drive-url">Folder URL*: </label></td>
							<td><input type="text" name="drive-url" id="drive-url"></td>
						</tr>
						<tr>
							<td><label for="drive-view">Folder view*: </label></td>
							<td>
								<select name="drive-view" id="drive-view">
									<option value="grid" selected="selected">Grid View</option>
									<option value="list">List View</option>
								</select>
							</td>
						</tr>
						<tr>
							<td><label for="drive-height">iframe height: </label></td>
							<td>
								<input type="range" min="290" max="1000" step="10" value="290" name="drive-height-range" id="drive-height-range">
								<input type="text" name="drive-height-value" id="drive-height-value" readonly="readonly">
							</td>
						</tr>
						<tr>
							<td><label for="drive-link">Add Link: </label></td>
							<td><input type="checkbox" name="drive-link" id="drive-link" value="true"></td>
						</tr>
					</table>

					<div id="google-drive-insert-btn" class="button button-primary">Insert</div>

				</div>
			</div>
		</div>';

	}

	public function register_popup_scripts()	{

		wp_register_script( $this->pluginName, plugins_url( $this->pluginName . '/js/popup.js' ) );
    	
    	wp_enqueue_script( $this->pluginName );

	}

	public function register_popup_styles()	{

		wp_register_style( $this->pluginName, plugins_url( $this->pluginName . '/css/popup.css' ) );

		wp_enqueue_style( $this->pluginName );

	}

	public function google_drive_func( $atts )	{

		extract( shortcode_atts( array(
	
			'url' => '',
			'view' => 'grid',
			'height' => '290',
			'link' => false,

		), $atts ) );

		if( !empty( $url ) )	{ // checking that a url has been provided

			// removing excess sharing from url
			if( strpos( $url, '&amp;usp=sharing' ) !== false )	{ // checking if button has pressed in visual mode

				$short_url = str_replace( '&amp;usp=sharing', '', $url );

			} else if( strpos( $url, '&usp=sharing' ) !== false )	{ // checking if button has been pressed in text mode

				$short_url = str_replace( '&usp=sharing', '', $url );
			
			} else {

				return false;

			}

			// removing domain details from url
			if( strpos( $short_url, 'id=' ) !== false )	{ // checking if an id is in the url

				$id = substr( $short_url, strpos( $short_url, 'id=' ) + 3 );

			}

			// creating iframe
			$iframe = '<iframe src="https://drive.google.com/embeddedfolderview?id=' 
					. $id . '#' . $view. '" width="100%" height="' . $height . '" frameborder="0"></iframe>';

			// creating link to Google Drive folder
			if( $link )	{

				$link = '<p><a href="' . $url . '" target="_blank">Click here</a>  to view this folder on Google Drive</p>';

			}

			return $iframe . $link;
					

		} else {

			return false;

		}

	}

}