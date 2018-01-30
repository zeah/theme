<?php 
$html = '<div class="em-footer"><div class="em-inner-footer">';

// social media column
if (get_option('em_social_active')) {
	$socmeta = ['em_facebook', 'em_twitter', 'em_google', 'em_youtube'];
	$meta = [];
	foreach ($socmeta as $value) {
		if (get_option($value))
			$meta[$value] = esc_url(get_option($value));
	}

	if (sizeof($meta) > 0) {
		$html .= '<div class="em-socialmedia-container"><ul class="em-footer-ul">';

		foreach ($meta as $key => $value) 
			$html .= '<li class="em-footer-listitem"><a class="em-footer-link" href="'.$value.'">'.preg_replace('/.*_/', '', $key).'</a></li>';

		$html .= '</ul></div>';
	}
}
// contact column
if (get_option('em_contact_active')) {
	$conmeta = ['em_epost', 'em_avdeling', 'em_selskap', 'em_poststed', 'em_postnr', 'em_vei', 'em_land'];
	$meta = [];
	foreach ($conmeta as $value)
		if (get_option($value))
			$meta[$value] = sanitize_text_field(get_option($value));

	if (sizeof($meta) > 0) {
		$html .= '<div class="em-contact-container"><ul class="em-footer-ul">';

		foreach($meta as $key => $value)
			$html .= '<li class="em-footer-listitem'.(($key == 'em_epost') ? ' em-footer-epost' : '').(($key == 'em_poststed' || $key == 'em_postnr') ? ' em-footer-post' : '').'">'.$value.'</li>';

		$html .= '</ul></div>';
	}
}

// about us column
if (get_option('em_omoss_active'))
	$html .= get_option('em_omoss') ? '<div class="em-aboutus-container">'.preg_replace('/\[p\]/', '<p>', sanitize_text_field(get_option('em_omoss'))).'</div>' : '';


$html .= '</div></div>';

if (get_option('em_social_active') || get_option('em_contact_active') || get_option('em_omoss_active'))
	echo $html;

wp_footer(); ?>
</body></html>