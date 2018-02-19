<?php 
/*
	Admin Menu Items and Modifying Subpages
*/

require_once 'options/emoption-frontpage.php';
require_once 'options/emoption-contact.php';
require_once 'options/emoption-logger.php';
require_once 'options/emoption-admin.php';

final class EmAdmin {
	/* SINGLETON */
	private static $instance = null;
	public static function get_instance($activate = true) {

		if (self::$instance === null)
			self::$instance = new self($activate);

		return self::$instance;
	}

	private function __construct($activate = true) {
		if ( (! $activate) || (! current_user_can('read')) )
			return;
		$this->wp_hooks();
	}

	private function wp_hooks() {
		/* removes admin menu items */
		add_action( 'admin_menu', array($this, 'remove_menus') );
		
		/* add "order" column to page list page */
		add_filter( 'manage_pages_columns', array($this, 'add_column') );

		/* removes comment column in page list page */
		add_filter( 'manage_pages_columns', array($this, 'remove_columns') );

		/* populates the custom columns */
		add_action( 'manage_pages_custom_column', array($this, 'populate_columns') );

		/* makes custom column sortable */
		add_filter( 'manage_edit-page_sortable_columns', array($this, 'sorting_column') );

		/* adds theme page to admin menu */
		add_action('admin_menu', array($this, 'add_emtheme_menu'));
		EmoFrontpage::get_instance();
		EmoContact::get_instance();
		EmoLogger::get_instance();
		EmoptionAdmin::get_instance();

	}

	/* removes admin menu items */
	public function remove_menus() {
		// removes Comments from admin menu
		remove_menu_page('edit-comments.php');

		// removes Posts from admin menu
		remove_menu_page('edit.php');
	}

	/* add "order" column to page list page */
	public function add_column($columns) {
		$columns['menu_order'] = 'Order';
		return $columns;
	}

	/* removes comment column in page list page */
	public function remove_columns($columns) {
		unset($columns['comments']);
		return $columns;
	}

	/* populates the custom columns */
	public function populate_columns($name) {
		global $post;

		// populates the "order" column
		if ($name == 'menu_order')
            echo $post->menu_order;
	}

	/* makes custom column sortable */
	public function sorting_column($columns) {
		$columns['menu_order'] = 'menu_order';
		return $columns;
	}

	/* adds theme page to admin menu */
	public function add_emtheme_menu() {
		add_menu_page('EmTheme Options', 'EM Theme', 'manage_options', 'em-options-page', array($this, 'emtheme_callback'), '', 61);
	}

	public function emtheme_callback() {
		echo '<div><h1>EM Theme Options</h1></div>';
		echo '<form action="options.php" method="POST">';
		settings_fields('em_options_admin');
		do_settings_sections('em-admin-page');
		submit_button('save');
		echo '</form>';
	}

	// public static function init() {
	// 	// add_theme_support( 'post-thumbnails' );

	// 	add_action('admin_menu', array('EmAdmin', 'remove_menus'));


	// 	add_filter('manage_pages_columns', array('EmAdmin', 'my_columns'));
	// 	add_action('manage_pages_custom_column',  array('EmAdmin', 'my_show_columns'));
	// 	add_filter('manage_edit-page_sortable_columns', array('RegEmArt', 'sort_column'));
		

	// 	add_filter('manage_pages_columns', array('EmAdmin', 'remove_columns'));

	// 	add_action('widgets_init', array('EmAdmin', 'register_widget'));
	// }

	
	// /* removes comments in admin menu */
	// public static function remove_menus(){
	//   	remove_menu_page( 'edit-comments.php' );          //Comments
 //  		// remove_menu_page( 'edit.php' );                   //Posts
	// }
	
	// /* adding order column and its name */
	// public static function my_columns($columns) {
	//     $columns['menu_order'] = 'Order';
	//     return $columns;
	// }

	// /* filter for adding menu order to page columns */
	// public static function my_show_columns($name) {
	// 	if ($name == 'menu_order') {
	// 	    global $post;
 //            echo $post->menu_order;
	// 	}
	// }

	// /* hook function for front-end ordering by "order" */
	// public static function sort_column($columns) {
	// 	$columns['menu_order'] = 'menu_order';
	// 	return $columns;
	// }

	// /* remove comments column */
	// public static function remove_columns( $columns ) {
	// 	unset($columns['comments']);
	// 	return $columns;
	// }

	// public static function register_widget() {
	// 	register_sidebar(array(
	// 		'name' => 'logo',
	// 		'id' => 'emtheme-logo'
	// 	));

	// 	register_sidebar(array(
	// 		'name' => 'logo mobile',
	// 		'id' => 'emtheme-logo-mobile'
	// 	));
	// }

}