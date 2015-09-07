<?php

/**
 * The plugin bootstrap file
 *
 * @link              http://webstractions.com
 * @since             1.0.0
 * @package           WPPBS
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Plugin Settings Boilerplate
 * Plugin URI:        http://webstractions.com/wppbs-uri/
 * Description:       An example plugin boilerplate for using the WordPress Settings API.
 * Version:           1.0.0
 * Author:            Ron Pugmire <webstractions@gmail.com>
 * Author URI:        http://webstractions.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wppbs
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wppbs-activator.php
 */
function activate_wppb_settings() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wppbs-activator.php';
	WPPBS_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wppbs-deactivator.php
 */
function deactivate_wppb_settings() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wppbs-deactivator.php';
	WPPBS_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wppb_settings' );
register_deactivation_hook( __FILE__, 'deactivate_wppb_settings' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wppbs.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wppb_settings() {

	$plugin = new WPPBS();
	$plugin->run();

}
run_wppb_settings();
