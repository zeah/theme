<?php 


final class Emtheme_Customizer {
	private static $instance = null;

	public static function get_instance($active = true) {

		if (self::$instance === null)
			self::$instance = new self();

		return self::$instance;
	}

	private function __construct($active = true) {
		if (! $active)
			return;

		$this->wp_hooks();
	}


	private function wp_hooks() {
		add_action('customize_register', array($this, 'site_identity'));
		add_action('customize_register', array($this, 'footer_info'));
		add_action('customize_register', array($this, 'emtheme_css'));

		add_action('customize_preview_init', array($this, 'cd_customizer'));

		add_action('customize_controls_enqueue_scripts', array($this, 'cd_customizer_pane'));
	}

	public function cd_customizer() {


		// $data = [
		// 	'emtheme_css_navbg_hover' => get_option('emtheme_css_navbg_hover'),
		// 	'emtheme_css_navsub_bg_hover' => get_option('emtheme_css_navsub_bg_hover'),
		// 	'emtheme_css_active_hover' => get_option('emtheme_css_active_hover')
		// ];
		wp_enqueue_script('cd_customizer', get_theme_file_uri() . '/assets/js/emtheme-customizer.js', array( 'jquery','customize-preview' ), '', true);
		// wp_localize_script( 'cd_customizer', 'emthemeCust', $data );
	}

	public function cd_customizer_pane() {
		$fontFile = get_stylesheet_directory() . '/assets/cache/google-web-fonts.txt';
        $content = json_decode(file_get_contents($fontFile));
        // print_r($content);
		// $content = 'hello';
		wp_enqueue_script('cd_customizer_pane', get_theme_file_uri() . '/assets/js/emtheme-customizer-pane.js', array( 'jquery','customize-preview' ), '', true);
	    wp_localize_script('cd_customizer_pane', 'gfont', $content->items);
	}

	public function site_identity($wp_customize) {

		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';


		// add a setting for the site logo
		$wp_customize->add_setting('emtheme_logo', array(
			'type' => 'option',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_setting('emtheme_logo_mobile', array(
			'type' => 'option',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_setting('emtheme_title_mobile', array(
			'type' => 'option',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		));

		// desktop logo
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'emtheme_logo',
			array(
				'label' => 'Upload Logo',
				'section' => 'title_tagline',
				'settings' => 'emtheme_logo',
		) ) );

		// mobile logo
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'emtheme_logo_mobile',
			array(
				'label' => 'Upload Mobile Logo',
				'section' => 'title_tagline',
				'settings' => 'emtheme_logo_mobile',
		) ) );

		// mobile site title
		$wp_customize->add_control('emtheme_title_mobile', 
			array(
				'label' => 'Mobile Title',
				'section' => 'title_tagline',
				'settings' => 'emtheme_title_mobile'
			)
		);
	}

	public function footer_info($wp_customize) {

		$wp_customize->add_panel('emtheme_footer_panel',
			array(
				'title' => 'Footer Info',
				'priority' => 22
			)
		);

		$wp_customize->add_section('emtheme_footer_contact', array(
			'title' => 'Contact Info',
			'panel' => 'emtheme_footer_panel'
		));

		$wp_customize->add_setting('emtheme_footer_contact_active', array(
			'type' => 'option'
		));
		$wp_customize->add_control('emtheme_control_contact_active', array(
			'label' => 'Aktiver',
			'section' => 'emtheme_footer_contact',
			'settings' => 'emtheme_footer_contact_active',
			'type' => 'checkbox'
		));

		$wp_customize->add_setting('emtheme_footer_email', array(
			'type' => 'option',
			// 'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_email',array(
			'label' => 'Email',
			'section' => 'emtheme_footer_contact',
			'settings' => 'emtheme_footer_email'
		));
		
		$wp_customize->add_setting('emtheme_footer_avdeling', array(
			'type' => 'option',
			// 'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_avdeling', array(
			'label' => 'Avdeling',
			'section' => 'emtheme_footer_contact',
			'settings' => 'emtheme_footer_avdeling'
		));

		$wp_customize->add_setting('emtheme_footer_selskap', array(
			'type' => 'option',
			// 'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_selskap', array(
			'label' => 'Selskap',
			'section' => 'emtheme_footer_contact',
			'settings' => 'emtheme_footer_selskap'
		));

		$wp_customize->add_setting('emtheme_footer_poststed', array(
			'type' => 'option',
			// 'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_poststed', array(
			'label' => 'Poststed',
			'section' => 'emtheme_footer_contact',
			'settings' => 'emtheme_footer_poststed'
		));

		$wp_customize->add_setting('emtheme_footer_postnr', array(
			'type' => 'option',
			// 'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_postnr', array(
			'label' => 'Post Nr',
			'section' => 'emtheme_footer_contact',
			'settings' => 'emtheme_footer_postnr'
		));

		$wp_customize->add_setting('emtheme_footer_veiadr', array(
			'type' => 'option',
			// 'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_veiadr', array(
			'label' => 'Vei Addresse',
			'section' => 'emtheme_footer_contact',
			'settings' => 'emtheme_footer_veiadr'
		));

		$wp_customize->add_setting('emtheme_footer_land', array(
			'type' => 'option',
			// 'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_land', array(
			'label' => 'Land',
			'section' => 'emtheme_footer_contact',
			'settings' => 'emtheme_footer_land'
		));


		/* SOCIAL SECTION */
		$wp_customize->add_section('emtheme_footer_social', array(
			'title' => 'Social Media',
			'description' => 'Social Media URLs',
			'panel' => 'emtheme_footer_panel'
		));

		$wp_customize->add_setting('emtheme_footer_social_active', array(
			'type' => 'option'
		));
		$wp_customize->add_control('emtheme_control_social_active', array(
			'label' => 'Aktiver',
			'section' => 'emtheme_footer_social',
			'settings' => 'emtheme_footer_social_active',
			'type' => 'checkbox'
		));

		$wp_customize->add_setting('emtheme_footer_twitter', array(
			'type' => 'option',
			// 'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_twitter', array(
			'label' => 'Twitter',
			'section' => 'emtheme_footer_social',
			'settings' => 'emtheme_footer_twitter'
		));

		$wp_customize->add_setting('emtheme_footer_facebook', array(
			'type' => 'option',
			// 'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_facebook', array(
			'label' => 'Facebook',
			'section' => 'emtheme_footer_social',
			'settings' => 'emtheme_footer_facebook'
		));

		$wp_customize->add_setting('emtheme_footer_google', array(
			'type' => 'option',
			// 'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_google', array(
			'label' => 'Google+',
			'section' => 'emtheme_footer_social',
			'settings' => 'emtheme_footer_google'
		));

		$wp_customize->add_setting('emtheme_footer_youtube', array(
			'type' => 'option',
			// 'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_youtube', array(
			'label' => 'Youtube',
			'section' => 'emtheme_footer_social',
			'settings' => 'emtheme_footer_youtube'
		));


		/* ABOUT US SECTION */
		$wp_customize->add_section('emtheme_footer_aboutus', array(
			'title' => 'About us',
			'panel' => 'emtheme_footer_panel'
		));

		// ACTIVATE
		$wp_customize->add_setting('emtheme_footer_aboutus_active', array(
			'type' => 'option'
		));
		$wp_customize->add_control('emtheme_control_aboutus_active', array(
			'label' => 'Aktiver',
			'section' => 'emtheme_footer_aboutus',
			'settings' => 'emtheme_footer_aboutus_active',
			'type' => 'checkbox'
		));

		// ABOUT US TEXTAREA
		$wp_customize->add_setting('emtheme_footer_about', array(
			'type' => 'option',
			// 'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control('emtheme_control_about', array(
			'label' => 'Om Oss',
			'section' => 'emtheme_footer_aboutus',
			'settings' => 'emtheme_footer_about',
			'type' => 'textarea'
		));

	
	

	}

	public function emtheme_css($wp_customize) {

		/* PANEL */
		$wp_customize->add_panel('emtheme_css_panel', array(
			'title' => 'Emtheme CSS',
			'priority' => 21
		));

		/* COLORS SECTION */
		$wp_customize->add_section('emtheme_css_color', array(
			'title' => 'Colors',
			'description' => '<p><strong>Top</strong><br>..font color<br>..background color</p><p><strong>Navbar</strong><br>..top menu font color<br>..top menu background color<br>...top menu hover background color<p>..sub menu font color<br>..sub menu background color<br>...sub menu hover background color<p>..active page background color<br>...active page hover background color',
			'panel' => 'emtheme_css_panel'
		));

		/* FONT COLOR TOP (LOGO, TITLE, TAGLINE )*/
		$wp_customize->add_setting('emtheme_css_emtop_font', array(
			'type' => 'option',
			'default' => '#000000',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		));
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_css_emtop_font_c', array(
				'label' => 'Header Font-color',
				'description' => 'Site title and tagline.',
				'settings' => 'emtheme_css_emtop_font',
				'section' => 'emtheme_css_color'
		)));

		/* BACKGROUND COLOR TOP (LOGO, TITLE, TAGLINE )*/
		$wp_customize->add_setting('emtheme_css_emtop', array(
			'type' => 'option',
			'default' => '#ffffff',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		));
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_css_emtop_c', array(
				'label' => 'Header Background-color',
				'description' => 'Container for site logo, title and tagline.',
				'settings' => 'emtheme_css_emtop',
				'section' => 'emtheme_css_color'
		)));

		/* NAVBAR FONT COLOR */
		$wp_customize->add_setting('emtheme_css_navfont', array(
			'type' => 'option',
			'default' => '#ffffff',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		));
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_css_navfont_c', array(
				'label' => 'Navbar Font-color',
				'settings' => 'emtheme_css_navfont',
				'section' => 'emtheme_css_color'
		)));

		/* NAVBAR BACKGROUND COLOR */
		$wp_customize->add_setting('emtheme_css_navbg', array(
			'type' => 'option',
			'default' => '#000000',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		));
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_css_navbg_c', array(
				'label' => 'Navbar Background-color',
				'settings' => 'emtheme_css_navbg',
				'section' => 'emtheme_css_color'
		)));

		/* NAVBAR BACKGROUND _HOVER_ COLOR */
		$wp_customize->add_setting('emtheme_css_navbg_hover', array(
			'type' => 'option',
			'default' => '#444444',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		));
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_css_navbg_c_hover', array(
				'label' => 'Navbar Hover Background-color',
				'settings' => 'emtheme_css_navbg_hover',
				'section' => 'emtheme_css_color'
		)));


		/* _SUB_ NAVBAR FONT COLOR*/
		$wp_customize->add_setting('emtheme_css_navsub_font', array(
			'type' => 'option',
			'default' => '#000000',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		));
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_css_navsub_font_c', array(
				'label' => 'Submenu Font-color',
				'settings' => 'emtheme_css_navsub_font',
				'section' => 'emtheme_css_color'
		)));

		/* _SUB_ NAVBAR BACKGROUND COLOR*/
		$wp_customize->add_setting('emtheme_css_navsub_bg', array(
			'type' => 'option',
			'default' => '#e8e8e8',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		));
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_css_navsub_bg_c', array(
				'label' => 'Submenu Background-color',
				'settings' => 'emtheme_css_navsub_bg',
				'section' => 'emtheme_css_color'
		)));
		
		/* _SUB NAVBAR BACKGROUND _HOVER_ COLOR */
		$wp_customize->add_setting('emtheme_css_navsub_bg_hover', array(
			'type' => 'option',
			'default' => '#bfbfbf',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		));
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_css_navsub_bg_hover_c', array(
				'label' => 'Submenu Hover Background-color',
				'settings' => 'emtheme_css_navsub_bg_hover',
				'section' => 'emtheme_css_color'
		)));

		/* ACTIVE PAGE COLOR MARKER */
		$wp_customize->add_setting('emtheme_css_active', array(
			'type' => 'option',
			'default' => '#222222',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color' 
		));
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_css_active_c', array(
				'label' => 'Background-color Marker',
				'description' => 'Shows which page you currently are on.',
				'settings' => 'emtheme_css_active',
				'section' => 'emtheme_css_color' 
		)));

		$wp_customize->add_setting('emtheme_css_active_hover', array(
			'type' => 'option',
			'default' => '#555555',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color' 
		));
		$wp_customize->add_control(new WP_Customize_Color_Control( 
			$wp_customize, 'emtheme_css_active_hover_c', array(
				'label' => 'Background-color Hover Marker',
				'description' => 'Shows which page you currently are on.',
				'settings' => 'emtheme_css_active_hover',
				'section' => 'emtheme_css_color' 
		)));
		
		

		// PANEL
		$wp_customize->add_section('emtheme_css_font', array(
			'title' => 'Fonts',
			'panel' => 'emtheme_css_panel'
		));

		$wp_customize->add_setting('emtheme_font_standard', array(
			'type' => 'option',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field' 
		));
		// $wp_customize->add_control(new Google_Font_Dropdown_Custom_Control(
		// 	$wp_customize, 'emtheme_google_test', array(
		// 		'label' => 'google test',
		// 		'settings' => 'emtheme_font_standard',
		// 		'section' => 'emtheme_css_font'
		// 	)));

		$wp_customize->add_setting('emtheme_font_test', array(
			'type' => 'option',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field' 
		));

		// if (get_option('emtheme_font_test'))
		// $wp_customize->add_control('emtheme_font_test_c', array(
		// 		'label' => 'Test',
		// 		// 'active_callback' => true,
		// 		'settings' => 'emtheme_font_test',
		// 		'type'    => 'select',
		// 	 //    'choices' => array(
		// 	 //        'default' => 'Default',
		// 	 //        'cerulean' => 'Cerulean',
		// 	 //        'cosmo' => 'Cosmo',
		// 	 //        'cyborg' => 'cyborg',
		// 	 //        'test' => null,
  //   // 			),
		// 		'section' => 'emtheme_css_font'
		// ));
		
		// $control = $wp_customize->get_control('emtheme_font_test');
		

		// $control->active_callback = function($control) {
		// 	$setting = $control->manager->get_setting('emtheme_font_standard');
			
		// 	// $test = $control->manager->post_value('emtheme_font_standard');
		// 	// print_r($setting);

		// 	// $control->choices = array('test' => '1'.$wp_customize->post_value('emtheme_font_standard').'2', 'test2' => get_option('emtheme_font_standard'));
		// 	// $control->choices = array('test' => '1'.$setting->value().'2', 'test2' => get_option('emtheme_font_standard'));

		// 	// if (! $setting) {
		// 	// 	true;
		// 	// }


		// 	return '' != $setting->value();
		// };
		

	}
}


/**
 * A class to create a dropdown for all google fonts
 */
if (class_exists('WP_Customize_Control')) {
 class Google_Font_Dropdown_Custom_Control extends WP_Customize_Control
 {
    private $fonts = false;
    public function __construct($manager, $id, $args = array(), $options = array())
    {
        $this->fonts = $this->get_fonts();
        parent::__construct( $manager, $id, $args );
    }
    /**
     * Render the content of the category dropdown
     *
     * @return HTML
     */
    public function render_content()
    {
        if(!empty($this->fonts))
        {
            ?>
                <label>
                    <span class="customize-category-select-control"><?php echo esc_html( $this->label ); ?></span>
                    <select <?php $this->link(); ?>>
                        <?php
                            foreach ( $this->fonts as $k => $v )
                            {
                                printf('<option value="%s" %s>%s</option>', $k, selected($this->value(), $k, false), $v->family);
                            }
                        ?>
                    </select>
                </label>
            <?php
        }
    }
    /**
     * Get the google fonts from the API or in the cache
     *
     * @param  integer $amount
     *
     * @return String
     */
    public function get_fonts( $amount = 30 )
    {
        // $selectDirectory = get_stylesheet_directory().'/wordpress-theme-customizer-custom-controls/select/';
        // $selectDirectoryInc = get_stylesheet_directory().'/inc/wordpress-theme-customizer-custom-controls/select/';
        // $finalselectDirectory = '';
        // if(is_dir($selectDirectory))
        // {
        //     $finalselectDirectory = $selectDirectory;
        // }
        // if(is_dir($selectDirectoryInc))
        // {
        //     $finalselectDirectory = $selectDirectoryInc;
        // }
        $fontFile = get_stylesheet_directory() . '/assets/cache/google-web-fonts.txt';
        //Total time the file will be cached in seconds, set to a week
        $cachetime = 86400 * 7;
        if(file_exists($fontFile) && $cachetime < filemtime($fontFile))
        {
            $content = json_decode(file_get_contents($fontFile));
        } else {
            $googleApi = 'https://www.googleapis.com/webfonts/v1/webfonts?sort=popularity&key=AIzaSyB0sb865wT8n1ZdWK9KhdknRLzyJxr0Qiw';
            $fontContent = wp_remote_get( $googleApi, array('sslverify'   => false) );
            $fp = fopen($fontFile, 'w');
            fwrite($fp, $fontContent['body']);
            fclose($fp);
            $content = json_decode($fontContent['body']);
        }
        if($amount == 'all')
        {
            return $content->items;
        } else {
            return array_slice($content->items, 0, $amount);
        }
    }
 }
}