<?php

get_header(); 

$content = '';

while (have_posts()) {
	the_post();

	$content = get_the_content();
}

echo '<div class="content">'.$content.'</div>';

get_footer();