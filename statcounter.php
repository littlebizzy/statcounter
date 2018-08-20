<?php
/*
Plugin Name: StatCounter
Plugin URI: https://www.littlebizzy.com/plugins/statcounter
Description: Inserts StatCounter tracking code just above the closing body tag to ensure the fastest loading speed and to avoid conflicting with any other scripts.
Version: 1.0.6
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.txt
Text Domain: sc-littlebizzy
Domain Path: /lang
Prefix: STCNTR
*/

// Admin Notices module
require_once dirname(__FILE__).'/admin-notices.php';
STCNTR_Admin_Notices::instance(__FILE__);

/**
 * Admin Notices Multisite check
 * Uncomment //return to disable this plugin on Multisite installs
 */
require_once dirname(__FILE__).'/admin-notices-ms.php';
if (false !== \LittleBizzy\StatCounter\Admin_Notices_MS::instance(__FILE__)) {
	//return;
}


/**
 * Define main plugin class
 */
class LB_Stat_Counter {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * Initalize plugin actions
	 *
	 * @return void
	 */
	public function init() {

		$this->lang();

		include $this->dir() . 'includes/class-lb-stat-counter-settings.php';
		lb_stat_counter_settings()->init();

		if ( ! is_admin() ) {
			include $this->dir() . 'includes/class-lb-stat-counter-front.php';
			lb_stat_counter_front()->init();
		}

	}

	/**
	 * Loads the translation files.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function lang() {
		load_plugin_textdomain( 'sc-littlebizzy', false, basename( dirname( __FILE__ ) ) . '/lang' );
	}

	/**
	 * Returns plugin base file
	 *
	 * @return string
	 */
	public static function file() {
		return __FILE__;
	}

	/**
	 * Returns plugin base file
	 *
	 * @return string
	 */
	public function dir() {
		return trailingslashit( dirname( __FILE__ ) );
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}

/**
 * Returns instance of LB_Stat_Counter class
 *
 * @return object
 */
function lb_stat_counter() {
	return LB_Stat_Counter::get_instance();
}

/**
 * Initalize plugin instance very on 'init' hook
 */
add_action( 'init', array( lb_stat_counter(), 'init' ), 20 );
