<?php
/*
Plugin Name: Customify Sites
Plugin URI: http://wordpress.org/extend/plugins/wordpress-importer/
Description: Import posts, pages, comments, custom fields, categories, tags and more from a WordPress export file.
Author: wordpressdotorg, rmccue
Author URI: http://wordpress.org/
Version: 2.0
Text Domain: wordpress-importer
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

define( 'CUSTOMIFY_SITES_URL', untrailingslashit( plugins_url(  '', __FILE__ ) ) );
define( 'CUSTOMIFY_SITES_PATH',dirname( __FILE__ ) );

if ( ! class_exists( 'WP_Importer' ) ) {
	defined( 'WP_LOAD_IMPORTERS' ) || define( 'WP_LOAD_IMPORTERS', true );
	require ABSPATH . '/wp-admin/includes/class-wp-importer.php';
}

require dirname( __FILE__ ) . '/importer/class-logger.php';
require dirname( __FILE__ ) . '/importer/class-logger-serversentevents.php';
require dirname( __FILE__ ) . '/importer/class-wxr-importer.php';
require dirname( __FILE__ ) . '/importer/class-wxr-import-info.php';
require dirname( __FILE__ ) . '/importer/class-wxr-import-ui.php';

require dirname( __FILE__ ) . '/classess/class-tgm.php';
require dirname( __FILE__ ) . '/classess/class-plugin.php';
require dirname( __FILE__ ) . '/classess/class-sites.php';
require dirname( __FILE__ ) . '/classess/class-ajax.php';


Customify_Sites::get_instance();

add_action( 'admin_init', array( 'Customify_Sites', 'get_instance' ) );
