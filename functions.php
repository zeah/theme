<?php 
// add shortcode for [col left/right/center]
function addingStyleResource() {
	wp_enqueue_style('style', get_stylesheet_uri(), array(), null, '(min-width: 1000px)');
	wp_enqueue_style('style-mobile', get_stylesheet_directory_uri() . '/style-mobile.css', array(), null, '(max-width: 1000px)');
}
add_action('wp_enqueue_scripts', 'addingStyleResource');
add_theme_support( 'post-thumbnails' );

function remove_menus(){
  
  // remove_menu_page( 'index.php' );                  //Dashboard
  // remove_menu_page( 'jetpack' );                    //Jetpack* 
  // remove_menu_page( 'edit.php' );                   //Posts
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



function change_post_menu_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Article';
    $submenu['edit.php'][5][0] = 'Articles';
    $submenu['edit.php'][10][0] = 'New Article';
    $submenu['edit.php'][15][0] = 'Categories'; // Change name for categories
    $submenu['edit.php'][16][0] = 'Tags'; // Change name for tags
    echo '';
}

function change_post_object_label() {
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
add_action( 'init', 'change_post_object_label' );
add_action( 'admin_menu', 'change_post_menu_label' );








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
add_action( 'add_meta_boxes', 'register_meta_box_show_in_nav' );
function register_meta_box_show_in_nav() {
    add_meta_box( 'navigation-opt', 'Navigation', 'show_in_nav_callback', 'page', 'side' );
}

function show_in_nav_callback() {
	$inputs = ['Navigation' => 'showinnav', 'Mobile Navigation' => 'showinmob'];
	
	$html = '';
	foreach ($inputs as $key => $value) {
		$html .= '<input type="checkbox" id="'.$value.'" name="'.$value.'"'.(getmeta($value) ? 'checked' : '').
		'><label for="'.$value.'">Don\'t show in '.$key.' Menu</label><br>';
		// $meta = getmeta($value);
		// $html .= '<input type="checkbox" id="'.$value.'" name="'.$value.'"';
		// if (getmeta($value) == 'on')
		// 	$html .= ' checked';
		// $html .= '><label for="'.$value.'">Don\'t show in '.$key.' Menu</label><br>';
	}

	echo $html;
}

/* SAVING */
add_action('save_post', 'save_meta_em');
function save_meta_em($post_id) {
	$metas = ['showinnav', 'showinmob', 'emtext', 'emtitle', 'emstrucdata'];

	foreach ($metas as $value) {
		helper_save($post_id, $value);
	}
}

function helper_save($id, $meta) {
	if (isset($_POST[$meta]))
		update_post_meta($id, $meta, sanitize_text_field($_POST[$meta]));
}


/* META DESCRIPTION META BOX */
add_action('add_meta_boxes', 'register_meta_description_box');
function register_meta_description_box() {
	add_meta_box( 'meta-description-opt', 'Meta Description', 'meta_desc_callback', 'page', 'advanced', 'high');
}

function meta_desc_callback() {
	// $meta = getmeta('emtext');

	// $html = '<textarea name="emtext" style="width: 100%"; height: 5em">';
	// if ($meta)
	// 	$html .= $meta;
	// $html .= '</textarea>';

	// echo $html;
	echo '<textarea name="emtext" style="width: 100%"; height: 5em">'.getmeta('emtext').'</textarea>';
}

/* CUSTOM TITLE META BOX for <title></title> */
add_action('add_meta_boxes', 'register_title_description');
function register_title_description() {
	 add_meta_box( 'title-opt', 'Page Title', 'title_opt_callback', 'page', 'advanced', 'high');
}

function title_opt_callback() {
	// $meta = getmeta('emtitle');

	// $html = '<input type="text" name="emtitle" style="width: 100%" value="';
	// if ($meta)
	// 	$html .= $meta;
	// $html .= '">';

	// echo $html;
	echo '<input type="text" name="emtitle" style="width: 100%" value="'.getmeta('emtitle').'">';
}

/* STRUCTURED DATA META BOX */
add_action('add_meta_boxes', 'register_strucdata_meta');
function register_strucdata_meta() {
	add_meta_box('strucdata', 'Structured data (JSON-LD format)', 'strucdata_callback', 'page', 'advanced', 'high');
}

function strucdata_callback() {
	echo '<textarea style="width: 100%; height: 20em;" name="emstrucdata">'.getmeta('emstrucdata').'</textarea>';
}


/* helper function for meta boxes */
function getmeta($m) {
	global $post;
	$meta = get_post_meta($post->ID, $m);

	if ( ! isset($meta[0]))
		return '';
		// return false;
	else
		return $meta[0];
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

// add_action( 'publish_post', 'itsg_create_sitemap' );
// add_action( 'publish_page', 'itsg_create_sitemap' );

function itsg_create_sitemap() {

    $postsForSitemap = get_posts(array(
        'numberposts' => -1,
        'orderby' => 'modified',
        'post_type'  => array( 'page' ),
        'order'    => 'DESC'
    ));

    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
    $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    foreach( $postsForSitemap as $post ) {
        setup_postdata( $post );

        $postdate = explode( " ", $post->post_modified );

        $sitemap .= '<url>'.
          '<loc>' . get_permalink( $post->ID ) . '</loc>' .
          '<lastmod>' . $postdate[0] . '</lastmod>' .
          '<changefreq>monthly</changefreq>' .
         '</url>';
      }

    $sitemap .= '</urlset>';

    $fp = fopen( ABSPATH . 'sitemap.xml', 'w' );

    fwrite( $fp, $sitemap );
    fclose( $fp );
}

/* FLEXBOX SHORTCODE */
add_shortcode('col', 'sccolumns_callback');
function sccolumns_callback($atts, $content = null) {
	foreach($atts as $key => $value) {
		if ($value == 'left')
			return '<div class="left">'.$content.'</div>';
		if ($value == 'center')
			return '<div class="center">'.$content.'</div>';
		if ($value == 'right') 
			return '<div class="right">'.$content.'</div>';
	}
	return '<div>'.$content.'</div>';
}

/* ARTICLES SHORTCODE */
add_shortcode('articles', 'emarticles_callback');
function emarticles_callback($atts, $content = null) {
	$args = [
			'post_type' => 'post'
		];
	$query = new WP_Query($args);
	$html = '';
	// $html .= '<div><pre>'.print_r($query, true).'</pre></div>';

	// $html .= '<div>'.sizeof($query->posts).'</div>'; 
	$html .= '<div class="em-articles-container"><ul class="em-articles-list">';
	
	$p = [];
	if ($query->have_posts())
		while ($query->have_posts()) {
			$query->the_post();

			$temp = [
				'title' => get_the_title(),
				'content' => get_the_content(),
				'thumbnail' => get_the_post_thumbnail_url(),
				'excerpt' => get_the_excerpt(),
				'url' => get_permalink()
			];
			array_push($p, $temp);
			// $html .= '<li class="em-articles-listitem">'.get_the_post_thumbnail().'<div class="em-articles-excerpt">'.get_the_excerpt().'</div>';

			// $html .= get_the_content();
		}

	wp_reset_postdata();

	if (sizeof($p) > 0) {
		$html .= '<li class="em-articles-listitem em-articles-first"><a href="'.$p[0]['url'].'"><div><img src="'.$p[0]['thumbnail'].'" class="em-articles-thumbnail"></div><div class="em-articles-excerpt">'.$p[0]['excerpt'].'</div></a>';
		// $html .= '<li class="em-articles-listitem em-articles-first"><div><a href="'.$p[0]['url'].'"><img src="'.$p[0]['thumbnail'].'" class="em-articles-thumbnail"></a></div><div class="em-articles-excerpt"><a href="'.$p[0]['url'].'">'.$p[0]['excerpt'].'</a></div>';
		$html .= '<li class="em-articles-listitem em-articles-widget">widget here';
	}

	for ($i = 1; $i < sizeof($p); $i++) {
		$html .= '<li class="em-articles-listitem"><a class="em-articles-link" href="'.$p[$i]['url'].'"><div><img src="'.$p[$i]['thumbnail'].'" class="em-articles-thumbnail"></div><div class="em-articles-excerpt">'.$p[$i]['excerpt'].'</div></a>';
	}

	$size = sizeof($p) - 1;
	if ($size % 5 == 0)
		$html .= '<li class="em-articles-listitem em-articles-empty">'.$size;

	if ($size % 4 == 0)
		$html .= '<li class="em-articles-listitem em-articles-empty"><li class="em-articles-listitem em-articles-empty">'.$size;
	// if ($size % 4 == 0) {
	// 	$html .= '<li class="em-articles-listitem>';
	// 	$html .= '<li class="em-articles-listitem>';
	// }
	// if ($size % 5 == 0) {
	// 	$html .= '<li class="em-articles-listitem>';
	// }

	$html .= '</ul></div>';
	// $html .= '<div><pre>'.print_r($p, true).'</pre></div>';
	return $html;
}


/* FILTER FOR FLOATING IMAGES */
add_filter('the_content', 'helper_float_image');
function helper_float_image($content) {
	$content = preg_replace('/\[caption.*alignright.*?\]/', '<span class="right-image editor-image">', $content);
	$content = preg_replace('/\[caption.*alignleft.*?\]/', '<span class="left-image editor-image">', $content);
	$content = preg_replace('/\[caption.*aligncenter.*?\]/', '<span class="center-image editor-image">', $content);
	$content = preg_replace('/\[\/caption\]/', '</span>', $content);

	return $content;
}
