<?php 
require_once 'inc/functions-admin.php';
require_once 'inc/functions-page.php';
require_once 'inc/functions-shortcode.php';
require_once 'inc/options/emtheme_customizer.php';

add_action('after_setup_theme', 'emtheme_setup');

if ( ! isset( $content_width ) )
    $content_width = 1920; /* pixels */

add_theme_support('post-thumbnails');

if (! function_exists('emtheme_setup')) {
    function emtheme_setup() {
        Emtheme_Admin::get_instance();

        /* page-edit page stuff (meta boxes and saving) */
        Emtheme_Page::get_instance();

        /* shortcodes ([col]) */
        Emtheme_ShortCode::get_instance();

        /* css, sitemap, filters */
        Emtheme_function::get_instance();

        Emtheme_customizer::get_instance();

    }
}

final class Emtheme_function {
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
        add_action('wp_ajax_nopriv_emmailAction', array($this, 'emmail_action'));
        add_action('wp_ajax_emmailAction', array($this, 'emmail_action'));  
    }

    private function wp_hooks() {
        add_action('publish_page', array($this, 'add_sitemap'));
        add_action('publish_article', array($this, 'add_sitemap'));
    }

    public function add_style() {
        $style = get_option('emtheme_styling');

        if ($style == 'two')
            wp_enqueue_style('style', get_theme_file_uri().'/assets/css/emstyle-two.css', array(), '0.0.1', '(min-width: 961px)');
        else if ($style == 'three')
            wp_enqueue_style('style', get_theme_file_uri().'/assets/css/emstyle-three.css', array(), '0.0.1', '(min-width: 961px)');
        // default style (style one)
        else 
            wp_enqueue_style('style', get_theme_file_uri().'/assets/css/emstyle.css', array(), '1.1.1', '(min-width: 961px)');

        wp_enqueue_style('style-mobile', get_theme_file_uri().'/assets/css/style-mobile.css', array(), '1.0.0', '(max-width: 960px)');
    }

    public function emmail_action() {
        global $wpdb;

        if (! isset($_COOKIE['user'])) {
            echo 'no cookie found';
            return;
        }

        echo 'heya';

        check_ajax_referer( 'em_ajax_post_validation', 'security' );

        // validates email
        if (! is_email($_POST['emmail']))
            return;

        $wpdb->update($wpdb->prefix.'em_logger', array( 
            'email' => $_POST['emmail'],
            'emailsrc' => $_POST['emmailsrc'],
            'name' => $_POST['emname']
        ), array('uniqueid' => $_COOKIE['user']));

        $wpdb->flush();
        wp_die();
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
        $content = preg_replace('/\[caption.*alignright.*?\]/', '<span class="emtheme-right-image editor-image">', $content);
        $content = preg_replace('/\[caption.*alignleft.*?\]/', '<span class="emtheme-left-image editor-image">', $content);
        $content = preg_replace('/\[caption.*aligncenter.*?\]/', '<span class="emtheme-center-image editor-image">', $content);
        $content = preg_replace('/\[\/caption\]/', '</span>', $content);

        return $content;
    }
}

final class Emtheme_Help {
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

function remove_thumbnail() {
    remove_meta_box('postimagediv', 'page', 'side');
}

add_action('do_meta_boxes', 'remove_thumbnail');
