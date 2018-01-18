<?php 

final class EmAdmin {

	public static function init() {
		add_theme_support( 'post-thumbnails' );

		add_action('init', array('EmAdmin', 'change_post_object_label'));
		add_action('admin_menu', array('EmAdmin', 'change_post_menu_label'));
		add_action('admin_menu', array('EmAdmin', 'remove_menus'));
		add_filter('manage_pages_columns', array('EmAdmin', 'my_columns'));
		add_action('manage_pages_custom_column',  array('EmAdmin', 'my_show_columns'));
		add_filter('manage_pages_columns', array('EmAdmin', 'my_custom_pages_columns'));


		// add_action('save_post', array('EmAdmin', 'save_meta_em'));
	}

	/* changes admin menu apperance from "post" to "article" */
	public static function change_post_menu_label() {
	    global $menu;
	    global $submenu;
	    $menu[5][0] = 'Article';
	    $submenu['edit.php'][5][0] = 'Articles';
	    $submenu['edit.php'][10][0] = 'New Article';
	    $submenu['edit.php'][15][0] = 'Categories'; // Change name for categories
	    $submenu['edit.php'][16][0] = 'Tags'; // Change name for tags
	    // echo '';
	}

	/* changes apperance in article pages in admin */
	public static function change_post_object_label() {
	        global $wp_post_types;
	        $labels = &$wp_post_types['post']->labels;
	        $labels->name = 'Articles';
	        $labels->singular_name = 'Article';
	        $labels->add_new = 'New Article';
	        $labels->add_new_item = 'New Article';
	        $labels->edit_item = 'Edit Articles';
	        $labels->new_item = 'Article';
	        $labels->view_item = 'View Article';
	        $labels->search_items = 'Search Articles';
	        $labels->not_found = 'No Articles found';
	        $labels->not_found_in_trash = 'No Articles found in Trash';
	}
	
	/* removes comments in admin menu */
	public static function remove_menus(){
	  remove_menu_page( 'edit-comments.php' );          //Comments
	}
	
	/* making menu order sortable */
	public static function my_columns($columns) {

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
	function my_custom_pages_columns( $columns ) {
		unset($columns['comments']);
		return $columns;
	}

	/* save function */
	// public static function save_meta_em($post_id) {
	// 	$metabox = ['emtext', 'emtitle', 'emstrucdata'];
	// 	$metainput = ['showinnav', 'showinmob'];

	// 	foreach ($metabox as $value)
	// 		EmAdmin::helper_save($post_id, $value);
		
	// 	foreach ($metainput as $value) {
	// 		if (isset($_POST[$value]))
	// 			EmAdmin::helper_save($post_id, $value);
	// 		else
	// 			EmAdmin::helper_save($post_id, $value, '');
	// 	}
	// }

	// public static function helper_save($id, $meta, $data = null) {
	// 	if ($data !== null) {
	// 		update_post_meta($id, $meta, sanitize_text_field($data));
	// 	}
	// 	else if (isset($_POST[$meta]))
	// 		update_post_meta($id, $meta, sanitize_text_field($_POST[$meta]));
	// }

}