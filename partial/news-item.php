<?php
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 1/01/2019
 * Time: 11:55 AM
 */
?>
<article class="news-item">
    <h2>
        <?php the_title() ?>
    </h2>
    <?php get_template_part('partial/news-item-date'); ?>
    <?php
    the_content(); ?>
</article>
