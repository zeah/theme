<?php
/*

*/
get_header(); 


while (have_posts()) {
	the_post();

$postid = get_the_ID();

	$html = '<div class="main">';

	$html .= '<div class="content-title"><h1>'.get_the_title().'</h1></div>';

	// $html .= '<div class="content content-3">'.do_shortcode(get_the_content()).'</div>';
	$html .= '<div class="content content-3">'.apply_filters('the_content', do_shortcode(get_the_content())).'</div>';

	$html .= '</div>';

	$html = preg_replace('/\[caption.*alignright.*?\]/', '<span class="right-image editor-image">', $html);
	$html = preg_replace('/\[caption.*alignleft.*?\]/', '<span class="left-image editor-image">', $html);
	$html = preg_replace('/\[caption.*aligncenter.*?\]/', '<span class="center-image editor-image">', $html);
	$html = preg_replace('/\[\/caption\]/', '</span>', $html);

	// $html = preg_replace('/\n/', '<br>', $html);

	// $html = preg_replace('/(?=<ul>.*)\n(.*?=<\/ul>)/', '', $html);
	// $ex = explode('</ul>', $html);
	// foreach($ex as &$v)
		// $v = str_replace('<br>', '', substr($v, strpos($v, 'ul>')));
		// $v = preg_replace('/(?<=ul)<br>/', '', $v);
	// preg_match('/<ul>.*?<\/ul>/', $html, $m);
	echo $html;
	// echo '<xmp>'.print_r($ex, true).'</xmp>';
	// echo '<xmp>'.print_r($m, true).'</xmp>';
}


get_footer();