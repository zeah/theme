<?php 
require_once 'inc/functions-admin.php';
require_once 'inc/functions-page.php';
require_once 'inc/functions-shortcode.php';
require_once 'inc/functions-widget.php';

/* admin menu and theme options */
EmAdmin::get_instance();

/* page-edit page stuff (meta boxes and saving) */
EmPage::get_instance();

/* widgets (top logo/top logo mobile) */
EmWidget::get_instance();

/* shortcodes ([col]) */
EmthemeShortCode::get_instance();

/* css, sitemap, filters */
Emfunc::get_instance();

final class Emfunc {
    private static $instance = null;

    public static function get_instance($activate = true) {

        if (self::$instance === null)
            self::$instance = new self($activate);

        return self::$instance;
    }

    private function __construct($activate = true) {
        if (! $activate)
            return;
        $this->public_wp_hooks();

        if ( (! current_user_can('edit')) || (! is_admin()) )
            return;
        $this->wp_hooks();
    }

    private function public_wp_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'add_style'));
        add_filter('the_content', array($this, 'helper_float_image'));
    }

    private function wp_hooks() {
        add_action('publish_page', array($this, 'add_sitemap'));
        add_action('publish_article', array($this, 'add_sitemap'));
    }

    public function add_style() {
        wp_enqueue_style('style', get_template_directory_uri().'/assets/css/emstyle.css', array(), '1.1.1', '(min-width: 1000px)');
        wp_enqueue_style('style-mobile', get_template_directory_uri().'/assets/css/style-mobile.css', array(), null, '(max-width: 1000px)');
    }

    public function add_sitemap() {
        $postsForSitemap = get_posts(array(
            'numberposts' => -1,
            'orderby' => 'modified',
            'post_type'  => array( 'page', 'article' ),
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


    public function helper_float_image($content) {
        $content = preg_replace('/\[caption.*alignright.*?\]/', '<span class="right-image editor-image">', $content);
        $content = preg_replace('/\[caption.*alignleft.*?\]/', '<span class="left-image editor-image">', $content);
        $content = preg_replace('/\[caption.*aligncenter.*?\]/', '<span class="center-image editor-image">', $content);
        $content = preg_replace('/\[\/caption\]/', '</span>', $content);

        return $content;
    }
}

final class EmHelp {
    public static function sanitize($input) {
        if (! is_array($input))
            return sanitize_text_field($input);

        $array = [];

        // recurvise for multidimensional arrays
        foreach($input as $key => $value) {
            if (is_array($value))
                $array[$key] = $this->san_callback($value);
            else if ($value != '')
                $array[$key] = sanitize_text_field($value);
        }

        return $array;
    }
}