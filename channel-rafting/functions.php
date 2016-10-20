<?php

// add all appropriate styles and scripts
add_action('wp_enqueue_scripts', 'channel_child_enqueue_scripts');
function channel_child_enqueue_scripts() {
	wp_enqueue_style('children_css', get_template_directory_uri() . '/style.css');
}

?>