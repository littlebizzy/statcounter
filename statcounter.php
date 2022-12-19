<?php
/*
Plugin Name: StatCounter
Plugin URI: https://www.littlebizzy.com/plugins/statcounter
Description: Optimized StatCounter tracking
Version: 1.1.0
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.txt
GitHub Plugin URI: https://github.com/littlebizzy/statcounter
Primary Branch: master
Prefix: STCNTR
*/


// disable wordpress.org updates
add_filter( 'gu_override_dot_org', function() {
    return [ 
        'statcounter/statcounter.php'
    ];
});


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

		include $this->dir() . 'inc/class-lb-stat-counter-settings.php';
		lb_stat_counter_settings()->init();

		if ( ! is_admin() ) {
			include $this->dir() . 'inc/class-lb-stat-counter-front.php';
			lb_stat_counter_front()->init();
		}

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
