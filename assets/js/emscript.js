(function() {
	var c = document.querySelector('.emtheme-search-box');
	var sh = false;
	var value = '';


	var temp = 'http://localhost:8010/wordpress';

	if (!c) return;

	var f = document.createElement('form');
	f.setAttribute('autocomplete', 'on');

	var i = document.createElement('input');
	i.classList.add('emtheme-search-input');
	if (location.search && !location.search.includes('=')) i.setAttribute('value', location.search.substring(3));
	i.setAttribute('type', 'search');
	i.setAttribute('autocomplete', 'on');

	if (location.search) i.classList.add('emtheme-search-input-active');

	var b = document.createElement('button');
	b.setAttribute('type', 'button');
	b.classList.add('emtheme-search-button');

	var go = function() { window.location.assign(temp+'?s='+i.value); }

	b.addEventListener('click', function() {
		if (i.value) go();

		i.classList.add('emtheme-search-input-active');
		i.focus();
	});

	i.addEventListener('keyup', function(e) {
		i.value = e.target.value;
		if (e.keyCode == 13) go();
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


(function() {

	var o = document.querySelector(".emtheme-mobile-icon");
	var n = document.querySelector(".menu");
	o.addEventListener("click", function() {
		n.classList.toggle("nav-show");
		// this.classList.toggle("nav-active");

		var x = document.querySelectorAll(".menu .nav-show");
		for (xx of x)
			xx.classList.remove("nav-show");
	});

	var m = document.querySelectorAll('.page_item_has_children > a, .menu-item-has-children > a');

	for (var mm of m)
		mm.addEventListener("click", function(e) {
			e.preventDefault();

			// need to iterate through childnodes as number of nodes in "page menu" differs from custom menu
			for (var c of this.parentNode.childNodes) {
				if (c.className.includes('sub-menu') || c.className.includes('children')) 
					c.classList.toggle("nav-show");
			}
		});

	if (window.innerWidth < 1024) {
		var v = document.querySelectorAll('.page_item_has_children, .menu-item-has-children');
		for (var vv of v) {
			var a = null;
			var ul = null;

			for (var cv of vv.childNodes) {
				if (cv.tagName == 'A') a = cv.cloneNode(true);
				else if (cv.tagName == 'UL') ul = cv
			}

			var li = document.createElement('li');
			li.classList.add('menu-item');
			li.classList.add('page_item');
			li.appendChild(a);

			ul.insertBefore(li, ul.firstChild);
		}
	}

})();