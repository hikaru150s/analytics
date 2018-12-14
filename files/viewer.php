<?php
require_once __DIR__ . '/../lib.php';

function file_viewer($userid, $manager = false, $courseid = 0, $blockid = 0) {
	$data = get_user_files_collection($userid);
	
	if ($manager && !is_null($data)) {
		foreach ($data->files as &$row) {
			$row->Action = '<a href="files/serve.php?act=del&id=' . $row->id . '&courseid=' . $courseid . '&blockid=' . $blockid . '">Delete</a>';
			$row->filename = '<a href="files/serve.php?id=' . $row->id . '">' . basename($row->filename) . '</a>';
		}
	}
	
	return
		'<h1 class="printable">Files</h1>' .
		(is_null($data) ? '' : '<h2>' . $data->firstname . ' ' . $data->lastname . '</h2>') .
		print_table(is_null($data) ? null : $data->files, 'table table-hover table-striped') .
		'<hr class="printable"/>'
	;
}
