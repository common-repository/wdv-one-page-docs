<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wdvillage.com/
 * @since             1.0.0
 * @package           Wdv_One_Page_Docs
 *
 * @wordpress-plugin
 * Plugin Name:       WDV One Page Docs
 * Plugin URI:        https://wdvillage.com/product/wdv-one-page-docs/
 * Description:       A one page documentation plugin for WordPress
 * Version:           1.2.3
 * Author:            Wdvillage
 * Author URI:        https://wdvillage.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wdv-one-page-docs
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WDV_ONE_PAGE_DOCS_VERSION', '1.2.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wdv-one-page-docs-activator.php
 */
function activate_wdv_one_page_docs() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wdv-one-page-docs-activator.php';
	Wdv_One_Page_Docs_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wdv-one-page-docs-deactivator.php
 */
function deactivate_wdv_one_page_docs() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wdv-one-page-docs-deactivator.php';
	Wdv_One_Page_Docs_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wdv_one_page_docs' );
register_deactivation_hook( __FILE__, 'deactivate_wdv_one_page_docs' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wdv-one-page-docs.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wdv_one_page_docs() {

	$plugin = new Wdv_One_Page_Docs();
	$plugin->run();

}
run_wdv_one_page_docs();
