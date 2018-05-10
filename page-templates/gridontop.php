<?php
/**
 Template Name: Grid on top
 Template Post Type: article, page
*/

/**
	Wraps content in a flexbox if content has [col ***]
*/

global $post;
setup_postdata($post);

get_header();

echo Emtheme_fp_template::get($post); 

final class Emtheme_fp_template {

	static function get($post) {
		$html = '<div class="main-bg">';

		$content = $post->content;

		$widget = '';
		if (is_active_sidebar('frontpage-grid-01')) {
			ob_start();
			dynamic_sidebar('frontpage-grid-01');
			$widget = ob_get_clean();
		}

		$cpt = '';
		if (is_active_sidebar('frontpage-grid-02')) {
			ob_start();
			dynamic_sidebar('frontpage-grid-02');
			$cpt = ob_get_clean();
		}

		$list = '';
		if (is_active_sidebar('frontpage-grid-03')) {
			ob_start();
			dynamic_sidebar('frontpage-grid-03');
			$list = ob_get_clean();
		}

		$sidelist = '';
		if (is_active_sidebar('frontpage-grid-04')) {
			ob_start();
			dynamic_sidebar('frontpage-grid-04');
			$sidelist = ob_get_clean();
		}


		$html .= '<div class="frontpage-grid">';

		// $html .= '<div class="frontpage-widget">hi</div>';
		$html .= '<div class="frontpage-widget">'.$widget.'</div>';

		// $html .= '<div class="frontpage-cpt">ya</div>';
		$html .= '<div class="frontpage-cpt">'.$cpt.'</div>';

		// $html .= '<div class="frontpage-list">bal</div>';
		$html .= '<div class="frontpage-list">'.$list.'</div>';

		// $html .= '<div class="frontpage-sidelist">dfdf</div>';
		$html .= '<div class="frontpage-sidelist">'.$sidelist.'</div>';

		$html .= '</div>';

		// if ($content) {
		// 	$html .= '<div class="content';
		// 	if (!(strpos($content, '[col') === false)) $html .= ' content-3';
		// 	$html .= '">'.apply_filters('the_content', $content).'</div>';
		// 	$html .= '</div>';
		// }

		return $html;
	}
}
get_footer();
wp_reset_postdata();
