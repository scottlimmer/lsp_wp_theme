<?php
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 20/10/2018
 * Time: 11:57 AM
 */


class AllowResubmitException extends Exception {
}

$config        = get_sightings_config();
$show_form     = true;
$error_message = null;

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ):
	try {
		// Check CSRF / nonce
		$token = filter_input(
			INPUT_POST,
			'_csrf',
			FILTER_SANITIZE_SPECIAL_CHARS
		);
		if ( ! $token || ! wp_verify_nonce( $token, 'report-a-sighting-nonce' ) ) {
			throw new Exception( 'Form already submitted.' );
		}

		// Sanitise and validate data
		$data         = validate_sighting_data( $_POST );
		$error_fields = get_missing_data_fields( $data );
		if ( ! empty( $error_fields ) ) {
			throw new AllowResubmitException(
				"There was a problem submitting your sighting. Please check the following
                        fields: " . implode( ', ', $error_fields )
			);
		}

		// Validate captcha
		$captcha_response = filter_input(
			INPUT_POST,
			'g-recaptcha-response',
			FILTER_SANITIZE_SPECIAL_CHARS
		);
		if ( ! $captcha_response ) {
			error_log( 'No captcha response found.' );
			throw new Exception( 'Unable to submit form.' );
		}

		if ( validate_captcha( $captcha_response ) ) {
			$data['submitted'] =
				( new DateTime(
					'now',
					( new DateTimeZone( 'UTC' ) )
				) )->format(
					DateTime::ATOM
				);
		}

		// Submit data
		if ( ! upload_to_sheets( $data ) ) {
			throw new Exception( 'Unable to submit sighting.' );
		}

		header( 'Location: /sighting-thank-you' );
		exit;
	} catch ( JsonException $e ) {
		error_log( 'Unable to parse recaptcha response' );
		$error_message = 'Unable to submit form.';
	} catch ( AllowResubmitException $e ) {
		$error_message = $e->getMessage();
	} catch ( \Exception $e ) {
		$error_message = $e->getMessage();
		$show_form     = false;
	}
endif;


get_header();
?>

    <div class="sidebar">

    </div>

    <div class="content">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) : the_post(); ?>
                <h1><?php the_title() ?></h1>
				<?php
				the_content();
			endwhile;
		endif;
		?>

		<?php if ( $error_message ) : ?>
            <div class="alert alert-danger" role="alert">
				<?= $error_message ?>
            </div>
		<?php endif; ?>

		<?php if ( $show_form ) : ?>

            <form action="" method="POST" id="report-a-sighting" class="mb-4">

                <input type="hidden" name="_csrf" value="<?= wp_create_nonce( 'report-a-sighting-nonce' ) ?>">

                <div class="mb-3">
                    <label class="form-label" for="sighting_name">Name</label>
                    <input type="text" id="sighting_name" name="sighting_name" class="form-control"
                           value="<?= $_POST['sighting_name'] ?? '' ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="sighting_email">Email</label>
                    <input type="email" id="sighting_email" name="sighting_email" class="form-control"
                           value="<?= $_POST['sighting_email'] ?? '' ?>" required>
                </div>


                <div class="mb-3">
                    <label class="form-label" for="sighting_location">Location/Site name</label>
                    <input type="text" id="sighting_location" name="sighting_location" class="form-control"
                           value="<?= $_POST['sighting_location'] ?? '' ?>" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="sighting_date">Date</label>
                        <input type="date" id="sighting_date" name="sighting_date" class="form-control"
                               max="<?= date( 'Y-m-d' ) ?>"
                               value="<?= $_POST['sighting_date'] ?? '' ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="col-form-label" for="sighting_count">Count</label>
                        <input type="number" id="sighting_count" name="sighting_count" min="0" class="form-control"
                               value="<?= $_POST['sighting_count'] ?? '' ?>" placeholder="0" required>
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="sighting_lat">Latitude</label>
                        <input type="number" id="sighting_lat" name="sighting_lat" step="any" min="-45" max="60"
                               class="form-control"
                               placeholder="-38.387706" value="<?= $_POST['sighting_lat'] ?? '' ?>"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="sighting_lng">Longitude</label>
                        <input type="number" id="sighting_lng" name="sighting_lng" step="any" min="110" max="180"
                               class="form-control"
                               placeholder="142.224398" value="<?= $_POST['sighting_lng'] ?? '' ?>"
                               required>
                    </div>
                </div>


                <div class="mb-3">
                    <label class="form-label" for="sighting_comments">Comments</label>
                    <textarea id="sighting_comments" name="sighting_comments" rows="4"
                              class="form-control" required><?= $_POST['sighting_comments'] ?? '' ?></textarea>
                </div>


                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response" value="">

                <button type="button" onclick="submit_recaptcha()" class="btn btn-primary">Submit</button>

            </form>
            <p>If you are having difficulties submitting your sighting, please use the generic <a
                        href="/contact">Contact</a>
                form.</p>
		<?php endif; ?>
    </div>
<?php
get_footer();