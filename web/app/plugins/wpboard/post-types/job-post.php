<?php

/**
 * Registers the `job_post` post type.
 */
function job_post_init() {
	register_post_type(
		'job-post',
		[
			'labels'                => [
				'name'                  => __( 'Job Posts', 'wpboard' ),
				'singular_name'         => __( 'Job Post', 'wpboard' ),
				'all_items'             => __( 'All Job Posts', 'wpboard' ),
				'archives'              => __( 'Job Post Archives', 'wpboard' ),
				'attributes'            => __( 'Job Post Attributes', 'wpboard' ),
				'insert_into_item'      => __( 'Insert into Job Post', 'wpboard' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Job Post', 'wpboard' ),
				'featured_image'        => _x( 'Featured Image', 'job-post', 'wpboard' ),
				'set_featured_image'    => _x( 'Set featured image', 'job-post', 'wpboard' ),
				'remove_featured_image' => _x( 'Remove featured image', 'job-post', 'wpboard' ),
				'use_featured_image'    => _x( 'Use as featured image', 'job-post', 'wpboard' ),
				'filter_items_list'     => __( 'Filter Job Posts list', 'wpboard' ),
				'items_list_navigation' => __( 'Job Posts list navigation', 'wpboard' ),
				'items_list'            => __( 'Job Posts list', 'wpboard' ),
				'new_item'              => __( 'New Job Post', 'wpboard' ),
				'add_new'               => __( 'Add New', 'wpboard' ),
				'add_new_item'          => __( 'Add New Job Post', 'wpboard' ),
				'edit_item'             => __( 'Edit Job Post', 'wpboard' ),
				'view_item'             => __( 'View Job Post', 'wpboard' ),
				'view_items'            => __( 'View Job Posts', 'wpboard' ),
				'search_items'          => __( 'Search Job Posts', 'wpboard' ),
				'not_found'             => __( 'No Job Posts found', 'wpboard' ),
				'not_found_in_trash'    => __( 'No Job Posts found in trash', 'wpboard' ),
				'parent_item_colon'     => __( 'Parent Job Post:', 'wpboard' ),
				'menu_name'             => __( 'Job Posts', 'wpboard' ),
			],
			'public'                => true,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'supports'              => [ 'title', 'editor' ],
			'has_archive'           => true,
			'rewrite'               => true,
			'query_var'             => true,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-admin-post',
			'show_in_rest'          => true,
			'rest_base'             => 'job-post',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		]
	);

}

add_action( 'init', 'job_post_init' );

/**
 * Sets the post updated messages for the `job_post` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `job_post` post type.
 */
function job_post_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['job-post'] = [
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Job Post updated. <a target="_blank" href="%s">View Job Post</a>', 'wpboard' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'wpboard' ),
		3  => __( 'Custom field deleted.', 'wpboard' ),
		4  => __( 'Job Post updated.', 'wpboard' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Job Post restored to revision from %s', 'wpboard' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Job Post published. <a href="%s">View Job Post</a>', 'wpboard' ), esc_url( $permalink ) ),
		7  => __( 'Job Post saved.', 'wpboard' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Job Post submitted. <a target="_blank" href="%s">Preview Job Post</a>', 'wpboard' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Job Post scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Job Post</a>', 'wpboard' ), date_i18n( __( 'M j, Y @ G:i', 'wpboard' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Job Post draft updated. <a target="_blank" href="%s">Preview Job Post</a>', 'wpboard' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	];

	return $messages;
}

add_filter( 'post_updated_messages', 'job_post_updated_messages' );

/**
 * Sets the bulk post updated messages for the `job_post` post type.
 *
 * @param  array $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
 *                              keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
 * @param  int[] $bulk_counts   Array of item counts for each message, used to build internationalized strings.
 * @return array Bulk messages for the `job_post` post type.
 */
function job_post_bulk_updated_messages( $bulk_messages, $bulk_counts ) {
	global $post;

	$bulk_messages['job-post'] = [
		/* translators: %s: Number of Job Posts. */
		'updated'   => _n( '%s Job Post updated.', '%s Job Posts updated.', $bulk_counts['updated'], 'wpboard' ),
		'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 Job Post not updated, somebody is editing it.', 'wpboard' ) :
						/* translators: %s: Number of Job Posts. */
						_n( '%s Job Post not updated, somebody is editing it.', '%s Job Posts not updated, somebody is editing them.', $bulk_counts['locked'], 'wpboard' ),
		/* translators: %s: Number of Job Posts. */
		'deleted'   => _n( '%s Job Post permanently deleted.', '%s Job Posts permanently deleted.', $bulk_counts['deleted'], 'wpboard' ),
		/* translators: %s: Number of Job Posts. */
		'trashed'   => _n( '%s Job Post moved to the Trash.', '%s Job Posts moved to the Trash.', $bulk_counts['trashed'], 'wpboard' ),
		/* translators: %s: Number of Job Posts. */
		'untrashed' => _n( '%s Job Post restored from the Trash.', '%s Job Posts restored from the Trash.', $bulk_counts['untrashed'], 'wpboard' ),
	];

	return $bulk_messages;
}

add_filter( 'bulk_post_updated_messages', 'job_post_bulk_updated_messages', 10, 2 );
