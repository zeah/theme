<?php

final class Emtheme_Frontpage {
	/* SINGLETON */
	private static $instance = null;
	private $data = null;

	public static function get_instance() {

		if (self::$instance === null)
			self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		if ((! current_user_can('read')) || (! is_admin())) return;

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
		register_setting('em_options_forside', 'em_forside_data',  $args);


	}

	public function san_callback($input) {
		if (! is_array($input)) return sanitize_text_field($input);

		$array = [];
		// recurvise for multidimensional arrays
		foreach($input as $key => $value) {
			if (is_array($value)) 	$array[$key] = $this->san_callback($value);
			elseif ($value != '') 	$array[$key] = sanitize_text_field($value);
		}

		return $array;
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

	private function g_opt($o, $checkbox = false) {
		if ($this->data === null) $this->data = get_option('em_forside_data');

		// print_r($this->data);

		if ($checkbox)  return isset($this->data[$o]) ? ' checked' : '';
		else 			return isset($this->data[$o]) ? esc_attr($this->data[$o]) : '';
	}

	public function forside_text_callback() {
		echo 'Choose an alternative frontpage to show at set times each week.<br>If no valid page/post ID is set, then default frontpage is shown.<br>';
	}

	public function foract_callback() {
		echo '<input type="checkbox" name="em_forside_active"'.(get_option('em_forside_active') ? ' checked' : '').'>';
	}

	public function forid_callback() {
		echo '<input type="text" size="4" name="em_forside_data[id]" value="'.$this->g_opt('id').'">';
	}

	public function monday_callback() {
		echo '<input type="checkbox" name="em_forside_data[monday]"'.$this->g_opt('monday', true).'>'.$this->get_dropdowns('monday');
	}

	public function tuesday_callback() {
		echo '<input type="checkbox" name="em_forside_data[tuesday]"'.$this->g_opt('tuesday', true).'>'.$this->get_dropdowns('tuesday');
	}

	public function wednesday_callback() {
		echo '<input type="checkbox" name="em_forside_data[wednesday]"'.$this->g_opt('wednesday', true).'>'.$this->get_dropdowns('wednesday');
	}

	public function thursday_callback() {
		echo '<input type="checkbox" name="em_forside_data[thursday]"'.$this->g_opt('thursday', true).'>'.$this->get_dropdowns('thursday');
	}

	public function friday_callback() {
		echo '<input type="checkbox" name="em_forside_data[friday]"'.$this->g_opt('friday', true).'>'.$this->get_dropdowns('friday');
	}

	public function saturday_callback() {
		echo '<input type="checkbox" name="em_forside_data[saturday]"'.$this->g_opt('saturday', true).'>'.$this->get_dropdowns('saturday');
	}

	public function sunday_callback() {
		echo '<input type="checkbox" name="em_forside_data[sunday]"'.$this->g_opt('sunday', true).'>'.$this->get_dropdowns('sunday');
	}

	private function get_dropdowns($id) {
		$pfix = ['_ts', '_te'];
		$time = ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '00'];
		$html = '';

		foreach ($pfix as $p) {
			$html .= '<select name="em_forside_data['.$id.$p.']">';

			$set = $this->g_opt($id.$p);
			foreach ($time as $t)
				$html .= '<option value="'.$t.'"'.(($t == $set) ? 'selected' : '').'>'.$t.':00</option>';

			$html .= '</select>';
		}
		return $html;
	}

}
