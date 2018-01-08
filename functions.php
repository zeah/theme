<?php 

function addingStyleResource() {
	wp_enqueue_style('style', get_stylesheet_uri(), array(), null, '(min-width: 1000px)');
	wp_enqueue_style('style-mobile', get_stylesheet_directory_uri() . '/style-mobile.css', array(), null, '(max-width: 1000px)');
}
add_action('wp_enqueue_scripts', 'addingStyleResource');
add_theme_support( 'post-thumbnails' );

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

/* NAVIGATIONAL OPTIONS */
function register_meta_box_show_in_nav() {
    add_meta_box( 'navigation-opt', 'Navigation', 'show_in_nav_callback', 'page', 'side' );
}
add_action( 'add_meta_boxes', 'register_meta_box_show_in_nav' );


function show_in_nav_callback() {

	global $post;

	$meta = get_post_meta($post->ID, 'showinnav');

	if (isset($meta[0]) && $meta[0] == 1)
		echo '<input type="checkbox" id="showinnav" name="showinnav" checked><label for="showinnav">Don\'t show in Navigation Menu</label>';
	else
		echo '<input type="checkbox" id="showinnav" name="showinnav"><label for="showinnav">Don\'t show in Navigation Menu</label>';


	$meta = get_post_meta($post->ID, 'showinmob');

	if (isset($meta[0]) && $meta[0] == 1)
		echo '<br><input type="checkbox" id="showinmob" name="showinmob" checked><label for="showinmob">Don\'t show in Mobile Navigation Menu</label>';
	else
		echo '<br><input type="checkbox" id="showinmob" name="showinmob"><label for="showinmob">Don\'t show in Mobile Navigation Menu</label>';
}

add_action('save_post', 'save_meta_em');

function save_meta_em($post_id) {
	$save = false;
	if (isset($_POST['showinnav']))
		$save = true;
	update_post_meta($post_id, 'showinnav', $save);	

	$save = false;
	if (isset($_POST['showinmob']))
		$save = true;
	update_post_meta($post_id, 'showinmob', $save);

	if (isset($_POST['emtext']))
		update_post_meta($post_id, 'emtext', $_POST['emtext']);

	if (isset($_POST['emtitle']))
		update_post_meta($post_id, 'emtitle', $_POST['emtitle']);

	if (isset($_POST['emstrucdata']))
		update_post_meta($post_id, 'emstrucdata', $_POST['emstrucdata']);
}


/* adding meta description */
add_action('add_meta_boxes', 'register_meta_description_box');

function register_meta_description_box() {
	add_meta_box( 'meta-description-opt', 'Meta Description', 'meta_desc_callback', 'page');
}

function meta_desc_callback() {

	global $post;

	$meta = get_post_meta($post->ID, 'emtext');

	if (isset($meta[0]))
		echo '<textarea name="emtext" style="width: 100%; height: 5em" class="em-textarea">'.$meta[0].'</textarea>';
	else
		echo '<textarea name="emtext" style="width: 100%; height: 5em" class="em-textarea"></textarea>';
}


add_action('add_meta_boxes', 'register_title_description');

function register_title_description() {
	 add_meta_box( 'title-opt', 'Page Title', 'title_opt_callback', 'page');
}

function title_opt_callback() {
	global $post;

	$meta = get_post_meta($post->ID, 'emtitle');

	if (isset($meta[0]))
		echo '<input type="text" name="emtitle" style="width: 100%" value="'.$meta[0].'">';
	else
		echo '<input type="text" name="emtitle" style="width: 100%">';
}


add_action('add_meta_boxes', 'register_strucdata_meta');
function register_strucdata_meta() {
	add_meta_box('strucdata', 'Structured data (JSON-LD format)', 'strucdata_callback', 'page');
}

function strucdata_callback() {
	global $post;

	$meta = get_post_meta($post->ID, 'emstrucdata');

	if (isset($meta[0]))
		echo '<textarea style="width: 100%; height: 20em" name="emstrucdata">'.$meta[0].'</textarea>';
	else
		echo '<textarea style="width: 100%; height: 20em" name="emstrucdata"></textarea>';
}

// function my_mce_buttons_2( $buttons ) {	
// 	/**
// 	 * Add in a core button that's disabled by default
// 	 */
// 	$buttons[] = 'superscript';
// 	$buttons[] = 'subscript';

// 	return $buttons;
// }
// add_filter( 'mce_buttons_2', 'my_mce_buttons_2' );

