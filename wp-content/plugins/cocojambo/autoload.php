<?php

spl_autoload_register( 'cocojambo_autoload' );

function cocojambo_include( $relpath ) {
	$path = plugin_dir_path( __FILE__ ) . '/' . $relpath;
	if ( ! file_exists( $path ) ) {
		$message = __('File not found: ','cocojambo') . $path;
		throw new Exception( $message . '; additionally src/error/Exception.php not loadable' );
	}

	return include $path;
}

function cocojambo_autoload( $name ) {
	if ( 'Cocojambo' === substr( $name, 0, 9 ) ) {
		cocojambo_include( 'src/' . strtr( substr( $name, 9 ), '', '/' ) . '.php' );
	}
}