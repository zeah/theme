<?php 

final class EmOptions {
	public static function init() {
		add_action('admin_menu', array('EmOptions', 'add_options_menu'));
		add_action( 'admin_init', array('EmOptions', 'initSetting') );
	}

	public static function add_options_menu() {
		add_menu_page('EmTheme Options', 'EM Options', 'manage_options', 'em-options-page', array('EmOptions', 'em_options_callback'), '', 61);
		add_submenu_page( 'em-options-page', 'Kontakt Informasjon', 'Kontakt', 'manage_options', 'em-contact-page', array('EmOptions', 'em_contact_callback') );
	}

	public static function initSetting() {
		$args = [
			'sanitize_callback' => array('EmOptions', 'san_callback')
		];
		register_setting('em_options', 'em_epost', $args);
		register_setting('em_options', 'em_avdeling', $args);
		register_setting('em_options', 'em_selskap', $args);
		register_setting('em_options', 'em_poststed', $args);
		register_setting('em_options', 'em_postnr', $args);
		register_setting('em_options', 'em_vei', $args);
		register_setting('em_options', 'em_land', $args);
		
		register_setting('em_options', 'em_twitter', $args);
		register_setting('em_options', 'em_facebook', $args);
		register_setting('em_options', 'em_youtube', $args);
		register_setting('em_options', 'em_google', $args);
		
		register_setting('em_options', 'em_omoss', $args);

		add_settings_section( 'em_contant_settings', 'Kontakt Informasjon', array('EmOptions', 'em_settings_callback'), 'em-contact-page' );
		add_settings_field( 'em-epost', 'Epost', array('EmOptions', 'epost_callback'), 'em-contact-page', 'em_contant_settings' );
		add_settings_field( 'em-avdeling', 'Avdeling (webside)', array('EmOptions', 'avdeling_callback'), 'em-contact-page', 'em_contant_settings' );
		add_settings_field( 'em-selskap', 'Selskap (Business Name)', array('EmOptions', 'selskap_callback'), 'em-contact-page', 'em_contant_settings' );
		add_settings_field( 'em-poststed', 'Poststed', array('EmOptions', 'poststed_callback'), 'em-contact-page', 'em_contant_settings' );
		add_settings_field( 'em-postnr', 'Post Nr', array('EmOptions', 'postnr_callback'), 'em-contact-page', 'em_contant_settings' );
		add_settings_field( 'em-vei', 'Vei Addresse', array('EmOptions', 'vei_callback'), 'em-contact-page', 'em_contant_settings' );
		add_settings_field( 'em-land', 'Land', array('EmOptions', 'land_callback'), 'em-contact-page', 'em_contant_settings' );

		add_settings_section( 'em_social_settings', 'Sosial Media', array('EmOptions', 'em_social_callback'), 'em-contact-page' );
		add_settings_field( 'em-twitter', 'Twitter', array('EmOptions', 'twitter_callback'), 'em-contact-page', 'em_social_settings' );
		add_settings_field( 'em-facebook', 'Facebook', array('EmOptions', 'facebook_callback'), 'em-contact-page', 'em_social_settings' );
		add_settings_field( 'em-google', 'Google+', array('EmOptions', 'google_callback'), 'em-contact-page', 'em_social_settings' );
		add_settings_field( 'em-youtbue', 'Youtube', array('EmOptions', 'Youtube_callback'), 'em-contact-page', 'em_social_settings' );
		
		add_settings_section( 'em_omoss_settings', 'Om Oss', array('EmOptions', 'omoss_callback'), 'em-contact-page' );
		add_settings_field( 'em-omoss', 'Om Oss', array('EmOptions', 'omosstext_callback'), 'em-contact-page', 'em_omoss_settings');

	}

	public static function san_callback($input) {
		return sanitize_text_field( $input );
	}

	public static function em_contact_callback() {
		echo '<form action="options.php" method="POST">';
		settings_fields('em_options');
		do_settings_sections('em-contact-page');
		// do_settings_sections('em_social_options');
		submit_button('save');
		echo '</form>';
	}

	public static function em_settings_callback() {
		echo 'For footer og structured data';
	}

	public static function epost_callback() {
		echo '<input name="em_epost" placeholder="epost" value="'.get_option('em_epost').'">';
	}

	public static function avdeling_callback() {
		echo '<input name="em_avdeling" placeholder="avdeling" value="'.get_option('em_avdeling').'">';
	}
	
	public static function selskap_callback() {
		echo '<input name="em_selskap" placeholder="selskap" value="'.get_option('em_selskap').'">';
	}
	
	public static function poststed_callback() {
		echo '<input name="em_poststed" placeholder="poststed" value="'.get_option('em_poststed').'">';
	}
	
	public static function postnr_callback() {
		echo '<input name="em_postnr" placeholder="postnr" value="'.get_option('em_postnr').'">';
	}
	
	public static function vei_callback() {
		echo '<input name="em_vei" placeholder="vei" value="'.get_option('em_vei').'">';
	}
	
	public static function land_callback() {
		echo '<input name="em_land" placeholder="land" value="'.get_option('em_land').'">';
	}

	public static function em_social_callback() {
		echo 'Sosiale Media Lenker i footer og structured data';
	}
	
	public static function twitter_callback() {
			echo '<input name="em_twitter" placeholder="twitter" value="'.get_option('em_twitter').'">';
	}
	
	public static function facebook_callback() {
			echo '<input name="em_facebook" placeholder="facebook" value="'.get_option('em_facebook').'">';
	}
	
	public static function google_callback() {
			echo '<input name="em_google" placeholder="google" value="'.get_option('em_google').'">';
	}
	
	public static function youtube_callback() {
			echo '<input name="em_youtube" placeholder="youtube" value="'.get_option('em_youtube').'">';
	}
	
	public static function omoss_callback() {
		echo 'tekst til "om oss"-del i footer';
	}

	public static function omosstext_callback() {
		echo '<textarea name="em_omoss" cols="50" rows="5">'.get_option('em_omoss').'</textarea><br>[p] for ny paragraph.';
	}

	public static function em_options_callback() {
		echo 'general options?';
		// echo '<form action="options.php" method="POST">';
		// settings_fields('em_options');
		// do_settings_sections('em-contact-page');
		// // do_settings_sections('em_social_options');
		// submit_button('save');
		// echo '</form>';
		// echo 'dklfj '.basename(__FILE__);
	}
}