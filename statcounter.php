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

// Add StatCounter settings page
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

// Register and sanitize settings
function statcounter_register_settings() {
    register_setting( 'statcounter', 'statcounter', 'statcounter_sanitize_settings' );
}
add_action( 'admin_init', 'statcounter_register_settings' );

// Sanitize settings input
function statcounter_sanitize_settings( $input ) {
    return [
        'project_id'    => sanitize_text_field( $input['project_id'] ),
        'security_code' => sanitize_text_field( $input['security_code'] ),
    ];
}

// Render the settings page
function statcounter_render_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( isset( $_GET['settings-updated'] ) ) {
        add_settings_error( 'statcounter_messages', 'statcounter_message', __( 'Settings Saved', 'statcounter' ), 'updated' );
    }

    settings_errors( 'statcounter_messages' );

    // Retrieve options from the database
    $settings = get_option( 'statcounter', [] );

    // Use the existing values from the database (empty fields if not set)
    $project_id    = $settings['project_id'] ?? '';
    $security_code = $settings['security_code'] ?? '';
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'StatCounter Settings', 'statcounter' ); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'statcounter' );
            ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="statcounter_project_id"><?php esc_html_e( 'Project ID', 'statcounter' ); ?></label>
                    </th>
                    <td>
                        <input type="text" id="statcounter_project_id" name="statcounter[project_id]" value="<?php echo esc_attr( $project_id ); ?>" class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="statcounter_security_code"><?php esc_html_e( 'Security Code', 'statcounter' ); ?></label>
                    </th>
                    <td>
                        <input type="text" id="statcounter_security_code" name="statcounter[security_code]" value="<?php echo esc_attr( $security_code ); ?>" class="regular-text" />
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
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
