<?php 

// printing head
echo '<html><head>';
wp_head();
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
echo '</head><body>';

// show_admin_bar(true);

// start of top menu html element
$nav = '<div class="nav">';

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
		if (wp_is_mobile() && !$mobile_added) {
			$mobile_added = true;
			$item->link = null;
			array_push($item->children, ['Alle '.$item->title, get_page_link($value->post_parent)]);
		}

		// adding child to layout
		array_push($item->children, [$value->post_title, get_page_link($value->ID)]);
	}
}

// print_r($titles[2256]);

// adding html
foreach ($titles as $key => $value) {
	// $link = get_page_link($value->ID);
	if ($value->link != null)
		$nav .= '<div class="nav-item"><a href="'.$value->link.'" class="menu-lenke">'.$value->title.'</a>';
	else
		$nav .= '<div class="nav-item nav-mobile-item"><span class="menu-lenke">'.$value->title.'</span>';

	if (sizeof($value->children) > 0) {
		$nav .= '<div class="nav-dropdown">';
		foreach ($value->children as $k => $v)
			$nav .= '<div class="nav-item"><a href="'.$v[1].'" class="menu-lenke menu-dropdown-lenke">'.$v[0].'</a></div>';
		$nav .= '</div>';
	}

	$nav .= '</div>';
}

// html end of top menu elemenet
$nav .= '</div>';

// printing html
echo $nav;