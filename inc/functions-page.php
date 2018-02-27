<?php 

final class Emtheme_Page {
	private static $instance = null;
	public static function get_instance($activate = true) {

		if (self::$instance === null)
			self::$instance = new self($activate);

		return self::$instance;
	}

	private function __construct($activate = true) {
		if ((! $activate))
			return;

		$this->public_wp_hooks();

		if ( (! current_user_can('read')) || (! is_admin()))
			return;

		$this->wp_hooks();
	} 

	private function wp_hooks() {
		add_action('save_post', array($this, 'save_meta'));

		add_action('add_meta_boxes', array($this, 'add_nav'));
		add_action('add_meta_boxes', array($this, 'add_title'));
		add_action('add_meta_boxes', array($this, 'add_description'));
		add_action('add_meta_boxes', array($this, 'add_strucdata'));
		add_action('add_meta_boxes', array($this, 'add_redirect'));

		// add_action('do_meta_boxes', array($this, 'remove_thumbnail'));
		
	}

	private function public_wp_hooks() {
		add_filter('wp_head', array($this, 'add_head'));
		add_filter('wp_footer', array($this, 'add_footer'));
	}

	public function save_meta($post_id) {
		if (! is_admin() )
			return;
		if ( ! current_user_can( 'edit_posts' ))
			return;
		if ( ! isset($_POST['em_nonce']))
			return;
		if ( ! wp_verify_nonce( $_POST['em_nonce'], basename(__FILE__)))
			return;

		$metabox = ['emtext', 'emtitle', 'emstrucdata', 'emredirect'];
		$metainput = ['showinnav', 'showinmob'];

		foreach ($metabox as $value)
			$this->helper_save($post_id, $value);
		
		foreach ($metainput as $value) {
			if (isset($_POST[$value]))
				$this->helper_save($post_id, $value);
			else
				$this->helper_save($post_id, $value, '');
		}
	}
	private function helper_save($id, $meta, $data = null) {
		if ($data !== null) {
			update_post_meta($id, $meta, sanitize_text_field($data));
		}
		else if (isset($_POST[$meta])) {
			if ($meta == 'emstrucdata') {
				$temp = $_POST[$meta];
				$temp = preg_replace('/=/', '', $temp);
				$temp = preg_replace('/function/', '', $temp);
				update_post_meta($id, $meta, sanitize_text_field($temp));
			}
			else
				update_post_meta($id, $meta, sanitize_text_field($_POST[$meta]));
		}
	}

	/* NAVIGATIONAL OPTIONS META BOX*/
	public function add_nav() {
		add_meta_box('navgation-opt', 'Navigation', array($this, 'nav_callback'), 'page', 'side');
	}
	public function nav_callback() {

		$inputs = ['Navigation' => 'showinnav'];
		// $inputs = ['Navigation' => 'showinnav', 'Mobile Navigation' => 'showinmob'];
		
		$html = '';
		foreach ($inputs as $key => $value) 
			$html .= '<input type="checkbox" id="'.$value.'" name="'.$value.'"'.($this->getmeta($value) ? 'checked' : '').'><label for="'.$value.'">Don\'t show in '.$key.' Menu</label><br>';
		echo $html;
	}

	/* <META> DESCRIPTION META BOX */
	public function add_description() {
		add_meta_box( 'meta-description-opt', 'Meta Description', array($this, 'description_callback'), array('page', 'post'), 'advanced', 'high');
	}
	public function description_callback() {
		echo '<textarea name="emtext" style="width: 100%"; height: 5em">'.esc_html($this->getmeta('emtext')).'</textarea>';
	}

	/* <TITLE> META BOX */
	public function add_title() {
		 add_meta_box( 'title-opt', 'Page Title', array($this, 'title_callback'), array('page', 'post'), 'advanced', 'high');
	}
	public function title_callback() {
		wp_nonce_field( basename(__FILE__), 'em_nonce' );
		echo '<input type="text" name="emtitle" style="width: 100%" value="'.esc_attr($this->getmeta('emtitle')).'">';
	}

	/* STRUCTURED DATA META BOX */
	public function add_strucdata() {
		add_meta_box('strucdata', 'Structured data (JSON-LD format)', array($this, 'strucdata_callback'), array('page', 'post'), 'advanced', 'high');
	}
	public function strucdata_callback() {
		if (json_decode($this->getmeta('emstrucdata')))
			echo '<textarea style="width: 100%; height: 20em;" name="emstrucdata">'.json_encode(json_decode($this->getmeta('emstrucdata')), JSON_PRETTY_PRINT).'</textarea>';
		else
			echo '<textarea style="width: 100%; height: 20em;" name="emstrucdata">'.esc_textarea($this->getmeta('emstrucdata')).'</textarea>';
	}

	/* REMOVE THUMBNAIL FROM PAGE */
	public function remove_thumbnail() {
		remove_meta_box('postimagediv', 'page', 'none');
	}

	/* ADD TO HEADER (META DESCRIPTION, TITLE)*/
	public function add_head() {
		echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">';
		global $post;
		if ($post && $post->post_type == 'page') {
			echo $this->getmeta('emtext') ? '<meta name="description" content="'.esc_attr($this->getmeta('emtext')).'">' : '';
			echo $this->getmeta('emtitle') ? '<title>'.esc_html($this->getmeta('emtitle')).'</title>' : '';
		}
	}

	/* ADD TO FOOTER  (STRUCTURED DATA) */
	public function add_footer() {
		global $post;
		if ($post && $post->post_type == 'page') 
			echo json_decode($this->getmeta('emstrucdata')) ? '<script type="application/ld+json">'.json_encode(json_decode($this->getmeta('emstrucdata'))).'</script>' : '';
	}

	/* PERMANENT MOVE META BOX */
	public function add_redirect() {
		add_meta_box( 'meta-redirect-opt', 'Permanent Redirect URL (301)', array($this, 'redirect_callback'), array('page', 'post'), 'advanced');
	}

	public function redirect_callback() {
		echo '<input type="text" size="100" name="emredirect" value="'.esc_attr($this->getmeta('emredirect')).'">';
	}


	/* HELPER FUNCTION FOR RETRIEVING META DATA */
	private function getmeta($m) {
		global $post;
		$meta = get_post_meta($post->ID, $m);

		if (! isset($meta[0]))
			return '';
		else
			return $meta[0];
	}
}