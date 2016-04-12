<?php

/**
 * Build breadcrumbs
 *
 * 1. Get page ID
 * 2. Read its' title (optionally also url)
 * 3. Get its' parent
 * 4. If parent exists - return to second point
 * 5. 
 */

function generate_breadcrumbs() {
	// Initialize output HTML variable
	$items = array();

	// Get ID of current page
	$id = get_the_id();

	// 
	// Get title only and mark item as "active" (item without link)
	array_push($items, get_item_list($id, true));

	// Ask Wordpress, if current post has any parent
	$parent_id = wp_get_post_parent_id($id);

	// If it has parent - start looking for its' name
	if ($parent_id) {
		$continue_job = true;

		while ($continue_job) {
			// Add to variable title of parent
			array_push($items, get_item_list($parent_id, false));

			// Ask Wordpress, if current post has any parent
			$parent_id = wp_get_post_parent_id($parent_id);	

			// If parent not exists - quit the job
			if (!$parent_id) $continue_job = false;
		}
	}

	// If there aren't any parent - just reverse array and render them in proper order
	$items = array_reverse($items);

	echo '<ol class="breadcrumb">';
	foreach ($items as $item) echo $item;
	echo '</ol>';
}

/**
 * Helper for generate_creadcrumbs
 * return item with or without link ($active is Boolean)
 */
function get_item_list($id, $active = false) {
	if ($active == true) {
		return '<li class="active">' . get_the_title($id) . '</li>';
	}
	return '<li><a href="' . get_permalink($id) . '">' . get_the_title($id) . '</a>&nbsp;-&nbsp;</li>';
}