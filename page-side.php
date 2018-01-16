<?php 
/**
 Template Name: Forced Single Column
*/

get_header();


while (have_posts()) {
	the_post();

	$postid = get_the_ID(); // to be used in footer.php
	// $html = '<div class="sitename">'.preg_replace('/.*\/\//', '', get_site_url()).'</div>';
	$html .= '<div class="main">';
	$html .= '<div class="content-title"><h1>'.get_the_title().'</h1></div>';

	$content = get_the_content();

	// removing flexbox shortcode
	$content = preg_replace('/\[\/*col.*?\]/', '', $content);

	$html .= '<div class="content">'.apply_filters('the_content', $content).'</div>';

	$html .= '</div>';

	echo $html;
}

get_footer();


