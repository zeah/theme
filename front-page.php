<?php 


if (isset($_GET['unsub']) &&  isset($_GET['email'])) {
	
	$unsub = Emtheme_UnSub::get_instance();

	if (! is_email($_GET['email'])) {
		echo $_GET['email'].' is not a valid email.';
		return;
	}

	$unsub->unsub($_GET['email']);

	return;
}

final class Emtheme_UnSub {
	private static $instance = null;
	private $table_name = 'em_logger';

	public static function get_instance($active = true) {
		if (self::$instance === null)
			self::$instance = new self();

		return self::$instance;
	}

	private function __construct($active = true) {
		if (! $active)
			return;
	}

	public function unsub($email) {
		global $wpdb;

		$table = $wpdb->prefix . $this->table_name;

		if ($wpdb->delete($table, array('email' => $email)))
			echo 'Unsubscribe successfull!';
		else
			echo 'Unsubscribe unsuccessfull.';

		$wpdb->flush();
	}
}

if (get_option('em_admin_maint')) {
	get_header('alt');
	echo '<h1 style="height: 100vh">Site is under maintenace. Try again shortly.</h1>';
	get_footer();
	return;
}

// checking if to use alternate page
if (time_check()) {
	global $post;

	$forside = get_option('em_forside_data');

	$post = get_post($forside['id']);

	// print_r($post);
	setup_postdata($post);

	get_template_part('page-templates/alternate-frontpage');

	wp_reset_postdata();
}
// else using default front page
else 
	get_template_part('index');

function time_check() {
	// if feature is active
	if ( ! get_option('em_forside_active')) 
		return false;

	$forside = get_option('em_forside_data');

	if (!isset($forside['id']) || !get_post($forside['id']))
		return false;

	// setting php time to CET time
	date_default_timezone_set('Europe/Oslo');
	$today = strtolower(date('l'));

	if (!isset($forside[$today]))
		return;	

	if (!($forside[$today.'_ts'] <= date('H')))
		return false;

	if ($forside[$today.'_te'] != '00')
		if (!($forside[$today.'_te'] > date('H')))
			return false;

	// if current time is set to be alternate page
	return true;
}
