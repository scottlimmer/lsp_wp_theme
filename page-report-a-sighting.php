<?php
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 20/10/2018
 * Time: 11:57 AM
 */


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
		<?php
		$show_form    = true;
		$show_sucess  = false;
		$error_fields = [];
		start_session();

		// Fetch current csrf then regenerate
		$csrf_token                      = $_SESSION['sighting_csrf_token'] ?? null;
		$_SESSION['sighting_csrf_token'] = bin2hex( random_bytes( 32 ) );

		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ):
			// Check CSRF
			$token = filter_input( INPUT_POST, '_csrf', FILTER_SANITIZE_SPECIAL_CHARS );
            if (!$token || !$csrf_token || $token !== $csrf_token) {
                get_footer();
                exit;
			}

			// Sanitise and validate data
			$data         = validate_sighting_data( $_POST );
			$error_fields = get_missing_data_fields( $data );

			// TODO: Captcha

			// Process data if valid
			if ( empty( $error_fields ) ) {
				if ( upload_to_sheets( $data ) ) {
					$show_form    = false;
					$show_success = true;
				}
			}
		endif; ?>



		<?php if ( $show_sucess ) : ?>
            <div class="alert alert-success" role="alert">
                Thank you, your sighting has been submitted successfully.
            </div>
		<?php endif; ?>

		<?php if ( $show_form ) : ?>

            <form action="" method="POST" class="mb-4">
                <input type="hidden" name="_csrf" value="<?= $_SESSION['sighting_csrf_token']; ?>">

				<?php if ( ! empty( $error_fields ) ): ?>
                    <div class="alert alert-danger" role="alert">
                        There was a problem submitting your sighting. Please check the following
                        fields: <?= implode( ', ', $error_fields ) ?>
                    </div>
				<?php endif; ?>
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


                <button type="submit" class="btn btn-primary">Submit</button>

            </form>
            <p>If you are having difficulties submitting your sighting, please use the generic <a href="/contact">Contact</a>
                form.</p>
		<?php endif; ?>
    </div>

<?php
get_footer();