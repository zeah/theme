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
	public static $style_default = '1.1.2';

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
	public static $style_mobile = '1.0.0';


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
			'bg' => '#aaaaaa',
			'hover' => '#777777',
			'font' => '#000000'
		],
		'active' => [
			'bg' => '#aa7777',
			'hover' => '#aa4444'
		]

	];
}