<?php
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 20/10/2018
 * Time: 11:57 AM
 */

// Static page
$meta = get_post_meta( get_the_id() );

if ( array_key_exists( 'redirect', $meta ) && $meta['redirect'][0] ) {
	header( 'Location: ' . $meta['redirect'][0] );
	exit;
}

get_header();
?>

<?php
include 'partial/sidebar/page.php';
?>


    <div class="content">
        <article>

			<?php
			if ( have_posts() ) :
				while ( have_posts() ) : the_post(); ?>
                    <h1><?php the_title() ?></h1>
					<?php
					the_content();
				endwhile;
			endif;
			?>
        </article>
    </div>
<?php
get_footer();