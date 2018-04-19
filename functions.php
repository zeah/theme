<?php 
require_once 'inc/emt-admin.php';
require_once 'inc/emt-page.php';
require_once 'inc/emt-shortcode.php';
require_once 'inc/settings/customizer.php';
require_once 'inc/style_version.php';
// require_once 'inc/emt-redirect.php';
require_once 'inc/emt-link.php';

add_action('after_setup_theme', 'emtheme_setup');

if ( ! isset( $content_width ) )
    $content_width = 1920; /* pixels */

add_theme_support('post-thumbnails');
// add_theme_support( 'html5', array( 'search-form' ) );


if (! function_exists('emtheme_setup')) {
    function emtheme_setup() {

        add_image_size('tilpasset', 810);

        Emtheme_Admin::get_instance();

        /* page-edit page stuff (meta boxes and saving) */
        Emtheme_Page::get_instance();

        /* redirecting pages */
        // Emtheme_redirect::get_instance();

        /* shortcodes ([col]) */
        Emtheme_ShortCode::get_instance();

        /* css, sitemap, filters */
        Emtheme_function::get_instance();

        Emtheme_customizer::get_instance();

        Emtheme_link::get_instance();
    }
}

final class Emtheme_function {
    private static $instance = null;
    private $google_string = 'Open+Sans:600|Roboto:100,300,400,700';

    // DEFAULT STYLE VERSION 
    // private static $style_default = '1.1.1';

    public static function get_instance() {
        if (self::$instance === null) self::$instance = new self();

        return self::$instance;
    }

    private function __construct() {
        $this->public_wp_hooks();
       
        if (! is_admin()) return;

        $this->wp_hooks();
    }

    private function public_wp_hooks() {
        add_action('init', array($this, 'register_nav_cat'));
        add_action('init', array($this, 'disable_emoji'));

        add_action('wp_enqueue_scripts', array($this, 'add_style'));
        add_filter('image_size_names_choose', array($this, 'my_custom_sizes'));

        add_action('wp_ajax_nopriv_emmailAction', array($this, 'emmail_action'));
        add_action('wp_ajax_emmailAction', array($this, 'emmail_action'));

        add_filter('pre_get_posts', array($this, 'set_search'));
    }

    private function wp_hooks() {
        add_action('publish_page', array($this, 'add_sitemap'));
        add_action('publish_article', array($this, 'add_sitemap'));


        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
    }

    public function my_custom_sizes( $sizes ) {
        return array_merge( $sizes, array(
            'tilpasset' => '810 vid (tilpasset)'
        ) );
    }

    public function set_search($query) {
        if ($query->is_search) {
            if (!$query->get('post_type')) $query->set('post_type', array('page'));
            else $query->set('post_type', array_merge(array('page'), $query->get('post_type')));
        }
    }

    public function admin_scripts() {
        wp_enqueue_style('em-admin-style', get_theme_file_uri().'/assets/css/emstyle-admin.css', array(), null);
    }

    public function register_nav_cat() {
        register_taxonomy_for_object_type('category', 'page'); 
        register_nav_menu('header-menu',__( 'Header Menu' ));
    }

    public function add_style() {
        // adding js
        // search box, mobile nav menu
        wp_enqueue_script('emscript', get_theme_file_uri().'/assets/js/emscript.js', array(), '1.0.1', true);

        // $style = get_option('emtheme_styling');
        // desktop style
        // if ($style == 'two')        wp_enqueue_style('style', get_theme_file_uri().'/assets/css/emstyle-two.css', array(), Emtheme_style::$style_two, '(min-width: 1024px)');
        // elseif ($style == 'three')  wp_enqueue_style('style', get_theme_file_uri().'/assets/css/emstyle-three.css', array(), Emtheme_style::$style_one, '(min-width: 1024px)');
        // else                        wp_enqueue_style('style', get_theme_file_uri().'/assets/css/emstyle.css', array(), Emtheme_style::$style_default, '(min-width: 1024px)');
        
        // desktop
        wp_enqueue_style('style', get_theme_file_uri().'/assets/css/emstyle.css', array(), Emtheme_style::$style_default, '(min-width: 1024px)');
        
        // mobile style
        wp_enqueue_style('style-mobile', get_theme_file_uri().'/assets/css/style-mobile.css', array(), Emtheme_style::$style_mobile, '(max-width: 1023px)');

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

            $continue = false;
            foreach(get_the_category($post) as $cat)
                if ($cat->name == 'redirect') $continue = true;

            if ($continue) continue;

            $postdate = explode( " ", $post->post_modified );

            $sitemap .= '<url>'.
              '<loc>' . get_permalink( $post->ID ) . '</loc>' .
              '<lastmod>' . $postdate[0] . '</lastmod>' .
              '<changefreq>monthly</changefreq>' .
             '</url>';
            
            wp_reset_postdata();
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

    public function disable_emoji() {
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

        add_filter( 'tiny_mce_plugins', array($this, 'disable_emojicons_tinymce') );

        add_filter( 'emoji_svg_url', '__return_false' );
    }

    function disable_emojicons_tinymce( $plugins ) {
        if ( is_array( $plugins ) ) 
            return array_diff( $plugins, array( 'wpemoji' ) );
        
        else 
            return array();
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


class Fset {
    public $font;
    public $weight = [];

    public function __construct($font = null, $weight = null) {
        if ($font)
            $this->setFont($font);

        if ($weight)
            $this->addWeight($weight);
    }

    public function setFont($font) {
        $this->font = $font;
    }

    public function addWeight($weight) {
        $weight = str_replace('italic', 'i', $weight);

        if ($weight == 'regular')
            return true;

        if (! in_array($weight, $this->weight)) {
            array_push($this->weight, $weight);
            return true;
        }

        return false;
    }

    public function __toString() {
        if ($this->font == '')
            return '';

        $str = str_replace(' ', '+', $this->font);

        if (sizeof($this->weight) > 0)
            $str .= ':'.implode(',', $this->weight);

        return $str;
    }

}

