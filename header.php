<?php 


$logger = EmoLogger::get_instance();

$logger->welcome_user();

// echo EmoLogger::table_name();
// if (isset($_COOKIE['bruker'])) { 
// 	if ($wpdb->get_var('select email from wp_em_logger where uniqueid = "'.$_COOKIE['bruker'].'"') == '') {
// 		echo 'no email set yet';	
// 		echo get_template_directory();
// 		// wp_enqueue_script( 'emsearch', get_template_directory().'/assets/email.js', array(), false, true );
// 		enqueue_email_script();
// 		// add_action('wp_enqueue_scripts', 'enqueue_email_script');
// 	}
// 	// echo $_COOKIE['bruker'];
// }
// else {
// // if (! isset($_COOKIE['bruker'])) {
// 	$unique = uniqid();
	
// 	setcookie('bruker', $unique);

// 	global $wpdb; // needed?
// 	$table = $wpdb->prefix . EmoLogger::table_name();

// 	$wpdb->insert($table, array(
// 		// 'hit_date' => date('l'),
// 		'ip' => $_SERVER['REMOTE_ADDR'],
// 		'uniqueid' => $unique,
// 		'email' => ''
// 		));
// }

function enqueue_email_script() {
	// wp_enqueue_script( 'emmail', '/wp-content/themes/emtheme2/assets/email.js', array(), false, true );
	wp_enqueue_script( 'emmail', get_template_directory_uri().'/assets/email.js', array(), false, true );
}

$mobile = wp_is_mobile();

if ($mobile) {
	add_filter('wp_footer', array('EmHead', 'add_footer'));
}

final class EmHead {

	public static function add_footer() {
		echo '<script> (function() { 
			var o = document.querySelector(".nav-mobile-top");
			var n = document.querySelector(".nav");
			o.addEventListener("click", function() {
				n.classList.toggle("nav-show");
				this.classList.toggle("nav-active");

				var x = document.querySelectorAll(".em-nav-parent-container > .nav-show");
				for (xx of x)
					xx.classList.remove("nav-show");
			});

			var m = document.querySelectorAll("span.em-nav-item");
			console.dir(m);
			for (var mm of m) 
				mm.addEventListener("click", function() {
					this.nextSibling.classList.toggle("nav-show");
				});
			
			})();
</script>';
	}
} 

// printing head
echo '<!DOCTYPE html><html lang="no"><head>';
wp_head();
echo '<meta name="viewport" content="width=device-width, initial-scale=1"></head><body>';


echo '<div class="emtop"><div class="topstuff-container"><div class="topstuff">';

if ($mobile && is_active_sidebar('emtheme-logo-mobile')) {
	echo '<div class="emtheme-logo-mobile">';
	dynamic_sidebar('emtheme-logo-mobile');
	echo '</div>';
}

elseif (!$mobile && is_active_sidebar('emtheme-logo')) {
	echo '<div class="emtheme-logo">';
	dynamic_sidebar('emtheme-logo');
	echo '</div>';
}

if (!$mobile)
	echo '<div class="topstuff-text"><div class="sitename">'.get_bloginfo( $show = 'name' ).'</div><div class="tagline">'.get_bloginfo( $show = 'description' ).'</div></div>';

echo '</div></div>';

$nav = '<div class="nav-container">';

if ($mobile)
	$nav .= '<div class="nav-mobile-top"></div>';

$nav .= '<nav class="nav" itemscope itemtype="http://schema.org/SiteNavigationElement">';

// parameters for page search in database
$args = [
	'sort_order' => 'desc',
	'sort_column' => 'menu_order',
	'post_status' => 'publish'
];

// helper class for menu layout
class Nav {
	public $title;
	public $link;
	public $children = array();
}


// sorting pages into iterator with correct layout
$titles = array();
foreach (get_pages($args) as $key => $value) {

	// checking if to include page in nav menu
	$meta = get_post_meta($value->ID, 'showinnav');
	if (isset($meta[0]) && $meta[0] == 'on')
		continue;

	// adding to top menu layer to layout
	if ($value->post_parent == 0) {
		$mobile_added = false;
		$item = new Nav();
		$item->title = $value->post_title;
		$item->link = get_page_link($value->ID);

		$titles[$value->ID] = $item;
	}

	// adding as child to top layer
	elseif (isset($titles[$value->post_parent])) {
		$item = $titles[$value->post_parent];

		// if mobile then top child is same as top menu item (as on mobile hover is impossible)
		// (this adds top menu item as a child on mobile devices)
		if ($mobile && !$mobile_added) {
			$mobile_added = true;
			$item->link = null;
			array_push($item->children, ['title' => 'Alle '.$item->title, 'link' => get_page_link($value->post_parent)]);
		}

		// adding child to layout
		array_push($item->children, ['title' => $value->post_title, 'link' => get_page_link($value->ID)]);
	}
}

foreach ($titles as $key => $value) {
	$is_parent = false;
	$parent_icon = '';

	if (sizeof($value->children) > 0)
		$is_parent = true;

	if  ($is_parent) {
		$nav .= '<div class="em-nav-parent-container">';
		$parent_icon = ' em-nav-item-parent';
	}

	if ($value-> link !== null)
		$nav .= '<div class="em-nav-item" itemprop="name"><a class="em-nav-lenke'.$parent_icon.'" itemprop="url" href="'.$value->link.'">'.$value->title.'</a></div>';
	else
		$nav .= '<span class="em-nav-item em-nav-lenke'.$parent_icon.'">'.$value->title.'</span>';

	if ($is_parent) {
		$nav .= '<div class="em-nav-sub-container">';
		foreach ($value->children as $k => $v)
			$nav .= '<div class="em-nav-item em-nav-subitem"><a class="em-nav-lenke em-nav-sublenke" href="'.$v['link'].'">'.$v['title'].'</a></div>';
		$nav .= '</div>';
	}

	if ($is_parent)
		$nav .= '</div>';
}

$nav .= '</nav>';

$nav .= '</div>'; // end of nav-container
$nav .= '</div>'; // end of emtop

// printing html
echo $nav;