(function() {
	var c = document.querySelector('.emtheme-search-box');
	var sh = false;
	var value = '';

	var temp = '';
	// if (location.hostname.includes('localhost')) temp = '/wordpress';
	if (location.hostname.indexOf('localhost') != -1) temp = '/wordpress';

	var url = location.protocol+'//'+location.host+temp;

	// console.log(location.protocol + '//' + location.hostname);

	// var temp = '/wordpress';
	// if (!location.href.includes('http://localhost')) temp = '';

	if (!c) return;

	var f = document.createElement('form');
	f.setAttribute('method', 'get');
	f.setAttribute('role', 'search');
	f.setAttribute('action', url);


	f.setAttribute('autocomplete', 'on');

	var i = document.createElement('input');
	i.classList.add('emtheme-search-input');
	i.setAttribute('type', 'search');
	i.setAttribute('name', 's');
	// i.setAttribute('required', '');

	if (location.search && location.search.indexOf('customize_changeset') == -1) i.setAttribute('value', location.search.substring(3));
	// if (location.search && !location.search.includes('customize_changeset')) i.setAttribute('value', location.search.substring(3));
	// i.setAttribute('autocomplete', 'on');

	if (location.search) i.classList.add('emtheme-search-input-active');

	var b = document.createElement('button');
	b.setAttribute('type', 'submit');
	b.classList.add('emtheme-search-button');

	var go = function(val) { location = location.origin+temp+'/?s='+val; }

	// redo for IE
	// let click = (e) => {
	var click = function(e) {
		if (i.value == '' || !i.value) e.preventDefault();
	
	 	i.classList.add('emtheme-search-input-active');
		i.focus()
	}

	b.addEventListener('click', click);

	// b.addEventListener('click', function() {
	// 	if (i.value) go(i.value);

	// 	i.classList.add('emtheme-search-input-active');
	// 	i.focus();
	// });

	// i.addEventListener('keyup', function(e) {
	// 	if (e.keyCode == 13) go(e.target.value);
	// });

	i.addEventListener('focusout', function() {
		if (!i.value) i.classList.remove('emtheme-search-input-active');
	});

	var s = document.createElement('i');
	s.classList.add('emtheme-search-icon');
	s.classList.add('material-icons');
	s.appendChild(document.createTextNode('search'));

	b.appendChild(s);

	f.appendChild(i);
	f.appendChild(b);

	c.appendChild(f);
})();


/* MOBILE NAV MENU */
(function() {
	var o = document.querySelector(".emtheme-mobile-icon");
	var n = document.querySelector(".menu");
	o.addEventListener("click", function() {
		n.classList.toggle("nav-show");

		var x = document.querySelectorAll(".menu .nav-show");
		for (var xx = 0; xx < x.length; xx++)
			x[xx].classList.remove("nav-show");
	});


	var p = document.querySelectorAll('.page_item_has_children, .menu-item-has-children');

	// finding where to put it (complicated because of custom menu adds invisible text child after every child)
	for (var i = 0; i < p.length; i++) {
		var a = null;

		for (var j = 0; j < p[i].childNodes.length; j++)
			if (p[i].childNodes[j].tagName == 'A') {
				a = p[i].childNodes[j];
				break;
			}

		var arrow = document.createElement('div');
		arrow.classList.add('emtheme-mob-arrow');

		arrow.addEventListener('click', function() {
			for (var k = 0; k < this.parentNode.childNodes.length; k++) {
				var c = this.parentNode.childNodes[k];
				if (c.className && (c.className.indexOf('sub-menu') != -1 || c.className.indexOf('children') != -1)) 
					c.classList.toggle("nav-show")
			}
		});

		p[i].insertBefore(arrow, a.nextSibling);
	}

})();