<?php 
/*
	Adding stuff to Admin Menu
*/
final class EmAdmin {

	public static function init() {
		// add_theme_support( 'post-thumbnails' );

		add_action('admin_menu', array('EmAdmin', 'remove_menus'));


		add_filter('manage_pages_columns', array('EmAdmin', 'my_columns'));
		add_action('manage_pages_custom_column',  array('EmAdmin', 'my_show_columns'));
		add_filter('manage_edit-page_sortable_columns', array('EmAdmin', 'sort_column'));
		

		add_filter('manage_pages_columns', array('EmAdmin', 'remove_columns'));

		add_action('widgets_init', array('EmAdmin', 'register_widget'));
	}

	
	/* removes comments in admin menu */
	public static function remove_menus(){
	  	remove_menu_page( 'edit-comments.php' );          //Comments
  		// remove_menu_page( 'edit.php' );                   //Posts
	}
	
	/* adding order column and its name */
	public static function my_columns($columns) {
	    $columns['menu_order'] = 'Order';
	    return $columns;
	}

	/* filter for adding menu order to page columns */
	public static function my_show_columns($name) {
		if ($name == 'menu_order') {
		    global $post;
            echo $post->menu_order;
		}
	}

	/* hook function for front-end ordering by "order" */
	public static function sort_column($columns) {
		$columns['menu_order'] = 'menu_order';
		return $columns;
	}

	/* remove comments column */
	public static function remove_columns( $columns ) {
		unset($columns['comments']);
		return $columns;
	}

	public static function register_widget() {
		register_sidebar(array(
			'name' => 'logo',
			'id' => 'emtheme-logo'
		));

		register_sidebar(array(
			'name' => 'logo mobile',
			'id' => 'emtheme-logo-mobile'
		));
	}

}