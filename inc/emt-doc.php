<?php 

defined( 'ABSPATH' ) or die( 'Blank Space' );

final class Emtheme_Doc {
	/* SINGLETON */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->wp_hooks();
	}

	private function wp_hooks() {
		add_action('admin_menu', array($this, 'add_menu'));
		add_filter('emtheme_doc', array($this, 'add_doc'));
	}

	public function add_menu() {
		add_menu_page('Documentations', 'Documentation', 'manage_options', 'em-documentation-page', array($this, 'doc_callback'), 'none', 161);
	}

	public function doc_callback() {
		$text = apply_filters('emtheme_doc', []);

		// print_r($text);
		echo '<div class="emtheme-doc-container">';

		echo '<ul>';
		foreach($text as $t) {
			echo $t['index'];
		}
		echo '</ul>';

		foreach($text as $t) {
			echo '<h1>'.$t['title'].'</h1>';
			echo $t['title_text'];
			foreach($t['text'] as $te)
				echo '<p>'.$te.'</p>';
		}

		echo '</div>';
	}

	public function add_doc($data) {
		array_push($data, [
			'index' => '<li>
							<a href="#em-theme-header">Theme</a>
							<ul>
								<li><a href="#em-theme-shortcodes">Shortcodes</a>
									<ul>
										<li><a href="#emtheme-doc-col">[col]</a></li>
										<li><a href="#emtheme-doc-box">[box]</a></li>
									</ul>
								</li>
							</ul>
						</li>',

 			'title' => '<span id="em-theme-header">Theme</span>',
			'title_text' => 'hiya',
			'text' => [
				
				'<h2 id="em-theme-shortcodes">Shortcodes</h2>
				 
				 <h3 id="emtheme-doc-col">[col right/center/left]content[/col]</h3>
				 <span>Important: Remember to close the shortcode with [/col] and don\'t have space between shortcode and content. All post content should be included in a [col] shortcode. If not included then each paragraph will be treatet as a column, check the html (text editor) if any bugs arise.</span>
				 <p><strong>right/center/left</strong> will position the content as right always after center and left and center always after left in no-wrapping flex columns.</p>
				 <p>"left" and "right" will have a max width of 250px and have a sticky effect which sticks them to top when scrolling down.</p>
				 <p>"left" has a 20px right margin. "right" has a 20px left margin. "center" has no margins.
				 <p><strong>Example:</strong><br>
				 [col left]Hello[/col][col center]World[/col][col right]![/col] - Will show 3 columns.<br>
				 [col center]content[/col][col right]info[/col] - Will show 2 columns. 
				 
				 <h3 id="emtheme-doc-box">[box name="abc,def,ghi" float="left"/"right" text="Read More" boxsize="25" fontsize="1.6" color="#fff" type="page" columns="2"]</h3>
				 <p><strong>Name</strong> - the slug name of page/post/custom post type. Required.</p>
				 <p><strong>Float</strong> - whether the box floats right or left. If floated, multiple boxes will be vertical. If not floated, then boxes are placed in columns and centered on page.</p>
				 <p><strong>Text</strong> - Text at bottom of box - Default Value: "Les Mer >>>"</p>
				 <p><strong>Boxsize</strong> - Height and width of box set in rem units. 1 rem equals 10 pixels. - Default Value: 25</p>
				 <p><strong>Fontsize</strong> - font size in rem. 1rem equals 10px (1.6 equals 16.) - Default Value: 3.2</p>
				 <p><strong>Color</strong> - font color in hexcode - Default Value: #ffffff (white)</p>
				 <p><strong>Type</strong> - type of post to return. Example: "page, post, nyheter" - Default Value: page. If the page you want to add has type post, you need to add type="post" to shortcode or the name of the custom post type it belongs to.</p>
				 <p><strong>Columns</strong> - how many columns non-floated boxes are in. Valid options: 4,3,2,1 - Default Value: 2</p>
				 <p>Font used is same as chosen for navbar.</p>
				 <p><strong>Examples:</strong><br>
				 [box name="page-slug"] - shows one box centered on screen.<br>
				 [box name="page-slug, page-two"] - shows two boxes centered on screen.<br>
				 [box name="page-slug" float="right"] - shows one box, floated to the right. (text written after this shortcode will be on the box\'s left side.)<br>
				 [box name="page-slug, page-two, page-news, page-about, page-go" color="#f00" fontsize="3.6" columns="3" type="page, post"] will show 5 boxes with max 3 boxes on each line and with red text sized 36px from retrieved pages and post.
				 </p>

				 <h3 id="emtheme-doc-redirect">[redirect time="2" url=""]text[/redirect]</h3>
				 <span>Use page template: Redirect to get the page design. If page has redirect template chosen or "redirect" as category, then it will not be added to sitemap.xml nor shown in on-site search.</span>
				 <p><strong>time</strong> - how long until the page will start the redirection. Default Value: 2 (note: time does not include the time between redirecting and waiting for next page to be connected to.)</p>
				 <p><strong>url</strong> - the url of which to connect to via the redirect shortcode.</p>
				 <p><strong>text</strong> - the text to be shown on the page above the manual link.</p>
				', 

				'paragraph two'

			],

		]);

		return $data;
	}
}