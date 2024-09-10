<?php
/*
Plugin Name: StatCounter
Plugin URI: https://www.littlebizzy.com/plugins/statcounter
Description: Optimized StatCounter tracking
Version: 2.0.0
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.txt
GitHub Plugin URI: https://github.com/littlebizzy/statcounter
Primary Branch: master
Prefix: STCNTR
*/

// Ensure direct file access protection
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Disable WordPress.org updates for this plugin
add_filter( 'gu_override_dot_org', function( $overrides ) {
    $overrides[] = 'statcounter/statcounter.php';
    return $overrides;
});

// Add StatCounter settings page to admin menu
function statcounter_add_settings_page() {
    add_options_page(
        __( 'StatCounter Settings', 'statcounter' ),
        __( 'StatCounter', 'statcounter' ),
        'manage_options',
        'statcounter',
        'statcounter_render_settings_page'
    );
}
add_action( 'admin_menu', 'statcounter_add_settings_page' );

// Register and initialize settings
function statcounter_register_settings() {
    register_setting( 'statcounter_settings_group', 'statcounter', 'statcounter_sanitize_settings' );

    add_settings_section(
        'statcounter_settings_section', 
        __( 'StatCounter Settings', 'statcounter' ), 
        '__return_null', 
        'statcounter'
    );

    add_settings_field(
        'statcounter_project_id',
        __( 'Project ID', 'statcounter' ),
        'statcounter_project_id_callback',
        'statcounter',
        'statcounter_settings_section'
    );

    add_settings_field(
        'statcounter_security_code',
        __( 'Security Code', 'statcounter' ),
        'statcounter_security_code_callback',
        'statcounter',
        'statcounter_settings_section'
    );
}
add_action( 'admin_init', 'statcounter_register_settings' );

// Sanitize settings input
function statcounter_sanitize_settings( $input ) {
    return [
        'project_id'    => sanitize_text_field( $input['project_id'] ),
        'security_code' => sanitize_text_field( $input['security_code'] ),
    ];
}

// Callback for project ID field
function statcounter_project_id_callback() {
    $options = get_option( 'statcounter' );
    $project_id = isset( $options['project_id'] ) ? $options['project_id'] : '';
    echo '<input type="text" id="statcounter_project_id" name="statcounter[project_id]" value="' . esc_attr( $project_id ) . '" class="regular-text" />';
}

// Callback for security code field
function statcounter_security_code_callback() {
    $options = get_option( 'statcounter' );
    $security_code = isset( $options['security_code'] ) ? $options['security_code'] : '';
    echo '<input type="text" id="statcounter_security_code" name="statcounter[security_code]" value="' . esc_attr( $security_code ) . '" class="regular-text" />';
}

// Render settings page
function statcounter_render_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'StatCounter Settings', 'statcounter' ); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'statcounter_settings_group' );
            do_settings_sections( 'statcounter' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Add the tracking code to the footer, always inject JS
function statcounter_add_tracking_code() {
    // Retrieve the settings from the database
    $settings = get_option( 'statcounter', [] );

    // Use empty strings as placeholders if the settings are empty
    $project_id    = ! empty( $settings['project_id'] ) ? $settings['project_id'] : '';
    $security_code = ! empty( $settings['security_code'] ) ? $settings['security_code'] : '';

    // Always output the script, even with empty values
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var scProject = '<?php echo esc_js( $project_id ); ?>';
        var scSecurity = '<?php echo esc_js( $security_code ); ?>';
        var scJsHost = document.location.protocol === 'https:' ? 'https://secure.' : 'http://www.';
        var script = document.createElement('script');
        script.src = scJsHost + 'statcounter.com/counter/counter.js';
        document.body.appendChild(script);
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'statcounter_add_tracking_code', PHP_INT_MAX );

// Ref: ChatGPT
