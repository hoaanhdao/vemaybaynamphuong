<?php
/*
Template Name: vmbwsgetnews
*/
	
	ini_set('display_errors',1);
	$args = array(
		'posts_per_page' => 20,
		'paged' => 1
	);
	// The Query
	$the_query = new WP_Query( $args );
	$arr['new_posts'] = array();
	
	// The Loop
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			
			$arr['new_posts'][] = array(
				'title' => get_the_title(),
				'link' => get_permalink(),
				'image' => getImageThumb($post->ID)
			);
			
		}
		
		
		/*echo '<pre>';
		print_r($arr);
		echo '</pre>';*/
		
		
		header('Content-type: application/json');
		echo html_entity_decode(json_encode($arr), ENT_QUOTES, 'UTF-8');
		wp_reset_postdata();
	} else {
		// no posts found
		header('Content-type: text/plain');
		echo 0;
	}
?>