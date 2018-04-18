<?php
/**
 Template Name: Redirct
 Template Post Type: article, page
*/

global $post;
setup_postdata($post);

$colors = get_option('emtheme_color');

$bgc = '#fff';
$fc = '#000';

if (isset($colors['emtop_bg'])) $bgc = $colors['emtop_bg'];
if (isset($colors['emtop_font'])) $fc = $colors['emtop_font'];


?>
<!DOCTYPE html>
<html lang="no">
<head>
<meta name="robots" content="noindex, follow">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
	.html {
		font-size: 62.5%;
	}
	.emtheme-redirect-container {
		margin: 0;
	    position: absolute;
	    top: 30%;
	    left: 50%;
	    transform: translate(-50%, -50%);
		padding: 2rem 5rem;
		text-align: center;
		font-size: 2.6rem;
		border-radius: 10px;
		/*display: block !important;*/
		box-shadow: 1px 1px 2px black;

		background-color: <?php echo esc_html($bgc) ?>;
		color: <?php echo esc_html($fc) ?>;
	}

</style>
<?php
wp_head();
echo '</head><body>';

echo do_shortcode(get_the_content());

wp_reset_postdata();

echo '</body></html>';