(function() {
	var c = document.querySelector('.emtheme-search-box');
	var sh = false;

	if (!c)
		return;

	var f = document.createElement('form');
	f.setAttribute('autocomplete', 'on');

	var i = document.createElement('input');
	i.classList.add('emtheme-search-input');
	i.setAttribute('value', location.search.substring(3));
	i.setAttribute('type', 'search');
	i.setAttribute('autocomplete', 'on');

	if (location.search)
		i.classList.add('emtheme-search-input-active');

	var b = document.createElement('button');
	b.setAttribute('type', 'button');
	b.classList.add('emtheme-search-button');

	var go = function() {
		window.location.assign('http://'+location.hostname+'/wordpress/?s='+i.value);
	}

	b.addEventListener('click', function() {
		console.log('h'+i.value+'i');
		if (i.value) {
			console.log('hi');
			go()
			return;
		}

		i.classList.add('emtheme-search-input-active');
		i.focus();
	});

	i.addEventListener('keyup', function(e) {
		if (e.keyCode == 13)
			go();
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