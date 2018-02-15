<?php 

final class EmoLogger {
	/* SINGLETON */
	private static $instance = null;
	private $table_name = 'em_logger';
	private $data = null;

	public static function get_instance($activate = true) {

		if (self::$instance === null)
			self::$instance = new self($activate);

		return self::$instance;
	}

	private function __construct($activate = true) {
		if ( (! $activate) )
			return;
	
		/* hooks for wordpress to accept ajax */
		$this->public_wp_hooks();

		if ( (! current_user_can('publish_pages')) || (! is_admin()) )
			return;

		// creating the table
		$this->init_db();

		// creating the options page in admin menu
		$this->options_hooks();
	}

	/* hooks for wordpress to accept ajax */
	private function public_wp_hooks() {
		add_action('wp_ajax_emmail_action', array($this, 'emmail_action'));
		add_action('wp_ajax_nopriv_emmail_action', array($this, 'emmail_action'));
	}	

	/* to be run when themem is activated/admin page is visited */
	private function init_db() {
		global $wpdb;

		// name of table to use
		$table = $wpdb->prefix . $this->table_name;

		// stop if table exists
		if ($this->table_exists($table))
			return;

		$sql = 'CREATE TABLE '.$table.'(
		id INTEGER(10) UNSIGNED AUTO_INCREMENT,
		hit_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		ip VARCHAR(255),
		name VARCHAR(255),
		uniqueid VARCHAR(255),
		email VARCHAR(255),
		emailsrc VARCHAR(255),
		PRIMARY KEY (id) )';

		// creating table
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	/* helper function for checking whether the table is already created */
	private function table_exists($table) {
		$table = esc_sql($table);
		global $wpdb;

		if ($wpdb->get_var('show tables like "'.$table.'"') != $table)
			return false;

		return true;
	}

	/* CALLED in head.php - before html is sent */
	public function welcome_user() {
		global $wpdb;

		// unique identifier for visiter in the database
		$id = uniqid();

		// do nothing if not activated from options page
		if (! get_option('em_popup_activate'))
			return; 

		// creates cookie and database entry.
		if (! isset($_COOKIE['user'])) {
			setcookie('user', $id, time()+(60*60*24*365), '/');

			// do nothing if cookie can't be set
			if (! isset($_COOKIE['user']))
				return;

			// initial a visitor in the db
			$wpdb->insert($wpdb->prefix . $this->table_name, array(
				'ip' => $_SERVER['REMOTE_ADDR'],
				'uniqueid' => $id
			));

			// enqueue the javascript
			$this->ajaxpopup();
		}
		else {
			// check if email as been set
			if ($wpdb->get_var('select email from '.$wpdb->prefix.$this->table_name.' where uniqueid = "'.$_COOKIE['user'].'"') === null)
				// enqueue the javascript
				$this->ajaxpopup();
		}
	}

	/* enqueue the javascript to do the ajax and popup */
	private function ajaxpopup() {
		if (wp_is_mobile())
			return;
		
		// script for popup and ajax
		wp_enqueue_script('em-email', get_template_directory_uri().'/assets/email.js', array('jquery'), '0.1', true);

		// javascript object from php
		wp_localize_script( 'em-email', 'emmail', array( 
			'ajax_url' => admin_url('admin-ajax.php'), 
			'data' => get_option('em_popup_data'),
			'active' => get_option('em_popup_activate')
		) );
	}

	public function emmail_action() {
		global $wpdb;

		// validates email
		if (! is_email($_POST['emmail']))
			return;

		$wpdb->update($wpdb->prefix.$this->table_name, array( 
			'email' => $_POST['emmail'],
			'emailsrc' => $_POST['emmailsrc'],
			'name' => $_POST['emname']
		), array('uniqueid' => $_COOKIE['user']));

		wp_die();
	}


	/* creating the popup/logger submenu on admin page */
	public function options_hooks() {
		add_action('admin_menu', array($this, 'add_logger_menu'));
		add_action('admin_init', array($this, 'initLogger'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_script'));

	}

	/* enqueuing javascript for image selector */
	public function enqueue_script() {
		$screen = get_current_screen();
		if ($screen->id != 'em-theme_page_em-logger-page')
			return;

		wp_enqueue_script('em-admin-email', get_template_directory_uri().'/assets/em-admin-email.js', array('jquery'), '0.1', true);
		wp_enqueue_media();
	}

	/* addning the submenu page for popup logger */
	public function add_logger_menu() {
		add_submenu_page( 'em-options-page', 'Email Logger', 'Popup/Email Logger', 'manage_options', 'em-logger-page', array($this, 'email_callback') );
	}

	/* initiating options */
	public function initLogger() {
		$args = [ 'sanitize_callback' => array($this, 'san_callback') ];

		register_setting('em_options_logger', 'em_popup_data', $args);
		register_setting('em_options_logger', 'em_popup_activate', $args);


		add_settings_section( 'em_logger_settings', 'Popup Settings', array($this, 'em_logger_callback'), 'em-logger-page' );
		add_settings_field( 'em-popup-aktivert', 'Aktivert', array($this, 'popup_aktivert_callback'), 'em-logger-page', 'em_logger_settings' );
		
		add_settings_field( 'em-popup-title', 'Title', array($this, 'popup_title_callback'), 'em-logger-page', 'em_logger_settings' );
		add_settings_field( 'em-popup-info-one', 'Info Paragraph 1', array($this, 'popup_info_one_callback'), 'em-logger-page', 'em_logger_settings' );
		add_settings_field( 'em-popup-info-two', 'Info Paragraph 2', array($this, 'popup_info_two_callback'), 'em-logger-page', 'em_logger_settings' );
		add_settings_field( 'em-popup-info-three', 'Info Paragraph 3', array($this, 'popup_info_three_callback'), 'em-logger-page', 'em_logger_settings' );


		add_settings_field( 'em-popup-name-title', 'Tekst til navn input', array($this, 'popup_name_text_callback'), 'em-logger-page', 'em_logger_settings' );
		add_settings_field( 'em-popup-email-text', 'Tekst til epost input', array($this, 'popup_email_text_callback'), 'em-logger-page', 'em_logger_settings' );
		add_settings_field( 'em-popup-gobutton-text', 'Tekst på "GO" knapp', array($this, 'popup_gobutton_text_callback'), 'em-logger-page', 'em_logger_settings' );

		add_settings_field( 'em-popup-image', 'Logo', array($this, 'popup_logo_callback'), 'em-logger-page', 'em_logger_settings' );
	}

	/* sanitizing data to be saved in database */
	public function san_callback($input) {
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

	/* helper function for retrieving option values */
	private function g_opt($input, $input2 = null) {
		// $data is the array to be retrieved - array gets retrived only once from database
		if ($this->data === null)
			$this->data = get_option('em_popup_data');

		$d = $this->data;

		// double dimensional array
		if ($input2 !== null)
			return isset($d[$input][$input2]) ? esc_attr($d[$input][$input2]) : '';
			
		return isset($d[$input]) ? esc_attr($d[$input]) : '';
	}

	/* printing the option fields */
	public function email_callback() {
		echo '<form action="options.php" method="POST">';
		settings_fields('em_options_logger');
		do_settings_sections('em-logger-page');
		submit_button('save');
		echo '</form>';
	}

	public function em_logger_callback() {
		echo 'Customize text and picture of popup window';
	}

	public function popup_aktivert_callback() {
		// (data is not stored in the field's array)
		echo '<input type="checkbox" name="em_popup_activate"'.(get_option('em_popup_activate') ? ' checked' : '').'>';
	}

	public function popup_title_callback() {
		echo '<input type="text" name="em_popup_data[title]" value="'.$this->g_opt('title').'">';
	}

	public function popup_info_one_callback() {
		echo '<input type="text" name="em_popup_data[info][one]" value="'.$this->g_opt('info', 'one').'">';
	}

	public function popup_info_two_callback() {
		echo '<input type="text" name="em_popup_data[info][two]" value="'.$this->g_opt('info', 'two').'">';
	}

	public function popup_info_three_callback() {
		echo '<input type="text" name="em_popup_data[info][three]" value="'.$this->g_opt('info', 'three').'">';
	}

	public function popup_gobutton_text_callback() {
		echo '<input type="text" name="em_popup_data[gobutton_text]" value="'.$this->g_opt('gobutton_text').'">';
	}

	public function popup_name_text_callback() {
		echo '<input type="text" name="em_popup_data[name_text]" value="'.$this->g_opt('name_text').'">';
	}

	public function popup_email_text_callback() {
		echo '<input type="text" name="em_popup_data[email_text]" value="'.$this->g_opt('email_text').'">';
	}

	public function popup_logo_callback() {
		echo '<img id="em-popup-logo-image" style="max-width: 200px; padding-bottom: 10px; display: block;" src="'.$this->g_opt('logo').'">
		<input type="button" class="button button-secondary" value="Choose Logo" id="em-popup-logo-button">
		<input type="button" class="button button-secondary" value="Remove Logo" id="em-popup-remove-button">
		<input type="hidden" id="em-popup-logo" name="em_popup_data[logo]" value="'.$this->g_opt('logo').'">';
	}
} 
