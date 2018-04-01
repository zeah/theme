<?php 

final class Emtheme_Contact {
	/* SINGLETON */
	private static $instance = null;
	private $data = null;
	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self($activate);

		return self::$instance;
	}

	private function __construct() {
		if ((! current_user_can('read')) || (! is_admin()) ) return;
		$this->wp_hooks();
	}

	private function wp_hooks() {
		add_action('admin_menu', array($this, 'add_contact_menu'));
		add_action('admin_init', array($this, 'initContact'));
	}	

	public function add_contact_menu() {
		add_submenu_page( 'em-options-page', 'Kontakt Informasjon', 'Footer Kontakt', 'manage_options', 'em-contact-page', array($this, 'contact_callback') );
	}

	public function contact_callback() {
		echo '<form action="options.php" method="POST">';
		settings_fields('em_options_contact');
		do_settings_sections('em-contact-page');
		submit_button('save');
		echo '</form>';
	}

	public static function initContact() {
		$args = [ 'sanitize_callback' => array($this, 'san_callback') ];
		register_setting('em_options_contact', 'em_social_active', $args);
		register_setting('em_options_contact', 'em_contact_data', $args);
		register_setting('em_options_contact', 'em_contact_active', $args);
		register_setting('em_options_contact', 'em_omoss_active', $args);

		add_settings_section( 'em_contact_settings', 'Kontakt Informasjon', array($this, 'em_settings_callback'), 'em-contact-page' );
		add_settings_field( 'em-contact-active', 'Kontakter Aktivert', array($this, 'conact_callback'), 'em-contact-page', 'em_contact_settings' );
		add_settings_field( 'em-epost', 'Epost', array($this, 'epost_callback'), 'em-contact-page', 'em_contact_settings' );
		add_settings_field( 'em-avdeling', 'Avdeling (webside)', array($this, 'avdeling_callback'), 'em-contact-page', 'em_contact_settings' );
		add_settings_field( 'em-selskap', 'Selskap (Business Name)', array($this, 'selskap_callback'), 'em-contact-page', 'em_contact_settings' );
		add_settings_field( 'em-poststed', 'Poststed', array($this, 'poststed_callback'), 'em-contact-page', 'em_contact_settings' );
		add_settings_field( 'em-postnr', 'Post Nr', array($this, 'postnr_callback'), 'em-contact-page', 'em_contact_settings' );
		add_settings_field( 'em-vei', 'Vei Addresse', array($this, 'vei_callback'), 'em-contact-page', 'em_contact_settings' );
		add_settings_field( 'em-land', 'Land', array($this, 'land_callback'), 'em-contact-page', 'em_contact_settings' );

		add_settings_section( 'em_social_settings', 'Sosial Media', array($this, 'em_social_callback'), 'em-contact-page' );
		add_settings_field( 'em-social-active', 'Sosial Medier Aktivert', array($this, 'socact_callback'), 'em-contact-page', 'em_social_settings' );
		add_settings_field( 'em-twitter', 'Twitter', array($this, 'twitter_callback'), 'em-contact-page', 'em_social_settings' );
		add_settings_field( 'em-facebook', 'Facebook', array($this, 'facebook_callback'), 'em-contact-page', 'em_social_settings' );
		add_settings_field( 'em-google', 'Google+', array($this, 'google_callback'), 'em-contact-page', 'em_social_settings' );
		add_settings_field( 'em-youtbue', 'Youtube', array($this, 'youtube_callback'), 'em-contact-page', 'em_social_settings' );
		
		add_settings_section( 'em_omoss_settings', 'Om Oss', array($this, 'omoss_callback'), 'em-contact-page' );
		add_settings_field( 'em-omoss-active', 'Om Oss Aktivert', array($this, 'omact_callback'), 'em-contact-page', 'em_omoss_settings' );
		add_settings_field( 'em-omoss', 'Om Oss', array($this, 'omosstext_callback'), 'em-contact-page', 'em_omoss_settings');

	}

	public function san_callback($input) {
		if (! is_array($input)) return sanitize_text_field($input);

		$array = [];

		// recursive for multidimensional arrays
		foreach($input as $key => $value) {
			if (is_array($value)) $array[$key] = $this->san_callback($value);
			elseif ($value != '') $array[$key] = sanitize_text_field($value);
		}

		return $array;
	}

	private function g_opt($input, $input2 = null) {
		// $data is the array to be retrieved - array gets retrived only once from database
		if ($this->data === null) $this->data = get_option('em_contact_data');

		$d = $this->data;

		// double dimensional array
		if ($input2 !== null) return isset($d[$input][$input2]) ? esc_attr($d[$input][$input2]) : '';
			
		return isset($d[$input]) ? esc_attr($d[$input]) : '';
	}

		/* CONTACT PAGE (SOCIAL MEDIA + CONTACT INFO + ABOUT US)*/
	public function em_contact_callback() {
		echo '<form action="options.php" method="POST">';
		settings_fields('em_options_contact');
		do_settings_sections('em-contact-page');
		submit_button('save');
		echo '</form>';
	}


	/* CONTACT INFORMATION */
	public function em_settings_callback() {
		echo 'For info i footer (og structured data.)';
	}

	public function conact_callback() {
		echo '<input type="checkbox" name="em_contact_active"'.(get_option('em_contact_active') ? ' checked' : '').'>';
	}

	public function epost_callback() {
		echo '<input type="text" name="em_contact_data[contact][epost]" placeholder="epost" value="'.$this->g_opt('contact', 'epost').'">';
	}

	public function avdeling_callback() {
		echo '<input type="text" name="em_contact_data[contact][avdeling]" placeholder="avdeling" value="'.$this->g_opt('contact', 'avdeling').'">';
	}
	
	public function selskap_callback() {
		echo '<input type="text" name="em_contact_data[contact][selskap]" placeholder="selskap" value="'.$this->g_opt('contact', 'selskap').'">';
	}
	
	public function poststed_callback() {
		echo '<input type="text" name="em_contact_data[contact][poststed]" placeholder="poststed" value="'.$this->g_opt('contact', 'poststed').'">';
	}
	
	public function postnr_callback() {
		echo '<input type="text" name="em_contact_data[contact][postnr]" placeholder="postnr" value="'.$this->g_opt('contact', 'postnr').'">';
	}
	
	public function vei_callback() {
		echo '<input type="text" name="em_contact_data[contact][vei]" placeholder="vei" value="'.$this->g_opt('contact', 'vei').'">';
	}
	
	public function land_callback() {
		echo '<input type="text" name="em_contact_data[contact][land]" placeholder="land" value="'.$this->g_opt('contact', 'land').'">';
	}

	/* SOCIAL MEDIA */
	public function em_social_callback() {
		echo 'Sosiale Media Lenker i footer og structured data';
	}

	public function socact_callback() {
		echo '<input type="checkbox" name="em_social_active"'.(get_option('em_social_active') ? ' checked' : '').'>';
	}
	
	public function twitter_callback() {
		echo '<input type="text" name="em_contact_data[social][twitter]" placeholder="twitter" value="'.$this->g_opt('social', 'twitter').'">';
	}
	
	public function facebook_callback() {
		echo '<input type="text" name="em_contact_data[social][facebook]" placeholder="facebook" value="'.$this->g_opt('social', 'facebook').'">';
	}
	
	public function google_callback() {
		echo '<input type="text" name="em_contact_data[social][google]" placeholder="google" value="'.$this->g_opt('social', 'google').'">';
	}
	
	public function youtube_callback() {
		echo '<input type="text" name="em_contact_data[social][youtube]" placeholder="youtube" value="'.$this->g_opt('social', 'youtube').'">';
	}
	
	/* ABOUT US */
	public function omoss_callback() {
		echo 'tekst til "om oss"-del i footer';
	}

	public function omact_callback() {
		echo '<input type="checkbox" name="em_omoss_active"'.(get_option('em_omoss_active') ? ' checked' : '').'>';
	}

	public function omosstext_callback() {
		echo '<textarea name="em_contact_data[omoss]" cols="50" rows="5">'.$this->g_opt('omoss').'</textarea><br>[p] for ny paragraph.';
	}

}
