<?php
/**
 * Class autoloader
 *
 * @package gted
 */

defined( 'ABSPATH' ) or die( 'Not allowed' );

spl_autoload_register(
	function( $classname ) {
		$path = dirname( __FILE__ ) . '/' . strtolower( $classname ) . '.php';
		if ( file_exists( $path ) ) {
			include $path;
		}
	}, true, true
);
