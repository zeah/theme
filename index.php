<?php
/*

*/
get_header(); 


while (have_posts()) {
	the_post();

	$postid = get_the_ID(); // to be used in footer.php

	$html = '<div class="sitename">'.preg_replace('/.*\/\//', '', get_site_url()).'</div>';

	$html .= '<div class="main">';
	$html .= '<div class="content-title"><h1>'.get_the_title().'</h1></div>';

	$content = get_the_content();

	$html .= '<div class="content';
	if (!(strpos($content, '[col') === false))
		$html .= ' content-3';
	$html .= '">'.apply_filters('the_content', do_shortcode(get_the_content())).'</div>';
	// $html .= '">'.apply_filters('the_content', do_shortcode(get_the_content())).'</div>';

	$html .= '</div>';

	echo $html;
}


get_footer();