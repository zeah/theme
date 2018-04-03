<?php

global $post;
setup_postdata($post);

$redir = get_post_meta($post->ID, 'emredir');
$colors = get_option('emtheme_color');

$bgc = '#fff';
$fc = '#000';

if (isset($colors['emtop_bg'])) $bgc = $colors['emtop_bg'];
if (isset($colors['emtop_font'])) $fc = $colors['emtop_font'];

if (isset($redir[0])) $redir = $redir[0];
else 				  $redir = false;	

?>
<!DOCTYPE html>
<html lang="no">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php 
if ($redir) echo '<meta http-equiv="refresh" content="2; url='.esc_url($redir).'">';
?>
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


$redirname = get_post_meta($post->ID, 'emredirname');
if (isset($redirname[0])) $redirname = $redirname[0];
else 					  $redirname = '';

$html = '<div class="emtheme-redirect-container">';

if (get_option('emtheme_logo')) $html .= '<img src="'.esc_url(get_option('emtheme_logo')).'"><br>';

$html .= 'Du vil nå bli videresendt til '.sanitize_text_field($redirname).'<br><a style="font-size: 1.6rem" href="'.esc_url($redir).'">Link</a>';

$html .= '</div>';

echo $html;
// echo '<div class="emtheme-redirect-container emtop emtheme-top-link">Du vil nå bli videresendt til '.sanitize_text_field($redirname).'<br><a style="font-size: 1.6rem" href="'.$redir.'">Link</a></div>';


wp_reset_postdata();

echo '</body></html>';