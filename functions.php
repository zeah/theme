<?php 
require_once 'inc/functions-admin.php';
require_once 'inc/functions-page.php';
require_once 'inc/functions-shortcode.php';
require_once 'inc/options/emtheme_customizer.php';
require_once 'inc/style_version.php';

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

    // DEFAULT STYLE VERSION 
    // private static $style_default = '1.1.1';

    public static function get_instance($activate = true) {

        if (self::$instance === null)
            self::$instance = new self($activate);

        return self::$instance;
    }

    private function __construct($activate = true) {
        if (! $activate)
            return;
        $this->public_wp_hooks();

        if (! is_admin())
            return;
        $this->wp_hooks();
    }

    private function public_wp_hooks() {

        add_action('wp_enqueue_scripts', array($this, 'add_style'));
        add_action('wp_head', array($this, 'add_head'));

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
            wp_enqueue_style('style', get_theme_file_uri().'/assets/css/emstyle-two.css', array(), Emtheme_style::$style_two, '(min-width: 961px)');
        else if ($style == 'three')
            wp_enqueue_style('style', get_theme_file_uri().'/assets/css/emstyle-three.css', array(), Emtheme_style::$style_one, '(min-width: 961px)');
        // default style (style one)
        else {
            wp_enqueue_style('style', get_theme_file_uri().'/assets/css/emstyle.css', array(), Emtheme_style::$style_default, '(min-width: 961px)');
        }

        wp_enqueue_style('style-mobile', get_theme_file_uri().'/assets/css/style-mobile.css', array(), Emtheme_style::$style_mobile, '(max-width: 960px)');
          
        $mobile = wp_is_mobile();
          
        $data = '';
        // desktop logo/title/tagline area
        if (get_option('emtheme_css_emtop'))
            $data .= '.emtop { background-color: '.get_option('emtheme_css_emtop').';}';

        // font color of title/tagline
        if (get_option('emtheme_css_emtop_font'))
            $data .=  '.emtheme-top-link { color: '.get_option('emtheme_css_emtop_font').';}';

        // title/tagline container element is in navbar on mobile
        // if (get_option('emtheme_css_navbg'))
        //     $data .= '.nav-container, .emtop-mobile { background-color: '.get_option('emtheme_css_navbg').';}';

        // desktop navbar
        if (get_option('emtheme_css_navbg')) {
            $data .= '.nav-container { 
                    background-color: '.get_option('emtheme_css_navbg').';
                }
                @media (max-width: 961px) {
                    .emtop {
                        background-color: '.get_option('emtheme_css_navbg').';
                    }
                }';
        }

        if (get_option('emtheme_css_navfont'))
            $data .= '.em-nav-lenke { color: '.get_option('emtheme_css_navfont').';}';

        if (get_option('emtheme_css_navbg_hover'))
            $data .= '.em-nav-item:hover { background-color: '.get_option('emtheme_css_navbg_hover').';}';

        if (get_option('emtheme_css_navsub_bg'))
            $data .= '.em-nav-sub-container, .em-nav-subitem { background-color: '.get_option('emtheme_css_navsub_bg').';}';

        if (get_option('emtheme_css_navsub_bg_hover'))
            $data .= '.em-nav-subitem:hover { background-color: '.get_option('emtheme_css_navsub_bg_hover').';}';

        if (get_option('emtheme_css_navsub_font'))
            $data .= '.em-nav-sublenke { color: '.get_option('emtheme_css_navsub_font').';}';

        if (get_option('emtheme_css_active'))
            $data .= '.em-nav-current { background-color: '.get_option('emtheme_css_active').';}';

        if (get_option('emtheme_css_active_hover'))
            $data .= '.em-nav-current:hover { background-color: '.get_option('emtheme_css_active_hover').';}';

        wp_add_inline_style( 'style', $data );
    }

    public function add_head() {
        // echo '<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:700|Roboto:100,300,400,700" rel="stylesheet">';
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
