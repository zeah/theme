<?php
/**
	Wraps content in a flexbox if content has [col ***]
*/
global $post;
setup_postdata($post);

get_header(); 

$html = '<div class="main" itemscope itemtype="http://schema.org/Article">';

if (! is_front_page()) $html .= '<div class="content-title"><h1 class="content-title-text" itemprop="name">'.get_the_title().'</h1></div>';

$content = get_the_content();

$html .= '<div itemprop="text" class="content';

if (!(strpos($content, '[col') === false)) $html .= ' content-3';

$html .= '">'.apply_filters('the_content', do_shortcode(get_the_content())).'</div>';


$html .= '</div>';

echo $html;

get_footer();
wp_reset_postdata();
