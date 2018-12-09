<?php
require_once __DIR__ . '/../lib.php';

function view_students($courseid) {
	// Show Students (and Everything)
	$dataset = list_students_in_course($courseid);
	// Add action
	$act_url = 'http://test.url/';
	for ($i = 0; $i < count($dataset); $i++) {
		$dataset[$i]->Action = '<a href="' . $act_url . $dataset[$i]->id . '">View</a>';
	}

	return
		'<h1>Students</h1>' .
		print_table($dataset, 'table table-hover table-striped') .
		'<hr/>';
}
