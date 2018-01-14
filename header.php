<?php 

// printing head
echo '<!DOCTYPE html><html lang="no"><head>';
wp_head();
echo '<link rel="icon" href="https://kredittkort-listen.no/wp-content/uploads/2016/03/cropped-kredittkort-listen-2-copy-32x32.jpg">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';

global $post;
$meta = get_post_meta($post->ID, 'emtext');
if (isset($meta[0]) && $meta[0] != '')
	echo '<meta name="description" content="'.$meta[0].'">';


$meta = get_post_meta($post->ID, 'emtitle');
if (isset($meta[0]) && $meta[0] != '')
	echo '<title>'.$meta[0].'</title>';
else
	echo '<title>'.get_the_title($post).'</title>';

echo '</head><body>';

// show_admin_bar(true);

// start of top menu html element
// $nav = '<div class="nav-container"><div class="nav">';
$nav = '<div class="nav-container"><nav class="nav" itemscope itemtype="http://schema.org/SiteNavigationElement">';
// $nav = '<div class="nav">';

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

$mobile = wp_is_mobile();
// sorting pages into iterator with correct layout
$titles = array();
foreach (get_pages($args) as $key => $value) {

	// echo '<p>'.get_page_link($value->ID);
	// print_r($value);

	// checking if to include page in nav menu
	$meta = get_post_meta($value->ID, 'showinnav');
	if (isset($meta[0]) && $meta[0] == 1)
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
		if ($mobile && !$mobile_added) {
			$mobile_added = true;
			$item->link = null;
			array_push($item->children, ['title' => 'Alle '.$item->title, 'link' => get_page_link($value->post_parent)]);
		}

		// adding child to layout
		array_push($item->children, ['title' => $value->post_title, 'link' => get_page_link($value->ID)]);
	}
}

// print_r($titles[2256]);

// adding html
foreach ($titles as $key => $value) {

	$parent_icon = '';
	if (sizeof($value->children) > 0)
	$parent_icon = ' menu-lenke-parent';

	if ($value->link != null)
		// $nav .= '<div class="nav-item"><a href="'.$value->link.'" class="menu-lenke'.$parent_icon.'">'.$value->title.'</a>';
		$nav .= '<div class="nav-item"><a itemprop="url" href="'.$value->link.'" class="menu-lenke'.$parent_icon.'"><meta itemprop="name" content="'.$value->title.'">'.$value->title.'</a>';
	else
		$nav .= '<div class="nav-item nav-mobile-item"><span class="menu-lenke'.$parent_icon.'">'.$value->title.'</span>';

	// adding child menu item to html element
	if (sizeof($value->children) > 0) {
		$nav .= '<div class="nav-dropdown">';
		foreach ($value->children as $k => $v)
			$nav .= '<div class="nav-item"><a href="'.$v['link'].'" class="menu-lenke menu-dropdown-lenke">'.$v['title'].'</a></div>';
			$nav .= '<div class="nav-item"><a itemprop="url" href="'.$v['link'].'" class="menu-lenke menu-dropdown-lenke"><meta itemprop="name" content="'.$v['title'].'">'.$v['title'].'</a></div>';
		$nav .= '</div>';
	}

	$nav .= '</div>';
}

// html end of top menu elemenet
// $nav .= '</div>';
$nav .= '</div></div>';
// $nav .= '</nav></div>';

// printing html
echo $nav;