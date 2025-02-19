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

	return $post->post_name;
}

