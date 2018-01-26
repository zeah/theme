<?php
/**
*/
get_header(); 


while (have_posts()) {
	the_post();

	$postid = get_the_ID(); // to be used in footer.php

	// $html = '<div class="topstuff"><div class="sitename">'.get_bloginfo( $show = 'name' ).'</div>';
	// $html .= '<div class="tagline">'.get_bloginfo( $show = 'description' ).'</div></div>';
	// $html = '<div class="sitename">'.preg_replace('/.*\/\//', '', get_site_url()).'</div>';

	$html = '<div class="main">';

	if ( ! is_front_page())
		$html .= '<div class="content-title"><h1>'.get_the_title().'</h1></div>';

	// $html .= print_r($query_vars, true);

	$content = get_the_content();

	$html .= '<div class="content';
	if (!(strpos($content, '[col') === false))
		$html .= ' content-3';
	// $html .= '">'.do_shortcode(wpautop(do_shortcode($content))).'</div>';
	$html .= '">'.apply_filters('the_content', get_the_content()).'</div>';


	// $html .= '<span id="tradingview-copyright"><a ref="nofollow noopener" target="_blank" href="http://www.tradingview.com" style="color: rgb(173, 174, 176); font-family: &quot;Trebuchet MS&quot;, Tahoma, Arial, sans-serif; font-size: 13px;">Market Quotes by <span style="color: #3BB3E4">TradingView</span></a></span><script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-market-overview.js">
	// 		{
	// 		  "showChart": true,
	// 		  "locale": "en",
	// 		  "width": "270",
	// 		  "height": "660",
	// 		  "plotLineColorGrowing": "rgba(60, 188, 152, 1)",
	// 		  "plotLineColorFalling": "rgba(255, 74, 104, 1)",
	// 		  "gridLineColor": "rgba(233, 233, 234, 1)",
	// 		  "scaleFontColor": "rgba(218, 221, 224, 1)",
	// 		  "belowLineFillColorGrowing": "rgba(60, 188, 152, 0.05)",
	// 		  "belowLineFillColorFalling": "rgba(255, 74, 104, 0.05)",
	// 		  "symbolActiveColor": "rgba(242, 250, 254, 1)",
	// 		  "tabs": [
	// 		    {
	// 		      "title": "BITCOIN",
	// 		      "symbols": [
	// 		        {
	// 		          "s": "COINBASE:BTCUSD",
	// 		          "d": "BITCOIN"
	// 		        }
	// 		      ]
	// 		    }
	// 		  ]
	// 		}
	// 		</script>';

	// $html .= '<script>document.write("hello")</script>';

	$html .= '</div>';

	echo $html;

}


get_footer();