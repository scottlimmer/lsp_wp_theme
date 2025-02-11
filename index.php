<?php get_header() ?>

    <div class="sidebar">
    </div>

    <div class="content news-listing">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article>
                <h3><?php the_title(); ?></h3>
				<?php include 'partial/news-item-date.php'; ?>
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
				<?php edit_post_link(); ?>
            </article>

		<?php endwhile; ?>

			<?php
			if ( get_next_posts_link() ) {
				next_posts_link();
			}
			?>
			<?php
			if ( get_previous_posts_link() ) {
				previous_posts_link();
			}
			?>

		<?php else: ?>

            <p>No posts found. :(</p>

		<?php endif; ?>
    </div>
<?php get_footer(); ?>