<?php 

final class EmoLogger {
	/* SINGLETON */
	private static $instance = null;
	private static $table_name = 'em_logger';
	public static function get_instance($activate = true) {

		if (self::$instance === null)
			self::$instance = new self($activate);

		return self::$instance;
	}

	private function __construct($activate = true) {
		if ( (! $activate) || (! current_user_can('read')) || (! is_admin()) )
			return;

		$this->wp_hooks();

		$this->init_db();
	}

	private function wp_hooks() {
	
	}	

	private function init_db() {
		global $wpdb;

		$table = $wpdb->prefix . self::$table_name;

		if ($this->table_exists($table))
			return;

		$sql = 'CREATE TABLE '.$table.'(
		id INTEGER(10) UNSIGNED AUTO_INCREMENT,
		hit_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		ip VARCHAR(255),
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

	public function add_email() {
		// adding email to database
	}
} 


if (isset($_POST['em_email'])) {
	$logger = EmoLogger::get_instance();

	// if ABSPATH ?? to check if ajax or not
}
