<?php
/**
	Wraps content in a flexbox if content has [col ***]
*/
global $post;
setup_postdata($post);


$html = '<div class="main">';

if (! is_front_page()) $html .= '<div class="content-title"><h1 class="content-title-text">'.get_the_title().'</h1></div>';

$content = get_the_content();

// get all before first [col]
// get all from first to last
// get all after last col

$first = strpos($content, '[col');

if ($first !== false) {
	$last = strrpos($content, '[/col]')+6;

	$content_before = substr($content, 0, $first);
	$content_col = substr($content, $first, $last - strlen($content_before));
	$content_after = substr($content, $last);


	if ($content_before) $html .= '<div class="content">'.apply_filters('the_content', do_shortcode($content_before)).'</div>';
	if ($content_col) $html .= '<div class="content content-3">'.apply_filters('the_content', do_shortcode($content_col)).'</div>';
	if ($content_after) $html .= '<div class="content">'.apply_filters('the_content', do_shortcode($content_after)).'</div>';
}
else
	$html .= '<div class="content">'.apply_filters('the_content', $content).'</div>';

$html .= '</div>';

get_header(); 

echo $html;

get_footer();
wp_reset_postdata();
