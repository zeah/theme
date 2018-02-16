<?php 
/*
Template Name: alternative front-page

*/
get_header('alt');

global $post;
setup_postdata($post);

$html = '<div class="content-title content-title-alt"><h1>'.get_the_title().'</h1></div>';


$html .= '<div class="main main-alt">';

// $html .= '<div class="content-title"><h1>'.get_the_title().'</h1></div>';

$content = get_the_content();

$html .= '<div class="content content-alt';
if (!(strpos($content, '[col') === false))
	$html .= ' content-3';
$html .= '">'.apply_filters('the_content', do_shortcode(get_the_content())).'</div>';

$html .= '</div>';

echo $html;

get_footer();
wp_reset_postdata();
