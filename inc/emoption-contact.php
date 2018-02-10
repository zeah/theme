<?php 

final class EmoContact {
	/* SINGLETON */
	private static $instance = null;
	public static function get_instance($activate = true) {

		if (self::$instance === null)
			self::$instance = new self($activate);

		return self::$instance;
	}

	private function __construct($activate = true) {
		if ( (! $activate) || (! current_user_can('read')) || (! is_admin()) )
			return;
		$this->wp_hooks();
	}

	private function wp_hooks() {
		add_action('admin_menu', array($this, 'add_contact_menu'));
		add_action( 'admin_init', array($this, 'initContact') );
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
		register_setting('em_options_contact', 'em_epost', $args);
		register_setting('em_options_contact', 'em_avdeling', $args);
		register_setting('em_options_contact', 'em_selskap', $args);
		register_setting('em_options_contact', 'em_poststed', $args);
		register_setting('em_options_contact', 'em_postnr', $args);
		register_setting('em_options_contact', 'em_vei', $args);
		register_setting('em_options_contact', 'em_land', $args);
		
		register_setting('em_options_contact', 'em_contact_active', $args);
		register_setting('em_options_contact', 'em_twitter', $args);
		register_setting('em_options_contact', 'em_facebook', $args);
		register_setting('em_options_contact', 'em_youtube', $args);
		register_setting('em_options_contact', 'em_google', $args);
		
		register_setting('em_options_contact', 'em_omoss_active', $args);
		register_setting('em_options_contact', 'em_omoss', $args);

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
		add_settings_field( 'em-youtbue', 'Youtube', array($this, 'Youtube_callback'), 'em-contact-page', 'em_social_settings' );
		
		add_settings_section( 'em_omoss_settings', 'Om Oss', array($this, 'omoss_callback'), 'em-contact-page' );
		add_settings_field( 'em-omoss-active', 'Om Oss Aktivert', array($this, 'omact_callback'), 'em-contact-page', 'em_omoss_settings' );
		add_settings_field( 'em-omoss', 'Om Oss', array($this, 'omosstext_callback'), 'em-contact-page', 'em_omoss_settings');

	}

	public function san_callback($input) {
		return sanitize_text_field( $input );
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
		echo '<input type="text" name="em_epost" placeholder="epost" value="'.get_option('em_epost').'">';
	}

	public function avdeling_callback() {
		echo '<input type="text" name="em_avdeling" placeholder="avdeling" value="'.get_option('em_avdeling').'">';
	}
	
	public function selskap_callback() {
		echo '<input type="text" name="em_selskap" placeholder="selskap" value="'.get_option('em_selskap').'">';
	}
	
	public function poststed_callback() {
		echo '<input type="text" name="em_poststed" placeholder="poststed" value="'.get_option('em_poststed').'">';
	}
	
	public function postnr_callback() {
		echo '<input type="text" name="em_postnr" placeholder="postnr" value="'.get_option('em_postnr').'">';
	}
	
	public function vei_callback() {
		echo '<input type="text" name="em_vei" placeholder="vei" value="'.get_option('em_vei').'">';
	}
	
	public function land_callback() {
		echo '<input type="text" name="em_land" placeholder="land" value="'.get_option('em_land').'">';
	}

	/* SOCIAL MEDIA */
	public function em_social_callback() {
		echo 'Sosiale Media Lenker i footer og structured data';
	}

	public function socact_callback() {
		echo '<input type="checkbox" name="em_social_active"'.(get_option('em_social_active') ? ' checked' : '').'>';
	}
	
	public function twitter_callback() {
		echo '<input type="text" name="em_twitter" placeholder="twitter" value="'.get_option('em_twitter').'">';
	}
	
	public function facebook_callback() {
		echo '<input type="text" name="em_facebook" placeholder="facebook" value="'.get_option('em_facebook').'">';
	}
	
	public function google_callback() {
		echo '<input type="text" name="em_google" placeholder="google" value="'.get_option('em_google').'">';
	}
	
	public function youtube_callback() {
		echo '<input type="text" name="em_youtube" placeholder="youtube" value="'.get_option('em_youtube').'">';
	}
	
	/* ABOUT US */
	public function omoss_callback() {
		echo 'tekst til "om oss"-del i footer';
	}

	public function omact_callback() {
		echo '<input type="checkbox" name="em_omoss_active"'.(get_option('em_omoss_active') ? ' checked' : '').'>';
	}

	public function omosstext_callback() {
		echo '<textarea name="em_omoss" cols="50" rows="5">'.get_option('em_omoss').'</textarea><br>[p] for ny paragraph.';
	}

}