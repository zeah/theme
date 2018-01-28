<?php 
require_once 'inc/functions-admin.php';
require_once 'inc/functions-page.php';
require_once 'inc/functions-shortcode.php';
require_once 'inc/themeoptions.php';

EmAdmin::init();
EmPage::init();
EmThemeShortCode::init();
EmOptions::init();

add_action('wp_enqueue_scripts', 'addingStyleResource');
function addingStyleResource() {
	wp_enqueue_style('style', get_stylesheet_uri(), array(), '1.0.1', '(min-width: 1000px)');
	wp_enqueue_style('style-mobile', get_stylesheet_directory_uri() . '/style-mobile.css', array(), null, '(max-width: 1000px)');
}


/* helper function for meta boxes */
function getmeta($m) {
	global $post;
	$meta = get_post_meta($post->ID, $m);

	if ( ! isset($meta[0]))
		return '';
		// return false;
	else
		return $meta[0];
}

// add_action( 'publish_post', 'itsg_create_sitemap' );
// add_action( 'publish_page', 'itsg_create_sitemap' );
function itsg_create_sitemap() {

    $postsForSitemap = get_posts(array(
        'numberposts' => -1,
        'orderby' => 'modified',
        'post_type'  => array( 'page' ),
        'order'    => 'DESC'
    ));

    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
    $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    foreach( $postsForSitemap as $post ) {
        setup_postdata( $post );

        $postdate = explode( " ", $post->post_modified );

        $sitemap .= '<url>'.
          '<loc>' . get_permalink( $post->ID ) . '</loc>' .
          '<lastmod>' . $postdate[0] . '</lastmod>' .
          '<changefreq>monthly</changefreq>' .
         '</url>';
      }

    $sitemap .= '</urlset>';

    $fp = fopen( ABSPATH . 'sitemap.xml', 'w' );

    fwrite( $fp, $sitemap );
    fclose( $fp );
}

/* FILTER FOR FLOATING IMAGES */
add_filter('the_content', 'helper_float_image');
function helper_float_image($content) {
	$content = preg_replace('/\[caption.*alignright.*?\]/', '<span class="right-image editor-image">', $content);
	$content = preg_replace('/\[caption.*alignleft.*?\]/', '<span class="left-image editor-image">', $content);
	$content = preg_replace('/\[caption.*aligncenter.*?\]/', '<span class="center-image editor-image">', $content);
	$content = preg_replace('/\[\/caption\]/', '</span>', $content);

	return $content;
}
