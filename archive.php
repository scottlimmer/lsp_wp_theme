<?php get_header() ?>

<?php include 'partial/sidebar/news.php'; ?>

<?php
global $year;
?>
    <div class="content news-listing">
        <h1><?= $year ?> Archives</h1>
		<?php
		$post_args = [
			'date_query'  => [
				'year' => $year
			],
			'numberposts' => - 1
		];

		$current_posts = get_posts( $post_args );
		if ( count( $current_posts ) ):

			foreach ( $current_posts as $post ) :
				setup_postdata( $post );
				get_template_part( 'partial/news-item' );
			endforeach;
		else: ?>

            <p>No posts found. :(</p>

		<?php endif; ?>
    </div>
<?php get_footer(); ?>