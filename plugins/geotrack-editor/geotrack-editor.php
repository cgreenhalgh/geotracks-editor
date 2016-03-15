<?php
/**
Plugin Name: geotrack-editor
Plugin URI: https://github.com/cgreenhalgh/geotrack-editor
Description: autoring for geo-located play lists.
Version: 0.1
Author: Chris Greenhalgh
Author URI: http://www.cs.nott.ac.uk/~cmg/
Network: true
License: AGPL-3.0

@package gted
 */

/*
Geotrack Editor - wordpress plugin creating geo-located play lists.

Copyright (c) 2016 The University of Nottingham

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as
published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
defined( 'ABSPATH' ) or die( 'This is a plugin' );

add_action( 'init', 'gted_create_post_types' );
/**
 * Register the geotrack and geolist post type.
 */
function gted_create_post_types() {
	register_post_type( 'geotrack',
		array(
			'labels' => array(
				'name' => __( 'Geotrack' ),
				'singular_name' => __( 'Geotrack' ),
				'add_new_item' => __( 'Add New Geotrack' ),
				'edit_item' => __( 'Edit Geotrack' ),
				'new_item' => __( 'New Geotrack' ),
				'view_item' => __( 'View Geotrack Info' ),
				'search_items' => __( 'Search Geotracks' ),
				'not_found' => __( 'No Geotrack found' ),
				'not_found_in_trash' => __( 'No Geotrack found in Trash' ),
				'all_items' => __( 'All Geotracks' ),
			),
			'description' => __( 'Geo-located music track' ),
			'public' => true,
			'has_archive' => true,
			'supports' => array( 'title', 'editor', 'author', 'revisions', 'comments', 'thumbnail' ),
			'menu_icon' => 'dashicons-format-audio',
		)
	);
	register_post_type( 'geolist',
		array(
			'labels' => array(
				'name' => __( 'Geolist' ),
				'singular_name' => __( 'Geolist' ),
				'add_new_item' => __( 'Add New Geolist' ),
				'edit_item' => __( 'Edit Geolist' ),
				'new_item' => __( 'New Geolist' ),
				'view_item' => __( 'View Geolist Info' ),
				'search_items' => __( 'Search Geolist' ),
				'not_found' => __( 'No Geolist found' ),
				'not_found_in_trash' => __( 'No Geolist found in Trash' ),
				'all_items' => __( 'All Geolists' ),
			),
			'description' => __( 'Geo-located play list' ),
			'public' => true,
			'has_archive' => true,
			'supports' => array( 'title', 'editor', 'author', 'revisions', 'comments', 'thumbnail' ),
			'menu_icon' => 'dashicons-playlist-audio',
		)
	);
}

/* Adds a meta box to the post edit screen. */
add_action( 'add_meta_boxes', 'gted_add_custom_box' );
/**
 * Define custom metaboxes.
 */
function gted_add_custom_box() {
	add_meta_box(
		'gted_geotrack_box_id',        // Unique ID.
		'Geotrack Settings', 	    // Box title.
		'gted_geotrack_custom_box',  // Content callback.
		'geotrack',               // Post type.
		'normal', 'high'
	);
		add_meta_box(
			'gted_geolist_box_id',        // Unique ID.
			'Geolist Settings',        // Box title.
			'gted_geolist_custom_box',  // Content callback.
			'geolist',               // Post type.
			'normal', 'high'
		);
}
/**
 * Html generator for geotrack custom metabox.
 *
 * @param post $post post being edited.
 */
function gted_geotrack_custom_box( $post ) {
	$duration_ms = intval( get_post_meta( $post->ID, '_gted_duration_ms', true ) );
?>
	<label><input type="number" name="gted_duration_ms" value="<?php esc_attr_e( $duration_ms ) ?>">Duration (ms)</label><br>
<?php
}
/* Register save_post handler. */
add_action( 'save_post_geotrack', 'gted_save_geotrack' );
/**
 * Save post form handler (geotrack).
 *
 * @param int $post_id Post being saved.
 */
function gted_save_geotrack( $post_id ) {
	if ( array_key_exists( 'gted_duration_ms', $_POST ) ) {
		$duration_ms = intval( $_POST['gted_duration_ms'] );
		if ( ! $duration_ms ) {
			$duration_ms = 0; }
		update_post_meta( $post_id, '_gted_duration_ms', $duration_ms );
	}
}
/**
 * Html generator for geolist custom metabox.
 *
 * @param post $post post being edited.
 */
function gted_geolist_custom_box( $post ) {
?>
<div ng-app="gted">
	<div ng-controller="test">{{ test }}</div>
</div>
<?php
}
/* Register save_post handler. */
add_action( 'save_post_geolist', 'gted_save_geolist' );
/**
 * Save post form handler (geolist).
 *
 * @param int $post_id Post being saved.
 */
function gted_save_geolist( $post_id ) {
}
/* Hook to enqueue scripts and style for my metaboxes. */
add_action( 'admin_enqueue_scripts', 'gted_enqueue' );
/**
 * Enqueue scripts and styles for my metaboxes.
 *
 * @param string $hook Page name.
 */
function gted_enqueue( $hook ) {
	if ( 'post.php' != $hook && 'post-new.php' != $hook ) { return; }
	wp_register_script( 'gted-angular',
		plugins_url( '/vendor/angular/angular.js', __FILE__ ),
		array( 'jquery' )
	);
	wp_enqueue_script( 'gted-editor',
		plugins_url( '/js/editor.js', __FILE__ ),
		array( 'jquery', 'gted-angular' )
	);
	$gted_nonce = wp_create_nonce( 'gted_editor' );
	wp_localize_script( 'gted-editor', 'gted_wp', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => $gted_nonce,
	) );
}
