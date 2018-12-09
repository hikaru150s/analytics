<?php
require_once __DIR__ . '/../lib.php';

function view_classreport($courseid) {
	// Show Students (and Everything)
	$dataset = list_students_in_course($courseid);
	// Add extra field
	for ($i = 0; $i < count($dataset); $i++) {
		$dataset[$i]->quiz = mean_of_quiz($dataset[$i]->id);
		$dataset[$i]->performance = mean_of_evaluation_performance($dataset[$i]->id);
	}
	// Show Groups
	$groups = get_groups($courseid);

	return
		'<h1>Students</h1>' .
		print_table($dataset, 'table table-hover table-striped') .
		'<hr/>' .
		'<h1>Groups</h1>' .
		print_groups($groups) .
		'<hr/>';
}
