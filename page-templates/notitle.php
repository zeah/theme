<?php 
/**
 Template Name: Dont show page title
 Template Post Type: article, page
*/

get_header();


global $post;
setup_postdata( $post );

$postid = get_the_ID(); // to be used in footer.php
$html = '<div class="main">';

$content = get_the_content();
// removing flexbox shortcode
$content = preg_replace('/\[\/*col.*?\]/', '', $content);

$html .= '<div class="content">'.apply_filters('the_content', $content).'</div>';

$html .= '</div>';

echo $html;

get_footer();
wp_reset_postdata();


