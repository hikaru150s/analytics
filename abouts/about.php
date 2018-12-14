<?php
require_once __DIR__ . '/../lib.php';

function about_about($userid) {
	$data = get_about($userid);
	
	return
		'<h1 class="printable">About ' . $data->firstname . ' ' . $data->lastname . '</h1>' .
		'<div class="printable">' .
			($data->about == '' ? 'Seems current user are not yet filling this ... (:/)' : $data->about) .
		'</div>' .
		'<hr class="printable"/>'
	;
}
