<?php
/**
	Wraps content in a flexbox if content has [col ***]
*/
global $post;
setup_postdata($post);

get_header(); 

$html = '<div class="main">';

if (! is_front_page()) $html .= '<div class="content-title"><h1 class="content-title-text">'.get_the_title().'</h1></div>';

$content = get_the_content();

// get all before first [col]
// get all from first to last
// get all after last col

$first = strpos($content, '[col');

if ($first) {
	$last = strrpos($content, '[/col]')+6;

	$content_before = substr($content, 0, $first);
	$content_col = substr($content, $first, $last - strlen($content_before));
	$content_after = substr($content, $last);

// wp_die(print_r($content_before.'#####'.$content_col.'######'.$content_after, true));


	if ($content_before) $html .= '<div class="content">'.apply_filters('the_content', $content_before).'</div>';
	if ($content_col) $html .= '<div class="content content-3">'.apply_filters('the_content', $content_col).'</div>';
	if ($content_after) $html .= '<div class="content">'.apply_filters('the_content', $content_after).'</div>';
}
else
	$html .= '<div class="content">'.apply_filters('the_content', $content).'</div>';
// $html .= '<div class="content';
// if (!(strpos($content, '[col') === false)) $html .= ' content-3';
// $html .= '">'.apply_filters('the_content', get_the_content()).'</div>';
// $html .= '">'.apply_filters('the_content', do_shortcode(get_the_content())).'</div>';

// $html .= '<div>hi</div>';

// $html .= do_shortcode('lala [emkort]');

$html .= '</div>';

echo $html;

get_footer();
wp_reset_postdata();
