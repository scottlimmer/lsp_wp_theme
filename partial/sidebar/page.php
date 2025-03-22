<?php
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 31/12/2018
 * Time: 11:29 AM
 */


//
//if ( is_single() ) {
//
//	$query = new WP_Query(
//		array(
//			'post_type'              => 'page',
//			'title'                  => 'News & Events',
//			'post_status'            => 'all',
//			'posts_per_page'         => 1,
//			'no_found_rows'          => true,
//			'ignore_sticky_posts'    => true,
//			'update_post_term_cache' => false,
//			'update_post_meta_cache' => false,
//			'orderby'                => 'post_date ID',
//			'order'                  => 'ASC',
//		)
//	);
//
//	if ( ! empty( $query->post ) ) {
//		$page_got_by_title = $query->post;
//	} else {
//		$page_got_by_title = null;
//	}
//
//	return $page_got_by_title;
//}

if ( ! isset( $parent_post ) ) {
	$parent_post = get_top_ancestor();
}

if ( ! isset( $child_pages ) ) {
	$child_pages = get_child_pages( $parent_post );
}


// only show submenu if top level ancestor has children
?>
<div class="sidebar">
        <div class="menu">
            <ul>
	            <?php echo render_sub_menu() ?>
            </ul>

        </div>

</div>
