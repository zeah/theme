<?php 

function addingStyleResource() {
	wp_enqueue_style('style', get_stylesheet_uri(), array(), null, '(min-width: 1000px)');
	wp_enqueue_style('style-mobile', get_stylesheet_directory_uri() . '/style-mobile.css', array(), null, '(max-width: 1000px)');
}
add_action('wp_enqueue_scripts', 'addingStyleResource');


function remove_menus(){
  
  // remove_menu_page( 'index.php' );                  //Dashboard
  // remove_menu_page( 'jetpack' );                    //Jetpack* 
  remove_menu_page( 'edit.php' );                   //Posts
  // remove_menu_page( 'upload.php' );                 //Media
  // remove_menu_page( 'edit.php?post_type=page' );    //Pages
  remove_menu_page( 'edit-comments.php' );          //Comments
  // remove_menu_page( 'themes.php' );                 //Appearance
  // remove_menu_page( 'plugins.php' );                //Plugins
  // remove_menu_page( 'users.php' );                  //Users
  // remove_menu_page( 'tools.php' );                  //Tools
  // remove_menu_page( 'options-general.php' );        //Settings
  
}
add_action( 'admin_menu', 'remove_menus' );


add_filter('manage_pages_columns', 'my_columns');

function my_columns($columns) {

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

add_action('manage_pages_custom_column',  'my_show_columns');

function my_show_columns($name) {
    global $post;

    switch ($name) {
        case 'menu_order':
            $views = $post->menu_order;
            echo $views;
            break;
    }
}


function my_custom_pages_columns( $columns ) {

	/** Add a Thumbnail Column **/
	// $myCustomColumns = array(
	// 	'thumbnail' => __( 'Thumbnail', 'Aternus' )
	// );
	// $columns = array_merge( $columns, $myCustomColumns );

	/** Remove a Author, Comments Columns **/
	unset(
		// $columns['author'],
		$columns['comments']
	);

	return $columns;
}
add_filter( 'manage_pages_columns', 'my_custom_pages_columns' );


function register_meta_box_show_in_nav() {
    add_meta_box( 'meta-box-id', 'Navigation', 'show_in_nav_callback', 'page', 'side' );
}
add_action( 'add_meta_boxes', 'register_meta_box_show_in_nav' );


function show_in_nav_callback() {

	global $post;

	$meta = get_post_meta($post->ID, 'showinnav');

	if (isset($meta[0]) && $meta[0] == 1)
		echo '<input type="checkbox" id="showinnav" name="showinnav" checked><label for="showinnav">Don\'t show in Navigation Menu</label>';
	else
		echo '<input type="checkbox" id="showinnav" name="showinnav"><label for="showinnav">Don\'t show in Navigation Menu</label>';

}

add_action('save_post', 'save_showinnav');

function save_showinnav($post_id) {

	$save = false;

	if (isset($_POST['showinnav']))
		$save = true;

	update_post_meta($post_id, 'showinnav', $save);
}


function my_mce_buttons_2( $buttons ) {	
	/**
	 * Add in a core button that's disabled by default
	 */
	$buttons[] = 'superscript';
	$buttons[] = 'subscript';

	return $buttons;
}
add_filter( 'mce_buttons_2', 'my_mce_buttons_2' );

add_theme_support( 'post-thumbnails' );