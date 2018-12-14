function printMode() {
	var po = document.getElementsByClassName('print-only');
	for (var iota in po) {
		po[iota].hidden = false;
	}

	var body = document.getElementsByTagName('body').item(0).childNodes;
	for (var i = 0; i < body.length; i++) {
		if (
			body[i].classList != undefined && // Trim non-visible elements
			body[i].localName != 'script' && // Trim scripts
			!body[i].classList.contains('printable') && !body[i].classList.contains('print-only')
		) {
			body[i].hidden = true;
		}
	}

	print();
	browseMode();
}

function browseMode() {
	var po = document.getElementsByClassName('print-only');
	for (var iota in po) {
		po[iota].hidden = true;
	}

	var body = document.getElementsByTagName('body').item(0).childNodes;
	for (var i = 0; i < body.length; i++) {
		if (
			body[i].classList != undefined && // Trim non-visible elements
			body[i].localName != 'script' && // Trim scripts
			!body[i].classList.contains('printable') && !body[i].classList.contains('print-only')
		) {
			body[i].hidden = false;
		}
	}
}
