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
//            TODO: Add selected style
			wp_get_archives( [
				'type'   => 'yearly',
				'format' => 'custom',
				'before' => '<li>',
				'after'  => '</li>'
			] );
			?>
        </ul>
    </div>

</div>