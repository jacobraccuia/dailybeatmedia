<?php
/**
 * The Template for displaying all single posts.
 *
 */

get_header();

global $post;
if($post->ID == 38998) {
	include_once('single-simple.php');
} else {
	include_once('single-default.php');
}


get_footer(); ?>