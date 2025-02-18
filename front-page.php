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

    <div class="intro">
        <div class="intro-text">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) : the_post(); ?>
					<?php
					the_content();
				endwhile;
			endif;
			?>
        </div>
        <div class="intro-side">
			<?php

			$child_pages = get_child_pages( get_the_ID() );

			if ( $child_pages->have_posts() ) {
				while ( $child_pages->have_posts() ) {
					$child_pages->the_post();
					?>
					<?php if ( has_post_thumbnail() ) :
						$image_url = wp_get_attachment_image_url( get_post_thumbnail_id(), 'tile-thumbnail' );
					endif; ?>

                    <a
                            class="tile"
                            href="<?php echo get_permalink(); ?>"
                            style="<?= $image_url ? "background-image: url({$image_url}); " : '' ?>"
                    >

                        <span><?php the_title(); ?></span>
                    </a>
					<?php
				}
			} else {
				echo 'No child pages found.';
			}

			?>

            <a
                    class="tile support"
                    href="https://www.supportlathamssnipe.au/"
                    style="background-image: url(<?= get_template_directory_uri() . "/assets/images/Lathams+Snipe-adobe-stock.jpg" ?>">
                <span>Support the project</span>
            </a>

            <a class=" tile facebook" href="https://www.facebook.com/groups/345220814765069/">
                <i class="fa-brands fa-facebook fa-3x"></i> Latham’s Snipe Project on Facebook
            </a>
        </div>
    </div>

    <div class="news">
        <h2>News</h2>
		<?php

		$args = [
			'posts_per_page' => 1,
			'post_type'      => 'post',
			'orderby'        => 'date',
			'order'          => 'DESC',
		];

		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) :
				$query->the_post();

				get_template_part( 'partial/news-item-compact' );

			endwhile;
		}
		wp_reset_postdata();
		?>
        <a class="more-news" href="<?php print_r( get_permalink( get_news_page()->ID ) ) ?>">More news &rarr;</i></a>
    </div>
    <div class="gallery-tile">
        <h2>Gallery</h2>
        <figure>
            <a href="/gallery">
                <img alt="Latham's Snipe" title="Latham's Snipe"
                     src="/wp-content/uploads/2025/01/MarkLeathlean-Lathams-SnipeBalnarring070119-37-Edit-Editweb.jpg">
            </a>
            <figcaption>&copy; Photo by Mark Lethlean</figcaption>
        </figure>
    </div>

<?php
get_footer();