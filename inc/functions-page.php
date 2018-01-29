<?php 

final class EmPage {
	public static function init() {
		add_action('add_meta_boxes', array('EmPage', 'meta_box_nav'));
		add_action('add_meta_boxes', array('EmPage', 'meta_box_description'));
		add_action('add_meta_boxes', array('EmPage', 'meta_box_title'));
		add_action('add_meta_boxes', array('EmPage', 'meta_box_structureddata'));

		add_action('save_post', array('EmPage', 'save_meta_em'));

		add_filter('wp_head', array('EmPage', 'add_head'));
		add_filter('wp_footer', array('EmPage', 'add_footer'));
	}

	public static function add_head() {
		global $post;
		if ($post->post_type == 'page') {
			echo getmeta('emtext') ? '<meta name="description" content="'.getmeta('emtext').'">' : '';
			echo getmeta('emtitle') ? '<title>'.getmeta('emtitle').'</title>' : '';
		}
	}

	public static function add_footer() {
		global $post;
		if ($post->post_type == 'page') {
			// echo getmeta('emstrucdata') ? '<script type="application/ld+json">'.json_encode(getmeta('emstrucdata')).'</script>' : '';
			
			echo json_decode(getmeta('emstrucdata')) ? '<script type="application/ld+json">'.json_encode(json_decode(getmeta('emstrucdata'))).'</script>' : '';
		}
	}

	/* TO SHOW OR NOT TO SHOW PAGE IN NAVIGATION MENU ON FRONT-END */
	public static function meta_box_nav() {
    	add_meta_box( 'navigation-opt', 'Navigation', array('EmPage', 'meta_box_nav_html'), 'page', 'side' );
	}
	public static function meta_box_nav_html() {
		wp_nonce_field( basename(__FILE__), 'em_nonce' );

		$inputs = ['Navigation' => 'showinnav', 'Mobile Navigation' => 'showinmob'];
		
		$html = '';
		foreach ($inputs as $key => $value) 
			$html .= '<input type="checkbox" id="'.$value.'" name="'.$value.'"'.(getmeta($value) ? 'checked' : '').'><label for="'.$value.'">Don\'t show in '.$key.' Menu</label><br>';
		echo $html;
	}

	/* <META> DESCRIPTION META BOX */
	public static function meta_box_description() {
		add_meta_box( 'meta-description-opt', 'Meta Description', array('EmPage', 'meta_box_desc_html'), array('page', 'post'), 'advanced', 'high');
	}
	public static function meta_box_desc_html() {
		echo '<textarea name="emtext" style="width: 100%"; height: 5em">'.getmeta('emtext').'</textarea>';
	}

	/* <TITLE> META BOX */
	public static function meta_box_title() {
		 add_meta_box( 'title-opt', 'Page Title', array('EmPage', 'meta_box_title_html'), array('page', 'post'), 'advanced', 'high');
	}
	public static function meta_box_title_html() {
		echo '<input type="text" name="emtitle" style="width: 100%" value="'.getmeta('emtitle').'">';
	}

	/* <SCRIPT> JSON-LD structured data (used in footer.php) */
	public static function meta_box_structureddata() {
		add_meta_box('strucdata', 'Structured data (JSON-LD format)', array('EmPage', 'meta_box_structureddata_html'), array('page', 'post'), 'advanced', 'high');
	}
	public static function meta_box_structureddata_html() {
		if (json_decode(getmeta('emstrucdata')))
			echo '<textarea style="width: 100%; height: 20em;" name="emstrucdata">'.json_encode(json_decode(getmeta('emstrucdata')), JSON_PRETTY_PRINT).'</textarea>';
		else
			echo '<textarea style="width: 100%; height: 20em;" name="emstrucdata">'.getmeta('emstrucdata').'</textarea>';
	}

	/* saving page stuff */
	public static function save_meta_em($post_id) {

		if ( ! current_user_can( 'edit_posts' ))
			return;
		if ( ! isset($_POST['em_nonce']))
			return;
		if ( ! wp_verify_nonce( $_POST['em_nonce'], basename(__FILE__)))
			return;

		$metabox = ['emtext', 'emtitle', 'emstrucdata'];
		$metainput = ['showinnav', 'showinmob'];

		foreach ($metabox as $value)
			EmPage::helper_save($post_id, $value);
		
		foreach ($metainput as $value) {
			if (isset($_POST[$value]))
				EmPage::helper_save($post_id, $value);
			else
				EmPage::helper_save($post_id, $value, '');
		}
	}

	public static function helper_save($id, $meta, $data = null) {
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

}