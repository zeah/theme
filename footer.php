<?php 
global $postid;
// echo $postid;
$meta = get_post_meta($postid, 'emstrucdata');
// echo print_r($meta, true);
// echo $post->ID;
if (isset($meta[0]))
	echo '<script type="application/ld+json">'.json_encode(json_decode($meta[0])).'</script>';
else
	echo 'bleh';
wp_footer(); ?>
</body></html>