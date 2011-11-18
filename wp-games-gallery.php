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

	// this defines the default function parameter values
	$default_atts = array( 'numberposts' => 30, 'post_type' => 'page', 'order'=> 'ASC', 'orderby' => 'title');

	// this merges the default parameters with the current parameters
	$query_args = shortcode_atts($default_atts, $atts);

	// now, this gets all the posts matching the criteria, the thing is, we are filtering when iterating too, so 
	// we have to see how to use numberposts argumets right in order to show the correct number of results
	$postslist = get_posts( $query_args );

	$result = "";

	foreach ($postslist as $post) :  setup_postdata($post); 

		// avoid current page
		if ($post->ID == $page_id)  {
			continue;
		}

		$post_ancestors = get_ancestors($post->ID, 'page' );

		// we want only child pages
		if (!in_array($page_id, $post_ancestors)) {
			continue;
		}

		if (count($post_ancestors) != 1) {
			continue;
		}

		$game_title = $post->post_title;
		$game_link = get_permalink($post->ID);

		$result .= "<div class=\"game\">";

		$result .= "<div class=\"title\">";
		$result .= "<a href=\"".$game_link."\">".$game_title."</a>";
		$result .= "</div>";

		$post_custom_fields = get_post_custom($post->ID);
		$game_screenshots = $post_custom_fields['screenshot'];

		if ($game_screenshots) {
			foreach ($game_screenshots as $game_screenshot) :
				$result .= "<div class=\"image\">";
				$result .= "<a href=\"".$game_link."\">";
				$result .= "<img src=\"".$game_screenshot."\">";
				$result .= "</img>";
				$result .= "</a>";
				$result .= "</div>";
			endforeach;
		}

		// A small description to show in the gallery.
		$game_descriptions = $post_custom_fields['description'];
	
		if ($game_descriptions) {
			$game_description = $game_descriptions[0];
			$result .= "<div class=\"description\">";
			$result .= $game_description;
			$result .= "</div>";
		}

		$result .= "</div>";

	endforeach; 	

	return $result;
}

add_shortcode('create_games_gallery', 'create_games_gallery');

?>
