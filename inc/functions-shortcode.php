<?php 

final class EmThemeShortcode {
	public static function init() {
		add_shortcode('col', array('EmThemeShortcode', 'columns_callback'));
		// add_shortcode('articles', array('EmThemeShortcode', 'articles_callback'));
	}

	public static function columns_callback($atts, $content = null) {

		/* shortcode strips shortcode, even unrelated that is in content */
		$content = do_shortcode($content);

		foreach($atts as $key => $value) {
			if ($value == 'left')
				return '<div class="left">'.$content.'</div>';
			if ($value == 'center')
				return '<div class="center">'.$content.'</div>';
			if ($value == 'right') 
				return '<div class="right">'.$content.'</div>';
		}
		return '<div>'.$content.'</div>';
	}

	// public static function articles_callback($atts, $content = null) {
	// 	$args = [
	// 			'post_type' => 'post'
	// 		];
	// 	$query = new WP_Query($args);
		
	// 	$p = [];
	// 	if ($query->have_posts())
	// 		while ($query->have_posts()) {
	// 			$query->the_post();

	// 			$temp = [
	// 				'title' => get_the_title(),
	// 				'content' => get_the_content(),
	// 				'thumbnail' => get_the_post_thumbnail_url(),
	// 				'excerpt' => get_the_excerpt(),
	// 				'url' => get_permalink()
	// 			];
	// 			array_push($p, $temp);
	// 		}

	// 	wp_reset_postdata();

	// 	if (sizeof($p) == 0)
	// 		return '';

	// 	$html = '';
	// 	$html .= '<div class="em-articles-container"><ul class="em-articles-list">';

	// 	if (sizeof($p) > 0) {
	// 		$html .= '<li class="em-articles-listitem em-articles-first">';
	// 		$html .= '<a class="em-articles-link" href="'.$p[0]['url'].'">';
	// 		$html .= '<span class="em-articles-thumbnail-first" style="background-image: url(\''.$p[0]['thumbnail'].'\')"></span>';
	// 		$html .= '<span class="em-articles-title">'.$p[0]['title'].'</span>';
	// 		if (sizeof($p[0]['excerpt']) > 0)
	// 			$html .= '<span class="em-articles-excerpt">'.$p[0]['excerpt'].'</span>';
	// 		$html .= '</a></li>';
	// 		$html .= '<li class="em-articles-listitem em-articles-widget">widget here</li>';
	// 	}

	// 	for ($i = 1; $i < sizeof($p); $i++) {
	// 		$html .= '<li class="em-articles-listitem">';
	// 		$html .= '<a class="em-articles-link" href="'.$p[$i]['url'].'">';
	// 		$html .= '<img class="em-articles-thumbnail" src="'.$p[$i]['thumbnail'].'">';
	// 		$html .= '<span class="em-articles-title">'.$p[$i]['title'].'</span>';
	// 		if (sizeof($p[$i]['excerpt']) > 0)
	// 			$html .= '<span class="em-articles-excerpt">'.$p[$i]['excerpt'].'</span>';
	// 		$html .= '</a></li>';
	// 	}

	// 	$html .= '</div>';

	// 	return $html;
	// }
}