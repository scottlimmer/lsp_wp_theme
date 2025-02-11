<?php
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 20/10/2018
 * Time: 11:57 AM
 */

// Single post
get_header();
?>

    <div class="row">
        <?php //get_sidebar(); ?>
        <div class="col-xs-12 col-md-9 main">
            <?php //get_breadcrumb(); ?>

            <div class="content">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post(); ?>
                        <h1><?php the_title() ?></h1>
                        <?php
                        the_content();
                    endwhile;
                endif;
                ?>
            </div>
        </div>
    </div>
<?php

get_footer();