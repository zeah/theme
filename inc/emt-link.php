<?php 


final class Emtheme_Link {
	/*singleton*/
	private static $instance = null;

	private $links = [];

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		add_filter('add_emtheme_links', array($this, 'add_links'));
	}

	public function add_links($value) {

		$fonts = get_option('emtheme_font');
		$variants = ['standard', 'nav', 'title'];

		foreach($variants as $v)
			if(isset($fonts[$v]) && isset($fonts[$v.'_weight'])) {

				if ($fonts[$v.'_weight'] == 'regular') $fonts[$v.'_weight'] = ''; 

				if (isset($value[$fonts[$v]])) 	array_push($value[$fonts[$v]], $fonts[$v.'_weight']); 
				else 							$value[$fonts[$v]] = [$fonts[$v.'_weight']];
			}

		return $value;
	}
}

