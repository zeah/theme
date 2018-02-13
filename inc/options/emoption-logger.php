<?php 

final class EmoLogger {
	/* SINGLETON */
	private static $instance = null;
	private $table_name = 'em_logger';
	public static function get_instance($activate = true) {

		if (self::$instance === null)
			self::$instance = new self($activate);

		return self::$instance;
	}

	private function __construct($activate = true) {
		if ( (! $activate) )
			return;

		$this->wp_hooks();

		if ( (! current_user_can('install_themes')) || (! is_admin()) )
			return;

		$this->init_db();
	}

	private function wp_hooks() {
		add_action('wp_ajax_emmail_action', array($this, 'emmail_action'));
		add_action('wp_ajax_nopriv_emmail_action', array($this, 'emmail_action'));
	}	

	private function init_db() {
		global $wpdb;

		$table = $wpdb->prefix . $this->table_name;

		if ($this->table_exists($table))
			return;

		$sql = 'CREATE TABLE '.$table.'(
		id INTEGER(10) UNSIGNED AUTO_INCREMENT,
		hit_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		ip VARCHAR(255),
		name VARCHAR(255),
		uniqueid VARCHAR(255),
		email VARCHAR(255),
		PRIMARY KEY (id) )';

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	private function table_exists($table) {
		$table = esc_sql($table);
		global $wpdb;

		if ($wpdb->get_var('show tables like "'.$table.'"') != $table)
			return false;

		return true;
	}


	public function check_user() {
		// checking to see if user is in database
		// cookies?
	}

	public function add_user() {
		// add user to database
		$tables = 'wp_em_logger';
		global $wpdb;
		return $wpdb->get_var('show tables like "'.$tables.'"');
	}

	public function welcome_user() {
		global $wpdb;
		$id = uniqid();
		$this->em_ajax();

		// creates cookie and database entry.
		// if (! isset($_COOKIE['user'])) {
		// 	setcookie('user', $id, time()+(60*60*24*365), '/');

		// 	$wpdb->insert($wpdb->prefix . $this->table_name, array(
		// 		'ip' => $_SERVER['REMOTE_ADDR'],
		// 		'uniqueid' => $id,
		// 		'email' => ''
		// 	));

		// 	$this->em_ajax();
		// }
		// else {
		// 	if ($wpdb->get_var('select email from '.$wpdb->prefix.$this->table_name.' where uniqueid = "'.$_COOKIE['user'].'"') == '')
		// 		$this->em_ajax();
		// }
	}

	private function em_ajax() {
		wp_enqueue_script('em-email', get_template_directory_uri().'/assets/email.js', array('jquery'), '0.1', true);
		wp_localize_script( 'em-email', 'emmail', array( 'ajax_url' => admin_url('admin-ajax.php'), 'tekst' => 'test tekst') );
	}

	public function emmail_action() {
		global $wpdb;
		$wpdb->update($wpdb->prefix.$this->table_name, array( 'email' => $_POST['emmail']), array('uniqueid' => $_COOKIE['user']));

		wp_die();
	}
} 
