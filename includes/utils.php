<?php


function get_modified_time( $path ): string {
	return date( 'YmdHi', filemtime( $path ) );
}


function get_active_template(): string {
	global $template;

	$basename = basename( $template, '.php' );
	if ( str_starts_with( $basename, 'page-' ) ) {
		$basename = "page $basename";
	}
	switch ( $basename ) {
		case '404':
			$basename = 'four-zero-four';
		default:
	}

	return $basename;

}


function get_child_pages( int|WP_Post $parent_id = null ): WP_Query {

	if ( $parent_id instanceof WP_Post ) {
		$parent_id = $parent_id->ID;
	}


	if ( ! is_integer( $parent_id ) ) {
		$parent_id = get_the_ID(); // Get current page ID if no parent is specified
	}

	$args = array(
		'post_type'      => 'page',
		'posts_per_page' => - 1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_parent'    => $parent_id,
	);

	return new WP_Query( $args );
}


function get_page_by_slug( string $slug ): WP_Post|null {
	$args = [
		'pagename' => $slug,
	];

	$query = ( new WP_Query( $args ) );

	if ( $query->have_posts() ) {
		return $query->next_post();
	} else {
		return null;
	}
}

function get_news_page(): WP_Post {
	return get_post( get_option( 'page_for_posts' ) );
}

function get_post_slug( int|null $post_id = null ): string {
	if ( is_archive() || is_home() ) {
		return 'archive';
	}

	$post = get_post( $post_id );

	return $post?->post_name ?? '';
}


/**
 * Retrieves the top ancestor of a given post.
 *
 * @param WP_Post|null $parent_post Optional. The parent post object to find the top ancestor for. Defaults to the current post if not provided.
 *
 * @return WP_Post The top ancestor post object.
 */
function get_top_ancestor( ?WP_Post $parent_post = null ): WP_Post {
	$parent_post = $parent_post ?: get_post();
	while ( $parent_post->post_parent ) {
		$parent_post = get_post( $parent_post->post_parent );
	}

	return $parent_post;
}

/**
 * Retrieves a list of years for which there are published posts.
 *
 * This function uses the WordPress database to query distinct years from the post_date
 * field in the wp_posts table where the post type is 'post' and status is 'publish'.
 * The results are cached using wp_cache_get and wp_cache_add to optimize performance
 * by reducing repetitive database queries.
 *
 * @return array An array of objects containing year information, sorted in descending order.
 */
function get_archive_years(): array {
	global $wpdb;

	// Attempt to retrieve the list of years from cache
	if ( wp_cache_get( 'year_list' ) === false ) {

		// Query the database for distinct years where posts are published
		$year_list = $wpdb->get_results(
			<<<SQL
                SELECT 
                    DISTINCT YEAR(post_date) as year
                FROM {$wpdb->prefix}posts
                WHERE post_type = 'post' AND post_status = 'publish' 
                ORDER BY YEAR(post_date) DESC
                SQL
		);

		// Cache the result for future use to optimize performance
		wp_cache_add( 'year_list', $year_list );
	}

	// Return the cached or freshly retrieved list of years
	return wp_cache_get( 'year_list' );
}