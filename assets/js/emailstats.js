console.dir(emaildb);

(() => {

	let newdiv = (o) => {
		let div = document.createElement('div');

		let addClasses = (c) => {
			if (Array.isArray(c)) {
				for (let cl of c)
					if (typeof(cl) == 'string')
						div.classList.add(cl);
			}
			
			else if (typeof(c) == 'string') 
				div.classList.add(c);
		}

		if (typeof(o) == 'string') {
			addClasses(o);
			return div;
		}

		for (let v of Object.keys(o))
			switch (v) {
				case 'text':
					if (typeof(o[v]) == 'string')
						div.appendChild(document.createTextNode(o[v]));
					break;
				case 'class':
					addClasses(o[v]);
					break;
				case 'node':
					if (o[v].nodeName)
						div.appendChild(o[v]);
					break;
			}

		return div;
	}

	let nth = (data) => {
		let th = document.createElement('th');
		th.classList.add('es-th');

		th.appendChild(document.createTextNode(data));

		return th;
	}

	let ntd = (data) => {
		let td = document.createElement('td');
		td.classList.add('es-td');

		td.appendChild(document.createTextNode(data));

		return td;
	}

	let nrow = (...data) => {
		let tr = document.createElement('tr');
		tr.classList.add('es-tr');

		for (let d of data)
			tr.appendChild(d);

		return tr;
	}

	let colgroup = (o) => {
		let colgroup = document.createElement('colgroup');

		let fcol = (cols) => {
			if (Array.isArray(cols))
				for (let c of cols)
					colgroup.appendChild(c);
			else
				colgroup.appendChild(cols);
		}

		for (let d of Object.keys(o))
			switch (d) {
				case 'col':
					fcol(o[d]);
					break;
			}

		return colgroup;
	}

	let ncol = (data) => {
		let col = document.createElement('col');
		col.classList.add('es-col');


		// console.dir(data);
		// for (cl of c)
		// 	col.classList.add(cl);

		return col;
	}

	let counter = (o) => {
		let c = 0;
		let col = o['column'];
		let m = o['match'];

		if (!m) {
			for (a of emaildb)
				if (a[col])
					c++;
		}
		else
			for (a of emaildb)
				if (a[col] == m)
					c++;

		// if (!data) {
		// 	for (a of emaildb)
		// 		if (a[column])
		// 			c++;
		// }
		// else 
		// 	for (a of emaildb)
		// 		if (a[column] == data)
		// 			c++;

		return c;
	}

	let ms = ['jan', 'feb', 'mars', 'apr', 'may', 'june', 'july', 'aug', 'sep', 'okt', 'nov', 'dec'];
	let today = new Date();
	let months = today.getFullYear() * 12 + today.getMonth() + 1;
	// today.setDate(1);


	for (let a of emaildb)
		if (a['email']) {
			let date = new Date(a['hit_date']);
			let month = date.getFullYear() * 12 + date.getMonth() + 1;
			// console.log(months - (new Date(a['hit_date']).getFullYear()*12));
			// console.log(months - month);
			let diff = months - month;
			let tm = today.getMonth();

			let temp = tm - diff;

			if (temp < 0)
				temp += 12;

			// for (let i = diff; i > 0; i--) {
			// 	tm--;

			// 	if (tm < 0)
			// 		tm = 11;
			// }

			console.log(ms[temp]);

		}

	// let date = new Date();
	// date.setDate(1);

	// let j = date.getMonth();
	// for (let i = 0; i < 12; i++) {
	// 	// console.log(ms[j]+' '+j);
	// 	date.setMonth(j);




	// 	j--;

	// 	if (j < 0)
	// 		j = 11;
	// }

	// console.log(ms[today.getMonth()]);

	// console.log(ms[new Date().setMonth(-1).getMonth()]);

	// let months = new Map();
	// let today = new Date();

	// for (let a of emaildb)
	// 	if (a['hit_date'] && a['email']) {
	// 		let month = new Date(a['hit_date']).getMonth();
	// 		console.log(today.getMonth() - new Date(a['hit_date']).getMonth());
	// 		if (! months.get(month))
	// 			months.set(month, 1);
	// 		else
	// 			months.set(month, months.get(month) + 1);
	// 	}

	// console.dir(months);

	// element created by php
	let container = document.querySelector('.es-container'); 

	container.appendChild(newdiv('test'));

	/* table for counting stats */
	let tableC = document.createElement('table');
	tableC.classList.add('es-table');
	// tableC.appendChild(colgroup(ncol(), ncol()));
	tableC.appendChild(colgroup({
		col: [ncol(), ncol()]
	}))

	tableC.appendChild(nrow(nth('Kilde'), nth('Antall')));

	tableC.appendChild(
		nrow(
			ntd('Dekstop'),
			ntd(counter({
				column: 'emailsrc',
				match: 'leave_popup'
			}))
			)
		);
	tableC.appendChild(
		nrow(
			ntd('Mobil'),
			ntd(counter({
				column: 'emailsrc',
				match: 'mobile_bottom'
			}))
			)
		);
	tableC.appendChild(
		nrow(
			ntd('Alle'),
			ntd(counter({
				column: 'email',
			}))
			)
		);

	// tableC.appendChild(nrow(ntd('Desktop'), ntd(counter('emailsrc', 'leave_popup'))));
	// tableC.appendChild(nrow(ntd('Mobile'), ntd(counter('emailsrc', 'mobile_bottom'))));
	// tableC.appendChild(nrow(ntd('Alle'), ntd(counter('email'))));

	container.appendChild(tableC);

	/* name - email table */
	let tableNE = document.createElement('table');
	tableNE.classList.add('es-table');

	tableNE.appendChild(colgroup({
		col: [ncol(), ncol()]
	}))

	// table headers
	tableNE.appendChild(nrow(nth('Name'), nth('Email')));

	for (let a of emaildb) 
		if (a['email']) 
			tableNE.appendChild(nrow(ntd(a['name']), ntd(a['email'])));

	// adding to page
	container.appendChild(tableNE);

})();