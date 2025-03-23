<?php


add_filter( 'excerpt_more', fn() => '...' );

// Enqueue stylesheets
function lsp_styles(): void {

	wp_enqueue_style(
		'base',
		get_template_directory_uri() . '/dist/main.css',
		[],
		get_modified_time( get_template_directory() . '/dist/main.css' )
	);


	wp_enqueue_script(
		'main',
		get_template_directory_uri() . '/dist/main.js',
		[],
		get_modified_time( get_template_directory() . '/dist/main.js' )
	);

	if ( is_page( 'report-a-sighting' ) ) {
		$config = include 'sightings.config.php';

		wp_enqueue_script(
			'recaptcha-sighting',
			"https://www.google.com/recaptcha/api.js?render={$config['recaptchaKey']}"
		);
		wp_add_inline_script(
			'recaptcha-sighting',
			<<<JS
		window.addEventListener('load', function() {
            let form = document.getElementById('report-a-sighting');
            if (form) {
				form.addEventListener('submit', submit_recaptcha);
            }
		})
		
        function submit_recaptcha() {
            grecaptcha.ready(function () {
                grecaptcha.execute('{$config['recaptchaKey']}', {action: 'report_a_sighting'}).then(function (token) {
                    document.getElementById('g-recaptcha-response').value = token;
                    document.getElementById('report-a-sighting').submit();
                });
            });
         }

JS,
			'after'
		);
	}
}

add_action( 'wp_enqueue_scripts', 'lsp_styles' );

// Add custom thumbnail size
add_image_size( 'tile-thumbnail', 220, 190, true );


function setup_title(): void {
	add_filter( 'document_title_separator', fn() => '|' );

	add_theme_support( 'title-tag' );
}


function remove_generator(): void {
// Remove wordpress generator meta
	add_filter( 'the_generator', fn() => '' );
	remove_action( 'wp_head', 'wp_generator' );
}

// Configure theme features
function lsp_setup(): void {
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );
	add_theme_support( 'post-thumbnails' );
	setup_title();
	remove_generator();

	register_nav_menu( 'top-menu', __( 'Top Menu' ) );
}

add_action( 'after_setup_theme', 'lsp_setup' );

