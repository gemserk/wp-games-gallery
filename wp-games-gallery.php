<?php

/*
Plugin Name: WP Games Gallery
Plugin URI: https://github.com/gemserk/wp-games-gallery
Description: List games in your wordpress site!
Version: 0.0.2-SNAPSHOT
Author: Gemserk
Author URI: http://blog.gemserk.com
*/

function create_games_gallery($atts, $content = null) {
	$page_id = get_the_ID();

	$query_args = array( 'numberposts' => 50, 'post_type' => 'page', 'order'=> 'ASC', 'orderby' => 'title');
	$postslist = get_posts( $query_args );

	$result = "";
	$result .= "<ul>";

	foreach ($postslist as $post) :  setup_postdata($post); 

		// avoid current page
		if ($post->ID == $page_id)  {
			continue;
		}

		$post_ancestors = get_ancestors($post->ID, 'page' );
		$post_link = get_permalink($post->ID);

		// we want only child pages
		if (!in_array($page_id, $post_ancestors)) {
			continue;
		}

		if (count($post_ancestors) != 1) {
			continue;
		}

		$result .= "<li>";
		$result .= "<a href=\"".$post_link."\">".$post->post_title."</a>";

		$post_custom_fields = get_post_custom($post->ID);
		$post_screenshots = $post_custom_fields['screenshot'];

		if ($post_screenshots) {
			foreach ($post_screenshots as $post_screenshot) :
				$result .= "<p>";
				$result .= "<a href=\"".$post_link."\">";
				$result .= "<img width=\"300px\" src=\"".$post_screenshot."\">";
				$result .= "</img>";
				$result .= "</a>";
				$result .= "</p>";
			endforeach;
		}

		$result .= "</li>";

	endforeach; 	

	$result .= "</ul>";

	return $result;
}

add_shortcode('create_games_gallery', 'create_games_gallery');

?>
