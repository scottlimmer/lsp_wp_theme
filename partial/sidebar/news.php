<?php
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 31/12/2018
 * Time: 11:29 AM
 */
?>
<div class="sidebar">

    <div class="menu">
        <ul>
			<?php
			global $wpdb;
			global $year;

			if ( wp_cache_get( 'year_list' ) === false ) {

				$year_list = $wpdb->get_results(
					<<<SQL
                            SELECT 
                                DISTINCT year(post_date) as "year" 
                            FROM wp_posts 
                            WHERE post_type = 'post' AND post_status = 'publish' 
                            ORDER BY year(post_date) DESC
                            SQL
				);

				wp_cache_add( 'year_list', $year_list );
			}
			foreach (
				wp_cache_get( 'year_list' )
				as $row
			) {

				printf( '<li class="%s"><a href="%s">%s</a></li>', $year == $row->year ? 'current_page_item' : '', get_home_url() . '/' . $row->year, $row->year );
			}
			?>
        </ul>
    </div>

</div>