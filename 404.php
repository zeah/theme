<?php 

get_header();

echo '<div class="main">
		<div class="page-not-found">Siden du besøker finnes ikke.
		<p><a class="page-not-found-link" href="'.get_site_url().'">Gå tilbake til forsiden</a></div>
		<div class="page-not-found-sidebar">';

dynamic_sidebar('em-notfound-widget');

echo '</div></div>';

get_footer();