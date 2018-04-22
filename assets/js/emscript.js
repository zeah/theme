(function() {
	var c = document.querySelector('.emtheme-search-box');
	var sh = false;
	var value = '';

	var temp = '/wordpress';
	if (!location.href.includes('http://localhost')) temp = '';

	if (!c) return;

	var f = document.createElement('form');
	f.setAttribute('autocomplete', 'on');

	var i = document.createElement('input');
	i.classList.add('emtheme-search-input');
	if (location.search && !location.search.includes('customize_changeset')) i.setAttribute('value', location.search.substring(3));
	i.setAttribute('type', 'search');
	i.setAttribute('autocomplete', 'on');

	if (location.search) i.classList.add('emtheme-search-input-active');

	var b = document.createElement('button');
	b.setAttribute('type', 'button');
	b.classList.add('emtheme-search-button');

	var go = function(val) { location = location.origin+temp+'/?s='+val; }

	b.addEventListener('click', function() {
		if (i.value) go();

		i.classList.add('emtheme-search-input-active');
		i.focus();
	});

	i.addEventListener('keyup', function(e) {
		if (e.keyCode == 13) go(e.target.value);
	});

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
		for (xx of x)
			xx.classList.remove("nav-show");
	});


	var p = document.querySelectorAll('.page_item_has_children, .menu-item-has-children');

	// finding where to put it (complicated because of custom menu adds invisible text child after every child)
	for (var pp of p) {
		var a = null;

		for (var nn of pp.childNodes)
			if (nn.tagName == 'A') {
				a = nn;
				break;
			}

		var o = document.createElement('div');
		o.classList.add('emtheme-mob-arrow');

		o.addEventListener('click', function() {
			for (var c of this.parentNode.childNodes) {
				if (c.className && (c.className.includes('sub-menu') || c.className.includes('children'))) 
					c.classList.toggle("nav-show");
			}
		});

		pp.insertBefore(o, a.nextSibling);
	}

})();