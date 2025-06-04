<?php

class Cocojambo_Activate {
	public static function activate() {

		if ( PHP_MAJOR_VERSION < 7 ) {
			die( __( 'Need PHP >= 7', 'cocojambo' ) );
		}

		global $wpdb;
		$wpdb->query( "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}cocojambo_panel` (
					  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
					  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
					  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
    				  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci" );
	}

	public static function deactivate() {
		file_put_contents( COCOJAMBO_PLUGIN_DIR . 'log.txt',
			__( "Plugin Deactivated\n" ), FILE_APPEND );
	}

	public static function addLinksTopluginPanel( $links ) {
		$newLinks = [
			'<a href="' . admin_url( 'admin.php?page=slide-settings' ) . '"> ' . __( 'Settings', 'cocojambo' ) . '</a>',
		];

		return array_merge( $links, $newLinks );
	}
}
