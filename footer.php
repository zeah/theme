<?php 
require_once 'inc/options/emoption-logger.php';

// $html = '<div class="em-footer"><div class="em-inner-footer">';

// $logger = EmoLogger::get_instance();
// $html .= $logger->add_user();

// $html .= $_SERVER['REMOTE_ADDR'];
// global $wpdb;

// if (! $wpdb->get_var('select ip from wp_em_logger where ip = "'.$_SERVER['REMOTE_ADDR'].'"')) {
// 	$data = [
// 		'ip' => $_SERVER['REMOTE_ADDR'],
// 		'email' => 'test@ja.no'
// 	];

// 	$wpdb->insert('wp_em_logger', $data);
// }

$emsoc = Emsoc::get_instance();

echo $emsoc->get_footer();

final class Emsoc {
	private static $instance = null;
	private $data = null;

	public static function get_instance($active = true) {

		if (self::$instance === null)
			self::$instance = new self();

		return self::$instance;
	}

	private function __construct($active = true) {
		if (! $active)
			return;

		$this->data = get_option('em_contact_data');

	}

	public function get_footer() {
		$html = '<div class="em-footer"><div class="em-inner-footer">';
		
		if (get_option('em_social_active'))
			$html .= $this->get_footer_social();

		if (get_option('em_contact_active'))
			$html .= $this->get_footer_contact();

		if (get_option('em_omoss_active'))
			$html .= $this->get_footer_about();

		$html .= '</div></div>';
		return $html;
	}

	private function get_footer_social() {
		if (! isset($this->data['social']))
			return;


		$html = '<div class="em-socialmedia-container"><ul class="em-footer-ul">';

		$soc = $this->data['social'];
		foreach ($soc as $key => $s)
			$html .= '<li class="em-footer-listitem"><a class="em-footer-link" href="'.esc_url($s).'">'.esc_html($key).'</a></li>';

		$html .= '</ul></div>';
		return $html;
	}

	private function get_footer_contact() {
		if (! isset($this->data['contact']))
			return;

		$html = '<div class="em-contact-container"><ul class="em-footer-ul">';

		$con = $this->data['contact'];
		foreach ($con as $key => $c)
			$html .= '<li class="em-footer-listitem'.(($key == 'epost') ? ' em-footer-epost' : '').(($key == 'poststed' || $key == 'postnr') ? ' em-footer-post': '').'">'.esc_html($c).'</li>';

		$html .= '</ul></div>';
		return $html;
	}

	private function get_footer_about() {
		if (! isset($this->data['omoss']))
			return;

		return '<div class="em-aboutus-container">'.preg_replace('/\[p\]/', '<p>', esc_html($this->data['omoss'])).'</div>';
	}
}

// social media column
// if (get_option('em_social_active')) {
// 	$socmeta = ['em_facebook', 'em_twitter', 'em_google', 'em_youtube'];
// 	$meta = [];
// 	foreach ($socmeta as $value) {
// 		if (get_option($value))
// 			$meta[$value] = esc_url(get_option($value));
// 	}

// 	if (sizeof($meta) > 0) {
// 		$html .= '<div class="em-socialmedia-container"><ul class="em-footer-ul">';

// 		foreach ($meta as $key => $value) 
// 			$html .= '<li class="em-footer-listitem"><a class="em-footer-link" href="'.$value.'">'.preg_replace('/.*_/', '', $key).'</a></li>';

// 		$html .= '</ul></div>';
// 	}
// }
// // contact column
// if (get_option('em_contact_active')) {
// 	$conmeta = ['em_epost', 'em_avdeling', 'em_selskap', 'em_poststed', 'em_postnr', 'em_vei', 'em_land'];
// 	$meta = [];
// 	foreach ($conmeta as $value)
// 		if (get_option($value)) {
// 			if ($value == 'em_epost')
// 				$meta[$value] = sanitize_email(get_option($value));
// 			else
// 				$meta[$value] = sanitize_text_field(get_option($value));
// 		}

// 	if (sizeof($meta) > 0) {
// 		$html .= '<div class="em-contact-container"><ul class="em-footer-ul">';

// 		foreach($meta as $key => $value)
// 			$html .= '<li class="em-footer-listitem'.(($key == 'em_epost') ? ' em-footer-epost' : '').(($key == 'em_poststed' || $key == 'em_postnr') ? ' em-footer-post' : '').'">'.$value.'</li>';

// 		$html .= '</ul></div>';
// 	}
// }

// // about us column
// if (get_option('em_omoss_active'))
// 	$html .= get_option('em_omoss') ? '<div class="em-aboutus-container">'.preg_replace('/\[p\]/', '<p>', sanitize_text_field(get_option('em_omoss'))).'</div>' : '';


// $html .= '</div></div>';

// if (get_option('em_social_active') || get_option('em_contact_active') || get_option('em_omoss_active'))
// echo $html;

wp_footer(); ?>
</body></html>