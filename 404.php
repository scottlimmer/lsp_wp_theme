<?php
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 20/10/2018
 * Time: 11:57 AM
 */

get_header();
?>

    <div class="sidebar">
    </div>


    <div class="content">
        <h1>Not found</h1>
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) : the_post(); ?>
                <h1><?php the_title() ?></h1>
				<?php
				the_content();
			endwhile;
		endif;
		?>
    </div>

<?php

get_footer();