<?php
/**
 * Settings page
 */

class LB_Stat_Counter_Front {

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

		add_action( 'wp_footer', array( $this, 'add_tracking_code' ), PHP_INT_MAX );

	}

	/**
	 * Print tracking code into footer
	 *
	 * @return  void
	 */
	public function add_tracking_code() {

		$project_id         = lb_stat_counter_settings()->get( 'project_id' );
		$security_key       = lb_stat_counter_settings()->get( 'security_key' );
		$track_logged_users = lb_stat_counter_settings()->get( 'track_logged_users' );

		if ( ! $project_id || ! $security_key ) {
			return;
		}

		if ( ! $track_logged_users && is_user_logged_in() ) {
			return;
		}

		?>
		<!-- Start of StatCounter Code -->
		<script type="text/javascript">
			var sc_project = <?php echo esc_attr( $project_id ); ?>;
			var sc_invisible = 1;
			var sc_security = "<?php echo esc_attr( $security_key ); ?>";
			var scJsHost = (("https:" == document.location.protocol) ?
			"https://secure." : "http://www.");
			document.write("<sc"+"ript type='text/javascript' src='" +
			scJsHost+
			"statcounter.com/counter/counter.js'></"+"script>");
		</script>
		<!-- End of StatCounter Code -->
		<?php

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
 * Returns instance of LB_Stat_Counter_Front class
 *
 * @return object
 */
function lb_stat_counter_front() {
	return LB_Stat_Counter_Front::get_instance();
}
