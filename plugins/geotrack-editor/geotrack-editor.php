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
/* Force form multi-part encoding */
add_action( 'post_edit_form_tag', 'gted_add_edit_form_multipart_encoding' );

/** Force form multi-part encoding (for file submission) */
function gted_add_edit_form_multipart_encoding() {

	echo ' enctype="multipart/form-data"';

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
	$duration_s = intval( get_post_meta( $post->ID, '_gted_duration_s', true ) );
	$md5 = get_post_meta( $post->ID, '_gted_md5', true );
	$track_info = get_post_meta( $post->ID, '_gted_track_info', true );
	$file = get_post_meta( $post->ID, '_gted_file', true );
	// See if there's a status message to display (we're using this to show errors during the upload process, though we should probably be using the WP_error class).
	$status_message = get_post_meta( $post->ID, '_gted_file_upload_feedback', true );
	if ( $status_message ) {
		echo '<div class="upload_status_message">';
		echo esc_html( $status_message );
		echo '</div>';
	}
?>
        <label>Upload audio file: <input type="file" name="gted_file" id="gted_file"></label><br>
	<input type="hidden" name="gted_manual_save_flag" value="true" />
	<p>MD5=<?php esc_html_e( $md5 ) ?>
	duration=<?php esc_html_e( $duration_s ) ?>
	track_info=<?php esc_html_e( $track_info ) ?>
	file=<?php esc_html_e( $file ) ?></p>
<?php
}
/** Get audio file directory.
 *
 *  @return string Audio file directory path with trailing '/'.
 */
function gted_get_audio_dir() {
	// TODO: keep these files somewhere else that isn't directly accessible.
	// Returns 'basedir' (no trailing slash) and 'baseurl' (trailing slash).
	$upload = wp_upload_dir();
	$dir = trailingslashit( $upload['basedir'] ) . 'geotracks';
	if ( ! wp_mkdir_p( $dir ) ) {
		return null; }
	return trailingslashit( $dir );
}
/**
 * Get audio file path.
 *
 * @param string $md5 Md5.
 * @param string $ext File extension.
 * @return string File path.
 */
function gted_get_audio_file( $md5, $ext ) {
	// TODO: multiple sub-directories?!.
	$dir = gted_get_audio_dir();
	if ( null === $dir ) {
		return null; }
	return $dir . $md5 . '.' . $ext;
}
/* Register save_post handler. */
add_action( 'save_post_geotrack', 'gted_save_geotrack' );
/**
 * Save post form handler (geotrack).
 *
 * @param int $post_id Post being saved.
 */
function gted_save_geotrack( $post_id ) {
	// See  http://wordpress.stackexchange.com/questions/4307/how-can-i-add-an-image-upload-field-directly-to-a-custom-write-panel/4413#4413.
	if ( $post_id && isset( $_POST['gted_manual_save_flag'] ) ) {
		if ( isset( $_FILES['gted_file'] ) ) {
			if ( ! isset( $_FILES['gted_file']['error'] ) || is_array( $_FILES['gted_file']['error'] ) ) {
				$upload_feedback = 'There was a problem with your upload ($_FILES[...]["error"] not valid)';
			} else if ( UPLOAD_ERR_NO_FILE === $_FILES['gted_file']['error'] ) {
				$upload_feedback = 'There was a problem with your upload (no file sent)';
			} else if ( UPLOAD_ERR_INI_SIZE === $_FILES['gted_file']['error'] ) {
				$upload_feedback = 'There was a problem with your upload (exceeded ini upload size)';
			} else if ( UPLOAD_ERR_FORM_SIZE === $_FILES['gted_file']['error'] ) {
				$upload_feedback = 'There was a problem with your upload (exceeded form upload size)';
			} else if ( UPLOAD_ERR_OK !== $_FILES['gted_file']['error'] ) {
				$upload_feedback = 'There was a problem with your upload (unknown $_FILES error '.$_FILES['gted_file']['error'].')';
			} else if ( $_FILES['gted_file']['size'] <= 0 ) {
				$upload_feedback = 'There was a problem with your upload (file too small)';
			} else {
				$arr_file_type = wp_check_filetype( basename( $_FILES['gted_file']['name'] ) );
				$uploaded_file_type = $arr_file_type['type'];
				$allowed_file_types = array( 'audio/mp3', 'audio/mpeg', 'audio/ogg' );
				if ( in_array( $uploaded_file_type, $allowed_file_types ) ) {
					$tmp_file = $_FILES['gted_file']['tmp_name'];
					$upload_feedback = 'Uploaded '.$_FILES['gted_file']['name'].' ('.$_FILES['gted_file']['size'].' bytes)';
					$md5 = md5_file( $tmp_file );
					if ( false === $md5 ) {
						$upload_feedback = 'There was a problem with your upload (could not get MD5 for uploaded file)';
					} else {
						$metadata = wp_read_audio_metadata( $tmp_file );
						update_post_meta( $post_id, '_gted_md5', $md5 );
						$duration_s = '';
						if ( ! empty( $metadata['length'] ) ) {
							$duration_s = intval( $metadata['length'] );
						}
						$track_info = array();
						if ( ! empty( $metadata['title'] ) ) {
							$track_info['title'] = $metadata['title'];
						}
						if ( ! empty( $metadata['artist'] ) ) {
							$track_info['artist'] = $metadata['artist'];
						}
						if ( ! empty( $metadata['album'] ) ) {
							$track_info['album'] = $metadata['album'];
						}
						if ( ! empty( $metadata['year'] ) ) {
							$track_info['year'] = $metadata['year'];
						}
						update_post_meta( $post_id, '_gted_duration_s', $duration_s );
						update_post_meta( $post_id, '_gted_track_info', json_encode( $track_info ) );
						update_post_meta( $post_id, '_gted_file_ext', $arr_file_type['ext'] );
						$file = gted_get_audio_file( $md5, $arr_file_type['ext'] );
						if ( null === $file ) {
							$upload_feedback = 'There was a problem with your upload (could not get audio dir, '.gted_get_audio_dir().', upload dir='.json_encode( wp_upload_dir() ).')';
						} else if ( ! move_uploaded_file( $_FILES['gted_file']['tmp_name'], $file ) ) {
							$upload_feedback = 'There was a problem with your upload (could not move file to '.$file.')';
						} else {
							update_post_meta( $post_id, '_gted_file', $file );
						}
					}
				} else {
					// Error - file type.
					$upload_feedback = 'Please upload only MP3 or OGG audio files.';
				}
			}
		} else {
			// No file.
			$upload_feedback = 'No file';
		}
		update_post_meta( $post_id, '_gted_file_upload_feedback',$upload_feedback );

		// Default track title from metadata.
		$track_info = get_post_meta( $post_id, '_gted_track_info', true );
		if ( ! empty( $track_info ) ) {
			$track_info = json_decode( $track_info, true );
			$title = '';
			if ( ! empty( $track_info['title'] ) ) {
				$title = $track_info['title'];
			}
			if ( ! empty( $track_info['artist'] ) ) {
				$title = $title . ' by ' . $track_info['artist'];
			}
			if ( ! empty( $track_info['album'] ) ) {
				$title = $title . ' from ' . $track_info['album'];
			}
			if ( ! empty( $track_info['year'] ) ) {
				$title = $title . ' (' . $track_info['year'] . ')';
			}
			if ( '' != $title && empty( $_POST['post_title'] ) ) {
				$_POST['post_title'] = $title;
				$pvals = array(
					'ID' => $post_id,
					'post_title' => $title,
				);
				wp_update_post( $pvals );
			}
		}
	}
}
/**
 * Html generator for geolist custom metabox.
 *
 * @param post $post post being edited.
 */
function gted_geolist_custom_box( $post ) {
	$tracks = get_post_meta( $post->ID, '_gted_geolist', true );
	if ( ! $tracks ) {
		$tracks = '[]';
	}
?>
<script type="text/javascript">var gted_geolist_id=<?php esc_attr_e( $post->ID ); ?>;</script>
<input id="gted_geolist" type="hidden" name="gted_geolist" value="<?php echo( filter_var( $tracks, FILTER_SANITIZE_SPECIAL_CHARS ) ) ?>">
<?php
	include( dirname( __FILE__ ) . '/partials/geolist.php' );
}
/* Register save_post handler. */
add_action( 'save_post_geolist', 'gted_save_geolist' );
/**
 * Save post form handler (geolist).
 *
 * @param int $post_id Post being saved.
 */
function gted_save_geolist( $post_id ) {
	if ( array_key_exists( 'gted_geolist', $_POST ) ) {
			$tracks = $_POST['gted_geolist'];
		if ( ! $tracks ) {
				$tracks = '[]'; }
			// Wp_slash allegedly required to preserve escaped chars in JSON, but I found it escaped double quotes and they never got unescapted.
			update_post_meta( $post_id, '_gted_geolist', $tracks );
	}
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
	wp_enqueue_style( 'gted-css',
		plugins_url( '/css/gted.css', __FILE__ )
	);
}
/* AJAX entry. */
if ( is_admin() ) {
	add_action( 'wp_ajax_gted_search_geotracks', 'gted_search_geotracks' );
}
/**
 * Geotrack Search AJAX entry.
 */
function gted_search_geotracks() {
	check_ajax_referer( 'gted_editor' );
	$data = $_POST ['data'];
	if ( ! $data ) {
		// Bad request.
		wp_send_json_error( 'no data' );
	}
	// Why does wordpress escape?
	$query = json_decode( stripslashes( $data ), true );
	if ( null === $query ) {
		// Bad request.
		wp_send_json_error( 'Not JSON: ' + $data );
	}
	$personal = false;
	if ( array_key_exists( 'personal', $query ) ) {
		$personal = boolval( $query['personal'] );
	}
	// Consider more complex keyword queries, see https://codex.wordpress.org/Custom_Queries.
	$text = null;
	if ( array_key_exists( 'text', $query ) ) {
		$text = sanitize_text_field( strval( $query['text'] ) );
	}
	$offset = 0;
	if ( array_key_exists( 'offset', $query ) ) {
		$offset = intval( $query['offset'] );
	}

	$args = array(
			'post_type' => 'geotrack',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => 11,
			's' => $text,
			'offset' => $offset,
	);
	// Offset offset.
	if ( $personal ) {
		$args['author'] = wp_get_current_user()->ID;
		$args['post_state'] = array( 'publish', 'private' );
	}
	// Default is publish.
	$q = new WP_Query( $args );
	$res = array();
	while ( $q->have_posts() ) {
		$post = $q->next_post();
		$duration_s = intval( get_post_meta( $post->ID, '_gted_duration_s', true ) );
		$md5 = get_post_meta( $post->ID, '_gted_md5', true );
		$file_ext = get_post_meta( $post->ID, '_gted_file_ext', true );
		if ( empty( $file_ext ) ) {
			$file_ext = '.mp3'; }
		$res[] = array(
				id => $post->ID,
				title => $post->post_title,
				duration_s => $duration_s,
				md5 => $md5,
				file_ext => $file_ext,
		);
	}
	wp_send_json_success( $res );
}
