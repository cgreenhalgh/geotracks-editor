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

