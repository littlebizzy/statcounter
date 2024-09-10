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
add_filter( 'gu_override_dot_org', function ( $overrides ) {
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
        'project_id'    => sanitize_text_field( $input['project_id'] ?? '' ),
        'security_code' => sanitize_text_field( $input['security_code'] ?? '' ),
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

    $settings = get_option( 'statcounter', [ 'project_id' => '', 'security_code' => '' ] );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'StatCounter Settings', 'statcounter' ); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'statcounter' );
            do_settings_sections( 'statcounter' );
            ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><?php esc_html_e( 'Project ID', 'statcounter' ); ?></th>
                    <td><input type="text" name="statcounter[project_id]" value="<?php echo esc_attr( $settings['project_id'] ); ?>" /></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Security Code', 'statcounter' ); ?></th>
                    <td><input type="text" name="statcounter[security_code]" value="<?php echo esc_attr( $settings['security_code'] ); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Add the tracking code to the footer
function statcounter_add_tracking_code() {
    $settings = get_option( 'statcounter', [ 'project_id' => '', 'security_code' => '' ] );

    if ( ! empty( $settings['project_id'] ) && ! empty( $settings['security_code'] ) ) {
        ?>
        <script type="text/javascript">
        var sc_project = '<?php echo esc_js( $settings['project_id'] ); ?>';
        var sc_invisible = 1;
        var sc_security = '<?php echo esc_js( $settings['security_code'] ); ?>';
        var scJsHost = (("https:" == document.location.protocol) ? "https://secure." : "http://www.");
        document.write("<sc" + "ript type='text/javascript' src='" + scJsHost + "statcounter.com/counter/counter.js'></" + "script>");
        </script>
        <?php
    }
}
add_action( 'wp_footer', 'statcounter_add_tracking_code', PHP_INT_MAX );

// Ref: ChatGPT
