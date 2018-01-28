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
		add_filter('manage_pages_columns', array('EmAdmin', 'my_custom_pages_columns'));

		add_action('widgets_init', array('EmAdmin', 'register_widget'));
	}

	
	/* removes comments in admin menu */
	public static function remove_menus(){
	  	remove_menu_page( 'edit-comments.php' );          //Comments
  		remove_menu_page( 'edit.php' );                   //Posts
	}
	
	/* making menu order sortable */
	public static function my_columns($columns) {

		$screen = get_current_screen();
		if ($screen->id != 'edit-page')
			return $columns;

		$link = get_admin_url().'edit.php?post_type=page&orderby=menu_order&order=';

		if ($_GET['orderby'] == 'menu_order') {
			if ($_GET['order'] == 'asc')
				$link .= 'desc';
			else
				$link .= 'asc';
		}
		else 
			$link .= 'desc';		

	    $columns['menu_order'] = '<a href="'.$link.'">Order</a>';
	    return $columns;
	}

	/* filter for adding menu order to page columns */
	public static function my_show_columns($name) {
	    global $post;

	    switch ($name) {
	        case 'menu_order':
	            $views = $post->menu_order;
	            echo $views;
	            break;
	    }
	}

	/* remove comments column */
	public static function my_custom_pages_columns( $columns ) {
		unset($columns['comments']);
		return $columns;
	}

	public static function register_widget() {
		register_sidebar(array(
			'name' => 'logo',
			'id' => 'emtheme-logo'
		));
	}

}