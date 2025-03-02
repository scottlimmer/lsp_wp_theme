<?php

function get_modified_time( $path ): string {
	$path = __DIR__ . "/" . $path;

	return date( 'YmdHi', filemtime( $path ) );
}

// Enqueue stylesheets
function lsp_styles() {
	wp_enqueue_style( 'base', get_template_directory_uri() . '/css/base.css', [], get_modified_time( 'css/base.css' ) );
}

add_image_size( 'tile-thumbnail', 220, 190, true );

// Configure theme features
function lsp_setup() {
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );
	add_theme_support( 'post-thumbnails' );
	setup_title();
	remove_generator();

	register_nav_menu( 'top-menu', __( 'Top Menu' ) );
}

function setup_title(): void {
	add_filter( 'document_title_separator', 'set_document_title_separator' );

	function set_document_title_separator( $sep ): string {
		return ( '|' );
	}

	add_theme_support( 'title-tag' );
}

add_action( 'wp_enqueue_scripts', 'lsp_styles' );

add_action( 'after_setup_theme', 'lsp_setup' );

function remove_generator(): void {
// Remove wordpress generator meta
	add_filter( 'the_generator', function () {
		return '';
	} );
	remove_action( 'wp_head', 'wp_generator' );
}

function get_top_ancestor() {
	$parent_post = get_post();
	while ( $parent_post->post_parent ) {
		$parent_post = get_post( $parent_post->post_parent );
	}

	return $parent_post;
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

function get_news_page(): WP_Post {
	return get_post( get_option( 'page_for_posts' ) );
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


function get_post_slug( int|null $post_id = null ): string {
	if ( is_archive() ) {
		return 'archive';
	}
	if ( is_home() ) {
		return 'archive';
	}

	$post = get_post( $post_id );

	return $post?->post_name ?? '';
}


function validate_sighting_data( array $input_data = [] ) {
	$filters = [
		'sighting_name'     => [
			'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
		],
		'sighting_email'    => [
			'filter' => FILTER_VALIDATE_EMAIL,
		],
		'sighting_location' => [
			'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
		],
		'sighting_date'     => [],
		'sighting_count'    => [
			'filter'  => FILTER_VALIDATE_INT,
			'options' => [
				'min_range' => 0
			]
		],
		'sighting_lat'      => [
			'filter'  => FILTER_VALIDATE_FLOAT,
			'flags'   => FILTER_FLAG_ALLOW_FRACTION,
			'options' => [
				'min_range' => - 45,
				'max_range' => 60
			]
		],
		'sighting_lng'      => [
			'filter'  => FILTER_VALIDATE_FLOAT,
			'flags'   => FILTER_FLAG_ALLOW_FRACTION,
			'options' => [
				'min_range' => 110,
				'max_range' => 180
			]
		],
		'sighting_comments' => [
			'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
		]
	];

	return filter_var_array( $input_data, $filters );

}

add_action( 'init', 'start_session', 1 );
function start_session(): void {
	if ( ! session_id() ) {
		session_start();
	}
}

add_action( 'wp_logout', 'end_session' );
add_action( 'wp_login', 'end_session' );
function end_session(): void {
	session_destroy();
}

function get_missing_data_fields( array $data ): array {
	$error_fields = array_keys( array_filter( $data, function ( $value, $key ) {
		return empty( $value );
	}, ARRAY_FILTER_USE_BOTH ) );
	$error_fields = array_map( function ( $value ) {
		return str_replace( 'sighting_', '', $value );
	}, $error_fields );

	return $error_fields;
}

function upload_to_sheets( $data ) {
	require 'vendor/autoload.php';
	$config = include 'sightings.config.php';

	$client = new Google\Client();
	$client->setAuthConfig( $config['credentialsFile'] );
	$client->setApplicationName( "Report a sighting" );
	$client->addScope( [ 'https://www.googleapis.com/auth/spreadsheets' ] );
	$service = new Google\Service\Sheets( $client );
	$success = false;

	try {
		$values = [ array_values( $data ) ];
		$body   = new Google_Service_Sheets_ValueRange( [
			'values' => $values
		] );
		$params = [
			'valueInputOption' => 'USER_ENTERED'
		];
		$service->spreadsheets_values->append( $config['spreadsheetId'], $config['range'], $body, $params );
		$success = true;
	} catch ( Exception $e ) {
		error_log($e->getMessage());
		error_log($e->getMessage(), 1, 'snipe@lathamssnipeproject.au');

	}

	return $success;
}
