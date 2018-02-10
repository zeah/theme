<?php

final class EmoFrontpage {
	/* SINGLETON */
	private static $instance = null;
	public static function get_instance($activate = true) {

		if (self::$instance === null)
			self::$instance = new self($activate);

		return self::$instance;
	}

	private function __construct($activate = true) {
		if ( (! $activate) || (! current_user_can('read')) || (! is_admin()))
			return;
		$this->wp_hooks();
	}

	private function wp_hooks() {
		add_action( 'admin_menu', array($this, 'add_forside_menu') );
		add_action( 'admin_init', array($this, 'initForside') );
		add_action( 'admin_init', array($this, 'registerSettings') );
	}

	public function add_forside_menu() {
		add_submenu_page( 'em-options-page', 'Forside Settings', 'Alternativ Forside', 'manage_options', 'em-forside-page', array($this, 'forside_callback') );
	}

	public function forside_callback() {
		echo '<form action="options.php" method="POST">';
		settings_fields('em_options_forside');
		do_settings_sections('em-forside-page');
		submit_button('save');
		echo '</form>';
	}

	/* saving in db */
	public function registerSettings() {
		$args = [ 'sanitize_callback' => array($this, 'san_callback') ];

		register_setting('em_options_forside', 'em_forside_active', $args);
		register_setting('em_options_forside', 'em_forside_id', $args);
		
		register_setting('em_options_forside', 'em_monday', $args);
		register_setting('em_options_forside', 'em_monday_time_start', $args);
		register_setting('em_options_forside', 'em_monday_time_end', $args);

		register_setting('em_options_forside', 'em_tuesday', $args);
		register_setting('em_options_forside', 'em_tuesday_time_start', $args);
		register_setting('em_options_forside', 'em_tuesday_time_end', $args);

		register_setting('em_options_forside', 'em_wednesday', $args);
		register_setting('em_options_forside', 'em_wednesday_time_start', $args);
		register_setting('em_options_forside', 'em_wednesday_time_end', $args);

		register_setting('em_options_forside', 'em_thursday', $args);
		register_setting('em_options_forside', 'em_thursday_time_start', $args);
		register_setting('em_options_forside', 'em_thursday_time_end', $args);

		register_setting('em_options_forside', 'em_friday', $args);
		register_setting('em_options_forside', 'em_friday_time_start', $args);
		register_setting('em_options_forside', 'em_friday_time_end', $args);

		register_setting('em_options_forside', 'em_saturday', $args);
		register_setting('em_options_forside', 'em_saturday_time_start', $args);
		register_setting('em_options_forside', 'em_saturday_time_end', $args);

		register_setting('em_options_forside', 'em_sunday', $args);
		register_setting('em_options_forside', 'em_sunday_time_start', $args);
		register_setting('em_options_forside', 'em_sunday_time_end', $args);
	}

	public function san_callback($input) {
		return sanitize_text_field( $input );
	}
	/* registering setting fields */
	public function initForside() {
		
		add_settings_section( 'em_forside_settings', 'Til hva tider skal forsiden vise', array($this, 'forside_text_callback'), 'em-forside-page' );
		add_settings_field( 'em-forside-active', 'Aktiv funksjon', array($this, 'foract_callback'), 'em-forside-page', 'em_forside_settings' );
		add_settings_field( 'em-forside-id', 'Alternative Forside ID', array($this, 'forid_callback'), 'em-forside-page', 'em_forside_settings' );
		add_settings_field( 'em-monday', 'Monday', array($this, 'monday_callback'), 'em-forside-page', 'em_forside_settings' );
		add_settings_field( 'em-tuesday', 'Tuesday', array($this, 'tuesday_callback'), 'em-forside-page', 'em_forside_settings' );
		add_settings_field( 'em-wednesday', 'Wednesday', array($this, 'wednesday_callback'), 'em-forside-page', 'em_forside_settings' );
		add_settings_field( 'em-thursday', 'Thursday', array($this, 'thursday_callback'), 'em-forside-page', 'em_forside_settings' );
		add_settings_field( 'em-friday', 'Friday', array($this, 'friday_callback'), 'em-forside-page', 'em_forside_settings' );
		add_settings_field( 'em-saturday', 'Saturday', array($this, 'saturday_callback'), 'em-forside-page', 'em_forside_settings' );
		add_settings_field( 'em-sunday', 'Sunday', array($this, 'sunday_callback'), 'em-forside-page', 'em_forside_settings' );

	}

	public function forside_text_callback() {
		echo 'Choose an alternative frontpage to show at set times each week.<br>If no valid page/post ID is set, then default frontpage is shown.';
	}

	public function foract_callback() {
		echo '<input type="checkbox" name="em_forside_active"'.(get_option('em_forside_active') ? ' checked' : '').'>';
	}

	public function forid_callback() {
		echo '<input type="text" style="width: 4em" name="em_forside_id" value="'.get_option('em_forside_id').'">';
	}

	public function monday_callback() {
		echo '<input type="checkbox" name="em_monday" '.(get_option('em_monday') ? 'checked' : '').'>'.$this->get_time_dropdown('em_monday_time_start').$this->get_time_dropdown('em_monday_time_end');
	}

	public function tuesday_callback() {
		echo '<input type="checkbox" name="em_tuesday" '.(get_option('em_tuesday') ? 'checked' : '').'>'.$this->get_time_dropdown('em_tuesday_time_start').$this->get_time_dropdown('em_tuesday_time_end');
	}

	public function wednesday_callback() {
		echo '<input type="checkbox" name="em_wednesday" '.(get_option('em_wednesday') ? 'checked' : '').'>'.$this->get_time_dropdown('em_wednesday_time_start').$this->get_time_dropdown('em_wednesday_time_end');
	}

	public function thursday_callback() {
		echo '<input type="checkbox" name="em_thursday" '.(get_option('em_thursday') ? 'checked' : '').'>'.$this->get_time_dropdown('em_thursday_time_start').$this->get_time_dropdown('em_thursday_time_end');
	}

	public function friday_callback() {
		echo '<input type="checkbox" name="em_friday" '.(get_option('em_friday') ? 'checked' : '').'>'.$this->get_time_dropdown('em_friday_time_start').$this->get_time_dropdown('em_friday_time_end');
	}

	public function saturday_callback() {
		echo '<input type="checkbox" name="em_saturday" '.(get_option('em_saturday') ? 'checked' : '').'>'.$this->get_time_dropdown('em_saturday_time_start').$this->get_time_dropdown('em_saturday_time_end');
	}

	public function sunday_callback() {
		echo '<input type="checkbox" name="em_sunday" '.(get_option('em_sunday') ? 'checked' : '').'>'.$this->get_time_dropdown('em_sunday_time_start').$this->get_time_dropdown('em_sunday_time_end');
	}

	private function get_time_dropdown($id) {
		$time = ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '00'];
		$html = '<select name="'.$id.'">';

		$set = get_option($id);
		foreach($time as $t) 
			$html .= '<option value="'.$t.'"'.(($t == $set) ? 'selected' : '').'>'.$t.':00</option>';
		
		$html .= '</select>';
		return $html;
	}
}