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

$colors = get_option('emtheme_color');
$fonts = get_option('emtheme_font');

$style = '<style>';
$style .= '
			.emtheme-title {
				font-family: '.(isset($fonts['title']) ? $fonts['title'] : 'verdana').';
				font-weight: '.((isset($fonts['title_weight']) && $fonts['title_weight'] != 'regular') ? (esc_html(str_replace('italic', '', $fonts['title_weight']))) : '400').';
				font-size: '.(isset($fonts['title_size']) ? $fonts['title_size'].'rem' : '4.6rem').';
			}

			.emtheme-top-link, .emtheme-top-link:visited { 
                color: '.(isset($colors['emtop_font']) ? esc_html($colors['emtop_font']) : Emtheme_style::$colors['top']['font']).'; 
            }

			.emtheme-tagline,
			.main {
				font-family: '.(isset($fonts['standard']) ? $fonts['standard'] : 'arial').';
				font-weight: '.((isset($fonts['standard_weight']) && $fonts['standard_weight'] != 'regular') ? (esc_html(str_replace('italic', '', $fonts['standard_weight']))) : '400').';
				font-size: '.(isset($fonts['standard_size']) ? $fonts['standard_size'].'rem' : '1.6rem').';
			}

			.content > p {
				line-height: '.(isset($fonts['standard_lineheight']) ? esc_html($fonts['standard_lineheight']) : '1.6').';
			}
	
			.menu-container {
				font-family: '.(isset($fonts['nav']) ? $fonts['nav'] : 'arial').';
				font-weight: '.((isset($fonts['nav_weight']) && $fonts['nav_weight'] != 'regular') ? (esc_html(str_replace('italic', '', $fonts['nav_weight']))) : '400').';
				font-size: '.(isset($fonts['nav_size']) ? $fonts['nav_size'].'rem' : '1.8rem').';
			}
	
			.menu ul, ul.menu {
				padding: 0;
				margin: 0 auto;
			}
			.menu li {
				list-style: none;
			}

			@media screen and (min-width: 1024px) {
				.emtop {
					background-color: '.(isset($colors['emtop_bg']) ? esc_html($colors['emtop_bg']) : Emtheme_style::$colors['nav']['bg']).';
				}

				.menu-container { 
					background-color: '.(isset($colors['nav_bg']) ? esc_html($colors['nav_bg']) : Emtheme_style::$colors['nav']['bg']).';
				}
				.menu {
					width: 112rem;
					margin: auto;
				}
				.emtheme-mobile-icon {
					display: none;
				}

			}
			@media screen and (max-width: 1023px) {
				.emtop {
					background-color: '.(isset($colors['nav_bg']) ? esc_html($colors['nav_bg']) : Emtheme_style::$colors['nav']['bg']).';
				}

				.emtheme-mobile-icon {
					color: white;
					font-size: 4.6rem !important;
				}

				.menu-container {
					position: relative;
				}
				.menu {
					display: none;
					position: absolute;
					width: 100vw;
					top: 4.6rem;
					right: 0;
					background-color: '.(isset($colors['nav_bg']) ? esc_html($colors['nav_bg']) : Emtheme_style::$colors['nav']['bg']).';
				}
				.nav-show {
					display: block !important;
				}
				.emtheme-mob-arrow {
					float: right;
					display: inline;
					color: white;
					margin-right: 3rem;
				}
				.emtheme-mob-arrow:after {
					content: " \25bc";
				}
			}
			';

if (!has_nav_menu('header-menu')) {
	$style .= '@media screen and (min-width: 1024px) {
					.menu > ul {
						display: flex;
					}
					.page_item > a { 
						display: block;
						min-width: 12rem;
						padding: 0.5rem 1rem;
						border-right: solid 1px rgba(255,255,255,.5);
						color: '.(isset($colors['nav_font']) ? esc_html($colors['nav_font']) : Emtheme_style::$colors['nav']['font']).';
						text-align: center;
						text-decoration: none;
						white-space: nowrap; 
					}
					.page_item:last-child > a {
						border-right: none;
					}

					.page_item > a:hover {
	                    background-color: '.(isset($colors['nav_bg_hover']) ? esc_html($colors['nav_bg_hover']) : Emtheme_style::$colors['nav']['hover']).'; 
					}

					.children {
						display: none;
						position: absolute;
						background-color: '.(isset($colors['navsub_bg']) ? esc_html($colors['navsub_bg']) : Emtheme_style::$colors['sub']['bg']).'; 
					}


					.page_item_has_children > a:after {
						content: " \25bc";
						font-size: 1.4rem;
					}
					.page_item_has_children:hover > .children {
						display: block;
					}
					.children > .page_item > a {
		                padding: 0.5rem 1rem; 
		                color: '.(isset($colors['navsub_font']) ? esc_html($colors['navsub_font']) : Emtheme_style::$colors['sub']['font']).';
					}

					.children > .page_item > a:hover {
	                    background-color: '.(isset($colors['navsub_bg_hover']) ? esc_html($colors['navsub_bg_hover']) : Emtheme_style::$colors['sub']['hover']).'; 
					}

					.current_page_item > a {
	                    background-color: '.(isset($colors['active']) ? esc_html($colors['active']) : Emtheme_style::$colors['active']['bg']).'; 
					}

					.current_page_item > a:hover {
						background-color: '.(isset($colors['active_hover']) ? esc_html($colors['active_hover']) : Emtheme_style::$colors['active']['hover']).' !important; 
					}
				}
				@media screen and (max-width: 1023px) {
					.page_item {
						display: inline-block;
						width: 100%;
						margin: 2rem 0;
					}
					.page_item > a {
						min-width: 12rem;
						padding: 0.5rem 3rem;
						color: '.(isset($colors['nav_font']) ? esc_html($colors['nav_font']) : Emtheme_style::$colors['nav']['font']).';
						text-decoration: none;
						text-align: left;
						white-space: nowrap;
					}

					/*.page_item_has_children > a:after {
						content: " \25bc";
						font-size: 1.4rem;
					}*/

					.children {
						display: none;
						background-color: '.(isset($colors['navsub_bg']) ? esc_html($colors['navsub_bg']) : Emtheme_style::$colors['sub']['bg']).'; 
					}
					.children > .page_item > a {
		                padding: 0.5rem 1rem; 
		                color: '.(isset($colors['navsub_font']) ? esc_html($colors['navsub_font']) : Emtheme_style::$colors['sub']['font']).';
					}

					.children > .page_item > a:hover {
	                    background-color: '.(isset($colors['navsub_bg_hover']) ? esc_html($colors['navsub_bg_hover']) : Emtheme_style::$colors['sub']['hover']).'; 
					}

					.current_page_item > a {
	                    background-color: '.(isset($colors['active']) ? esc_html($colors['active']) : Emtheme_style::$colors['active']['bg']).'; 
					}

					.current_page_item > a:hover {
						background-color: '.(isset($colors['active_hover']) ? esc_html($colors['active_hover']) : Emtheme_style::$colors['active']['hover']).' !important; 
					}

				}
				';
}
else {
	$style .= '@media screen and (min-width: 1024px) {
				.menu {
					display: flex;
					padding: 0;
				}
				.menu-item > a { 
					display: block;
					min-width: 12rem;
					padding: 0.5rem 1rem;
					border-right: solid 1px rgba(255,255,255,.5);
					color: '.(isset($colors['nav_font']) ? esc_html($colors['nav_font']) : Emtheme_style::$colors['nav']['font']).';
					text-decoration: none;
					text-align: center;
					white-space: nowrap; 
				}
				.menu-item:last-child > a {
					border-right: none;
				}
				.menu-item > a:hover {
	                background-color: '.(isset($colors['nav_bg_hover']) ? esc_html($colors['nav_bg_hover']) : Emtheme_style::$colors['nav']['hover']).'; 
				}
				.sub-menu {
					display: none;
					position: absolute;
					background-color: '.(isset($colors['navsub_bg']) ? esc_html($colors['navsub_bg']) : Emtheme_style::$colors['sub']['bg']).'; 
				}

				.menu-item-has-children > a:after {
					content: " \25bc";
					font-size: 1.4rem;
				}
				.menu-item-has-children:hover > .sub-menu {
					display: block;
				}
				.sub-menu > .menu-item > a {
	                padding: 0.5rem 1rem; 
	                color: '.(isset($colors['navsub_font']) ? esc_html($colors['navsub_font']) : Emtheme_style::$colors['sub']['font']).';
				}

				.sub-menu > .menu-item > a:hover {
	                background-color: '.(isset($colors['navsub_bg_hover']) ? esc_html($colors['navsub_bg_hover']) : Emtheme_style::$colors['sub']['hover']).'; 
				}

				.current-menu-item > a {
	                background-color: '.(isset($colors['active']) ? esc_html($colors['active']) : Emtheme_style::$colors['active']['bg']).'; 
				}

				.current-menu-item > a:hover {
					background-color: '.(isset($colors['active_hover']) ? esc_html($colors['active_hover']) : Emtheme_style::$colors['active']['hover']).' !important; 
				}
			}
			@media screen and (max-width: 1023px) {
					.menu-item > a {
						display: block;
						min-width: 12rem;
						padding: 1rem;
						border-right: solid 1px rgba(255,255,255,.5);
						color: '.(isset($colors['nav_font']) ? esc_html($colors['nav_font']) : Emtheme_style::$colors['nav']['font']).';
						text-decoration: none;
						text-align: left;
						white-space: nowrap;
					}

					.menu-item-has-children > a:after {
						content: " \25bc";
						font-size: 1.4rem;
					}

					.sub-menu {
						display: none;
						background-color: '.(isset($colors['navsub_bg']) ? esc_html($colors['navsub_bg']) : Emtheme_style::$colors['sub']['bg']).'; 
					}
					.sub-menu > .menu-item > a {
		                padding: 1rem; 
		                color: '.(isset($colors['navsub_font']) ? esc_html($colors['navsub_font']) : Emtheme_style::$colors['sub']['font']).';
					}

					.sub-menu > .menu-item > a:hover {
	                    background-color: '.(isset($colors['navsub_bg_hover']) ? esc_html($colors['navsub_bg_hover']) : Emtheme_style::$colors['sub']['hover']).'; 
					}

					.current-menu-item > a {
	                    background-color: '.(isset($colors['active']) ? esc_html($colors['active']) : Emtheme_style::$colors['active']['bg']).'; 
					}

					.current-menu-item > a:hover {
						background-color: '.(isset($colors['active_hover']) ? esc_html($colors['active_hover']) : Emtheme_style::$colors['active']['hover']).' !important; 
					}

				}
			';
}

$style .= '</style>';
echo $style;


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


// nav menu or a default nav menu of pages
echo '<div class="menu-container"><i class="material-icons emtheme-mobile-icon">menu</i>'.wp_nav_menu( array( 'theme_location' => 'header-menu', 'fallback_cb' => 'default_menu','depth' => 2, 'echo' => false) ).'</div>';
echo '</div>'; // end of emtop


function default_menu() {
	// getting pages to ignore
	$list = get_pages(['meta_key' => 'showinnav', 'meta_value' => 'on']);
	$ignore = [];
	foreach($list as $li)
		array_push($ignore, $li->ID);	

	// returning pages as menu 
	return wp_page_menu(['exclude' => implode(',', $ignore), 'echo' => false, 'depth' => 2]);
}