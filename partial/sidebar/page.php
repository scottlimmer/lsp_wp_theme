<?php
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 31/12/2018
 * Time: 11:29 AM
 */

?>
<?php

if ( is_single() ) {
	# FIXME
	$parent_post = get_page_by_title( 'News & Events' );
}

if ( ! isset( $parent_post ) ) {
	$parent_post = get_top_ancestor();
}

if ( ! isset( $child_pages ) ) {
	$child_pages = get_child_pages( $parent_post );
}


// only show submenu if top level ancestor has children
?>
<div class="sidebar">
<?php
if ( $child_pages->have_posts() ) {
	?>
    <div class="menu">
        <ul>
			<?php

//			$query = new WP_Query( [
//				'category_name' => 'fieldwork',
//				'post_type'     => 'page'
//			] );
//
//			$exclude = implode( ',', array_column( $query->posts, 'ID' ) );
//			wp_reset_postdata();
//
//			$pages = new WP_Query( [
//				'child_of'    => $parent_post->ID,
//				'exclude'     => $exclude,
//				'sort_column' => 'menu_order'
//			] );
//
//
//			$pages = get_pages( [
//				'child_of'    => $parent_post->ID,
//				'exclude'     => $exclude,
//				'sort_column' => 'menu_order'
//			] );

			$child_open = false;
			foreach ( $child_pages->posts as $page ) {
				$classes     = array();
				$target      = '';
				$previous_id = $previous_id ?? 0;
				$parent_id   = $parent_id ?? 0;
				$title       = $page->post_title;

				if ( $page->ID === get_the_ID() ) {
					$classes[] = 'current_page_item';
				}
				if ( ! $child_open && $previous_id == $page->post_parent ) {
					printf( '<ul class="children">' );
					$child_open = true;
					$parent_id  = $previous_id;
				}

				if ( $child_open && $parent_id != $page->post_parent ) {
					printf( '</li></ul>' );
					$child_open = false;
				} else {
					printf( '</li>' );
				}

				$page_meta = get_post_meta( $page->ID );
				if ( array_key_exists( 'redirect', $page_meta ) && stripos( $page_meta['redirect'][0], get_site_url() ) === false ) {
					$target    = '_blank';
					$classes[] = 'external';
					$title     = sprintf( "%s %s", $title, '<i class="fas fa-external-link-alt"></i>' );
				}


				printf( '<li class="%s"><a href="%s" target="%s">%s</a>', implode( ' ', $classes ), get_the_permalink( $page->ID ), $target, $title );


				$previous_id = $page->ID;

			}

			?>
        </ul>
    </div>
	<?php
}
?>
</div>
