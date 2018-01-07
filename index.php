<?php

get_header(); 

$html = '<div class="main">';

while (have_posts()) {
	the_post();

	$html .= '<div class="content-title">'.get_the_title().'</div>';


	$content = get_the_content();
	$content = preg_replace('/\[caption.*alignright.*?\]/', '<span class="right-image editor-image">', $content);
	$content = preg_replace('/\[caption.*alignleft.*?\]/', '<span class="left-image editor-image">', $content);
	$content = preg_replace('/\[caption.*aligncenter.*?\]/', '<span class="center-image editor-image">', $content);
	$content = preg_replace('/\[\/caption\]/', '</span>', $content);

	$html .= '<div class="content">'.wpautop($content).'</div>';

	// apply_filters('the_content', the_content());
}

$html .= '</div>';

echo apply_filters('the_content', $html);

get_footer();