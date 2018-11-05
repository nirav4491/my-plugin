<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       multidots.com
 * @since      1.0.0
 *
 * @package    Library_Book_Search_Plugin
 * @subpackage Library_Book_Search_Plugin/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Library_Book_Search_Plugin
 * @subpackage Library_Book_Search_Plugin/includes
 * @author     Nirav Mehta <nirmehta4491@gmail.com>
 */
class Library_Book_Search_Plugin_I18n {


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		add_action( 'plugins_loaded', 'Library_Book_Search_Plugin_I18n::load_plugin_textdomain' );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public static function load_plugin_textdomain() {

		load_plugin_textdomain(
			'library-book-search-plugin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}


}
