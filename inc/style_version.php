<?php 


final class Emtheme_style {
	/*
		DEFAULT STYLE
		Number 1

		Site Logo -> title/tagline
		Navbar
		Content
		Footer
	*/
	public static $style_default = '1.1.5';

	/*
		STYLE TWO
		Number 2

		Site logo -> Navbar
					 title/tagline
		Content
		Footer
	*/
	public static $style_one = '1.0.0';

	/*
		STYLE THREE
		Number 3

		Site logo 				-> content
		Site title/tagline 		-> content
		Navbar					-> content
								   footer		
	*/
	public static $style_two = '1.0.0';


	/*
		STYLE MOBILE
	*/
	public static $style_mobile = '1.0.1';


	public static $colors = [
		'top' => [
			'bg' => '#ffffff',
			'font' => '#000000'
		],
		'nav' => [
			'bg' => '#000000',
			'hover' => '#444444',
			'font' => '#ffffff'
		],
		'sub' => [
			'bg' => '#e5e5e5',
			'hover' => '#bababa',
			'font' => '#000000'
		],
		// 'active' => [
		// 	'bg' => '#aa7777',
		// 	'hover' => '#aa4444'
		// ],
		'search' => [
			'color' => '#aaaaaa'
		]

	];
}