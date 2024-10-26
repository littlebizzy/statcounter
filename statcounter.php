<?php
/*
Plugin Name: StatCounter
Plugin URI: https://www.littlebizzy.com/plugins/statcounter
Description: Optimized StatCounter tracking
Version: 2.0.2
Requires PHP: 7.0
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.txt
GitHub Plugin URI: littlebizzy/statcounter
Primary Branch: master
*/

// prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// disable wordpress.org updates for this plugin
add_filter( 'gu_override_dot_org', function( $overrides ) {
    $overrides[] = 'statcounter/statcounter.php';
    return $overrides;
}, 999 );

// Add StatCounter settings page to admin menu
function statcounter_add_settings_page() {
    add_options_page(
        __( 'StatCounter Settings', 'statcounter' ),  // Page title
        __( 'StatCounter', 'statcounter' ),           // Menu title
        'manage_options',                             // Capability
        'statcounter',                                // Menu slug
        'statcounter_render_settings_page'            // Callback function
    );
}

// Hook into the admin menu to add the settings page
add_action( 'admin_menu', 'statcounter_add_settings_page' );

// Register and initialize settings
function statcounter_register_settings() {
    register_setting(
        'statcounter_settings_group', 
        'statcounter', 
        'statcounter_sanitize_settings'
    );

    add_settings_section(
        'statcounter_settings_section', 
        __( 'StatCounter Settings', 'statcounter' ), 
        '__return_null', 
        'statcounter'
    );

    add_settings_field(
        'statcounter_project_id',
        __( 'Project ID', 'statcounter' ),
        'statcounter_render_project_id_field',
        'statcounter',
        'statcounter_settings_section'
    );

    add_settings_field(
        'statcounter_security_code',
        __( 'Security Code', 'statcounter' ),
        'statcounter_render_security_code_field',
        'statcounter',
        'statcounter_settings_section'
    );
}
add_action( 'admin_init', 'statcounter_register_settings' );

// Migrate old settings from individual options to the new array structure
function statcounter_migrate_old_settings() {
    // Check if old individual options exist
    $old_project_id = get_option( 'project_id' );
    $old_security_code = get_option( 'security_code' );

    // Retrieve the existing statcounter array
    $new_settings = get_option( 'statcounter', [] );

    // Only add old values if the new settings do not already have them
    if ( $old_project_id !== false && empty( $new_settings['project_id'] ) ) {
        $new_settings['project_id'] = sanitize_text_field( $old_project_id );
        delete_option( 'project_id' ); // Optionally delete old individual option
    }

    if ( $old_security_code !== false && empty( $new_settings['security_code'] ) ) {
        $new_settings['security_code'] = sanitize_text_field( $old_security_code );
        delete_option( 'security_code' ); // Optionally delete old individual option
    }

    // Save the new array only if it's been updated
    if ( ! empty( $new_settings ) ) {
        update_option( 'statcounter', $new_settings );
    }
}
add_action( 'admin_init', 'statcounter_migrate_old_settings' );

// Sanitize settings input
function statcounter_sanitize_settings( $input ) {
    // Initialize with default values to ensure keys exist
    $sanitized_input = [
        'project_id'    => '',
        'security_code' => '',
    ];
    
    // Sanitize project ID
    if ( isset( $input['project_id'] ) ) {
        $sanitized_input['project_id'] = sanitize_text_field( $input['project_id'] );
    }

    // Sanitize security code
    if ( isset( $input['security_code'] ) ) {
        $sanitized_input['security_code'] = sanitize_text_field( $input['security_code'] );
    }

    return $sanitized_input;
}

// Render project ID field
function statcounter_render_project_id_field() {
    // Retrieve the 'statcounter' option from the database
    $options = get_option( 'statcounter', [] );
    
    // Check if 'project_id' exists and escape it for safe output
    $project_id = isset( $options['project_id'] ) ? esc_attr( $options['project_id'] ) : '';
    ?>
    <input type="text" id="statcounter_project_id" name="statcounter[project_id]" value="<?php echo $project_id; ?>" class="regular-text" />
    <?php
}

// Render security code field
function statcounter_render_security_code_field() {
    // Retrieve the 'statcounter' option from the database
    $options = get_option( 'statcounter', [] );
    
    // Check if 'security_code' exists and escape it for safe output
    $security_code = isset( $options['security_code'] ) ? esc_attr( $options['security_code'] ) : '';
    ?>
    <input type="text" id="statcounter_security_code" name="statcounter[security_code]" value="<?php echo $security_code; ?>" class="regular-text" />
    <?php
}

// Render settings page
function statcounter_render_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'StatCounter Settings', 'statcounter' ); ?></h1>
        <form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">
            <?php
            // Output nonce, action, and option page fields for settings page
            settings_fields( 'statcounter_settings_group' );
            do_settings_sections( 'statcounter' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Add the tracking code to the footer
function statcounter_add_tracking_code() {
    // Retrieve the settings from the database, ensure defaults
    $settings = get_option( 'statcounter', [
        'project_id' => '',
        'security_code' => ''
    ]);

    // Sanitize and ensure values are strings
    $project_id = sanitize_text_field( $settings['project_id'] );
    $security_code = sanitize_text_field( $settings['security_code'] );

    // New line after the closing PHP tag for proper HTML source formatting
    ?>
    
    <script type="text/javascript">
    var sc_project = <?php echo esc_js( $project_id ); ?>;
    var sc_invisible = 1;
    var sc_security = "<?php echo esc_js( $security_code ); ?>";
    </script>
    <script type="text/javascript" src="https://www.statcounter.com/counter/counter.js" async></script>
    <?php
}
add_action( 'wp_footer', 'statcounter_add_tracking_code', PHP_INT_MAX );

// Ref: ChatGPT
