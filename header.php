<?php 


// IF PAGE IS MOVED
global $post;
$meta = get_post_meta($post->ID, 'emredirect');
if (isset($meta[0])) {
	$url = esc_url($meta[0]);

	if ($url) {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: $url");
		exit();
	}
}
// echo $meta[0];

/* setting cookies and inserting ajax for email logging to database if email not yet set */
$logger = Emtheme_Logger::get_instance();
$logger->welcome_user();

$mobile = wp_is_mobile();

if ($mobile) {
	if (! get_option('emtheme_shownav'))
		add_filter('wp_footer', array('Emtheme_Head', 'add_footer'));
}

final class Emtheme_Head {

	/* javascript for mobile menu */
	public static function add_footer() {
		echo '<script> (function() { 
			var o = document.querySelector(".emtheme-mobile-icon");
			var n = document.querySelector(".nav");
			o.addEventListener("click", function() {
				n.classList.toggle("nav-show");
				this.classList.toggle("nav-active");

				var x = document.querySelectorAll(".em-nav-parent-container > .nav-show");
				for (xx of x)
					xx.classList.remove("nav-show");
			});

			var m = document.querySelectorAll("span.em-nav-item");
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

// top container 
$top = '<div class="emtop"><div class="topstuff">';

if (get_theme_mod('emtheme_logo'))
	$top .= '<div class="emtheme-logo-container"><img class="emtheme-logo" src="'.esc_url(get_theme_mod('emtheme_logo')).'"></div>';


if (get_theme_mod('emtheme_logo_mobile'))
	$top .= '<div class="emtheme-logo-mobile-container"><img class="emtheme-logo-mobile" src="'.esc_url(get_theme_mod('emtheme_logo_mobile')).'"></div>';

if (get_theme_mod('emtheme_title_mobile'))
	$top .= '<div class="emtheme-mobile-title">'.get_theme_mod('emtheme_title_mobile').'</div>';



$emtheme_name = get_bloginfo('name');
$emtheme_tagline = get_bloginfo('description');

if ($emtheme_name || $emtheme_tagline)
	$top .= '<div class="emtheme-toptext-container">';

if ($emtheme_name)
	$top .= '<div class="emtheme-title">'.$emtheme_name.'</div>';

if ($emtheme_tagline)
	$top .= '<div class="emtheme-tagline">'.$emtheme_tagline.'</div>';

if ($emtheme_name || $emtheme_tagline)
	$top .= '</div>';


echo $top;
// echo '<div class="emtop"><div class="topstuff">';



// if (get_theme_mod('your_theme_logo')) {
// 	echo '<div><img src="'.get_theme_mod('your_theme_logo').'"></div>';
// }
// logo widget for mobile
// if ($mobile && is_active_sidebar('emtheme-logo-mobile')) {
// 	echo '<div class="emtheme-logo-mobile">';
// 	dynamic_sidebar('emtheme-logo-mobile');
// 	echo '</div>';
// }

// // logo widget for non-mobile
// elseif (!$mobile && is_active_sidebar('emtheme-logo')) {
// 	echo '<div class="emtheme-logo">';
// 	dynamic_sidebar('emtheme-logo');
// 	echo '</div>';
// }

// site title and tagline for non-mobile devices
// if (!$mobile)
// 	echo '<div class="topstuff-text"><div class="sitename">'.get_bloginfo( $show = 'name' ).'</div><div class="tagline">'.get_bloginfo( $show = 'description' ).'</div></div>';

// end div for topstuff-container and topstuff 
echo '</div>';

// menu
if (! get_option('emtheme_shownav')) {
	$nav = '<div class="nav-container">';

	$nav .= '<i class="material-icons emtheme-mobile-icon">menu</i>';

	// to hide entire menu for mobile
	// if ($mobile)
	// 	$nav .= '<div class="nav-mobile-top"><i class="material-icons">menu</i></div>';

	// menu items container
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

	// adding menu items to html
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

	// printing html
	echo $nav;
}
echo '</div>'; // end of emtop
