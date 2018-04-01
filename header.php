<?php 


// IF PAGE IS MOVED
global $post;
if ($post) {
	$meta = get_post_meta($post->ID, 'emredirect');
	if (isset($meta[0])) {
		$url = esc_url($meta[0]);

		if ($url) {
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: $url");
			exit();
		}
	}
}
// echo $meta[0];

/* setting cookies and inserting ajax for email logging to database if email not yet set */
$logger = Emtheme_Logger::get_instance();
$logger->welcome_user();

$mobile = wp_is_mobile();

// printing head
echo '<!DOCTYPE html><html lang="no"><head>';
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
echo '<meta name="theme-color" content="#317EFB"/>';
echo '<link rel="preconnect" href="https://fonts.googleapis.com/">';
echo '<link rel="preconnect" href="https://fonts.gstatic.com/">';
echo '<link rel="preconnect" href="http://static.kredittkort.rocks/">';

wp_head();
echo '</head><body style="opacity: 0">';

// top container 
$top = '<div class="emtop"><div class="emtheme-site-identity">';

$customize = is_customize_preview();
if (get_option('emtheme_logo') || $customize) $top .= '<div class="emtheme-logo-container"><a href="'.esc_url( home_url( '/' ) ).'"><img class="emtheme-logo" src="'.esc_url(get_option('emtheme_logo')).'"></a></div>';

$emtheme_logo_mobile = esc_url(get_option('emtheme_logo_mobile'));
$emtheme_title_mobile = esc_html(get_option('emtheme_title_mobile'));

if ($emtheme_logo_mobile || $customize) $top .= '<div class="emtheme-logo-mobile-container emtheme-mobile"><a class="emtheme-top-link-mobile" href="'.esc_url(home_url('/')).'"><img class="emtheme-logo-mobile" src="'.$emtheme_logo_mobile.'"></a></div>';
if ($emtheme_title_mobile || $customize) $top .= '<div class="emtheme-mobile-title emtheme-mobile"><a class="emtheme-top-link-mobile" href="'.esc_url(home_url('/')).'">'.$emtheme_title_mobile.'</a></div>';

$emtheme_name = esc_html(get_bloginfo('name'));
$emtheme_tagline = esc_html(get_bloginfo('description'));

if ($emtheme_name || $emtheme_tagline || $customize) {
	$top .= '<div class="emtheme-toptext-container"><a class="emtheme-top-link" href="'.esc_url( home_url( '/' ) ).'">';

	if ($emtheme_name || $customize) $top .= '<div class="emtheme-title">'.$emtheme_name.'</div>';
	if ($emtheme_tagline || $customize) $top .= '<div class="emtheme-tagline">'.$emtheme_tagline.'</div>';

	$top .= '</a></div>';
}
echo $top;

if (! $mobile) echo '<div class="emtheme-search-box"></div>';

echo '</div>'; // end of emtheme-site-identitity (logo, title, tagline)

// menu
// hide if option active and user not logged in.
if ( (!get_option('emtheme_shownav')) || current_user_can( 'read' )) {
	$nav = '<div class="nav-container">';

	// mobile activation icon (css hidden on desktop)
	$nav .= '<i class="material-icons emtheme-mobile-icon emtheme-mobile">menu</i>';

	// flexbox for nav items and structured data
	$nav .= '<nav class="nav" itemscope itemtype="http://schema.org/SiteNavigationElement">';

	// page info database retrieval 
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

	$link = is_search() ? '' : get_permalink();

	// adding menu items to html
	foreach ($titles as $key => $value) {
		$is_parent = false;
		$parent_icon = '';

		// css class for top menu item (either .em-nav-parent-container or .em-nav-item)
		$par_link = ' em-nav-flexitem';

		// css class for current page shown.
		$cur_link = '';

		// if (get_permalink() == $value->link)
		if ($link == $value->link) $cur_link = ' em-nav-current';

		// nav item has child pages (sub menu)
		if (sizeof($value->children) > 0) $is_parent = true;

		// adding parent container for submenu
		if  ($is_parent) {
			$nav .= '<div class="em-nav-parent-container'.$par_link.'">';
			$parent_icon = ' em-nav-item-parent';
			$par_link = '';
		}

		// adding nav item
		if ($value->link !== null) 	$nav .= '<div class="em-nav-item'.$par_link.$cur_link.' em-nav-item-top" itemprop="name"><a class="em-nav-lenke'.esc_attr($parent_icon).'" itemprop="url" href="'.esc_url($value->link).'">'.esc_html($value->title).'</a></div>';
		
		// mobile nav item with children does not have a link attr
		else 						$nav .= '<span class="em-nav-item em-nav-lenke'.esc_attr($parent_icon).'">'.esc_html($value->title).'</span>'; // mobile only 

		// adding children and their container
		if ($is_parent) {
			$nav .= '<div class="em-nav-sub-container">';
			foreach ($value->children as $k => $v) {
				$cur_link = '';

				if  ($link == $v['link']) $cur_link = ' em-nav-current';

				$nav .= '<div class="em-nav-item em-nav-subitem'.$cur_link.'"><a class="em-nav-lenke em-nav-sublenke" href="'.esc_url($v['link']).'">'.esc_html($v['title']).'</a></div>';
			}
			$nav .= '</div>';
		}

		// end of .em-nav-parent-container
		if ($is_parent) $nav .= '</div>';
	}

	// end of flexbox
	$nav .= '</nav>';

	// end of .nav-container
	$nav .= '</div>';

	// printing html
	echo $nav;
}
echo '</div>'; // end of emtop