<?php
/**
	Wraps content in a flexbox if content has [col ***]
*/
get_header(); 

global $post;
setup_postdata($post);

$html = '<div class="main">';

if ( ! is_front_page())
	$html .= '<div class="content-title"><h1>'.get_the_title().'</h1></div>';

$content = get_the_content();

$html .= '<div class="content';
if (!(strpos($content, '[col') === false))
	$html .= ' content-3';
$html .= '">'.apply_filters('the_content', get_the_content()).'</div>';

wp_reset_postdata();

$html .= '</div>';

echo $html;

get_footer();