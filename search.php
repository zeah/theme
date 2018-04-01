<?php
$search = Emkk_search::get_instance();
$found_posts = false;

get_header();

echo '<div class="main"><div class="content">';
echo '<ul class="emtheme-search-ul">';

if (have_posts()) {
	$found_posts = true;
	while (have_posts()) {
		the_post();
	
		global $post;

		if ($post->post_type == 'emkort')
			echo $search->emkort($post);
	}
}

rewind_posts();

if (have_posts()) {
	while (have_posts()) {
		the_post();

		global $post;

		if ($post->post_type == 'page' || $post->post_type == 'article')
			echo $search->page($post);
	}
}

if ( ! $found_posts)
	echo '<li class="emtheme-search-li" style="background: inherit">Ingen Søkeresultat Funnet</li>';

echo '</ul>';
echo '</div></div>';

get_footer();


final class Emkk_search {
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {

	}

	public function emkort($post) {
		do_action('emkort_shortcode', $post->ID);
	}

	public function page($post) {
		$meta = get_post_meta($post->ID, 'emtext');
		$title = get_post_meta($post->ID, 'emtitle');

		if (isset($title[0]) && $title[0] != '')	$title = $title[0];
		else 										$title = $post->post_title;

		$html = '<li class="emtheme-search-li"><a class="emtheme-search-link" href="'.get_permalink($post).'">';
		$html .= '<h2 class="emtheme-search-title">'.$title.'</h2></a>';
		$html .= '<span class="emtheme-search-url">'.get_permalink($post).'</span>';

		if (isset($meta[0]) && $meta[0] != '') $html .= '<p class="emtheme-search-description">'.$meta[0].'</p>';

		$html .= '</li>';
		return $html;
	}
}