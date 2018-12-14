<?php
require_once __DIR__ . '/../lib.php';

function view_students($courseid, $blockid) {
	// Show Students (and Everything)
	$dataset = list_students_in_course($courseid);
	// Add action
	$view_url = new moodle_url('/blocks/analytics/view.php', array('type' => 'progress', 'courseid' => $courseid, 'blockid' => $blockid));
	$about_url = new moodle_url('/blocks/analytics/about.php', array('courseid' => $courseid, 'blockid' => $blockid));
	$files_url = new moodle_url('/blocks/analytics/file.php', array('courseid' => $courseid, 'blockid' => $blockid));
	for ($i = 0; $i < count($dataset); $i++) {
		$dataset[$i]->Action = '<a href="' . $view_url . '&studentid=' . $dataset[$i]->id . '">View</a> | <a href="' . $about_url . '&studentid=' . $dataset[$i]->id . '">Summary</a> | <a href="' . $files_url . '&studentid=' . $dataset[$i]->id . '">Files</a>';
	}

	return
		'<h1 class="printable">Students</h1>' .
		print_table($dataset, 'table table-hover table-striped') .
		'<hr class="printable"/>';
}
