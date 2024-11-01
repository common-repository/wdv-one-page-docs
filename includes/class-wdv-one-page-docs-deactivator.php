<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://wdvillage.com/
 * @since      1.0.0
 *
 * @package    Wdv_One_Page_Docs
 * @subpackage Wdv_One_Page_Docs/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wdv_One_Page_Docs
 * @subpackage Wdv_One_Page_Docs/includes
 * @author     Wdvillage <wdvillage100@gmail.com>
 */
class Wdv_One_Page_Docs_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
	//drop a custom db table
	 //global $wpdb;
	 //$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'wdv_docs_settings' );
	}

}
