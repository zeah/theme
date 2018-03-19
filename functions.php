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
// add_theme_support( 'html5', array( 'search-form' ) );

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
    private $google_string = 'Open+Sans:600|Roboto:100,300,400,700';

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
          
        // $mobile = wp_is_mobile();
          
        $data = '';

        $colors = get_option('emtheme_color');
        // COLOR TOP BACKGROUND
        // if (isset($colors['emtop_bg']))
            $data .= '@media (min-width: 961px) { 
                        .emtop { 
                            background-color: '.(isset($colors['emtop_bg']) ? esc_html($colors['emtop_bg']) : Emtheme_style::$colors['top']['bg']).'; 
                        } 
                      }';
        
        // COLOR TOP FONT
        // if (isset($colors['emtop_font']))
            $data .= '.emtop { 
                        color: '.(isset($colors['emtop_font']) ? esc_html($colors['emtop_font']) : Emtheme_style::$colors['top']['font']).'; 
                    }';

        // COLOR NAVBAR FONT
        // if (isset($colors['nav_font']))
            $data .= '.em-nav-lenke { 
                        color: '.(isset($colors['nav_font']) ? esc_html($colors['nav_font']) : Emtheme_style::$colors['nav']['font']).'; 
                    }';

        // COLOR NAVBAR BACKGROUND
        // if (isset($colors['nav_bg'])) {
            $data .= '.nav-container { 
                        background-color: '.(isset($colors['nav_bg']) ? esc_html($colors['nav_bg']) : Emtheme_style::$colors['nav']['bg']).'; 
                      }';
            $data .= '@media (max-width: 960px) { 
                        .emtop { background-color: '.(isset($colors['nav_bg']) ? esc_html($colors['nav_bg']) : Emtheme_style::$colors['nav']['bg']).'; } 
                      }';
        // }
       
        // COLOR NAVBAR BACKGROUND HOVER
        // if (isset($colors['nav_bg_hover']))
            $data .= '.em-nav-item:hover { 
                        background-color: '.(isset($colors['nav_bg_hover']) ? esc_html($colors['nav_bg_hover']) : Emtheme_style::$colors['nav']['hover']).'; 
                      }';

        // COLOR SUBMENU BACKGROUND
        // if (isset($colors['navsub_bg']))
            $data .= '.em-nav-sub-container, .em-nav-subitem { 
                            background-color: '.(isset($colors['navsub_bg']) ? esc_html($colors['navsub_bg']) : Emtheme_style::$colors['sub']['bg']).'; 
                       }';

        // COLOR SUBMENU BACKGROUND HOVER
        // if (isset($colors['navsub_bg_hover']))
            $data .= '.em-nav-subitem:hover { 
                        background-color: '.(isset($colors['navsub_bg_hover']) ? esc_html($colors['navsub_bg_hover']) : Emtheme_style::$colors['sub']['hover']).'; 
                      }';

        // COLOR SUBMENU FONT
        // if (isset($colors['navsub_font']))
            $data .= '.em-nav-sublenke { 
                        color: '.(isset($colors['navsub_font']) ? esc_html($colors['navsub_font']) : Emtheme_style::$colors['sub']['font']).'; 
                      }';

        // COLOR ACTIVE PAGE MARKER ON NAVBAR
        // if (isset($colors['active']))
            $data .= '.em-nav-current { 
                        background-color: '.(isset($colors['active']) ? esc_html($colors['active']) : Emtheme_style::$colors['active']['bg']).'; 
                      }';

        // COLOR ACTIVE PAGE MARKER HOVER ON NAVBAR
        // if (isset($colors['active_hover']))
            $data .= '.em-nav-current:hover { 
                        background-color: '.(isset($colors['active_hover']) ? esc_html($colors['active_hover']) : Emtheme_style::$colors['active']['hover']).'; 
                      }';
        
        // adding fonts
        $fonts = get_option('emtheme_font');

        if (isset($fonts['title']))
            $data .= '.emtheme-title { font-family: '.esc_html($fonts['title']).'; }';
        if (isset($fonts['title_weight']) && $fonts['title_weight'] != 'regular')
            $data .= '.emtheme-title { font-weight: '.esc_html(str_replace('italic', '', $fonts['title_weight'])).'; }';
        if (isset($fonts['title_size']))
            $data .= '.emtheme-title { font-size: '.esc_html($fonts['title_size']).'rem; }';
        

        if (isset($fonts['nav']))
            $data .= '.nav { font-family: '.esc_html($fonts['nav']).'; }';
        if (isset($fonts['nav_weight']) && $fonts['nav_weight'] != 'regular')
            $data .= '.nav { font-weight: '.esc_html(str_replace('italic', '', $fonts['nav_weight'])).'; }';
        if (isset($fonts['nav_size']))
            $data .= '.nav { font-size: '.esc_html($fonts['nav_size']).'rem; }';
                        

        if (isset($fonts['standard']))
            $data .= '.content, .emtheme-tagline, .content-title-text { font-family: '.esc_html($fonts['standard']).'; }';
        if (isset($fonts['standard_weight']) && $fonts['standard_weight'] != 'regular')
            $data .= '.content, .emtheme-tagline { font-weight: '.esc_html(str_replace('italic', '', $fonts['standard_weight'])).'; }';
        if (isset($fonts['standard_size']))
            $data .= '.content, .emtheme-tagline { font-size: '.esc_html($fonts['standard_size']).'rem; }';
        if (isset($fonts['standard_lineheight']))
            $data .= '.content > p { line-height: '.esc_html($fonts['standard_lineheight']).'; }';
        

        wp_add_inline_style( 'style', $data );
        wp_add_inline_style( 'style-mobile', $data );

        $font_set = [];

        $ff = ['standard', 'nav', 'title'];
        foreach ($ff as $value) {
            if (isset($fonts[$value])) {

                $found = false;

                foreach($font_set as $v) 
                    if ($v->font == $fonts[$value]) {
                        $found = true;
                        if (isset($fonts[$value.'_weight']))
                            $v->addWeight($fonts[$value.'_weight']);
                    }
                
                if (! $found) {
                    if (isset($fonts[$value.'_weight']))
                        array_push($font_set, new Fset($fonts[$value], $fonts[$value.'_weight']));
                    else
                        array_push($font_set, new Fset($fonts[$value]));
                }

            }
        }

        $font_str = '';
        foreach ($font_set as $o)
            if ($o != '')
                $font_str .= $o.'|';

        $font_str = rtrim($font_str, '|');

        $this->google_string = $font_str;

    }

    public function add_head() {
        $font = $this->google_string;

        if ($font == ':' || $font == '')
            $font = 'Roboto|Open+Sans+Condensed:700';

        echo '<link href="https://fonts.googleapis.com/css?family='.esc_html($font).'" rel="stylesheet">';
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