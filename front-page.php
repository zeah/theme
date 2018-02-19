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
	$post = get_post(get_option('em_forside_id'));
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

	// if alternate page is valid
	if ( ! get_post(get_option('em_forside_id')))
		return false;

	// convert today to meta key
	$today = 'em_'.strtolower(date('l'));
	
	// setting php time to CET time
	date_default_timezone_set('Europe/Oslo');

	// checks if today is active as alternate page
	if ( ! (get_option($today)))
		return false;

	// if after set start time
	if ( ! (get_option($today.'_time_start') <= date('H')))
		return false;

	// if before set end time
	if ( ! (get_option($today.'_time_end') == '00')) // skip if end time is midnight
		if ( ! (get_option($today.'_time_end') > date('H')))
			return false;

	// if current time is set to be alternate page
	return true;
}
