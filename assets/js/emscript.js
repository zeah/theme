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
	i.setAttribute('value', location.search.substring(3));
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
	var n = document.querySelector(".nav");
	o.addEventListener("click", function() {
		n.classList.toggle("nav-show");
		this.classList.toggle("nav-active");

		var x = document.querySelectorAll(".em-nav-parent-container > .nav-show");
		for (xx of x)
			xx.classList.remove("nav-show");
	});

	var m = document.querySelectorAll("span.em-nav-item");
	for (var mm of m) 
		mm.addEventListener("click", function() {
			this.nextSibling.classList.toggle("nav-show");
		});
		
})();