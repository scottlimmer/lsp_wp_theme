<?php
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 20/10/2018
 * Time: 11:57 AM
 */

// Single post
get_header();
include 'partial/sidebar/news.php';
?>


    <div class="content">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) : the_post(); ?>
                <article class="news-item">
                    <h1><?php the_title(); ?></h1>

					<?php get_template_part( 'partial/news-item-date' ); ?>
					<?php
					the_content(); ?>
                </article>
			<?php

			endwhile;

			$prev_post = get_adjacent_post( false, '', true );
			$next_post = get_adjacent_post( false, '', false );
			if ( $prev_post || $next_post ) : ?>
                <nav class="post-navigation">
                    <div class="prev-post">
						<?php if ( $prev_post ) : ?>
                            <span>&larr;</span>
                            <a
                                    href="<?php echo get_permalink( $prev_post->ID ); ?>"><?php echo esc_html( $prev_post->post_title ); ?></a>

						<?php endif; ?>
                    </div>
                    <div class="next-post">
                    <?php if ( $next_post ) : ?>

                            <a
                                    href="<?php echo get_permalink( $next_post->ID ); ?>"><?php echo esc_html( $next_post->post_title ); ?></a>
                        <span>&rarr;</span>


                    <?php endif; ?>
                    </div>
                </nav>
			<?php endif;

		endif;
		?>
    </div>

<?php

get_footer();