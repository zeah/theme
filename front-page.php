<?php 

if (get_option('em_admin_maint')) {
	get_header('alt');
	echo '<h1 style="height: 100vh">Site is under maintenace. Try again shortly.</h1>';
	get_footer();
	return;
}

// checking if to use alternate page
if (time_check()) {
	global $post;

	$forside = get_option('em_forside_data');
	$post = get_post($forside['id']);
	setup_postdata($post);
	get_template_part('page-altfrontpage');
	wp_reset_postdata();
}
// else using default front page
else 
	get_template_part('index');

function time_check() {
	// if feature is active
	if ( ! get_option('em_forside_active')) 
		return false;

	$forside = get_option('em_forside_data');

	if (!isset($forside['id']) || !get_post($forside['id']))
		return false;

	// setting php time to CET time
	date_default_timezone_set('Europe/Oslo');
	$today = strtolower(date('l'));

	if (!isset($forside[$today]))
		return;	

	if (!($forside[$today.'_ts'] <= date('H')))
		return false;

	if ($forside[$today.'_te'] != '00')
		if (!($forside[$today.'_te'] > date('H')))
			return false;


	// if (!isset($forside[''] || )

	// // if alternate page is valid
	// if ( ! get_post(get_option('em_forside_id')))
	// 	return false;

	// // convert today to meta key
	// $today = 'em_'.strtolower(date('l'));
	
	// // setting php time to CET time
	// date_default_timezone_set('Europe/Oslo');

	// // checks if today is active as alternate page
	// if ( ! (get_option($today)))
	// 	return false;

	// // if after set start time
	// if ( ! (get_option($today.'_time_start') <= date('H')))
	// 	return false;

	// // if before set end time
	// if ( ! (get_option($today.'_time_end') == '00')) // skip if end time is midnight
	// 	if ( ! (get_option($today.'_time_end') > date('H')))
	// 		return false;

	// if current time is set to be alternate page
	return true;
}
