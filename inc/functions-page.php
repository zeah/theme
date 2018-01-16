<?php 

final class EmPage {
	public static function init() {
		add_action('add_meta_boxes', array('EmPage', 'meta_box_nav'));
		add_action('add_meta_boxes', array('EmPage', 'meta_box_description'));
		add_action('add_meta_boxes', array('EmPage', 'meta_box_title'));
		add_action('add_meta_boxes', array('EmPage', 'meta_box_structureddata'));
	}

	/* TO SHOW OR NOT TO SHOW PAGE IN NAVIGATION MENU ON FRONT-END */
	public static function meta_box_nav() {
    	add_meta_box( 'navigation-opt', 'Navigation', array('EmPage', 'meta_box_nav_html'), 'page', 'side' );
	}
	public static function meta_box_nav_html() {
		$inputs = ['Navigation' => 'showinnav', 'Mobile Navigation' => 'showinmob'];
		
		$html = '';
		foreach ($inputs as $key => $value) 
			$html .= '<input type="checkbox" id="'.$value.'" name="'.$value.'"'.(getmeta($value) ? 'checked' : '').'><label for="'.$value.'">Don\'t show in '.$key.' Menu</label><br>';
		echo $html;
	}

	/* <META> DESCRIPTION META BOX */
	public static function meta_box_description() {
		add_meta_box( 'meta-description-opt', 'Meta Description', array('EmPage', 'meta_box_desc_html'), array('page', 'post'), 'advanced', 'high');
	}
	public static function meta_box_desc_html() {
		echo '<textarea name="emtext" style="width: 100%"; height: 5em">'.getmeta('emtext').'</textarea>';
	}

	/* <TITLE> META BOX */
	public static function meta_box_title() {
		 add_meta_box( 'title-opt', 'Page Title', array('EmPage', 'meta_box_title_html'), array('page', 'post'), 'advanced', 'high');
	}
	public static function meta_box_title_html() {
		echo '<input type="text" name="emtitle" style="width: 100%" value="'.getmeta('emtitle').'">';
	}

	/* <SCRIPT> JSON-LD structured data (used in footer.php) */
	public static function meta_box_structureddata() {
		add_meta_box('strucdata', 'Structured data (JSON-LD format)', array('EmPage', 'meta_box_structureddata_html'), array('page', 'post'), 'advanced', 'high');
	}
	public static function meta_box_structureddata_html() {
		echo '<textarea style="width: 100%; height: 20em;" name="emstrucdata">'.getmeta('emstrucdata').'</textarea>';
	}

}