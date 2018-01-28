<?php 

$html = '<div class="em-footer">';

$html .= '<div class="em-socialmedia-container"><ul class="em-footer-ul">';

if (get_option('em_facebook'))
	$html .= '<li class="em-footer-listitem"><a href="'.esc_url(get_option('em_facebook')).'">Facebook</a></li>';

if (get_option('em_twitter'))
	$html .= '<li class="em-footer-listitem"><a href="'.esc_url(get_option('em_twitter')).'">Twitter</a></li>';

if (get_option('em_google'))
	$html .= '<li class="em-footer-listitem"><a href="'.esc_url(get_option('em_google')).'">Google+</a></li>';

if (get_option('em_youtube'))
	$html .= '<li class="em-footer-listitem"><a href="'.esc_url(get_option('em_youtube')).'">Youtube</a></li>';

$html .= '</ul></div>';

$html .= '<div class="em-contact-container"><ul class="em-footer-ul">';

$html .= get_option('em_epost') ? '<li class="em-footer-listitem em-footer-epost">'.sanitize_text_field(get_option('em_epost')).'</li><li class="em-footer-listitem">&nbsp;</li>' : '';
$html .= get_option('em_avdeling') ? '<li class="em-footer-listitem">v/ '.sanitize_text_field(get_option('em_avdeling')).'</li>' : '';
$html .= get_option('em_selskap') ? '<li class="em-footer-listitem">'.sanitize_text_field(get_option('em_selskap')).'</li>' : '';
// $html .= get_option('em_poststed') ? '<li class="em-footer-listitem">'.sanitize_text_field(get_option('em_poststed')).'</li>' : '';
$html .= get_option('em_poststed') ? '<li class="em-footer-listitem">'.sanitize_text_field(get_option('em_poststed')) : '';
$html .= get_option('em_postnr') ? ' '.sanitize_text_field(get_option('em_postnr')).'</li>' : '';
// $html .= get_option('em_postnr') ? '<li class="em-footer-listitem">'.sanitize_text_field(get_option('em_postnr')).'</li>' : '';
$html .= get_option('em_vei') ? '<li class="em-footer-listitem">'.sanitize_text_field(get_option('em_vei')).'</li>' : '';
$html .= get_option('em_land') ? '<li class="em-footer-listitem">'.sanitize_text_field(get_option('em_land')).'</li>' : '';

$html .= '</ul></div>';


$html .= get_option('em_omoss') ? '<div class="em-aboutus-container">'.preg_replace('/\[p\]/', '<p>', sanitize_text_field(get_option('em_omoss'))).'</div>' : '';


$html .= '</div>';
echo $html;

// global $postid;
// $meta = get_post_meta($postid, 'emstrucdata');
// if (isset($meta[0]) && $meta[0] != '')
// 	echo '<script type="application/ld+json">'.json_encode(json_decode($meta[0])).'</script>';
wp_footer(); ?>
</body></html>