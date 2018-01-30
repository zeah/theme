<?php 

date_default_timezone_set('Europe/Oslo');

if (get_option('em_forside_active') && get_post(get_option('em_forside_id'))) {
	$meta = 'em_'.strtolower(date('l'));

	if (get_option($meta))
		if (get_option($meta.'_time_start') <= date('H'))
			if (get_option($meta.'_time_end') == '00' || get_option($meta.'_time_end') > date('H')) {
				global $post;
				$post = get_post(get_option('em_forside_id'));
				setup_postdata($post);
				get_template_part('index');
				wp_reset_postdata();
			}
			else
				get_template_part('index');
}
else 
	get_template_part('index');