<?php

final class Emtheme_redirect {
	/*singleton*/
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		add_action('init', array($this, 'create_cpt'));

		add_action('add_meta_boxes_redir', array($this, 'create_meta'));
		add_action('save_post', array($this, 'save'));

	}

	public function create_cpt() {
		$plur = 'Redir Pages';
		$sing = 'Redir Page';
	
		$labels = array(
			'name'               => __( $plur, 'text-domain' ),
			'singular_name'      => __( $sing, 'text-domain' ),
			'add_new'            => _x( 'Add New '.$sing, 'text-domain', 'text-domain' ),
			'add_new_item'       => __( 'Add New '.$sing, 'text-domain' ),
			'edit_item'          => __( 'Edit '.$sing, 'text-domain' ),
			'new_item'           => __( 'New '.$sing, 'text-domain' ),
			'view_item'          => __( 'View '.$sing, 'text-domain' ),
			'search_items'       => __( 'Search '.$plur, 'text-domain' ),
			'not_found'          => __( 'No '.$plur.' found', 'text-domain' ),
			'not_found_in_trash' => __( 'No '.$plur.' found in Trash', 'text-domain' ),
			'parent_item_colon'  => __( 'Parent '.$sing.':', 'text-domain' ),
			'menu_name'          => __( $plur, 'text-domain' ),
		);
	
		$args = array(
			'labels'              => $labels,
			'hierarchical'        => false,
			'description'         => 'description',
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => null,
			'menu_icon' => 'dashicons-format-gallery',
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'has_archive'         => false,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => true,
			'capability_type'     => 'post',
			'supports'            => array(
				'title',
				// 'editor',
				// 'author',
				// 'thumbnail',
				// 'excerpt',
				// 'custom-fields',
				// 'trackbacks',
				// 'comments',
				// 'revisions',
				// 'page-attributes',
				// 'post-formats'
			),
		);

		// register_post_type('redir', $args);
	}


	public function create_meta() {
		add_meta_box(
			'redir_link',
			'Link to which to redirect to',
			array($this, 'link_callback'),
			'redir'
		);
	}

	public function link_callback($post) {
		wp_nonce_field('em'.basename(__FILE__), 'em_nonce');

		$redir = get_post_meta($post->ID, 'emredir');

		if (isset($redir[0])) $redir = $redir[0];
		else 				 $redir = '';

		$redirname = get_post_meta($post->ID, 'emredirname');

		if (isset($redirname[0])) $redirname = $redirname[0];
		else 				 $redirname = '';


		echo '<p>URL:<br><input type="url" name="emredir" style="width: 500px" value="'.esc_attr($redir).'"></p>';
		echo '<p>Display Name:<br><input type="text" name="emredirname" style="width: 500px" value="'.esc_attr($redirname).'"></p>';
	}

	public function save($post_id) {
		// post type is emkort
		if (!get_post_type($post_id) == 'emkort') return;

		// is on admin screen
		if (!is_admin()) return;

		// user is logged in and has permission
		if (!current_user_can('edit_posts')) return;

		// nonce is sent
		if (!isset($_POST['em_nonce'])) return;

		// nonce is checked
		if (!wp_verify_nonce($_POST['em_nonce'], 'em'.basename(__FILE__))) return;

		if (isset($_POST['emredir'])) update_post_meta($post_id, 'emredir', esc_url($_POST['emredir']));
		if (isset($_POST['emredirname'])) update_post_meta($post_id, 'emredirname', sanitize_text_field($_POST['emredirname']));

		// data is sent, then sanitized and saved
		// if (isset($_POST['emdata'])) update_post_meta($post_id, 'em_data', $this->sanitize($_POST['emdata']));
		// if (isset($_POST['emkort_sort'])) update_post_meta($post_id, 'emkort_sort', $this->sanitize($_POST['emkort_sort']));

	}
}