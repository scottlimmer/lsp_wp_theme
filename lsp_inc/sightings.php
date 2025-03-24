<?php

function get_sightings_config() {
	return include get_template_directory().'/sightings.config.php';
}

/**
 * @throws JsonException
 * @throws Exception
 */
function validate_captcha( $captcha ): true {
	$config   = get_sightings_config();
	$response = file_get_contents( 'https://www.google.com/recaptcha/api/siteverify',
		false,
		stream_context_create( [
			'http' => [
				'method'  => 'POST',
				'content' => http_build_query( [
					'secret'   => $config['recaptchaSecret'],
					'response' => $captcha
				] )
			]
		] ) );

	$response = json_decode( $response, null, 512, JSON_THROW_ON_ERROR );

	if ( $response->success !== true ) {
		error_log( 'Failed captcha response' );
		throw new Exception( 'Unable to submit form.' );
	}

	return true;
}

function validate_sighting_data( array $input_data = [] ): array {
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


function get_missing_data_fields( array $data ): array {
	$error_fields = array_keys( array_filter( $data, function ( $value, $key ) {
		return is_null( $value ) or $value === false;
	}, ARRAY_FILTER_USE_BOTH ) );
	$error_fields = array_map( function ( $value ) {
		return str_replace( 'sighting_', '', $value );
	}, $error_fields );

	return $error_fields;
}

function upload_to_sheets( $data ): bool {
	require get_template_directory().'/vendor/autoload.php';
	$config = get_sightings_config();

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
		error_log( $e->getMessage() );
		error_log( $e->getMessage(), 1, 'snipe@lathamssnipeproject.au' );

	}

	return $success;
}