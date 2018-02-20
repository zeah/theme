<?php

global $post;
$meta = get_post_meta($post->ID, 'emredirect');
if (isset($meta[0])) {
	$url = esc_url($meta[0]);

	if ($url) {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: $url");
		exit();
	}
}

$logger = EmoLogger::get_instance();
$logger->welcome_user();

echo '<!DOCTYPE html><html lang="no"><head>';
wp_head();
echo '<meta name="viewport" content="width=device-width, initial-scale=1"></head><body>';