<?php
require_once __DIR__ . '/../lib.php';

function view_correlation($courseid) {
	// Show Correlation
	$dataset = correlate($courseid);

	return
		'<h1>Correlation</h1>' .
		print_matrix($dataset, 'table table-hover table-striped', true) .
		'<hr/>';
}
