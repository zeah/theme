<?php 
/**
 Template Name: Ignore flexbox
 Template Post Type: article, page
*/

get_header();


global $post;
setup_postdata( $post );

$postid = get_the_ID(); // to be used in footer.php
$html = '<div class="main">';
$html .= '<div class="content-title"><h1>'.get_the_title().'</h1></div>';

$content = get_the_content();
// removing flexbox shortcode
$content = preg_replace('/\[\/*col.*?\]/', '', $content);

$html .= '<div class="content">'.apply_filters('the_content', $content).'</div>';

$html .= '</div>';

wp_reset_postdata();
echo $html;

get_footer();


