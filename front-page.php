<?php 
$weekend = false;
if ((date('D') == 'Fri' && date('G') > 17) || date('D') == 'Sat' || date('D') == 'Sun')
	$weekend = true;

if ($weekend) {
	global $post;
	$post = get_post(2214);
	setup_postdata($post);
	get_template_part('index');
	wp_reset_postdata();
}
else 
	get_template_part('index');