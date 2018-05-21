<?php 

// require_once 'inc/emt-style.php';
require_once 'inc/emt-css.php';

// IF PAGE IS MOVED
global $post;	
// wp_die('<xmp>'.print_r($post, true).'</xmp>');
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

if (get_option('em_admin_maint')) {
	get_header('alt');
	echo '<h1 style="height: 100vh; padding: 30px;">Site is under maintenace. Try again shortly.</h1>';
	get_footer();
	return;
}


// echo $meta[0];

/* setting cookies and inserting ajax for email logging to database if email not yet set */

if (get_option('em_popup_activate')) {
	$logger = Emtheme_Logger::get_instance();
	$logger->welcome_user();
}

// $css = Emtheme_styler::get_instance();
$css = Emtheme_CSS::get_instance();

$mobile = wp_is_mobile();

// printing head
echo '<!DOCTYPE html><html lang="no"><head>';
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
if (get_option('emtheme_mobile_color')) echo '<meta name="theme-color" content="'.sanitize_text_field(get_option('emtheme_mobile_color')).'">';
echo '<link rel="preconnect" href="https://fonts.googleapis.com/">';
echo '<link rel="preconnect" href="https://fonts.gstatic.com/">';
// echo '<link rel="preconnect" href="http://static.kredittkort.rocks/">';

// wp_die(print_r(get_page_template_slug($post->ID), true));


$links = apply_filters('add_google_fonts', []);
// wp_die('<xmp>'.print_r($links, true).'</xmp>');
/* NAVBAR CSS as inline css */
echo $css->get_css();

$out_link = '<link href="https://fonts.googleapis.com/css?family=';
foreach($links as $link => $weights) {
	$out = str_replace(' ', '+', $link);
	// $out .= 'link href="https://fonts.googleapis.com/css?family='.str_replace(' ', '+', $link);

	if (sizeof($weights) > 0) $out .= ':';

	foreach($weights as $weight)
		if ($weight && !strpos($out, $weight)) $out.= $weight.',';

	$out = rtrim($out, ',');
	$out = ltrim($out, '|:');

	$out .= '|';


	$out_link .= $out;
}

$out_link = rtrim($out_link, '|');
$out_link .= '" rel="stylesheet">';
echo $out_link;


wp_head();
echo '</head><body style="opacity: 0">';

// $out_link = 'link href="https://fonts.googleapis.com/css?family=';
// foreach($links as $link => $weights) {
// 	$out_link .= str_replace(' ', '+', $link);
// 	// $out_link .= 'link href="https://fonts.googleapis.com/css?family='.str_replace(' ', '+', $link);

// 	if (sizeof($weights) > 0) $out_link .= ':';

// 	foreach($weights as $weight)
// 		if ($weight) $out_link.= $weight.',';

// 	$out_link = rtrim($out_link, ',');

// 	$out_link .= '|';
// }

// $out_link = rtrim($out_link, '|');
// $out_link .= '" rel="stylesheet"<br>';
// echo $out_link;

// print_r($links);

// top container

// $option = get_option('emtheme_styling');

$nav_layout = get_option('emtheme_nav_layout');

// if ($option == 'two') {
if ($nav_layout == 'one') {
	echo '<div class="emtheme-header-container"><span class="emtheme-header">';

	echo '<a class="emtheme-header-link" href="'.esc_url( home_url( '/' ) ).'">';
	
	if ($mobile) echo '<img class="emtheme-logo" src="'.esc_url(get_option('emtheme_logo_mobile')).'">';

	if (!$mobile) echo '<span class="emtheme-title">'.esc_html(get_bloginfo('name')).($mobile ? esc_html(get_option('emtheme_title_mobile')) : '').'</span>';
	if ($mobile) echo '<span class="emtheme-title">'.esc_html(get_option('emtheme_title_mobile')).'</span>';

	// echo '<span class="emtheme-logo"><img class="emtheme-logo" src="'.esc_url(get_option('emtheme_logo')).'"></span>';
	if (!$mobile) echo '<img class="emtheme-logo" src="'.esc_url(get_option('emtheme_logo')).'">';

	echo '</a>';

	echo get_em_menu();

	echo '</span></div>';
}

else {
	$top = '<div class="emtheme-header-container"><div class="emtheme-header">';

	$top .= '<a class="emtheme-identity" href="'.esc_url(home_url('/')).'">';

	if (!$mobile && get_option('emtheme_logo')) $top .= '<img class="emtheme-logo" src="'.esc_url(get_option('emtheme_logo')).'">';

	if ($mobile && get_option('emtheme_logo_mobile')) $top .= '<img class="emtheme-logo-mobile" src="'.esc_url(get_option('emtheme_logo_mobile')).'">';

	$top .= '<span class="emtheme-title-tagline">';

	if (!$mobile && get_bloginfo('name')) $top .= '<span class="emtheme-title">'.esc_html(get_bloginfo('name')).'</span>';
	if ($mobile && get_option('emtheme_title_mobile')) $top .= '<span class="emtheme-title">'.esc_html(get_option('emtheme_title_mobile')).'</span';

	if (!$mobile && get_bloginfo('description')) $top .= '<span class="emtheme-tagline">'.esc_html(get_bloginfo('description')).'</span>';

	$top .= '</span>';

	$top .= '</a>';

	if (!$mobile) $top .= '<div class="emtheme-search-box"></div>';

	$top .= '</div>';

	$top .= get_em_menu();

	$top .= '</div>';

	echo $top;

	// $top = '<div class="emtop"><div class="emtop-bg"></div><div class="emtheme-site-identity">';

	// $customize = is_customize_preview();
	// if (get_option('emtheme_logo') || $customize) $top .= '<div class="emtheme-logo-container"><a href="'.esc_url( home_url( '/' ) ).'"><img class="emtheme-logo" src="'.esc_url(get_option('emtheme_logo')).'"></a></div>';

	// $emtheme_logo_mobile = esc_url(get_option('emtheme_logo_mobile'));
	// $emtheme_title_mobile = esc_html(get_option('emtheme_title_mobile'));

	// if ($emtheme_logo_mobile || $customize) $top .= '<div class="emtheme-logo-mobile-container emtheme-mobile"><a class="emtheme-top-link-mobile" href="'.esc_url(home_url('/')).'"><img class="emtheme-logo-mobile" src="'.$emtheme_logo_mobile.'"></a></div>';
	// if ($emtheme_title_mobile || $customize) $top .= '<div class="emtheme-mobile-title emtheme-mobile"><a class="emtheme-top-link-mobile" href="'.esc_url(home_url('/')).'">'.$emtheme_title_mobile.'</a></div>';

	// $emtheme_name = esc_html(get_bloginfo('name'));
	// $emtheme_tagline = esc_html(get_bloginfo('description'));

	// if ($emtheme_name || $emtheme_tagline || $customize) {
	// 	$top .= '<div class="emtheme-toptext-container"><a class="emtheme-top-link" href="'.esc_url( home_url( '/' ) ).'">';

	// 	if ($emtheme_name || $customize) $top .= '<div class="emtheme-title">'.$emtheme_name.'</div>';
	// 	if ($emtheme_tagline || $customize) $top .= '<div class="emtheme-tagline">'.$emtheme_tagline.'</div>';

	// 	$top .= '</a></div>';
	// }
	// echo $top;

	// if (! $mobile) echo '<div class="emtheme-search-box"></div>';

	// echo '</div>'; // end of emtheme-site-identitity (logo, title, tagline)


	// // nav menu or a default nav menu of pages
	// // echo '<div class="menu-container"><i class="material-icons emtheme-mobile-icon">menu</i>'.wp_nav_menu( array( 'theme_location' => 'header-menu', 'fallback_cb' => 'default_menu','depth' => 2, 'echo' => false) ).'</div>';
	// echo get_em_menu();
	// echo '</div>'; // end of emtop
}

function get_em_menu() {

	$mob = '<i class="material-icons emtheme-mobile-icon">menu</i>';
	if (!wp_is_mobile()) $mob = '';

	return '<div class="menu-container">'.$mob.wp_nav_menu( array( 'theme_location' => 'header-menu', 'fallback_cb' => 'default_menu','depth' => 2, 'echo' => false) ).'</div>';

}

function default_menu() {
	// getting pages to ignore
	$list = get_pages(['meta_key' => 'showinnav', 'meta_value' => 'on']);
	$ignore = [];
	foreach($list as $li)
		array_push($ignore, $li->ID);	

	// returning pages as menu 
	return wp_page_menu(['exclude' => implode(',', $ignore), 'echo' => false, 'depth' => 2]);
}