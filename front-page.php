<?php
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 20/10/2018
 * Time: 11:57 AM
 */

/**
 * This is the static home-page template
 */
get_header();

?>

    <article>

        <div class="text-tile intro-text">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) : the_post(); ?>
					<?php
					the_content();
				endwhile;
			endif;
			?>
        </div>

        <div class="text-tile survey-dates">
            <i class="fa-solid fa-calendar-days fa-2x"></i> <?php
			echo get_field( 'survey_dates' ) ?: 'No survey dates have been scheduled for this season.';
			?>
        </div>


        <div class="text-tile news">
            <h2>News</h2>
            <ul>
				<?php

				$args = [
					'posts_per_page' => 3,
					'post_type'      => 'post',
					'orderby'        => 'date',
					'order'          => 'DESC',
				];

				$query = new WP_Query( $args );
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) :
						$query->the_post();

						printf(
							'<li><a href="%s">%s</a></li>',
							get_the_permalink(),
							get_the_title()
						);

					endwhile;
				}
				wp_reset_postdata();
				?>

            </ul>
            <a class="more-news" href="<?php print_r( get_permalink( get_news_page()->ID ) ) ?>">More news
                &rarr;</i></a>

        </div>
    </article>

    <aside>
		<?php
		$page = get_page_by_slug( 'report-a-sighting' );
		if ( $page ): ?>

			<?php if ( has_post_thumbnail( $page ) ) :
				$image_url = wp_get_attachment_image_url( get_post_thumbnail_id( $page ), 'tile-thumbnail' );
			endif; ?>

            <a
                    class="tile"
                    href="<?php echo get_permalink( $page ); ?>"
                    style="<?= $image_url ? "background-image: url({$image_url}); " : '' ?>"
            >
                <span><?= $page->post_title ?></span>
            </a>
		<?php
		endif;
		?>

        <a
                class="tile support"
                href="https://www.supportlathamssnipe.au/"
                style="background-image: url(<?= get_template_directory_uri() . "/assets/images/Lathams+Snipe-adobe-stock.jpg" ?>)"
                target="_blank"
        >
            <span>Support the project</span>
        </a>

        <a
                class="tile facebook"
                href="https://www.facebook.com/groups/345220814765069/"
                target="_blank"
        >
            <i class="fa-brands fa-facebook fa-3x"></i> Latham’s Snipe Project on Facebook
        </a>


        <a class="tile"
           href="/gallery"
           title="&copy; Photo by Mark Lethlean"
           style="background-image: url(<?= get_template_directory_uri() . "/assets/images/MarkLeathlean-Lathams-snipe-thumb.jpg" ?>)"
        >
            <span>Gallery</span>
        </a>

        <a class="tile documentary"
           href="https://youtu.be/uKouzoQmspY"
           target="_blank">
            <img src="<?= get_template_directory_uri() . "/assets/images/snipe_poster.jpg" ?>" alt="SNIPE: The Latham's Snipe Project - A Film Story">
        </a>

    </aside>
<?php
get_footer();