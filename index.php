<?php
/**
	Wraps content in a flexbox if content has [col ***]
*/
global $post;
setup_postdata($post);

get_header(); 

$html = '<div class="main">';

if ( ! is_front_page())
	$html .= '<div class="content-title"><h1>'.get_the_title().'</h1></div>';

$content = get_the_content();

$html .= '<div class="content';
if (!(strpos($content, '[col') === false))
	$html .= ' content-3';
$html .= '">'.apply_filters('the_content', do_shortcode(get_the_content())).'</div>';


$html .= '</div>';

echo $html;

get_footer();
wp_reset_postdata();
