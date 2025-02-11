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

                <h1><?=get_news_page()->post_title?></h1>

                <?php
                $post_args = [
                    'date_query' => [
                        'year' => date('Y')
                    ]
                ];

                $current_posts = get_posts($post_args);

                if (count($current_posts)):
                    foreach ($current_posts as $post) :
                        setup_postdata($post);
                        get_template_part('partial/news-item');
                    endforeach;
                else:
                    if (have_posts()) :
                        while (have_posts()) :
                            the_post();
                            get_template_part('partial/news-item');
                        endwhile;
                    endif;
                endif;
                ?>
            </div>

<?php

get_footer();