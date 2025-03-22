<?php

class Mobile_Nav_Walker extends Walker_Nav_Menu {

	public function end_el( &$output, $data_object, $depth = 0, $args = array() ): void {
		global $year;

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";

		if ( $this->active_menu_branch( $data_object ) ) {
			$sub_menu_output = render_sub_menu( get_post( $data_object->object_id ) );
			if ( ! empty( $sub_menu_output ) ) {
				$output .= "</ul>{$n}";
				$output .= "<ul class='nav navbar-nav sub-menu'>{$n}";
				$output .= $sub_menu_output;
				$output .= "</ul>{$n}";
				$output .= "<ul class='nav navbar-nav'>{$n}";
			}
		}

		if ( $this->is_news_listing_page() && $data_object->object_id == get_option( 'page_for_posts' ) ) {
			$output .= "</ul>{$n}";
			$output .= "<ul class='nav navbar-nav sub-menu news-sub-menu'>{$n}";
			foreach ( get_archive_years() as $row ) {
				$output .= sprintf( '<li class="%s"><a href="%s">%s</a></li>', $year == $row->year ? 'current_page_item' : '', get_home_url() . '/' . $row->year, $row->year );
			}
			$output .= "</ul>{$n}";
			$output .= "<ul class='nav navbar-nav '>{$n}";
		}
	}

	/**
	 * Determines if the current menu item or any of its ancestors is active.
	 *
	 * This function checks whether a given menu data object corresponds to the current page,
	 * or if it is an ancestor of the current page. It helps in managing active states
	 *
	 * @param WP_Post $data_object The post object representing a menu item.
	 *
	 * @return bool True if the menu item or its ancestor matches the current page; false otherwise.
	 */
	private function active_menu_branch( WP_Post $data_object ): bool {
		return $data_object->object_id == get_the_ID() or in_array( $data_object->object_id, get_post_ancestors( get_the_ID() ) );
	}


	function is_news_listing_page(): bool {
		return ( is_archive() || is_singular( 'post' ) || is_home() );
	}

}


function render_sub_menu( ?WP_Post $parent_post = null ): string {

	if ( empty( $parent_post ) ) {
		$parent_post = get_top_ancestor( $parent_post );
	}

	if ( ! isset( $child_pages ) ) {
		$child_pages = get_child_pages( $parent_post );
	}

	$output = '';

	if ( $child_pages->have_posts() ) {

		foreach ( $child_pages->posts as $page ) {
			$classes     = array();

			if ( $page->ID === get_the_ID() ) {
				$classes[] = 'current_page_item';
			}

			$output .= sprintf(
				'<li class="%s"><a href="%s">%s</a>',
				implode( ' ', $classes ),
				get_the_permalink( $page->ID ),
				$page->post_title
			);

		}
	}

	return $output;
}