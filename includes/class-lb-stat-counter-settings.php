<?php
/**
 * Settings page
 */

class LB_Stat_Counter_Settings {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * Settings slug
	 *
	 * @var string
	 */
	protected $settings_slug = 'statcounter';

	/**
	 * Holder for saved settings
	 *
	 * @var array
	 */
	protected $settings = null;

	/**
	 * Initalize plugin actions
	 *
	 * @return void
	 */
	public function init() {

		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'register_settings_page' ) );
			add_action( 'admin_init', array( $this, 'init_settings' ) );
		}

	}

	/**
	 * Get setting value by name
	 *
	 * @param  string $name Setting name to get.
	 * @return mixed
	 */
	public function get( $name = null ) {

		if ( null === $this->settings ) {
			$this->settings = get_option( $this->settings_slug, array() );
		}

		if ( isset( $this->settings[ $name ] ) ) {
			return $this->settings[ $name ];
		} else {
			return false;
		}

	}

	/**
	 * Register plugin settings
	 *
	 * @return void
	 */
	public function init_settings() {

		$fields = array(
			array(
				'id'       => 'project_id',
				'title'    => esc_html__( 'Project ID', 'sc-littlebizzy' ),
				'callback' => array( $this, 'field_text' ),
			),
			array(
				'id'       => 'security_key',
				'title'    => esc_html__( 'Security Key', 'sc-littlebizzy' ),
				'callback' => array( $this, 'field_text' ),
			),
			array(
				'id'       => 'track_logged_users',
				'title'    => esc_html__( 'Track logged in users?', 'sc-littlebizzy' ),
				'callback' => array( $this, 'field_checkbox' ),
			),
		);

		register_setting( $this->settings_slug, $this->settings_slug );

		add_settings_section( $this->settings_slug . '-main', '', '__return_empty_string', $this->settings_slug );

		foreach ( $fields as $field ) {
			add_settings_field(
				$field['id'],
				$field['title'],
				$field['callback'],
				$this->settings_slug,
				$this->settings_slug . '-main',
				$field
			);
		}

	}

	/**
	 * Register settings page as Settings sub-menu
	 *
	 * @return void
	 */
	public function register_settings_page() {

		add_options_page(
			esc_html__( 'StatCounter LittleBizzy', 'sc-littlebizzy' ),
			esc_html__( 'StatCounter', 'sc-littlebizzy' ),
			'manage_options',
			$this->settings_slug,
			array( $this, 'render_page' )
		);

	}

	/**
	 * Render settings page
	 *
	 * @return void
	 */
	public function render_page() {
		?>
		<div class="wrap">
			<form action='options.php' method='post'>

				<h2><?php esc_html_e( 'StatCounter Settings', 'sc-littlebizzy' ); ?></h2>

				<?php
					settings_fields( $this->settings_slug );
					do_settings_sections( $this->settings_slug );
					submit_button();
				?>

			</form>
		</div>
		<?php
	}

	/**
	 * Render text field.
	 *
	 * @param  array $field Field data.
	 * @return void
	 */
	public function field_text( $field ) {

		$value = $this->get( $field['id'] );

		printf(
			'<input type="text" name="%1$s" value="%2$s">',
			$this->settings_slug . '[' . $field['id'] . ']',
			$value
		);
	}

	/**
	 * Render text field.
	 *
	 * @param  array $field Field data.
	 * @return void
	 */
	public function field_checkbox( $field ) {

		$value = $this->get( $field['id'] );

		printf(
			'<input type="checkbox" name="%1$s" value="yes" %2$s>',
			$this->settings_slug . '[' . $field['id'] . ']',
			checked( $value, 'yes', false )
		);
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
 * Returns instance of LB_Stat_Counter_Settings class
 *
 * @return object
 */
function lb_stat_counter_settings() {
	return LB_Stat_Counter_Settings::get_instance();
}
