<?php
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 20/10/2018
 * Time: 11:57 AM
 */

get_header();
/**
 * This is the post listing template (News & Events)
 */
?>
<?php
include 'partial/sidebar/news.php';
?>

    <div class="content news-listing">

        <h1><?= get_news_page()->post_title ?></h1>


		<?php
		$post_args = [
			'numberposts' => 5
		];

		$current_posts = get_posts( $post_args );


		foreach ( $current_posts as $post ) :
			setup_postdata( $post );
			get_template_part( 'partial/news-item' );
		endforeach;

		?>
    </div>

<?php

get_footer();