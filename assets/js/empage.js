(() => {

	// console.log(em_meta);

	let metadescription = document.querySelector('.em-metadescription');
	let metatitle = document.querySelector('.em-metatitle');
	let seocontainer = document.querySelector('.em-seo-container');

	let editor = document.querySelector('#content');
	let content = editor.value;

	let newdiv = (...c) => {
		let div = document.createElement('div');

		for (cl of c) div.classList.add(cl);

		return div;
	}

	let newinput = (cl, name, type = 'input', counter = true) => {
		let div = newdiv();
		let t = document.createElement(type);
		t.classList.add(cl);
		t.setAttribute('name', name);

		if (Array.isArray(em_meta[name]) && em_meta[name][0]) t.value = em_meta[name][0];

		if (counter) {
			let n = document.createElement('span');
			n.innerHTML = 'Char Count: '+t.value.length;
			n.classList.add('em-counter');

			t.addEventListener('input', () => n.innerHTML = 'Char Count: '+t.value.length );
		
			div.appendChild(n);
		}

		// inserts as first child
		div.insertBefore(t, div.childNodes[0]);
	
		return div;
	}

	let newtab = (text, node = null, ...c) => {
		let tab = document.createElement('div');
		tab.classList.add('em-seo-tab');

		for (cl of c) tab.classList.add(cl);

		if (node) tab.addEventListener('click', () => { 
		
			document.querySelector('.em-seo-tab-active').classList.toggle('em-seo-tab-active');

			tab.classList.toggle('em-seo-tab-active');

			seom.replaceChild(node, seom.lastChild);
		});

		tab.appendChild(document.createTextNode(text))

		return tab;
	}

	let newrow = (...cell) => {
		let row = document.createElement('tr');
		row.classList.add('em-tr');

		for (let c of cell) {
			let td = document.createElement('td');
			td.classList.add('em-td');
			if (c instanceof Element) td.appendChild(c);
			else td.appendChild(document.createTextNode(c));
			row.appendChild(td);
		}

		return row;
	}

	let newbutton = (text) => {
		let button = document.createElement('button');
		button.setAttribute('type', 'button');
		button.classList.add('button');
		button.classList.add('button-secondary');

		button.appendChild(document.createTextNode(text));

		return button;
	}

	let findbutton = (link) => {
		let start = content.indexOf(link);
		let length = link.length;

		let select = () => {
			document.querySelector('#content-html').click();
			
			editor.focus();
			editor.selectionStart = start;
			editor.selectionEnd = start + length;
		}

		let container = document.createElement('div');

		let wcme_button = newbutton('Find');

		wcme_button.addEventListener('click', () => {
			select();
			document.querySelector('#content-tmce').click();
		});


		container.appendChild(wcme_button);

		// let html_button = newbutton('HTML');
		// html_button.addEventListener('click', () => select());
		// container.appendChild(html_button);

		return container;
	}

	let getTarget = (link) => {
		let lo = link.match(/(?:target=")(.+?)(?:")/i);
		
		if (lo && lo.length >= 1) 
			switch(lo[1]) {
				case '_blank': return 'new tab';
			}

		if (lo) return lo[1];
		return '';
	}

	let linktable = () => {

		let headrow = () => {

			let head = (text, ...c) => {
				let head = document.createElement('th');
				head.classList.add('em-th');
				// head.classList.add('em-td-info');

				for (let cl of c) head.classList.add(c);

				head.appendChild(document.createTextNode(text));
				return head;
			}

			let tablehead = document.createElement('tr');
			tablehead.appendChild(head('Go To', 'em-th-100'));
			tablehead.appendChild(head('URL'));
			tablehead.appendChild(head('Opens', 'em-th-100'));
			tablehead.appendChild(head('Anchor Text'));

			return tablehead;
		}

		let links = content.match(/<a.*>/g);

		let addrow = (row, input_table) => {
			let link = row.match(/(?:<a.*?)(?:href=")(.+?)(?:".*?>)(.*?)(?:<\/a>)/);
			input_table.appendChild(newrow(findbutton(link[0]), link[1], getTarget(link[0]), link[2]));
		}

		let infoRow = (text) => {
			let tr = document.createElement('tr');
			tr.classList.add('em-tr-info');

			let td = document.createElement('td');
			td.setAttribute('colspan', '4');
			td.classList.add('em-td-info');

			td.appendChild(document.createTextNode(text));

			tr.appendChild(td);

			return tr;
		}


		console.log(links);

		let container = newdiv();

		let internal_table = document.createElement('table');
		internal_table.classList.add('em-table');

		let external_table = document.createElement('table');
		external_table.classList.add('em-table');


		let loc = 'www.kredittkort.rocks';
		// let loc = location.hostname;

		// table.appendChild(infoRow('Internal Links'));
		internal_table.appendChild(headrow());
		for (let row of links) if (row.includes(loc)) addrow(row, internal_table);

		// table.appendChild(infoRow('External Links'));
		external_table.appendChild(headrow());
		for (let row of links) if (!row.includes(loc)) addrow(row, external_table);


		let external_info = newdiv('em-table-info');
		external_info.appendChild(document.createTextNode('External Links'));
		container.appendChild(external_info);
		container.appendChild(external_table);

		let internal_info = newdiv('em-table-info');
		internal_info.appendChild(document.createTextNode('Internal Links'));
		container.appendChild(internal_info);
		container.appendChild(internal_table);
		return container;
	}

	// <META DESCRIPTION>
	metadescription.appendChild(newinput('em-metadesc', 'emtext', 'textarea'));


	// META TITLE
	metatitle.appendChild(newinput('em-metatitle-input', 'emtitle'));


	// SEO 
	// seocontainer.appendChild(newinput('em-seo-keyword', 'emkeyword'));
	let top = newdiv('em-seo-top');

	// text tab
	let text = newdiv();
	text.appendChild(document.createTextNode('text info'));

	// link tab
	let links = linktable();

	// images tab
	let images = newdiv();
	images.appendChild(document.createTextNode('hi'));


	// adding tabs
	top.appendChild(newtab('Text', text));
	top.appendChild(newtab('Links', links, 'em-seo-tab-active'));
	top.appendChild(newtab('Images', images));

	// adding to web
	seocontainer.appendChild(top);


	// seo main element
	let seom = newdiv('em-seo-main');

	// inital state
	seom.appendChild(links);

	// adding to web
	seocontainer.appendChild(seom);
})();