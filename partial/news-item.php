<?php
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 1/01/2019
 * Time: 11:55 AM
 */
?>
<article class="news-item">
    <?php include 'partial/news-item-date.php'; ?>
    <h1>
        <?php the_title() ?>
    </h1>
    <?php
    the_content(); ?>
</article>
