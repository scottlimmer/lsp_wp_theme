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
			global $year;

			foreach (
				get_archive_years()
				as $row
			) {

				printf(
					'<li class="%s"><a href="%s">%s</a></li>',
					$year == $row->year ? 'current_page_item' : '',
					get_home_url() . '/' . $row->year,
					$row->year
				);
			}
			?>
        </ul>
    </div>

</div>