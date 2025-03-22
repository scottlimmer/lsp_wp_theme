<?php
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 1/01/2019
 * Time: 11:55 AM
 */
?>
<article class="news-item">
    <header>
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><i class="fa-solid fa-link"></i></a>
        <h2><?php the_title(); ?></h2>
    </header>

	<?php get_template_part( 'partial/news-item-date' ); ?>
	<?php
	the_content(); ?>

</article>
