<?php
require_once __DIR__ . '/../lib.php';

function view_groups($userid) {
	// List Groups (Table)
	$dataset = list_group_from_user($userid);

	return
		'<h1>Groups</h1>' .
		print_table($dataset, 'table table-hover table-striped') .
		'<hr/>';
}
