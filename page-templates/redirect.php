<?php
/**
 Template Name: Redirect
 Template Post Type: page
*/

global $post;
setup_postdata($post);

$colors = get_option('emtheme_color');

$bgc = '#fff';
$fc = '#000';

if (isset($colors['emtop_bg'])) $bgc = $colors['emtop_bg'];
if (isset($colors['emtop_font'])) $fc = $colors['emtop_font'];


get_header();

?><style>
	.html {
		font-size: 62.5%;
	}
	.emtheme-redirect-container {
		margin: 5rem auto;
	    /*position: absolute;*/
	    /*top: 30%;*/
	    /*left: 50%;*/
	    /*transform: translate(-50%, -50%);*/
	    width: 60rem;
		padding: 2rem 5rem;
		text-align: center;
		font-size: 2.6rem;
		border-radius: 10px;
		/*display: block !important;*/
		box-shadow: 1px 1px 2px black;
	}

	.emtheme-lock {
		float: right;
		font-size: 12rem;
	}

	.em-footer {
		position: fixed;
		bottom: 0;
		left: 0;
		right: 0;
	}

</style>
<?php
// wp_head();
// echo '</head><body>';

echo do_shortcode(get_the_content());

wp_reset_postdata();

get_footer();

echo '</body></html>';