<?php
require_once __DIR__ . '/../lib.php';

function view_print() {
	return
		'<button onclick="printMode()">Print</button>' .
		'<script type="text/javascript" src="' . __DIR__ . '/../js/lib.js' . '"></script>' . 
		'<script type="text/javascript">document.onload = function (e) { browseMode() };</script>'
	;
}
