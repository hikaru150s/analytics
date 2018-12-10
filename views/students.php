<?php
require_once __DIR__ . '/../lib.php';

function view_students($courseid, $blockid) {
	// Show Students (and Everything)
	$dataset = list_students_in_course($courseid);
	// Add action
	$act_url = new moodle_url('/blocks/analytics/view.php', array('type' => 'progress', 'courseid' => $courseid, 'blockid' => $blockid));
	for ($i = 0; $i < count($dataset); $i++) {
		$dataset[$i]->Action = '<a href="' . $act_url . '&studentid=' . $dataset[$i]->id . '">View</a>';
	}

	return
		'<h1>Students</h1>' .
		print_table($dataset, 'table table-hover table-striped') .
		'<hr/>';
}
