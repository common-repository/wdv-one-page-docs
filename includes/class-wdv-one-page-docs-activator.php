<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wdvillage.com/
 * @since      1.0.0
 *
 * @package    Wdv_One_Page_Docs
 * @subpackage Wdv_One_Page_Docs/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wdv_One_Page_Docs
 * @subpackage Wdv_One_Page_Docs/includes
 * @author     Wdvillage <wdvillage100@gmail.com>
 */
class Wdv_One_Page_Docs_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
               global $wpdb;
                $wdv_docs_db_version = '1.0.0';

				// Table settings
				$table_name = $wpdb->prefix . 'wdv_one_page_settings';
				$charset_collate = $wpdb->get_charset_collate();

				if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {
				$sql = "CREATE TABLE $table_name (
				                        `id` int(11) NOT NULL AUTO_INCREMENT,
				                        `optionname` varchar(250) NOT NULL,
				                        `price` int(5) NOT NULL,
				                        `type` varchar(50) NOT NULL,
				                        PRIMARY KEY  (id)
				                        ) $charset_collate;";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
			}

                // Table shortcode
                $table_name = $wpdb->prefix . 'wdv_one_page_shortcodes';
                $charset_collate = $wpdb->get_charset_collate();

                if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {
                $sql = "CREATE TABLE $table_name (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                        `title` varchar(250) NOT NULL,
                                        `shortcode` varchar(50) NOT NULL,
                                        `docid` int(11) NOT NULL,
                                        /*`posturl` varchar(250) NOT NULL,
                                        `pageurl` varchar(250) NOT NULL,*/
                                        `posttitle` varchar(250) NOT NULL,
                                        `pagetitle` varchar(250) NOT NULL,
                                        `postid` int(11) NOT NULL,
                                        `pageid` int(11) NOT NULL,
                                        `hide` boolean not null default 0,
                                        PRIMARY KEY  (id)
                                        ) $charset_collate;";

                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                dbDelta( $sql );
            }

                add_option( 'wdv_docs_db_version', $wdv_docs_db_version );
	}

}
