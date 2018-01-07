<?php 
/**
	Template Name: With left Side Bar

*/

get_header();


while (have_posts()) {
	the_post();

	$html = '<div class="main">';

	$html .= '<div class="content-title"><h1>'.get_the_title().'</h1></div>';

	$html .= '<div class="content content-3">'.wpautop(get_the_content()).'</div>';

	$html .= '</div>';

	$html = preg_replace('/\[caption.*alignright.*?\]/', '<span class="right-image editor-image">', $html);
	$html = preg_replace('/\[caption.*alignleft.*?\]/', '<span class="left-image editor-image">', $html);
	$html = preg_replace('/\[caption.*aligncenter.*?\]/', '<span class="center-image editor-image">', $html);
	$html = preg_replace('/\[\/caption\]/', '</span>', $html);

	echo $html;
	// echo '<div class="content content-3"><span class="content-title">'.get_the_title().'</span>'.get_the_content().'</div>';
}

get_footer();


