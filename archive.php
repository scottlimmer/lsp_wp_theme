<?php get_header() ?>

<?php include 'partial/sidebar/news.php'; ?>

<?php
global $year;
?>
    <div class="content news-listing">
        <h1><?=$year?> Archives</h1>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'partial/news-item' ); ?>

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